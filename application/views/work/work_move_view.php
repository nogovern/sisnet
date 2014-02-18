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
            <td class="col-sm-3">등록일: <?php echo $op->getDateRegister(TRUE); ?></td>
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
              <tr>
                <td colspan="9">등록된 장비가 없습니다</td>
              </tr>
<?php
} else {
  $idx = 1;
  foreach($op->getItems() as $item):
?>
              <tr data-item_id="<?=$item->id?>">
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
      <a class="btn btn-default" href="<?=base_url() . 'work/change'?>"><i class="fa fa-list"></i> 리스트</a>
      <button id="btn_cancel_request" class="btn btn-danger" type="button">요청취소</button>
      <button id="btn_move_edit_form" type="button" class="btn btn-info">요청서 수정</button>
      <button id="btn_move_part_add" type="button" class="btn btn-warning">장비 등록</button>
      <button id="btn_move_send" type="button" class="btn btn-primary">장비 발송</button>

      <button id="btn_move_part_scan" type="button" class="btn btn-primary">스캔</button>
  <?php
  if($op->status):
  ?>
      <button id="btn_move_op_complete" type="button" class="btn btn-success">완료</button>
  <?php
  endif;
  ?>
    </div>

  </div><!-- /end of row -->
</div><!-- start of div.container -->

<?php
/**
 * modal include 
 */

$this->view('common/modal_part_register');      // 설치 장비등록 사용
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
var count_item = <?=$item_count?>;

$(document).ready(function(){
  // 장비등록
  $("#btn_move_part_add").click(function(){
    $("#modal_part_register").modal('show');
  });

  // 장비삭제
  $("#item_list .remove_item").click(function(){
    // confirm('삭제할까요?');
  });
});

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
  
  var tr = $("<tr/>").attr('data-item_id', id);
  tr.append($("<td/>").text(idx));
  tr.append($("<td/>").text(type_text));
  tr.append($("<td/>").text(sn));
  tr.append($("<td/>").text(item.cat_name));
  tr.append($("<td/>").text(item.name));
  tr.append($("<td/>").text((is_new == 'Y') ? '신품' : '중고'));
  tr.append($("<td/>").text(qty));
  tr.append($("<td/>").text(''));
  tr.append($("<td/>").html('<button class="btn btn-danger btn-xs remove_item" type="button">X</button>'));

  $("#item_list tbody").append(tr);

}

function callback_remove_row(what) {
  $(what).closest('tr').fadeOut('slow').remove();
}
</script>

<?php
$this->view('layout/footer');
?>
