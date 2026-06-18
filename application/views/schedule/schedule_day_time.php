<div class="card">
    <h2 class="card-title mb-4">Day / Time Operational Hours</h2>
    <?php $this->load->view("schedule/filter"); ?>
    <table id="operationalTable" class="table table-bordered border-primary text-center"></table>
</div>

<script>
    const generateOperationalTable = () => {
        const days = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun']

        const now = new Date()
        const currentHour = now.getHours()
        const currentDay = (now.getDay() + 6) % 7

        const $table = $('#operationalTable')

        let thead = `
            <thead>
                <tr>
                    <th style="width: 160px">Day / Time</th>
        `

        days.forEach((day, index) => {
            const active = index === currentDay ? 'table-primary' : ''
            thead += `<th class="${active}">${day}</th>`
        })

        thead += `
                </tr>
            </thead>
        `

        let tbody = '<tbody>'

        for (let hour = 0; hour < 24; hour++) {
            const start = String(hour).padStart(2, '0')
            const end = `${start}:59`
            const label = `${start}:00 - ${end}`

            const rowClass = hour === currentHour ? 'table-warning' : ''

            tbody += `<tr class="${rowClass}">`
            tbody += `<th>${label}</th>`

            days.forEach((_, dayIndex) => {
                let cellClass = ''

                if (dayIndex === currentDay)
                    cellClass += ' table-primary'

                if (hour === currentHour)
                    cellClass += ' table-warning'

                if (dayIndex === currentDay && hour === currentHour)
                    cellClass = 'table-success'

                tbody += `<td class="${cellClass.trim()}">...</td>`
            })

            tbody += '</tr>'
        }

        tbody += '</tbody>'

        $table.html(thead + tbody)
    }

    $(document).ready(function () {
        generateOperationalTable()
    })
</script>