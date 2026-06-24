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

    .pin-item {
        display: flex;
        align-items: center;
        gap: var(--spaceSM);
        padding: var(--spaceSM) var(--spaceMD);
        cursor: pointer;
        transition: background 0.13s;
        border-bottom: 1px solid rgba(0,0,0,0.04);
    }
    .pin-item:last-child {
        border-bottom: none;
    }
    .pin-item:hover, .pin-item.active {
        background: var(--primaryLightColor);
    }
    .pin-item-img {
        width: 44px;
        height: 44px;
        border-radius: var(--roundedMini);
        object-fit: cover;
        flex-shrink: 0;
        background: var(--silverLightColor);
    }
    .pin-item-img-placeholder {
        width: 44px;
        height: 44px;
        border-radius: var(--roundedMini);
        background: var(--primaryLightColor);
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        color: var(--primaryColor);
        font-size: var(--textXLG);
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
    <input type="text" id="pinNameInput" class="form-control form-control-custom" placeholder="Search marker..." autocomplete="off"/>
    <div class="pin-dropdown" id="pinDropdown">
        <div class="pin-dropdown-inner" id="pinDropdownInner"></div>
    </div>
</div>

<script>
    let debounceTimer
    let searchRequest = null
    const $input = $('#pinNameInput')
    const $dropdown = $('#pinDropdown')
    const $inner = $('#pinDropdownInner')
    const showDropdown = () => $dropdown.addClass('show')
    const hideDropdown = () => $dropdown.removeClass('show')
    const renderLoading = () => `<div class="pin-dropdown-state"><i class="fa-solid fa-magnifying-glass"></i>Searching...</div>`
    const renderEmpty = () => `<div class="pin-dropdown-state"><i class="fa-solid fa-triangle-exclamation"></i>No markers found</div>`

    const renderItem = (dt) => {
        const imgEl = dt.pin_img ? `<img class="pin-item-img" src="${dt.pin_img}" alt="${dt.pin_name}" loading="lazy"/>` : `<div class="pin-item-img-placeholder"><i class="fa-solid fa-building"></i></div>`

        return `
            <div class="pin-item" data-name="${dt.pin_name}">
                ${imgEl}
                <div class="d-flex flex-column">
                    <div class="text-sm fw-bold mb-1">${dt.pin_name}</div>
                    <div class="d-flex gap-1">
                        <span class="tag bg-info">${dt.pin_category}</span>
                        <span class="tag bg-success"><i class="fa-solid fa-location-dot"></i> ${dt.pin_final_address}</span>
                    </div>
                </div>
            </div>`
    }

    const fetchPinSearch = query => {
        if (searchRequest) searchRequest.abort()

        $inner.html(renderLoading())
        showDropdown()

        searchRequest = $.ajax({
            url: '/api/v1/pin/search',
            method: 'GET',
            data: {
                pin_name: query
            },
            headers: {
                Authorization: `Bearer ${tokenKey}`
            },
            success: response => {
                const data = response.data ?? []

                if (data.length === 0) {
                    $inner.html(renderEmpty())
                    return
                }

                $inner.html(data.map(renderItem).join(''))
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

    const search = query => {
        clearTimeout(debounceTimer)

        debounceTimer = setTimeout(() => {
            fetchPinSearch(query)
        }, 750)
    }

    $input.on('focus input', function () {
        const keyword = $(this).val().trim()
        search(keyword)
    })

    $inner.on('click', '.pin-item', function () {
        $('#pinNameInput').val($(this).data('name'))
        $('#pinNameInput').data('id', $(this).data('id'))
        hideDropdown()
    })

    $(document).on('click', function (e) {
        if (!$(e.target).closest('.pin-autocomplete-wrapper').length) hideDropdown()
    })

    $input.on('keydown', function (e) {
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
                $input.val($active.data('name'))
                hideDropdown()
            }
        } else if (e.key === 'Escape') {
            hideDropdown()
        }
    })

    const scrollToActive = () => {
        const $active = $inner.find('.pin-item.active')
        if ($active.length) {
            const itemTop = $active[0].offsetTop
            const itemH   = $active[0].offsetHeight
            const innerST = $inner[0].scrollTop
            const innerH  = $inner[0].clientHeight

            if (itemTop < innerST) {
                $inner[0].scrollTop = itemTop
            } else if (itemTop + itemH > innerST + innerH) {
                $inner[0].scrollTop = itemTop + itemH - innerH
            }
        }
    }
</script>