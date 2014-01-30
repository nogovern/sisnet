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

  <div class="row">
    <div class="col-md-12">
      <!-- start: ALERTS PANEL -->
      <div class="panel panel-primary">
        <div class="panel-heading"><i class="fa fa-tags"></i> 장비 리스트</div>
        <div class="panel-body" style="padding:0 15px;">
          <table class="table table-hover" id="part_table">
            <thead>
              <tr>
                <th>#</th>
                <th>종류</th>
                <th>장비명</th>
                <th>상태</th>
                <th>S/N</th>
                <th>직전위치</th>
                <th>등록수량</th>
                <th></th>
              </tr>
            </thead>
            <tbody>
<?php
$arr_type_text = array('1' => '시리얼', '2'=>'수량', '3'=>'소모품');
$arr_type_class= array('1' => 'label-success', '2'=>'label-default', '3'=>'label-warning');

$i = 1;
$item_count = count($items);
foreach($items as $item):
?>                  
              <tr data-item_id="<?=$item->id?>">
                <td><?=$item->id?></td>
                <td><?=$arr_type_text[$item->part_type]?></td>
                <td><?=$item->part_name?></td>
                <td><?=($item->is_new == 'Y')? '신품' : '중고'?></td>
                <td><?=$item->serial_number?></td>
                <td><?=($item->part_type == '1') ? '' : ''?></td>
                <td><?=$item->qty_request?></td>
                <td style="width:150px;">
                  <?php if($work->getStatus() < '4'):?>
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
if($work->status == 1 && $work->type != '205') {
?>
      <button class="btn btn-success" type="button" data-toggle="modal" data-target="#modal_request_ok">요청확정</button>
<?php
}

// 작업메모는 확정 후 부터만 가능 
if($work->status > 2 && $work->status < 5) {
?>
      <button class="btn btn-default" type="button" data-toggle="modal" data-target="#modal_memo" >작업 메모</button>

<?php
}

if($work->status == 2) {
?>
      <button class="btn btn-default" type="button" data-toggle="modal" data-target="#modal_change_worker">방문자 변경</button>
      <button class="btn btn-success" type="button" data-toggle="modal" data-target="#modal_store_complete">점포 완료</button>
<?php
}

if($work->status == 3):
?>
      <button class="btn btn-danger" type="button" data-toggle="modal" data-target="#modal_op_complete">작업 완료</button>
      <button id="btn_complete" class="btn btn-success" type="button" disabled>설치 완료</button>
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
$this->view('common/modal_part_register');        // 장비 등록
$this->view('common/modal_request_ok');           // 요청 확정
$this->view('common/modal_memo');                 // 작업자 메모
$this->view('common/modal_change_worker');        // 방문자 변경
$this->view('common/modal_store_complete');       // 점포 완료
$this->view('common/modal_op_complete');          // 작업 완료
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
      $("#myModal .modal-content").html('').load('/work/install/loadModalContent', function(result){
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

  // 등록 장비 없을 시 점포완료 비활성
  var len = $("#part_table tbody tr").length;
  if(len == 0) {
    $("button[data-target=#modal_store_complete]").attr('disabled', true);
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