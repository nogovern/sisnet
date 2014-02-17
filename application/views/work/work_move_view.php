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
      <table class="table table-striped">
        <tbody>
          <tr>
            <td class="col-sm-2">작업번호: <?php echo $op->operation_number; ?></td>
            <td class="col-sm-2">등록일: <?php echo $op->getDateRegister(); ?></td>
            <td class="col-sm-2">재고사무소: <?php echo $op->office->name; ?></td>
            <td class="col-sm-2">수량: <span id="total_qty" style="font-weight:bold;"></span></td>
            <td class="col-sm-2">완료시간: <?php echo $op->getDateRegister(TRUE); ?></td>
            <td class="col-sm-2">완료시간: <?php echo $op->getDateRegister(TRUE); ?></td>
          </tr>
          <tr>
            <td class="col-sm-2">상태: <?php echo constant('GS2_OP_CHANGE_STATUS_' . $op->status)?></td>
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
                <th class="col-xs-1">#</th>
                <th class="col-xs-1">타입</th>
                <th class="col-xs-2">장비종류</th>
                <th class="col-xs-2">모델명</th>
                <th class="col-xs-1">상태</th>
                <th class="col-xs-1">S/N</th>
                <th class="col-xs-1">등록수량</th>
                <th class="col-xs-1">분실수량</th>
                <th class="col-xs-1"></th>
              </tr>
            </thead>
            <tbody>

            </tbody>
          </table>
        </div>
      </div>
    </div>
    
    <div class="col-md-12">
      <a class="btn btn-default" href="<?=base_url() . 'work/change'?>">리스트</a>
      <button type="button" class="btn btn-primary">요청서 수정</button>
      <button type="button" class="btn btn-primary">장비 등록</button>
      <button type="button" class="btn btn-primary">스캔</button>
  <?php
  if($op->status == '1'):
  ?>
      <button type="submit" class="btn btn-primary">완료</button>
  <?php
  endif;
  ?>
    </div>

  </div><!-- /end of row -->
</div><!-- start of div.container -->

<!-- 공통 modal 사용하기 위한 container -->
<div class="modal fade" id="modal_container" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
    </div>
  </div>
</div><!-- /.modal -->

<script type="text/javascript">
$(document).ready(function(){

});
</script>

<?php
$this->view('layout/footer');
?>
