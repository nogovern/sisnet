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
            <button type="button" class="btn btn-warning">검색</button>
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

        <div class="form-group">
          <label class="form-label col-sm-4">수량</label>
          <div class="col-sm-4">
            <input type="text" id="part_qty" name="part_qty" class="form-control">
          </div>
        </div>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary">초기화</button>
        <button type="button" class="btn btn-primary">확인 저장</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">닫기</button>
      </div>
      </form>
    </div>
  </div>
</div><!-- /.modal -->

<script type="text/javascript">
$(document).ready(function(){
  $("#query").keypress(function(e){
    if(e.keyCode == 13) {
      e.preventDefault();
      $("#btn_search_serial").click();
    }
  });

  // 장비 종류 선택 시 장비 목록 가져오기
  $(document).on('change', "#select_category", function(){
    reset_part_register_form();
    
    var cat = $(":selected", this).val();
    if( cat == '0'){
      $("#select_part").html('<option>not loaded...</option>');
      return false;
    } 
      
    var target_url = _base_url + "ajax/get_models_for_scan/" + cat;
    $.ajax({
      url: target_url,
      type: "POST",
      data: {
        "category_id": cat,
        "extra": "test",
        "csrf_test_name": $.cookie("csrf_cookie_name")
      },
      dataType: "html",
    })
      .done(function(html) {
        gs2_console(html);
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

        gs2_console(item);

        // 장비 구분 하여 폼 컨트롤 형식 변경
        changeFormLayout(item.type);
      })
      .fail(function(xhr, textStatus){
        alert("Request failed: " + textStatus);
      });
  });

  // 장비 등록
  $(document).on("click", "#btn_part_add", function(e){
    e.stopPropagation();
    
    var qty = parseInt($("#part_qty").val(), 10);
    if(!qty || qty < 1) {
      alert('수량을 입력하세요');
      $("#part_qty").focus();
      return false;
    }

    if(item.type == '1') {
      if( $("#serial_number").val() == '') {
        if(!confirm('========\n확인하세요\n========\n시리얼넘버 가 없이 장비 등록하시겠습니까?')) {
          $("#serial_number").focus();
          return false;
        }
      }
    }

    // 철수 시 장비는 모두 중고 상태임
    var is_new = 'N';

  });


});//end of ready


// 장비 type에 따라 폼 입력 양식 변경
function changeFormLayout(part_type) {
  if( part_type == '1') {
    $("#serial_number").val('').attr('readonly', false);
    $("#part_qty").val('1').attr('readonly', true);
  } else {
    $("#serial_number").attr('readonly', true);
    $("#part_qty").val('1').attr('readonly', false);
  }
}

// 폼 초기화
function reset_part_register_form() {
  var form = $("#modal_part_scan form");

  // $("#select_part").html('');
  $('#part_qty').val(0).prop('readonly', true);
  $("#serial_number").val('').prop('readonly', true);

}

function enableAddItem() {
  $("#btn_part_add").prop("disabled", false);
}

// 등록된 시리얼넘버 검색
function exist_serial_number(sn, haystack) {
  if(haystack !== undefined) {
    return true;
  }

  return false;
}
</script>
