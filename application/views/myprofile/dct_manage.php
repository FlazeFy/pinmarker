<table class="table table-bordered my-3">
    <thead class="text-center">
        <tr>
            <th>Type</th>
            <th>Name</th>
            <th style="width:200px;">Color</th>
            <th>Props</th>
            <th style='width: 100px;'>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php
            foreach($dt_all_dct as $dt){
                echo "
                    <tr>
                        <td>$dt->dictionary_type</td>
                        <td>$dt->dictionary_name</td>
                        <td class='text-center'>";
                            if($dt->dictionary_type == 'pin_category'){
                                echo "
                                    <form action='/MyProfileController/edit_category_color/$dt->id' method='POST'>
                                        <select name='dictionary_color' class='form-select' id='dictionary_color' onchange='this.form.submit()'>
                                            <option value='red' "; if($dt->dictionary_color == "red"){ echo "selected"; } echo">Red</option>
                                            <option value='blue' "; if($dt->dictionary_color == "blue"){ echo "selected"; } echo">Blue</option>
                                            <option value='yellow' "; if($dt->dictionary_color == "yellow"){ echo "selected"; } echo">Yellow</option>
                                            <option value='orange' "; if($dt->dictionary_color == "orange"){ echo "selected"; } echo">Orange</option>
                                            <option value='purple' "; if($dt->dictionary_color == "purple"){ echo "selected"; } echo">Purple</option>
                                            <option value='green' "; if($dt->dictionary_color == "green"){ echo "selected"; } echo">Green</option>
                                        </select>
                                    </form>
                                ";
                            } else {
                                echo "<a class='text-secondary fst-italic text-decoration-none'>- Color is not available for this type -</a>";
                            }
                        echo "</td>
                        <td>
                            <p class='mt-2 mb-0 fw-bold'>Created By</p>";
                            if($dt->created_by){
                                echo "<span class='date-target'><button class='btn-account-attach'>@$dt->created_by</button></span>";
                            } else {
                                echo "<span class='date-target'>-</span>";
                            }
                            echo"
                        </td>
                        <td style='max-width:100px;'>
                            <button class='btn btn-dark w-100 rounded-pill mb-2'><i class='fa-solid fa-pen-to-square'></i></button>
                            <button class='btn btn-dark w-100 rounded-pill mb-2'><i class='fa-solid fa-fire-flame-curved'></i></button>
                        </td>
                    </tr>
                ";
            }
        ?>
    </tbody>
</table>