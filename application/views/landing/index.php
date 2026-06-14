<script>
    const urlParams = new URLSearchParams(window.location.search)
    const isExplorer = urlParams.get('explorer') === 'true'
    const search = urlParams.get('search')
    const map_type = urlParams.get('map_type')
    const max_distance = urlParams.get('max_distance')
    const limit = urlParams.get('limit')
</script>

<section class="section" id="welcome-section">
    <?php $this->load->view("landing/welcome"); ?>
</section>

<?php $this->load->view("landing/maps_filter"); ?>
<?php $this->load->view("landing/maps_weather"); ?>
<section class="section-sm text-center pt-0" id="map-section">
    <?php $this->load->view("landing/maps_board"); ?>
</section>

<section class="section" id="feature-section">
    <?php $this->load->view("landing/feature"); ?>
</section>

<section class="section" id="platform-section">
    <?php $this->load->view("landing/platform"); ?>
</section>

<section class="section" id="faq-section">
    <?php $this->load->view("landing/faq"); ?>
</section>

<style>
    .skeleton-loading.map-item-skeleton{
        width: 100%;
        margin-bottom: var(--spaceSM);
        height: 45px;
    }
</style>

<script>
    $(document).ready(function () {
        const pmObserver = new IntersectionObserver((entries) => {
            entries.forEach((e) => {
                if (e.isIntersecting) $(e.target).addClass('visible')
            })
        }, { threshold: 0.5 })

        $('section, .feature-card, .accordion-item, .platform-item').each(function () {
            $(this).addClass('reveal')
            pmObserver.observe(this)
        })
    })
</script>
