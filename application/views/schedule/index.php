<script>
    const urlParams = new URLSearchParams(window.location.search)
    const search = urlParams.get('search')
    let category = urlParams.get('category')
</script>

<div class="d-flex" style="min-height:100vh; background:var(--containerColor);">
    <?php $this->load->view("others/left_bar"); ?>
    <div class="main-wrap">
        <?php $this->load->view("others/top_bar"); ?>
        <div class="content">
            <?php $this->load->view("schedule/header"); ?>
            <?php $this->load->view("schedule/schedule_day_time"); ?>
        </div>
    </div>
</div>