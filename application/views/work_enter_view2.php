<?php
$this->load->view('layout/header', array('title' => '입고 >> 입고 요청 보기'));
$this->load->view('layout/navbar', array('current' => 'page-work-enter'));

?>

    <!-- start of div.container -->
    <div class="container">
      <div class="page-header">
        <h1><span class="fa fa-desktop"></span>&nbsp;입고 업무</h1>
      </div>
      <div class="row">
        <div class="col-sm-4">
          <table class="table table-condensed table-hover">
            <thead>
              <tr>
                <th colspan="3">주문 정보</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>주문번호</td>
                <td><?=$work->operation_number?></td>
                <td>&nbsp;</td>
              </tr>
              <tr>
                <td>요청자</td>
                <td><?=$work->user->name?></td>
                <td>&nbsp;</td>
              </tr>
              <tr>
                <td>입고장소</td>
                <td><?=$work->office->name?></td>
                <td>&nbsp;</td>
              </tr>
              <tr>
                <td>등록일</td>
                <td><?=$work->date_register->format("Y-m-d H:i:s")?></td>
                <td>&nbsp;</td>
              </tr>
              <tr>
                <td>작업요청일</td>
                <td><?=$work->date_request->format("Y-m-d H:i:s");?></td>
                <td>&nbsp;</td>
              </tr>
              <tr>
                <td>진행상태</td>
                <td><?=$work->status?></td>
                <td>&nbsp;</td>
              </tr>
            </tbody>
          </table>
        </div>
        <div class="col-sm-4">
          <table class="table table-condensed table-hover">
            <thead>
              <tr>
                <th colspan="3">납품처 정보</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>납품처</td>
                <td><?=$work->getWorkLocation();?></td>
                <td>&nbsp;</td>
              </tr>
              <tr>
                <td>납품 담당자</td>
                <td><?=$work->getItem()->part->company->user->name?></td>
                <td>&nbsp;</td>
              </tr>
              <tr>
                <td>연락처</td>
                <td><?=$work->getItem()->part->company->tel?></td>
                <td>&nbsp;</td>
              </tr>
              <tr>
                <td>주  소</td>
                <td><?=$work->getItem()->part->company->address?></td>
                <td>&nbsp;</td>
              </tr>
            </tbody>
          </table>
        </div>

        <div class="col-sm-4">
          <table class="table table-condensed table-hover">
            <thead>
              <tr>
                <th colspan="3">장비 정보</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>장비 종류 </td>
                <td><?=$work->getItem()->part->category->name?></td>
                <td>&nbsp;</td>
              </tr>
              <tr>
                <td>장비 구분</td>
                <td><?=$work->getItem()->part->type?></td>
                <td>&nbsp;</td>
              </tr>
              <tr>
                <td>장비 모델명</td>
                <td><?=$work->getItem()->part->name?></td>
                <td>&nbsp;</td>
              </tr>
              <tr>
                <td>요청 수량</td>
                <td><?=$work->getItem()->qty_request?> 개</td>
                <td>&nbsp;</td>
              </tr>
              <tr>
                <td>장비 상태</td>
                <td><?=$work->getItem()->isNew() ? '신품' : '중고'?></td>
                <td>&nbsp;</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div><!-- end of row -->

      <div class="row">
        <div class="col-md-12">
          <!-- start: ALERTS PANEL -->
          <div class="panel panel-primary">
            <div class="panel-heading"><i class="fa fa-tags"></i>출고 대기 리스트</div>
            <div class="panel-body" style="padding:0 15px;">
              <table class="table table-hover" id="part_table">
                <thead>
                  <tr>
                    <th>#</th>
                    <th>장비명</th>
                    <th>S/N</th>
                    <th>등록수량</th>
                    <th>삭제</th>
                  </tr>
                </thead>
                <tbody>
                  <!-- sample -->
                  <tr data-temp_id="">
                    <td>-</td>
                    <td><?=$work->getItem()->part->name?></td>
                    <td></td>
                    <td>1</td>
                    <td style="width:150px;">
                      <button class="btn btn-danger btn-xs btn_delete" type="button">X</button>
                    </td>
                  </tr>
             
                </tbody>
              </table>
            </div>
          </div>
          <!-- end: ALERTS PANEL -->
        </div>
      </div>
      <div class="row">
        <div class="col-md-12">
