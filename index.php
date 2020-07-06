<?php
    declare(strict_types=1);
    //define('SESSION_NAME', 'ONYXTestTask');
    if (isset($_POST['userRegistrationSubmit'])):
        $_SESSION['registeredUserId']['sessionName'] = $_POST['userFirstName'].$_POST['userLastName'].$_POST['userAge'];
    endif;
    $sessionName = isset($_SESSION['registeredUserId']) ?
      $_SESSION['registeredUserId']['sessionName'] :
      'unregisteredUserSession';

    session_name($sessionName);
    session_start();
?>    
<div class="sessionCount">
    <p>session counter:</p>
    <?php
        if (!isset($_SESSION['count'])) {
            $_SESSION['count'] = 0;
        }
        echo ++$_SESSION['count'];
    ?>
</div>
<form id="userRegistration" action = "index.php" method="post">
    <input name = "userFirstName" type="text" value = "">
    <input name = "userLastName" type="text" value = "">
    <input name = "userAge" type="number">
    <input name = "userRegistrationSubmit" type="submit" value = "Submit">
</form>

