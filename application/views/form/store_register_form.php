<div class="container">
<?php 
echo form_open('', 'id="store_register_form" role="form" class="form-horizontal" ');

if(isset($form_saved)) {
  echo '<input type="hidden" name="form_saved" id="form_saved" value="' . $form_saved . '">';
}
?>
  <div class="page-header">
    <h2><span class="fa fa-home"></span> 점포 등록</h2>
  </div>
  <?php
    // 에러 출력
    echo validation_errors();
  ?>
  
  <div class="form-group">
    <label class="control-label col-sm-3">점포명</label>
    <div class="col-sm-6">
      <input type="text" class="form-control required" id="name" name="name" placeholder="입력하세요" >
    </div>
    <div class="col-sm-3"></div>
  </div>
  
  <div class="form-group">
    <label class="control-label col-sm-3">점포 코드</label>
    <div class="col-sm-6">
      <input type="text" class="form-control" id="code" name="code" placeholder="입력하세요">
    </div>
    <div class="col-sm-3"></div>
  </div>

  <div class="form-group">
    <label class="control-label col-sm-3">점포 가변 코드</label>
    <div class="col-sm-6">
      <input type="text" class="form-control " id="code2" name="code2" placeholder="입력하세요">
    </div>
  </div>

  <div class="form-group">
    <label class="control-label col-sm-3">점주 이름</label>
    <div class="col-sm-6">
      <input type="text" class="form-control" id="owner_name" name="owner_name" placeholder="입력하세요">
    </div>
  </div>

  <div class="form-group">
    <label class="control-label col-sm-3">점주 연락처</label>
    <div class="col-sm-6">
      <input type="text" class="form-control" id="owner_tel" name="owner_tel" placeholder="입력하세요">
    </div>
  </div>

  <div class="form-group">
    <label class="control-label col-sm-3">전화번호</label>
    <div class="col-sm-6">
      <input type="text" class="form-control" id="tel" name="tel" placeholder="입력하세요">
    </div>
  </div>

  <div class="form-group">
    <label class="control-label col-sm-3">주 소</label>
    <div class="col-sm-6">
      <input type="text" class="form-control" id="address" name="address" placeholder="입력하세요">
    </div>
  </div>

  <div class="form-group">
    <label class="control-label col-sm-3">RFC 이름</label>
    <div class="col-sm-6">
      <input type="text" class="form-control" id="rfc_name" name="rfc_name" placeholder="입력하세요">
    </div>
  </div>

  <div class="form-group">
    <label class="control-label col-sm-3">RFC 연락처</label>
    <div class="col-sm-6">
      <input type="text" class="form-control" id="rft_tel" name="rft_tel" placeholder="입력하세요">
    </div>
  </div>

  <div class="form-group">
    <label class="control-label col-sm-3">OFC 이름</label>
    <div class="col-sm-6">
      <input type="text" class="form-control" id="ofc_name" name="ofc_name" placeholder="입력하세요">
    </div>
  </div>

  <div class="form-group">
    <label class="control-label col-sm-3">OFC 연락처</label>
    <div class="col-sm-6">
      <input type="text" class="form-control" id="ofc_tel" name="ofc_tel" placeholder="입력하세요">
    </div>
  </div>

  <div class="form-group">
    <label class="control-label col-sm-3">가입 형태</label>
    <div class="col-sm-6">
      <select name="join_type" class="form-control" >
        <option value="">-- 선택하세요 --</option>
        <option value="1">가맹1종A (순수)</option>
        <option value="2">가맹1종C (전대)</option>
        <option value="3">직영A (일반)</option>
        <option value="4">직영B</option>
        <option value="5">C타입</option>
        <option value="6">G타입</option>
        <option value="7">K타입</option>
        <option value="8">S타입</option>
      </select>
    </div>
  </div>

  <div class="form-group">
    <label class="control-label col-sm-3">무인택배함 설치 유무</label>
    <div class="col-sm-6">
        <label class="radio-inline">
          <input type="radio" name="has_postbox" value="1" >일반 설치
        </label>
        <label class="radio-inline">
          <input type="radio" name="has_postbox" value="1" >MMK 설치
        </label>
        <label class="radio-inline">
          <input type="radio" name="has_postbox" value="0"> 미설치
        </label>
    </div>
  </div>

  <div class="form-group">
    <label class="control-label col-sm-3">상 태</label>
    <div class="col-sm-6">
        <label class="radio-inline">
          <input type="radio" name="status" value="1" checked="checked"> 정상
        </label> /
        <label class="radio-inline">
          <input type="radio" name="status" value="2"> 휴점
        </label> /
        <label class="radio-inline">
          <input type="radio" name="status" value="0"> 폐점
        </label>
    </div>
  </div>
  <hr>

  <div class="row col-xs-offset-9">
    <button class="btn btn-primary" type="submit">저장</button>
    <button class="btn btn-default btn_colorbox_close" type="button">취소</button>
  </div>
</form>
</div>
<br>
      
<!-- jquery form validation -->
<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
<script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.min.js"></script>

<script type="text/javascript">
$(document).ready(function(){
<?php
// 저장이 성공적으로 되었을 경우 처리
if(isset($form_saved) && $form_saved == TRUE):
?>
  var store_id = "<?=$new_store_id?>";
  var store_name = "<?=$new_store_name?>";

  // console.log('after save store...');
  alert('신규 점포 등록 완료.');

  // callback 함수 사용하여 부모창 element 에 설정
  parent.callback_store_info(store_id, store_name);

  // colorbox close
  parent.jQuery.fn.colorbox.close();
  
  return false;
<?php
endif;
?>
  // form validation
  $("#store_register_form").validate({
    rules: {
      name : {
        required: true,
        remote: {
          url: "<?=site_url('ajax/is_exist_store_name')?>",
          type: "post",
          data: {
            "csrf_test_name": $.cookie("csrf_cookie_name")
          }
        }
      },
      code : {
        required: true,
        remote: {
          url: "<?=site_url('ajax/is_exist_store_code')?>",
          type: "post",
          data: {
            "csrf_test_name": $.cookie("csrf_cookie_name")
          }
        }
      }
    },
    messages: {
      name: {
        required: "필수항목 입니다.",
        remote: '등록된 점포명 입니다. 확인 후 입력해주세요'
      },
      code: {
        required: "필수항목 입니다.",
        remote: '등록된 점포 코드입니다. 확인 후 입력해주세요'
      }
    },
    errorPlacement: function(error, el) {
      error.appendTo(el.parent());
      el.closest(".form-group").addClass('has-error');
    },
    success: function(el) {
      el.closest(".form-group").removeClass('has-error');
    },
    submitHandler: function(form){
      doSubmit(form);
    }
  });
    
  // datepicker...
  $(".date-picker").each(function(){
    $(this).datepicker({
      dateFormat: "yy-mm-dd",
      minDate: new Date(),
      changeMonth: true,
      changeYear: true
    });
  });

  $(".btn_colorbox_close").click(function(){
    // colorbox close
    parent.jQuery.fn.colorbox.close();
  });
});
</script>
