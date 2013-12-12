<?php
// 타이틀 설정
$title = (isset($title)) ? $title : '----- title is not defined ----';

$this->load->view('layout/header_popup', array('title' => "$title"));
?>

    <!-- start of div.container -->
    <div class="container">
    
      <!-- Example row of columns -->
      <div class="row">
        <h2><span class="fa fa-pencil-square-o"></span> 입고 요청 양식</h2>
        <div class="col-md-8">
          <form name="my_form" mehtod="post" role="form" action="">
<?php 
/*=============================================
hidden 값은 로그인 session 을 사용 해야 함!
*=============================================
*/
echo form_hidden('type', '101');
echo form_hidden('office_id', '');      // 사무소
echo form_hidden('user_id', '');        // 사용자
?>
          <!-- <form role="form"> -->

            <!-- 장비 선택-->
            <div class="form-group">
              <label for="part_id" class="control-label">장비 선택</label>
              <?php
              // echo $form_part_select;
              ?>
            </div>

            <div class="form-group">
              <label class="control-label">입고 예정일 <small class="text-danger">입고 희망 날짜</small></label>
              <div class="input-group">
                  <input type="text" id="date_request1" name="date_request1" class="form-control date-picker">
                  <span class="input-group-addon" id="btn_date1"><i class="fa fa-calendar"></i></span>
              </div>
            </div>

            <div class="form-group">
              <label for="part_name">장비명</label>
              <input type="text" class="form-control" id="part_name" name="part_name" value="" palceholder="지정값">
            </div>

            <div class="form-group">
              <label for="qty">수량</label>
              <input type="text" class="form-control" id="qty" name="qty" placeholder="수량을 입력하세요">
            </div>

            <div class="form-group">
              <label for="memo">메 모  <small class="text-success">간단한 추가 요청 사항</small></label>
              <textarea name="memo" id="memo" rows="2" class="form-control"></textarea>
            </div>

            <p class="form-actions">
              <button class="btn btn-primary" type="submit">입력완료</button>
              <button id="ajax" class="btn btn-danger" type="button">다른 종류</button>
            </p>
          </form>
        </div>
        <div class="col-md-4">
          <div class="panel panel-primary">
            <div class="panel-heading">col-md-4</div>
            <div class="panel-body">
              입력하세요
            </div>
          </div>
        </div>
      </div><!-- end of row -->

      <div class="row">
        <div class="col-lg-12">
          <table class="table table-striped table-condensed" id="part_table">
            <thead>
              <tr>
                <th>#</th>
                <th>col1</th>
                <th>col2</th>
                <th>col3</th>
                <th>col4</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>row1</td>
                <td>row2</td>
                <td>row3</td>
                <td><button class="btn btn-success" id="create-user">Create new user</button></td>
                <td><button class="btn btn-default" id="btn_modal" data-target="#b3_modal" data-toggle="modal">modal popup</button></td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

    </div><!-- end of div.container -->

    <!-- modal contents -->
    <div class="modal fade" id="b3_modal">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title">Modal title</h4>
          </div>
          <div class="modal-body">
            <p>One fine body&hellip;</p>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary">Save changes</button>
          </div>
        </div><!-- /.modal-content -->
      </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

    
    <div id="dialog-form" title="Create new user">
      <p class="validateTips">All form fields are required.</p>
      <form name="dialog-form">
      <fieldset>
        <label for="name">Name</label>
        <input type="text" name="name" id="name" class="text ui-widget-content ui-corner-all">
        <label for="email">Email</label>
        <input type="text" name="email" id="email" value="" class="text ui-widget-content ui-corner-all">
        <label for="password">Password</label>
        <input type="password" name="password" id="password" value="" class="text ui-widget-content ui-corner-all">
      </fieldset>
      </form>
    </div>

    <!-- jquery form validation -->
    <link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css">
    <script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>

    <script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.min.js"></script>

    <script type="text/javascript">
    $(document).ready(function(){
      // datepicker...
      $(".date-picker").each(function(){
        $(this).datepicker({
          dateFormat: "yy-mm-dd",
          minDate: new Date(),
          changeMonth: true,
          changeYear: true
        });
      });

      // datepicker 이벤트
      $("#btn_date1").click(function(){
        $(".date-picker").datepicker("show");
      });

      // open bootstrap modal
      $("#b3_modal").click(function(){
        // $(this).modal("show");
      });

      // open jquery ui modal dialog
      $("#dialog-form").dialog({
        autoOpen:false,
        modal: true,
        buttons: {
          "Create an account": function() {
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
          Cancel: function() {
            $(this).dialog("close");
          }
        }
      });

      $("#create-user").click(function(){
        $("#dialog-form").dialog("open");
      });

    });
    </script>
<?php
$this->load->view('layout/footer');
?>