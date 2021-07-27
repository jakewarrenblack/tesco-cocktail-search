<?php
include('simplehtmldom/simple_html_dom.php');
$id = $_POST['id'];
$id = str_replace(" ","+",$id);
$ingredients = null;
$tesco_output = array();

$small = getIngredients($id);

function getIngredients($id){
    $html = file_get_html('https://www.webtender.com/cgi-bin/search?name=' .$id. '&verbose=on');
    foreach($html->find('html body div form') as $elements) {
        $table = $elements->find('table', 1);
        $tr = $table->find('tr',0);
        $td = $tr->find('td',1);
        $small = $td->find('small',1);
        if($small!=null){
            $small = $small->plaintext;
            return $small;
        }else{
            return 'Ingredients not found for this cocktail, sorry.';
        }
    }
    $html->clear(); 
    unset($html);
}

$small_arr = explode(", ", substr($small,13));
$urls = array();

foreach($small_arr as $small_arr_item){
    array_push($urls, 'https://www.tesco.ie/groceries/product/search/default.aspx?searchBox='.str_replace(" ","%20",$small_arr_item));
}

foreach($urls as $url){
    array_push($tesco_output, tescoSearch($url));
}

function tescoSearch($url){
    $html = file_get_html($url);
    foreach($html->find('html body #container #outer #content') as $elements){
        $div = $elements->find('div',2);
        $contentMain = $div->find('#contentMain');
        $multipleAdd = $contentMain[0]->find('#multipleAdd');
        $endFacets = $multipleAdd[0]->find('#endFacets-1');
        $ul = $endFacets[0]->find('ul');
        $li = $ul[0]->find('li',0);
        /* replace 2 or more spaces with a single space */
        return str_replace(" Add to basketQuantity", "",preg_replace('!\s+!', ' ', $li->plaintext));
    }
    $html->clear(); 
    unset($html);
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
                        <li><?= str_replace(" Alcohol can only be delivered between 11am - 10pm Monday to Saturday. Alcohol can only be delivered between 1pm and 10pm on Sunday.","",$tesco_item); ?></li>
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