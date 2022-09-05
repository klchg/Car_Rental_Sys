<?php

require_once('../../../private/initialize.php');

require_login();

if(!isset($_GET['c_id'])) {
    redirect_to(url_for('/staff/class/index.php'));
}
$c_id = $_GET['c_id'];

if(is_post_request()) {

    // Handle form values sent by new.php
    $class = [];
    $class['c_id'] = $c_id;
    $class['class_name'] = $_POST['class_name'] ?? '';
    $class['rental_rate'] = $_POST['rental_rate'] ?? '';
    $class['over_fee'] = $_POST['over_fee'] ?? '';

    $result = update_class($class);
    if($result === true) {
        $_SESSION['message'] = 'The class was updated successfully.';
        redirect_to(url_for('/staff/class/show.php?c_id=' . $c_id));
    } else {
        $errors = $result;
    }

} else {

    $class_set = find_class_by_id($c_id);

}

?>

<?php $page_title = 'Edit Class'; ?>
<?php include(SHARED_PATH . '/staff_header.php'); ?>

<div id="content">

    <a class="back-link" href="<?php echo url_for('/staff/class/index.php'); ?>">&laquo; Back to Previous Page</a>

    <div class="class edit">
        <h1>Edit Class</h1>

        <?php echo display_errors($errors); ?>

        <form action="<?php echo url_for('/staff/class/edit.php?c_id=' . h(u($c_id))); ?>" method="post">
            <?php while($class = mysqli_fetch_assoc($class_set)){ ?>
            <dl>
                <dt>Class Name</dt>
                <dd><input type="text" name="class_name" value="<?php echo h($class['class_name']); ?>" /></dd>
            </dl>
            <dl>
                <dt>Rental Rate</dt>
                <dd><input type="text" name="rental_rate" value="<?php echo h($class['rental_rate']); ?>" /></dd>
            </dl>
            <dl>
                <dt>Over Limit Fee</dt>
                <dd><input type="text" name="over_fee" value="<?php echo h($class['over_fee']); ?>" /></dd>
            </dl>
            <div id="operations">
                <input type="submit" value="Edit Class" />
            </div>
            <?php } mysqli_free_result($class_set); ?>
        </form>

    </div>

</div>

<?php include(SHARED_PATH . '/staff_footer.php'); ?>
