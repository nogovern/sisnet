<!-- modal dialog -->
<div class="modal fade" id="modal_op_complete" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title">작업 완료</h4>
      </div>
      <!-- start form -->
      <form method="post" enctype="multipart/form-data" role="form" class="form form-horizontal">
        <input type="hidden" name="csrf_test_name" value="<?php echo $this->security->get_csrf_hash();?>">
        <input type="hidden" name="op_id" value="<?=$work->id?>">
    
      <div class="modal-body">
        <ul class="well well-sm" style="list-style: none;">
          <li> <span class="text-danger">작업 완료 하려고 합니다.</span></li>
          <li> <span class="text-danger">(주의) 실제 설치 수량이 재고에 반영됩니다.</span></li>
          <li> <span class="text-danger">첨부파일 기능은 아직 미구현...</span></li>
        </ul>
          
        <div class="form-group">
          <label class="form-label col-sm-3">완료일시</label>
          <div class="input-group col-sm-6">
            <input type="text" name="date_complete" class="form-control date-picker" value="<?=$work->getDateExpect()?>">
            <span class="input-group-addon btn_date"><i class="fa fa-calendar"></i></span>
          </div>
        </div>

        <div class="form-group">
          <label class="form-label col-sm-3">파일 첨부 (테스트중)</label>
          <div class="col-sm-8">
            <input id="fileupload" type="file" name="userfile" data-url="<?=base_url()?>upload/do_upload" multiple>
            <div class="help-block">파일 당 <?=GS2_MAX_FILE_SIZE?> M bytes 까지 업로드 가능합니다</div>
            <ul class="list-group" id="attachments">
            </ul>
          </div>
        </div>

      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-primary">완료</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">취소</button>
      </div>
      </form>
    </div>
  </div>
</div><!-- /.modal -->

<script src="<?=base_url()?>assets/js/vendor/jquery.ui.widget.js"></script>
<script src="<?=base_url()?>assets/js/jquery.iframe-transport.js"></script>
<script src="<?=base_url()?>assets/js/jquery.fileupload.js"></script>
<script>
var attachments = [];   //첨부파일

$(function () {
  'use strict';

    $('#fileupload').fileupload({
        dataType: 'json',
        done: function (e, data) {
          gs2_console(data.result);
          $.each(data.result.files, function (index, file) {
            attachments.push(file);
            $('<li/>').addClass("list-group-item").text(file.client_name).appendTo("#attachments");
          });
        }
    });
});
</script>

<script type="text/javascript">
  $(document).ready(function(){
    $("#modal_op_complete form").submit(function(e){
      e.preventDefault();

      var target_url = "<?=base_url()?>work/ajax/complete";
      var date_complete = $("input[name=date_complete]", this).val();
      if(date_complete == ''){
        alert('작업 완료일시를 입력하세요');
        $("input[name=date_complete]", this).focus();
        return false;
      }

      $.ajax({
        url: target_url,
        type: "POST",
        cache: false,
        async: false,
        data: {
          id : operation.id,
          office_id: $("#office_id").val(),
          date_complete: date_complete,
          memo: $("textarea[name=memo]", this).val(),
          files: attachments,
          "csrf_test_name": $.cookie("csrf_cookie_name")
        },
        dataType: "html",
      })
        .done(function(html) {
          alert(html);
          location.reload();
        })
        .fail(function(xhr, textStatus){
          alert("Request failed: " + textStatus);
        });
    });
  });
</script>