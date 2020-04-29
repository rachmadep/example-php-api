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

    public function read_single(){
        //Create Query
        $query = 'SELECT * FROM '.$this->table.' WHERE id=:id';
        //Prepare Statement
        $stmt =  $this->conn->prepare($query);

        $this->id=htmlspecialchars(strip_tags($this->id));
        $stmt->bindParam(':id', $this->id);

        //Execute Query
        if($stmt->execute())
        {   
            return $stmt;
        }else{
            //Print error if something goes wrong
            print_r($stmt->errorInfo());
        }
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

    function update(){
        // update query
        $query = 'UPDATE
                    ' . $this->table . '
                SET
                    title=:title, 
                    body=:body, 
                    author=:author, 
                    updated_at=:updated_at
                WHERE
                    id=:id';
    
        // prepare query statement
        $stmt = $this->conn->prepare($query);
    
        // sanitize
        $this->title=htmlspecialchars(strip_tags($this->title));
        $this->body=htmlspecialchars(strip_tags($this->body));
        $this->author=htmlspecialchars(strip_tags($this->author));
        $this->updated_at=htmlspecialchars(strip_tags($this->updated_at));
        $this->id=htmlspecialchars(strip_tags($this->id));
    
        // bind new values
        $stmt->bindParam(":title", $this->title);
        $stmt->bindParam(":body", $this->body);
        $stmt->bindParam(":author", $this->author);
        $stmt->bindParam(":updated_at", $this->updated_at);
        $stmt->bindParam(':id', $this->id);
    
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

    function delete(){
        // delete query
        $query = 'DELETE FROM '. $this->table .' WHERE id = :id';
      
        // prepare query
        $stmt = $this->conn->prepare($query);
      
        // sanitize
        $this->id=htmlspecialchars(strip_tags($this->id));
      
        // bind id of record to delete
        $stmt->bindParam(':id', $this->id);
      
        // execute query
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