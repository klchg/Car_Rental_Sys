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

require_customer_not_login();

$errors = [];
$username = '';
$password = '';
$time=[];
$time=date('Y-m-d h:i:sa');
?>



<?php
				if(is_post_request()) {

                    $username = h($_POST['username']) ?? '';
                    $password = h($_POST['password']) ?? '';
                
                    // Validations
                    if(is_blank($username)) {
                        $errors[] = "Username cannot be blank.";
                    }
                    if(is_blank($password)) {
                        $errors[] = "Password cannot be blank.";
                    }
                
                    // if there were no errors, try to login
                    if(empty($errors)) {
                        // Using one variable ensures that msg is the same
                        $login_failure_msg = "Log in was unsuccessful.";
                
                        $customer = find_customer_by_username($username);
                        if($customer) {
                
                            //if($password===$customer['hashed_password']){ //store bare password
                            if(password_verify($password, $customer['hashed_password'])) {
                                // password matches
                                log_in_customer($customer);
                                redirect_to(url_for('pagetwo.php'));
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



<?php $page_title = 'Customer Log in'; ?>
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
			<form action="pagelogin.php" method="post">
			&nbsp;
			<h3 style="text-align:center; color: #000099; font-weight:bold; text-decoration:underline">Client Login Area</h3>
			<dl>
			&nbsp;
            <dt><h4><?php echo $_SESSION['message'] ?? ''; $_SESSION['message'] = ''; ?><h4></dt>
			&nbsp;
        </dl>
            <?php echo display_errors($errors); ?>
				<table height="120" align="center">
					<tr>
						<td>&nbsp;&nbsp;&nbsp;Username:</td>
						<td>&nbsp;&nbsp;&nbsp;<input type="text" name="username" value="<?php echo h($username); ?>" placeholder="Enter Username" required></td>
					</tr>
					<tr>
						<td>&nbsp;&nbsp;&nbsp;Password:</td>
						<td>&nbsp;&nbsp;&nbsp;<input type="password" name="password" value="<?php echo h($password); ?>" placeholder="Enter Password" required></td>
					</tr>
					<tr>
						<td style="text-align:center;"><a href="pagesignup.php">Signup Here</a></td>
                        <td style="text-align:center;"><a href="forgotpassword.php">Forgot Password?</a></td>
                        <td><input type="submit" name="submit" value="Login"></td>
					</tr>
					
				</table>
			</form>
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


