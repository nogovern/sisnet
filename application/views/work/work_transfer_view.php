<?php
/**
 * 이관 - 상세보기 
 */

$this->view('layout/header');
$this->view('layout/navbar');

if($op->type == '801') {
  $sender   = gs2_decode_location($op->work_location);
  $receiver = $op->office;
  $s_name   = $sender->name;
  $r_name   = $receiver->name . " (시스네트)";
} else {
  $sender   = $op->office;
  $receiver = gs2_decode_location($op->work_location);
  $s_name   = $sender->name . " (시스네트)";
  $r_name   = $receiver->name;
}
?>

<!-- start of div.container -->
<div class="container">
  <div class="row">
    <div class="page-header">
      <h2><span class="fa fa-desktop"></span>&nbsp;이관 업무</h2>
    </div>

    <div class="col-md-12">
      <table class="table" style="border:2px solid #CCC;">
        <tbody>
          <tr>
            <td class="col-sm-3">작업번호: <?php echo $op->operation_number; ?></td>
            <td class="col-sm-3">형태 : <?php echo gs2_op_type($op->type); ?></td>
            <td class="col-sm-3">상태 : <?php echo constant('GS2_OP_MOVE_STATUS_' . $op->status) ?></td>
            <td class="col-sm-3">등록일: <?php echo $op->getDateRegister(); ?></td>
          </tr>
          <tr>
            <td class="col-sm-3">송신 업체: <?php echo $s_name; ?></td>
            <td class="col-sm-3">수신 업체: <?php echo $r_name; ?></td>
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
$scan_count = 0;

if($item_count == 0) {
?>    
              <tr class="no-item">
                <td colspan="9">등록된 장비가 없습니다</td>
              </tr>
<?php
} else {
  $idx = 1;
  foreach($op->getItems() as $item):
    // scan 카운트 증가
    if($item->isScan()) {
      $scan_count = $scan_count + 1;
    }
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
                  <button class="btn btn-danger btn-xs remove_item" type="button">X</button>
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
      <a class="btn btn-default" href="<?=base_url() . 'work/transfer'?>"><i class="fa fa-list"></i> 리스트</a>
<?php if($op->status < '3'): ?>
      <button id="btn_cancel_request" class="btn btn-danger" type="button">요청취소</button>
      <button id="btn_edit_form" type="button" class="btn btn-info">요청서 수정</button>
      <button id="btn_add_item" type="button" class="btn btn-warning">장비 등록</button>
      <button id="btn_op_complete" type="button" class="btn btn-success">완료</button>
<?php endif; ?>
    </div>

  </div><!-- /end of row -->
</div><!-- start of div.container -->

<?php
/**
 * modal include 
 */

$this->view('common/modal_part_register2');      // 장비등록 모달
$this->view('common/modal_search_previous');    // 직전위치 검색 결과 모달

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

/* 스캔 작업에서는 numItem == numSacn 이어야 완료 가능 */
var numItem = 0;    // 등록된 장비 모델 수
var numScan = <?php echo $scan_count; ?>;        // 스캔된 장비 모델 수

$(document).ready(function(){
  // modal 공통 설정
  $(".modal").modal({backdrop: 'static', show: false});

  // 발송 버튼 활성화 여부
  checkPartRegistered();

  // 장비등록
  $("#btn_add_item").click(function(){
    $("#modal_add_op_item").modal('show');
  });

  // 장비삭제
  $("#item_list .remove_item").click(function(){
    // modal_part_register 에 정의 되어 있음
  });

  //////////////
  // 장비발송 ( 이부분은 이관에서 사용 안 함)
  //////////////
  $("#btn_send").click(function(){
    if(!confirm("등록된 장비를 수신 업체로 보냅니다.\n수신처에서 장비 확인 해야 합니다")) {
      return false;
    }

    // 장비발송 실행
    $.ajax({
      url: "<?=base_url()?>work/transfer/ajax_send",
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
  $("#btn_part_scan").click(function(){
    $("#modal_part_scan").modal("show");
  });

  // 완료
  $("#btn_op_complete").click(function(){
    if(!confirm("이관 업무를 완료합니다.\n완료 시 실제 재고량이 변경됩니다.\n계속 하시겠습니까?")){
      return false;
    }

    $.get( _base_url + "ajax/complete/" + operation.id, function( data ) {
      alert( "업무 완료!" );
      gs2_console(data);
      location.reload();
    });
  });

  //////////
  // 요청취소 
  //////////
  $("#btn_cancel_request").click(function(){
    if(numItem > 0) {
      alert("먼저 등록된 장비를 삭제 후 진행해 주십시요");
      return false;
    }

    // 이 함수 내에서 confirm 으로 확인
    gs2_cancel_operation(_base_url + "work/transfer");
  });

});

// 장비가 등록 되어 있는지 확인 
function checkPartRegistered() {
  var len = $("#item_list tbody tr.op-item").length;
  var total = 0;

  if(len > 0) {
    $("#item_list tbody tr.op-item td:nth-child(7)").each(function(n){
      total += parseInt($(this).text(), 10);
    });

    $("#btn_op_complete").prop('disabled', false);
  } else {
    $("#btn_op_complete").prop('disabled', true);
  }

  numItem = len;
  $("#total_qty").text(total);
}

// 스캔 처리 에 따른 화면 요소 변경
function display() {
  if(numScan == 0) {
    $("#btn_scan_reset").prop('disabled', true);
  } else {
    $("#btn_scan_reset").prop('disabled', false);
  }

  if(numItem > 0 && numScan == numItem) {
    $("#btn_op_complete").prop('disabled', false);
  } else {
    $("#btn_op_complete").prop('disabled', true);
  }
}

////////////////////////
/// callback 함수 등록
////////////////////////
function callback_insert_row(id, is_new, qty) {
  var type_text = '';
  if( item.type == '1') type_text = '시리얼';
  if( item.type == '2') type_text = '수량';
  if( item.type == '3') type_text = '소모품';

  var idx = $("#item_list tbody tr.op-item").length + 1;
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
