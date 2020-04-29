<?php
//Headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: DELETE');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

include_once '../Database.php';
include_once '../Post.php';


//Instantiate DB & Connect
$database = new Database();
$db = $database->getConnection();

//Instantiate Blog Post Object
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
        //Set ID to update
        $post->id = $data->id;

        // delete the post
        if($post->delete()){
            http_response_code(200);
            echo json_encode(array("message" => "Post was deleted."));
        }
        //unable to delete the post
        else{
            http_response_code(503);
            echo json_encode(array("message" => "Unable to delete post."));
        }
    }
    //unable to find post id
    else{
        http_response_code(503);
        echo json_encode(array("message" => "Invalid Post Id."));
    }  
}