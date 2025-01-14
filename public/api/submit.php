<?php

require_once('./vendor/autoload.php');
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

// Create the logger
$logger = new Logger('submit');
// Now add some handlers
$logger->pushHandler(new StreamHandler('/tmp/my_app.log', Logger::DEBUG));
$logger->info('Submit called');

// Comment if you don't want to allow posts from other domains
header('Access-Control-Allow-Origin: *');

// Allow the following methods to access this file
header('Access-Control-Allow-Methods: POST');

// Load the FilePond class
require_once('FilePond.class.php');

// Load our configuration for this server
require_once('config.php');

// Catch server exceptions and auto jump to 500 response code if caught
FilePond\catch_server_exceptions();
FilePond\route_form_post(ENTRY_FIELD, [
    'FILE_OBJECTS' => 'handle_file_post',
    'BASE64_ENCODED_FILE_OBJECTS' => 'handle_base64_encoded_file_post',
    'TRANSFER_IDS' => 'handle_transfer_ids_post'
]);

function handle_file_post($files) {
    global $logger;
    $logger->info('handle_file_post' . $files);
    // This is a very basic implementation of a classic PHP upload function, please properly
    // validate all submitted files before saving to disk or database, more information here
    // http://php.net/manual/en/features.file-upload.php
    
    foreach($files as $file) {
        FilePond\move_file($file, UPLOAD_DIR);
    }
}

function handle_base64_encoded_file_post($files) {

    $logger->info('handle_base64_encoded_file_post' . $files);

    foreach ($files as $file) {

        // Suppress error messages, we'll assume these file objects are valid
        /* Expected format:
        {
            "id": "iuhv2cpsu",
            "name": "picture.jpg",
            "type": "image/jpeg",
            "size": 20636,
            "metadata" : {...}
            "data": "/9j/4AAQSkZJRgABAQEASABIAA..."
        }
        */
        $file = @json_decode($file);

        // Skip files that failed to decode
        if (!is_object($file)) continue;

        // write file to disk
        FilePond\write_file(
            UPLOAD_DIR, 
            base64_decode($file->data), 
            FilePond\sanitize_filename($file->name)
        );
    }

}

function handle_transfer_ids_post($ids) {
    global $logger;
    $logger->info('handle_transfer_ids_post' . implode(',',$ids));
    foreach ($ids as $id) {
        $logger->info('handling ');
        // create transfer wrapper around upload
        $transfer = FilePond\get_transfer(TRANSFER_DIR, trim($id));
        $logger->info('got transfer');
        // transfer not found
        if (!$transfer) continue;
        
        // move files
        $logger->info('moving files');
        $files = $transfer->getFiles(defined('TRANSFER_PROCESSOR') ? TRANSFER_PROCESSOR : null);
        
        foreach($files as $file) {
            $logger->info('moving files '.print_r($file,true));
            FilePond\move_file($file, UPLOAD_DIR);
        }

        // handle the metadata file
        $metadata = $transfer->getMetadata();
        print_r($metadata);

        $servername = "filepond-boilerplate-php_mariadbtest_1";
        $username = "root";
        $password = "mypass";
        $dbname = "OSPREY_UPLOAD";
        
        // Create connection
        $conn = new mysqli($servername, $username, $password, $dbname);
        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
        
 $sql = "INSERT INTO Uploads (username, filename,title) VALUES ('"  . $metadata->username .  "', '" . $metadata->filename . "','" . $metdata->title . "')";
        
         if ($conn->query($sql) === TRUE) {
             echo "New record created successfully";
         } else {
             echo "Error: " . $sql . "<br>" . $conn->error;
         }
        
        $conn->close();


        // remove transfer directory
        FilePond\remove_transfer_directory(TRANSFER_DIR, $id);
    }

}