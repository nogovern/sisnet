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
  foreach($rows as $row):
?>
        <tr>
          <td><?=$row->id?></td>
          <td><?=$row->name?></td>
          <td><?=$row->owner_name?></td>
          <td><?=$row->address?></td>
          <td><?=$row->tel?></td>
          <td><a href="#" class="select_me">[선택]</a></td>
        </tr>
<?php
  endforeach;
}
?>
      </tbody>
    </table>
    <button type="button" class="btn btn-primary" id="btn_add_store">점포 신규 등록</button>
    <button type="button" class="btn btn-default" id="btn_colorbox">colorbox</button>
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
  $("#btn_colorbox").click(function(){
    var url = '<?=site_url("/admin/store/add")?>';

    $.colorbox({
      'href'  : url,
      'iframe'  : true,
      'opacity' : 0.5,
      'width'   : '100%',
      'height'  : '100%'
    });
  });

  $("#btn_add_store").click(function(e){
    $("#dialog-form2").dialog("open");
  });

  $("#dialog-form2").dialog({
    autoOpen: false,
    modal: true,
    width: 600,
    height: "600",
    open: function(ev, ui) {
      $("#inner_frame").attr('src', '<?=site_url("/admin/store/add")?>');
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

<!-- modal dialog -->
<div class="modal fade" id="modal_add_store" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title">#example</h4>
      </div>
      <!-- start form -->
      <form id="form_modal_memo" role="form" class="form form-horizontal">
      <div class="modal-body">
        <div class="form-group">
          <label class="form-label col-sm-4">작업 메모</label>
          <div class="col-sm-7">
            <textarea name="memo" class="form-control"></textarea>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button id="modal_memo_ok" type="submit" class="btn btn-primary">입력</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">닫기</button>
      </div>
      </form>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div id="dialog-form2" title="점포 등록">
  <iframe id="inner_frame" src="" style="width:550px;height:580px;"></iframe>
</div>

<?php
$this->load->view('layout/footer');
?>