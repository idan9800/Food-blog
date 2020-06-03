<?php

require_once 'db_config.php';


if (!function_exists('old')) {

  /**
   *
   * Restore last value to a field
   *
   * @param    string  $fn The field name
   * @return   string
   *
   */
  function old($fn)
  {
    return $_REQUEST[$fn] ?? '';
  }
}

if (!function_exists('csrf')) {
  /**
   *
   * Generate random string for security
   *
   * @return   string
   *
   */
  function csrf()
  {
    $token = sha1(rand(1, 1000) . '$$' . rand(1, 1000) . 'digg');
    $_SESSION['csrf_token'] = $token;
    return $token;
  }
}

if (!function_exists('user_auth')) {

  /**
   *
   * Checking if it's not an impostor
   *
   * @return   boolean
   *
   */

  function user_auth()
  {
    $auth = false;

    if (isset($_SESSION['user_id'])) {

      if (isset($_SESSION['user_ip']) && $_SESSION['user_ip'] == $_SERVER['REMOTE_ADDR']) {


        if (
          isset($_SESSION['user_agent']) &&
          $_SESSION['user_agent'] == $_SERVER['HTTP_USER_AGENT']
        ) {
          $auth = true;
        }
      }
    }


    return $auth;
  }
}

if (!function_exists('email exist')) {

  /**
   *
   * Checking if there is another email like this
   *
   * @param    @var   $link The connection to db
   * @param    @var  $email The cheack if is exist
   * @return   boolean
   *
   */

  function email_exist($link, $email)
  {
    $exist = false;
    $sql = "SELECT email FROM users WHERE email = '$email'";
    $result = mysqli_query($link, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
      $exist = true;
    }

    return $exist;
  }
}