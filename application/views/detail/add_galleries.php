<a class="btn btn-dark rounded-pill py-2 px-3" data-bs-toggle="modal" data-bs-target="#addGalleriesModal"><i class="fa-solid fa-plus m-0"></i> Add Galleries</a>

<div class="modal fade" id="addGalleriesModal" tabindex="-1" aria-labelledby="addGalleriesLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addGalleriesLabel">Add Galleries</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form class="d-inline" method="POST" action="/detailcontroller/add_gallery/<?= $dt_detail_pin->id ?>">
                    <input hidden type="text" name="gallery_url" id="gallery_url">
                    <span id="gallery_preview"></span>
                    <span id="caption-holder"></span>
                    <div class="input-group">
                        <input type="file" onchange="setValueGallery()" disabled class="form-control" style="border-radius:var(--roundedMD) 0 0 var(--roundedMD) !important;" id="file-input" aria-describedby="inputGroupFileAddon04" aria-label="Upload">
                        <span id="toogle-type-holder">
                            <select class="form-select" id="gallery_type" name="gallery_type" onchange="setGalleryType(this)" name="gallery_type" style="border-radius: 0 var(--roundedMD) var(--roundedMD) 0 !important;" aria-label="Default select example">
                                <option selected>- Select Type -</option>
                                <option value="image">Image</option>
                                <option value="video">Video</option>
                            </select>
                        </span>
                        <span id="toogle-submit-holder"></span>
                    </div>
                </form>
                <a class="msg-error-input" id="progress-upload"></a>
                <a class="msg-error-input" id="failed-upload"></a>
            </div>
        </div>
    </div>
</div>
<script>
    function setGalleryType(prop){
        document.getElementById('file-input').setAttribute('accept', `${prop.value}/*`)
        document.getElementById('file-input').disabled = false
    }

    function setValueGallery(){
        var file_src = document.getElementById('file-input').files[0]
        var type = document.getElementById('gallery_type').value
        var maxSize = 4; // Mb

        if(type == "video"){
            maxSize = 16;
        }

        if(file_src.size <= maxSize * 1024 * 1024){
            var filePath = `gallery_${type}/` + getUUID()

            var storageRef = firebase.storage().ref(filePath)
            var uploadTask = storageRef.put(file_src)

            uploadTask.on('state_changed',function (snapshot) {
                document.getElementById('file-input').disabled = true
                document.getElementById('gallery_type').disabled = true

                var progress = Math.round((snapshot.bytesTransferred/snapshot.totalBytes)*100);
                document.getElementById('progress-upload').innerHTML = `<span class="box-loading"><div role="progressbar" aria-valuenow="${progress}" aria-valuemin="0" aria-valuemax="${progress}" style="--value: ${progress}"></div></span>`
            }, 
            function (error) {
                document.getElementById('failed-upload').innerHTML = `<span class='box-loading'><img class='d-inline mx-auto img img-fluid' src='http://127.0.0.1:8000/assets/Failed.png'><h6>File upload is ${error.message}</h6></span>`
            }, 
            function () {
                uploadTask.snapshot.ref.getDownloadURL().then(function (downloadUrl) {
                    document.getElementById('file-input').disabled = false
                    document.getElementById('gallery_type').disabled = false
                    
                    if(type == 'image'){
                        document.getElementById('gallery_preview').innerHTML = `
                            <img class="img img-fluid d-block mx-auto rounded w-100" src="${downloadUrl}">
                        `
                    } else if(type == 'video') {
                        document.getElementById('gallery_preview').innerHTML = `
                            <video controls class='rounded w-100 mx-auto mt-2' alt='${downloadUrl}'>
                                <source src='${downloadUrl}'>
                            </video>
                        `
                    }
                    document.getElementById('toogle-type-holder').style.display = 'none'
                    document.getElementById('toogle-submit-holder').innerHTML = `
                        <input class="btn btn-dark" style="border-radius: 0 var(--roundedMD) var(--roundedMD) 0 !important;" type="submit" id="inputGroupFileAddon04" value="Upload" type="submit">
                    `
                    document.getElementById('caption-holder').innerHTML = `
                        <textarea class="form-control" name="gallery_caption" id"gallery_caption"></textarea>
                    `
                    document.getElementById('gallery_url').value = downloadUrl
                });
            });
        } else {
            document.getElementById('failed-upload').innerHTML = `<span class='box-loading'><img class='d-inline mx-auto img img-fluid' src='http://127.0.0.1:8000/assets/Failed.png'><h6>Maximum size is ${maxSize} mb </h6></span>`
        }
    }
</script>

<script src="https://www.gstatic.com/firebasejs/6.0.2/firebase.js"></script>

<script>
    // Your web app's Firebase configuration
    const firebaseConfig = {
        apiKey: "AIzaSyCKyEYWw4qBXZXxeCcboy3EM9PGv_0h0FI",
        authDomain: "pinmarker-36552.firebaseapp.com",
        projectId: "pinmarker-36552",
        storageBucket: "pinmarker-36552.appspot.com",
        messagingSenderId: "476329575389",
        appId: "1:476329575389:web:4d180427c65e47da5e6c34",
        measurementId: "G-9QS9THP8PY"
    };
    // Initialize Firebase
    firebase.initializeApp(firebaseConfig);
</script>