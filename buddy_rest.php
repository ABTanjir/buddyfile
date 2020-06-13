<?php

// $root = strstr($_SERVER['SCRIPT_FILENAME'], 'wp-content', true);
// require_once($root.'wp-load.php');
// if(!is_user_logged_in()) return false;

/* Load libraries */
foreach (glob(__DIR__. '/drive/lib/*.php') as $lib) {
    include($lib);
}

function GUID(){
    if (function_exists('com_create_guid') === true){
        return trim(com_create_guid(), '{}');
    }
    return sprintf('%04X%04X-%04X-%04X-%04X-%04X%04X%04X', mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(16384, 20479), mt_rand(32768, 49151), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535));
}
#custom end point
function upload_file() {
        
    $current_user = wp_get_current_user();

    if(!$current_user) return false;

    $min_file_size = 20 * 1024 * 1024;  #20  mb
    $max_file_size = 200 * 1024 * 1024; #200 mb
    $upload_dir = wp_upload_dir()['basedir'].'/filebuddy'; #locar server oath
    $remote_path = '/filebuddy'; #google drive path

    #create dir if not exists
    if(!file_exists($upload_dir)){
        mkdir($upload_dir);
    }

    #validate video file
    if (($_FILES["file"]["type"] == "video/mov")
        ||  ($_FILES["file"]["type"] == "video/mp4")
        ||  ($_FILES["file"]["type"] == "video/3gp")
        ||  ($_FILES["file"]["type"] == "video/ogg")
        ){

        $file = $_FILES['file']['tmp_name'];
        $file_name = $_FILES["file"]["name"];
        $path_parts = pathinfo($file_name);
        $extension = '.'.$path_parts['extension'];
        $upload_file_name = GUID().$extension;

        
        if(($_FILES['file']['size'] >= $min_file_size) && ($_FILES['file']['size'] <= $max_file_size)){
            #stor file in google drive
            $gd_link = upload_file_gd($remote_path, $file, $upload_file_name);
            if($gd_link){
                global $wpdb;
                $wpdb->insert( 
                    'wp_buddy_files', 
                    array( 
                        'user_id' => $current_user->ID, 
                        'file_path' => $gd_link 
                    )
                );
    
                $response = array(
                    'file_name'=> $_FILES["file"]["name"],
                    'file_size'=> $_FILES["file"]["size"]/1024
                );
                echo json_encode($response);
            }

        }else{
            $response = array(
                'status'=> 'error',
                'message'=> 'File size must be 20 mb to 200 mb!'
            );
            echo json_encode($response);
        }
    }
}

add_action( 'rest_api_init', function(){
    register_rest_route( 'v1/', '/upload/', array(
            'methods' => 'POST',
            'callback' => 'upload_file'
    ) );
});
