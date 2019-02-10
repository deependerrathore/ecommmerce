<?php 

namespace App\Models;
use Core\Model;
use App\Models\{Products};
use Core\H;

class ProductImages extends Model{
    public $id,$url,$product_id,$name, $deleted = 0;

    public function __construct(){
        $table = 'product_images';
        parent::__construct($table); 
    }

    public function validateImages($images){
        $files = self::restructureFiles($images);
        $errors = []; 
        $maxSize = 5242880;
        
        $allowedTypes = [IMAGETYPE_GIF,IMAGETYPE_JPEG,IMAGETYPE_PNG]; 
        //$allowedTypes = [1,2,3]; 

        foreach($files as $file){
            $name = $file['name'];
            //checking for empty uploaded
            if ($file['error'] === 4) {
                $errors['nofileuploaded'] = 'Product images are required.';
            }else{

                //checking for file size
                if ($file['size'] > $maxSize) {
                    $errors[$name] = $name . " is over the max allowed size.";
                }

                //checking for file type
                if (!in_array(exif_imagetype($file['tmp_name']),$allowedTypes)) {
                    $name = $file['name'];
                    $errors[$name] = $name . " is not an allowed file type. Please use a jpeg,png or gif.";
                }
            }
            

            
        }
        return (empty($errors)) ? true : $errors;
    }

    public static function restructureFiles($files){
        $structured = [];
        foreach($files['tmp_name']  as $key =>$val){
            $structured[$key]= [
                'tmp_name' =>$files['tmp_name'][$key],
                'name' => $files['name'][$key],
                'error' =>$files['error'][$key],
                'size' => $files['size'][$key],
                'type' => $files['type'][$key]
            ];
        }

        return $structured;
    }

    public static function uploadProductIamges($product_id,$files){
        $path = 'uploads'.DS .'product_images'.DS .'product_' .$product_id.DS;
        
        foreach ($files as $file) {

            $parts = explode('.',$file['name']);
            $ext = end($parts);
            $hash = sha1(time() .$product_id . $file['tmp_name']);

            $uploadName = $hash .'.' . $ext;
            $image = new self();
            $image->url = $path . $uploadName;
            $image->name = $uploadName;
            $image->product_id = $product_id;
            if ($image->save()) {
                if (!file_exists($path)) {
                    mkdir($path);
                }
                move_uploaded_file($file['tmp_name'],ROOT. DS . $image->url);
            }
        }
    }
    // private static function codeToMessage($code) { 
    //     switch ($code) { 
    //         case UPLOAD_ERR_INI_SIZE: 
    //             $message = "The uploaded file exceeds the upload_max_filesize directive in php.ini"; 
    //             break; 
    //         case UPLOAD_ERR_FORM_SIZE: 
    //             $message = "The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form";
    //             break; 
    //         case UPLOAD_ERR_PARTIAL: 
    //             $message = "The uploaded file was only partially uploaded"; 
    //             break; 
    //         case UPLOAD_ERR_NO_FILE: 
    //             $message = "No file was uploaded"; 
    //             break; 
    //         case UPLOAD_ERR_NO_TMP_DIR: 
    //             $message = "Missing a temporary folder"; 
    //             break; 
    //         case UPLOAD_ERR_CANT_WRITE: 
    //             $message = "Failed to write file to disk"; 
    //             break; 
    //         case UPLOAD_ERR_EXTENSION: 
    //             $message = "File upload stopped by extension"; 
    //             break; 

    //         default: 
    //             $message = "Unknown upload error"; 
    //             break; 
    //     } 
    //     return $message; 
    // } 
}