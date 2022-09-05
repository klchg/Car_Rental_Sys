<?php

require_once('../../../private/initialize.php');

require_login();

if(!isset($_GET['c_no'])) {
    redirect_to(url_for('/staff/emp/index.php'));
}
$c_no = $_GET['c_no'];

if(is_post_request()) {

    $emp = [];
    $emp['c_no'] = $c_no;
    $emp['c_type'] = 'E';
    $emp['c_street'] = $_POST['c_street'] ?? '';
    $emp['c_city'] = $_POST['c_city'] ?? '';
    $emp['c_state'] = $_POST['c_state'] ?? '';
    $emp['c_zipcode'] = $_POST['c_zipcode'] ?? '';
    $emp['c_email'] = $_POST['c_email'] ?? '';
    $emp['p_no'] = $_POST['p_no'] ?? '';
    $emp['emp_id'] = $_POST['emp_id'] ?? '';
    $id_set = find_coop_id_by_name($_POST['c_name']);
    while($coop_id = mysqli_fetch_assoc($id_set)) {
        $emp['coop_id'] = $coop_id['coop_id'];
    }

    $result = update_emp($emp);
    if($result === true) {
        $result = update_customer($emp);
        if($result === true) {
            $_SESSION['message'] = 'The corporate customer info was updated successfully.';
            redirect_to(url_for('/staff/emp/show.php?c_no=' . $c_no));
        } else {
            $errors = $result;
        }
    } else {
        $errors = $result;
    }

} else {

    $emp_set = find_emp_detail_by_id($c_no);

}

?>

<?php $page_title = 'Edit Corporate Customer Record'; ?>
<?php include(SHARED_PATH . '/staff_header.php'); ?>

<div id="content">

    <a class="back-link" href="<?php echo url_for('/staff/emp/index.php'); ?>">&laquo; Back to Previous Page</a>

    <div class="corporation edit">
        <h1>Edit Corporate Customer Info</h1>

        <?php echo display_errors($errors); ?>

        <form action="<?php echo url_for('/staff/emp/edit.php?c_no=' . h(u($c_no))); ?>" method="post">

            <?php while($emp = mysqli_fetch_assoc($emp_set)){ ?>
                <input type="hidden" name="c_type" value="<?php echo $emp['c_type']?>"/>
                <dl>
                    <dt>Street</dt>
                    <dd><input type="text" name="c_street" value="<?php echo h($emp['c_street']); ?>" /></dd>
                </dl>
                <dl>
                    <dt>City</dt>
                    <dd><input type="text" name="c_city" value="<?php echo h($emp['c_city']); ?>" /></dd>
                </dl>
                <dl>
                    <dt>State</dt>
                    <dd><input type="text" name="c_state" value="<?php echo h($emp['c_state']); ?>" /></dd>
                </dl>
                <dl>
                    <dt>Zipcode</dt>
                    <dd><input type="text" name="c_zipcode" value="<?php echo h($emp['c_zipcode']); ?>" /></dd>
                </dl>
                <dl>
                    <dt>Email</dt>
                    <dd><input type="text" name="c_email" value="<?php echo h($emp['c_email']); ?>" /></dd>
                </dl>
                <dl>
                    <dt>Phone Number</dt>
                    <dd><input type="text" name="p_no" value="<?php echo h($emp['p_no']); ?>" /></dd>
                </dl>
                <dl>
                    <dt>Corporation Name</dt>
                    <dd>
                        <select name="c_name">
                            <?php
                            $coop_set = find_all_coop_name();
                            while($coop = mysqli_fetch_assoc($coop_set)) {
                                echo "<option value=\"" . h($coop['c_name']) . "\"";
                                if($emp['c_name'] == $coop['c_name']) {
                                    echo " selected";
                                }
                                echo ">" . h($coop['c_name']) . "</option>";
                            }
                            mysqli_free_result($coop_set);
                            ?>
                        </select>
                    </dd>
                </dl>
                <dl>
                    <dt>Corporate Customer Number</dt>
                    <dd><input type="text" name="emp_id" value="<?php echo h($emp['emp_id']); ?>" /></dd>
                </dl>
                <div id="operations">
                    <input type="submit" value="Edit Corporate Customer" />
                </div>
            <?php } mysqli_free_result($emp_set); ?>
        </form>

    </div>

</div>

<?php include(SHARED_PATH . '/staff_footer.php'); ?>
