<div class="modal fade" id="relatedPinTrackModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Related Pin x Track</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <h6>From : <span id="coor_now"></span></h6>
                <table class="table table-bordered" id="tb_related_pin_track">
                    <thead style="font-size: var(--textMD);" class="text-center">
                        <tr>
                            <th scope="col">Pin Name</th>
                            <th scope="col">Coordinate</th>
                            <th scope="col">Distance</th>
                        </tr>
                    </thead>
                    <tbody id="tb_related_pin_track_body" style="font-size: var(--textSM);">
                        <tr>
                            <td colspan="7">loading...</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    const coordinate = [
        <?php 
            foreach($dt_get_my_pin_coor as $dt){
                echo "
                    {pin_name:'$dt->pin_name',coordinate:'$dt->pin_lat,$dt->pin_long'},
                ";
            }    
        ?>
    ]

    liveTrack()
    function liveTrack() {
        getLastTrack()
        setInterval(getLastTrack, 3000)
    }

    function getLastTrack() {
        $( document ).ready(function() {
            $.ajax({
                url: `http://127.0.0.1:8000/api/v1/track/last/<?= $this->session->userdata('user_id'); ?>`,
                datatype: "json",
                type: "get",
                beforeSend: function (xhr) {
                    // 
                }
            })
            .done(function (response) {
                let data = response.data
                const current_lat = data.track_lat
                const current_long = data.track_long
                $("#coor_now").text(`${current_lat}, ${current_long}`)
                $('#tb_related_pin_track_body').empty()

                coordinate.forEach(el => {
                    $('#tb_related_pin_track_body').append(`
                        <tr>
                            <td>${el.pin_name}</td>
                            <td>${el.coordinate}</td>
                            <td>${distanceUnit(calculateDistance(el.coordinate, `${current_lat},${current_long}`))}</td>
                        </tr>
                    `)
                });
            })
            .fail(function (jqXHR, ajaxOptions, thrownError) {
                // Do something
            });
        });
    }
</script>