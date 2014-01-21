<!-- modal dialog -->
<div class="modal fade" id="modal_store_info" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h2 class="modal-title">점포 정보</h2>
      </div>
      <!-- start form -->
      <div class="modal-body">
        <table id="store_info_table" class="table table-bordered">
          <tbody>
            <tr>
              <td class="col-xs-3">점포명</td>
              <td class="col-xs-9" colspan="3" id="sinfo_name"></td>
            </tr>
            <tr>
              <td class="col-xs-3">점포코드</td>
              <td class="col-xs-3" id="sinfo_code"></td>
              <td class="col-xs-3">점포가변코드</td>
              <td class="col-xs-3" id="sinfo_code2"></td>
            </tr>
            <tr>
              <td class="col-xs-3">점주 이름</td>
              <td class="col-xs-3" id="sinfo_owner_name"></td>
              <td class="col-xs-3">전주 연락처</td>
              <td class="col-xs-3" id="sinfo_owner_tel"></td>
            </tr>
            <tr>
              <td class="col-xs-3">주소</td>
              <td class="col-xs-3" id="sinfo_addr"></td>
              <td class="col-xs-3">전화번호</td>
              <td class="col-xs-3" id="sinfo_tel"></td>
            </tr>
            <tr>
              <td class="col-xs-3">RFC 이름</td>
              <td class="col-xs-3" id="sinfo_rfc_name"></td>
              <td class="col-xs-3">RFC 연락처</td>
              <td class="col-xs-3" id="sinfo_rfc_tel"></td>
            </tr>
            <tr>
              <td class="col-xs-3">OFC 이름</td>
              <td class="col-xs-3" id="sinfo_ofc_name"></td>
              <td class="col-xs-3">OFC 연락처</td>
              <td class="col-xs-3" id="sinfo_ofc_tel"></td>
            </tr>
            <tr>
              <td class="col-xs-3">가입형태</td>
              <td class="col-xs-3" id="sinfo_join_type"></td>
              <td class="col-xs-3">무인택배함 설치</td>
              <td class="col-xs-3" id="sinfo_postbox"></td>
            </tr>
            <tr>
              <td class="col-xs-3">상태</td>
              <td class="col-xs-3" id="sinfo_status"></td>
              <td class="col-xs-3"></td>
              <td class="col-xs-3"></td>
            </tr>
          </tbody>
        </table>
      </div><!-- /.modal-body -->
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">닫기</button>
      </div>
    </div>
  </div>
</div><!-- /.modal -->

<script type="text/javascript">
  function openStoreInfo(store_id) {
    $.ajax({
      url: "<?=base_url()?>ajax/store_info/" + store_id,
      type: "POST",
      async: false,
      data: {
        "store_id": store_id,         
        "csrf_test_name": $.cookie("csrf_cookie_name")
      },
      dataType: "json",
    })
      .done(function(response) {
        $("#sinfo_name").text(response.name);
        $("#sinfo_code").text(response.code);
        $("#sinfo_code2").text(response.code2);
        $("#sinfo_owner_name").text(response.owner_name);
        $("#sinfo_rfc_name").text(response.rfc_name);
        $("#sinfo_rfc_tel").text(response.rfc_tel);
        $("#sinfo_ofc_name").text(response.ofc_name);
        $("#sinfo_ofc_tel").text(response.ofc_tel);
        $("#sinfo_join_type").text(response.join_type);
        $("#sinfo_postbox").text(response.has_postbox);

        // modal open
        $("#modal_store_info").modal('show');
        
        // debug
        if(window.console){
          console.log(response);
        }
      })
      .fail(function(xhr, textStatus){
        alert("Request failed: " + textStatus);
      });
  }
</script>