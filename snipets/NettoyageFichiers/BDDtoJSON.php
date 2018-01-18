<?php
if($_POST)
{
    
    
    var_dump($_POST);
    $tableName=$_POST["table"];
    $type=$_POST["type"];
    $fichier=$_POST["nomgeojson"];
}

?>
<html>
    <head>
        <title>Récupération d'un json à partir de la BDD</title>
        <meta charset="utf-8" />
    </head>
    <body>
        <form method="POST" action="http://localhost/repoGit/snipets/NettoyageFichiers/BDDtoJSON.php">
        <select name="type">
            <option value="json">JSON simple</option>
            <option value="geojsonpoly">Polygones GeoJSON</option>
            <option value="geojsonpoints">Points GeoJSON</option>
        </select>
        <input type="text" name="table" id="lePath" placeholder="nom de la table à extraire">
        <input type="text" name="nomgeojson" id="lePath" placeholder="nommer le fichier">
        <input type="submit" value="envoyer" />

        </form>
        <p id="createTabqsdqdle"></p>
        <p id="insertIntqsdsqdo"></p>

    </body>
</html>
<script
  src="https://code.jquery.com/jquery-3.2.1.min.js"
  integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4="
  crossorigin="anonymous"></script>
<script>
    Object.size = function(obj) {
    var size = 0, key;
    for (key in obj) {
        if (obj.hasOwnProperty(key)) size++;
    }
    return size;
};


    var tableName= "<?php echo $tableName; ?>";
    var type= "<?php echo $type; ?>";
    var fichier="<?php echo $fichier; ?>"
    var tableauADL=[];
    var depart=0;
    var geoJSON = new Object();
    geoJSON={ "type": "FeatureCollection",
    "features": []
     };
    geoJSONsample={ "type": "FeatureCollection",
    "features": []
     };
    console.log(geoJSON);
    
    dataToPost="action=JSON&tableName="+tableName+"&depart="+depart;
    fiftyLines(dataToPost, tableName, depart);
    function fiftyLines(dataToPost, tableName, depart)
    {
        $.ajax({   url:"interface.php",
                type:"POST",
                data: dataToPost,
                dataType:'json',
                success: function (data) {
                    dataToPost="action=JSON&tableName="+tableName+"&depart="+data[0];
                    console.log("SUCCESS");
                    var avant=tableauADL.length;
                        $.each(data[1], function(key, value){
                            tableauADL.push(value);
                        });

                           

                    if (data[0]-tableauADL.length<100)
                        {
                            fiftyLines(dataToPost, tableName, data[0]);
                        }
                    else
                        {
                            console.log("FINI");
                            var checkKeys = true;
                            var tableauFeatures=[];
                            var ii=0;
                            $.each(tableauADL, function(cle, valeur){
                                if (checkKeys)
                                    {
                                        $.each(valeur, function(ki, valiou){
                                            tableauFeatures.push(ki);
                                        });
                                        checkKeys=false;
                                    }
                                var properties={};
                                for(var i=8; i<tableauFeatures.length; i++)
                                {
                                    properties[tableauFeatures[i]]=valeur[tableauFeatures[i]];
                                }
                                
                                geoJSON.features.push({
                                           "type": "Feature",
                                           "geometry": {
                                               "type": "MultiPolygon",
                                               "coordinates": JSON.parse(valeur[tableauFeatures[1]])
                                           },
                                           "properties": properties
                                           });
//                                if(ii<500)
//                                    {
//                                         geoJSONsample.features.push({
//                                           "type": "Feature",
//                                           "geometry": {
//                                               "type": "MultiPolygon",
//                                               "coordinates": valeur[tableauFeatures[1]]
//                                           },
//                                           "properties": properties
//                                           });
//                                    }
//                                ii++;
//                                1
                            });
                            download(JSON.stringify(geoJSON), fichier+'.geojson', 'text/plain');
                        }

                        },
                error: function(data){
                    console.log("erreur");
                    console.log(data);
                }
            });
    }
    
    function download(text, name, type) {
    var a = document.createElement("a");
    var file = new Blob([text], {type: type});
    a.href = URL.createObjectURL(file);
    a.download = name;
    a.click();}
</script>