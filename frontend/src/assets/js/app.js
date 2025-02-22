let App = function() {
	let plugin = this;
	let remote = new Remote();
	


	addEvent(document, 'click', 'a.btn-signout', function(e) {
		localStorage.removeItem("user_token");
		window.location.replace('/user/login');
	});




	addEvent(document, 'click', 'a.authRedir', function(e) {
		var href = $(this).attr('href');

		var url = href + '&token=' + localStorage.getItem('user_token');

		var win = window.open(url, '_blank');
		win.focus();

		return false;
	});


	if (localStorage.getItem('user_token') == null) {
		localStorage.removeItem("user_token");
	}






	plugin.load_main = function()
	{
		template.load_page('/templates/dashboard.jsr', '#main', '', true).then(response => {

			remote.rpc(config.api_url + '/user/me').then(response => {				
				document.getElementById('nav-loggin-btn').style.display = 'none';
				document.getElementById('nav-user-circle').style.display = 'inline-block';
				document.getElementById('nav-user-circle').innerHTML = response.initials;
			}).catch((err) => {
				console.error('Request error for current logged in user');
			});

		})

		.catch((err) => {
			notification.error('En feil oppstod under lasting av siden. Vennligst prøv oppfrisk siden å prøv på nytt.');
			console.error(err);
		});
	}





	plugin.validate_login = function()
	{
		remote.rpc(config.api_url + '/user/me').then(response => {
			// No response
			if (response == null || response == undefined || response == 0 || response == false) {
				document.getElementById('nav-login-button').innerHTML = '<i class="fal fa-right-to-bracket"></i>';
			}

			// Valid response
			else {
				localStorage.setItem("user_data", JSON.stringify(response));

				document.getElementById('nav-login-button').innerHTML = '<i class="fal fa-user"></i>';

				if (response.admin == 1) {
					document.querySelectorAll('.display-admin').forEach(function(item) {
						item.style.display = "block";
					});
				}

			}
		}).catch((err) => {
			console.error(err);
			console.error(err.status);
			if (err.status == 401) {
				localStorage.removeItem("user_token");
				window.location.replace('/user/login');
			}
		});
	}


	plugin.sign_out = function()
	{	

		// Remove session from server
		remote.rpc(config.api_url + '/auth/login/signout').then(response => {
		}).catch((err) => {
			console.error('Request error for current logged in user', err);
		});

		// Remove JWT token from client
		localStorage.removeItem("user_token");
		window.location.replace('/user/login');
	}







	plugin.getAllUrlParams = function(url) {

		// get query string from url (optional) or window
		var queryString = url ? url.split('?')[1] : window.location.search.slice(1);

		// we'll store the parameters here
		var obj = {};

		// if query string exists
		if (queryString) {

			// stuff after # is not part of query string, so get rid of it
			queryString = queryString.split('#')[0];

			// split our query string into its component parts
			var arr = queryString.split('&');

			for (var i = 0; i < arr.length; i++) {
				// separate the keys and the values
				var a = arr[i].split('=');

				// set parameter name and value (use 'true' if empty)
				var paramName = a[0];
				var paramValue = typeof (a[1]) === 'undefined' ? true : a[1];

				// (optional) keep case consistent
				paramName = paramName.toLowerCase();
				if (typeof paramValue === 'string') paramValue = paramValue.toLowerCase();

				// if the paramName ends with square brackets, e.g. colors[] or colors[2]
				if (paramName.match(/\[(\d+)?\]$/)) {

					// create key if it doesn't exist
					var key = paramName.replace(/\[(\d+)?\]/, '');
					if (!obj[key]) obj[key] = [];

					// if it's an indexed array e.g. colors[2]
					if (paramName.match(/\[\d+\]$/)) {
						// get the index value and add the entry at the appropriate position
						var index = /\[(\d+)\]/.exec(paramName)[1];
						obj[key][index] = paramValue;
					} else {
						// otherwise add the value to the end of the array
						obj[key].push(paramValue);
					}
				} else {
					// we're dealing with a string
					if (!obj[paramName]) {
						// if it doesn't exist, create property
						obj[paramName] = paramValue;
					} else if (obj[paramName] && typeof obj[paramName] === 'string'){
						// if property does exist and it's a string, convert it to an array
						obj[paramName] = [obj[paramName]];
						obj[paramName].push(paramValue);
					} else {
						// otherwise add the property
						obj[paramName].push(paramValue);
					}
				}
			}
		}

		return obj;
	}





	plugin.log = function(type, entity_id, entity_id2)
	{

		var formData = new FormData();
		formData.append('type', type); // app, file, company_profile, user_profile
		formData.append('entity_id', entity_id);
		formData.append('entity_id2', entity_id2);

		remote.rpc_post(config.api_url + '/stats/log', formData).then(response => {
		}).catch((err) => {
			console.error(err);
		});
	}

}


const app = new App();

/* app.validate_login();
setInterval(function(){
	app.validate_login();
}, 60000); */