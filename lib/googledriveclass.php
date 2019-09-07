<?php

ini_set('memory_limit', '-1');
ini_set('upload_max_filesize', '1000M');
ini_set('post_max_size', '1000M');
ini_set('max_input_time', 3000);
ini_set('max_execution_time', 3000);
//error_reporting(1);
ini_set('memory_limit', '-1');
require_once (__DIR__) . '/google-api-php-client/src/Google_Client.php';
require_once (__DIR__) . '/google-api-php-client/src/contrib/Google_DriveService.php';
require_once (__DIR__) . '/google-api-php-client/src/contrib/Google_Oauth2Service.php';

class GoogleDrive {
    /*
     * go to console.google.com
     * create your service account
     * replace servcice accoount email and keyfile  
     */

    var $DRIVE_SCOPE = "https://www.googleapis.com/auth/drive";
    var $SERVICE_ACCOUNT_EMAIL;
    var $SERVICE_ACCOUNT_PKCS12_FILE_PATH;
    var $service;

    /*
     * connect with service account 
     */

    function ConnectService() {
	 $this->SERVICE_ACCOUNT_PKCS12_FILE_PATH; 
        $key = file_get_contents($this->SERVICE_ACCOUNT_PKCS12_FILE_PATH);
        $auth = new Google_AssertionCredentials(
                $this->SERVICE_ACCOUNT_EMAIL, array($this->DRIVE_SCOPE), $key);

        $client = new Google_Client();
        $client->setAssertionCredentials($auth);
        if ($client->getAuth()->isAccessTokenExpired()) {
            $client->getAuth()->refreshTokenWithAssertion($auth);
        }
        $_SESSION['service_token'] = json_decode($client->getAccessToken())->access_token;

        $service = new Google_DriveService($client);
		$this->service = $service;
        return $service;
    }

    /*
     * set service
     */

    function setService($service) {

        //$this->service = $service;
    }

    /*
     * create folder
     * @param folder_name string (require)
     * @param parent_id int (optional)
     */

    function CreateFolder($service='', $parent_id = '', $folder_name) {

        $folder = new Google_DriveFile();

        if (!empty($parent_id)) {

            $parent = new Google_ParentReference();
            $parent->setId($parent_id);
            $folder->setParents(array($parent));
        }

        $folder_mime = "application/vnd.google-apps.folder";
        $folder->setTitle($folder_name);
        $folder->setMimeType($folder_mime);
        try {
            $newFolder = (array) $service->files->insert($folder);
            $folder_id = $newFolder;
        } catch (Exception $e) {
            print "An error occurred: " . $e->getMessage(); exit;
            $folder_id = "";
        }
        return $folder_id;
    }

    /*
     * create File 
     * @param parent_id int (optional)
     * @param FILE_data array (Require)
     * @param permissions array (Require)
     */

    function CreateFile($service, $parent_id = '', $FILE_data, $permissions = "") {

        $file = new Google_DriveFile();
        if (!empty($parent_id)) {
            $parent = new Google_ParentReference();
            $parent->setId($parent_id);
            $file->setParents(array($parent));
        }

        $file_name = $FILE_data["name"];
        $mime_type = $FILE_data["type"];
        $file_path = $FILE_data["tmp_name"];

        $file->setTitle($file_name);
        $file->setDescription('This is a ' . $mime_type . ' document');
        $file->setMimeType($mime_type);

        try {
            $createdFile = (array) $service->files->insert(
                            $file, array(
                        'data' => file_get_contents($file_path),
                        'mimeType' => $mime_type
                            )
            );
            //print_r($createdFile); exit;
            $file_id = $createdFile['id'];

            // if (!empty($permissions)) {
            $this->SetFilePermission($service, $file_id);
            //}
        } catch (Exception $e) {
            print "An error occurred: " . $e->getMessage();
            $file_id = "";
            $createdFile = "";
        }
        return $createdFile;
    }

    /*
     * create File from link
     * @param parent_id int (optional)
     * @param file_link string (Require)
     * @param permissions array (Require)
     */

    function CreateFileFromLink($service, $parent_id = '', $file_link, $permissions = "") {

        $file = new Google_DriveFile();
        if (!empty($parent_id)) {
            $parent = new Google_ParentReference();
            $parent->setId($parent_id);
            $file->setParents(array($parent));
        }
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime_type = finfo_file($finfo, $file_link);
        $file_name = basename($file_link);
        $file_content = file_get_contents($file_link);
        $file->setTitle($file_name);
        $file->setDescription('This is a ' . $mime_type . ' document');
        $file->setMimeType($mime_type);
        try {
            $createdFile = (array) $service->files->insert(
                            $file, array(
                        'data' => $file_content,
                        'mimeType' => $mime_type
                            )
            );
            //print_r($createdFile); exit;
            $file_id = $createdFile['id'];

            // if (!empty($permissions)) {
            $this->SetFilePermission($service, $file_id);
            //}
        } catch (Exception $e) {
            print "An error occurred: " . $e->getMessage();
            $file_id = "";
            $createdFile = "";
        }
        return $createdFile;
    }

    /*
     * file permission
     * @param file_id int (Require)
     */

