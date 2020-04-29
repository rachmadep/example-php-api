<?php
//Interact with HTTP
//Headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

include_once '../Database.php';
include_once '../Post.php';


//Instantiate DB & Connect
$database = new Database();
$db = $database->getConnection();

//Instantiate Blog Post Object
$post = new Post($db);

//Call Read Function from Post Class
$result = $post->read();

//Get row count
$num = $result->rowCount();

//Check if any posts
if($num > 0){
    //Post Array
    $posts_arr = array();
    $posts_arr['data'] = array();

    while($row = $result->fetch(PDO::FETCH_ASSOC)){
        extract($row);
        $post_item = array(
            'id' => $id,
            'title' => $title,
            'body' => html_entity_decode($body),
            'author' => $author,
            'created_at' => $created_at,
            'updated_at' => $updated_at
        );

        //Push to 'data'
        array_push($posts_arr['data'], $post_item);
    }
    //Turn to JSON & output data
    echo json_encode($posts_arr);
}else{
    //No post error
    echo json_encode(
        array('message' => 'No Posts Found.')
    );
}