<?php
/**
 * 업무 메모 리스트
 */
?>

<ul class="list-group">
<?php
foreach($logs as $log):
?>
        <li class="list-group-item">
          <p class="help-block"><span class="badge pull-right"><?=$log->getDateRegister(TRUE);?></span><?php echo $log->user->name?></p>
          <?php 
          if($log->type == '1') {
          	echo '<span class="badge pull-left" style="background-color:#468847">' . $log->getEvent() . '</span>&nbsp;&nbsp;';	
          }

          // 내용 출력
          echo nl2br($log->content); 
          ?>
        </li>
<?php
endforeach;
?>
 </ul>