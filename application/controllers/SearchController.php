<?php

class SearchController extends CI_Controller{
    
    public function index(){
        
    
        
    }    
    
    
    
    
    public function searchEbayByKeyword(){
        
    // *******************************  EBAY *******************************  
        
        $this->load->model('Product'); 
        $query = $_POST['searchQuery'];
        $count =$_POST['paginationNumber'];
	// API request variables
	$endpoint = 'http://svcs.ebay.com/services/search/FindingService/v1';  // URL to call
	$version = '1.0.0';  // API version supported by your application
	$appid = 'Anmolpre-PriceWis-PRD-ad2d4c74b-db30d8ae';  // Replace with your own Production AppID
	$globalid = 'EBAY-ENCA';  // Global ID of the eBay site you want to search
	//$query = 'harry potter';  // You may want to supply your own query
	$safequery = urlencode($query);  // Make the query URL-friendly
	$i = '0';  // Initialize the item filter index to 0
	

	// Construct the findItemsByKeywords HTTP GET call
	$apicall = "$endpoint?";
	$apicall .= "OPERATION-NAME=findItemsByKeywords";
	$apicall .= "&SERVICE-VERSION=$version";
	$apicall .= "&SECURITY-APPNAME=$appid";
	$apicall .= "&GLOBAL-ID=$globalid";
	$apicall .= "&keywords=$safequery";
        $apicall .= "&itemFilter.name=Condition";
        $apicall .= "&itemFilter.value=New";
        $apicall .= "&paginationInput.entriesPerPage=10";
        $apicall .= "&paginationInput.pageNumber=$count";
	

	//Parsing API call response
	
	// Load the call and capture the document returned by eBay API
	$resp = simplexml_load_file($apicall);
        
        $results = [];
        
	// Check to see if the request was successful, else print an error
	if ($resp->ack == "Success") {
  		
  	// If the response was loaded, parse it and build links
  	for($i = 0; $i< sizeof($resp->searchResult->item);$i++) {
    	$pic   = $resp->searchResult->item[$i]->galleryURL;
    	$link  = $resp->searchResult->item[$i]->viewItemURL;
    	$title = $resp->searchResult->item[$i]->title;
        $price = $resp->searchResult->item[$i]->sellingStatus->currentPrice;
	$pid   = $resp->searchResult->item[$i]->itemId;
        $site  = "ebay";
        //$obj = new stdClass;
        //var_dump($resp->searchResult->item);
        $Product = new Product();
        $obj = $Product->CreateProduct($title, $pid, $pic, $link, $price,$site);
        
        array_push($results, $obj);
        
        
        }
        
       echo json_encode($results);
        
        }
        
    }
    

