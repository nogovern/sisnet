<table id="store_info_table" class="table table-bordered">
  <colgroup>
    <col style="width:20%;background-color:#EEE;">
    <col style="width:30%;">
    <col style="width:20%;background-color:#EEE;">
    <col style="width:30%;">
  </colgroup>
  <tbody>
    <tr>
      <th>점포명</td>
      <td colspan="3" id="sinfo_name"><?=$sinfo->name?></td>
    </tr>
    <tr>
      <th>최초코드</td>
      <td id="sinfo_code"><?=$sinfo->code?></td>
      <th>점포코드</td>
      <td id="sinfo_code2"><?=$sinfo->code2?></td>
    </tr>
    <tr>
      <th>점주 이름</td>
      <td id="sinfo_owner_name"><?=$sinfo->owner_name?></td>
      <th>점주 연락처</td>
      <td id="sinfo_owner_tel"><?=$sinfo->owner_tel?></td>
    </tr>
    <tr>
      <th>주소</td>
      <td id="sinfo_addr"><?=$sinfo->address?></td>
      <th>전화번호</td>
      <td id="sinfo_tel"><?=$sinfo->tel?></td>
    </tr>
    <tr>
      <th>RFC 이름</td>
      <td id="sinfo_rfc_name"><?=$sinfo->rfc_name?></td>
      <th>RFC 연락처</td>
      <td id="sinfo_rfc_tel"><?=$sinfo->rfc_tel?></td>
    </tr>
    <tr>
      <th>OFC 이름</td>
      <td id="sinfo_ofc_name"><?=$sinfo->ofc_name?></td>
      <th>OFC 연락처</td>
      <td id="sinfo_ofc_tel"><?=$sinfo->ofc_tel?></td>
    </tr>
    <tr>
      <th>가맹형태</td>
      <td id="sinfo_join_type"><?=$sinfo->join_type?></td>
      <th>무인택배 유무</td>
      <td id="sinfo_postbox"><?=$sinfo->getPostboxType()?></td>
    </tr>
    <tr>
      <th>상태</td>
      <td id="sinfo_status"><?=$sinfo->status?></td>
      <td></td>
      <td></td>
    </tr>
  </tbody>
</table>