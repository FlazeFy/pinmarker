<style>
    .pin-autocomplete-wrapper {
        position: relative;
    }
    .pin-dropdown {
        display: none;
        position: absolute;
        top: calc(100% + var(--spaceSM));
        left: 0;
        width: 100%;
        background: #fff;
        padding-inline: var(--spaceXXSM);
        border-radius: var(--roundedMD);
        box-shadow: 0 8px 32px rgba(30, 33, 36, 0.14), 0 1.5px 6px rgba(99, 91, 255, 0.07);
        border: 1.5px solid rgba(99, 91, 255, 0.13);
        z-index: 1050;
        overflow: hidden;
        animation: dropFade 0.25s ease;
    }
    .pin-dropdown.show {
        display: block;
    }
    .pin-dropdown-inner {
        overflow-y: auto;
        max-height: 40vh;
        padding: var(--spaceXXSM) 0;
    }
    @keyframes dropFade {
        from { opacity: 0; transform: translateY(-6px); }
        to   { opacity: 1; transform: translateY(0); }
    }
    @media (max-width: 575.98px) {
        .pin-dropdown-inner {
            max-height: 60vh;
        }
    }

    .pin-dropdown-inner::-webkit-scrollbar {
        width: var(--spaceMini);
    }
    .pin-dropdown-inner::-webkit-scrollbar-thumb {
        background: var(--primaryColor);
        border-radius: 99px;
    }

    .pin-dropdown-state {
        padding: var(--spaceLG) var(--spaceMD);
        text-align: center;
        color: var(--greyColor);
        font-size: var(--textMD);
    }
    .pin-dropdown-state i {
        font-size: var(--textJumbo);
        display: block;
        margin-bottom: var(--spaceMini);
    }
</style>

<label class="form-label-custom">Pin Name</label>
<div class="pin-autocomplete-wrapper">
    <input type="text" id="pin_name" class="form-control form-control-custom" placeholder="Search marker..." autocomplete="off"/>
    <div class="pin-dropdown" id="pin-dropdown">
        <div class="pin-dropdown-inner" id="pin-dropdown-inner"></div>
    </div>
</div>

<script>
    let debounceTimer
    let searchRequest = null
    const $inputPinName = $('#pin_name')
    const $dropdown = $('#pin-dropdown')
    const $inner = $('#pin-dropdown-inner')
    const showDropdown = () => $dropdown.addClass('show')
    const hideDropdown = () => $dropdown.removeClass('show')
    const renderLoading = () => `<div class="pin-dropdown-state"><i class="fa-solid fa-magnifying-glass"></i>Searching...</div>`
    const renderEmpty = () => `<div class="pin-dropdown-state"><i class="fa-solid fa-triangle-exclamation"></i>No markers found</div>`

    const fetchPinSearch = (search) => {
        if (searchRequest) searchRequest.abort()

        $inner.html(renderLoading())
        showDropdown()

        searchRequest = $.ajax({
            url: '/api/v1/pin/search',
            method: 'GET',
            data: { search },
            headers: {
                Authorization: `Bearer ${tokenKey}`
            },
            success: response => {
                const data = response.data ?? []

                if (data.length === 0) {
                    $inner.html(renderEmpty())
                    return
                }

                $inner.html(data.map(renderMarkerItemShort).join(''))
            },
            error: response => {
                if (response.status === 401) return failedAuth()
                if (response.statusText === 'abort') return

                $inner.html(`
                    <div class="pin-dropdown-state text-danger">
                        <i class="fa-solid fa-triangle-exclamation"></i>
                        Failed fetch marker
                    </div>
                `)
            }
        })
    }

    const search = (search) => {
        clearTimeout(debounceTimer)
        debounceTimer = setTimeout(() => fetchPinSearch(search), 750)
    }

    $inputPinName.on('focus input', function () {
        const keyword = $(this).val().trim()
        search(keyword)
    })

    $(document).ready(function () {
        $inner.on('click', '.pin-item', function () {
            const name = $(this).data('name')
            const id = $(this).data('id')
            const lat = $(this).data('lat')
            const lng = $(this).data('lng')

            $inputPinName.val(name)
            $inputPinName.data('id', id)
            $inputPinName.data('lat', lat)
            $inputPinName.data('lng', lng)
            hideDropdown()
            routingControl = showPinOnMap(lat, lng, map, routingControl, userLat, userLng)

            const date = $('#visit_date').val().trim()
            if (date) fetchForecast(id, lat, lng, date)
        })
    })

    $(document).on('click', function (e) {
        if (!$(e.target).closest('.pin-autocomplete-wrapper').length) hideDropdown()
    })

    $inputPinName.on('keydown', function (e) {
        const $items = $inner.find('.pin-item')
        const $active = $items.filter('.active')

        if (e.key === 'ArrowDown') {
            e.preventDefault()
            $active.length === 0 ? $items.first().addClass('active') : $active.removeClass('active').next('.pin-item').addClass('active')
            scrollToActive()
        } else if (e.key === 'ArrowUp') {
            e.preventDefault()
            if ($active.length) $active.removeClass('active').prev('.pin-item').addClass('active')
            scrollToActive()
        } else if (e.key === 'Enter') {
            if ($active.length) {
                e.preventDefault()
                const name = $active.data('name')
                const id = $active.data('id')
                const lat = $active.data('lat')
                const lng = $active.data('lng')
                
                $inputPinName.val(name)
                $inputPinName.data('id', id)
                hideDropdown()
                routingControl = showPinOnMap(lat, lng, map, routingControl, userLat, userLng)

                const date = $('#visit_date').val().trim()
                if (date) fetchForecast(id, lat, lng, date)
            }
        } else if (e.key === 'Escape') {
            hideDropdown()
        }
    })

    const scrollToActive = () => {
        const $active = $inner.find('.pin-item.active')
        if ($active.length) {
            const itemTop = $active[0].offsetTop
            const itemH = $active[0].offsetHeight
            const innerST = $inner[0].scrollTop
            const innerH = $inner[0].clientHeight

            if (itemTop < innerST) {
                $inner[0].scrollTop = itemTop
            } else if (itemTop + itemH > innerST + innerH) {
                $inner[0].scrollTop = itemTop + itemH - innerH
            }
        }
    }
</script>