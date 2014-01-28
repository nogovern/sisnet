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
<?php
// 에러 출력
echo validation_errors(); 
echo form_open('', 'id="register_form" role="form" class="form-horizontal" ');
?>
      <input type="hidden" name="op_type" value="900">
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
      <table class="table" id="target_operations">
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
            <td><input type="checkbox" name="target_ops[]" value="<?=$row->id?>"></td>
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
      <div class="col-sm-12">
        <button type="submit" class="btn btn-primary" >저장</button>
        <button type="button" class="btn btn-default" >리스트</button>
      </div>
    </form>


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


  // 예제
  $("form").submit(function(e){
    // 선택된 checkbox 값을 배열에 저장 하는 방법
    var arr = $.map($('#register_form :checkbox:checked'), function(e,i) {
      return +e.value;
    });

    if(arr.length < 1) {
      alert('상태변경을 요청 할 1개이상의 작업(업무)를 선택하셔야 합니다');
      return false;
    }
    gs2_console(arr.toString());
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
