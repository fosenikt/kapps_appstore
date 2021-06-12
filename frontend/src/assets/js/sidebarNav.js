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

		var sidebarElm = document.getElementById("nav-sidebar");

		if (sidebar_data_id != undefined) {
			sidebarElm.setAttribute('data-sidebar-id', sidebar_data_id);
		}

		if (document.getElementById("nav-sidebar").style.width > "0") {
			//console.log('Sidebar nav is already open');
		}

		sidebarElm.style.width = "270px";
		document.getElementById("main").style.marginLeft = "270px";

		document.getElementById("nav-sidebar").style.display = "block";
		return true;
	}




	plugin.close_sidebar = function() 
	{
		document.getElementById("nav-sidebar").style.width = "0";
		document.getElementById("main").style.marginLeft= "0px";

		document.getElementById("nav-sidebar").style.display = "none";
	}




	plugin.load_template = function(template_file)
	{
		var template = new Template();
		template.load_webpart(template_file, '#nav-sidebar', '');
	}

}

//const sidebarNav = new SidebarNav();