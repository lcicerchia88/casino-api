<?php
/**
 * Plugin Name: Casino API
 * Plugin URI: http://example.com
 * Author: Luciano Cicerchia
 * Author URI: http://lucianocicerchia.com
 * Description: Plugin that fetchs an array of reviews from an external REST API and display them in a nice list to the user
 * Version: 1.0.0
 * License: GPL2
 * License URL: http://www.gnu.org/licenses/gpl-2.0.txt
 * text-domain: casino-api
*/

add_shortcode ('show_reviews_casino', 'show_reviews_casino_function'); // I create a shortcode in this first instance just 
//to be able to show the results in any page, for testing purposes

function show_reviews_casino_function (){
    $curl = curl_init();

    curl_setopt_array($curl, array(
    CURLOPT_URL => "https://example.api.com/casino_example",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "GET",
    CURLOPT_HTTPHEADER => array(
        "x-casino-host: test.p.casino.com",
        "x-casino-key: xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx"
    ),
    ));

    //This is how it should be
    //$response = curl_exec($curl);

    // I hardocde the response with data.json just for this example

    $response = file_get_contents('https://ezequielc13.sg-host.com/wp-content/plugins/casino_api/data.json');
    $err = curl_error($curl);

    curl_close($curl);

    if ($err) {
        echo "cURL Error #:" . $err;
    } else {
        
        //Create an array of objects from the JSON returned by the API
        $jsonObj = json_decode($response, false);

        // print_r to check the results
        //print_r($jsonObj->toplists);

    }

    $filtered_json = filter_json ($jsonObj);
    print_table ($filtered_json);
}

function filter_json ($jsonObj){
    
    foreach($jsonObj->toplists as $key=>$value){
        if ($key == 575){
            $objToPrint = $value;
            break; // As soon as I get the key 575, I end the for each, as there is no need to keep on iterating
        }
    }

    return $objToPrint;
}

function print_table ($fitered_json){
?>

<table class="table table-striped">
  <thead>
    <tr>
      <th scope="col">Casino</th>
      <th scope="col">Bonus</th>
      <th scope="col">Features</th>
      <th scope="col">Play</th>
    </tr>
  </thead>
  <tbody>

  <?php foreach ($fitered_json as $single){ ?>
        <tr>
            <td>
                <img src="<?php echo $single->logo;?>"><br>
                <a class="review-link" href="<?php echo 'https://example.com/'.$single->brand_id;?>">Review</a>
            </td>
            <td><?php 
                for ($i=0;$i<$single->info->rating;$i++){?>
                    <span style="color:orange;">&#9733;</span>
                    <?php }?>

                <?php for ($i=0;$i<(5 - $single->info->rating);$i++){?>
                    <span>&#9734;</span>
                    <?php }?>
                <p><?php echo $single->info->bonus;?></p>
            </td>
            <td><?php for ($i=0;$i<count($single->info->features);$i++){
                        echo $single->info->features[$i].'<br>';
                    }?>
            </td>
            <td>
                <a class="btn btn-primary" href="<?php echo $single->play_url;?>" role="button">Play now</a>
                <p><?php echo $single->terms_and_conditions;?></p>
            </td>
        </tr>
    <?php } ?>

  </tbody>
</table>

<?php
}