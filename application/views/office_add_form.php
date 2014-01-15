<?php
$this->view('layout/header');
$this->view('layout/navbar');
?>

<!-- start of div.container -->
<div class="container">

  <!-- Example row of columns -->
  <div class="row">
    <div class="col-md-10">
      <?php
        // 에러 출력
        echo validation_errors();
      ?>

      <div>
<?php 
echo form_open('', 'id="register_form" role="form" class="form form-horizontal" ');
?>
          <div class="page-header">
            <h2><span class="fa fa-pencil-square-o"></span> 사무소 등록</h2>
          </div>

          <div class="form-group">
            <label class="form-label col-sm-3">타입을 선택하세요</label>
            <div class="col-sm-5">
              <label class="radio-inline">
                <input type="radio" id="type1" name="type" value="O" checked >사무소
              </label>
              <!--
              <label class="radio-inline">
                <input type="radio" id="type2" name="type" value="I" >창고
              </label>
            -->
            </div>
            <div class="col-sm-4"></div>
          </div>

          <div class="form-group">
            <label for="name" class="form-label col-sm-3">사무소명</label>
            <div class="col-sm-5">
              <input type="text" class="form-control" id="name" name="name" placeholder="입력하세요" required>
            </div>
            <div class="col-sm-4"></div>
          </div>

          <!-- 마스터 사무소 선택-->
          <div class="form-group">
            <label for="office_id" class="form-label col-sm-3">
              상위 사무소 선택
            </label>
            <div class="col-sm-5">

<?php
echo $select_office;
?>
              <span class="help-block"><small class="text-info">선택 안하면 최상위 사무소가 됩니다</small></span>
            </div>
            <div class="col-sm-4"></div>
          </div>

          <!-- 담당자 선택-->
          <div class="form-group">
            <label for="user_id" class="form-label col-sm-3">담당자 선택</label>
            <div class="col-sm-5">
<?php
echo $select_user;
?>
            </div>
            <div class="col-sm-4"></div>
          </div>

          <!-- 담당자 선택 (임시)
          <div class="form-group">
            <label for="aaa" class="form-label col-sm-3">optgroup</label>
            <div class="col-sm-5">
              <select class="form-control" name="aaa" id="aaa" disabled>
                <option>--선택하슈--</option>
                <optgroup label="본사">
                  <option value="">본사-1</option>
                  <option value="">본사-2</option>
                  <option value="">본사-3</option>
                </optgroup>
                <optgroup label="대전">
                  <option value="">대전-1</option>
                  <option value="">대전-2</option>
                  <option value="">대전-3</option>
                </optgroup>
              </select>
            </div>
          </div>
          -->

          <div class="form-group">
            <label for="phone" class="form-label col-sm-3">전화번호</label>
            <div class="col-sm-5">
              <input type="text" class="form-control" id="phone" name="phone" placeholder="입력하세요">
            </div>
            <div class="col-sm-4"></div>
          </div>

          <div class="form-group">
            <label for="address" class="form-label col-sm-3">주 소</label>
            <div class="col-sm-5">
              <input type="text" class="form-control" id="address" name="address" placeholder="입력하세요">
              <span class="help-block">Start typing e.g. Yellow, Red</span>
            </div>
            <div class="col-sm-4"></div>
          </div>

          <div class="form-group">
            <label for="memo" class="form-label col-sm-3">메 모  <br><small class="text-success">간단한 추가 요청 사항</small></label>
            <div class="col-sm-8">
              <textarea name="memo" id="memo" rows="3" class="form-control"></textarea>
            </div>
            <div class="col-sm-4"></div>
          </div>

          <div class="form-group">
            <label class="form-label col-sm-3">상 태</label>
            <div class="col-sm-5">
              <label class="radio-inline">
                <input type="radio" name="status" value="1" checked> 정상
              </label>
              <label class="radio-inline">
                <input type="radio" name="status" value="0"> 폐쇄
              </label>
            </div>
          </div>

          <p class="form-actions">
            <button class="btn btn-primary" type="submit">저장</button>
            <button class="btn btn-default" type="button">취소</button>
          </p>
        </form>
      </div>

    </div>
  </div>
<!-- start of div.container -->
</div>

<!-- jquery form validation -->
<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
<script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.min.js"></script>

<script type="text/javascript">
jQuery.validator.setDefaults({
  onkeyup: false,
  debug: true
});

$(document).ready(function(){
  $("#register_form").validate({
    rules: {
      name: {
        required: true,
        remote: {
          url: "<?=site_url('ajax/is_exist_office_name')?>",
          type: "post",
          data: {
            "csrf_test_name": $.cookie("csrf_cookie_name")
          }
        }
      }
    },
    messages: {
      name: {
        remote: '사용할수 없는 사무소명 입니다. 다시 선택해 주세요'
      }
    },
    success: function(el) {
        el.closest(".form-group").removeClass('has-error');
    },
    errorPlacement: function(error, el) {
      if( el.is(":radio")) {
        error.appendTo( el.closest('div').next());
      } else {
        error.appendTo( el.parent().next());
        el.closest(".form-group").addClass('has-error');
      }
    },
    submitHandler: function(form) {
      if( $("#office_id").val() == '0') {
        if(!confirm('마스터 사무소로 등록합니다.\n계속하시겠습니까?')) {
          return false;
        }
      }
      form.submit();
    }
  });
  
});
</script>

<?php
$this->view('layout/footer');
?>