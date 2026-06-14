<div class="map-toolbar">
    <div class="map-toolbar-top-row">
        <div class="map-type-wrap map-search-wrap">
            <h6>Search By Name</h6>
            <input class="map-search-field" id="pin-name-search" type="text" placeholder="Search by name..."/>
        </div>
        <div class="map-type-wrap map-modal-btn-wrap d-sm-block d-md-none flex-column gap-2">
            <button type="button" class="btn btn-primary map-filter-btn" data-bs-toggle="modal" data-bs-target="#mapFiltersModal">
                <i class="fa-solid fa-bars"></i>
            </button>
            <button type="button" class="btn btn-outline-primary map-filter-btn position-relative" id="map-places-toggle-btn">
                <i class="fa-solid fa-building"></i>
                <div class="total-marker-hint">-</div>
            </button>
        </div>
    </div>
    <div class="map-type-wrap d-none d-md-block">
        <h6>Markers to Show</h6>
        <select class="map-range-select marker-limit-select">
            <option value="10">10 Places</option>
            <option value="20">20 Places</option>
            <option value="50" selected>50 Places</option>
            <option value="150">150 Places</option>
            <option value="all">All</option>
        </select>
    </div>
    <div class="map-type-wrap d-none d-md-block">
        <h6>Max Range</h6>
        <select class="map-range-select marker-range-select">
            <option value="3">3 Km</option>
            <option value="5" selected>5 Km</option>
            <option value="15">15 Km</option>
            <option value="30">30 Km</option>
            <option value="100">100 Km</option>
            <option value="all">All</option>
        </select>
    </div>
    <div class="map-type-wrap d-none d-md-block">
        <h6>Map Type</h6>
        <div class="map-type-group">
            <button class="map-type active" data-type="default">Default</button>
            <button class="map-type" data-type="satellite">Satellite</button>
            <button class="map-type" data-type="terrain">Terrain</button>
        </div>
    </div>
</div>

<style>
    .map-toolbar {
        position: absolute;
        top: var(--spaceMD);
        left: var(--spaceMD);
        right: var(--spaceMD);
        z-index: 1000;
        display: flex;
        gap: var(--spaceMD);
        text-align: start;
        align-items: flex-start;
    }
    .map-toolbar-top-row {
        display: flex;
        gap: var(--spaceMD);
        align-items: flex-end;
        width: 100%;
    }
    .map-search-wrap {
        flex: 1;
        min-width: 0;
    }
    .map-modal-btn-wrap {
        flex-shrink: 0;
        display: flex;
        align-items: flex-end;
    }
    .map-filter-btn {
        height: 38px;
        width: 38px;
        padding: 0;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .map-type-wrap:not(.map-search-wrap):not(.map-modal-btn-wrap) {
        flex-shrink: 0;
    }
    .map-type-wrap {
        background: #fff;
        padding: var(--spaceXSM);
        border-radius: var(--roundedLG);
        box-shadow: 0 4px 16px rgba(0,0,0,.1);
    }
    .map-modal-btn-wrap {
        background: transparent;
        box-shadow: none;
        padding: 0;
    }
    .map-type-wrap h6 {
        margin-left: var(--spaceMini);
        font-size: var(--textSM);
        font-weight: 600;
        margin-bottom: var(--spaceMini);
    }
    .map-modal-label {
        display: block;
        font-size: var(--textSM);
        font-weight: 600;
        margin-bottom: var(--spaceMini);
        color: #464555;
    }
    .map-type-group {
        background: rgba(255,255,255,.9);
        backdrop-filter: blur(12px);
        border-radius: var(--roundedMD);
        padding: 4px;
        display: flex;
        gap: 2px;
        border: 1px solid rgba(199,196,216,.3);
    }
    .map-type {
        padding: 7px 16px;
        border: none;
        border-radius: var(--roundedSM);
        font-weight: 700;
        font-family: inherit;
        color: #464555;
        background: transparent;
        cursor: pointer;
        transition: all .2s;
        white-space: nowrap;
        flex: 1;
    }
    .map-type.active {
        background: var(--primaryColor);
        color: #fff;
        box-shadow: 0 3px 10px rgba(99,91,255,.3);
    }
    .map-type:hover:not(.active) {
        background: #f2f3f7;
    }
    .map-range-select {
        min-width: 120px;
    }
    .map-search-field {
        width: 100%;
    }
    .map-range-select, .map-search-field {
        padding: var(--spaceSM);
        border: 1px solid rgba(199,196,216,.3);
        border-radius: var(--roundedMD);
        background: rgba(255,255,255,.9);
        backdrop-filter: blur(12px);
        margin-bottom: 0;
        color: #464555;
        outline: none;
        cursor: pointer;
    }
    .map-search-field, .map-range-select, .map-type {
        font-size: var(--textXSM);
        font-weight: 600;
    }
    .total-marker-hint, .danger-weather-hint {
        position: absolute;
        top: -7.5px;
        right: -7.5px;
        background: var(--dangerBG);
        color: var(--whiteColor);
        font-size: var(--textSM);
        font-weight: 500;
        padding: 2px;
        border-radius: 100%;
        height: 20px;
        width: 20px;
    }
    .map-range-select:focus {
        border-color: var(--primaryColor);
    }
    @media (max-width: 767px) {
        .map-toolbar {
            top: 12px;
            right: 12px;
            left: 12px;
            flex-direction: column;
            gap: 8px;
        }
        .map-toolbar-top-row {
            gap: 8px;
            align-items: flex-end;
            width: 100%;
        }
        .map-type-group {
            overflow-x: auto;
        }
    }
</style>

<script>
    $(document).on('change', '.marker-limit-select, .marker-range-select', function () {
        addUrlParam($(this).attr('id') === 'marker-range-select' ? 'max_distance' : 'limit', $(this).val())
    })
</script>