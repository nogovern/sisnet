<?php
$this->view('layout/header');
$this->view('layout/navbar');

// 여기서 해줘야지 만 되네요....
//$_config= $this->config->item('gs2');
?>

<!-- start of div.container -->
<div class="container">
  <div class="page-header">
    <h1><span class="fa fa-desktop"></span>&nbsp;설치 업무</h1>
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
            <td>
<?php  
  echo $_config['op_type'][$work->type];
  
  // 교체 업무인 경우 교체 요청서 링크 표시
  echo $replace_link;
?>              
            </td>
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
            <td><?=$work->getDateRegister()?></td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td>개점일 </td>
            <td><?=$work->getDateStore()?></td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td>작업 요청일</td>
            <td><?=$work->getDateRequest(TRUE);?></td>
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
            <td style="white-space:nowrap;">
<?php
if($work->numFiles()) {
  foreach($work->files as $file) {
    $label_color = ($file->gubun == '요청') ? 'label-primary' : 'label-success';
    echo '<span class="label '. $label_color .'">' . $file->gubun . "</span> ";
    echo anchor('assets/files/' . $file->save_name, $file->org_name, ' target="_blank"') . '<br>';
  }
}
?>
            </td>
            <td>&nbsp;</td>
          </tr>
        </tbody>
      </table>
    </div>

    <div class="col-sm-4">
      <table class="table table-condensed table-hover">
        <thead>
          <tr>
            <th class="col-md-12" colspan="2">점포 정보
              <a class="btn btn-default btn-xs popup_store_info" data-storeid="<?=$store->id?>">상세보기</a>
            </th>
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
            <td><?=$store->getPostboxType();?></td>
          </tr>
          <tr>
            <td>RFC</td>
            <td><?=$store->rfc_name?>/<?=$store->rfc_tel?></td>
          </tr>
          <tr>
            <td>OFC</td>
            <td><?=$store->ofc_name?>/<?=$store->ofc_tel?></td>
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

  <div class="row">
    <div class="col-md-12">
      <!-- start: ALERTS PANEL -->
      <div class="panel panel-primary">
        <div class="panel-heading"><i class="fa fa-tags"></i> 장비 리스트</div>
        <div class="panel-body">
          <table class="table table-hover table-condensed table-responsive" id="part_table">
            <thead>
              <tr>
                <th class="col-xs-1">#</th>
                <th class="col-xs-1">타입</th>
                <th class="col-xs-2">장비종류</th>
                <th class="col-xs-2">모델명</th>
                <th class="col-xs-1">상태</th>
                <th class="col-xs-1">S/N</th>
                <th class="col-xs-1">직전위치</th>
                <th class="col-xs-1">등록수량</th>
                <th class="col-xs-1"></th>
              </tr>
            </thead>
            <tbody>
<?php
$arr_type_class= array('1' => 'label-success', '2'=>'label-default', '3'=>'label-warning');

$i = 1;
$item_count = count($items);
foreach($items as $item):
?>                  
              <tr data-item_id="<?=$item->id?>">
                <td><?=$i++?></td>
                <td><?=gs2_part_type($item->part_type)?></td>
                <td><?=$item->part->category->name?></td>
                <td><?=$item->part_name?></td>
                <td><?=($item->is_new == 'Y')? '신품' : '중고'?></td>
                <td><?=$item->serial_number?></td>
                <td><?=($item->part_type == '1') ? '' : ''?></td>
                <td><?=$item->qty_request?></td>
                <td style="width:150px;">
                  <?php if($work->getStatus() < '3'):?>
                  <button class="btn btn-danger btn-xs remove_item" type="button">X</button>
                  <?php else:?>
                  <i class="fa fa-check scan_status" style="color:green;font-size:20px;"></i>
                  <?php endif;?>
                </td>
              </tr>
<?php
endforeach;
?>             
            </tbody>
          </table>
        </div>
      </div>
      <!-- end: ALERTS PANEL -->
    </div>
  </div>
  
  <div class="row">
    <div class="col-md-12">
      <a href="<?=site_url('work/install')?>"><span class="btn btn-default" type="button">리스트</span></a>
<?php
if(gs2_user_type() == '2' && $work->status == '1'):
?>
      <button id="btn_cancel_request" class="btn btn-danger" type="button">요청취소</button>
<?php
endif;

