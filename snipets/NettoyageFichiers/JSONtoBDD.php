<?php
// 1 - Sélectionner un fichier geojson,
// 2 - Demander le nom de la table, l'insérer dans une variable
// 3 - Pour la géométrie et pour chaque propriété, créer un champs de formulaire, préfilled avec le nom de la propriété json, insérer dans des variables créées dynamiquement
// 4 - Créer une connexion PDO
// 5 - Requête de création de table
// 6 - Pour chaque ligne du json, requête d'insertion dans la table
?>
        <?php
        if($_POST)
        {
            var_dump($_POST);
            $jsonPath=$_POST["path"];
            $tableName=$_POST["table"];
            $type=$_POST["type"];
        }

        ?>
<script
  src="https://code.jquery.com/jquery-3.2.1.min.js"
  integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4="
  crossorigin="anonymous"></script>
<script>
    var jsonPath="<?php echo $jsonPath ?>";
    var tableName="<?php echo $tableName ?>";
    var type="<?php echo $type ?>";
    var featuresNames=[];
    var createTable="CREATE TABLE "+tableName+" (ID int NOT NULL AUTO_INCREMENT PRIMARY KEY, ";
    

    $.getJSON(jsonPath, function(data)
    {
        if(type=="json")
        {
            var startInsertInto = "INSERT INTO "+tableName+" ( ";
            var featuresLength=data.length;
            var value=data;
            laBoucle(featuresLength, 0, value, startInsertInto, type);
        }
        if(type=="geojsonpoly")
        {
            $.each(data, function(key, value)
            {
                if(key=="features")
                {
                    var startInsertInto = "INSERT INTO "+tableName+" ( "; // Le début de la requête insert into
                    var insertInto = "";
                    var featuresLength = Object.keys(value).length;
                    laBoucle(featuresLength, 0, value, startInsertInto, type);
                }
            });
        }
    });

    
    

</script>

<html>
    <head>
        <title>Insertion d'un geojson en base</title>
        <meta charset="utf-8" />
    </head>
    <body>
        <form method="POST" action="http://localhost/repoGit/snipets/NettoyageFichiers/JSONtoBDD.php">
        <select name="type">
            <option value="json">JSON simple</option>
            <option value="geojsonpoly">Polygones GeoJSON</option>
            <option value="geojsonpoints">Points GeoJSON</option>
        </select>
        <input type="text" name="path" id="lePath" placeholder="chemin du json à insérer en base">
        <input type="text" name="table" id="laTable" placeholder="nom de la table">
        <input type="submit" value="envoyer" />

        </form>
        <p id="createTabqsdqdle"></p>
        <p id="insertIntqsdsqdo"></p>
<!--
            <form method="POST" action="http://localhost/NettoyageFichiers/insertionGeojson.php">
            <input type="text" name="societe" placeholder="societe"/>
            <input type="text" name="nom_client" placeholder="nom_client" />
            <input type="text" name="adresse_client" placeholder="adresse_client" />
            <input type="text" name="cp_client" placeholder="cp_client" />
            <input type="text" name="ville_client" placeholder="ville_client" />
            <input type="text" name="telephone_client" placeholder="telephone_client" />
            <input type="text" name="email_client" placeholder="email_client" />
            <input type="text" name="fonction_contact" placeholder="fonction_contact" />
            <input type="text" name="budget_alloue" placeholder="budget_alloue" />
            <input type="submit" value="valider" /> 
       </form>
-->

    </body>
</html>

