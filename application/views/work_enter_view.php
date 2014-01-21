<?php
$this->view('layout/header');
$this->view('layout/navbar');
?>

<!-- start of div.container -->
<div class="container">
  <div class="page-header">
    <h2><span class="fa fa-desktop"></span>&nbsp;입고 업무</h2>
  </div>

<?php
$this->view('work/work_enter_view_header');
?>

  <div class="row">
    <div class="col-md-12">
      <!-- start: ALERTS PANEL -->
      <div class="panel panel-primary">
        <div class="panel-heading"><i class="fa fa-tags"></i> 납품 장비</div>
        <div class="panel-body" style="padding:0 15px;">
          <table class="table table-hover" id="part_table">
            <thead>
              <tr>
                <th>#</th>
                <th>장비명</th>
                <th>종류</th>
                <th>S/N</th>
                <th>요청수량</th>
                <th>등록수량</th>
                <th></th>
              </tr>
            </thead>
            <tbody>
<?php
$i = 1;
foreach($work->getItems() as $item):
?>                  
              <tr data-itemid="<?=$item->id?>" data-sn="<?=$item->serial_number?>" data-isset="<?=($item->qty_complete>0) ? 'Y' : 'N'?>">
                <td><?=$i++?></td>
                <td><?=constant('GS2_PART_TYPE_' . $item->part_type);?></td>
                <td><?=$item->part_name?></td>
                <td><?=($item->part_type == '1') ? $item->serial_number : '-'?></td>
                <td><?=$item->getQtyRequest()?></td>
                <td><?=$item->qty_complete?></td>
                <td class="function" style="width:100px;">
                  <button class="btn btn-danger btn-xs btn_delete" type="button" style="display:none;">X</button>
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
      <a href="<?=site_url('work/enter')?>"><span class="btn btn-default" type="button">리스트</span></a>
<?php
if($work->status == 1):
?>
      <button id="btn_request_ok" class="btn btn-success" type="button">요청확정</button>
<?php
endif;

if($work->status == 2):
?>
      <button id="btn_part_register" class="btn btn-primary" type="button" data-target="#modal_enter_add_item">개별등록</button>
      <button id="btn_delivery" class="btn btn-success btn_delivery" type="button"  disabled>출고</button>
<?php
endif;

if($work->status == 3):
?>
      <button id="btn_scan" class="btn btn-danger" type="button" data-toggle="modal" data-target="#modal_scan">장비 스캔</button>
      <button id="btn_complete" class="btn btn-success" type="button" disabled>입고 완료</button>
<?php
endif;
?>
    </div>
  </div>
</div><!-- end of div.container -->

<!-- 입고 장비 등록 modal dialog -->
<div class="modal fade" id="modal_enter_add_item" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title">장비 등록</h4>
      </div>
      <form id="form_enter_add_item" role="form" class="form form-horizontal">
      <div class="modal-body">
        <input type="hidden" name="part_type" value="<?=$work->getItem()->part_type?>">
        <div class="form-group">
          <label class="form-label col-sm-3"><?=($work->getItem()->part->type == '1') ? '시리얼넘버' : '수 량 '?></label>
          <div class="col-sm-7">
            <input type="text" class="form-control" name="input_text" id="input_text">
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button id="btn_enter_add_item" type="submit" class="btn btn-primary">저장</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">닫기</button>
      </div>
      </form>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<script type="text/javascript">

// 출고 버튼 상태 & 장비 삭제 버튼 표시
// 장비 등록 & 삭제 시 항상 호출하므로 일단 여기에 넣음
function checkDeliveryStatus() {
  $("#btn_delivery").attr('disabled', (qty_complete > 0) ? false : true);
  
  // 삭제(초기화) 버튼
  $("#part_table tbody tr").each(function(){
    if($(this).data('isset') == 'Y') {
      $(".btn_delete", this).show();
    } else {
      $(".btn_delete", this).hide();
    }
  });
}

