const isFutureDateTime = (datetime) => {
    let inputDate = new Date(datetime)
    let now = new Date()
    if (isNaN(inputDate.getTime())) return false
    
    return inputDate > now
}