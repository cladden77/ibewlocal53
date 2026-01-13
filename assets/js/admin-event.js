/**
 * Admin Event Date Picker Enhancements
 * Makes the event date picker more user-friendly
 */
(function($) {
    'use strict';

    $(document).ready(function() {
        // Only run on event post edit screens
        if ($('#event_start_datetime').length === 0) {
            return;
        }

        var $startDate = $('#event_start_datetime');
        var $endDate = $('#event_end_datetime');
        var $allDayCheckbox = $('#event_all_day');

        // Make the date field more prominent
        $startDate.css({
            'font-size': '14px',
            'padding': '8px',
            'border': '2px solid #2271b1',
            'border-radius': '4px'
        });

        // Add helper text and styling
        $startDate.closest('td').append(
            '<p class="description" style="margin-top: 8px; color: #2271b1; font-weight: 500;">⚠️ This field is required. Events without a date will not appear on the calendar.</p>'
        );

        // Auto-fill end date when start date changes (if end date is empty)
        $startDate.on('change', function() {
            var startValue = $(this).val();
            if (startValue && !$endDate.val()) {
                // Set end date to 2 hours after start date by default
                var startDate = new Date(startValue);
                startDate.setHours(startDate.getHours() + 2);
                var endValue = startDate.toISOString().slice(0, 16);
                $endDate.val(endValue);
            }
        });

        // Handle all-day checkbox
        $allDayCheckbox.on('change', function() {
            if ($(this).is(':checked')) {
                // For all-day events, set time to 00:00
                var startValue = $startDate.val();
                if (startValue) {
                    var dateOnly = startValue.split('T')[0];
                    $startDate.val(dateOnly + 'T00:00');
                }
                if ($endDate.val()) {
                    var endDateOnly = $endDate.val().split('T')[0];
                    $endDate.val(endDateOnly + 'T23:59');
                }
            }
        });

        // Validate that start date is set before allowing publish
        var $publishButton = $('#publish, #save-post');
        var originalClick = null;

        $publishButton.on('click', function(e) {
            if (!$startDate.val()) {
                e.preventDefault();
                e.stopPropagation();
                alert('⚠️ Please set an Event Start Date & Time before publishing. Events without a date will not appear on the calendar.');
                $startDate.focus();
                return false;
            }
        });

        // Add visual indicator when date is set
        $startDate.on('change blur', function() {
            if ($(this).val()) {
                $(this).css('border-color', '#00a32a');
            } else {
                $(this).css('border-color', '#d63638');
            }
        });

        // Trigger on page load if value exists
        if ($startDate.val()) {
            $startDate.trigger('change');
        }
    });
})(jQuery);
