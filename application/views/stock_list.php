<?php
$this->load->view('layout/header', array('title' => "$title"));
$this->load->view('layout/navbar', array('current' => 'page-stock'));
?>

    <!-- start of div.container -->
    <div class="container">
      <!-- Main hero unit for a primary marketing message or call to action -->
      <div class="page-header">
        <h1>재고</h1>
      </div>

      <!-- Example row of columns -->
      <div class="row">
        <div class="span12">

        <ul class="nav nav-pills">
          <li class="<?=($type=='')?'active':''?>"><a href="/admin/user">전체</a></li>
          <li class="<?=($type==1)?'active':''?>"><a href="/admin/user/lists/1">시스네트</a></li>
          <li class="<?=($type==2)?'active':''?>"><a href="/admin/user/lists/2">GS25</a></li>
          <li class="<?=($type==3)?'active':''?>"><a href="/admin/user/lists/3">납품처</a></li>
        </ul>
          
        <table class="table table-responsive table-hover" id="stock_list">
          <thead>
            <tr>
              <th>No</th>
              <th>장비 타입</th>
              <th>장비 종류</th>
              <th>장비명</th>
              <th>상태</th>
              <th>신품 합계</th>
              <th>중고 합계</th>
              <th>재고</th>
            </tr>
          </thead>

          <tbody>
<?php
$arr_type_text = array('1' => '시리얼', '2'=>'수량', '3'=>'소모품');
$arr_type_class= array('1' => 'label-success', '2'=>'label-warning', '3'=>'label-default');
foreach($rows as $row):
?>
            <tr class="">
              <td><?=$row->id?></td>
              <td>
                <span class="label <?=$arr_type_class[$row->type]?>">
<?php
                echo $arr_type_text[$row->type];
?>
                </span>
              </td>
              <td><?=$row->category->name?></td>
              <td><?=$row->name?></td>
              <td><?=$row->status?></td>
              <td><?=intval($row->getNewTotal())?></td>
              <td><?=intval($row->getUsedTotal())?></td>
              <td>
<?php
if(count($row->getStockList())):
?>
<table class="table table-hover" style="margin-bottom:0;">
  <tbody>
<?php
  foreach($row->getStockList() as $stock):
?>
    <tr class="success">
      <td class="col-sm-4"><?=$stock->inventory->name?></td>
      <td class="col-sm-2"><?=$stock->qty_minimum?></td>
      <td class="col-sm-2"><?=number_format($stock->qty_new)?></td>
      <td class="col-sm-2"><?=number_format($stock->qty_used)?></td>
      <td class="col-sm-2"><button class="btn btn-info btn-xs btn_order" type="button" data-order="<?=sprintf('?part_id=%d&extra=extra',$row->id)?>">Order</button></td>
    </tr>
<?php
endforeach;
?>
  </tbody>
</table>
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

        <ul class="pagination pagination-sm">
          <li><a href="#">&laquo;</a></li>
          <li class="active"><a href="#">1</a></li>
          <li><a href="#">2</a></li>
          <li><a href="#">3</a></li>
          <li><a href="#">4</a></li>
          <li><a href="#">5</a></li>
          <li><a href="#">&raquo;</a></li>
        </ul>

        <p class="well">
          <!-- ie8 호환성 위해 button 태그 대신 span 태그로 변경 -->
          <a href="/stock/add"><span class="btn btn-primary" >&nbsp;신규  등록</span></a>
        </p>

        </div>
      </div>
    </div><!-- end of container -->
      <script type="text/javascript">
      $(document).ready(function() {
        
        // 입고 요청 팝업
        $(".btn_order").click(function(){
          var request_uri = $(this).data('order');
          request_uri = '<?=site_url("work/enter/popup_order/")?>' + request_uri;
          console.log(request_uri);

          $.colorbox({
            'href'  : request_uri,
            'iframe'  : true,
            'opacity' : 0.5,
            'width'   : '50%',
            'height'  : '90%'
          });
        });
      });
      </script>

<?php
$this->load->view('layout/footer');
?>