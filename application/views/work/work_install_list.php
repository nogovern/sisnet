<?php
$this->load->view('layout/header', array('title' => '설치 >> 설치 리스트'));
$this->load->view('layout/navbar', array('current' => 'page-install'));
?>
    <!-- start of div.container -->
    <div class="container">
      <!-- Main hero unit for a primary marketing message or call to action -->
      <div class="page-header">
        <h1><span class="fa fa-desktop"></span>&nbsp;설치 업무</h1>
      </div>

      <!-- filter -->
      <div class="well well-sm">
        <form method="post" id="filter-form" class="form-inline" role="search">
          <input type="hidden" name="csrf_test_name" value="<?php echo $this->security->get_csrf_hash();?>">
          <div class="form-group">
            진행상태 : 
            <?php echo $status_filter; ?>
          </div>

          <div class="form-group">
            &nbsp;&nbsp;작업형태 : 
            <?php echo $type_filter; ?>
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
     
      <!-- Example row of columns -->
      <div class="row">
        <div class="col-md-12">
          <table id="op_list" class="table table-hover table-condensed table-responsive">
            <thead>
              <tr>
                <th>No</th>
                <th>작업형태</th>
                <th>대상점포</th>
                <th>요청자</th>
                <th>담당사무소</th>
                <th>진행상태</th>
                <th>등록일</th>
                <th>개점일</th>
                <th>요청일</th>
                <th>예정일</th>
                <th>완료일</th>
                <th>&nbsp;</th>
              </tr>
            </thead>

            <tbody>
  <?php
  if(!count($rows)) {
    echo '<tr><td colspan="12" class="text-center" style="">검색된 결과가 없습니다</td></tr>';
  } 
  else {
    foreach($rows as $row):
      switch($row->status) {
        case '1': $label_color = 'label-default';break;
        case '2': $label_color = 'label-danger';break;
        case '3': $label_color = 'label-info';break;
        case '4': $label_color = 'label-success';break;
        default : $label_color = 'label-default';break;
      }

      // 점포명
    $store = gs2_decode_location($row->work_location);
    $store_name = ($store) ? $store->name : '';

  ?>
              <tr class="">
                <td><?=$row->id?></td>
                <td><?=gs2_op_type($row->type)?></td>
                <td><?=$store_name?></td>
                <td><?=$row->user->name?></td>
                <td><?=$row->office->name?></td>
                <td>
                  <span class="label <?=$label_color?>"><?=constant("GS2_OP_INSTALL_STATUS_" .$row->status)?></span>
                </td>
                <td><?=$row->getDateRegister();?></td>
                <td><?=$row->getDateStore();?></td>
                <td><?=$row->getDateRequest();?></td>
                <td><?=$row->getDateExpect();?></td>
                <td><?=$row->getDateFinish();?></td>
                <td><button class="btn btn-default btn-sm btn_view" type="button" data-href="<?=site_url('work/install/view/') . '/' . $row->id ?>">보기</button></td>
              </tr>
  <?php
    endforeach;
  }
  ?>
            </tbody>

          </table>
          <!-- Pagination -->
          <div style="text-align:center">
            <?=$pagination?>
          </div>

<?php if(gs2_user_type() == '2'): ?>
          <p>
            <a href="<?=site_url('work/install/add')?>"><span class="btn btn-primary"><i class="fa fa-pencil-square-o"></i>&nbsp;요청 등록</span></a>
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
        var url = _base_url + 'work/install/?';
        var query = $(this).serialize();
        
        $(this).prop('action', url + query);
        // return false;
      });

    });


    </script>

<?php
$this->load->view('layout/footer');
?>