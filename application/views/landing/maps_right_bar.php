<div class="map-card">
    <div class="accordion accordion-flush h-100 d-flex flex-column" id="map-card-accordion">
        <div class="accordion-item map-accordion-item border-0 bg-transparent">
            <div class="accordion-header">
                <button class="accordion-button py-2 px-0 bg-transparent shadow-none fw-bold collapsed"
                    style="font-size:var(--textMD); color:#464555;" type="button" data-bs-toggle="collapse" data-bs-target="#global-place-collapse" aria-expanded="false">
                    Global Place
                </button>
            </div>
            <div id="global-place-collapse" class="accordion-collapse collapse" data-bs-parent="#map-card-accordion">
                <div id="global-place-holder" class="map-place-holder"></div>
            </div>
        </div>
        <div class="accordion-item map-accordion-item border-0 bg-transparent flex-1 min-h-0 d-flex flex-column">
            <div class="accordion-header">
                <button class="accordion-button py-2 px-0 bg-transparent shadow-none fw-bold"
                    style="font-size:var(--textMD); color:#464555;" type="button" data-bs-toggle="collapse" data-bs-target="#pinmarker-place-collapse" aria-expanded="true">
                    Pinmarker Place
                </button>
            </div>
            <div id="pinmarker-place-collapse" class="accordion-collapse collapse show flex-1 min-h-0 d-flex flex-column" data-bs-parent="#map-card-accordion">
                <div id="pinmarker-place-holder" class="map-place-holder"></div>
            </div>
        </div>
    </div>
</div>