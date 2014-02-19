<?php
// 모달 content - 장비 등록
?>
<!-- modal dialog -->
<div class="modal fade" id="modal_part_scan" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <!-- start form -->
      <form role="form" class="form form-horizontal">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title">장비 스캔</h4>
      </div>
      <div class="modal-body">
        <div class="form-group">
          <label class="form-label col-sm-4">시리얼넘버</label>
          <div class="col-sm-5">
            <input type="text" class="form-control" id="serial_number">
          </div>
          <div class="col-sm-3" style="padding-left:0;">
            <button id="btn_retrieve_sn" type="button" class="btn btn-warning">조회</button>
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
              <input type="radio" name="is_new" value="Y"> 신품
            </label>
            <label class="radio-inline">
              <input type="radio" name="is_new" value="N"> 중고
            </label>
          </div>
        </div>

      </div>
      <div class="modal-footer">
        <button id="btn_scan_save" type="button" class="btn btn-primary">확인 저장</button>
        <button id="btn_scan_reset" type="button" class="btn btn-info">초기화</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">닫기</button>
      </div>
      </form>
    </div>
  </div>
</div><!-- /.modal -->

<script type="text/javascript">
var scanned_item = 0;       // 조회한 item id 저장, 0 보다 크면 조회 결과가 있는 것임

$(document).ready(function(){
  // 모달 보이기 전 설정
  $("#modal_part_scan").on('shown.bs.modal', function() {
    $("#serial_number").focus();    // 기본 포커스
    resetModalForm();               // 폼 초기화
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
      .done(function(repsonse) {
        item = {};      // empty item
        item = repsonse;

        // gs2_console(item);

        // 장비 구분 하여 폼 컨트롤 형식 변경
        changeFormLayout(item.type);
      })
      .fail(function(xhr, textStatus){
        alert("Request failed: " + textStatus);
      });
  });

  // 시리얼넘버 텍스트창에서 enter 처리
  $("#serial_number").keypress(function(e){
    if(e.keyCode == 13) {
      e.preventDefault();
      $("#btn_retrieve_sn").click();
    }
  });

  ////////////////////
  // 시리얼넘버 조회
  ////////////////////
  $("#btn_retrieve_sn").on('click', function(){
    var form = $("#modal_part_scan form");
    var sn = $("#serial_number", form).val();
    sn = $.trim(sn);

    if(sn == '') {
      alert("시리얼넘버를 입력하셔야 합니다");
      $("#serial_number").focus();
      return false;
    }

    $.ajax({
      url: "<?=base_url()?>work/move/ajax_retrieve",
      type: "POST",
      data: {
        id: operation.id,
        serial_number: sn,
        part_id: item.id,
        qty: $("#part_qty", form).val(),
        is_new: $(":radio[name=is_new]:checked").val(),
        "csrf_test_name": $.cookie("csrf_cookie_name")
      },
      dataType: "json",
    })
      .done(function(response) {
        gs2_console(response);
        if(response.error) {
          alert(response.error_msg);
          resetModalForm();
          return false;
        } else {
          setPartInfo(response.item);
        }
      })
      .fail(function(xhr, textStatus){
        alert("Request failed: " + textStatus);
      });
  })

  ////////////
  // 등록
  ////////////
  $("#btn_scan_save").click(function(){
    var form = $("#modal_part_scan form");

    var qty = parseInt($("#part_qty").val(), 10);
    var is_new = $(':radio[name="is_new"]:checked').val();
    var part_id = $("#select_part").val();

    if(part_id == "0") {
      alert("장비 모델을 선택하세요");
      return false;
    }

    if(is_new == undefined) {
      alert('장비 상태를 선택하세요');
      return false;
    }

    if(qty < 1) {
      alert('장비 수량을 입력하세요');
      return false;
    }

    $.ajax({
      url: "<?=base_url()?>work/move/ajax_register_scan",
      type: "POST",
      data: {
        id: operation.id,
        item_id: scanned_item, 
        part_id: part_id,
        qty: qty,
        is_new: is_new,
        "csrf_test_name": $.cookie("csrf_cookie_name")
      },
      dataType: "json",
    })
      .done(function(response) {
        gs2_console(response);
        if(response.error) {
          alert(response.error_msg);
          return false;
        } else {
          // 스캔 확인 마크 표시
          var tr = $("#item_list tbody").find("tr[data-item_id='" + response.item_id + "']");
          tr.find(":last").removeClass('hide');
          numScan++;      // 스캔 수량 증가
          resetModalForm();
          display();
        }
      })
      .fail(function(xhr, textStatus){
        alert("Request failed: " + textStatus);
      });
  });

  ////////////
  // 초기화
  ////////////
  $("#btn_scan_reset").click(function(){
    if(!confirm("스캔 결과를 초기화 합니다.\n계속 하시겠습니까?")){
      return false;
    }

    $.get( _base_url + "work/move/ajax_reset_scan/" + operation.id, function( data ) {
      alert( "스캔 결과 초기화 완료!" );
      gs2_console(data);

      $(".scan_status").addClass('hide');
      numScan = 0;
      resetModalForm();
      display();
    });
  });

  display();
});//!-- end of ready

// 장비 type에 따라 폼 입력 양식 변경
function changeFormLayout(part_type) {
  if( part_type == '1') {
    //$("#serial_number").val('').attr('readonly', false);
    $("#part_qty").val('1').attr('readonly', true);
  } else {
    //$("#serial_number").attr('readonly', true);
    $("#part_qty").val('1').attr('readonly', false);
  }
}

// 폼 초기화
function resetModalForm() {
  var form = $("#modal_part_scan form");

  $("#serial_number", form).val('').prop('readonly', false);
  $("#select_category", form).val(0).change();
  $('#part_qty', form).val(0).prop('readonly', false);
  $(':radio[name="is_new"]', form).prop('checked', false).prop('readonly', false);

  scanned_item = 0;
}

// 조회한 시리얼장비 정보 채우기
function setPartInfo(info) {
  var form = $("#modal_part_scan form");
  
  $("#select_category", form).val(info.cat_id).change();
  $("#select_part", form).val(info.part_id);
  $('#part_qty', form).val(1).prop('readonly', true);

  if(info.is_new) {
    $(':radio[name="is_new"][value="Y"]').prop('checked', true).prop('readonly', true);
  } else {
    $(':radio[name="is_new"][value="N"]').prop('checked', true).prop('readonly', true);
  }

  scanned_item = info.id;
}

// 스캔 처리 에 따른 화면 요소 변경
function display() {
  if(numScan == 0) {
    $("#btn_scan_reset").prop('disabled', true);
  } else {
    $("#btn_scan_reset").prop('disabled', false);
  }

  if(numItem > 0 && numScan == numItem) {
    $("#btn_move_op_complete").prop('disabled', false);
  } else {
    $("#btn_move_op_complete").prop('disabled', true);
  }
}
</script>

