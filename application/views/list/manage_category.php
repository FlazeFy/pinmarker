<div class="modal fade" id="manageCategory" tabindex="-1" aria-labelledby="addGalleriesLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addGalleriesLabel">Manage Category</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <?php
                    if(count($dt_my_category) == 0){
                        echo "<div class='d-block mx-auto my-2 text-center'>
                            <img class='img img-fluid' src='http://127.0.0.1:8080/public/images/category.png' style='max-width:240px;'>
                            <p>You have not created your personal category yet. Try create and using it to custom your pin grouping and also easily post it on global</p>
                            <a class='btn btn-dark rounded-pill py-3 px-4'><i class='fa-solid fa-plus'></i> Create Now!</a>
                        </div>";
                    } else {
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
                                            <td>$dt->dictionary_name</td>
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
                                            <td>$dt->total_pin</td>
                                            <td>
                                                <a class='btn btn-dark w-100'><i class='fa-solid fa-trash'></i></a>
                                            </td>    
                                        </tr>
                                    ";
                                }
                            echo"</tbody>
                        </table>";
                    }
                ?>  
            </div>
        </div>
    </div>
</div>
