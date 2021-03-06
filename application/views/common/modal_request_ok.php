<?php
/**
 * 요청서 확정 modal interface
 */
?>
<!-- modal dialog -->
<div class="modal fade" id="modal_request_ok" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title"><?=$_config['op_type'][$work->type]?> 요청서 확정</h4>
      </div>
      <!-- start form -->
      <form role="form" class="form form-horizontal">
      <div class="modal-body">
          <input type="hidden" name="mode" value="install_request_ok">
          <div class="form-group">
            <label class="form-label col-sm-4">담당 사무소</label>
            <div class="col-sm-5">
    <?php
    echo $select_office;
    ?>
            </div>
            <div class="col-sm-3">
              <button id="btn_change_office" class="btn btn-info btn-sm" type="button">사무소 변경</button>
            </div>
          </div>
          <div class="form-group">
            <label class="form-label col-sm-4">담당 직원 선택</label>
            <div class="col-sm-7">
    <?php
    echo $select_user;
    ?>      
            </div>
          </div>

          <div class="form-group">
            <label class="form-label col-sm-4">작업 예정일</label>
            <div class="col-sm-6">
              <div class="input-group">
                <input type="text" id="date_expect" name="date_expect" class="form-control date-picker" value="<?php echo $work->getDateRequest(); ?>">
                <span class="input-group-addon btn_date"><i class="fa fa-calendar"></i></span>
              </div>
            </div>
          </div>

          <div class="form-group">
            <label class="form-label col-sm-4">작업 메모</label>
            <div class="col-sm-7">
              <textarea name="memo" class="form-control"></textarea>
            </div>
          </div>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-primary">요청 확정</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">닫기</button>
      </div>
      </form>
    </div>
  </div>
</div><!-- /.modal -->

<script type="text/javascript">
$(document).ready(function(){
  $("#date_request").datepicker({});

  $("#modal_request_ok form").submit(function(e){
    e.preventDefault();

    var $form = $("#modal_request_ok form");
    var $worker_id = $("#worker_id");
    var $date_expect = $("#date_expect");

    if($worker_id.val() < 1) {
      alert('필수 항목 입니다');
      $worker_id.focus();
      return false;
    }

    if($date_expect.val() == '') {
      alert('작업 예정일은 필수 항목 입니다');
      $date_expect.focus();
      return false;
    }

    var is_ok = confirm("확정 하시겠습니까?\n그리고 작업 예정일 확인 해야 합니다");

    if(is_ok == true){
      $.ajax({
        url: "<?=base_url()?>work/ajax/accept_request",
        type: "POST",
        data: {
          id : operation.id,
          office_id: $("#office_id").val(),
          worker_id: $worker_id.val(),
          date_work: $date_expect.val(),
          memo: $("textarea[name=memo]", this).val(),
          "csrf_test_name": $.cookie("csrf_cookie_name")
        },
        dataType: "html",
      })
        .done(function(html) {
          alert('확정하였습니다.\n 다음단계로 이동합니다.');
          location.reload();
        })
        .fail(function(xhr, textStatus){
          alert("Request failed: " + textStatus);
        });
    }// end of if

  });//end of submit

  //////////////
  // 담당사무소 변경
  //////////////
  $("#btn_change_office").click(function(){
    var oId = $("#office_id").val();

    if(oId == operation.office_id) {
      alert('지정된 사무소와 같습니다.');
      return false;
    }

    $.ajax({
        url: "<?=base_url()?>work/ajax/change_office",
        type: "POST",
        data: {
          id : operation.id,
          office_id: oId,
          "csrf_test_name": $.cookie("csrf_cookie_name")
        },
        dataType: "json",
      })
        .done(function(response) {
          if(!response.error) {
            $("#modal_request_ok").modal('hide');
            alert("담당 사무소를 변경 하였습니다.\n리스트로 이동합니다.");
            location.href= response.data;    // 이동 url
          
          } else {
            alert(response.error_msg);
            return false;  
          }
        })
        .fail(function(xhr, textStatus){
          alert("Request failed: " + textStatus);
        });
  });
});//end of ready

</script>