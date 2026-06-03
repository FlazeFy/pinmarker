<div class="card">
    <h3>Total Hourly Visit</h3>
    <div class="accordion accordion text-start">
        <div id="hourlyVisitAccordion" style='overflow-y:auto; max-height: 770px;'></div>
    </div>
</div>

<script>
    const getHourlyVisit = (data) => {
        const holder = '#hourlyVisitAccordion'
        $(holder).empty()

        data.forEach((dt, idx) => {
            const hr = dt.context
            const total = dt.total

            let listPinEl = ''
            dt.visit_list.split(", ").forEach(dt => listPinEl += `<div class="card py-1 px-2 d-inline">${dt}</div>`)

            $(holder).append(`
                <div class="accordion-item accordion-item mb-2">
                    <h2 class="accordion-header">
                        <button class="accordion-button accordion-btn" type="button" data-bs-toggle="collapse" data-bs-target="#collapse${hr}">
                            <div class="d-flex flex-column">
                                <span>${hr} ${hr > 12 ? 'PM' : 'AM'} - ${hr+1} ${hr+1 > 12 ? 'PM' : 'AM'}</span>
                                <span class="text-muted mt-1">${total} Visit${total > 1 ? 's':''}</span>
                            </div>
                        </button>
                    </h2>
                    <div id="collapse${hr}" class="accordion-collapse collapse ${idx === 0 ? 'show' :''}" data-bs-parent="#hourlyVisitAccordion">
                        <div class="accordion-body accordion-body d-flex gap-2 flex-wrap">
                            ${listPinEl}
                        </div>
                    </div>
                </div>
            `)
        })
    }
</script>