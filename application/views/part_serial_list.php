    <!-- start of div.container -->
    <div class="container">
      <!-- Main hero unit for a primary marketing message or call to action -->
      <div class="page-header">
        <h1>시리얼 관리 장비 리스트</h1>
      </div>

      <!-- Example row of columns -->
      <div class="row">
        <div class="col-md-12">
          
        <table class="table table-hover">
          <thead>
            <tr>
              <th>No</th>
              <th>시리얼넘버</th>
              <th>장비종류</th>
              <th>모델명</th>
              <th>제조사명</th>
              <th>현재위치</th>
              <th>직전위치</th>
              <th>최초설치일</th>
              <th>입고일</th>
              <th>수정일</th>
              <th>상태</th>
              <th>메모</th>
            </tr>
          </thead>

          <tbody>
<?php
foreach($rows as $row):
?>
            <tr class="">
              <td><?=$row->id?></td>
              <td><?=$row->getSerialNumber()?></td>
              <td><?=$row->part->category->name?></td>
              <td><?=$row->part->name?></td>
              <td><?=$row->part->manufacturer?></td>
              <td><?=$row->current->name?></td>
              <td><?=$row->previous->name?></td>
              <td><?=$row->date_install?></td>
              <td><?=$row->getDateEnter()?></td>
              <td><?=$row->getDateModify()?></td>
              <td><?=$row->status?></td>
              <td><?=$row->memo?></td>
            </tr>
<?php
endforeach;
?>
          </tbody>

        </table>

        <p>
          <a href="/admin/part/add"><button class="btn btn-primary"><i class="icon-pencil"></i>&nbsp;장비 등록???</button></a>
        </p>

        </div>
      </div>
    </div><!-- end of container -->
      <script type="text/javascript">
      </script>