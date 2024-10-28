<link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.0/main.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.0/main.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<?php include('header.php');?>
<style>
    .modal-xl {
        max-width: 98%; 
        height: 90vh; 
    }

    #calendar-container {
        height: 80vh; 
        max-width: 100%; 
        overflow: hidden;
    }

    #calendar {
        height: 100%; 
        width: 100%; 
    }
</style>

<script>
    $(document).ready(function() {
        var calendar;

        $('#calendarModal').on('shown.bs.modal', function () {
            var calendarEl = document.getElementById('calendar');

            calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay'
                },
                events: function(fetchInfo, successCallback, failureCallback) {
                    $.ajax({
                        url: 'fetch_reservations.php',
                        type: 'GET',
                        dataType: 'json',
                        success: function(data) {
                            successCallback(data);
                        },
                        error: function() {
                            failureCallback();
                        }
                    });
                },
                eventContent: function(arg) {
                    // Create the badge element
                    let badge = `<span>${arg.event.title}</span>`;
                    return { html: badge }; // Return the HTML to display
                },
                height: '100%',
                contentHeight: 'auto'
            });

            calendar.render();
        });

        $('#calendarModal').on('hidden.bs.modal', function () {
            if (calendar) {
                calendar.destroy();
            }
        });
    });
</script>

