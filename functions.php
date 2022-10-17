<?php

// Include Bootstrap CSS and Casino API CSS file
function casino_css() {
	wp_enqueue_style( 'bootstrap_css', 
  					'https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css', 
  					array(), 
  					'4.1.3'
  					); 
    wp_enqueue_style( 'casino_css', 
                    plugin_dir_url(__FILE__).'assets/css/casino_api_style.css', 
                    array(), 
                    '1.0.0'
                    ); 
}
add_action( 'wp_enqueue_scripts', 'casino_css');


// Include Bootstrap JS y dependencia popper
function bootstrap_js() {
	wp_enqueue_script( 'popper_js', 
  					'https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js', 
  					array(), 
  					'1.14.3', 
  					true); 
	wp_enqueue_script( 'bootstrap_js', 
  					'https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js', 
  					array('jquery','popper_js'), 
  					'4.1.3', 
  					true); 
}
add_action( 'wp_enqueue_scripts', 'bootstrap_js');


/* FUNCTION TO GET THE API RESPONSE */
function get_api_response() {
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

    // But I hardocde the response with data.json just for this example

    $response = file_get_contents('https://ezequielc13.sg-host.com/wp-content/plugins/casino_api/data.json');
    $err = curl_error($curl);

    curl_close($curl);

    if ($err) {
        echo "cURL Error #:" . $err;
    } else {
        
        //Create an array of objects from the JSON returned by the API
        $jsonObj = json_decode($response, false);

    }

    return $jsonObj;
}

/* FUNCTIONS TO SHOW CASINO API IN THE ADMIN PAGE */

function admin_casino_plugin_menu(){
    add_menu_page( 'Casino API', 'Casino API', 'manage_options', 'casino_api', 'casino_init' );
}
 
function casino_init(){
    echo "<h1>Welcome to the CASINO API Setup page</h1><br><h3>In order to show the reviews, just insert the shortcode [show_reviews_casino] in any page or widget</h2>";
}


/* FUNCTION TO FILTER JSON */

function filter_json ($jsonObj){

    
    
    foreach($jsonObj->toplists as $key=>$value){
        if ($key == 575){
            $objToPrint = $value;
            break; // As soon as I get the key 575, I end the for each, as there is no need to keep on iterating
        }
    }
    usort($objToPrint, 'sortyByPosition'); // I sort $objToPrint first, before returning it
    return $objToPrint;
}

function sortyByPosition($a, $b) { // custom function to use together with usort, in order to sort everything by position
    return $a->position > $b->position;
}

/* FUNCTION TO PRINT TABLE USING BOOTSTRAP */

function print_table ($fitered_json){
?>
<div>
    <table class="table table-striped" style="background-color:white;">
    <thead>
        <tr>
        <th class="casino-th" scope="col">Casino</th>
        <th class="casino-th" scope="col">Bonus</th>
        <th class="casino-th" scope="col">Features</th>
        <th class="casino-th" scope="col">Play</th>
        </tr>
    </thead>
    <tbody>

    <?php foreach ($fitered_json as $single){ 
        print_table_row ($single);
            
        } ?>

    </tbody>
    </table>
</div>

<?php
}

function print_table_row ($single){
    ?>
    <tr>
                <td class="casino-td" >
                    <img src="<?php echo $single->logo;?>"><br>
                    <a class="review-link" href="<?php echo 'https://example.com/'.$single->brand_id;?>">Review</a>
                </td>
                <td  class="casino-td"><?php 
                    for ($i=0;$i<$single->info->rating;$i++){?>
                        <span style="color:orange;">&#9733;</span>
                        <?php }?>

                    <?php for ($i=0;$i<(5 - $single->info->rating);$i++){?>
                        <span>&#9734;</span>
                        <?php }?>
                    <p><?php echo $single->info->bonus;?></p>
                </td>
                
                <td  class="casino-td">
                    <ul class="ul-casino">
                        <?php for ($i=0;$i<count($single->info->features);$i++){
                        
                            echo ' <li>'.$single->info->features[$i].'</li>';
                        }?>
                    </ul>
                </td>
                <td  class="casino-td">
                    <a class="btn btn-primary btn-casino" href="<?php echo $single->play_url;?>" role="button">Play now</a>
                    <p><?php echo $single->terms_and_conditions;?></p>
                </td>
            </tr>
    <?php
}