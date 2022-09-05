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

<?php

require_once('../private/initialize.php');

require_login_customer();

//$id = get_customer_id(); // PHP > 7.0
$user_id = $_SESSION['customer_id'];
$customer_id = find_customer_id_by_user_id($user_id);
$customer = find_user_info_by_customer_id($customer_id['c_no']);
//$customer = find_customer_by_id($customer['c_no']);

?>


<?php $page_title = 'Show Customer'; ?>
<section class="">
		<?php
			include 'header.php';
		?>
<a class="back-link" href="<?php echo url_for('pagetwo.php'); ?>">&laquo; Back to User Home</a>
			<section class="caption">
				<h2 class="caption" style="text-align: center">Find You Dream Cars For Hire</h2>
				<h3 class="properties" style="text-align: center">Range Rovers - Mercedes Benz - Landcruisers</h3>
			</section>
	</section><!--  end hero section  -->



	<section class="search">
		<div class="wrapper">
		<div id="fom">
    &nbsp;
			<form action="pageshow" method="post">
      &nbsp;
                <h3 style="text-align:center; color: #000099; font-weight:bold; text-decoration:underline">Show My Profile</h3>

                    <table height="500" align="center">
                    <tr><td><h2>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo h($customer['username']); ?></h2></td></tr>
            &nbsp;
                        <div class="actions">
                            <tr> <td><h4><a class="action" href="<?php echo url_for('pageedit.php?id=' . h(u($customer['id']))); ?>"></h4><h4>Edit Profile</h4></a></td></tr>

                        </div>

                        <div class="">
                            <tr>
                                <td><h4>First name:</td>
                                <td></h4><?php echo h($customer['i_fname']); ?></td>
                            </tr>
                            <tr>
                                <td><h4>Last name：</td>
                                <td></h4><?php echo h($customer['i_lname']); ?></td>
                            </tr>
                            <tr>
                                <td> <h4>Email：</td>
                                <td></h4><?php echo h($customer['c_email']); ?></dd></td>
                            </tr>
                            <tr>
                                <td><h4>Username：</td>
                                <td></h4><?php echo h($customer['username']); ?></dd></td>
                            </tr>
                            <tr>
                                <td><h4>Phone Number：</td>
                                <td></h4><?php echo h($customer['p_no']); ?></dd></td>
                            </tr>
                            <tr>
                                <td><h4>Street：</td>
                                <td></h4><?php echo h($customer['c_street']); ?></dd></td>
                            </tr>
                            <tr>
                                <td><h4>City：</td>
                                <td></h4><?php echo h($customer['c_city']); ?></dd></td>
                            </tr>
                            <tr>
                                <td><h4>State：</td>
                                <td></h4><?php echo h($customer['c_state']); ?></dd></td>
                            </tr>
                            <tr>
                                <td><h4>Zipcode：</td>
                                <td></h4><?php echo h($customer['c_zipcode']); ?></dd></td>
                            </tr>
                            <tr>
                                <td><h4>Driver License Number：</td>
                                <td></h4><?php echo h($customer['dl_no']); ?></dd></td>
                            </tr>
                            <tr>
                                <td><h4>Insurance Company Name：</td>
                                <td></h4><?php echo h($customer['ins_c_name']); ?></dd></td>
                            </tr>
                            <tr>
                                <td><h4>Insurance Policy Number：</td>
                                <td></h4><?php echo h($customer['ins_p_no']); ?></dd></td>
                            </tr>
                          <tr><td>&nbsp;</td></tr>
                        </div>

                    </table>
			</form>

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

