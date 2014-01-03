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
                    <th>종류</th>
                    <th>장비명</th>
                    <th>상태</th>
                    <th>S/N</th>
                    <th>직전위치</th>
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
                  <tr data-item_id="<?=$item->id?>">
                    <td><?=$item->id?></td>
                    <td><?=$item->part->type?></td>
                    <td><?=$item->part->name?></td>
                    <td><?=($item->is_new == 'Y')? '신품' : '중고'?></td>
                    <td><?=($item->part->type == '1') ? '' : ''?></td>
                    <td><?=''?></td>
                    <td><?=$item->qty_request?></td>
                    <td style="width:150px;">
                      <button class="btn btn-danger btn-xs remove_item" type="button">X</button>
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
          <button id="btn_delivery" class="btn btn-success btn_delivery" type="button" disabled>출고</button>
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
              <input type="hidden" name="mode" value="install_request_ok">
              <div class="form-group">
                <label class="form-label col-sm-4">설치 사무소</label>
                <div class="col-sm-5">
<?php
echo $select_office;
?>
                </div>
                <div class="col-sm-3">
                  <button class="btn btn-info" type="button">사무소 변경</button>
                </div>
              </div>
              <div class="form-group">
                <label class="form-label col-sm-4">담당직원선택</label>
                <div class="col-sm-7">
<?php
echo $select_user;
?>      
                </div>
              </div>
              <div class="form-group">
                <label class="form-label col-sm-4">설치 예정일</label>
                <div class="input-group col-sm-6">
                  <input type="text" id="date_work" name="date_work" class="form-control date-picker" readonly>
                  <span class="input-group-addon btn_date"><i class="fa fa-calendar"></i></span>
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
            <button id="btn_request_ok" type="button" class="btn btn-primary">요청 확정</button>
            <button type="button" class="btn btn-default" data-dismiss="modal">닫기</button>
          </div>
        </div><!-- /.modal-content -->
      </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

    <!-- jquery form validation -->
    <script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.min.js"></script>
    <script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>

    <script type="text/javascript">
    // 작업 정보 객체
    var operation = {
      'id' : <?=$work->id?>,
      'office_id' : <?=$work->office->id?>
    };

    // 장비 목록
    var items = [];     // array of item object
    var item = {};

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
        $(".date-picker", $(this).parent()).datepicker("show");
      });

      //--------------------------------------

      // 요청 확정
      $("#btn_request_ok").click(function(){
        var $form = $("#myModal form");
        var $worker_id = $("#worker_id");
        var $date_work = $("#date_work");

        if($worker_id.val() < 1) {
          alert('필수 항목 입니다');
          $worker_id.focus();
          return false;
        }

        if($date_work.val() == '') {
          alert('필수 항목 입니다');
          $date_work.focus();
          return false;
        }

        var is_ok = confirm("확정 하시겠습니까?\n그리고 설치예정일 확인해야함.");

        if(is_ok == true){
          $.ajax({
            url: "/work/install/ajax/request_ok",
            type: "POST",
            data: {
              id : <?=$work->id?>,
              office_id: $("#office_id").val(),
              worker_id: $worker_id.val(),
              date_work: $date_work.val(),
              memo: $("#memo").val(),
              "csrf_test_name": $.cookie("csrf_cookie_name")
            },
            dataType: "html",
          })
            .done(function(html) {
              alert(html);
              // location.reload();
            })
            .fail(function(xhr, textStatus){
              alert("Request failed: " + textStatus);
            });
        }// end of if
      });

      // 장비 등록 모달 open
      $("#btn_register").click(function(){
          $("#myModal .modal-content").html('').load('/work/install/loadModalContent', function(result){
            $("#myModal").modal({show:true});
          });
      });

      // 장비 삭제 이벤트 등록
      $(document).on('click', '.remove_item', function(e){
        var item_id = $(this).closest('tr').data('item_id');
        var that = this;
        if(!confirm(item_id + ' 를 목록에서 삭제하시겠습니까?')) {
          return false;
        }

        $.ajax({
            url: "/work/install/ajax/remove_item",
            type: "POST",
            data: {
              id : <?=$work->id?>,
              item_id: item_id,
              "csrf_test_name": $.cookie("csrf_cookie_name")
            },
            dataType: "html",
          })
            .done(function(html) {
              callback_remove_row(that);
            })
            .fail(function(xhr, textStatus){
              alert("Request failed: " + textStatus);
            });
      });
    });
    
    //  장비리스트에 행 추가
    function callback_insert_row(id, type, name, sn, prev, qty, is_new) {
      var type_text = '';
      if( type == '1') type_text = '시리얼';
      if( type == '2') type_text = '수량';
      if( type == '3') type_text = '소모품';

      var tr = $("<tr/>").attr('data-item_id', id);
      tr.append($("<td/>").text(id));
      tr.append($("<td/>").text(type_text));
      tr.append($("<td/>").text(name));
      tr.append($("<td/>").text((is_new == 'Y') ? '신품' : '중고'));
      tr.append($("<td/>").text(sn));
      tr.append($("<td/>").text(prev));
      tr.append($("<td/>").text(qty));
      tr.append($("<td/>").html('<button class="btn btn-danger btn-xs remove_item" type="button">X</button>'));
      $("#part_table tbody").append(tr);
    }

    // 행 삭제
    function callback_remove_row(what) {
      $(what).closest('tr').fadeOut('slow');
    }

    </script>
<?php
$this->load->view('layout/footer');
?>