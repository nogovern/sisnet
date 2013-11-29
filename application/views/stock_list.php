    <!-- start of div.container -->
    <div class="container">
      <!-- Main hero unit for a primary marketing message or call to action -->
      <div class="page-header">
        <h1>재고</h1>
      </div>

      <!-- Example row of columns -->
      <div class="row">
        <div class="span12">
          
        <table class="table table-responsive table-hover" id="stock_list">
          <thead>
            <tr>
              <th>No</th>
              <th>장비명</th>
              <th>장비종류</th>
              <th>모델명</th>
              <th>상태</th>
              <th>신품 합계</th>
              <th>중고 합계</th>
              <th>재고</th>
            </tr>
          </thead>

          <tbody>
<?php
foreach($rows as $row):
?>
            <tr class="">
              <td><?=$row->id?></td>
              <td><?=$row->type?></td>
              <td><?=$row->part_code?></td>
              <td><?=$row->name?></td>
              <td><?=$row->status?></td>
              <td><?=intval($row->getNewTotal())?></td>
              <td><?=intval($row->getUsedTotal())?></td>
              <td>
<?php
if(count($row->getStockList())):
?>
<table class="table table-hover">
  <tbody>
<?php
  foreach($row->getStockList() as $stock):
?>
    <tr class="warning">
      <td class="col-sm-4"><?=$stock->inventory->name?></td>
      <td class="col-sm-2"><?=$stock->qty_minimum?></td>
      <td class="col-sm-2"><?=number_format($stock->qty_new)?></td>
      <td class="col-sm-2"><?=number_format($stock->qty_used)?></td>
      <td class="col-sm-2"><button class="btn btn-info btn-xs" type="button">Order</button></td>
    </tr>
<?php
endforeach;
?>
  </tbody>
</table>
<?php
endif;
?>
                
              </td>
            </tr>
<?php
endforeach;
?>
          </tbody>

        </table>

        <ul class="pagination pagination-sm">
          <li><a href="#">&laquo;</a></li>
          <li class="active"><a href="#">1</a></li>
          <li><a href="#">2</a></li>
          <li><a href="#">3</a></li>
          <li><a href="#">4</a></li>
          <li><a href="#">5</a></li>
          <li><a href="#">&raquo;</a></li>
        </ul>

        <p class="well">
          <a href="/stock/add"><button class="btn btn-primary">&nbsp;신규  등록</button></a>
        </p>

        </div>
      </div>
    </div><!-- end of container -->
      <script type="text/javascript">
      $(document).ready(function() {
        $("#stock_list tr").hover(function(){
          // $(this).addClass('active');
        });
      });
      </script>