<style>
    .btn.change-image{
        width:40px; 
        height:40px; 
        background:var(--primaryColor);
        border-radius: var(--roundedCircle);
        display: block;
        margin-inline: auto;
    }
    .btn-icon-reset-image{
        position: absolute; 
        bottom: 10px; 
        left: 10px;
        background: var(--warningBG) !important;
        color:var(--whiteColor) !important;
    }
    .status-holder{
        position: absolute; 
        bottom: 10px; 
        left: 60px;
    }
    .btn-change-image:hover, .change-image:hover, .btn-reset-image:hover, .btn-icon-reset-image:hover{
        transform: scale(1.075);
    }
    .btn-change-image{
        position: absolute;
        background: var(--secondaryColor);
        height: 50px;
        width: 50px;
        cursor: pointer;
        padding: var(--spaceSM);
        border-radius: 100%;
        bottom: 10px;
        left: 40px;
        border: 3px solid var(--primaryColor);
        box-shadow: rgba(0, 0, 0, 0.24) 0px 3px 8px;
        -webkit-transition: all 0.6s;
        -o-transition: all 0.5s;
        transition: all 0.5s;
    }
    .btn-reset-image{
        height:50px; 
        width:50px; 
        background: var(--secondaryColor);
        border-radius: 100%; 
        padding: var(--spaceMD);
        position: absolute;
        cursor: pointer;
        color: var(--whiteColor) !important;
        box-shadow: rgba(0, 0, 0, 0.24) 0px 3px 8px;
        border: 3px solid var(--primaryColor);
        -webkit-transition: all 0.6s;
        -o-transition: all 0.5s;
        transition: all 0.5s;
        bottom: 10px; 
        right: 50px;
    } 
</style>

<span class="position-relative mb-3">
    <?php 
        $img_url = $this->session->userdata('user_img_url');
        if($img_url == null){
            echo "<img class='avatar_dashboard' src='http://127.0.0.1:8080/public/images/avatar_man_1.png' id='profile_image_info'><br>";
        } else {
            echo "<img class='avatar_dashboard' src='$img_url' alt='$img_url' id='profile_image_info'>";
        }
    ?>
    <div class='image-upload' id='formFileImg'>
        <label for='file-input'>
            <span class="btn-change-image" id="btn-change-image"><img class="img img-fluid" style="height:27.5px;" src="http://127.0.0.1:8080/public/images/camera.png"></span>
        </label>
        <input id='file-input' type='file' accept="image/*" value="" onchange='setValueProfileImage()'/>
    </div>
    <form id="form-image" class="d-inline">
        <input hidden type="text" name="image_url" id="profile_image_url" value="">
    </form>
    <?php 
        $img_url = $this->session->userdata('user_img_url');
        if($img_url){
            echo "<a class='btn btn-reset-image' id='btn-reset-image' title='Reset to default image' onclick='clearImage()'><i class='fa-solid fa-trash-can fa-lg'></i></a>";
        }
    ?>
    <span class="status-holder">
        <span class="attach-upload-status success" id="header-progress"></span>
        <a class="attach-upload-status failed" id="header-failed"></a>
        <a class="attach-upload-status warning" id="header-warning"></a>
    </span>

    <form method="POST" id="form-image-edit" action="/myprofilecontroller/edit_image">
        <input hidden name="img_url" id="img_url">
    </form>
</span>

<script>        
    function setValueProfileImage(){
        var file_src = document.getElementById('file-input').files[0]
        var maxSize = 4; // Mb

        if(file_src.size <= maxSize * 1024 * 1024){
            var filePath = 'profile_image/' + getUUID()

            var storageRef = firebase.storage().ref(filePath)
            var uploadTask = storageRef.put(file_src)

            uploadTask.on('state_changed',function (snapshot) {
                var progress = Math.round((snapshot.bytesTransferred/snapshot.totalBytes)*100);
                document.getElementById('header-progress').innerHTML = `<span class="box-loading"><div role="progressbar" aria-valuenow="${progress}" aria-valuemin="0" aria-valuemax="${progress}" style="--value: ${progress}"></div></span>`
            }, 
            function (error) {
                document.getElementById('header-failed').innerHTML = `<span class='box-loading'><img class='d-inline mx-auto img img-fluid' src='http://127.0.0.1:8000/assets/Failed.png'><h6>File upload is ${error.message}</h6></span>`
            }, 
            function () {
                uploadTask.snapshot.ref.getDownloadURL().then(function (downloadUrl) {
                    document.getElementById('profile_image_info').src = downloadUrl
                    document.getElementById('profile_image_url').value = downloadUrl
                    edit_image(downloadUrl)
                });
            });
        } else {
            document.getElementById('header-failed').innerHTML = `<span class='box-loading'><img class='d-inline mx-auto img img-fluid' src='http://127.0.0.1:8000/assets/Failed.png'><h6>${messages('max_file_size')} ${maxSize} mb </h6></span>`
        }
    }

    function clearImage() {
        var storageRef = firebase.storage();
        var desertRef = storageRef.refFromURL('<?= $img_url ?>');

        desertRef.delete().then(() => {
            document.getElementById("header-progress").innerHTML = `<span class="box-loading"><img class="d-inline mx-auto img img-fluid" src="http://127.0.0.1:8000/assets/Success.png"><h6>Profile image has been set to default</h6></span>`
        }).catch((error) => {
            document.getElementById("header-failed").innerHTML = `<span class="box-loading"><img class="d-inline mx-auto img img-fluid" src="http://127.0.0.1:8000/assets/Failed.png"><h6>${error}</h6></span>`
        });

        document.getElementById("profile_image_url").value = null
        edit_image(null)
    }

    function edit_image(val){
        document.getElementById('img_url').value = val
        document.getElementById('form-image-edit').submit()
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