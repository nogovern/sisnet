<?php
$this->load->view('layout/header', array('title' => '입고 >> 입고 리스트'));
$this->load->view('layout/navbar', array('current' => 'page-enter'));
?>
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
                <th>요청자</th>
                <th>입고위치</th>
                <th>장비종류</th>
                <th>모 델</th>
                <th>납품처</th>
                <th>수량</th>
                <th>등록일</th>
                <th>요청일</th>
                <th>상태</th>
                <th>메모</th>
                <th>&nbsp;</th>
              </tr>
            </thead>

            <tbody>
  <?php
  foreach($rows as $row):
  ?>
              <tr class="">
                <td><?=$row->id?></td>
                <td><?=$row->type?></td>
                <td><?=$row->user->name?></td>
                <td><?=$row->office->name?></td>
                <!-- 장비 -->
                <td><?=$row->items[0]->part->category->name?></td>
                <td><?=$row->items[0]->part->name?></td>
                <td><?=@$row->location_object->name;?></td>
                <td><?=$row->items[0]->qty_request?></td>
                <td><?=(is_object($row->date_register)) ? $row->date_register->format('Y-m-d'): '';?></td>
                <td><?=(is_object($row->date_request)) ? $row->date_request->format('Y-m-d'): '';?></td>
                <td><?=$row->status?></td>
                <td><?=(mb_strlen($row->memo) > 20) ? mb_substr($row->memo, 0, 20) . '...' : $row->memo;?></td>
                <td><button class="btn btn-default btn-sm btn_view" type="button" data-href="<?=site_url('work/enter/view/') . '/' . $row->id ?>">보기</button></td>
              </tr>
  <?php
  endforeach;
  ?>
            </tbody>

          </table>

          <p>
            <a href="/work/enter/request"><span class="btn btn-primary"><i class="icon-pencil"></i>&nbsp;입고 요청 등록</span></a>
          </p>

        </div>
      </div>
    </div><!-- end of container -->

    <script type="text/javascript">
    $(document).ready(function(){
      
      // colorbox      
      $(".ajax").colorbox({'opacity': '0.6', 'width': '80%'});
      $(".iframe").colorbox({
        'iframe'  : true,
        'width'   : '50%',
        'height'  : '80%'
      });

        // 상세 보기 페이지로 이동
      $("button.btn_view").click(function(){
        var href = $(this).data('href');
        location.href = href;
        return false;
      });
    });


    </script>

<?php
$this->load->view('layout/footer');
?>