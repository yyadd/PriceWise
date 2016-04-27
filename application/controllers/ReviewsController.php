<?php

class ReviewsController extends CI_Controller{
    
    public function index(){
        
    }
    
    public function getAmazonReviews(){
        
        $prodId = $_POST['prodId'];
        
        $aws_access_key_id = "AKIAJDYWLCIAW32ROJQQ";

        // Your AWS Secret Key corresponding to the above ID, as taken from the AWS Your Account page
        $aws_secret_key = "2mYoL5wU5+D6KA+Jk6HqgNaP4VCI6HtpTFdY9AqZ";

        // The region you are interested in
        $endpoint = "webservices.amazon.ca";

        $uri = "/onca/xml";

        $params = array(
            "Service" => "AWSECommerceService",
            "Operation" => "ItemLookup",
            "AWSAccessKeyId" => "AKIAJDYWLCIAW32ROJQQ",
            "AssociateTag" => "pricewise04-20",
            "ItemId" => $prodId,
            "ResponseGroup" => "Reviews,Small",
            "TruncateReviewsAt" => "256",
            "IncludeReviewsSummary" => "true"
        );

        // Set current timestamp if not set
        if (!isset($params["Timestamp"])) {
            $params["Timestamp"] = gmdate('Y-m-d\TH:i:s\Z');
        }

        // Sort the parameters by key
        ksort($params);

        $pairs = array();

        foreach ($params as $key => $value) {
            array_push($pairs, rawurlencode($key)."=".rawurlencode($value));
        }

        // Generate the canonical query
        $canonical_query_string = join("&", $pairs);

        // Generate the string to be signed
        $string_to_sign = "GET\n".$endpoint."\n".$uri."\n".$canonical_query_string;

        // Generate the signature required by the Product Advertising API
        $signature = base64_encode(hash_hmac("sha256", $string_to_sign, $aws_secret_key, true));

        // Generate the signed URL
        $request_url = 'http://'.$endpoint.$uri.'?'.$canonical_query_string.'&Signature='.rawurlencode($signature);

        //echo "Signed URL: \"".$request_url."\"";


                $response = simplexml_load_file($request_url);
        //var_dump($response);
        $results;
        foreach ($response->Items->Item as $item)
      {
      	$topSeller = $item->CustomerReviews->IFrameURL;
      	
      	//$results .= "<iframe src=\"$topSeller\" />";
        
      }
        
      echo json_encode($topSeller);
        
        
    } 
    
public function getEbayReviews(){
    //$itemId = $_POST['itemId'];
    $itemId = 272204315193;
        $prodId = $this->getEbayProductId($itemId);
    if($prodId != NULL){
 
// *******************************  EBAY *******************************  

	// API request variables
        $endpoint = 'http://svcs.ebay.com/services/search/FindingService/v1';  // URL to call
        $version = '1.0.0';  // API version supported by your application
        $appid = 'Anmolpre-PriceWis-PRD-ad2d4c74b-db30d8ae';  // Replace with your own Production AppID
        $globalid = 'EBAY-ENCA';  // Global ID of the eBay site you want to search
        //$safequery = urlencode($query);  // Make the query URL-friendly
        $i = '0';  // Initialize the item filter index to 0
	
/////////////////////////////////////////////////////////////////////////

	// Construct the findItemsByKeywords HTTP GET call
	$apicall = "$endpoint?";
        $apicall .= "OPERATION-NAME=findItemsByProduct";
        $apicall .= "&SERVICE-VERSION=$version";
        $apicall .= "&SECURITY-APPNAME=$appid";
        $apicall .= "&GLOBAL-ID=$globalid";
        $apicall .= "&RESPONSE-DATA-FORMAT=XML";
        $apicall .= "&REST-PAYLOAD";
        $apicall .= "&productId.@type=ReferenceID";
        $apicall .= "&productId=215876420";



        //Parsing API call response
        //$reviews =  curl_download('http://search.reviews.ebay.ca/Message-in-a-Bottle-DVD-1999-Widescreen_UPC_085391698920?fvcs=1166&sopr=3298066&upvr=2');
	// Load the call and capture the document returned by eBay API
	$resp = simplexml_load_file($apicall);
	//var_dump($resp->searchResult->item);	
	//var_dump()	
	// Check to see if the request was successful, else print an error
	if ($resp->ack == "Success") {
  		$results = '';
  	// If the response was loaded, parse it and build links
  	$response = $resp->searchResult->item;
          //var_dump($response[0]);
  		//foreach($resp->searchResult->item as $item) {
                        //var_dump($item);
			//$avgRate   = $item->Rating;
			//$link = $item->URL;
                        $reviews =  $this->curl_download($response[0]->viewItemURL);
			//$title = $item->Title;
		var_dump($reviews);
                //echo json_encode($reviews);/////////////////////////
    		//$results .= "<tr><td><a href=\"$link\">$title</td></tr>";
  		//}
	}
	// If the response does not indicate 'Success,' print an error
	else {
  	$results  = "<h3>Oops! The request was not successful. Make sure you are using a valid ";
  	$results .= "AppID for the Production environment.</h3>";
        //echo json_encode($results);
        
       
        }
    }
    else
        echo json_encode("NULL");

}

public function curl_download($url) {
//initalize session
$ch = curl_init();
//echo($url);
//set options

curl_setopt($ch,CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$output = curl_exec($ch);
//var_dump($output);

$start = strpos($output, '<div id="rwid"');
$end = strpos($output, '</body>');
$length = $end-$start;
$output = substr($output, $start, $length);


curl_close($ch);
  
return($output);
}




public function getEbayProductId($itemId){
    // API request variables
        $endpoint = 'http://open.api.ebay.com/shopping';  // URL to call
        //$version = '1.0.0';  // API version supported by your application
        $appid = 'Anmolpre-PriceWis-PRD-ad2d4c74b-db30d8ae';  // Replace with your own Production AppID
        $globalid = 'EBAY-ENCA';  // Global ID of the eBay site you want to search
        $i = '0';  // Initialize the item filter index to 0




        // Construct the findItemsByKeywords HTTP GET call
        $apicall = "$endpoint?";
        $apicall .= "callname=GetSingleItem";
        $apicall .= "&responseencoding=XML";
        $apicall .= "&appid=$appid";
        $apicall .= "&siteid=2";
        $apicall .= "&version=949";
        $apicall .= "&ItemID=$itemId";

        //Parsing API call response

        // Load the call and capture the document returned by eBay API
        $resp = simplexml_load_file($apicall);
        //var_dump($resp);

        // Check to see if the request was successful, else print an error
        if ($resp->Ack == "Success") {
                $results = '';
        // If the response was loaded, parse it and build links
                    //var_dump($resp);
                foreach($resp->Item as $item) {
                        if($item->productId){
                        return $item->productId;
                        }
                        else
                            return NULL;
                //}
        //$results .= "<tr><td>$pid</td></tr>";
                }
        }
        // If the response does not indicate 'Success,' print an error
        else {
        $results  = "<h3>Oops! The request was not successful. Make sure you are using a valid ";
        $results .= "AppID for the Production environment.</h3>";
        }
}
    
    
}

