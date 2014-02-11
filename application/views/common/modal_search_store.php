<?php
/**
 * 점포 검색
 */
?>
<!-- 점포 검색 modal dialog -->
<div class="modal fade" id="modal_store_search_result" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog" style="width:70%;height:300px;">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title">점포 검색 리스트</h4>
      </div>
      <div class="modal-body" style="padding:10px 20px;">
        <h5>점포 검색 결과 : <span id="cnt_result"></span> 건</h5>
        <table class="table table-hover table-condensed" style="display: block;overflow-y: auto;height:230px; margin:0;">
          <thead>
            <tr>
              <th></th>
              <th>점포명</th>
              <th>점주</th>
              <th>주소</th>
              <th>연락처</th>
              <th>상태</th>
              <th>선택</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
            </tr>
          </tbody>
        </table>
      </div>
      <div class="modal-footer">
        <button id="btn_modal_store_register" type="button" class="btn btn-primary">신규 등록</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">닫기</button>
      </div>
    </div>
  </div>
</div><!-- /.modal -->

<script type="text/javascript">
  $(document).ready(function(){
    $("#btn_modal_store_register").click(function(e){
      $("#modal_store_search_result").modal("hide");
      callback_store_register();
    });
  });

  $(document).on('click', '.select_me', function(e){
    // 상점 id, name
    var store_id = $(this).closest('tr').find('td:eq(0)').text();
    var store_name = $(this).closest('tr').find('td:eq(1)').text();

    // callback 함수 사용하여 부모창 element 에 설정
    callback_store_info(store_id, store_name);

    $("#modal_store_search_result").modal("hide");
  });
</script>