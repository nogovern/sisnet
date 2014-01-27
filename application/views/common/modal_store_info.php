<!-- modal dialog -->
<div class="modal fade" id="modal_store_info" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h2 class="modal-title">점포 정보</h2>
      </div>
      <!-- .modal-body -->
      <div class="modal-body">
        
      </div><!-- /.modal-body -->
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">닫기</button>
      </div>
    </div>
  </div>
</div><!-- /.modal -->

<script type="text/javascript">
  // 점포 상세정보 modal 열기
  function openStoreInfo(store_id) {
    var sinfo_url = "<?=site_url("admin/store/showTableFormat")?>" + "/" + store_id;

    $("#modal_store_info .modal-title").text('점포 정보');
    $("#modal_store_info .modal-body").html('').load(sinfo_url, function(){
      $("#modal_store_info").modal('show');---
    });
  }
</script>