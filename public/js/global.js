const getUUID = () => {
    return ([1e7]+-1e3+-4e3+-8e3+-1e11).replace(/[018]/g, c =>
        (c ^ crypto.getRandomValues(new Uint8Array(1))[0] & 15 >> c / 4).toString(16)
    );
}

const countDatetimeStrInterval = (startTime, endTime) => {
    const formattedStartTime = startTime.replace(" ", "T") + "Z"
    const formattedEndTime = endTime.replace(" ", "T") + "Z"
    const diffInMs = new Date(formattedEndTime) - new Date(formattedStartTime)

    return Math.round(diffInMs) // in second
}

const calculateDistance = (coord1, coord2) => {
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

    return res // in m
}

const distanceUnit = (val) => {
    let unit = 'm'
    let res
    if(val > 1000){
        unit = 'km'
        res = res / 1000
    } 

    return `${val.toFixed(2)} ${unit}`
}

const timeUnit = (val) => {
    if (val < 1000) {
        return `${val} ms`
    } else {
        const diffInSeconds = Math.floor(val / 1000);
        return `${diffInSeconds}s`
    }
}

const toRadians = (degrees) => {
    return degrees * (Math.PI / 180)
}

const validateInput = (type, id, max, min) => {
    if(type == "text"){
        const check = $(`#${id}`).val()
        const check_len = check.trim().length
    
        if(check && check_len > 0 && check_len <= max && check_len >= min){
            return true
        } else {
            return false
        }
    }
}

const getDateToContext = (datetime, type) => {
    if(datetime){
        const result = new Date(datetime);

        if (type == "full") {
            const now = new Date(Date.now());
            const yesterday = new Date();
            const tomorrow = new Date();
            yesterday.setDate(yesterday.getDate() - 1);
            tomorrow.setDate(tomorrow.getDate() + 1);
            
            if (result.toDateString() === now.toDateString()) {
                return ` ${messages('today_at')} ${("0" + result.getHours()).slice(-2)}:${("0" + result.getMinutes()).slice(-2)}`;
            } else if (result.toDateString() === yesterday.toDateString()) {
                return ` ${messages('yesterday_at')} ${("0" + result.getHours()).slice(-2)}:${("0" + result.getMinutes()).slice(-2)}`;
            } else if (result.toDateString() === tomorrow.toDateString()) {
                return ` ${messages('tommorow_at')} ${("0" + result.getHours()).slice(-2)}:${("0" + result.getMinutes()).slice(-2)}`;
            } else {
                return ` ${result.getFullYear()}/${(result.getMonth() + 1)}/${("0" + result.getDate()).slice(-2)} ${("0" + result.getHours()).slice(-2)}:${("0" + result.getMinutes()).slice(-2)}`;
            }
        } else if (type == "24h" || type == "12h") {
            return `${("0" + result.getHours()).slice(-2)}:${("0" + result.getMinutes()).slice(-2)}`;
        } else if (type == "datetime") {
            return ` ${result.getFullYear()}/${(result.getMonth() + 1)}/${("0" + result.getDate()).slice(-2)} ${("0" + result.getHours()).slice(-2)}:${("0" + result.getMinutes()).slice(-2)}`;
        } else if (type == "date") {
            return `${result.getFullYear()}-${("0" + (result.getMonth() + 1)).slice(-2)}-${("0" + result.getDate()).slice(-2)}`;
        } else if (type == "calendar" || type == "calendar_server") {
            const result = new Date(datetime)

            if(type == "calendar"){
                const offsetHours = getUTCHourOffset()
                result.setUTCHours(result.getUTCHours() + offsetHours)
            }
        
            return `${result.getFullYear()}-${("0" + (result.getMonth() + 1)).slice(-2)}-${("0" + result.getDate()).slice(-2)} ${("0" + result.getHours()).slice(-2)}:${("0" + result.getMinutes()).slice(-2)}:00`;
        }        
    } else {
        return "-";
    }
}


const getUTCHourOffset = () => {
    const offsetMi = new Date().getTimezoneOffset();
    const offsetHr = -offsetMi / 60;
    return offsetHr;
}

const messageCopy = (val) => {
    navigator.clipboard.writeText(val)
    .then(function() {
        Swal.fire({
            title: 'Success!',
            text: 'Data has been added to clipboard',
            icon: 'success'
        });
    })
    .catch(function(err) {
        Swal.fire({ 
            title: 'Failed!', 
            text: err, 
            icon: 'error' 
        });
    });
}

const stripHtmlTag = (val) => {
    return val.replace(/<\/?[^>]+>/gi, '')
}

const ucEachWord = (val) => {
    const arr = val.split(" ");

    for (var i = 0; i < arr.length; i++) {
        arr[i] = arr[i].charAt(0).toUpperCase() + arr[i].slice(1);
    }
    
    const res = arr.join(" ");

    return res;
}

const ucFirst = (val) => {
    if (typeof val !== 'string' || val.length === 0) {
        var res = val;
    } else {
        var res = val.charAt(0).toUpperCase() + val.slice(1);
    }

    return res;
}