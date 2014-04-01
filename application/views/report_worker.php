<?php
/**
 * 리포트 - 사용자별 리스트
 */
$this->view('layout/header');
$this->view('layout/navbar');

?>
<!-- start of div.container -->
<div class="container">
  <!-- Main hero unit for a primary marketing message or call to action -->
  <div class="page-header">
    <h2><span class="fa fa-bar-chart-o"></span>&nbsp;사용자별 작업량 리포트</h2>
  </div>
 
  <!-- Example row of columns -->
  <div class="row">
    <div class="col-md-12">
      
      <!-- filter -->
      <div class="well well-sm">
        <form method="post" id="filter-form" class="form-inline" role="search">
          <input type="hidden" name="csrf_test_name" value="<?php echo $this->security->get_csrf_hash();?>">
          
          <div class="form-group"> 검색기간: </div>

          <div class="form-group">
            <input type="text" id="fromDate" name="fromDate" class="form-control input-sm date-picker">
            <!-- <span class="input-group-addon btn_date"><i class="fa fa-calendar"></i></span> -->
          </div>

          <div class="form-group"> ~ </div>
          <div class="form-group">
            <input type="text" id="toDate" name="toDate" class="form-control input-sm date-picker">
            <!-- <span class="input-group-addon btn_date"><i class="fa fa-calendar"></i></span> -->
          </div>

          <div class="form-group">
            <button type="submit" class="btn btn-primary btn-sm">검색</button> 
          </div>
        </form>
      </div>

      <table id="op_list" class="table table-hover table-bordered table-responsive">
        <thead>
          <tr class="active">
            <th>작업자</th>
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
            <td><?php echo $row['name']?></td>
            <td><?php echo $row[100]; ?></td>
            <td><?php echo $row[200]; ?></td>
            <td><?php echo $row[300]; ?></td>
            <td><?php echo $row[400]; ?></td>
            <td><?php echo $row[500]; ?></td>
            <td><?php echo $row[600]; ?></td>
            <td><?php echo $row[700]; ?></td>
            <td><?php echo $row[800]; ?></td>
            <td><?php echo $row[900]; ?></td>
            <td><?php echo $row['total'] ?></td>
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

<script type="text/javascript">
var fromDate = new Date("<?=$fromDate?>"); 
var toDate = new Date("<?=$toDate?>"); 

$(document).ready(function() {
  $("#fromDate").datepicker({
    dateFormat: "yy-mm-dd",
    changeYear: true,
    changeMonth: true
  });
  
  $("#toDate").datepicker({
    dateFormat: "yy-mm-dd",
    changeYear: true,
    changeMonth: true
  });

  // 기본 기간 설정
  $("#fromDate").datepicker('setDate', fromDate);
  $("#toDate").datepicker('setDate', toDate);

  ///////////////////////
  // 검색 필터 전송 
  ///////////////////////
  $("#filter-form").submit(function() {
    // var url = location.href;
    var url = _base_url + 'report/worker';
    var query = $(this).serialize();

    url = url + '/?' + query;
    $(this).prop('action', url);
  });
});
</script>

<?php
$this->view('layout/footer');
?>
