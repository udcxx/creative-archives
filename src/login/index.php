<?php

    session_start();

    if (isset($_SESSION["isLogin"])) {
        if ($_SESSION["isLogin"]) {
            if (isset($_GET['creativecode'])) {
                header("Location: ../details/?creativecode=".$_GET['creativecode']);                
            } else {
                header("Location: ../");
            }
        }
    }
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>udcxx creative archives</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=BIZ+UDPGothic:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/base.css">
    <link rel="stylesheet" href="../assets/common.css">
    <link rel="stylesheet" href="../assets/page-login.css">
    <script src="../assets/functions.js" async></script>
</head>
<body>
    <div class="box">
        <a onclick="history.back();">&laquo; 戻る</a>
        <h2>ログインページ</h2>
        <p>受け取った認証コードを入力し、ログインをクリックしてください</p>
        <form action="./certification.php" method="post">
            <input type="text" name="code" class="input--code">
            <p class="error"></p>
            <input type="text" name="creativecode" value="0" hidden>
            <button type="submit">ログイン</button>
        </form>
    </div>

    <script>
        const params = location.search.substring(1).split('&');

        params.forEach((pram) => {
            const item = pram.split('=');
            if (item[0] === 'creativecode') {
                document.getElementsByName('creativecode')[0].value = item[1];
            } else if (item[0] === 'error') {
                const errorEl = document.querySelector('.error')
                if (item[1] === '1') {
                    errorEl.innerText = 'データベースの接続に失敗しました。再度お試しください。(code: 1)'
                } else if (item[1] === '2') {
                    errorEl.innerText = '認証コードの有効期限が切れています。(code: 2)'
                } else if (item[1] === '3') {
                    errorEl.innerText = '認証コードが間違っている、または、有効期限が切れています。(code: 3)'
                }
            }
        });
    </script>
</body>
</html>