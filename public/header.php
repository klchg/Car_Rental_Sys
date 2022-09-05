
<header>
			<div class="wrapper">
			<a href="<?php echo url_for('/index.php'); ?>">
          <img src="<?php echo url_for('/images/logo.png'); ?>" width="480" height="67" alt="" />
        </a>
				<a href="#" class="hamburger"></a>
				<nav>
					<?php
						if (!isset($_SESSION['customer_id'])){
					?>
					<ul>
						<li><a href="index.php">Home</a></li>
						<li><a href="vehicle_search.php">Rent Cars</a></li>
						<li><a href="pagefaq.php">FAQs</a></li>
						<li><a href="contact-us.php">Contact</a></li>
					</ul>
					<a href="pagelogin.php">Client Login</a>
					<a href="staff/index.php">Admin</a>
					<?php
						} else{
					?>
							<ul>
								<li><a href="<?php echo url_for('/index.php'); ?>">Home</a></li>
								<li><a href="<?php echo url_for('/vehicle_search.php'); ?>">Rent Cars</a></li>
								<li><a href="<?php echo url_for('/contact-us.php'); ?>">Contact Us</a></li>
								<li><a href="<?php echo url_for('/pagefaq.php'); ?>">FAQs</a></li>
								<li><a href="<?php echo url_for('/pagetwo.php'); ?>">User</a></li>
							</ul>
					<a href="<?php echo url_for('/pagelogout.php'); ?>">Logout</a>
					<?php
						}
					?>
				</nav>
			</div>
		</header>