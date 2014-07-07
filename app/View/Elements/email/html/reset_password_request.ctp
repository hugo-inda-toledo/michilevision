<p>Estimado/a <?php echo $User['User']['name'].' '.$User['User']['first_lastname']; ?>,</p>

<p>Puedes cambiar tu clave de acceso accediendo al siguiente link.</p>
<?php $url = 'http://' . env('SERVER_NAME') . ':8080/michilevision/users/reset_password_token/' . $User['User']['reset_password_token']; ?>
<p><?php echo $html->link($url, $url); ?></p>

<p>Su clave no cambiara hasta que acceda al enlace anterior.</p>
<p>Gracias y que tengas un buen dia!</p>