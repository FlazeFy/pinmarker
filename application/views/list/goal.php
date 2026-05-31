<?php 
    $indicators_el = "";
    $items_el = "";
    foreach ($dt_get_visited_pin_progress as $idx => $dt) {
        $total_pin = $dt->total_pin;
        $total_visited = $dt->total_visited;
        $is_all_visited = $dt->percentage === 100 ? true : false;
        $percentage = $dt->percentage;

        $indicators_el .= "<button type='button' data-bs-target='#goalCarousel' data-bs-slide-to='$idx' ". ($idx === 0 ? "class='active'" : "") ."></button>";
        $items_el .= "
            <div class='carousel-item". ($idx === 0 ? " active" : "") ."'>
                <div class='goal-title'>Visit $total_pin $dt->pin_category".($total_pin > 1 ? "s":"")."</div>
                <div style='font-size:var(--textSM); opacity:.85;'>".($is_all_visited ? "All" : $total_visited)." visited</div>
                <div class='goal-bar mt-3 mb-1'>
                    <div class='goal-fill' style='width:$percentage%;'></div>
                </div>
                <div style='font-size:var(--textSM); opacity:.85;'>$percentage% Completed</div>
            </div>
        ";
    }
?>

<div class="card stats-card--goal card-lift position-relative overflow-hidden">
    <div class="position-relative" style="z-index:1;">
        <div class="goal-eyebrow">Travel Goal</div>
        <div id="goalCarousel" class="carousel slide mt-0" data-bs-ride="carousel" data-bs-interval="3000">
            <div class="carousel-indicators goal-indicators">
                <?= $indicators_el ?>
            </div>
            <div class="carousel-inner">
                <?= $items_el ?>
            </div>
        </div>
    </div>
    <i class="fa-solid fa-trophy goal-deco"></i>
</div>

<style>
    .stats-card--goal {
        background: var(--primaryColor);
        color: #fff;
        border-color: transparent;
    }
    .goal-eyebrow {
        font-size: 10px;
        font-weight: 700;
        letter-spacing: .14em;
        text-transform: uppercase;
        opacity: .8;
        margin-bottom: 4px;
    }
    .goal-title {
        font-size: var(--textJumbo);
        font-weight: 800;
    }
    .goal-bar {
        width: 100%;
        height: 8px;
        background: rgba(255,255,255,.25);
        border-radius: var(--roundedJumbo);
        overflow: hidden;
    }
    .goal-fill {
        height: 100%;
        background: #fff;
        border-radius: var(--roundedJumbo);
        transition: width 1s ease;
    }
    .goal-deco {
        position: absolute;
        right: -10px;
        bottom: -10px;
        font-size: 90px;
        opacity: .1;
        pointer-events: none;
    }

    .goal-indicators {
        position: static;
        margin-top: 12px;
        margin-bottom: 0;
        justify-content: flex-start;
    }
    .goal-indicators [data-bs-target] {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        border: 0;
        margin: 0 4px;
        opacity: .4;
        background: #fff;
    }
    .carousel-indicators {
        justify-self: end;
        position: absolute;
        bottom: -5px;
        right: -50px;
    }
    .goal-indicators .active {
        opacity: 1;
    }
</style>