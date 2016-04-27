<?php

// *******************************  EBAY *******************************  

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
  		$results = '';
  	// If the response was loaded, parse it and build links
  	
  		foreach($resp->productRecommendations->product as $item) {
    		
			$pid   = $item->productId;
			$link = $item->productURL;
			$title = $item->title;
			$pic = $item->imageURL;
			$catalog = $item->catalogName;
			if(!empty($pic)) {
				// For each SearchResultItem node, build a link and append it to $results
    		$results .= "<tr><td><img src=\"$pic\"></td><td><a href=\"$link\">$title</a></td><td>$catalog</td></tr>";
			}    	
    		//$results .= "<tr><td>$pid</td></tr>";
  		}
	}
	// If the response does not indicate 'Success,' print an error
	else {
  	$results  = "<h3>Oops! The request was not successful. Make sure you are using a valid ";
  	$results .= "AppID for the Production environment.</h3>";
}


?>
<html>
	<head>
		<title>Ebay Results</title>
		
		<link href="home.css" type="text/css" rel="stylesheet" />
	</head>
	<body>
		<br />
		
			<table>
			
			<tr>
  				<td>
    				<?php echo $results;?>  
  				</td>
			</tr>
			
			</table>
	</body>
</html
