<?php
$this->load->view('layout/header', array('title' => '철수 >> 철수 요청 양식'));
$this->load->view('layout/navbar', array('current' => 'page-work-install'));
?>

    <!-- start of div.container -->
    <div class="container">
    
      <!-- Example row of columns -->
      <div class="row">
<?php
        // 에러 출력
        echo validation_errors();
        echo form_open('', 'role="form" class="form-horizontal" ');
        echo form_hidden('store_id', '', ' id="store_id"');

        // test 용 
        echo form_hidden('user_id', "$user_id", ' id="user_id"');        
?>
          <h2><span class="fa fa-pencil-square-o"></span> 철수 요청 양식</h2>
            <fieldset class="col-sm-7">
              <legend>철수 양식</legend>

              <div class="form-group">
                <label class="control-label col-sm-3">철수 점포명</label>
                <div class="col-sm-5">
                  <input type="text" class="form-control required" id="store_name" name="store_name">
                </div>
                <div class="col-sm-4" style="padding-top:1px;">
                  <button type="button" id="btn_search" class="btn btn-info">검색</button>
                  <button type="button" id="btn_search2" class="btn btn-default">검색 - dialog</button>
                </div>
              </div>

              <div class="form-group">
                <label class="control-label col-sm-3">점포 폐점일</label>
                <div class="col-sm-6">
                  <div class="input-group">
                      <input type="text" id="date_close" name="date_close" class="form-control date-picker" readonly>
                      <span class="input-group-addon btn_date"><i class="fa fa-calendar"></i></span>
                  </div>
                </div>
              </div>

              <div class="form-group">
                <label class="control-label col-sm-3">철수 일시</label>
                <div class="col-sm-6">
                  <div class="input-group">
                    <input type="text" id="date_request" name="date_request" class="form-control date-picker" readonly>
                    <span class="input-group-addon btn_date"><i class="fa fa-calendar"></i></span>
                  </div>
                </div>
                <div class="col-sm-2">
                  <select class="form-control" name="date_request_hour">
                    <?php for($i=0; $i < 25; $i++):?>
                    <option value="<?=$i?>"><?=$i?>시</option>
                    <?php endfor; ?>
                  </select>
                </div>
              </div>

              <div class="form-group">
                <label class="control-label col-sm-3">철수 형태</label>
                <div class="col-sm-5">
                  <select class="form-control" name="op_type" id="op_type">
                    <option value="301">폐점</option>
                    <option value="302">휴점S</option>
                    <option value="303">휴점C</option>
                    <option value="304">리뉴얼</option>
                  </select>
                </div>
              </div>

              <div class="form-group">
                <label class="control-label col-sm-3">철수 사무소</label>
                <div class="col-sm-5">
<?php
                echo $select_office;
?>                  
                </div>
              </div>

              <div class="form-group">
                <label class="control-label col-sm-3">메  모</label>
                <div class="col-sm-9">
                  <textarea class="form-control" name="memo"></textarea>
                </div>
              </div>

              <div class="form-group">
                <label class="control-label col-sm-3">파일 첨부</label>
                <div class="col-sm-9">
                  <input type="file" class="form-control" name="file1"></input>
                  <input type="file" class="form-control" name="file2"></input>
                  <input type="file" class="form-control" name="file3"></input>
                </div>
              </div>
            </fieldset>

            <div class="col-sm-5" style="padding-top:30px;">
              <div class="panel panel-default">
                <div class="panel-heading">점포 정보</div>
                <div class="panel-body">
                  선택하신 점포가 없습니다
                </div>
              </div>

            </div>
          <div class="clearfix"></div>

          <p class="row col-sm-offset-2">
            <button id="btn_save" class="btn btn-primary" type="submit">저 장</button>
            <button id="btn_close" class="btn btn-default" type="button">닫 기</button>
          </p>
        </form>

      </div>
    </div><!-- start of div.container -->

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

      // datepicke 아이콘 이벤트
      $(".btn_date").click(function(e){
        var pa = $(this).parent();
        $(".date-picker", pa).datepicker("show");
      });

      // colorbox - 점포 검색
      $("#btn_search").click(function(){
        var query = $.trim($("#store_name").val());      // 점포명
        if(query === '') {
          alert('최소 2자 이상의 점포명을 입력하세요');
          $("#store_name").val('').focus();
          return false;
        }
        var url = '<?=site_url("/util/store/search")?>';
        var request_uri = url + '/' + encodeURIComponent(query);

        $.colorbox({
          'href'  : request_uri,
          'iframe'  : true,
          'opacity' : 0.5,
          'width'   : '50%',
          'height'  : '90%'
        });
      });

      // $("form").validate({
      // });
      $("#btn_search2").click(function(e){
        $("#dialog-form2").dialog("open");
      });

      $("#dialog-form2").dialog({
        autoOpen: false,
        modal: true,
        width: "auto",
        height: "auto",
        open: function(ev, ui) {
          $("#inner_frame").attr('src', '<?=site_url("/admin/store/add")?>');
        },
        buttons: {
          "저장": function() {
            $(this).dialog('close');
          },
          "닫기": function() {
            $(this).dialog('close');
          }
        }
      });

      $("form").submit(function(e){
        if($("#store_name").val() === '') {
          alert('필수 항목!');
          $("#store_name").focus();
          return false;
        }
      });
    }); //end of jQuery ready
    
    ///////////////////////////////////
    // 점포 검색용 callback function
    ///////////////////////////////////
    function callback_store_info(id, name) {
      $(":hidden[name=store_id]").val(id);
      $("#store_name").val(name).attr('readonly', true);
    }
    </script>
<?php
$this->load->view('layout/footer');
?>
<div id="dialog-form" title="Create new user">
  <p class="validateTips">All form fields are required.</p>
  <form class="form">
  <fieldset>
    <label for="name">Name</label>
    <input type="text" name="name" id="name" class="text ui-widget-content ui-corner-all">
    <label for="email">Email</label>
    <input type="text" name="email" id="email" value="" class="text ui-widget-content ui-corner-all">
    <label for="password">Password</label>
    <input type="password" name="password" id="password" value="" class="text ui-widget-content ui-corner-all">
  </fieldset>
  </form>
</div>

