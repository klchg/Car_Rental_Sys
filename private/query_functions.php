<?php

  function find_all_brand() {
    global $db;

    $sql = "SELECT distinct make FROM jxx_vehicle order by 1";
    //echo $sql;
    $result = mysqli_query($db, $sql);
    confirm_result_set($result);
    return $result;
  }

  function find_all_cities() {
    global $db;

    $sql = "SELECT distinct l_city FROM jxx_loc order by 1";
    //echo $sql;
    $result = mysqli_query($db, $sql);
    confirm_result_set($result);
    return $result;
  }


  function find_vehicles_by_brand($brand) {
    global $db;

    $sql = "SELECT * FROM jxx_vehicle ";
    $sql .= "where available = 1 and brand='" . db_escape($db,$brand) . "'";
    //echo $sql;
    $result = mysqli_query($db, $sql);
    confirm_result_set($result);
    return $result;
  }

  function find_vehicles_by_brand_and_city($search){
    global $db;
    $sql = "select v.vid, cl.rental_rate, cl.over_fee, l.l_id from jxx_vehicle v ";
    $sql .= "join jxx_class cl on v.c_id=cl.c_id ";
    $sql .= "join jxx_loc l on l.l_id=v.l_id ";
    $sql .= "where v.make='" . db_escape($db,$search['make']) . "' and l.l_city='" . db_escape($db,$search['l_city']) . "'";
//    echo $sql;
    $result = mysqli_query($db, $sql);
    confirm_result_set($result);
    return $result;
  }

//  function validate_subject($subject) {
//    $errors = [];
//
//    // menu_name
//    if(is_blank($subject['menu_name'])) {
//      $errors[] = "Name cannot be blank.";
//    } elseif(!has_length($subject['menu_name'], ['min' => 2, 'max' => 255])) {
//      $errors[] = "Name must be between 2 and 255 characters.";
//    }
//
//    // position
//    // Make sure we are working with an integer
//    $postion_int = (int) $subject['position'];
//    if($postion_int <= 0) {
//      $errors[] = "Position must be greater than zero.";
//    }
//    if($postion_int > 999) {
//      $errors[] = "Position must be less than 999.";
//    }
//
//    // visible
//    // Make sure we are working with a string
//    $visible_str = (string) $subject['visible'];
//    if(!has_inclusion_of($visible_str, ["0","1"])) {
//      $errors[] = "Visible must be true or false.";
//    }
//
//    return $errors;
//  }

  function validate_service($service) {
    $errors = [];

    // pick up date
    date_default_timezone_set("America/New_York");
    if(is_blank($service['pk_date'])) {
      $errors[] = "Pick up date cannot be blank.";
    } elseif(floor(strtotime(date("y-m-d h:i:s"))-strtotime($service['pk_date']))/86400 <= 1) {
      $errors[] = "Pick up date must be at least one day later than current date.";
    }

    // start odometer
    // end odometer
    // daily limit
    // vid
    // pick up location id
    // drop location id
    // customer id
    // coupoun id

  }

  function insert_subject($subject) {
    global $db;

    $errors = validate_subject($subject);
    if(!empty($errors)) {
      return $errors;
    }

    shift_subject_positions(0, $subject['position']);

    $sql = "INSERT INTO subjects ";
    $sql .= "(menu_name, position, visible) ";
    $sql .= "VALUES (";
    $sql .= "'" . db_escape($db, $subject['menu_name']) . "',";
    $sql .= "'" . db_escape($db, $subject['position']) . "',";
    $sql .= "'" . db_escape($db, $subject['visible']) . "'";
    $sql .= ")";
    $result = mysqli_query($db, $sql);
    // For INSERT statements, $result is true/false
    if($result) {
      return true;
    } else {
      // INSERT failed
      echo mysqli_error($db);
      db_disconnect($db);
      exit;
    }
  }


  function update_subject($subject) {
    global $db;

    $errors = validate_subject($subject);
    if(!empty($errors)) {
      return $errors;
    }

    $old_subject = find_subject_by_id($subject['id']);
    $old_position = $old_subject['position'];
    shift_subject_positions($old_position, $subject['position'], $subject['id']);

    $lock_sql = "SELECT * FROM subjects";
    $lock_sql .= "WHERE id='" . db_escape($db, $subject['id']) . "' ";
    $lock_sql .= "FOR UPDATE;";
    
    $sql = "UPDATE subjects SET ";
    $sql .= "menu_name='" . db_escape($db, $subject['menu_name']) . "', ";
    $sql .= "position='" . db_escape($db, $subject['position']) . "', ";
    $sql .= "visible='" . db_escape($db, $subject['visible']) . "' ";
    $sql .= "WHERE id='" . db_escape($db, $subject['id']) . "' ";
    $sql .= "LIMIT 1;";
    

    $result = execute_sql_query_with_lock($lock_sql, $sql, true, true);
    // For UPDATE statements, $result is true/false
    if($result) {
      return true;
    } else {
      // UPDATE failed
      echo mysqli_error($db);
      db_disconnect($db);
      exit;
    }

  }

  function delete_subject($id) {
    global $db;

    $old_subject = find_subject_by_id($id);
    $old_position = $old_subject['position'];
    shift_subject_positions($old_position, 0, $id);

    $lock_sql = "SELECT * FROM subjects ";
    $lock_sql .= "WHERE id='" . db_escape($db, $id) . "' ";
    $lock_sql .= "for update;";

    $sql = "DELETE FROM subjects ";
    $sql .= "WHERE id='" . db_escape($db, $id) . "' ";
    $sql .= "LIMIT 1;";

    $result = execute_sql_query_with_lock($lock_sql, $sql, true, true);

    // For DELETE statements, $result is true/false
    if($result) {
      return true;
    } else {
      // DELETE failed
      echo mysqli_error($db);
      db_disconnect($db);
      exit;
    }
  }

  function shift_subject_positions($start_pos, $end_pos, $current_id=0) {
    global $db;

    if($start_pos == $end_pos) { return; }
    $lock_sql = "SELECT * FROM subjects ";
    $sql = "UPDATE subjects ";
    if($start_pos == 0) {
      // new item, +1 to items greater than $end_pos
      $sql .= "SET position = position + 1 ";
      $sql .= "WHERE position >= '" . db_escape($db, $end_pos) . "' ";
      $lock_sql .= "WHERE position >= '" . db_escape($db, $end_pos) . "' ";
    } elseif($end_pos == 0) {
      // delete item, -1 from items greater than $start_pos
      $sql .= "SET position = position - 1 ";
      $sql .= "WHERE position > '" . db_escape($db, $start_pos) . "' ";
      $lock_sql .= "WHERE position > '" . db_escape($db, $start_pos) . "' ";
    } elseif($start_pos < $end_pos) {
      // move later, -1 from items between (including $end_pos)
      $sql .= "SET position = position - 1 ";
      $sql .= "WHERE position > '" . db_escape($db, $start_pos) . "' ";
      $sql .= "AND position <= '" . db_escape($db, $end_pos) . "' ";
      $lock_sql .= "WHERE position > '" . db_escape($db, $start_pos) . "' ";
      $lock_sql .= "AND position <= '" . db_escape($db, $end_pos) . "' ";
    } elseif($start_pos > $end_pos) {
      // move earlier, +1 to items between (including $end_pos)
      $sql .= "SET position = position + 1 ";
      $sql .= "WHERE position >= '" . db_escape($db, $end_pos) . "' ";
      $sql .= "AND position < '" . db_escape($db, $start_pos) . "' ";
      $lock_sql .= "WHERE position >= '" . db_escape($db, $end_pos) . "' ";
      $lock_sql .= "AND position < '" . db_escape($db, $start_pos) . "' ";
    }
    // Exclude the current_id in the SQL WHERE clause
    $sql .= "AND id != '" . db_escape($db, $current_id) . "' ";
    $lock_sql .= "AND id != '" . db_escape($db, $current_id) . "' ";
    $lock_sql .= "FOR UPDATE;";
    $result = execute_sql_query_with_lock($lock_sql, $sql, true, true);
    // For UPDATE statements, $result is true/false
    if($result) {
      return true;
    } else {
      // UPDATE failed
      echo mysqli_error($db);
      db_disconnect($db);
      exit;
    }
  }


  // Pages

  function find_all_pages() {
    global $db;

    $sql = "SELECT * FROM pages ";
    $sql .= "ORDER BY subject_id ASC, position ASC";
    $result = mysqli_query($db, $sql);
    confirm_result_set($result);
    return $result;
  }

  function find_page_by_id($id, $options=[]) {
    global $db;

    $visible = $options['visible'] ?? false;

    $sql = "SELECT * FROM pages ";
    $sql .= "WHERE id='" . db_escape($db, $id) . "' ";
    if($visible) {
      $sql .= "AND visible = true";
    }
    $result = mysqli_query($db, $sql);
    confirm_result_set($result);
    $page = mysqli_fetch_assoc($result);
    mysqli_free_result($result);
    return $page; // returns an assoc. array
  }

  function validate_page($page) {
    $errors = [];

    // subject_id
    if(is_blank($page['subject_id'])) {
      $errors[] = "Subject cannot be blank.";
    }

    // menu_name
    if(is_blank($page['menu_name'])) {
      $errors[] = "Name cannot be blank.";
    } elseif(!has_length($page['menu_name'], ['min' => 2, 'max' => 255])) {
      $errors[] = "Name must be between 2 and 255 characters.";
    }
    $current_id = $page['id'] ?? '0';
    if(!has_unique_page_menu_name($page['menu_name'], $current_id)) {
      $errors[] = "Menu name must be unique.";
    }


    // position
    // Make sure we are working with an integer
    $postion_int = (int) $page['position'];
    if($postion_int <= 0) {
      $errors[] = "Position must be greater than zero.";
    }
    if($postion_int > 999) {
      $errors[] = "Position must be less than 999.";
    }

    // visible
    // Make sure we are working with a string
    $visible_str = (string) $page['visible'];
    if(!has_inclusion_of($visible_str, ["0","1"])) {
      $errors[] = "Visible must be true or false.";
    }

    // content
    if(is_blank($page['content'])) {
      $errors[] = "Content cannot be blank.";
    }

    return $errors;
  }

  function insert_page($page) {
    global $db;

    $errors = validate_page($page);
    if(!empty($errors)) {
      return $errors;
    }

    shift_page_positions(0, $page['position'], $page['subject_id']);

    $sql = "INSERT INTO pages ";
    $sql .= "(subject_id, menu_name, position, visible, content) ";
    $sql .= "VALUES (";
    $sql .= "'" . db_escape($db, $page['subject_id']) . "',";
    $sql .= "'" . db_escape($db, $page['menu_name']) . "',";
    $sql .= "'" . db_escape($db, $page['position']) . "',";
    $sql .= "'" . db_escape($db, $page['visible']) . "',";
    $sql .= "'" . db_escape($db, $page['content']) . "'";
    $sql .= ")";
    $result = mysqli_query($db, $sql);
    // For INSERT statements, $result is true/false
    if($result) {
      return true;
    } else {
      // INSERT failed
      echo mysqli_error($db);
      db_disconnect($db);
      exit;
    }
  }

  function update_page($page) {
    global $db;

    $errors = validate_page($page);
    if(!empty($errors)) {
      return $errors;
    }

    $old_page = find_page_by_id($page['id']);
    $old_position = $old_page['position'];
    shift_page_positions($old_position, $page['position'], $page['subject_id'], $page['id']);

    $lock_sql = "SELECT * FROM pages ";
    $lock_sql .= "WHERE id = '" . db_escape($db, $page['id']) . "' ";
    $lock_sql .= "FOR UPDATE;";

    $sql = "UPDATE pages SET ";
    $sql .= "subject_id='" . db_escape($db, $page['subject_id']) . "', ";
    $sql .= "menu_name='" . db_escape($db, $page['menu_name']) . "', ";
    $sql .= "position='" . db_escape($db, $page['position']) . "', ";
    $sql .= "visible='" . db_escape($db, $page['visible']) . "', ";
    $sql .= "content='" . db_escape($db, $page['content']) . "' ";
    $sql .= "WHERE id='" . db_escape($db, $page['id']) . "' ";
    $sql .= "LIMIT 1";

    $result = execute_sql_query_with_lock($lock_sql, $sql, true, true);
    // For UPDATE statements, $result is true/false
    if($result) {
      return true;
    } else {
      // UPDATE failed
      echo mysqli_error($db);
      db_disconnect($db);
      exit;
    }

  }

  function delete_page($id) {
    global $db;

    $old_page = find_page_by_id($id);
    $old_position = $old_page['position'];
    shift_page_positions($old_position, 0, $old_page['subject_id'], $id);

    $lock_sql = "SELECT * FROM pages ";
    $lock_sql .= "WHERE id = '" . db_escape($db, $id) . "' ";
    $lock_sql .= "FOR UPDATE;";

    $sql = "DELETE FROM pages ";
    $sql .= "WHERE id='" . db_escape($db, $id) . "' ";
    $sql .= "LIMIT 1";

    $result = execute_sql_query_with_lock($lock_sql, $sql, true, true);

    // For DELETE statements, $result is true/false
    if($result) {
      return true;
    } else {
      // DELETE failed
      echo mysqli_error($db);
      db_disconnect($db);
      exit;
    }
  }

  function find_pages_by_subject_id($subject_id, $options=[]) {
    global $db;

    $visible = $options['visible'] ?? false;

    $sql = "SELECT * FROM pages ";
    $sql .= "WHERE subject_id='" . db_escape($db, $subject_id) . "' ";
    if($visible) {
      $sql .= "AND visible = true ";
    }
    $sql .= "ORDER BY position ASC";
    $result = mysqli_query($db, $sql);
    confirm_result_set($result);
    return $result;
  }

  function count_pages_by_subject_id($subject_id, $options=[]) {
    global $db;

    $visible = $options['visible'] ?? false;

    $sql = "SELECT COUNT(id) FROM pages ";
    $sql .= "WHERE subject_id='" . db_escape($db, $subject_id) . "' ";
    if($visible) {
      $sql .= "AND visible = true ";
    }
    $sql .= "ORDER BY position ASC";
    $result = mysqli_query($db, $sql);
    confirm_result_set($result);
    $row = mysqli_fetch_row($result);
    mysqli_free_result($result);
    $count = $row[0];
    return $count;
  }

  function shift_page_positions($start_pos, $end_pos, $subject_id, $current_id=0) {
    global $db;

    if($start_pos == $end_pos) { return; }

    $lock_sql = "SELECT * FROM pages ";
    $sql = "UPDATE pages ";
    if($start_pos == 0) {
      // new item, +1 to items greater than $end_pos
      $sql .= "SET position = position + 1 ";
      $sql .= "WHERE position >= '" . db_escape($db, $end_pos) . "' ";
      $lock_sql = "WHERE position >= '" . db_escape($db, $end_pos) . "' ";
    } elseif($end_pos == 0) {
      // delete item, -1 from items greater than $start_pos
      $sql .= "SET position = position - 1 ";
      $sql .= "WHERE position > '" . db_escape($db, $start_pos) . "' ";
      $lock_sql = "WHERE position > '" . db_escape($db, $start_pos) . "' ";
    } elseif($start_pos < $end_pos) {
      // move later, -1 from items between (including $end_pos)
      $sql .= "SET position = position - 1 ";
      $sql .= "WHERE position > '" . db_escape($db, $start_pos) . "' ";
      $sql .= "AND position <= '" . db_escape($db, $end_pos) . "' ";
      $lock_sql = "WHERE position > '" . db_escape($db, $start_pos) . "' ";
      $lock_sql .= "AND position <= '" . db_escape($db, $end_pos) . "' ";
    } elseif($start_pos > $end_pos) {
      // move earlier, +1 to items between (including $end_pos)
      $sql .= "SET position = position + 1 ";
      $sql .= "WHERE position >= '" . db_escape($db, $end_pos) . "' ";
      $sql .= "AND position < '" . db_escape($db, $start_pos) . "' ";
      $lock_sql = "WHERE position >= '" . db_escape($db, $end_pos) . "' ";
      $lock_sql .= "AND position < '" . db_escape($db, $start_pos) . "' ";
    }
    // Exclude the current_id in the SQL WHERE clause
    $sql .= "AND id != '" . db_escape($db, $current_id) . "' ";
    $sql .= "AND subject_id = '" . db_escape($db, $subject_id) . "'";
    $lock_sql .= "AND id != '" . db_escape($db, $current_id) . "' ";
    $lock_sql .= "AND subject_id = '" . db_escape($db, $subject_id) . "'";
    $lock_sql .= "FOR UPDATE;";

    $result = execute_sql_query_with_lock($lock_sql, $sql, true, true);
    // For UPDATE statements, $result is true/false
    if($result) {
      return true;
    } else {
      // UPDATE failed
      echo mysqli_error($db);
      db_disconnect($db);
      exit;
    }
  }

  // Admins

  // Find all admins, ordered last_name, first_name
  function find_all_admins() {
    global $db;

    $sql = "SELECT * FROM jxx_admins ";
    $sql .= "ORDER BY last_name ASC, first_name ASC";
    $result = mysqli_query($db, $sql);
    confirm_result_set($result);
    return $result;
  }

  function find_admin_by_id($id) {
    global $db;

    $sql = "SELECT * FROM jxx_admins ";
    $sql .= "WHERE id='" . db_escape($db, $id) . "' ";
    $sql .= "LIMIT 1";
    $result = mysqli_query($db, $sql);
    confirm_result_set($result);
    $admin = mysqli_fetch_assoc($result); // find first
    mysqli_free_result($result);
    return $admin; // returns an assoc. array
  }

  function find_customer_by_id($id) {
    global $db;

    $sql = "SELECT * FROM jxx_users ";
    $sql .= "WHERE id='" . db_escape($db, $id) . "' ";
    $sql .= "LIMIT 1";
    $result = mysqli_query($db, $sql);
    confirm_result_set($result);
    $customer = mysqli_fetch_assoc($result); // find first
    mysqli_free_result($result);
    return $customer; // returns an assoc. array
  }

  function find_customer_id_by_user_id($id) {
    global $db;

    $sql = "SELECT c_no FROM jxx_users ";
    $sql .= "WHERE id='" . db_escape($db, $id) . "' ";
    $sql .= "LIMIT 1";
    $result = mysqli_query($db, $sql);
    confirm_result_set($result);
    $customer = mysqli_fetch_assoc($result); // find first
    mysqli_free_result($result);
    return $customer; // returns an assoc. array
  }

  function find_user_by_user_id($id) {
    global $db;

    $sql = "SELECT id,username, c_email FROM jxx_customer c join jxx_users u on c.c_no=u.c_no ";
    $sql .= "WHERE u.id='" . db_escape($db, $id) . "' ";
    $sql .= "LIMIT 1";
//    echo $sql;
    $result = mysqli_query($db, $sql);
    confirm_result_set($result);
    $customer = mysqli_fetch_assoc($result); // find first
    mysqli_free_result($result);
    return $customer; // returns an assoc. array
  }

  function find_admin_by_username($username) {
    global $db;

    $sql = "SELECT * FROM jxx_admins ";
    $sql .= "WHERE username='" . db_escape($db, $username) . "' ";
    $sql .= "LIMIT 1";
    $result = mysqli_query($db, $sql);
    confirm_result_set($result);
    $admin = mysqli_fetch_assoc($result); // find first
    mysqli_free_result($result);
    return $admin; // returns an assoc. array
  }

  function find_customer_by_username($username) {
  global $db;

  $sql = "SELECT * FROM jxx_users ";
  $sql .= "WHERE username='" . db_escape($db, $username) . "' ";
  $sql .= "LIMIT 1";
//  echo $sql;
  $result = mysqli_query($db, $sql);
  confirm_result_set($result);
  $customer = mysqli_fetch_assoc($result); // find first
  mysqli_free_result($result);
  return $customer; // returns an assoc. array
}

  function validate_admin($admin, $options=[]) {

    $password_required = $options['password_required'] ?? true;

    if(is_blank($admin['first_name'])) {
      $errors[] = "First name cannot be blank.";
    } elseif (!has_length($admin['first_name'], array('min' => 2, 'max' => 255))) {
      $errors[] = "First name must be between 2 and 255 characters.";
    }

    if(is_blank($admin['last_name'])) {
      $errors[] = "Last name cannot be blank.";
    } elseif (!has_length($admin['last_name'], array('min' => 2, 'max' => 255))) {
      $errors[] = "Last name must be between 2 and 255 characters.";
    }

    if(is_blank($admin['email'])) {
      $errors[] = "Email cannot be blank.";
    } elseif (!has_length($admin['email'], array('max' => 255))) {
      $errors[] = "Last name must be less than 255 characters.";
    } elseif (!has_valid_email_format($admin['email'])) {
      $errors[] = "Email must be a valid format.";
    }

    if(is_blank($admin['username'])) {
      $errors[] = "Username cannot be blank.";
    } elseif (!has_length($admin['username'], array('min' => 8, 'max' => 255))) {
      $errors[] = "Username must be between 8 and 255 characters.";
    } elseif (!has_unique_username($admin['username'], $admin['id'] ?? 0)) {
      $errors[] = "Username not allowed. Try another.";
    }

    if($password_required) {
      if(is_blank($admin['password'])) {
        $errors[] = "Password cannot be blank.";
      } elseif (!has_length($admin['password'], array('min' => 12))) {
        $errors[] = "Password must contain 12 or more characters";
      } elseif (!preg_match('/[A-Z]/', $admin['password'])) {
        $errors[] = "Password must contain at least 1 uppercase letter";
      } elseif (!preg_match('/[a-z]/', $admin['password'])) {
        $errors[] = "Password must contain at least 1 lowercase letter";
      } elseif (!preg_match('/[0-9]/', $admin['password'])) {
        $errors[] = "Password must contain at least 1 number";
      } elseif (!preg_match('/[^A-Za-z0-9\s]/', $admin['password'])) {
        $errors[] = "Password must contain at least 1 symbol";
      }

      if(is_blank($admin['confirm_password'])) {
        $errors[] = "Confirm password cannot be blank.";
      } elseif ($admin['password'] !== $admin['confirm_password']) {
        $errors[] = "Password and confirm password must match.";
      }
    }

    return $errors;
  }

