<h4 class="text-center mt-2">Category</h4>
<div class="mb-2 text-center">
    <?php 
        if(count($dt_dct_pin_category) > 0){
            $used = 0;
            foreach($dt_dct_pin_category as $dt){
                if($dt->total_pin > 0){
                    $used++;
                    echo "<span class='me-2'>
                        <span class='container py-0 rounded mx-1' style='background: $dt->dictionary_color;'></span><b>($dt->total_pin)</b> $dt->dictionary_name
                    </span>";
                }
            }
            if($used == 0){
                echo "<p class='text-secondary fst-italic'>- No Pin Category has been used to show -</p>";
            }
        } else {
            echo "<p class='text-secondary fst-italic'>- No Pin Category has been used to show -</p>";
        }
    ?>
</div>