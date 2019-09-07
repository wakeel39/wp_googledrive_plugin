<?php
if ($_POST) {


    $targetfolder = (__DIR__) . "/";
  
    $message = "option saved.";
    if (!empty($_FILES['SERVICE_ACCOUNT_PKCS12_FILE_PATH']['name'])) {
        $targetfolder = $targetfolder . basename($_FILES['SERVICE_ACCOUNT_PKCS12_FILE_PATH']['name']);
        $ext = pathinfo($_FILES['SERVICE_ACCOUNT_PKCS12_FILE_PATH']['name'], PATHINFO_EXTENSION);
        if ($ext !== 'p12') {
            $message = 'File extension must be p12.';
        } else if (move_uploaded_file($_FILES['SERVICE_ACCOUNT_PKCS12_FILE_PATH']['tmp_name'], $targetfolder)) {
            //echo "The file " . basename($_FILES['apns_key_file']['name']) . " is uploaded";
        } else {
            // echo "Problem uploading file"; exit;
            $message = "Problem uploading file.";
        }
        update_option('SERVICE_ACCOUNT_PKCS12_FILE_PATH', $_FILES['SERVICE_ACCOUNT_PKCS12_FILE_PATH']['name']);
    }
    //Form data sent
    $SERVICE_ACCOUNT_EMAIL = $_POST['SERVICE_ACCOUNT_EMAIL'];
    update_option('SERVICE_ACCOUNT_EMAIL', $SERVICE_ACCOUNT_EMAIL);
 ?>


    <div class="updated" ><p><strong><?php _e($message); ?></strong></p></div>
    <?php
} 
    $SERVICE_ACCOUNT_EMAIL = get_option('SERVICE_ACCOUNT_EMAIL');
    $SERVICE_ACCOUNT_PKCS12_FILE_PATH = get_option('SERVICE_ACCOUNT_PKCS12_FILE_PATH');
  

//Normal page display
?>

<div class="wrap">
    <?php echo "<h2>" . __('Google Drive Settings', 'oscimp_trdom') . "</h2>"; ?>

    <form name="oscimp_form" method="post" enctype="multipart/form-data" action="<?php echo str_replace('%7E', '~', $_SERVER['REQUEST_URI']); ?>">
        <input type="hidden" name="oscimp_hidden" value="Y">

        <table class="widefat" style="width:100%">
            <thead>
                <tr><td colspan="2">
                        <?php echo "<h4>" . __('Google Drive Settings', 'T') . "</h4>"; ?>
                    </td>

            </thead>
            <tr>

                <td>
                    <?php _e("Service Account Email: "); ?>
                </td>

                <td>
                    <input type="text" style="width:80%" name="SERVICE_ACCOUNT_EMAIL" value="<?php echo $SERVICE_ACCOUNT_EMAIL; ?>" >
                </td>
            </tr>
            <tr>
                <td>
                    <p><?php _e("Service Account pem File: "); ?>            
                </td>
                <td>
                    <input type="file" name="SERVICE_ACCOUNT_PKCS12_FILE_PATH" ></p>
                </td>
            </tr>

            
            <tr>
                <td colspan="2">
                    <input type="submit" class="button-primary" name="Submit" value="<?php _e('Update Options', 'oscimp_trdom') ?>" />
                </td>
        </table>
    </form>
</div>

