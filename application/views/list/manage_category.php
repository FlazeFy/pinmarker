<div class="modal fade" id="manageCategory" data-bs-backdrop="static" tabindex="-1" aria-labelledby="addGalleriesLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addGalleriesLabel">Set Category</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id='close-manage-cat-modal-btn' onclick="clear_form_add_category()"></button>
            </div>
            <div class="modal-body">
                <?php
                    if(count($dt_my_category) == 0){
                        echo "<div class='d-block mx-auto my-2 text-center'>
                            <img class='img img-fluid' src='http://127.0.0.1:8080/public/images/category.png' style='max-width:240px;'>
                            <p>You have not created your personal category yet. Try create and using it to custom your pin grouping and also easily post it on global</p>
                            <a class='btn btn-success py-3 px-4' id='add-now-category-btn'><i class='fa-solid fa-plus'></i> Create Now!</a>
                        </div>";
                    } else {
                        if(!$is_mobile_device){
                            echo" <table class='table table-bordered' id='tb_my_category'>
                                <thead>
                                    <tr class='text-center'>
                                        <th scope='col'>Category Name</th>
                                        <th scope='col'>Color</th>
                                        <th scope='col'>Total Used</th>
                                        <th scope='col'>Delete</th>
                                    </tr>
                                </thead>
                                <tbody>"; 
                                    foreach($dt_my_category as $dt){
                                        echo "
                                            <tr>
                                                <td>
                                                    <input hidden class='form-control old-dictionary-name-holder' type='text' value='$dt->dictionary_name'>
                                                    <input class='form-control dictionary-name-holder' type='text' value='$dt->dictionary_name'>
                                                </td>
                                                <td>
                                                    <form action='/ListController/edit_category_color/$dt->id' method='POST'>
                                                        <select name='dictionary_color' class='form-select' id='dictionary_color' onchange='this.form.submit()'>
                                                            <option value='red' "; if($dt->dictionary_color == "red"){ echo "selected"; } echo">Red</option>
                                                            <option value='blue' "; if($dt->dictionary_color == "blue"){ echo "selected"; } echo">Blue</option>
                                                            <option value='yellow' "; if($dt->dictionary_color == "yellow"){ echo "selected"; } echo">Yellow</option>
                                                            <option value='orange' "; if($dt->dictionary_color == "orange"){ echo "selected"; } echo">Orange</option>
                                                            <option value='purple' "; if($dt->dictionary_color == "purple"){ echo "selected"; } echo">Purple</option>
                                                            <option value='green' "; if($dt->dictionary_color == "green"){ echo "selected"; } echo">Green</option>
                                                        </select>
                                                    </form>
                                                </td>
                                                <td class='total-used'>$dt->total_pin</td>
                                                <td>
                                                    <input hidden class='id-holder' value='$dt->id'>
                                                    <a class='btn btn-danger w-100 manage-category-btn'><i class='fa-solid fa-trash'></i></a>
                                                </td>    
                                            </tr>
                                        ";
                                    }
                                echo"
                                    <tr id='add_form_dct_holder'>
                                        <td colspan='4'><a class='btn btn-success w-100' id='add-category-btn' onclick='add_form_dct_category()'><i class='fa-regular fa-plus'></i> Add Category</a></td>
                                    </tr>
                                </tbody>
                            </table>";
                        } else {
                            foreach($dt_my_category as $dt){
                                echo "
                                    <div class='container w-100 bordered mb-3 p-3'>
                                        <input hidden class='form-control old-dictionary-name-holder' type='text' value='$dt->dictionary_name'>
                                        <label>Category Name</label>
                                        <input class='form-control dictionary-name-holder' type='text' value='$dt->dictionary_name'>
                                        <label>Color</label>
                                        <form action='/ListController/edit_category_color/$dt->id' method='POST'>
                                            <select name='dictionary_color' class='form-select' id='dictionary_color' onchange='this.form.submit()'>
                                                <option value='red' "; if($dt->dictionary_color == "red"){ echo "selected"; } echo">Red</option>
                                                <option value='blue' "; if($dt->dictionary_color == "blue"){ echo "selected"; } echo">Blue</option>
                                                <option value='yellow' "; if($dt->dictionary_color == "yellow"){ echo "selected"; } echo">Yellow</option>
                                                <option value='orange' "; if($dt->dictionary_color == "orange"){ echo "selected"; } echo">Orange</option>
                                                <option value='purple' "; if($dt->dictionary_color == "purple"){ echo "selected"; } echo">Purple</option>
                                                <option value='green' "; if($dt->dictionary_color == "green"){ echo "selected"; } echo">Green</option>
                                            </select>
                                        </form>
                                        <div class='d-flex justify-content-between'>
                                            <h6 class='total-used mt-2'>Total Used : $dt->total_pin</h6>
                                            <input hidden class='id-holder' value='$dt->id'>
                                            <a class='btn btn-danger manage-category-btn'><i class='fa-solid fa-trash'></i></a>
                                        </div>
                                    </div>
                                ";
                            }
                        }
                    }
                ?>  
            </div>
        </div>
    </div>
