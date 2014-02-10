<table id="search_prev_location_result" class="table table-bordered">
  <thead>
    <tr>
      <th>#</th>
      <th>시리얼</th>
      <th>장비종류</th>
      <th>모델</th>
      <th>신품</th>
      <th>현재상태</th>
      <th>현재위치</th>
      <th></th>
    </tr>
  </thead>

  <tbody>
<?php 
if(count($rows)) {
  foreach($rows as $row):
?>
    <tr>
      <td><?php echo $row->id;?></td>
      <td><?php echo $row->serial_number;?></td>
      <td><?php echo $row->part->category->name;?></td>
      <td><?php echo $row->part->name;?></td>
      <td><?php echo $row->is_new;?></td>
      <td><?php echo $row->status;?></td>
      <td><?php echo $row->current_location;?></td>
      <td><?php echo '';?></td>
    </tr>
<?php
  endforeach;
} 
// 검색 결과가 없을 경우
else {
?>
    <tr>
      <td colspan="8">결과가 없습니다</td>
    </tr>
<?php  
}
?>
  </tbody>
</table>