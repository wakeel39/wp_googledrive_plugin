<?php
// insert a data 

if (isset($_POST['folder'])) {

	global $wpdb, $instance, $Drive;

	$name = $_POST['name'];
	$parent_id = "";
	if ($_POST['folder_parent_id'] != '0') {
		$parent_id =  $_POST['folder_parent_id'];
	}
	$folder = $Drive->CreateFolder($service, $parent_id, $_POST['name']);
	$folder_id = $folder['id'];
	$file_link = $folder['id'];
	if (!empty($folder_id)) {
		$colnames = "name,is_folder,folder_id";
		if ($_POST['folder_parent_id'] != '0') {
			$colnames .= ",parent_id";
		}
		$values = "'$name','1','$folder_id'";
		if ($_POST['folder_parent_id'] != '0') {
			$values .= ",'$parent_id'";
		}
		$result = $newxpert_authorized_db->google_drive_insertData("workspaces", $colnames, $values);
		if ($result) {
			$message = __('Item was successfully saved', 'custom_table_example');
		} else {
			$notice = __('There was an error while saving item', 'custom_table_example');
		}
	}
	die();
	//header("Location: ?page=google_drive");
}
if (isset($_POST['action'])) {

	if ($_GET['action'] == "del_folder" || $_GET['action'] == "del_file") {

		global $wpdb, $instance, $Drive;
		$id = $_GET['id'];
		$folder = $Drive->deleteFile($service, $id);

		if ($folder == '1') {
			$values = "folder_id = '$id'";
			$result = $newxpert_authorized_db->Delete_data("workspaces", $values);

			$values = "parent_id = '$id'";
			$result = $newxpert_authorized_db->Delete_data("workspaces", $values);
		}
		return $folder;
		exit();
		//header("Location: ?page=google_drive");
	}
}


if (isset($_POST['file'])) {

	global $wpdb, $instance, $Drive;

	//$name = $_POST['name'];
	$parent_id = "";
	if ($_POST['folder_iddd'] != '0') {
		$parent_id =  $_POST['folder_iddd'];
	}
	$folder = $Drive->CreateFile($service, $parent_id, $_FILES['file_upload']);

	$name = $folder['originalFilename'];
	$folder_id = $folder['id'];
	$file_link = $folder['webContentLink'];

	if (!empty($folder_id)) {
		$colnames = "name,is_folder,folder_id,file_link,parent_id";
		$values = "'$name','0','$folder_id','$file_link','$parent_id'";
		$result = $newxpert_authorized_db->google_drive_insertData("workspaces", $colnames, $values);
		if ($result) {
			$message = __('Item was successfully saved', 'custom_table_example');
		} else {
			$notice = __('There was an error while saving item', 'custom_table_example');
		}
	}
	die();
	//header("Location: ?page=google_drive");
}


if (isset($_POST['folder_edit'])) {

	global $wpdb, $instance, $Drive;

	//$name = $_POST['name'];
	$name = $_POST['name'];
	$folder_id = $_POST['folder_id'];

	$folder = $Drive->renameFile($service, $folder_id, $name);

	//print_r($folder_id);
	if (!empty($folder_id)) {
		$colnames = "name='$name'";
		$filters = "folder_id = '$folder_id'";
		$result = $newxpert_authorized_db->update_data("workspaces", $colnames, $filters);
		if ($result) {
			$message = __('Item was successfully saved', 'custom_table_example');
		} else {
			$notice = __('There was an error while saving item', 'custom_table_example');
		}
	}
	die();
	//header("Location: ?page=google_drive");
}


if (isset($_POST['file_edit'])) {

	global $wpdb, $instance, $Drive;

	$file_id = $_POST['file_id'];
	$folder = $Drive->UpdateFile($service, $file_id, $_FILES['file_upload']);
	if (!empty($folder['id'])) {
		$name = $folder['title'];
		$colnames = "name='$name'";
		$filters = "folder_id = '$file_id'";
		$result = $newxpert_authorized_db->update_data("workspaces", $colnames, $filters);
		if ($result) {
			$message = __('Item was successfully saved', 'custom_table_example');
		} else {
			$notice = __('There was an error while saving item', 'custom_table_example');
		}
	}
	die();

	//header("Location: ?page=google_drive");
}
