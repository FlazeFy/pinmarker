<div class="modal fade" id="coordinatePickerModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Select Location</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-0">
                <div id="picker-map" style="height: 420px; width: 100%;"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="confirm-coordinate-btn">Confirm Location</button>
            </div>
        </div>
    </div>
</div>

<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css"/>
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

<script>
    let pickerMap = null
    let pickerMarker = null
    let pickedLat = null
    let pickedLng = null

    const initPickerMap = () => {
        if (pickerMap) return

        pickerMap = L.map('picker-map', {
            zoomControl: false
        }).setView([userLat ?? -2.5, userLng ?? 118], userLat ? 13 : 5)

        L.control.zoom({ position: 'bottomright' }).addTo(pickerMap)

        L.tileLayer(
            'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',
            { attribution: '&copy; OpenStreetMap contributors' }
        ).addTo(pickerMap)

        if (userLat && userLng) {
            pickerMarker = L.marker([userLat, userLng]).addTo(pickerMap)
            pickedLat = userLat
            pickedLng = userLng
        }

        pickerMap.on('click', (e) => {
            if (pickerMarker) {
                pickerMap.removeLayer(pickerMarker)
            }

            pickerMarker = L.marker([e.latlng.lat, e.latlng.lng]).addTo(pickerMap)
            pickedLat = e.latlng.lat
            pickedLng = e.latlng.lng
        })
    }

    $(document).on('click', '#select-search-coordinate', function () {
        $('#coordinatePickerModal').modal('show')
        $('#coordinatePickerModal').one('shown.bs.modal', function () {
            initPickerMap()
            pickerMap.invalidateSize()
        })
    })

    $(document).on('click', '#confirm-coordinate-btn', function () {
        if (pickedLat && pickedLng) $('#pin-coordinate').val(`${pickedLat},${pickedLng}`)

        $('#coordinatePickerModal').modal('hide')
        fetchSchedule()
    })
</script>