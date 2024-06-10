function getUUID() {
    return ([1e7]+-1e3+-4e3+-8e3+-1e11).replace(/[018]/g, c =>
        (c ^ crypto.getRandomValues(new Uint8Array(1))[0] & 15 >> c / 4).toString(16)
    );
}

function countDatetimeStrInterval(startTime, endTime) {
    const formattedStartTime = startTime.replace(" ", "T") + "Z"
    const formattedEndTime = endTime.replace(" ", "T") + "Z"
    const diffInMs = new Date(formattedEndTime) - new Date(formattedStartTime)

    if (Math.abs(diffInMs) < 1000) {
        return `${Math.round(diffInMs)} ms`
    } else {
        const diffInSeconds = Math.floor(diffInMs / 1000);
        return `${diffInSeconds} s`
    }
}

function calculateDistance(coord1, coord2) {
    const earthRadius = 6371000 //fixed
    const [lat1, lon1] = coord1.split(',')
    const [lat2, lon2] = coord2.split(',')

    const latRad1 = toRadians(parseFloat(lat1))
    const lonRad1 = toRadians(parseFloat(lon1))
    const latRad2 = toRadians(parseFloat(lat2))
    const lonRad2 = toRadians(parseFloat(lon2))

    const latDiff = latRad2 - latRad1
    const lonDiff = lonRad2 - lonRad1

    const a = Math.sin(latDiff / 2) * Math.sin(latDiff / 2) + Math.cos(latRad1) * Math.cos(latRad2) * Math.sin(lonDiff / 2) * Math.sin(lonDiff / 2);
    const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a))
    let res = earthRadius * c

    let unit = 'm'
    if(res > 1000){
        unit = 'km'
        res = res / 1000
    }

    return res.toFixed(2)+' '+unit
}

function toRadians(degrees) {
    return degrees * (Math.PI / 180)
}
