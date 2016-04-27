var _throttleTimer = null;
var _throttleDelay = 100;
var $window = $(window);
var $document = $(document);

$document.ready(function () {

    $window
        .off('scroll', ScrollHandler)
        .on('scroll', ScrollHandler);

});

function ScrollHandler(e) {
    //throttle event:
    clearTimeout(_throttleTimer);
    _throttleTimer = setTimeout(function () {
        console.log('scroll');

        //do work
        if ($window.scrollTop() + $window.height() > $document.height() && $("#resultsDiv").css('display')!= 'none') {
            //alert("near bottom!");
            $("#pagination").show();     
        }
        else {
            $("#pagination").hide(); 
        }

    }, _throttleDelay);
}


//global variables
var g_userId="empty";
var paginationNumber = 1;
$(document).keypress(function(event) {
    var keycode = (event.keyCode ? event.keyCode : event.which);
    if(keycode == '13') {
        $("#searchButton").click();  
    }
});

$(document).click(function(event) { 
    if(!$(event.target).closest('#loginDiv').length &&
       !$(event.target).is('#loginDiv')) {
        if($('#loginDiv').is(":visible")) {
            $('#loginDiv').hide();
        }
    }        
})



function checkBoxCheck(){
    
}
//update pagination number

function updatePagination(){
    if(paginationNumber !=10){
        paginationNumber++;
        if(g_userId == "empty")
         submitSearch();
        else
         submitSearchLoggedIn();  
    }
    else
        alert("No more results!!!");
}

function submitSearch()
{
    var searchQuery = $("#searchBox").val();
    var searchInCB = $('input[name="searchIn[]"]');
    var searchIn = [];
    var CBcount=0;
    if($.trim(searchQuery)!=""){    
        for(var i=0;i<searchInCB.length;i++){
            if(searchInCB[i].value ==="Ebay" && searchInCB[i].checked ==true){
                searchEbay(searchQuery,'ebay',paginationNumber);
                
               
            }
            if(searchInCB[i].value ==="Amazon" && searchInCB[i].checked ==true){
                searchAmazon(searchQuery,'amazon',paginationNumber);
                
            }
        }
    }
    else
        alert("Enter a product to search");
    
    
    
}

function submitSearchLoggedIn()
{   var wishList = true;
    $("#dealsDiv").hide();
    var searchQuery = $("#searchBox").val();
    var searchInCB = $('input[name="searchIn[]"]');
    var searchIn = [];
    var CBcount=0;
    if($.trim(searchQuery)!=""){    
        for(var i=0;i<searchInCB.length;i++){
            if(searchInCB[i].value ==="Ebay" && searchInCB[i].checked ==true){
                searchEbay(searchQuery,'ebay',paginationNumber,wishList);
                checkInWishList();
                
               
            }
            if(searchInCB[i].value ==="Amazon" && searchInCB[i].checked ==true){
                searchAmazon(searchQuery,'amazon',paginationNumber,wishList);
               
            }
        }
    }
    else
        alert("Enter a product to search");
    
}


function searchEbay(searchQuery,company,paginationNumber,wishList){
    if(paginationNumber ==1){
    $("#ebayDiv").empty();
    $("#amazonDiv").empty();
    $(".productDiv").empty();
    $("#comparisonDiv").hide();
    $("#dealsDiv").hide();
    var prods=0
    }
    
       var offset = $(".prodDisplay").length;
   
   //console.log(prods);
    $.ajax({
                type:'POST',
                url:'http://localhost:8080/PW/index.php/SearchController/searchEbayByKeyword',
                data:{'searchQuery':searchQuery,'paginationNumber':paginationNumber},
                success:function(resp){
                    resultObj = JSON.parse(resp);
                    //console.log(resultObj[1].link);
                      
                    for(i=0;i<(resultObj.length);i++){
                      //console.log(resultObj);
                      displayProducts(resultObj,i,company,offset,wishList);
                    
                    }
                    
                    $("#resultsDiv").show();
                    checkInWishlist();
                    //displayResult(resultObj);
                    
                    
                }
            });
    
}


function searchAmazon(searchQuery,company,paginationNumber,wishList){
     if(paginationNumber ==1){
    $("#ebayDiv").empty();
    $("#amazonDiv").empty();
    $(".productDiv").empty();
    $("#comparisonDiv").hide();
    $("#dealsDiv").hide();
    var prods=0
    }
    
       var offset = $(".prodDisplay").length;
   
    $.ajax({
                type:'POST',
                url:'http://localhost:8080/PW/index.php/SearchController/searchAmazonByKeyword',
                data:{'searchQuery':searchQuery,'paginationNumber':paginationNumber},
                success:function(resp){
                    resultObj = JSON.parse(resp);
                    //console.log(resultObj[1].link);
                      
                    for(var i=0;i<resultObj.length;i++){
                      
                      displayProducts(resultObj,i,company,offset,wishList);
                    
                    }
                    
                    $("#resultsDiv").show();
                    checkInWishlist();
                    //displayResult(resultObj);
                    
                    
                }
            });
}


