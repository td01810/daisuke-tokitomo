<?php
session_start();
//データベースに接続
$dsn='';

$user='';

$password='';

$pdo=new PDO($dsn,$user,$password);

//テーブルの作成

$sql = "CREATE TABLE touroku2"

."("

."id text,"

."name char(32),"

."password TEXT"

.");";

$stmt = $pdo->query($sql);

$stmt = $pdo->query('SET NAMES utf8');//文字化け対策
//$pdo = null;

 //テーブル一覧を表示する
/*$pdo=new PDO($dsn,$user,$password);

$sql='SHOW TABLES;';

$result=$pdo->query($sql);

foreach($result as $row){

	echo $row[0];

	echo '<br>';

}

echo "<hr>";*/
//テーブルpblog作成ok

//テーブルのカラム型を確認
 //テーブル一覧を表示する
/*$sql='SHOW CREATE TABLE touroku2;';

$result = $pdo->query($sql);

foreach($result as $row){

	print_r($row);
}*/
//カラム型確認ok

//投稿番号をつける
$stmt = $pdo -> query("SELECT * FROM pblog");

$count = $stmt -> rowCount();

$id= $count +1;

//入力された名前を$nameとして変数に入れる
$name = $_POST["name"];

//入力されたコメントを$messageとして変数に入れる
$message = $_POST["message"];

//日時の取得
$time = date("Y/n/j G:i");

//入力されたパスワードを$passとして変数に入れる
$pass = $_POST["pass"];

//入力された削除指定番号を$delete_numとして変数に入れる
$delete_num = $_POST["delete_num"];
//echo $delete_num;

//入力された編集指定番号を$edit_numとして変数に入れる
$edit_num = $_POST["edit_num"];
//echo $edit_num;

//削除番号指定時のパスワードを$delete_passとして変数に入れる
$delete_pass = $_POST["delete_pass"];
//echo $delete_pass;

//編集番号指定時のパスワードを$edit_passとして変数に入れる
$edit_pass = $_POST["edit_pass"];
//echo $edit_pass;

//入力された登録用の名前を$t_nameとして変数に入れる
$t_name = $_POST["t_name"];
//echo $t_name;

//入力された登録用のパスワードを$t_passとして変数に入れる
$t_pass = $_POST["t_pass"];
//echo $t_pass;

//echo $_SESSION["loginpass"];
//echo $_SESSION["loginid"];
//登録用の名前とパスワードが入力された場合の処理
if(!empty($t_name) && !empty($t_pass)){
//IDを生成
$t_id = uniqid();
$sql = $pdo -> prepare("INSERT INTO touroku2(id,name,password) VALUES('$t_id', :name, :password);");

$sql -> bindParam(':name',$t_name,PDO::PARAM_STR);

$sql -> bindParam(':password',$t_pass,PDO::PARAM_STR);

$sql -> execute();

}//if文を閉じる

//パスワードが入力され削除指定番号と編集指定番号がない場合の処理
if(!empty($pass) && empty($delete_num) && empty($edit_num)){
	//DBに投稿内容を書き込む
$sql = $pdo -> prepare("INSERT INTO pblog(id,name,comment,time,password) VALUES('$id',:name, :comment, :time, :password);");

$sql -> bindParam(':name',$bdname,PDO::PARAM_STR);

$sql -> bindParam(':comment',$bdcomment,PDO::PARAM_STR);

$sql -> bindParam(':time',$bdtime,PDO::PARAM_STR);

$sql -> bindParam(':password',$bdpassword,PDO::PARAM_STR);

$bdname = $name;

$bdcomment = $message;

$bdtime = $time;

$bdpassword = $pass;

$sql -> execute();

}//if文を閉じる
//削除用のパスワードが入力され削除指定番号が入力された場合の処理
if(!empty($delete_pass) && !empty($delete_num)){
	//削除したい投稿番号
	$delid = $delete_num;
	//代わりに入れる名前
	$delnm = "削除しました。";
	//代わりに入れる内容
	$delkome = "削除しました。";
	//書き換えを行う
	$sql = "update pblog set name='$delnm',comment='$delkome'where id = '$delid';";
	
	$result = $pdo->query($sql);
}//if文を閉じる

