<?php
$this->load->view('layout/header', array('title' => "$title"));
$this->load->view('layout/navbar', array('current' => 'page-stock'));
?>

    <!-- start of div.container -->
    <div class="container">
      <!-- Main hero unit for a primary marketing message or call to action -->
      <div class="page-header">
        <h1>재고 현황</h1>
      </div>

      <!-- Example row of columns -->
      <div class="row">
        <div class="col-md-12">

        <ul class="nav nav-pills">
          <li class="<?=is_null($this_office) ?'active':''?>"><a href="<?=site_url()?>stock/lists">전체</a></li>
<?php
foreach($office_list as $o):
?>
          <li class="<?=(!is_null($this_office) && $o->id == $this_office->id)?'active':''?>"><a href="<?=site_url()?>stock/lists/<?=$o->id?>"><?=$o->name?></a></li>
<?php
endforeach;
?>          
        </ul>
          
        <table class="table table-responsive table-hover " id="stock_list">
          <thead>
            <tr>
              <th rowspan="2">No</th>
              <th rowspan="2">장비 타입</th>
              <th rowspan="2">장비 종류</th>
              <th rowspan="2">장비명</th>
              <th rowspan="2">상태</th>
              <th colspan="2">합계</th>
              <th class="col-xs-6">사무소별 현황</th>
            </tr>
            <tr>
              <th>가용</th>
              <th>비가용</th>
              <!-- 사무소별 -->
              <th style="padding:0;margin:0;">
                <table class="table" style="margin:0;">
                  <tbody>
                    <td class="col-xs-2">사무소명</td>
                    <td class="col-xs-1">기준</td>
                    <td class="col-xs-1">신품</td>
                    <td class="col-xs-1">중고</td>
                    <td class="col-xs-1" style="background-color: #CCC;">발주</td>
                    <td class="col-xs-1" style="background-color: #CCC;">설치</td>
                    <td class="col-xs-1" style="background-color: #CCC;">점검</td>
                    <td class="col-xs-1" style="background-color: #CCC;">수리</td>
                    <td class="col-xs-1" style="background-color: #CCC;">페기</td>
                    <td class="col-xs-2">기능</td>
                  </tbody>
                </table>
              </th>
            </tr>
          </thead>

          <tbody>
<?php
$arr_type_text = array('1' => '시리얼', '2'=>'수량', '3'=>'소모품');
$arr_type_class= array('1' => 'label-success', '2'=>'label-default', '3'=>'label-warning');
$arr_status_text = array('단종', '정상', '일시품절');
foreach($rows as $row):
?>
            <tr class="">
              <td><?=$row->id?></td>
              <td>
                <span class="label <?=$arr_type_class[$row->type]?>"> <?=$arr_type_text[$row->type];?> </span>
              </td>
              <td><?=$row->category->name?></td>
              <td><?=$row->name?></td>
              <td><?=$arr_status_text[$row->status]?></td>
              <td><?=intval($row->getUsableTotal())?></td>
              <td><?=intval($row->getDisableTotal())?></td>
              <!--
              <td><?=intval($row->getNewTotal())?></td>
              <td><?=intval($row->getUsedTotal())?></td>
              -->
              <td colspan="10" style="padding:0;">
<?php
if(count($row->getStockList())):
?>
<table class="table table-hover table-bordered table-condensed" style="margin:0;">
  <tbody>
<?php
  foreach($row->getStockList() as $stock):
?>
    <tr class="default">
      <td class="col-xs-2"><?=$stock->office->name?></td>
      <td class="col-xs-1 active"><?=$stock->qty_minimum?></td>
      <td class="col-xs-1"><?=number_format($stock->qty_new)?></td>
      <td class="col-xs-1"><?=number_format($stock->qty_used)?></td>
      <td class="col-xs-1"><b><?=$stock->qty_s100?></b></td>
      <td class="col-xs-1"><?=$stock->qty_s200?></td>
      <td class="col-xs-1"><?=$stock->qty_s900?></td>
      <td class="col-xs-1"><?=$stock->qty_s500?></td>
      <td class="col-xs-1"><?=$stock->qty_s600?></td>
      <td class="col-xs-2">
<?php
      // 사무소가 master 이고 장비 상태가 정상인 경우만 입고 버튼 보임
      if($stock->office->isMaster() === TRUE && $row->status > '0'):
?>
        <button class="btn btn-info btn-xs btn_order" type="button" data-query="<?=sprintf('?part_id=%d&office_id=%d',$row->id, $stock->office->id)?>">입고</button>
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
      });
      </script>

<?php
$this->load->view('layout/footer');
?>