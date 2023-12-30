<?php

    session_start();

    $data_souce = "public";
    $is_login = '<a href="./login/" class="is_secret_mode">ログイン</a>';

    if (isset($_SESSION["isLogin"])) {
        if ($_SESSION["isLogin"]) {
            $data_souce = "private";
            $is_login = '<p class="is_secret_mode">シークレットモードで表示中</p>';
        }
    }

    $data = file_get_contents("./data/".$data_souce.".json");
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
    <link rel="stylesheet" href="./assets/base.css">
    <link rel="stylesheet" href="./assets/common.css">
    <link rel="stylesheet" href="./assets/page-top.css">
    <script src="./assets/functions.js" async></script>
</head>
<body>
    <div class="sidebar">
        <div class="header">
            <a href="https://app.udcxx.me/">
                <img src="https://blog.udcxx.me/_nuxt/logo.85a8d4fc.png" class="header--image">
            </a>
            <h1>udcxx creative archives</h1>
            <?php echo $is_login; ?>
        </div>
        <div class="filter">
            <div class="secret filter--item">
                <p class="filter--name">🔓 シークレット</p>
                <div class="toggleselect--box">
                    非表示
                    <input type="checkbox" id="secret" class="toggleselect" data-content="PRIVATE" checked>
                    <label for="secret" class="toggleselect--label"></label>
                    表示
                </div>
            </div><!-- / secret -->
            <div class="tags filter--item">
                <p class="filter--name">🔖 タグ</p>
                <input type="checkbox" id="Static" class="multiselect" data-content="静的ページ" checked>
                <label for="Static" class="multiselect--label">静的ページ</label>
                <input type="checkbox" id="Nuxt" class="multiselect" data-content="Nuxt" checked>
                <label for="Nuxt" class="multiselect--label">Nuxt</label>
                <input type="checkbox" id="Wordpress"  class="multiselect" data-content="Wordpress" checked>
                <label for="Wordpress" class="multiselect--label">Wordpress</label>
                <input type="checkbox" id="Wix" class="multiselect" data-content="Wix" checked>
                <label for="Wix" class="multiselect--label">Wix</label>
                <input type="checkbox" id="EC" class="multiselect" data-content="EC" checked>
                <label for="EC" class="multiselect--label">EC</label>
                <input type="checkbox" id="motion" class="multiselect" data-content="動作がポイント" checked>
                <label for="motion" class="multiselect--label">動作がポイント</label>
                <input type="checkbox" id="animation" class="multiselect" data-content="アニメーションがポイント" checked>
                <label for="animation" class="multiselect--label">アニメーションがポイント</label>
            </div><!-- / tags -->
            <div class="category filter--item">
                <p class="filter--name">🎈 種別</p>
                <input type="checkbox" id="Web" class="multiselect" data-content="Webサイト" checked>
                <label for="Web" class="multiselect--label">Webサイト</label>
                <input type="checkbox" id="LP"  class="multiselect" data-content="LP" checked>
                <label for="LP" class="multiselect--label">LP</label>
                <input type="checkbox" id="Tool" class="multiselect" data-content="Webツール" checked>
                <label for="Tool" class="multiselect--label">ツール</label>
                <!-- <input type="checkbox" id="Program" class="multiselect" checked><label for="Program" class="multiselect--label">プログラム開発</label> -->
            </div><!-- / category -->
            <div class="size filter--item">
                <p class="filter--name">📱 画面サイズ</p>
                <input type="checkbox" id="PC" class="multiselect" data-content="PC" checked>
                <label for="PC" class="multiselect--label">PC</label>
                <input type="checkbox" id="SP"  class="multiselect" data-content="SP" checked>
                <label for="SP" class="multiselect--label">SP</label>
                <input type="checkbox" id="Responsive" class="multiselect" data-content="レスポンシブ" checked>
                <label for="Responsive" class="multiselect--label">レスポンシブ</label>
                <input type="checkbox" id="PC/SP"  class="multiselect" data-content="PC/SP" checked>
                <label for="PC/SP" class="multiselect--label">PC/SP</label>
            </div><!-- / size -->
        </div><!-- / filtter -->
    </div><!-- /sidebar -->

    <main>
        <p class="main--details">だいちゃん（udcxx）がこれまでに制作したクリエイティブの一覧です</p>
        <div class="list_area"></div>
    </main>

    <script>
        let dataSet = <?php echo $data; ?>;
        window.onload = () => {
            init();
        }
    </script>
</body>
</html>