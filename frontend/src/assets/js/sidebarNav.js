/**
 * Sidebar for pages (e.g. menu)
 * 
 * Note: This is bad code that is very poorly thought out.
 * 		 The close sidebar is appended to each page, and sidebar
 * 		 lives outside the page itself. Meaning we need to do a lot
 * 		 of checks and loops.
 */

let SidebarNav = function() {

	let plugin = this;
	


	plugin.is_open = function(sidebar_data_id) 
	{
		// Check for sidebar with this id, if it is set
		if (sidebar_data_id != undefined) {
			var sidebarElm = document.querySelector('[data-sidebar-id="'+sidebar_data_id+'"]');
			if (sidebarElm == null) {
				return false;
			}
		}
		
		var sidebarElm = document.getElementById("nav-sidebar");
		if (window.getComputedStyle(sidebarElm).display === "none") {
			return false;
		}

		if (document.getElementById("nav-sidebar").style.width > "0") {
			return true;
		}

		return false;
	}




	plugin.open_sidebar = function(sidebar_data_id) 
	{
		console.log('Open sidebar');

		var sidebarElm = document.getElementById("nav-sidebar");

		if (sidebar_data_id != undefined) {
			sidebarElm.setAttribute('data-sidebar-id', sidebar_data_id);
		}

		if (document.getElementById("nav-sidebar").style.width > "0") {
			//console.log('Sidebar nav is already open');
		}

		sidebarElm.style.width = "270px";
		document.getElementById("main").style.marginLeft = "270px";

		if (window.innerWidth <= 768) {
			document.getElementById("main").style.marginRight = "-270px";
		}

		document.getElementById("nav-sidebar").style.display = "block";



		// Loop pages
		if (window.innerWidth <= 768) {
			setTimeout(function(){
				document.querySelectorAll('.page-item').forEach(function(page) {
					
					// Check for visible page
					if (page.style.display === 'block') {
						
						// Check if page has close-sidebar
						// Append if not
						if(!page.getElementsByClassName('close-sidebar')[0]) {
							var a = document.createElement('a');
							a.className = 'close-sidebar';
							a.innerHTML = '<i class="fas fa-chevron-left"></i>';
							a.href = "javascript:nav_sidebar.toggle()";
							page.appendChild(a)
						}
					}
				});

				document.querySelectorAll('.close-sidebar').forEach(function(togglesidebar) {
					togglesidebar.innerHTML = `<i class="fas fa-chevron-left"></i>`;
				});
			}, 200);
		}

		

		return true;
	}




	plugin.close_sidebar = function() 
	{
		document.getElementById("nav-sidebar").style.width = "0";
		document.getElementById("main").style.marginLeft= "0px";
		document.getElementById("main").style.marginRight= "0px";

		document.getElementById("nav-sidebar").style.display = "none";

		document.querySelectorAll('.close-sidebar').forEach(function(togglesidebar) {
			togglesidebar.innerHTML = `<i class="fas fa-chevron-right"></i>`;
		});
	}


	plugin.toggle = function() 
	{
		if (plugin.is_open() == true) {
			plugin.close_sidebar();
		} else {
			plugin.open_sidebar();
		}
	}




	plugin.load_template = function(template_file)
	{
		var template = new Template();
		template.load_webpart(template_file, '#nav-sidebar', '');

		setTimeout(function(){
			if (window.innerWidth <= 768) {
				plugin.close_sidebar();
			}
		}, 300);
	}

}

//const sidebarNav = new SidebarNav();