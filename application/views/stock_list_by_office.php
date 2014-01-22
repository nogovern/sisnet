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

      <!-- Example row of columns -->
      <div class="row">
        <div class="col-md-12">

        <ul class="nav nav-pills">
          <li class="<?=($this_office == 'all') ?'active':''?>"><a href="<?=base_url()?>stock/lists/all">전체</a></li>
<?php
foreach($office_list as $o):
?>
          <li class="<?=($o->id == $this_office)?'active':''?>"><a href="<?=base_url()?>stock/lists/<?=$o->id?>"><?=$o->name?></a></li>
<?php
endforeach;
?>          
        </ul>
          
        <table class="table table-responsive table-hover " id="stock_list">
          <caption style="text-align: right;">등록 장비수 : <?=count($rows)?></caption>
          <thead>
            <tr>
              <th>No</th>
              <th>장비 타입</th>
              <th>장비 종류</th>
              <th>장비명</th>
              <th>상태</th>
              <th>가용</th>
              <th>비가용</th>
              <th>기준</th>
              <th>신품</th>
              <th>중고</th>
              <th style="background-color: #CCC;">발주</th>
              <th style="background-color: #CCC;">설치</th>
              <th style="background-color: #CCC;">점검</th>
              <th style="background-color: #CCC;">수리</th>
              <th style="background-color: #CCC;">페기</th>
              <th>기능</th>
            </tr>
          </thead>

          <tbody>
<?php
$arr_type_text = array('1' => '시리얼', '2'=>'수량', '3'=>'소모품');
$arr_type_class= array('1' => 'label-success', '2'=>'label-default', '3'=>'label-warning');
$arr_status_text = array('단종', '정상', '일시품절');
foreach($rows as $row):
  $part = $row->part;
?>
            <tr class="">
              <td><?=$row->part->id?></td>
              <td>
                <span class="label <?=$arr_type_class[$row->part->type]?>"> <?=$arr_type_text[$row->part->type];?> </span>
              </td>
              <td><?=$row->part->category->name?></td>
              <td><?=$row->part->name?></td>
              <td><?=$arr_status_text[$row->part->status]?></td>
              <td><?=intval($row->part->getUsableTotal())?></td>
              <td><?=intval($row->part->getDisableTotal())?></td>
              <td><?=$row->qty_minimum?></td>
              <td><?=number_format($row->qty_new)?></td>
              <td><?=number_format($row->qty_used)?></td>
              <td><b><?=$row->qty_s100?></b></td>
              <td><?=$row->qty_s200?></td>
              <td><?=$row->qty_s900?></td>
              <td><?=$row->qty_s500?></td>
              <td><?=$row->qty_s600?></td>
              <td>
<?php
  // 사무소가 master 이고 장비 상태가 정상인 경우만 입고 버튼 보임
  if($row->office->isMaster() === TRUE && $row->part->status > '0'):
?>
                        <button class="btn btn-info btn-xs btn_order" type="button" data-query="<?=sprintf('?part_id=%d&office_id=%d',$row->id, $row->office->id)?>">입고</button>
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
          <a href="<?=base_url()?>stock/add"><span class="btn btn-primary" >&nbsp;신규  등록</span></a>
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
$this->view('layout/footer');
?>