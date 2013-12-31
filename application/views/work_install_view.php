<?php
$this->load->view('layout/header', array('title' => '설치 >> 설치 요청 보기'));
$this->load->view('layout/navbar', array('current' => 'page-work-install'));

?>

    <!-- start of div.container -->
    <div class="container">
      <div class="page-header">
        <h1><span class="fa fa-desktop"></span>&nbsp;설치 업무</h1>
      </div>
<?php
// 업무 공통 헤더
$this->load->view('_work_view_header', $work);
?>

      <div class="row">
        <div class="col-md-12">
          <!-- start: ALERTS PANEL -->
          <div class="panel panel-primary">
            <div class="panel-heading"><i class="fa fa-tags"></i> 장비 리스트</div>
            <div class="panel-body" style="padding:0 15px;">
              <table class="table table-hover" id="part_table">
                <thead>
                  <tr>
                    <th>#</th>
                    <th>장비명</th>
                    <th>S/N</th>
                    <th>등록수량</th>
                    <th>삭제</th>
                  </tr>
                </thead>
                <tbody>
<?php
$i = 1;
$item_count = count($items);
foreach($items as $item):
?>                  
                  <tr data-temp_id="<?=$item->id?>">
                    <td><?=$i++?></td>
                    <td><?=$item->part->name?></td>
                    <td><?=($item->part->type == '1') ? $item->serial_number : ''?></td>
                    <td><?=$item->qty?></td>
                    <td style="width:150px;">
                      <button class="btn btn-danger btn-xs btn_delete" type="button">X</button>
                    </td>
                  </tr>
<?php
endforeach;
?>             
                </tbody>
              </table>
            </div>
          </div>
          <!-- end: ALERTS PANEL -->
        </div>
      </div>
      <div class="row">
        <div class="col-md-12">
          <a href="/work/install"><span class="btn btn-default" type="button">리스트</span></a>
<?php
if($work->status == 1):
?>
          <button class="btn btn-success" type="button" data-toggle="modal" data-target="#myModal">요청확정</button>
<?php
endif;

if($work->status == 2):
?>
          <button id="btn_register" class="btn btn-warning btn_add" type="button" >장비 등록</button>
          <button id="btn_delivery" class="btn btn-success btn_delivery" type="button"  disabled>출고</button>
<?php
endif;

if($work->status == 3):
?>
          <button id="btn_scan" class="btn btn-danger" type="button" data-toggle="modal" data-target="#myModal">장비 스캔</button>
          <button id="btn_complete" class="btn btn-success" type="button" disabled>설치 완료</button>
