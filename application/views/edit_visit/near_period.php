<div class="row mt-3">
    <div class="col-6" id="previous-visit-button"></div>
    <div class="col-6" id="next-visit-button"></div>
</div>

<script>
    $(document).on('click', '.visit-item', function(){
        const id = $(this).data('id')
        changeIdInPath('EditVisitController', id)
        fetchVisit(id)
    })
</script>