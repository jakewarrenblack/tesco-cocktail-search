<?php

require_once('vendor/autoload.php');
$web = new \spekulatius\phpscraper();

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

function getIngredients($id,$web){
    $web->go('https://www.webtender.com/cgi-bin/search?name=' .$id. '&verbose=on');

    function getSmall($web){
        foreach($web->smalls as $small){
            if (strpos($small, 'Ingredients:') !== false) {
                return $small;
            }
        }
    }
    return $ingredients = getSmall($web);
}

// echo getIngredients('sidecar',$web);

echo tescoSearch('https://www.tesco.ie/groceries/product/search/default.aspx?searchBox=triple%20sec',$web);

?>