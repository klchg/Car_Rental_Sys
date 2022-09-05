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

// $id = isset($_GET['id']) ? $_GET['id'] : '1';
$user_id = $_SESSION['customer_id'];
$customer = find_customer_id_by_user_id($user_id);
//$c_no = $_SESSION['customer_id'];
$payment_set = find_payments_by_customer_id($customer['c_no']);

//$id = $_GET['id'] ?? '1'; // PHP > 7.0
//
//$order = find_order_by_id($id);
//$subject = find_subject_by_id($page['subject_id']);

?>





<?php $page_title = 'My Payments'; ?>
<section class="">
		<?php
			include '../header.php';
		?>

			<section class="caption">
            <a class="back-link" href="<?php echo url_for('../public/pagetwo.php'); ?>">&laquo; Back to User Home</a>
				<h2 class="caption" style="text-align: center">Find You Dream Cars For Hire</h2>
				<h3 class="properties" style="text-align: center">Range Rovers - Mercedes Benz - Landcruisers</h3>
			</section>
	</section><!--  end hero section  -->



	<section class="search">
		<div class="wrapper">
		<div id="fom">
		&nbsp;
        <h3 style="text-align:center; color: #000099; font-weight:bold; text-decoration:underline">Show all my payments</h3>
        &nbsp;
        </div>
        <dl>
            <dt><?php echo $_SESSION['message'] ?? ''; $_SESSION['message'] = ''; ?></dt>
        </dl>

        <div class="payments show">
        

            <?php if($payment_set!=[]){?>
                <table class="list">
                    <tr>
                        <th><h5 style="text-align:left; color: black; font-weight:bold; ">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <th><h5 style="text-align:left; color: black; font-weight:bold; ">Brand &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                        <th><h5 style="text-align:left; color: black; font-weight:bold; ">Model&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                        <th><h5 style="text-align:left; color: black; font-weight:bold; ">Amount&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                        <th><h5 style="text-align:left; color: black; font-weight:bold; ">Method&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                        <th><h5 style="text-align:left; color: black; font-weight:bold; ">Payment Date&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                        <th>&nbsp;</th>
                        <th>&nbsp;</th>
                    </tr>

                    <?php
                    while($payment = mysqli_fetch_assoc($payment_set)){ ?>

                        <tr>
						    <td>&nbsp;</td>
                            <td><?php echo h($payment['make']); ?></td>
                            <td><?php echo h($payment['model']); ?></td>
                            <td><?php echo h($payment['amount']); ?></td>
                            <td><?php echo h($payment['method']); ?></td>
                            <td><?php echo substr(h($payment['p_date']), 0, 10); ?></td>
                            <td><?php echo h(substr($payment['card_no'], 0, 4) . '********' . substr($payment['card_no'], -4)); ?></td>
                        </tr>
                    <?php } ?>
                </table>
            <?php mysqli_free_result($payment_set); }
            else{?>
                <p>no payments</p>
            <?php }?>


        </div>
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


