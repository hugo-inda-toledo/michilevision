<?php
/**
 *
 * PHP 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       Cake.View.Errors
 * @since         CakePHP(tm) v 2.2.0
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
?>
<p><?php echo $this->html->image('top-mail.png')?></p>
<h3><strong>Ups! Creo que el compilador PHP ha arrojado un error, detalles a continuación.</strong></h3>

<h2><?php echo __d('cake_dev', 'Error Fatal'); ?></h2>
<p class="error">
	<strong><?php echo __d('cake_dev', 'Error Generado por PHP: '); ?>: </strong>
	<?php echo h($error->getMessage()); ?>
	<br>

	<strong><?php echo __d('cake_dev', 'Archivo afectado: '); ?>: </strong>
	<?php echo h($error->getFile()); ?>
	<br>

	<strong><?php echo __d('cake_dev', 'Numero de linea'); ?>: </strong>
	<?php echo h($error->getLine()); ?>
</p>