<?php
$this->view('layout/header');
$this->view('layout/navbar');
?>

<!-- start of div.container -->
<div class="container">
<!-- Main hero unit for a primary marketing message or call to action -->
<div class="page-header">
  <h1>재고 현황</h1>
</div>

<!-- filter -->
<div class="well well-sm">
  <form method="post" id="filter-form" class="form-inline" role="search">
    <input type="hidden" name="csrf_test_name" value="<?php echo $this->security->get_csrf_hash();?>">
    <div class="form-group">
      장비종류 : 
      <?php echo $category_filter; ?>
    </div>

    <div class="form-group">
      장비모델: 
      <select id="part_id" name="part_id" class="form-control">
        <option value="0">--- 전체 ---</option>
      </select>
    </div>

    <div class="form-group">
      &nbsp;&nbsp;사무소:
      <?php echo $office_filter; ?>
    </div>

    <div class="form-group">
      &nbsp;&nbsp; 
      <button type="submit" class="btn btn-primary btn-sm">검색</button> 
    </div>
  </form>
</div>

<!-- Example row of columns -->
<div class="row">
  <div class="col-md-12">
    
  <table class="table table-condensed table-bordered table-hover " id="stock_list">
    <caption style="text-align: right;">등록 장비수 : <?=number_format($total_rows)?></caption>
    <thead>
      <tr>
        <th>No</th>
        <th>장비 타입</th>
        <th>장비 종류</th>
        <th>장비명</th>
        <th>상태</th>
        <th>기준</th>
        <th style="width:5%;">신가</th>
        <th style="width:5%;">중가</th>
        <th style="width:5%;">중비</th>
        <th style="width:5%;background-color: #CCC;">설치</th>
        <th style="width:5%;background-color: #CCC;">점검</th>
        <th style="width:5%;background-color: #CCC;">수리</th>
        <th style="width:5%;background-color: #CCC;">페기</th>
        <th style="width:5%;">발주</th>
        <th style="width:5%;"></th>
      </tr>
    </thead>

    <tbody>
<?php
$arr_type_class= array('1' => 'label-success', '2'=>'label-default', '3'=>'label-warning');
$arr_status_text = array('단종', '정상', '일시품절');

foreach($rows as $row):
$part = $row->part;   // 편의상

$notice = '';         // 기준 수량 비교용
if($row->getQtyMinimum() && $row->getQtyUsable() < $row->getQtyMinimum()) {
$notice = 'danger';
}
?>
      <tr class="<?=$notice?>">
        <td><?=$part->id?></td>
        <td>
          <span class="label <?=$arr_type_class[$part->type]?>"> <?=gs2_part_type($part->type);?> </span>
        </td>
        <td><?=$part->category->name?></td>
        <td><?=$part->name?></td>
        <td><?=$arr_status_text[$part->status]?></td>
        <td><?=gs2_zero_to_dash($row->qty_minimum)?></td>
        <td class="active"><?=gs2_zero_to_dash($row->getQtyNew())?></td>
        <td class="active"><?=gs2_zero_to_dash($row->getQtyUsed())?></td>
        <td class="active"><?=gs2_zero_to_dash($row->getQtyDisabled())?></td>
        <td><?=gs2_zero_to_dash($row->getQtyS200())?></td>
        <td><?=gs2_zero_to_dash($row->getQtyS900())?></td>
        <td><?=gs2_zero_to_dash($row->getQtyS500())?></td>
        <td><?=gs2_zero_to_dash($row->getQtyS600())?></td>
        <td><b><?=gs2_zero_to_dash($row->getQtyS100())?></b></td>
        <td>
<?php
// 사무소가 master 이고 장비 상태가 정상인 경우만 입고 버튼 보임
if( $this->session->userdata('user_type') != '3' && $row->office->isMaster() === TRUE && $part->status > '0'):
?>
          <button class="btn btn-info btn-xs btn_order" type="button" data-query="<?=sprintf('?part_id=%d&office_id=%d',$part->id, $row->office->id)?>">입고</button>
<?php
endif;
?>
        </td>
      </tr>
          
<?php
endforeach;
?>
    </tbody>

  </table>

  <!-- Pagination -->
  <div style="text-align:center">
    <?php echo $pagination; ?>
  </div>

<?php if($this->session->userdata('user_level') >= '3'): ?>
  <p class="well">
    <!-- ie8 호환성 위해 button 태그 대신 span 태그로 변경 -->
    <a href="<?=base_url()?>stock/add"><span class="btn btn-primary" >&nbsp;신규  등록</span></a>
  </p>
<?php endif; ?>

  </div>
</div>
</div><!-- end of container -->
<script type="text/javascript">
$(document).ready(function() {
  
  // 입고 요청 팝업
  $(".btn_order").click(function(){
    var url = '<?=site_url("work/enter/order_part")?>';
    var query = $(this).data('query');
    var request_uri = url + '/' + query;

    // console.log(request_uri);

    $.colorbox({
      'href'  : request_uri,
      'iframe'  : true,
      'opacity' : 0.5,
      'width'   : '50%',
      'height'  : '90%'
    });
  });

  // 장비 종류 선택 시 장비 목록 가져오기
  $(document).on('change', "#category_filter", function(){
    var cat = $(":selected", this).val();
      
    var target_url = _base_url + "ajax/get_models_for_filter/" + cat + '/filter';
    $.ajax({
      url: target_url,
      async: false,
      type: "POST",
      data: {
        "category_filter": cat,
        "extra": "test",
        "csrf_test_name": $.cookie("csrf_cookie_name")
      },
      dataType: "html",
    })
      .done(function(html) {
        $("#part_id").html(html);
      })
      .fail(function(xhr, textStatus){
        alert("Request failed: " + textStatus);
      });
  });

  ///////////////////////
  // 검색 필터 전송 
  ///////////////////////
  $("#filter-form").submit(function() {
    var url;
    var query = $(this).serialize();
    var office = $("#office_filter").val();

    if(office == '0') {
      gs2_go_page(_base_url + "stock/lists/?off_id=all");
    } else {
      url = _base_url + 'stock/lists/?' + query ;
      $(this).prop('action', url);
    }

  });

  // 장비 종류가 선택된 경우
  var cat_id = $("#category_filter").val();
  if( cat_id != '0') {
    $("#category_filter").change();
  }

});
</script>

<?php
$this->view('layout/footer');
?>