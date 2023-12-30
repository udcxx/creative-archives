<?php

// デバッグ用
// ini_set('display_errors', 1);

require_once ('../vars.php');

$opts = array(
    'http'=>array(
        'method'=>"GET",
        'header'=>"Accept-language: ja\r\n" .
        "X-Cybozu-API-Token:" .KINTONE_APPTOKEN. "\r\n"
    )
);

$context = stream_context_create($opts);

$file = file_get_contents('https://'.KINTONE_DOMAIN.'.cybozu.com/k/v1/records.json?app='.KINTONE_APPID.'&fields[0]=CREATIVE_CODE&fields[1]=CREATIVE_TAG&fields[2]=CREATIVE_CATEGORY&fields[3]=CREATIVE_PUBLIC&fields[4]=CREATIVE_REQUESTER&fields[5]=CREATIVE_TITLE&fields[6]=CREATIVE_URL&fields[7]=CREATIVE_TEXT&fields[8]=CREATIVE_SIZE', false, $context);

if ($file === false) {
    // Kintone接続エラー
    header("Location: ./?error=4");
}

// 取得したデータの文字コードをエンコード
$json = mb_convert_encoding($file, "UTF8", 'ASCII,JIS,UTF-8,EUC-JP,SJIS-WIN');

// 取得したデータをそのままJSONとして保存
file_put_contents('../data/private.json', $json);


// 取得したデータをPHPの配列に変換
$public_data = json_decode($json, true);

// 公開したくない値を非表示にする
for ($i = 0; $i < count($public_data['records']); $i++) {
    if ($public_data['records'][$i]['CREATIVE_PUBLIC']['value'] == 'PRIVATE') {
        $public_data['records'][$i]['CREATIVE_URL']['value'] = '***';
        $public_data['records'][$i]['CREATIVE_TEXT']['value'] = '***';
        $public_data['records'][$i]['CREATIVE_REQUESTER']['value'] = '***';
        $public_data['records'][$i]['CREATIVE_TITLE']['value'] = 'シークレット';
    };
};

// 配列をJSONに変換
$public_data = json_encode($public_data);
file_put_contents('../data/public.json', $public_data);

header("Location: ./?update=success");
?>