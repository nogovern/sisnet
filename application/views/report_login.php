<?php
/**
 * 리포트 - 사용자 접속 기록
 */
$this->view('layout/header');
$this->view('layout/navbar');

?>
<!-- start of div.container -->
<div class="container">
  
  <div class="page-header">
    <h2><span class="fa fa-bar-chart-o"></span>&nbsp;사용자 접속 기록</h2>
  </div>
 
  <!-- Example row of columns -->
  <div class="row">
    <div class="col-md-12">
      
      <!-- filter -->
      <div class="well well-sm">
        <form method="post" id="filter-form" class="form-inline" role="search">
          <input type="hidden" name="csrf_test_name" value="<?php echo $this->security->get_csrf_hash();?>">
          
          <div class="form-group"> 이름 </div>
          <div class="form-group">
            <input type="text" id="name" name="name" class="form-control input-sm">
          </div>

          <div class="form-group"> &nbsp;&nbsp;&nbsp;검색기간 </div>
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

      <table id="report_table" class="table table-hover table-bordered table-responsive">
        <thead>
          <tr class="" style="background-color: #FFE146;">
            <th class="col-xs-1 text-center">No</th>
            <th class="col-xs-2 text-center">아이디</th>
            <th class="col-xs-2 text-center">이름</th>
            <th class="col-xs-2 text-center">접속 IP</th>
            <th class="col-xs-5 text-center">로그인 시간</th>
          </tr>
        </thead>

        <tbody>
<?php
if(!count($rows)) {
?>
          <tr class="" style="text-align: center;">
            <td colspan="5">결과가 없습니다</td>
          </tr>

<?php
} else {
  foreach($rows as $row):
?>
          <tr class="" style="text-align: center;">
            <td><?php echo $row->id?></td>
            <td><?php echo $row->user->username?></td>
            <td><?php echo $row->user->name?></td>
            <td><?php echo $row->ip_address?></td>
            <td><?php echo $row->date_login->format("Y-m-d h:i:s"); ?></td>
          </tr>
<?php
  endforeach;
}
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
    maxDate: '0',             // 최대치는 오늘
    dateFormat: "yy-mm-dd",
    changeYear: true,
    changeMonth: true
  });
  
  $("#toDate").datepicker({
    maxDate: '0',             // 최대치는 오늘
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
    var url = _base_url + 'report/log';
    var query = $(this).serialize();

    url = url + '/?' + query;
    $(this).prop('action', url);
  });
});
</script>

<?php
$this->view('layout/footer');
?>
