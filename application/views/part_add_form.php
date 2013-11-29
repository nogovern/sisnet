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
              <h2>장비 등록 양식</h2>
              
              <input type="hidden" name="category_name" id="category_name" value="">
              <div class="form-group">
                <label>장비 타입을 선택하세요</label>
                <div class="radio">
                  <label>
                    <input type="radio" id="type1" name="type" value="1" checked>
                    시리얼 관리 장비
                  </label>
                </div>

                <div class="radio">
                  <label>
                    <input type="radio" id="type2" name="type" value="2">
                    수량 관리 장비
                  </label>
                </div>

                <div class="radio">
                  <label>
                    <input type="radio" id="type3" name="type" value="3">
                    소모품
                  </label>
                </div>

              </div>

              <div class="form-group">
                <lable for="category" class="control-label">장비 종류</lable>
                  <select id="category" name="category" class="form-control">
                    <option value="">--선택하세요--</option>
                    <option value="1">POS스캐너</option>
                    <option value="2">고정스캐너</option>
                    <option value="3">모니터_CRT</option>
                    <option value="4">모니터_LCD</option>
                    <option value="5">서버</option>
                    <option value="6">프린터_점포용</option>
                    <option value="7">자동차</option>
                  </select>
              </div>

              <div class="form-group">
                <lable for="inventory_id" class="control-label">창고 선택</lable>
                  <select id="inventory_id" class="form-control">
                    <option>--선택하세요--</option>
                    <option value="1">가산</option>
                    <option value="2">대전</option>
                    <option value="3">부산</option>
                    <option value="4">제주</option>
                  </select>
              </div>
              
              <!--
              <div class="form-group">
                <lable for="customer_id" class="control-label">외부 업체 선택</lable>
                  <select id="customer_id" class="form-control">
                    <option>--선택하세요--</option>
                    <option value="1">IBM</option>
                    <option value="2">Samsung</option>
                    <option value="3">LG</option>
                  </select>
              </div>
              -->

              <div class="form-group">
                <lable for="part_no" >장비 식별자 입력</lable>
                <input type="text" class="form-control" id="part_no" name="part_no" placeholder="Enter...">
              </div>

              <div class="form-group">
                <label for="part_code">장비 CODE</label>
                <input type="text" class="form-control" id="part_code" name="part_code" placeholder="Enter...">
              </div>

              <div class="form-group has-success">
                <label for="name" class="control-label">모델명</label>
                <input type="text" class="form-control" id="name" name="name" placeholder="Enter...">
              </div>

              <div class="form-group">
                <label for="manufacturer" class="control-label">제조사명</label>
                <input type="text" class="form-control" id="manufacturer" name="manufacturer" placeholder="Enter...">
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
      $("form").submit(function(){
        var selected = $("#category").val();
        if( selected == ''){
          alert('장비 종류를 선택하세요');
          return false;
        }

        var cat_name = $("#category option:selected").text();
        $("#category_name").val(cat_name);      

      });

    });
    </script>