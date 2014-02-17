<?php
$this->view('layout/header');
$this->view('layout/navbar');
?>

<!-- start of div.container -->
<div class="container">
  <!-- Main hero unit for a primary marketing message or call to action -->
  <div class="page-header">
    <h2><span class="fa fa-desktop"></span>&nbsp;입고 업무</h2>
  </div>
  
  <!-- filter -->
  <div class="row well well-sm">
    <form id="filter-form" class="form-inline" role="search">
      <div class="form-group">
        작업구분 : 
        <select class="form-control">
          <option value="">입고</option>
        </select>
      </div>
      <div class="form-group">
        진행상태 : 
        <select class="form-control">
          <option value="">--전체--</option>
          <option value="">요청</option>
          <option value="">확정</option>
          <option value="">입력</option> 
        </select>
      </div>
      <div class="form-group">
        사무소 : 
        <select class="form-control">
          <option value="">--전체--</option>
          <option value="">가산</option>
          <option value="">부산</option> 
        </select>
      </div>
      <div class="form-group">
        기간 : 
        <button type="button" class="btn btn-primary btn-sm">검색</button> 
        -- 아직 동작 안합니다! --
      </div>
    </form>
  </div>

  <!-- Example row of columns -->
  <div class="row">
    <div class="col-md-12">
      <ul class="nav nav-pills">
        <li class="<?=($type=='')?'active':''?>"><a href="#">전체</a></li>
        <li class=""><a href="#">처리중</a></li>
        <li class=""><a href="#">확인중</a></li>
        <li class=""><a href="#">완료</a></li>
      </ul>

      <table id="op_list" class="table table-hover ">
        <thead>
          <tr>
            <th>No</th>
            <th>종류</th>
            <th>요청자</th>
            <th>입고위치</th>
            <th>장비종류</th>
            <th>모 델</th>
            <th>납품처</th>
            <th>수량(요청/리스트/입고)</th>
            <th>상태</th>
            <th>등록일</th>
            <th>입고요청일</th>
            <th>입고예정일</th>
            <th>완료일</th>
            <th>&nbsp;</th>
          </tr>
        </thead>

        <tbody>
<?php
foreach($rows as $row):
switch($row->status) {
  case '1': $label_color = 'label-default';break;
  case '2': $label_color = 'label-info';break;
  case '3': $label_color = 'label-warning';break;
  case '4': $label_color = 'label-success';break;
  default : $label_color = 'label-default';break;
}

?>
          <tr class="">
            <td><?=$row->id?></td>
            <td><?=gs2_get_work_name($row->type)?></td>
            <td><?=$row->user->name?></td>
            <td><?=$row->office->name?></td>
            <!-- 장비 -->
            <td><?=$row->getItem()->part->category->name?></td>
            <td><?=$row->getItem()->part->name?></td>
            <td><?=$row->getItem()->part->company->name?></td>
            <td><?=$row->getTotalRequestQty() .'/'. $row->getTotalCompleteQty() .'/'. $row->getTotalScanQty()?></td>
            <td>
              <span class="label <?=$label_color?>"><?=constant("GS2_OP_ENTER_STATUS_" .$row->status)?></span>
            </td>
            <td><?=$row->getDateRegister();?></td>
            <td><?=$row->getDateRequest();?></td>
            <td><?=$row->getDateExpect();?></td>
            <td><?=$row->getDateFinish();?></td>
            <!--
            <td><a class="popover_memo" href="#" data-toggle="popover" data-original-title="요청메모" data-content="<?=$row->memo?>">[메모보기]</a></td>
            -->
            <td><button class="btn btn-default btn-xs btn_view" type="button" data-href="<?=site_url('work/enter/view/') . '/' . $row->id ?>">보기</button></td>
          </tr>
<?php
endforeach;
?>
        </tbody>

      </table>
      
      <div class="well well-sm">
        입고 요청은 "재고" 리스트에서 가능합니다.
        <!--
        <a href="/work/enter/request"><span class="btn btn-primary"><i class="icon-pencil"></i>&nbsp;입고 요청 등록</span></a>
        -->
      </div>

    </div>
  </div>
</div><!-- end of container -->

<script type="text/javascript">
$(document).ready(function(){
  
  // colorbox      
  $(".ajax").colorbox({'opacity': '0.6', 'width': '80%'});
  $(".iframe").colorbox({
    'iframe'  : true,
    'width'   : '50%',
    'height'  : '80%'
  });

    // 상세 보기 페이지로 이동
  $("button.btn_view").click(function(){
    var href = $(this).data('href');
    gs2_go_page(href);
  });

  $("#op_list tbody tr").dblclick(function(event) {
    var href = $("td .btn_view", this).data('href');
    gs2_go_page(href);
  });

  /////////////////////////
  // bootstrap 3 popover //
  /////////////////////////
  $(".popover_memo").popover({trigger: 'hover', placement: 'left'});
  $(".popover").click(function(e){e.preventDefault();});
});

// 페이지 이동
function gs2_go_page(page_url) {
  location.href = page_url;
}

</script>

<?php
$this->view('layout/footer');
?>