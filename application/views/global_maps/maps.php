<style>
    #map-board {
        height:92vh;
    }
    .maps-toolbar {
        border: 5px solid black;
        padding: 0 !important;
        background: black;
    }
    .maps-toolbar button {
        margin: 10px !important;
        border-radius: 10px !important;
    }
</style>

<div class="maps-toolbar">
    <div class="d-flex justify-content-between">
        <a class="btn btn-danger py-2 px-4 m-2" href="<?php if($dt_active_search){ echo"/LoginController/view/$dt_active_search"; } else { echo"/"; }?>" id="back-page-btn" style="height:50px;"><i class="fa-solid fa-arrow-left"></i> Back</a>
        <h3 class="text-white mt-3 ms-3">Global Maps</h3>
        <div class="d-flex justify-content-end">
            <?php $this->load->view('maps/filter_category'); ?>
        </div>
    </div>
    <div class="position-relative">
        <div id="map-board"></div>
    </div>
</div>

<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDXu2ivsJ8Hj6Qg1punir1LR2kY9Q_MSq8&callback=initMap&v=weekly" defer></script>

<script type="text/javascript">
    let map
    let openInfoWindows = []
    let maxOpenInfoWindows = 2
    var markers = []

    function initMap() {
        map = new google.maps.Map(document.getElementById("map-board"), {
            center: { lat: -6.226838579766097, lng: 106.82157923228753},
            zoom: 12,
        });

        markers.forEach(dt => {
            addMarker({
                coords: { lat: parseFloat(dt.pin_lat), lng: parseFloat(dt.pin_long) },
                content: 
                `<div>
                    <h6>${dt.pin_name}</h6>
                    <span class='bg-dark rounded-pill px-2 py-1 text-white'>${dt.pin_category}</span><br><br>
                    ${dt.pin_desc ?? '<span class"fst-italic">- No description provided -</span>'}
                    <p class='mt-2 mb-0 fw-bold'>List Name</p>
                    <p class='mb-0'>${dt.list_name}</p>
                    <p class='mt-1 mb-0 fw-bold'>Created At</p>
                    <p class='date-target'>${dt.created_at}</p>
                    <a class='btn btn-light px-2 py-1 set-direction-btn' style='font-size:12px;' href='https://www.google.com/maps/dir/My+Location/${dt.pin_lat},${dt.pin_long}'><i class='fa-solid fa-location-arrow'></i> Set Direction</a>
                    <a class='btn btn-light px-2 py-1 search-person-btn me-1' style='font-size:12px;' href='/GlobalMapsController/view/${dt.created_by}'><i class="fa-solid fa-user"></i> Search Owner</a>
                    <a class='btn btn-light px-2 py-1 save-to-my-pin-btn' style='font-size:12px;'><i class='fa-solid fa-bookmark'></i> Save to My Pin</a>
                </div>`,
                icon: null
            })
        })
    }

    function addMarker(props) {
        var marker = new google.maps.Marker({
            position: props.coords,
            map: map,
            icon: props.icon,
        })

        if (props.iconImage) {
            marker.setIcon(props.iconImage)
        }

        if (props.content) {
            var infoWindow = new google.maps.InfoWindow({
                content: props.content
            });

            marker.addListener('click', function () {
                if (openInfoWindows.length >= maxOpenInfoWindows) {
                    var oldestInfoWindow = openInfoWindows.shift()
                    oldestInfoWindow.close()
                }
                infoWindow.open(map, marker)
                openInfoWindows.push(infoWindow)
            });
        }
    }

    getListPin('<?php foreach ($dt_global as $dt) { echo "$dt->id,"; } ?>')

    function getListPin(list_ids) {
        $.ajax({
            url: `http://127.0.0.1:8000/api/v1/pin_global/search/by_list_id`,
            dataType: 'json',
            contentType: 'application/json',
            type: "POST",
            data: JSON.stringify({
                list_ids: list_ids
            })
        })
        .done(function (response) {
            const data = response.data
            data.forEach(el => {
                markers.push({
                    pin_lat: el.pin_coordinate.split(',')[0],
                    pin_long: el.pin_coordinate.split(',')[1],
                    pin_name: el.pin_name,
                    list_name: el.list_name,
                    pin_category: el.pin_category,
                    created_by: el.created_by,
                    created_at: el.created_at,
                    pin_address: el.pin_address
                })
            });
            initMap()
        })
        .fail(function (xhr, ajaxOptions, thrownError) {
            Swal.fire({
                title: 'Failed!',
                text: `Something wrong happened`,
                icon: 'error'
            });
        })
    }

    $( document ).ready(function() {
        const date_holder = document.querySelectorAll('.date-target');

        date_holder.forEach(e => {
            const date = new Date(e.textContent);
            e.textContent = getDateToContext(e.textContent, "calendar");
        });
    });
</script>
