<?php

require_once('../../../private/initialize.php');

require_login();

if(is_post_request()) {

    $emp = [];
    $emp['c_type'] = 'E';
    $emp['c_street'] = $_POST['c_street'] ?? '';
    $emp['c_city'] = $_POST['c_city'] ?? '';
    $emp['c_state'] = $_POST['c_state'] ?? '';
    $emp['c_zipcode'] = $_POST['c_zipcode'] ?? '';
    $emp['c_email'] = $_POST['c_email'] ?? '';
    $emp['p_no'] = $_POST['p_no'] ?? '';
    $emp['emp_id'] = $_POST['emp_id'] ?? '';
//    $emp['coop_id'] = $_POST['coop_id'] ?? '';
    $id_set = find_coop_id_by_name($_POST['c_name']);
    while($coop_id = mysqli_fetch_assoc($id_set)) {
        $emp['coop_id'] = $coop_id['coop_id'];
    }

    $result = insert_customer($emp);
    if($result === true) {
        $emp['c_no'] = mysqli_insert_id($db);
        $result = insert_emp($emp);
        if($result === true) {
            $new_id = mysqli_insert_id($db);
            $_SESSION['message'] = 'The corporate customer was created successfully.';
            redirect_to(url_for('/staff/emp/show.php?c_no=' . $new_id));
        } else{
            $errors = $result;
        }
    } else {
        $errors = $result;
    }

} else {

    $emp = [];
    $emp['c_no'] = '';
    $emp['c_street'] = '';
    $emp['c_city'] = '';
    $emp['c_state'] = '';
    $emp['c_zipcode'] = '';
    $emp['c_email'] = '';
    $emp['p_no'] = '';
    $emp['emp_id'] = '';
    $emp['coop_id'] = '';
    $emp['c_name'] = '';
}

?>

<?php $page_title = 'Create Corporate Customer'; ?>
<?php include(SHARED_PATH . '/staff_header.php'); ?>

<div id="content">

    <a class="back-link" href="<?php echo url_for('/staff/emp/index.php'); ?>">&laquo; Back to Corporate Customer Page</a>

    <div class="corporate customer new">
        <h1>Create Corporate Customer</h1>

        <?php echo display_errors($errors); ?>

        <form action="<?php echo url_for('/staff/emp/new.php'); ?>" method="post">
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
                <input type="submit" value="Create Corporate Customer" />
            </div>
        </form>

    </div>

</div>

<?php include(SHARED_PATH . '/staff_footer.php'); ?>
