<div class="card">
    <h3 class="card-title">Global List & Tags</h3>
    <label>List that attached this marker</label>
    <div class="d=flex mb-2">
        <?php
            if (count($dt_global_list_pin) > 0) {
                foreach ($dt_global_list_pin as $dt) {
                    echo "<a class='tag bg-primary list-name me-1 mb-1' data-value='$dt->id'><i class='fa-solid fa-folder'></i> $dt->list_name</a>";
                }
            } else {
                echo "<span class='text-none'>- No list found -</span>";
            }
        ?>
    </div>
    <label>Attached Tag</label>
    <div class="d=flex">
        <?php
            if (count($dt_global_list_tag) > 0) {
                foreach ($dt_global_list_tag as $dt) {
                    echo "<a class='tag bg-success tag-name me-1 mb-1' data-value='$dt->id'><i class='fa-solid fa-hashtag'></i> $dt->tag_name</a>";
                }
            } else {
                echo "<span class='text-none'>- No tag found -</span>";
            }
        ?>
    </div>
</div>