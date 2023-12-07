<script src="https://kit.fontawesome.com/630290f70f.js" crossorigin="anonymous"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

<h3><?php if($_SESSION["userid"]) { echo "Logged in: ".ucfirst($_SESSION["name"]); } ?> | <a href="logout.php">Logout</a> </h3><br>
<p><strong>Welcome <?php echo ucfirst($_SESSION["role"]); ?></strong></p>	

<?php if($_SESSION["role"] == 'admin') { ?>

    
<div id="containers" >
    <nav >
        <ul>
            <li><a href="dashboard.php">Dashboard</a></li>
            <li><a href="#">File Maintenance</a>
            <!-- First Tier Drop Down -->
            <ul style="z-index: 999999 !important">
				<li><a href="category.php">Category</a></li>
				<li><a href="items.php">Products</a></li>
				<li><a href="promo.php">Promo</a></li>	
                <li><a href="discount.php">Discount</a></li>
				<li><a href="tables.php">Tables</a></li>
				<li><a href="uom.php">Unit of Measurement</a></li>
            </ul>
            </li>
			<li>
			<a href="order.php">POS</a>
			<ul>
				<li><a href="order.php">Order</a></li>
                <li><a href="#">Billing</a></li>
				<li><a href="#">Table Management</a></li>
			</ul>
			</li>
            <li><a href="#">Reports	</a>
            <!-- First Tier Drop Down -->
            <ul>
                <li><a href="#">OR</a></li>
                <li><a href="#">Sales Report</a></li>
				<li><a href="#">Product Masterlist</a></li>
            </ul>
            </li>
			<li>
            <a href="#">Utilities</a>
			<ul>
				<li><a href="#">Backup/Restore</a></li>
                <li><a href="users.php">User Maintenance</a></li>
				<li><a href="#">Audit Trail</a></li>
			</ul>
			</li>
        </ul>
    </nav>

<style>
/* CSS Document */

@import url(https://fonts.googleapis.com/css?family=Open+Sans);
@import url(https://fonts.googleapis.com/css?family=Bree+Serif);



#containers {
	margin: 0 auto;
}

p {
	text-align: center;
}

nav {
	margin: 50px 0;
	background-color: #E64A19;
}

nav ul {
	padding: 0;
  	margin: 0;
	list-style: none;
	position: relative;
	}
	
nav ul li {
	display:inline-block;
	background-color: #E64A19;
	}

nav a {
	display:block;
	padding:0 10px;	
	color:#FFF;
	font-size:16px;
	line-height: 40px;
	text-decoration:none;
}

nav a:hover { 
	background-color: #000000; 
}

/* Hide Dropdowns by Default */
nav ul ul {
	display: none;
	position: absolute; 
	top: 40px; /* the height of the main nav */
}
	
/* Display Dropdowns on Hover */
nav ul li:hover > ul {
	display:inherit;
}
	
/* Fisrt Tier Dropdown */
nav ul ul li {
	width:170px;
	float:none;
	display:list-item;
	position: relative;
}

/* Second, Third and more Tiers	*/
nav ul ul ul li {
	position: relative;
	top:-40px; 
	left:170px;
}

	
/* Change this in order to change the Dropdown symbol */
li > a:after { content:  ' +'; }
li > a:only-child:after { content: ''; } 
</style>

	<?php } ?>	
	<?php if($_SESSION["role"] == 'waiter') { ?>		
		<li id="order"><a href="order.php">Order</a></li>	
	<?php } ?>
	<?php if($_SESSION["role"] == 'cashier') { ?>
		<li id="order"><a href="order.php">Order</a></li>
		<li id="billing"><a href="billing.php">Billing</a></li>	
	<?php } ?>