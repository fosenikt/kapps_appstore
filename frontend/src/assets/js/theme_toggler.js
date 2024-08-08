let ThemeToggler = function() {

    let plugin = this;

    let theme_path = '/assets/css/';
    let default_theme = 'light';
    let current_theme;

    let icon_on = '<i class="fa-light fa-brightness"></i>';
    let icon_off = '<i class="fa-light fa-moon"></i>';
    let icon;
    let button = document.querySelector('.page__theme');

    // Set theme on load
    plugin.init = function() {
        if (!button) {
            console.error("Button with class 'page__theme' not found");
            return;
        }

        current_theme = localStorage.getItem('theme') || default_theme;
		console.log('current_theme', current_theme);
        
        // Initialize the icon in the button
        icon = document.createElement('span');
        button.appendChild(icon);
        
        setTimeout(() => {
            plugin.set_theme(current_theme);
        }, 200);

        // Add event listener to the button
        button.addEventListener('click', plugin.toggle);
    }

    // Toggle between themes
    plugin.toggle = function() {
        let current_theme = localStorage.getItem('theme');

        if (current_theme == 'dark') {
            plugin.set_theme('light');
        } else {
            plugin.set_theme('dark');
        }
    }

    // Change theme
    plugin.set_theme = function(theme) {
		console.log('set_theme', theme);
        localStorage.setItem('theme', theme);
        document.getElementById("css-theme").setAttribute("href", theme_path + theme + '.css');

        let elementsToToggle = document.querySelectorAll('[data-bs-color-scheme]');
        elementsToToggle.forEach(el => {
            el.setAttribute('data-bs-color-scheme', theme);
        });

        let tables = document.querySelectorAll('.table');
        tables.forEach(table => {
            if (theme == 'dark') {
                if (table.classList.contains('table-light')) {
                    table.classList.remove('table-light');
                }
                table.classList.add('table-dark');
            } else {
                if (table.classList.contains('table-dark')) {
                    table.classList.remove('table-dark');
                }
                table.classList.add('table-light');
            }
        });

        // Update the icon every time the theme is changed
        plugin.update_icon(theme);

        let buttons = document.querySelectorAll('.btn');

        buttons.forEach(button => {
            if (theme == 'dark' && button.classList.contains('btn-light')) {
                button.classList.remove('btn-light');
                button.classList.add('btn-dark');
            } else if (theme == 'light' && button.classList.contains('btn-dark')) {
                button.classList.remove('btn-dark');
                button.classList.add('btn-light');
            }
        });
    }

    // Update the icon in the button
    plugin.update_icon = function(theme) {
		console.log('update_icon', theme);

        if (!icon) {
            console.error("Icon element not found");
            return;
        }

        if (theme == 'dark') {
			console.log('Set icon off', icon_off);
            icon.innerHTML = icon_off;
        } else {
			console.log('Set icon on', icon_on);
            icon.innerHTML = icon_on;
        }
    }

    // Run init on load
    plugin.init();

}

const theme_toggler = new ThemeToggler();
