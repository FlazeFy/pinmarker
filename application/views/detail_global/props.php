<div class="row">
    <div class="col-lg-4 col-md-6 col-sm-12">
        <p class='mt-2 mb-0 fw-bold'>Created At</p>
        <p class="mb-0"><span class="date-target"><?= $dt_detail->created_at ?></span> by <button class='btn-account-attach'>@<?= $dt_detail->created_by ?></button></p>
    </div>
    <?php 
        if($dt_detail->updated_at != null){
            echo "<div class='col-lg-4 col-md-6 col-sm-12'>
                <p class='mt-2 mb-0 fw-bold'>Updated At</p>
                <p class='date-target mb-0'>"; echo $dt_detail->updated_at; echo "</p>
            </div>";
        }
    ?>
</div>