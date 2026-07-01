<div class="modal fade" id="manage-tag-modal" tabindex="-1" aria-labelledby="manage-tag-modalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="manage-tag-modalLabel">Manage Tag</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <label>Attached Tags</label>
                <div id="tag-display" class="tag-display-box"></div>
                <textarea id="tag-textarea" class="form-control d-none" rows="3" placeholder="e.g. food, travel, coffee" style="resize: none;"></textarea>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="save-tag-btn">Save changes</button>
            </div>
        </div>
    </div>
</div>

<style>
    .tag-display-box {
        min-height: 90px;
        max-height: 90px;
        overflow-y: auto;
        padding: var(--spaceSM) var(--spaceXMD);
        border: 1.5px solid #e7e8ec;
        border-radius: var(--roundedMD);
        background: var(--containerColor);
        cursor: text;
        display: flex;
        flex-wrap: wrap;
        align-content: flex-start;
        gap: var(--spaceMini);
    }
    .tag-display-box:hover {
        border-color: var(--primaryColor);
    }
    .tag-display-box.empty::before {
        content: 'Click to edit tags...';
        color: #aaa;
        font-size: var(--textMD);
    }
    #tag-textarea {
        min-height: 90px;
        max-height: 90px;
        background: var(--containerColor);
        border-radius: var(--roundedMD);
        font-size: var(--textMD);
    }
</style>

<script>
    let currentTags = []

    const renderAllTags = (tags) => {
        currentTags = tags
        syncTagDisplay()
    }

    const syncTagDisplay = () => {
        const holder = $('#tag-display')
        holder.empty()

        if (currentTags.length === 0) {
            holder.addClass('empty')
            return
        }

        holder.removeClass('empty')
        currentTags.forEach(dt => holder.append(`<span class="tag bg-primary">#${dt.tag_name}</span>`))
    }

    const enterTagEditMode = () => {
        const val = currentTags.map(dt => dt.tag_name).join(', ')
        $('#tag-display').addClass('d-none')
        $('#tag-textarea').removeClass('d-none').val(val).trigger('focus')
    }

    const exitTagEditMode = () => {
        $('#tag-textarea').addClass('d-none')
        $('#tag-display').removeClass('d-none')
    }

    const submitTagChange = (val) => {
        const tags = val.split(',').map(dt => dt.trim()).filter(dt => dt !== '').join(',')

        $.ajax({
            url: `/api/v1/global_list/edit/tag/<?= $id ?>`,
            method: 'POST',
            headers: { 'Authorization': `Bearer ${tokenKey}` },
            data: { list_tag: tags },
            success: (response) => {
                currentTags = tags.split(',').map(dt => ({ tag_name: dt.trim() }))
                syncTagDisplay()
            },
            error: (response) => {
                if (response.status === 401) return failedAuth()
            }
        })
    }

    $(document).on('click', '#tag-display', function () {
        enterTagEditMode()
    })

    $(document).on('click', '#save-tag-btn', function () {
        const val = $('#tag-textarea').val().trim()
        submitTagChange(val)
        exitTagEditMode()
    })
</script>