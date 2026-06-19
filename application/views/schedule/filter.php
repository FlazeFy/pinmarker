<div class="d-flex flex-wrap gap-4 mb-4">
    <div class="map-type-wrap map-search-wrap">
        <h6>Search By Name</h6>
        <input class="map-search-field" id="pin-name-search" type="text" placeholder="Search by name..."/>
    </div>
    <div class="map-type-wrap map-search-wrap">
        <h6>View Type</h6>
        <select class="map-range-select" id="view-type-select">
            <option value="all" selected>All Marker</option>
            <option value="favorite">Favorited</option>
            <option value="visited">Visited</option>
            <option value="unvisited">Unvisited</option>
        </select>
    </div>
    <div class="map-type-wrap map-search-wrap">
        <h6>Open Status</h6>
        <select class="map-range-select" id="open-status-select">
            <option value="all" selected>All Marker</option>
            <option value="1">Open Only </option>
        </select>
    </div>
    <div class="map-type-wrap map-search-wrap">
        <h6>Max Range</h6>
        <select class="map-range-select" id="max-range-select">
            <option value="5" selected>5 Km</option>
            <option value="15">15 Km</option>
            <option value="30">30 Km</option>
            <option value="100">100 Km</option>
            <option value="all">All</option>
        </select>
    </div>
    <div class="map-type-wrap map-search-wrap">
        <div class="d-flex justify-content-between align-items-center">
            <h6 class="mb-0">Near Location</h6>
            <a class="btn btn-primary px-2" id="select-search-coordinate"><i class="fa-solid fa-map-location-dot fa-sm"></i></a>
        </div>
        <input class="map-search-field" id="pin-coordinate" type="text" placeholder="Current location..."/>
    </div>
</div>
<?php $this->load->view('schedule/maps_select') ?>

<style>
    .map-toolbar {
        position: absolute;
        top: var(--spaceMD);
        left: var(--spaceMD);
        right: var(--spaceMD);
        z-index: 1000;
        display: flex;
        gap: var(--spaceMD);
        align-items: flex-start;
    }
    .map-type-wrap {
        background: #fff;
        padding: var(--spaceXSM);
        border-radius: var(--roundedLG);
        box-shadow: 0 4px 16px rgba(0,0,0,.1);
    }
    .map-search-wrap {
        flex: 1;
        min-width: 250px;
    }
    .map-type-wrap:not(.map-search-wrap) {
        flex: 0 0 auto;
    }
    .map-type-wrap h6 {
        margin: 0 0 var(--spaceMini) var(--spaceMini);
        font-size: var(--textSM);
        font-weight: 600;
    }
    .map-search-field, .map-range-select {
        padding: var(--spaceSM);
        border: 1px solid rgba(199,196,216,.3);
        border-radius: var(--roundedMD);
        background: rgba(255,255,255,.9);
        color: #464555;
        font-size: var(--textXSM);
        font-weight: 600;
        outline: none;
    }
    .map-search-field {
        width: 100%;
    }
    .map-range-select {
        width: auto;
        min-width: 120px;
        cursor: pointer;
    }

    .map-range-select:focus, .map-search-field:focus {
        border-color: var(--primaryColor);
    }

    @media (max-width: 768px) {
        .map-toolbar {
            top: 12px;
            right: 12px;
            left: 12px;
            flex-direction: column;
            gap: 8px;
        }

        .map-search-wrap, .map-type-wrap:not(.map-search-wrap) {
            width: 100%;
        }

        .map-range-select {
            width: 100%;
        }
    }
</style>

<script>
    let userLat = getCookie('lat')
    let userLng = getCookie('long')
    $('#pin-coordinate').val(`${userLat},${userLng}`)
</script>