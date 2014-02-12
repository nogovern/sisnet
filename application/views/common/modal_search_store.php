<?php
/**
 * 점포 검색
 */
?>

<!-- 점포 검색 modal dialog -->
<div class="modal fade" id="modal_store_search_result" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="top:10%;">
  <div class="modal-dialog" style="width:80%;">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title">점포 검색 리스트</h4>
      </div>
      <div class="modal-body" style="padding:10px 20px;">

        <p class="text-info">결과수 : <span class="num_rows"></span> 건</p>
        <div class="row table-scroll" style="margin:0;padding:0;overflow-y:auto;height:250px;">
          <table class="table table-hover table-condensed">
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
  //신규 등록
  $("#btn_modal_store_register").click(function(e){
    $("#modal_store_search_result").modal("hide");
    callback_store_register();
  });
});

// 결과에서 선택
$(document).on('click', '.select_me', function(e){
  // 상점 id, name
  var store_id = $(this).closest('tr').find('td:eq(0)').text();
  var store_name = $(this).closest('tr').find('td:eq(1)').text();

  // callback 함수 사용하여 부모창 element 에 설정
  callback_store_info(store_id, store_name);

  $("#modal_store_search_result").modal("hide");
});

// 행 클릭했을때 색 반전
$(document).on('click', '#modal_store_search_result tr', function(){
  $("#modal_store_search_result tr").removeClass('selected');
  $(this).addClass('selected');
});
</script>