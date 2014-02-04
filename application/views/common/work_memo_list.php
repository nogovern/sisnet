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
          <?php echo nl2br($log->content); ?>
        </li>
<?php
endforeach;
?>
 </ul>