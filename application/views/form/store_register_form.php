<div class="container">
<?php 
echo form_open('', 'id="store_register_form" role="form" class="form-horizontal" ');
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
  </div>
  
    <div class="form-group">
      <label class="control-label col-sm-3">점포 코드</label>
      <div class="col-sm-6">
        <input type="text" class="form-control" id="code" name="code" placeholder="입력하세요">
      </div>
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
      <span class="help-block">Start typing e.g. Yellow, Red</span>
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
      <input type="text" class="form-control" id="join_type" name="join_type" placeholder="입력하세요">
    </div>
  </div>

  <div class="form-group">
    <label class="control-label col-sm-3">무인택배함 설치 유무</label>
    <div class="col-sm-6">
        <label class="radio-inline">
          <input type="radio" name="has_postbox" value="Y" > 설치
        </label> /
        <label class="radio-inline">
          <input type="radio" name="has_postbox" value="N"> 미설치
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
          <input type="radio" name="status" value="9"> 폐점
        </label>
    </div>
  </div>
  <hr>

  <div class="row col-xs-offset-9">
    <button class="btn btn-primary" type="submit">저장</button>
    <button class="btn btn-default" type="button">취소</button>
  </div>
</form>
</div>
      
<!-- jquery form validation -->
<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
<script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.min.js"></script>

<script type="text/javascript">
$(document).ready(function(){
  // form validation
  $("#store_register_form").validate({
    rules: {
      name: 'required',
      code: 'required'
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
});
</script>
