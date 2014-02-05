<?php
// 모달 content - 장비 등록
?>
<!-- modal dialog -->
<div class="modal fade" id="modal_close_part_register" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <!-- start form -->
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title">휴점 장비 등록</h4>
      </div>
      <div class="modal-body">

        <form role="form" class="form-inline">
          <div class="form-group">
            <div class="form-label" style="font-size:18px;">개별장비추가   </div>
          </div>

          <div class="form-group">
<?php
echo $select_category;
?>
          </div>

          <div class="form-group">
              <select id="select_part" name="select_part" class="form-control"></select>
          </div>

          <div class="form-group">
            <select class="form-control" id="part_qty" name="part_qty">
              <option value="1">1 대</option>
              <option value="2">2 대</option>
              <option value="3">3 대</option>
              <option value="4">4 대</option>
              <option value="5">5 대</option>
              <option value="6">6 대</option>
              <option value="7">7 대</option>
              <option value="8">8 대</option>
              <option value="9">9 대</option>
              <option value="10">10 대</option>
            </select>
          </div>
        </form>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" id="btn_part_add" disabled>장비 등록</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">닫기</button>
      </div>
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
  $(document).on('change', "#category_id", function(){
    reset_part_register_form();
    
    var cat = $(":selected", this).val();
    if( cat == '0'){
      $("#select_part").html('<option>not loaded...</option>');
      return false;
    } 
      
    var target_url = _base_url + "ajax/get_model_list_for_warehousing/" + cat;
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
          alert('해당 카테고리에 등록된 장비가 없습니다.\n관리자에게 장비 등록을 요청하세요');
          $("#category_id").val(0).change();
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
      disableAddItem();
      return false;
    }

    enableAddItem(); 
  });

  // 장비 등록
  $(document).on("click", "#btn_part_add", function(e){
    var cat_name,
        part_id,
        part_name,
        qty;

    cat_name = $("#category_id option:selected").text();
    part_name = $("#select_part option:selected").text();
    part_id = $("#select_part:selected").val();
    qty = $("#part_qty").val();

    var tr = $("<tr/>").attr('data-item_id', part_id);
    tr.append($("<td/>").text(cat_name));
    tr.append($("<td/>").text(part_name));
    tr.append($("<td/>").text(qty));

    $("tr.blank").remove();
    $("#part_table tbody").append(tr);

    // 1대로 초기화
    $("#part_qty").val(1).change();
  });

  // 장비 삭제 이벤트 등록
  $(document).on('click', '.remove_item', function(e){
    var item_id = $(this).closest('tr').data('item_id');
    var that = this;
    if(!confirm(item_id + ' 를 목록에서 삭제하시겠습니까?')) {
      return false;
    }

    $.ajax({
        url: _base_url + "work/ajax/remove_item/" + operation.id,
        type: "POST",
        data: {
          id: operation.id,         
          item_id: item_id,
          csrf_test_name: $.cookie("csrf_cookie_name")
        },
        dataType: "html",
      })
        .done(function(html) {
          // 자체 삭제 루틴 짜야 함
          alert(html);
        })
        .fail(function(xhr, textStatus){
          alert("Request failed: " + textStatus);
        });
  });

  $("#category_id").change();
});//end of ready

// 폼 초기화
function reset_part_register_form() {
  var form = $("#modal_close_part_register form");

  disableAddItem();
}

function enableAddItem() {
  $("#btn_part_add").prop("disabled", false);
}

function disableAddItem() {
  $("#btn_part_add").prop("disabled", true);
}

</script>