    public function searchAmazonByKeyword(){
        $count =$_POST['paginationNumber'];
        $query = $_POST['searchQuery'];
        $this->load->model('Product');
        
        // ****************************** Amazon ***********************************

        // Your AWS Access Key ID, as taken from the AWS Your Account page
        $aws_access_key_id = "AKIAJDYWLCIAW32ROJQQ";

        // Your AWS Secret Key corresponding to the above ID, as taken from the AWS Your Account page
        $aws_secret_key = "2mYoL5wU5+D6KA+Jk6HqgNaP4VCI6HtpTFdY9AqZ";

        // The region you are interested in
        $endpoint = "webservices.amazon.ca";

        $uri = "/onca/xml";

        $params = array(
            "Service" => "AWSECommerceService",
            "Operation" => "ItemSearch",
            "AWSAccessKeyId" => "AKIAJDYWLCIAW32ROJQQ",
            "AssociateTag" => "pricewise04-20",
            "SearchIndex" => "All",
            "ResponseGroup" => "Images,ItemAttributes,Offers",
            "ItemPage" => $count,
            "Keywords" => $query,
            "Condition" => "New"
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
        //var_dump($response->Items->Item);
        
        
        $results = [];
        // If the response was loaded, parse it and build links
  	for($i = 0; $i< sizeof($response->Items->Item);$i++) {
        $item = $response->Items->Item[$i];
    	$pic   = $item->MediumImage->URL;
    	$link  = $item->DetailPageURL;
    	$title = $item->ItemAttributes->Title;
        $amount = $item->OfferSummary->LowestNewPrice->Amount;
        $price = ['0'=>number_format((float) ($amount / 100), 2, '.', '')];
        $pid   = $item->ASIN;
        $site  = "amazon";
        //$obj = new stdClass;
        $Product = new Product();
        $obj = $Product->CreateProduct($title, $pid, $pic, $link, $price,$site);
        //var_dump($response->Items->Item);
        array_push($results, $obj);
        
        
        }
        
        echo json_encode($results);
        
        
        

    }
    public function fetchProduct(){
        $productId = $_POST['productId'];
        $website = $_POST['site'];
        if($website == "amazon"){
            $product = $this->searchAmazonByID($productId);
            echo json_encode($product);
        }
        else if($website =="ebay"){
            $product = $this->searchEbayById($productId);
            echo json_encode($product);
        }
    }
    public function searchAmazonByID($ASIN){
        
        //$ASIN = $_POST['productId'];
        $this->load->model('Product');
        
        
        // Your AWS Access Key ID, as taken from the AWS Your Account page
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
            "ItemId"=>$ASIN,
            "IdType"=>"ASIN",
            "Condition"=>"New",
            "ResponseGroup"=>"Images,ItemAttributes,Offers",
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

        foreach ($response->Items->Item as $item)
              {
                $title = $item->ItemAttributes->Title;
                $pic = $item->MediumImage->URL;
                $amount = $item->OfferSummary->LowestNewPrice->Amount;
                $price = number_format((float) ($amount / 100), 2, '.', '');
                $code = $item->OfferSummary->LowestNewPrice->CurrencyCode;
                $link = $item->DetailPageURL;
                $asin = $item->ASIN;
                $site  = "amazon";
        //$obj = new stdClass;
                $Product = new Product();
                $obj = $Product->CreateProduct($title, $asin, $pic, $link, $price,$site);
                return ($obj);
                //$results .= "<tr><td><img src=\"$pic\"></td><td><a href=\"$link\">$title</a></td><td>$price CAD</td><td>$asin</td></tr>";
              }
             
            }
            
            
        public function searchEbayById($productId){
            $this->load->model('Product'); 
           // $productId = $_POST['productId'];
            error_reporting(E_ALL);  // Turn on all errors, warnings and notices for easier debugging

	//gettting query from search page
	session_start();

	//$query = $_POST['search'];
// *******************************  EBAY *******************************  

	// API request variables
	$endpoint = 'http://open.api.ebay.com/shopping';  // URL to call
	$version = '1.0.0';  // API version supported by your application
	$appid = 'Anmolpre-PriceWis-PRD-ad2d4c74b-db30d8ae';  // Replace with your own Production AppID
	$globalid = 'EBAY-ENCA';  // Global ID of the eBay site you want to search
	//$safequery = urlencode($query);  // Make the query URL-friendly
	$i = '0';  // Initialize the item filter index to 0
	
/////////////////////////////////////////////////////////////////////////




	// Construct the findItemsByKeywords HTTP GET call
	$apicall = "$endpoint?";
	$apicall .= "callname=GetSingleItem";
	$apicall .= "&responseencoding=XML";
	$apicall .= "&appid=$appid";
	$apicall .= "&siteid=2";
        $apicall .= "&version=949";
        $apicall .= "&ItemID=".$productId;
	
	//Parsing API call response
	
	// Load the call and capture the document returned by eBay API
	$resp = simplexml_load_file($apicall);
	//print_r($resp);	
		
	// Check to see if the request was successful, else print an error
	if ($resp->Ack == "Success") {
  		$results = '';
  	// If the response was loaded, parse it and build links
  	
  		foreach($resp->Item as $item) {
    		$pic   = $item->GalleryURL;
    		$link  = $item->ViewItemURLForNaturalSearch;
    		$title = $item->Title;
                $price = $item->ConvertedCurrentPrice;
		$pid   = $item->ItemID;
			//$ePid =  $item->productId;
			//if(!empty($ePid)) {
                $site = 'ebay';
		$Product = new Product();
                $obj = $Product->CreateProduct($title, $pid, $pic, $link, $price,$site);
                return ($obj);

			
    	// For each SearchResultItem node, build a link and append it to $results
    		//$results .= "<tr><td><img src=\"$pic\"></td><td><a href=\"$link\">$title</a></td><td>$price CAD</td><td>$pid</td></tr>";
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