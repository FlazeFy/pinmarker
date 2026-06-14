<div class="modal fade" id="mapFiltersModal" tabindex="-1" aria-labelledby="mapFiltersModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="mapFiltersModalLabel">Map Filters</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-start">
                <div class="row mb-3">
                    <div class="col-6">
                        <label class="map-modal-label">Markers to Show</label>
                        <select class="map-range-select w-100" id="marker-per-fetch-select-modal">
                            <option value="10">10 Places</option>
                            <option value="20">20 Places</option>
                            <option value="50" selected>50 Places</option>
                            <option value="150">150 Places</option>
                            <option value="all">All</option>
                        </select>
                    </div>
                    <div class="col-6">
                        <label class="map-modal-label">Max Range</label>
                        <select class="map-range-select w-100" id="max-range-select-modal">
                            <option value="3">3 Km</option>
                            <option value="5" selected>5 Km</option>
                            <option value="15">15 Km</option>
                            <option value="30">30 Km</option>
                            <option value="100">100 Km</option>
                            <option value="all">All</option>
                        </select>
                    </div>
                </div>
                <div class="mb-1">
                    <label class="map-modal-label">Map Type</label>
                    <div class="map-type-group">
                        <button class="map-type active" data-type="default">Default</button>
                        <button class="map-type" data-type="satellite">Satellite</button>
                        <button class="map-type" data-type="terrain">Terrain</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>