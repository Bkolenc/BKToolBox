
<?php
$servername = "localhost";
$username = "root";
$password = "root";
try {
    $conn = new PDO("mysql:host=$servername;dbname=cartes", $username, $password);
    // set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
catch(PDOException $e)
    {
    echo "Connection failed: " . $e->getMessage();
    }

if(isset($_POST))
{
    	if($_POST['action']=="BDD")
        {
            if($_POST['tableCreation']!="prout")
            {
                $createTableQuery=$_POST['tableCreation'];
                $conn->exec($createTableQuery);
            }
            $insertionQuery=$_POST['insertion'];

            
            $arr = array("insertInto"=>$insertionQuery, "arrivee"=>$_POST["arrivee"]);

            $conn->exec($insertionQuery);
            
            echo json_encode($arr);
        }
        if($_POST['action']=="JSON")
        {
            $table=$_POST["tableName"];
            $depart=$_POST["depart"];
            $arrivee=$_POST["depart"]+100;
            $sql = $conn->query("SELECT * FROM ".$table." WHERE id>".$depart." AND id<=".$arrivee);
            $donnees[0]=$arrivee;
            $donnees[1]=$sql->fetchAll(PDO::FETCH_ASSOC);
            
            echo json_encode($donnees);
        }
}
?>