<form id="publish-to-global-form" method="POST" action="/ListController/publish_to_global">
    <input hidden id="list_id" name="list_id">
    <input hidden id="category_name" name="category_name">
</form>

<script>
    $(document).ready(function() {
        $(document).on('click', '.publish-to-global', function() {
            const idx = $(this).index('.publish-to-global')
            const category_name = $('.dictionary_name_holder').eq(idx).text()
            const opt_el = "<?php 
                foreach($dt_my_list as $dt){
                    echo "<option value='$dt->id'>$dt->list_name</option>";
                }
            ?>"
            
            if($('.list-pin-desc').eq(idx).text().includes('- No Marker Found -')){
                Swal.fire({
                    title: "Oops!",
                    text: "Can't publish empty category",
                    icon: "error"
                });
            } else {
                Swal.fire({
                    title: "Publish All Location?",
                    html: `Are you sure want to copy all location in this category to global list and can be accessed by peoples
                        <br><br>
                        <p>Publish all <b>${category_name}</b>'s pin to list : </p>
                        <select id='global_migrate' class='form-select mt-3'>${opt_el}</select>
                        `,
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonText: "Continue Publish"
                })
                .then((result) => {
                    if (result.isConfirmed) {
                        $('#list_id').val($('#global_migrate').val())
                        $('#category_name').val(category_name)
                        $('#publish-to-global-form').submit()
                    }
                });
            }
        })
    })
</script>