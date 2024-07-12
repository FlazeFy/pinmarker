<div class="text-center mt-3 mb-4">
    <h2 class="mb-0">Give Us Feedback</h2>
    <h5 class="text-secondary">Your words are helpfull for us to develop</h5>
</div>
<form action="/FeedbackController/add_feedback" method="POST">
    <label>Rate</label><br>
    <input type="range" id="feedback_rate" name="feedback_rate" class="w-100" min="0" max="10">
    <a class="msg-error-input"></a><br>
    <label>Message</label>
    <textarea name="feedback_body" id="feedback_body" rows="4" class="form-control"></textarea>
    <a class="msg-error-input"></a>
    <button class="btn btn-dark rounded-pill w-100 mt-3" type="submit">Send Feedback</button>
</form>

<?php 
    if($this->session->flashdata('message_insert_error')){
        echo "
            <script>
                Swal.fire({
                    title: 'Failed!',
                    text: '".$this->session->flashdata('message_insert_error')."',
                    icon: 'error'
                });
            </script>
        ";
    }
    if($this->session->flashdata('message_insert_success')){
        echo "
            <script>
                Swal.fire({
                    title: 'Success!',
                    text: '".$this->session->flashdata('message_insert_success')."',
                    icon: 'success'
                });
            </script>
        ";
    }
?>