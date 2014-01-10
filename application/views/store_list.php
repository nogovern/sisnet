<?php
$this->load->view('layout/header', array('title' => '관리자 >> GS25 점포 리스트'));
$this->load->view('layout/navbar', array('current' => 'page-admin-store'));
?>
    <!-- start of div.container -->
    <div class="container">
      <!-- Main hero unit for a primary marketing message or call to action -->
      <div class="page-header">
        <h1><span class="fa fa-home"> 점포 리스트</h1>
      </div>

      <!-- Example row of columns -->
      <div class="row">
        <div class="col-xs-12">
          
        <table class="table table-hover">
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
            </tr>
          </thead>

          <tbody>
<?php
$status_text = array('페점', '정상', '휴점C', '휴점S' );
foreach($rows as $row):
?>
            <tr class="">
              <td><?=$row->id?></td>
              <td><?=$row->code?></td>
              <td><?=$row->name?></td>
              <td><?=$row->owner_name?></td>
              <td><?=$row->owner_tel?></td>
              <td><?=$row->tel?></td>
              <td><?=$row->address?></td>
              <td><?=$row->getDateRegister()?></td>
              <td><?=$status_text[$row->status]?></td>
            </tr>
<?php
endforeach;
?>
          </tbody>

        </table>

        <p>
          <a href="/admin/store/add"><span class="btn btn-primary"><i class="icon-pencil"></i>&nbsp;점포 등록</span></a>
        </p>

        </div>
      </div>
    </div><!-- end of container -->
      <script type="text/javascript">
      </script>

<?php
$this->load->view('layout/footer');
?>