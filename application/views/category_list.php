<?php
$this->load->view('layout/header', array('title' => '관리자 >> 카테고리 관리'));
$this->load->view('layout/navbar', array('current' => 'page-admin-category'));
?>
    <!-- start of div.container -->
    <div class="container">
      <!-- Main hero unit for a primary marketing message or call to action -->
      <div class="page-header">
        <h1><span class="fa fa-code-fork"> 장비 카테고리</h1>
      </div>

      <!-- Example row of columns -->
      <div class="row">
        <div class="col-xs-12">
          
        <table class="table table-hover">
          <thead>
            <tr>
              <th>#</th>
              <th>상위카테고리</th>
              <th>카테고리명</th>
              <th>상태</th>
              <th>등록일</th>
            </tr>
          </thead>

          <tbody>
<?php
foreach($rows as $row):
?>
            <tr class="">
              <td><?=$row->id?></td>
              <td><?=($row->parent) ? $row->parent->name : ''?></td>
              <td><?=$row->name?></td>
              <td><?=$row->status?></td>
              <td><?=(is_object($row->date_register)) ? $row->date_register->format('Y-m-d'): '';?></td>
            </tr>
<?php
endforeach;
?>
          </tbody>

        </table>

        <p>
          <a href="<?=base_url()?>admin/category/add"><span class="btn btn-primary"><i class="icon-pencil"></i>&nbsp;카테고리 등록</span></a>
        </p>

        </div>
      </div>
    </div><!-- end of container -->
      <script type="text/javascript">
      </script>

<?php
$this->load->view('layout/footer');
?>