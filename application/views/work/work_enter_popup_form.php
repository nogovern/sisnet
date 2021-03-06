<?php
// 타이틀 설정
$title = (isset($title)) ? $title : '----- title is not defined ----';

$this->load->view('layout/header_popup', array('title' => "$title"));
?>

    <!-- start of div.container -->
    <div class="container">
    
      <!-- Example row of columns -->
      <div class="row">
        <div class="col-md-8">
          <div>
<?php 
echo form_open('', 'role="form"');

/*=============================================
  hidden 값은 로그인 session 을 사용 해야 함!
 *=============================================
 */
echo form_hidden('op_type', $form_hiddens['op_type']);
echo form_hidden('user_id', $form_hiddens['user_id']);        // 사용자
echo form_hidden('office_id', $form_hiddens['office_id']);      // 사무소
echo form_hidden('part_id', $form_hiddens['part_id']);
?>
            <!-- <form role="form"> -->
              <h2><span class="fa fa-pencil-square-o"></span> 입고 요청 양식</h2>
              
          <?php
            echo validation_errors();   // 에러 출력
          ?>
              <div class="form-group">
                <label for="office_name">사무소명</label>
                <input type="text" class="form-control" id="office_name" name="office_name" value="<?=set_value('office_name', $office_name)?>" disabled>
              </div>

              <div class="form-group">
                <label for="part_name">장비명</label>
                <input type="text" class="form-control" id="part_name" name="part_name" value="<?=set_value('part_name', $part_name)?>" disabled>
              </div>

              <div class="form-group">
                <label for="qty">수량</label>
                <input type="text" class="form-control" id="qty" name="qty" placeholder="수량을 입력하세요">
              </div>

              <div class="form-group">
                <label class="control-label">입고 예정일 <small class="text-danger">입고 희망 날짜</small></label>
                <div class="input-group">
                    <input type="text" id="date_request" name="date_request" class="form-control date-picker">
                    <span class="input-group-addon" id="btn_date1"><i class="fa fa-calendar"></i></span>
                </div>
              </div>

              <div class="form-group">
                <label for="memo">메 모  <small class="text-success">간단한 추가 요청 사항</small></label>
                <textarea name="memo" id="memo" rows="2" class="form-control"></textarea>
              </div>

              <p class="form-actions">
                <button class="btn btn-primary" type="submit">입력완료</button>
              </p>
            </form>
          </div>

        </div>
      </div>
    <!-- start of div.container -->
    </div>

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