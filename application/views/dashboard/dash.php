<div class="row mb-2 text-center">
    <div class="col-lg-4 col-md-6 col-sm-12 p-2">
        <h1 style="font-size: 60px; font-weight:bold;"><?= $dt_count_my_pin->total; ?></h1>
        <h4>Total Marker</h4>
    </div>
    <div class="col-lg-4 col-md-6 col-sm-12 p-2">
        <h1 style="font-size: 60px; font-weight:bold;"><?= $dt_count_my_fav_pin->total; ?></h1>
        <h4>Total Favorite Pin</h4>
    </div>
    <div class="col-lg-4 col-md-6 col-sm-12 p-2">
        <h2 style="font-weight:bold;"><?= $dt_get_last_visit->pin_name; ?></h2>
        <h4>Last Visit</h4>
    </div>
    <div class="col-lg-4 col-md-6 col-sm-12 p-2">
    <h2 style="font-weight:bold;"><?= "($dt_get_most_visit->total) $dt_get_most_visit->context"; ?></h2>
        <h4>Most Visit</h4>
    </div>
    <div class="col-lg-4 col-md-6 col-sm-12 p-2">
    <h2 style="font-weight:bold;"><?= "($dt_get_most_category->total) $dt_get_most_category->context"; ?></h2>
        <h4>Most Category</h4>
    </div>
    <div class="col-lg-4 col-md-6 col-sm-12 p-2">
        <h2 style="font-weight:bold;"><?= $dt_get_latest_pin->pin_name; ?></h2>
        <h4>Last Added</h4>
    </div>
</div>