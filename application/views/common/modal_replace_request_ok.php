<?php
/**
 * 요청서 확정 modal interface
 */
?>
<!-- modal dialog -->
<div class="modal fade" id="modal_replace_request_ok" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
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
            <label class="form-label col-sm-3">담당 사무소</label>
            <div class="col-sm-6">
    <?php
    echo $select_office;
    ?>
            </div>
            <div class="col-sm-3">
              <button id="btn_change_office" class="btn btn-info btn-sm" type="button">사무소 변경</button>
            </div>
          </div>
          
          <!-- 담당자 -->
          <div class="row">
            <div class="col-sm-6">
              <div class="form-group">
                <label class="form-label col-sm-5">철수 담당자</label>
                <div class="col-sm-7">
        <?php
        echo $select_close_user;
        ?>      
                </div>
              </div>
            </div>

            <div class="col-sm-6">
              <div class="form-group">
                <label class="form-label col-sm-5">설치 담당자</label>
                <div class="col-sm-7">
        <?php
        echo $select_install_user;
        ?>      
                </div>
              </div>
            </div>
          </div>

          <!-- 작업 예정일 -->
          <div class="row">
            <div class="col-sm-6">
              <div class="form-group">
                <label class="form-label col-sm-5">철수 예정일</label>
                <div class="col-sm-7">
                  <div class="input-group">
                    <input type="text" id="close_expect_date" name="close_expect_date" class="form-control date-picker" value="<?php echo $work->getDateRequest(); ?>">
                    <span class="input-group-addon btn_date"><i class="fa fa-calendar"></i></span>
                  </div>
                </div>
              </div>
            </div>

            <div class="col-sm-6">
              <div class="form-group">
                <label class="form-label col-sm-5">설치 예정일</label>
                <div class="col-sm-7">
                  <div class="input-group">
                    <input type="text" id="install_expect_date" name="install_expect_date" class="form-control date-picker" value="<?php echo $work->getDateRequest(); ?>">
                    <span class="input-group-addon btn_date"><i class="fa fa-calendar"></i></span>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="form-group">
            <label class="form-label col-sm-3">작업 메모</label>
            <div class="col-sm-9">
              <textarea name="memo" class="form-control" rows="5"></textarea>
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
// 공통 으로 둬야 겠다
jQuery.validator.setDefaults({
  onkeyup: false,
  debug: true
});

$(document).ready(function(){
  $("#modal_replace_request_ok form").validate({
    rules: {
      install_worker_id: {
        required: true,
        min : 1
      },
      close_worker_id: {
        required: true,
        min : 1
      }
      // install_expect_date: 'required',
      // close_expect_date: 'required'
    },
    submitHandler: function(form) {
      var is_ok = confirm("교체 요청을 확정 합니다.\n진행할까요?");

      if(!is_ok)
        return false;

      // gs2_console($(form).serialize());
      // ajax request for 요청확정
      $.ajax({
        url: "<?=base_url()?>work/ajax/accept_request",
        type: "POST",
        data: {
          id : operation.id,
          close_worker_id : $("#close_worker_id").val(),
          install_worker_id : $("#install_worker_id").val(),
          close_expect_date: $("#close_expect_date").val(),
          install_expect_date: $("#install_expect_date").val(),
          memo: $("textarea[name=memo]", form).val(),
          "csrf_test_name": $.cookie("csrf_cookie_name")
        },
        dataType: "html",
      })
        .done(function(html) {
          gs2_console(html);
          location.reload();
        })
        .fail(function(xhr, textStatus){
          alert("Request failed: " + textStatus);
        });
    }
  });//end of validate
  
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
        dataType: "html",
      })
        .done(function(html) {
          alert(html);
        })
        .fail(function(xhr, textStatus){
          alert("Request failed: " + textStatus);
        });
  });

});//end of ready

</script>