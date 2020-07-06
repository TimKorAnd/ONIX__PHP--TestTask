<?php
    declare(strict_types=1);
    //$sessionName = 'unregisteredUserSession';
    //if user reg form submitted
    if (isset($_POST['userRegistrationSubmit'])):
        $userFullName  = $_SESSION['registeredUserId']['userFullName'] = $_POST['userFirstName'].' '.$_POST['userLastName'];
        $userAge = $_SESSION['registeredUserId']['userAge'] = $_POST['userAge'];
        $sessionName = $_SESSION['registeredUserId']['sessionName'] = hash('sha256', $userFullName.$userAge);
        $_SESSION['registeredUserId']['count'] = $_COOKIE[$sessionName.'count'] ?? 0;
    endif;

    $sessionName = $_SESSION['registeredUserId']['sessionName'] ?? 'unregisteredUserSession';

    session_name($sessionName);
    session_start();

    $count = $_COOKIE[$sessionName.'count'] ?? 0;
    setcookie($sessionName.'count',(String)(++$count),time() + (60*60*24*120) ); //about termins of internature


?>    
<div class="sessionCount">
    <p>session counter for <?=$userFullName ?? ''?>:</p>
    <?php
       /* if (!isset($_SESSION['registeredUserId']['count'])) {
            $_SESSION['registeredUserId']['count'] = 0;
        }
        echo ++$_SESSION['registeredUserId']['count'];*/
        echo $count;

    ?>
</div>
<form id="userRegistration" action = "index.php" method="post">
    <input name = "userFirstName" type="text" value = "">
    <input name = "userLastName" type="text" value = "">
    <input name = "userAge" type="number">
    <input name = "userRegistrationSubmit" type="submit" value = "Submit">
</form>

