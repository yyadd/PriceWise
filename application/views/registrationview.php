<!-- Build the HTML page with values from the call response -->
<html>
	<head>
		<title>PriceWise</title>
		<!-- <style type="text/css">body { font-family: arial,sans-serif;} </style> -->
                <base href="<?php echo base_url(); ?>">
                <link href="assets/css/home.css" type="text/css" rel="stylesheet" />
                <script src="<?php echo base_url(); ?>/assets/js/jquery-1.12.1.js" type="text/javascript"></script>
                <script src="<?php echo base_url(); ?>/assets/js/main.js" type="text/javascript"></script>
                <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
                
	</head>
	<body>
            <div id="navBar">
                <div id="PWtitle">
                    
                    <div id="PWname">
                        <a href="http://localhost:8080/PW/"><img style="height: 50px;" src="<?php echo base_url(); ?>assets/img/logo.png"/></a>
                        <!-- <span>Price</span><span>Wise</span> -->
                    </div>

                </div>
            </div> 
            <div id="registrationDiv">
                        
                            <input type="text" id="firstname" placeholder="First Name">
                            <input type="text" id="lastname" placeholder="Last Name">
                            <input type="text" id="email" placeholder="Email">
                            <input type="password" id="password" placeholder="Password">
                            <input type="password" id="ConfirmPassword" placeholder="Confirm Password">
                            <button id="registerButton" onclick="registerUser()">Register</button>
                            <div id="registrationError"><div>
                           
                        
            </div>
            
	</body>
</html>

