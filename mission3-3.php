<!-- ミッション3-2 -->
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>3-3</title>
</head>
<body>

<h1>WEB掲示板 投稿一覧の表示機能付き</h1>

<form action="mission3-3.php" method="POST">
    名前：<input type="text" name="name" placeholder="名前を入力"><br>
    コメント：<input type="text" name="comment" placeholder="コメントを入力"><br>
    <input type="submit" value="送信"><br>
    <input type="text" placeholder="削除番号" name="delete">
    <input type="submit" value="削除">
</form>

<h3>▼投稿一覧▼</h3>
<hr>

<?php   //php領域

/*issetという関数は、()の中身に、データがセットされていれば真を返し、
データがなければ偽になって処理が実行されないという性質を持つ▼*/
if(isset($_POST['name'], $_POST['comment']))
{
    $name = $_POST['name'];         //formから送られてきた情報を代入
    $comment = $_POST['comment'];   //formから送られてきた情報を代入
    $timestamp = date("Y年m月d日 H:i:s");   //年月日と現在時刻を取得して代入
    $datafile = "mission3-3.txt";      //データを記録するファイルの名前を指定

    /*フォーム入力されたデータをPHPで受け取り、テキストファイルに、
    1送信ごとに1行ずつ、投稿番号と日時を添えて保存▼*/
    if($name == '' || $comment == '')   //名前とコメントが無い時は
    {
        exit;                           //動作しない
    }
    elseif(!(file_exists($datafile)))   //指定したファイルが存在しない場合は
    {
        $fp = fopen($datafile ,"a");    //ファイルを作成
        fwrite($fp , 1 . '<>' . $name . '<>' . $comment . '<>' . $timestamp . "\r\n");  //1行目に「投稿番号」「名前」「コメント」「日時」を記入して改行
        fclose($fp );
    }
    else
    {
        $line_count = count(file($datafile));    //ファイルの行数を数えて代入
        $post_number = $line_count + 1;          //投稿番号の設定
        $fp = fopen($datafile ,"a");        //追記保存
        fwrite($fp , $post_number . '<>' .  $name . '<>' . $comment . '<>' . $timestamp . "\r\n");  //2行目以降に「投稿番号」「名前」「コメント」「日時」を記入して改行
        fclose($fp );
    }


    //▼テキストファイルをPHPで配列として読み込み、1行ごとに改行してブラウザに表示させる
    $comment_library = file($datafile) ;        //改行区切りでtxtを配列として読み込み
    foreach($comment_library as $comment_list)  //配列の繰り返し処理
    {
        $line = explode("<>" , $comment_list);    //区切り文字を<>とする
        foreach($line as $word)     //配列の繰り返し処理(入れ子)
        {
            echo $word . ' ';       //配列を1行に出力。半角スペースで区切る
            
            if(isset($_POST['delete'])){
                $delete = $_POST['delete'];    //formから送られてきたdelete番号を代入
                $fp = fopen($datafile,'a');
            if($delete == $post_number){
                unset($comment_library[$delete]);
            }

    }
        }
        echo '<br>';                //配列を出力し終わったら改行
        echo '<hr style="border:none;border-top:dashed 1px #d3d3d3;height:1px;">';  //水平線（点線）挿入
    }

    echo '<hr>';    //水平線挿入
    echo '<h3>▼参考：配列の中身はこうなっています</h3>';
    echo('<pre>');  //var_dumpを改行して表示させるため
    var_dump($comment_library); //現時点での配列の中身を表示
    echo('</pre>');
}

?>

</body>
</html>
