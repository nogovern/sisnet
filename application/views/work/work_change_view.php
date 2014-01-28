<?php
$this->view('layout/header');
$this->view('layout/navbar');
?>

<!-- start of div.container -->
<div class="container">
  <div class="row">

  <div class="page-header">
    <h2><span class="fa fa-desktop"></span>&nbsp;장비 상태 변경서 :: 상태저장</h2>
  </div>

  <table class="table table-condensed">
    <tbody>
      <tr>
        <td class="col-sm-3">작업번호: <?php echo $op->operation_number; ?></td>
        <td class="col-sm-3">등록일: <?php echo $op->getDateRegister(); ?></td>
        <td class="col-sm-2">재고사무소: <?php echo $op->office->name; ?></td>
        <td class="col-sm-2">수량: </td>
        <td class="col-sm-2">완료시간: <?php echo $op->getDateFinish(); ?></td>
      </tr>
    </tbody>
  </table>
  

  <!-- 철수 장비 변경 테이블-->
<?php
// 에러 출력
echo validation_errors(); 
echo form_open('', 'id="register_form" role="form" class="form-horizontal" ');
?>
  <input type="hidden" name="op_id" value="<?=$op->id?>">
  <table class="table table-bordered">
    <thead>
      <tr class="active">
        <th>철수 작업번호</th>
        <th>시리얼</th>
        <th>장비종류</th>
        <th>모델</th>
        <th>철수점포</th>
        <th>수량</th>
        <th>상태(가용/수리/폐기)</th>
        <th>삭제</th>
      </tr>
    </thead>
    <tbody>
<?php
foreach($op->targets as $t):
  $row = $t->target;        // 대상 작업 Entity

  $item_count = count($row->getItems());
  $idx = 0;
  foreach($row->getItems() as $item) {
?>      
      <tr data-opid="<?=$row->id?>" data-itemid="<?=$item->id?>" data-qty="<?=$item->qty_request?>" class="op-item">
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

    $input_name = 'items['. $item->id .']'; 
?> 
        <td><?=$item->serial_number?></td>
        <td><?=$item->part->category->name?></td>
        <td><?=$item->part_name?></td>
        <td><?=gs2_decode_location($row->work_location)->name?></td>
        <td><?=$item->qty_request?></td>
        <td data-itemid="<?=$item->id?>">
          <input type="text" name="<?=$input_name?>[0]" value="0" style="width:30px;">/
          <input type="text" name="<?=$input_name?>[1]" value="0" style="width:30px;">/
          <input type="text" name="<?=$input_name?>[2]" value="0" style="width:30px;"></td>
        <td>삭제</td>
      </tr>
<?php
    $idx = $idx + 1;
  }
endforeach;
?>
    </tbody>
  </table>

  <div>
    <button type="submit" class="btn btn-primary">완료</button>
    <button type="button" class="btn btn-default" >리스트</button>
    <button id="btnSample" type="button" class="btn btn-info" data-target="#modal_select_op1" data-toggle="modal">철수 완료 작업 리스트 불러오기</button>
  </div>
  </form>

  </div><!-- /end of row -->
</div><!-- start of div.container -->

<!-- 공통 modal 사용하기 위한 container -->
<div class="modal fade" id="modal_container" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
    </div>
  </div>
</div><!-- /.modal -->

<!-- jquery form validation -->
<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
<script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.min.js"></script>

<script type="text/javascript">
var items = [];
var sums = [];
var requests = [];

function makeIt() {
  $("tr.op-item").each(function(){
    var idx = $(this).data('itemid');
    items[idx] = [];
    $("input", this).each(function(n){ 
      items[idx][n] = ($(this).val()); 
    });
  });

  // 1번만 실행
  if(requests.length == 0) {
    $("tr.op-item").each(function(){
      var idx = $(this).data('itemid');
      requests[idx] = parseInt($(this).data('qty'), 10);
    });
  }

  // console.log(typeof items);
  var sum,
      j;

  for(var i in items) {
    sum = 0;
    for(j=0; j < items[i].length; j++) {
      sum += parseInt(items[i][j], 10);
    }
    sums[i] = sum;
  }

  gs2_console(sums);
}

function checkCount() {

}

$(document).ready(function(){

  // 장비 수량들 배열 만들기
  $("input").change(makeIt);
  makeIt();

  $("#btnSample").click(function(e){
    var url = '<?=site_url('tests/modal')?>'; 
    $('#modal_container .modal-content').load(url, function(){
      $("#modal_container").modal('show');
    });
  });

  // 전송
  $("form").submit(function(){
    
    $("tr.op-item input").css('background-color', '');
    for(var i in items) {
      if(sums[i] !== requests[i]) {
        $("tr[data-itemid="+ i +"] input").css('background-color', 'yellow');
        $("tr[data-itemid="+ i +"] input").first().focus();
        alert('각 상태의 합이 수량과 같아야 합니다');
        return false;
      }
    }
  });
});
</script>

<?php
$this->view('layout/footer');
?>
