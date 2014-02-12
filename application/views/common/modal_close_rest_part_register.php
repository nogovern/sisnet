<?php
// 모달 content - 장비 등록
?>
<!-- modal dialog -->
<div class="modal fade" id="modal_rest_part_register" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
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
echo $select_category2;
?>
          </div>

          <div class="form-group">
              <select id="select_part2" name="select_part2" class="form-control"></select>
          </div>

          <div class="form-group">
            <select class="form-control" id="rest_qty" name="rest_qty">
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
        <button type="button" class="btn btn-primary" id="btn_rpart_add" disabled>장비 등록</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">닫기</button>
      </div>
    </div>
  </div>
</div><!-- /.modal -->

<script type="text/javascript">
$(document).ready(function(){
  // 장비 종류 선택 시 장비 목록 가져오기
  $(document).on('change', "#select_cat", function(){
    var cat = $(":selected", this).val();
    if( cat == '0'){
      $("#select_part2").html('<option>not loaded...</option>');
      return false;
    } 
      
    var target_url = _base_url + "ajax/get_model_list_for_warehousing/" + cat;
    $.ajax({
      url: target_url,
      async: false,
      type: "POST",
      data: {
        "select_cat": cat,
        "extra": "test",
        "csrf_test_name": $.cookie("csrf_cookie_name")
      },
      dataType: "html",
    })
      .done(function(html) {
        if(html == 'none'){
          alert('해당 카테고리에 등록된 장비가 없습니다.\n관리자에게 장비 등록을 요청하세요');
          $("#select_cat").val(0).change();
        } else {
          $("#select_part2").html(html);
        }
      })
      .fail(function(xhr, textStatus){
        alert("Request failed: " + textStatus);
      });
  });

  // 장비 모델 선택
  $(document).on("change", "#select_part2", function(e){
    var part_id = $(":selected", this).val();
    part_id = parseInt(part_id, 10);
    if( part_id === 0) {
      $("#btn_rpart_add").prop("disabled", true);
      return false;
    }

    $("#btn_rpart_add").prop("disabled", false);
  });

  // 점포내 보관 장비 등록
  $(document).on("click", "#btn_rpart_add", function(e){
    var cat_name,
        part_id,
        part_name,
        qty,
        item_id;

    cat_name = $("#select_cat option:selected").text();
    part_name = $("#select_part2 option:selected").text();
    part_id = $("#select_part2").val();
    qty = $("#rest_qty").val();

    $.ajax({
      url: _base_url + "util/addStoreItem/",
      type: "POST",
      data: {
        id: operation.id,
        store_id: <?=$store->id?>,
        part_id: part_id,
        qty: qty,
        "extra": "add rest item for hujum",
        "csrf_test_name": $.cookie("csrf_cookie_name")
      },
      dataType: "json",
    })
      .done(function(response) {
        gs2_console(response);
        if(response.error) {
          alert(response.error_msg);
        } else {
          var tr = $("<tr/>").attr('data-item_id', response.item_id);
          tr.append($("<td/>").text(cat_name));
          tr.append($("<td/>").text(part_name));
          tr.append($("<td/>").text(qty));
          tr.append($("<td/>").html('<button class="btn btn-danger btn-xs remove_item" type="button">X</button>'));

          $("tr.blank").remove();
          $("#rest_part_table tbody").append(tr);

          // 1대로 초기화
          $("#rest_qty").val(1).change();
        }
      })
      .fail(function(xhr, textStatus){
        alert("Request failed: " + textStatus);
      });
    
  });

  // 점포내 보관 장비 삭제
  $("#rest_part_table tbody").on('click', '.remove_item', function(e){
    var item_id = $(this).closest('tr').data('item_id');
    var p_name = $(this).closest('tr').find("td:eq(1)").text();
    
    if(!confirm(p_name + ' 을(를) 목록에서 삭제하시겠습니까?')) {
      return false;
    }

    // ajax scope 로 들어가면 this의 context 가 달라지므로 that 에 저장
    var that = this;

    $.ajax({
        url: _base_url + "util/removeStoreItem/" + item_id,
        type: "POST",
        data: {
          id: operation.id,         
          item_id: item_id,
          csrf_test_name: $.cookie("csrf_cookie_name")
        },
        dataType: "html",
      })
        .done(function(response) {
          gs2_console(that);
          $(that).closest('tr').remove();
        })
        .fail(function(xhr, textStatus){
          alert("Request failed: " + textStatus);
        });
  });

  $("#select_cat").change();
});//end of ready

</script>