function displayProducts(resultObj,i,company,offset,wishList){
                        //product number offset in case of pagination
                        
                      //console.log($("#"+company+"Div"));  
                      var siteDiv = $("#"+company+"Div");
                      console.log(offset)
                      var product = document.createElement('div');
                      product.className = "prodDisplay";
                      product.id = company+"product"+(i+offset);
                      siteDiv.append(product);
                      
                      var parentProd = $("#"+company+"product"+(i+offset));
                      
                      
                     
                      var image = document.createElement('div');
                      image.className = "prodImage";
                      //image.title = "Add to Compare";
                      image.id = "prodImage"+resultObj[i].productId[0];
                      image.style.background = "url("+resultObj[i].pic[0]+")no-repeat scroll center center #fff";
                      parentProd.append(image);
                      
                      var prodTitle = document.createElement('div');
                      prodTitle.className = 'prodTitle';
                      prodTitle.innerHTML = "<a target='_blank' href='"+resultObj[i].link[0]+"'>"+resultObj[i].productTitle[0]+"</a>";
                      parentProd.append(prodTitle);
                      
                      var prodPrice = document.createElement('div');
                      prodPrice.className = 'prodPrice';
                      prodPrice.innerHTML = "$"+resultObj[i].price[0];
                      parentProd.append(prodPrice);
                      
                      var comapreDiv = document.createElement('div');
                      comapreDiv.innerHTML = "<input type='checkbox' class='selectedItems' onchange='updateCompare();' name='"+resultObj[i].productId[0]+"' id='"+resultObj[i].productId[0]+"'>";
                      comapreDiv.className = 'selectedItems';
                      parentProd.append(comapreDiv);
                      
                      var siteLogo = document.createElement('img');
                      siteLogo.src = "http://localhost:8080/PW/application/assets/img/"+company+"-logo.png";
                      siteLogo.className = 'siteLogo';
                      siteLogo.setAttribute("name", company);
                      parentProd.append(siteLogo);
                      
                      if(wishList == true){
                          
                      var wishlistDiv = document.createElement('div');
                      wishlistDiv.id= "star"+resultObj[i].productId[0];
                      wishlistDiv.innerHTML = "<i class='fa fa-star' onclick='addProductToWishList("+company+"product"+i+");'></i>";
                      wishlistDiv.className = 'wishListIcon';
                      wishlistDiv.title="Add to Wish List.";
                      parentProd.append(wishlistDiv);
                        }
                      
                      
                       
    
}


function checkInWishlist(){
    var products = $('.prodDisplay .selectedItems input');
    var prodIds = [];
    for(var i=0;i<products.length;i++){
        prodIds.push(products[i].name);
    }
    //console.log(prodIds);
    
    var username = $("#LoggedInUsername").val();
    $.ajax({
                type:'POST',
                url:'http://localhost:8080/PW/index.php/WishlistController/checkInWishlist',
                data:{'username':username,'prodIds':JSON.stringify(prodIds)},
                success:function(resp){
                    resultObj = JSON.parse(resp);
                    console.log(resultObj);
                      
                    for(var i=0;i<resultObj.length;i++){
                        $('#star'+resultObj[i]+' i').css('color','#FF9000');
                        $('#star'+resultObj[i]+' i').attr('title','Already in Wishlist');
                        $('#star'+resultObj[i]+' i').attr('onclick','');
                    }
                    
                    //$("#resultsDiv").show();
                    
                    //displayResult(resultObj);
                    
                    
                }
            });
    
    
    
    
    
}

function updateCompare(){
   var items = $(".selectedItems"); 
   var count = 0;
   for(var i=0;i<items.length;i++){
        if(items[i].checked ==true){
           count++;
        }
        
        
    }   
     if(count>1 && count<=3)
        $('#compareProductsButton').show();
    else
        $('#compareProductsButton').hide();
   
    
    
}

function toggleAddToCompare(id){
   // console.log($("#"+id));
}


