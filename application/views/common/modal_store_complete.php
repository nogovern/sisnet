<!-- modal dialog -->
<div class="modal fade" id="modal_store_complete" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title">점포 완료</h4>
      </div>
      <!-- start form -->
      <form role="form" class="form form-horizontal">
      <div class="modal-body">
        <div class="well well-sm">
          <span class="text-danger">점포 완료 하려고 합니다.</span>
        </div>
        
        <div class="form-group">
          <label class="form-label col-sm-3">완료일시</label>
          <div class="input-group col-sm-6">
            <input type="text" name="store_complete_date" id="store_complete_date" class="form-control date-picker" value="<?=date('Y-m-d');?>">
            <span class="input-group-addon btn_date"><i class="fa fa-calendar"></i></span>
          </div>
        </div>

        <div class="form-group">
          <label class="form-label col-sm-3">메  모</label>
          <div class="col-sm-8">
            <textarea name="store_complete_memo" id="store_complete_memo" class="form-control" rows="5"></textarea>
            <span class="help-block"><small class="text-info">메모 입력하세요...</small></span>
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

<script type="text/javascript">
$(document).ready(function(){
  $("#modal_store_complete form").validate({
    rules : {
      store_complete_date: 'required'
    },
    submitHandler: function(form) {
      $.ajax({
        url: "<?=base_url()?>work/ajax/store_complete",
        type: "POST",
        data: {
          id : operation.id,
          date_complete:  $("#store_complete_date", form).val(),
          memo:           $("#store_complete_memo", form).val(),
          "csrf_test_name": $.cookie("csrf_cookie_name")
        },
        dataType: "html",
      })
        .done(function(response) {
          alert(response);
          location.reload();
        })
        .fail(function(xhr, textStatus){
          alert("Request failed: " + textStatus);
        });
    }
  });
});
</script>