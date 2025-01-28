<div class="container bordered p-3 bg-white">
    <h4 class="text-start">Filter Monthly Chart</h4>
    <div>
        <div class="d-flex justify-content-start">
            <h6 class="mt-2">Monthly Stats : </h6>
            <form action="/DetailPersonController/filter_year/<?= $raw_name ?>" method="POST">
                <select class="form-select-custom" aria-label="Default select example" style="width:80px;" name="year_filter" id="year_filter" onchange="this.form.submit()">
                    <?php $year_filter = $this->session->userdata('year_filter'); ?>
                    <?php foreach ($dt_available_year as $dt): ?>
                        <option value="<?= $dt->year ?>" <?php if($dt->year == $year_filter) echo "selected"; ?>><?= $dt->year ?></option>
                    <?php endforeach; ?>
                </select>
            </form>
        </div>
    </div>
</div>