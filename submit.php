<?php
include 'Request.php';

if($_POST['query'] == ""){
    header('Location: index.php');    
    return;
}

$query = $_POST['query'];

$drinks = Request::search("https://www.thecocktaildb.com/api/json/v1/1/filter.php?i=",$query);

if($drinks == null){
    $drinks = Request::search("https://www.thecocktaildb.com/api/json/v1/1/search.php?s=",$query);
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
        <h1>Cocktails matching <?= $query?></h1>
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