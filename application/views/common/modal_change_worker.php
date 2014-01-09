<!-- modal dialog -->
<div class="modal fade" id="modal_change_worker" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
      <h4 class="modal-title">방문자 변경</h4>
    </div>
    <!-- start form -->
    <form role="form" class="form form-horizontal">
    <div class="modal-body">
      <p class="help-block">
        방문자를 변경합니다. 어적구 저쩌구
      </p>

      <div class="form-group">
        <label class="form-label col-sm-4">방문자 선택</label>
        <div class="col-sm-7">
          <select name="change_worker" class="form-control">
            <option value="1">--샘플 유저 1--</option>
            <option value="2">--샘플 유저 2--</option>
            <option value="3">--샘플 유저 3--</option>
          </select>
        </div>
      </div>

      <div class="form-group">
        <label class="form-label col-sm-4">변경 메모</label>
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
</div><!-- /.modal -->

<script type="text/javascript">
$(document).ready(function(){
  $("#modal_change_worker form").submit(function(e){
    e.preventDefault();

    var changer = $("select", this).val();
    alert('debug: ' + changer + '\nSorry, 아직 구현되지 않음');
  });
});
</script>