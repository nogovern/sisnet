<?php
$this->view('layout/header');
$this->view('layout/navbar');
?>
<!-- start of div.container -->
<div class="container">
  <!-- Main hero unit for a primary marketing message or call to action -->
  <div class="page-header">
    <h1><span class="fa fa-desktop"></span>&nbsp;이동 업무</h1>
  </div>
 
  <!-- Example row of columns -->
  <div class="row">
    <div class="col-md-12">
      <ul class="nav nav-pills">
        <li class="<?=($status=='')?'active':''?>"><a href="#">전체</a></li>
        <li class=""><a href="#">요청</a></li>
        <li class=""><a href="#">요청확정</a></li>
        <li class=""><a href="#">점포완료</a></li>
        <li class=""><a href="#">작업완료</a></li>
        <li class=""><a href="#">완료</a></li>
      </ul>

      <table class="table table-hover">
        <thead>
          <tr>
            <th>No</th>
            <th>작업형태</th>
            <th>요청자</th>
            <th>재고사무소</th>
            <th>진행상태</th>
            <th>상태변경 장비수량</th>
            <th>등록일</th>
            <th>완료일</th>
            <th>메모</th>
            <th>&nbsp;</th>
          </tr>
        </thead>

        <tbody>
<?php
foreach($rows as $row):
switch($row->status) {
  case '1': $label_color = 'label-default';break;
  case '2': $label_color = 'label-info';break;
  case '3': $label_color = 'label-warning';break;
  case '4': $label_color = 'label-success';break;
  default : $label_color = 'label-default';break;
}

?>
          <tr class="">
            <td><?=$row->id?></td>
            <td><?=$row->type?></td>
            <td><?=$row->user->name?></td>
            <td><?=$row->office->name?></td>
            <td>
              <span class="label <?=$label_color?>"><?=constant("GS2_OP_CLOSE_STATUS_" .$row->status)?></span>
            </td>
            <td>0</td>
            <td><?=$row->getDateRegister();?></td>
            <td><?=$row->getDateFinish();?></td>
            <td><button class="btn btn-default btn-sm btn_view" type="button" data-href="<?=site_url('work/move/view/') . '/' . $row->id ?>">보기</button></td>
          </tr>
<?php
endforeach;
?>
        </tbody>

      </table>

      <p>
        <button id="btn_request_move" type="button" class="btn btn-primary"><i class="fa fa-pencil-square-o"></i>&nbsp;요청서 등록</button>
      </p>

    </div>
  </div>
</div><!-- end of container -->

<!-- modal dialog -->
<div class="modal fade" id="modal_request_move" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title">이동 요청서</h4>
      </div>
      <!-- start form -->
      <form role="form" class="form form-horizontal" method="post" action="<?php echo site_url('work/move/register');?>">
      <div class="modal-body">
        <div class="well well-sm">
          <span class="text-danger">장비 이동 요청을 등록합니다.</span>
        </div>
        
        <div class="form-group">
          <label class="form-label col-sm-3">송신 사무소</label>
          <div class="input-group col-sm-6">
            <?php echo $select_sender ?>
          </div>
        </div>

        <div class="form-group">
          <label class="form-label col-sm-3">수신 사무소</label>
          <div class="input-group col-sm-6">
            <?php echo $select_receiver ?>
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

<!-- jquery form validation -->
<script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.min.js"></script>

<script type="text/javascript">

// notEqual validate rule 추가
jQuery.validator.addMethod("notEqual", function(value, element, param) {
  return this.optional(element) || value != param;
}, "송신사무소와 수신사무소와 같을 수 없습니다");

$(document).ready(function(){
  // open modal
  $("#btn_request_move").click(function(){
    $("#modal_request_move").modal('show');
  });  

  // 상세 보기 페이지로 이동
  $("button.btn_view").click(function(){
    var href = $(this).data('href');
    location.href = href;
    return false;
  });

  $("#modal_request_move form").validate({
    rules: {
      send_office_id: {
        required: true,
        min: 1
      },
      receive_office_id: {
        required: true,
        min: 1,
        notEqual: $("#send_office_id").val()
      }
    },
    messages: {
      send_office_id: {
        min: '송신 사무소를 선택하세요'
      },
      receive_office_id: {
        min: '수신 사무소를 선택하세요'
      }
    },
    submitHandler: function(form) {
      alert('form submit!');
      form.submit();
      // return false;
    }

  });

  /////////////////////////
  // bootstrap 3 popover //
  /////////////////////////
  $(".popover_memo").popover({trigger: 'hover', placement: 'left'});
  $(".popover").click(function(e){e.preventDefault();});
});
</script>

<?php
$this->view('layout/footer');
?>