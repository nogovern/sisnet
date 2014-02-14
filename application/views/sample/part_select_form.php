<?php
// 타이틀 설정
$title = (isset($title)) ? $title : '----- title is not defined ----';

$this->load->view('layout/header_popup', array('title' => "$title"));
?>

    <!-- start of div.container -->
    <div class="container">
      <h2><span class="fa fa-pencil-square-o"></span> 장비를 선택하세요</h2>
      <form name="my_form" mehtod="post" role="form" action="">
        <div class="row">
          <!-- 장비 종류 선택 -->
          <div class="col-xs-4">
            <div class="form-group">
              <label for="select_category">장비 종류 선택</label>
              <select id="select_category" class="form-control">
                <option value="">--선택하세요--</option>
<?php
foreach($cats as $cat):
?>
                <option value="<?=$cat->id?>"><?=$cat->name?></option>
<?php
endforeach;
?>
              </select>
            </div>
          </div>

          <!-- 장비 선택 -->
          <div class="col-xs-4">
            <div class="form-group">
              <label for="select_part">장비 선택</label>
              <select id="select_part" class="form-control"></select>
            </div>
          </div>

          <!-- 수량 입력 -->
          <div class="col-xs-1">
            <div class="form-group">
              <label for="input_qty">수 량</label>
              <input type="text" class="form-control" id="input_qty" placeholder="수량">
            </div>
          </div>

          <div class="col-xs-3">
            <div style="margin-top: 25px;"><button type="button" class="btn btn-warning btn-small" id="btn_add" disabled>Add</button></div>
          </div>
        </div>

      </form>

      <div class="row">
        <div class="col-lg-12">
          <table class="table table-striped table-condensed" id="part_table">
            <thead>
              <tr>
                <th>#</th>
                <th>장비종류</th>
                <th>장비명</th>
                <th>수량</th>
                <th></th>
              </tr>
            </thead>
            <tbody></tbody>
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

    <script type="text/javascript">
    var btn_enabled = false;
    var tbl_idx = 1; 

    $(document).ready(function(){
      // 장비 종류 선택 시 장비 목록 가져오기
      $("#select_category").change(function(){
        var cat = $(":selected",this).val();
        // console.log(cat);
        if( cat == ''){
          $("#select_part").html('');
          return false;
        } else {
          var target_url = "<?=site_url('ajax/response/')?>" + '/' + cat;
        }

        // ajax request
        $.ajax({
          url: target_url,
          type: "POST",
          data: {
            "category_id": cat,
            "extra": "test",
            "csrf_test_name": $.cookie("csrf_cookie_name")
          },
          dataType: "html",
        })
          .done(function(html) {
            if(html == 1000){
              alert('error : 해당 카테고리에 등록된 장비가 없어요');
            } else {
              $("#select_part").html(html);
              $("#btn_add").prop("disabled", false);
            }
          })
          .fail(function(xhr, textStatus){
            alert("Request failed: " + textStatus);
          });
      });

      $("#btn_add").click(function(){
        var table = $("#part_table tbody");

        var $tr = $('<tr>').addClass('active');
        var $td = $("<td>").html(tbl_idx);
        $tr.append($td);
        $td = $("<td>").html($("#select_category :selected").text());
        $tr.append($td);
        $tr.append($("<td>").html($("#select_part :selected").text()));
        $tr.append($("<td>").html($("#input_qty").val()));

        if(window.console) {
          console.log($tr);
        }

        table.append($tr);
        tbl_idx++;
      });

    });
    </script>
<?php
$this->load->view('layout/footer');
?>