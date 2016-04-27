<?php

class DealsController extends CI_Controller{
    
    public function index(){
        
    
        
    }
    
    function ebayDeals(){
    // *******************************  EBAY *******************************  
        $this->load->model('Product');
	// API request variables
	$endpoint = 'http://svcs.ebay.com/MerchandisingService';  // URL to call
	$version = '1.0.0';  // API version supported by your application
	$appid = 'Anmolpre-PriceWis-PRD-ad2d4c74b-db30d8ae';  // Replace with your own Production AppID
	
/////////////////////////////////////////////////////////////////////////

	// Construct the findItemsByKeywords HTTP GET call
	$apicall = "$endpoint?";
	$apicall .= "OPERATION-NAME=getTopSellingProducts";
	$apicall .= "&SERVICE-NAME=MerchandisingService";
	$apicall .= "&SERVICE-VERSION=$version";
	$apicall .= "&CONSUMER-ID=$appid";
	$apicall .= "&RESPONSE-DATA-FORMAT=XML";
	$apicall .= "&REST-PAYLOAD";
	$apicall .= "&maxResults=5";



//Parsing API call response
	
	// Load the call and capture the document returned by eBay API
	$resp = simplexml_load_file($apicall);
	//print_r($resp);	
		
	// Check to see if the request was successful, else print an error
	if ($resp->ack == "Success") {
  		$results = [];
  	// If the response was loaded, parse it and build links
  	
  		foreach($resp->productRecommendations->product as $item) {
                        //echo("<br><br>");
                        //var_dump($item);
                        $minPrice = $item->priceRangeMin;
			$pid   = $item->productId;
			$link = $item->productURL;
			$title = $item->title;
			$pic = $item->imageURL;
			$catalog = $item->catalogName;
			if(!empty($pic)) {
				// For each SearchResultItem node, build a link and append it to $results
    		//$results .= "<tr><td><img src=\"$pic\"></td><td><a href=\"$link\">$title</a></td><td>$catalog</td></tr>";
                $Product = new Product();
                $obj = $Product->CreateProduct($title, $pid, $pic, $link, $minPrice,$catalog);
                array_push($results,$obj);
			}    	
    		//$results .= "<tr><td>$pid</td></tr>";
  		}
	}
	// If the response does not indicate 'Success,' print an error
	else {
  	//$results  = "<h3>Oops! The request was not successful. Make sure you are using a valid ";
  	//$results .= "AppID for the Production environment.</h3>";
            echo ("<h3>Oops! The request was not successful. Make sure you are using a valid "."AppID for the Production environment.</h3>");
        }

       echo json_encode($results);
    
    
    }
    
    function amazonDeals(){
        $this->load->model('Product');
       
        $browseId = $_POST['browseId'];
        //var_dump($browseId);
// Your AWS Access Key ID, as taken from the AWS Your Account page
	$aws_access_key_id = "AKIAJDYWLCIAW32ROJQQ";

// Your AWS Secret Key corresponding to the above ID, as taken from the AWS Your Account page
	$aws_secret_key = "2mYoL5wU5+D6KA+Jk6HqgNaP4VCI6HtpTFdY9AqZ";

// The region you are interested in
	$endpoint = "webservices.amazon.ca";

	$uri = "/onca/xml";

	$params = array(
    	"Service" => "AWSECommerceService",
    	"Operation" => "BrowseNodeLookup",
    	"AWSAccessKeyId" => "AKIAJDYWLCIAW32ROJQQ",
    	"AssociateTag" => "pricewise04-20",
    	"BrowseNodeId" => $browseId,
    	"ResponseGroup" => "TopSellers"
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

             $results =[];

                foreach($response->BrowseNodes->BrowseNode->TopItemSet->TopItem as $item)
              { //echo('<br><br>');
                //var_dump($item);
                $title = $item->Title;
                $link = $item->DetailPageURL;
                //$itemID = $item->TopItemSet->TopItem->ASIN;
                $minPrice = '';
		$pid   = $item->ASIN;
                $pic = '';
                $catalog ='';
                
                $Product = new Product();
                $obj = $Product->CreateProduct($title, $pid, $pic, $link, $minPrice,$catalog);
                //var_dump($obj);
                array_push($results,$obj);

                //$results .= "<tr><td><a href=\"$itemURL\">$topItem</a></td><td>$itemID</td></tr>";
              }
              
              echo json_encode($results);
            }
    
}

