<h4 class="text-center">All Person History</h4>
<?php if(!$is_mobile_device): ?>
    <table class="table table-bordered" id="tb_related_pin_track">
        <thead>
            <tr>
                <th scope="col">Name</th>
                <th scope="col">Total Visit</th>
                <th scope="col">Location History</th>
                <th scope="col" style="width:140px;">Analyze</th>
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
                                <td><b>"; echo $idx == 0 ? "<i class='fa-solid fa-crown text-warning'></i>" : ($idx <= 2 ? "<i class='fa-solid fa-star text-warning'></i>" : ""); echo ucwords($dt->name)."</b></td>
                                <td class='text-center'><b>$dt->total</b> Visit</td>
                                <td>$location_element</td>
                                <td><a class='btn btn-dark px-2 py-1 mx-auto see-detail-btn' href='/DetailPersonController/view/$dt->name'><i class='fa-solid fa-circle-info'></i> See Detail</a></td>
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