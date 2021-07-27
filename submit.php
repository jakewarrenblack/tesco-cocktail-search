<?php
$ingredient = $_POST['ingredients'];
$resp = null;

$url = "https://www.thecocktaildb.com/api/json/v1/1/filter.php?i=" . str_replace(" ","%20",$ingredient);

try{
    $data = [
        'collection' => 'drinks'
    ];

    $curl = curl_init($url);

    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl,CURLOPT_POST, true);
    curl_setopt($curl,CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($curl,CURLOPT_HTTPHEADER,[
        'X-RapidAPI-Host: the-cocktail-db.p.rapidapi.com',
        'X-RapidAPI-Key: 1',
        'Content-Type: application/json'
    ]);

    $response = curl_exec($curl);
    $decode = json_decode($response);
    if(is_object($decode)){
        $drinks = $decode->drinks;
    }
    
    curl_close($curl);
}
catch(Exception $e){
    echo $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<?php include 'nav.php' ?>
    <div class="container">
        <h1>Cocktails using <?= $_POST['ingredients'] ?></h1>
        <br>
        <ul class="drinks-list">
        <?php
            if(isset($drinks)){
            foreach($drinks as $drink){                
                ?>
                <form class="drink" action="drinklookup.php" method="POST">
                    <div class="img-container">
                        <img class="drink-image" src="<?= $drink->strDrinkThumb ?>" width="50" height="50">
                    </div>                    
                    <input type="hidden" name="id" value="<?=$drink->strDrink?>"></input>
                    <input type="hidden" name="img" value="<?=$drink->strDrinkThumb?>"></input>
                    <button class="drink_btn" type="submit"><?=$drink->strDrink?></button>
                </form>
                <?php
            }
        }else{
            echo '<h1 class="error">Sorry, no results for that query.</h1>';
        }
        ?>
        </ul>
    </div>
</body>
</html>