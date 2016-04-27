<?php

class UpdateProductsController extends CI_Controller{
    function __construct()
 {
   parent::__construct();
 }
    
    public function index(){
    
        $this->load->model('ProductsRepository');
        
        
        $productsRepository = new ProductsRepository();
        $products = $productsRepository->getProducts();
        //var_dump($products);
        
        foreach ($products as $product){
            //var_dump($product->username);
            if($product->website == 'amazon')
                $prod = $this->searchAmazonByID($product->product_id);
            else if($product->website == 'ebay')
                $prod = $this->searchEbayById($product->product_id);
                
            if($prod->price != $product->current_price){
                $res = $productsRepository->updateProduct($product->product_id,$prod->price);
                
                if($product->alert == 1 && $prod->price < $product->current_price)
                    $this->emailAlert($product->username,$product->current_price,$prod);
                
            }
            
            
        }
       // $this->emailAlert($product->username);
        
        
        
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
    
    
    Public function emailAlert($username,$oldPrice,$product) {
		$config = Array(		
		    'protocol' => 'smtp',
		    'smtp_host' => 'ssl://smtp.googlemail.com',
		    'smtp_port' => 465,
		    'smtp_user' => 'PriceWisePW@gmail.com',
		    'smtp_pass' => 'pricewise1234',
		    'smtp_timeout' => '4',
		    'mailtype'  => 'html', 
		    'charset'   => 'iso-8859-1'
		);

		$this->load->library('email', $config);
		
		$this->email->set_newline("\r\n");
                
                $this->load->model('UserRepository');
                $userRepository = new UserRepository();
                $user = $userRepository->getUser($username);
                //var_dump($oldPrice);
		$this->email->from('PriceWisePW@gmail.com','PriceWise - Product Alert');
		//user email
		$this->email->to($user[0]->email);
		//mail subject
		$this->email->subject('Price Alert');
		//mail contents
                $message = "Hey ".$user[0]->first_name;
                $message .= "<br><br>There has been an update in the price of the following product:";
		$message .= "<br><br><b><a href='".$product->link."'>". $product->productTitle ."</a></b>";
                $message .= "<br><br><img src='".$product->pic."'>";
                $message .="<br><br><b>Previous Price: </b>$".$oldPrice;
                $message .="<br><b>New Price: </b>$".$product->price;
                
                $this->email->message($message);
                //echo($message);
		if($this->email->send()) {
			echo "Sent\n";
		}
		else {
			show_error($this->email->print_debugger());
		}
	}
    
    
}