function compareProducts(){
    var items = $(".selectedItems");
    $("#resultsDiv").hide();
    
   // $(".productDiv").remove();
    $(".comparisonTD").remove();
    for(var i=0;i<items.length;i++){
        if(items[i].checked ==true){
         var cb = $("#"+items[i].id).parent();
         var product = cb.parent();
         var ID = product.attr('id'); 
         
         var pic = $("#"+ID+" .prodImage");
         var title = $("#"+ID+" .prodTitle");
         var price = $("#"+ID+" .prodPrice");
         var website = $("#"+ID+" .siteLogo");
         
         var SiteName = $("#siteName");
         var siteImageTD = document.createElement('td');
         siteImageTD.id = "siteImageTD"+i;
         siteImageTD.className = "comparisonTD";
         SiteName.append(siteImageTD);
                 
         var siteImageTD = $("#siteImageTD"+i);
        
         var siteLogo = document.createElement('img');
         siteLogo.src = website.attr('src');
         siteLogo.style.height = '30px';
         siteImageTD.append(siteLogo);
         
         //image of product
         var prodImage = $("#imageRow")
         var prodImageTD = document.createElement('td');
         prodImageTD.className = "comparisonTD";
         prodImageTD.id = "prodImageTD"+i;
         prodImage.append(prodImageTD);
         
         var prodImageTD  = $("#prodImageTD"+i);
         var prodImage = document.createElement('div');
         prodImage.style.background = pic.css('background');
         prodImage.setAttribute('site',$("#"+ID+" .siteLogo")[0].name);
         prodImage.setAttribute('product-name',$("#"+ID+" .prodTitle a").html());
         prodImage.setAttribute('product-id',items[i].id);
         prodImage.className= 'prodImage';
         prodImage.className += ' comparisonProdImage';
         prodImageTD.append(prodImage);
         
         var prodTitle = $("#titleRow")
         var prodTitleTD = document.createElement('td');
         prodTitleTD.className = "comparisonTD";
         prodTitleTD.id = "prodTitleTD"+i;
         prodTitle.append(prodTitleTD);
         
         var prodTitleTD  = $("#prodTitleTD"+i);
         var prodTitle = document.createElement('div');
         prodTitle.innerHTML = title.html();
         prodTitle.className= 'prodTitle';
         prodTitleTD.append(prodTitle);
         
         var prodPrice = $("#priceRow")
         var prodPriceTD = document.createElement('td');
         prodPriceTD.className = "comparisonTD";
         prodPriceTD.id = "prodPriceTD"+i;
         prodPrice.append(prodPriceTD);
         
         var prodPriceTD  = $("#prodPriceTD"+i);
         var prodPrice = document.createElement('div');
         prodPrice.innerHTML = price.html();
         prodPriceTD.append(prodPrice);
         
         
         //get reviews from respective site and display
         
         var website = $("#"+ID+" .siteLogo").attr('name');
         console.log(website);
         
         if(website == "amazon"){
             var amazonReview = getReviewsFromAmazon(items[i].id,i);
             //console.log(amazonReview);   
         }
         else if (website == "ebay"){
             //console.log(items[i].id);
                    var ebayReview = getReviewsFromEbay(items[i].id,i);
         }
         
         
      }
    }
    $("#comparisonDiv").show();
    
    
}

function getReviewsFromAmazon(ID,i){
    
    //console.log(typeof ID);
    if(typeof ID == 'object')
        ID = ID[0];
    
    
    $.ajax({
                type:'POST',
                url:'http://localhost:8080/PW/index.php/ReviewsController/getAmazonReviews',
                data:{'prodId':ID},
                success:function(resp){
                    resultObj = JSON.parse(resp);
                    
                    
                    amazonReview = resultObj[0];
                    //console.log(amazonReview);
                    var prodReview = $("#reviewRow");
                    var prodReviewTD = document.createElement('td');
                    prodReviewTD.className = "comparisonTD";
                    prodReviewTD.id = "prodReviewTD"+i;
                    prodReview.append(prodReviewTD);

                    prodReviewTD = $("#prodReviewTD"+i);
                    var review = document.createElement('iframe');
                    review.src = amazonReview;
                    prodReviewTD.append(review); 
                    
                    
                    //$("#resultsDiv").show();
                    
                    //displayResult(resultObj);
                    
                    
                }
            });
            
}

