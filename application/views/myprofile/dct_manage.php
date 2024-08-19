<table class="table table-bordered my-3">
    <thead class="text-center">
        <tr>
            <th>Type</th>
            <th>Name</th>
            <th style="width:100px;">Total Used</th>
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
                        <td class='dictionary-type-holder'>$dt->dictionary_type</td>
                        <td>
                            <input hidden class='form-control old-dictionary-name-holder' type='text' value='$dt->dictionary_name'>
                            <input class='form-control dictionary-name-holder' type='text' value='$dt->dictionary_name'>
                        </td>
                        <td class='total-used'>";
                        if($dt->dictionary_type == 'pin_category'){
                            echo $dt->total_pin;
                        } else if($dt->dictionary_type == 'visit_by'){
                            echo $dt->total_visit;
                        }
                        echo"</td>
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
                            <input hidden class='id-holder' value='$dt->id'>
                            <button class='btn btn-dark w-100 rounded-pill mb-2 dictionary-delete-btn'><i class='fa-solid fa-fire-flame-curved'></i></button>
                        </td>
                    </tr>
                ";
            }
        ?>
    </tbody>
</table>

<form hidden action="/MyProfileController/rename_category" method="POST" id="rename-cat-form">
    <input id="dictionary_id_rename" name='id'>
    <input id="dictionary_type_rename" name='dictionary_type'>
    <input id="dictionary_name_old" name='dictionary_name_old'>
    <input id="dictionary_name_new" name='dictionary_name_new'>
</form>

<form hidden action="/MyProfileController/delete_category" method="POST" id="delete-cat-form">
    <input id="dictionary_id" name='id'>
    <input id="dictionary_name_migrate" name='dictionary_migrate'>
</form>

<script>
    $(document).ready(function() {
        $(document).on('blur', '.dictionary-name-holder', function() {
            const idx = $(this).index('.dictionary-name-holder')
            const id = $('.id-holder').eq(idx).val()
            const old_name = $('.old-dictionary-name-holder').eq(idx).val()
            const dct_type = $('.dictionary-type-holder').eq(idx).text()
            const new_name = $(this).val().trim()
            const total = $('.total-used').eq(idx).text()

            if(old_name != new_name){
                let desc = ''
                if(total > 0){
                    desc = `. There's some ${dct_type == 'pin_category' ? 'pin' : dct_type == 'visit_by' ? 'visit':''} who attached to this category that can be affected too`
                } 

                Swal.fire({
                    title: "Are you sure?",
                    html: `Want to update this dictionary's name?${desc}`,
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonText: "Yes, update it!"
                })
                .then((result) => {
                    if (result.isConfirmed) {
                        $('#dictionary_id_rename').val(id)
                        $('#dictionary_type_rename').val(dct_type)
                        $('#dictionary_name_old').val(old_name)
                        $('#dictionary_name_new').val(new_name)
                        $('#rename-cat-form').submit()
                    } else {
                        $(this).val(old_name)
                    }
                });
            }
        })

        $(document).on('click', '.dictionary-delete-btn', function() {
            const idx = $(this).index('.dictionary-delete-btn')
            const id = $('.id-holder').eq(idx).val()
            const dct_name = $('.old-dictionary-name-holder').eq(idx).val()
            const dct_type = $('.dictionary-type-holder').eq(idx).text()
            const total = $('.total-used').eq(idx).text()
            let desc = ''

            if(total > 0){
                let opt_el = ''
                const dct_ava_name = [<?php 
                    foreach($dt_all_dct as $dt){
                        echo "{
                            dct_name: '$dt->dictionary_name',
                            dct_type: '$dt->dictionary_type',
                            is_public: "; if($dt->created_by){ echo "false"; } else { echo "true"; } echo"
                        },";
                    }
                ?>]

                $('#dictionary_migrate').empty()
                dct_ava_name.forEach(dt => {
                    if(dt.dct_name != dct_name && dct_type == dt.dct_type && dt.is_public){
                        opt_el += `<option value='${dt.dct_name}'>${dt.dct_name}</option>`
                    }
                });

                Swal.fire({
                    title: "Are you sure?",
                    html: `This dictionary is being used in <b>${total}</b> ${dct_type == 'pin_category' ? 'pin' : dct_type == 'visit_by' ? 'visit':''}. 
                        If you want to keep process this delete, make sure you choose another dictionary to replace the deleted dictionary?
                        <br><br>
                        <p>Replace <b>${dct_name}</b> to : </p>
                        <select id='dictionary_migrate' class='form-select mt-3'>${opt_el}</select>
                        `,
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonText: "Continue Delete"
                })
                .then((result) => {
                    if (result.isConfirmed) {
                        $('#dictionary_id').val(id)
                        $('#dictionary_name_migrate').val($('#dictionary_migrate').val())
                        $('#delete-cat-form').submit()
                    }
                });
            } else {
                Swal.fire({
                    title: "Are you sure?",
                    html: `Want to delete this dictionary?`,
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonText: "Yes, delete it!"
                })
                .then((result) => {
                    if (result.isConfirmed) {
                        $('#dictionary_id').val(id)
                        $('#delete-cat-form').submit()
                    }
                });
            }
        })
    })
</script>