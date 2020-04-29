<?php
//Headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: PUT");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../Database.php';
include_once '../Post.php';

date_default_timezone_set('Asia/Jakarta');

//Instantiate DB & Connect
$database = new Database();
$db = $database->getConnection();

//Instantiate Post Object
$post = new Post($db);

//Get raw posted data
$data = json_decode(file_get_contents("php://input"));

if(isset($data->id)){
    $id = $data->id;
    $get_post = "SELECT * FROM `posts` WHERE id=:id";
    $get_stmt = $db->prepare($get_post);
    $get_stmt->bindValue(':id', $id,PDO::PARAM_INT);
    $get_stmt->execute();

    if($get_stmt->rowCount() > 0){
        // fetch post form database 
        $row = $get_stmt->fetch(PDO::FETCH_ASSOC);
        
        $post->id = $data->id;
        $post->title = isset($data->title) ? $data->title : $row['title'];
        $post->body = isset($data->body) ? $data->body : $row['body'];
        $post->author = isset($data->author) ? $data->author : $row['author'];
        $post->updated_at = date('Y-m-d H:i:s');

        //update the post
        if($post->update()){
            http_response_code(200);
            echo json_encode(array("message" => "Post was updated."));
        }
        //unable to update the post
        else{
            http_response_code(503);
            echo json_encode(array("message" => "Unable to update Post."));
        } 
    }
    //unable to find post id
    else{
        http_response_code(503);
        echo json_encode(array("message" => "Invalid Post Id."));
    }  
}