<?php
function my_session_start($timeout = 1024) {
  ini_set('session.gc_maxlifetime', $timeout);
  session_start();
  if (isset($_SESSION['timeout_idle']) && $_SESSION['timeout_idle'] < time()) {
    session_destroy();
    session_start();
    session_regenerate_id();
    $_SESSION = array();
  }
  $_SESSION['timeout_idle'] = time() + $timeout;
}
?>
