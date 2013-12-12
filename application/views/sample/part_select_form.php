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
          <div class="col-xs-5">
            <div class="form-group">
              <label for="select_category">장비 종류 선택</label>
              <select id="select_category" class="form-control">
                <option vlaue="0"></option>
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
          <div class="col-xs-5">
            <div class="form-group">
              <label for="select_part">장비 선택</label>
              <select id="select_part" class="form-control"></select>
            </div>
          </div>

          <!-- 수량 입력 -->
          <div class="col-xs-2">
            <div class="form-group">
              <label for="inputqty">수 량</label>
              <input type="text" class="form-control" placeholder="수량">
            </div>
          </div>
        </div>

      </form>

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

    <!-- jquery form validation -->
    <link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css">
    <script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>

    <script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.min.js"></script>

    <script type="text/javascript">
    $(document).ready(function(){
      $("#select_category").change(function(){
        var cat = $(":selected",this).val();
        if( cat == ''){
          return false;
        } else {
          var target_url = "<?=site_url('ajax/response/')?>" + '/' + cat;
        }

        $.ajax({
          url: target_url,
          type: "POST",
          data: {
            "category_id": cat,
            "extra": "test"
          },
          dataType: "html",
        })
          .done(function(html) {
            if(html == 1000){
              alert('error : 해당 카테고리에 등록된 장비가 없어요');
            } else {
              // console.log(html);
              $("#select_part").append(html);
            }
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