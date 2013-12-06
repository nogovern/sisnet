
    <!-- start of div.container -->
    <div class="container">
      <!-- Main hero unit for a primary marketing message or call to action -->
      <div class="page-header">
        <h1><span class="fa fa-desktop"></span>&nbsp;입고 업무</h1>
      </div>
     
      <!-- Example row of columns -->
      <div class="row">
        <div class="col-md-12">
          <ul class="nav nav-pills">
            <li class="<?=($type=='')?'active':''?>"><a href="#">전체</a></li>
            <li class=""><a href="#">처리중</a></li>
            <li class=""><a href="#">확인중</a></li>
            <li class=""><a href="#">완료</a></li>
          </ul>

          <table class="table table-hover">
            <thead>
              <tr>
                <th>No</th>
                <th>종류</th>
                <th>소속</th>
                <th>이름</th>
                <th>장비종류</th>
                <th>모 델</th>
                <th>납품처</th>
                <th>수량</th>
                <th>등록일</th>
                <th>요청일</th>
                <th>상태</th>
              </tr>
            </thead>

            <tbody>
  <?php
  foreach($rows as $row):
  ?>
              <tr class="">
                <td><?=$row->id?></td>
                <td><?=$row->type?></td>
                <td><?=$row->office->name?></td>
                <td><?=$row->user->name?></td>
                <!-- 장비 -->
                <td><?=$row->part_list[0]->part_category?></td>
                <td><?=$row->part_list[0]->name?></td>
                <td><?=$row->part_list[0]->part_id?></td>
                <td><?=$row->part_list[0]->qty?></td>
                <td><?=(is_object($row->date_register)) ? $row->date_register->format('Y-m-d H:i:s'): '';?></td>
                <td><?=(is_object($row->date_request)) ? $row->date_request->format('Y-m-d'): '';?></td>
                <td><?=$row->status?></td>
              </tr>
  <?php
  endforeach;
  ?>
            </tbody>

          </table>

          <p>
            <a class='iframe' href="<?=site_url('admin/user/form')?>" title="사용자를 등록하세요">iframe open</a>
          </p>
          <p>
            <a href="/admin/user/add"><span class="btn btn-primary"><i class="icon-pencil"></i>&nbsp;사용자 등록</span></a>
          </p>

        </div>
      </div>
    </div><!-- end of container -->

    <script type="text/javascript">
    $(document).ready(function(){
        $(".ajax").colorbox({'opacity': '0.6', 'width': '80%'});
        $(".iframe").colorbox({
          'iframe'  : true,
          'width'   : '50%',
          'height'  : '80%'
        });
    });
    </script>