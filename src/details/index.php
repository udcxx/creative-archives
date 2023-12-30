<?php
    session_start();

    $data_souce = "public";

    if (isset($_SESSION["isLogin"])) {
        if ($_SESSION["isLogin"]) {
            $data_souce = "private";
        }
    }

    $data = file_get_contents("../data/".$data_souce.".json");
    $data = mb_convert_encoding($data, 'UTF8', 'ASCII,JIS,UTF-8,EUC-JP,SJIS-WIN');
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
    <link rel="stylesheet" href="../assets/page-details.css">
    <script src="../assets/functions.js" async></script>
</head>
<body>
    <div class="sidebar close">
        <div class="header">
            <a href="https://app.udcxx.me/">
                <img src="https://blog.udcxx.me/_nuxt/logo.85a8d4fc.png" class="header--image">
            </a>
            <h1>udcxx creative archives</h1>
        </div><!-- /header -->

        <div class="info">
            <div class="size info--item">
                <p class="info--name">📱 画面サイズ</p>
                <div class="toggleselect--box">
                    PC
                    <input type="checkbox" id="size" class="toggleselect" checked>
                    <label for="size" class="toggleselect--label"></label>
                    SP
                </div>
            </div><!-- / secret -->
            <div class="tags info--item">
                <p class="info--name">🔖 タグ</p>
            </div><!-- / tags -->
            <div class="category info--item">
                <p class="info--name">🎈 種別</p>
                
            </div><!-- / category -->
        </div>

        <div class="sidebar_togglebutton close">
            <img src="../assets/close.svg" class="sidebar_togglebutton--close_icon">
            <img src="../assets/open.svg" class="sidebar_togglebutton--open_icon">
        </div>
    </div><!-- /sidebar -->

    <main>
        <a href="../" class="main--back">&laquo; 一覧に戻る</a>
        <div class="main--details">
            <p class="main--requester"></p>
            <p class="main--title"></p>
            <p class="main--url"></p>
            <p class="main--text"></p>
        </div>
        
        <div class="main--image image--pc"></div>
        <div class="main--image image--sp"></div>
    </main>

    <script>
        const params = location.search.substring(1).split('&');
        let creativecode = 0;

        params.forEach((pram) => {
            const item = pram.split('=');
            if (item[0] === 'creativecode') {
                creativecode = item[1]
            }
        });

        let dataSet = <?php echo $data; ?>;
        window.onload = () => {
            initDetails(creativecode);
        }
    </script>
</body>
</html>