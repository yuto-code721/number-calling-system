<?php
$filename = "number.txt";
$speakerfile = "speaker.txt";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $action = $_POST["action"] ?? "";
    $num    = $_POST["number"] ?? "";
    $speaker = $_POST["speaker"] ?? "";

    // 呼び出し（番号追加）
    if ($action === "call") {

        // speaker を保存（空の場合は 0 を入れる）
        if ($speaker === "") $speaker = "0";
        file_put_contents($speakerfile, $speaker);

        // 既存番号読み込み
        $old = "";
        if (file_exists($filename)) {
            $old = trim(file_get_contents($filename));
        }

        // カンマ区切りで追加
        if ($old === "") {
            $new = $num;
        } else {
            $new = $old . "," . $num;
        }

        file_put_contents($filename, $new);
        echo "CALLED";
        exit;
    }

    // 受け取り完了（指定番号を削除）
    if ($action === "clear") {
        if (file_exists($filename)) {
            $list = explode(",", trim(file_get_contents($filename)));
            $list = array_filter($list, function($n) use ($num) { return $n !== $num; });
            file_put_contents($filename, implode(",", $list));
        }
        echo "CLEARED";
        exit;
    }

} else {
    // GET → 番号一覧を返す
    if (file_exists($filename)) {
        echo file_get_contents($filename);
    } else {
        echo "";
    }
}