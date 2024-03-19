<div class="row">
    <div class="col-lg-4 col-md-6 col-sm-12">
        <p class='mt-2 mb-0 fw-bold'>Created At</p>
        <p><?= date('Y-m-d H:i', strtotime($dt_detail_pin->created_at)) ?></p>
    </div>
    <?php 
        if($dt_detail_pin->updated_at != null){
            echo "<div class='col-lg-4 col-md-6 col-sm-12'>
                <p class='mt-2 mb-0 fw-bold'>Updated At</p>
                <p>"; echo date('Y-m-d H:i', strtotime($dt_detail_pin->updated_at)); echo "</p>
            </div>";
        }
        if($dt_detail_pin->deleted_at != null){
            echo "<div class='col-lg-4 col-md-6 col-sm-12'>
                <p class='mt-2 mb-0 fw-bold'>Deleted At</p>
                <p>"; echo date('Y-m-d H:i', strtotime($dt_detail_pin->deleted_at)); echo "</p>
            </div>";
        }
    ?>
</div>