<?php

require_once('../../../private/initialize.php');

require_login();

if(!isset($_GET['c_no'])) {
    redirect_to(url_for('/staff/emp/index.php'));
}
$c_no = $_GET['c_no'];

$emp_set = find_emp_by_id($c_no);

if(is_post_request()) {

    $result = delete_emp($c_no);
    if($result===true){
        $result = delete_customer($c_no);
        if($result===true){
            $_SESSION['message'] = 'The corporate customer record was deleted successfully.';
            redirect_to(url_for('/staff/emp/index.php'));
        }
    }

}

?>

<?php $page_title = 'Delete Corporate Customer'; ?>
<?php include(SHARED_PATH . '/staff_header.php'); ?>

<div id="content">

    <a class="back-link" href="<?php echo url_for('/staff/emp/index.php'); ?>">&laquo; Back to Previous Page</a>

    <div class="emp delete">
        <?php while($emp = mysqli_fetch_assoc($emp_set)){ ?>
        <h1>Delete Corporate Customer</h1>
        <p>Are you sure you want to delete this corporate customer?</p>
            <p class="item">Corporate Customer ID: <?php echo h($emp['emp_id']); ?></p>
            <p class="item">Corporation Name: <?php echo h($emp['c_name']); ?></p>
            <p class="item">Email Address: <?php echo h($emp['c_email']); ?></p>
            <p class="item">Phone Number: <?php echo h($emp['p_no']); ?></p>
            <p class="item">Corporate Discount Rate: <?php echo h($emp['c_rate']); ?></p>

        <form action="<?php echo url_for('/staff/emp/delete.php?c_no=' . h(u($emp['c_no']))); ?>" method="post">
            <div id="operations">
                <input type="submit" name="commit" value="Delete Corporate Customer" />
            </div>
        </form>

        <?php } mysqli_free_result($emp_set); ?>
    </div>

</div>

<?php include(SHARED_PATH . '/staff_footer.php'); ?>
