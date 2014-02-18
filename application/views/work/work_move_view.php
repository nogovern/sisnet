<?php
$this->view('layout/header');
$this->view('layout/navbar');
?>

<!-- start of div.container -->
<div class="container">
  <div class="row">
    <div class="page-header">
      <h2><span class="fa fa-desktop"></span>&nbsp;이동 업무</h2>
    </div>

    <div class="col-md-12">
      <table class="table" style="border:2px solid #CCC;">
        <tbody>
          <tr>
            <td class="col-sm-3">작업번호: <?php echo $op->operation_number; ?></td>
            <td class="col-sm-3">상태 : <?php echo constant('GS2_OP_MOVE_STATUS_' . $op->status) ?></td>
            <td class="col-sm-3">등록일: <?php echo $op->getDateRegister(); ?></td>
            <td class="col-sm-3"></td>
          </tr>
          <tr>
            <td class="col-sm-3">송신 사무소: <?php echo $op->office->name; ?></td>
            <td class="col-sm-3">수신 사무소: <?php echo gs2_decode_location($op->work_location)->name; ?></td>
            <td class="col-sm-3">수량: <span id="total_qty" style="font-weight:bold;"></span></td>
            <td class="col-sm-3">완료일: <?php echo $op->getDateFinish(TRUE); ?></td>
          </tr>
        </tbody>
      </table>
    </div>

    <div class="col-md-12">
      <div class="panel panel-info">
        <div class="panel-heading"><i class="fa fa-tags"></i> 장비 리스트</div>
        <div class="panel-body">
          <table id="item_list" class="table table-hover table-condensed table-responsive" >
            <thead>
              <tr>
                <th class="col-xs-1"></th>
                <th class="col-xs-1">타입</th>
                <th class="col-xs-2">S/N</th>
                <th class="col-xs-2">장비종류</th>
                <th class="col-xs-2">모델명</th>
                <th class="col-xs-1">상태</th>
                <th class="col-xs-1">수량</th>
                <th class="col-xs-1">확인</th>
                <th class="col-xs-1"></th>
              </tr>
            </thead>
            <tbody>
<?php

$item_count = $op->numItems();
if($item_count == 0) {
?>    
              <tr class="no-item">
                <td colspan="9">등록된 장비가 없습니다</td>
              </tr>
<?php
} else {
  $idx = 1;
  foreach($op->getItems() as $item):
?>
              <tr class="op-item" data-item_id="<?=$item->id?>">
                <td><?php echo $idx++; ?></td>
                <td><?php echo gs2_part_type($item->part_type); ?></td>
                <td><?php echo $item->serial_number; ?></td>
                <td><?php echo $item->part->category->name; ?></td>
                <td><?php echo $item->part_name; ?></td>
                <td><?php echo $item->isNew() ? '신품' : '중고'; ?></td>
                <td><?php echo $item->getQtyRequest(); ?></td>
                <td><?php echo $item->isComplete(); ?></td>
                <td>
<?php if($op->status == '1'): ?>
                  <button class="btn btn-danger btn-xs remove_item" type="button">X</button>
<?php endif; ?>                  
                </td>
              </tr>
<?php
  endforeach;  
} 
?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
    
    <div class="col-md-12">
      <a class="btn btn-default" href="<?=base_url() . 'work/change'?>"><i class="fa fa-list"></i> 리스트</a>
<?php if($op->status == '1'): ?>
      <button id="btn_cancel_request" class="btn btn-danger" type="button">요청취소</button>
      <button id="btn_move_edit_form" type="button" class="btn btn-info">요청서 수정</button>
      <button id="btn_move_part_add" type="button" class="btn btn-warning">장비 등록</button>
      <button id="btn_move_send" type="button" class="btn btn-primary">장비 발송</button>
<?php endif; ?>

<?php if($op->status == '2'): ?> 
      <button id="btn_move_part_scan" type="button" class="btn btn-primary">스캔</button>
      <button id="btn_move_op_complete" type="button" class="btn btn-success">완료</button>
