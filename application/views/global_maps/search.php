<input class="form-control w-100 <?php if(!$is_mobile_device){ echo "py-3"; } else { echo "py-2"; } ?> px-4" placeholder="Search by list name or list tag..." style="font-weight: <?php if(!$is_mobile_device){ echo "700; font-size: var(--textJumbo)"; } else { echo "500; font-size: var(--textXLG)"; } ?>" id="search_input" value="<?= $dt_active_search ?>">

<script>
    $(document).ready(function() {
        $('#search_input').on('blur', function() {
            const val = this.value.trim()
            const old_val = '<?= $dt_active_search; ?>'

            if(old_val != val && val && val != ''){
                window.location.href = `/GlobalMapsController/view/${this.value}`
            } else if((old_val != val && val) || val == ''){
                window.location.href = `/`
            } else {
               
            }
        })

        $('.share-global-pin-btn').on('click', function() {
            const idx = $(this).index('.share-global-pin-btn')
            const list_name = $(`#list-name-holder-${idx}`).text().trim().replace(' ','%20')
            messageCopy(`http://127.0.0.1:8080/LoginController/view/${list_name}`)
        })
    })
</script>