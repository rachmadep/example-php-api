<?php
class Post{
    // database connection and table name
    private $conn;
    private $table = "posts";

    // object properties
    public $id;
    public $title;
    public $body;
    public $author;
    public $created_at;
    public $updated_at;

    // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
    }

    public function read(){
        //Create Query
        $query = 'SELECT * FROM '.$this->table.' ORDER BY created_at DESC';
        //Prepare Statement
        $stmt =  $this->conn->prepare($query);
        //Execute Query
        $stmt->execute();
        return $stmt;
    }

    function create(){
        // query to insert record
        $query = 'INSERT INTO
                    ' . $this->table . '
                SET
                    title=:title, 
                    body=:body, 
                    author=:author, 
                    created_at=:created_at, 
                    updated_at=:updated_at';
    
        // prepare query
        $stmt = $this->conn->prepare($query);
    
        // sanitize
        $this->title=htmlspecialchars(strip_tags($this->title));
        $this->body=htmlspecialchars(strip_tags($this->body));
        $this->author=htmlspecialchars(strip_tags($this->author));
        $this->created_at=htmlspecialchars(strip_tags($this->created_at));
        $this->updated_at=htmlspecialchars(strip_tags($this->updated_at));
    
        // bind values
        $stmt->bindParam(":title", $this->title);
        $stmt->bindParam(":body", $this->body);
        $stmt->bindParam(":author", $this->author);
        $stmt->bindParam(":created_at", $this->created_at);
        $stmt->bindParam(":updated_at", $this->updated_at);
    
        //Execute Query
        if($stmt->execute())
        {   
            return true;
        }else{
            //Print error if something goes wrong
            print_r($stmt->errorInfo());
            return false;
        }
    }
}
?>