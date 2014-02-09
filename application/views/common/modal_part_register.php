<?php
// 모달 content - 설치 장비 등록
?>
<!-- modal dialog -->
<div class="modal fade" id="modal_part_register" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <!-- start form -->
      <form role="form" class="form form-horizontal">
      <input type="hidden" name="serail_number" id="serial_number" value="">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title">장비 등록</h4>
      </div>
      <div class="modal-body">
        <div class="form-group">
          <label class="form-label col-sm-4">장비 검색</label>
          <div class="col-sm-5">
            <label class="radio-inline">
              <input type="radio" name="search_method" value="0" checked> 없음
            </label> 
            <label class="radio-inline">
              <input type="radio" name="search_method" value="1"> 시리얼
            </label>
            <label class="radio-inline">
              <input type="radio" name="search_method" value="2"> 직전위치
            </label>
          </div>
        </div>

        <div class="form-group" id="search_block" style="display:none;">
          <label class="form-label col-sm-4">&nbsp;</label>
          <div class="col-sm-5">
            <input class="form-control" type="text" name="query" id="query">
          </div>
          <div class="col-sm-3">
              <button id="btn_search_serial" class="btn btn-info btn-sm" type="button">검색</button>
          </div>
        </div>

        <!--
        <div class="form-group" class="search_block">
          <label class="form-label col-sm-4">직전 위치 검색</label>
          <div class="col-sm-5">
            <input class="form-control" type="text" name="serach_prev_location" id="serach_prev_location">
          </div>
          <div class="col-sm-3">
              <button class="btn btn-info btn-sm btn_search" type="button">검색</button>
          </div>
        </div>
        -->

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
              <input type="radio" name="is_new" value="Y" required> 신품
            </label> /
            <label class="radio-inline">
              <input type="radio" name="is_new" value="N"> 중고
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
        <button type="button" class="btn btn-primary" id="btn_part_add" disabled>장비 등록</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">닫기</button>
      </div>
      </form>
    </div>
  </div>
</div><!-- /.modal -->

