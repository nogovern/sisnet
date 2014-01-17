<?php
$this->view('layout/header');
$this->view('layout/navbar');
?>

<!-- start of div.container -->
<div class="container">
  <div class="page-header">
    <h2><span class="fa fa-desktop"></span>&nbsp;입고 업무</h2>
  </div>
  <div class="row">
    <div class="col-sm-4">
      <table class="table table-condensed table-hover">
        <thead>
          <tr class="active">
            <th colspan="3">주문 정보</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>업무번호</td>
            <td><?=$work->operation_number?></td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td>작업형태</td>
            <td><?=gs2_get_work_name($work->type)?></td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td>요청자</td>
            <td><?=$work->user->name?></td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td>담당사무소</td>
            <td><?=$work->office->name?></td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td>담당자</td>
            <td><?=$work->getWorker();?></td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td>등록일</td>
            <td><?=$work->getDateRegister(TRUE)?></td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td>입고요청일</td>
            <td><?=$work->getDateRequest();?></td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td>완료일시</td>
            <td><?=$work->getDateFinish(TRUE);?></td>
            <td>&nbsp;</td>
          </tr>
          <tr  class="danger">
            <td>진행상태</td>
            <td><?=constant("GS2_OP_ENTER_STATUS_" . $work->status)?></td>
            <td>&nbsp;</td>
          </tr>

        </tbody>
      </table>
    </div>
    <div class="col-sm-4">
      <table class="table table-condensed table-hover">
        <thead>
          <tr class="active">
            <th colspan="3">납품처 정보</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>납품처</td>
            <td><?=gs2_decode_location($work->getWorkLocation())->name;?></td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td>담당자</td>
            <td><?=$work->getItem()->part->company->user->name?></td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td>담당자 연락처</td>
            <td><?=$work->getItem()->part->company->user->phone?></td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td>입고위치</td>
            <td><?=$work->office->address?></td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td>요청 메모</td>
            <td><?=$work->memo;?></td>
            <td>&nbsp;</td>
          </tr>
        </tbody>
      </table>
      <table class="table table-condensed table-hover">
        <thead>
          <tr class="active">
            <th colspan="3">장비 정보</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>장비 종류 </td>
            <td><?=$work->getItem()->part->category->name?></td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td>장비 모델명</td>
            <td><?=$work->getItem()->part_name?></td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td>장비 구분</td>
            <td><?=constant('GS2_PART_TYPE_' . $work->getItem()->part_type)?></td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td>요청 수량</td>
            <td><?=$work->getTotalRequestQty()?> 개</td>
            <td>&nbsp;</td>
          </tr>
        </tbody>
      </table>
    </div>

    <div class="col-sm-4">
    </div>
  </div><!-- end of row -->

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
                <th>S/N</th>
                <th>등록수량</th>
                <th>삭제</th>
              </tr>
            </thead>
            <tbody>
<?php
$i = 1;
$item_count = count($temp_items);
foreach($temp_items as $temp_item):
?>                  
              <tr data-temp_id="<?=$temp_item->id?>">
                <td><?=$i++?></td>
                <td><?=$temp_item->part->name?></td>
                <td><?=($temp_item->part->type == '1') ? $temp_item->serial_number : ''?></td>
                <td><?=$temp_item->qty?></td>
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
      <a href="/work/enter"><span class="btn btn-default" type="button">리스트</span></a>
<?php
if($work->status == 1):
?>
      <button id="btn_request_ok" class="btn btn-success" type="button">요청확정</button>
<?php
endif;

if($work->status == 2):
?>
      <button id="btn_register" class="btn btn-warning btn_add" type="button">개별등록</button>
      <button id="btn_modal_1" class="btn btn-primary" type="button" data-target="#modal_enter_add_item">모달 개별등록</button>
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

<!-- dialog form -->
<div id="dialog-form" title="장비 등록">
  <div class="row col-xs-10">
  <form id="my_form" role="form" class="form">
    <div class="form-group">
      <label class="form-label"><?=($work->getItem()->part->type == '1') ? '시리얼넘버' : '수 량 '?></label>
      <input id="my_val" class="form-control" name="value" id="value">
    </div>
  </form>
  </div>
