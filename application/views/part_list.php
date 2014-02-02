<?php
$this->view('layout/header');
$this->view('layout/navbar');

$part_type = array(
  0 => '전체',
  1 => '시리얼',
  2 => '수량',
  3 => '소모폼',
);
?>
<!-- start of div.container -->
<div class="container">
  <!-- Main hero unit for a primary marketing message or call to action -->
  <div class="page-header">
    <h2><i class="fa fa-briefcase"></i> 장비 리스트</h2>
  </div>

  <!-- Example row of columns -->
  <div class="row">
    <div class="col-md-12">
      <ul class="nav nav-pills">
<?php 
foreach($part_type as $idx => $type_name) {
  if($type == $idx) {
?>
        <li class="active"><a href="#"><?=$type_name?></a></li>
<?php
  }
  else {
?>
        <li><a href="<?=base_url()?>admin/part/lists/?type=<?=$idx?>"><?=$type_name?></a></li>
<?php
  }
}
?>
      </ul>
      
      <table class="table">
        <caption>
          <div class="text-right" ><span class="label label-info">타입</span> 1:시리얼 2:수량관리 3:소모품</div>
        </caption>
        <thead>
          <tr>
            <th>No</th>
            <th>타입</th>
            <th>장비종류</th>
            <th>제조사명</th>
            <th>모델명</th>
            <th>납품처</th>
            <th>등록일</th>
            <th>재고수량</th>
            <th>상태</th>
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
            <td><?=$row->manufacturer?></td>
            <td><?=$row->name?></td>
            <td><?=$row->getCompany()?></td>
            <td><?=(is_object($row->date_register)) ? $row->date_register->format('Y-m-d'): '';?></td>
            <td><?=$row->getNewTotal()?>/<?=$row->getUsedTotal()?></td>
            <td><?=$arr_status_text[$row->status]?></td>
          </tr>
  <?php
  endforeach;
  ?>
        </tbody>
      </table>
      <div style="text-align:center">
        <?=$pagination?>
      </div>

      <p>
        <a href="<?=base_url()?>admin/part/add"><span class="btn btn-primary"><i class="icon-pencil"></i>&nbsp;장비 등록</span></a>
      </p>

    </div>
  </div>
</div><!-- end of container -->

<script type="text/javascript">
</script>

<?php
$this->view('layout/footer');
?>