<script type="text/javascript">
$(document).ready(function(){

  // 장비 검색 방법 선택 
  $(document).on('change', ":radio[name=search_method]", function(e){
    reset_part_register_form();
    var sm = $(":radio[name=search_method]:checked").val();
    
    if(sm == '0') {
      $("#search_block").hide();
      changeFormLayout(2);              // 수량 장비 양식
    } else {
      $("#search_block").slideDown();
      changeFormLayout(1);              // 시리얼 장비 양식
    }
  });

  // 장비 종류 선택 시 장비 목록 가져오기
  $(document).on('change', "#select_category", function(){
    var cat = $(":selected", this).val();
    if(cat == 0){
      $("#select_part").html('');
      return false;
    } else {
      var target_url = "<?=base_url()?>ajax/get_model_list_for_delivery" + '/' + cat;
    }

    // ajax request
    $.ajax({
      url: target_url,
      type: "POST",
      async: false,
      data: {
        "category_id": cat,
        "office_id": operation.office_id,
        "extra": "test",
        "csrf_test_name": $.cookie("csrf_cookie_name")
      },
      dataType: "html",
    })
      .done(function(html) {
        // gs2_console(html);
        if(html == 1000){
          alert('해당 카테고리에 등록된 장비가 없습니다');
        } else {
          $("#select_part").html(html);
          disableAddItem();

          // 비활성화된 옵션 글자색 변경
          $("#select_part option:disabled").css('background-color', '#CEC');
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
        enableAddItem();

        gs2_console(html);
      })
      .fail(function(xhr, textStatus){
        alert("Request failed: " + textStatus);
      });
  });

  // enter key 처리
  $("#query").keypress(function(e){
    if(e.keyCode == 13) {
      e.preventDefault();
      $("#btn_search_serial").click();
    }
  });

  // 시리얼 장비 검색 or 직전위치 검색
  $("#btn_search_serial").click(function(e){
    var q = $.trim($("#query").val());
    if( q == '') {
      alert('검색할 장비 텍스트를 입력하세요');
      $("#query").focus();
      return false;
    }

    // 검색 방법 (1:시리얼, 2:직전위치)
    var sm = $(":radio[name=search_method]:checked").val();
    var target_url = '';

    if(sm == '1') {
      target_url = "<?=base_url()?>ajax/get_part_by_serial/" + encodeURIComponent(q);
    } else if(sm == '2') {
      target_url = "<?=base_url()?>util/loadModalSearchPrevious/" + encodeURIComponent(q);

      // 직전위치 검색 결과
      $("#modal_search_previous .modal-body").load(target_url, {office_id : operation.office_id},function(response){
        $("#modal_search_previous").modal('show');
        $("#modal_part_register").modal('hide');

        gs2_console(response);
      });
      return false;

    } else {
      alert('잘못된 검색 방법입니다.');
      return false;
    }

    $.ajax({
      url: target_url,
      type: "POST",
      data: {
        "office_id": operation.office_id,
        "query": encodeURIComponent(q),
        "extra": "test",
        "csrf_test_name": $.cookie("csrf_cookie_name")
      },
      dataType: "json",
    })
      .done(function(response) {
        gs2_console(response);

        if(!response.error) {
          set_serialinfo(response.info);    // 시리얼 장비 정보 셋팅
          enableAddItem();                  // 등록 버튼 활성화
        } else {
          alert(response.error_msg);
          $("#query").val('').focus();
        }
      })
      .fail(function(xhr, textStatus){
        alert("Request failed: " + textStatus);
      });
  });

  // 장비 등록
  $(document).on("click", "#btn_part_add", function(e){
    e.stopPropagation();
    
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

    // 요청 수량과 재고수량 비교
    var max_qty = (is_new == 'Y') ? item.qty_new : item.qty_used;
    if(qty > max_qty) {
      alert('재고 수량을 넘을 수 없습니다');
      $("#part_qty").val('').focus();
      return false;
    }

    $.ajax({
      url: "<?=base_url()?>work/ajax/add_item",
      type: "POST",
      data: {
        "id": operation.id,         
        "part_id": item.id,
        "serial_part_id": '',
        "serial_number": $('#serial_number').val(),
        "qty": qty,   
        'is_new': is_new,
        "extra": "add_item_for_install_op",
        "csrf_test_name": $.cookie("csrf_cookie_name")
      },
      dataType: "json",
    })
      .done(function(response) {
        if(response.result === 'success') {
          callback_insert_row(response.id, item.type, item.name, $('#serial_number').val(), '', qty, is_new);
          reset_part_register_form();
        } else {
          alert('에러');
        }
      })
      .fail(function(xhr, textStatus){
        alert("Request failed: " + textStatus);
      });

    // 버튼 비활성
    // 선택 초기화해야 함!
    $("#btn_part_add").prop("disabled", true);
  });

  // 장비 삭제 이벤트 등록
  $(document).on('click', '.remove_item', function(e){
    var item_id = $(this).closest('tr').data('item_id');
    var that = this;
    if(!confirm(item_id + ' 를 목록에서 삭제하시겠습니까?')) {
      return false;
    }

    $.ajax({
        url: "<?=base_url()?>work/ajax/remove_item",
        type: "POST",
        data: {
          id : operation.id,
          item_id: item_id,
          "csrf_test_name": $.cookie("csrf_cookie_name")
        },
        dataType: "html",
      })
        .done(function(html) {
          callback_remove_row(that);
          alert(html);
        })
        .fail(function(xhr, textStatus){
          alert("Request failed: " + textStatus);
        });
  });

});//end of ready

// 장비 type에 따라 폼 입력 양식 변경
function changeFormLayout(part_type) {
  var form = $("#modal_part_register form");

  // 시리얼, 직전위치 검색 시
  if( part_type == 1) {
    $("#select_category", form).val('0').prop('disabled', true)
    $("#select_part", form).val('').prop('disabled', true);
    $(':radio[name="is_new"]', form).prop('disabled', true);
    $("#part_qty", form).val('1').prop('readonly', true);
  }
  // 수량 장비 일 경우
  else {
    $("#select_category", form).val('0').prop('disabled', false)
    $("#select_part", form).prop('disabled', false);
    $(':radio[name="is_new"]', form).prop('disabled', false);
    $("#part_qty", form).val('').prop('readonly', false);
  }
}

// 폼 초기화
function reset_part_register_form() {
  var form = $("#modal_part_register form");

  $("#query").val('');
  $("#select_category").val('0').change();
  $(':radio[name="is_new"]', form).prop('checked', false);
  $('#part_qty').val(0);
  //hidden value
  $("#serial_number").val('');

  disableAddItem();
}

// 시리얼장비 정보 채우기
function set_serialinfo(spart) {
  var form = $("#modal_part_register form");

  $("#select_category").val(spart.category_id).change();
  $("#select_part", form).val(spart.part_id).change();
  if(spart.is_new == 'Y') {
    $(':radio[name="is_new"][value="Y"]').prop('checked', true);
  } else {
    $(':radio[name="is_new"][value="N"]').prop('checked', true);
  }
  $('#part_qty', form).val(1);
  //hidden value
  $("#serial_number").val(spart.serial_number);
}

function enableAddItem() {
  $("#btn_part_add").prop("disabled", false);
}

function disableAddItem() {
  $("#btn_part_add").prop("disabled", true);
}

// 등록된 시리얼넘버 검색
function exist_serial_number(sn, haystack) {
  if(haystack !== undefined) {
    return true;
  }

  return false;
}

</script>