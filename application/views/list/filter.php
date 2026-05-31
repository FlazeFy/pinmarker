<div class="filter-bar mb-3">
    <div class="d-flex align-items-center gap-3 flex-wrap">
        <div class="d-flex align-items-center gap-2">
            <span class="filter-label">Sort By:</span>
            <select class="sort-select" id="sortSelect">
                <option value="created_at-desc" selected>Date Created (Newest)</option>
                <option value="created_at-asc">Date Created (Oldest)</option>
                <option value="total_visit-desc">Most Visited</option>
                <option value="total_visit-asc">Least Visited</option>
                <option value="pin_name-asc">A-Z Alphabetical</option>
                <option value="pin_name-desc">Z-A Alphabetical</option>
            </select>
        </div>
        <div class="filter-divider"></div>
        <div class="d-flex align-items-center gap-2">
            <span class="filter-label">Item per Page:</span>
            <select class="sort-select" id="itemPerPageSelect">
                <option value="14" selected>14</option>
                <option value="30">30</option>
                <option value="50">50</option>
                <option value="100">100</option>
            </select>
        </div>
        <div class="filter-divider"></div>
        <div class="d-flex gap-2 flex-wrap" id="categoryTag"></div>
    </div>
</div>

<script>
    let fetchPinDebounce = null
    const getSelectedCategories = () => {
        return $('.filter-chip.active').map(function(){
            return $(this).data('filter')
        }).get().join(',')
    }

    $(document).on('change', '#sortSelect,#itemPerPageSelect', function(){
        fetchPin(1)
    })

    $(document).on('click', '.filter-chip', function(){
        $(this).toggleClass('active')
        clearTimeout(fetchPinDebounce)
        fetchPinDebounce = setTimeout(() => fetchPin(1), 2000)
    })
</script>

<style>
    .filter-bar {
        background: #fff;
        border: 1px solid #e7e8ec;
        border-radius: var(--roundedXLG);
        padding: 12px var(--spaceXMD);
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: var(--spaceMD);
        flex-wrap: wrap;
    }
    .filter-label {
        font-size: var(--textXSM);
        font-weight: 700;
        color: #777587;
        text-transform: uppercase;
        letter-spacing: .04em;
        white-space: nowrap;
    }
    .sort-select {
        background: transparent;
        border: none;
        outline: none;
        font-size: var(--textXMD);
        font-weight: 700;
        color: var(--primaryColor);
        font-family: inherit;
        cursor: pointer;
    }
    .filter-divider {
        width: 1px;
        height: 20px;
        background: #e1e2e6;
        flex-shrink: 0;
    }
    .filter-chip {
        padding: 4px 12px;
        background: #f2f3f7;
        border-radius: var(--roundedJumbo);
        border: 1.5px solid transparent;
        font-size: var(--textXSM);
        font-weight: 600;
        color: #464555;
        cursor: pointer;
        transition: all .2s;
        user-select: none;
    }
    .filter-chip:hover, .filter-chip.active {
        background: #e2dfff;
        color: var(--primaryColor);
        border-color: #c3c0ff;
    }
</style>