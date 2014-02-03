<?php
/**
 * 요청서 확정 modal interface
 */
?>
<!-- modal dialog -->
<div class="modal fade" id="modal_enter_request_ok" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title"><?=$_config['op_type'][$work->type]?> 요청서 확정</h4>
      </div>
      <!-- start form -->
      <form role="form" class="form form-horizontal">
      <div class="modal-body">

          <div class="form-group">
            <label class="form-label col-sm-4">납품 예정일</label>
            <div class="col-sm-6">
              <div class="input-group">
                <input type="text" id="date_expect" name="date_expect" class="form-control date-picker" value="<?php echo $work->getDateRequest(); ?>">
                <span class="input-group-addon btn_date"><i class="fa fa-calendar"></i></span>
              </div>
            </div>
          </div>

          <div class="form-group">
            <label class="form-label col-sm-4">메모</label>
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
    $("#date_expect").datepicker({
      dateFormat: "yy-mm-dd",
      minDate: new Date(),
      changeMonth: true,
      changeYear: true
    });

    $("#modal_enter_request_ok form").submit(function(e){
      var form = $("#modal_enter_request_ok form");

      // 입고 업무에서는 default 값 사용      
      var   office_id = 1,
            worker_id = 1;    // 납품 업체 담당자

      var $date_expect = $("#date_expect");
      if($date_expect.val() == '') {
        alert('납품 예정일은 필수 항목 입니다');
        $date_expect.focus();
        return false;
      }

      var is_ok = confirm("납품 예정일 확인 하세요\n\n요청 확정 하시겠습니까?");

      if(!is_ok){
        return false;
      }

      $.ajax({
        url: "<?=base_url()?>work/ajax/accept_request",
        async: false,
        type: "POST",
        data: {
          id : operation.id,
          // office_id: office_id,
          // worker_id: worker_id,
          date_expect: $date_expect.val(),
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

    });//end of submit
  });//end of ready

</script>