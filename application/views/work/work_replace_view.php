<?php
$this->view('layout/header');
$this->view('layout/navbar');

// 점포 객체 
$store = gs2_decode_location($work->work_location);

// 대상 업무 객체
foreach($work->targets as $top) {
  if( $top->target->type == '205' ) {
    $install_target = $top->target;
  }
  if( $top->target->type == '305' ) {
    $close_target = $top->target;
  }
}
?>

<!-- start of div.container -->
<div class="container">
  <div class="page-header">
    <h1><span class="fa fa-desktop"></span>&nbsp;교체 업무</h1>
  </div>

  <div class="row">
    <div class="col-sm-4">
      <table class="table table-condensed">
        <thead>
          <tr>
            <th colspan="3">작업 정보</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>업무번호</td>
            <td><?=$work->operation_number?></td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td>작업형태</td>
            <td><?=$_config['op_type'][$work->type]?></td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td>요청자</td>
            <td><?=$work->user->name?></td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td>담당사무소</td>
            <td><?=$work->office->name?></td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td>담당직원</td>
            <td><?=$work->getWorkerInfo();?></td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td>등록일</td>
            <td><?=$work->date_register->format("Y-m-d H:i:s")?></td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td>폐점일 </td>
            <td><?=$work->getDateStore()?></td>
            <td>&nbsp;</td>
          </tr>
          <tr class="warning">
            <td>설치 요청일</td>
            <td><?=$install_target->getDateRequest(TRUE);?></td>
            <td>&nbsp;</td>
          </tr>
          <tr class="warning">
            <td>철수 요청일</td>
            <td><?=$close_target->getDateRequest(TRUE);?></td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td>방문 예정일</td>
            <td><?=$work->getDateExpect()?></td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td>진행상태</td>
            <td><?=constant("GS2_OP_CLOSE_STATUS_" . $work->status)?></td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td>첨부파일</td>
            <td></td>
            <td>&nbsp;</td>
          </tr>
        </tbody>
      </table>
    </div>

    <div class="col-sm-4">
      <table class="table table-condensed table-hover">
        <thead>
          <tr>
            <th class="col-md-4">점포 정보</th>
            <th class="col-md-8">&nbsp;</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>점포명</td>
            <td><?=$store->name;?></td>
          </tr>
          <tr>
            <td>점포 코드</td>
            <td><?=$store->code;?></td>
          </tr>
          <tr>
            <td>점주 이름</td>
            <td><?=$store->owner_name;?></td>
          </tr>
          <tr>
            <td>가맹형태</td>
            <td><?=$_config['store_join_type'][$store->join_type]?></td>
          </tr>
          
          <tr>
            <td>점포 전화</td>
            <td><?=$store->tel?></td>
          </tr>
          <tr>
            <td>주소</td>
            <td><?=$store->address?></td>
          </tr>
          <tr>
            <td>무인택배</td>
            <td><?=($store->has_postbox == 'Y') ? '설치' : '미설치'?></td>
          </tr>
          <tr>
            <td>RFC</td>
            <td><?=$store->rfc_tel?></td>
          </tr>
          <tr>
            <td>OFC</td>
            <td><?=$store->ofc_tel?></td>
          </tr>
          <tr>
            <td>작업메모</td>
            <td><?=$work->memo?></td>
          </tr>
          <tr>
            <td></td>
            <td><?=''?></td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- 유저 메모 -->
    <div id="memo_panel" class="col-sm-4" style="overflow-y: auto; height: 340px;">
    </div>

  </div><!-- end of row -->

  <!-- 설치 작업 -->
  <div class="row">
    <div class="col-md-12">
      <!-- start: ALERTS PANEL -->
      <div id="install_target_panel" class="panel panel-primary">
        <div class="panel-heading"><i class="fa fa-tags"></i> 장비 설치 작업 번호: <?php echo $install_target->operation_number; ?> </div>
        <div class="panel-body" style="">
          <table class="table table-condensed">
            <tbody>
              <tr>
                <td class="col-sm-3">장비 설치 상태: <?php echo $install_target->getStatus(); ?></td>
                <td class="col-sm-3">설치 작업자: <?php echo $install_target->getWorkerInfo();; ?></td>
                <td class="col-sm-3">설치 예정일: <?php echo $install_target->getDateExpect(); ?></td>
                <td class="col-sm-3">설치 완료일: <?php echo $install_target->getDateWork(); ?></td>
              </tr>
              <tr>
                <td class="col-sm-12" colspan="4">설치작업 첨부 파일: </td>
              </tr>
            </tbody>
          </table>
          <table class="table table-condensed table-hover">
            <thead>
              <tr class="active">
                <th>#</th>
                <th>장비구분</th>
                <th>모델</th>
                <th>시리얼</th>
                <th>장비상태</th>
                <th>수량</th>
              </tr>
            </thead>
            <tbody>

            </tbody>
          </table>
        </div>
      </div>
      <!-- end: ALERTS PANEL -->
    </div>
  </div>

  <!-- 철수 작업 -->
  <div class="row">
    <div class="col-md-12">
      <!-- start: ALERTS PANEL -->
      <div id="close_target_panel" class="panel panel-danger">
        <div class="panel-heading"><i class="fa fa-tags"></i> 장비 철수 작업 번호: <?php echo $close_target->operation_number; ?> </div>
        <div class="panel-body" style="">
          <table class="table table-condensed">
            <tbody>
              <tr>
                <td class="col-sm-3">장비 철수 상태: <?php echo $close_target->getStatus(); ?></td>
                <td class="col-sm-3">철수 작업자: <?php echo $close_target->getWorkerInfo();; ?></td>
                <td class="col-sm-3">철수 예정일: <?php echo $close_target->getDateExpect(); ?></td>
                <td class="col-sm-3">철수 완료일: <?php echo $close_target->getDateWork(); ?></td>
              </tr>
              <tr>
                <td class="col-sm-12" colspan="4">철수작업 첨부 파일: </td>
              </tr>ß
            </tbody>
          </table>
          <table class="table table-condensed table-hover">
            <thead>
              <tr class="active">
                <th>#</th>
                <th>장비구분</th>
                <th>모델</th>
                <th>시리얼</th>
                <th>장비상태</th>
                <th>수량</th>
              </tr>
            </thead>
            <tbody>

            </tbody>
          </table>
        </div>
      </div>
      <!-- end: ALERTS PANEL -->
    </div>
  </div>

  <div class="row">
    <div class="col-md-12">
      <a href="<?=site_url('work/close')?>"><span class="btn btn-default" type="button">리스트</span></a>
      <button class="btn btn-default" type="button" data-toggle="modal" data-target="#modal_memo" >작업 메모</button>
