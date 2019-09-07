    <?php
    /* for get user id must be include this file  plugable
      1:  $user_ID = get_current_user_id();
     */
    /*if (isset($_GET['submit'])) {
        print_r($_GET);
        die();
    }*/
    require_once(ABSPATH . 'wp-includes/pluggable.php');
    require_once(rtrim(dirname(__FILE__), '/') . '/model/google_drive_list_table.php');
    require_once(rtrim(dirname(__FILE__), '/') . '/includes/page_add_offers_form.php');
    require_once(rtrim(dirname(__FILE__), '/') . '/includes/shortcode.php');
    require_once(rtrim(dirname(__FILE__), '/') . '/model/google_drive_db.php');
    require_once(rtrim(dirname(__FILE__), '/') . '/lib/googledriveclass.php');
    $newxpert_authorized_db = new google_drive_db();
    require_once(rtrim(dirname(__FILE__), '/') . '/controllers/controller.php');
    /*
      Plugin Name: google drive
      Description: This plugin is used to view google drive.
      Author: wakeel
      Version: 1.0
    */

    //update data 
    /////////////////////////////////////////////////////////////////////////////////////////
    class google_drive extends WP_Widget
    {

        // function for create table if not exists
        function google_drive_createtable()
        {
            global $wpdb;
            $sql = "CREATE TABLE IF NOT EXISTS `workspaces` (
                      `id` int(11) NOT NULL,
                      `name` varchar(255) CHARACTER SET utf8 NOT NULL,
                      `is_folder` int(11) NOT NULL,
                      `parent_id` varchar(255) NOT NULL,
                      `folder_id` varchar(255) NOT NULL,
                      `file_link` varchar(255) NOT NULL,
                      `created_date` datetime NOT NULL
                    ) ENGINE=InnoDB DEFAULT CHARSET=latin1;";



            require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
            dbDelta($sql);
        }

        //add css and js into plugin 
        static function xpertoffers_cssscirpt()
        {
            wp_register_style('xpert-prefix-style', plugins_url('assets/css/styles.css', __FILE__));
            wp_register_style('xpert-prefix-style2', plugins_url('assets/css/ezmodal.css', __FILE__));

            wp_enqueue_script('my_script', plugins_url('assets/js/script.js', __FILE__), array('jquery'), null, true);
            wp_enqueue_script('my_script2', plugins_url('assets/js/ezmodal.js', __FILE__), array('jquery'), null, true);


            //wp_register_script('xpert-prefix-script', 'http://code.jquery.com/jquery-1.11.0.min.js',array( 'jquery' ), null, true);
            // wp_register_script('xpert-prefix-script', plugins_url('assets/js/script.js', __FILE__));
            wp_enqueue_style('xpert-prefix-style');
            wp_enqueue_style('xpert-prefix-style2');

            //wp_enqueue_script('xpert-prefix-script');
        }

        //display the data list
        static function google_drive_display_list_page()
        {
            //$myListTable = new appliconic_groups_List_Table();
            $myListTable = new google_drive_List_Table();

            $myListTable->prepare_items();
            $message = '';

            if ('delete' === $myListTable->current_action()) {
                $message = '<p>' . sprintf(__('Items deleted: %d', 'custom_table_example'), count($_REQUEST['id'])) . '</p>';
            }
            if ('edit' === $myListTable->current_action()) {
                $message = '<p>' . sprintf(__('Items update: %d', 'custom_table_example'), count($_REQUEST['id'])) . '</p>';
            }



            if (!empty($notice)) :
                ?>
    <div id="notice" class="error">
        <p><?php echo $notice ?></p>
    </div>
    <?php endif; ?>
    <?php if (!empty($message)) : ?>
    <div id="message" class="updated">
        <p><?php echo $message ?></p>
    </div>
    <?php endif; ?>


    <div id="preloader" style="display: none;">
        <div id="preloader_status">

        </div>
    </div>
    <?php
            $SERVICE_ACCOUNT_EMAIL = get_option('SERVICE_ACCOUNT_EMAIL');
            $SERVICE_ACCOUNT_PKCS12_FILE_PATH = __DIR__ . "/" . get_option('SERVICE_ACCOUNT_PKCS12_FILE_PATH');
            if (empty($SERVICE_ACCOUNT_PKCS12_FILE_PATH) || empty($SERVICE_ACCOUNT_EMAIL) ||  !file_exists($SERVICE_ACCOUNT_PKCS12_FILE_PATH)) {

                ?>
    <div id="notice" class="error">
        <p>Please add your settings then google drive work. go to <b>settings->google drive settings </b></p>
    </div>
    <?php
            }
            ?>
    <div class="filemanager">



        <div class="search">
            <input type="search" placeholder="Find a file.." />
        </div>

        <div class="breadcrumbs"></div>
        <div class="btns">
            <button type="button" class="myButton" ezmodal-target="#folder_create">Create Folder</button>
            <button type="button" class="myButton" ezmodal-target="#uploadfile">Upload File</button>
        </div>
        <ul class="data"></ul>

        <div class="nothingfound">
            <div class="nofiles"></div>
            <span>No files here.</span>
        </div>

    </div>

    <div id="folder_create" class="ezmodal" ezmodal-width="500">
        <div class="ezmodal-container">
            <div class="ezmodal-header">
                <div class="ezmodal-close" data-dismiss="ezmodal">x</div>
                Create Folder
            </div>
            <div class="ezmodal-content">
                <form method="POST" id="formId2" action="<?= $_SERVER['PHP_SELF']; ?>">
                    <input type="text" name="name" placeholder="Enter Folder Name">
                    <input type="hidden" value="0" name="folder_parent_id" id="folder_parent_id">
                    <input type="hidden" name="folder" value="folder">
                    <input type="submit" value="submit" class="myButton">
                </form>
            </div>

        </div>
    </div>


    <div id="folderEdit" class="ezmodal" ezmodal-width="500">
        <div class="ezmodal-container">
            <div class="ezmodal-header">
                <div class="ezmodal-close" data-dismiss="ezmodal">x</div>
                Update Folder
            </div>
            <div class="ezmodal-content">
                <form method="POST" id="folderEdit1" action="<?= $_SERVER['PHP_SELF']; ?>">
                    <input type="text" name="name" id="editfolderName" placeholder="Enter Folder Name">
                    <input type="hidden" value="0" name="folder_id" id="folder_id">
                    <input type="hidden" name="folder_edit" value="folder_edit">
                    <input type="submit" value="submit" class="myButton">
                </form>
            </div>

        </div>
    </div>



    <div id="uploadfile" class="ezmodal" ezmodal-width="500">
        <div class="ezmodal-container">
            <div class="ezmodal-header">
                <div class="ezmodal-close" data-dismiss="ezmodal">x</div>
                Upload File
            </div>
            <div class="ezmodal-content">
                <form method="POST" id="formId" enctype="multipart/form-data" action="<?= $_SERVER['PHP_SELF']; ?>">
                    <input type="file" name="file_upload" id="file_upload">
                    <input type="hidden" value="0" name="folder_iddd" id="folder_iddd">
                    <input type="hidden" name="file" value="file">
                    <input type="submit" value="submit" class="myButton">
                </form>
            </div>

        </div>
    </div>



    <div id="editFile" class="ezmodal" ezmodal-width="500">
        <div class="ezmodal-container">
            <div class="ezmodal-header">
                <div class="ezmodal-close" data-dismiss="ezmodal">x</div>
                Update File
            </div>
            <div class="ezmodal-content">
                <form method="POST" id="editFile1" enctype="multipart/form-data" action="<?= $_SERVER['PHP_SELF']; ?>">
                    <input type="file" name="file_upload" id="file_upload">
                    <input type="hidden" value="0" name="file_id" id="file_id">
                    <input type="hidden" name="file_edit" value="file_edit">
                    <input type="submit" value="submit" class="myButton">
                </form>
            </div>

        </div>
    </div>


    <div id="ShareFileAndFolder" class="ezmodal" ezmodal-width="500">
        <div class="ezmodal-container">
            <div class="ezmodal-header">
                <div class="ezmodal-close" data-dismiss="ezmodal">x</div>
                Update File
            </div>
            <div class="ezmodal-content">
                <form method="POST" id="shareFIleandfolder" enctype="multipart/form-data" action="<?= $_SERVER['PHP_SELF']; ?>">

                </form>
            </div>

        </div>
    </div>



    <?php
            echo '</div>';
        }
    }

    ////////////////////////////////////////end class/////////////////////////////////////// 
    // add to menu page setting
    function google_drive_register_menu_page()
    {
        //add menu in the left bar admin pannel
        $hook = add_menu_page('Google drive', 'Google drive', 'activate_plugins', 'google_drive', array('google_drive', 'google_drive_display_list_page'));
        //add_submenu_page( '?page=propert_offers&action=add_offer', "Add Offer", "Add Offer", "manage_options", "add_offer", "addofferform" );
        add_options_page("Google Drive Settings", "google drive settings", "manage_options", "googledrivesettings", "google_drive_settingsFunction");
        //screen top options show
        add_action("load-$hook", array('google_drive', 'xpertoffers_cssscirpt'));
    }


    function google_drive_settingsFunction()
    {
        include('settings.php');
    }


    //ajax page calling

    function google_drive_ajax_action_function()
    {

        if ($_GET['action'] == 'my_action' && !empty($_GET['path'])) {

            /*
              $ex = explode("/", $_GET['path']);
              $total_count = count($ex);
              $name = $ex[$total_count-1];
              $newxpert_authorized_db->google_drive_fetchdata("workspaces",
             */
            die();
        } else {

            $newxpert_authorized_db = new google_drive_db();
            require_once(rtrim(dirname(__FILE__), '/') . '/includes/scan.php');
        }
    }

    //ajax call response

    add_action('wp_ajax_my_action', 'google_drive_ajax_action_function');
    //register admin menu left bar
    add_action('admin_menu', 'google_drive_register_menu_page');
    //include a scripts 
    add_action('wp_enqueue_scripts', array('google_drive', 'xpertoffers_cssscirpt'));
    add_action('wp_enqueue_scripts', 'xpertoffers_cssscirpt');
    //Create tables on plugin activation
    register_activation_hook(__FILE__, array('google_drive', 'google_drive_createtable'));
    //admin pannel setting end 
    ?>