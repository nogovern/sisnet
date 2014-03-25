<?php
/**
 * 리포트 - 사무소별 리스트
 */
$this->view('layout/header');
$this->view('layout/navbar');

// gs2_dump($rows);
?>
<!-- start of div.container -->
<div class="container">
  <!-- Main hero unit for a primary marketing message or call to action -->
  <div class="page-header">
    <h2><span class="fa fa-bar-chart-o"></span>&nbsp;사무소별 작업량 리포트</h2>
  </div>
 
  <!-- Example row of columns -->
  <div class="row">
    <div class="col-md-12">
      
      <!-- filter -->
      <div class="well well-sm">
      </div>

      <table id="op_list" class="table table-hover table-bordered table-responsive">
        <thead>
          <tr>
            <th>담당사무소</th>
            <th>입고</th>
            <th>설치</th>
            <th>철수</th>
            <th>교체</th>
            <th>수리</th>
            <th>폐기</th>
            <th>이동</th>
            <th>이관</th>
            <th>변경</th>
            <th>합계</th>
          </tr>
        </thead>

        <tbody>
<?php
foreach($rows as $row):
?>
          <tr class="">
            <td></td>
            <td><?php echo $row[100]; ?></td>
            <td><?php echo $row[200]; ?></td>
            <td><?php echo $row[300]; ?></td>
            <td><?php echo $row[400]; ?></td>
            <td><?php echo $row[500]; ?></td>
            <td><?php echo $row[600]; ?></td>
            <td><?php echo $row[700]; ?></td>
            <td><?php echo $row[800]; ?></td>
            <td><?php echo $row[900]; ?></td>
            <td></td>
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

    </div>
  </div>
</div><!-- end of container -->

<?php
$this->view('layout/footer');
?>
