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
          <li class="<?=($this_office == 'all') ?'active':''?>"><a href="<?=base_url()?>stock/lists/?office_id=all">전체</a></li>
<?php
foreach($office_list as $o):
?>
          <li class="<?=($o->id == $this_office)?'active':''?>"><a href="<?=base_url()?>stock/lists/?office_id=<?=$o->id?>"><?=$o->name?></a></li>
<?php
endforeach;
?>          
        </ul>
          
        <table class="table table-condensed table-hover " id="stock_list">
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
              <th>중비</th>
              <!-- 사무소별 -->
              <th style="padding:0;margin:0;">
                <table class="table" style="margin:0;">
                  <tbody>
                    <td class="col-xs-2">사무소명</td>
                    <td class="col-xs-1">기준</td>
                    <td class="col-xs-1">신가</td>
                    <td class="col-xs-1">중가</td>
                    <td class="col-xs-1">중비</td>
                    <td class="col-xs-1" style="background-color: #CCC;">설치</td>
                    <td class="col-xs-1" style="background-color: #CCC;">점검</td>
                    <td class="col-xs-1" style="background-color: #CCC;">수리</td>
                    <td class="col-xs-1" style="background-color: #CCC;">페기</td>
                    <td class="col-xs-1">발주</td>
                    <td class="col-xs-1"></td>
                  </tbody>
                </table>
              </th>
            </tr>
          </thead>

          <tbody>
<?php
$arr_type_class= array('1' => 'label-success', '2'=>'label-default', '3'=>'label-warning');
$arr_status_text = array('단종', '정상', '일시품절');
foreach($rows as $row):
?>
            <tr class="">
              <td><?=$row->id?></td>
              <td>
                <span class="label <?=$arr_type_class[$row->type]?>"> <?=gs2_part_type($row->type);?> </span>
              </td>
              <td><?=$row->category->name?></td>
              <td><?=$row->name?></td>
              <td><?=$arr_status_text[$row->status]?></td>
              <td><?=intval($row->getUsableTotal())?></td>
              <td><?=intval($row->getDisableTotal())?></td>
              <td colspan="10" style="padding:0;">
<?php
if(count($row->getStockList())):
?>
<table class="table table-hover table-bordered table-condensed" style="margin:0;">
  <tbody>
<?php
  foreach($row->getStockList() as $stock):
    
    $notice = '';         // 기준 수량 비교용
    if($stock->getQtyMinimum() && $stock->getQtyUsable() < $stock->getQtyMinimum()) {
      $notice = 'danger';
    }
?>
    <tr class="<?=$notice?>">
      <td class="col-xs-2"><?=$stock->office->name?></td>
      <td class="col-xs-1"><?=gs2_zero_to_dash($stock->getQtyMinimum())?></td>
      <td class="col-xs-1 active"><?=gs2_zero_to_dash(number_format($stock->getQtyNew()))?></td>
      <td class="col-xs-1 active"><?=gs2_zero_to_dash(number_format($stock->getQtyUsed()))?></td>
      <td class="col-xs-1 active"><?=gs2_zero_to_dash(number_format($stock->getQtyDisabled()))?></td>
      <td class="col-xs-1"><?=gs2_zero_to_dash($stock->getQtyS200())?></td>
      <td class="col-xs-1"><?=gs2_zero_to_dash($stock->getQtyS500())?></td>
      <td class="col-xs-1"><?=gs2_zero_to_dash($stock->getQtyS600())?></td>
      <td class="col-xs-1"><?=gs2_zero_to_dash($stock->getQtyS900())?></td>
      <td class="col-xs-1"><b><?=gs2_zero_to_dash($stock->getQtyS100())?></b></td>
      <td class="col-xs-1">
<?php
      // 입고요청 버튼 - 마스터 사무소, 장비상태, 시트네트 or GS25 유저만 가능
      if( $this->session->userdata('user_type') != '3' && $stock->office->isMaster() === TRUE && $row->status > '0'):
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
      });
      </script>

<?php
$this->load->view('layout/footer');
?>