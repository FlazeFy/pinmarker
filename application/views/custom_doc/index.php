<!-- Richtext -->
<link rel="stylesheet" href="http://127.0.0.1:8080/public/richtexteditor/rte_theme_default.css" />
<script type="text/javascript" src="http://127.0.0.1:8080/public/richtexteditor/rte.js"></script>
<script type="text/javascript" src="http://127.0.0.1:8080/public/richtexteditor/rte-upload.js"></script>
<script type="text/javascript" src="http://127.0.0.1:8080/public/richtexteditor/plugins/all_plugins.js"></script>

<a class="btn btn-danger mb-4 py-3 px-4 me-2" href="/DetailController/view/<?= $id ?>" id="back-page-btn"><i class="fa-solid fa-arrow-left"></i><?php if (!$is_mobile_device){ echo " Back"; } ?></a>
<?php $this->load->view('custom_doc/workarea'); ?>