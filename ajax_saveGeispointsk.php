<?php
/*
* (c) 2015 Geis CZ s.r.o.
*/
  require_once('../../config/config.inc.php');
  require_once('../../init.php');
  
if(!$cart || !$cart->id) return;
  
$db = Db::getInstance();


if($db->getValue('select 1 from `'._DB_PREFIX_.'geispointsk_order` where id_cart=' . ((int) $cart->id))) {
    $db->execute('update `'._DB_PREFIX_.'geispointsk_order` set id_gp="' . (Tools::getValue('selectedGeispointskId')) . '" where id_cart=' . ((int) $cart->id));
}
else {
    $db->execute('insert into `'._DB_PREFIX_.'geispointsk_order` set id_gp="' . (Tools::getValue('selectedGeispointskId')) . '", id_cart=' . ((int) $cart->id));
}
  
header("Content-Type: application/json");
echo Tools::jsonEncode(array('success' => true));
?>