function getReviewsFromEbay(ID,i){
    //console.log(typeof ID);
    if(typeof ID == 'object')
        ID = ID[0];
    
    $.ajax({
                type:'POST',
                url:'http://localhost:8080/PW/index.php/ReviewsController/getEbayReviews',
                data:{'itemId':ID},
                success:function(resp){
                    resultObj = JSON.parse(resp);
                    console.log(typeof resultObj)
                    if(resultObj == 'NULL')
                        ebayReview = "No Reviews Available! Check Ebay for more";
                    else    
                        ebayReview = resultObj;
                    //console.log(amazonReview);
                    var prodReview = $("#reviewRow");
                    var prodReviewTD = document.createElement('td');
                    prodReviewTD.className = "comparisonTD";
                    prodReviewTD.id = "prodReviewTD"+i;
                    prodReview.append(prodReviewTD);

                    prodReviewTD = $("#prodReviewTD"+i);
                    var review = document.createElement('div');
                    review.innerHTML = ebayReview;
                    prodReviewTD.append(review); 
                    
                    
                    //$("#resultsDiv").show();
                    
                    //displayResult(resultObj);
                    
                }
               
            });
            
}

//Close comparison div
function closeComparison(){
    //$("#ebayDiv").empty();
    //$("#amazonDiv").empty();
    //$(".productDiv").empty();
    $('#resultsDiv').show();
    $("#comparisonDiv").hide();
    $('#compareProductsButton').hide();
    
    
}

function toggleLoginDiv(){
    if($('#loginDiv').css('display') != 'none'){
        $('#loginDiv').hide();
    }
    else
        $('#loginDiv').show();
}



function homeButtonClicked(){
    //$("#resultsDiv").empty();
    $("#chart").hide();
    $("#MyComparisonsDiv").hide();
    $("#ebayDiv").empty();
    $("#resultsDiv").hide();
    $("#amazonDiv").empty();
    $(".productDiv").empty();
    $("#comparisonDiv").hide();
    $("#wishlistDiv").hide();
    $("#charts").hide();
    $('#compareProductsButton').hide()
    $("#dealsDiv").hide();
    $("#searchBox").val('');
    paginationNumber =1;
    
}

function getEbayDeals(){
    homeButtonClicked();
    var company="ebay";
    $('.dealProdDisplay').remove();
    $.ajax({
                type:'POST',
                url:'http://localhost:8080/PW/index.php/DealsController/ebayDeals',
                data:{},
                success:function(resp){
                    resultObj = JSON.parse(resp);
                    
                    //console.log(resultObj);
                    
                    for(var i=0;i<resultObj.length;i++){
                        displayDealsProduct(resultObj[i],company,i);
                    }
                    $("#dealsDiv").show();
                    $("#ebayDeals").show();
                    
                    
                    
                    
                    //amazonReview = resultObj[0];
                    
                    
                    //$("#resultsDiv").show();
                    
                    //displayResult(resultObj);
                    
                    
                }
            });
               
}

function getAmazonDeals(){
    
    var company="amazon";
    var sel = document.getElementById('amazonDealsCatagories');
    var opt = sel.options[sel.selectedIndex];
    console.log(opt.value);
    $('.dealProdDisplayAmazon').remove();
    $.ajax({
                type:'POST',
                url:'http://localhost:8080/PW/index.php/DealsController/amazonDeals',
                data:{'browseId':opt.value},
                success:function(resp){
                    resultObj = JSON.parse(resp);
                    
                    //console.log(resultObj);
                    
                    for(var i=0;i<resultObj.length;i++){
                       // displayDealsProduct(resultObj[i],company,i);
                        var display = $("#amazonDeals");
    
                        var prodDiv = document.createElement('div');
                        prodDiv.className = "dealProdDisplayAmazon";
                        prodDiv.id = "amazonDeal"+i;
                        display.append(prodDiv);
                        
                        //console.log(resultObj[i].link[0]);
                        var prodDiv = $("#amazonDeal"+i);
                        var prodTitle = document.createElement('div');
                        prodTitle.className = 'dealProdTitle';
                        prodTitle.innerHTML = "<a target='_blank' href='"+resultObj[i].link[0]+"'>"+resultObj[i].productTitle[0]+"</a>";
                        prodDiv.append(prodTitle);
                       
                       
                       
                    }
                   // $("#dealsDiv").show();
                   // $("#ebayDeals").show();
                    
                    
                    
                    
                    //amazonReview = resultObj[0];
                    
                    
                    //$("#resultsDiv").show();
                    
                    //displayResult(resultObj);
                    
                    
                }
            });
               $("#amazonDeals").show();
}

