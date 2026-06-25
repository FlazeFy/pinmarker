const getTemperature = temp => {
    if (temp <= 15) return { label: 'Cold', color: 'bg-warning' }
    if (temp <= 25) return { label: 'Cool', color: 'bg-info' }
    if (temp <= 32) return { label: 'Warm', color: 'bg-success' }
    return { label: 'Hot', color: 'bg-danger' }
}

const getHumidity = humidity => {
    if (humidity < 30) return { label: 'Dry', color: 'bg-warning' }
    if (humidity <= 60) return { label: 'Normal', color: 'bg-success' }
    return { label: 'Humid', color: 'bg-info' }
}

const getWind = wind => {
    if (wind <= 5) return { label: 'Calm', color: 'bg-success' }
    if (wind <= 20) return { label: 'Breezy', color: 'bg-warning' }
    return { label: 'Danger', color: 'bg-danger' }
}

const getAqi = aqi => {
    if (aqi <= 50) return { label: 'Good', color: 'bg-success' }
    if (aqi <= 100) return { label: 'Moderate', color: 'bg-warning' }
    if (aqi <= 150) return { label: 'Unhealthy', color: 'bg-danger' }
    return { label: 'Bad', color: 'bg-danger' }
}