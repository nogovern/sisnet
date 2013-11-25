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
            <?php echo form_open('', 'role="form"');?>
            <!-- <form role="form"> -->
              <h2>사용자 추가 양식</h2>


              <div class="form-group">
                <lable for="type" class="control-label">Select User Type</lable>
                  <select id="type" name="type" class="form-control">
                    <option value="">--선택하세요--</option>
                    <option value="1">시스네트</option>
                    <option value="2">GS</option>
                    <option value="3">납품처</option>
                  </select>
              </div>

              <div class="form-group">
                <lable for="office_id" class="control-label">사무소 선택</lable>
                  <select id="office_id" class="form-control">
                    <option>--선택하세요--</option>
                    <option value="1">가산</option>
                    <option value="2">대전</option>
                    <option value="3">부산</option>
                    <option value="4">제주</option>
                  </select>
              </div>

              <!-- 외부 업체 선택-->
              <div class="form-group">
                <lable for="customer_id" class="control-label">외부 업체 선택</lable>
                  <select id="customer_id" class="form-control">
                    <option>--선택하세요--</option>
                    <option value="1">IBM</option>
                    <option value="2">Samsung</option>
                    <option value="3">LG</option>
                  </select>
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
                <input type="password" class="form-control" id="re_password" name="password" placeholder="패스워드를 입력하세요">
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

      // $("select option").attr('font-weight', 'bold').attr('color', 'red');
    });
    </script>