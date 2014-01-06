<!-- modal dialog -->
<div class="modal fade" id="modal_memo" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title">작업 메모</h4>
      </div>
      <!-- start form -->
      <form id="form_modal_memo" role="form" class="form form-horizontal">
      <div class="modal-body">
        <div class="form-group">
          <label class="form-label col-sm-4">작업 메모</label>
          <div class="col-sm-7">
            <textarea name="memo" class="form-control"></textarea>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button id="modal_memo_ok" type="submit" class="btn btn-primary">입력</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">닫기</button>
      </div>
      </form>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<script type="text/javascript">
  $(document).ready(function(){
    $("#modal_memo form").submit(function(e){
      e.preventDefault();

      alert('Sorry, 아직 구현되지 않음');
    });
  });
</script>