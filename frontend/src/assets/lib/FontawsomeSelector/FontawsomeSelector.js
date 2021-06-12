class FontawsomeSelector {

	constructor(config) {
		//this.element = element;		// The input-element (search-element)
		console.log(config);

		this.element = config.element;
		this.input = config.input_name;
		this.randid = this.randomId();


		this.init();
	}


	/*
		
	*/


	init()
	{
		var plugin = this;
		console.log(plugin.element);

		//console.log(document.querySelector(plugin.element));

		document.querySelector(plugin.element).innerHTML = `
			<div class="dropdown fa-icon-selector" id="dd-${this.randid}">
				<a class="btn btn-primary dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-bs-toggle="dropdown" aria-expanded="false">
					<span class="fa-selector-icon-preview" id="dd-preview-${this.randid}">
						<i class="fal fa-info-circle"></i>
					</span>
				</a>

				<ul class="dropdown-menu" id="nw-icons" aria-labelledby="dropdownMenuLink"></ul>
			</div>
			<input type="hidden" id="input-fontawsome-icon-${plugin.randid}" name="${plugin.input}">
		`;

		//console.log();

		//let preview_container = document.querySelector(plugin.element).getElementsByClassName("fa-selector-icon-preview")[0];
		let ddm = document.querySelector(plugin.element).getElementsByClassName("dropdown-menu")[0];

		let fa_icons = plugin.default_icons();
		var icons_html = '';
		for (let i = 0; i < fa_icons.length; i++) {
			//const element = array[i];
			icons_html += `<a class="fa-selector-icon fa-selector-icon-${plugin.randid}" href="" data-icon="${fa_icons[i]}"><i class="${fa_icons[i]} fa-fw"></i></a>`;
		}

		document.querySelector('#input-fontawsome-icon-'+plugin.randid).value = 'fal info-circle';

		ddm.innerHTML = icons_html;


		addEvent(document, 'click', '.fa-selector-icon-'+plugin.randid, function(e) {
			console.log(this);

			let dd = this.closest(".fa-icon-selector");
			let preview_container = dd.getElementsByClassName("fa-selector-icon-preview")[0];

			let icon = this.dataset.icon;
			let icon_svg = this.innerHTML;
			preview_container.innerHTML = icon_svg;
			document.querySelector('#input-fontawsome-icon-'+plugin.randid).value = icon;
		})
	}




	select(icon)
	{
		var plugin = this;

		console.log('selecting icon');
		console.log(icon);
		console.log('ID: ' + plugin.randid);
		console.log('element: ' + this.element);

		document.getElementById('dd-preview-'+this.randid).innerHTML = `<i class="${icon} fa-fw">`;
		document.querySelector('#input-fontawsome-icon-'+plugin.randid).value = icon;
	}


	default_icons()
	{
		let fa_icons = [
			'fal fa-user',
			'fal fa-arrow-up',
			'fal fa-exclamation-triangle',
			'fal fa-address-book',
			'fal fa-baby',
			'fal fa-bed',
			'fal fa-bell',
			'fal fa-birthday-cake',
			'fal fa-building',
			'fal fa-bus',
			'fal fa-calendar-alt',
			'fal fa-car',
			'fal fa-chalkboard-teacher',
			'fal fa-clinic-medical',
			'fal fa-coffee',
			'fal fa-conveyor-belt',
			'fal fa-cubes',
			'fal fa-info-circle',
			'fal fa-question-circle',
			'fal fa-deer',
			'fal fa-digging',
			'fal fa-fighter-jet',
			'fal fa-fire-extinguisher',
			'fal fa-forklift',
			'fal fa-futbol',
			'fal fa-gamepad-alt',
			'fal fa-glass-cheers',
			'fal fa-globe-europe',
			'fal fa-hamburger',
			'fal fa-heart-rate',
			'fal fa-heartbeat',
			'fal fa-home',
			'fal fa-hospital',
			'fal fa-house-signal',
			'fal fa-envelope',
			'fal fa-lock-alt',
			'fal fa-mailbox',
			'fal fa-map-marker-alt',
			'fal fa-mobile-alt',
			'fal fa-newspaper',
			'fal fa-phone-alt',
			'fal fa-radio-alt',
			'fal fa-rss',
			'fal fa-running',
			'fal fa-shield-check',
			'fal fa-signal',
			'fal fa-snowboarding',
			'fal fa-snowflake',
			'fal fa-snowmobile',
			'fal fa-street-view',
			'fal fa-swimmer',
			'fal fa-thumbs-up',
			'fal fa-trash-alt',
			'fal fa-tree-christmas',
			'fal fa-video',
			'fal fa-virus',
			'fal fa-wind-turbine',
			'fal fa-syringe',
		]

		return fa_icons;
	}




	addEvent(parent, evt, selector, handler) {
		parent.addEventListener(evt, function(event) {
			if (event.target.matches(selector + ', ' + selector + ' *')) {
				handler.apply(event.target.closest(selector), arguments);
			}
		}, false);
	}


	randomId()
	{
		return '_' + Math.random().toString(36).substr(2, 9);
	}

}