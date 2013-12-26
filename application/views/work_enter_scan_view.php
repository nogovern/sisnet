<?php
$this->load->view('layout/header', array('title' => '입고 >> 입고 요청 보기'));
$this->load->view('layout/navbar', array('current' => 'page-work-enter'));

?>

    <!-- start of div.container -->
    <div class="container">
      <div class="page-header">
        <h1><span class="fa fa-desktop"></span>&nbsp;입고 업무</h1>
      </div>
<?php
// 업무 공통 헤더
$this->load->view('_work_view_header', $work);
?>

      <div class="row">
        <div class="col-md-12">
          <!-- start: ALERTS PANEL -->
          <div class="panel panel-primary">
            <div class="panel-heading"><i class="fa fa-tags"></i>출고 대기 리스트</div>
            <div class="panel-body" style="padding:0 15px;">
              <table class="table table-hover" id="part_table">
                <thead>
                  <tr>
                    <th>#</th>
                    <th>장비명</th>
                    <th>S/N</th>
                    <th>등록 수량</th>
                    <th>확인 수량</th>
                    <th>장비 확인</th>
                  </tr>
                </thead>
                <tbody>
<?php
$i = 1;
$item_count = count($items);
$scan_count = 0;
foreach($items as $item):
  // 스캔 완료된 장비
  if($item->isScan()) {
    $scan_count++;
  }
?>                  
                  <tr data-temp_id="<?=$item->id?>">
                    <td><?=$i++?></td>
                    <td><?=$item->part->name?></td>
                    <td><?=($item->part->type == '1') ? $item->serial_number : ''?></td>
                    <td><?=$item->qty?></td>
                    <td>0</td>
                    <td style="width:150px;">
                      <i class="fa fa-check scan_status <?=($item->isScan()) ? '' : 'hide'?>" style="color:green;font-size:20px;"></i>
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
          <button id="btn_cancel" class="btn btn-default" type="button">리스트</button>
<?php
if($work->status == 3):
?>
          <button id="btn_scan" class="btn btn-danger" type="button" data-toggle="modal" data-target="#myModal">장비 스캔</button>
          <button id="btn_complete" class="btn btn-success" type="button" disabled>입고 완료</button>
<?php
endif;
?>
        </div>
      </div>
    </div><!-- end of div.container -->
    
    <!-- dialog form -->
    <div id="dialog-form" title="장비 등록" style="display:none;">
      <div class="row col-xs-10">
      <form id="my_form" role="form" class="form">
        <div class="form-group">
          <label class="form-label"><?=($work->getItem()->part->type == '1') ? '시리얼넘버' : '수 량 '?></label>
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
            <h4 class="modal-title">Modal title</h4>
          </div>
          <div class="modal-body">
            <form id="form_scan" role="form" class="form form-horizontal">
              <div class="form-group">
                <label class="form-label col-sm-3">시리얼넘버</label>
                <div class="col-sm-6">
                  <input type="text" class="form-control" name="q">
                </div>
                <div class="col-sm-3"><button id="btn_search" type="submit" class="btn btn-default">검색</button></div>
              </div>
            </form>
            <div class="well text-center" style="font-size:34px;">
              <span><?=$item_count?></span>/<span id="scan_count">0</span>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <button id="btn_reset" type="button" class="btn btn-warning">스캔 초기화</button>
            <button id="btn_scan_save" type="button" class="btn btn-primary">스캔 저장</button>
          </div>
        </div><!-- /.modal-content -->
      </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

    <!-- jquery form validation -->
    <link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css">
    <script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>

    <script type="text/javascript">
    var _item_total = <?=$work->getItem()->qty_request?>;   // 요청 장비 수량
    
    // 등록된 장비 목록 갯수
    var item_count = <?=$item_count?>;
    var scan_count = <?=$scan_count?>;
    var scanned_serials = [];

    $(document).ready(function(){
      // 스캔 카운트 및 스캔 목록 초기화
      if(scan_count == 0) {
        $("#btn_complete").attr('disabled', true);
      } else {
        $("#btn_complete").attr('disabled', false);
      }
      

      // 검색
      $("#form_scan").submit(function(e) {
        e.preventDefault();
        var $input = $(":input[name=q]");
        var v = $.trim($input.val());
        var scan_success = false;

        if($.inArray(v, scanned_serials) >= 0) {
          alert('등록된 시리얼넘버 입니다.\n확인 후 다시 입력해 주세요');
          $input.val('').focus();
          return false;
        }
        
        $("#part_table tbody tr td:nth-child(3)").each(function(n){
          var sn = $(this).text();
          console.log(n, sn);
          // 시리얼번호가 일치할 경우
          if( v === sn) {
            $(".scan_status", $(this).parent()).removeClass('hide');
            scanned_serials.push(v);
            scan_count++;
            scan_success = true;
            $("#scan_count").text(scan_count);
            return false;         // escape each loop
          }
        });

        if(!scan_success) {
          alert('없는 시리얼넘버 입니다.\n확인 후 다시 입력해 주세요');
        }

        if(scan_count == item_count){
          $(":input[name=q]").attr('disabled', true);
          $("#btn_search").attr('disabled', true);
        }

        if(scan_count == 0) {
          $("#btn_complete").attr('disabled', true);
        } else {
          $("#btn_complete").attr('disabled', false);
        }

        $input.val('').focus();
      });

      // 스캔 초기화
      $("#btn_reset").click(function(){
        var ok = confirm("재입력을 위해 입력된 정보를 초기화합니다.");
        if(ok === true) {
          scan_count = 0;
          scanned_serials = [];     // empty array
          $("#scan_count").text(scan_count);
          $(".scan_status").addClass('hide');
          $(":input[name=q]").attr('disabled', false);
          $("#btn_search").attr('disabled', false);
        }
      });

      // 스캔 저장
      $("#btn_scan_save").click(function(){
        var ok = confirm('스캔 결과를 저장합니다.\n 진행할까요?');

        if(ok === true){
          $.ajax({
            url: "/work/enter/ajax/scan_save",
            type: "POST",
            data: {
              id : <?=$work->id?>,
              items : scanned_serials.toString(),
              "csrf_test_name": $.cookie("csrf_cookie_name")
            },
            dataType: "html",
          })
            .done(function(html) {
              alert(html);
            })
            .fail(function(xhr, textStatus){
              alert("Request failed: " + textStatus);
            });
        }
      });
      
      // 요청 확정
      $("#btn_complete").click(function(){
        var is_ok = confirm("요청 하시겠습니까?\n그리고 입고예정일 확인해야함.");

        if(is_ok == true){
          $.ajax({
            url: "/work/enter/ajax/complete",
            type: "POST",
            data: {
              id : <?=$work->id?>,
              "csrf_test_name": $.cookie("csrf_cookie_name")
            },
            dataType: "html",
          })
            .done(function(html) {
              alert(html);
            })
            .fail(function(xhr, textStatus){
              alert("Request failed: " + textStatus);
            });
        }// end of if
      });

      

    });
    </script>
<?php
$this->load->view('layout/footer');
?>