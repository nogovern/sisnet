<?php
$this->view('layout/header', array('title' => '관리자 >> GS25 점포 리스트'));
$this->view('layout/navbar', array('current' => 'page-admin-store'));
?>
<!-- start of div.container -->
<div class="container">
  <!-- Main hero unit for a primary marketing message or call to action -->
  <div class="page-header">
    <h1><span class="fa fa-home"> 점포 리스트</h1>
  </div>

  <!-- Example row of columns -->
  <div class="row">
    <div class="col-md-12">
      
    <table class="table table-hover table-condensed">
      <thead>
        <tr>
          <th>No</th>
          <th>점포코드</th>
          <th>점포명</th>
          <th>점주명</th>
          <th>점주 연락처</th>
          <th>점포 연락처</th>
          <th>주  소</th>
          <th>등록일</th>
          <th>상  태</th>
          <th></th>
        </tr>
      </thead>

      <tbody>
<?php
$status_text = array('페점', '정상', '휴점C', '휴점S' );
foreach($rows as $row):
?>
        <tr class="" data-storeid="<?=$row->id?>">
          <td><?=$row->id?></td>
          <td><?=$row->code?></td>
          <td><?=$row->name?></td>
          <td><?=$row->owner_name?></td>
          <td><?=$row->owner_tel?></td>
          <td><?=$row->tel?></td>
          <td><?=$row->address?></td>
          <td><?=$row->getDateRegister()?></td>
          <td><?=$status_text[$row->status]?></td>
          <td><button class="btn btn-info btn-sm click_me">상세</button></td>
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
      <a href="<?=base_url()?>admin/store/register"><span class="btn btn-primary"><i class="icon-pencil"></i>&nbsp;점포 등록</span></a>
    </p>

    </div>
  </div>
</div><!-- end of container -->
<?php
// $this->view('common/modal_store_info');
?>
<script type="text/javascript">
jQuery(document).ready(function($) {
  $(".click_me").click(function(){
    openStoreInfo($(this).closest('tr').data('storeid'));
  });
});
</script>

<?php
$this->view('common/modal_store_info');
$this->view('layout/footer');
?>
