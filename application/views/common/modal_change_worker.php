<!-- modal dialog -->
<div class="modal fade" id="modal_change_worker" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title">방문자 변경</h4>
      </div>
      <!-- start form -->
      <form role="form" class="form form-horizontal">
      <div class="modal-body">
        <p class="well well-sm">
          방문자를 변경합니다.
        </p>

        <div class="form-group">
          <label class="form-label col-sm-4">방문자 선택</label>
          <div class="col-sm-7">
            <?php echo $select_worker ?>
          </div>
        </div>

        <div class="form-group">
          <label class="form-label col-sm-4">변경 사유</label>
          <div class="col-sm-7">
            <textarea name="memo" class="form-control"></textarea>
          </div>
        </div>
      </div>

      <div class="modal-footer">
        <button type="submit" class="btn btn-primary">입력</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">닫기</button>
      </div>
      </form>
    </div>
  </div>
</div><!-- /.modal -->

<script type="text/javascript">
$(document).ready(function(){
  $("#modal_change_worker form").submit(function(e){
    e.preventDefault();

    var changer = $("#select_worker", this).val();
    var memo = $("textarea", this).val();
    
    // 작업자 요청 변경
    $.ajax({
      url: "<?=base_url()?>work/ajax/change_worker",
      type: "POST",
      data: {
        id: operation.id,
        worker_id: changer,
        memo: memo,   
        extra: "request change worker",
        "csrf_test_name": $.cookie("csrf_cookie_name")
      },
      dataType: "json",
    })
      .done(function(response) {
        gs2_console(response);
        if(!response.error) {
          alert("담당자를 변경하였습니다\n페이지를 갱신합니다");
          location.reload();
        } else {
          alert(response.error_msg);
          return false;
        }
      })
      .fail(function(xhr, textStatus){
        alert("Request failed: " + textStatus);
      });
  });
});
</script>