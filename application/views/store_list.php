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
          <th class="col-sm-1"></th>
          <th class="col-sm-1">최초코드</th>
          <th class="col-sm-1">점포코드</th>
          <th class="col-sm-2">점포명</th>
          <th class="col-sm-1">점주명</th>
          <th class="col-sm-1">연락처</th>
          <th class="col-sm-2">주  소</th>
          <th class="col-sm-1">등록일</th>
          <th class="col-sm-1">상  태</th>
          <th class="col-sm-1"></th>
        </tr>
      </thead>

      <tbody>
<?php
foreach($rows as $row):
  switch($row->status) {
    case '0':
      $tr_color = 'class="danger"';
      break;
    case '1':
      $tr_color = '';
      break;
    case '2':
      $tr_color = 'class="success"';
      break;

    default:
      $tr_color = '';
  }
?>
        <tr <?=$tr_color?> data-storeid="<?=$row->id?>">
          <td><?=$row->id?></td>
          <td><?=$row->code?></td>
          <td><?=$row->code2?></td>
          <td><?=$row->name?></td>
          <td><?=$row->owner_name?></td>
          <td><?=$row->tel?></td>
          <td><?=$row->address?></td>
          <td><?=$row->getDateRegister()?></td>
          <td><?=gs2_store_status($row->status)?></td>
          <td><button class="btn btn-info btn-sm click_me">상세</button></td>
        </tr>
<?php
endforeach;
?>
      </tbody>

    </table>
    
    <!-- Pagination -->
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
