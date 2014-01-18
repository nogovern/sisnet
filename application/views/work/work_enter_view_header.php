  <div class="row">
    <div class="col-sm-4">
      <table class="table table-condensed table-hover">
        <thead>
          <tr class="active">
            <th colspan="3">주문 정보</th>
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
            <td><?=gs2_get_work_name($work->type)?></td>
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
            <td>담당자</td>
            <td><?=$work->getWorker();?></td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td>등록일</td>
            <td><?=$work->getDateRegister(TRUE)?></td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td>입고요청일</td>
            <td><?=$work->getDateRequest();?></td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td>완료일시</td>
            <td><?=$work->getDateFinish(TRUE);?></td>
            <td>&nbsp;</td>
          </tr>
          <tr  class="danger">
            <td>진행상태</td>
            <td><?=constant("GS2_OP_ENTER_STATUS_" . $work->status)?></td>
            <td>&nbsp;</td>
          </tr>

        </tbody>
      </table>
    </div>
    <div class="col-sm-4">
      <table class="table table-condensed table-hover">
        <thead>
          <tr class="active">
            <th colspan="3">납품처 정보</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>납품처</td>
            <td><?=gs2_decode_location($work->getWorkLocation())->name;?></td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td>담당자</td>
            <td><?=$work->getItem()->part->company->user->name?></td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td>담당자 연락처</td>
            <td><?=$work->getItem()->part->company->user->phone?></td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td>입고위치</td>
            <td><?=$work->office->address?></td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td>요청 메모</td>
            <td><?=nl2br($work->memo)?></td>
            <td>&nbsp;</td>
          </tr>
        </tbody>
      </table>
      <table class="table table-condensed table-hover">
        <thead>
          <tr class="active">
            <th colspan="3">장비 정보</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>장비 종류 </td>
            <td><?=$work->getItem()->part->category->name?></td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td>장비 모델명</td>
            <td><?=$work->getItem()->part_name?></td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td>장비 구분</td>
            <td><?=constant('GS2_PART_TYPE_' . $work->getItem()->part_type)?></td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td>수량 (요청/등록/스캔)</td>
            <td><?=$work->getTotalRequestQty()?>/<span id="complete_count"><?=$work->getTotalCompleteQty()?></span>/<span id="scan_count"><?=$work->getTotalScanQty()?></span>            </td>
            <td>&nbsp;</td>
          </tr>
        </tbody>
      </table>
    </div>

    <div class="col-sm-4">
    </div>
  </div><!-- end of row -->