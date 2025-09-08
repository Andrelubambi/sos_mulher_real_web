// Sidebar functionality
document.addEventListener('DOMContentLoaded', function() {
    // Toggle sidebar on mobile
    const menuIcon = document.querySelector('.menu-icon');
    const sidebarClose = document.querySelector('.sidebar-close');
    const mobileOverlay = document.querySelector('.mobile-menu-overlay');
    const leftSidebar = document.querySelector('.left-side-bar');
    
    if (menuIcon) {
        menuIcon.addEventListener('click', function() {
            leftSidebar.classList.add('open');
            mobileOverlay.classList.add('show');
        });
    }
    
    if (sidebarClose) {
        sidebarClose.addEventListener('click', function() {
            leftSidebar.classList.remove('open');
            mobileOverlay.classList.remove('show');
        });
    }
    
    if (mobileOverlay) {
        mobileOverlay.addEventListener('click', function() {
            leftSidebar.classList.remove('open');
            mobileOverlay.classList.remove('show');
        });
    }
    
    // Toggle submenus
    const dropdownToggles = document.querySelectorAll('#accordion-menu .dropdown-toggle');
    
    dropdownToggles.forEach(toggle => {
        toggle.addEventListener('click', function(e) {
            if (this.getAttribute('href') === 'javascript:;') {
                e.preventDefault();
                const submenu = this.nextElementSibling;
                
                if (submenu && submenu.classList.contains('submenu')) {
                    // Close other submenus
                    document.querySelectorAll('.submenu.show').forEach(menu => {
                        if (menu !== submenu) {
                            menu.classList.remove('show');
                        }
                    });
                    
                    // Toggle current submenu
                    submenu.classList.toggle('show');
                }
            }
        });
    });
    
    // Active menu item highlighting
    const currentPath = window.location.pathname;
    const menuLinks = document.querySelectorAll('#accordion-menu a');
    
    menuLinks.forEach(link => {
        if (link.getAttribute('href') === currentPath) {
            link.classList.add('active');
            
            // Expand parent menu if exists
            const parentMenu = link.closest('.submenu');
            if (parentMenu) {
                parentMenu.classList.add('show');
                const parentToggle = parentMenu.previousElementSibling;
                if (parentToggle) {
                    parentToggle.classList.add('active');
                }
            }
        }
    });
    
    // Search functionality
    const searchInput = document.querySelector('.search-input');
    const searchToggle = document.querySelector('.search-toggle-icon');
    const headerSearch = document.querySelector('.header-search');
    
    if (searchToggle && headerSearch) {
        searchToggle.addEventListener('click', function() {
            headerSearch.classList.toggle('show');
            if (headerSearch.classList.contains('show')) {
                searchInput.focus();
            }
        });
    }
});