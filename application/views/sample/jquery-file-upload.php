<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>jQuery File Upload Example</title>
	<!-- Bootstrap styles -->
	<link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap.min.css">
</head>
<body>
	<form action="upload/do_upload" method="POST" enctype="multipart/form-data">
		<input type="hidden" name="csrf_test_name" value="<?php echo $this->security->get_csrf_hash();?>">
		<input type="hidden" name="operation_id" value="999">
		
		<input id="fileupload" type="file" name="userfile" data-url="<?=base_url()?>upload/do_upload" multiple>
	</form>

	<ul class="list-group">

	</ul>
	
	<form name="aaa">
		<input type="hidden" name="another" value="111">
	</form>


	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
	<script src="<?=base_url()?>assets/js/vendor/jquery.ui.widget.js"></script>
	<script src="<?=base_url()?>assets/js/jquery.iframe-transport.js"></script>
	<script src="<?=base_url()?>assets/js/jquery.fileupload.js"></script>
	<script>
	$(function () {
		'use strict';

	    $('#fileupload').fileupload({
	        dataType: 'json',
	        done: function (e, data) {
	        	console.log(data.result);
	            $.each(data.result.files, function (index, file) {
	                $('<li/>').addClass("list-group-item").text(file.client_name).appendTo(document.body);
	            });
	        }
	    });
	});
	</script>
</body> 
</html>
	