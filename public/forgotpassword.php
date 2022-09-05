<?php
require_once('../private/initialize.php');
require_customer_not_login();
$errors = [];
$username = '';
$email = '';
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
<body>

<?php
if(is_post_request()) {

    $username = $_POST['username'] ?? '';
    $email = $_POST['email'] ?? '';

    // Validations
    if(is_blank($username)) {
        $errors[] = "Username cannot be blank.";
    }
    if(is_blank($email)) {
        $errors[] = "email cannot be blank.";
    }

    // if there were no errors, try to login
    if(empty($errors)) {
        // Using one variable ensures that msg is the same
        $login_failure_msg = "Verify was unsuccessful.";

        $user = find_customer_by_username($username);
        $customer = find_user_by_user_id($user['id']);

        if($customer) {

            if($email===$customer['c_email']){ //store bare password
            //if(password_verify($password, $customer['hashed_password'])) {
                // password matches
                echo "email correct";
                log_in_customer($customer);
//                redirect_to(url_for('pageedit.php?id=' . $customer['id']));
                redirect_to(url_for('pageedit.php'));
            } else {
                // username found, but password does not match
                $errors[] = $login_failure_msg;
            }

        } else {
            // no username found
            $errors[] = $login_failure_msg;
        }

    }

}

?>

<?php $page_title = 'forgot password'; ?>
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
		<div id="fom">&nbsp;
			<form action="forgotpassword.php" method="post">
			<h3 style="text-align:center; color: #000099; font-weight:bold; text-decoration:underline">Forgot Password</h3>
            <?php echo display_errors($errors); ?>
            <?php echo display_errors($errors); ?>
				<table height="120" align="center">
					<tr>
						<td>Username:</td>
						<td><input type="text" name="username" value="<?php echo h($username); ?>" placeholder="Enter Username" required></td>
					</tr>
					<tr>
						<td>Email:</td>
						<td><input type="text" name="email" value="<?php echo h($email); ?>" placeholder="Enter Email" required></td>
					</tr>
					<tr>
						<td style="text-align:center;"><a href="pagesignup.php">Signup Here</a></td>
                        <td style="text-align:center;"><a href="pagelogin.php">Login Here</a></td>
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

