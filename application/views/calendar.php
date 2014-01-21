<?php
// 타이틀 설정
$title = (isset($title)) ? $title : '----- title is not defined ----';

$this->load->view('layout/header', array('title' => "$title"));
$this->load->view('layout/navbar', array('current' => "page-schedule"));
?>
	<!-- start of div.container -->
    <div class="container">
		<!-- Main hero unit for a primary marketing message or call to action -->
		<div class="page-header">
			<h1><span class="fa fa-calendar"></span>&nbsp;일정표</h1>
		</div>

		<?php
		echo $calendar; 
		?>
  	</div><!-- end of calendar -->
  	<script type="text/javascript">
  	$(document).ready(function(){
  		$("#calendar tr:eq(1) th").each(function(){
  			$(this).css('background-color', '#EEE');
  		});

      $(".highlight").closest('td').addClass('active');

  		$("#calendar tbody td").hover(
  			function(){
  				$(this).addClass('success');
  			},
  			function() {
  				$(this).removeClass('success');
  			}
  		);
  	});
  	</script>
<?php
$this->load->view('layout/footer');
?>