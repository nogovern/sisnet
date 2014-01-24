<?php
// 모달 content - 장비 등록
?>
<!-- modal dialog -->
<div class="modal fade" id="modal_part_register" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <!-- start form -->
      <form role="form" class="form form-horizontal">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title">장비 등록</h4>
      </div>
      <div class="modal-body">

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
          <label class="form-label col-sm-4">시리얼넘버</label>
          <div class="col-sm-5">
            <input type="text" class="form-control" id="serial_number">
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
        
        <div class="form-group">
          <label class="form-label col-sm-4"></label>
          <div class="col-sm-6">
            <label>
              <input type="checkbox" name="is_lost" value="Y"> 장비 분실
              <span class="help-block">분실 된 장비...</span>
            </div>
          </label>
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
  $("#query").keypress(function(e){
    // e.preventDefault();

    if(e.keyCode == 13)
      $("#btn_search_serial").click();
  });


  // 장비 종류 선택 시 장비 목록 가져오기
  $(document).on('change', "#category_id", function(){
    var cat = $(":selected", this).val();
    // console.log(cat);
    if( cat == ''){
      $("#select_part").html('');
      return false;
    } else {
      var target_url = "<?=site_url('ajax/get_model_list_for_warehousing/')?>" + '/' + cat;
    }

    // ajax request
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
        if(html == 'none'){
          alert('error : 해당 카테고리에 등록된 장비가 없어요');
        } else {
          $("#select_part").html(html);
          $("#btn_part_add").prop("disabled", false);
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

        if(window.console) {
          console.log(item);
        }

        if( item.type == '1') {
          $("#serial_number").val('').attr('readonly', false);
          $("#part_qty").val('1').attr('readonly', true);
        } else {
          $("#serial_number").attr('readonly', true);
          $("#part_qty").val('1').attr('readonly', false);
        }
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

    // 철수 시 장비는 모두 중고 상태임
    var is_new = $(":radio[name=is_new]:checked").val();

    // 장비 분실 여부
    var is_lost = $(":checkbox[name=is_lost]").is(":checked") ? 'Y' : 'N';

    $.ajax({
      url: "<?=base_url()?>work/ajax/add_item/" + operation.id,
      type: "POST",
      data: {
        "id"        : operation.id,         
        "part_id"   : item.id,
        "serial_number": $("#serial_number").val(),
        "qty"       : qty,   
        'is_new'    : is_new,
        'is_lost'   : is_lost,      
        "extra": "add_item_for_close_op",
        "csrf_test_name": $.cookie("csrf_cookie_name")
      },
      dataType: "json",
    })
      .done(function(response) {
        if(window.console){
          console.log(response);
        }

        if(response.result == 'success') {
          callback_insert_row(response.id, item.type, item.name, $("#serial_number").val(), '', qty, is_new);
        } else {
          alert('에러!');
        }
      })
      .fail(function(xhr, textStatus){
        alert("Request failed: " + textStatus);
      });

    // 버튼 비활성
    // 선택 초기화해야 함!
    // $("#btn_part_add").prop("disabled", true);
  });

  // 장비 삭제 이벤트 등록
  $(document).on('click', '.remove_item', function(e){
    var item_id = $(this).closest('tr').data('item_id');
    var that = this;
    if(!confirm(item_id + ' 를 목록에서 삭제하시겠습니까?')) {
      return false;
    }

    $.ajax({
        url: "<?=base_url()?>work/ajax/remove_item/" + operation.id,
        type: "POST",
        data: {
          id: operation.id,         
          item_id: item_id,
          csrf_test_name: $.cookie("csrf_cookie_name")
        },
        dataType: "html",
      })
        .done(function(html) {
          alert(html);
          callback_remove_row(that);
        })
        .fail(function(xhr, textStatus){
          alert("Request failed: " + textStatus);
        });
  });

});//end of ready
</script>