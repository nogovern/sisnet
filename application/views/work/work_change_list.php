<?php
$this->view('layout/header');
$this->view('layout/navbar');
?>
    <!-- start of div.container -->
    <div class="container">
      <!-- Main hero unit for a primary marketing message or call to action -->
      <div class="page-header">
        <h1><span class="fa fa-desktop"></span>&nbsp;상태변경 업무</h1>
      </div>
     
      <!-- Example row of columns -->
      <div class="row">
        <div class="col-md-12">
          <ul class="nav nav-pills">
            <li class="<?=($status=='')?'active':''?>"><a href="#">전체</a></li>
            <li class=""><a href="#">생성</a></li>
            <li class=""><a href="#">입력</a></li>
            <li class=""><a href="#">완료</a></li>
          </ul>

          <table class="table table-hover">
            <thead>
              <tr>
                <th>No</th>
                <th>작업형태</th>
                <th>요청자</th>
                <th>재고사무소</th>
                <th>진행상태</th>
                <th>상태변경 장비개수</th>
                <th>등록일</th>
                <th>완료일</th>
                <th>&nbsp;</th>
              </tr>
            </thead>

            <tbody>
  <?php
  foreach($rows as $row):
    switch($row->status) {
      case '1': $label_color = 'label-default';break;
      case '2': $label_color = 'label-success';break;
      case '3': $label_color = 'label-info';break;
      default : $label_color = 'label-default';break;
    }

    $item_count = 0;
    // $target 은 gs2_operation_targets 의 row 에 해당
    foreach($row->targets as $target) {
      // gs2_dump($target->target->id);
      $item_count += count($target->target->getItems());
    }

  ?>
              <tr class="">
                <td><?=$row->id?></td>
                <td><?=gs2_op_type($row->type)?></td>
                <td><?=$row->user->name?></td>
                <td><?=$row->office->name?></td>
                <td>
                  <span class="label <?=$label_color?>"><?=constant("GS2_OP_CHANGE_STATUS_" .$row->status)?></span>
                </td>
                <td><?php echo $item_count; ?></td>
                <td><?=$row->getDateRegister();?></td>
                <td><?=$row->getDateFinish();?></td>
                <td><button class="btn btn-default btn-sm btn_view" type="button" data-href="<?=site_url('work/change/view/') . '/' . $row->id ?>">보기</button></td>
              </tr>
  <?php
  endforeach;
  ?>
            </tbody>

          </table>

          <p>
            <a href="<?=site_url("/work/change/register")?>"><span class="btn btn-primary"><i class="icon-pencil"></i>&nbsp;변경서 등록</span></a>
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

      /////////////////////////
      // bootstrap 3 popover //
      /////////////////////////
      $(".popover_memo").popover({trigger: 'hover', placement: 'left'});
      $(".popover").click(function(e){e.preventDefault();});
    });
    </script>

<?php
$this->view('layout/footer');
?>