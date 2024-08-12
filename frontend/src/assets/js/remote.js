let Remote = function() {

	let plugin = this;

	plugin.rpc = function(url, callback) {
		return new Promise(function (resolve, reject) {
			var xhr = new XMLHttpRequest();
			xhr.open('GET', url, true);
			xhr.responseType = 'json';

			xhr.setRequestHeader('Authorization', 'Bearer ' + localStorage.getItem('user_token'));
			
			xhr.onload = function() {
				var status = xhr.status;

				if (status >= 200 && status < 300) {
					if (callback && typeof(callback) == "function") {
						callback(200, xhr.response);
					}

					resolve(xhr.response);
				} else {
					if (callback && typeof(callback) == "function") {
						callback(xhr.status, xhr.response);
					}

					reject({
						status: xhr.status,
						statusText: xhr.statusText
					});
				}
			};

			xhr.onerror = function () {
				reject({
					status: xhr.status,
					statusText: xhr.statusText
				});
			};

			xhr.send();
		});
	};



	plugin.rpc_post = function(url, params, callback) {
		return new Promise(function (resolve, reject) {
			var xhr = new XMLHttpRequest();
			xhr.open("POST", url, true);
			xhr.timeout = 10000; // time in milliseconds
			xhr.responseType = 'json';

			xhr.setRequestHeader('Authorization', 'Bearer ' + localStorage.getItem('user_token'));

			// Send the proper header information along with the request
			xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
			// xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded"); // Removed to get POST correct on serverside on page save

			xhr.onload = function() {
				var status = xhr.status;
				if (status >= 200 && status < 300) {
					if (callback && typeof(callback) == "function") {
						callback(200, xhr.response);
					}

					resolve(xhr.response);
				} else {
					if (callback && typeof(callback) == "function") {
						callback(xhr.status, xhr.response);
					}

					// Include xhr.response in the reject object for detailed error handling
					reject({
						status: xhr.status,
						statusText: xhr.statusText,
						response: xhr.response
					});
				}
			};

			xhr.onerror = function () {
				reject({
					status: xhr.status,
					statusText: xhr.statusText,
					response: xhr.response
				});
			};

			xhr.send(params);
		});
	};
}

const remote = new Remote();