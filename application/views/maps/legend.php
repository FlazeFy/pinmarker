<h4 class="text-center">Category</h4>
<div class="mb-2 text-center">
    <?php 
        foreach($dt_dct_pin_category as $dt){
            echo "<span class=' me-2'>
                <span class='container rounded mx-1' style='height: 20px; width: 20px; background: $dt->dictionary_color;'></span> $dt->dictionary_name
            </span>";
        }
    ?>
</div>