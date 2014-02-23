<?php
$this->view('layout/header');
$this->view('layout/navbar');
?>
<!-- start of div.container -->
<div class="container">
  <!-- Main hero unit for a primary marketing message or call to action -->
  <div class="page-header">
    <h1><span class="fa fa-desktop"></span>&nbsp;폐기 업무</h1>
  </div>
 
  <!-- Example row of columns -->
  <div class="row">
    <div class="col-md-12">
      
      <!-- filter -->
      <div class="well well-sm">
        <form method="post" id="filter-form" class="form-inline" role="search">
          <input type="hidden" name="csrf_test_name" value="<?php echo $this->security->get_csrf_hash();?>">
          <div class="form-group">
            진행상태 : 
            <?php echo $status_filter; ?>
          </div>

          <div class="form-group">
            &nbsp;&nbsp;작업형태 : 
            <?php echo $type_filter; ?>
          </div>

          <div class="form-group">
            &nbsp;&nbsp;사무소:
            <?php echo $office_filter; ?>
          </div>

          <div class="form-group">
            &nbsp;&nbsp; 
            <button type="submit" class="btn btn-primary btn-sm">검색</button> 
          </div>
        </form>
      </div>

      <table class="table table-hover">
        <thead>
          <tr>
            <th>No</th>
            <th>작업구분</th>
            <th>요청자</th>
            <th>승인/폐기 업체</th>
            <th>요청/출고 수량</th>
            <th>재고사무소</th>
            <th>상태</th>
            <th>등록일</th>
            <th>완료일</th>
            <th>&nbsp;</th>
          </tr>
        </thead>

        <tbody>
<?php
foreach($rows as $row):
  switch($row->status) {
    case '1': $label_color = 'label-default';break;
    case '2': $label_color = 'label-warning';break;
    case '3': $label_color = 'label-success';break;
    default : $label_color = 'label-default';break;
  }

  if( $row->type == '601') {
    $company_name = 'GS';
  } else {
    $company_name = gs2_location_name($row->work_location);
  }

?>
          <tr class="">
            <td><?=$row->id?></td>
            <td><?=gs2_op_type($row->type)?></td>
            <td><?=$row->user->name?></td>
            <td><?=$company_name?></td>
            <td><?=$row->getTotalRequestQty()?></td>
            <td><?=$row->office->name?></td>
            <td>
              <span class="label <?=$label_color?>"><?=constant("GS2_OP_MOVE_STATUS_" .$row->status)?></span>
            </td>
            <td><?=$row->getDateRegister();?></td>
            <td><?=$row->getDateFinish();?></td>
            <td><button class="btn btn-default btn-sm btn_view" type="button" data-href="<?=site_url('work/destroy/view/') . '/' . $row->id ?>">보기</button></td>
          </tr>
<?php
endforeach;
?>
        </tbody>
      </table>
      <!-- Pagination -->
      <div style="text-align:center">
        <?=$pagination?>
      </div>

      <p>
        <button id="btn_request_destroy" type="button" class="btn btn-primary"><i class="fa fa-pencil-square-o"></i>&nbsp;요청서 등록</button>
      </p>

    </div>
  </div>
</div><!-- end of container -->

<!-- modal dialog -->
<div class="modal fade" id="modal_request_destroy" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title">폐기 요청서</h4>
      </div>
      <!-- start form -->
      <form role="form" class="form form-horizontal" method="post" action="<?php echo site_url('work/destroy/register');?>">
        <input type="hidden" name="csrf_test_name" value="<?php echo $this->security->get_csrf_hash();?>">
        <div class="modal-body">
          <div class="well well-sm">
            <span class="text-danger">장비 폐기 요청을 등록합니다.</span>
          </div>
          
          <div class="form-group">
            <label class="form-label col-sm-3">형태</label>
            <div class="input-group col-sm-6">
              <select name="op_type" id="op_type" class="form-control">
                <option value="0">--선택하세요--</option>
                <option value="601">승인</option>
                <option value="602">출고</option>
              </select>
            </div>
          </div>

          <div class="form-group">
            <label class="form-label col-sm-3">재고 사무소</label>
            <div class="input-group col-sm-6">
              <?php echo $select_office ?>
            </div>
          </div>

        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">완료</button>
          <button type="button" class="btn btn-default" data-dismiss="modal">취소</button>
        </div>
      </form>
    </div>
  </div>
</div><!-- /.modal -->


<script type="text/javascript">

// notEqual validate rule 추가
jQuery.validator.addMethod("notEqual", function(value, element, param) {
  return this.optional(element) || value != param;
}, "송신사무소와 수신사무소와 같을 수 없습니다");

$(document).ready(function(){
  // open modal
  $("#btn_request_destroy").click(function(){
    $("#modal_request_destroy").modal('show');
  });  

  // 상세 보기 페이지로 이동
  $("button.btn_view").click(function(){
    var href = $(this).data('href');
    location.href = href;
    return false;
  });

  $("#modal_request_destroy form").validate({
    rules: {
      select_office: {
        required: true,
        min: 1
      },
      op_type: {
        required: true,
        min: 1,
      }
    },
    messages: {
      select_office: {
        min: '재고 사무소를 선택하세요'
      },
      op_type: {
        min: '폐기업무 형태를 선택하세요'
      }
    },
    submitHandler: function(form) {
      form.submit();
    }

  });

  /////////////////////////
  // bootstrap 3 popover //
  /////////////////////////
  $(".popover_memo").popover({trigger: 'hover', placement: 'left'});
  $(".popover").click(function(e){e.preventDefault();});

  ///////////////////////
  // 검색 필터 전송 
  ///////////////////////
  $("#filter-form").submit(function() {
    var url = _base_url + 'work/destroy/?';
    var query = $(this).serialize();
    
    $(this).prop('action', url + query);
  });

});
</script>

<?php
$this->view('layout/footer');
?>