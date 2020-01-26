<?php
//どこでも使うもの、繰り返し使うもの
	$No=null;
	$editname=null;
	$editcomment=null;

	//データベースに接続
	$db_host = 'データベース名';
	$db_user = 'ユーザー名';
	$db_pass = 'パスワード';
	$pdo = new PDO($db_host, $db_user, $db_pass, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
	// var_dump($pdo);
	
	//データベース内にテーブル作成
	$dbsql = "CREATE TABLE IF NOT EXISTS user"
	." ("
	. "id INT AUTO_INCREMENT PRIMARY KEY,"
	. "dbt_name char(32),"
	. "dbt_comment TEXT,"
	. "dbt_time DATETIME,"
	. "dbt_pass TEXT"
	.");";
	$stmt = $pdo->query($dbsql);

//投稿機能
	if(!empty($_POST['namae']) and !empty($_POST['coment'] and !empty($_POST['pass']))){
		$name=$_POST['namae'];
		$comment=$_POST['coment'];
		$pass=$_POST['pass'];
		$time=date('y/m/d/ h:i:s');
		
//新規
		if(empty($_POST['No'])){
			//データベースに書き込み
			$dbsql = $pdo -> prepare("INSERT INTO user (dbt_name, dbt_comment, dbt_time, dbt_pass) VALUES (:dbt_name, :dbt_comment, :dbt_time, :dbt_pass)");
			$dbsql -> bindParam(':dbt_name', $dbt_name, PDO::PARAM_STR);
			$dbsql -> bindParam(':dbt_comment', $dbt_comment, PDO::PARAM_STR);
			$dbsql -> bindParam(':dbt_time', $dbt_time, PDO::PARAM_STR);
			$dbsql -> bindParam(':dbt_pass', $dbt_pass, PDO::PARAM_STR);
			$dbt_name =$name;
			$dbt_comment =$comment;
			$dbt_time =$time;
			$dbt_pass =$pass;
			$dbsql -> execute();
			echo "新規投稿しました";
		}
//編集
		else{
			$id=$_POST['No'];
			$dbt_name=$_POST['namae'];
			$dbt_comment=$_POST['coment'];
			
			$dbsql = 'update user set dbt_name=:dbt_name,dbt_comment=:dbt_comment where id=:id';
			$stmt = $pdo->prepare($dbsql);
			$stmt->bindParam(':dbt_name', $dbt_name, PDO::PARAM_STR);
			$stmt->bindParam(':dbt_comment', $dbt_comment, PDO::PARAM_STR);
			$stmt->bindParam(':id', $id, PDO::PARAM_INT);
			$stmt->execute();
			echo "編集投稿しました";
		}
	}
	
//削除機能
	elseif (!empty($_POST['deleteNo']) and !empty($_POST['deletepass'])){
		$deletepass=$_POST['deletepass'];
		$deleteNo=$_POST['deleteNo'];
		$dbsql = 'SELECT * FROM user';
		$stmt = $pdo->query($dbsql);
		$results = $stmt->fetchAll();
		foreach ($results as $row){
			if($row['id']==$deleteNo){
				if(strcmp($row['dbt_pass'],$deletepass)==0){
					$id = $deleteNo;
					$dbsql = 'delete from user where id=:id';
					$stmt = $pdo->prepare($dbsql);
					$stmt->bindParam(':id', $id, PDO::PARAM_INT);
					$stmt->execute();
					echo "削除しました";
				}
				else{
					echo "パスワードが不正です";
				}
			}
		}
	}
//編集機能
	else{
		if(!empty($_POST['editNo']) and !empty($_POST['editpass'])){
			echo "アドバイス";
			$editpass=$_POST['editpass'];
			$editNo=$_POST['editNo'];
			$dbsql = 'SELECT * FROM user';
			$stmt = $pdo->query($dbsql);
			$results = $stmt->fetchAll();
			foreach ($results as $row){
				if($row['id']==$editNo){
					if(strcmp($row['dbt_pass'],$editpass)==0){
						$No=$row['id'];
						$editname=$row['dbt_name'];
						$editcomment=$row['dbt_comment'];
					}
					else{
						echo "パスワードが不正です";
					}
				}
			}
		}
	}
?>

<!DOCTYPE html>
<html lang="ja">
	<head>
		<meta  http-equiv="Content-Type" content="text/html" charset="utf-8"/>
<!-- http-equivは文書の処理の仕方や扱いの指定、content="text/html"はhtmlで書かれてるよって意味  -->
	</head>
	<body>
		<h2>どの国の料理が好きか、その国で好きな料理は？</h2>
		<form method="post" action=" " >
<!-- /入力フォーム -->
		<div class="input">
			国　　　　：<input type="text" name="namae" value="<?php echo $editname;?>"> <br>
			料理　　　：<input type="text" name="coment" value="<?php echo $editcomment;?>"> <br>
			<!--編集用番号--><input type="hidden" name="No" value="<?php echo $No;?>">
			パスワード：<input type="password" name="pass" > <br>
			<input type="submit" value="送信"> <br> <br>
		</div>
<!-- 削除フォーム -->
		<div class="delete">
			削除番号　：<input type="text" name="deleteNo" > <br>
			パスワード：<input type="password" name="deletepass" > <br>
			<input type="submit" name="delete" value="削除" > <br> <br>
		</div>
<!-- 編集フォーム -->
		<div class="edit">
			編集番号　：<input type="text" name="editNo" > <br>
			パスワード：<input type="password" name="editpass" > <br>
			<input type="submit" name="edit" value="編集" >
		</div> 
		</form>
	</body>
</html>

<?php
//データベースに接続
	$db_host = 'データベース名';
	$db_user = 'ユーザー名';
	$db_pass = 'パスワード';
	$pdo = new PDO($db_host, $db_user, $db_pass, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));

//データベース表示
	$dbsql = 'SELECT * FROM user';
	$stmt = $pdo->query($dbsql);
	$results = $stmt->fetchAll();
	foreach ($results as $row){
	//$rowの中にはテーブルのカラム名が入る
		echo $row['id'].' ';
		echo $row['dbt_name'].' ';
		echo $row['dbt_comment'].' ';
		echo $row['dbt_time'].'<br>';
	}
?>