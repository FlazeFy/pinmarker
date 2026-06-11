<div class="map-toolbar">
    <div class="map-type-wrap map-search-wrap">
        <h6>Search By Name</h6>
        <input class="map-search-field" id="pin-name-search" type="text" placeholder="Search by name..."/>
    </div>
    <div class="map-type-wrap">
        <h6>View Type</h6>
        <select class="map-range-select" id="view-type-select">
            <option value="all" selected>All Marker</option>
            <option value="favorite">Favorited</option>
            <option value="visited">Visited</option>
            <option value="unvisited">Unvisited</option>
        </select>
    </div>
    <div class="map-type-wrap">
        <h6>Markers to Show</h6>
        <select class="map-range-select" id="marker-per-fetch-select">
            <option value="20">20 Places</option>
            <option value="50" selected>50 Places</option>
            <option value="150">150 Places</option>
            <option value="all">All</option>
        </select>
    </div>
    <div class="map-type-wrap">
        <h6>Max Range</h6>
        <select class="map-range-select" id="max-range-select">
            <option value="5" selected>5 Km</option>
            <option value="15">15 Km</option>
            <option value="30">30 Km</option>
            <option value="100">100 Km</option>
            <option value="all">All</option>
        </select>
    </div>
    <div class="map-type-wrap">
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
    .map-search-wrap{
        flex: 1;
        min-width: 250px;
    }
    .map-type-wrap:not(.map-search-wrap){
        flex-shrink: 0;
    }
    .map-type-wrap {
        background: #fff;
        padding: var(--spaceXSM);
        border-radius: var(--roundedLG);
        box-shadow: 0 4px 16px rgba(0,0,0,.1);
    }
    .map-type-wrap h6 {
        margin-left: var(--spaceMini);
        font-size: var(--textSM);
        font-weight: 600;
        margin-bottom: var(--spaceMini);
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
    .map-range-select:focus{
        border-color: var(--primaryColor);
    }
    @media(max-width: 768px){
        .map-toolbar{
            top: 12px;
            right: 12px;
            left: 12px;
            flex-direction: column;
            gap: 8px;
        }
        .map-type-group{
            overflow-x: auto;
        }
        .map-range-select{
            width: 100%;
        }
    }
</style>