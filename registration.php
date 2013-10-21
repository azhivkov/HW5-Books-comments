<?php
$Pagetitle = 'Регистрация | registration.php'; //промелива за html tag TITLE
$h1_tag = 'Регистрация на нов потребител';  //промелнива за H1 tag
$background = 'img/bg.jpg'; // променлива за пътя до background-image
$style = 'styles/styles.css'; //промелнива за пътя до файла с стиловете
include 'includes/heder.php'; //Всички горни промелниви се използват в header.php и управляват изгледа на страницата
include 'includes/connection.php'; // Свързваме се към базата данни
$error = FALSE;
if (@$_SESSION['isLogged'] == TRUE) { // Проверка дали сесията на потребителя е активна
    header('Location: index.php'); // Пренасочваме към страницата с коментари
} else {
    if ($_POST) {
        $realname = trim($_POST['name']);
        $realname = strip_tags($realname);
        $username = $_POST['username'];
        $password1 = $_POST['password1'];
        $password2 = $_POST['password2'];
        if (preg_match('/[^a-z0-9]+/', $username)) {
            echo '<h4>Потребителското съдържа непозволени символи - въведете само малки букви и цифри</h4>';
            $error = TRUE;
        }
        if (mb_strlen($username) < 5) { // Проверка за дължина на името 
            echo '<h4>Потребителското име е прекалено късо</h4>';
            $error = TRUE;
        }
        if (($password1 != $password2)) {
            echo '<h4>паролите не съвпадат !</h4>';
            //    header('Location: registration.php'); // Пренасочваме отново към регистрация.
            $error = TRUE;
        } else {
            @$password = $password1;
        }
        if (preg_match('/[^A-Za-z0-9]+/', $password)) {
            echo '<h4>Паролата съдържа непозволени символи - въведете само главни, малки букви и цифри</h4>';
            $error = TRUE;
        }
        if (mb_strlen($password1) < 5) { // Проверка за дължина на името 
            echo '<h4>Паролата е прекалено къса</h4>';
            $error = TRUE;
        }

        $query = mysqli_query($connection, 'SELECT username FROM users WHERE username="' . $username . '"');
        if (mysqli_num_rows($query) > 0) {
            echo '<h4>Въведеното от Вас потрбителско име (' . $username . ') вече съществува в БД</h4>';
            $error = TRUE;
        }
        if (!$error) {
            $username = mysqli_real_escape_string($connection, $username);
            $password = mysqli_real_escape_string($connection, $password);
            $realname = mysqli_real_escape_string($connection, $realname);
            $query = 'INSERT INTO users (username,password,realname)
        VALUES("' . $username . '","' . $password . '","' . $realname . '")';
            mysqli_query($connection, $query);
            echo '<h4>Регистрирахте се успешно!</h4> <h3> данни за вход: </h3> 
                потребителско име: <b>' . $username . '</b> <br>парола: <b>' . $password . '</b> <br>';
            echo '<a href="login.php" style="text-decoration: none;"><input type="button" value="Вход" /></a>';
            exit;
        } else {
            echo '<h4>Неуспешна регистрация - възникна грешка при записа на данните Ви.</h4>';
        }
    }
    ?>
    <form method="POST">

        <div>Име и Фамилия: <br><input type="text" name="name" placeholder="Вашето истинско име" required="required"/></div>
        <div>Потребителско име: <br><input type="text" name="username" placeholder="малки букви и цифри" required="required"/></div>
        <div>Парола: <br><input type="password" name="password1" placeholder="малки/големи букви и цифри" required="required" /></div>
        <div>Повтори парола:<br><input type="password" name="password2" placeholder="повтори парола" required="required" /></div>
        <div><input type="submit" value="Регистрация"></div>
    </form>
    <p>
        Потребителско Име трябва да е минимум 5 символа изписано само на латиница с малки букви и/или цифри <br>
        Паролата трябва да е минимум 5 символа и може да включва малки и големи латински букви и цифри<br>

        <a href="login.php" style="text-decoration: none;">Вече си се регистрил - влез от тук!</a>

        <a href="login.php" style="text-decoration: none;"><input type="button" value="Вход" /></a>
    </p>
    <?php
}
include 'includes/footer.php';
?>