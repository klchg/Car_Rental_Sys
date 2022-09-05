<?php

require_once('../private/initialize.php');
date_default_timezone_set('America/New_York');
$time=[];
$time=date('Y-m-d h:i:sa');

?>

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


<?php
if(is_post_request()) {
  $contact['time'] = $time ??'';
  $contact['first_name'] = $_POST['first_name'] ?? '';
  $contact['last_name'] = $_POST['last_name'] ?? '';
  $contact['email'] = $_POST['email'] ?? '';
  $contact['info'] = $_POST['info'] ?? '';

  $result = insert_contact($contact);
  if($result === true) {
   
    $_SESSION['message'] = 'contact submitted.';
    redirect_to(url_for('index.php'));
  } else {
    $errors = $result;
  }

} else {
  // display the blank form
  $contact = [];
  $contact["first_name"] = '';
  $contact["last_name"] = '';
  $contact["email"] = '';
  $contact['info'] = '';
}

?>

<?php $page_title = 'Contact Us'; ?>
<section class="">
		<?php
			include 'header.php';
		?>

			<section class="caption">
			<a class="back-link" href="<?php echo url_for('index.php'); ?>">&laquo; Back to Home</a>
				<h2 class="caption" style="text-align: center">Find You Dream Cars For Hire</h2>
				<h3 class="properties" style="text-align: center">Range Rovers - Mercedes Benz - Landcruisers</h3>
			</section>
	</section><!--  end hero section  -->



	<section class="search">
		<div class="wrapper">
		<div id="fom">
			<form action="contact-us.php" method="post">
			&nbsp;
			<h3 style="text-align:center; color: #000099; font-weight:bold; text-decoration:underline">Send us messages</h3>
			&nbsp;
            <?php echo display_errors($errors); ?>
				<table height="250" align="center">
					<tr>
						<td>First Name:</td>
						<td><input type="text" name="first_name" value="<?php echo h($contact['first_name']); ?>" placeholder="Enter First Name" required></td>
					</tr>
          <tr>
						<td>Last Name:</td>
						<td><input type="text" name="last_name" value="<?php echo h($contact['last_name']); ?>" placeholder="Enter Last Name" required></td>
					</tr>
          <tr>
						<td>Email:</td>
						<td><input type="text" name="email" value="<?php echo h($contact['email']); ?>" placeholder="Enter Email" required></td>
					</tr>
					<tr>
						<td>Message:</td>
						<td><input type="text" name="info" value="<?php echo h($contact['info']); ?>" placeholder="Enter Your Message" required></td>
					</tr>
					<tr>
												<td style="text-align:center;"><a href="pagelogin.php">Login Here</a></td>
                        <td style="text-align:center;"><a href="index.php">Back to Homepage</a></td>
                        <td><input type="submit" name="submit" value="Submit"></td>
					</tr>
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

