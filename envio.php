<?php
$to = 'jesus.borreguero@gmail.com';
$remite = $_POST['jcremite'];
$subject = 'Aviso de pedido';
$jcitems = $_POST['jcitems'];
$headers = 'X-Mailer: PHP/' . phpversion();
mail($to, $subject, $jcitems, $headers);
mail($remite, $subject, $jcitems, $headers);
echo 'Su pedido ha sido enviado.<br/> Nos pondremos en contacto con Vd. mediante el correo facilitado: ';
echo $remite;
echo '<br/><br/><a href="javascript:history.go(-1)">Volver a la tienda</a>';
?>
