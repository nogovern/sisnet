<?php
$this->view('layout/header');
$this->view('layout/navbar');
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

      <?php echo form_open('', 'id="register_form" role="form" class="form-horizontal" ');?>
      <!-- <form role="form"> -->
        
        <input type="hidden" name="category_name" id="category_name" value="">

        <div class="form-group">
          <label class="form-label col-sm-3">장비 타입을 선택하세요</label>
          <div class="col-sm-5">
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
          <div class="col-sm-4"></div>
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
          <label for="part_no" class="form-label col-sm-3">장비 코드(자동입력)</label>
          <div class="col-sm-7">
            <input type="text" class="form-control" id="part_no" name="part_no" placeholder="" readonly>
          </div>
        </div>

        <div class="form-group">
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
              <input type="radio" name="status" value="1" checked> 정상
            </label>
            <label class="radio-inline">
              <input type="radio" name="status" value="0"> 단종
            </label>
          </div>
        </div>

        <p class="form-actions">
          <button class="btn btn-primary" type="submit">등록</button>
          <a href="<?=site_url('admin/part/')?>"><span class="btn btn-default" type="button">취소</span></a>
        </p>
      </form>
    </div>
  </div><!-- end of row -->
</div><!-- start of div.container -->

<!-- jquery form validation -->
<script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.min.js"></script>
<script type="text/javascript">
$(document).ready(function(){
  $("#register_form").validate({
    rules: {
      type: 'required',
      category_id: {
        required: true,
        min: 1
      },
      name: 'required',
      company_id: {
        required: true,
        min: 1
      }
    },
    errorPlacement: function(error, el) {
      if( el.is(":radio")) {
        error.appendTo( el.closest('div').next());
      } else {
        error.insertAfter(el);
        el.closest(".form-group").addClass('has-error');
      }
    },
    success: function(el) {
        el.closest(".form-group").removeClass('has-error');
    },
    submitHandler: function(form) {
      form.submit();
    }
  });
});
</script>

<?php
$this->view('layout/footer');
?>