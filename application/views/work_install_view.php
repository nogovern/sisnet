<?php
$this->load->view('layout/header', array('title' => '설치 >> 설치 요청 보기'));
$this->load->view('layout/navbar', array('current' => 'page-work-install'));

?>

    <!-- start of div.container -->
    <div class="container">
      <div class="page-header">
        <h1><span class="fa fa-desktop"></span>&nbsp;설치 업무</h1>
      </div>
<?php
// 업무 공통 헤더
$this->load->view('_work_view_header', $work);
?>

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
                    <th>삭제</th>
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
                    <td><?=$arr_type_text[$item->part->type]?></td>
                    <td><?=$item->part->name?></td>
                    <td><?=($item->is_new == 'Y')? '신품' : '중고'?></td>
                    <td><?=($item->part->type == '1') ? '' : ''?></td>
                    <td><?=''?></td>
                    <td><?=$item->qty_request?></td>
                    <td style="width:150px;">
                      <button class="btn btn-danger btn-xs remove_item" type="button">X</button>
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
          <a href="/work/install"><span class="btn btn-default" type="button">리스트</span></a>
<?php
if($work->status == 1):
?>
          <button class="btn btn-success" type="button" data-toggle="modal" data-target="#modal_request_ok">요청확정</button>
<?php
endif;

if($work->status == 2):
?>
          <button class="btn btn-warning btn_add" type="button" data-toggle="modal" data-target="#modal_part_register">장비 등록</button>
          <button class="btn btn-default" type="button" data-toggle="modal" data-target="#modal_change_worker">방문자 변경</button>
          <button class="btn btn-info" type="button" data-toggle="modal" data-target="#modal_memo">작업 메모</button>
          <button id="btn_store_end" class="btn btn-success" type="button" disabled>점포 종료</button>
          <button class="btn btn-danger" type="button">업무 종료</button>
<?php
endif;

if($work->status == 3):
?>
          <button id="btn_scan" class="btn btn-danger" type="button" data-toggle="modal" data-target="#myModal">장비 스캔</button>
          <button id="btn_complete" class="btn btn-success" type="button" disabled>설치 완료</button>
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
    $this->load->view('common/modal_part_register');        // 장비 등록
    $this->load->view('common/modal_request_ok');           // 요청 확정
    $this->load->view('common/modal_memo');                 // 작업자 메모
    $this->load->view('common/modal_change_worker');        // 방문자 변경
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

      //--------------------------------------

      // 장비 등록 모달 open
      $("#btn_register").click(function(){
          $("#myModal .modal-content").html('').load('/work/install/loadModalContent', function(result){
            $("#myModal").modal({show:true});
          });
      });
    });
    
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
    }

    // 행 삭제
    function callback_remove_row(what) {
      $(what).closest('tr').fadeOut('slow');
    }

    </script>
<?php
$this->load->view('layout/footer');
?>