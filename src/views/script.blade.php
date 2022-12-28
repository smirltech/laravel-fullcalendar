<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.0.2/index.global.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var calendarEl = document.getElementById('calendar-{{ $id }}');
        var calendar = new FullCalendar.Calendar(calendarEl, {!! $options !!});
        calendar.render();
    });
</script>
