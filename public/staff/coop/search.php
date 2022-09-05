<?php

require_once('../../../private/initialize.php');

require_login();

if(is_post_request()) {

    $coop = [];
    $coop['c_name'] = h($_POST['c_name']) ?? '';
    $coop['reg_no'] = h($_POST['reg_no']) ?? '';
    $coop['c_rate'] = h($_POST['c_rate']) ?? '';

    $result = find_coop_by_factors($coop);
    $available_set = $result[0];
    $errors = $result[1];

} else {

    $coop = [];
    $coop['c_name'] = '';
    $coop['reg_no'] = '';
    $coop['c_rate'] = '';
    $available_set = [];

}

?>

<?php $page_title = 'Corporation Search'; ?>
<?php include(SHARED_PATH . '/staff_header.php'); ?>

<div id="content">
    <a class="back-link" href="<?php echo url_for('/staff/coop/index.php'); ?>">&laquo; Back to Previous Page</a>

    <div class="corporation search">
        <h1>Search Corporation</h1>

        <?php if(!is_null($errors)){echo display_errors($errors);} ?>

        <form action="<?php echo url_for('/staff/coop/search.php'); ?>" method="post">
            <dl>
                <dt>Corporation Name</dt>
                <dd>
                    <select name="c_name">
                        <option value=""></option>
                        <?php
                        $coop_set = find_all_coop_name();
                        while($coop_name = mysqli_fetch_assoc($coop_set)) {
                            echo "<option value=\"" . h($coop_name['c_name']) . "\"";
                            if($coop['c_name'] == $coop_name['c_name']) {
                                echo " selected";
                            }
                            echo ">" . h($coop_name['c_name']) . "</option>";
                        }
                        mysqli_free_result($coop_set);
                        ?>
                    </select>
                </dd>
            </dl>

            <dl>
                <dt>Registration Number</dt>
                <dd>
                    <input name="reg_no" type="text" />
                </dd>
            </dl>

            <dl>
                <dt>Discount Rate</dt>
                <dd>
                    <label for="c_rate">Input discount range from 1 to 100:</label>
                    <input name="c_rate" type="text" />
                </dd>
            </dl>

            <div id="operations">
                <input type="submit" value="Search" />
            </div>

        </form>

        <?php if($available_set!=[]){?>
            <table class="list">
                <tr>
                    <th>Corporation Name</th>
                    <th>Registration Number</th>
                    <th>iscount Rate</th>
                    <th>&nbsp;</th>
                    <th>&nbsp;</th>
                </tr>

                <?php
                while($available = mysqli_fetch_assoc($available_set)){ ?>

                    <tr>
                        <td><?php echo h($available['c_name']); ?></td>
                        <td><?php echo h($available['reg_no']); ?></td>
                        <td><?php echo h($available['c_rate']); ?></td>
                        <td><a class="action" href="<?php echo url_for('/staff/coop/edit.php?coop_id=' . h(u($available['coop_id'])));?>">Edit</a></td>
                        <td><a class="action" href="<?php echo url_for('/staff/coop/delete.php?&coop_id=' . h(u($available['coop_id'])));?>">Delete</a></td>
                    </tr>
                <?php } ?>
            </table>

            <?php mysqli_free_result($available_set); }?>

    </div>

</div>

<?php include(SHARED_PATH . '/staff_footer.php'); ?>
