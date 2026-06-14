<div class="map-card d-none d-md-block" id="map-places-card">
    <div class="map-section-header" data-target="global-place-holder">
        <span style="font-weight:700; font-size:var(--textMD);">
            <span class="text-primary fw-bold" id="total-other-location-text"></span> Global Place
        </span>
        <i class="fa-solid fa-angle-down map-section-chevron"></i>
    </div>
    <div id="global-place-holder" class="map-place-holder"></div>
    <div class="map-section-header mt-2" data-target="pinmarker-place-holder">
        <span style="font-weight:700; font-size:var(--textMD);">
            <span class="text-primary fw-bold" id="total-pinmarker-text"></span> Pinmarker Place
        </span>
        <i class="fa-solid fa-angle-down map-section-chevron"></i>
    </div>
    <div id="pinmarker-place-holder" class="map-place-holder"></div>
</div>

<script>
    $(document).on('click', '.map-section-header', function () {
        const targetId = $(this).data('target')
        const $target = $('#' + targetId)
        const isOpen = $target.hasClass('open')

        $('.map-place-holder').removeClass('open').css('max-height', '0')
        $('.map-section-chevron').removeClass('rotated')

        if (!isOpen) {
            $target.addClass('open').css('max-height', '40vh')
            $(this).find('.map-section-chevron').addClass('rotated')
        }
    })

    $(document).on('click', '#map-places-toggle-btn', function () {
        const $card = $('#map-places-card')
        const isHidden = $card.hasClass('d-none')

        if (isHidden) {
            $card.removeClass('d-none')
            $(this).removeClass('btn-outline-primary').addClass('btn-primary')
        } else {
            $card.addClass('d-none')
            $(this).removeClass('btn-primary').addClass('btn-outline-primary')
        }
    })
</script>