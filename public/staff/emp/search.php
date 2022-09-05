<?php

require_once('../../../private/initialize.php');

require_login();

if(is_post_request()) {

    $emp = [];
    $emp['emp_id'] = $_POST['emp_id'] ?? '';
    $emp['c_name'] = $_POST['c_name'] ?? '';
    $emp['c_email'] = $_POST['c_email'] ?? '';
    $emp['p_no'] = $_POST['p_no'] ?? '';

    $result = find_emp_by_factors($emp);
    $available_set = $result[0];
    $errors = $result[1];

} else {

    $emp = [];
    $emp['emp_id'] = '';
    $emp['c_name'] = '';
    $emp['c_email'] = '';
    $emp['p_no'] = '';
    $available_set = [];

}

?>

<?php $page_title = 'Corporate Customer Search'; ?>
<?php include(SHARED_PATH . '/staff_header.php'); ?>

<div id="content">
    <a class="back-link" href="<?php echo url_for('/staff/emp/index.php'); ?>">&laquo; Back to Previous Page</a>

    <div class="corporate customer search">
        <h1>Search Corporate Customer</h1>

        <?php if(!is_null($errors)){echo display_errors($errors);} ?>

        <form action="<?php echo url_for('/staff/emp/search.php'); ?>" method="post">
            <dl>
                <dt>Corporate Customer ID</dt>
                <dd>
                    <input name="emp_id" type="text" />
                </dd>
            </dl>

            <dl>
                <dt>Corporation Name</dt>
                <dd>
                    <select name="c_name">
                        <option value=""></option>
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
                <dt>Email Address</dt>
                <dd>
                    <input name="c_email" type="text" />
                </dd>
            </dl>

            <dl>
                <dt>Phone Number</dt>
                <dd>
                    <input name="p_no" type="text" />
                </dd>
            </dl>

            <div id="operations">
                <input type="submit" value="Search" />
            </div>

        </form>

        <?php if($available_set!=[]){?>
            <table class="list">
                <tr>
                    <th>Corporate Customer ID</th>
                    <th>Corporation Name</th>
                    <th>Email Address</th>
                    <th>Phone Number</th>
                    <th>&nbsp;</th>
                    <th>&nbsp;</th>
                    <th>&nbsp;</th>
                </tr>

                <?php
                while($available = mysqli_fetch_assoc($available_set)){ ?>

                    <tr>
                        <td><?php echo h($available['emp_id']); ?></td>
                        <td><?php echo h($available['c_name']); ?></td>
                        <td><?php echo h($available['c_email']); ?></td>
                        <td><?php echo h($available['p_no']); ?></td>
                        <td><a class="action" href="<?php echo url_for('/staff/emp/show.php?c_no=' . h(u($available['c_no']))); ?>">View</a></td>
                        <td><a class="action" href="<?php echo url_for('/staff/emp/edit.php?c_no=' . h(u($available['c_no'])));?>">Edit</a></td>
                        <td><a class="action" href="<?php echo url_for('/staff/emp/delete.php?&c_no=' . h(u($available['c_no'])));?>">Delete</a></td>
                    </tr>
                <?php } ?>
            </table>

            <?php mysqli_free_result($available_set); }?>

    </div>

</div>

<?php include(SHARED_PATH . '/staff_footer.php'); ?>
