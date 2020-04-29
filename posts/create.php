<?php
//Headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
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

if(
    !empty($data->title) &&
    !empty($data->body) &&
    !empty($data->author)
){
    // set post property values
    $post->title = $data->title;
    $post->body = $data->body;
    $post->author = $data->author;
    $post->created_at = date('Y-m-d H:i:s');
    $post->updated_at = date('Y-m-d H:i:s');
    
    // create the post
    if($post->create()){
        //201 created
        http_response_code(201);
        echo json_encode(array("message" => "Post was created."));
    }
    //unable to create the post
    else{
        //503 service unavailable
        http_response_code(503);
        echo json_encode(array("message" => "Unable to create post."));
    }
}
//data is incomplete
else{
    http_response_code(400);
    echo json_encode(array("message" => "Unable to create post. Data is incomplete."));
}