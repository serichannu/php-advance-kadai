<?php
$dsn = 'mysql:dbname=php_book_app;host=localhost;charset=utf8mb4';
$user = 'root';
$password = '';

if (isset($_POST['submit'])) {
    try {
        $pdo = new PDO($dsn, $user, $password);

        $sql_insert = '
        INSERT INTO books (book_code, book_name, price, stock-quantity, genre-code)
        VALUES (:book_code, :book_name, :price, :stock-quantity, :genre-code)
        ';

        $stmt_insert = $pdo->prepare($sql_insert);

        $stmt_insert->bindValue(':book_code', $_POST['book_code'], PDO::PARAM_INT);
        $stmt_insert->bindValue(':book_name', $_POST['book_name'], PDO::PARAM_STR);
        $stmt_insert->bindValue(':price', $_POST['price'], PDO::PARAM_INT);
        $stmt_insert->bindValue(':stock-quantity', $_POST['stock-quantity'], PDO::PARAM_INT);
        $stmt_insert->bindValue(':genre-code', $_POST['genre-code'], PDO::PARAM_INT);
    
        $stmt_insert->execute();

        $count = $stmt_insert->rowCount();

        $message = "書籍を{$count}件登録しました";

        header("Location: read.php?message={$message}");
    } catch (PDOException $e) {
        exit($e->getMessage());
    }
}

try {
    $pdo = new PDO($dsn, $user, $password);

    $sql_select = "SELECT genre_code FROM genres";

    $stmt_select = $pdo->query($sql_select);

    $genre_codes = $stmt_select->fetchAll(PDO::FETCH_COLUMN);
} catch (PDOException $e) {
    exit($e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> 書籍登録</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP&display=swap" rel="stylesheet">    
</head>
<body>
    <header>
        <nav>
            <a href="index.php">書籍管理アプリ</a>
        </nav>
    </header>
    <main>
        <article class="registration">
            <h1>書籍登録</h1>
            <div class="back">
                <a href="read.php" class="btn">&lt; 戻る</a>
            </div>
            <form action="create.php" method="post" class="registration-form">
                <div>
                    <label for="book-code">書籍コード</label>
                    <input type="number" name="book-code" min="0" max="100000000" required>

                    <label for="book-name">書籍名</label>
                    <input type="text" name="book-name" maxlength="50" required>

                    <label for="price">値段</label>
                    <input type="number" name="price" min="0" max="100000000" required>

                    <label for="stock-quantity">在庫数</label>
                    <input type="number" name="stock-quantity" min="0" max="100000000" required>

                    <label for="genre-code">ジャンルコード</label>
                    <select name="genre-code" required>
                        <option disabled selected value>選択してください</option>
                        <?php
                        foreach ($genre_codes as $genre_code) {
                            echo "<option value='{$genre_code}'>{$genre_code}</option>";
                        }
                        ?>
                </div>
                <button type="submit" class="submit-btn" name="submit" value="create">登録</button>
            </form>

        </article>
    </main>
    <footer>
        <p class="copyright">&copy; 書籍管理アプリ All right reserved.</p>
    </footer>

</body>
</html>