<label>Tags</label>
<div class='mt-2' id='available-tag-holder'>
    <?php 
        foreach($dt_global_tag as $dt){
            echo "<a class='pin-tag-btn me-2 mb-1 text-decoration-none'>#$dt->tag_name</a>";
        }
    ?>
</div>
<div id='selected-tag-holder'></div>
<input id="list_tag" class='d-none' name="list_tag">