<?php
$this->view('layout/header');
$this->view('layout/navbar');
?>
    <!-- start of div.container -->
    <div class="container">

      <!-- Main hero unit for a primary marketing message or call to action -->
      <div class="page-header">
        <h2><i class="fa fa-building-o"></i> <?=$page_title?></h2>
      </div>

      <!-- Example row of columns -->
      <div class="row">
        <div class="col-md-12">
          <table class="table table-hover">
            <thead>
              <tr>
                <th>#</th>
                <th>Master 사무소</th>
                <th>사무소명</th>
                <th>담당자</th>
                <th>전화번호</th>
                <th>주소</th>
                <th>메모</th>
                <th>&nbsp;</th>
              </tr>
            </thead>

            <tbody>
  <?php
  foreach($rows as $row):
  ?>
              <tr>
                <td><?=$row->id?></td>
                <td><?=($row->is_master == 'Y') ? '' : $row->master->name; ?></td>
                <td><?=$row->name?></td>
                <td>
  <?php
  if($row->user){
    echo anchor('/admin/user/view/' . $row->user->id, $row->user->getName());
  } else {
    echo '';
  }
  ?>
                </td>
                <td><?=$row->phone?></td>
                <td><?=$row->address?></td>
                <td><?=$row->memo?></td>
                <td></td>
              </tr>
  <?php
  endforeach;
  ?>
            </tbody>
          </table>

          <p>
            <a href="<?=base_url()?>admin/office/add"><span class="btn btn-primary">&nbsp;신규  등록</span></a>
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
