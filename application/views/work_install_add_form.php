<?php
$this->view('layout/header');
$this->view('layout/navbar');
?>

    <!-- start of div.container -->
    <div class="container">
    
      <!-- Example row of columns -->
      <div class="row">
<?php
        // 에러 출력
        echo validation_errors();
        echo form_open_multipart('', 'role="form" class="form-horizontal" ');

        // echo form_hidden('store_id', '', ' id="store_id"');
?>
          <input type="hidden" name="store_id" id="store_id" value="">

          <h2><span class="fa fa-pencil-square-o"></span> 설치 요청 양식</h2>
            <fieldset class="col-sm-7">
              <legend>설치 양식</legend>

              <div class="form-group">
                <label class="control-label col-sm-3">설치 점포명</label>
                <div class="col-sm-5">
                  <input type="text" class="form-control required" id="store_name" name="store_name">
                </div>
                <div class="col-sm-4" style="padding-top:1px;">
                  <button type="button" id="btn_search_store" class="btn btn-info">검 색</button>
                </div>
              </div>

              <div class="form-group">
                <label class="control-label col-sm-3">점포 개점일</label>
                <div class="col-sm-6">
                  <div class="input-group">
                      <input type="text" id="date_open" name="date_open" class="form-control date-picker" readonly>
                      <span class="input-group-addon btn_date"><i class="fa fa-calendar"></i></span>
                  </div>
                </div>
              </div>

              <div class="form-group">
                <label class="control-label col-sm-3">설치 일시</label>
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
                <label class="control-label col-sm-3">설치 형태</label>
                <div class="col-sm-5">
                  <select class="form-control" name="op_type" id="op_type">
                    <option value="201">신규</option>
                    <option value="203">휴점-보관</option>
                    <option value="204">휴점-점검</option>
                    <option value="206">리뉴얼</option>
                    <option value="202">서비스</option>
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
                <label class="control-label col-sm-3">파일 첨부</label>
                <div class="col-sm-9">
                  <input type="file" class="form-control" name="userfile[]"></input>
                  <input type="file" class="form-control" name="userfile[]"></input>
                  <input type="file" class="form-control" name="userfile[]"></input>

                  <div class="help-block">파일 당 <?=GS2_MAX_FILE_SIZE?> M bytes 까지 업로드 가능합니다 </div>
                </div>
              </div>
              
              <div class="form-group">
                <label class="control-label col-sm-3">메  모</label>
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
            <a href="<?=base_url() . 'work/install'?>" class="btn btn-default">닫기</a>
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

      // 점포명 에서 enter 키 눌렀을때 처리
      $("#store_name").keypress(function(e){
        if(e.keyCode == 13){
          e.preventDefault();
          $('#btn_search_store').click();
        }
      });

      // colorbox - 점포 검색
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
            var modal = $("#modal_store_search_result");

            // clear & fit
            $("#modal_store_search_result table tbody").html('').html(text);  
            $("#modal_store_search_result").css('top', '20%').modal('show');
          })
          .fail(function(xhr, textStatus){
            alert("Request failed: " + textStatus);
          });

      });

      // $("form").validate({
      // });

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
      $("#store_name").val(name);

      // 점포 정보
      var url2 = "<?=site_url("admin/store/showTableFormat")?>" + "/" + id;
      $("#store_info .panel-body").load(url2);
    }

    // 점포 검색 창에서 점포 신규 등록시 
    // 기존 colorbox 를 닫은 후 colorbox 다시 열기
    function callback_store_register() {
      var url = '<?=site_url("/admin/store/register/popup")?>';

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

<!-- 점포 검색 modal dialog -->
<div class="modal fade" id="modal_store_search_result" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title">점포 검색 리스트</h4>
      </div>
      <div class="modal-body">
        <h5>점포 검색 결과 : <span id="cnt_result"></span> 건</h5>
        <table class="table table-hover">
          <thead>
            <tr>
              <th></th>
              <th>점포명</th>
              <th>점주</th>
              <th>주소</th>
              <th>연락처</th>
              <th>상태</th>
              <th>선택</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
            </tr>
          </tbody>
        </table>
      </div>
      <div class="modal-footer">
        <button id="btn_modal_store_register" type="button" class="btn btn-primary">신규 등록</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">닫기</button>
      </div>
    </div>
  </div>
</div><!-- /.modal -->

<?php
// $this->view('common/modla_store_info');
?>

<script type="text/javascript">
  $(document).ready(function(){
    $("#btn_modal_store_register").click(function(e){
      $("#modal_store_search_result").modal("hide");
      callback_store_register();
    });
  });

  $(document).on('click', '.select_me', function(e){
    // 상점 id, name
    var store_id = $(this).closest('tr').find('td:eq(0)').text();
    var store_name = $(this).closest('tr').find('td:eq(1)').text();

    // callback 함수 사용하여 부모창 element 에 설정
    callback_store_info(store_id, store_name);

    $("#modal_store_search_result").modal("hide");
  });
</script>

<?php
$this->view('layout/footer');
?>