<?php
if($work->status == 1):
?>
          <button id="btn_request_ok" class="btn btn-success" type="button">요청확정</button>
<?php
endif;
?>
          <button id="btn_cancel" class="btn btn-default" type="button">리스트로...</button>

<?php
if($work->status == 2):
?>
          <button id="btn_register" class="btn btn-warning btn_add" type="button">개별등록</button>
          <button id="btn_delivery" class="btn btn-success btn_delivery" type="button"  disabled>출고</button>
<?php
endif;
?>
        </div>
      </div>
          
    </div><!-- end of div.container -->
    
    <!-- dialog form -->
    <div id="dialog-form" title="납품요청확인">
      <div class="row">
      <form id="my_form" role="form" class="form">
        <div class="form-group">
          <label class="form-label col-sm-3">값</label>
          <input id="my_val" class="form-control" name="value" id="value">
        </div>
      </form>
      </div>
    </div>

    <!-- jquery form validation -->
    <link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css">
    <script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>

    <script type="text/javascript">
    var _item_total = <?=$work->getItem()->qty_request?>;   // 요청 장비 수량

    $(document).ready(function(){
      
      // open jquery ui modal dialog
      $("#dialog-form").dialog({
        autoOpen:false,
        modal: true,
        buttons: {
          "저장": function() {
            $.ajax({
              url: "/work/enter/ajax/temp_add",
              type: "POST",
              data: {
                id : <?=$work->id?>,
                val: $("#my_val").val(),
                "csrf_test_name": $.cookie("csrf_cookie_name")
              },
              dataType: "html",
            })
              .done(function(html) {
                $("#part_table tbody").append(html);
                alert(html);
                console.log(html);
              })
              .fail(function(xhr, textStatus){
                alert("Request failed: " + textStatus);
              });
            $(this).dialog("close");
          },
          "닫기": function() {
            $(this).dialog("close");
          }
        }
      });

      // 요청 확정
      $("#btn_request_ok").click(function(){
        var is_ok = confirm("요청 하시겠습니까?\n그리고 입고예정일 확인해야함.");

        if(is_ok == true){
          $.ajax({
            url: "/work/enter/ajax/request_ok",
            type: "POST",
            data: {
              id : <?=$work->id?>,
              "csrf_test_name": $.cookie("csrf_cookie_name")
            },
            dataType: "html",
          })
            .done(function(html) {
              alert(html);
              location.reload();
            })
            .fail(function(xhr, textStatus){
              alert("Request failed: " + textStatus);
            });
        }// end of if
      });

      // 장비 시리얼 등록
      var items = [];
      $(".btn_add").click(function(){
          $("#dialog-form").dialog('open');
      });

      // 리스트 장비 삭제
      $(document).on("click", ".btn_delete", function(e){
        console.log($(this).parent().html());
        console.log($(this).parent().parent().data('temp_id'));
        return false;
        if(!confirm('목록에서 삭제 할까요?')){
          return false;
        }

        $.ajax({
          url: "/work/enter/ajax/temp_delete",
          type: "POST",
          data: {
            id : <?=$work->id?>,
            temp_id: $(this).parent().parent().data('temp_id'),
            "csrf_test_name": $.cookie("csrf_cookie_name")
          },
          dataType: "html",
        })
          .done(function(html) {
            alert(html);
          })
          .fail(function(xhr, textStatus){
            alert("Request failed: " + textStatus);
          });
      });
      
      // 출고
      $("#btn_delivery").click(function(){
        if(items.length == 0) {
          alert('최소 1개 이상의 장비 정보를 입력해야 합니다');
          return false;
        }

        var msg = _item_total + '/' + items.length + '를 등록하였습니다.\n출고진행?';
        if(!confirm(msg)) {
          return false;
        }

        $.ajax({
            url: "/work/enter/ajax/delivery",
            type: "POST",
            data: {
              id : <?=$work->id?>,
              items : items.toString(),
              "csrf_test_name": $.cookie("csrf_cookie_name")
            },
            dataType: "html",
          })
            .done(function(html) {
              alert(html);
              // location.reload();
            })
            .fail(function(xhr, textStatus){
              alert("Request failed: " + textStatus);
            });
      });

    });
    </script>
<?php
$this->load->view('layout/footer');
?>