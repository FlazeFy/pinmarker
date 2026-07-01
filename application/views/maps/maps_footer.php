<div class="region-bar">
    <div class="region-focus">
        <i class="fa-solid fa-compass fa-lg" style="color:var(--primaryColor);"></i>
        <div>
            <div class="region-label">Current Region Focus</div>
            <div class="region-desc">Loading map data...</div>
        </div>
    </div>
    <a class="btn btn-danger align-self-center text-nowrap text-sm py-2 d-none exit-route-button"><i class="fa-solid fa-xmark"></i> Exit Route</a>
</div>

<style>
    .region-bar{
        position: absolute;
        bottom: var(--spaceMD);
        left: var(--spaceMD);
        right: 20px;
        z-index: 1000;
        display: flex;
        gap: var(--spaceMD);
    }
    .region-focus{
        background: rgba(255,255,255,.9);
        backdrop-filter: blur(12px);
        border-radius: var(--roundedLG);
        padding: var(--spaceMD) var(--spaceXMD);
        display: inline-flex;
        gap: var(--spaceMD);
        align-items: center;
        border: 1px solid rgba(199,196,216,.25);
        box-shadow: 0 8px 24px rgba(0,0,0,.12);
        max-width: 420px;
    }
    .region-label{
        font-size: var(--textXSM);
        font-weight: 700;
        color: var(--primaryColor);
        margin-bottom: 2px;
    }
    .region-desc{
        font-size: var(--textXSM);
        color: #464555;
        line-height: 1.5;
    }
</style>