$(document).ready(function(){
  checkDeliveryStatus();

  // 개별 등록 버튼
  $("#btn_part_register").click(function(e) {
    if(!checkCompleteCount()) {
      return false;
    }

    $("#input_text").val('');
    $("#modal_enter_add_item").modal('show');
  });

  // 요청 수량 보다 적으면 true 리턴
  function checkCompleteCount() {
    // 수량 장비
    if(equipment.type != '1' && qty_complete > 0) {
      alert('먼저 등록 된 수량을 먼저 삭제 후 재등록해 주세요');
      return false;
    }

    if(qty_complete < qty_request) {
      return true;
    } else {
      alert('요청 수량 보다 많이 등록할 수 없습니다');
      $("#modal_enter_add_item").modal('hide');
      return false;
    }
  }

  // 등록 수량 변경
  function changeCompleteCount() {
    $("#complete_count").text(qty_complete);
  }

  // 개별 장비 폼 validate & 저장
  $("#form_enter_add_item").validate({
    debug: true,
    onkeyup: false,
    rules: {
      input_text: {
        required: true,
        number: {
          depends: function(el) {
            return (equipment.type !== '1');
          }
        },
        max: {
          depends: function(el) {
            return (equipment.type !== '1' && parseInt($("#input_text").val(), 10) > qty_request);
          }
        },
        remote: {
          params: {
            url: "<?=base_url()?>ajax/search_part_by_serial",
            type: "post",
            data: {
              "csrf_test_name": $.cookie("csrf_cookie_name")
            }
          },
          depends: function(el){
            if(equipment.type !== '1') {
              return false;
            }

            if(window.console) {
              // console.log('done');
            }
            // 시리얼장비일 경우에 등록된 배열 시리얼 검사 후 ajax 중복 검사 실행
            var sn = $.trim($("#input_text").val());
            var idx = $.inArray(sn, arr_serial);
            // return (idx < 0) ? true: false;
            // 나중에 하자
            return false;
          }
        }
      },
      messages: {
        input_text: {
          required: '필수 항목 입니다',
          max: '요청한 수량보다 클 수 없습니다.'
        }
      }
    },
    submitHandler: function(form) {
      do_submit(form);
    }
  });
  
  var do_submit = function (form) {
    var input = $("#input_text").val();
    var sn = (equipment.type == '1') ?  input : '';
    var qty = (equipment.type == '1') ? 1 : parseInt(input, 10);

    $.ajax({
      url: "<?=base_url()?>work/ajax/update_item/register",
      type: "POST",
      async: false,
      data: {
        "id": operation.id,         
        "item_id": equipment.id,
        "serial_number": input,
        "qty": qty,   
        "extra": "add_item_for_enter_op",
        "csrf_test_name": $.cookie("csrf_cookie_name")
      },
      dataType: "json",
    })
      .done(function(response) {
        // for debug
        if(window.console) {
          console.log(response);
        }
        
        // 성공 시 처리
        if(!response.error) {
          $("#modal_enter_add_item").modal('hide'); //modal 닫기

          // 리스트 갱신
          var $tr = $("#part_table tr[data-itemid="+ response.item_id +"]");
          $tr.data('isset', 'Y');
          $("td", $tr).eq(3).text(sn);              // SN
          $("td", $tr).eq(5).text(qty);    // 등록 수량
          
          // 수량,버튼 갱신        
          qty_complete += qty;
          changeCompleteCount();
          checkDeliveryStatus();
        } else {
          alert(response.error_msg);
          $("#input_text").val('').focus();
        }
      })
      .fail(function(xhr, textStatus){
        alert("Request failed: " + textStatus);
      });
  }

  // 장비 등록 삭제 or 초기화
  $(".btn_delete").click(function(){
    // var input = $("#input_text").val();
    // var qty = (equipment.type == '1') ? 1 : parseInt(input, 10);

    $.ajax({
      url: "<?=base_url()?>work/ajax/update_item/reset",
      type: "POST",
      async: false,
      data: {
        "id": operation.id,         
        "item_id": $(this).closest('tr').data('itemid'),
        "extra": "item_reset",
        "csrf_test_name": $.cookie("csrf_cookie_name")
      },
      dataType: "json",
    })
      .done(function(response) {
        // 성공 시 처리
        if(!response.error) {
          // 리스트 갱신
          var $tr = $("#part_table tr[data-itemid="+ response.item_id +"]");
          $tr.data('isset', 'N');
          $("td", $tr).eq(3).text('');              // SN
          $("td", $tr).eq(5).text(0);               // 등록 수량

          qty_complete = (equipment.type == '1') ? qty_complete - 1 : 0;
          changeCompleteCount();
          checkDeliveryStatus();

          // alert('해당 장비가 초기화 되었음');
        }

        // for debug
        if(window.console) {
          console.log(response);
        }
      })
      .fail(function(xhr, textStatus){
        alert("Request failed: " + textStatus);
      });
  });

});

</script>

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
var qty_scan  = 0;

// 장비 목록
var items = [];     // array of item object

// 장비 목록에 있는 serial_number 배열 (unique 임을 이미 확보)
var arr_serial = [];

$(document).ready(function(){

  // 요청 확정
  $("#btn_request_ok").click(function(){
    var is_ok = confirm("요청 하시겠습니까?\n그리고 입고예정일 확인해야함.");

    if(is_ok == true){
      $.ajax({
        url: "<?=base_url()?>work/enter/ajax/request_ok",
        type: "POST",
        data: {
          id : operation.id,
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
  
  // 출고
  $("#btn_delivery").click(function(){
    if(qty_complete < 1) {
      alert('납품할 장비 정보를 등록하세요');
      return false;
    }

    var msg = '정말로 \n출고진행?';
    if(!confirm(msg)) {
      return false;
    }

    $.ajax({
        url: "<?=base_url()?>work/enter/ajax/delivery",
        type: "POST",
        data: {
          id : operation.id,
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
});//end of ready
</script>

<?php
$this->view('layout/footer');
?>