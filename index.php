<?php 
$Pagetitle = 'Домашно 5 | index.php'; //промелива за html tag TITLE
$h1_tag = 'Домашно номер 5';  //промелнива за H1 tag
$background = 'img/bg.jpg'; // променлива за пътя до background-image
$style = 'styles/styles.css'; //промелнива за пътя до файла с стиловете
include 'includes/heder.php'; //Всички горни промелниви се използват в header.php и управляват изгледа на страницата
include 'includes/connection.php'; // Свързваме се към базата данни
include 'includes/functions.php';
?>
<a href="addbook.php" style="text-decoration: none;"><input type="button" value="Добави книга" /></a>
<a href="addauthor.php" style="text-decoration: none;"><input type="button" value="Добави aвтор" /></a>
<?php
if (isset($_SESSION['isLogged']) && $_SESSION['isLogged'] == TRUE) {
    echo '<a href="logout.php" style="text-decoration: none;"><input type="button" value="Изход" /></a>';
} else {
     echo '<a href="login.php" style="text-decoration: none;"><input type="button" value="Вход" /></a>';
}
if (isset($_GET['author-id'])) {
    $book_author_id = trim(urldecode($_GET['author-id']));
    $query_check = 'SELECT author_name FROM authors WHERE author_id="' . $book_author_id . '"';
    $result = mysqli_query($connection, $query_check);
    $order='DESC'; // TO DO - да взема тази променлива от GET параметър!!!!
    if (mysqli_num_rows($result) == 0) {
        echo '<h4>Несъществуващ автор</h4> <br> Премини към <a href="index.php">всички автори</a>';
        exit;
    }
    $query = mysqli_query($connection, '
            SELECT * FROM books_authors AS ba
            INNER JOIN books AS b ON b.book_id=ba.book_id
            INNER JOIN books_authors AS bba ON bba.book_id=ba.book_id
            INNER JOIN authors AS a ON bba.author_id=a.author_id
            WHERE ba.author_id=  "' . $book_author_id . '"
            ORDER BY book_title '.$order.'            
            ');
    echo '<a href="index.php" style="text-decoration: none;"><input type="button" value="Всички книги" /></a>';
} else {
    $query = mysqli_query($connection, '
        SELECT a.author_name, b.book_title, b.book_id, a.author_id
        FROM authors as a
        INNER JOIN books_authors as ba
        ON a.author_id=ba.author_id        
        INNER JOIN books as b
        on b.book_id = ba.book_id 
        ');
}
$result = array();
while ($row = mysqli_fetch_assoc($query)) {
    //echo '<pre>'.print_r($row, true).'</pre>';
    $result[$row['book_id']]['book_title'] = $row['book_title'];
    $result[$row['book_id']]['authors'][$row['author_id']] = $row['author_name'];
    //$result[$row['book_id']]['author_id'][] = $row['author_name'];
}
    //echo '<pre>'. print_r($result, TRUE) .'</pre>';
echo '
    <table>
    <colgroup>
     <col width="40%" />
     <col width="60%" />
    </colgroup>
    <thead>
         <tr>
            <td>Книга</td> 
            <td>Автори</td>
        </tr>
    </thead>
    '; // TO DO  в td Книга да добавя сортиране от сорта:  <a href=?order=АSC>Сортирай</a> !!! 
foreach ($result as $key => $value) {
    $result_authors = array();
    echo '<tr><td><a href="book.php?book-id=' . $key . '" name="' . $value['book_title']. '"
        title= "'.$value['book_title'].'">' . $value['book_title'] . '</a></td><td>';
    foreach ($value['authors'] as $key2 => $value2) {
        $result_authors[] = '<a href="?author-id=' . $key2 . '" name="' . $value2 . '" title=" '.$value2.'">' . $value2 . '</a>';
    }
    echo implode(', ', $result_authors);
}
echo'</td></tr></table>';
include 'includes/footer.php';
?>