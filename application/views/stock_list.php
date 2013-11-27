    <!-- start of div.container -->
    <div class="container">
      <!-- Main hero unit for a primary marketing message or call to action -->
      <div class="page-header">
        <h1>재고</h1>
      </div>

      <!-- Example row of columns -->
      <div class="row">
        <div class="span12">
          
        <table class="table table-responsive" id="stock_list">
          <thead>
            <tr>
              <th>No</th>
              <th>장비명</th>
              <th>장비종류</th>
              <th>모델명</th>
              <th>등록일자</th>
              <th>재고수량</th>
              <th>상태</th>
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
              <td><?=(is_object($row->date_register)) ? $row->date_register->format('Y-m-d H:i:s'): '';?></td>
              <td><?=intval($row->qty_total)?></td>
              <td><?=$row->status?></td>
              <td>
<?php
if(count($row->getStockList())):
?>
<table class="table">
  <tbody>
<?php
  foreach($row->getStockList() as $stock):
?>
    <tr class="warning">
      <td><?=$stock->inventory->name?></td>
      <td><?=$stock->qty_minimum?></td>
      <td><?=number_format($stock->qty_new)?></td>
      <td><?=number_format($stock->qty_used)?></td>
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