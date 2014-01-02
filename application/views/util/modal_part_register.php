<?php
// 모달 content - 장비 등록
?>

<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
  <h4 class="modal-title">장비 등록</h4>
</div>
<div class="modal-body">
  <form id="inner_form" role="form" class="form form-horizontal">
    <input type="hidden" name="mode" value="install_request_ok">

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

    <div class="form-group" id="search_block">
      <label class="form-label col-sm-4">&nbsp;</label>
      <div class="col-sm-5">
        <input class="form-control" type="text" name="query" id="query">
      </div>
      <div class="col-sm-3">
          <button class="btn btn-info btn-sm btn_search" type="button">검색</button>
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
  </form>
</div>
<div class="modal-footer">
  <button id="btn_part_add" type="button" class="btn btn-primary" disabled>장비 등록</button>
  <button type="button" class="btn btn-default" data-dismiss="modal">닫기</button>
</div>

<script type="text/javascript">
// 검색 숨기기
$("#search_block").hide();

// 장비 종류 선택 시 장비 목록 가져오기
$(document).on('change', "#category_id", function(){
  var cat = $(":selected", this).val();
  // console.log(cat);
  if( cat == ''){
    $("#select_part").html('');
    return false;
  } else {
    var target_url = "<?=site_url('ajax/response/')?>" + '/' + cat;
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
      if(html == 1000){
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

$(document).on("change", "#select_part", function(e){
  var part_id = $(":selected", this).val();
  part_id = parseInt(part_id, 10);
  if( part_id === 0) {
    return false;
  } 

  // 장비 정보 요청
  $.ajax({
    url: "/util/part/get",
    type: "POST",
    data: {
      "part_id": part_id,
      "extra": "test",
      "csrf_test_name": $.cookie("csrf_cookie_name")
    },
    dataType: "json",
  })
    .done(function(html) {
      var obj = html;
      console.log(obj.type);
    })
    .fail(function(xhr, textStatus){
      alert("Request failed: " + textStatus);
    });
});

$(document).on("click", "#btn_part_add", function(e){
  callback_insert_row(2, '333', '333', '저기', 5);
});

$(document).on('change', ":radio[name=search_method]", function(e){
  var sm = $(":radio[name=search_method]:checked").val();
  if(sm == '0') {
    $("#search_block").hide();
    $("#part_qty").val(1).attr('readonly', false);
  }   else {
    $("#search_block").slideDown();
    $("#part_qty").val(1).attr('readonly', true);
  }
});
</script>