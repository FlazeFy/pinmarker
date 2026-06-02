<div class="filter-bar mb-3">
    <div class="d-flex align-items-center gap-3 flex-wrap">
        <div class="d-flex align-items-center gap-2">
            <span class="filter-label">Sort By:</span>
            <select class="sort-select" id="sortSelect">
                <option value="created_at-desc" selected>Date Created (Newest)</option>
                <option value="created_at-asc">Date Created (Oldest)</option>
                <option value="total_visit-desc">Most Visited</option>
                <option value="total_visit-asc">Least Visited</option>
                <option value="list_name-asc">A-Z Alphabetical</option>
                <option value="list_name-desc">Z-A Alphabetical</option>
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
        <div class="d-flex align-items-center gap-2">
            <span class="filter-label">With Companion:</span>
            <select class="sort-select" id="withCompanionSelect">
                <option value="0" selected>Off</option>
                <option value="1">On</option>
            </select>
        </div>
        <div class="filter-divider"></div>
        <div class="d-flex gap-2 flex-wrap" id="categoryTag"></div>
    </div>
</div>

<script>
    let fetchGlobalListDebounce = null

    $(document).on('change', '#sortSelect,#itemPerPageSelect,#withCompanionSelect', function(){
        fetchGlobalList(1)
    })
    $(document).on('change', '#withCompanionSelect', function(){
        $('#filterCompanionSection').toggleClass('d-none')
    })

    $(document).on('click', '.filter-chip', function(){
        $(this).toggleClass('active')
        clearTimeout(fetchGlobalListDebounce)
        fetchGlobalListDebounce = setTimeout(() => fetchGlobalList(1), debouncerTime)
    })
</script>