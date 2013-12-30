<?php
$this->load->view('layout/header', array('title' => '설치 >> 설치 요청 양식'));
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
  ?>
          <h2><span class="fa fa-pencil-square-o"></span> 설치 요청 양식</h2>
            <fieldset class="col-sm-7">
              <legend>설치 양식</legend>

              <div class="form-group">
                <label class="control-label col-sm-3">설치 점포명</label>
                <div class="col-sm-5">
                  <input type="text" class="form-control" id="store_name" name="store_name">
                </div>
                <div class="col-sm-4" style="padding-top:1px;">
                  <button type="button" id="btn_search" class="btn btn-info">검 색</button>
                </div>
              </div>

              <div class="form-group">
                <label class="control-label col-sm-3">점포 개점일</label>
                <div class="input-group col-sm-6">
                    <input type="text" id="date_open" name="date_open" class="form-control date-picker" readonly>
                    <span class="input-group-addon btn_date"><i class="fa fa-calendar"></i></span>
                </div>
              </div>

              <div class="form-group">
                <label class="control-label col-sm-3">설치 일시</label>
                <div class="input-group col-sm-6">
                  <input type="text" id="date_work" name="date_work" class="form-control date-picker" readonly>
                  <span class="input-group-addon btn_date"><i class="fa fa-calendar"></i></span>
                </div>
                <div class="col-sm-3">
                  <select class="form-control" name="date_work_hour">
                    <?php for($i=0; $i < 25; $i++):?>
                    <option value="<?=$i?>"><?=$i?>시</option>
                    <?php endfor; ?>
                  </select>
                </div>
              </div>

              <div class="form-group">
                <label class="control-label col-sm-3">설치 형태</label>
                <div class="col-sm-5">
                  <select class="form-control" name="install_type" id="install_type">
                    <option value="201">신규</option>
                    <option value="202">휴점S</option>
                    <option value="203">휴점C</option>
                  </select>
                </div>
              </div>

              <div class="form-group">
                <label class="control-label col-sm-3">설치 사무소</label>
                <div class="col-sm-5">
<?php
                echo $select_office;
?>                  
                </div>
              </div>

              <div class="form-group">
                <label class="control-label col-sm-3">메  모</label>
                <div class="col-sm-9">
                  <textarea class="form-control" name="name"></textarea>
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
        console.log(request_uri);

        $.colorbox({
          'href'  : request_uri,
          'iframe'  : true,
          'opacity' : 0.5,
          'width'   : '50%',
          'height'  : '90%'
        });
      });
    });
    </script>
<?php
$this->load->view('layout/footer');
?>