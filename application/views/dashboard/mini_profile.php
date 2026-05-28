<div class="card text-start mb-4">
    <h3 class="card-title">Good Morning, jalanjalan!</h3>
    <p class="card-sub mt-1">You've visited 4 new places this month. Keep exploring!</p>
    <div class="progress-block mt-3 d-flex justify-content-between align-items-center">
        <div class="d-flex flex-column">
            <span style="font-size:var(--textSM); font-weight:700;">Today's Weather</span>
            <span style="font-size:var(--textSM)"><i class="fa-solid fa-location-dot"></i> South Jakarta</span>
        </div>
        <div class="d-flex gap-1 mt-1">
            <span class="tag bg-secondary py-2 px-3">🌤️ Cloudy</span>
            <span class="tag bg-secondary py-2 px-3"><i class="fa-solid fa-temperature-high"></i> 32</span>
            <span class="tag bg-secondary py-2 px-3"><i class="fa-solid fa-droplet"></i> 90%</span>
        </div>
    </div>
    <div class="progress-block mt-3">
        <div class="d-flex justify-content-between mb-1">
            <span style="font-size:var(--textSM); font-weight:700;">Explorer Progress</span>
            <span style="font-size:var(--textSM); font-weight:700; color:var(--primaryColor);">75%</span>
        </div>
        <div class="progress-bar">
            <div class="progress-fill" style="width:75%;"></div>
        </div>
    </div>
</div>

<script>
    const fetchWeather = () => {
        $('.progress-block').first().find('.d-flex.gap-1').html(`
            <div class="weather-skeleton"></div>
            <div class="weather-skeleton"></div>
            <div class="weather-skeleton"></div>
        `)

        $.ajax({
            url: '/api/v1/location/weather?lat=-6.226341056289639&long=106.82254165458681',
            method: 'GET',
            success: (response) => {
                const weather = response.data.weather

                const weatherEmoji = {
                    0: '☀️ Clear',
                    1: '🌤️ Cloudy',
                    2: '☁️ Overcast',
                    3: '🌧️ Rain',
                    45: '🌫️ Fog',
                    61: '🌦️ Drizzle'
                }

                const weatherLabel = weatherEmoji[weather.code] || '🌍 Weather'

                $('.progress-block').first().find('.d-flex.gap-1').html(`
                    <span class="tag bg-secondary py-2 px-3">
                        ${weatherLabel}
                    </span>
                    <span class="tag bg-secondary py-2 px-3">
                        <i class="fa-solid fa-temperature-high"></i> 
                        ${weather.temperature}${weather.unit}
                    </span>
                    <span class="tag bg-secondary py-2 px-3">
                        <i class="fa-solid fa-droplet"></i> 
                        ${weather.humidity}%
                    </span>
                `)
            },
            error: () => {
                $('.progress-block').first().find('.d-flex.gap-1').html(`
                    <span class="tag bg-danger py-2 px-3">
                        Failed fetch weather
                    </span>
                `)
            }
        })
    }
    $(document).ready(() => {
        fetchWeather()
    })
</script>

<style>
    .weather-skeleton{
        width: 60px;
        height: 24px;
        border-radius: var(--roundedJumbo);
        background: linear-gradient(90deg, #e5e7eb 25%, #f3f4f6 50%, #e5e7eb 75%);
        background-size: 200% 100%;
        animation: skeleton-loading 1.2s infinite linear;
    }

    @keyframes skeleton-loading{
        0%{
            background-position:200% 0;
        }
        100%{
            background-position:-200% 0;
        }
    }
</style>