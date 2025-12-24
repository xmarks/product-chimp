/**
 * File navigation.js.
 *
 * Handles toggling the navigation menu for small screens and enables TAB key
 * navigation support for dropdown menus.
 *
 * @package Product_Chimp
 */

(function () {
    const siteNavigation = document.getElementById('site-navigation');
    let ignoreScrollEvent = false; // Add a flag to ignore scroll events

    // Return early if the navigation doesn't exist.
    if (!siteNavigation) {
        return;
    }

    const body = document.body;
    const header = document.getElementById("masthead");
    const burger = siteNavigation.getElementsByClassName('navigation__burger-controller')[0];

    // Return early if the button doesn't exist.
    if ('undefined' === typeof burger) {
        return;
    }

    const menu = siteNavigation.getElementsByTagName('ul')[0];

    // Hide menu toggle button if menu is empty and return early.
    if ('undefined' === typeof menu) {
        burger.style.display = 'none';
        return;
    }

    if (!menu.classList.contains('nav-menu')) {
        menu.classList.add('nav-menu');
    }

    function getHeaderHeight() {
        header.style.height = ''; // reset height so it can be re-calculated
        return header.offsetHeight;

    }

    function getScreenHeight() {
        const innerHeight = window.innerHeight; // Viewport height
        const outerHeight = window.outerHeight; // Total height including browser chrome
        return outerHeight - (outerHeight - innerHeight);
    }

    let headerHeight = getHeaderHeight();
    let screenHeight = getScreenHeight();
    header.style.height = `${headerHeight}px`;

    // Function to update the variables
    function updateDimensions() {
        headerHeight = getHeaderHeight();
        screenHeight = getScreenHeight();
        header.style.height = `${headerHeight}px`;
    }

    // Debounce function to limit how often updateDimensions is called
    function debounce(func, wait) {
        let timeout;
        return function () {
            clearTimeout(timeout);
            timeout = setTimeout(func, wait);
        };
    }

    // Listen to resize event
    window.addEventListener('resize', debounce(updateDimensions, 200));

    burger.addEventListener('change', function () {

        // Set the flag to true to ignore scroll events temporarily
        ignoreScrollEvent = true;

        if (this.checked) {
            // Input is checked
            body.classList.add('nav-open');
            header.style.height = `${screenHeight}px`;
        } else {
            // Input is not checked
            body.classList.remove('nav-open');
            header.style.height = `${headerHeight}px`;
        }

        // Set a timeout to reset the flag after a short delay
        setTimeout(() => {
            ignoreScrollEvent = false;
        }, 100);
    });


    // Get all the link elements within the menu.
    const links = menu.getElementsByTagName('a');

    // Get all the link elements with children within the menu.
    const linksWithChildren = menu.querySelectorAll('.menu-item-has-children > a, .page_item_has_children > a');
    const submenus = menu.querySelectorAll('ul.sub-menu');

    // Toggle focus each time a menu link is focused or blurred.
    for (const link of links) {
        link.addEventListener('focus', toggleFocus, true);
        link.addEventListener('blur', toggleFocus, true);

        link.addEventListener('mouseenter', toggleFocus, true);
        link.addEventListener('mouseleave', toggleFocus, true);

        link.addEventListener('click', toggleFocus, true);
    }

    // Toggle focus each time a menu link with children receive a touch event.
    for (const link of linksWithChildren) {
        link.addEventListener('click', toggleClick, false);
    }

    for (const submenu of submenus) {
        submenu.addEventListener('mouseenter', toggleSubmenu, true);
        submenu.addEventListener('mouseleave', toggleSubmenu, true);
    }

    function toggleSubmenu(event) {
        // keep header class while mouse is inside submenu
        if (event.type === 'mouseenter') {
            header.classList.add('submenu-open');
        }
    }

    /**
     * Sets or removes .opened class on an element.
     */
    function toggleClick(event) {

        // Prevent default only for 'click' event
        if (event.type === 'click') {
            // Prevents the default action (navigation)
            event.preventDefault();
        }

        // Get the direct parent <li> element
        const parentLi = this.parentElement;

        // Toggle the 'open' class on the parent <li>
        if (parentLi.classList.contains('open')) {
            parentLi.classList.remove('open');
            header.classList.remove('submenu-open');
        } else {
            parentLi.classList.add('open');
            header.classList.add('submenu-open');
        }
    }


    /**
     * Sets or removes .focus class on an element.
     */
    let focusedParent = null; // Variable to store the currently focused/clicked parent

    function toggleFocus(event) {
        let self = this;

        // Handle focus and click events
        if (event.type === 'focus' || event.type === 'click') {
            // Move up through the ancestors of the current link until we hit .nav-menu.
            while (!self.classList.contains('nav-menu')) {
                if ('li' === self.tagName.toLowerCase()) {
                    // Remove focus from the previously focused parent if it exists and is different from the current one
                    if (focusedParent && focusedParent !== self) {
                        focusedParent.classList.remove('focus');
                    }

                    // Add focus to the current parent
                    self.classList.add('focus');

                    // Update the focusedParent variable to the current parent
                    focusedParent = self;
                }
                self = self.parentNode;
            }
        }

        // Handle hover behavior
        if (event.type === 'mouseenter') {
            if (focusedParent && focusedParent !== this.closest('li')) {
                // Remove the focus class from the previously focused parent when hovering over a new parent
                focusedParent.classList.remove('focus');

                if (window.innerWidth >= 769) {
                    focusedParent.classList.remove('open'); // removes class from toggleClick
                }

                focusedParent = null; // Reset focusedParent
            }
            header.classList.add('submenu-open');
        }

        if (event.type === 'mouseleave') {
            header.classList.remove('submenu-open');
        }
    }

    // Detect clicks outside the menu to remove focus
    document.addEventListener('click', function (event) {
        if (focusedParent && !menu.contains(event.target)) {
            // Remove focus class if the click is outside the menu
            focusedParent.classList.remove('focus');

            if (window.innerWidth >= 769) {
                focusedParent.classList.remove('open'); // removes class from toggleClick
            }

            focusedParent = null; // Reset focusedParent
        }
    });
}());


document.addEventListener("DOMContentLoaded", function () {
    document.addEventListener("scroll", function () {
        const header = document.querySelector(".site-header");
        if (window.scrollY > 0) {
            header.classList.add("is-static");
        } else {
            header.classList.remove("is-static");
        }
    });
});
