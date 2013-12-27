<?php
$this->load->view('layout/header', array('title' => '관리자 >> 점포 >> 신규 등록'));
$this->load->view('layout/navbar', array('current' => 'page-admin-store'));
?>

    <!-- start of div.container -->
    <div class="container">
    
      <!-- Example row of columns -->
      <div class="row">
        <div class="col-sm-12">
          <?php
            // 에러 출력
            echo validation_errors();
          ?>
 
          <div>
<?php 
echo form_open('', 'id="register_form" role="form" class="form-horizontal" ');
?>
              <h2><span class="fa fa-home"></span> 점포 등록</h2>

              <div class="form-group">
                <label class="control-label col-sm-3">점포명</label>
                <div class="col-sm-7">
                  <input type="text" class="form-control required" id="name" name="name" placeholder="입력하세요" >
                </div>
              </div>

              <div class="form-group">
                <label class="control-label col-sm-3">점포 코드</label>
                <div class="col-sm-7">
                  <input type="text" class="form-control required" id="code" name="code" placeholder="입력하세요">
                </div>
              </div>

              <div class="form-group">
                <label class="control-label col-sm-3">점주 이름</label>
                <div class="col-sm-7">
                  <input type="text" class="form-control required" id="owner_name" name="owner_name" placeholder="입력하세요">
                </div>
              </div>

              <div class="form-group">
                <label class="control-label col-sm-3">전화번호</label>
                <div class="col-sm-7">
                  <input type="text" class="form-control" id="tel" name="tel" placeholder="입력하세요">
                </div>
              </div>

              <div class="form-group">
                <label class="control-label col-sm-3">주 소</label>
                <div class="col-sm-7">
                  <input type="text" class="form-control" id="address" name="address" placeholder="입력하세요">
                  <span class="help-block">Start typing e.g. Yellow, Red</span>
                </div>
              </div>

              <div class="form-group">
                <label class="control-label col-sm-3">RFC 연락처</label>
                <div class="col-sm-7">
                  <input type="text" class="form-control" id="tel_rfc" name="tel_rfc" placeholder="입력하세요">
                </div>
              </div>

              <div class="form-group">
                <label class="control-label col-sm-3">OFC 연락처</label>
                <div class="col-sm-7">
                  <input type="text" class="form-control" id="tel_ofc" name="tel_ofc" placeholder="입력하세요">
                </div>
              </div>

              <div class="form-group">
                <label class="control-label col-sm-3">점포 규모</label>
                <div class="col-sm-7">
                  <input type="text" class="form-control" id="scale" name="scale" placeholder="입력하세요">
                </div>
              </div>

              <div class="form-group">
                <label class="control-label col-sm-3">가입 형태</label>
                <div class="col-sm-7">
                  <input type="text" class="form-control" id="join_type" name="join_type" placeholder="입력하세요">
                </div>
              </div>

              <div class="form-group">
                <label class="control-label col-sm-3">무인택배함 설치 유무</label>
                <div class="col-sm-7">
                    <label class="radio-inline">
                      <input type="radio" name="has_postbox" value="Y" required> 설치
                    </label> /
                    <label class="radio-inline">
                      <input type="radio" name="has_postbox" value="N"> 미설치
                    </label>
                </div>
              </div>

              <div class="form-group">
                <label class="control-label col-sm-3">상 태</label>
                <div class="col-sm-7">
                    <label class="radio-inline">
                      <input type="radio" name="status" value="1" required> 정상운영
                    </label> /
                    <label class="radio-inline">
                      <input type="radio" name="status" value="2"> 휴점
                    </label> /
                    <label class="radio-inline">
                      <input type="radio" name="status" value="9"> 폐점
                    </label>
                </div>
              </div>

              <div class="row col-sm-offset-3">
                <button class="btn btn-primary" type="submit">입력완료</button>
                <button class="btn btn-default" type="button">취소</button>
              </div>
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
    $(document).ready(function(){
      $("#register_form").validate({
        
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

      // 이벤트
      $("#btn_date1").click(function(e){
        $(".date-picker").datepicker("show");
      });
    });
    </script>
<?php
$this->load->view('layout/footer');
?>