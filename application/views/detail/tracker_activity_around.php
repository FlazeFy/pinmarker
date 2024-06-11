<ol id="tracked_activity_around">Loading...</ol>
<script>
    getActivityAround()
    function getActivityAround() {
        $.ajax({
            url: `http://127.0.0.1:2000/api/v1/track/activity_around/<?= $dt_detail_pin->pin_lat ?>,<?= $dt_detail_pin->pin_long ?>/<?= $this->session->userdata('user_id'); ?>`,
            dataType: 'json',
            contentType: 'application/json',
            type: "get",
            beforeSend: function (xhr) {
                // ...
            }
        })
        .done(function (response) {            
            $('#tracked_activity_around').empty()

            const data = response.data

            data.forEach(el => {
                const date = new Date(el.created_at);

                const formattedDate = date.getFullYear() + '-' + 
                      ('0' + (date.getMonth() + 1)).slice(-2) + '-' + 
                      ('0' + date.getDate()).slice(-2) + ' ' + 
                      ('0' + date.getHours()).slice(-2) + ':' + 
                      ('0' + date.getMinutes()).slice(-2);

                if(el.distance <= 500){
                    $('#tracked_activity_around').append(`
                        <li><p class="mb-1">You have visited around this place at <b>${formattedDate}</b>. The distance is <b>${distanceUnit(el.distance)}</b> long to travelled</p></li>
                    `)
                }
            });
        })
        .error(function (xhr, ajaxOptions, thrownError) {
            // ...
            $('#tracked_activity_around').empty(`
                <div class='text-center text-secondary'>
                    <img class='img img-fluid m-1' style='width:200px;' src='http://127.0.0.1:8080/public/images/empty_item.png'>
                    <h6>No Tracked Activity found around this Pin</h6>
                </div>
            `)
        })
        
        
    }
</script>