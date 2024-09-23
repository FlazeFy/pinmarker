<div class="modal fade" id="historyTrackModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Track History</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id='close-track-history-modal-btn'></button>
            </div>
            <div class="modal-body">
                <h6>History From</h6>
                <div class="d-flex justify-content-start">
                    <input id="filter_date_track_history_start" type="date" class="form-control">
                    <input id="filter_hour_track_history_start" type="time" class="form-control ms-2">
                </div>
                <h6>History Until</h6>
                <div class="d-flex justify-content-start">
                    <input id="filter_date_track_history_end" type="date" class="form-control">
                    <input id="filter_hour_track_history_end" type="time" class="form-control ms-2">
                </div>
                <table class="table table-bordered" id="tb_history_track">
                    <thead style="font-size: var(--textMD);">
                        <tr class="text-center">
                            <th scope="col">Datetime</th>
                            <th scope="col">Coordinate</th>
                            <th scope="col">Route</th>
                            <th scope="col">Distance</th>
                            <th scope="col">Time Taken</th>
                            <th scope="col">Speed</th>
                            <th scope="col">Battery Indicator</th>
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody id="tb_history_track_body" style="font-size: var(--textSM);">
                        <tr class="text-center">
                            <td colspan="7">loading...</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger rounded-pill py-3 px-4" id='refresh-track-btn'><i class="fa-solid fa-arrows-rotate"></i> Refresh</button>
                <button type="button" class="btn btn-success rounded-pill py-3 px-4" id='save-track-btn'><i class="fa-solid fa-file"></i> Save as CSV</button>
                <button type="button" class="btn btn-dark rounded-pill py-3 px-4" id='prev-day-btn'><i class="fa-solid fa-arrow-left"></i> Previous Day</button>
                <button type="button" class="btn btn-dark rounded-pill py-3 px-4" id='this-day-btn'><i class="fa-solid fa-play"></i> This Day</button>
                <button type="button" class="btn btn-dark rounded-pill py-3 px-4" id='next-day-btn'>Next Day <i class="fa-solid fa-arrow-right"></i></button>
            </div>
        </div>
    </div>
</div>

<script>
    let requestData = {
        start_time: "2024-06-10T10:20:00",
        end_time: "2024-06-11T00:00:00"
    };

    let dateStart = requestData.start_time.slice(0, 10)
    let timeStart = requestData.start_time.slice(11, 19)
    let dateEnd = requestData.end_time.slice(0, 10)
    let timeEnd = requestData.end_time.slice(11, 19)

    $("#filter_date_track_history_start").val(dateStart)
    $("#filter_hour_track_history_start").val(timeStart)
    $("#filter_date_track_history_end").val(dateEnd)
    $("#filter_hour_track_history_end").val(timeEnd)

    $('#filter_date_track_history_start').on('change', function() {
        requestData.start_time = `${$(this).val()}T${timeStart}`
        getHistoryTrack()
    });
    $('#filter_date_track_history_end').on('change', function() {
        requestData.end_time = `${$(this).val()}T${timeEnd}`
        getHistoryTrack()
    });
    $('#filter_hour_track_history_start').on('change', function() {
        requestData.start_time = `${dateStart}T${$(this).val()}`
        getHistoryTrack()
    });
    $('#filter_hour_track_history_end').on('change', function() {
        requestData.end_time = `${dateEnd}T${$(this).val()}`
        getHistoryTrack()
    });

    function getHistoryTrack() {
        $.ajax({
            url: `http://127.0.0.1:8000/api/v1/track/journey/period/<?= $this->session->userdata('user_id'); ?>`,
            dataType: 'json',
            contentType: 'application/json',
            data: JSON.stringify(requestData), // Stringify the request data
            type: "POST", // Change to POST or PUT
            beforeSend: function (xhr) {
                // ...
            }
        })
        .done(function (response) {
            $("#tb_history_track_body").empty()
            
            let data = response.data
            data.forEach((dt,idx) => {
                const distance = calculateDistance(`${data[idx].track_lat},${data[idx].track_lat}`, `${data[idx+1].track_lat},${data[idx+1].track_lat}`)
                const time = countDatetimeStrInterval(data[idx].created_at, data[idx+1].created_at)
                let speed = 0
                if(distance != null && time != null){
                    speed = (distance / time) * 3.6
                }

                $("#tb_history_track_body").append(`
                    <tr class="text-center">
                        <th scope="row" class="text-center">${dt.created_at}</th>
                        <td>${dt.track_lat}, ${dt.track_long}</td>
                        <td>
                            <b>From</b>
                            <p class="m-0">${data[idx].track_lat}, ${data[idx].track_long}</p>
                            <b>To</b>
                            <p class="m-0">${data[idx+1].track_lat}, ${data[idx+1].track_long}</p>
                        </td>
                        <td>${distanceUnit(distance)}</td>
                        <td>${timeUnit(time)}</td>
                        <td>${speed.toFixed(2)} Km/h</td>
                        <td>${dt.battery_indicator}%</td>
                        <td><a class='btn btn-dark rounded-pill set-direction-btn'>Track on Maps</a></td>
                    </tr>
                `)

                Swal.fire({
                    title: "Track is Filtered!",
                    text: `With range from ${requestData.start_time} until ${requestData.end_time}`,
                    icon: "success"
                });
            });
        })
        .fail(function (jqXHR, ajaxOptions, thrownError) {
            Swal.fire({
                title: "Oops!",
                text: `No track found from ${requestData.start_time} until ${requestData.end_time}`,
                icon: "error"
            });
        });
    }
</script>