<?php
$this->view('layout/header_popup');
?>

<div class="container" style="width:100%;">
	<div class="row">

<?php
// 결과를 보여줌 
if(isset($show_result)) { 
?>
  <script type="text/javascript">
  $(document).ready(function(){
    //var parent = $(window.frameElement).parent();
    window.parent.location.reload();
  });
  </script>

<?php 
} else { 
?> 

    <?php
    echo validation_errors();
    ?>
    
		<form id="upload_form" method="post" enctype="multipart/form-data" role="form" class="form form-horizontal">
      <input type="hidden" name="csrf_test_name" value="<?php echo $this->security->get_csrf_hash();?>">
      <input type="hidden" name="op_id" value="1">
      <input type="hidden" name="excel_only" value="1">
			
      <div class="form-group">
        <label class="form-label col-xs-3">파일 선택</label>
        <div class="col-xs-8">
          <input type="file" class="form-control" name="userfile[]"></input>

          <div class="help-block">파일 당 <?=GS2_MAX_FILE_SIZE?> M bytes 까지 업로드 가능합니다</div>
        </div>
      </div>

      <div class="col-xs-12">
        <button type="submit" class="btn btn-primary">등록</button>
      </div>

    </form>
  </div>
</div>

<?php 
}// !-- 폼출력 끝 

$this->view('layout/footer');
?>                