//  function insert_admin($admin) {
//    global $db;
//
//    $errors = validate_admin($admin);
//    if (!empty($errors)) {
//      return $errors;
//    }
//
//    $hashed_password = password_hash($admin['password'], PASSWORD_BCRYPT);
//
//    $sql = "INSERT INTO admins ";
//    $sql .= "(first_name, last_name, email, username, hashed_password) ";
//    $sql .= "VALUES (";
//    $sql .= "'" . db_escape($db, $admin['first_name']) . "',";
//    $sql .= "'" . db_escape($db, $admin['last_name']) . "',";
//    $sql .= "'" . db_escape($db, $admin['email']) . "',";
//    $sql .= "'" . db_escape($db, $admin['username']) . "',";
//    $sql .= "'" . db_escape($db, $hashed_password) . "'";
//    $sql .= ")";
//    $result = mysqli_query($db, $sql);
//
//    // For INSERT statements, $result is true/false
//    if($result) {
//      return true;
//    } else {
//      // INSERT failed
//      echo mysqli_error($db);
//      db_disconnect($db);
//      exit;
//    }
//  }

//  function update_admin($admin) {
//    global $db;
//
//    $password_sent = !is_blank($admin['password']);
//
//    $errors = validate_admin($admin, ['password_required' => $password_sent]);
//    if (!empty($errors)) {
//      return $errors;
//    }
//
//    $hashed_password = password_hash($admin['password'], PASSWORD_BCRYPT);
//
//    $sql = "UPDATE jxx_admins SET ";
//    $sql .= "first_name='" . db_escape($db, $admin['first_name']) . "', ";
//    $sql .= "last_name='" . db_escape($db, $admin['last_name']) . "', ";
//    $sql .= "email='" . db_escape($db, $admin['email']) . "', ";
//    if($password_sent) {
//      $sql .= "hashed_password='" . db_escape($db, $hashed_password) . "', ";
//    }
//    $sql .= "username='" . db_escape($db, $admin['username']) . "' ";
//    $sql .= "WHERE id='" . db_escape($db, $admin['id']) . "' ";
//    $sql .= "LIMIT 1";
//    $result = mysqli_query($db, $sql);
//
//    // For UPDATE statements, $result is true/false
//    if($result) {
//      return true;
//    } else {
//      // UPDATE failed
//      echo mysqli_error($db);
//      db_disconnect($db);
//      exit;
//    }
//  }

