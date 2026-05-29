const addContentCoor = (coor, targetLatId, targetLongId) => {
    coor = coor.toJSON()
    $(`#${targetLatId}`).val(coor['lat'])
    $(`#${targetLongId}`).val(coor['lng'])
}

$(document).on('click', '.btn-direction', function () {
    const lat = $(this).data('lat')
    const lng = $(this).data('long')

    const gmapsUrl = `https://www.google.com/maps/dir/My+Location/${lat},${lng}`

    window.open(gmapsUrl, '_blank')
})