<?php
// 타이틀 설정
$title = (isset($title)) ? $title : '----- title is not defined ----';

$this->load->view('layout/header_popup', array('title' => "$title"));
?>

    <!-- start of div.container -->
    <div class="container">
    
      <!-- Example row of columns -->
      <div class="row">
        <h4>점포 검색 결과 : <?=count($rows)?> 건</h4>
        <table class="table table-hover">
          <thead>
            <tr>
              <th></th>
              <th>점포명</th>
              <th>점주</th>
              <th>주소</th>
              <th>연락처</th>
              <th>선택</th>
            </tr>
          </thead>
          <tbody>
<?php
foreach($rows as $row):
?>
            <tr>
              <td><?=$row->id?></td>
              <td><?=$row->name?></td>
              <td><?=$row->owner_name?></td>
              <td><?=$row->address?></td>
              <td><?=$row->tel?></td>
              <td><a href="#" class="select_me">[선택]</a></td>
            </tr>
<?php
endforeach;
?>
          </tbody>
        </table>
        
      </div>
    <!-- start of div.container -->
    </div>

    <!-- jquery form validation -->
    <script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
    <script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.min.js"></script>

    <script type="text/javascript">
    $(document).ready(function(){
      $(".select_me").click(function(e){
        e.preventDefault();
        var target = window.parent.document.getElementById('store_name');
        var target_id = $(":hidden[name=store_id]", window.parent.document);    // 이렇게 해도 되네요
        
        // 부모창 element 에 설정
        target_id.val($(this).closest('tr').find('td:eq(0)').text());
        target.value = $(this).closest('tr').find('td:eq(1)').text();

        parent.jQuery.fn.colorbox.close();
      });
      
    });
    </script>
<?php
$this->load->view('layout/footer');
?>