<?php
// 모달 content - 수리/폐기 장비 등록
?>
<!-- modal dialog -->
<div class="modal fade" id="modal_waitpart_register" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <!-- start form -->
      <form role="form" class="form form-horizontal">
        <input type="hidden" name="serial_part_id" id="serial_part_id" value="">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          <h4 class="modal-title">장비 등록</h4>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <label class="form-label col-sm-4">시리얼넘버</label>
            <div class="col-sm-5">
              <input type="text" class="form-control" id="serial_number">
            </div>
            <div class="col-sm-3" style="padding-left:0;">
              <button id="btn_part_search" type="button" class="btn btn-warning">조회</button>
            </div>
          </div>

          <div class="form-group">
            <label class="form-label col-sm-4">장비 종류</label>
            <div class="col-sm-6">
  <?php
  echo $select_category;
  ?>
            </div>
          </div>
          <div class="form-group">
            <label class="form-label col-sm-4">장비 모델</label>
            <div class="col-sm-6">
              <select id="select_part" name="select_part" class="form-control"></select>
            </div>
          </div>

          <div class="form-group">
            <label class="form-label col-sm-4">수량</label>
            <div class="col-sm-4">
              <input type="text" id="part_qty" name="part_qty" class="form-control">
            </div>
          </div>

          <div class="form-group">
            <label class="form-label col-sm-4">장비 상태</label>
            <div class="col-sm-5">
              <label class="radio-inline">
                <input type="radio" name="is_new" value="Y" disabled> 신품
              </label>
              <label class="radio-inline">
                <input type="radio" name="is_new" value="N" checked disabled> 중고
              </label>
            </div>
          </div>

        </div>
        <div class="modal-footer">
          <button id="btn_part_add" type="button" class="btn btn-primary">확인</button>
          <button type="button" class="btn btn-default" data-dismiss="modal">닫기</button>
        </div>
      </form>
    </div>
  </div>
</div><!-- /.modal -->

<script type="text/javascript">
var sel_item_id = 0;

