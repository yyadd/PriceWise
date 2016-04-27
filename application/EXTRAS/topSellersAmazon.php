<?php


$browseId = $_POST['categories'];

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



	foreach($response->BrowseNodes->BrowseNode as $item)
      {
      	$topItem = $item->TopItemSet->TopItem->Title;
      	$itemURL = $item->TopItemSet->TopItem->DetailPageURL;
      	//$itemID = $item->TopItemSet->TopItem->ASIN;
      	
      	
      	$results .= "<tr><td><a href=\"$itemURL\">$topItem</a></td><td>$itemID</td></tr>";
      }


?>

<html>
	<head>
		<title>Search Results</title>
		
		<link href="home.css" type="text/css" rel="stylesheet" />
	</head>
	<body>
		<br />
		<form method="POST">
			<select name="categories">
  			<option value="6386372011">Apps & Games</option>
  			<option value="6948389011">Automotive</option>
  			<option value="3561347011">Baby</option>
  			<option value="6205125011">Beauty</option>
  			<option value="927726">Books</option>
  			<option value="8604904011">Clothing & Accessories</option>
  			<option value="677211011">Electronics</option>
  			<option value="9230167011">Gift Cards</option>
  			<option value="6967216011">Grocery & Gourmet Food</option>
  			<option value="6205178011">Health & Personal Care</option>
  			<option value="2206276011">Home & Kitchen</option>
  			<option value="9674384011">Jewelry</option>
  			<option value="2972706011">Kindle Store</option>
  			<option value="6205506011">Luggage & Bags</option>
  			<option value="14113311">Movies & TV</option>
  			<option value="962454">Music</option>
  			<option value="6916845011">Musical Instruments, Stage & Studio</option>
  			<option value="6205512011">Office Products</option>
  			<option value="6299024011">Patio, Lawn & Garden</option>
  			<option value="6291628011">Pet Supplies</option>
  			<option value="8604916011">Shoes & Handbags</option>
  			<option value="3234171">Software</option>
  			<option value="2242990011">Sports & Outdoors</option>
  			<option value="3006903011">Tools & Home Improvement</option>
  			<option value="6205517011">Toys & Games</option>
  			<option value="110218011">Video Games</option>
  			<option value="2235621011">Watches</option>
			</select>
			<button type="submit" name="submit" >Submit</button>
		<br />
		</form>
			<table>
			
			<tr>
  				<td>
    				<?php echo $results;?>  
  				</td>
			</tr>
			
			</table>
	</body>
</html>