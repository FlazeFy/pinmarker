<br>
<?php 
    if($this->session->userdata('is_global_edit_mode') == false){
        if($dt_detail->list_desc){
            echo "<p>$dt_detail->list_desc</p>";
        } else {
            echo "<p class='text-secondary fst-italic'>- No Description -</p>";
        }
    } else {
        echo "
            <div class='row'>
                <div class='col-lg-6 col-md-6 col-sm-12 col-12'>
                    <form action='/DetailGlobalController/edit_list/$dt_detail->id' method='POST' id='edit-list-detail-form'>
                        <label>List Name</label>
                        <input name='list_name' id='list_name' value='$dt_detail->list_name' class='form-control'/>
                        <label>Description</label>
                        <textarea name='list_desc' id='list_desc' value='$dt_detail->list_desc' class='form-control'>$dt_detail->list_desc</textarea>
                        <a class='btn btn-success' id='edit-list-detail-submit-btn'><i class='fa-solid fa-floppy-disk'></i> Save Changes</a>
                    </form>
                </div>
                <div class='col-lg-6 col-md-6 col-sm-12 col-12'>";
                    if($this->session->userdata('is_global_edit_mode')){
                        echo "<label class='mb-1'>Manage Tag</label><br>
                        <br><label class='mt-2'>Add Tag</label><br>
                        ";
                    }
                echo"
                </div>
            </div>
        ";
    }
?>

<script>
    $(document).ready(function() {
        $(document).on('click', '.pin-tag-btn:not(.remove)', function() {
            const idx = $(this).index('.pin-tag-btn')
            const tag_name = $(this).text()
            let tagEl = `<a class='pin-tag-btn remove me-2 mb-1 text-decoration-none bg-white' style='color:var(--primaryColor) !important;
                border: calc(var(--spaceMini)/2) solid var(--primaryColor);'>${tag_name}</a>`

            $('#selected-tag-holder').append(tagEl)
            $(this).remove()

            if($('#available-tag-holder').children().length == 0){
                $(`#available-tag-holder`).html(`<p class='fst-italic text-secondary' id='no-available-tag-msg'>- No Available Tag -</p>`)
            }
        })

        $(document).on('click', '.pin-tag-btn.remove', function() {            
            const idx = $(this).index('.pin-tag-btn.remove')
            const tag_name = $(this).text()
            let tagEl = `<a class='pin-tag-btn me-2 mb-1 text-decoration-none'>${tag_name}</a>`

            $('#available-tag-holder').append(tagEl)
            $('#no-available-tag-msg').remove()
            $(this).remove()
        })
    })
</script>