<?php endif; ?>
    </div>

  </div><!-- /end of row -->
</div><!-- start of div.container -->

<?php
/**
 * modal include 
 */

if($op->status == '1') {
  $this->view('common/modal_part_register');      // 설치 장비등록 사용
  $this->view('common/modal_search_previous');    // 직전위치 검색용
}
?>

<!-- 공통 modal 사용하기 위한 container -->
<div class="modal fade" id="modal_container" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
    </div>
  </div>
</div><!-- /.modal -->

<script type="text/javascript">
// 작업 정보 객체
var operation = {
  id : <?=$op->id?>,
  office_id : <?=$op->office->id?>,
  type: "<?=$op->type?>",
  status: "<?=$op->status?>"
};

// 장비 변수
var items = []; 
var item = {};          // 현재 선택된 장비 정보
var numItemRows = 0;    

$(document).ready(function(){
  // modal 공통 설정
  $(".modal").modal({backdrop: 'static', show: false});

  // 발송 버튼 활성화 여부
  checkPartRegistered();

  // 장비등록
  $("#btn_move_part_add").click(function(){
    $("#modal_part_register").modal('show');
  });

  // 장비삭제
  $("#item_list .remove_item").click(function(){
    // modal_part_register 에 정의 되어 있음
  });

  // 장비발송
  $("#btn_move_send").click(function(){
    if(!confirm("등록된 장비를 수신사무소로 보냅니다.\n수신처에서 장비 확인 해야 합니다")) {
      return false;
    }

    // 장비발송 실행
    $.ajax({
      url: "<?=base_url()?>work/move/send",
      type: "POST",
      data: {
        "id": operation.id,   
        "extra": "sending for move operation",
        "csrf_test_name": $.cookie("csrf_cookie_name")
      },
      dataType: "json",
    })
      .done(function(response) {
        // gs2_console(response);
        if(!response.error) {
          alert("등록한 장비를 발송 상태로 변경 하였습니다");
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

  // 장비스캔
  $("#btn_move_part_scan").click(function(){

  });

  // 완료
  $("#btn_move_op_complete").click(function(){

  });

  // 취소
});

// 장비가 등록 되어 있는지 확인 
function checkPartRegistered() {
  var len = $("#item_list tbody tr.op-item").length;
  var total = 0;

  if(len == 0) {
    $("#btn_move_send").prop('disabled', true);
  } else {
    $("#btn_move_send").prop('disabled', false);

    $("#item_list tbody tr.op-item td:nth-child(7)").each(function(n){
      total += parseInt($(this).text(), 10);
    });
  }

  numItemRows = len;
  $("#total_qty").text(total);
}

////////////////////////
/// callback 함수 등록
////////////////////////
function callback_insert_row(id, is_new, qty) {
  var type_text = '';
  if( item.type == '1') type_text = '시리얼';
  if( item.type == '2') type_text = '수량';
  if( item.type == '3') type_text = '소모품';

  var idx = $("#item_list tbody tr").length + 1;
  var sn = $("#serial_number").val();
  
  var tr = $("<tr/>").addClass('op-item').attr('data-item_id', id);
  tr.append($("<td/>").text(idx));
  tr.append($("<td/>").text(type_text));
  tr.append($("<td/>").text(sn));
  tr.append($("<td/>").text(item.cat_name));
  tr.append($("<td/>").text(item.name));
  tr.append($("<td/>").text((is_new == 'Y') ? '신품' : '중고'));
  tr.append($("<td/>").text(qty));
  tr.append($("<td/>").text(''));
  tr.append($("<td/>").html('<button class="btn btn-danger btn-xs remove_item" type="button">X</button>'));

  $("#item_list tbody tr.no-item").remove();
  $("#item_list tbody").append(tr);
  checkPartRegistered();
}

function callback_remove_row(what) {
  $(what).closest('tr').fadeOut('slow').remove();
  checkPartRegistered();
}
</script>

<?php
$this->view('layout/footer');
?>