function displayDealsProduct(product,company,i){
    
    
    var displayDiv = $("#"+company+"Deals");
    
    var prodDiv = document.createElement('div');
    prodDiv.className = "dealProdDisplay";
    prodDiv.id = company+"Deal"+i;
    displayDiv.append(prodDiv);
    
    var prodDiv = $('#'+company+"Deal"+i);
    var prodPic = document.createElement('div');
    prodPic.className = "dealProdImage";
    prodPic.id = "dealProdImage"+product.productId[0];
    prodPic.style.background = "url("+product.pic[0]+")no-repeat scroll center center #fff";
    prodDiv.append(prodPic);
    
    var prodTitle = document.createElement('div');
    prodTitle.className = 'dealProdTitle';
    prodTitle.innerHTML = "<a target='_blank' href='"+product.link[0]+"'>"+product.productTitle[0]+"</a>";
    prodDiv.append(prodTitle);
    
    var prodPrice = document.createElement('div');
    prodPrice.className = 'prodPrice';
    prodPrice.innerHTML = "$"+product.price[0];
    prodDiv.append(prodPrice);
    
    
    
}



function login(){
    
    var username = $('#username').val();
    var password = $('#password').val();
    
    $.ajax({
                type:'POST',
                url:'http://localhost:8080/PW/index.php/UserController/login',
                data:{'username':username,'password':password},
                success:function(resp){
                    resultObj = JSON.parse(resp);
                    
                    location.reload();
                    
                    
                   
                    
                    
                }
            });
    
}

function logout(){
    $.ajax({
                type:'POST',
                url:'http://localhost:8080/PW/index.php/HomeController/logout',
                data:{},
                success:function(resp){
                    //resultObj = JSON.parse(resp);
                    
                    console.log('logged Out');
                    location.reload();
                    //g_userId = resultObj.email;
                    
                    
                }
            });
    
}

function addProductToWishList(parent){
    //var parent = star.parentNode;
    //var ID = parent.attributes.id;
    ID = parent.attributes.id.value;
    var productId = $("#"+ID+" .selectedItems .selectedItems").attr('id')
    var website = $("#"+ID+" .siteLogo").attr('name');
    var title = $("#"+ID+" .prodTitle a").html();
    var link = $("#"+ID+" .prodTitle a").attr('href');
    var price = $("#"+ID+" .prodPrice").html();
    price = price.replace("$","");
    var username = $("#LoggedInUsername").val();
    
console.log(price);
    
    
        $.ajax({
                type:'POST',
                url:'http://localhost:8080/PW/index.php/WishlistController/add',
                data:{'product_user_id':username,'product_id':productId,'product_title':title,'link':link,'website':website,'current_price':price},
                success:function(resp){
                    resultObj = JSON.parse(resp);
                    console.log(resultObj);
                    if(resultObj != 1){
                        alert("Already added to wishlist");
                    }
                    else if(resultObj == 1){
                        alert("Product Added To Wishlist!");
                        $("#star"+productId).css("color","rgb(255, 144, 0)");
                    }
                    
                    //g_userId = resultObj.email;
                    
                    
                }
            });
}

function getMyWishlist(username){
    $("#dealsDiv").hide();
    homeButtonClicked();
    $("#wishListTable tr").remove();
    
    $.ajax({
                type:'POST',
                url:'http://localhost:8080/PW/index.php/WishlistController/getWishlist',
                data:{'username':username},
                success:function(resp){
                    resultObj = JSON.parse(resp);
                    //console.log(resultObj);
                    if(resultObj != false){
                       $("#wishlistDiv").show();
                       displayWishlist(resultObj);
                    }
                    else alert("Wishlist Empty!");
                    
                }
            });
    
}

function displayWishlist(products){

    for (var i=0;i<products.length;i++){
        
        var table = $("#wishListTable");
        
        var row = document.createElement('tr');
        row.id = "wishList"+products[i].product_id;
        table.append(row);
        
        var row = $("#wishList"+products[i].product_id);
        
        var siteLogo = document.createElement('td');
        siteLogo.innerHTML ="<img style='height:30px;' src='http://localhost:8080/PW/application/assets/img/"+products[i].website+"-logo.png'>"; 
        row.append(siteLogo);
        
        var title = document.createElement('td');
        title.innerHTML = "<a href='"+products[i].link+"'>"+products[i].title+"</a>";
        row.append(title);
        
        var price = document.createElement('td');
        price.innerHTML = "$"+products[i].current_price;
        row.append(price);
        
        var priceTrend= document.createElement('td');
        priceTrend.innerHTML = "<div onclick='drawChart("+products[i].product_id+")'><i class='fa fa-line-chart' aria-hidden='true'></i></div>";
        priceTrend.id = "priceTrend"+products[i].product_id;
        row.append(priceTrend);
        
        var Alert= document.createElement('td');
        Alert.innerHTML = "<div onclick='Alert("+products[i].product_id+")'><i style='padding:6px;' class='fa fa-bell'></i></div>";
        Alert.id = "alert"+products[i].product_id;
        row.append(Alert);
        checkAlert(products[i].product_id);
        
        var removeProd= document.createElement('td');
        removeProd.innerHTML = "<div onclick='deleteFromWishlist("+products[i].product_id+")'><i style='padding:6px;' class='fa fa-trash'></i></div>";
        removeProd.id = products[i].product_id;
        row.append(removeProd);
        
        
        
        
        
    }
  
}

