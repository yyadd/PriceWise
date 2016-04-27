<?php

if(isset($_POST['submit'])) {
	$query = $_POST['search'];
	if(!empty($query)) {
			session_start();

			$_SESSION['search'] = $query;
	
		} //empty if ends
	} //isset if ends
?>        
<!-- Build the HTML page with values from the call response -->
<html>
	<head>
		<title>PriceWise : <?php echo $firstName?> <?php echo $lastName?></title>
		<!-- <style type="text/css">body { font-family: arial,sans-serif;} </style> -->
                <base href="<?php echo base_url(); ?>">
                <link href="assets/css/home.css" type="text/css" rel="stylesheet" />
                <script src="<?php echo base_url(); ?>/assets/js/jquery-1.12.1.js" type="text/javascript"></script>
                <script src="<?php echo base_url(); ?>/assets/js/main.js" type="text/javascript"></script>
                <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
                <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
                <script type="text/javascript">
                    google.charts.load('current', {packages: ['corechart', 'line']});
                    google.charts.setOnLoadCallback(drawChart);
                </script>
                
	</head>
	<body>
            <div id="navBar">
                <div id="PWtitle">
                    <div id="LoggedInUser" style="font-size:18px;float: right;width: 200px;min-height: 10px;padding-top: 10px;padding-bottom: 10px;"><b><?php echo $firstName?> <?php echo $lastName?></b></div>
                    <input id="LoggedInUsername" type="hidden" value="<?php echo $username ?>">
                    <div id="PWname">
                        <div style="float:left;width: 200px;height:10px;">
                            <button id="compareProductsButton" onclick="compareProducts();" name="submit" class="submit">Compare</button>   
                        </div>
                        <img style="height: 50px;" src="<?php echo base_url(); ?>assets/img/logo.png"/>
                        <!-- <span>Price</span><span>Wise</span> -->
                    
                        
                    </div>
                    
                </div>
                <ul id="navul">
                    <li><a class="active" href="javascript:homeButtonClicked()">Home</a></li>
                    <li><a href="javascript:getEbayDeals();javascript:getAmazonDeals();">Deals</a></li>
                    <li><a href="javascript:getMyWishlist('<?php echo $username ?>');">Wish List</a></li>
                    <li><a href="javascript:getMyComparisons('<?php echo $username ?>');">My Comparisons</a></li>
                    <li><a href="">About</a></li>
                    
                    <li style="float:right;"><a href="javascript:logout()">Logout</a></li>
                </ul>
                <div id="changePassword" style="display:none;">
                    <div style="padding: 2px;"> <input type="text" id="oldPassword" placeholder="Old Password" class="login"> </div>
                    <div style="padding: 2px;"> <input type="text" id="newPassword" placeholder="New Password" class="login"> </div>
                    <div style="padding: 2px;"> <input type="password" id="confirlPassword" placeholder="Confirm Password" class="login"> </div>
                    <button  onclick="changePassword();" name="Login" id="changePasswordButton" class="submit" style="">Update Password</button>
                
                </div>
            </div>
		<!-- <form id="searchForm" method="POST" action="SearchController"> -->
                <div id="searchDiv">
                <div id="searchForm">
                    <h2>Save big, Shop big <br/></h2>
                    <button onclick ="submitSearchLoggedIn();" name="submit" class="submit" id="searchButton">Search</button>
                    <input type="text" name="search" class="search" id="searchBox" value="" placeholder="Enter Product"/>
                    
		</div>
                <!-- </form>-->
            <div id="searchInDiv">
                <fieldset>
                    <legend>Search in?</legend>
                    <input type="checkbox" name="searchIn[]" value="Amazon" checked="true" onchange="checkBoxCheck();"/>Amazon
                    <input id="ebay" type="checkbox" name="searchIn[]" value="Ebay" onchange="checkBoxCheck();" />Ebay 
                            
                </fieldset>
            </div>
                
                <div id="resultsDiv" style="display: none; width:900px;">
                   
                    
                    <div id="ebayDiv">
                        
                    </div>
                    
                    <div id="amazonDiv" >
                        
                    </div>
                     
                    </div> 
                <div id="pagination" class="clickable" style="display:none;"onclick="updatePagination();">MORE</div>
                </div>
                
                <div id="comparisonDiv" style="display: none;width:100%;">
                    <div style="float:right;width: 100%;margin-bottom: 10px;">
                        
                            <button id="closeCompariosnButton" onclick="closeComparison();" name="submit" class="submit" style="display: block;width: 25px;padding: 2px;height: 25px;margin-right: 0px;font-size: 15px;" title="Close Comparison">X</button>   
                            <button id="saveCompariosnButton" onclick="saveComparison();" name="submit" class="submit" style="display: block;width: 50px;padding: 2px;height: 25px;margin-right: 0px;font-size: 15px;margin-right:10px;" title="Save Comparison">Save</button>
                    </div>
                    
                    <table border='1' style="background-color: #f5f5f5;margin-left:auto;margin-right:auto;margin-:10px;">
                        <tr id="siteName">
                            <th>Site Name</th>
                        </tr>
                        <tr id="imageRow">
                            <th>Image</th>
                        </tr>
                        <tr id="titleRow">
                            <th>Title</th>
                        </tr>
                        <tr id="priceRow">
                            <th>Price</th>
                        </tr>
                        <tr id="reviewRow">
                            <th>Reviews</th>
                        </tr>
                    </table>
                </div> 
                <div id="dealsDiv" style="display:none;">
                    <div class="logoDiv"><img src="http://localhost:8080/PW/application/assets/img/ebay_large.png" id="ebayDealsLogo"></div>
                    <div id="ebayDeals" style="display:none;"></div>    
                    
                    <div class = "logoDiv"><img src="http://localhost:8080/PW/application/assets/img/amazon_large.png" id="amazonDealsLogo"></div>
                    <select name="categories" style="width: 200px;" onchange="getAmazonDeals();" id="amazonDealsCatagories">
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
                    <div id="amazonDeals" style="display:none;"></div>
                </div>
                
                <div id="wishlistDiv" style="display:none">
                    <div style="float:right;width: 100%;margin-bottom: 10px;">
                            <button id="closeWishlistButton" onclick="closeWishlist();" name="submit" class="submit" style="display: block;width: 25px;padding: 2px;height: 25px;margin-right: 0px;font-size: 15px;" title="Close Comparison">X</button>   
                    </div>
                    <h3 style="color:white;">Your Wish List</h3>
                    
                        <table id="wishListTable" border='1' style="background-color: #f5f5f5;margin-left:auto;margin-right:auto;margin-:10px;">
                    </table>
                    
                </div>
                
                <div id="MyComparisonsDiv" style="display:none">
                    <div style="float:right;width: 100%;margin-bottom: 10px;">
                            <button id="closeComparisonsButton" onclick="closeComparisons();" name="submit" class="submit" style="display: block;width: 25px;padding: 2px;height: 25px;margin-right: 0px;font-size: 15px;" title="Close Comparison">X</button>   
                    </div>
                    <h3 style="color:white;">Your Saved Comparisons</h3>
                    
                       <!-- <table id="comparisonsTable" border='1' style="background-color: #f5f5f5;margin-left:auto;margin-right:auto;margin-:10px;">
                    </table> -->
                    
                </div>
                <div id="chart" style="display:none;">
                    
                    <div style="float:right;width: 100%;margin-bottom: 10px;">
                            <button id="closeChartButton" onclick="closeChart();" name="submit" class="submit" style="display: block;width: 25px;padding: 2px;height: 25px;margin-right: 0px;font-size: 15px;" title="Close Comparison">X</button>   
                    </div>
                    <h3 style="color:white;">Price Trend</h3>
                    <div id="chart_div">

                    </div>
                </div>
	</body>
</html>