<script>
function laBoucle(featuresLength, depart, value, startInsertInto)
{
    if (depart==0)
        {
            $("#createTabqsdqdle").text("B R R R");
        }
    var arrivee=depart+50;
    var insertInto=startInsertInto;
    
    if(arrivee>=featuresLength)
    {
        arrivee=featuresLength;
    }
    
    for(v=depart; v<arrivee; v++)
    {
        if(v==depart)
        {
            if(type=="json")
            {
                var features=value[v];    
            }
            if(type=="geojsonpoly" || type=="geojsonpoints")
            {
                var features=value[v].properties;    
            }
            
            featuresNames=[];
            
            if(type=="geojsonpoly")
            {
                featuresNames.push("geom");
                featuresNames.push("centreLng");
                featuresNames.push("centreLat");
                featuresNames.push("minLat");
                featuresNames.push("maxLat");
                featuresNames.push("minLng");
                featuresNames.push("maxLng"); 
            }
            
            $.each(features, function(clay, valeuyr)
            {
                featuresNames.push(clay);
            });
            console.log(featuresNames);
            for (var k=0; k<featuresNames.length; k++)
            {
                if (k==0 && type=="geojsonpoly")
                {
                    createTable += featuresNames[k]+" TEXT,";
                    insertInto += featuresNames[k]+", ";   
                }
                else if(k<featuresNames.length-1)
                {
                    createTable += featuresNames[k]+" VARCHAR (255),";
                    insertInto += featuresNames[k]+", ";
                }
                else
                {
                    createTable += featuresNames[k]+" VARCHAR (255))";
                    insertInto += featuresNames[k]+") VALUES ";
                }
            }                      
            $("#createTabqsdqdle").text($("#createTabqsdqdle").text()+"A A A A A A A A A A A A A");
        }
        
        if(type=="geojsonpoly")
        {
            var coordinates=value[v].geometry.coordinates;
            var polygonePrincipal;
            var polygonLength=1;
            var minLat=1000;
            var maxLat=-1000;
            var minLng=1000;
            var maxLng=-1000;
            for(var ii=0; ii<coordinates.length; ii++)
            {
                if(coordinates[ii][0].length>polygonLength)
                    {
                        polygonLength=coordinates[ii][0].length;
                        polygonePrincipal=coordinates[ii][0];
                    }
                for(var w=0; w<coordinates[ii][0].length; w++)
                    {
                        if(coordinates[ii][0][w][0]<minLng)
                        {
                            minLng=coordinates[ii][0][w][0];
                        }
                        if(coordinates[ii][0][w][0]>maxLng)
                        {
                            maxLng=coordinates[ii][0][w][0];
                        }
                        if(coordinates[ii][0][w][1]<minLat)
                        {
                            minLat=coordinates[ii][0][w][1];
                        }
                        if(coordinates[ii][0][w][1]>maxLat)
                        {
                            maxLat=coordinates[ii][0][w][1];
                        }         
                    }
            }
            var centre=getCentroid(polygonePrincipal);
        }
        
        for (var k=0; k<featuresNames.length; k++)
        {   
            if(type=="geojsonpoly")
            {
                if(k==0)
                {
                    insertInto += '("'+JSON.stringify(coordinates)+'", ';
                }
                else if(k==1)
                {
                    insertInto += '"'+centre[0]+'", '
                }
                else if(k==2)
                {
                    insertInto += '"'+centre[1]+'", '
                }
                else if(k==3)
                {
                    insertInto += '"'+minLat+'", '
                }
                else if(k==4)
                {
                    insertInto += '"'+maxLat+'", '
                }
                else if(k==5)
                {
                    insertInto += '"'+minLng+'", '
                }
                else if(k==6)
                {
                    insertInto += '"'+maxLng+'", '
                }
                else if(k<featuresNames.length-1)
                {
                    insertInto += '"'+value[v].properties[featuresNames[k]]+'", ';
                }
                else if(v==(arrivee-1))
                {
                    insertInto += '"'+value[v].properties[featuresNames[k]]+'") '; 
                }
                else
                {
                    insertInto += '"'+value[v].properties[featuresNames[k]]+'"), ';
                }
            }
            if(type=="json")
            {
                if(k==0)
                {
                    insertInto += '("'+value[v][featuresNames[k]]+'", ';
                }
                else if(k<featuresNames.length-1)
                {
                    insertInto += '"'+value[v][featuresNames[k]]+'", ';
                }
                else if(v==(arrivee-1))
                {
                    insertInto += '"'+value[v][featuresNames[k]]+'") '; 
                }
                else
                {
                    insertInto += '"'+value[v][featuresNames[k]]+'"), ';
                }
            }
                

        }      
    }
    var dataToPost;
    if (depart==0)
    {
        dataToPost="action=BDD&tableCreation="+createTable+"&insertion="+insertInto+"&arrivee="+arrivee;
    }
    else
    {
        console.log(insertInto);
        dataToPost="action=BDD&tableCreation="+"prout"+"&insertion="+insertInto;        
    }
    if (arrivee==featuresLength)
    {
        $.ajax({   url:"interface.php",
                type:"POST",
                data: dataToPost,
                dataType:'json',
                success: function (data) {
                    console.log(data.insertInto);
                    console.log("FINI");
                        }
            });     
    }
    else
    {
        $.ajax({   url:"interface.php",
                type:"POST",
                data: dataToPost,
                dataType:'json',
                success: function (data) {
                    console.log("featuresLength : "+featuresLength);
                    console.log("startInsertInto : "+startInsertInto);
                    console.log(data.insertInto);
                    console.log(value);
                    laBoucle(featuresLength, arrivee, value, startInsertInto, type)
                        },
                error: function(data){
                    console.log("prout");
                    laBoucle(featuresLength, arrivee, value, startInsertInto, type);
                    console.log(data.insertInto);
                }
            }); 
    }

}
var getCentroid = function (coord) 
{
	var center = coord.reduce(function (x,y) {
		return [x[0] + y[0]/coord.length, x[1] + y[1]/coord.length] 
	}, [0,0])
	return center;
}
</script>
