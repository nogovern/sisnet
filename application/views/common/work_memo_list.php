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
          <p class="help-block"><?php echo $log->user->name . "   " . $log->getDateRegister(TRUE); ?></p>
          <?php echo nl2br($log->content); ?><span class="badge"><?=$log->getDateRegister(TRUE);?></span>
        </li>
<?php
endforeach;
?>
 </ul>