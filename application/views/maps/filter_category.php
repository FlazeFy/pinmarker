<div class="panel-card flex-grow-1">
    <h3 class="panel-title">Category Breakdown</h3>
    <div class="d-flex flex-column gap-1 mt-3">
        <?php
            foreach ($dt_pin_category as $dt) {
                echo "
                    <a class='cat-item'>
                        <div class='cat-header'>
                            <div class='cat-icon bg-$dt->dictionary_color'>
                                <i class='fa-solid ".($dt->dictionary_icon ?? "fa-thumbtack")."'></i>
                            </div>
                            <div class='flex-grow-1'>
                                <div class='cat-name' data-val='$dt->pin_category'>$dt->pin_category</div>
                                <div class='cat-count'>$dt->total Marker".($dt->total > 1 ? "s":"")."</div>
                            </div>
                            <i class='fa-solid fa-chevron-right cat-arrow'></i>
                        </div>
                        <div class='cat-body'></div>
                    </a>
                ";
            }
        ?>
    </div>
</div>

<style>
    .cat-item {
        padding: var(--spaceSM);
        border-radius: var(--roundedMD);
        margin-bottom: var(--spaceMini);
        cursor: pointer;
    }
    .cat-header {
        display: flex;
        align-items: center;
        gap: var(--spaceMD);
    }
    .cat-item:hover .cat-arrow {
        color: var(--primaryColor);
    }
    .cat-arrow {
        font-size: 11px;
        color: #c7c4d8;
        transition: color .2s;
    }
</style>