    function SetFilePermission($service, $file_id) {

        if (!empty($file_id)) {
            //Give everyone permission to read and write the file
            $permission = new Google_Permission();
            $permission->setRole('writer');
            $permission->setType('anyone');
            $permission->setValue('default');
            $service->permissions->insert($file_id, $permission);
        }
    }

    /**
     * Retrieve a list of permissions.

     */
    function retrievePermissions($service, $fileId) {
        //$service = $this->service;
        try {
            $permissions = $service->permissions->listPermissions($fileId);

            return $permissions["items"];
        } catch (Exception $e) {
            print "An error occurred: " . $e->getMessage();
        }
        return NULL;
    }

    /*
     * get folder items by id
     * @param parentId int (Require)
     */

    function getResult($service, $parentId = '') {

        if (empty($parentId)) {
            $parentId = "0AKsVujZ1I6r-Uk9PVA";
        }

        $queryString = ($queryString ? ' AND ' : '') . "'{$parentId}' in parents";
        $files = (array) $service->files->list(array("q" => $queryString, "fields" => "items(downloadUrl,mimeType,webContentLink,parents(id,isRoot),fileExtension,id,thumbnailLink,title)"));
        return $files;
    }

    /*
     * copy File
     * @param parent_id int (optional)
     * @param $file_id (Require)
     * @param $file_name array (optional)

     */

    function copyFile($service, $parent_id, $file_id, $file_name) {
        $file = new Google_DriveFile();
        if (!empty($parent_id)) {
            $parent = new Google_ParentReference();
            $parent->setId($parent_id);
            $file->setParents(array($parent));
        }
        //$file->setTitle($file_name);
        try {
            $copy_file = (array) $service->files->copy($file_id, $file);
            $file_id = $copy_file["id"];
            $this->SetFilePermission($service, $file_id);
            return $copy_file;
        } catch (Exception $e) {
            print "An error occurred: " . $e->getMessage();
            $copy_file = "";
        }
        return $copy_file;
    }

    /*
     * delete file
     */

    /**
     * Permanently delete a file, skipping the trash.
     *
     * @param Google_Service_Drive $service Drive API service instance.
     * @param String $fileId ID of the file to delete.
     */
    function deleteFile($service, $file_id) {
        try {
           $folder =  $service->files->delete($file_id);
           return 1;
        } catch (Exception $e) {
            //return  "An error occurred: " . $e->getMessage();
            return 0;
        }
        //return true;
    }

    /*
     * Update file
     */

    function UpdateFile($service, $fileId, $FILE_data) {
        try {
            // First retrieve the file from the API.
            $file = $service->files->get($fileId);
            // print_r($file); exit;
            $file_name = $FILE_data["name"];
            $mime_type = $FILE_data["type"];
            $file_path = $FILE_data["tmp_name"];
            // File's new metadata.
            $file->setTitle($file_name);
            $file->setDescription("this is $mime_type file");
            $file->setMimeType($mime_type);

            // File's new content.
            $data = file_get_contents($file_path);

            $additionalParams = array(
                'newRevision' => false,
                'data' => $data,
                'mimeType' => $mime_type
            );

            // Send the request to the API.
            $updatedFile = (array) $service->files->update($fileId, $file, $additionalParams);
            
            return $updatedFile;
        } catch (Exception $e) {
            //print "An error occurred: " . $e->getMessage();
        }
    }

    /**
     * Rename a file.
     *
     * @param Google_Service_Drive $service Drive API service instance.
     * @param string $fileId ID of the file to rename.
     * @param string $newTitle New title for the file.
     * @return Google_Service_Drive_DriveFile The updated file. NULL is returned if
     *     an API error occurred.
     */
    function renameFile($service, $fileId, $newTitle) {
        try {
            //$file = new Google_Service_Drive_DriveFile();
            $file = new Google_DriveFile();
            $file->setTitle($newTitle);

           /* $updatedFile = $service->files->patch($fileId, $file, array(
                'fields' => 'title'
            )); */
             $updatedFile = (array) $service->files->patch($fileId, $file);
             
            return $updatedFile;
        } catch (Exception $e) {
           // print "An error occurred: " . $e->getMessage();
        }
    }

}

/*
 * creating folder example
 */

$SERVICE_ACCOUNT_EMAIL = get_option('SERVICE_ACCOUNT_EMAIL');
$SERVICE_ACCOUNT_PKCS12_FILE_PATH = __DIR__."/../".get_option('SERVICE_ACCOUNT_PKCS12_FILE_PATH');
if(!empty($SERVICE_ACCOUNT_PKCS12_FILE_PATH ) && !empty($SERVICE_ACCOUNT_EMAIL) && file_exists($SERVICE_ACCOUNT_PKCS12_FILE_PATH)) {
    $Drive = new GoogleDrive();
    
    $Drive->SERVICE_ACCOUNT_EMAIL = $SERVICE_ACCOUNT_EMAIL;
    $Drive->SERVICE_ACCOUNT_PKCS12_FILE_PATH = $SERVICE_ACCOUNT_PKCS12_FILE_PATH;
    $service = $Drive->ConnectService();
    $Drive->setService($service);
}

?>