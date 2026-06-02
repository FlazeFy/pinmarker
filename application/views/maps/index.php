<div class="d-flex" style="min-height:100vh; background:var(--containerColor);">
    <?php $this->load->view("others/left_bar"); ?>
    <div class="main-wrap">
        <?php $this->load->view("others/top_bar"); ?>
        <div class="content">
            <?php $this->load->view("maps/header"); ?>
            <div class="maps-bento">
                <div class="map-area">
                    <?php $this->load->view('maps/maps_board'); ?>
                </div>                
                <div class="map-panel">
                    <?php $this->load->view('maps/filter_category'); ?>
                    <?php $this->load->view('maps/places_nearby'); ?>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .maps-bento {
        display: grid;
        grid-template-columns: 1fr 375px;
        gap: var(--spaceXMD);
        height: 700px;
    }
    .map-panel {
        display: flex;
        flex-direction: column;
        gap: var(--spaceMD);
        overflow: hidden;
    }
    .panel-card {
        background: #fff;
        border-radius: var(--roundedXLG);
        border: 1.5px solid #e7e8ec;
        padding: var(--spaceXMD);
        box-shadow: 0 4px 20px rgba(99,91,255,.04);
        display: flex;
        flex-direction: column;
        overflow-y: auto;
        min-height: 0;
    }
    .panel-title {
        font-size: var(--textMD);
        font-weight: 800;
        color: var(--secondaryColor);
        margin: 0;
    }
</style>