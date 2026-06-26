const isFutureDateTime = (datetime) => {
    let inputDate = new Date(datetime)
    let now = new Date()
    if (isNaN(inputDate.getTime())) return false
    
    return inputDate > now
}

const cleanListPerson = (target) =>  {
    const val = $(target).val().trim()
    
    if(val !== ''){
        const visit_with = val.split(/,\s+and\s+|,\s+/).map(dt => dt.toLowerCase()).filter(dt => dt !== '')
        const uniqueVisitWith = [...new Set(visit_with)]

        let cleanVisitWith = ''
        if(uniqueVisitWith.length > 1){
            cleanVisitWith = uniqueVisitWith.slice(0, -1).map(dt => ucEachWord(dt)).join(', ') + `, and ${ucEachWord(uniqueVisitWith[uniqueVisitWith.length - 1])}`
        } else if(uniqueVisitWith.length === 1){
            cleanVisitWith = ucEachWord(uniqueVisitWith[0])
        }

        $(target).val(cleanVisitWith)
    }
}


$(document).on('blur', '.visit-with', function() {
    cleanListPerson('.visit-with')
})