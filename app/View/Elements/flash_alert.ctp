<div class="flash flash_alert">
    <div class="left"><?php echo $message ?></div>
    <div class="right">
    	<?php echo $this->Html->link('Cerrar', 'javascript:hide_flash();', array('class' => 'tip_tip_default', 'title' => 'Cerrar este mensaje'))?>
    </div>
</div>