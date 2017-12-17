<?php
//phpでプログラミング
$filename="kadai2-6.txt";

//変数$arrayにfile("kadai6.txt")を格納
$array = file("$filename");
//数字を振る
$n = count($array)+1;

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
//echo $edit_num; ok

//削除番号指定時のパスワードを$delete_passとして変数に入れる
$delete_pass = $_POST["delete_pass"];
//echo $delete_pass;

//編集番号指定時のパスワードを$edit_passとして変数に入れる
$edit_pass = $_POST["edit_pass"];
//echo $edit_pass; ok

//以下で分岐させる
//パスワードが入力され削除指定番号と編集指定番号がない場合の処理
if(!empty($pass) && empty($delete_num) && empty($edit_num)){
	//まずはfopenのaモード(追記モード)でファイルを開く
	$fp=fopen($filename,'a');
	
	//テクストファイルに書き込む
	fwrite($fp, $n ."<>" .$name."<>".$message."<>".$time. "<>" .$pass."<>". PHP_EOL);

	//fopenで開いたテキストファイルを閉じる
	fclose($fp);
}//if文を閉じる
//削除用のパスワードが入力され削除指定番号が入力された場合の処理
if(!empty($delete_pass) && !empty($delete_num)){
	//テキストファイルをファイル関数で配列として読み込む
	$sakujo = file($filename , FILE_SKIP_EMPTY_LINES);
	//まずはfopenのr+モード(読み込み+書き出しモード)でファイルを開く
	$fp = fopen($filename , 'w+');
	//テキストファイルを配列として読み込む
		foreach($sakujo as $delete_line){
			///配列をexplodeでさらに分割
			$sai = explode("<>",$delete_line);
			//var_dump($sai);
			//echo "<br>";
			//削除番号と投稿番号が一致しなければそのまま書き込み
			if($sai[0] != $delete_num){
				fwrite($fp,$delete_line);
			}//if文を閉じる
			//投稿番号が削除番号と一致してパスワードも一致した場合は削除
			if($sai[0] == $delete_num && $sai[4] == $delete_pass){
                		fwrite($fp, 削除しました。. PHP_EOL);
				//echo "削除しました。<br>";
				
			}//if文を閉じる
			//投稿番号と削除番号は一致しているがパスワードが一致していない場合
			if($sai[0] == $delete_num && $sai[4] != $delete_pass){
				fwrite($fp,$delete_line);
				$keikoku1 = "パスワードが違います";
				//echo $keikoku1;
			}//if文を閉じる
		}//foreachを閉じる
	//ファイルを閉じる
	fclose($fp);
}//if文を閉じる
//パスワードが入力され削除指定番号は入力されていないが編集番号が入力されている場合の処理
if(!empty($edit_num) && !empty($edit_pass)){
	//テキストファイルをファイル関数で配列として読み込む
	$hensyu = file($filename , FILE_SKIP_EMPTY_LINES);
	//まずはfopenのr+モード(読み込み+書き出しモード)でファイルを開く
	$fp = fopen($filename , 'r+');
	//テキストファイルを配列として読み込む
		foreach($hensyu as $edit_line){
			///配列をexplodeでさらに分割
			$sai_edit = explode("<>",$edit_line);
				//番号が$editと一致した場合は行を後で使うために変数に入れる
				if($sai_edit[0] == $edit_num && $sai_edit[4] == $edit_pass){
                			$edit_number = $sai_edit[0];
					$edit_user = $sai_edit[1];
					$edit_text = $sai_edit[2];
					//echo $edit_number;  ok
					//echo $edit_user;  ok
					//echo $edit_text;  ok
				}//if文を閉じる
				if($sai_edit[0] == $edit_num && $sai_edit[4] != $edit_pass){
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
<input name = "sai_edit_name" type = "text" value = "<?php echo $edit_user; ?>" /></br>
<input name = "sai_edit_text" type = "text" value = "<?php echo $edit_text; ?>" /></br>
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
	//テキストファイルを配列として読み込む
	$ret_array = file($filename , FILE_SKIP_EMPTY_LINES);
	//テキストファイルを書き込みモードで開く
	$fp=fopen($filename,'w+');
	//配列として読み込んだテキストファイルをさらにexplodeで分割
		foreach($ret_array as $line3){ 
			$saikeisai = explode("<>",$line3);
	
			//$saikeisai[0]と$edit_num2が一致していないならそのままにして書き込み
			if($saikeisai[0] != $edit_num2){
				fputs($fp,$line3);
			//$eddit_num2と同じ場合は書き換え
			}else{
				fwrite($fp,  $edit_num2 ."<>" .$edit_name2 ."<>" . $edit_text2 ."<>".$time ."<>".$edit_pass2."<>". PHP_EOL);
			}//if文を閉じる
		}//foreachをとじる
	//ファイルを閉じる
	fclose($fp);

}//if文を閉じる

//テキストファイルの内容を表示
// ファイルを全て配列に入れる
$ret_array = file( $filename );
// 取得したファイルデータ(配列)を全て表示する
	foreach($ret_array as $line){ 
	//「<>」で分割することでそれぞれの値を取得する
	list($num,$name2,$message2,$time2) = explode("<>",$line);
	//配列を表示する
	echo "$num $name2 $message2 $time2<br />\n";
	} 
//phpでプログラミング終了
?>
