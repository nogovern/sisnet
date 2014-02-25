<?php
$this->view('layout/header');
$this->view('layout/navbar');
?>
<!-- start of div.container -->
<div class="container">
  <!-- Main hero unit for a primary marketing message or call to action -->
  <div class="page-header">
    <h1><span class="fa fa-desktop"></span>&nbsp;교체 업무</h1>
  </div>
 
  <!-- Example row of columns -->
  <div class="row">
    <div class="col-md-12">

      <!-- filter -->
      <div class="well well-sm">
        <form method="post" id="filter-form" class="form-inline" role="search">
          <input type="hidden" name="csrf_test_name" value="<?php echo $this->security->get_csrf_hash();?>">
          <div class="form-group">
            진행상태 : 
            <?php echo $status_filter; ?>
          </div>

          <div class="form-group">
            &nbsp;&nbsp;사무소:
            <?php echo $office_filter; ?>
          </div>

          <div class="form-group">
            &nbsp;&nbsp; 
            <button type="submit" class="btn btn-primary btn-sm">검색</button> 
          </div>
        </form>
      </div>

      <table id="op_list" class="table table-hover table-condensed table-responsive">
        <thead>
          <tr>
            <th>No</th>
            <th>구분</th>
            <th>요청자</th>
            <th>대상점포</th>
            <th>담당사무소</th>
            <th>진행상태</th>
            <th>철수요청일</th>
            <th>설치요청일</th>
            <th>완료일</th>
            <th>&nbsp;</th>
          </tr>
        </thead>

        <tbody>
<?php
foreach($rows as $row):
switch($row->status) {
  case '1': $label_color = 'label-default';break;
  case '2': $label_color = 'label-danger';break;
  case '3': $label_color = 'label-success';break;
  case '4': $label_color = 'label-primary';break;
  default : $label_color = 'label-default';break;
}

// 대상 업무 객체
foreach($row->targets as $top) {
  if( $top->target->type == '205' ) {
    $install_target = $top->target;
  }
  if( $top->target->type == '305' ) {
    $close_target = $top->target;
  }
}

$store = gs2_decode_location($row->work_location);
?>
          <tr class="">
            <td><?=$row->id?></td>
            <td><?=gs2_op_type($row->type)?></td>
            <td><?=$row->user->name?></td>
            <td><?=$store->name?></td>
            <td><?=$row->office->name?></td>
            <td>
              <span class="label <?=$label_color?>"><?=constant("GS2_OP_REPLACE_STATUS_" .$row->status)?></span>
            </td>
            <td><?=$row->getDateRequest(TRUE);?></td>
            <td><?=$row->getDateExpect(TRUE);?></td>
            <td><?=$row->getDateFinish();?></td>
          <!--
            <td><a class="popover_memo" href="#" data-toggle="popover" data-original-title="요청메모" data-content="<?=$row->memo?>">[메모보기]</a></td>
          -->
            <td><button class="btn btn-default btn-sm btn_view" type="button" data-href="<?=site_url('work/replace/view/') . '/' . $row->id ?>">보기</button></td>
          </tr>
<?php
endforeach;
?>
        </tbody>

      </table>
      <!-- Pagination -->
      <div style="text-align:center">
        <?=$pagination?>
      </div>

<?php if(gs2_user_type() == '2'): ?>
      <p>
        <a href="<?=site_url("/work/replace/register")?>"><span class="btn btn-primary"><i class="fa fa-pencil-square-o"></i>&nbsp;요청서 등록</span></a>
      </p>
<?php endif; ?>

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

  ///////////////////////
  // 검색 필터 전송 
  ///////////////////////
  $("#filter-form").submit(function() {
    var url = _base_url + 'work/replace/?';
    var query = $(this).serialize();
    
    $(this).prop('action', url + query);
  });

});
</script>

<?php
$this->view('layout/footer');
?>