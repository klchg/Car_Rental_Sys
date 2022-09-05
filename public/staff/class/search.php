<?php

require_once('../../../private/initialize.php');

require_login();

if(is_post_request()) {

    $class = [];
    $class['class_name'] = $_POST['class_name'] ?? '';

    $available_set = find_class_by_name($class);

} else {

    $class = [];
    $class['class_name'] = '';
    $available_set = [];

}

?>

<?php $page_title = 'Class Search'; ?>
<?php include(SHARED_PATH . '/staff_header.php'); ?>

<div id="content">
    <a class="back-link" href="<?php echo url_for('/staff/class/index.php'); ?>">&laquo; Back to Previous Page</a>

    <div class="class search">
        <h1>Search class by name</h1>

        <form action="<?php echo url_for('/staff/class/search.php'); ?>" method="post">
            <dl>
                <dt>Name</dt>
                <dd>
                    <select name="class_name">
                        <?php
                        $name_set = find_all_class_name();
                        while($name = mysqli_fetch_assoc($name_set)) {
                            echo "<option value=\"" . h($name['class_name']) . "\"";
                            if($class['class_name'] == $name['class_name']) {
                                echo " selected";
                            }
                            echo ">" . h($name['class_name']) . "</option>";
                        }
                        mysqli_free_result($name_set);
                        ?>
                    </select>
                </dd>
            </dl>

            <div id="operations">
                <input type="submit" value="Search" />
            </div>

        </form>

        <?php if($available_set!=[]){?>
            <table class="list">
                <tr>
                    <th>Class Name</th>
                    <th>Rental Rate</th>
                    <th>Over Fee</th>
                    <th>&nbsp;</th>
                    <th>&nbsp;</th>
                </tr>

                <?php
                while($available = mysqli_fetch_assoc($available_set)){ ?>

                    <tr>
                        <td><?php echo h($available['class_name']); ?></td>
                        <td><?php echo h($available['rental_rate']); ?></td>
                        <td><?php echo h($available['over_fee']); ?></td>
                        <td><a class="action" href="<?php echo url_for('/staff/class/edit.php?c_id=' . h(u($available['c_id'])));?>">Edit</a></td>
                        <td><a class="action" href="<?php echo url_for('/staff/class/delete.php?&c_id=' . h(u($available['c_id'])));?>">Delete</a></td>
                    </tr>
                <?php } ?>
            </table>

            <?php mysqli_free_result($available_set); }?>

    </div>

</div>

<?php include(SHARED_PATH . '/staff_footer.php'); ?>
