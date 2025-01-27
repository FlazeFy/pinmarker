<div class="container bordered pb-0">
    <h4 class="text-start">Filter Chart</h4>
    <div>
        <div class="d-flex justify-content-start">
            <h6 class="mt-2">Monthly Stats : </h6>
            <form action="/DashboardController/filter_year" method="POST">
                <select class="form-select-custom" aria-label="Default select example" style="width:80px;" name="year_filter" id="year_filter" onchange="this.form.submit()">
                    <?php $year_filter = $this->session->userdata('year_filter'); ?>
                    <?php foreach ($dt_available_year as $dt): ?>
                        <option value="<?= $dt->year ?>" <?php if($dt->year == $year_filter) echo "selected"; ?>><?= $dt->year ?></option>
                    <?php endforeach; ?>
                </select>
            </form>
            <h6 class="mt-2">Pie Chart : </h6>
            <form action="/DashboardController/filter_year_pin" method="POST">
                <select class="form-select-custom" aria-label="Default select example" style="width:80px;" name="year_filter_pin" id="year_filter_pin" onchange="this.form.submit()">
                    <?php $year_filter_pin = $this->session->userdata('year_filter_pin'); ?>
                    <option value="all" <?php if("all" == $year_filter_pin) echo "selected"; ?>>All</option>
                    <?php foreach ($dt_available_year as $dt): ?>
                        <option value="<?= $dt->year ?>" <?php if($dt->year == $year_filter_pin) echo "selected"; ?>><?= $dt->year ?></option>
                    <?php endforeach; ?>
                </select>
            </form>
        </div>
    </div>
</div>