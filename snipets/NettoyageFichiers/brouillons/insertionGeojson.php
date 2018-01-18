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
        }

        ?>
<script
  src="https://code.jquery.com/jquery-3.2.1.min.js"
  integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4="
  crossorigin="anonymous"></script>
<script>
    var jsonPath="<?php echo $jsonPath ?>";
    var tableName="<?php echo $tableName ?>";
    var featuresNames=[];
    
    
    // PLUSIEURS REQUETES
    // 1 - CREATION DE LA TABLE
    // 2 - CREATION DE CHAQUE COLONNE
    // 3 - INSERTION DE CHAQUE LIGNE
    
    $.getJSON(jsonPath, function(data){
        $.each(data, function(key, value){
            
            if(key=="features")
                {
                    var i=1;
                    $.each(value, function(cle, valeur){
                    // A la première ligne...
                    if(i==1)
                        {
                            // On stocke les noms de colonnes (clés des features)...
                            var features=valeur.properties;
                            $.each(features, function(clay, valeuyr){
                                featuresNames.push(clay);
                            });
                            console.log(featuresNames);
                            // On a besoin de la longueur de featuresNames
                            var dataToPost="";
                            var dataToPost='action=tableName$featuresLength='+featuresNames.length+'&table='+tableName;
                            for (var k=0; k<featuresNames.length; k++)
                            {
                                dataToPost+="&feature"+(k+1)+"="+featuresNames[k];        
                            }
                            console.log(dataToPost);
                            i++

                        };
                        
                            $.ajax(
                            {
                                url:"interface.php",
                                async: true,
                                type:"POST",
                                data: dataToPost,
                                dataType:'html',
                                success: function (data) {
                                    console.log(data);
                                        }
                            });
                    });

                            
                }
    });
});
        
</script>

<html>
    <head>
        <title>Insertion d'un geojson en base</title>
        <meta charset="utf-8" />
    </head>
    <body>
        <form method="POST" action="http://localhost/NettoyageFichiers/insertionGeojson.php">
    
        <input type="text" name="path" id="lePath" placeholder="chemin du geojson à insérer en base">
        <input type="text" name="table" id="laTable" placeholder="nom de la table">
        <input type="submit" value="envoyer" />

        </form>
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
