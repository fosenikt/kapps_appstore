let notification = new Notification();
let nav_sidebar = new SidebarNav();

//page.base('/app')

page('/', function() {
	page_switch();
	template.load_page('/components/Dashboard/dashboard.jsr', '#page-dashboard', '').then(response => {
	})
});





page('/apps*', function(ctx, next){
	next()
	if (nav_sidebar.is_open('apps') == false) {
		nav_sidebar.open_sidebar('apps');
		nav_sidebar.load_template('/components/Apps/_sidebar/sidebar.jsr');
	}
});

page.exit('/apps*', function(ctx, next) {
	nav_sidebar.close_sidebar();

	next()
})


page('/apps', function() {
	page_switch();
	template.load_page('/components/Apps/apps.jsr', '#page-app-types', '').then(response => {
	})
});

page('/app/new', function() {
	page_switch();
	template.load_page('/components/Apps/new.jsr', '#page-app-new', '');
});


page('/app/:id', function(ctx) {
	page_switch();

	remote.rpc(config.api_url+'/app/get/'+ctx.params.id).then(response => {
		template.load_page('/components/Apps/app.jsr', '#page-app-'+ctx.params.id, response);
	})

	.catch((err) => {
		notification.error('En feil oppstod under henting av template');
		console.log(err);
	});
});

page('/app/edit/:id', function(ctx) {
	page_switch();

	remote.rpc(config.api_url+'/app/get/'+ctx.params.id).then(response => {
		template.load_page('/components/Apps/edit.jsr', '#page-app-edit-'+ctx.params.id, response);
	})

	.catch((err) => {
		notification.error('En feil oppstod under henting av template');
		console.log(err);
	});
});

page('/apps/get', function(ctx) {
	page_switch();
	template.load_page('/components/Apps/apps.jsr', '#page-apps', '', true);
});


page('/company/myapps', function() {
	page_switch();
	template.load_page('/components/Apps/myapps.jsr', '#page-company-myapp', '').then(response => {
	})
});

page('/company/apps/:id', function(ctx) {
	page_switch();

	remote.rpc(config.api_url+'/company/get/'+ctx.params.id).then(response => {
		console.log(response);
		template.load_page('/components/Apps/company.jsr', '#page-company-apps-'+ctx.params.id, response);
	})

	.catch((err) => {
		notification.error('En feil oppstod under henting av template');
		console.log(err);
	});
});



page('/myprofile/edit', function(ctx) {
	page_switch();
	
	remote.rpc(config.api_url+'/user/me').then(response => {
		template.load_page('/components/MyProfile/edit.jsr', '#page-myprofile-edit', response).then(response => {
			M.updateTextFields();
		})

	})

	.catch((err) => {
		notification.error('En feil oppstod under henting av template');
		console.log(err);
	});
});

page('/myprofile', function(ctx) {
	page_switch();
	
	remote.rpc(config.api_url+'/user/me').then(response => {
		console.log(response);
		template.load_page('/components/MyProfile/myprofile.jsr', '#page-myprofile', response);
	})

	.catch((err) => {
		notification.error('En feil oppstod under henting av template');
		console.log(err);
	});
});






page('/admin*', function(ctx, next){
	next()
	if (nav_sidebar.is_open('admin') == false) {
		nav_sidebar.open_sidebar('admin');
		nav_sidebar.load_template('/components/Admin/_sidebar/sidebar.jsr');
	}
});

page.exit('/admin*', function(ctx, next) {
	nav_sidebar.close_sidebar();
	next()
})


page('/admin', function(ctx) {
	page_switch();
	template.load_page('/components/Admin/dashboard.jsr', '#page-admin', '');
});


page('/admin/companies', function(ctx) {
	page_switch();
	template.load_page('/components/Admin/Companies/companies.jsr', '#page-admin-companies', '');
});


page('/admin/company/new', function(ctx) {
	page_switch();
	template.load_page('/components/Admin/Companies/new.jsr', '#page-admin-companies-new', '');
});


page('/admin/company/:id', function(ctx) {
	page_switch();

	remote.rpc(config.api_url+'/company/get/'+ctx.params.id).then(response => {
		template.load_page('/components/Admin/Companies/profile.jsr', '#page-admin-companies-'+ctx.params.id, response);
	})

	.catch((err) => {
		notification.error('En feil oppstod under henting av template');
		console.log(err);
	});
});


page('/admin/users', function(ctx) {
	page_switch();
	template.load_page('/components/Admin/Users/users.jsr', '#page-admin-users', '');
});

page('/admin/user/new', function(ctx) {
	page_switch();
	template.load_page('/components/Admin/Users/new.jsr', '#page-admin-user-new', '');
});

page('/admin/user/edit/:id', function(ctx) {
	page_switch();

	remote.rpc(config.api_url+'/user/get/'+ctx.params.id).then(userdata => {
		console.log(userdata);
		template.load_page('/components/Admin/Users/new.jsr', '#page-admin-user-edit-'+ctx.params.id, userdata).then(response => {
			M.updateTextFields();

			document.getElementById('sel-newuser-status').value = userdata.status;
			if (userdata.admin == 1) {
				console.log('Admin: ' + userdata.admin);
				document.getElementById('sel-newuser-status').value = userdata.status;
				document.getElementById('chk-newuser-systemuser').checked = true;
			} else {
				console.log('Not admin');
			}
		})
	})

	.catch((err) => {
		notification.error('En feil oppstod under henting av template');
		console.log(err);
	});
});

page('/admin/user/tokens/:id', function(ctx) {
	page_switch();

	remote.rpc(config.api_url+'/user/get/'+ctx.params.id).then(response => {
		console.log(response);
		template.load_page('/components/Admin/Users/tokens.jsr', '#page-admin-user-tokens-'+ctx.params.id, response);
	})

	.catch((err) => {
		notification.error('En feil oppstod under henting av template');
		console.log(err);
	});
});

page('/admin/user/:id', function(ctx) {
	page_switch();

	remote.rpc(config.api_url+'/user/get/'+ctx.params.id).then(response => {
		console.log(response);
		template.load_page('/components/Admin/Users/user.jsr', '#page-admin-user-'+ctx.params.id, response);
	})

	.catch((err) => {
		notification.error('En feil oppstod under henting av template');
		console.log(err);
	});
});








page('/company/profile/:id', function(ctx) {
	page_switch();

	remote.rpc(config.api_url+'/company/get/'+ctx.params.id).then(response => {
		template.load_page('/components/Companies/profile.jsr', '#page-company-'+ctx.params.id, response);
	})

	.catch((err) => {
		notification.error('En feil oppstod under henting av template');
		console.log(err);
	});
});






page('/users/profile/:id', function(ctx) {
	page_switch();

	remote.rpc(config.api_url+'/user/get/'+ctx.params.id).then(response => {
		console.log(response);
		template.load_page('/components/Users/profile.jsr', '#page-user-'+ctx.params.id, response);
	})

	.catch((err) => {
		notification.error('En feil oppstod under henting av template');
		console.log(err);
	});
});







page('/search/all', function(ctx) {
	page_switch();
	template.load_page('/components/Dashboard/search.jsr', '#page-dashboard-search', '', true);
});



page('*', notfound);
page();

function notfound() {
	console.log('Not found');
}


function page_switch()
{
	var divsToHide = document.getElementsByClassName("page-item"); //divsToHide is an array
    for(var i = 0; i < divsToHide.length; i++){
        divsToHide[i].style.visibility = "hidden"; // or
        divsToHide[i].style.display = "none"; // depending on what you're doing
    }
}