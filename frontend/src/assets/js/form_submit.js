(function() {
	let originalContent;
	let modalContent;
	let inputValues = {};
	let debug = true;

	// Define a function to handle form submissions
	function handleFormSubmit(event) {
		event.preventDefault();

		var f = event.target, formData = new FormData(f);
		console.log("Form data for debugging:", Object.fromEntries(formData.entries()));

		var formAction = f.getAttribute("action");
		
		var formMethod = f.getAttribute("method");
		if (formMethod) {
			formMethod = formMethod.toUpperCase();
		} else {
			console.warn("Method attribute is missing from the form. Defaulting to 'GET'.");
			formMethod = "POST";
		}

		if (f.dataset.ignore === "true") {
			return;
		}

		// Store the input values before submitting the form
		var inputs = f.querySelectorAll("input");
		for (var i = 0; i < inputs.length; i++) {
			var input = inputs[i];
			if (input.type === "checkbox") {
				if (input.checked) {
					formData.set(input.name, input.value); // Add the checkbox's actual value if checked
				} else {
					formData.set(input.name, 0); // Add the checkbox's actual value if checked
				}
			} else {
				inputValues[input.name] = input.value;
			}
		}

		// Here, you might add logic for selects if Select2 doesn't update them in a way FormData expects.
		var selects = f.querySelectorAll("select");
		selects.forEach(function(select) {
			// If it's a single select and not multiple, you might not need to delete existing entries.
			// Just ensure the value is correctly set in FormData, which it should be if the select element is properly updated by Select2.
		});

		// Show the loader animation
		modalContent = f.closest(".modal").querySelector(".modal-content");
		if (modalContent === null) {
			return;
		}

		// Show loader
		originalContent = modalContent.innerHTML; // Store original content before replacing it with a loader
		var loaderImg = document.createElement("img");
		loaderImg.src = "/assets/images/animated/paper_plane.gif";
		modalContent.innerHTML = "";
		modalContent.appendChild(loaderImg);

		if (formMethod === "GET") {
			var id = formData.get('id');  // Assuming 'id' is the input name for the ID field

			remote.rpc(config.api_url + formAction + '/' + id).then(response => {
				handleFormResponse(response, f);
			}).catch(err => {
				handleFormError(err)
			});
		}
		
		else if (formMethod === "DELETE") {
			// Do this later
		}
		
		else if (formMethod === "POST") {

			// Legg til stÃ¸tte for select-elementer med multiple valg
			var selects = f.querySelectorAll("select[multiple]");
			selects.forEach(function(select) {
				// Fjern tidligere oppfÃ¸ringer for dette navnet for Ã¥ unngÃ¥ duplikater
				formData.delete(select.name); 
				// Legg til hver valgt verdi
				Array.from(select.selectedOptions).forEach(function(option) {
					formData.append(select.name, option.value);
				});
			});

			remote.rpc_post(config.api_url + formAction, formData).then(response => {
				handleFormResponse(response, f);
			}).catch(err => {
				handleFormError(err)
			});
			
		}

		return false;
	}


	/**
	 * Handle the response from the form (POST, GET, DELETE...)
	 * 
	 * @param {json} response 
	 */
	function handleFormResponse(response, f) {
		if (debug) {
			console.log('Modal Form Handler: handleFormResponse()', response);
			console.log('Modal Form Handler: form elm', f);
		}

		if (response === null) {
			// Stop the loader and show an error message
			handleError(modalContent, `<b>En feil oppstod!</b><br />Ingen response fra backend (null)`);
		}
		
		else if (response.status == "success") {
			if (debug) console.log('Modal Form Handler: success');

			// Show the success animation
			var successImg = document.createElement("img");
			successImg.src = "/assets/images/animated/animat-checkmark-color.gif";
			successImg.style.maxWidth = "400px";  // This will ensure the image takes up to 100% of the container's width but not more.
			successImg.style.display = "block"; // This ensures the image is displayed as a block element (centered by default).
			successImg.style.margin = "0 auto"; // Centers the image horizontally if it's smaller than the container width.
			modalContent.innerHTML = "";
			modalContent.appendChild(successImg);

			notification.success('Fullført!');

			//let modalElement = f.closest(".modal");
			let modalElement = document.querySelector('.modal.show');
			if (debug) console.log('Modal Form Handler: modalElement', modalElement);
			
			// Check if the modal element exists
			if (modalElement) {
				let modalInstance = bootstrap.Modal.getInstance(modalElement);
				if (debug) console.log('Modal Form Handler: modalInstance', modalInstance);

				// If the modal instance is not created yet, create it
				if (!modalInstance) {
					console.warning('Cant find modal instance, so we create a new one for the element', modalElement);
					modalInstance = new bootstrap.Modal(modalElement);
				}

				// Hide the modal
				setTimeout(function() {
					// Call the callback function on success
					var callback = f.dataset.callback;
					if (callback && typeof window[callback] === "function") {
						window[callback](response);
					}

					if (debug) console.log('Modal Form Handler: Hide modalInstance');
					modalInstance.hide();
					setTimeout(function() {
						revertForm();

						// Remove error alerts from modal after successful submission
           				removeErrorAlertsFromElement(modalElement);

						f.reset();
					}, 500);
				}, 800);
			}

			else {
				if (debug) console.log('Modal Form Handler: Could not find modalElement');
			}
		}
		
		else {
			handleError(modalContent, `<b>En feil oppstod!</b><br />${response.message}`);
		}
	}


	/**
	 * Handle the error from the form (POST, GET, DELETE...)
	 * 
	 * @param {json} response 
	 */
	function handleFormError(err) {
		console.error(err);
		if (err.response && err.response.message != '') {
			handleError(modalContent, `<b>En feil oppstod!</b><br /> ${err.response.message}`);
		} else {
			handleError(modalContent, `<b>En feil oppstod!</b><br /> Kan væree uforståelig data fra backend eller javascript feil.`);
		}
	}




	// Define a function to remove .alert-danger from a modal or any element
	function removeErrorAlertsFromElement(element) {
		var errorMsgs = element.querySelectorAll(".alert-danger");
		for (var i = 0; i < errorMsgs.length; i++) {
			errorMsgs[i].remove();
		}
	}





	/**
	 * Process the error
	 * 
	 * @param {*} modalContent 
	 * @param {*} message 
	 */
	function handleError(modalContent, message) {
		revertForm();
		
		// Remove any existing error messages
		var errorMsgs = modalContent.querySelectorAll(".alert-danger");
		for (var i = 0; i < errorMsgs.length; i++) {
			errorMsgs[i].remove();
		}

		// Create error message
		var errorMsg = document.createElement("div");
		errorMsg.innerHTML = message;
		errorMsg.classList.add("alert", "alert-danger");


		var modalBody = modalContent.querySelector(".modal-body");
		if (modalBody === null) {
			console.warning('Modal body is null');
			modalContent.appendChild(errorMsg);
		} else {
			console.info('Revert to original content');
			//modalContent.innerHTML = originalContent;
			modalBody.insertBefore(errorMsg, modalBody.firstChild);
		}
	}



	/**
	 * Reset the form
	 */
	function revertForm() {
		modalContent.innerHTML = originalContent;

		// Put back stored input values
		for (var key in inputValues) {
			if (inputValues.hasOwnProperty(key)) {
				var input = modalContent.querySelector(`[name='${key}']`);
				if (input !== null) {
					if (input.type === "file") {
						// Do nothing for file inputs due to security constraints
					} else if (input.type === "checkbox") {
						input.checked = inputValues[key];
					} else {
						input.value = inputValues[key];
					}
				}
			}
		}

		// Re-initialize all Select2 within the modal content
   		reinitializeSelect2Within(modalContent);
	}


	function reinitializeSelect2Within(element) {
		// Find all select elements within the given element
		let selects = element.querySelectorAll('select');
		selects.forEach(select => {
			if ($(select).data('select2')) { // Check if it was a select2
				$(select).select2(); // Reinitialize
			}
		});
	}





	// Define a function to add the event listener to a modal
	function addModalFormListener(modal) {
		var forms = modal.querySelectorAll("form");

		for (var i = 0; i < forms.length; i++) {
			forms[i].addEventListener("submit", handleFormSubmit);
		}
	}

	// Define a function to add the event listener to all modals
	function addModalFormListeners() {
		var modals = document.querySelectorAll(".modal");

		for (var i = 0; i < modals.length; i++) {
			addModalFormListener(modals[i]);
		}

		// Listen for modals that are appended via AJAX
		document.addEventListener("shown.bs.modal", function(event) {
			var modal = event.target;
			addModalFormListener(modal);
		});
	}

	// Add the event listener to all existing modals
	addModalFormListeners();
})();