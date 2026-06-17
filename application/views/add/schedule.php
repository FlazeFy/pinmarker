<div class="card">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h2 class="card-title mb-1">Schedule</h2>
            <p class="card-sub text-secondary">Add the place's operational hours so we can provide better trip advice.</p>
        </div>
        <div class="form-check form-switch mb-0">
            <input class="form-check-input" type="checkbox" id="schedule-toggle" style="width: 40px;">
        </div>
    </div>
    <div id="schedule-holder"></div>
</div>

<script>
    const days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday']

    const buildScheduleRows = () => {
        let html = ''
        days.forEach(day => {
            html += `
                <div class="container p-3 pb-1 pt-2 mb-2">
                    <label>${day}</label>
                    <div class="d-flex gap-3 align-items-center text-sm">
                        <div>
                            <label class="fw-normal text-secondary">From</label>
                            <input type="time" name="${day.toLowerCase()}_start" class="form-control py-1" value="00:00" style="max-width: 100px;">
                        </div>
                        <div>
                            <label class="fw-normal text-secondary">To</label>
                            <input type="time" name="${day.toLowerCase()}_end" class="form-control py-1" value="00:00" style="min-width: 70px;">
                        </div>
                        <div class="d-flex flex-column">
                            <div class="form-check mb-0">
                                <input class="form-check-input dayoff-check" type="checkbox" value="" data-day="${day}">
                                <label class="form-check-label text-secondary">Closed</label>
                            </div>
                            <div class="form-check mb-0">
                                <input class="form-check-input full-day-check" type="checkbox" value="" data-day="${day}">
                                <label class="form-check-label text-secondary">24H</label>
                            </div>
                        </div>
                    </div>
                </div>
            `
        })
        return html
    }

    $(document).ready(function () {
        $(document).on('change', '.dayoff-check', function () {
            const day = $(this).data('day').toLowerCase()
            const isChecked = $(this).is(':checked')

            // Uncheck 24h if closed checked
            $(`.full-day-check[data-day="${$(this).data('day')}"]`).prop('checked', false)

            $(`input[name="${day}_start"]`).val('00:00').prop('disabled', isChecked)
            $(`input[name="${day}_end"]`).val('00:00').prop('disabled', isChecked)
        })

        $(document).on('change', '.full-day-check', function () {
            const day = $(this).data('day').toLowerCase()
            const isChecked = $(this).is(':checked')

            // Uncheck closed if 24h checked
            $(`.dayoff-check[data-day="${$(this).data('day')}"]`).prop('checked', false)

            $(`input[name="${day}_start"]`).val('00:00').prop('disabled', isChecked)
            $(`input[name="${day}_end"]`).val('23:59').prop('disabled', isChecked)
        })

        $(document).on('change', `input[type="time"]`, function () {
            const name = $(this).attr('name')
            const day = name.replace('_start', '').replace('_end', '')

            const start = $(`input[name="${day}_start"]`).val()
            const end = $(`input[name="${day}_end"]`).val()

            // Auto check 24h if time is 00:00 - 23:59
            const is24h = start === '00:00' && end === '23:59'
            $(`.full-day-check[data-day="${day.charAt(0).toUpperCase() + day.slice(1)}"]`).prop('checked', is24h)
        })

        $(document).on('change', '#schedule-toggle', function () {
            const isActive = $(this).is(':checked')
            $('#schedule-holder').toggle(isActive)

            isActive ? $('#schedule-holder').html(buildScheduleRows()) : $('#schedule-holder').empty()
        })
    })
</script>