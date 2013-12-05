    <!-- start of div.container -->
    <div class="container">
    
      <!-- Example row of columns -->
      <div class="row">
        <div class="col-md-8">
          <?php
            // 에러 출력
            echo validation_errors();
          ?>
 
          <div>
            <?php echo form_open('', 'role="form"');?>
            <!-- <form role="form"> -->
              <h2>사용자 추가 양식</h2>


              <div class="form-group">
                <p>사용자 타입 선택</p>
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

              <div class="form-group" id="select_office">
                <lable for="office_id" class="control-label">사무소 선택</lable>
                <?php
                echo $form_office_select;
                ?>
              </div>

              <!-- 외부 업체 선택-->
              <div class="form-group" id="select_company">
                <lable for="company_id" class="control-label">외부 업체 선택</lable>
                <?php
                echo $form_company_select;
                ?>
              </div>

              <div class="form-group">
                <lable for="username" >사용자 ID 입력</lable>
                <input type="text" class="form-control" id="username" name="username" placeholder="Enter ID">
              </div>

              <div class="form-group">
                <label for="name">이름</label>
                <input type="text" class="form-control" id="name" name="name" placeholder="이름을 입력하세요">
              </div>

              <div class="form-group">
                <label for="password" class="control-label">패스워드</label>
                <input type="password" class="form-control" id="password" name="password" placeholder="패스워드를 입력하세요">
              </div>

              <div class="form-group">
                <label for="re_password" class="control-label">패스워드 재입력</label>
                <input type="password" class="form-control" id="re_password" name="re_password" placeholder="패스워드를 입력하세요">
              </div>
              
              <p class="form-actions">
                <button class="btn btn-primary" type="submit">입력완료</button>
                <button id="ajax" class="btn btn-default" type="button">팝업 띄우기</button>
                <button id="ajax" class="btn btn-danger" type="button">다른 종류</button>
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
    $(document).ready(function(){
      $("#re_password").parent().addClass('has-warning');

      var user_type;

      $("#select_office").hide();
      $("#select_company").hide();

      $(":radio[name=type]").change(function(e){
        user_type = $(this).val();
        
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

    });
    </script>