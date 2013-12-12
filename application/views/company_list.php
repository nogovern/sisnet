    <!-- start of div.container -->
    <div class="container">
      
      <!-- Main hero unit for a primary marketing message or call to action -->
      <div class="page-header">
        <h1><span class="fa fa-building-o"></span>&nbsp;<?=$page_title?></h1>
      </div>

      <!-- Example row of columns -->
      <div class="row">
        <div class="span12">
          
        <table class="table table-hover">
          <thead>
            <tr>
              <th>#</th>
              <th>Type</th>
              <th>업체명</th>
              <th>업체코드</th>
              <th>담당자</th>
              <th>연락처</th>
              <th>주소</th>
              <th>등록일</th>
              <th>상태</th>
              <th>설명</th>
            </tr>
          </thead>

          <tbody>
<?php
$arr_type = array(
  1 => '시스네트',
  2 => 'GS25',
  3 => '납품',
  4 => '수리',
  5 => '제조',
  6 => '폐기',
);

foreach($rows as $row):
?>
            <tr>
              <td><?=$row->id?></td>
              <td><?=$arr_type[$row->type]?></td>
              <td><?=$row->name?></td>
              <td><?=$row->code?></td>
              <td><?=($row->user) ? $row->user->getName() : '-- 지정안됨 --'?></td>
              <td><?=$row->tel?></td>
              <td><?=$row->address?></td>
              <td><?=$row->getRegisterDate()?></td>
              <td><?=$row->status?></td>
              <td><?=$row->memo?></td>
            </tr>
<?php
endforeach;
?>
          </tbody>
        </table>

        <p>
          <a href="/admin/company/add"><span class="btn btn-primary">&nbsp;신규  등록</span></a>
        </p>

        </div>
      </div>
    </div><!-- end of container -->
    <script type="text/javascript">
    $(document).ready(function(){
        $(".ajax").colorbox({'opacity': '0.6', 'width': '80%'});
    });
    </script>