<?php

require_once('../../../private/initialize.php');

require_login();

if(!isset($_GET['coop_id'])) {
    redirect_to(url_for('/staff/coop/index.php'));
}
$coop_id = $_GET['coop_id'];

$coop_set = find_coop_by_id($coop_id);

if(is_post_request()) {

    $result = delete_coop($coop_id);
    $_SESSION['message'] = 'The corporation record was deleted successfully.';
    redirect_to(url_for('/staff/coop/index.php'));

}

?>

<?php $page_title = 'Delete Corporation Record'; ?>
<?php include(SHARED_PATH . '/staff_header.php'); ?>

<div id="content">

    <a class="back-link" href="<?php echo url_for('/staff/coop/index.php'); ?>">&laquo; Back to Previous Page</a>

    <div class="coop delete">
        <?php while($coop = mysqli_fetch_assoc($coop_set)){ ?>
        <h1>Delete Class</h1>
        <p>Are you sure you want to delete this corporation record?</p>
            <p class="item">Corporation Name: <?php echo h($coop['c_name']); ?></p>
            <p class="item">Registration Number: <?php echo h($coop['reg_no']); ?></p>
            <p class="item">Discount Rate: <?php echo h($coop['c_rate']); ?></p>

        <form action="<?php echo url_for('/staff/coop/delete.php?coop_id=' . h(u($coop['coop_id']))); ?>" method="post">
            <div id="operations">
                <input type="submit" name="commit" value="Delete Corporation Record" />
            </div>
        </form>

        <?php } mysqli_free_result($coop_set); ?>
    </div>

</div>

<?php include(SHARED_PATH . '/staff_footer.php'); ?>
