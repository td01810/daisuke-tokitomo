<?php
//データベースに接続
$dsn='mysql:データベース名;host=localhost';

$user='ユーザー名';

$password='パスワード';

$pdo=new PDO($dsn,$user,$password);

//テーブルの作成

$sql = "CREATE TABLE pblog"

."("

."id INT,"

."name char(32),"

."comment TEXT,"

."time DATETIME,"

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
/*$sql='SHOW CREATE TABLE pblog;';

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

//以下で分岐させる
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
<title>プログラミングインターンシップブログ</title>
</head>
<body>
<!-見出しをつける->
<h1>プログラミングインターンシップブログ</h1>

<!-編集指定番号が入力されていない場合と入力されている場合で分岐させる->
<!-編集番号が指定されていない場合->
<?php if(empty($edit_number)): ?>
	<!-投稿フォームを作る->
<form action="" method="POST">
		<!-名前->
名前:<input type="text" name="name" value = "<?php echo $edit_user; ?>" /><br>
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
名前:<input name = "sai_edit_name" type = "text" value = "<?php echo $edit_user; ?>" /></br>
投稿内容:<input name = "sai_edit_text" type = "text" value = "<?php echo $edit_text; ?>" /></br>
<input name = "sai_edit_pass" type = "hidden" value = "<?php echo $edit_pass; ?>" /></br>

<input type = "submit"/>

</form>

<!-if文を閉じる->
<?php endif; ?>
</body>
<!-htmlでプログラミング終了->
</html>
<hr>




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
