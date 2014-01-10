<?php
$this->load->view('layout/header', array('title' => '관리자 >> 점포 >> 신규 등록'));
$this->load->view('layout/navbar', array('current' => 'page-admin-store'));

$this->load->view('form/store_register_form');
?>

<script type="text/javascript">
$(document).ready(function(){

  // 실제 validation 후 submit 구현은
  // doSubmit 함수를 정의한다.
  doSubmit = function(form) {
    form.submit();      
  };
});
</script>
<?php
$this->load->view('layout/footer');
?>