//  function delete_admin($admin) {
//    global $db;
//
//    $sql = "DELETE FROM jxx_admins ";
//    $sql .= "WHERE id='" . db_escape($db, $admin['id']) . "' ";
//    $sql .= "LIMIT 1;";
//    $result = mysqli_query($db, $sql);
//
//    // For DELETE statements, $result is true/false
//    if($result) {
//      return true;
//    } else {
//      // DELETE failed
//      echo mysqli_error($db);
//      db_disconnect($db);
//      exit;
//    }
//  }

  function find_user_info_by_customer_id($c_no) {
    global $db;

    $sql = "SELECT * FROM jxx_customer c join jxx_users u on c.c_no=u.c_no join jxx_indiv i on i.c_no=c.c_no ";
    $sql .= "WHERE c.c_no='" . db_escape($db, $c_no) . "' ";
    $sql .= "LIMIT 1";
//    echo $sql;
    $result = mysqli_query($db, $sql);
    confirm_result_set($result);
    $customer = mysqli_fetch_assoc($result); // find first
    mysqli_free_result($result);
    return $customer; // returns an assoc. array
  }



  //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  //////class
  function find_all_class() {
    global $db;

    $sql = "SELECT * FROM jxx_class order by class_name,rental_rate";
    //echo $sql;
    $result = mysqli_query($db, $sql);
    confirm_result_set($result);
    return $result;
  }

  function find_all_class_name() {
    global $db;

    $sql = "SELECT distinct class_name FROM jxx_class order by 1";
    //echo $sql;
    $result = mysqli_query($db, $sql);
    confirm_result_set($result);
    return $result;
  }

  function validate_class($class) {
    $errors = [];

    // class_name
    if(is_blank($class['class_name'])) {
      $errors[] = "Class name cannot be blank.";
    } elseif(!has_length($class['class_name'], ['min' => 1, 'max' => 30])) {
      $errors[] = "Class name must be between 1 and 30 characters.";
    }

    // rental_rate
    $rental_rate_int = (int) $class['rental_rate'];
    if(is_blank($class['rental_rate'])) {
      $errors[] = "Rental rate cannot be blank.";
    } elseif(!is_numeric($class['rental_rate'])){
      $errors[] = "Rental rate must be numeric.";
    } elseif($rental_rate_int <= 0) {
      $errors[] = "Rental rate must be greater than zero.";
    }

    // over_fee
    $over_fee_int = (int) $class['over_fee'];
    if(is_blank($class['over_fee'])) {
      $errors[] = "Overtime fee cannot be blank.";
    } elseif(!is_numeric($class['over_fee'])){
      $errors[] = "Overtime fee must be numeric.";
    } elseif($over_fee_int <= 0) {
      $errors[] = "Overtime fee must be greater than zero.";
    }

    return $errors;
  }

  function insert_class($class) {
    global $db;

    $errors = validate_class($class);
    if(!empty($errors)) {
      return $errors;
    }

    $sql = "INSERT INTO jxx_class ";
    $sql .= "(class_name, rental_rate, over_fee) ";
    $sql .= "VALUES (";
    $sql .= "'" . db_escape($db, $class['class_name']) . "',";
    $sql .= "'" . db_escape($db, $class['rental_rate']) . "',";
    $sql .= "'" . db_escape($db, $class['over_fee']) . "'";
    $sql .= ")";
    $result = mysqli_query($db, $sql);
    // For INSERT statements, $result is true/false
    if($result) {
      return true;
    } else {
      // INSERT failed
      echo mysqli_error($db);
      db_disconnect($db);
      exit;
    }
  }

  function find_class_by_id($c_id){
    global  $db;

    $sql = "select * from jxx_class where c_id='" . $c_id . "'";
    //    echo $sql;
    $result = mysqli_query($db, $sql);
    confirm_result_set($result);
    return $result;
  }

  function find_class_by_name($class){
    global  $db;

    $sql = "select * from jxx_class where class_name='" . $class['class_name'] . "'";
    //    echo $sql;
    $result = mysqli_query($db, $sql);
    confirm_result_set($result);
    return $result;
  }

  function update_class($class) {
    global $db;

    $errors = validate_class($class);
    if(!empty($errors)) {
      return $errors;
    }

    $lock_sql = "SELECT * FROM jxx_class ";
    $lock_sql .= "WHERE c_id='" . db_escape($db, $class['c_id']) . "' ";
    $lock_sql .= "FOR UPDATE";

    $sql = "UPDATE jxx_class SET ";
    $sql .= "class_name='" . db_escape($db, $class['class_name']) . "', ";
    $sql .= "rental_rate='" . db_escape($db, $class['rental_rate']) . "', ";
    $sql .= "over_fee='" . db_escape($db, $class['over_fee']) . "' ";
    $sql .= "WHERE c_id='" . db_escape($db, $class['c_id']) . "' ";
    $sql .= "LIMIT 1";

    $result = execute_sql_query_with_lock($lock_sql, $sql, true, true);
    // For UPDATE statements, $result is true/false
    if($result) {
      return true;
    } else {
      // UPDATE failed
      echo mysqli_error($db);
      db_disconnect($db);
      exit;
    }

  }

  function delete_class($c_id) {
    global $db;

    $sql = "set foreign_key_checks=0";
    mysqli_query($db, $sql);

    $lock_sql = "SELECT * FROM jxx_class ";
    $lock_sql .= "WHERE c_id='" . db_escape($db, $c_id) . "' ";
    $lock_sql .= "FOR UPDATE";

    $sql = "DELETE FROM jxx_class ";
    $sql .= "WHERE c_id='" . db_escape($db, $c_id) . "' ";
    $sql .= "LIMIT 1";

    $result = execute_sql_query_with_lock($lock_sql, $sql, true, true);

    // For DELETE statements, $result is true/false
    if($result) {
      return true;
    } else {
      // DELETE failed
      echo mysqli_error($db);
      db_disconnect($db);
      exit;
    }
  }

  /////////////////////////////// coupon
  function find_all_coupon() {
    global $db;

    $sql = "SELECT * FROM jxx_coupons order by cou_discount";
    //echo $sql;
    $result = mysqli_query($db, $sql);
    confirm_result_set($result);
    return $result;
  }

  function validate_coupon($coupon) {
    $errors = [];

    // cou_discount
    $cou_discount_int = (int) $coupon['cou_discount'];
    if(is_blank($coupon['cou_discount'])) {
      $errors[] = "Coupon discount rate cannot be blank.";
    } elseif(!is_numeric($coupon['cou_discount'])){
      $errors[] = "Coupon discount rate must be numeric.";
    } elseif($cou_discount_int <= 0) {
      $errors[] = "Coupon discount rate must be greater than zero.";
    }

    // start date & end date
    date_default_timezone_set("America/New_York");
    if(is_blank($coupon['s_date'])) {
      $errors[] = "Coupon start date cannot be blank.";
    } elseif(floor(strtotime($coupon['e_date'])-strtotime($coupon['s_date']))/86400 <= 1){
      $errors[] = "The end date must be at least one day later than the start date.";
    }

//    if(!is_bool($coupon['expired'])){
//      $errors[] = "Coupon must be tagged expired or not.";
//    }

    return $errors;
  }

  function generate_cou_no($length){
    $str = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    $len = strlen($str)-1;
    $randstr = '';
    for ($i=0;$i<$length;$i++) {
      $num=mt_rand(0,$len);
      $randstr .= $str[$num];
    }
    return $randstr;
  }

  function insert_coupon($coupon) {
    global $db;
    $length=7;
    $errors = validate_coupon($coupon);
    if(!empty($errors)) {
      return $errors;
    }

    while (true){
        $cou_no = generate_cou_no($length);
        $sql = "select * from jxx_coupons where cou_no='" . db_escape($db, $cou_no) . "'";
        $result = mysqli_query($db, $sql);
        if (mysqli_num_rows($result)==0){
            break;
        }
    }

    $sql = "INSERT INTO jxx_coupons ";
    $sql .= "(cou_discount, s_date, e_date, is_available, cou_no) ";
    $sql .= "VALUES (";
    $sql .= "'" . db_escape($db, $coupon['cou_discount']) . "',";
    $sql .= "'" . db_escape($db, $coupon['s_date']) . "',";
    $sql .= "'" . db_escape($db, $coupon['e_date']) . "',1,";
    $sql .= "'" . db_escape($db,$cou_no) . "'";
    $sql .= ")";
    $result = mysqli_query($db, $sql);
    // For INSERT statements, $result is true/false
    if($result) {
      return true;
    } else {
      // INSERT failed
      echo mysqli_error($db);
      db_disconnect($db);
      exit;
    }
  }

  function find_coupon_by_id($cou_id){
    global  $db;

    $sql = "select * from jxx_coupons where cou_id='" . db_escape($db,$cou_id) . "'";
//        echo $sql;
    $result = mysqli_query($db, $sql);
    confirm_result_set($result);
    $coupon = mysqli_fetch_assoc($result); // find first
    mysqli_free_result($result);
    return $coupon; // returns an assoc. array

  }

  function find_coupon_id_by_no($cou_no){
    global  $db;

    $sql = "select * from jxx_coupons where cou_no='" . db_escape($db,$cou_no) . "'";
  //        echo $sql;
    $result = mysqli_query($db, $sql);
    confirm_result_set($result);
    $coupon = mysqli_fetch_assoc($result); // find first
    mysqli_free_result($result);
    return $coupon; // returns an assoc. array
  }

  function find_coupon_by_factors($coupon){
    global  $db;

    $errors = [];
    $sql = "select * from jxx_coupons where ";
    if(!is_blank($coupon['cou_discount'])){
      $sql .= "cou_discount='" . db_escape($db,$coupon['cou_discount']) . "' ";
      if(!is_blank($coupon['s_date'])) {
        $sql .= "and s_date='" . db_escape($db,$coupon['s_date']) . "' ";
        if (!is_blank($coupon['e_date'])) {
          $sql .= "and e_date='" . db_escape($db,$coupon['e_date']) . "' ";
          if (!is_blank($coupon['cou_no'])) {
            $sql .= "and cou_no='" . db_escape($db,$coupon['cou_no']) . "' ";
            if (!is_null($coupon['is_available'])) {
              if (!$coupon['is_available']) {
                $sql .= "and is_available='0' ";
              } else {
                $sql .= "and is_available='1' ";
              }
            }
          }
        }
      }
    }elseif(!is_blank($coupon['s_date'])) {
      $sql .= "s_date='" . db_escape($db,$coupon['s_date']) . "' ";
      if (!is_blank($coupon['e_date'])) {
        $sql .= "and e_date='" . db_escape($db,$coupon['e_date']) . "' ";
        if (!is_blank($coupon['cou_no'])) {
          $sql .= "and cou_no='" . db_escape($db,$coupon['cou_no']) . "' ";
          if (!is_null($coupon['is_available'])) {
            if (!$coupon['is_available']) {
              $sql .= "and is_available='0' ";
            } else {
              $sql .= "and is_available='1' ";
            }
          }
        }
      }
    }elseif(!is_blank($coupon['e_date'])) {
      $sql .= "e_date='" . db_escape($db,$coupon['e_date']) . "' ";
      if (!is_blank($coupon['cou_no'])) {
        $sql .= "and cou_no='" . db_escape($db,$coupon['cou_no']) . "' ";
        if (!is_null($coupon['is_available'])) {
          if (!$coupon['is_available']) {
            $sql .= "and is_available='0' ";
          } else {
            $sql .= "and is_available='1' ";
          }
        }
      }
    }elseif(!is_blank($coupon['cou_no'])) {
      $sql .= "cou_no='" . db_escape($db,$coupon['cou_no']) . "' ";
      if (!is_null($coupon['is_available'])) {
        if (!$coupon['is_available']) {
          $sql .= "and is_available='0' ";
        } else {
          $sql .= "and is_available='1' ";
        }
      }
    }elseif(!is_null($coupon['is_available'])) {
      if (!$coupon['is_available']) {
        $sql .= "is_available='0' ";
      } else {
        $sql .= "is_available='1' ";
      }
    }else{
//        echo $coupon['is_available'];
        $errors[] = "Please input at least one factor to search.";
      }

//    echo $sql;
    $result = mysqli_query($db, $sql);
    confirm_result_set($result);
    return array($result, $errors);
  }

  function update_coupon($coupon) {
    global $db;

    $errors = validate_coupon($coupon);
    if(!empty($errors)) {
      return $errors;
    }

    while (true){
      $cou_no = generate_cou_no(7);
      $sql = "select * from jxx_coupons where cou_no='" . db_escape($db, $cou_no) . "'";
      $result = mysqli_query($db, $sql);
      if (mysqli_num_rows($result)==0){
          break;
      }
    }

    $lock_sql = "SELECT * FROM jxx_coupons ";
    $lock_sql .= "WHERE cou_id='" . db_escape($db, $coupon['cou_id']) . "' ";
    $lock_sql .= "FOR UPDATE";

    $sql = "UPDATE jxx_coupons SET ";
    $sql .= "cou_discount='" . db_escape($db, $coupon['cou_discount']) . "', ";
    $sql .= "s_date='" . db_escape($db, $coupon['s_date']) . "', ";
    $sql .= "e_date='" . db_escape($db, $coupon['e_date']) . "', ";
    $sql .= "is_available='" . db_escape($db, $coupon['is_available']) . "' ";
//    $sql .= "cou_no='" . db_escape($db, $cou_no) . "' ";
    $sql .= "WHERE cou_id='" . db_escape($db, $coupon['cou_id']) . "' ";
    $sql .= "LIMIT 1";

    $result = execute_sql_query_with_lock($lock_sql, $sql, true, true);
    // For UPDATE statements, $result is true/false
    if($result) {
      return true;
    } else {
      // UPDATE failed
      echo mysqli_error($db);
      db_disconnect($db);
      exit;
    }

  }

  function delete_coupon($cou_id) {
    global $db;

//    $sql = "set foreign_key_checks=0";
//    mysqli_query($db, $sql);

    $lock_sql = "SELECT * FROM jxx_coupons ";
    $lock_sql .= "WHERE cou_id='" . db_escape($db, $cou_id) . "' ";
    $lock_sql .= "FOR UPDATE";

    $sql = "DELETE FROM jxx_coupons ";
    $sql .= "WHERE cou_id='" . db_escape($db, $cou_id) . "' ";
    $sql .= "LIMIT 1";

    $result = execute_sql_query_with_lock($lock_sql, $sql, true, true);

//    $sql = "set foreign_key_checks=1";
//    mysqli_query($db, $sql);

    // For DELETE statements, $result is true/false
    if($result) {
      return true;
    } else {
      // DELETE failed
      echo mysqli_error($db);
      db_disconnect($db);
      exit;
    }
  }

  ////////////////////////////////location
  function find_all_location() {
    global $db;

    $sql = "SELECT * FROM jxx_loc order by l_state,l_city,l_street";
    //echo $sql;
    $result = mysqli_query($db, $sql);
    confirm_result_set($result);
    return $result;
  }

  function find_all_states() {
    global $db;

    $sql = "SELECT distinct l_state FROM jxx_loc order by 1";
    //echo $sql;
    $result = mysqli_query($db, $sql);
    confirm_result_set($result);
    return $result;
  }

  function find_all_streets() {
    global $db;

    $sql = "SELECT distinct l_street FROM jxx_loc order by 1";
    //echo $sql;
    $result = mysqli_query($db, $sql);
    confirm_result_set($result);
    return $result;
  }

  function validate_location($location) {
    $errors = [];

    // l_street
    if(is_blank($location['l_street'])) {
      $errors[] = "Street cannot be blank.";
    } elseif(!has_length($location['l_street'], ['min' => 1, 'max' => 30])) {
      $errors[] = "Street must be between 1 and 30 characters.";
    }

    // l_city
    if(is_blank($location['l_city'])) {
      $errors[] = "City cannot be blank.";
    } elseif(!has_length($location['l_city'], ['min' => 1, 'max' => 30])) {
      $errors[] = "City must be between 1 and 30 characters.";
    }

    // l_state
    if(is_blank($location['l_state'])) {
      $errors[] = "State cannot be blank.";
    } elseif(!has_length($location['l_state'], ['min' => 1, 'max' => 30])) {
      $errors[] = "State must be between 1 and 30 characters.";
    }

    // l_zipcode
    if(is_blank($location['l_zipcode'])) {
      $errors[] = "Zipcode cannot be blank.";
    } elseif(!has_length_exactly($location['l_zipcode'], 5)) {
      $errors[] = "Zipcode must be 5 digits.";
    }

    // l_pno
    if(!is_numeric(($location['l_pno']))){
      $errors[] = "Phone number contains only digits.";
    } elseif(!is_blank($location['l_pno']) and !has_length_exactly($location['l_pno'], 10)) {
      $errors[] = "Phone number must be 11 digits.";
    }

    return $errors;
  }

  function insert_location($location) {
    global $db;

    $errors = validate_location($location);
    if(!empty($errors)) {
      return $errors;
    }

    $sql = "INSERT INTO jxx_loc ";
    $sql .= "(l_street, l_city, l_state, l_zipcode, l_pno) ";
    $sql .= "VALUES (";
    $sql .= "'" . db_escape($db, $location['l_street']) . "',";
    $sql .= "'" . db_escape($db, $location['l_city']) . "',";
    $sql .= "'" . db_escape($db, $location['l_state']) . "',";
    $sql .= "'" . db_escape($db, $location['l_zipcode']) . "',";
    $sql .= "'" . db_escape($db, $location['l_pno']) . "'";
    $sql .= ")";
    $result = mysqli_query($db, $sql);
    // For INSERT statements, $result is true/false
    if($result) {
      return true;
    } else {
      // INSERT failed
      echo mysqli_error($db);
      db_disconnect($db);
      exit;
    }
  }

  function find_location_by_id($l_id){
    global  $db;

    $sql = "select * from jxx_loc where l_id='" . $l_id . "'";
    //    echo $sql;
    $result = mysqli_query($db, $sql);
    confirm_result_set($result);
    return $result;
  }

  function find_location_by_factors($location){
    global  $db;

    $errors = [];
    $sql = "select * from jxx_loc where ";
    if(!is_blank($location['l_state'])){
      $sql .= "l_state='" . db_escape($db,$location['l_state']) . "' ";
      if(!is_blank($location['l_city'])){
        $sql .= "and l_city='" . db_escape($db,$location['l_city']) . "' ";
        if(!is_blank($location['l_street'])){
          $sql .= "and l_street='" . db_escape($db,$location['l_street']) . "' ";
          if(!is_blank($location['l_zipcode'])){
            $sql .= "and l_zipcode='" . db_escape($db,$location['l_zipcode']) . "' ";
            if(!is_blank($location['l_pno'])){
              $sql .= "and l_pno='" . db_escape($db,$location['l_pno']) . "'";
            }
          }
        }
      }
    }elseif(!is_blank($location['l_city'])){
      $sql .= "l_city='" . db_escape($db,$location['l_city']) . "' ";
      if(!is_blank($location['l_street'])){
        $sql .= "and l_street='" . db_escape($db,$location['l_street']) . "'";
        if(!is_blank($location['l_zipcode'])){
          $sql .= "and l_zipcode='" . db_escape($db,$location['l_zipcode']) . "' ";
          if(!is_blank($location['l_pno'])){
            $sql .= "and l_pno='" . db_escape($db,$location['l_pno']) . "'";
          }
        }
      }
    }elseif(!is_blank($location['l_street'])){
      $sql .= "l_street='" . db_escape($db,$location['l_street']) . "'";
      if(!is_blank($location['l_zipcode'])){
        $sql .= "and l_zipcode='" . db_escape($db,$location['l_zipcode']) . "' ";
        if(!is_blank($location['l_pno'])){
          $sql .= "and l_pno='" . db_escape($db,$location['l_pno']) . "'";
        }
      }
    }elseif(!is_blank($location['l_zipcode'])){
      $sql .= "l_zipcode='" . db_escape($db,$location['l_zipcode']) . "' ";
      if(!is_blank($location['l_pno'])){
        $sql .= "and l_pno='" . db_escape($db,$location['l_pno']) . "'";
      }
    }elseif(!is_blank($location['l_pno'])){
      $sql .= "l_pno='" . db_escape($db,$location['l_pno']) . "'";
    }
    else{
      $errors[] = "Please input at least one factor to search.";
    }
    //    echo $sql;
    $result = mysqli_query($db, $sql);
    confirm_result_set($result);
    return array($result, $errors);
  }

  function update_location($location) {
    global $db;

    $errors = validate_location($location);
    if(!empty($errors)) {
      return $errors;
    }

    $lock_sql = "SELECT * FROM jxx_loc ";
    $lock_sql .= "WHERE l_id='" . db_escape($db, $location['l_id']) . "' ";
    $lock_sql .= "FOR UPDATE";

    $sql = "UPDATE jxx_loc SET ";
    $sql .= "l_street='" . db_escape($db, $location['l_street']) . "', ";
    $sql .= "l_city='" . db_escape($db, $location['l_city']) . "', ";
    $sql .= "l_state='" . db_escape($db, $location['l_state']) . "', ";
    $sql .= "l_zipcode='" . db_escape($db, $location['l_zipcode']) . "', ";
    $sql .= "l_pno='" . db_escape($db, $location['l_pno']) . "' ";
    $sql .= "WHERE l_id='" . db_escape($db, $location['l_id']) . "' ";
    $sql .= "LIMIT 1";

    $result = execute_sql_query_with_lock($lock_sql, $sql, true, true);
    // For UPDATE statements, $result is true/false
    if($result) {
      return true;
    } else {
      // UPDATE failed
      echo mysqli_error($db);
      db_disconnect($db);
      exit;
    }

  }

  function delete_location($l_id) {
    global $db;

    $lock_sql = "SELECT * FROM jxx_loc ";
    $lock_sql .= "WHERE l_id='" . db_escape($db, $l_id) . "' ";
    $lock_sql .= "FOR UPDATE";

    $sql = "DELETE FROM jxx_loc ";
    $sql .= "WHERE l_id='" . db_escape($db, $l_id) . "' ";
    $sql .= "LIMIT 1";

    $result = execute_sql_query_with_lock($lock_sql, $sql, true, true);

    // For DELETE statements, $result is true/false
    if($result) {
      return true;
    } else {
      // DELETE failed
      echo mysqli_error($db);
      db_disconnect($db);
      exit;
    }
  }

  ////////////////////////////////vehicle
  function find_all_vehicle() {
    global $db;

    $sql = "SELECT * FROM jxx_vehicle v join jxx_loc l on v.l_id=l.l_id ";
    $sql .= "join jxx_class c on v.c_id=c.c_id order by class_name,make";
    //echo $sql;
    $result = mysqli_query($db, $sql);
    confirm_result_set($result);
    return $result;
  }

  function find_vehicle_by_id($vid) {
    global $db;

    $sql = "SELECT * FROM jxx_vehicle v join jxx_loc l on v.l_id=l.l_id ";
    $sql .= "join jxx_class c on v.c_id=c.c_id where vid='" . db_escape($db,$vid) . "'order by class_name,make";
    //echo $sql;
    $result = mysqli_query($db, $sql);
    confirm_result_set($result);
    return $result;
  }

  function find_vehicle_by_factors($vehicle){
    //params: class_name, make, vin, l_street, l_city, l_state, l_zipcode
    global  $db;

    $errors = [];

    $sql = "
              select
                *
              from
                jxx_vehicle v join jxx_class c on c.c_id=v.c_id join jxx_loc l on l.l_id=v.l_id
              where
                v.is_available=1
      ";

    if (!is_blank($vehicle['class_name'])) {
      $sql .= "and c.class_name='" . db_escape($db,$vehicle['class_name']) . "' ";
    }
    if (!is_blank($vehicle['make'])) {
      $sql .= "and v.make='" . db_escape($db,$vehicle['make']) . "' ";
    }
    if (!is_blank($vehicle['vin'])) {
      $sql .= "and v.vin='" . db_escape($db,$vehicle['vin']) . "' ";
    }
    if (!is_blank($vehicle['l_street'])) {
      $sql .= "and l.l_street='" . db_escape($db,$vehicle['l_street']) . "' ";
    }
    if (!is_blank($vehicle['l_city'])) {
      $sql .= "and l.l_city='" . db_escape($db,$vehicle['l_city']) . "' ";
    }
    if (!is_blank($vehicle['l_state'])) {
      $sql .= "and l.l_state='" . db_escape($db,$vehicle['l_state']) . "' ";
    }
    if (!is_blank($vehicle['l_zipcode'])) {
      $sql .= "and l.l_zipcode='" . db_escape($db,$vehicle['l_zipcode']) . "' ";
    }
    //echo $sql;
    $result = mysqli_query($db, $sql);
    confirm_result_set($result);
    return array($result, $errors);
  }

  function validate_vehicle($vehicle) {
    $errors = [];

    // make
    if(is_blank($vehicle['make'])) {
      $errors[] = "Vehicle Brand cannot be blank.";
    } elseif(!has_length($vehicle['make'], ['min' => 1, 'max' => 30])) {
      $errors[] = "Vehicle Brand must be between 1 and 30 characters.";
    }

    // model
    if(is_blank($vehicle['model'])) {
      $errors[] = "Model number cannot be blank.";
    } elseif(!has_length($vehicle['model'], ['min' => 1, 'max' => 30])) {
      $errors[] = "Model number must be between 1 and 30 characters.";
    }

    // year
    date_default_timezone_set("America/New_York");
    if(is_blank($vehicle['year'])) {
      $errors[] = "Made year cannot be blank.";
    } elseif(strtotime(date("y-m-d h:i:s"))<=strtotime($vehicle['year'])) {
      $errors[] = "Made year must be before current date.";
    }

    // vin
    if(is_blank($vehicle['vin'])) {
      $errors[] = "VIN cannot be blank.";
    } elseif(!has_length($vehicle['vin'], ['min' => 1, 'max' => 30])) {
      $errors[] = "VIN must be between 1 and 30 characters.";
    }

    // lic_p_no
    if(is_blank($vehicle['lic_p_no'])) {
      $errors[] = "License plate number cannot be blank.";
    } elseif(!has_length($vehicle['lic_p_no'], ['min' => 1, 'max' => 30])) {
      $errors[] = "License plate number must be between 1 and 30 characters.";
    }

    // l_id
    $l_id_int = (int) $vehicle['l_id'];
    if(is_blank($vehicle['l_id'])) {
      $errors[] = "Location ID cannot be blank.";
    } elseif($l_id_int<= 0) {
      $errors[] = "Location ID cannot be negative.";
    }
    elseif($l_id_int > 999) {
      $errors[] = "Location ID must be less than 999.";
    }

    // c_id
    $c_id_int = (int) $vehicle['c_id'];
    if(is_blank($vehicle['c_id'])) {
      $errors[] = "Class ID cannot be blank.";
    } elseif($c_id_int<= 0) {
      $errors[] = "Location ID cannot be negative.";
    }
    elseif($c_id_int > 999) {
      $errors[] = "Class ID must be less than 999.";
    }

    return $errors;
  }

  function update_vehicle($vehicle) {
    global $db;

    $errors = validate_vehicle($vehicle);
    if(!empty($errors)) {
      return $errors;
    }

    $lock_sql = "SELECT * FROM jxx_vehicle ";
    $lock_sql .= "WHERE vid='" . db_escape($db,$vehicle['vid']) . "' ";
    $lock_sql .= "FOR UPDATE";

    $sql = "UPDATE jxx_vehicle SET ";
    $sql .= "make='" . db_escape($db, $vehicle['make']) . "', ";
    $sql .= "model='" . db_escape($db, $vehicle['model']) . "', ";
    $sql .= "year='" . db_escape($db, $vehicle['year']) . "', ";
    $sql .= "vin='" . db_escape($db, $vehicle['vin']) . "', ";
    $sql .= "lic_p_no='" . db_escape($db, $vehicle['lic_p_no']) . "', ";
    $sql .= "l_id='" . db_escape($db, $vehicle['l_id']) . "', ";
    $sql .= "c_id='" . db_escape($db, $vehicle['c_id']) . "' ";
    $sql .= "WHERE vid='" . db_escape($db, $vehicle['vid']) . "' ";
    $sql .= "LIMIT 1";

    $result = execute_sql_query_with_lock($lock_sql, $sql, true, true);
    // For UPDATE statements, $result is true/false
    if($result) {
      return true;
    } else {
      // UPDATE failed
      echo mysqli_error($db);
      db_disconnect($db);
      exit;
    }
  }

  function insert_vehicle($vehicle) {
    global $db;

    $errors = validate_vehicle($vehicle);
    if(!empty($errors)) {
      return $errors;
    }

    $sql = "INSERT INTO jxx_vehicle ";
    $sql .= "(make,model,year,vin,lic_p_no,l_id,c_id) ";
    $sql .= "VALUES (";
    $sql .= "'" . db_escape($db, $vehicle['make']) . "',";
    $sql .= "'" . db_escape($db, $vehicle['model']) . "',";
    $sql .= "'" . db_escape($db, $vehicle['year']) . "',";
    $sql .= "'" . db_escape($db, $vehicle['vin']) . "',";
    $sql .= "'" . db_escape($db, $vehicle['lic_p_no']) . "',";
    $sql .= "'" . db_escape($db, $vehicle['l_id']) . "',";
    $sql .= "'" . db_escape($db, $vehicle['c_id']) . "'";
    $sql .= ")";
    $result = mysqli_query($db, $sql);
    // For INSERT statements, $result is true/false
    if($result) {
      return true;
    } else {
      // INSERT failed
      echo mysqli_error($db);
      db_disconnect($db);
      exit;
    }
  }

  function delete_vehicle($vid) {
    global $db;

    //    $sql = "set foreign_key_checks=0";
    //    mysqli_query($db, $sql);

    $lock_sql = "SELECT * FROM jxx_vehicle ";
    $lock_sql .= "WHERE vid='" . db_escape($db, $vid) . "' ";
    $lock_sql .= "FOR UPDATE";

    $sql = "DELETE FROM jxx_vehicle ";
    $sql .= "WHERE vid='" . db_escape($db, $vid) . "' ";
    $sql .= "LIMIT 1";

    $result = execute_sql_query_with_lock($lock_sql, $sql, true, true);

    //    $sql = "set foreign_key_checks=1";
    //    mysqli_query($db, $sql);

    // For DELETE statements, $result is true/false
    if($result) {
      return true;
    } else {
      // DELETE failed
      echo mysqli_error($db);
      db_disconnect($db);
      exit;
    }
  }