function checkAlert(product_id){
    
    $username = $("#LoggedInUsername").val();
    //$(pid).remove();
    
    //console.log(product_id);
   $.ajax({
                type:'POST',
                url:'http://localhost:8080/PW/index.php/WishlistController/checkAlert',
                data:{'username':$username,'product_id':product_id},
                success:function(resp){
                    resultObj = JSON.parse(resp);
                    console.log(resultObj[0].alert);
                    if(resultObj[0].alert == 1){
                        $('#alert'+product_id+' div i').css('color','red');
                    }
                    
                   
                }
            });
    
}

function deleteFromWishlist(product_id){
    //var pid  = "#wishList"+product_id.id;
    $username = $("#LoggedInUsername").val();
    if(product_id.id)
        product_id = product_id.id;
    //$(pid).remove();
    
    //console.log(pid);
    $.ajax({
                type:'POST',
                url:'http://localhost:8080/PW/index.php/WishlistController/delete',
                data:{'username':$username,'product_id':product_id},
                success:function(resp){
                    //resultObj = JSON.parse(resp);
                    //console.log(resultObj);
                    $("#wishList"+product_id).remove();
                    //if(resultObj == 1){
                     //  $("#"+product_id).remove(); 
                    //}
                    //else alert("Product Already Removed!")
                   
                }
            });
}

// set up alerting on product basis 

function Alert(product_id){
    $username = $("#LoggedInUsername").val();
    //handle case for amazon
    if(product_id.id)
        product_id = product_id.id;
    $.ajax({
                type:'POST',
                url:'http://localhost:8080/PW/index.php/WishlistController/toggleAlert',
                data:{'username':$username,'product_id':product_id},
                success:function(resp){
                    resultObj = JSON.parse(resp);
                    //console.log(resultObj);
                    if(resultObj == 1)
                        $('#alert'+product_id+' div i').css('color','red');
                    else if(resultObj ==0)
                         $('#alert'+product_id+' div i').css('color','black');
                }
            });
}



function saveComparison(){
    var ComparisonName = prompt("Enter a name for this comparison : ", "Name");
    var username = $("#LoggedInUsername").val();
    var prodsToSave = $('.comparisonProdImage');
    //console.log(ComparisonName);
    if(ComparisonName.trim() != '' && ComparisonName.trim() !='your name here'){
    var prodInfo =[];
    
    for(var i=0;i < prodsToSave.length;i++){
        var prod = {
            site:prodsToSave[i].getAttribute('site'),
            id:prodsToSave[i].getAttribute('product-id'),
            title:prodsToSave[i].getAttribute('product-name')
        };
        
        prodInfo.push(prod);
        }
        //console.log(prodInfo);
        $.ajax({
                type:'POST',
                url:'http://localhost:8080/PW/index.php/ComparisonController/save_comparison',
                data:{'username':username,'prod_info':JSON.stringify(prodInfo),'comparison_name':ComparisonName},
                success:function(resp){
                    resultObj = JSON.parse(resp);
                    //console.log(resultObj);
                    if(resultObj == 1){
                     alert("Comparsion Added");
                    }
                    //else alert("Product Already Removed!")
                   
                }
            });
        }
        else if(ComparisonName.trim() =='your name here' || ComparisonName.trim() =='')
            alert("Enter a name for the Comparison.")
}


