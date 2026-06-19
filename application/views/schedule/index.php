<script>
    const urlParams = new URLSearchParams(window.location.search)
    const search = urlParams.get('search')
    const view_type = urlParams.get('view_type')
    const open_status = urlParams.get('open_status')
    const max_distance = urlParams.get('max_distance')
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