<div class="text-center mt-3 mb-4">
    <h2 class="mb-0">Give Us Feedback</h2>
    <h5 class="text-secondary">Your words are helpfull for us to develop</h5>
</div>
<form action="/FeedbackController/add_feedback" method="POST">
    <?php 
        if($this->session->flashdata('validation_error')){
            echo "
                <div class='alert alert-danger' role='alert'>
                    <h5><i class='fa-solid fa-triangle-exclamation'></i> Error</h5>
                    ".$this->session->flashdata('validation_error')."
                </div>
            "; 
        }
    ?>
    <div class="d-flex justify-content-between">
        <label>Rate</label>
        <div class="fw-bold"><i class="fa-solid fa-star"></i> <span id="rate-val-holder">5</span></div>
    </div>
    <input type="range" id="feedback_rate" name="feedback_rate" class="w-100" min="0" value="5" max="10">
    <br>
    <label>Message</label>
    <textarea name="feedback_body" id="feedback_body" rows="4" class="form-control"></textarea>
    
    <button class="btn btn-dark rounded-pill w-100 mt-3" type="submit">Send Feedback</button>
</form>

<script>
    $('#feedback_rate').on('input',function(){
        $('#rate-val-holder').text(this.value)
    })
</script>

<?php 
    if($this->session->flashdata('message_error')){
        echo "
            <script>
                Swal.fire({
                    title: 'Failed!',
                    text: '".$this->session->flashdata('message_error')."',
                    icon: 'error'
                });
            </script>
        ";
    }
    if($this->session->flashdata('message_success')){
        echo "
            <script>
                Swal.fire({
                    title: 'Success!',
                    text: '".$this->session->flashdata('message_success')."',
                    icon: 'success'
                });
            </script>
        ";
    }
?>