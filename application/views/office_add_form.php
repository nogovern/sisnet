<?php
$this->load->view('layout/header', array('title' => '사무소 >> 신규 등록'));
$this->load->view('layout/navbar', array('current' => 'page-admin-office'));
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
 
          <div>
<?php 
echo form_open('', 'role="form"');
?>
            <!-- <form role="form"> -->
              <h2><span class="fa fa-pencil-square-o"></span> 사무소 등록</h2>

              <div class="form-group">
                <label for="name">사무소명</label>
                <input type="text" class="form-control" id="name" name="name" placeholder="입력하세요" required>
              </div>

              <div class="form-group">
                <label for="code">사무소 CODE</label>
                <input type="text" class="form-control" id="code" name="code" placeholder="입력하세요">
              </div>

              <!-- 창고 선택-->
              <div class="form-group">
                <label for="inventory_id" class="control-label">담당 창고 선택 <small class="text-danger">없으면 그냥 두세요</small></label>
                <?php
                  echo $select_inventory;
                ?>
              </div>

              <!-- 담당자 선택-->
              <div class="form-group">
                <label for="user_id" class="control-label">담당자 선택</label>
                <?php
                  echo $select_user;
                ?>
              </div>

              <!-- 담당자 선택 (임시)-->
              <div class="form-group">
                <label for="aaa" class="control-label">optgroup</label>
                <select class="form-control" name="aaa" id="aaa">
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

              <div class="form-group">
                <label for="tel">전화번호</label>
                <input type="text" class="form-control" id="tel" name="tel" placeholder="입력하세요">
              </div>

              <div class="form-group">
                <label for="address">주 소</label>
                <input type="text" class="form-control" id="address" name="address" placeholder="입력하세요">
                <span class="help-block">Start typing e.g. Yellow, Red</span>
              </div>

              <div class="form-group">
                <label for="memo">메 모  <small class="text-success">간단한 추가 요청 사항</small></label>
                <textarea name="memo" id="memo" rows="3" class="form-control"></textarea>
              </div>

              <p class="form-actions">
                <button class="btn btn-primary" type="submit">입력완료</button>
                <button class="btn btn-default" type="button">취소</button>
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