<!DOCTYPE html>
<html lang="en">
<head>
	<title>Car Rental</title>
	<meta charset="utf-8">
	<meta name="author" content="pixelhint.com">
	<meta name="description" content="La casa free real state fully responsive html5/css3 home page website template"/>
	<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0" />
	
	<link rel="stylesheet" type="text/css" href="css/reset.css">
	<link rel="stylesheet" type="text/css" href="css/responsive.css">

	<script type="text/javascript" src="js/jquery.js"></script>
	<script type="text/javascript" src="js/main.js"></script>
</head>
<body>

<?php require_once('../private/initialize.php');

require_login_customer(); 

?>


<?php $page_title = 'User Home'; ?>
<section class="">
		<?php
			include 'header.php';
		?>

			<section class="caption">
				<h2 class="caption" style="text-align: center">Find You Dream Cars For Hire</h2>
				<h3 class="properties" style="text-align: center">Range Rovers - Mercedes Benz - Landcruisers</h3>
			</section>
	</section><!--  end hero section  -->



	<section class="search">
		<div class="wrapper">
		<div id="fom">
		&nbsp;
        <h1>User Home</h1>
        <form action="pagetwo.php">
            <table height="200" align="center">
            <tr>
                <td><h3><li><a href="<?php echo url_for('/orders/myorders.php'); ?>"> <span style="color: #1c365; font-size: 20px;">My Orders History</a></li></h3></td>
            </tr>
            <tr>
                <td><h3><li><a href="<?php echo url_for('/orders/mypayments.php'); ?>"> <span style="color: #1c365; font-size: 20px;">My Payments History</a></li></h3></td>
            </tr>
            <tr>
                <td><h3><li><a href="<?php echo url_for('vehicle_search.php'); ?>"><span style="color: #1c365; font-size: 20px;">Create New Order</a></li></h3></td>
            </tr>
            <tr>
                <td><h3><li><a href="<?php echo url_for('pageshow.php'); ?>"><span style="color: #1c365; font-size: 20px;">Show my profile</a></li></h3></td>
            </tr>
            <tr>
                <td><h3><li><a href="<?php echo url_for('pagelogout.php'); ?>"><span style="color: #1c365; font-size: 20px;">Logout</a></li></h3></td>
            </tr>
			
            </table>
            </form>
			&nbsp;
			&nbsp;
			</div>
			<a href="#" class="advanced_search_icon" id="advanced_search_btn"></a>
		</div>

	</section><!--  end search section  -->

	<footer>
		<div class="wrapper footer">
			<ul>
				<li class="links">
					<ul>
						<li>OUR COMPANY</li>
						<li><a href="#">About Us</a></li>
						<li><a href="#">Terms</a></li>
						<li><a href="#">Policy</a></li>
						<li><a href="#">Contact</a></li>
					</ul>
				</li>

				<li class="links">
					<ul>
						<li>OTHERS</li>
						<li><a href="#">...</a></li>
						<li><a href="#">...</a></li>
						<li><a href="#">...</a></li>
						<li><a href="#">...</a></li>
					</ul>
				</li>

				<li class="links">
					<ul>
						<li>OUR CAR TYPES</li>
						<li><a href="#">Mercedes</a></li>
						<li><a href="#">Range Rover</a></li>
						<li><a href="#">Landcruisers</a></li>
						<li><a href="#">Others.</a></li>
					</ul>
				</li>

			<?php include_once "includes/footer.php"; ?>




