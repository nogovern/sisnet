<?php
$this->view('layout/header');
$this->view('layout/navbar');
?>

<!-- start of div.container -->
<div class="container">
  <div class="page-header">
    <h1><span class="fa fa-desktop"></span>&nbsp;입고 업무</h1>
  </div>
<?php
$this->view('work/work_enter_view_header');
?>
  <div class="row">
    <div class="col-md-12">
      <!-- start: ALERTS PANEL -->
      <div class="panel panel-primary">
        <div class="panel-heading"><i class="fa fa-tags"></i>  출고 대기 리스트</div>
        <div class="panel-body" style="padding:0 15px;">
          <table class="table table-hover" id="part_table">
            <thead>
              <tr>
                <th>#</th>
                <th>종류</th>
                <th>장비명</th>
                <th>Serial Nmber</th>
                <th>등록 수량</th>
                <th>확인 수량</th>
                <th>스캔 수량</th>
                <th>장비 확인</th>
              </tr>
            </thead>
            <tbody>
<?php
$i = 1;
$item_count = count($work->getItems());
$scan_count = 0;
foreach($work->getItems() as $item):
// 스캔 완료된 장비
if($item->isScan()) {
$scan_count++;
}
?>                  
              <tr data-temp_id="<?=$item->id?>">
                <td><?=$i++?></td>
                <td><?=constant('GS2_PART_TYPE_' . $item->part_type)?></td>
                <td><?=$item->part_name?></td>
                <td><?=($item->part_type == '1') ? $item->serial_number : ''?></td>
                <td><?=$item->qty_request?></td>
                <td><?=$item->qty_complete?></td>
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
      <a href="/work/enter"><span class="btn btn-default" type="button">리스트</span></a>
<?php
if($work->status == 3):
  if($work->getItem()->part_type == '1'){
?>
      <button id="btn_open_scan_serial" class="btn btn-danger" type="button" data-toggle="modal">장비 스캔</button>
      <button id="btn_complete" class="btn btn-success" type="button" disabled>입고 완료</button>
<?php
  } else {
?>          
      <button id="btn_count" class="btn btn-danger" type="button" data-toggle="modal" data-target="#modal_scan_count">수량 등록</button>
      <button id="btn_complete" class="btn btn-success" type="button" disabled>입고 완료</button>
<?php
  }
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

<!-- 시리얼장비 modal dialog -->
<div class="modal fade" id="modal_scan_serial" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title">장비 등록</h4>
      </div>
      <div class="modal-body">
        <form id="form_serial" role="form" class="form form-horizontal">
          <div class="form-group">
            <label class="form-label col-sm-3">시리얼넘버</label>
            <div class="col-sm-6">
              <input type="text" class="form-control" name="q">
            </div>
            <div class="col-sm-3"><button id="btn_search" type="submit" class="btn btn-default">검색</button></div>
          </div>
        </form>
        <div class="well text-center" style="font-size:34px;">
          <span><?=$item_count?></span>/<span id="scan_count_text">0</span>
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

<!-- 수량장비 용 modal dialog -->
<div class="modal fade" id="modal_scan_count" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title">장비 등록</h4>
      </div>
      <div class="modal-body">
        <form id="form_count" role="form" class="form form-horizontal">
          <div class="form-group">
            <label class="form-label col-sm-3">수  량</label>
            <div class="col-sm-4">
              <input type="text" class="form-control" name="cnt" value="0">
            </div>
            <div class="col-sm-4"><button id="btn_register" type="submit" class="btn btn-default">검색</button></div>
          </div>
        </form>
        <div class="well text-center" style="font-size:34px;">
          <span><?=$work->getTotalCompleteQty()?></span>/<span id="count_count">0</span>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">닫기</button>
        <button id="btn_count_save" type="button" class="btn btn-primary">저장</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<!-- jquery form validation -->
<script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.min.js"></script>
<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>

<script type="text/javascript">
// modal 공통 설정
$(".modal").modal({backdrop: 'static', show: false});

// 작업 정보 객체
var operation = {
  id : <?=$work->id?>,
  office_id : <?=$work->office->id?>,
  type: "<?=$work->type?>",
  status: "<?=$work->status?>"
};