//パスワードが入力され削除指定番号は入力されていないが編集番号が入力されている場合の処理
if(!empty($edit_num) && !empty($edit_pass)){
	
	$sql = 'SELECT * FROM pblog;';//クエリ「tbtest」は自分が作ったテーブル名

	$result = $pdo->query($sql);//実行・結果取得
	
		foreach($result as $row){
			//番号が$editと一致した場合は行を後で使うために変数に入れる
			if($row['id'] == $edit_num && $row['password'] == $edit_pass){
               			$edit_number = $row['id'];
				$edit_user = $row['name'];
				$edit_text = $row['comment'];
				//echo $edit_number;
				//echo $edit_user;
				//echo $edit_text;
			}//if文を閉じる
			if($row['id'] == $edit_num && $row['password'] != $edit_pass){
			$keikoku2 = "パスワードが違います。";
			}//if文を閉じる
		}//foreachを閉じる
	
}//if文を閉じる

        if (!empty($_FILES['upfile']['error']) && is_int($_FILES['upfile']['error']) && $_FILES["upfile"]["name"] != ""){
            //エラーチェック
            switch ($_FILES['upfile']['error']) {
                case UPLOAD_ERR_OK: // OK
                    break;
                case UPLOAD_ERR_NO_FILE:   // 未選択
                    throw new RuntimeException('ファイルが選択されていません', 400);
                case UPLOAD_ERR_INI_SIZE:  // php.ini定義の最大サイズ超過
                    throw new RuntimeException('ファイルサイズが大きすぎます', 400);
                default:
                    throw new RuntimeException('その他のエラーが発生しました', 500);
		}
	}

            //画像・動画をバイナリデータにする．
            $raw_data = file_get_contents($_FILES['upfile']['tmp_name']);

            //拡張子を見る
            $tmp = pathinfo($_FILES["upfile"]["name"]);
            $extension = $tmp["extension"];
            if($extension == "jpg" || $extension == "jpeg" || $extension == "JPG" || $extension == "JPEG"){
                $extension = "jpeg";
            }
            elseif($extension == "png" || $extension == "PNG"){
                $extension = "png";
            }
            elseif($extension == "gif" || $extension == "GIF"){
                $extension = "gif";
            }
            elseif($extension == "mp4" || $extension == "MP4"){
                $extension = "mp4";
            }
            else{
                echo "非対応ファイルです．<br/>";
            }
            //DBに格納するファイルネーム設定
            //サーバー側の一時的なファイルネームと取得時刻を結合した文字列にsha256をかける．
            $date = getdate();
            $fname = $_FILES["upfile"]["tmp_name"].$date["year"].$date["mon"].$date["mday"].$date["hours"].$date["minutes"].$date["seconds"];
            $fname = hash("sha256", $fname);

            //画像・動画をDBに格納．
            $sql = "INSERT INTO GDuploader(fname, extension, raw_data) VALUES (:fname, :extension, :raw_data);";
            $stmt = $pdo->prepare($sql);
            $stmt -> bindValue(":fname",$fname, PDO::PARAM_STR);
            $stmt -> bindValue(":extension",$extension, PDO::PARAM_STR);
            $stmt -> bindValue(":raw_data",$raw_data, PDO::PARAM_STR);
            $stmt -> execute();


//phpでプログラミング終了
?>
	

<!DOCTYPE html>
<html>
<!-htmlでプログラミング->
<!-タイトルを入れる->
<head>
<!-文字化け防止->
<meta http-equiv="content-type" charset="utf-8">
<lang = "ja">
<title>茶道初心者ブログ</title>
</head>
<body>
<!-見出しをつける->
<h1>茶道初心者ブログ</h1>

<?php if(!empty($_SESSION["loginid"]) || !empty($_SESSION["loginpass"])):?>
ようこそ<?php echo $_SESSION["loginuser"]; ?>さん
<!-編集指定番号が入力されていない場合と入力されている場合で分岐させる->
<!-編集番号が指定されていない場合->
<?php if(empty($edit_number)): ?>
	<!-登録フォームを作る->
<form action = "" method = "POST">
名前:<input type = "text" name = "t_name"></br>
パスワード:<input type = "text" name = "t_pass"></br>
名前とパスワードを両方入力してください。


		<!-送信ボタン->
<input type="submit"/><br>
	<!-登録内容を表示する->
<?php
$sql = 'SELECT * FROM touroku2;';//クエリ「tbtest」は自分が作ったテーブル名

$result = $pdo->query($sql);//実行・結果取得

//以下でブラウザに出力する

	foreach($result as $row){
		if($row['id'] == $t_id){
			$kakunin_id = $row['id'];
			$kakunin_name = $row['name'];
			$kakunin_pass = $row['password'];
			echo "あなたのIDは";
			echo $kakunin_id;
			echo nl2br("\n");
			echo "あなたの名前は";
			echo $kakunin_name;
			echo nl2br("\n");
			echo "あなたのパスワードは";
			echo $kakunin_pass;
		}//if文を閉じる
	}//foreachを閉じる

?>

	<!-投稿フォームを作る->
<form action="" method="POST">
		<!-名前->
<br>名前:<input type="text" name="name" value = "<?php echo $_SESSION["loginuser"]; ?>" /><br>
		<!-投稿内容->
コメント:<input type="text" name="message" value = "<?php echo $edit_text; ?>" /><br>		
		<!-パスワード->
パスワ－ド:<input type="text" name="pass"/><br>
	<?php
	if(empty($pass)&& (!empty($name) || !empty($message))){
	echo パスワードが入力されていません;
	}
	?>
		<!-送信ボタン->
