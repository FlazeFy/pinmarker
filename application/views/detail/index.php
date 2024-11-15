<style>
    .gallery-btn {
        border: 2px solid black; border-radius: 15px;
        padding: var(--spaceMD);
        text-align: left;
        background: var(--whiteColor);
    }
    .gallery-btn:hover {
        transform: scale(1.05);
    }
</style>
<?php $is_edit = $this->session->userdata('is_edit_mode'); ?>
<?php $this->load->view('detail/detail'); ?>

<?php if (!$is_edit): ?>
    <hr>
    <?php $this->load->view('detail/props'); ?>
<?php endif; ?>

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