/////////////////////////////////coop
  function find_all_coop() {
    global $db;

    $sql = "SELECT * FROM jxx_coop order by c_name";
    //echo $sql;
    $result = mysqli_query($db, $sql);
    confirm_result_set($result);
    return $result;
  }

  function find_all_coop_name() {
    global $db;

    $sql = "SELECT distinct c_name FROM jxx_coop order by c_name";
    //echo $sql;
    $result = mysqli_query($db, $sql);
    confirm_result_set($result);
    return $result;
  }

  function validate_coop($coop) {
    $errors = [];

    // c_name
    if(is_blank($coop['c_name'])) {
      $errors[] = "Corporation name cannot be blank.";
    } elseif(!has_length($coop['c_name'], ['min' => 1, 'max' => 30])) {
      $errors[] = "Corporation name must be between 1 and 30 characters.";
    }

    // reg_no
    if(is_blank($coop['reg_no'])) {
      $errors[] = "Registration number cannot be blank.";
    } elseif(!has_length($coop['reg_no'], ['min' => 1, 'max' => 30])) {
      $errors[] = "Registration number must be between 1 and 30 characters.";
    }

    // c_rate
    $c_rate_int = (int) $coop['c_rate'];
    if(is_blank($coop['c_rate'])) {
      $errors[] = "Discount rate cannot be blank.";
    } elseif(!is_numeric($coop['c_rate'])){
      $errors[] = "Discount rate must be numeric.";
    } elseif($c_rate_int <= 0) {
      $errors[] = "Discount rate must be greater than zero.";
    }

    return $errors;
  }

  function insert_coop($coop) {
    global $db;

    $errors = validate_coop($coop);
    if(!empty($errors)) {
      return $errors;
    }

    $sql = "INSERT INTO jxx_coop ";
    $sql .= "(c_name, reg_no, c_rate) ";
    $sql .= "VALUES (";
    $sql .= "'" . db_escape($db, $coop['c_name']) . "',";
    $sql .= "'" . db_escape($db, $coop['reg_no']) . "',";
    $sql .= "'" . db_escape($db, $coop['c_rate']) . "'";
    $sql .= ")";
    $result = mysqli_query($db, $sql);
    // For INSERT statements, $result is true/false
    if($result) {
      return true;
    } else {
      // INSERT failed
      echo mysqli_error($db);
      db_disconnect($db);
      exit;
    }
  }

  function find_coop_by_id($coop_id){
    global  $db;

    $sql = "select * from jxx_coop where coop_id='" . $coop_id . "'";
    //    echo $sql;
    $result = mysqli_query($db, $sql);
    confirm_result_set($result);
    return $result;
  }

  function find_coop_id_by_name($c_name){
    global  $db;

    $sql = "select coop_id from jxx_coop where c_name='" . $c_name . "'";
    //    echo $sql;
    $result = mysqli_query($db, $sql);
    confirm_result_set($result);
    return $result;
  }

  function find_coop_by_factors($coop){
    global  $db;

    $errors = [];
    $sql = "select * from jxx_coop where ";
    if(!is_blank($coop['c_name'])){
      $sql .= "c_name='" . db_escape($db,$coop['c_name']) . "' ";
      if(!is_blank($coop['reg_no'])){
        $sql .= "and reg_no='" . db_escape($db,$coop['reg_no']) . "' ";
        if(!is_blank($coop['c_rate'])){
          $sql .= "and c_rate='" . db_escape($db,$coop['c_rate']) . "'";
        }
      }
    }elseif(!is_blank($coop['reg_no'])){
      $sql .= "reg_no='" . db_escape($db,$coop['reg_no']) . "' ";
      if(!is_blank($coop['c_rate'])){
        $sql .= "and c_rate='" . db_escape($db,$coop['c_rate']) . "'";
      }
    }elseif(!is_blank($coop['c_rate'])){
      $sql .= "c_rate='" . db_escape($db,$coop['c_rate']) . "'";
    }else{
      $errors[] = "Please input at least one factor to search.";
    }
  //    echo $sql;
    $result = mysqli_query($db, $sql);
    confirm_result_set($result);
    return array($result, $errors);
  }

  function update_coop($coop) {
    global $db;

    $errors = validate_coop($coop);
    if(!empty($errors)) {
      return $errors;
    }

    $lock_sql = "SELECT * FROM jxx_coop ";
    $lock_sql .= "WHERE coop_id='" . db_escape($db, $coop['coop_id']) . "' ";
    $lock_sql .= "FOR UPDATE";

    $sql = "UPDATE jxx_coop SET ";
    $sql .= "c_name='" . db_escape($db, $coop['c_name']) . "', ";
    $sql .= "reg_no='" . db_escape($db, $coop['reg_no']) . "', ";
    $sql .= "c_rate='" . db_escape($db, $coop['c_rate']) . "' ";
    $sql .= "WHERE coop_id='" . db_escape($db, $coop['coop_id']) . "' ";
    $sql .= "LIMIT 1";

    $result = execute_sql_query_with_lock($lock_sql, $sql, true, true);
    // For UPDATE statements, $result is true/false
    if($result) {
      return true;
    } else {
      // UPDATE failed
      echo mysqli_error($db);
      db_disconnect($db);
      exit;
    }
  }

  function delete_coop($coop_id) {
    global $db;

  //    $sql = "set foreign_key_checks=0";
  //    mysqli_query($db, $sql);

    $lock_sql = "SELECT * FROM jxx_coop ";
    $lock_sql .= "WHERE coop_id='" . db_escape($db, $coop_id) . "' ";
    $lock_sql .= "FOR UPDATE";

    $sql = "DELETE FROM jxx_coop ";
    $sql .= "WHERE coop_id='" . db_escape($db, $coop_id) . "' ";
    $sql .= "LIMIT 1";

    $result = execute_sql_query_with_lock($lock_sql, $sql, true, true);

  //    $sql = "set foreign_key_checks=1";
  //    mysqli_query($db, $sql);

    // For DELETE statements, $result is true/false
    if($result) {
      return true;
    } else {
      // DELETE failed
      echo mysqli_error($db);
      db_disconnect($db);
      exit;
    }
  }

  /////////////////////////////////indiv
  function find_all_indiv() {
    global $db;

    $sql = "SELECT * FROM jxx_indiv order by i_fname,i_lname";
    //echo $sql;
    $result = mysqli_query($db, $sql);
    confirm_result_set($result);
    return $result;
  }

  function find_indiv_by_id($c_no){
    global  $db;

    $sql = "select * from jxx_customer c join jxx_indiv i on c.c_no=i.c_no where i.c_no='" . db_escape($db,$c_no) . "'";
    //    echo $sql;
    $result = mysqli_query($db, $sql);
    confirm_result_set($result);
    return $result;
  }

  function find_indiv_by_factors($indiv){
    global  $db;

    $errors = [];
    $sql = "select * from jxx_indiv i join jxx_customer c on c.c_no=i.c_no where ";
    if(!is_blank($indiv['i_fname']) and !is_blank($indiv['i_lname'])){
      $sql .= "i_fname='" . $indiv['i_fname'] . "' and i_lname='" . db_escape($db,$indiv['i_lname']) . "' ";
      if(!is_blank($indiv['dl_no'])){
        $sql .= "and dl_no='" . db_escape($db,$indiv['dl_no']) . "' ";
        if(!is_blank($indiv['c_email'])){
          $sql .= "and c_email='" . db_escape($db,$indiv['c_email']) . "' ";
          if(!is_blank($indiv['p_no'])){
            $sql .= "and p_no='" . db_escape($db,$indiv['p_no']) . "'";
          }
        }
      }
    }elseif(!is_blank($indiv['dl_no'])){
      $sql .= "dl_no='" . db_escape($db,$indiv['dl_no']) . "' ";
      if(!is_blank($indiv['c_email'])){
        $sql .= "and c_email='" . db_escape($db,$indiv['c_email']) . "' ";
        if(!is_blank($indiv['p_no'])) {
          $sql .= "and p_no='" . db_escape($db,$indiv['p_no']) . "'";
        }
      }
    }elseif(!is_blank($indiv['c_email'])){
      $sql .= "c_email='" . db_escape($db,$indiv['c_email']) . "' ";
      if(!is_blank($indiv['p_no'])) {
        $sql .= "and p_no='" . db_escape($db,$indiv['p_no']) . "'";
      }
    }elseif(!is_blank($indiv['p_no'])) {
      $sql .= "p_no='" . db_escape($db,$indiv['p_no']) . "'";
    }else{
      $errors[] = "Please input at least one factor to search.";
    }
    //    echo $sql;
    $result = mysqli_query($db, $sql);
    confirm_result_set($result);
    return array($result, $errors);
  }

  function validate_indiv($indiv) {
    $errors = [];

    // i_fname
    if(is_blank($indiv['i_fname'])) {
      $errors[] = "Firstname cannot be blank.";
    } elseif(!has_length($indiv['i_fname'], ['min' => 1, 'max' => 30])) {
      $errors[] = "Firstname must be between 1 and 30 characters.";
    }

    // i_lname
    if(is_blank($indiv['i_lname'])) {
      $errors[] = "Lastname cannot be blank.";
    } elseif(!has_length($indiv['i_lname'], ['min' => 1, 'max' => 30])) {
      $errors[] = "Lastname must be between 1 and 30 characters.";
    }

    // dl_no
    if(is_blank($indiv['dl_no'])) {
      $errors[] = "Driver license number cannot be blank.";
    } elseif(!has_length($indiv['dl_no'], ['min' => 1, 'max' => 30])) {
      $errors[] = "Driver license number must be between 1 and 30 characters.";
    }

    // ins_c_name
    if(is_blank($indiv['ins_c_name'])) {
      $errors[] = "Insurance Company Name cannot be blank.";
    } elseif(!has_length($indiv['ins_c_name'], ['min' => 1, 'max' => 30])) {
      $errors[] = "Insurance Company Name must be between 1 and 30 characters.";
    }

    // ins_p_no
    if(is_blank($indiv['ins_p_no'])) {
      $errors[] = "Insurance policy number cannot be blank.";
    } elseif(!has_length($indiv['ins_p_no'], ['min' => 1, 'max' => 30])) {
      $errors[] = "Insurance policy number must be between 1 and 30 characters.";
    }

    return $errors;
  }

  function insert_indiv($indiv) {
    global $db;

    $errors = validate_indiv($indiv);
    if(!empty($errors)) {
      return $errors;
    }

    $sql = "INSERT INTO jxx_indiv ";
    $sql .= "(c_no,i_fname,i_lname,dl_no,ins_c_name,ins_p_no) ";
    $sql .= "VALUES (";
    $sql .= "'" . db_escape($db, $indiv['c_no']) . "',";
    $sql .= "'" . db_escape($db, $indiv['i_fname']) . "',";
    $sql .= "'" . db_escape($db, $indiv['i_lname']) . "',";
    $sql .= "'" . db_escape($db, $indiv['dl_no']) . "',";
    $sql .= "'" . db_escape($db, $indiv['ins_c_name']) . "',";
    $sql .= "'" . db_escape($db, $indiv['ins_p_no']) . "'";
    $sql .= ")";
//    echo $sql;
    $result = mysqli_query($db, $sql);
    // For INSERT statements, $result is true/false
    if($result) {
      return true;
    } else {
      // INSERT failed
      echo mysqli_error($db);
      db_disconnect($db);
      exit;
    }
  }

  function update_indiv($indiv) {
    global $db;

    $errors = validate_indiv($indiv);
    if(!empty($errors)) {
      return $errors;
    }

    $lock_sql = "SELECT * FROM jxx_indiv ";
    $lock_sql .= "WHERE c_no='" . db_escape($db, $indiv['c_no']) . "' ";
    $lock_sql .= "for update;";

    $sql = "UPDATE jxx_indiv SET ";
    $sql .= "i_fname='" . db_escape($db, $indiv['i_fname']) . "', ";
    $sql .= "i_lname='" . db_escape($db, $indiv['i_lname']) . "', ";
    $sql .= "dl_no='" . db_escape($db, $indiv['dl_no']) . "', ";
    $sql .= "ins_c_name='" . db_escape($db, $indiv['ins_c_name']) . "', ";
    $sql .= "ins_p_no='" . db_escape($db, $indiv['ins_p_no']) . "' ";
    $sql .= "WHERE c_no='" . db_escape($db, $indiv['c_no']) . "' ";
    $sql .= "LIMIT 1";
//    echo $sql;
    $result = execute_sql_query_with_lock($lock_sql, $sql, true, true);
    // For UPDATE statements, $result is true/false
    if($result) {
      return true;
    } else {
      // UPDATE failed
      echo mysqli_error($db);
      db_disconnect($db);
      exit;
    }
  }

  /////////////////////////////////emp
  function find_all_emp() {
    global $db;

    $sql = "SELECT c.c_email,c.p_no,e.c_no,e.emp_id,co.c_name ";
    $sql .= "FROM jxx_emp e join jxx_coop co on e.coop_id=co.coop_id ";
    $sql .= "join jxx_customer c on c.c_no=e.c_no order by co.c_name, e.emp_id";
    //echo $sql;
    $result = mysqli_query($db, $sql);
    confirm_result_set($result);
    return $result;
  }

  function find_emp_by_id($c_no){
    global  $db;

    $sql = "SELECT c.c_email,c.p_no,e.c_no,e.emp_id,co.c_name,co.c_rate ";
    $sql .= "FROM jxx_emp e join jxx_coop co on e.coop_id=co.coop_id ";
    $sql .= "join jxx_customer c on c.c_no=e.c_no where e.c_no='" . db_escape($db,$c_no) . "'order by co.c_name, e.emp_id";
    //    echo $sql;
    $result = mysqli_query($db, $sql);
    confirm_result_set($result);
    return $result;
  }

  function find_emp_detail_by_id($c_no){
    global  $db;

    $sql = "SELECT * FROM jxx_emp e join jxx_coop co on e.coop_id=co.coop_id ";
    $sql .= "join jxx_customer c on c.c_no=e.c_no where e.c_no='" . db_escape($db,$c_no) . "'order by co.c_name, e.emp_id";
    //    echo $sql;
    $result = mysqli_query($db, $sql);
    confirm_result_set($result);
    return $result;
  }

  function find_emp_by_factors($emp){
    global  $db;

    $errors = [];
    $sql = "SELECT c.c_email,c.p_no,e.c_no,e.emp_id,co.c_name,co.c_rate ";
    $sql .= "FROM jxx_emp e join jxx_coop co on e.coop_id=co.coop_id ";
    $sql .= "join jxx_customer c on c.c_no=e.c_no where ";
    if(!is_blank($emp['emp_id'])){
      $sql .= "emp_id='" . db_escape($db,$emp['emp_id']) . "' ";
      if(!is_blank($emp['c_name'])){
        $sql .= "and c_name='" . db_escape($db,$emp['c_name']) . "' ";
        if(!is_blank($emp['c_email'])){
          $sql .= "and c_email='" . db_escape($db,$emp['c_email']) . "' ";
          if(!is_blank($emp['p_no'])){
            $sql .= "and p_no='" . db_escape($db,$emp['p_no']) . "'";
          }
        }
      }
    }elseif(!is_blank($emp['c_name'])){
      $sql .= "c_name='" . db_escape($db,$emp['c_name']) . "' ";
      if(!is_blank($emp['c_email'])){
        $sql .= "and c_email='" . db_escape($db,$emp['c_email']) . "' ";
        if(!is_blank($emp['p_no'])) {
          $sql .= "and p_no='" . db_escape($db,$emp['p_no']) . "'";
        }
      }
    }elseif(!is_blank($emp['c_email'])){
      $sql .= "c_email='" . db_escape($db,$emp['c_email']) . "' ";
      if(!is_blank($emp['p_no'])) {
        $sql .= "and p_no='" . db_escape($db,$emp['p_no']) . "'";
      }
    }elseif(!is_blank($emp['p_no'])) {
      $sql .= "and p_no='" . db_escape($db,$emp['p_no']) . "'";
    }else{
      $errors[] = "Please input at least one factor to search.";
    }
    //    echo $sql;
    $result = mysqli_query($db, $sql);
    confirm_result_set($result);
    return array($result, $errors);
  }

  function validate_customer($emp) {
    $errors = [];

    // c_street
    if(is_blank($emp['c_street'])) {
      $errors[] = "Street cannot be blank.";
    } elseif(!has_length($emp['c_street'], ['min' => 1, 'max' => 30])) {
      $errors[] = "Street must be between 1 and 30 characters.";
    }
    // c_city
    if(is_blank($emp['c_city'])) {
      $errors[] = "City cannot be blank.";
    } elseif(!has_length($emp['c_city'], ['min' => 1, 'max' => 30])) {
      $errors[] = "City must be between 1 and 30 characters.";
    }
    // c_state
    if(is_blank($emp['c_state'])) {
      $errors[] = "State cannot be blank.";
    } elseif(!has_length($emp['c_state'], ['min' => 1, 'max' => 30])) {
      $errors[] = "State must be between 1 and 30 characters.";
    }
    // c_zipcode
    if(is_blank($emp['c_zipcode'])) {
      $errors[] = "Zipcode cannot be blank.";
    } elseif(!has_length_exactly($emp['c_zipcode'], 5)) {
      $errors[] = "Zipcode must be 5 characters.";
    }
    // c_email
    if(is_blank($emp['c_email'])) {
      $errors[] = "Email cannot be blank.";
    }elseif(!filter_var($emp['c_email'],FILTER_VALIDATE_EMAIL)){
      $errors[] = "Please enter legal email.";
    } elseif(!has_length($emp['c_email'], ['min' => 1, 'max' => 30])) {
      $errors[] = "Email must be between 1 and 30 characters.";
    }
    // p_no
    if(is_blank($emp['p_no'])) {
      $errors[] = "Phone number cannot be blank.";
    } elseif(!has_length_exactly($emp['p_no'], 10)) {
      $errors[] = "Phone number must be 10 characters.";
    }

    return $errors;
  }

  function insert_customer($emp) {
    global $db;

    $errors = validate_customer($emp);
    if(!empty($errors)) {
      return $errors;
    }

    $sql = "INSERT INTO jxx_customer ";
    $sql .= "(c_type,c_street,c_city,c_state,c_zipcode,c_email,p_no) ";
    $sql .= "VALUES (";
    $sql .= "'" . db_escape($db, $emp['c_type']) . "',";
    $sql .= "'" . db_escape($db, $emp['c_street']) . "',";
    $sql .= "'" . db_escape($db, $emp['c_city']) . "',";
    $sql .= "'" . db_escape($db, $emp['c_state']) . "',";
    $sql .= "'" . db_escape($db, $emp['c_zipcode']) . "',";
    $sql .= "'" . db_escape($db, $emp['c_email']) . "',";
    $sql .= "'" . db_escape($db, $emp['p_no']) . "'";
    $sql .= ")";
//    echo $sql;
    $result = mysqli_query($db, $sql);
    // For INSERT statements, $result is true/false
    if($result) {
      return true;
    } else {
      // INSERT failed
      echo mysqli_error($db);
      db_disconnect($db);
      exit;
    }
  }

  function validate_emp($emp) {
    $errors = [];

    // emp_id
    $emp_id_int = (int) $emp['emp_id'];
    if(is_blank($emp['emp_id'])) {
      $errors[] = "Employee ID cannot be blank.";
    } elseif($emp_id_int<= 0) {
      $errors[] = "Employee ID cannot be negative.";
    }
    elseif($emp_id_int > 999) {
      $errors[] = "Employee ID must be less than 999.";
    }

    // coop_id
    $coop_id_int = (int) $emp['coop_id'];
    if(is_blank($emp['coop_id'])) {
      $errors[] = "Corporation ID cannot be blank.";
    } elseif($coop_id_int<= 0) {
      $errors[] = "Corporation ID cannot be negative.";
    }
    elseif($coop_id_int > 999) {
      $errors[] = "Corporation ID must be less than 999.";
    }

    return $errors;
  }

  function insert_emp($emp) {
    global $db;

    $errors = validate_emp($emp);
    if(!empty($errors)) {
      return $errors;
    }

    $sql = "INSERT INTO jxx_emp ";
    $sql .= "(c_no,emp_id,coop_id) ";
    $sql .= "VALUES (";
    $sql .= "'" . db_escape($db, $emp['c_no']) . "',";
    $sql .= "'" . db_escape($db, $emp['emp_id']) . "',";
    $sql .= "'" . db_escape($db, $emp['coop_id']) . "'";
    $sql .= ")";
    //echo $sql;
    $result = mysqli_query($db, $sql);
    // For INSERT statements, $result is true/false
    if($result) {
      return true;
    } else {
      // INSERT failed
      echo mysqli_error($db);
      db_disconnect($db);
      exit;
    }
  }

  function update_emp($emp) {
    global $db;

    $errors = validate_emp($emp);
    if(!empty($errors)) {
      return $errors;
    }

    $lock_sql = "SELECT * FROM jxx_emp ";
    $lock_sql .= "WHERE c_no='" . db_escape($db, $emp['c_no']) . "' ";
    $lock_sql .= "FOR UPDATE;";

    $sql = "UPDATE jxx_emp SET ";
    $sql .= "emp_id='" . db_escape($db, $emp['emp_id']) . "', ";
    $sql .= "coop_id='" . db_escape($db, $emp['coop_id']) . "' ";
    $sql .= "WHERE c_no='" . db_escape($db, $emp['c_no']) . "' ";
    $sql .= "LIMIT 1";

    $result = execute_sql_query_with_lock($lock_sql, $sql, true, true);
    // For UPDATE statements, $result is true/false
    if($result) {
      return true;
    } else {
      // UPDATE failed
      echo mysqli_error($db);
      db_disconnect($db);
      exit;
    }
  }

  function update_customer($emp) {
    global $db;

    $errors = validate_customer($emp);
    if(!empty($errors)) {
      return $errors;
    }

    $lock_sql = "SELECT * FROM jxx_customer ";
    $lock_sql .= "WHERE c_no='" . db_escape($db, $emp['c_no']) . "' ";
    $lock_sql .= "FOR UPDATE;";

    $sql = "UPDATE jxx_customer SET ";
    $sql .= "c_type='" . db_escape($db, $emp['c_type']) . "', ";
    $sql .= "c_street='" . db_escape($db, $emp['c_street']) . "', ";
    $sql .= "c_city='" . db_escape($db, $emp['c_city']) . "', ";
    $sql .= "c_state='" . db_escape($db, $emp['c_state']) . "', ";
    $sql .= "c_zipcode='" . db_escape($db, $emp['c_zipcode']) . "', ";
    $sql .= "c_email='" . db_escape($db, $emp['c_email']) . "', ";
    $sql .= "p_no='" . db_escape($db, $emp['p_no']) . "' ";
    $sql .= "WHERE c_no='" . db_escape($db, $emp['c_no']) . "' ";
    $sql .= "LIMIT 1";
//    echo $sql;
    $result = execute_sql_query_with_lock($lock_sql, $sql, true, true);
    // For UPDATE statements, $result is true/false
    if($result) {
      return true;
    } else {
      // UPDATE failed
      echo mysqli_error($db);
      db_disconnect($db);
      exit;
    }
  }

  function delete_emp($c_no) {
    global $db;

    //    $sql = "set foreign_key_checks=0";
    //    mysqli_query($db, $sql);

    $lock_sql = "SELECT * FROM jxx_emp ";
    $lock_sql .= "WHERE c_no='" . db_escape($db, $c_no) . "' ";
    $lock_sql .= "FOR UPDATE;";

    $sql = "DELETE FROM jxx_emp ";
    $sql .= "WHERE c_no='" . db_escape($db, $c_no) . "' ";
    $sql .= "LIMIT 1";

    $result = execute_sql_query_with_lock($lock_sql, $sql, true, true);

    //    $sql = "set foreign_key_checks=1";
    //    mysqli_query($db, $sql);

    // For DELETE statements, $result is true/false
    if($result) {
      return true;
    } else {
      // DELETE failed
      echo mysqli_error($db);
      db_disconnect($db);
      exit;
    }
  }

  function delete_customer($c_no) {
    global $db;

    //    $sql = "set foreign_key_checks=0";
    //    mysqli_query($db, $sql);

    $lock_sql = "SELECT * FROM jxx_customer ";
    $lock_sql .= "WHERE c_no='" . db_escape($db, $c_no) . "' ";
    $lock_sql .= "FOR UPDATE;";

    $sql = "DELETE FROM jxx_customer ";
    $sql .= "WHERE c_no='" . db_escape($db, $c_no) . "' ";
    $sql .= "LIMIT 1";

    $result = execute_sql_query_with_lock($lock_sql, $sql, true, true);

    //    $sql = "set foreign_key_checks=1";
    //    mysqli_query($db, $sql);

    // For DELETE statements, $result is true/false
    if($result) {
      return true;
    } else {
      // DELETE failed
      echo mysqli_error($db);
      db_disconnect($db);
      exit;
    }
  }


  //Modified by Xiaoyuan
  function find_orders_by_customer_id($customer_id) {
    global $db;
    $sql = "select
                  s.s_id as s_id,
                  dl.l_street as d_street,
                  dl.l_city as d_city,
                  dl.l_state as d_state,
                  dl.l_zipcode as d_zipcode,
                  pl.l_street as pk_street,
                  pl.l_city as pk_city,
                  pl.l_state as pk_state,
                  pl.l_zipcode as pk_zipcode,
                  cl.rental_rate as rental_rate,
                  cl.over_fee as over_fee,
                  v.make as make,
                  v.vid as vid,
                  cou.cou_discount as cou_discount,
                  s.pk_date as pk_date,
                  s.d_date as d_date,
                  s.s_odom as s_odom,
                  s.e_odom as e_odom,
                  s.daily_limit as daily_limit,
                  s.is_complete as is_complete
                from
                (
                  select
                    *
                  from
                    jxx_service
                  where 
                    c_no = '" . db_escape($db,$customer_id) . "'
                ) s 
                left join jxx_vehicle v on s.vid = v.vid
                left join jxx_loc pl on s.pk_l_id = pl.l_id
                left join jxx_loc dl on s.d_l_id = dl.l_id
                left join jxx_class cl on v.c_id = cl.c_id
              left join jxx_coupons cou on s.cou_id = cou.cou_id;";

    //    echo $sql;

    $result = mysqli_query($db, $sql);
    confirm_result_set($result);
    return $result;
  }

  function find_orders_by_service_id($service_id) {
    global $db;
    $sql = "select
                s.s_id as s_id,
                dl.l_street as d_street,
                dl.l_city as d_city,
                dl.l_state as d_state,
                dl.l_zipcode as d_zipcode,
                pl.l_street as pk_street,
                pl.l_city as pk_city,
                pl.l_state as pk_state,
                pl.l_zipcode as pk_zipcode,
                cl.rental_rate as rental_rate,
                cl.over_fee as over_fee,
                v.make as make,
                v.vid as vid,
                cou.cou_discount as cou_discount,
                s.pk_date as pk_date,
                s.d_date as d_date,
                s.s_odom as s_odom,
                s.e_odom as e_odom,
                s.daily_limit as daily_limit
              from
              (
                select
                  *
                from
                  jxx_service
                where 
                  s_id = '" . db_escape($db,$service_id) . "'
              ) s 
              left join jxx_vehicle v on s.vid = v.vid
              left join jxx_loc pl on s.pk_l_id = pl.l_id
              left join jxx_loc dl on s.d_l_id = dl.l_id
              left join jxx_class cl on v.c_id = cl.c_id
            left join jxx_coupons cou on s.cou_id = cou.cou_id;";

    $result = mysqli_query($db, $sql);
    confirm_result_set($result);
    return $result;
  }

  function find_orders_by_customer_id_and_service_id($customer_id, $service_id) {
    global $db;
    $sql = "select
                s.s_id as s_id,
                dl.l_street as d_street,
                dl.l_city as d_city,
                dl.l_state as d_state,
                dl.l_zipcode as d_zipcode,
                pl.l_street as pk_street,
                pl.l_city as pk_city,
                pl.l_state as pk_state,
                pl.l_zipcode as pk_zipcode,
                cl.rental_rate as rental_rate,
                cl.over_fee as over_fee,
                v.make as make,
                v.vid as vid,
                cou.cou_discount as cou_discount,
                s.pk_date as pk_date,
                s.d_date as d_date,
                s.s_odom as s_odom,
                s.e_odom as e_odom,
                s.daily_limit as daily_limit
              from
              (
                select
                  *
                from
                  jxx_service
                where 
                  s_id = " . db_escape($db,$service_id) . " and c_no = " . db_escape($db,$customer_id) . "
              ) s 
              left join jxx_vehicle v on s.vid = v.vid
              left join jxx_loc pl on s.pk_l_id = pl.l_id
              left join jxx_loc dl on s.d_l_id = dl.l_id
              left join jxx_class cl on v.c_id = cl.c_id
            left join jxx_coupons cou on s.cou_id = cou.cou_id;";

    $result = mysqli_query($db, $sql);
    confirm_result_set($result);
    return $result;
  }

  function pay_invoice($service) {
    // params: s_id, p_date, method, card_no
    global $db;

    //根据s_id查询订单
    $sql = "
                select 
                  i_id
                from
                  jxx_invoice i
                where
                  i.s_id = " . db_escape($db,$service['s_id']);
    //echo $sql;
    $result = mysqli_query($db, $sql);
    $i_id = -1;
    if($result) {
      $record = mysqli_fetch_assoc($result);
      if (isset($record['i_id']) && !empty($record['i_id']))
        $i_id = $record['i_id'];
      else {
        echo 'invoice does not exist!';
        echo mysqli_error($db);
        db_disconnect($db);
        exit;
      }
    } else {
      echo mysqli_error($db);
      db_disconnect($db);
      exit;
    }
    //echo $i_id;

    //向payment表中写入数据
    $sql = "
                insert into jxx_payment (
                  method, 
                  p_date,
                  card_no, 
                  i_id,
                  p_amount
                ) values (
                  '" . db_escape($db,$service['method']) . "',
                  '" . db_escape($db,$service['p_date']) . "',
                  '" . db_escape($db,$service['card_no']) . "',
                  " . db_escape($db,$i_id) . ",
                  " . db_escape($db,$service['p_amount']) . "
                )";
    //echo $sql;
    $result = mysqli_query($db, $sql);
    // For INSERT statements, $result is true/false
    if (false == $result) {
      // INSERT failed
      echo mysqli_error($db);
      db_disconnect($db);
      exit;
    }

    //更新车辆数据
    $lock_sql = "
                select *
                from jxx_vehicle
                where vid = " . db_escape($db,$service['vid']) ." for update;";

    $sql = "
                update jxx_vehicle v set
                  is_available = true
                where
                  v.vid = " . db_escape($db,$service['vid']);
    //echo $sql;

    $result = execute_sql_query_with_lock($lock_sql, $sql, true, true);
    if (false == $result) {
      // 更新车辆可用状态失败，回滚
      echo mysqli_error($db);
      db_disconnect($db);
      exit;
    }
    return true;
  }

  function update_admin($admin) {
    global $db;

    $password_sent = !is_blank($admin['password']);

    $errors = validate_admin($admin, ['password_required' => $password_sent]);
    if (!empty($errors)) {
      return $errors;
    }

    $hashed_password = password_hash($admin['password'], PASSWORD_BCRYPT);

    $lock_sql = "SELECT * FROM jxx_admins ";
    $lock_sql .= "WHERE id='" . db_escape($db, $admin['id']) . "' ";
    $lock_sql .= "FOR UPDATE;";

    $sql = "UPDATE jxx_admins SET ";
    $sql .= "first_name='" . db_escape($db, $admin['first_name']) . "', ";
    $sql .= "last_name='" . db_escape($db, $admin['last_name']) . "', ";
    $sql .= "email='" . db_escape($db, $admin['email']) . "', ";
    if($password_sent) {
      $sql .= "hashed_password='" . db_escape($db, $hashed_password) . "', ";
    }
    $sql .= "username='" . db_escape($db, $admin['username']) . "' ";
    $sql .= "WHERE id='" . db_escape($db, $admin['id']) . "' ";
    $sql .= "LIMIT 1";

    $result = execute_sql_query_with_lock($lock_sql, $sql, true, true);

    // For UPDATE statements, $result is true/false
    if($result) {
      return true;
    } else {
      // UPDATE failed
      echo mysqli_error($db);
      db_disconnect($db);
      exit;
    }
  }

  function update_user($customer) {
    global $db;

    $password_sent = !is_blank($customer['password']);

    $errors = validate_user($customer, ['password_required' => $password_sent]);
    if (!empty($errors)) {
      return $errors;
    }

    $hashed_password = password_hash($customer['password'], PASSWORD_BCRYPT);

    $lock_sql = "SELECT * FROM jxx_users ";
    $lock_sql .= "WHERE id='" . db_escape($db, $customer['id']) . "' ";
    $lock_sql .= "FOR UPDATE;";

    $sql = "UPDATE jxx_users SET ";
//    $sql .= "first_name='" . db_escape($db, $customer['first_name']) . "', ";
//    $sql .= "last_name='" . db_escape($db, $customer['last_name']) . "', ";
//    $sql .= "email='" . db_escape($db, $customer['email']) . "', ";
    if($password_sent) {
      $sql .= "hashed_password='" . db_escape($db, $hashed_password) . "', ";
    }
    $sql .= "username='" . db_escape($db, $customer['username']) . "' ";
    $sql .= "WHERE id='" . db_escape($db, $customer['id']) . "' ";
    $sql .= "LIMIT 1";
//    echo $sql;
    $result = execute_sql_query_with_lock($lock_sql, $sql, true, true);

    // For UPDATE statements, $result is true/false
    if($result) {
      return true;
    } else {
      // UPDATE failed
      echo mysqli_error($db);
      db_disconnect($db);
      exit;
    }
  }

  function delete_admin($admin) {
    global $db;

    $lock_sql = "SELECT * FROM jxx_admins ";
    $lock_sql .= "WHERE id='" . db_escape($db, $admin) . "' ";
    $lock_sql .= "FOR UPDATE;";

    $sql = "DELETE FROM jxx_admins ";
    $sql .= "WHERE id='" . db_escape($db, $admin) . "' ";
    $sql .= "LIMIT 1;";

    $result = execute_sql_query_with_lock($lock_sql, $sql, true, true);

    // For DELETE statements, $result is true/false
    if($result) {
      return true;
    } else {
      // DELETE failed
      echo mysqli_error($db);
      db_disconnect($db);
      exit;
    }
  }

  function validate_user($customer, $options=[]) {

    $password_required = $options['password_required'] ?? true;
    $errors = [];
//    if(is_blank($customer['first_name'])) {
//      $errors[] = "First name cannot be blank.";
//    } elseif (!has_length($customer['first_name'], array('min' => 2, 'max' => 255))) {
//      $errors[] = "First name must be between 2 and 255 characters.";
//    }
//
//    if(is_blank($customer['last_name'])) {
//      $errors[] = "Last name cannot be blank.";
//    } elseif (!has_length($customer['last_name'], array('min' => 2, 'max' => 255))) {
//      $errors[] = "Last name must be between 2 and 255 characters.";
//    }

//    if(is_blank($customer['email'])) {
//      $errors[] = "Email cannot be blank.";
//    } elseif (!has_length($customer['email'], array('max' => 255))) {
//      $errors[] = "Last name must be less than 255 characters.";
//    } elseif (!has_valid_email_format($customer['email'])) {
//      $errors[] = "Email must be a valid format.";
//    }

    if(is_blank($customer['username'])) {
      $errors[] = "Username cannot be blank.";
    } elseif (!has_length($customer['username'], array('min' => 8, 'max' => 255))) {
      $errors[] = "Username must be between 8 and 255 characters.";
    } elseif (!has_unique_customer_username($customer['username'], $customer['id'] ?? 0)) {
      $errors[] = "Username not allowed. Try another.";
    }


    if($password_required) {
      if(is_blank($customer['password'])) {
        $errors[] = "Password cannot be blank.";
      } elseif (!has_length($customer['password'], array('min' => 12))) {
        $errors[] = "Password must contain 12 or more characters";
      } elseif (!preg_match('/[A-Z]/', $customer['password'])) {
        $errors[] = "Password must contain at least 1 uppercase letter";
      } elseif (!preg_match('/[a-z]/', $customer['password'])) {
        $errors[] = "Password must contain at least 1 lowercase letter";
      } elseif (!preg_match('/[0-9]/', $customer['password'])) {
        $errors[] = "Password must contain at least 1 number";
      } elseif (!preg_match('/[^A-Za-z0-9\s]/', $customer['password'])) {
        $errors[] = "Password must contain at least 1 symbol";
      }

      if(is_blank($customer['confirm_password'])) {
        $errors[] = "Confirm password cannot be blank.";
      } elseif ($customer['password'] !== $customer['confirm_password']) {
        $errors[] = "Password and confirm password must match.";
      }
    }

    return $errors;
  }

  function insert_user($customer) {
    global $db;

    $errors = validate_user($customer);
    if (!empty($errors)) {
      return $errors;
    }

    $hashed_password = password_hash($customer['password'], PASSWORD_BCRYPT);

    $sql = "INSERT INTO jxx_users ";
    $sql .= "(username, hashed_password,c_no) ";
    $sql .= "VALUES (";
//    $sql .= "'" . db_escape($db, $customer['first_name']) . "',";
//    $sql .= "'" . db_escape($db, $customer['last_name']) . "',";
//    $sql .= "'" . db_escape($db, $customer['email']) . "',";
    $sql .= "'" . db_escape($db, $customer['username']) . "',";
    $sql .= "'" . db_escape($db, $hashed_password) . "',";
    $sql .= "'" . db_escape($db, $customer['c_no']) . "'";
    $sql .= ")";
    $result = mysqli_query($db, $sql);



    // For INSERT statements, $result is true/false
    if($result) {
      return true;
    } else {
      // INSERT failed
      echo mysqli_error($db);
      db_disconnect($db);
      exit;
    }
  }

  function validate_contact($contact) {


    if(is_blank($contact['first_name'])) {
      $errors[] = "First name cannot be blank.";
    } elseif (!has_length($contact['first_name'], array('min' => 2, 'max' => 255))) {
      $errors[] = "First name must be between 2 and 255 characters.";
    }

    if(is_blank($contact['last_name'])) {
      $errors[] = "Last name cannot be blank.";
    } elseif (!has_length($contact['last_name'], array('min' => 2, 'max' => 255))) {
      $errors[] = "Last name must be between 2 and 255 characters.";
    }

    if(is_blank($contact['email'])) {
      $errors[] = "Email cannot be blank.";
    } elseif (!has_length($contact['email'], array('max' => 255))) {
      $errors[] = "Last name must be less than 255 characters.";
    } elseif (!has_valid_email_format($contact['email'])) {
      $errors[] = "Email must be a valid format.";
    }


    if(is_blank($contact['last_name'])) {
      $errors[] = "Last name cannot be blank.";
    } elseif (!has_length($contact['info'], array('min' => 2, 'max' => 255))) {
      $errors[] = "info must be between 2 and 255 characters.";
    }

  }

  function insert_contact($contact) {
    global $db;

    $errors = validate_contact($contact);
    if (!empty($errors)) {
      return $errors;
    }

    $sql = "INSERT INTO jxx_contactus ";
    $sql .= "(time, first_name, last_name, email, info) ";
    $sql .= "VALUES (";
    $sql .= "'" . db_escape($db, $contact['time']) . "',";
    $sql .= "'" . db_escape($db, $contact['first_name']) . "',";
    $sql .= "'" . db_escape($db, $contact['last_name']) . "',";
    $sql .= "'" . db_escape($db, $contact['email']) . "',";
    $sql .= "'" . db_escape($db, $contact['info']) . "'";
    $sql .= ")";
    $result = mysqli_query($db, $sql);

    // For INSERT statements, $result is true/false
    if($result) {
      return true;
    } else {
      // INSERT failed
      echo mysqli_error($db);
      db_disconnect($db);
      exit;
    }
  }

    //0426 by Xiaoyuan

  function find_payments_by_customer_id($c_no) {
    global $db;
    $sql = "
                select
                       s.s_id s_id,
                  v.make make,
                  v.model model,
                  p.p_amount p_amount,
                  p.method method,
                  p.p_date p_date,
                  p.card_no card_no
                from
                jxx_payment p
                left join jxx_invoice i on p.i_id = i.i_id
                left join jxx_service s on i.s_id = s.s_id
                left join jxx_vehicle v on v.vid = s.vid
                where
                s.c_no = " . db_escape($db, $c_no);

  //    echo $sql;
    $result = mysqli_query($db, $sql);
    confirm_result_set($result);

    return $result;
  }

  function find_payment_by_service_id($s_id) {
    global $db;
    $sql = "
              select
                *
              from
              (
                select
                  *
                from
                  jxx_service
                where
                  s_id = " . db_escape($db, $s_id) . "
              ) s
              inner join
                jxx_invoice i on s.s_id = i.s_id
              inner join
                jxx_payment p on i.i_id = p.i_id 
            ";
    //echo $sql;
    $result = mysqli_query($db, $sql);
    confirm_result_set($result);

    return $result;
  }

  function insert_service($service) {
    // params: vid, pk_date, daily_limit, d_date, pk_l_id, c_no, cou_id
    global $db;

    $errors = validate_service($service);
    if(!empty($errors)) {
      return $errors;
    }

    //根据vid查询车辆odom
    $lock_sql = "
              select 
                *
              from
                jxx_vehicle v
              where
                v.vid = '" . db_escape($db,$service['vid']) . "'
                for update;";
    $sql = "
                select 
                  odom,
                  is_available
                from
                  jxx_vehicle v
                where
                  v.vid = '" . db_escape($db,$service['vid']) . "'";
//    echo $sql;
    $result = execute_sql_query_with_lock($lock_sql, $sql, true, false);
    $odom = 0;
    if($result) {
      $record = mysqli_fetch_assoc($result);
      if ($record['is_available'])
        //车辆未被占用
        $odom = $record['odom'];
      else {
        //echo 'vehicle is not available';
        $_SESSION['message'] = 'vehicle is not available';
        echo mysqli_error($db);
        execute_sql_query_with_lock("", "", false, true);
        return false;
      }
    } else {
      echo mysqli_error($db);
      execute_sql_query_with_lock("", "", false, true);
      return false;
    }
//    echo $odom;

    //查询coupon可用性
    //echo "coupon id: " . $service['cou_id'];
    //echo "      ";
    if (isset($service['cou_id']) && (!empty($service['cou_id'])) && ($service['cou_id'] != '')) {
      if (!valid_coupon($service['cou_id'], $service['pk_date'])) {
        //echo 'coupon is not available';
        $_SESSION['message'] = 'coupon is not available';
        echo mysqli_error($db);
//        db_disconnect($db);
        execute_sql_query_with_lock("", "", false, true);
        return false;
      }

      //coupon可用，将其锁定
      $sql = "
                update
                  jxx_coupons c
                set
                  is_available = 0
                where
                  c.cou_id = " . db_escape($db,$service['cou_id']);

      //echo $sql;
      $result = mysqli_query($db, $sql);
      if (false == $result) {
        // 更新优惠券可用状态失败，回滚
        echo mysqli_error($db);
        $_SESSION['message'] = 'coupon is not available';
        //db_disconnect($db);
        execute_sql_query_with_lock("", "", false, true);
        return false;
      }
    } else {
      $service['cou_id'] = 'NULL';
    }

    //向sevice表中写入数据
    $sql = "
                insert into jxx_service (
                  pk_date, 
                  d_date,
                  s_odom, 
                  e_odom,
                  daily_limit,
                  vid,
                  pk_l_id,
                  d_l_id,
                  c_no, 
                  cou_id
                ) values (
                  '" . db_escape($db,$service['pk_date']) . "',
                  '" . db_escape($db,$service['d_date']) . "',
                  '" . db_escape($db,$odom) . "',
                  NULL,
                  " . db_escape($db,$service['daily_limit']) . ",
                  '" . db_escape($db,$service['vid']) . "',
                  '" . db_escape($db,$service['pk_l_id']) . "',
                  NULL,
                  '" . db_escape($db,$service['c_no']) . "',
                  " . db_escape($db,$service['cou_id']) . "
                )";
    //echo $sql;
    $result = execute_sql_query_with_lock("", $sql, false, false);
    // For INSERT statements, $result is true/false
    if (false == $result) {
      // INSERT failed
      echo mysqli_error($db);
      $_SESSION['message'] = 'error happend when insert service!';
      execute_sql_query_with_lock("", "", false, true);
      mysqli_rollback($db);
      exit;
    }

    //更新车辆数据
    $sql = "
                update jxx_vehicle v set
                  is_available = false
                where
                  v.vid = " . db_escape($db,$service['vid']);
    //echo $sql;
    $result = execute_sql_query_with_lock($lock_sql="", $sql, $execute_begin=false, $execute_end=true);
    if (false == $result) {
      // 更新车辆可用状态失败，回滚
      $_SESSION['message'] = 'vehicle is not avaiable!';
      echo mysqli_error($db);
      //db_disconnect($db);
      mysqli_rollback($db);
      return false;
    }
    $_SESSION['message'] = 'create order success!';
    return true;
  }

  function update_service($service) {
    // params: s_id, d_date, daily_limit, cou_id
    global $db;

    //恢复原优惠券可用性
    $lock_sql = "
              select 
                *
              from
                jxx_service s
              where
                s.s_id = " . db_escape($db,$service['s_id']) . " 
                for update;";
    $sql = "
              select
                cou_id
              from
                jxx_service
              where
                s_id = " . db_escape($db,$service['s_id']);
    $result = execute_sql_query_with_lock($lock_sql, $sql, true, false);

    $record = mysqli_fetch_assoc($result);
    if (isset($record['cou_id']) && (!empty($record['cou_id'])) && ($record['cou_id'] != 0)) {
      $sql = "
                update
                  jxx_coupons c
                set
                  is_available = 1
                where
                  c.cou_id = " . db_escape($db,$record['cou_id']);
      $result = execute_sql_query_with_lock($lock_sql="", $sql, false, false);

      if (false == $result) {
        // 更新优惠券可用状态失败，回滚
        $_SESSION['message'] = 'update coupon failed';
        echo mysqli_error($db);
        execute_sql_query_with_lock("", "", false, true);
        mysqli_rollback($db);
        return false;
      }
    }

    //查询新coupon可用性
    if (isset($service['cou_id']) && (!empty($service['cou_id'])) && ($service['cou_id'] != '')) {
      if (!valid_coupon($service['cou_id'], $service['pk_date'])){
        //echo 'coupon is not available';
        $_SESSION['message'] = 'coupon is not available';
        echo mysqli_error($db);
        execute_sql_query_with_lock("", "", false, true);
        mysqli_rollback($db);
        return false;
      }

      //coupon可用，将其锁定
      $sql = "
                update
                  jxx_coupons c
                set
                  is_available = 0
                where
                  c.cou_id = " . db_escape($db,$service['cou_id']);

      //echo $sql;
      $result = mysqli_query($db, $sql);
      if (false == $result) {
        // 更新优惠券可用状态失败，回滚
        echo mysqli_error($db);
        $_SESSION['message'] = 'coupon is not available';
        execute_sql_query_with_lock("", "", false, true);
        mysqli_rollback($db);
        return false;
      }
    } else {
      $service['cou_id'] = 'NULL';
    }

    //更新订单
    $lock_sql = "
              select 
                *
              from
                jxx_service s
              where
                s.s_id = " . db_escape($db,$service['s_id']) . " 
                for update;";
    $sql = "
                update jxx_service s set
                  d_date = '" . db_escape($db,$service['d_date']) . "',
                  daily_limit = " . db_escape($db,$service['daily_limit']) . ",
                  cou_id = " . db_escape($db,$service['cou_id']) . "
                where
                  s.s_id = " . db_escape($db,$service['s_id']);
    //echo $sql;
    $result = execute_sql_query_with_lock($lock_sql, $sql, false, true);
    // For INSERT statements, $result is true/false
    if (false == $result) {
      // INSERT failed
      echo mysqli_error($db);
      $_SESSION['message'] = 'error happend when update service!';
      execute_sql_query_with_lock("", "", false, true);
      mysqli_rollback($db);
      return false;
    }
    
    $_SESSION['message'] = 'update service success!';
    return true;
  }

  function delete_service($service) {
    // params: s_id, vid
    global $db;

    //更新车辆数据
    $lock_sql = "
              select
                *
              from  
                jxx_vehicle v
              where
                v.vid = " . db_escape($db,$service['vid']) . "
                for update;";
    $sql = "
                update jxx_vehicle v set
                  is_available = true
                where
                  v.vid = " . db_escape($db,$service['vid']);
    //echo $sql;
    $result = execute_sql_query_with_lock($lock_sql, $sql, true, false);
    if (false == $result) {
      // 更新车辆可用状态失败，回滚
      echo mysqli_error($db);
      $_SESSION['message'] = 'error happend when set vehicle avaiable!';
      execute_sql_query_with_lock("", "", false, true);
      mysqli_rollback($db);
      return false;
    }

    //恢复原优惠券可用性
    $lock_sql = "
              select 
                *
              from
                jxx_service s
              where
                s.s_id = " . db_escape($db,$service['s_id']) . " 
                for update;";
    $sql = "
              select
                cou_id
              from
                jxx_service
              where
                s_id = " . db_escape($db,$service['s_id']);
    $result = execute_sql_query_with_lock($lock_sql, $sql, false, false);
    $record = mysqli_fetch_assoc($result);
    if (isset($record['cou_id']) && (!empty($record['cou_id'])) && ($record['cou_id'] != 0)) {
      $lock_sql = "
              select 
                *
              from
                jxx_coupons c
              where
                c.cou_id = " . db_escape($db,$record['cou_id']) . " 
                for update;";
      $sql = "
                update
                  jxx_coupons c
                set
                  is_available = 1
                where
                  c.cou_id = " . db_escape($db,$record['cou_id']);
      $result = execute_sql_query_with_lock($lock_sql, $sql, false, false);
      if (false == $result) {
        // 更新优惠券可用状态失败，回滚
        $_SESSION['message'] = 'update coupon failed';
        echo mysqli_error($db);
        execute_sql_query_with_lock("", "", false, true);
        mysqli_rollback($db);
        return false;
      }
    }

    //删除订单
    $lock_sql = "
              select 
                *
              from
                jxx_service s
              where
                s.s_id = " . db_escape($db,$service['s_id']) . " 
                for update;";
    $sql = "delete
                from
                  jxx_service 
                where 
                  s_id = " . db_escape($db,$service['s_id']);
    //echo $sql;
    $result = execute_sql_query_with_lock($lock_sql, $sql, false, true);
    $odom = 0;
    if(false == $result) {
      echo mysqli_error($db);
      $_SESSION['message'] = 'error happend when delete service!';
      execute_sql_query_with_lock("", "", false, true);
      mysqli_rollback($db);
      return false;
    }

    $_SESSION['message'] = 'delete service success!';
    return true;
  }

  function calculate_invoice_by_service_id($service_id) {
    global $db;
    $sql = "select
                  round(avg(i.i_amount),2) as total_amount,
                  round(avg(i.i_amount) - coalesce(sum(p.p_amount),0.0), 2) as i_amount
              from
                (
                  select
                    *
                  from
                    jxx_service
                  where 
                    s_id = " . db_escape($db,$service_id) . "
                ) s 
                left join jxx_invoice i on s.s_id = i.s_id
                left join jxx_payment p on i.i_id = p.i_id
              group by s.s_id
              ";

//    echo $sql;

    $result = mysqli_query($db, $sql);
    confirm_result_set($result);
    return $result;
  }

  ////////////////////////////////////////////////////////////////////
  function find_service_by_vin_username($service) {
    global $db;

    $errors = [];
    $sql = "SELECT * FROM jxx_service s join jxx_users u on s.c_no=u.c_no join jxx_vehicle v on v.vid=s.vid ";
    $sql .= "WHERE u.username='" . db_escape($db, $service['username']) . "' ";
    $sql .= "AND v.vin='" . db_escape($db, $service['vin']) . "' ";
    $sql .= "AND s.is_complete=0 ";
//    $sql .= "LIMIT 1";
//    echo $sql;
    $result = mysqli_query($db, $sql);
    confirm_result_set($result);
    $service = mysqli_fetch_assoc($result); // find first

    if(mysqli_num_rows($result)<=0) {
      $errors[] = "Invalid VIN or username.";
    }
    mysqli_free_result($result);
    return array($service,$errors);

  }

  function find_services_by_factors($service) {
    global $db;

    $errors = [];
    $sql = "SELECT * FROM jxx_service s 
            join jxx_users u on s.c_no=u.c_no 
            join jxx_vehicle v on v.vid=s.vid 
            join jxx_class c on c.c_id=v.c_id 
            where ";
    if(!is_blank($service['username'])){
      $sql .= "u.username='" . db_escape($db,$service['username']) . "' ";
      if(!is_blank($service['vin'])){
        $sql .= "and v.vin='" . db_escape($db,$service['vin']) . "' ";
        if(!is_blank($service['make'])){
          $sql .= "and v.make='" . db_escape($db,$service['make']) . "' ";
          if(!is_blank($service['class_name'])){
            $sql .= "and c.class_name='" . db_escape($db,$service['class_name']) . "' ";
          }
        }
      }
    }elseif(!is_blank($service['vin'])){
      $sql .= "v.vin='" . db_escape($db,$service['vin']) . "' ";
      if(!is_blank($service['make'])){
        $sql .= "and v.make='" . db_escape($db,$service['make']) . "' ";
        if(!is_blank($service['class_name'])){
          $sql .= "and c.class_name='" . db_escape($db,$service['class_name']) . "' ";
        }
      }
    }elseif(!is_blank($service['make'])){
      $sql .= "v.make='" . db_escape($db,$service['make']) . "' ";
      if(!is_blank($service['class_name'])){
        $sql .= "and c.class_name='" . db_escape($db,$service['class_name']) . "' ";
      }
    }elseif(!is_blank($service['class_name'])){
      $sql .= "c.class_name='" . db_escape($db,$service['class_name']) . "' ";
    }else{
      $errors[] = "Please at least input one factor.";
    }
//    $sql .= "LIMIT 1";
//      echo $sql;
    $result = mysqli_query($db, $sql);
    confirm_result_set($result);

//    $service = mysqli_fetch_assoc($result); // find first
//
//    if(mysqli_num_rows($result)<=0) {
//      $errors[] = "No result.";
//    }
//    mysqli_free_result($result);
    return array($result,$errors);

  }

  function find_vehicle_by_vin($vin){
    global $db;

    $sql = "SELECT * FROM jxx_vehicle ";
    $sql .= "WHERE vin='" . db_escape($db, $vin) . "' ";
//    $sql .= "LIMIT 1";
    $result = mysqli_query($db, $sql);
    confirm_result_set($result);
    $vehicle = mysqli_fetch_assoc($result); // find first
    mysqli_free_result($result);
    return $vehicle; // returns an assoc. array
  }