// 시스네트 유저만!!!
if( gs2_user_type() == '1' || gs2_user_level() >= 5 )
{
  if($work->status == 1 && $work->type != '205') {
?>
      <button class="btn btn-success" type="button" data-toggle="modal" data-target="#modal_request_ok">요청확정</button>
<?php
  }

  // 작업메모는 확정 후 부터만 가능 
  if($work->status >= 2 && $work->status <= 3) {
?>
      <button class="btn btn-default" type="button" data-toggle="modal" data-target="#modal_memo" >작업 메모</button>
      <button class="btn btn-default" type="button" data-toggle="modal" data-target="#modal_change_worker">방문자 변경</button>
<?php
  }

  if($work->status == 2) {
?>
      <button class="btn btn-warning btn_add" type="button" data-toggle="modal" data-target="#modal_part_register">장비 등록</button>
      <button id="btn_store_complete" class="btn btn-success" type="button" data-toggle="modal" data-target="#modal_store_complete">점포 완료</button>
<?php
  }

  if($work->status == 3):
?>
      <!--
     <button id="btn_op_complete" class="btn btn-danger" type="button" data-toggle="modal" data-target="#modal_op_complete">작업 완료</button>
     -->
      <button id="btn_op_complete2" class="btn btn-danger" type="button" href="<?=base_url()?>work/ajax/iframe_complete/<?=$work->id?>" data-target="#modal_op_complete_container">작업완료</button>
<?php
  endif;
}//!-- end of 시스네트 유저만!!
?>

<?php if(gs2_user_type() == '2' && $work->status == '4'): ?>
      <button id="btn_approve" class="btn btn-success" type="button">승인</button>
<?php endif; ?>

    </div>
  </div>
</div><!-- end of div.container -->

<?php
/////////////////////////////
/// 모달 dialog include
/////////////////////////////
$this->view('common/modal_part_register');        // 설치 장비 등록
$this->view('common/modal_request_ok');           // 요청 확정
$this->view('common/modal_memo');                 // 작업자 메모
$this->view('common/modal_change_worker');        // 방문자 변경
$this->view('common/modal_store_complete');       // 점포 완료
// $this->view('common/modal_op_complete');          // 작업 완료
$this->view('common/modal_op_complete_container');      // 작업 완료2

$this->view('common/modal_search_previous');      // 직전위치 검색용

///////////////
//  공통 modal
///////////////
$this->view('common/modal_store_info');

?>

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

  // 승인 버튼
  $("#btn_approve").click(function(){
    if(!confirm("이 업무를 승인하시겠습니까?")) {
      return false;
    }

    var url = _base_url  + 'work/ajax/approve';
    $.post(url, { id: operation.id, csrf_test_name: $.cookie("csrf_cookie_name")} )
      .done(function(data){
        location.reload();
      });
  });

  // 사용자 메모 출력
  gs2_display_memo("#memo_panel", operation.id);

  // 요청취소 이벤트
  $("#btn_cancel_request").click(function(){
    gs2_cancel_operation("<?=base_url()?>work/install");
  });

  // 점포완료 버튼 초기화
  checkPartRegistered();

  // iframe 방식의 작업 완료 modal 열기
  $("#btn_op_complete2").on('click', function(e){
    e.preventDefault();
    var url = $(this).attr('href');
    $("#modal_op_complete_container .modal-body").html('<iframe width="100%" height="100%" frameborder="0" scrolling="no" allowtransparency="true" src="'+url+'" name="iframe"></iframe>').css('height', '370px');
    $("#modal_op_complete_container").modal('show');
  });

});// end of ready

//  장비리스트에 행 추가
function callback_insert_row(id, is_new, qty) {
  var type_text = '';
  if( item.type == '1') type_text = '시리얼';
  if( item.type == '2') type_text = '수량';
  if( item.type == '3') type_text = '소모품';

  var idx = $("#part_table tbody tr").length + 1;
  var sn = $("#serial_number").val();

  var tr = $("<tr/>").attr('data-item_id', id);
  tr.append($("<td/>").text(idx));
  tr.append($("<td/>").text(type_text));
  tr.append($("<td/>").text(item.cat_name));
  tr.append($("<td/>").text(item.name));
  tr.append($("<td/>").text((is_new == 'Y') ? '신품' : '중고'));
  tr.append($("<td/>").text(sn));
  tr.append($("<td/>").text(''));
  tr.append($("<td/>").text(qty));
  tr.append($("<td/>").html('<button class="btn btn-danger btn-xs remove_item" type="button">X</button>'));
  $("#part_table tbody").append(tr);

  checkPartRegistered();
}

// 행 삭제
function callback_remove_row(what) {
  $(what).closest('tr').fadeOut('slow').remove();

  // 등록 장비 없을 시 점포완료 비활성
  checkPartRegistered();
}

// 장비가 등록 되어 있는지 확인 
function checkPartRegistered() {
  var len = $("#part_table tbody tr").length;
  if(len > 0) {
    enableBtnStoreComplete();
  } else {
    disableBtnStoreComplete();
  }
}

// 점포완료 버튼 활성
function enableBtnStoreComplete() {
  $("#btn_store_complete").prop('disabled', false);
}

// 점포완료 버튼 비활성
function disableBtnStoreComplete() {
  $("#btn_store_complete").prop('disabled', true);
}

/////////////
// 공통
/////////////

</script>
<?php
$this->view('layout/footer');
?>