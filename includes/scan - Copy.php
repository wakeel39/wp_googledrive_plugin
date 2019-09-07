<?php

function getItems($newxpert_authorized_db, $parent_id,$sub_name) {
    $results = $newxpert_authorized_db->google_drive_fetchdata("workspaces", "where  parent_id='" . $parent_id . "'");


    $files = array();
    foreach ($results as $result) {

        $resultss = (array) $newxpert_authorized_db->google_drive_fetchdata("workspaces", "where  parent_id='$result->folder_id'");
        //print_r($resultss); exit;
        if (count($resultss) > 0) {
            $items = getItems($newxpert_authorized_db, $result->folder_id,$sub_name."/".$result->name);
        } else { $items = array();  } 
        if ($result->is_folder == 1) {
            $type = 'folder';
            $path = $sub_name."/" . $result->name;
        } else {
            $type = "file";
            $path = $result->file_link;
        }
        $files[] = array(
            "name" => $result->name,
            "type" => $type,
            "path" => $path,
            "items" => $items
                //"items" => (array)$newxpert_authorized_db->google_drive_fetchdata("workspaces"," where parent_id='".$result->file_id."'") // Gets the size of this file
        );
    }
    return $files;
}

header('Content-type: application/json');
echo json_encode(array(
    "name" => "Main",
    "type" => "folder",
    "path" => "Main",
    "items" => getItems($newxpert_authorized_db, "","Main")
));
die();
