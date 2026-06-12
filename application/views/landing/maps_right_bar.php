<div class="map-card">
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
</script>