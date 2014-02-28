<!-- 작업 완료 modal dialog -->
<div class="modal fade" id="modal_op_complete_container" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="top:10%;">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title">작업 완료</h4>
      </div>
      <div class="modal-body" style="padding:10px 20px;">

      </div>
      <div class="modal-footer">
        <button id="btn_iframe_submit" type="submit" class="btn btn-primary">완료</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">닫기</button>
      </div>
    </div>
  </div>
</div><!-- /.modal -->

<script type="text/javascript">
$(document).ready(function(){
  var iframe_form = $("#modal_op_complete_container iframe form");
  $("#date_complete", iframe_form).datepicker({
    minDate: new Date()
  });

  // 폼 전송
  $("#btn_iframe_submit").on('click', function(e){
    $("#modal_op_complete_container iframe").contents().find("form").submit();
  })
}); 

function iframe_callback() {
  alert('불렀나요?');
} 

</script>