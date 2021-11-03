function show_loader() {
	document.getElementById('login-section-mail').style.display = 'none';
	document.getElementById('login-section-code').style.display = 'none';
	document.getElementById('login-section-loader').style.display = 'block';
}

function show_enter_mail() {
	document.getElementById('login-section-mail').style.display = 'block';
	document.getElementById('login-section-code').style.display = 'none';
	document.getElementById('login-section-loader').style.display = 'none';
}

function show_enter_code() {
	document.getElementById('login-section-mail').style.display = 'none';
	document.getElementById('login-section-code').style.display = 'block';
	document.getElementById('login-section-loader').style.display = 'none';

	document.getElementById('input-code').focus();
}


function msg_status(type, msg)
{
	const feedback = document.querySelectorAll(".feedback");
	for (var i = 0; i < feedback.length; i++) {
		if (type == 'error') {
			feedback[i].innerHTML = '<div class="alert error"><i class="fal fa-exclamation-triangle"></i> '+msg+'</div>';
			//const element = document.querySelector('.my-element');
			feedback[i].classList.add('animate__animated', 'animate__shakeX');
		}
		else if (type == 'warning') {
			feedback[i].innerHTML = '<div class="alert warning"><i class="fas fa-check"></i> '+msg+'</div>';
			feedback[i].classList.add('animate__animated', 'animate__shakeY');
		}
		else if (type == 'success') {
			feedback[i].innerHTML = '<div class="alert success"><i class="fas fa-check"></i> '+msg+'</div>';
			feedback[i].classList.add('animate__animated', 'animate__shakeY');
		}

		else {
			feedback[i].innerHTML = '';
		}
	}
}




// Get URL parameters
function getParameterByName(name, url) {
	if (!url) url = window.location.href;
	name = name.replace(/[\[\]]/g, '\\$&');
	var regex = new RegExp('[?&]' + name + '(=([^&#]*)|&|#|$)'),
	results = regex.exec(url);
	if (!results) return null;
	if (!results[2]) return '';
	return decodeURIComponent(results[2].replace(/\+/g, ' '));
}



function fadeOut(el){
	el.style.opacity = 1;

	(function fade() {
		if ((el.style.opacity -= .1) < 0) {
			el.style.display = "none";
		} else {
			requestAnimationFrame(fade);
		}
	})();
};

function fadeIn(el, display){
	el.style.opacity = 0;
	el.style.display = display || "block";

	(function fade() {
		var val = parseFloat(el.style.opacity);
		if (!((val += .1) > 1)) {
			el.style.opacity = val;
			requestAnimationFrame(fade);
		}
	})();
};