<label>Tags</label>
<div class='mt-2' id='available-tag-holder'>
    <?php 
        foreach($dt_global_tag as $dt){
            echo "<a class='pin-tag-btn me-2 mb-1 text-decoration-none'>#$dt->tag_name</a>";
        }
    ?>
</div>
<label>Selected Tags</label><br>
<div id='selected-tag-holder'>
    <p class='fst-italic text-secondary' id='no-selected-tag-msg'>- No Selected Tag -</p>
</div>
<input id="list_tag" class='d-none' name="list_tag">

<script>
    $(document).ready(function() {
        const tagElementManipulate = (tag_name, type) => {
            let tagEl = ''
            if(type == 'available'){
                tagEl = `<a class='pin-tag-btn remove me-2 mb-1 text-decoration-none'>${tag_name}</a>`
            } else if(type == 'selected'){
                tagEl = ` <a class='pin-tag-btn me-2 mb-1 text-decoration-none'>${tag_name}</a>`
            }

            $(`#no-${type == 'available' ? 'selected' :'available'}-tag-msg`).remove()
            $(`#${type == 'available' ? 'selected' :'available'}-tag-holder`).append(tagEl)

            if($(`#${type}-tag-holder`).children().length == 0){
                $(`#${type}-tag-holder`).html(`<p class='fst-italic text-secondary' id='no-${type}-tag-msg'>- No ${ucFirst(type)} Tag -</p>`)
            }
        }

        $(document).on('click', '.pin-tag-btn:not(.remove)', function() {
            const idx = $(this).index('.pin-tag-btn')
            const tag_name = $(this).text()

            $(this).remove()
            tagElementManipulate(tag_name, 'available')
        })

        $(document).on('click', '.pin-tag-btn.remove', function() {            
            const idx = $(this).index('.pin-tag-btn.remove')
            const tag_name = $(this).text()
            let tagEl = ` <a class='pin-tag-btn me-2 mb-1 text-decoration-none'>${tag_name}</a>`

            $(this).remove()
            tagElementManipulate(tag_name, 'selected')
        })
    })
</script>