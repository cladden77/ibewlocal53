/**
 * IBEW Local 53 Main JavaScript
 * Includes calendar functionality and scroll reveal animations
 */

(function() {
    'use strict';

    // Scroll Reveal Animation using Intersection Observer
    function initScrollReveal() {
        // Check if user prefers reduced motion
        const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
        
        if (prefersReducedMotion) {
            // If reduced motion is preferred, show all elements immediately
            document.querySelectorAll('.reveal, .reveal-fade-up, .reveal-fade-down, .reveal-fade-left, .reveal-fade-right, .reveal-scale, .reveal-stagger').forEach(function(el) {
                el.classList.add('revealed');
            });
            return;
        }

        // Options for the Intersection Observer
        const observerOptions = {
            root: null, // Use viewport as root
            rootMargin: '0px 0px -80px 0px', // Trigger slightly before element enters viewport
            threshold: 0.1 // Trigger when 10% of element is visible
        };

        // Callback function when intersection changes
        const observerCallback = function(entries, observer) {
            entries.forEach(function(entry) {
                if (entry.isIntersecting) {
                    // Add revealed class when element comes into view
                    entry.target.classList.add('revealed');
                    // Stop observing once revealed (one-time animation)
                    observer.unobserve(entry.target);
                }
            });
        };

        // Create the observer
        const observer = new IntersectionObserver(observerCallback, observerOptions);

        // Select all elements with reveal classes and observe them
        const revealElements = document.querySelectorAll('.reveal, .reveal-fade-up, .reveal-fade-down, .reveal-fade-left, .reveal-fade-right, .reveal-scale, .reveal-stagger');
        
        revealElements.forEach(function(el) {
            observer.observe(el);
        });
    }

    // Calendar functionality for Events archive
    function initCalendar() {
        const calendarGrid = document.getElementById('calendar-grid');
        const monthYearEl = document.getElementById('calendar-month-year');
        const prevBtn = document.querySelector('.prev-month');
        const nextBtn = document.querySelector('.next-month');
        const calendarCard = document.querySelector('.calendar-card');

        if (!calendarGrid || !monthYearEl) {
            return;
        }

        // Get event dates from data attribute
        let eventDates = [];
        let eventCategories = {};
        if (calendarCard) {
            if (calendarCard.dataset.eventDates) {
                try {
                    eventDates = JSON.parse(calendarCard.dataset.eventDates);
                } catch (e) {
                    console.error('Error parsing event dates:', e);
                }
            }
            if (calendarCard.dataset.eventCategories) {
                try {
                    eventCategories = JSON.parse(calendarCard.dataset.eventCategories);
                } catch (e) {
                    console.error('Error parsing event categories:', e);
                }
            }
        }

        let currentDate = new Date();
        let currentMonth = currentDate.getMonth();
        let currentYear = currentDate.getFullYear();
        let selectedFilterDate = null; // Track currently selected date for filtering
        let selectedFilterCategory = null; // Track currently selected category for filtering

        // Helper function to format date as YYYY-MM-DD
        function formatDate(year, month, day) {
            const monthStr = String(month + 1).padStart(2, '0');
            const dayStr = String(day).padStart(2, '0');
            return `${year}-${monthStr}-${dayStr}`;
        }

        // Helper function to check if a date has an event and get its category
        function getEventCategory(year, month, day) {
            const dateStr = formatDate(year, month, day);
            if (eventDates.includes(dateStr)) {
                return eventCategories[dateStr] || 'event';
            }
            return null;
        }

        // Function to apply filters (date and/or category)
        function applyFilters() {
            const eventItems = document.querySelectorAll('.event-list-item');
            const monthSections = document.querySelectorAll('.events-month-section');
            const noEventsMessage = document.getElementById('no-events-scheduled');
            const resetLink = document.getElementById('events-filter-reset');
            const pagination = document.querySelector('.pagination');
            const todayStr = formatDate(currentDate.getFullYear(), currentDate.getMonth(), currentDate.getDate());
            let visibleCount = 0;

            const hasDateFilter = selectedFilterDate !== null;
            const hasCategoryFilter = selectedFilterCategory !== null;
            const hasAnyFilter = hasDateFilter || hasCategoryFilter;

            if (!hasAnyFilter) {
                // Show all events
                eventItems.forEach(item => {
                    item.style.display = '';
                });
                monthSections.forEach(section => {
                    section.style.display = '';
                });
                if (noEventsMessage) {
                    noEventsMessage.style.display = 'none';
                }
                // Hide reset link
                if (resetLink) {
                    resetLink.style.display = 'none';
                }
                // Show pagination in default view
                if (pagination) {
                    pagination.style.display = '';
                }
            } else {
                // Filter events by date and/or category
                eventItems.forEach(item => {
                    const eventDate = item.getAttribute('data-event-date');
                    const eventCategory = item.getAttribute('data-event-category');
                    
                    let matchesDate = !hasDateFilter || eventDate === selectedFilterDate;
                    let matchesCategory = !hasCategoryFilter || eventCategory === selectedFilterCategory;
                    
                    if (matchesDate && matchesCategory) {
                        item.style.display = '';
                        visibleCount++;
                    } else {
                        item.style.display = 'none';
                    }
                });

                // Show/hide month sections based on visible events
                monthSections.forEach(section => {
                    const sectionEvents = section.querySelectorAll('.event-list-item');
                    const visibleInSection = Array.from(sectionEvents).some(item => item.style.display !== 'none');
                    if (visibleInSection) {
                        section.style.display = '';
                    } else {
                        section.style.display = 'none';
                    }
                });

                // Show "No Events Scheduled" if no events match
                if (noEventsMessage) {
                    if (visibleCount === 0) {
                        noEventsMessage.style.display = 'block';
                    } else {
                        noEventsMessage.style.display = 'none';
                    }
                }

                // Show reset link when filtering (unless date filter is just today)
                if (resetLink) {
                    const showReset = hasCategoryFilter || (hasDateFilter && selectedFilterDate !== todayStr);
                    resetLink.style.display = showReset ? 'block' : 'none';
                }

                // Hide pagination when filtering (unless more than 4 visible events)
                if (pagination) {
                    if (visibleCount > 4) {
                        pagination.style.display = '';
                    } else {
                        pagination.style.display = 'none';
                    }
                }
            }
        }

        // Function to filter events by selected date
        function filterEventsByDate(filterDate) {
            selectedFilterDate = filterDate;
            applyFilters();
        }

        // Function to filter events by selected category
        function filterEventsByCategory(filterCategory) {
            selectedFilterCategory = filterCategory;
            applyFilters();
        }

        function renderCalendar(month, year) {
            const firstDay = new Date(year, month, 1).getDay();
            const daysInMonth = new Date(year, month + 1, 0).getDate();
            const daysInPrevMonth = new Date(year, month, 0).getDate();

            // Update month/year display
            const monthNames = ['January', 'February', 'March', 'April', 'May', 'June',
                'July', 'August', 'September', 'October', 'November', 'December'];
            monthYearEl.textContent = monthNames[month] + ' ' + year;

            // Clear calendar
            calendarGrid.innerHTML = '';

            // Add day headers
            const dayHeaders = ['Su', 'Mo', 'Tu', 'We', 'Th', 'Fr', 'Sa'];
            dayHeaders.forEach(day => {
                const header = document.createElement('div');
                header.className = 'calendar-day-header';
                header.textContent = day;
                calendarGrid.appendChild(header);
            });

            // Add previous month days
            for (let i = firstDay - 1; i >= 0; i--) {
                const dayContainer = document.createElement('div');
                dayContainer.className = 'calendar-day-wrapper';
                const day = document.createElement('div');
                day.className = 'calendar-day other-month';
                day.textContent = daysInPrevMonth - i;
                dayContainer.appendChild(day);
                calendarGrid.appendChild(dayContainer);
            }

            // Add current month days
            const today = new Date();
            for (let i = 1; i <= daysInMonth; i++) {
                const dayContainer = document.createElement('div');
                dayContainer.className = 'calendar-day-wrapper';
                
                const day = document.createElement('div');
                day.className = 'calendar-day';
                day.textContent = i;

                // Highlight today with background color
                const isTodayDay = (i === today.getDate() && month === today.getMonth() && year === today.getFullYear());
                if (isTodayDay) {
                    day.classList.add('selected');
                }

                // Add event indicator circle (only if not today)
                const eventCategory = getEventCategory(year, month, i);
                if (eventCategory && !isTodayDay) {
                    day.classList.add('has-event');
                    day.classList.add(eventCategory); // Add category class to day for text color styling
                    const indicator = document.createElement('span');
                    indicator.className = 'calendar-event-indicator ' + eventCategory;
                    dayContainer.appendChild(day);
                    dayContainer.appendChild(indicator);
                } else {
                    dayContainer.appendChild(day);
                }

                // Add click handler for filtering events by date
                day.addEventListener('click', function() {
                    const clickedDate = formatDate(year, month, i);
                    
                    // If clicking the same date, clear the filter
                    if (selectedFilterDate === clickedDate) {
                        selectedFilterDate = null;
                        // Remove selected from all days
                        document.querySelectorAll('.calendar-day').forEach(d => d.classList.remove('selected'));
                        // Show all events
                        filterEventsByDate(null);
                    } else {
                        selectedFilterDate = clickedDate;
                        // Remove selected from all days
                        document.querySelectorAll('.calendar-day').forEach(d => d.classList.remove('selected'));
                        // Add selected to clicked day
                        this.classList.add('selected');
                        // Filter events by selected date
                        filterEventsByDate(clickedDate);
                    }
                });

                calendarGrid.appendChild(dayContainer);
            }

            // Add next month days to fill grid
            const totalCells = calendarGrid.children.length;
            const remainingCells = 42 - totalCells; // 6 rows * 7 days
            for (let i = 1; i <= remainingCells; i++) {
                const dayContainer = document.createElement('div');
                dayContainer.className = 'calendar-day-wrapper';
                const day = document.createElement('div');
                day.className = 'calendar-day other-month';
                day.textContent = i;
                dayContainer.appendChild(day);
                calendarGrid.appendChild(dayContainer);
            }
        }

        // Initial render
        renderCalendar(currentMonth, currentYear);

        // Previous month button
        if (prevBtn) {
            prevBtn.addEventListener('click', function() {
                currentMonth--;
                if (currentMonth < 0) {
                    currentMonth = 11;
                    currentYear--;
                }
                // Clear filter when navigating
                selectedFilterDate = null;
                filterEventsByDate(null);
                renderCalendar(currentMonth, currentYear);
            });
        }

        // Next month button
        if (nextBtn) {
            nextBtn.addEventListener('click', function() {
                currentMonth++;
                if (currentMonth > 11) {
                    currentMonth = 0;
                    currentYear++;
                }
                // Clear filter when navigating
                selectedFilterDate = null;
                filterEventsByDate(null);
                renderCalendar(currentMonth, currentYear);
            });
        }

        // Reset filter link
        // The reset link now has a proper href for server-side category reset
        // If there's a date filter active (client-side only), we handle it here
        // Otherwise, let the natural link navigation happen for category reset
        const resetLink = document.getElementById('reset-filter-link');
        if (resetLink) {
            resetLink.addEventListener('click', function(e) {
                // Check if there's a category filter in the URL
                const urlParams = new URLSearchParams(window.location.search);
                const hasCategoryFilter = urlParams.has('event_category');
                
                // If we only have a date filter (no category filter), prevent navigation and just reset date
                if (selectedFilterDate && !hasCategoryFilter) {
                    e.preventDefault();
                    // Clear selected date
                    selectedFilterDate = null;
                    // Remove selected from all days
                    document.querySelectorAll('.calendar-day').forEach(d => d.classList.remove('selected'));
                    
                    // Re-select today's date if it's in the current month view
                    const today = new Date();
                    if (currentMonth === today.getMonth() && currentYear === today.getFullYear()) {
                        const todayDay = today.getDate();
                        const calendarDays = document.querySelectorAll('.calendar-day:not(.other-month)');
                        calendarDays.forEach(day => {
                            if (parseInt(day.textContent) === todayDay) {
                                day.classList.add('selected');
                            }
                        });
                    }
                    
                    // Clear all filters
                    selectedFilterCategory = null;
                    applyFilters();
                }
                // If there's a category filter, let the link navigate naturally to clear it
            });
        }

        // Category filter - now uses server-side filtering via URL
        // The category links have proper href attributes, so they work as regular links
        // We just need to track if there's an active category filter for date filtering purposes
        const categoryList = document.getElementById('event-category-filter');
        if (categoryList) {
            const activeCategory = categoryList.querySelector('.category-item.active');
            if (activeCategory) {
                selectedFilterCategory = activeCategory.getAttribute('data-category');
            }
        }
    }

    // Events carousel functionality for front page
    function initEventsCarousel() {
        const eventsGrid = document.querySelector('.upcoming-events-section .events-grid');
        const prevArrow = document.querySelector('.upcoming-events-section .prev-arrow');
        const nextArrow = document.querySelector('.upcoming-events-section .next-arrow');

        if (!eventsGrid || !prevArrow || !nextArrow) {
            return;
        }

        // Calculate scroll amount: one card width + gap
        // Get computed values
        const computedStyle = window.getComputedStyle(eventsGrid);
        const gap = parseInt(computedStyle.gap) || 32; // Default to 32px (2rem) if gap not found
        const firstCard = eventsGrid.querySelector('.event-card');
        const cardWidth = firstCard ? firstCard.offsetWidth : 300;
        const scrollAmount = cardWidth + gap;

        // Previous arrow - scroll left by one card
        prevArrow.addEventListener('click', function() {
            eventsGrid.scrollBy({
                left: -scrollAmount,
                behavior: 'smooth'
            });
        });

        // Next arrow - scroll right by one card
        nextArrow.addEventListener('click', function() {
            eventsGrid.scrollBy({
                left: scrollAmount,
                behavior: 'smooth'
            });
        });

        // Update arrow visibility based on scroll position
        function updateArrowVisibility() {
            const isAtStart = eventsGrid.scrollLeft <= 10; // Small threshold for rounding
            const isAtEnd = eventsGrid.scrollLeft >= eventsGrid.scrollWidth - eventsGrid.clientWidth - 10;

            prevArrow.style.opacity = isAtStart ? '0.5' : '1';
            prevArrow.style.pointerEvents = isAtStart ? 'none' : 'auto';
            prevArrow.setAttribute('aria-disabled', isAtStart ? 'true' : 'false');

            nextArrow.style.opacity = isAtEnd ? '0.5' : '1';
            nextArrow.style.pointerEvents = isAtEnd ? 'none' : 'auto';
            nextArrow.setAttribute('aria-disabled', isAtEnd ? 'true' : 'false');
        }

        // Check on scroll
        eventsGrid.addEventListener('scroll', updateArrowVisibility);
        
        // Initial check
        updateArrowVisibility();
    }

    // Mobile menu functionality
    function initMobileMenu() {
        const menuToggle = document.querySelector('.mobile-menu-toggle');
        const menuOverlay = document.querySelector('.mobile-menu-overlay');
        const menuClose = document.querySelector('.mobile-menu-close');
        const mobileMenu = document.querySelector('.mobile-menu');
        const body = document.body;

        if (!menuToggle || !menuOverlay || !menuClose) {
            return;
        }

        function openMenu() {
            menuToggle.setAttribute('aria-expanded', 'true');
            menuOverlay.classList.add('active');
            menuOverlay.setAttribute('aria-hidden', 'false');
            body.style.overflow = 'hidden'; // Prevent body scroll when menu is open
        }

        function closeMenu() {
            menuToggle.setAttribute('aria-expanded', 'false');
            menuOverlay.classList.remove('active');
            menuOverlay.setAttribute('aria-hidden', 'true');
            body.style.overflow = ''; // Restore body scroll
        }

        // Toggle menu on hamburger click
        menuToggle.addEventListener('click', function(e) {
            e.stopPropagation();
            if (menuOverlay.classList.contains('active')) {
                closeMenu();
            } else {
                openMenu();
            }
        });

        // Close menu on close button click
        menuClose.addEventListener('click', closeMenu);

        // Close menu when clicking overlay (outside menu)
        menuOverlay.addEventListener('click', function(e) {
            if (e.target === menuOverlay) {
                closeMenu();
            }
        });

        // Close menu on escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && menuOverlay.classList.contains('active')) {
                closeMenu();
            }
        });

        // Close menu when clicking a menu link
        const mobileMenuLinks = document.querySelectorAll('.mobile-nav-menu a');
        mobileMenuLinks.forEach(link => {
            link.addEventListener('click', function() {
                closeMenu();
            });
        });
    }

    // Smooth scroll for pagination links
    function initPaginationScroll() {
        // Handle pagination link clicks
        document.querySelectorAll('.pagination-nav a').forEach(function(link) {
            link.addEventListener('click', function(e) {
                // Get the hash from the href
                const href = this.getAttribute('href');
                const hashIndex = href.indexOf('#');
                
                if (hashIndex !== -1) {
                    const targetId = href.substring(hashIndex + 1);
                    const targetElement = document.getElementById(targetId);
                    
                    if (targetElement) {
                        // Store scroll target in sessionStorage
                        sessionStorage.setItem('scrollToElement', targetId);
                    }
                }
            });
        });
        
        // Check if we need to scroll on page load
        const scrollTarget = sessionStorage.getItem('scrollToElement');
        if (scrollTarget) {
            sessionStorage.removeItem('scrollToElement');
            const targetElement = document.getElementById(scrollTarget);
            if (targetElement) {
                // Small delay to ensure page is fully loaded
                setTimeout(function() {
                    const headerHeight = document.querySelector('.site-header') ? document.querySelector('.site-header').offsetHeight : 0;
                    const targetPosition = targetElement.getBoundingClientRect().top + window.pageYOffset - headerHeight - 20;
                    window.scrollTo({
                        top: targetPosition,
                        behavior: 'smooth'
                    });
                }, 100);
            }
        }
        
        // Also handle direct hash navigation on page load
        if (window.location.hash) {
            const targetId = window.location.hash.substring(1);
            const targetElement = document.getElementById(targetId);
            if (targetElement) {
                setTimeout(function() {
                    const headerHeight = document.querySelector('.site-header') ? document.querySelector('.site-header').offsetHeight : 0;
                    const targetPosition = targetElement.getBoundingClientRect().top + window.pageYOffset - headerHeight - 20;
                    window.scrollTo({
                        top: targetPosition,
                        behavior: 'smooth'
                    });
                }, 100);
            }
        }
    }

    // Resources page filtering and search functionality
    function initResourcesFilter() {
        const filterChips = document.querySelectorAll('.resource-category-filters .filter-chip');
        const searchInput = document.getElementById('resource-search');
        const resourcesGrid = document.getElementById('resources-grid');
        const noResultsMessage = document.getElementById('no-results-message');
        
        if (!resourcesGrid) {
            return;
        }
        
        const resourceCards = resourcesGrid.querySelectorAll('.resource-card');
        let activeCategory = 'all';
        let searchTerm = '';
        
        // Filter function that combines category and search
        function filterResources() {
            let visibleCount = 0;
            
            resourceCards.forEach(function(card) {
                const cardCategories = card.getAttribute('data-categories') || '';
                const cardTitle = card.getAttribute('data-title') || '';
                
                // Check category match
                const categoryMatch = activeCategory === 'all' || cardCategories.includes(activeCategory);
                
                // Check search match (case-insensitive)
                const searchMatch = searchTerm === '' || cardTitle.includes(searchTerm.toLowerCase());
                
                // Show card if both filters match
                if (categoryMatch && searchMatch) {
                    card.classList.remove('hidden');
                    visibleCount++;
                } else {
                    card.classList.add('hidden');
                }
            });
            
            // Show/hide no results message
            if (noResultsMessage) {
                if (visibleCount === 0 && resourceCards.length > 0) {
                    noResultsMessage.style.display = 'flex';
                    resourcesGrid.style.display = 'none';
                } else {
                    noResultsMessage.style.display = 'none';
                    resourcesGrid.style.display = 'grid';
                }
            }
        }
        
        // Category filter chip click handlers
        filterChips.forEach(function(chip) {
            chip.addEventListener('click', function() {
                // Remove active class from all chips
                filterChips.forEach(function(c) {
                    c.classList.remove('active');
                });
                
                // Add active class to clicked chip
                this.classList.add('active');
                
                // Update active category and filter
                activeCategory = this.getAttribute('data-category');
                filterResources();
            });
        });
        
        // Search input handler with debounce
        if (searchInput) {
            let searchTimeout;
            
            searchInput.addEventListener('input', function() {
                clearTimeout(searchTimeout);
                
                searchTimeout = setTimeout(function() {
                    searchTerm = searchInput.value.trim();
                    filterResources();
                }, 200); // 200ms debounce
            });
            
            // Also handle Enter key
            searchInput.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    clearTimeout(searchTimeout);
                    searchTerm = searchInput.value.trim();
                    filterResources();
                }
            });
            
            // Clear search on Escape
            searchInput.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    searchInput.value = '';
                    searchTerm = '';
                    filterResources();
                    searchInput.blur();
                }
            });
        }
    }

    // Initialize on DOM ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', function() {
            initScrollReveal();
            initCalendar();
            initEventsCarousel();
            initMobileMenu();
            initPaginationScroll();
            initResourcesFilter();
        });
    } else {
        initScrollReveal();
        initCalendar();
        initEventsCarousel();
        initMobileMenu();
        initPaginationScroll();
        initResourcesFilter();
    }

})();

