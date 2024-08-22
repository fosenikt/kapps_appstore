page('/', function () {
	page_switch('main');
});


/* Apps
----------------------------------------- */

page('/apps/all', (context, next) => {
	page_switch(context.canonicalPath);
});

page('/apps/app/:id', function(ctx) {
	//page_switch();

	remote.rpc(`${config.api_url}/app/get/${ctx.params.id}`).then(response => {
		console.log('app', response);
		page_switch('/apps/app', `page-apps-app-${ctx.params.id}`, response);
	})

	.catch((err) => {
		notification.error('En feil oppstod under henting av applikasjon');
		console.error(err);

		// If token is expired
		if (err.status == 401) {
			localStorage.removeItem("user_token");
			location.reload();
		}
	});
});

page('/apps/edit/:id', function(ctx) {
	//page_switch();

	remote.rpc(`${config.api_url}/app/get/${ctx.params.id}`).then(response => {
		console.log('app', response);
		page_switch('/apps/edit', `page-apps-edit-${ctx.params.id}`, response);
	})

	.catch((err) => {
		notification.error('En feil oppstod under henting av applikasjon');
		console.error(err);

		// If token is expired
		if (err.status == 401) {
			localStorage.removeItem("user_token");
			location.reload();
		}
	});
});

page('/apps/share', (context, next) => {
	page_switch(context.canonicalPath);
});



/* Organization
----------------------------------------- */

page('/organization/profile/:id', (ctx) => {
	remote.rpc(config.api_url+'/company/get/'+ctx.params.id).then(response => {
		console.log('app', response);
		page_switch('/organization/profile', `organization-profile-${ctx.params.id}`, response);
	})

	.catch((err) => {
		notification.error('En feil oppstod under henting av applikasjon');
		console.error(err);

		// If token is expired
		if (err.status == 401) {
			localStorage.removeItem("user_token");
			page('/user/login');
		}
	});
});




/* Info
----------------------------------------- */

page('/info/about', (context, next) => {
	page_switch(context.canonicalPath);
});

page('/info/resources', (context, next) => {
	page_switch(context.canonicalPath);
});

page('/info/privacy', (context, next) => {
	page_switch(context.canonicalPath);
});

page('/info/contact', (context, next) => {
	page_switch(context.canonicalPath);
});



/* User
----------------------------------------- */

page('/user/login', (context, next) => {
	page_switch(context.canonicalPath);
});

page('/user/me', (context, next) => {
	page_switch(context.canonicalPath);
});

page('/user/me/edit', (context, next) => {
	page_switch(context.canonicalPath);
});






page('*', notfound);
page();

function notfound() {
	page_switch('_error_pages/404');
}

function page_switch(page, page_id, data) {
	console.log('Switch page');
	console.log('page', page);
	console.log('page_id', page_id);
	console.log('data', data);

    // Hide all elements with class 'page-item'
    document.querySelectorAll('.page-item').forEach(item => {
        item.style.display = 'none';
    });

    // Show the element with id 'main'
    const mainElement = document.getElementById('main');
    if (mainElement) {
        mainElement.style.display = 'block';
    }

    if (page_id === undefined) {
        page_id = page.replace(/\//g, '-');
    }

    // Check if it starts with a /
    if (page.charAt(0) !== "/") {
        page = "/" + page;
    }

    // Remove @ and . from page_id
    page_id = page_id.replace(/[@.]/g, "-");

    page = page.toLowerCase();

    template.load_page('/components' + page + '.jsr', '#page-' + page_id, data)
        .then(response => {
			console.log('Page loaded');
            updateActiveLink(page);
        })
        .catch(err => {
            console.error(err);
        });
}
