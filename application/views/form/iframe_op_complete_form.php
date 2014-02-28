<?php
/**
 * iframe 방식의 업무 완료 form
 */
$this->view('layout/header_popup');
?>

<!-- 업무 완료 폼 -->
<div class="container">
	<form id="iframe_form" method="post" enctype="multipart/form-data" role="form" class="form form-horizontal" target="iframe">
	    <input type="hidden" name="csrf_test_name" value="<?php echo $this->security->get_csrf_hash();?>">
	    <input type="hidden" name="op_id" value="<?=$work->id?>">
	    <input type="hidden" id="form_saved" name="form_saved" value="<?=$form_saved?>">

	    <ul class="well well-sm" style="list-style: none;">
	      <li> <span class="text-danger">작업 완료 하려고 합니다.</span></li>
	      <li> <span class="text-danger">(주의) 이 시점에서 재고에 반영되며, 전 상태로 돌릴 수 없습니다.</span></li>
	    </ul>
		
	    <?php echo validation_errors();?>
	    <?php 
	    if(isset($errors)) {
		    echo "<p>" . $errors . "<p>";
		}
	    ?>
	      
	    <div class="form-group">
	      <label class="form-label col-xs-3">완료일시</label>
	      <div class="input-group col-xs-6" style="padding-left:15px;">
	        <input type="text" name="date_complete" class="form-control date-picker" value="<?=date('Y-m-d');?>">
	        <span class="input-group-addon btn_date"><i class="fa fa-calendar"></i></span>
	      </div>
	    </div>

	    <div class="form-group">
	      <label class="form-label col-xs-3">파일 첨부</label>
	      <div class="col-xs-9">
	        <input type="file" class="form-control" name="userfile[]"></input>
            <input type="file" class="form-control" name="userfile[]"></input>
            <input type="file" class="form-control" name="userfile[]"></input>

	        <div class="help-block">파일 당 <?=GS2_MAX_FILE_SIZE?> M bytes 까지 업로드 가능합니다</div>
	      </div>
	    </div>
	</form>
</div>

<script type="text/javascript">
$(document).ready(function(){
  var iframe_form = $("#modal_op_complete_container iframe").contents().find("form");
  $(".date-picker").datepicker({
    minDate: new Date()
  });

  if($("#form_saved").val() == 'y') {
  	alert("완료되었습니다");
  	parent.location.reload();
  }

});  
</script>

<?php
$this->view('layout/footer');
?>