</div>

<!-- 스캔 modal dialog -->
<div class="modal fade" id="modal_scan" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
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
            <div class="col-sm-7">
              <input type="text" class="form-control" name="value">
            </div>
          </div>
        </form>
        <div class="well text-center" style="font-size:34px;">
          <span>1</span>/<span >0</span>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button id="btn_next" type="button" class="btn btn-success">스캔 계속</button>
        <button id="btn_scan_save" type="button" class="btn btn-primary">Save changes</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

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
$(document).ready(function(){
  //  개별 등록 modal open
  $("#btn_modal_1").click(function(e) {
    $("#input_text").val('');
    $("#modal_enter_add_item").modal('show');
  });

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
            return (equipment.type !== '1' && parseInt($("#input_text").val(), 10) > equipment.request_qty);
          }
        },
        remote: {
          params: {
            url: "<?=base_url() . 'ajax/search_part_by_serial'?>",
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
      $("#modal_enter_add_item").modal('hide'); //modal 닫기
    }
  });

  var do_submit = function (form) {
    var val = $("#input_text").val();
    var sn = (equipment.type == '1') ?  val : '';
    var qty = (equipment.type == '1') ? 1 : parseInt(val, 10);

    $.ajax({
      url: "/work/ajax/add_item",
      type: "POST",
      data: {
        "id": operation.id,         
        "part_id": equipment.id,
        "serial_number": val,
        "qty": qty,   
        'is_new': 'Y',
        "extra": "add_item_for_enter_op",
        "csrf_test_name": $.cookie("csrf_cookie_name")
      },
      dataType: "html",
    })
      .done(function(response) {
        if(response.result === 'success') {
          callback_insert_row(response.id, item.type, item.name, sn, qty, is_new);
        } else {
          if(window.console) 
            console.log(response);
        }
      })
      .fail(function(xhr, textStatus){
        alert("Request failed: " + textStatus);
      });
  }
});

</script>

<!-- jquery form validation -->
<script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.min.js"></script>
<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>

<script type="text/javascript">
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
  request_qty: <?=$work->getItem()->qty_request?>
};

// 장비 목록
var items = [];     // array of item object

// 등록된 장비 목록 갯수
var item_count = <?=$item_count?>;

// 장비 목록에 있는 serial_number 배열 (unique 임을 이미 확보)
var arr_serial = [];

function checkDeliveryStatus() {
  $("#btn_delivery").attr('disabled', (item_count > 0) ? false : true);
}

$(document).ready(function(){
  checkDeliveryStatus();
  
  // open jquery-ui modal dialog
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
    var is_ok = confirm("요청 하시겠습니까?\n그리고 입고예정일 확인해야함.");

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

  //  장비리스트에 행 추가
  function callback_insert_row(id, type, name, sn, prev, qty) {
    var type_text = '';
    if( type == '1') type_text = '시리얼';
    if( type == '2') type_text = '수량';
    if( type == '3') type_text = '소모품';

    var tr = $("<tr/>").attr('data-item_id', id);
    tr.append($("<td/>").text(id));
    tr.append($("<td/>").text(type_text));
    tr.append($("<td/>").text(name));
    tr.append($("<td/>").text(sn));
    tr.append($("<td/>").text(prev));
    tr.append($("<td/>").text(qty));
    tr.append($("<td/>").html('<button class="btn btn-danger btn-xs remove_item" type="button">X</button>'));
    $("#part_table tbody").append(tr);

    $("button[data-target=#modal_store_complete]").attr('disabled', false);
  }

  // 행 삭제
  function callback_remove_row(what) {
    $(what).closest('tr').fadeOut('slow').remove();

    // 등록 장비 없을 시 작업완료 비활성
    var len = $("#part_table tbody tr").length;
    if(len == 0) {
      $("button[data-target=#modal_op_complete]").attr('disabled', true);
    }
  }
});//end of ready
</script>

<?php
$this->view('layout/footer');
?>