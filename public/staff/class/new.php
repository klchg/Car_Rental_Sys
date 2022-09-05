<?php

require_once('../../../private/initialize.php');

require_login();

if(is_post_request()) {

    $class = [];
    $class['class_name'] = $_POST['class_name'] ?? '';
    $class['rental_rate'] = $_POST['rental_rate'] ?? '';
    $class['over_fee'] = $_POST['over_fee'] ?? '';

    $result = insert_class($class);
    if($result === true) {
        $new_id = mysqli_insert_id($db);
        $_SESSION['message'] = 'The class was created successfully.';
        redirect_to(url_for('/staff/class/show.php?c_id=' . $new_id));
    } else {
        $errors = $result;
    }

} else {

    $class = [];
    $class['class_name'] = '';
    $class['rental_rate'] = '';
    $class['over_fee'] = '';

}

?>

<?php $page_title = 'Create Class'; ?>
<?php include(SHARED_PATH . '/staff_header.php'); ?>

<div id="content">

    <a class="back-link" href="<?php echo url_for('/staff/class/index.php'); ?>">&laquo; Back to Class Page</a>

    <div class="class new">
        <h1>Create New Class</h1>

        <?php echo display_errors($errors); ?>

        <form action="<?php echo url_for('/staff/class/new.php'); ?>" method="post">
            <dl>
                <dt>Class Name</dt>
                <dd><input type="text" name="class_name" value="<?php echo h($class['class_name']); ?>" /></dd>
            </dl>
            <dl>
                <dt>Rental Rate</dt>
                <dd><input type="text" name="rental_rate" value="<?php echo h($class['rental_rate']); ?>" /></dd>
            </dl>
            <dl>
                <dt>Overtime Fee</dt>
                <dd><input type="text" name="over_fee" value="<?php echo h($class['over_fee']); ?>" /></dd>
            </dl>
            <div id="operations">
                <input type="submit" value="Create Class" />
            </div>
        </form>

    </div>

</div>

<?php include(SHARED_PATH . '/staff_footer.php'); ?>
