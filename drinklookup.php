<?php
require_once('vendor/autoload.php');
$web = new \spekulatius\phpscraper();
$id = str_replace(" ","+",$_POST['id']);
$ingredients = null;
$tesco_output = array();

$small = getIngredients($id,$web);

function getIngredients($id,$web){
    $web->go('https://www.webtender.com/cgi-bin/search?name=' .$id. '&verbose=on');

    function getSmall($web){
        foreach($web->smalls as $small){
            if (strpos($small, 'Ingredients:') !== false) {
                return $small;
            }
        }
    }
    return getSmall($web);
}

$small_arr = explode(", ", substr($small,13));
$urls = array();

foreach($small_arr as $small_arr_item){
    array_push($urls, 'https://www.tesco.ie/groceries/product/search/default.aspx?searchBox='.str_replace(" ","%20",$small_arr_item));
}

foreach($urls as $url){
    array_push($tesco_output, tescoSearch($url,$web));
}

function tescoSearch($url,$web){
    $web->go($url);

    if(strpos(implode(',',$web->paragraphs), 'No products are available') !== false){
        /* Means Tesco doesn't have this product */
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
        <h1><?= $small ?></h1>
        <br>
        <div class="cocktail-img-contain">
            <img src="<?= $_POST['img'] ?>">
        </div>        
        <ul class="tesco_result">
            <?php
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
            ?>
        </ul>
    </div>
</body>
</html>