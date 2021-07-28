<?php
require('vendor/autoload.php');
$web = new \spekulatius\phpscraper();
/* the name of the cocktail, passed from the form on submit.php */
$id = str_replace(" ","+",$_POST['id']);
$tesco_output = array();
$ingredients = getIngredients($id,$web);

function getIngredients($id,$web){
    /* going to webtender.com to retrieve ingredients for the cocktail */
    $web->go('https://www.webtender.com/cgi-bin/search?name=' .$id. '&verbose=on');

    /* added a bit to phpscraper to return <small> tags */
    foreach($web->smalls as $small){
        /* check each <small> tag to see if it contains the word 'Ingredients:' - return it, if so */
        if (strpos($small, 'Ingredients:') !== false) {
            return explode(", ", ($small));
        }
    }
}

if($ingredients!=null){
    foreach($ingredients as $ingredient){
        /* create a Tesco search query with the ingredient appended */
        $tesco_output[] = tescoSearch('https://www.tesco.ie/groceries/product/search/default.aspx?searchBox='.str_replace(" ","%20",$ingredient),$web);
    }
}

function tescoSearch($url,$web){
    $web->go($url);

    /* if any paragraph contains this text */
    if(strpos(implode(',',$web->paragraphs), 'No products are available') !== false){
        /* Means Tesco doesn't have this product*/
        return;
    }

    if(!function_exists('getTitle')){
        function getTitle($web){
            foreach($web->h3 as $h3){
                if($h3!='Filter by area'){
                    return $h3;
                }
            }
        }
    }

    if(!function_exists('getPrice')){
        function getPrice($web){
            foreach($web->paragraphs as $price){
                if (strpos($price, '€') !== false) {
                    return $price;
                }
            }
        }
    }
    return getTitle($web) . ' ' . getPrice($web); 
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
        <h1><?= str_replace("+"," ",$id) ?></h1>
        <br>
        <h2><?= $ingredients != null ? implode(", ",$ingredients) : 'Ingredients not found.' ?></h2>
        <br>
        <div class="cocktail-img-contain">
            <img src="<?= $_POST['img'] ?>">
        </div>    
        <h4>Tesco Ireland prices:</h4>    
        <h5>(Only available ingredients listed)</h5>
        <hr>
        <ul class="tesco_result">
            <?php
            if($tesco_output !=null){
                foreach($tesco_output as $tesco_item){
                    ?>
                    <?php
                    
                    if($tesco_item != null){
                        ?>
                        <li><?= $tesco_item ?></li>
                        <?php
                    }
                    ?>
                    <?php
                }
            }else{
                ?>
                <li><?= 'No products found.' ?></li>
                <?php
            }
            ?>
        </ul>
    </div>
</body>
</html>