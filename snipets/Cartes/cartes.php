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
?>


<?php
if(isset($_POST))
{
    	if($_POST['action']=="clic")
        {
            $lat=$_POST['latClic'];
            $lng=$_POST['lngClic'];
            $villesPossibles=$conn->prepare("SELECT * FROM `lolilol`
                                            WHERE minLat < ?
                                            AND maxLat > ?
                                            AND minLng < ?
                                            AND maxLng > ?");
            $villesPossibles->execute(array($lat,$lat,$lng,$lng));
            $tableauVilles=$villesPossibles->fetchAll(PDO::FETCH_ASSOC);
            $arr = array("resultat"=>$tableauVilles);
		}
        if($_POST['action']=="clicLycees")
        {
            $lat=$_POST['latClic'];
            $lng=$_POST['lngClic'];
            $villesPossibles=$conn->prepare("SELECT * FROM `cartelycees`
                                            WHERE minLat < ?
                                            AND maxLat > ?
                                            AND minLng < ?
                                            AND maxLng > ?");
            $villesPossibles->execute(array($lat,$lat,$lng,$lng));
            $tableauVilles=$villesPossibles->fetchAll(PDO::FETCH_ASSOC);
            $arr = array("resultat"=>$tableauVilles);
		}

            echo json_encode($arr);
}

?>