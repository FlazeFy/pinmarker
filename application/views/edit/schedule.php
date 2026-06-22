<div class="card">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h2 class="card-title mb-1">Schedule</h2>
            <p class="card-sub text-secondary">Add the place's operational hours so we can provide better trip advice.</p>
        </div>
        <button class="btn btn-success py-1 px-3" id="save-schedule-button"><i class="fa-solid fa-floppy-disk"></i> Save Schedule</button>
    </div>
    <div id="schedule-holder" class="row"></div>
</div>

<script>
    const days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday']

    const buildScheduleRows = () => {
        let html = ''
        days.forEach(day => {
            const dayLower = day.toLowerCase()
            html += `
                <div class="col-md-6 col-sm-12 mb-2">
                    <div class="container p-3 pb-1 pt-2 mb-2 schedule-item" data-day="${dayLower}">
                        <label>${day}</label>
                        <div class="d-flex gap-3 align-items-center text-sm">
                            <div>
                                <label class="fw-normal text-secondary">From</label>
                                <input type="time" name="${dayLower}_start" class="form-control py-1" value="00:00" style="max-width: 100px;">
                            </div>
                            <div>
                                <label class="fw-normal text-secondary">To</label>
                                <input type="time" name="${dayLower}_end" class="form-control py-1" value="00:00" style="min-width: 70px;">
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
                </div>
            `
        })
        return html
    }

    const fetchSchedule = (id) => {
        const holder = '#schedule-holder'
        const submitButton = '#save-schedule-button'

        $(holder).html(`
            <div class="col-md-6 col-sm-12 mb-2">
                <div class="skeleton-loading schedule-skeleton"></div>
            </div>
            <div class="col-md-6 col-sm-12 mb-2">
                <div class="skeleton-loading schedule-skeleton"></div>
            </div>
        `)
        $(submitButton).attr('disabled',true)

        $.ajax({
            url: `/api/v1/schedule/${id}`,
            method: 'GET',
            headers: {
                'Authorization': `Bearer ${tokenKey}`
            },
            success: (response) => {
                const rows = response.data || []

                $(holder).html(buildScheduleRows())

                if (rows.length > 0) {
                    const dayMap = {
                        MON: 'monday',
                        TUE: 'tuesday',
                        WED: 'wednesday',
                        THU: 'thursday',
                        FRI: 'friday',
                        SAT: 'saturday',
                        SUN: 'sunday'
                    }

                    rows.forEach(dt => {
                        const day = dayMap[dt.schedule_day]
                        const dayLabel = day.charAt(0).toUpperCase() + day.slice(1)

                        $(`.schedule-item[data-day="${day}"]`).addClass(`border border-${dt.is_closed === '1' ? 'danger' : 'primary'} border-2`)
                        $(`input[name="${day}_start"]`).val(dt.schedule_hour_start)
                        $(`input[name="${day}_end"]`).val(dt.schedule_hour_end)

                        if (dt.is_closed === '1') {
                            $(`.dayoff-check[data-day="${dayLabel}"]`).prop('checked', true).trigger('change')
                        } else if (dt.is_24_h === '1') {
                            $(`.full-day-check[data-day="${dayLabel}"]`).prop('checked', true).trigger('change')
                        }
                    })
                }
            },
            error: () => {
                $(holder).html(buildScheduleRows())

            },
            complete: () => {
                $(submitButton).attr('disabled',false)
            }
        })
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

        fetchSchedule(id)
    })

    const postEditSchedule = (id) => {
        Swal.showLoading()

        const data = {
            schedules: buildSchedulePayload()
        }

        $.ajax({
            url: `/api/v1/schedule/edit/${id}`,
            method: 'POST',
            data: data,
            headers: {
                'Authorization': `Bearer ${tokenKey}`
            },
            success: (response) => {
                Swal.hideLoading()
                Swal.fire({
                    title: 'Success!',
                    text: response.message,
                    icon: 'success'
                }).then(() => {
                    fetchSchedule(id)
                })
            },
            error: (response) => {
                Swal.hideLoading()

                const message = response.responseJSON?.message ?? 'Something went wrong.'

                if (response.status === 400) {
                    Swal.fire({
                        title: 'Failed!',
                        html: message,
                        icon: 'warning'
                    })
                } else {
                    unknownErrorSwal()
                }
            }
        })
    }

    $(document).ready(function () {
        $(document).on('click', '#save-schedule-button', function (e) {
            e.preventDefault()
            postEditSchedule(id)
        })
    })
</script>

<style>
    .skeleton-loading.schedule-skeleton{
        width: 100%;
        height: 90px;
    }
</style>