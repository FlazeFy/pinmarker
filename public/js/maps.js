const addContentCoor = (coor, targetLatId, targetLongId) => {
    coor = coor.toJSON()
    $(`#${targetLatId}`).val(coor['lat'])
    $(`#${targetLongId}`).val(coor['lng'])
}