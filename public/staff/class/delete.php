<?php

require_once('../../../private/initialize.php');

require_login();

if(!isset($_GET['c_id'])) {
    redirect_to(url_for('/staff/class/index.php'));
}
$c_id = $_GET['c_id'];

$class_set = find_class_by_id($c_id);

if(is_post_request()) {

    $result = delete_class($c_id);
    $_SESSION['message'] = 'The page was deleted successfully.';
    redirect_to(url_for('/staff/class/index.php'));

}

?>

<?php $page_title = 'Delete Class'; ?>
<?php include(SHARED_PATH . '/staff_header.php'); ?>

<div id="content">

    <a class="back-link" href="<?php echo url_for('/staff/class/index.php'); ?>">&laquo; Back to Previous Page</a>

    <div class="class delete">
        <?php while($class = mysqli_fetch_assoc($class_set)){ ?>
        <h1>Delete Class</h1>
        <p>Are you sure you want to delete this class?</p>
        <p class="item">Class Name: <?php echo h($class['class_name']); ?></p>
        <p class="item">Rental Rate: <?php echo h($class['rental_rate']); ?></p>
        <p class="item">Over Limit Fee: <?php echo h($class['over_fee']); ?></p>

        <form action="<?php echo url_for('/staff/class/delete.php?c_id=' . h(u($class['c_id']))); ?>" method="post">
            <div id="operations">
                <input type="submit" name="commit" value="Delete Class" />
            </div>
        </form>
        <?php } mysqli_free_result($class_set); ?>
    </div>

</div>

<?php include(SHARED_PATH . '/staff_footer.php'); ?>
