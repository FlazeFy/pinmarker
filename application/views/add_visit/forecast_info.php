
<div class="card d-none" id="forecast-info-section">
    <h2 class="card-title">Forecast Info</h2>
    <table id="operationalTable" class="table table-bordered border-primary text-center align-middle">
        <thead>
            <tr>
                <th>Time</th>
                <th>Weather</th>
                <th>Temperature</th>
                <th>Humidity</th>
                <th>Wind</th>
                <th>AQI</th>
            </tr>
        </thead>
        <tbody id="forecast-holder"></tbody>
    </table>
</div>

<script>
    let forecastRequest = null

    const renderForecastLoading = () => `
        <div class="col-12">
            <div class="skeleton-loading" style="height:180px;border-radius:var(--roundedMD);"></div>
        </div>
    `

    const weatherIcon = code => {
        if (code === 0) return '☀️'
        if ([1, 2].includes(code)) return '🌤️'
        if (code === 3) return '☁️'
        if ([45, 48].includes(code)) return '🌫️'
        if ([51, 53, 55, 61, 63, 65, 80, 81, 82].includes(code)) return '🌧️'
        if ([71, 73, 75, 85, 86].includes(code)) return '❄️'
        if ([95, 96, 99].includes(code)) return '⛈️'
        return '🌡️'
    }

    const renderForecast = data => {
        let html = ''

        data.weather.forEach((dt, index) => {
            const air = data.air[index] ?? {}
            const date = new Date(dt.datetime)
            const hour = date.toLocaleTimeString([], {
                hour: '2-digit',
                minute: '2-digit',
                hour12: false
            })

            const temp = getTemperature(dt.temperature)
            const humidity = getHumidity(dt.humidity)
            const wind = getWind(dt.wind_speed)
            const aqi = air.aqi != null ? getAqi(air.aqi) : { label: '-', color: 'bg-secondary' }

            html += `
                <tr>
                    <th>${hour}</th>
                    <th>
                        <span style="font-size:1.2rem">${weatherIcon(dt.code)}</span>
                    </th>
                    <th>
                        <span class="fw-bold text-primary text-xl">${dt.temperature}°C</span>
                    </th>
                    <th>
                        <span class="tag ${humidity.color}">Humidity : ${dt.humidity}%</span>
                    </th>
                    <th>
                        <span class="tag ${wind.color}">Wind : ${dt.wind_speed} km/h</span>
                    </th>
                    <th>
                        <span class="tag ${aqi.color}">AQI : ${air.aqi ?? '-'}</span>
                    </th>
                </tr>
            `
        })

        $('#forecast-holder').html(html)
    }

    const fetchForecast = (pinId, lat, lng, date) => {
        if (forecastRequest) forecastRequest.abort()

        $('#forecast-holder').html(renderForecastLoading())
        forecastRequest = $.ajax({
            url: '/api/v1/location/forecast',
            method: 'GET',
            headers: {
                Authorization: `Bearer ${tokenKey}`
            },
            data: {
                pin_id: pinId,
                lat,
                long: lng,
                start_date: date,
                end_date: date
            },
            success: response => {
                renderForecast(response.data)
                $('#forecast-info-section').removeClass('d-none')
            },
            error: response => {
                if (response.status === 401) return failedAuth()
                if (response.statusText === 'abort') return

                $('#forecast-holder').html(`
                    <div class="col-12">
                        <div class="alert alert-danger mb-0">
                            Failed to fetch weather forecast.
                        </div>
                    </div>
                `)
            }
        })
    }

    $(document).on('change', '#visit_date', function() {
        const date = $(this).val()
        const id = $inputPinName.data('id')
        const lat = $inputPinName.data('lat')
        const lng = $inputPinName.data('lng')

        if (date && id && lat && lng) fetchForecast(id, lat, lng, date)
    })
</script>