<div class="analytics-card">
    <div class="position-relative" style="z-index:1;">
        <div class="analytics-eyebrow">
            <i class="fa-solid fa-chart-line"></i> Places Nearby Visit Goals
        </div>
        <div class="analytics-num">30%</div>
        <div class="analytics-badge">
            <i class="fa-solid fa-circle-info"></i> in 15 Km Radius
        </div>
    </div>
    <div class="analytics-blur-1"></div>
    <div class="analytics-blur-2"></div>
</div>

<style>
    .analytics-card {
        background: var(--primaryColor);
        border-radius: var(--roundedXLG);
        padding: var(--spaceXMD);
        position: relative;
        overflow: hidden;
        color: #fff;
        flex-shrink: 0;
        box-shadow: 0 8px 32px rgba(99,91,255,.3);
    }
    .analytics-eyebrow {
        font-size: 10px;
        font-weight: 700;
        letter-spacing: .05em;
        opacity: .8;
        display: flex;
        align-items: center;
        gap: 6px;
        margin-bottom: 4px;
    }
    .analytics-num {
        font-size: 40px;
        font-weight: 800;
        line-height: 1;
        margin-bottom: var(--spaceMD);
    }
    .analytics-badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        background: rgba(255,255,255,.15);
        border-radius: var(--roundedJumbo);
        padding: 4px 12px;
        font-size: 10px;
        font-weight: 700;
    }
    .analytics-blur-1 {
        position: absolute;
        right: -20px;
        bottom: -20px;
        width: 100px;
        height: 100px;
        background: rgba(255,255,255,.1);
        border-radius: 50%;
        filter: blur(30px);
        pointer-events: none;
    }
    .analytics-blur-2 {
        position: absolute;
        left: -10px;
        top: -10px;
        width: 70px;
        height: 70px;
        background: rgba(255,255,255,.1);
        border-radius: 50%;
        filter: blur(24px);
        pointer-events: none;
    }
</style>