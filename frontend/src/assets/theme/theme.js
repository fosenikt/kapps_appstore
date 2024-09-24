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



const template = new Template();