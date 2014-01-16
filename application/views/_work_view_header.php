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
                <td>업무번호</td>
                <td><?=$work->operation_number?></td>
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
<?php if($work->type == '100'): ?>
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
                <td><?=gs2_decode_location($work->getWorkLocation())->name;?></td>
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
<?php endif; ?>

<?php if($work->type >= '200' && $work->type < '300'): ?>
        <div class="col-sm-4">
          <table class="table table-condensed table-hover">
            <thead>
              <tr>
                <th class="col-md-4">설치 정보</th>
                <th class="col-md-8">&nbsp;</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>개점일 </td>
                <td><?=$work->getDateWork();?></td>
              </tr>
              <tr>
                <td>방문직원</td>
                <td><?=$work->getWorkerInfo();?></td>
              </tr>
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
                <td><?=$store->join_type?></td>
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
                <td><?=$store->has_postbox?></td>
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
<?php endif; ?>        

      </div><!-- end of row -->