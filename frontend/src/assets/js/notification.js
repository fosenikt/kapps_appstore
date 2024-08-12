let Notification = function () {
	let plugin = this;


	plugin.success = function (inputText) {
		new Noty({
			theme: 'nest',
			type: 'success',
			layout: 'bottomRight',
			text: '<i class="fas fa-check"></i> ' + inputText,
			timeout: 2500
		}).show();


		//var mySound = new plugin.sound("/assets/sounds/light.mp3");
		//mySound.play();
	}



	plugin.error = function (inputText) {
		new Noty({
			theme: 'nest',
			type: 'error',
			layout: 'topRight',
			text: '<i class="fas fa-exclamation-triangle"></i> ' + inputText,
			timeout: 6000
		}).show();


		//var mySound = new plugin.sound("/assets/sounds/out-of-space-dog.mp3");
		//mySound.play();
	}


	plugin.warning = function (inputText) {
		new Noty({
			theme: 'nest',
			type: 'warning',
			layout: 'topRight',
			text: '<i class="far fa-engine-warning"></i> ' + inputText,
			timeout: 5000
		}).show();


		//var mySound = new plugin.sound("/assets/sounds/out-of-space-dog.mp3");
		//mySound.play();
	}


	plugin.sound = function (src) {
		this.sound = document.createElement("audio");
		this.sound.src = src;
		this.sound.setAttribute("preload", "auto");
		this.sound.setAttribute("controls", "none");
		this.sound.style.display = "none";
		document.body.appendChild(this.sound);

		this.play = function () {
			this.sound.play();
		}

		this.stop = function () {
			this.sound.pause();
		}
	}
}

const notification = new Notification();