function getMyComparisons(username){
   
   homeButtonClicked();
   $('.myComparisonTable').remove();
   $('.compName').remove();
   $('#MyComparisonsDiv').show(); 
   
    $.ajax({
                type:'POST',
                url:'http://localhost:8080/PW/index.php/ComparisonController/getMyComparisons',
                data:{'username':username},
                success:function(resp){
                    resultObj = JSON.parse(resp);
                    //console.log(resultObj);
                    for(var i=0;i<resultObj.length;i++){
                        var comp = JSON.parse(resultObj[i].product_info);
                                
                                var MyComparisons = $('#MyComparisonsDiv');
                                
                                var compName = document.createElement('div');
                                compName.className = 'compName';
                                //compName.style.color = 'white';
                                //compName.onclick = newComparison('MyComparison'+i);
                                compName.innerHTML = "<a style='color:white;' href='javascript:newComparison("+'MyComparison'+i+");'>"+resultObj[i].comparison_name+"</a>";
                                MyComparisons.append(compName);
                                
                                var table = document.createElement('table')
                                table.id = 'MyComparison'+i;
                                table.className = 'myComparisonTable';
                                table.setAttribute('border',1);
                                MyComparisons.append(table);
                                
                                var products =[];
                                
                                for(var k=0;k<comp.length;k++){
                                    //console.log(comp[k]);
                                    var parentTable = $('#MyComparison'+i);
                                    var row = document.createElement('tr');
                                    row.id = comp[k].id;
                                    parentTable.append(row);
                                    
                                    var product = {site:comp[k].site,id:comp[k].id};
                                    products.push(product);
                                    
                                    var parentRow = $('#MyComparison'+i+' #'+comp[k].id);
                                    
                                    var siteLogo = document.createElement('td');
                                    siteLogo.innerHTML ="<img style='height:30px;' src='http://localhost:8080/PW/application/assets/img/"+comp[k].site+"-logo.png'>"; 
                                    parentRow.append(siteLogo);
                                    
                                    var title = document.createElement('td');
                                    title.innerHTML = '<div>'+comp[k].title+'</div>';
                                    parentRow.append(title);
                                    
                                }
                                var ProductList = document.createElement('input');
                                ProductList.type = 'hidden';
                                ProductList.id = 'MyComparison'+i+'List';
                                ProductList.value = JSON.stringify(products);
                                MyComparisons.append(ProductList);
                                
                                //console.log(products);
                                //console.log('\n\n');
                    }
                    
                   // if(resultObj == 1){
                    // alert("Comparsion Added");
                    //}
                    //else alert("Product Already Removed!")
                   
                }
            });
    
}


function toggleAlert(product_id){
    
}

function closeWishlist(){
    $("#wishlistDiv").hide();
    homeButtonClicked();  
}


function closeComparisons(){
    $("#MyComparisonsDiv").hide();
      
}

function newComparison(id){
    closeComparisons();
    
   // $(".productDiv").remove();
    $(".comparisonTD").remove();
    $('#comparisonDiv').show();
    $('#saveCompariosnButton').hide();
    
    var productsToFetch = $("#"+id.id+"List").val();
    
    productsToFetch = JSON.parse(productsToFetch);
    //console.log(productsToFetch);
    for(var i=0;i< productsToFetch.length;i++){
           getProductForComparison(productsToFetch[i].id,i,productsToFetch[i].site);
           
    }
    
        
}

function getProductForComparison(productId,i,site){
    
    $.ajax({
                type:'POST',
                url:'http://localhost:8080/PW/index.php/SearchController/fetchProduct',
                data:{'productId':productId,'site':site},
                success:function(resp){
                    resultObj = JSON.parse(resp);
                    //console.log(resultObj);
                    appendProductForSavedComparison(resultObj,i);  
                },
                complete:function(resp){
                    //resultObj = JSON.parse(resp);
                    //console.log(resultObj);
                    //appendProductForSavedComparison(resultObj,i); 
                }
            });
}



function appendProductForSavedComparison(product,i){
    
    
   // $(".productDiv").remove();
   // $(".comparisonTD").remove();
    
         
         
         var ID = product.productId; 
         
         var pic = product.pic;
         var title = product.productTitle;
         var price = product.price;
         var website = product.site;
         
         console.log(product.link[0]);
                  
         var SiteName = $("#siteName");
         var siteImageTD = document.createElement('td');
         siteImageTD.id = "siteImageTD"+i;
         siteImageTD.className = "comparisonTD";
         SiteName.append(siteImageTD);
         
                
         var siteImageTD = $("#siteImageTD"+i);
        
         var siteLogo = document.createElement('img');
         siteLogo.src = 'http://localhost:8080/PW/application/assets/img/'+website+'-logo.png';
         siteLogo.style.height = '30px';
         siteImageTD.append(siteLogo);
        
         //image of product
         var prodImage = $("#imageRow")
         var prodImageTD = document.createElement('td');
         prodImageTD.className = "comparisonTD";
         prodImageTD.id = "prodImageTD"+i;
         prodImage.append(prodImageTD);
         
         var ProdImageTD = $("#prodImageTD"+i);
         var image = document.createElement('div');
         image.className = "prodImage";
         //image.title = "Add to Compare";
         image.id = "prodImage"+ID;
        // console.log(pic);
         image.style.background = "url("+pic[0]+")no-repeat scroll center center #fff";
         ProdImageTD.append(image);
         
         var prodTitle = $("#titleRow")
         var prodTitleTD = document.createElement('td');
         prodTitleTD.className = "comparisonTD";
         prodTitleTD.id = "prodTitleTD"+i;
         prodTitle.append(prodTitleTD);
         
         //console.log(title);
         var prodTitleTD  = $("#prodTitleTD"+i);
         var prodTitle = document.createElement('div');
         prodTitle.innerHTML = '<a href="'+product.link[0]+'">'+title[0]+'</a>';
         prodTitle.className= 'prodTitle';
         prodTitleTD.append(prodTitle);
         
         var prodPrice = $("#priceRow")
         var prodPriceTD = document.createElement('td');
         prodPriceTD.className = "comparisonTD";
         prodPriceTD.id = "prodPriceTD"+i;
         prodPrice.append(prodPriceTD);
         
         var prodPriceTD  = $("#prodPriceTD"+i);
         var prodPrice = document.createElement('div');
         if(website == 'ebay')
            prodPrice.innerHTML = '$'+price[0];
         else
             prodPrice.innerHTML = '$'+price;
         prodPriceTD.append(prodPrice);
         
         
         //get reviews from respective site and display
         
         //var website = $("#"+ID+" .siteLogo").attr('name');
        // console.log(website);
         
         if(website == "amazon"){
             var amazonReview = getReviewsFromAmazon(ID,i);
             //console.log(amazonReview);   
         }
         else if (website == "ebay"){
                    var prodReview = $("#reviewRow");
                    var prodReviewTD = document.createElement('td');
                    prodReviewTD.className = "comparisonTD";
                    prodReviewTD.id = "prodReviewTD"+i;
                    prodReview.append(prodReviewTD);
         }
         
         
      
    
    $("#comparisonDiv").show();
    
    
}