<input type="submit"/><br>

<!-phpでパスワードが入力されていない場合、入力されていないことを表示させる->

</form>
    <form action="" enctype="multipart/form-data" method="post">
        <label>画像/動画アップロード</label>
        <br><input type="file" name="upfile">
        <br>
        ※画像はjpeg方式，png方式，gif方式に対応しています．動画はmp4方式のみ対応しています．<br>
        <input type="submit" value="アップロード"></br>
	<!-削除番号指定用フォームを作る->
<form action="" method="POST">
		<!-削除番号の指定->
削除番号指定:<input type="text" name="delete_num"/><br>
		<!-パスワードの入力->
パスワ－ド:<input type="password" name="delete_pass"/><br>
		<!-送信ボタン->
<input type="submit"/><br>

<!-入力されたパスワードが元のものと一致しなかったら、phpで『パスワードが違います』と表示->
<?php
echo $keikoku1;
?>
<!-パスワードが入力されていない場合『パスワードを入力してください』と表示->
<?php
if(empty($delete_pass) && !empty($delete_num)){
echo "パスワードを入力してください。";
}
?>
</form>
	<!-編集番号指定フォームを作る->
<form action="" method="POST">
		<!-編集番号の指定->
編集番号指定:<input type="text" name="edit_num"/><br>
		<!-パスワードの入力->
パスワ－ド:<input type="password" name="edit_pass"/><br>
		<!-送信ボタン->
<input type="submit"/><br>

<!-入力されたパスワードが元のものと一致しなかったら、phpで『パスワードが違います』と表示->
<?php
echo $keikoku2;
?>
<!-パスワードが入力されていない場合『パスワードを入力してください』と表示->
<?php
if(empty($edit_pass) && !empty($edit_num)){
echo "パスワードを入力してください。";
}
?>

</form>


<!-編集番号が送信されたら編集用のフォームを表示->
<?php else: ?>
<!-編集用のフォームを作る->
<form action="" method="POST">
<input name = "sai_edit_num" type = "hidden" value = "<?php echo $edit_number; ?>" /></br>
名前:<input name = "sai_edit_name" type = "text" value = "<?php echo $kakunin_user; ?>" /></br>
投稿内容:<input name = "sai_edit_text" type = "text" value = "<?php echo $edit_text; ?>" /></br>
<input name = "sai_edit_pass" type = "hidden" value = "<?php echo $edit_pass; ?>" /></br>

<input type = "submit"/>

</form>

<!-if文を閉じる->
<?php endif; ?>

<?php else :?>
ログインをやり直してください。
<?php $_SESSION = array(); ?>
<?php endif; ?>

</body>
<!-htmlでプログラミング終了->
</html>
<hr>

<?php
//DBから取得して表示する．
$sql = "SELECT * FROM GDuploader ORDER BY id;";
$stmt = $pdo->prepare($sql);
$stmt -> execute();
    while ($row = $stmt -> fetch(PDO::FETCH_ASSOC)){
        echo ($row["id"]."<br/>");
        //動画と画像で場合分け
        $target = $row["fname"];
        if($row["extension"] == "mp4"){
            echo ("<video src=\"import_media.php?target=$target\" width=\"426\" height=\"240\" controls></video>");
        }
        elseif($row["extension"] == "jpeg" || $row["extension"] == "png" || $row["extension"] == "gif"){
            echo ("<img src='import_media.php?target=$target'>");
        }
        echo ("<br/><br/>");
    }
?>

<?php
//phpでプログラミング
//POSTで送信されたsai_edit_numを変数に格納
$edit_num2 = $_POST["sai_edit_num"];
//echo $edit_num2;

//POSTで送信されたsai_edit_nameを変数に格納
$edit_name2 = $_POST["sai_edit_name"];
//echo $edit_name2;

//POSTで送信されたsai_edit_textを変数に格納
$edit_text2 = $_POST["sai_edit_text"];
//echo $edit_text2;

//POSTで送信されたsai_edit_textを変数に格納
$edit_pass2 = $_POST["sai_edit_pass"];
//echo $edit_text2;

//実際に編集をする場合の処理
//編集した名前または編集した投稿内容が送られてきた場合
if(!empty($edit_name2) || !empty($edit_text2)){
	$sql = "update pblog set name='$edit_name2',comment='$edit_text2'where id = '$edit_num2';";
	
	$result = $pdo->query($sql);
}//if文を閉じる


//DBの内容を表示
$sql = 'SELECT * FROM pblog;';//クエリ「pblog」は自分が作ったテーブル名

$result = $pdo->query($sql);//実行・結果取得

//以下でブラウザに出力する

foreach($result as $row){

	echo $row['id'].',';
	echo $row['name'].',';
	echo $row['comment'].',';
	echo $row['time'].'<br>';

}


//phpでプログラミング終了
?>
