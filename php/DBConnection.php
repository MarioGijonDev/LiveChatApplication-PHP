<?php
/*
  * PDO Database Class
  * Connect to database
  * Create prepared statements
  * Bind values
  * Return rows and results
  */

require_once 'config.php';

class DBConnection {
  private $host = DB_HOST;
  private $user = DB_USER;
  private $pass = DB_PASS;
  private $dbname = DB_NAME;

  private $dbh;
  private $stmt;
  private $error;

  public function __construct(){
    // Set DSN
    $dsn = 'mysql:host=' . $this->host . ';dbname=' . $this->dbname;
    $options = array(
      PDO::ATTR_PERSISTENT => true,
      PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    );

    // Create PDO instance
    try{
      $this->dbh = new PDO($dsn, $this->user, $this->pass, $options);
    } catch(PDOException $e){
      $this->error = $e->getMessage();
      echo $this->error;
    }
  }

  // Prepare statement with query
  public function query($sql){
    $this->stmt = $this->dbh->prepare($sql);
  }

  // Bind values
  public function bind($param, $value, $type = null){
    if(is_null($type)){
      switch(true){
        case is_int($value):
          $type = PDO::PARAM_INT;
          break;
        case is_bool($value):
          $type = PDO::PARAM_BOOL;
          break;
        case is_null($value):
          $type = PDO::PARAM_NULL;
          break;
        default:
          $type = PDO::PARAM_STR;
      }
    }

    $this->stmt->bindValue($param, $value, $type);
  }

  // Execute the prepared statement
  public function execute(){
    return $this->stmt->execute();
  }

  // Get result set as array of objects
  public function resultSet(){
    $this->execute();
    return $this->stmt->fetchAll(PDO::FETCH_OBJ);
  }

  // Get single record as object
  public function single(){
    $this->execute();
    return $this->stmt->fetch(PDO::FETCH_OBJ);
  }

  // Get row count
  public function rowCount(){
    return $this->stmt->rowCount();
  }

  // Convert characters to UTF8 encoding
  private function convertUTF8($array){
    array_walk_recursive($array, function(&$item, $key){

        if(!mb_detect_encoding($item, 'utf-8', true))
            $item = utf8_encode($item);

    });
    return $array;
  }

  public function checkEmailExists($email){
    $this->query('SELECT email FROM users WHERE email = :email');
    $this->bind(':email', $email);
    return $this->single();
  }

  public function getUser($unique_id){

    $this->query('SELECT * FROM users WHERE unique_id = :unique_id');
    $this->bind(':unique_id', $unique_id);
    return json_decode(json_encode($this->single()), true);
  }

  public function setUser($userData){
    $this->query('INSERT INTO users (unique_id, fname, lname, email, password, img, status)
                  VALUES(:unique_id, :fname, :lname, :email, :password, :img, :status)');

    $this->bind(':unique_id', $userData['unique_id']);
    $this->bind(':fname', $userData['fname']);
    $this->bind(':lname', $userData['lname']);
    $this->bind(':email', $userData['email']);
    $this->bind(':password', $userData['password']);
    $this->bind(':img', $userData['img']);
    $this->bind(':status', $userData['status']);

    if($this->execute())
      return $userData['unique_id'];
    
    return false;
  }

  private function getUserPassword($email){
    $this->query('SELECT password FROM users WHERE email = :email');
    $this->bind(':email', $email);
    return $this->single();
  }

  public function checkUser($userData){

    $passwordDB = $this->getUserPassword($userData['email'])->{'password'};

    if(password_verify($userData['password'], $passwordDB)){
      $this->query('SELECT unique_id FROM users WHERE email = :email AND password = :password');
      $this->bind(':email', $userData['email']);
      $this->bind(':password', $passwordDB);

      return $this->single()->{'unique_id'};

    }

    return false;
  }

  public function getAllUsers($unique_id){
    $this->query('SELECT * FROM users WHERE NOT unique_id = :unique_id');
    $this->bind(':unique_id', $unique_id);
    $allUsers = $this->resultSet();


    if(count($allUsers) > 0){

      return json_decode(json_encode($this->resultSet()), true);
      
    }

    return false;
  }

  public function getUserByName($searchTerm, $unique_id){
    $this->query('SELECT * FROM users WHERE NOT unique_id = :unique_id AND (fname LIKE :searchTerm)');
    $this->bind(':unique_id', $unique_id);
    $this->bind(':searchTerm', '%'.$searchTerm.'%');

    $users = $this->resultSet();

    if(count($users) > 0)
      return json_decode(json_encode($this->resultSet()), true);

    return false;
  }

  public function setMessage($msgData){
    $this->query('INSERT INTO messages (incoming_msg_id, outgoing_msg_id, msg)
                  VALUES(:incoming_msg_id, :outgoing_msg_id, :msg)');

    $this->bind(':incoming_msg_id', $msgData['incoming_msg_id']);
    $this->bind(':outgoing_msg_id', $msgData['outgoing_msg_id']);
    $this->bind(':msg', $msgData['msg']);

    if($this->execute())
      return true;
    
    return false;
  }

  public function getMessages($msgData){
    $this->query('SELECT * FROM messages LEFT JOIN users ON users.unique_id = messages.outgoing_msg_id
                WHERE
                 outgoing_msg_id = :outgoing_msg_id AND incoming_msg_id = :incoming_msg_id OR
                 outgoing_msg_id = :incoming_msg_id AND incoming_msg_id = :outgoing_msg_id
                ORDER BY msg_id ASC');

    $this->bind(':incoming_msg_id', $msgData['incoming_msg_id']);
    $this->bind(':outgoing_msg_id', $msgData['outgoing_msg_id']);

    $allMsg = $this->resultSet();

    if(count($allMsg) > 0)
      return json_decode(json_encode($this->resultSet()), true);

    
    return false;
  }

  public function getLastMsg($msgData){
    $this->query('SELECT * FROM messages WHERE 
      (incoming_msg_id = :incoming_msg_id OR outgoing_msg_id = :incoming_msg_id AND 
      outgoing_msg_id = :outgoing_msg_id OR outgoing_msg_id = :outgoing_msg_id) ORDER BY msg_id DESC LIMIT 1');

    $this->bind(':incoming_msg_id', $msgData['incoming_msg_id']);
    $this->bind(':outgoing_msg_id', $msgData['outgoing_msg_id']);

    
    return json_decode(json_encode($this->single()), true);
    
  }

  public function updateStatus($status, $unique_id){
    
    $this->query('UPDATE users SET status = :status WHERE unique_id = :unique_id');

    $this->bind(':status', $status);
    $this->bind(':unique_id', $unique_id);

    return $this->single();

  }

}