<?php
if($work->status == 1):
?>
      <button class="btn btn-success" type="button" data-toggle="modal" data-target="#modal_replace_request_ok">요청확정</button>
<?php
endif;

if($work->status == 2):
?>
      <button class="btn btn-default" type="button" data-toggle="modal" data-target="#modal_change_worker">방문자 변경</button>
      <button class="btn btn-success" type="button" data-toggle="modal" data-target="#modal_store_complete">점포 완료</button>
<?php
endif;

if($work->status == 3):
?>
      <button class="btn btn-warning btn_add" type="button" data-toggle="modal" data-target="#modal_close_part_register">장비 등록</button>
      <button class="btn btn-danger" type="button" data-toggle="modal" data-target="#modal_op_complete">작업 완료</button>
      <button id="btn_approve" class="btn btn-success" type="button" disabled>승인</button>
<?php
endif;
if($work->status == 4):
?>
      <button id="btn_confirm" class="btn btn-success" type="button">승인</button>
<?php
endif;
?>

    </div>
  </div>
</div><!-- end of div.container -->

<?php
/////////////////////////////
/// 모달 dialog include
/////////////////////////////
$this->view('common/modal_replace_request_ok');           	// 요청 확정
$this->view('common/modal_memo');                 	// 작업자 메모
//$this->view('common/modal_change_worker');        	// 방문자 변경
//$this->view('common/modal_store_complete');       	// 점포 완료
$this->view('common/modal_op_complete');       		  // 작업 완료
$this->view('common/modal_close_part_register');   	// 장비 등록 (설치/철수 다름)
// 작업 완료
?>

<!-- jquery form validation -->
<script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.min.js"></script>
<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>

<script type="text/javascript">
// 작업 정보 객체
var operation = {
  id : <?=$work->id?>,
  office_id : <?=$work->office->id?>,
  type: "<?=$work->type?>",
  status: "<?=$work->status?>"
};

// 장비 목록
var items = [];     // array of item object
var item = {};
var count_item = <?=$item_count?>;

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

  // datepicke 아이콘 이벤트
  $(".btn_date").click(function(e){
    $(".date-picker", $(this).parent()).datepicker("show");
  });

  // modal 공통 설정
  $(".modal").modal({backdrop: 'static', show: false});

  //--------------------------------------

  // 장비 등록 모달 open (deprecated)
  $("#btn_register").click(function(){
      $("#myModal .modal-content").html('').load('/work/close/loadModalContent', function(result){
        $("#myModal").modal({show:true});
      });
  });

  // 승인 버튼
  $("#btn_confirm").click(function(){
    alert('승인 합니다... 미구현');
  });

  // 사용자 메모 출력
  gs2_display_memo("#memo_panel");

});// end of ready

//  장비리스트에 행 추가
function callback_insert_row(id, type, name, sn, prev, qty, is_new) {
  var type_text = '';
  if( type == '1') type_text = '시리얼';
  if( type == '2') type_text = '수량';
  if( type == '3') type_text = '소모품';

  var tr = $("<tr/>").attr('data-item_id', id);
  tr.append($("<td/>").text(id));
  tr.append($("<td/>").text(type_text));
  tr.append($("<td/>").text(name));
  tr.append($("<td/>").text((is_new == 'Y') ? '신품' : '중고'));
  tr.append($("<td/>").text(sn));
  tr.append($("<td/>").text(prev));
  tr.append($("<td/>").text(qty));
  tr.append($("<td/>").html('<button class="btn btn-danger btn-xs remove_item" type="button">X</button>'));
  $("#part_table tbody").append(tr);

  $("button[data-target=#modal_store_complete]").attr('disabled', false);
}

// 행 삭제
function callback_remove_row(what) {
  $(what).closest('tr').fadeOut('slow').remove();

  // 등록 장비 없을 시 작업완료 비활성
  var len = $("#part_table tbody tr").length;
  if(len == 0) {
    $("button[data-target=#modal_op_complete]").attr('disabled', true);
  }
}

/////////////
// 공통
/////////////
function gs2_display_memo(where, op_id) {
  var load_url;
  if(op_id === undefined)
    op_id = operation.id;

  load_url = '<?=site_url("work/ajax/loadUserMemo")?>' + '/' + op_id;
  $(where).load(load_url);
}

</script>
<?php
$this->view('layout/footer');
?>