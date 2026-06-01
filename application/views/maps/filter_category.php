<div class="panel-card flex-grow-1">
    <h3 class="panel-title">Category Breakdown</h3>
    <div class="d-flex flex-column gap-1 mt-3">
        <a class="cat-item cat-item--active">
            <div class="cat-icon" style="background:rgba(59,130,246,.1); color:#3b82f6;">
                <i class="fa-solid fa-utensils"></i>
            </div>
            <div class="flex-grow-1">
                <div class="cat-name">Restaurant</div>
                <div class="cat-count">155 Locations</div>
            </div>
            <i class="fa-solid fa-chevron-right cat-arrow"></i>
        </a>
    </div>
</div>

<style>
    .cat-item {
        display: flex;
        align-items: center;
        gap: var(--spaceMD);
        padding: 10px var(--spaceSM);
        border-radius: var(--roundedMD);
        text-decoration: none;
        color: inherit;
        transition: all .2s;
        cursor: pointer;
    }
    .cat-item:hover, .cat-item--active {
        background: #f2f3f7;
    }
    .cat-item--active {
        background: #e2e1f1 !important;
    }
    .cat-item:hover .cat-arrow {
        color: var(--primaryColor);
    }
    .cat-icon {
        width: 40px;
        height: 40px;
        border-radius: var(--roundedMD);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 16px;
        flex-shrink: 0;
    }
    .cat-name {
        font-size: var(--textXMD);
        font-weight: 700;
        color: var(--secondaryColor);
    }
    .cat-count {
        font-size: 10px;
        color: #777587;
    }
    .cat-arrow {
        font-size: 11px;
        color: #c7c4d8;
        transition: color .2s;
    }
</style>


<script>
    $('.cat-item').on('click', function(e) {
        e.preventDefault()
        $('.cat-item').removeClass('cat-item--active')
        $(this).addClass('cat-item--active')
    })
</script>