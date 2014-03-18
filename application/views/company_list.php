<?php
$this->view('layout/header');
$this->view('layout/navbar');

$arr_type = array(
  0 => '전체',
  1 => '시스네트',
  2 => 'GS25',
  3 => '납품',
  4 => '제조',
  5 => '수리',
  6 => '폐기',
);

?>
    <!-- start of div.container -->
    <div class="container">
      
      <!-- Main hero unit for a primary marketing message or call to action -->
      <div class="page-header">
        <h1><span class="fa fa-building-o"></span>&nbsp;거래처 리스트</h1>
      </div>

      <!-- Example row of columns -->
      <div class="row">
        <div class="col-md-12">
          <ul class="nav nav-pills">
            <?php 
            foreach($arr_type as $idx => $type_name) {
              if($type == $idx) {
            ?>
            <li class="active"><a href="#"><?=$type_name?></a></li>
            <?php
              }
              else {
            ?>
            <li><a href="<?=base_url()?>admin/company/lists/?type=<?=$idx?>"><?=$type_name?></a></li>
              
            <?php
              }
            }
            ?>
          </ul>
          
          <table class="table table-hover">
            <thead>
              <tr>
                <th>#</th>
                <th>Type</th>
                <th>업체명</th>
                <th>담당자</th>
                <th>연락처</th>
                <th>주소</th>
                <th>등록일</th>
                <th>상태</th>
                <th>설명</th>
              </tr>
            </thead>

            <tbody>
  <?php
  foreach($rows as $row):
  ?>
              <tr>
                <td><?=$row->id?></td>
                <td><?=$arr_type[$row->type]?></td>
                <td><?=$row->name?></td>
                <td><?=$row->getUserAnchor();?></td>
                <td><?=$row->tel?></td>
                <td><?=$row->address?></td>
                <td><?=$row->getDateRegister()?></td>
                <td><?=$row->status?></td>
                <td><?=$row->memo?></td>
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
            <a href="<?=base_url()?>admin/company/add"><span class="btn btn-primary">&nbsp;신규  등록</span></a>
          </p>

        </div>
      </div>
    </div><!-- end of container -->
    <script type="text/javascript">
    $(document).ready(function(){
        $(".ajax").colorbox({'opacity': '0.6', 'width': '80%'});
    });
    </script>

<?php
$this->view('layout/footer');
?>