<?php

require_once('../../../../wp-load.php');
require_once('../model/appliconic_help_db.php');
$newxpert_authorized_db = new help_authorized_db();
//require_once(rtrim(dirname(__FILE__), '/') . '/controllers/controller.php');
if ($_REQUEST['action'] == "update_status") {
    //print_r($_REQUEST);
    $filter = "id =" . $_GET['id'];
    $colums = "status='" . $_GET['status'] . "'";
    $result = $newxpert_authorized_db->update_data($colums, $filter);
}
?>