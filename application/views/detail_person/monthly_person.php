<form action="/DetailPersonController/filter_year/<?= $raw_name ?>" method="POST">
    <select class="form-select-custom" aria-label="Default select example" style="width:80px;" name="year_filter" id="year_filter" onchange="this.form.submit()">
        <?php $year_filter = $this->session->userdata('year_filter'); ?>
        <?php foreach ($dt_available_year as $dt): ?>
            <option value="<?= $dt->year ?>" <?php if($dt->year == $year_filter) echo "selected"; ?>><?= $dt->year ?></option>
        <?php endforeach; ?>
    </select>
</form>
<?php
    $stats['data'] = $dt_visit_pertime_year;
    $year_filter = $this->session->userdata('year_filter');
    $stats['ctx'] = "total_visit_with_by_month_in_$year_filter";
    $this->load->view('others/line_chart', $stats);
?>