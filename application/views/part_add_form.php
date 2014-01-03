<?php
$this->load->view('layout/header', array('title' => '관리자 >> 장비 등록'));
$this->load->view('layout/navbar', array('current' => 'page_admin'));
?>
    <!-- start of div.container -->
    <div class="container">
    
      <!-- Example row of columns -->
      <div class="row">
        <div class="col-md-10">
          <div class="page-header">
            <h2><span class="fa fa-pencil-square-o"></span> 장비 등록</h2>
          </div>
<?php
echo validation_errors();             // 에러 출력
?>
 
          <?php echo form_open('', 'role="form" class="form-horizontal" ');?>
          <!-- <form role="form"> -->
            
            <input type="hidden" name="category_name" id="category_name" value="">

            <div class="form-group">
              <label class="form-label col-sm-3">장비 타입을 선택하세요</label>
              <div class="col-sm-7">
                <label class="radio-inline">
                  <input type="radio" id="type1" name="type" value="1" required> 시리얼 관리 장비
                </label>
                <label class="radio-inline">
                  <input type="radio" id="type2" name="type" value="2"> 수량 관리 장비
                </label>
                <label class="radio-inline">
                  <input type="radio" id="type3" name="type" value="3"> 소모품
                </label>
              </div>
            </div>

            <div class="form-group">
              <label for="category_id" class="form-label col-sm-3">장비 종류</label>
              <div class="col-sm-7">
<?php
echo $select_category;
?>
              </div>
            </div>

            <div class="form-group" >
              <label for="part_no" class="form-label col-sm-3">장비 식별자 입력 (자동입력)</label>
              <div class="col-sm-7">
                <input type="text" class="form-control" id="part_no" name="part_no" placeholder="Enter...">
              </div>
            </div>

            <div class="form-group has-success">
              <label for="name" class="form-label col-sm-3">모델명</label>
              <div class="col-sm-7">
                <input type="text" class="form-control" id="name" name="name" placeholder="Enter...">
              </div>
            </div>

            <div class="form-group">
              <label for="manufacturer" class="form-label col-sm-3">제조사명</label>
              <div class="col-sm-7">
                <input type="text" class="form-control" id="manufacturer" name="manufacturer" placeholder="Enter...">
              </div>
            </div>
            
            <div class="form-group">
              <label for="company_id" class="form-label col-sm-3">납품처</label>
              <div class="col-sm-7">
<?php
echo $select_company;
?>
              </div>
            </div>

            <div class="form-group">
              <label class="form-label col-sm-3">장비 취급 여부</label>
              <div class="col-sm-7">
                <label class="radio-inline">
                  <input type="radio" name="status" value="1" required> 정상
                </label>
                <label class="radio-inline">
                  <input type="radio" name="status" value="0"> 단종
                </label>
                <label class="radio-inline">
                  <input type="radio" name="status" value="2"> 임시 품절
                </label>
              </div>
            </div>

            <p class="form-actions">
              <button class="btn btn-primary" type="submit">입력완료</button>
            </p>
          </form>
        </div>
      </div><!-- end of row -->
    </div><!-- start of div.container -->

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

<?php
$this->load->view('layout/footer');
?>