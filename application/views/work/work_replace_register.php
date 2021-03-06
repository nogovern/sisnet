<?php
$this->view('layout/header');
$this->view('layout/navbar');
?>

<!-- start of div.container -->
<div class="container">

  <!-- Example row of columns -->
  <div class="row">
    <h2><span class="fa fa-pencil-square-o"></span> 교체 요청 양식</h2>
<?php
    // 에러 출력
    echo validation_errors();
    echo form_open('', 'enctype="multipart/form-data" role="form" class="form-horizontal" ');

    ////////////////
    // 교체 업무 기본 값 - hidden element
    ////////////////
?>
      <input type="hidden" name="store_id" id="store_id" value="">
      <input type="hidden" name="op_type" id="op_type" value="400">

      <fieldset class="col-sm-7">
        <legend>교체 양식</legend>

        <div class="form-group">
          <label class="control-label col-sm-3">교체 점포명</label>
          <div class="col-sm-5">
            <input type="text" class="form-control required" id="store_name" name="store_name">
          </div>
          <div class="col-sm-4" style="padding-top:1px;">
            <button type="button" id="btn_search_store" class="btn btn-info">검색</button>
          </div>
        </div>
        
        <div class="form-group">
          <label class="control-label col-sm-3">철수요청일</label>
          <div class="col-sm-6">
            <div class="input-group">
                <input type="text" id="date_close" name="date_close" class="form-control date-picker" readonly>
                <span class="input-group-addon btn_date"><i class="fa fa-calendar"></i></span>
            </div>
          </div>
          <div class="col-sm-2">
            <select class="form-control" id="date_close_hour" name="date_close_hour">
              <?php for($i=0; $i < 25; $i++):?>
              <option value="<?=$i?>"><?=$i?>시</option>
              <?php endfor; ?>
            </select>
          </div>
        </div>

        <div class="form-group">
          <label class="control-label col-sm-3">설치요청일</label>
          <div class="col-sm-6">
            <div class="input-group">
              <input type="text" id="date_open" name="date_open" class="form-control date-picker" readonly>
              <span class="input-group-addon btn_date"><i class="fa fa-calendar"></i></span>
            </div>
          </div>
          <div class="col-sm-2">
            <select class="form-control" id="date_open_hour" name="date_open_hour">
              <?php for($i=0; $i < 25; $i++):?>
              <option value="<?=$i?>"><?=$i?>시</option>
              <?php endfor; ?>
            </select>
          </div>
        </div>
        
        <div class="form-group">
          <label class="control-label col-sm-3">교체 사무소</label>
          <div class="col-sm-5">
<?php
          echo $select_office;
?>                  
          </div>
        </div>

        <div class="form-group">
          <label class="control-label col-sm-3">파일 첨부</label>
          <div class="col-sm-9">
            <input type="file" class="form-control" name="userfile[]"></input>
            <input type="file" class="form-control" name="userfile[]"></input>
            <input type="file" class="form-control" name="userfile[]"></input>
            <div class="help-block">파일 당 <?=GS2_MAX_FILE_SIZE?> M bytes 까지 업로드 가능합니다 </div>
          </div>
        </div>

        <div class="form-group">
          <label class="control-label col-sm-3">메  모<br>(최대 1,300자)</label>
          <div class="col-sm-9">
            <textarea class="form-control" name="memo" rows="7"></textarea>
          </div>
        </div>
      </fieldset>
      
      <!-- 점포 정보 -->
      <div id="store_info" class="col-sm-5" style="padding-top:30px;">
        <div class="panel panel-default">
          <div class="panel-heading">점포 정보</div>
          <div class="panel-body">
            선택하신 점포가 없습니다
          </div>
        </div>

      </div>
      <div class="clearfix"></div>

      <p class="row col-sm-offset-2">
        <button id="btn_save" class="btn btn-primary" type="submit">저장</button>
        <a href="<?=base_url() . 'work/replace'?>" class="btn btn-default" >닫기</a>
      </p>
    </form>

  </div>
</div><!-- start of div.container -->

<?php
$this->view('common/modal_search_store');     // 점포 검색 modal
?>

<script type="text/javascript">
// 점포 검색이 되었는지 구분 변수
var is_store_setted = false;

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

  // datepicker 아이콘 이벤트
  $(".btn_date").click(function(e){
    var pa = $(this).parent();
    $(".date-picker", pa).datepicker("show");
  });

  // 점포명 에서 enter 키 눌렀을때 처리
  $("#store_name").keypress(function(e){
    if(e.keyCode == 13){
      e.preventDefault();   // submit 막기 
      $('#btn_search_store').click();
    }
  });

  // 점포명 텍스트 변경시 결과 reset
  $("#store_name").change(function(){
    is_store_setted = false;
  });

  //////////////
  // 점포 검색
  //////////////
  $("#btn_search_store").click(function(){
    var query = $.trim($("#store_name").val());      // 점포명
    if(query === '') {
      alert('최소 2자 이상의 점포명을 입력하세요');
      $("#store_name").val('').focus();
      return false;
    }
    var url = '<?=site_url("admin/store/ajax/search")?>';
    var request_uri = url + '/' + encodeURIComponent(query);

    $.ajax({
        url: url,
        type: "POST",
        data: {
          query : encodeURIComponent(query),
          "csrf_test_name": $.cookie("csrf_cookie_name")
        },
        dataType: "html",
      })
      .done(function(text) {
        // 함수화 해도 되겠는데요
        $("#modal_store_search_result table tbody").html('').html(text);
        // 검색결과 표시
        var len = $("#modal_store_search_result table tbody tr").length;
        $("#modal_store_search_result .num_rows").text(len);
        $("#modal_store_search_result").modal('show');
      })
      .fail(function(xhr, textStatus){
        alert("Request failed: " + textStatus);
      });

  });

  $("form").validate({
    rules: {
      store_id: {
        required: true,
        min: 1
      },
      office_id: {
        required: true,
        min: 1
      },
      store_name: "required",
      date_close: "required",
      date_open: "required"
    }, 
    submitHandler: function(form) {
      if(!is_store_setted) {
        alert("점포가 제대로 선택되지 않았습니다.\n올바른 점포명으로 검색하여 선택하세요.");
        $("#store_name").focus();
        $('html, body').animate({
          scrollTop: $("#store_name").offset().top - 500
        }, 1000);
        return false;
      }

      form.submit();
    }
  });
  
}); //end of jQuery ready


///////////////////////////////////
// 점포 검색용 callback function
///////////////////////////////////
function callback_store_info(id, name) {
  // 점포 정보
  var url2 = "<?=site_url("admin/store/showTableFormat")?>" + "/" + id;
  $("#store_info .panel-body").load(url2);

  // 점포 검색 되었음을 나타냄
  is_store_setted = true;
  $(":hidden[name=store_id]").val(id);
  $("#store_name").val(name);
}

// 점포 검색 창에서 점포 신규 등록시 
// 기존 colorbox 를 닫은 후 colorbox 다시 열기
function callback_store_register() {
  // close() 를 사용하면 이후 colorbox 가 열리지 않음
  // $.colorbox.close();

  var url = '<?=site_url("/admin/store/register/popup")?>';
  // alert(url);

  $.colorbox({
    href  : url,
    iframe  : true,
    opacity : 0.5,
    width   : '70%',
    height  : '90%',
    overlayClose: false
  });
}
</script>

<?php
$this->view('layout/footer');
?>
