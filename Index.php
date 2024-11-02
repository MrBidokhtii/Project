<?php
class Start{
    private $connection;
    private $host = 'localhost';
    private $user = 'root';
    private $pass = '';
    private $datadb = 'datadb';
    public function __construct(){
        $this->connection = new mysqli($this->host, $this->user, $this->pass, $this->datadb);
        if($this->connection->connect_error){
            die("connecetion failed : " . $this->connection->connect_error);
        }
    }
    public function getConnect(){
        return $this->connection;
    }
}
class Avrg {
    private $connection;
    public function __construct(Start $connection){
        $this->connection = $connection->getConnect();
    }
    public function save($name, $family){
        $stmt = $this->connection->prepare("INSERT INTO chatroom (name, text) VALUES (?,?)");
        $stmt->bind_param("ss", $name, $family);
        $stmt->execute();
        $stmt->close();
    }
 public function getInput(){
  $input = $this->connection->query("SELECT name,text FROM chatroom");
  $data = [];
  while($row = $input->fetch_assoc()){
   $data[] = $row;
  }
  return $data;
 }
}
if(isset($_GET['name']) && isset($_GET['text']))
{
 $connection = new Start();
    $avrgs = new Avrg($connection);
    $avrgs->save($_GET['name'], $_GET['text']);
    $connection->getConnect()->close();
}
$connection = new Start();
$avrgs = new Avrg($connection);
$data = $avrgs->getInput();
$connection->getConnect()->close();
?>
<form method="GET" action="">
 <input typr="text" name="name" placeholder="Name ..."><br>
 <input typr="text" name="text" placeholder="Enter Messsage ..."><br>
 <input type="submit" value="send">
</form>
<p><?php foreach($data as $item){ echo htmlspecialchars($item['name']) . " : " . htmlspecialchars($item['text']) . "<br>";}?></p>
