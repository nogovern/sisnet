<?php
// 타이틀 설정
$title = (isset($title)) ? $title : '----- title is not defined ----';

$this->load->view('layout/header_popup', array('title' => "$title"));
?>

<!-- start of div.container -->
<div class="container">

  <!-- Example row of columns -->
  <div class="row">
    <h4>점포 검색 결과 : <?=count($rows)?> 건</h4>
    <table class="table table-hover">
      <thead>
        <tr>
          <th></th>
          <th>점포명</th>
          <th>점주</th>
          <th>주소</th>
          <th>연락처</th>
          <th>상태</th>
          <th>선택</th>
        </tr>
      </thead>
      <tbody>
<?php
if(!count($rows)) {
?>
        <tr>
          <td colspan="6">결과가 없습니다.</td>
        </tr>

<?php
} else {
  $status_text = array('페점', '정상', '휴점C', '휴점S' );
  foreach($rows as $row):
?>
        <tr>
          <td><?=$row->id?></td>
          <td><?=$row->name?></td>
          <td><?=$row->owner_name?></td>
          <td><?=$row->address?></td>
          <td><?=$row->tel?></td>
          <td><?=$status_text[$row->status]?></td>

          <td><a href="#" class="select_me">[선택]</a></td>
        </tr>
<?php
  endforeach;
}
?>
      </tbody>
    </table>
    <button type="button" class="btn btn-primary" id="btn_add_store">점포 신규 등록</button>
    <!--
    <button type="button" class="btn btn-default" id="btn_dialog_open">점포 신규 등록</button>
    -->
    <button type="button" class="btn btn-default" id="btn_close">닫기</button>
  </div>
<!-- start of div.container -->
</div>

<!-- jquery form validation -->
<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
<script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.min.js"></script>

<script type="text/javascript">
$(document).ready(function(){

  $(".select_me").click(function(e){
    e.preventDefault();
    
    // 상점 id, name
    var store_id = $(this).closest('tr').find('td:eq(0)').text();
    var store_name = $(this).closest('tr').find('td:eq(1)').text();

    // callback 함수 사용하여 부모창 element 에 설정
    parent.callback_store_info(store_id, store_name);
    // colorbox close
    parent.jQuery.fn.colorbox.close();
  });

  // colorbox
  $("#btn_add_store").click(function(){
    parent.callback_store_register();
  });

  $("#btn_close").click(function(){
    parent.jQuery.fn.colorbox.close();
  });

  // jquery-ui dialog 방식
  $("#btn_dialog_open").click(function(e){
    $("#dialog-form2").dialog("open");
  });

  $("#dialog-form2").dialog({
    autoOpen: false,
    modal: true,
    width: 600,
    height: "600",
    open: function(ev, ui) {
      $("#inner_frame").attr('src', '<?=site_url("/admin/store/register")?>');
    },
    buttons: {
      "저장": function() {
        $(this).dialog('close');
      },
      "닫기": function() {
        $(this).dialog('close');
      }
    }
  });
  
});
</script>

<div id="dialog-form2" title="점포 등록">
  <iframe id="inner_frame" src="" style="width:550px;height:580px;"></iframe>
</div>

<?php
$this->load->view('layout/footer');
?>