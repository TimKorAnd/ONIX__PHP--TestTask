<?php
    declare(strict_types=1);
    date_default_timezone_set('Europe/Uzhgorod');

    define('COOKIE_LIFETIME', time() + (60*60*24*120)); //roughly termin of internature
    $userFullName = '';

    //if user reg form submitted
    if (isset($_POST['userRegistrationSubmit'])):
        $userFullName  = $_SESSION['registeredUserId']['userFullName'] = $_POST['userFirstName'].' '.$_POST['userLastName'];
        $userAge = $_SESSION['registeredUserId']['userAge'] = $_POST['userAge'];
        $sessionName = $_SESSION['registeredUserId']['sessionName'] = hash('sha256', $userFullName.$userAge);
        //$_SESSION['registeredUserId']['count'] = $_COOKIE[$sessionName]['count'] ?? 0;
    endif;

    $sessionName = $_SESSION['registeredUserId']['sessionName'] ?? 'unregisteredUserSession';

    session_name($sessionName);
    session_start();

    $visitCounter = $_COOKIE[$sessionName]['count'] ?? 0;
    $lastVisitTime = $_COOKIE[$sessionName]['lastVisitTime'] ?? '';
    $date = new DateTime();
    setcookie($sessionName."[count]",(string)(++$visitCounter), COOKIE_LIFETIME);
    setcookie($sessionName."[lastVisitTime]",$date->format("d/m/Y H:i:s"), COOKIE_LIFETIME);


    /**
     * @param int $visitCounter quantity of visits
     * @return string 'a' for "раза" or '' for "раз"
     */
    function getEnding(int $visitCounter): string
    {
        $strCount = (string)$visitCounter;
        if (isLastDigitCausingEndingOnA($strCount)) {
            if (($visitCounter < 5) || !isLastCausingNumberInSecondTen($strCount) ) {
                return 'а';
            }
        }
        return '';
    }

    /**
     *
     * @param int $visitCounter
     * @param string $strCount
     * @return bool
     */
    function isLastCausingNumberInSecondTen(string $strCount): bool
    {
        return (substr($strCount, -2, 1) == '1');
    }

    /**
     * @param string $strCount
     * @return bool
     */
    function isLastDigitCausingEndingOnA(string $strCount): bool
    {
        define('LINE_OF_DIGITS_ENDING_FOR_A', '234');
        return strpos(LINE_OF_DIGITS_ENDING_FOR_A, substr($strCount, -1)) !== false;
    }

?>
<div class="sessionCount">
    <?php if (!empty($userFullName)):?>
        <p>Вы <?=$userFullName?> и вы родились в
            <?=($date->sub(new DateInterval("P".$userAge."Y"))->format("Y"))?> году.</p>
    <?php endif;?>
    <p>Вы здесь уже <?=$visitCounter?> раз<?=getEnding($visitCounter)?>.</p>
    <?php if (!empty($lastVisitTime)):?>
        <p>Ваш последний визит был <?=$lastVisitTime?> .</p>
    <?php endif;?>

</div>
<form id="userRegistration" action = "index.php" method="post">
    <fieldset>
        <legend>Форма для идентификации:</legend>
        <label for="userFirstName">Введите имя:</label>
        <input id="userFirstName" name = "userFirstName" type="text" value = "" required>
        <label for="userLastName">Введите фамилию:</label>
        <input id = "userLastName" name = "userLastName" type="text" value = "" required>
        <label for="userAge">Введите возраст:</label>
        <input id = "userAge" name = "userAge" type="number" min="0" required>
        <input name = "userRegistrationSubmit" type="submit" value = "Submit">
    </fieldset>
</form>

