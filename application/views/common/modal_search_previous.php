<?php
/**
 * 직전위치 검색 용 modal
 */
?>
<!-- modal dialog -->
<div class="modal fade" id="modal_search_previous" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog" style="width:70%;">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h2 class="modal-title">장비 직전 위치 검색</h2>
      </div>
      <!-- .modal-body -->
      <div class="modal-body" style="min-height:250px;overflow-y: auto;">
        <button id="btn_test" type="button" class="btn btn-danger">Click Me!</button>
        
      </div>
      <!-- /.modal-body -->
      
      <div class="modal-footer">
        <button id="btn_close_modal" type="button" class="btn btn-default" >닫기</button>
      </div>
    </div>
  </div>
</div><!-- /.modal -->

<script type="text/javascript">
$(document).ready(function(){
  // 현재 창 닫기
  $("#btn_close_modal").click(function(){
    $("#modal_part_register").modal('show');
    $("#modal_search_previous").modal('hide');
  });

});

// 검색 목록 장비 중 선택했을 경우
$(document).on('click', '.select_me', function(e){
  var spart_id = $(this).data('spid');
  var target_url = "<?=base_url()?>ajax/get_serial/" + spart_id;

  $.ajax({
    url: target_url,
    type: "POST",
    data: {
      office_id: operation.office_id,
      sp_id: spart_id,
      extra: "get serial part by SN",
      "csrf_test_name": $.cookie("csrf_cookie_name")
    },
    dataType: "json",
  })
    .done(function(response) {
      if(!response.error) {
        set_serialinfo(response.info);    // 시리얼 장비 정보 셋팅
        enableAddItem();                  // 등록 버튼 활성화
      } else {
        gs2_console(response);
        $("#query").val('').focus();
      }
    })
    .fail(function(xhr, textStatus){
      alert("Request failed: " + textStatus);
    });

  // 창 닫기
  $("#modal_part_register").modal('show');
  $("#modal_search_previous").modal('hide');
});
</script>