</div>

<form hidden action="/ListController/delete_category" method="POST" id="delete-cat-form">
    <input id="dictionary_id" name='id'>
    <input id="dictionary_name_migrate" name='dictionary_migrate'>
</form>

<form hidden action="/ListController/rename_category" method="POST" id="rename-cat-form">
    <input id="dictionary_id_rename" name='id'>
    <input id="dictionary_name_old" name='dictionary_name_old'>
    <input id="dictionary_name_new" name='dictionary_name_new'>
</form>

<script>
    let add_i = 0
    const add_form_dct_category = () => {
        if(add_i == 0){
            $('#add_form_dct_holder').remove()
            $(`#tb_my_category tbody`).append(`
                <tr id='add_category_tr'>
                    <td colspan='4'>
                        <form action='/ListController/add_category' method='POST' onsubmit='return handleFormSubmit(this);'>
                            <div class="d-flex justify-content-start">
                                <input class='form-control me-2' type='text' name='dictionary_name' placeholder='Category Name' required>
                                <select name='dictionary_color' class='form-select me-2'>
                                    <option value='red'>Red</option>
                                    <option value='blue'>Blue</option>
                                    <option value='yellow'>Yellow</option>
                                    <option value='orange'>Orange</option>
                                    <option value='purple'>Purple</option>
                                    <option value='green'>Green</option>
                                </select>
                                <button class='btn btn-success' style='width:180px;' id='btn-submit' type='submit'><i class='fa-solid fa-floppy-disk'></i> Save</button>
                            </div>
                        </form>
                    </td>
                </tr>
            `);
            add_i++
        }
    }

    const clear_form_add_category = () => {
        add_i = 0
        $('#add_category_tr').empty()
    }

    $(document).ready(function() {
        $(document).on('blur', '.dictionary-name-holder', function() {
            const idx = $(this).index('.dictionary-name-holder')
            const id = $('.id-holder').eq(idx).val()
            const old_name = $('.old-dictionary-name-holder').eq(idx).val()
            const new_name = $(this).val().trim()
            const total = $('.total-used').eq(idx).text()

            if(old_name != new_name){
                let desc = ''
                if(total > 0){
                    desc = `. There's some pin who attached to this category that can be affected too`
                } 

                Swal.fire({
                    title: "Are you sure?",
                    html: `Want to update this category's name?${desc}`,
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonText: "Yes, update it!"
                })
                .then((result) => {
                    if (result.isConfirmed) {
                        $('#dictionary_id_rename').val(id)
                        $('#dictionary_name_old').val(old_name)
                        $('#dictionary_name_new').val(new_name)
                        $('#rename-cat-form').submit()
                    } else {
                        $(this).val(old_name)
                    }
                });
            }
        })

        $(document).on('click', '.manage-category-btn', function() { 
            const idx = $(this).index('.manage-category-btn')
            const id = $('.id-holder').eq(idx).val()
            const total = $('.total-used').eq(idx).text()
            
            if(total > 0){
                const dct_name = $('.dictionary-name-holder').eq(idx).val()
                let opt_el = ''
                const dct_ava_name = [<?php 
                    foreach($dt_dct_pin_category as $dt){
                        echo "'$dt->dictionary_name',";
                    }
                ?>]

                $('#dictionary_migrate').empty()
                dct_ava_name.forEach(dt => {
                    if(dt != dct_name){
                        opt_el += `<option value='${dt}'>${dt}</option>`
                    }
                });

                Swal.fire({
                    title: "Are you sure?",
                    html: `This category is being used in <b>${total}</b> pin. 
                        If you want to keep process this delete, make sure you choose another category to replace the deleted category?
                        <br><br>
                        <p>Replace <b>${dct_name}</b> to : </p>
                        <select id='dictionary_migrate' class='form-select mt-3'>${opt_el}</select>
                        `,
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonText: "Continue delete"
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
                    html: `Want to delete this category?`,
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