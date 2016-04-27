<?php
	error_reporting(E_ALL);  // Turn on all errors, warnings and notices for easier debugging

	//gettting query from search page
	session_start();

	$query = $_POST['search'];
// *******************************  EBAY *******************************  

	// API request variables
	$endpoint = 'http://open.api.ebay.com/shopping';  // URL to call
	$version = '1.0.0';  // API version supported by your application
	$appid = 'Anmolpre-PriceWis-PRD-ad2d4c74b-db30d8ae';  // Replace with your own Production AppID
	$globalid = 'EBAY-ENCA';  // Global ID of the eBay site you want to search
	$safequery = urlencode($query);  // Make the query URL-friendly
	$i = '0';  // Initialize the item filter index to 0
	
/////////////////////////////////////////////////////////////////////////




	// Construct the findItemsByKeywords HTTP GET call
	$apicall = "$endpoint?";
	$apicall .= "callname=GetSingleItem";
	$apicall .= "&responseencoding=XML";
	$apicall .= "&appid=$appid";
	$apicall .= "&siteid=2";
    $apicall .= "&version=949";
    $apicall .= "&ItemID=291660001430";
	
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
		

			
    	// For each SearchResultItem node, build a link and append it to $results
    		$results .= "<tr><td><img src=\"$pic\"></td><td><a href=\"$link\">$title</a></td><td>$price CAD</td><td>$pid</td></tr>";
    		//}
    	//$results .= "<tr><td>$pid</td></tr>";
  		}
	}
	// If the response does not indicate 'Success,' print an error
	else {
  	$results  = "<h3>Oops! The request was not successful. Make sure you are using a valid ";
  	$results .= "AppID for the Production environment.</h3>";
	}
 
// ****************************** EBAY ends ***************************



?>	

<html>
	<head>
		<title>Search Results</title>
		
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
</html>