/**
 * Enhanced Menu Highlight Script - FIXED VERSION with localStorage support
 * Clean, simplified code for sidebar navigation with working expand functionality
 * and persistent state management using localStorage
 * 
 * NEW FEATURES:
 * - Enhanced submenu item highlighting with "clicked" state
 * - Better visual distinction between active parent and submenu items
 * - Improved click feedback and animations
 * - Persistent clicked state tracking
 * - FIXED: Submenu highlighting no longer automatically disappears
 */
(function($) {
    'use strict';
    
    // LocalStorage keys
    const STORAGE_KEY = 'hms_navigation_state';
    const EXPANDED_ITEMS_KEY = 'expanded_menu_items';
    const CLICKED_ITEM_KEY = 'hms_clicked_menu_item';
    
    // State tracking variables
    let isInitialized = false;
    let highlightLock = false;
    
    $(document).ready(function() {
        console.log('=== ENHANCED MENU HIGHLIGHT SCRIPT LOADED (FIXED VERSION) ===');
        
        // Get current URL path
        var currentPath = window.location.pathname;
        var $mainMenu = $('#main-menu');
        
        // Check if menu exists
        if ($mainMenu.length === 0) {
            console.log('Main menu not found, exiting');
            return;
        }
        
        console.log('Main menu found with', $mainMenu.find('li').length, 'items');
        console.log('Submenu items:', $mainMenu.find('li:has(ul)').length);
        
        // Clean up the path
        currentPath = currentPath.replace(/\/$/, '').replace(/^\//, '');
        
        // LocalStorage functions
        function saveMenuState() {
            try {
                var expandedItems = [];
                var clickedItem = null;
                
                // Save expanded submenus
                $mainMenu.find('li:has(ul)').each(function() {
                    var $li = $(this);
                    var menuText = $li.find('> a').text().trim();
                    if ($li.hasClass('active')) {
                        expandedItems.push(menuText);
                    }
                });
                
                // Save clicked item
                var $clickedItem = $mainMenu.find('a.clicked-menu');
                if ($clickedItem.length > 0) {
                    clickedItem = {
                        href: $clickedItem.attr('href'),
                        text: $clickedItem.text().trim(),
                        timestamp: Date.now()
                    };
                }
                
                var state = {
                    expandedItems: expandedItems,
                    clickedItem: clickedItem,
                    timestamp: Date.now()
                };
                
                localStorage.setItem(STORAGE_KEY, JSON.stringify(state));
                console.log('✅ Menu state saved to localStorage:', {
                    expanded: expandedItems,
                    clicked: clickedItem ? clickedItem.text : 'none'
                });
            } catch (e) {
                console.warn('Could not save menu state to localStorage:', e);
            }
        }
        
        function loadMenuState() {
            try {
                var stored = localStorage.getItem(STORAGE_KEY);
                if (stored) {
                    var state = JSON.parse(stored);
                    var expandedItems = state.expandedItems || [];
                    var clickedItem = state.clickedItem || null;
                    
                    console.log('📂 Loading menu state from localStorage:', {
                        expanded: expandedItems,
                        clicked: clickedItem ? clickedItem.text : 'none'
                    });
                    
                    // Apply expanded state to matching menu items
                    $mainMenu.find('li:has(ul)').each(function() {
                        var $li = $(this);
                        var menuText = $li.find('> a').text().trim();
                        
                        if (expandedItems.includes(menuText)) {
                            $li.addClass('active');
                            var $submenu = $li.find('> ul');
                            var $arrow = $li.find('.fa.arrow');
                            
                            $submenu.removeClass('collapse').addClass('collapse in').show();
                            if ($arrow.length > 0) {
                                $arrow.removeClass('fa-angle-left').addClass('fa-angle-down');
                            }
                            console.log('📂 Restored expanded state for:', menuText);
                        }
                    });
                    
                    // Apply clicked state if it exists
                    if (clickedItem) {
                        var $clickedItem = $mainMenu.find('a').filter(function() {
                            return $(this).text().trim() === clickedItem.text;
                        });
                        
                        if ($clickedItem.length > 0) {
                            $clickedItem.addClass('clicked-menu');
                            console.log('📂 Restored clicked state for:', clickedItem.text);
                        }
                    }
                    
                    return true;
                }
            } catch (e) {
                console.warn('Could not load menu state from localStorage:', e);
            }
            return false;
        }
        
        function clearMenuState() {
            try {
                localStorage.removeItem(STORAGE_KEY);
                localStorage.removeItem(CLICKED_ITEM_KEY);
                console.log('🗑️ Menu state cleared from localStorage');
            } catch (e) {
                console.warn('Could not clear menu state from localStorage:', e);
            }
        }
        
        // Enhanced clicked item tracking
        function saveClickedItem($clickedItem) {
            try {
                if ($clickedItem.length > 0) {
                    var clickedData = {
                        href: $clickedItem.attr('href'),
                        text: $clickedItem.text().trim(),
                        timestamp: Date.now()
                    };
                    localStorage.setItem(CLICKED_ITEM_KEY, JSON.stringify(clickedData));
                    console.log('✅ Clicked item saved:', clickedData.text);
                }
            } catch (e) {
                console.warn('Could not save clicked item to localStorage:', e);
            }
        }
        
        function loadClickedItem() {
            try {
                var stored = localStorage.getItem(CLICKED_ITEM_KEY);
                if (stored) {
                    var clickedData = JSON.parse(stored);
                    console.log('📂 Loading clicked item from localStorage:', clickedData.text);
                    return clickedData;
                }
            } catch (e) {
                console.warn('Could not load clicked item from localStorage:', e);
            }
            return null;
        }
        
        // Function to check if a URL matches the current page
        function isCurrentPage(href) {
            if (!href || href === '#' || href === 'javascript:void(0)') {
                return false;
            }
            
            // Clean up the href
            if (href.startsWith('/')) {
                href = href.substring(1);
            }
            
            if (href.includes('://')) {
                var urlParts = href.split('/');
                href = urlParts.slice(3).join('/');
            }
            
            href = href.replace(/\/$/, '');
            
            // More flexible URL matching
            var currentPathClean = currentPath.replace(/\/$/, '');
            
            // Exact match
            if (currentPathClean === href) {
                return true;
            }
            
            // Check if current path starts with href (for nested routes)
            if (currentPathClean.startsWith(href + '/')) {
                return true;
            }
            
            // Check if href starts with current path (for base routes)
            if (href.startsWith(currentPathClean + '/')) {
                return true;
            }
            
            // Check for partial matches (e.g., "stocks" matches "stocks/add")
            var currentParts = currentPathClean.split('/');
            var hrefParts = href.split('/');
            
            // Check if any part of the current URL matches the href
            for (var i = 0; i < currentParts.length; i++) {
                if (hrefParts.includes(currentParts[i])) {
                    return true;
                }
            }
            
            return false;
        }
        
        // Enhanced function to highlight menu items with clicked state
        function highlightMenu() {
            if (highlightLock) {
                console.log('🔒 Highlighting locked, skipping...');
                return;
            }
            
            highlightLock = true;
            
            var $menuItems = $mainMenu.find('li a');
            var $parentItems = $mainMenu.find('li:has(ul)');
            
            // Remove only active-menu classes, preserve clicked-menu
            $menuItems.removeClass('active-menu');
            $parentItems.removeClass('active');
            
            // Find and highlight the current page
            var currentMenuItem = null;
            var currentParentItem = null;
            var foundMatch = false;
            
            console.log('🔍 Looking for menu item matching path:', currentPath);
            
            // First pass: look for exact matches
            $menuItems.each(function() {
                var $this = $(this);
                var href = $this.attr('href');
                
                if (isCurrentPage(href)) {
                    currentMenuItem = $this;
                    currentParentItem = $this.closest('li:has(ul)');
                    foundMatch = true;
                    console.log('✅ Found exact match:', $this.text().trim(), 'for href:', href);
                    return false;
                }
            });
            
            // Second pass: if no exact match, look for partial matches
            if (!foundMatch) {
                $menuItems.each(function() {
                    var $this = $(this);
                    var href = $this.attr('href');
                    var menuText = $this.text().trim().toLowerCase();
                    
                    // Check if current path contains any part of the menu text
                    if (href === '#' && menuText.length > 0) {
                        var currentPathLower = currentPath.toLowerCase();
                        if (currentPathLower.includes(menuText.replace(/\s+/g, '').toLowerCase())) {
                            currentMenuItem = $this;
                            currentParentItem = $this.closest('li:has(ul)');
                            foundMatch = true;
                            console.log('✅ Found partial match:', $this.text().trim());
                            return false;
                        }
                    }
                });
            }
            
            // Apply highlighting
            if (currentMenuItem) {
                currentMenuItem.addClass('active-menu');
                console.log('✅ Highlighted menu item:', currentMenuItem.text().trim());
                
                // Check if this is the recently clicked item
                var clickedData = loadClickedItem();
                if (clickedData && clickedData.href === currentMenuItem.attr('href')) {
                    currentMenuItem.addClass('clicked-menu');
                    console.log('✅ Applied clicked-menu class to:', currentMenuItem.text().trim());
                }
                
                // Add a brief flash effect to show the highlight
                currentMenuItem.addClass('highlight-flash');
                setTimeout(function() {
                    currentMenuItem.removeClass('highlight-flash');
                }, 500);
                
                if (currentParentItem.length > 0) {
                    currentParentItem.addClass('active');
                    var $submenu = currentParentItem.find('> ul');
                    if ($submenu.length > 0) {
                        $submenu.removeClass('collapse').addClass('collapse in').show();
                        var $arrow = currentParentItem.find('.fa.arrow');
                        if ($arrow.length > 0) {
                            $arrow.removeClass('fa-angle-left').addClass('fa-angle-down');
                        }
                        console.log('✅ Expanded parent menu for:', currentParentItem.find('> a').text().trim());
                            
                        // Handle third-level navigation - expand parent of current submenu item
                        var $currentSubmenuItem = currentMenuItem.closest('ul.nav-second-level, ul.nav-third-level');
                        if ($currentSubmenuItem.length > 0 && $currentSubmenuItem.hasClass('nav-third-level')) {
                            var $secondLevelParent = $currentSubmenuItem.closest('li');
                            var $secondLevelArrow = $secondLevelParent.find('> a .fa.arrow');
                            if ($secondLevelArrow.length > 0) {
                                $secondLevelArrow.removeClass('fa-angle-left').addClass('fa-angle-down');
                            }
                            console.log('✅ Expanded second-level parent for third-level item');
                        }
                    }
                }
            } else {
                console.log('⚠️ No matching menu item found for current path:', currentPath);
            }
            
            // Restore clicked state from localStorage if no current clicked item
            if ($mainMenu.find('.clicked-menu').length === 0) {
                var clickedData = loadClickedItem();
                if (clickedData) {
                    var $clickedItem = $mainMenu.find('a').filter(function() {
                        return $(this).text().trim() === clickedData.text;
                    });
                    
                    if ($clickedItem.length > 0) {
                        $clickedItem.addClass('clicked-menu');
                        console.log('✅ Restored clicked state for:', clickedData.text);
                    }
                }
            }
            
            highlightLock = false;
        }
        
        // Initialize Metis Menu for proper submenu functionality
        function initializeMetisMenu() {
            console.log('=== INITIALIZING METIS MENU ===');
            
            // Check if Metis Menu is available
            if (typeof $.fn.metisMenu === 'undefined') {
                console.log('Metis Menu plugin not found, using fallback');
                initializeFallbackMenu();
                return;
            }
            
            // Initialize Metis Menu with custom options
            $mainMenu.metisMenu({
                toggle: true,
                preventDefault: false,
                activeClass: 'active',
                collapseClass: 'collapse',
                collapseInClass: 'in',
                collapsingClass: 'collapsing'
            });
            
            console.log('✅ Metis Menu initialized');
            
            // Override Metis Menu click behavior to prevent conflicts and save state
            $mainMenu.find('li:has(ul) > a').off('click.metisMenu').on('click.metisMenu', function(e) {
                e.preventDefault();
                e.stopPropagation();
                
                var $this = $(this);
                var $parent = $this.parent('li');
                var $submenu = $parent.find('> ul');
                var $arrow = $this.find('.fa.arrow');
                
                console.log('=== METIS MENU CLICK EVENT ===');
                console.log('Submenu clicked:', $this.text().trim());
                
                // Toggle active state
                $parent.toggleClass('active');
                
                // Toggle submenu visibility
                if ($parent.hasClass('active')) {
                    // Expand submenu
                    $submenu.removeClass('collapse').addClass('collapse in').show();
                    if ($arrow.length > 0) {
                        $arrow.removeClass('fa-angle-left').addClass('fa-angle-down');
                    }
                    console.log('✅ Submenu expanded');
                } else {
                    // Collapse submenu
                    $submenu.removeClass('collapse in').addClass('collapse').hide();
                    if ($arrow.length > 0) {
                        $arrow.removeClass('fa-angle-down').addClass('fa-angle-left');
                    }
                    console.log('✅ Submenu collapsed');
                }
                
                // Save state to localStorage
                saveMenuState();
            });
            
            // Handle third-level navigation clicks
            $mainMenu.find('ul.nav-second-level li:has(ul) > a').off('click.thirdLevel').on('click.thirdLevel', function(e) {
                e.preventDefault();
                e.stopPropagation();
                
                var $this = $(this);
                var $parent = $this.parent('li');
                var $submenu = $parent.find('> ul');
                var $arrow = $this.find('.fa.arrow');
                
                console.log('=== THIRD LEVEL MENU CLICK EVENT ===');
                console.log('Third level clicked:', $this.text().trim());
                
                // Toggle active state
                $parent.toggleClass('active');
                
                // Toggle submenu visibility
                if ($parent.hasClass('active')) {
                    // Expand submenu
                    $submenu.removeClass('collapse').addClass('collapse in').show();
                    if ($arrow.length > 0) {
                        $arrow.removeClass('fa-angle-left').addClass('fa-angle-down');
                    }
                    console.log('✅ Third level submenu expanded');
                } else {
                    // Collapse submenu
                    $submenu.removeClass('collapse in').addClass('collapse').hide();
                    if ($arrow.length > 0) {
                        $arrow.removeClass('fa-angle-down').addClass('fa-angle-left');
                    }
                    console.log('✅ Third level submenu collapsed');
                }
                
                // Save state to localStorage
                saveMenuState();
            });
        }
        
        // Fallback menu system if Metis Menu is not available
        function initializeFallbackMenu() {
            console.log('=== INITIALIZING FALLBACK MENU SYSTEM ===');
            
            // Remove any existing click handlers
            $mainMenu.find('li:has(ul) > a').off('click.fallback');
            
            // Add click handlers for submenu items
            $mainMenu.find('li:has(ul) > a').on('click.fallback', function(e) {
                e.preventDefault();
                e.stopPropagation();
                
                var $this = $(this);
                var $parent = $this.parent('li');
                var $submenu = $parent.find('> ul');
                var $arrow = $this.find('.fa.arrow');
                
                console.log('=== FALLBACK MENU CLICK EVENT ===');
                console.log('Submenu clicked:', $this.text().trim());
                
                // Toggle active state
                $parent.toggleClass('active');
                
                // Toggle submenu visibility
                if ($parent.hasClass('active')) {
                    // Expand submenu
                    $submenu.removeClass('collapse').addClass('collapse in').show();
                    if ($arrow.length > 0) {
                        $arrow.removeClass('fa-angle-left').addClass('fa-angle-down');
                    }
                    console.log('✅ Submenu expanded');
                } else {
                    // Collapse submenu
                    $submenu.removeClass('collapse in').addClass('collapse').hide();
                    if ($arrow.length > 0) {
                        $arrow.removeClass('fa-angle-down').addClass('fa-angle-left');
                    }
                    console.log('✅ Submenu collapsed');
                }
                
                // Save state to localStorage
                saveMenuState();
            });
            
            // Handle third-level navigation clicks in fallback mode
            $mainMenu.find('ul.nav-second-level li:has(ul) > a').on('click.thirdLevelFallback', function(e) {
                e.preventDefault();
                e.stopPropagation();
                
                var $this = $(this);
                var $parent = $this.parent('li');
                var $submenu = $parent.find('> ul');
                var $arrow = $this.find('.fa.arrow');
                
                console.log('=== THIRD LEVEL FALLBACK MENU CLICK EVENT ===');
                console.log('Third level clicked:', $this.text().trim());
                
                // Toggle active state
                $parent.toggleClass('active');
                
                // Toggle submenu visibility
                if ($parent.hasClass('active')) {
                    // Expand submenu
                    $submenu.removeClass('collapse').addClass('collapse in').show();
                    if ($arrow.length > 0) {
                        $arrow.removeClass('fa-angle-left').addClass('fa-angle-down');
                    }
                    console.log('✅ Third level submenu expanded');
                } else {
                    // Collapse submenu
                    $submenu.removeClass('collapse in').addClass('collapse').hide();
                    if ($arrow.length > 0) {
                        $arrow.removeClass('fa-angle-down').addClass('fa-angle-left');
                    }
                    console.log('✅ Third level submenu collapsed');
                }
                
                // Save state to localStorage
                saveMenuState();
            });
            
            console.log('✅ Fallback menu system initialized');
        }
        
        // Initialize submenu states
        function initializeSubmenuStates() {
            console.log('=== INITIALIZING SUBMENU STATES ===');
            
            // First, try to load saved state from localStorage
            var stateLoaded = loadMenuState();
            
            $mainMenu.find('li:has(ul)').each(function(index) {
                var $parent = $(this);
                var $submenu = $parent.find('> ul');
                var $arrow = $parent.find('.fa.arrow');
                
                // If no state was loaded, ensure submenu starts in collapsed state unless active
                if (!stateLoaded) {
                    if (!$parent.hasClass('active')) {
                        $submenu.removeClass('collapse in').addClass('collapse').hide();
                        if ($arrow.length > 0) {
                            $arrow.removeClass('fa-angle-down').addClass('fa-angle-left');
                        }
                    } else {
                        $submenu.removeClass('collapse').addClass('collapse in').show();
                        if ($arrow.length > 0) {
                            $arrow.removeClass('fa-angle-left').addClass('fa-angle-down');
                        }
                    }
                }
            });
            
            // Initialize third-level submenu states
            $mainMenu.find('ul.nav-second-level li:has(ul)').each(function(index) {
                var $parent = $(this);
                var $submenu = $parent.find('> ul');
                var $arrow = $parent.find('.fa.arrow');
                
                // If no state was loaded, ensure third-level submenu starts in collapsed state unless active
                if (!stateLoaded) {
                    if (!$parent.hasClass('active')) {
                        $submenu.removeClass('collapse in').addClass('collapse').hide();
                        if ($arrow.length > 0) {
                            $arrow.removeClass('fa-angle-down').addClass('fa-angle-left');
                        }
                    } else {
                        $submenu.removeClass('collapse').addClass('collapse in').show();
                        if ($arrow.length > 0) {
                            $arrow.removeClass('fa-angle-left').addClass('fa-angle-down');
                        }
                    }
                }
            });
            
            console.log('✅ Submenu states initialized (including third-level)');
        }
        
        // Enhanced submenu item click handling with clicked state
        $mainMenu.on('click', 'li:not(:has(ul)) > a', function(e) {
            var $this = $(this);
            var $parent = $this.closest('li:has(ul)');
            
            console.log('=== SUBMENU ITEM CLICKED ===');
            console.log('Clicked item:', $this.text().trim());
            console.log('Parent menu:', $parent.length > 0 ? $parent.find('> a').text().trim() : 'None');
            
            // Save clicked item to localStorage
            saveClickedItem($this);
            
            // Remove clicked-menu class from all other items
            $mainMenu.find('li a').removeClass('clicked-menu');
            
            // Add clicked-menu class to this item
            $this.addClass('clicked-menu');
            
            // Add special click effect
            $this.addClass('menu-click-effect');
            setTimeout(function() {
                $this.removeClass('menu-click-effect');
            }, 300);
            
            if ($parent.length > 0) {
                // Ensure parent stays active and submenu stays open
                $parent.addClass('active');
                var $submenu = $parent.find('> ul');
                if ($submenu.length > 0) {
                    $submenu.removeClass('collapse').addClass('collapse in').show();
                }
                
                // Save state to localStorage
                saveMenuState();
            }
        });
        
        // Handle parent menu clicks (for expanding/collapsing)
        $mainMenu.on('click', 'li:has(ul) > a', function(e) {
            var $this = $(this);
            var $parent = $this.parent('li');
            
            console.log('=== PARENT MENU CLICKED ===');
            console.log('Parent menu clicked:', $this.text().trim());
            
            // Save clicked item to localStorage
            saveClickedItem($this);
            
            // Remove clicked-menu class from all other items
            $mainMenu.find('li a').removeClass('clicked-menu');
            
            // Add clicked-menu class to this item
            $this.addClass('clicked-menu');
            
            // Add special click effect
            $this.addClass('menu-click-effect');
            setTimeout(function() {
                $this.removeClass('menu-click-effect');
            }, 300);
        });
        
        // Add click handler for menu items to update highlighting
        $mainMenu.on('click', 'a', function() {
            var $this = $(this);
            var href = $this.attr('href');
            
            if (href && href !== '#' && href !== 'javascript:void(0)' && !href.startsWith('http')) {
                // Update current path immediately for highlighting
                var newPath = href.replace(/\/$/, '').replace(/^\//, '');
                if (newPath !== currentPath) {
                    currentPath = newPath;
                    console.log('🔄 URL changed to:', currentPath);
                    
                    // Immediately highlight the clicked item
                    highlightMenu();
                }
            }
        });
        
        // Also handle navigation events for better URL tracking
        $(window).on('popstate', function() {
            // Update current path when browser back/forward is used
            currentPath = window.location.pathname.replace(/\/$/, '').replace(/^\//, '');
            console.log('🔄 Browser navigation detected, new path:', currentPath);
            highlightMenu();
        });
        
        // Update highlighting when page loads or URL changes
        function updateHighlightingFromURL() {
            var newPath = window.location.pathname.replace(/\/$/, '').replace(/^\//, '');
            if (newPath !== currentPath) {
                currentPath = newPath;
                console.log('🔄 Page load detected, path:', currentPath);
                highlightMenu();
            }
        }
        
        // Call this function periodically to catch URL changes
        setInterval(updateHighlightingFromURL, 1000);
        
        // Initialize highlighting immediately
        highlightMenu();
        
        // Initialize menu system
        initializeMetisMenu();
        
        // Initialize submenu states after a short delay
        setTimeout(function() {
            initializeSubmenuStates();
        }, 200);
        
        // Prevent automatic removal of highlighting classes
        $mainMenu.on('DOMNodeRemoved', function(e) {
            if ($(e.target).hasClass('clicked-menu') || $(e.target).hasClass('active-menu')) {
                console.log('⚠️ Highlighting class being removed, preventing...');
                e.preventDefault();
                e.stopPropagation();
            }
        });
        
        // Monitor for class changes and restore if needed
        var observer = new MutationObserver(function(mutations) {
            mutations.forEach(function(mutation) {
                if (mutation.type === 'attributes' && mutation.attributeName === 'class') {
                    var $target = $(mutation.target);
                    
                    // If clicked-menu class was removed, restore it
                    if ($target.is('a') && !$target.hasClass('clicked-menu')) {
                        var clickedData = loadClickedItem();
                        if (clickedData && $target.text().trim() === clickedData.text) {
                            console.log('🔄 Restoring clicked-menu class for:', clickedData.text);
                            $target.addClass('clicked-menu');
                        }
                    }
                }
            });
        });
        
        // Observe the main menu for class changes
        observer.observe($mainMenu[0], {
            attributes: true,
            attributeFilter: ['class'],
            subtree: true
        });
        
        // TEST FUNCTIONS
        window.testSubmenuExpansion = function() {
            console.log('=== TESTING SUBMENU EXPANSION ===');
            var $firstSubmenu = $mainMenu.find('li:has(ul)').first();
            if ($firstSubmenu.length > 0) {
                var $submenu = $firstSubmenu.find('> ul');
                var $arrow = $firstSubmenu.find('.fa.arrow');
                
                console.log('Testing submenu:', $firstSubmenu.find('> a').text().trim());
                console.log('Current state:', {
                    active: $firstSubmenu.hasClass('active'),
                    visible: $submenu.is(':visible'),
                    classes: $submenu.attr('class'),
                    display: $submenu.css('display')
                });
                
                // Test expansion
                $firstSubmenu.find('> a').click();
                
                setTimeout(function() {
                    console.log('After click:', {
                        active: $firstSubmenu.hasClass('active'),
                        visible: $submenu.is(':visible'),
                        classes: $submenu.attr('class'),
                        display: $submenu.css('display')
                    });
                }, 100);
            }
        };
        
        // Test highlighting with current URL
        window.testCurrentHighlighting = function() {
            console.log('=== TESTING CURRENT URL HIGHLIGHTING ===');
            console.log('Current URL path:', currentPath);
            highlightMenu();
            
            var $activeItems = $mainMenu.find('.active-menu');
            console.log('Active menu items found:', $activeItems.length);
            $activeItems.each(function(index) {
                console.log(index + 1 + '. ' + $(this).text().trim());
            });
            
            return $activeItems.length;
        };
        
        // Test highlighting with a specific URL
        window.testHighlighting = function(testUrl) {
            console.log('=== TESTING HIGHLIGHTING ===');
            console.log('Testing with URL:', testUrl);
            
            // Temporarily change current path for testing
            var originalPath = currentPath;
            currentPath = testUrl;
            
            // Run highlighting
            highlightMenu();
            
            // Show results
            var $activeItems = $mainMenu.find('.active-menu');
            console.log('Active menu items found:', $activeItems.length);
            $activeItems.each(function(index) {
                console.log(index + 1 + '. ' + $(this).text().trim());
            });
            
            // Restore original path
            currentPath = originalPath;
            
            return $activeItems.length;
        };
        
        // Force highlight a specific menu item by text
        window.forceHighlight = function(menuText) {
            console.log('=== FORCE HIGHLIGHTING ===');
            console.log('Force highlighting:', menuText);
            
            // Remove all active classes first
            $mainMenu.find('li a').removeClass('active-menu clicked-menu');
            $mainMenu.find('li:has(ul)').removeClass('active');
            
            // Find menu item by text
            var $menuItem = $mainMenu.find('a').filter(function() {
                return $(this).text().trim() === menuText;
            });
            
            if ($menuItem.length > 0) {
                $menuItem.addClass('active-menu clicked-menu');
                console.log('✅ Force highlighted:', menuText);
                
                // Expand parent if it's a submenu item
                var $parent = $menuItem.closest('li:has(ul)');
                if ($parent.length > 0) {
                    $parent.addClass('active');
                    var $submenu = $parent.find('> ul');
                    var $arrow = $parent.find('.fa.arrow');
                    
                    $submenu.removeClass('collapse').addClass('collapse in').show();
                    if ($arrow.length > 0) {
                        $arrow.removeClass('fa-angle-left').addClass('fa-angle-down');
                    }
                    console.log('✅ Expanded parent for:', menuText);
                }
                
                return true;
            } else {
                console.log('❌ Menu item not found:', menuText);
                return false;
            }
        };
        
        window.checkAllSubmenus = function() {
            console.log('=== CHECKING ALL SUBMENUS ===');
            $mainMenu.find('li:has(ul)').each(function(index) {
                var $parent = $(this);
                var $submenu = $parent.find('> ul');
                var $arrow = $parent.find('.fa.arrow');
                
                console.log(index + 1 + '. ' + $parent.find('> a').text().trim());
                console.log('   Active:', $parent.hasClass('active'));
                console.log('   Submenu classes:', $submenu.attr('class'));
                console.log('   Submenu visible:', $submenu.is(':visible'));
                console.log('   Arrow classes:', $arrow.attr('class'));
                console.log('---');
            });
        };
        
        window.expandAllSubmenus = function() {
            console.log('=== FORCING ALL SUBMENUS TO EXPAND ===');
            $mainMenu.find('li:has(ul)').each(function() {
                var $parent = $(this);
                var $submenu = $parent.find('> ul');
                var $arrow = $parent.find('.fa.arrow');
                
                $parent.addClass('active');
                $submenu.removeClass('collapse').addClass('collapse in').show();
                if ($arrow.length > 0) {
                    $arrow.removeClass('fa-angle-left').addClass('fa-angle-down');
                }
            });
            console.log('All submenus forced to expand');
            
            // Save expanded state to localStorage
            saveMenuState();
        };
        
        window.collapseAllSubmenus = function() {
            console.log('=== FORCING ALL SUBMENUS TO COLLAPSE ===');
            $mainMenu.find('li:has(ul)').each(function() {
                var $parent = $(this);
                var $submenu = $parent.find('> ul');
                var $arrow = $parent.find('.fa.arrow');
                
                $parent.removeClass('active');
                $submenu.removeClass('collapse in').addClass('collapse').hide();
                if ($arrow.length > 0) {
                    $arrow.removeClass('fa-angle-down').addClass('fa-angle-left');
                }
            });
            console.log('All submenus forced to collapse');
            
            // Save collapsed state to localStorage
            saveMenuState();
        };
        
        window.clearMenuState = function() {
            clearMenuState();
            console.log('Menu state cleared from localStorage');
        };
        
        window.loadMenuState = function() {
            loadMenuState();
            console.log('Menu state loaded from localStorage');
        };
        
        window.saveMenuState = function() {
            saveMenuState();
            console.log('Menu state saved to localStorage');
        };
        

        
        // New function to test clicked menu highlighting
        window.testClickedMenuHighlighting = function() {
            console.log('=== TESTING CLICKED MENU HIGHLIGHTING ===');
            var $clickedItems = $mainMenu.find('.clicked-menu');
            console.log('Clicked menu items found:', $clickedItems.length);
            $clickedItems.each(function(index) {
                console.log(index + 1 + '. ' + $(this).text().trim());
            });
            
            var clickedData = loadClickedItem();
            if (clickedData) {
                console.log('Last clicked item from localStorage:', clickedData);
            }
            
            return $clickedItems.length;
        };
        
        // New function to simulate clicking a menu item
        window.simulateMenuClick = function(menuText) {
            console.log('=== SIMULATING MENU CLICK ===');
            console.log('Simulating click on:', menuText);
            
            var $menuItem = $mainMenu.find('a').filter(function() {
                return $(this).text().trim() === menuText;
            });
            
            if ($menuItem.length > 0) {
                $menuItem.trigger('click');
                console.log('✅ Click simulated on:', menuText);
                return true;
            } else {
                console.log('❌ Menu item not found:', menuText);
                return false;
            }
        };
        
        window.testBasicStructure = function() {
            console.log('=== TESTING BASIC STRUCTURE ===');
            var $submenuItems = $mainMenu.find('li:has(ul)');
            console.log('Submenu items found:', $submenuItems.length);
            
            $submenuItems.each(function(index) {
                var $li = $(this);
                var $a = $li.find('> a');
                var $ul = $li.find('> ul');
                var $arrow = $a.find('.fa.arrow');
                
                console.log(index + 1 + '. ' + $a.text().trim());
                console.log('   Has submenu:', $ul.length > 0);
                console.log('   Submenu classes:', $ul.attr('class'));
                console.log('   Has arrow:', $arrow.length > 0);
                console.log('   Arrow classes:', $arrow.attr('class'));
                console.log('   Submenu visible:', $ul.is(':visible'));
                console.log('---');
            });
        };
        
        window.testCSSFix = function() {
            console.log('=== TESTING CSS FIX ===');
            var $firstSubmenu = $mainMenu.find('li:has(ul)').first();
            
            if ($firstSubmenu.length > 0) {
                var $submenu = $firstSubmenu.find('> ul');
                var $arrow = $firstSubmenu.find('.fa.arrow');
                
                console.log('Testing submenu:', $firstSubmenu.find('> a').text().trim());
                console.log('Current submenu classes:', $submenu.attr('class'));
                console.log('Current submenu visible:', $submenu.is(':visible'));
                console.log('Current submenu display:', $submenu.css('display'));
                
                // Test manual expansion
                console.log('Testing manual expansion...');
                $firstSubmenu.addClass('active');
                $submenu.removeClass('collapse').addClass('collapse in').show();
                
                if ($arrow.length > 0) {
                    $arrow.removeClass('fa-angle-left').addClass('fa-angle-down');
                }
                
                console.log('After manual expansion:');
                console.log('  - Submenu classes:', $submenu.attr('class'));
                console.log('  - Submenu visible:', $submenu.is(':visible'));
                console.log('  - Submenu display:', $submenu.css('display'));
                console.log('  - Parent active:', $firstSubmenu.hasClass('active'));
                console.log('  - Arrow classes:', $arrow.attr('class'));
                
                // Test CSS rules
                var computedStyle = window.getComputedStyle($submenu[0]);
                console.log('CSS computed values:');
                console.log('  - display:', computedStyle.display);
                console.log('  - visibility:', computedStyle.visibility);
                console.log('  - opacity:', computedStyle.opacity);
                console.log('  - height:', computedStyle.height);
            }
        };
        
        // Log test functions availability
        console.log('Enhanced test functions available:');
        console.log('- window.testSubmenuExpansion()');
        console.log('- window.checkAllSubmenus()');
        console.log('- window.expandAllSubmenus()');
        console.log('- window.collapseAllSubmenus()');
        console.log('- window.clearMenuState()');
        console.log('- window.loadMenuState()');
        console.log('- window.saveMenuState()');
        console.log('- window.testBasicStructure()');
        console.log('- window.testCSSFix()');
        console.log('- window.testClickedMenuHighlighting()');
        console.log('- window.simulateMenuClick("Menu Text")');
        console.log('=== ENHANCED MENU HIGHLIGHT SCRIPT READY (FIXED VERSION) ===');
    });
    
})(jQuery);
