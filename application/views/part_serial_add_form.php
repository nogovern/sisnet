<?php
// 타이틀 설정
$title = (isset($title)) ? $title : '----- title is not defined ----';
$this->load->view('layout/header_popup', array('title' => "$title"));

// 팝업일 경우 표시 않함
if(!isset($popup) || $popup === FALSE) {
  $this->load->view('layout/navbar', array('current' => isset($current)? $current : 'page-admin'));
}
?>

    <!-- start of div.container -->
    <div class="container">
      <div>
<?php 
      echo form_open('', 'role="form" class="form-horizontal" ');
      // echo form_hidden('work_type', $form_hiddens['work_type']);
?>
        <!-- <form role="form"> -->
          <h2><span class="fa fa-pencil-square-o"></span>시리얼장비 등록</h2>
          
<?php
        echo validation_errors();   // 에러 출력
?>
          <div class="form-group">
            <label class="col-sm-3 control-label">입고 사무소</label>
            <div class="col-sm-7">
              <?=$select_office?>
            </div>
          </div>

          <div class="form-group">
            <label class="col-sm-3 control-label">장비 모델</label>
            <div class="col-sm-7">
              <?=$select_part?>
            </div>
          </div>

          <div class="form-group">
            <label class="col-sm-3 control-label">시리얼 넘버</label>
            <div class="col-sm-7">
              <input class="form-control" name="serial_number">
            </div>
          </div>

          <div class="form-group">
            <label class="col-sm-3 control-label">장비 상태</label>
            <div class="col-sm-7">
                <label>
                  <input type="radio" name="is_new" value="Y" checked>신품
                </label>
                &nbsp;/&nbsp;
                <label>
                  <input type="radio" name="is_new" value="N"> 중고
                </label>
            </div>
          </div>

          <div class="form-group">
            <label class="col-sm-3 control-label">최초 입고일</label>
            <div class="col-sm-7 input-group">
                <input type="text" id="date_enter" name="date_enter" class="form-control date-picker">
                <span class="input-group-addon" id="btn_date1"><i class="fa fa-calendar"></i></span>
            </div>
          </div>

          <div class="form-group">
            <label class="col-sm-3 control-label"> 메 모<br><small class="text-info">간단한 추가 요청 사항</small></label>
            <div class="col-sm-7">
              <textarea name="memo" id="memo" rows="2" class="form-control"></textarea>
            </div>
          </div>

          <p class="form-actions col-sm-offset-3 col-sm-7">
            <button class="btn btn-primary" type="submit">입력완료</button>
            <button id="ajax" class="btn btn-danger" type="button">다른 종류</button>
          </p>
        </form>
      </div>
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
          // minDate: new Date(),
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