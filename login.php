<?php
$Pagetitle = 'Вход | login.php'; //промелива за html tag TITLE
$h1_tag = 'Вход в страницата';  //промелнива за H1 tag
$background = 'img/bg.jpg'; // променлива за пътя до Background фона
$style = 'styles/styles.css'; //промелнива за пътя до файла с стиловете
include 'includes/heder.php'; //Всички горни промелниви се използват в header.php и управляват изгледа на страницата
include 'includes/connection.php';
if (isset($_SESSION['isLogged']) && $_SESSION['isLogged'] == TRUE) {
    echo 'За изход от сесията моля използвайте бутона -> <a href="logout.php" style="text-decoration: none;"><input type="button" value="Изход" /></a>';
} else {

    if ($_POST) {
        $username = trim($_POST['username']); // Взимаме въведение потребителско име и парола от формата и махаме интервалите
        $password = trim($_POST['password']);
        
        $LoginChek = mysqli_query($connection, 'SELECT username, password, user_id, realname FROM users');
        echo '<pre>'. print_r($LoginChek, TRUE) .'</pre>';
        if (mysqli_num_rows($LoginChek) > 0) { // Проверка дали в базата данни има въобще данни
            while ($row = $LoginChek->fetch_assoc()) {
                if ($username == $row['username'] && $password == $row['password']) { // Проверяваме за правилно въведено име и парола
                    $_SESSION['isLogged'] = TRUE;
                    $_SESSION['user_id'] = $row['user_id']; // В сесията записваме потребителското име защото ще достъпваме папката
                    $_SESSION['username'] = $row['username']; // В сесията записваме потребителското име защото ще достъпваме папката
                    $_SESSION['realname'] = $row['realname']; //   ... и истинското име въведено при регистрацията за да се обърнем персонално към потребителя
                    header('Location: index.php'); // Пренасочваме към страницата с книги
                    exit;
                } else {
                    echo '<h4>Грешно име или парола</h4>';
                }
            }
        } else {
            echo '<h4>Моля регистрирайте поне 1 потребител</h4>'; // Ако в базата данни няма данни - няма регистрирани потребители го вадим като съобщение
        }
    }
    ?>
    <form method="POST">
        <div>Име: <br><input type="text" name="username" required="required"/></div>
        <div>Парола: <br><input type="password" name="password" required="required" /></div>
        <div><input type="submit" value="Вход"></div>
    </form>
    <a href="registration.php" style="text-decoration: none;"><input type="button" value="Регистрация" /></a>
    <a href="index.php" style="text-decoration: none;"><input type="button" value="Книги" /></a>
    <?php
}
include 'includes/footer.php';
?>