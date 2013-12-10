<?php
$this->load->view('layout/header', array('title' => '안녕하세요'));
$this->load->view('layout/navbar', array('current' => 'page_enter'));
?>

    <!-- start of div.container -->
    <div class="container">
    
      <!-- Example row of columns -->
      <div class="row">
        <div class="col-md-8">
          <?php
            // 에러 출력
            echo validation_errors();
          ?>
          <ul class="well">
            <li>작업 요청일</li>
            <li>담당자 - 입고시는 제고사/납품처 담당자</li>
            <li>메모</li>
            <li>장비</li>
            <li>수량</li>
            <li>장비 상태 (중고/신품)</li>
            <li></li>
          </ul>
 
          <div>
<?php 
echo form_open('', 'role="form"');

/*
hidden 대신 로그인 session 을 이용해도 될듯
 */
echo form_hidden('type', '101');
echo form_hidden('office_id', '');      // 사무소
echo form_hidden('user_id', '');        // 사용자
?>
            <!-- <form role="form"> -->
              <h2><span class="fa fa-pencil-square-o"></span> 입고 요청 양식</h2>

              <!-- 장비 선택-->
              <div class="form-group">
                <label for="part_id" class="control-label">장비 선택</label>
                <?php
                // echo $form_part_select;
                ?>
              </div>

              <div class="form-group">
                <label class="control-label">입고 예정일 <small class="text-danger">입고 희망 날짜</small></label>
                <div class="input-group">
                    <input type="text" id="date_request1" name="date_request1" class="form-control date-picker">
                    <span class="input-group-addon" id="btn_date1"><i class="fa fa-calendar"></i></span>
                </div>
              </div>

              <div class="form-group">
                <label for="username">사용자 ID 입력</label>
                <input type="text" class="form-control" id="username" name="username" placeholder="Enter ID">
              </div>

              <div class="form-group">
                <label for="name">이름</label>
                <input type="text" class="form-control" id="name" name="name" placeholder="이름을 입력하세요">
              </div>

              <div class="form-group">
                <label for="memo">메 모  <small class="text-success">간단한 추가 요청 사항</small></label>
                <textarea name="memo" id="memo" rows="3" class="form-control"></textarea>
              </div>

              <p class="form-actions">
                <button class="btn btn-primary" type="submit">입력완료</button>
                <button id="ajax" class="btn btn-danger" type="button">다른 종류</button>
              </p>
            </form>
          </div>

        </div>
      </div>
    <!-- start of div.container -->
    </div>

    <!-- jquery form validation -->

    <link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css">
    <script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>

    <script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.min.js"></script>

    <script type="text/javascript">
    $(document).ready(function(){
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
      $("#btn_date1").click(function(){
        $(".date-picker").datepicker("show");
      });
    });
    </script>
<?php
$this->load->view('layout/footer');
?>