<?php
$this->load->view('layout/header', array('title' => "$title"));
$this->load->view('layout/navbar', array('current' => $current));

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
          <li class="<?=($office == '')?'active':''?>"><a href="<?=site_url()?>stock/lists">전체</a></li>
          <li class="<?=($office->id==1)?'active':''?>"><a href="<?=site_url()?>stock/listByOffice/1">서울-가산</a></li>
          <li class="<?=($office->id==2)?'active':''?>"><a href="<?=site_url()?>stock/listByOffice/2">대전</a></li>
          <li class="<?=($office->id==3)?'active':''?>"><a href="<?=site_url()?>stock/listByOffice/3">부산</a></li>
          <li class="<?=($office->id==4)?'active':''?>"><a href="<?=site_url()?>stock/listByOffice/4">제주</a></li>
        </ul>
          
        <table class="table table-responsive table-hover" id="stock_list">
          <thead>
            <tr>
              <th>No</th>
              <th>장비 구분</th>
              <th>장비명</th>
              <th>모델명</th>
              <th>상태</th>
              <th>신품 합계</th>
              <th>중고 합계</th>
              <th>기준수량</th>
              <th>신품</th>
              <th>중고</th>
              <th>S100</th>
              <th>S400</th>
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
                <span class="label <?=$arr_type_class[$row->part->type]?>">
<?php
                echo $arr_type_text[$row->part->type];
?>
                </span>
              </td>
              <td><?=$row->part->part_code?></td>
              <td><?=$row->part->name?></td>
              <td><?=$row->part->status?></td>
              <td></td>
              <td></td>
              <td><?=$row->qty_minimum?></td>
              <td><?=$row->qty_new?></td>
              <td><?=$row->qty_used?></td>
              <td><?=$row->qty_s100?></td>
              <td><?=$row->qty_s400?></td>
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
        $("#stock_list tr").hover(function(){
          // $(this).addClass('active');
        });
      });
      </script>
<?php
$this->load->view('layout/footer', array('current' => $current));
?>