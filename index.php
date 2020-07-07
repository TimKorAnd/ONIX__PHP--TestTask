<?php
    declare(strict_types=1);
    date_default_timezone_set('Europe/Uzhgorod');

    define('COOKIE_LIFETIME', time() + (60*60*24*120)); //roughly termin of internship
    $userFullName = '';

    //if user reg form submitted
    if (isset($_POST['userRegistrationSubmit'])):
        $userFullName  = $_SESSION['registeredUserId']['userFullName'] = $_POST['userFirstName'].' '.$_POST['userLastName'];
        $userAge = $_SESSION['registeredUserId']['userAge'] = $_POST['userAge'];
        $sessionName = $_SESSION['registeredUserId']['sessionName'] = hash('sha256', $userFullName.$userAge);
    endif;

    $sessionName = $_SESSION['registeredUserId']['sessionName'] ?? 'unregisteredUserSession';

    session_name($sessionName);
    session_start();

    $visitCounter = $_COOKIE[$sessionName]['count'] ?? 0;
    $lastVisitTime = $_COOKIE[$sessionName]['lastVisitTime'] ?? '';
    $date = new DateTime();
    setcookie($sessionName."[count]",(string)(++$visitCounter), COOKIE_LIFETIME);
    setcookie($sessionName."[lastVisitTime]",$date->format("d/m/Y H:i:s"), COOKIE_LIFETIME);


    /** Get ending depending times and russian languages rules
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
     * Checks is last number (of two digits) is in second ten? (twelve, thirteen, fourteen)
     * @param string $strCount
     * @return bool true if number of last two digits in range [12-14]
     */
    function isLastCausingNumberInSecondTen(string $strCount): bool
    {
        return (substr($strCount, -2, 1) == '1');
    }

    /** Checks is last digit from list of causing "a"-ending
     * @param string $strCount
     * @return bool true if may causing "a"-ending, and vice versa
     */
    function isLastDigitCausingEndingOnA(string $strCount): bool
    {
        define('LINE_OF_DIGITS_ENDING_FOR_A', '234');
        return strpos(LINE_OF_DIGITS_ENDING_FOR_A, substr($strCount, -1)) !== false;
    }

?>
<!DOCTYPE html>
<html lang="ru">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>ONYX Intership Test Task</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="test task for ONYX internship by timkorand@gmail">
    <link rel="stylesheet"
          href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css"
          integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk"
          crossorigin="anonymous">
    <style>
        input {
            display: block;
            margin-bottom: 20px;
            width: 25em;
        }

        input:invalid:required {
            box-shadow: 0 0 5px 1px red;
        }

        input:valid {
            box-shadow: 0 0 5px 1px green;
        }

        .times {
            color: red;
            font-weight: bold;
        }
        .dateVisit {
            color: green;
            font-weight: lighter;
        }
    </style>
</head>

<body data-gr-c-s-loaded="true" class="">
<div class="sessionCount container">
    <?php if (!empty($userFullName)):?>
        <p>Вы <?=htmlspecialchars($userFullName)?> и вы родились в
            <?php try {
                echo ($date->sub(new DateInterval("P" . $userAge . "Y"))->format("Y"));
            } catch (Exception $e) {
            } ?> году.</p>
    <?php endif;?>
    <p>Вы здесь уже <span class="times"><?=$visitCounter?></span> раз<?=getEnding($visitCounter)?>.</p>
    <?php if (!empty($lastVisitTime)):?>
        <p>Ваш последний визит был <span class="dateVisit"><?=$lastVisitTime?></span>.</p>
    <?php endif;?>

    <form id="userRegistration" action = "index.php" method="post">
        <fieldset>
            <legend>Форма для идентификации:</legend>
            <label for="userFirstName">Введите имя:</label>
            <input id="userFirstName"
                   name = "userFirstName"
                   type="text"
                   value = ""
                   pattern="[a-zA-Zа-яА-Я]*"
                   placeholder="только буквы, минимально одна"
                   autofocus
                   required>
            <label for="userLastName">Введите фамилию:</label>
            <input id = "userLastName"
                   name = "userLastName"
                   type="text"
                   value = ""
                   pattern="[a-zA-Zа-яА-Я]*"
                   placeholder="только буквы, минимально одна"
                   required>
            <label for="userAge">Введите возраст:</label>
            <input id = "userAge"
                   name = "userAge"
                   type="number"
                   min="0"
                   placeholder="только цифры, минимум одна" required>
            <input name = "userRegistrationSubmit" type="submit" value = "Идентифицировать">
        </fieldset>
    </form>
</div>
</body>
</html>