//load registeration view

function loadRegisterView(){
    window.location.replace("http://localhost:8080/PW/index.php/registrationcontroller");
}

function registerUser(){
    var firstName = $("#firstname").val();
    var lastName = $("#lastname").val();
    var email = $('#email').val();
    var pass = $("#password").val();
    var confirmPass = $('#ConfirmPassword').val();
    //console.log(pass +"  :  "+confirmPass);
    if(!validateEmail(email)){
        //console.log(validateEmail(email));
        $("#registrationError").html("Email format is incorrect!");
    }
    else if(pass != confirmPass){
        $("#registrationError").html("Passwords do not match!");
    }
    //add regex for password password
    else{
        $("#registrationError").html("");
        //do ajax call to register user
        $.ajax({
                type:'POST',
                url:'http://localhost:8080/PW/index.php/usercontroller/registerUser',
                data:{'email':email,'password':pass,'firstName':firstName,'lastName':lastName},
                success:function(resp){
                    resultObj = JSON.parse(resp);
                    //console.log(resultObj);
                    if(resultObj == true){
                        firstName = $("#firstname").val('');
                        lastName = $("#lastname").val('');
                        email = $('#email').val('');
                        pass = $("#password").val('');
                        confirmPass = $('#ConfirmPassword').val('');
                        $("#registrationError").html("You have been registered with PriceWise!");
                        
                        setTimeout(window.location.replace("http://localhost:8080/PW/"), 15000);
                    }
                    
                    
                   
                }
            });
        
        
        
    }
        
}
//email validation
function validateEmail(email) {
    var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(email);
}



//function to make charts to display price trends
function drawChart(product_id) {
    //handle case for amazon
    if(product_id.id)
        product_id = product_id.id;

            console.log(product_id);    
              
              // ajax call to get data from database
              $.ajax({
                type:'POST',
                url:'http://localhost:8080/PW/index.php/ChartController',
                data:{'product_id':product_id},
                success:function(resp){
                    var data = new google.visualization.DataTable();
                    data.addColumn('number', 'date');
                    data.addColumn('number', 'price');
                    
                    resultObj = JSON.parse(resp);
                    var prices = resultObj;
                    //var i ='Day'+1;
                    //console.log(prices);
                    //prices.reverse();
                    var len = prices.length;
                      for(var i=len-15;i<len;i++){
                          //console.log(i);
                          //console.log(prices[i]);  
                          
                          data.addRows([[i-(len-16), parseInt(prices[i])]]);
                      }
                      
                      
                      
	      var options = {
	        hAxis: {
	          title: 'Day',
                  ticks:[1,2,3,4,5,6,7,8,9,10,11,12,13,14,15]
	        },
                
	        vAxis: {
	          title: 'Price (CAD)'
	        },
                width: 800,
                height:400
	      };

	      var chart = new google.visualization.LineChart(document.getElementById('chart_div'));
	      chart.draw(data, options);
              $("#chart").show();
                    
                    
                    
                   
                }
            });
              
   }
   
   function closeChart(){
       $("#chart").hide();
   }