///////0430 modified
  function finish_order($service){
    global $db;

    $errors = [];
    $lock_sql = "SELECT * FROM jxx_vehicle ";
    $lock_sql .= "WHERE vid='" . db_escape($db, $service['vid']) . "' ";
    $lock_sql .= "FOR UPDATE";

    $sql = "UPDATE jxx_vehicle SET ";
    $sql .= "odom='" . db_escape($db, $service['e_odom']) . "', ";
      $sql .= "l_id='" . db_escape($db, $service['d_l_id']) . "', ";
    $sql .= "is_available=1 ";
    $sql .= "WHERE vid='" . db_escape($db, $service['vid']) . "' ";
    $sql .= "LIMIT 1";
    $result = execute_sql_query_with_lock($lock_sql, $sql, true, false);
    // For UPDATE statements, $result is true/false
    if(!$result) {
      // UPDATE failed
      echo mysqli_error($db);
      $_SESSION['message'] = "update vehicle failed when trying to finish order.";
      execute_sql_query_with_lock("", "", false, true);
      mysqli_rollback($db);
      return false;
    }

    $lock_sql = "SELECT * FROM jxx_service ";
    $lock_sql .= "WHERE c_no='" . db_escape($db, $service['c_no']) . "' ";
    $lock_sql .= "and vid='" . db_escape($db, $service['vid']) . "' ";
    $lock_sql .= "and is_complete=0 ";
    $lock_sql .= "FOR UPDATE";

    $sql = "UPDATE jxx_service SET ";
    $sql .= "d_date='" . db_escape($db, $service['d_date']) . "', ";
    $sql .= "e_odom='" . db_escape($db, $service['e_odom']) . "', ";
    $sql .= "d_l_id='" . db_escape($db, $service['d_l_id']) . "', ";
    $sql .= "is_complete=1 ";
    $sql .= "WHERE c_no='" . db_escape($db, $service['c_no']) . "' ";
    $sql .= "and vid='" . db_escape($db, $service['vid']) . "' ";
    $sql .= "and is_complete=0 ";
    $sql .= "LIMIT 1";

    $result = execute_sql_query_with_lock($lock_sql, $sql, false, true);
    // For UPDATE statements, $result is true/false
    if($result) {
      return true;
    } else {
      // UPDATE failed
      echo mysqli_error($db);
      $_SESSION['message'] = "update service failed when trying to finish order.";
      mysqli_rollback($db);
    }
  }

  function find_location_id($location){
    global  $db;

    $errors = [];
    $sql = "select l_id from jxx_loc where ";
    $sql .= "l_state='" . db_escape($db,$location['l_state']) . "' ";
    $sql .= "and l_city='" . db_escape($db,$location['l_city']) . "' ";
    $sql .= "and l_street='" . db_escape($db,$location['l_street']) . "' ";
    $sql .= "and l_zipcode='" . db_escape($db,$location['l_zipcode']) . "'";
    $sql .= "LIMIT 1";
    $result = mysqli_query($db, $sql);
    confirm_result_set($result);
    $location = mysqli_fetch_assoc($result); // find first
    mysqli_free_result($result);
    return $location; // returns an assoc. array
  }

  function find_all_orders() {
    global $db;
    $sql = "select * from jxx_service s join jxx_users u on s.c_no=u.c_no";

    $result = mysqli_query($db, $sql);
    confirm_result_set($result);
    return $result;
  }

  function find_order_by_id($s_id) {
    global $db;

    $sql = "select
                  dl.l_city as d_city,
                  pl.l_city as pk_city,
                  cl.rental_rate as rental_rate,
                  cl.over_fee as over_fee,
                  v.make as make,
                  cou.cou_discount as cou_discount,
                  s.pk_date as pk_date,
                  s.d_date as d_date,
                  s.s_odom as s_odom,
                  s.e_odom as e_odom,
                  s.daily_limit as daily_limit,
                  s.is_complete as is_complete,
                  i.i_amount as i_amount,
                  u.username as username
                from jxx_service s 
                left join jxx_vehicle v on s.vid = v.vid
                left join jxx_loc pl on s.pk_l_id = pl.l_id
                left join jxx_loc dl on s.d_l_id = dl.l_id
                left join jxx_class cl on v.c_id = cl.c_id
                left join jxx_coupons cou on s.cou_id = cou.cou_id
                left join jxx_invoice i on i.s_id=s.s_id
                left join jxx_users u on u.c_no=s.c_no
                where s.s_id='" . db_escape($db,$s_id) . "'";
    $result = mysqli_query($db, $sql);
    confirm_result_set($result);
    $page = mysqli_fetch_assoc($result);
    mysqli_free_result($result);
    return $page; // returns an assoc. array
  }


  function execute_sql_query_with_lock($lock_sql, $sql, $execute_begin=false, $execute_end=false) {
    global $db;
    $result = "";
    if ($execute_begin) {
      //echo "BEGIN";
      if ((!mysqli_autocommit($db,false)) || (!mysqli_query($db, "BEGIN;"))) {
        $_SESSION['message'] = 'database error: cannot start transaction';
        return false;
      } 
    }
    if ($lock_sql != NULL && $lock_sql != "") {
      //echo $lock_sql;
      $result = mysqli_query($db, $lock_sql);
      if (!$result) {
        $_SESSION['message'] = 'database error: cannot lock table';
        return false;
      }
    }

    if ($sql != NULL && $sql != "") {
      //echo $sql;
      $result = mysqli_query($db, $sql);
      if (!$result) {
        $_SESSION['message'] = 'database error: cannot write or update table';
        return false;
      }
    }

    if ($execute_end){
      //echo "COMMIT";
      if (!mysqli_commit($db) || (!mysqli_autocommit($db, true))){
        $_SESSION['message'] = 'database error: cannot commit transaction';
        return false;
      }
    }
    return $result;
  }


?>