$(document).ready(function(){
  // 모달 보이기 전 설정
  $("#modal_waitpart_register").on('shown.bs.modal', function() {
    $("#serial_number").focus();    // 기본 포커스
    reset_register_form();               // 폼 초기화
  });

  // 장비 종류 선택 시 장비 목록 가져오기
  $(document).on('change', "#select_category", function(){
    var cat = $(":selected", this).val();
    if( cat == '0'){
      $("#select_part").html('<option value="0">not loaded...</option>');
      return false;
    } 
      
    var target_url = _base_url + "ajax/get_models_for_scan/" + cat;
    $.ajax({
      url: target_url,
      type: "POST",
      async: false,
      data: {
        "category_id": cat,
        "extra": "test",
        "csrf_test_name": $.cookie("csrf_cookie_name")
      },
      dataType: "html",
    })
      .done(function(html) {
        // gs2_console(html);
        if(html == 'none'){
          alert('해당 카테고리에 등록된 장비가 없습니다.\n관리자에게 장비 등록을 요청하세요');
          $("#select_category").val(0).change();
        } else {
          $("#select_part").html(html);
        }
      })
      .fail(function(xhr, textStatus){
        alert("Request failed: " + textStatus);
      });
  });

  // 장비 모델 선택
  $(document).on("change", "#select_part", function(e){
    var part_id = $(":selected", this).val();
    part_id = parseInt(part_id, 10);
    if( part_id === 0) {
      return false;
    } 

    // 장비 정보 요청
    $.ajax({
      url: "<?=base_url()?>util/part/get",
      type: "POST",
      data: {
        "part_id": part_id,
        "office_id": operation.office_id,   
        "extra": "test",
        "csrf_test_name": $.cookie("csrf_cookie_name")
      },
      dataType: "json",
    })
      .done(function(html) {
        item = {};            // empty item
        item = html;
        changeFormLayout(item.type);
        // gs2_console(html);
      })
      .fail(function(xhr, textStatus){
        alert("Request failed: " + textStatus);
      });
  });

  // 시리얼넘버 enter key 처리
  $("#serial_number").keypress(function(e){
    if(e.keyCode == 13) {
      e.preventDefault();
      $("#btn_part_search").click();
    }
  });

  //////////////
  // 시리얼넘버 검색
  //////////////
  $("#btn_part_search").on('click', function(e){
    var q = $.trim($("#serial_number").val());
    if( q == '') {
      alert('시리얼 넘버를 입력하세요');
      $("#serial_number").focus();
      return false;
    }

    $.ajaxSetup({
      async: false
    });
    
    var ajax_url = _base_url + 'ajax/search_waitpart/' + encodeURIComponent(q);
    $.getJSON(ajax_url, {
      op_id: operation.id,
      gubun: "D",
      office_id: operation.office_id,
      csrf_test_name: $.cookie("csrf_cookie_name")
    }, function(data) {
        // gs2_console(data);
        var error = data.error;

        if(!error) {
          set_serialinfo(data.info);        // 시리얼 장비 정보 셋팅
          sel_item_id = data.info.wpart_id; // gs2_deprecated_parts.id
          $("#btn_part_add").click();
        } else {
          alert(data.error_msg);
          $("#serial_number").val('').focus();
        }
    });
  });

  ///////////
  // 장비 등록
  ///////////
  $(document).on("click", "#btn_part_add", function(e){
    // 장비종류,  모델
    if(item.id == undefined) {
      alert("장비를 선택하세요");
      return false;
    }
        
    // 신품 or 중고(Y/N)
    var is_new = $(":radio[name=is_new]:checked").val();
    if(is_new === undefined) {
      alert('장비 신품 여부를 선택하세요');
      $(":radio[name=is_new]").focus();
      return false;
    }

    // 수량 확인
    var qty = parseInt($("#part_qty").val(), 10);
    if(!qty || qty < 1) {
      alert('수량을 입력하세요');
      $("#part_qty").focus();
      return false;
    }

    // 시리얼장비 id
    var spart_id = (item.type == '1') ? $("#serial_part_id").val() : '';

    $.ajax({
      url: "<?=base_url()?>work/destroy/addItem",
      type: "GET",
      data: {
        "id": operation.id,         
        "part_id": item.id,
        "serial_part_id": spart_id,
        "serial_number": $('#serial_number').val(),
        "qty": qty,   
        'is_new': is_new,
        wpart_id: sel_item_id,
        "csrf_test_name": $.cookie("csrf_cookie_name")
      },
      dataType: "json",
    })
      .done(function(response) {
        if(!response.error) {
          callback_insert_row(response.id, is_new, qty);
          reset_register_form();
        } else {
          alert(response.error_msg);
        }
      })
      .fail(function(xhr, textStatus){
        alert("Request failed: " + textStatus);
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
        url: "<?=base_url()?>work/destroy/removeItem",
        type: "GET",
        data: {
          id : operation.id,
          item_id: item_id,
          "csrf_test_name": $.cookie("csrf_cookie_name")
        },
        dataType: "json",
      })
        .done(function(json) {
          callback_remove_row(that);
          alert(json.msg);
        })
        .fail(function(xhr, textStatus){
          alert("Request failed: " + textStatus);
        });
  });
});//end of ready

// 장비 type에 따라 폼 입력 양식 변경
function changeFormLayout(part_type) {
  var form = $("#modal_waitpart_register form");

  // 시리얼, 직전위치 검색 시
  if( part_type == 1) {
    // $("#select_category", form).val('0').prop('disabled', true)
    // $("#select_part", form).val('').prop('disabled', true);
    $("#part_qty", form).val('1').prop('readonly', true);
  }
  // 수량 장비 일 경우
  else {
    // $("#select_category", form).val('0').prop('disabled', false)
    // $("#select_part", form).prop('disabled', false);
    $("#part_qty", form).val('1').prop('readonly', false);
  }
}

// 폼 초기화
function reset_register_form() {
  var form = $("#modal_waitpart_register form");

  $("#serial_number", form).val('');
  $("#select_category", form).val('0').change();
  $('#part_qty', form).val(1);
  $("#serial_number", form).val('');
  $("#serial_part_id", form).val('');
}

// 시리얼장비 정보 채우기
function set_serialinfo(spart) {
  var form = $("#modal_waitpart_register form");

  $("#select_category").val(spart.category_id).change();
  $("#select_part", form).val(spart.part_id).change();
  if(spart.is_new == 'Y') {
    $(':radio[name="is_new"][value="Y"]').prop('checked', true);
  } else {
    $(':radio[name="is_new"][value="N"]').prop('checked', true);
  }
  $('#part_qty', form).val(1);

  //hidden value
  $("#serial_part_id").val(spart.spart_id);
}

function enableAddItem() {
  $("#btn_part_add").prop("disabled", false);
}

function disableAddItem() {
  $("#btn_part_add").prop("disabled", true);
}

</script>

