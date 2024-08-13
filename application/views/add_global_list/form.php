<br>
<div class="row">
    <div class="col">
        <?php 
            if($this->session->flashdata('validation_error')){
                echo "
                    <div class='alert alert-danger' role='alert'>
                        <h5><i class='fa-solid fa-triangle-exclamation'></i> Error</h5>
                        ".$this->session->flashdata('validation_error')."
                    </div>
                "; 
            }
        ?>
        <form action="/AddGlobalListController/add_list" method="POST" id="form-add-global-list">
            <label>List Name</label>
            <input name="list_name" id="list_name" type="text" class="form-control" required/>
            <a class="msg-error-input"></a>
            <label>List Code</label>
            <input name="list_code" id="list_code" type="text" class="form-control" maxlength="6"/>
            <a class="msg-error-input" id='list_code_msg'></a><br>
            <label>Description</label>
            <textarea name="list_desc" id="list_desc" rows="5" class="form-control"></textarea>
            <a class="msg-error-input"></a>
            <label>Tags</label>
            <div class='mt-2' id='available-tag-holder'>
                <?php 
                    foreach($dt_global_tag as $dt){
                        echo "<a class='pin-tag me-2 mb-1 text-decoration-none'>#$dt->tag_name</a>";
                    }
                ?>
            </div>
            <div id='selected-tag-holder'></div>
            <input id="list_tag" class='d-none' name="list_tag">
            <a class="btn btn-dark rounded-pill w-100 py-3 mt-3" id="btn_submit"><i class="fa-solid fa-floppy-disk"></i> Save Global List <span id="submit-note"></span></a>
        </form>
    </div>
    <div class="col">
        
    </div>
</div>

<script>
    $(document).ready(function() {
        $(document).on('click', '.pin-tag:not(.remove)', function() {
            const idx = $(this).index('.pin-tag')
            let tagEl = ''
            const tag_name = $(this).text()

            if($('#selected-tag-holder').children().length > 0){
                tagEl = `
                    <a class='pin-tag remove me-2 mb-1 text-decoration-none'>${tag_name}</a>
                `
            } else {
                tagEl = `
                    <label>Selected Tags</label><br>
                    <a class='pin-tag remove me-2 mb-1 text-decoration-none'>${tag_name}</a>
                `
            }
            $('#selected-tag-holder').append(tagEl)
    
            $('.pin-tag').eq(idx).remove()
        })
        $(document).on('click', '.pin-tag.remove', function() {            
            const idx = $(this).index('.pin-tag.remove')
            const tag_name = $(this).text()
            let tagEl = ''

            tagEl = `
                <a class='pin-tag me-2 mb-1 text-decoration-none'>${tag_name}</a>
            `

            $('#available-tag-holder').append(tagEl)
    
            $('.pin-tag.remove').eq(idx).remove()
        })

        $(document).on('input', '#list_code', function() {            
            const val = $(this).val().trim()

            if(val.length != 6){
                $('#submit-note').empty()
                $('#list_code_msg').html(`<span class='text-danger'><i class="fa-solid fa-triangle-exclamation"></i> Your code is not valid</span>`)
            } else {
                $('#submit-note').text('with Code')
                $('#list_code_msg').html(`<span class='text-success'><i class="fa-solid fa-circle-check"></i> Your code is valid</span>`)
            }
        })

        $(document).on('click', '#btn_submit', function() {  
            let listTag = null
                
            if($('#selected-tag-holder').children().length > 0){
                listTag = []
                $('#selected-tag-holder .pin-tag').each(function(idx, el) {
                    listTag.push({
                        tag_name: $(el).text().replace('#','')
                    })
                })

                listTag = JSON.stringify(listTag)

                $('#list_tag').val(listTag)
            } 
            $('#form-add-global-list').submit()
        })
    })
</script>