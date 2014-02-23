<?php
// 타이틀 설정
$title = (isset($title)) ? $title : '----- title is not defined ----';

$this->load->view('layout/header', array('title' => "$title"));
$this->load->view('layout/navbar', array('current' => "page-schedule"));
?>
	<!-- start of div.container -->
    <div class="container">
  		<div class="page-header">
  			<h1><span class="fa fa-calendar"></span>&nbsp;일정표</h1>
  		</div>

      <div class="well well-sm">
        <form method="post" id="filter-form" class="form-inline" role="search">
          <input type="hidden" name="csrf_test_name" value="<?php echo $this->security->get_csrf_hash();?>">

          <div class="form-group">
            업무구분 : 
            <?php echo $op_category_filter; ?>
          </div>

          <div class="form-group">
            업무형태 : 
            <?php echo $op_type_filter; ?>
          </div>

          <div class="form-group">
            &nbsp;&nbsp;사무소 : 
            <?php echo $office_filter; ?>
          </div>

          <div class="form-group">
            <button type="submit" class="btn btn-primary btn-sm">검색</button> 
          </div>
        </form>
      </div>
<?php
		echo $calendar; 
?>
  	</div><!-- end of container -->


  	<script type="text/javascript">
    var sel_type = <?=$selected_type?>;

  	$(document).ready(function(){
  		$("#calendar tr:eq(1) th").each(function(){
  			$(this).css('background-color', '#EEE');
  		});

      $(".highlight").closest('td').addClass('active');

      // 휴일 표시
      $("#calendar tbody tr td:nth-child(1)").css('color', 'red');
      $("#calendar tbody tr td:nth-child(7)").css('color', 'red');

  		$("#calendar tbody td").hover(
  			function(){
  				$(this).addClass('success');
  			},
  			function() {
  				$(this).removeClass('success');
  			}
  		);

      ///////////////////////// 
      // 업무 종류 변경시
      /////////////////////////
      $("#op_category").change(function(){
        var val = $(this).val();

        $.ajaxSetup({
          async: false
        });
        
        // 업무 형태 option 설정
        $.getJSON(_base_url + 'ajax/get_operation_type/' + val, function(data) {
          gs2_console(data);

          // 받은 json 데이터로 옵션 생성 
          $("#op_type").empty();
          for(var idx in data) {
            $("#op_type").append('<option value="' + idx + '">' + data[idx] + '</option>');
          }
        });
      }).change(); 

      if(sel_type > 0) {
        $("#op_type").val(sel_type).change();
      }

      ///////////////////////
      // 검색 필터 전송 
      ///////////////////////
      $("#filter-form").submit(function() {
        // var url = location.href;
        var url = _base_url + 'schedule';
        var query = $(this).serialize();

        url = url + '/?' + query;
        $(this).prop('action', url);

        // alert(url);
        // return false;
      });
  	});
  	</script>
<?php
$this->load->view('layout/footer');
?>