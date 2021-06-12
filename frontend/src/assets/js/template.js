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
			setTimeout(function(){


			// Check if target has an hash to indicate ID
			// Jquery support
			var without_hash = target.substring(1);


			// If force reload, delete target and recreate
			if (force_reload) {
				$(target).remove;
				var element = null;
			}

			else {
				var element =  document.getElementById(without_hash);
			}
			

			//console.log(element);

			// If target container does not exist
			if (typeof(element) == 'undefined' || element == null)
			{
				// Create target container
				var node = document.createElement("div");
				node.setAttribute("class", 'page-item');
				node.setAttribute("id", without_hash);
				document.getElementById("main").prepend(node);
				$(node).html('<div class="page-content-loader"><i class="fas fa-circle-notch fa-spin"></i><br />Laster...</div>');


				// Fetch template
				var xhr = new XMLHttpRequest();
				xhr.open("GET", template, true);
				xhr.responseType = 'text';

				//Send the proper header information along with the request
				//xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
				//xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

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
		}, 0);

		});
	}

}


const template = new Template();