<?php
endif;
?>
        </div>
      </div>
    </div><!-- end of div.container -->
    
    <!-- dialog form -->
    <div id="dialog-form" title="장비 등록">
      <div class="row col-xs-10">
      <form id="my_form" role="form" class="form">
        <div class="form-group">
          <label class="form-label">장비등록</label>
          <input id="my_val" class="form-control" name="value" id="value">
        </div>
      </form>
      </div>
    </div>

    <!-- modal dialog -->
    <div class="modal fade" id="myModal" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title">설치 요청서 확정</h4>
          </div>
          <div class="modal-body">
            <form id="inner_form" role="form" class="form form-horizontal">
              <div class="form-group">
                <label class="form-label col-sm-4">설치 사무소</label>
                <div class="col-sm-7">
                  <input type="text" class="form-control" name="office_id">
                </div>
              </div>
              <div class="form-group">
                <label class="form-label col-sm-4">담당직원선택</label>
                <div class="col-sm-7">
                  <input type="text" class="form-control" name="worker_id">
                </div>
              </div>
              <div class="form-group">
                <label class="form-label col-sm-4">설치 예정일</label>
                <div class="col-sm-7">
                  <input type="text" class="form-control" name="date_work">
                </div>
              </div>
              <div class="form-group">
                <label class="form-label col-sm-4">작업 메모</label>
                <div class="col-sm-7">
                  <textarea name="memo" class="form-control"></textarea>
                </div>
              </div>
            </form>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <button id="btn_request_ok" type="button" class="btn btn-success">스캔 계속</button>
          </div>
        </div><!-- /.modal-content -->
      </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

    <!-- jquery form validation -->
    <script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.min.js"></script>
    <script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>

    <script type="text/javascript">
    // 장비 목록
    var items = [];
    // 등록된 장비 갯수
    var item_count = <?=$item_count?>;

    function checkDeliveryStatus() {
      $("#btn_delivery").attr('disabled', (item_count > 0) ? false : true);
    }

    $(document).ready(function(){
      checkDeliveryStatus();
      
      // open jquery ui modal dialog
      $("#dialog-form").dialog({
        autoOpen:false,
        modal: true,
        width: '350px',
        buttons: {
          "저장": function() {
            $.ajax({
              url: "/work/enter/ajax/temp_add",
              type: "POST",
              data: {
                id : <?=$work->id?>,
                val: $("#my_val").val(),
                "csrf_test_name": $.cookie("csrf_cookie_name")
              },
              dataType: "html",
            })
              .done(function(html) {
                $("#part_table tbody").append(html);
                item_count++;
                checkDeliveryStatus();
              })
              .fail(function(xhr, textStatus){
                alert("Request failed: " + textStatus);
              });
            $(this).dialog("close");
          },
          "닫기": function() {
            $(this).dialog("close");
          }
        }
      });

      // 요청 확정
      $("#btn_request_ok").click(function(){
        var is_ok = confirm("확정 하시겠습니까?\n그리고 설치예정일 확인해야함.");

        if(is_ok == true){
          $.ajax({
            url: "/work/enter/ajax/request_ok",
            type: "POST",
            data: {
              id : <?=$work->id?>,
              "csrf_test_name": $.cookie("csrf_cookie_name")
            },
            dataType: "html",
          })
            .done(function(html) {
              alert(html);
              location.reload();
            })
            .fail(function(xhr, textStatus){
              alert("Request failed: " + textStatus);
            });
        }// end of if
      });

      $(".btn_add").click(function(){
          $("#dialog-form").dialog('open');
      });

      // 리스트 장비 삭제
      $(document).on("click", ".btn_delete", function(e){
        // console.log($(this).parent().parent().data('temp_id'));

        // 삭제 전 확인
        if(!confirm('목록에서 삭제 할까요?')){
          return false;
        }

        var $tr = $(this).parent().parent();

        $.ajax({
          url: "/work/enter/ajax/temp_delete",
          type: "POST",
          data: {
            id : <?=$work->id?>,
            temp_id: $tr.data('temp_id'),
            "csrf_test_name": $.cookie("csrf_cookie_name")
          },
          dataType: "html",
        })
          .done(function(html) {
            $tr.remove();     // 현재 행 삭제
            item_count--;
            checkDeliveryStatus();
          })
          .fail(function(xhr, textStatus){
            alert("Request failed: " + textStatus);
          });
      });
      
      // 출고
      $("#btn_delivery").click(function(){
        if(item_count <= 0) {
          alert('최소 1개 이상의 장비 정보를 입력해야 합니다');
          return false;
        }

        var msg = '정말로 \n출고진행?';
        if(!confirm(msg)) {
          return false;
        }

        $.ajax({
            url: "/work/enter/ajax/delivery",
            type: "POST",
            data: {
              id : <?=$work->id?>,
              // items : items.toString(),
              "csrf_test_name": $.cookie("csrf_cookie_name")
            },
            dataType: "html",
          })
            .done(function(html) {
              alert(html);
              location.reload();
            })
            .fail(function(xhr, textStatus){
              alert("Request failed: " + textStatus);
            });
      });

    });
    </script>
<?php
$this->load->view('layout/footer');
?>