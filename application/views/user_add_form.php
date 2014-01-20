<?php
$this->view('layout/header');
$this->view('layout/navbar');
?>

<!-- start of div.container -->
<div class="container">

  <!-- Example row of columns -->
  <div class="row">
    <div class="col-md-12">
      <?php
        // 에러 출력
        echo validation_errors();
      ?>

      <div>
        <?php echo form_open('', 'id="register_form" role="form" class="form form-horizontal" ');?>
        <!-- <form role="form"> -->
          <div class="page-header">
            <h2><i class="fa fa-user">사용자 추가 양식</i></h2>
          </div>

          <div class="form-group">
            <label class="form-label col-sm-3">사용자 타입 선택</label>
            <div class="col-sm-6">
              <label class="radio-inline">
                <input type="radio" class="grey" name="type" value="1">
                시스네트
              </label>
              <label class="radio-inline">
                <input type="radio" class="grey" name="type" value="2">
                GS25
              </label>
              <label class="radio-inline">
                <input type="radio" class="grey" name="type" value="3">
                납품업체
              </label>
            </div>
            <div class="col-sm-3 "></div>
          </div>

          <div class="form-group" id="select_office">
            <label class="form-label col-sm-3">사무소 선택</label>
            <div class="col-sm-6">
            <?php
            echo $form_office_select;
            ?>
            </div>            
            <div class="col-sm-3 error_msg"></div>
          </div>

          <!-- 외부 업체 선택-->
          <div class="form-group" id="select_company">
            <label class="form-label col-sm-3">외부 업체 선택</label>
            <div class="col-sm-6">
            <?php
            echo $form_company_select;
            ?>
            </div>
            <div class="col-sm-3 error_msg"></div>
          </div>

          <div class="form-group">
            <label class="form-label col-sm-3">사용자 ID 입력</label>
            <div class="col-sm-6">
              <input type="text" class="form-control" id="username" name="username" placeholder="Enter ID">
            </div>
            <div class="col-sm-3 error_msg"></div>
          </div>

          <div class="form-group">
            <label class="form-label col-sm-3">이름</label>
            <div class="col-sm-6">
              <input type="text" class="form-control" id="name" name="name" placeholder="이름을 입력하세요">
            </div>
            <div class="col-sm-3 error_msg"></div>
          </div>

          <div class="form-group">
            <label class="form-label col-sm-3">패스워드</label>
            <div class="col-sm-4">
              <input type="password" class="form-control" id="password" name="password" placeholder="패스워드를 입력하세요">
            </div>
            <div class="col-sm-5 error_msg"></div>
          </div>

          <div class="form-group">
            <label class="form-label col-sm-3">패스워드 재입력</label>
            <div class="col-sm-4">
              <input type="password" class="form-control" id="re_password" name="re_password" placeholder="패스워드를 입력하세요">
            </div>
            <div class="col-sm-5 error_msg"></div>
          </div>

          <div class="form-group">
            <label class="form-label col-sm-3">이메일</label>
            <div class="col-sm-5">
              <input type="text" class="form-control" id="email" name="email" placeholder="입력하세요">
            </div>
            <div class="col-sm-4 error_msg"></div>
          </div>
          
          <div class="form-group">
            <label class="form-label col-sm-3">연락처</label>
            <div class="col-sm-6">
              <input type="text" class="form-control" id="phone" name="phone" placeholder="연락처를 입력하세요">
            </div>
          </div>
          
          <p class="form-actions">
            <button class="btn btn-primary" type="submit">등 록</button>
            <button class="btn btn-default" type="button">취 소</button>
          </p>
        </form>
      </div>

    </div>
  </div>
<!-- start of div.container -->
</div>

<!-- jquery form validation -->
<script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.min.js"></script>
<script type="text/javascript">
jQuery.validator.setDefaults({
  onkeyup: false,
  debug: true
});

$(document).ready(function(){

  $("#select_office").hide();
  $("#select_company").hide();

  $(":radio[name=type]").change(function(e){
    var user_type = $(this).val();
    
    if(user_type == 1) {
      $("#select_office").fadeIn();
      $("#select_company").hide();
    } else if(user_type == 2) {
      $("#select_office").hide();
      $("#select_company").hide();
    } else if(user_type == 3) {
      $("#select_office").hide();
      $("#select_company").fadeIn();
    } 

  });

  // jquery validate 설정
  $("#register_form").validate({
    debug: true,
    onkeyup: false,
    rules: {
      type: {
        required: true
      },
      office_id: {
        min: 1,
        required: {
          depends: function(el) {
            return ($(":radio[name=type]:checked").val() === '1');
          }
        }
      },
      company_id: {
        min: 1,
        required: {
          depends: function(el) {
            return ($(":radio[name=type]:checked").val() === '3');
          }
        }
      },
      username : {
        required: true,
        remote: {
          url: "<?=site_url('ajax/is_exist_username')?>",
          type: "post",
          data: {
            "csrf_test_name": $.cookie("csrf_cookie_name")
          }
        }
      },
      name : 'required',
      password: {
        required: true,
        minlength: 1
      },
      re_password: {
        required: true,
        equalTo: "#password"
      },
      email: {
        required: false,
        email: true
      }
    },
    messages: {
      email: {
        email: '올바른 email 형식이 아닙니다. ex) abc@sisnet.net'
      },
      username: {
        remote: '사용할수 없는 ID 입니다. 다시 선택해 주세요'
      },
      re_password: "비밀번호가 일치하지 않습니다"
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
      alert('새로운 사용자를 등록하였습니다');
      form.submit();
    }
  });

});// end of $.ready()
</script>

<?php
$this->view('layout/footer');
?>