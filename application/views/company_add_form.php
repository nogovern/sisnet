<?php
$this->view('layout/header');
$this->view('layout/navbar');
?>

<!-- start of div.container -->
<div class="container">

  <!-- Example row of columns -->
  <div class="row">
    <div class="col-md-8 col-sx-12">
      <h2>거래처 등록 (관리자 전용)</h2>

      <?php echo form_open('', 'role="form" class="form-horizontal"');?>
      <!-- <form role="form"> -->
        <p>
        <?php
          echo validation_errors();   // 에러 출력
        ?>
        </p>

        <div class="form-group">
          <p>업체 타입을 선택하세요</p>
          <label class="radio-inline">
            <input type="radio" class="grey" name="type" value="1">
            시스네트
          </label>
          <label class="radio-inline">
            <input type="radio" class="grey" name="type" value="2">
            GS25
          </label>
          <label class="radio-inline">
            <input type="radio" class="grey" name="type" value="3">
            납품
          </label>
          <label class="radio-inline">
            <input type="radio" class="grey" name="type" value="4">
            수리
          </label>
          <label class="radio-inline">
            <input type="radio" class="grey" name="type" value="5">
            제조
          </label>
          <label class="radio-inline">
            <input type="radio" class="grey" name="type" value="6">
            폐기
          </label>
        </div>

        <div class="form-group">
          <label for="name">업체명</label>
          <input type="text" class="form-control" id="name" name="name" placeholder="업체명을 입력하세요">
        </div>

        <div class="form-group">
          <label for="code">업체 코드</label>
          <input type="text" class="form-control" id="code" name="code" placeholder="입력하세요...">
          <p class="help-block">
            <span class="label label-warning">NOTE!</span> Exmaple block-level help text here.
          </p>
        </div>
        
        <div class="form-group">
          <label for="user_id">담당자 선택</label>
<?php
echo $select_user;
?>
        </div>

        <div class="form-group">
          <label for="tel">연락처</label>
          <input type="text" class="form-control" id="tel" name="tel" placeholder="입력하세요...">
        </div>

        <div class="form-group">
          <label for="address">주 소</label>
          <input type="text" class="form-control" id="address" name="address" placeholder="입력하세요...">
        </div>

        <div class="form-group">
          <label for="memo">메 모  <small class="text-success">업체 설명</small></label>
          <textarea name="memo" id="memo" rows="3" class="form-control"></textarea>
        </div>

        <p class="row well">
          <button class="btn btn-primary" type="submit">입력완료</button>
        </p>
      </form>
    </div>

    <div class="col-md-4"></div>
  </div><!-- end of row -->
</div> <!-- start of div.container -->

<!-- jquery form validation -->
<script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.min.js"></script>
<script type="text/javascript">
$(document).ready(function(){
  $("#re_password").parent().addClass('has-warning');
  $("form").submit(function(){
    var selected = $("#part_id").val();
    if( selected == '' ){
      alert('장비를 선택하세요');
      return false;
    }

  });

});
</script>

<?php
$this->view('layout/footer');
?>