<?php
$this->view('layout/header');
$this->view('layout/navbar');
?>

<!-- start of div.container -->
<div class="container">
  <!-- Main hero unit for a primary marketing message or call to action -->
  <div class="page-header">
    <h1><span class="fa fa-user"></span>&nbsp;사용자 리스트</h1>
  </div>
 
  <!-- Example row of columns -->
  <div class="row">
    <div class="col-md-12">
      <ul class="nav nav-pills">
        <li class="<?=($type=='')?'active':''?>"><a href="<?=base_url()?>admin/user">전체</a></li>
        <li class="<?=($type==1)?'active':''?>"><a href="<?=base_url()?>admin/user/lists/?type=1">시스네트</a></li>
        <li class="<?=($type==2)?'active':''?>"><a href="<?=base_url()?>admin/user/lists/?type=2">GS25</a></li>
        <li class="<?=($type==3)?'active':''?>"><a href="<?=base_url()?>admin/user/lists/?type=3">납품처</a></li>
      </ul>

      <table class="table table-hover">
        <thead>
          <tr>
            <th>No</th>
            <th>구분</th>
            <th>아이디</th>
            <th>이름</th>
            <th>패스워드(임시)</th>
            <th>소속/거래처</th>
            <th>등록일자</th>
            <th>상태</th>
            <th>&nbsp;</th>
          </tr>
        </thead>

        <tbody>
<?php
foreach($rows as $row):
?>
          <tr class="">
            <td><?=$row->id?></td>
            <td><?=$row->getUserTypeText()?></td>
            <td><?=$row->username?></td>
            <td><?=$row->name?></td>
            <td><?=$row->password?></td>
            <td>
<?php
if($row->type == 1) {
echo $row->office->name;
} elseif($row->type == 3) {
echo $row->company->name;
}
?>
            </td>
            <td><?=(is_object($row->date_register)) ? $row->date_register->format('Y-m-d H:i:s'): '';?></td>
            <td><?=$row->status?></td>
            <td><?='--'?></td>
          </tr>
<?php
endforeach;
?>
        </tbody>
      </table>
<?php 
echo $pagination 
?>
    </div>
  </div><!-- end of row -->
  <div class="row">  
    <div class="col-md-12">
      <a href="<?=base_url()?>admin/user/add"><span class="btn btn-primary"><i class="icon-pencil"></i>&nbsp;사용자 등록</span></a>
    </div>
  </div>
</div><!-- end of container -->

<script type="text/javascript">
$(document).ready(function(){
    $(".ajax").colorbox({'opacity': '0.6', 'width': '80%'});
    $(".iframe").colorbox({
      'iframe'  : true,
      'width'   : '50%',
      'height'  : '80%'
    });
});
</script>

<?php
$this->view('layout/footer');
?>