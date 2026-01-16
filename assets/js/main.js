/**
 * IBEW Local 53 Main JavaScript
 * Minimal JS for calendar functionality
 */

(function() {
    'use strict';

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

        // Function to filter events by selected date
        function filterEventsByDate(filterDate) {
            const eventItems = document.querySelectorAll('.event-list-item');
            const monthSections = document.querySelectorAll('.events-month-section');
            const noEventsMessage = document.getElementById('no-events-scheduled');
            const resetLink = document.getElementById('events-filter-reset');
            const todayStr = formatDate(currentDate.getFullYear(), currentDate.getMonth(), currentDate.getDate());
            let visibleCount = 0;

            if (filterDate === null) {
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
            } else {
                // Filter events by date
                eventItems.forEach(item => {
                    const eventDate = item.getAttribute('data-event-date');
                    if (eventDate === filterDate) {
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

                // Show reset link only if selected date is not today
                if (resetLink) {
                    if (filterDate !== todayStr) {
                        resetLink.style.display = 'block';
                    } else {
                        resetLink.style.display = 'none';
                    }
                }
            }
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
        const resetLink = document.getElementById('reset-filter-link');
        if (resetLink) {
            resetLink.addEventListener('click', function(e) {
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
                
                // Show all events
                filterEventsByDate(null);
            });
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

    // Initialize on DOM ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', function() {
            initCalendar();
            initEventsCarousel();
            initMobileMenu();
        });
    } else {
        initCalendar();
        initEventsCarousel();
        initMobileMenu();
    }

})();

