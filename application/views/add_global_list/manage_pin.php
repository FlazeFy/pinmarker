<script type="text/javascript" charset="utf-8">
    $(document).ready(function () {
        $('#pinTable').DataTable({
            pageLength: 14, 
            lengthMenu: [ 
                [14, 28, 75, 125],
                [14, 28, 75, 125] 
            ],
        });
        $('#pinTable_info').closest('.col-sm-12.col-md-5').remove()
    });
</script>

<label>Attach some Pin</label>
<table id="pinTable" class="display table table-bordered w-100">
    <thead>
        <tr>
            <th>Pin Name</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php 
            foreach($dt_available_pin as $dt){
                echo "
                    <tr>
                        <td>
                            <span class='pin-name-holder'>$dt->pin_name</span>
                            <input hidden class='pin-id-coor' value='$dt->id,$dt->pin_lat,$dt->pin_long'>  
                        </td>
                        <td style='width:60px;' class='action-btn-holder'><a class='btn btn-success btn-add-pin w-100'><i class='fa-solid fa-plus'></i></a></td>
                    </tr>   
                ";
            }
        ?>
        
    </tbody>
</table>
<input id="list_pin" class='d-none'  name="list_pin">