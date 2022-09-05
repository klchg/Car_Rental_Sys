<?php

require_once('../../private/initialize.php');

require_login_customer();

//if(is_post_request()) {
if ((!isset($_GET['s_id'])) || empty($_GET['s_id']) || $_GET['s_id'] == '') 
    redirect_to(url_for('/Orders/myorders.php'));
if ((!isset($_GET['vid'])) || empty($_GET['vid']) || $_GET['vid'] == '') 
    redirect_to(url_for('/Orders/myorders.php'));
$service = [];
$service['s_id'] = $_GET['s_id'];
$service['vid'] = $_GET['vid'];
    
$result = delete_service($service);
if($result === true) {
    $_SESSION['message'] = 'The order was deleted successfully.';
        
} else {
    $errors = $result;
}
redirect_to(url_for('/Orders/myorders.php'));
//} else {    
//    redirect_to(url_for('myorders.php'));
//}

?>

<?php include(SHARED_PATH . '/staff_footer.php'); ?>
