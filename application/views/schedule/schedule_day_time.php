<div class="card">
    <h2 class="card-title mb-4">Day / Time Operational Hours</h2>
    <?php $this->load->view("schedule/filter"); ?>
    <table id="operationalTable" class="table table-bordered border-primary text-center"></table>
</div>

<script>
    const days = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun']
    const dayKeys = ['MON', 'TUE', 'WED', 'THU', 'FRI', 'SAT', 'SUN']

    const generateSkeletonTable = () => {
        const now = new Date()
        const currentDay = (now.getDay() + 6) % 7

        let thead = `<thead><tr><th style="width:160px">Day / Time</th>`
        days.forEach((day, i) => {
            const active = i === currentDay ? 'table-primary' : ''
            thead += `<th class="${active}">${day}</th>`
        })
        thead += `</tr></thead>`

        let tbody = '<tbody>'
        for (let hour = 0; hour < 24; hour++) {
            const hStr  = String(hour).padStart(2, '0')
            const label = `${hStr}:00 - ${hStr}:59`

            tbody += `<tr><th>${label}</th>`
            days.forEach(() => {
                tbody += `<td><div class="skeleton-loading" style="height:14px;border-radius:4px;"></div></td>`
            })
            tbody += `</tr>`
        }
        tbody += '</tbody>'

        $('#operationalTable').html(thead + tbody)
    }

    const generateOperationalTable = (rows) => {
        const now = new Date()
        const currentHour = now.getHours()
        const currentDay = (now.getDay() + 6) % 7

        const byDay = {}
        dayKeys.forEach(dk => { byDay[dk] = [] })
        rows.forEach(row => {
            if (byDay[row.schedule_day] !== undefined) {
                byDay[row.schedule_day].push(row)
            }
        })

        let thead = `<thead><tr><th style="width:160px">Day / Time</th>`
        days.forEach((day, i) => {
            const active = i === currentDay ? 'table-primary' : ''
            thead += `<th class="${active}">${day}</th>`
        })
        thead += `</tr></thead>`

        let tbody = '<tbody>'
        for (let hour = 0; hour < 24; hour++) {
            const hStr = String(hour).padStart(2, '0')
            const label = `${hStr}:00 - ${hStr}:59`
            const rowClass = hour === currentHour ? 'table-warning' : ''

            tbody += `<tr class="${rowClass}"><th>${label}</th>`

            dayKeys.forEach((dk, di) => {
                let cellClass = ''

                if (di === currentDay) cellClass += ' table-primary'
                if (hour === currentHour) cellClass += ' table-warning'
                if (di === currentDay && hour === currentHour) cellClass = 'table-success'

                const activePins = byDay[dk].filter(sched => {
                    if (sched.is_closed) return true
                    if (sched.is_24_h) return true

                    const [startH] = sched.schedule_hour_start.split(':').map(Number)
                    const [endH] = sched.schedule_hour_end.split(':').map(Number)
                    return hour >= startH && hour < endH
                })

                const content = activePins.length
                    ? activePins.map(dt => `
                        <div class="d-inline-block position-relative">
                            ${dt.is_favorite ? `<i class='fa-solid fa-heart text-danger position-absolute' style="top: -5px; right: 2px;"></i>` : ''}
                            <span class="tag pointer bg-${dt.is_closed ? 'danger' : 'success'} tag-pin-name" data-pin-id="${dt.id}">${dt.pin_name}</span>
                        </div>
                        `).join(' ')
                    : ''

                tbody += `<td class="${cellClass.trim()}">${content}</td>`
            })

            tbody += '</tr>'
        }
        tbody += '</tbody>'

        const $table = $('#operationalTable')
        $table.html(thead + tbody)

        const $currentRow = $table.find('tbody tr').eq(currentHour)
        if ($currentRow.length) {
            $table.closest('.card').animate({
                scrollTop: $currentRow.offset().top - $table.offset().top - 100
            }, 300)
        }
    }

    const fetchSchedule = () => {
        generateSkeletonTable()
        $('#schedule-error').addClass('d-none')

        $.ajax({
            url: '/api/v1/schedule',
            method: 'GET',
            data: {
                
            },
            headers: {
                'Authorization': `Bearer ${tokenKey}`
            },
            success: (response) => {
                const rows = response.data || []
                generateOperationalTable(rows)
            },
            error: () => {
                $('#operationalTable').html('')
                $('#schedule-error').removeClass('d-none')
            }
        })
    }

    $(document).ready(() => {
        fetchSchedule()
    })
</script>