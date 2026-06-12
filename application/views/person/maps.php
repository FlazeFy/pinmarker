<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css"/>

<div class="map-area">
    <div class="map-img-wrap">
        <div id="map-board"></div>
    </div>
    <?php $this->load->view("person/maps_toolbar") ?>
</div>

<style>
    .map-area{
        position: relative;
        height: 70vh;
        border-radius: var(--roundedJumbo);
        overflow: hidden;
        border: 1.5px solid #e7e8ec;
        background: #1a1a2e;
        box-shadow: 0 8px 32px rgba(0,0,0,.08);
    }
    .map-img-wrap{
        position: absolute;
        inset: 0;
    }
    #map-board{
        width: 100%;
        height: 100%;
    }
    .leaflet-control-zoom{
        border-radius: var(--roundedMD)!important;
        overflow: hidden;
    }
    .leaflet-popup-content-wrapper{
        border-radius: var(--roundedMD);
    }
</style>

<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

<script>
    const map = L.map('map-board', {
        zoomControl: false
    })

    let markers = []
    let allPins = []
    let bounds = []
    let pinSearchDebounce = null
    let isInitialMapLoad = true

    L.control.zoom({ position: 'bottomright' }).addTo(map)

    let tileLayer = L.tileLayer(
        'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',
        { attribution: '&copy; OpenStreetMap contributors' }
    ).addTo(map)

    const clearMarkers = () => {
        markers.forEach(marker => map.removeLayer(marker))
        markers = []
        bounds = []
    }

    const populateCategoryFilter = (pins) => {
        const uniqueCategories = [...new Set(
            pins
                .map(pin => pin.pin_category)
                .filter(cat => cat)
        )]

        let optionEl = `<option value="all" selected>All Marker</option>`

        uniqueCategories.forEach(cat => {
            optionEl += `<option value="${cat}">${cat}</option>`
        })

        $('#view-category-select').html(optionEl)
        
        // Select category in maps toolbar after repopulate option
        if (category) $('#view-category-select').val(category)
    }

    const renderVisitedLocation = () => {
        clearMarkers()

        const search = $('#pin-name-search').val().toLowerCase().trim()
        const viewType = $('#view-type-select').val()
        const category = $('#view-category-select').val()
        let filteredPins = [...allPins]

        if (viewType === 'favorite') {
            filteredPins = filteredPins.filter(pin => parseInt(pin.is_favorite) === 1)
        }

        if (category !== 'all') {
            filteredPins = filteredPins.filter(pin => pin.pin_category === category)
        }

        if (search !== '') {
            filteredPins = filteredPins.filter(pin => 
                pin.pin_name.toLowerCase().includes(search)
            )
        }

        filteredPins.forEach(dt => {
            bounds.push([dt.pin_lat, dt.pin_long])
            const marker = L.marker([dt.pin_lat, dt.pin_long]).addTo(map)

            marker.bindPopup(`
                <div class="place-popup">
                    <h3>${dt.pin_name}</h3>
                    <hr>
                    <div class="popup-info">
                        <div>
                            <span>Category</span>
                            <h5>${dt.pin_category}</h5>
                        </div>
                    </div>
                    <div class="popup-info mt-2">
                        <div>
                            <span>Total Visit</span>
                            <h5>${dt.total_visit}</h5>
                        </div>
                    </div>
                    <div class="popup-info mt-2">
                        <div>
                            <span>Last Visit</span>
                            <h5>${dt.last_visit_at}</h5>
                        </div>
                    </div>
                    <div class="popup-info mt-2">
                        <div>
                            <span>Favorite</span>
                            <h5>${parseInt(dt.is_favorite) === 1 ? 'Yes' : 'No'}</h5>
                        </div>
                    </div>
                </div>
            `)

            markers.push(marker)
        })

        if (isInitialMapLoad) {
            isInitialMapLoad = false
            return
        }

        if (bounds.length > 1) {
            map.fitBounds(bounds, { padding: [50, 50] })
        } else if (bounds.length === 1) {
            map.setView(bounds[0], 14)
        }
    }

    const renderVisitedPin = (pins) => {
        allPins = pins || []

        if (allPins.length > 0) {
            const mostVisitedPin = [...allPins]
                .sort((a, b) => b.total_visit - a.total_visit)[0]

            map.setView(
                [mostVisitedPin.pin_lat, mostVisitedPin.pin_long],
                13
            )
        } else {
            map.setView([userLat, userLng], 11)
        }

        populateCategoryFilter(allPins)
        isInitialMapLoad = true
        renderVisitedLocation()

        setTimeout(() => {
            map.invalidateSize()
        }, 300)
    }

    $('.map-type').on('click', function () {
        const type = $(this).data('type')
        switchMapType(type, map, tileLayer)
        addUrlParam('map_type', type)
    })

    $(document).on('change', '#view-type-select, #view-category-select', function() {
        renderVisitedLocation()
        addUrlParam($(this).attr('id') === 'view-type-select' ? 'view_type' : 'category', $(this).val())

        if ($(this).attr('id') === 'view-category-select') category = $(this).val()
    })

    $(document).on('input', '#pin-name-search', function() {
        clearTimeout(pinSearchDebounce)

        pinSearchDebounce = setTimeout(() => {
            renderVisitedLocation()
            addUrlParam('search', $(this).val())
        }, debouncerTime)
    })

    // Validate query param
    const validateParams = () => {
        if (search !== "") $('#pin-name-search').val(search)
        !['favorite','all'].includes(view_type) ? removeUrlParam('view_type') : $('#view-type-select').val(view_type)
        !['default','satellite','terrain'].includes(map_type) ? removeUrlParam('map_type') : switchMapType(map_type, map, tileLayer)
    }
    
    $(document).ready(function () {
        validateParams()
    })
</script>