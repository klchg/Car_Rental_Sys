<!DOCTYPE html>
<html lang="en">
<head>
	<title>Car Rental</title>
	<meta charset="utf-8">
	<meta name="author" content="pixelhint.com">
	<meta name="description" content="La casa free real state fully responsive html5/css3 home page website template"/>
	<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0" />
	
	<link rel="stylesheet" type="text/css" href="../css/reset.css">
	<link rel="stylesheet" type="text/css" href="../css/responsive.css">

	<script type="text/javascript" src="../js/jquery.js"></script>
	<script type="text/javascript" src="../js/main.js"></script>
</head>



<?php

require_once('../../private/initialize.php');

require_login_customer();

if ((!isset($_GET['s_id'])) || empty($_GET['s_id']) || $_GET['s_id'] == '') 
    redirect_to(url_for('/Orders/myorders.php'));
if ((!isset($_GET['vid'])) || empty($_GET['vid']) || $_GET['vid'] == '') 
    redirect_to(url_for('/Orders/myorders.php'));

$service = [];
$service['s_id'] = $_GET['s_id'];
$service['vid'] = $_GET['vid'];

?>


<?php $page_title = 'delete confirm'; ?>
<section class="">
		<?php
			include '../header.php';
		?>

			<section class="caption">
            <a class="back-link" href="<?php echo url_for('orders/myorders.php'); ?>">&laquo; Back to All Orders</a>
				<h2 class="caption" style="text-align: center">Find You Dream Cars For Hire</h2>
				<h3 class="properties" style="text-align: center">Range Rovers - Mercedes Benz - Landcruisers</h3>
			</section>
	</section><!--  end hero section  -->



	<section class="search">
    <div id="fom"> &nbsp;
    <h3 style="text-align:center; color: #000099; font-weight:bold; text-decoration:underline">Delete Order</h3>
        
        <?php echo display_errors($errors); ?>
        &nbsp;
        <form action="<?php echo url_for('/Orders/delete_order.php?&s_id=' . h(u($service['s_id'])) . '&vid='. h(u($service['vid']))); ?>" method="post" id="yes">
            <input type="hidden" name="s_id" value="<?php echo $service['s_id']?>"/>
            <input type="hidden" name="vid" value="<?php echo $service['vid']?>"/>

                <h3>Are you sure to delete this order?</h3>

			&nbsp;
            &nbsp;
        </form>

        <form action="<?php echo url_for('/Orders/myorders.php'); ?>" id="no">
        </form>

        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        <button type="submit" form="yes" class="btn btn-sm btn-info" name="something"><i class="fa fa-check"></i>Yes</button>
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        <button type="submit" form="no" class="btn btn-sm btn-info" name="something"><i class="fa fa-check"></i>No</button>

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

			<?php include_once "../includes/footer.php"; ?>

