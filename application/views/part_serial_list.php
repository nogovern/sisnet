    <!-- start of div.container -->
    <div class="container">
      <!-- Main hero unit for a primary marketing message or call to action -->
      <div class="page-header">
        <h2><i class="fa fa-briefcase"></i> 시리얼 관리 장비 리스트</h2>
      </div>

      <!-- Example row of columns -->
      <div class="row">
        <div class="col-md-12">
          
        <table class="table table-hover">
          <thead>
            <tr>
              <th>No</th>
              <th>시리얼넘버</th>
              <th>장비종류</th>
              <th>모델명</th>
              <th>제조사명</th>
              <th>현재위치</th>
              <th>직전위치</th>
              <th>신품</th>
              <th>상태</th>
              <th>최초설치일</th>
              <th>입고일</th>
              <th>수정일</th>
              <th>메모</th>
            </tr>
          </thead>

          <tbody>
<?php
foreach($rows as $row):
?>
            <tr class="">
              <td><?=$row->id?></td>
              <td><?=$row->getSerialNumber()?></td>
              <td><?=$row->part->category->name?></td>
              <td><?=$row->part->name?></td>
              <td><?=$row->part->manufacturer?></td>
              <td><?=$row->getCurrentLocation()?></td>
              <td><?=$row->getPreviousLocation()?></td>
              <td><?=$row->isNew() ? '신품' : '중고'?></td>
              <td><?=$row->status?></td>
              <td><?=$row->getDateInstall()?></td>
              <td><?=$row->getDateEnter()?></td>
              <td><?=$row->getDateModify()?></td>
              <td><?=$row->memo?></td>
            </tr>
<?php
endforeach;
?>
          </tbody>

        </table>

        <p>
          <a href="/admin/part/serial_add"><span class="btn btn-primary"><i class="icon-pencil"></i> 장비 등록</span></a>
          <button class="btn btn-success" data-target="#myModal" data-toggle="modal"><i class="icon-pencil"></i>&nbsp;팝업 등록</button>
        </p>

        </div>
      </div>
    </div><!-- end of container -->
    

    <!-- Modal -->
    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h3 class="modal-title" id="myModalLabel">시리얼 관리 장비 수동 입고 양식</h3>
          </div>
          <div class="modal-body">
            <form id="myform" method="post" role="form" class="form-horizontal">
              <div class="form-group">
                <label class="col-sm-3 control-label">입고 사무소</label>
                <div class="col-sm-7">
                  <input class="form-control" type="text" name="office_id" placeholder="...">
                </div>
              </div>

              <div class="form-group">
                <label class="col-sm-3 control-label">장비 <small class="text-danger">시리얼장비만 가능</small></label>
                <div class="col-sm-7">
                  <input class="form-control" type="text" name="part_id" placeholder="">
                </div>
              </div>
                            
              <div class="form-group">
                <label class="col-sm-3 control-label">시리얼넘버</label>
                <div class="col-sm-7">
                  <input class="form-control" type="text" name="serial_number" placeholder="" required>
                </div>
              </div>
                            
              <div class="form-group">
                <label class="col-sm-3 control-label">최초 입고일</label>
                <div class="col-sm-7">
                  <input class="form-control" type="text" name="date_enter" placeholder="">
                </div>
              </div>
                            
              <div class="form-group">
                <label class="col-sm-3 control-label">신품 여부</label>
                <div class="col-sm-7">
                  <input class="form-control" type="text" name="is_new" placeholder="">
                </div>
              </div>
                            
              <div class="form-group">
                <label class="col-sm-3 control-label">메  모</label>
                <div class="col-sm-7">
                  <input class="form-control" type="text" name="memo" placeholder="">
                </div>
              </div>
                            
            </form>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary" id="btn_apply">Save changes</button>
          </div>
        </div><!-- /.modal-content -->
      </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

    <script type="text/javascript">
    $(document).ready(function(){

      // ajax request
      $("#btn_apply").click(function(){
        var post_data = $("#myform").serialize(); 
      
        $.ajax({
          url: "/admin/part/ajax_serial_add",
          type: "POST",
          data: {
            data : post_data,
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
        //$("#myModal").modal("hide");
      });
    });
    </script>