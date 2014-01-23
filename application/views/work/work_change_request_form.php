<?php
$this->view('layout/header');
$this->view('layout/navbar');
?>

<!-- start of div.container -->
<div class="container">
  <div class="row">

  <div class="page-header">
    <h2><span class="fa fa-desktop"></span>&nbsp;장비 상태 변경서 :: 등록</h2>
  </div>

  <table class="table table-condensed">
    <tbody>
      <tr>
        <td class="col-sm-3">작업번호: XXXXX</td>
        <td class="col-sm-3">등록일: XXXXX</td>
        <td class="col-sm-2">재고사무소: XXXXX</td>
        <td class="col-sm-2">수량: </td>
        <td class="col-sm-2">완료시간: </td>
      </tr>
    </tbody>
  </table>
  
  <!-- 철수 작업 목록 -->
  <table class="table">
    <thead>
      <tr class="warning">
        <th>선택</th>
        <th>#</th>
        <th>완료일</th>
        <th>구분</th>
        <th>상태</th>
        <th>대상점포</th>
        <th>담당사무소</th>
      </tr>
    </thead>
    <tbody>
<?php
foreach($rows as $row):
?>      
      <tr>
        <td><input type="checkbox" name="sel" value="1"></td>
        <td><?=$row->id?></td>
        <td><?=$row->getDateFinish()?></td>
        <td><?=gs2_op_type($row->type)?></td>
        <td><?=constant('GS2_OP_CLOSE_STATUS_' . $row->status)?></td>
        <td><?=gs2_decode_location($row->work_location)->name?></td>
        <td><?=$row->office->name?></td>
      </tr>
<?php
endforeach;
?>      
    </tbody>
  </table>

  <!-- 철수 장비 변경 테이블-->
  <table class="table table-bordered">
    <thead>
      <tr class="active">
        <th>철수 작업번호</th>
        <th>시리얼</th>
        <th>장비종류</th>
        <th>모델</th>
        <th>철수점포</th>
        <th>수량</th>
        <th>상태 선택</th>
        <th>삭제</th>
      </tr>
    </thead>
    <tbody>
<?php
foreach($rows as $row):
  $item_count = count($row->getItems());
  $idx = 0;
  foreach($row->getItems() as $item):
?>      
      <tr data-opid="<?=$row->id?>">
<?php
if($item_count == 1 && $idx < $item_count) {
?> 
        <td><?=$row->operation_number?></td>
<?php
} else {
  if( $idx == 0) {
?>
        <td rowspan="<?=$item_count?>"><?=$row->operation_number?></td>
<?php    
  }
} 
?> 
        <td><?=$item->serial_number?></td>
        <td><?=$item->part_name?></td>
        <td><?=$item->part->category->name?></td>
        <td><?=gs2_decode_location($row->work_location)->name?></td>
        <td><?=$item->qty_complete?></td>
        <td data-itemid="<?=$item->id?>">
          <input type="text" name="qty1" value="0" style="width:30px;">/
          <input type="text" name="qty2" value="0" style="width:30px;">/
          <input type="text" name="qty3" value="0" style="width:30px;"></td>
        <td>삭제</td>
      </tr>
<?php
  $idx = $idx + 1;
  endforeach;
endforeach;
?>
    </tbody>
  </table>

  <div>
    <button id="btnOpen" type="button" class="btn btn-primary" data-target="#modal_select_op1" data-toggle="modal">Open</button>
  </div>

  </div><!-- /end of row -->
</div><!-- start of div.container -->

<!-- jquery form validation -->
<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
<script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.min.js"></script>

<script type="text/javascript">
$(document).ready(function(){
  // ajax 로 외부 페이지 load 하여 modal 여는 방법
  $("#btnOpen").click(function(){
    var url = "<?=site_url('tests/opnumber')?>";
    $("#modal_select_op1 .modal-body").load(url, function(result){
      $("#modal_select_op1").modal('show');
    });
  });
});
</script>

<!-- 철수완료 작업 리스트 modal dialog -->
<div class="modal fade" id="modal_select_op1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title">철수완료 작업 리스트</h4>
      </div>
      <div class="modal-body">
        
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary">저장</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">닫기</button>
      </div>
    </div>
  </div>
</div><!-- /.modal -->

<?php
$this->view('layout/footer');
?>
