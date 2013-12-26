      <div class="row">
        <div class="col-sm-4">
          <table class="table table-condensed table-hover">
            <thead>
              <tr>
                <th colspan="3">주문 정보</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>주문번호</td>
                <td><?=$work->operation_number?></td>
                <td>&nbsp;</td>
              </tr>
              <tr>
                <td>요청자</td>
                <td><?=$work->user->name?></td>
                <td>&nbsp;</td>
              </tr>
              <tr>
                <td>입고장소</td>
                <td><?=$work->office->name?></td>
                <td>&nbsp;</td>
              </tr>
              <tr>
                <td>등록일</td>
                <td><?=$work->date_register->format("Y-m-d H:i:s")?></td>
                <td>&nbsp;</td>
              </tr>
              <tr>
                <td>작업요청일</td>
                <td><?=$work->date_request->format("Y-m-d H:i:s");?></td>
                <td>&nbsp;</td>
              </tr>
              <tr>
                <td>진행상태</td>
                <td><?=$work->status?></td>
                <td>&nbsp;</td>
              </tr>
            </tbody>
          </table>
        </div>
        <div class="col-sm-4">
          <table class="table table-condensed table-hover">
            <thead>
              <tr>
                <th colspan="3">납품처 정보</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>납품처</td>
                <td><?=$work->getWorkLocation();?></td>
                <td>&nbsp;</td>
              </tr>
              <tr>
                <td>납품 담당자</td>
                <td><?=$work->getItem()->part->company->user->name?></td>
                <td>&nbsp;</td>
              </tr>
              <tr>
                <td>연락처</td>
                <td><?=$work->getItem()->part->company->tel?></td>
                <td>&nbsp;</td>
              </tr>
              <tr>
                <td>주  소</td>
                <td><?=$work->getItem()->part->company->address?></td>
                <td>&nbsp;</td>
              </tr>
            </tbody>
          </table>
        </div>

        <div class="col-sm-4">
          <table class="table table-condensed table-hover">
            <thead>
              <tr>
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
                <td>장비 구분</td>
                <td><?=$work->getItem()->part->type?></td>
                <td>&nbsp;</td>
              </tr>
              <tr>
                <td>장비 모델명</td>
                <td><?=$work->getItem()->part->name?></td>
                <td>&nbsp;</td>
              </tr>
              <tr>
                <td>요청 수량</td>
                <td><?=$work->getItem()->qty_request?> 개</td>
                <td>&nbsp;</td>
              </tr>
              <tr>
                <td>장비 상태</td>
                <td><?=$work->getItem()->isNew() ? '신품' : '중고'?></td>
                <td>&nbsp;</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div><!-- end of row -->