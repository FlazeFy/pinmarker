const isFutureDateTime = (datetime) => {
    let inputDate = new Date(datetime)
    let now = new Date()
    if (isNaN(inputDate.getTime())) return false
    
    return inputDate > now
}

const cleanListPerson = (target) =>  {
    const val = $(target).val().trim()
    
    if(val.trim() != ''){
        const visit_with = val.split(/,\s+and\s+|,\s+/).map(dt => dt.toLowerCase())
        let cleanVisitWith = ''

        if(visit_with.length > 1){
            cleanVisitWith = visit_with.slice(0, -1).map(dt => ucEachWord(dt)).join(', ') + `, and ${ucEachWord(visit_with[visit_with.length - 1])}`
        } else {
            cleanVisitWith = ucEachWord(visit_with[0])
        }

        $(target).val(cleanVisitWith)
    }
}

$(document).on('blur', '.visit-with', function() {
    cleanListPerson('.visit-with')
})