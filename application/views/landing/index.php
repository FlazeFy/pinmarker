<section class="section" id="welcome-section">
    <?php $this->load->view("landing/welcome"); ?>
</section>

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