// 발주 한 장비 정보
var equipment = {
  id: <?=$work->getItem()->part->id?>,
  name: "<?=$work->getItem()->part_name?>",
  type: "<?=$work->getItem()->part_type?>",
};

var qty_request = <?=$work->getTotalRequestQty()?>;       // 요청 수량
var qty_complete = <?=$work->getTotalCompleteQty()?>;     // 등록 수량
var scan_count  = 0;


// 등록된 장비 목록 갯수
var scanned_serials = [];

$(document).ready(function(){
  // 스캔 카운트 및 스캔 목록 초기화
  if(scan_count == 0) {
    $("#btn_complete").attr('disabled', true);
  } else {
    $("#btn_complete").attr('disabled', false);
  }

  // 스캔 시리얼 버튼
  $("#btn_open_scan_serial").click(function() {
    $("#modal_scan_serial").modal('show');
  });

  /////////////////////
  // 시리얼 장비 스캔
  /////////////////////
  $("#form_serial").submit(function(e) {
    e.preventDefault();

    if(scan_count >= qty_complete) {
      alert('더 이상 스캔할 수 없습니다.');
      return false;
    }

    var $input = $(":input[name=q]");
    var v = $.trim($input.val());
    var scan_success = false;

    if($.inArray(v, scanned_serials) >= 0) {
      alert('등록된 시리얼넘버 입니다.\n확인 후 다시 입력해 주세요');
      $input.val('').focus();
      return false;
    }
    
    $("#part_table tbody tr td:nth-child(4)").each(function(n){
      var sn = $(this).text();
      console.log(n, sn);
      // 시리얼번호가 일치할 경우
      if( v === sn) {
        $(".scan_status", $(this).parent()).removeClass('hide');
        scanned_serials.push(v);
        scan_count++;
        scan_success = true;
        $("#scan_count").text(scan_count);
        $("#scan_count_text").text(scan_count);
        return false;         // escape each loop
      }
    });

    if(!scan_success) {
      alert('없는 시리얼넘버 입니다.\n확인 후 다시 입력해 주세요');
    }

    if(scan_count >= qty_complete){
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

  /////////////////
  /// 수량장비 스캔
  /////////////////
  $("#form_count").submit(function(e){
    e.preventDefault();

    var $el = $(":input[name=cnt]"); 
    var v = parseInt($el.val(), 10);

    if( v <= 0 || v > qty_complete) {
      alert('입력 수량을 확인하세요');
      $el.val('0').focus();
      return false;
    }
    $("#count_count").text(v).css('color', 'red');
  });

  // 수량 장비 스캔 결과 저장
  $("#btn_count_save").click(function(e){

    $.ajax({
      url: "/work/enter/ajax/scan_count_save",
      type: "POST",
      data: {
        id : operation.id,
        cnt: $(":input[name=cnt]").val(),
        "csrf_test_name": $.cookie("csrf_cookie_name")
      },
      dataType: "html",
    })
      .done(function(html) {
        alert(html);
        // 완료 버튼 활성화
        $("#btn_complete").attr('disabled', false);
      })
      .fail(function(xhr, textStatus){
        alert("Request failed: " + textStatus);
      });
    
    $("#part_table tr td:nth-child(5)").text($(":input[name=cnt]").val());
    $(".scan_status").removeClass('hide');
    $("#modal_scan_count").modal('hide');
  });

  // 시리얼 장비 스캔 결과 저장
  $("#btn_scan_save").click(function(){
    var ok = confirm('스캔 결과를 저장합니다.\n 진행할까요?');

    if(ok === true){
      $.ajax({
        url: "/work/enter/ajax/scan_serial_save",
        type: "POST",
        data: {
          id : <?=$work->id?>,
          serials : scanned_serials.toString(),
          "csrf_test_name": $.cookie("csrf_cookie_name")
        },
        dataType: "html",
      })
        .done(function(html) {
          alert(html);
          // 완료 버튼 활성화
          $("#btn_complete").attr('disabled', false);
        })
        .fail(function(xhr, textStatus){
          alert("Request failed: " + textStatus);
        });
    }
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

  // 입고 완료
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
          location.reload();
        })
        .fail(function(xhr, textStatus){
          alert("Request failed: " + textStatus);
        });
    }// end of if
  });

});
</script>


<?php
$this->view('layout/footer');
?>