<?php if(!$is_mobile_device): ?>
    <table class="table table-bordered" id="tb_related_pin_track">
        <thead>
            <tr>
                <th scope="col">Name</th>
                <th scope="col">Total Visit</th>
                <th scope="col">Location History</th>
            </tr>
        </thead>
        <tbody id="tb_related_pin_track_body" style="font-size: var(--textSM);">
            <?php 
                if(count($dt_person) > 0){
                    foreach($dt_person as $idx => $dt){
                        $locations = explode(', ',$dt->locations);
                        $total_loc = count($locations);
                        $visit_at = explode(', ',$dt->visit_at);
                        $location_element = "";

                        foreach ($locations as $idx_lc => $lc) {
                            $location_element .= "<b>$lc</b> last visit at <span class='date-target'>".$visit_at[$idx_lc]."</span>";

                            if($total_loc > 1 && $idx_lc == $total_loc - 2){
                                $location_element .= ", and ";
                            } else if($idx_lc < $total_loc - 1){
                                $location_element .= ", ";
                            }
                        }

                        echo "
                            <tr>
                                <td><h6>".ucwords($dt->name)."</h6></td>
                                <td class='text-center'><b>$dt->total</b> Visit</td>
                                <td>$location_element</td>
                            </tr>
                        ";
                    }
                } else {
                    echo "
                        <tr>
                            <td colspan='5' class='text-secondary fst-italic'>- No Item Found In Trash -</td>
                        </tr>
                    ";
                }
            ?>
        </tbody>
    </table>
<?php else: ?>
    <?php 
        if(count($dt_person) > 0){
            foreach($dt_person as $idx => $dt){
                $locations = explode(', ',$dt->locations);
                $total_loc = count($locations);
                $visit_at = explode(', ',$dt->visit_at);
                $location_element = "";

                foreach ($locations as $idx_lc => $lc) {
                    $location_element .= "<b>$lc</b> last visit at <span class='date-target'>".$visit_at[$idx_lc]."</span>";

                    if($total_loc > 1 && $idx_lc < $total_loc - 1){
                        $location_element .= ", and ";
                    } else if($idx_lc < $total_loc - 1){
                        $location_element .= ", ";
                    }
                }
                
                echo "
                    <div class='container w-100 bordered mb-3 px-3 py-2'>
                        <h3>$dt->name <span class='btn-tag'>$dt->total Visit</span></h3>
                        <p>$location_element</p>
                    </div>
                ";
            }
        } else {
            echo "<p class='text-secondary fst-italic'>- No Item Found In Trash -</p>";
        }
    ?>
<?php endif; ?>