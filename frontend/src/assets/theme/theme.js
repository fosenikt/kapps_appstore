let Template = function() {

	let plugin = this;


	plugin.load_webpart = async function(template, target, json)
	{
		return new Promise(function (resolve, reject) {

			// Fetch template
			var xhr = new XMLHttpRequest();
			xhr.open("GET", template, true);
			xhr.responseType = 'text';

			xhr.onload = function() {
				var status = xhr.status;
				if (status >= 200 && status < 300) {

					

					$.templates({my_template: xhr.response});
					$(target).html(
						$.render.my_template(json)
					);

					resolve(xhr.response);
				} else {

					reject({
						status: xhr.status,
						statusText: xhr.statusText
					});
				}
			}

			xhr.onerror = function () {
				reject({
					status: xhr.status,
					statusText: xhr.statusText
				});
			};

			xhr.send();

		});
	}




	plugin.load_page = async function(template, target, json, force_reload)
	{
		return new Promise(function (resolve, reject) {

			// Check if target has an hash to indicate ID
			// Jquery support
			var without_hash = target.substring(1);

			// If force reload, delete target and recreate
			if (force_reload == true) {
				if (document.getElementById(without_hash)) {
					document.getElementById(without_hash).remove();
				}
				var element = null;
			}

			else {
				var element =  document.getElementById(without_hash);
			}
			

			// If target container does not exist
			if (typeof(element) == 'undefined' || element == null)
			{

				// Make a page ID
				var page_gen_id = plugin.makeid(32);

				// Create target container
				var node = document.createElement("div");
				node.setAttribute("class", 'page-item');
				node.setAttribute("id", without_hash);
				node.setAttribute("data-id", page_gen_id);
				document.getElementById("main").prepend(node);
				node.innerHTML = '<div class="page-content-loader"><i class="fas fa-circle-notch fa-spin"></i><br />Laster...</div>';


				// Fetch template
				var xhr = new XMLHttpRequest();
				xhr.open("GET", template, true);
				xhr.responseType = 'text';

				xhr.onload = function() {
					var status = xhr.status;
					if (status >= 200 && status < 300) {
						$.templates({my_template: xhr.response});
						$(target).html(
							$.render.my_template(json)
						);

						resolve(xhr.response);
					} else {
						reject({
							status: xhr.status,
							statusText: xhr.statusText
						});
					}
				}

				xhr.onerror = function () {
					reject({
						status: xhr.status,
						statusText: xhr.statusText
					});
				};

				xhr.send();
				
			
			} else {
				// Nothing to do. Template already loaded to DOM.
				resolve(true);
			}

			// Set page to visible
			document.getElementById(without_hash).style.display = "block";
			document.getElementById(without_hash).style.visibility = "visible";
		});
	}






	plugin.refresh_page = async function(template, target, json, force_reload)
	{
		return new Promise(function (resolve, reject) {

			// Check if target has an hash to indicate ID
			// Jquery support
			//var without_hash = target.substring(1);
			//var element =  document.getElementById(without_hash);


			//console.log(element);


			// Fetch template
			var xhr = new XMLHttpRequest();
			xhr.open("GET", template, true);
			xhr.responseType = 'text';

			xhr.onload = function() {
				var status = xhr.status;
				if (status >= 200 && status < 300) {
					$.templates({my_template: xhr.response});
					$(target).html(
						$.render.my_template(json)
					);

					resolve(xhr.response);
				} else {

					reject({
						status: xhr.status,
						statusText: xhr.statusText
					});
				}
			}

			xhr.onerror = function () {
				reject({
					status: xhr.status,
					statusText: xhr.statusText
				});
			};

			xhr.send();
				

			// Set page to visible
			//document.getElementById(without_hash).style.display = "block";
		});
	}



	plugin.makeid = function(length) {
		var result           = '';
		var characters       = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
		var charactersLength = characters.length;
		for ( var i = 0; i < length; i++ ) {
			result += characters.charAt(Math.floor(Math.random() * charactersLength));
		}
		return result;
	}

}









let ThemeToggler = function() {
	let plugin = this;

	let theme_path = '/assets/css/';
	let default_theme = 'light';
	let current_theme;

	let icon_on = '<i class="far fa-fw fa-lightbulb-on"></i>';
	let icon_off = '<i class="far fa-fw fa-lightbulb"></i>';
	let icon = document.getElementById('theme-icon');


	// Set theme on load
	plugin.init = function() {
		if (localStorage.getItem("theme") === null) {
			current_theme = default_theme;
		}

		else {
			var current_theme = localStorage.getItem('theme');
		}

		plugin.set_theme(current_theme);
	}

	// Toggle between themes
	plugin.toggle = function() {
		var current_theme = localStorage.getItem('theme');

		if (current_theme == 'dark') {
			plugin.set_theme('light');
		}

		else {
			plugin.set_theme('dark');
		}
	}

	// Change theme
	plugin.set_theme = function(theme) {
		localStorage.setItem('theme', theme);

		if (document.getElementById("css-theme")) {
			document.getElementById("css-theme").setAttribute("href", theme_path + theme + '.css');

			if (theme == 'dark') {
				icon.innerHTML = icon_off;
			}

			else {
				icon.innerHTML = icon_on;
			}
		}
	}

	// Run init on load
	plugin.init();
}















let Notification = function() {
	let plugin = this;

	plugin.success = function(inputText, timeout, link)
	{
		if (timeout == undefined) {
			timeout = 2500;
		}

		new Noty({
			theme: 'metroui',
			type: 'success',
			layout: 'topRight',
			text: '<i class="fas fa-check"></i> ' + inputText,
			timeout: timeout
		}).on('onClick', function() {
			if (link != '' && link != undefined) {
				//window.location.href = link;
				page(link);
			}
		}).show();
	}

	plugin.error = function(inputText, timeout, link)
	{
		if (timeout == undefined) {
			timeout = 5000;
		}

		new Noty({
			theme: 'metroui',
			type: 'error',
			layout: 'topRight',
			text: '<i class="fas fa-exclamation-triangle"></i> ' + inputText,
			timeout: timeout
		}).on('onClick', function() {
			if (link != '' && link != undefined) {
				page(link);
			}
		}).show();
	}

	plugin.warning = function(inputText, timeout, link)
	{
		if (timeout == undefined) {
			timeout = 3000;
		}

		new Noty({
			theme: 'metroui',
			type: 'warning',
			layout: 'topRight',
			text: '<i class="far fa-engine-warning"></i> ' + inputText,
			timeout: timeout
		}).on('onClick', function() {
			if (link != '' && link != undefined) {
				page(link);
			}
		}).show();
	}

	plugin.sound = function(src) {
		this.sound = document.createElement("audio");
		this.sound.src = src;
		this.sound.setAttribute("preload", "auto");
		this.sound.setAttribute("controls", "none");
		this.sound.style.display = "none";
		document.body.appendChild(this.sound);
		
		this.play = function(){
			this.sound.play();
		}
		
		this.stop = function(){
			this.sound.pause();
		}
	}
}









const template = new Template();
const theme_toggler = new ThemeToggler();
//const remote = new Remote();