<?php
$this->load->view('layout/header', array('title' => '사무소 >> 신규 등록'));
$this->load->view('layout/navbar', array('current' => 'page-admin-office'));
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
echo form_open('', 'role="form" class="form form-horizontal" ');
?>
              <div class="page-header">
                <h2><span class="fa fa-pencil-square-o"></span> 사무소 등록</h2>
              </div>

              <div class="form-group">
                <label class="form-label col-sm-3">타입을 선택하세요</label>
                <div class="col-sm-7">
                  <label class="radio-inline">
                    <input type="radio" id="type1" name="type" value="O" checked> 사무소
                  </label>
                  <label class="radio-inline">
                    <input type="radio" id="type2" name="type" value="I"> 창고
                  </label>
                </div>
              </div>

              <div class="form-group">
                <label for="name" class="form-label col-sm-3">사무소명</label>
                <div class="col-sm-7">
                  <input type="text" class="form-control" id="name" name="name" placeholder="입력하세요" required>
                </div>
              </div>

              <!-- 마스터 사무소 선택-->
              <div class="form-group">
                <label for="office_id" class="form-label col-sm-3">
                  상위 사무소 선택
                </label>
                <div class="col-sm-7">

<?php
  echo $select_office;
?>
                  <span class="help-block"><small class="text-info">선택 안하면 최상위 사무소가 됩니다</small></span>
                </div>
              </div>

              <!-- 담당자 선택-->
              <div class="form-group">
                <label for="user_id" class="form-label col-sm-3">담당자 선택</label>
                <div class="col-sm-7">
<?php
  echo $select_user;
?>
                </div>
              </div>

              <!-- 담당자 선택 (임시)-->
              <div class="form-group">
                <label for="aaa" class="form-label col-sm-3">optgroup</label>
                <div class="col-sm-7">
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

              <div class="form-group">
                <label for="phone" class="form-label col-sm-3">전화번호</label>
                <div class="col-sm-7">
                  <input type="text" class="form-control" id="phone" name="phone" placeholder="입력하세요">
                </div>
              </div>

              <div class="form-group">
                <label for="address" class="form-label col-sm-3">주 소</label>
                <div class="col-sm-7">
                  <input type="text" class="form-control" id="address" name="address" placeholder="입력하세요">
                  <span class="help-block">Start typing e.g. Yellow, Red</span>
                </div>
              </div>

              <div class="form-group">
                <label for="memo" class="form-label col-sm-3">메 모  <br><small class="text-success">간단한 추가 요청 사항</small></label>
                <div class="col-sm-7">
                  <textarea name="memo" id="memo" rows="3" class="form-control"></textarea>
                </div>
              </div>

              <div class="form-group">
                <label class="form-label col-sm-3">장비 상태</label>
                <div class="col-sm-7">
                  <label class="radio-inline">
                    <input type="radio" name="status" value="1" checked> 정상
                  </label> /
                  <label class="radio-inline">
                    <input type="radio" name="status" value="0"> 비활성
                  </label>
                  <label class="radio-inline">
                    <input type="radio" name="status" value="2"> 폐쇄
                  </label>
                </div>
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