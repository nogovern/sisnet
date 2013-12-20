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
                <td><?=$work->getItem()->part->company->user?></td>
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
            <div class="panel-heading"><i class="fa fa-tags"></i> 장  비 </div>
            <div class="panel-body" style="padding:0 15px;">
              <table class="table table-hover table-bordred" id="part_table">
                <thead>
                  <tr>
                    <th>#</th>
                    <th>S/N</th>
                    <th>장비명</th>
                    <th>수량</th>
                    <th>기능</th>
                  </tr>
                </thead>
                <tbody>
                  <!-- sample -->
<?php
$qty = $work->getItem()->qty_request;
for($i=1; $i <= $qty; $i++):
?>                  
                  <tr>
                    <td><?=$i?></td>
                    <td><?=''?></td>
                    <td><?=$work->getItem()->part->name?></td>
                    <td><?='1'?></td>
                    <td style="width:150px;">
                      <button class="btn btn-danger btn-xs">reset</button>
                      <button class="btn btn-success btn-xs">input s/n</button>
                    </td>
                  </tr>
<?php
endfor;
?>                  
                </tbody>
              </table>
            </div>
          </div>
          <!-- end: ALERTS PANEL -->
        </div>
      </div>
      <div class="row">
        <div class="col-md-12">
          <button id="btn_next" class="btn btn-success btn-sm" type="button">요청확정</button>
          <button id="btn_cancel" class="btn btn-default btn-sm" type="button">리스트로...</button>
          <button id="btn_register" class="btn btn-warning btn-sm" type="button">개별등록</button>
          <button id="btn_delivery" class="btn btn-success btn-sm" type="button">출고</button>
        </div>
      </div>
          
    </div><!-- end of div.container -->
    
    <!-- dialog form -->
    <div id="dialog-form" title="납품요청확인">
      <p class="validateTips">All form fields are required.</p>
      <form name="dialog-form">
      <fieldset>
        <label for="name">납품예정일</label>
        <input type="text" name="name" id="name" class="text ui-widget-content ui-corner-all">
        <label for="email">Email</label>
        <input type="text" name="email" id="email" value="" class="text ui-widget-content ui-corner-all">
        <label for="date_request">납품예정일</label>
        <input type="date_request" name="date_request" id="date_request" value="" class="text ui-widget-content ui-corner-all">
      </fieldset>
      </form>
    </div>

    <!-- jquery form validation -->
    <link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css">
    <script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>

    <script type="text/javascript">
    $(document).ready(function(){
      
      // open jquery ui modal dialog
      $("#dialog-form").dialog({
        autoOpen:false,
        modal: true,
        buttons: {
          "저장": function() {
            var table = $("#part_table tbody");

            table.append( '<tr class="active">' +
              "<td>1111</td>" +
              "<td>1111</td>" +
              "<td>1111</td>" +
              "<td>1111</td>" +
              "<td>1111</td>" +
            "</tr>");
            $(this).dialog("close");
          },
          "닫기": function() {
            $(this).dialog("close");
          }
        }
      });

      $("#btn_next").click(function(){
        $("#dialog-form").dialog("open");
      });

    });
    </script>
<?php
$this->load->view('layout/footer');
?>