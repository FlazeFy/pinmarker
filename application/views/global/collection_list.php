<div class="row g-4" id="collectionsGrid">
    <div class="col-xl-4 col-md-6">
        <div class="col-card col-card--purple h-100">
            <div class="d-flex justify-content-between align-items-start mb-3">
                <span class="pin-badge pin-badge--purple">
                    <i class="fa-solid fa-thumbtack"></i> 1 Marker
                </span>
                <button class="icon-btn-sm"><i class="fa-solid fa-ellipsis-vertical"></i></button>
            </div>
            <h3 class="col-title">bolu</h3>
            <p class="col-desc fst-italic text-secondary">No Description available</p>
            <div class="col-meta">
                <div class="meta-row">
                    <span class="meta-label"><i class="fa-solid fa-list"></i> List Marker</span>
                    <span class="meta-text">Tji Laki 9</span>
                </div>
                <div class="meta-row">
                    <span class="meta-label">Created At</span>
                    <span class="meta-text">2025-12-09 • @jalanjalan</span>
                </div>
            </div>
            <div class="col-footer">
                <a href="/GlobalListController/detail/bolu" class="col-detail-btn">
                    <i class="fa-regular fa-eye"></i> See Detail
                </a>
                <button class="col-share-btn"><i class="fa-solid fa-share-nodes"></i></button>
            </div>
        </div>
    </div>
</div>

<style>
    .col-card {
        background: #fff;
        border-radius: var(--roundedXLG);
        border: 1.5px solid #e7e8ec;
        padding: var(--spaceXLG);
        display: flex;
        flex-direction: column;
        box-shadow: 0 4px 20px rgba(99,91,255,.04);
        transition: transform .25s ease,
                    box-shadow .25s ease,
                    border-color .25s ease;
    }
    .col-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 20px 40px rgba(99,91,255,.1);
    }
    .col-card--purple:hover {
        border-color: rgba(99,91,255,.3);
    }
    .col-card--orange:hover {
        border-color: rgba(138,75,0,.25);
    }
    .pin-badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 4px 12px;
        border-radius: var(--roundedJumbo);
        font-size: 10px;
        font-weight: 700;
        letter-spacing: .06em;
        text-transform: uppercase;
    }
    .pin-badge--purple {
        background: rgba(99,91,255,.07);
        color: var(--primaryColor);
    }
    .pin-badge--orange {
        background: rgba(138,75,0,.08);
        color: #8a4b00;
    }
    .icon-btn-sm {
        background: none;
        border: none;
        color: #777587;
        font-size: 16px;
        cursor: pointer;
        padding: 4px 6px;
        border-radius: var(--roundedMini);
        transition: all .2s;
        line-height: 1;
    }
    .icon-btn-sm:hover {
        background: #f2f3f7;
        color: var(--primaryColor);
    }
    .col-title {
        font-size: var(--textXLG);
        font-weight: 800;
        color: var(--secondaryColor);
        margin-bottom: 6px;
        transition: color .2s;
        text-transform: capitalize;
    }
    .col-card:hover .col-title {
        color: var(--primaryColor);
    }
    .col-desc {
        font-size: var(--textXMD);
        color: #777587;
        margin-bottom: var(--spaceLG);
        flex-grow: 0;
        line-height: 1.5;
    }
    .col-meta {
        display: flex;
        flex-direction: column;
        gap: var(--spaceMD);
        margin-bottom: var(--spaceXLG);
        flex: 1;
    }
    .meta-row {
        display: flex;
        flex-direction: column;
        gap: 4px;
    }
    .meta-label {
        font-size: 10px;
        font-weight: 700;
        letter-spacing: .05em;
        text-transform: uppercase;
        color: #777587;
        display: flex;
        align-items: center;
        gap: 5px;
    }
    .meta-text {
        font-size: var(--textXMD);
        color: var(--secondaryColor);
        line-height: 1.5;
    }
    .clamp {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    .marker-chip {
        background: #f2f3f7;
        color: #464555;
        font-size: 11px;
        font-weight: 600;
        padding: 2px 8px;
        border-radius: 4px;
        display: inline-block;
    }
    .col-footer {
        display: flex;
        gap: var(--spaceMD);
        border-top: 1px solid #f2f3f7;
        padding-top: var(--spaceXMD);
        margin-top: auto;
    }
    .col-detail-btn {
        flex: 1;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        background: #e2e1f1;
        color: #636371;
        border-radius: var(--roundedMD);
        padding: 10px;
        font-size: var(--textXSM);
        font-weight: 700;
        text-decoration: none;
        transition: all .2s;
    }
    .col-detail-btn:hover {
        background: var(--primaryColor);
        color: #fff;
    }
    .col-share-btn {
        width: 42px;
        height: 42px;
        flex-shrink: 0;
        background: #e7e8ec;
        color: #464555;
        border: none;
        border-radius: var(--roundedMD);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 14px;
        cursor: pointer;
        transition: all .2s;
    }
    .col-share-btn:hover {
        background: var(--primaryColor);
        color: #fff;
    }
</style>