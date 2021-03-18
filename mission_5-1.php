<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <title>mission_5-1</title>
    </head>
    <body>
        
        <?php
        //DB接続
        $dsn = 'データベース名';
	    $user = 'ユーザー名';
	    $password = 'パスワード';
	    $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
	    
	    $sql = "CREATE TABLE IF NOT EXISTS tb"
	    ." ("
	    . "id INT AUTO_INCREMENT PRIMARY KEY,"
	    . "name char(32),"
	    . "comment TEXT,"
	    . "pass varchar(100),"
	    . "time timestamp"
	    .");";
	    $stmt = $pdo->query($sql);
	    
        
        
        //新規投稿
        if(empty($_POST["hide"])&&!empty($_POST["name"])&&!empty($_POST["str"])&&!empty($_POST["pass"])){  //もし名前コメパスが入っていたら
            $name = $_POST["name"];
	        $comment = $_POST["str"];
    	    $pass = $_POST["pass"];
	        $sql = $pdo -> prepare("INSERT INTO tb (name,comment,pass,time) VALUES (:name,:comment,:pass,now())");
	        $sql -> bindParam(':name', $name, PDO::PARAM_STR);
	        $sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
	        $sql -> bindParam(':pass', $pass, PDO::PARAM_STR);
	    
	        $sql -> execute();
            echo "書き込み完了<br>";
        }

        //削除
        elseif(!empty($_POST["num"])&&!empty($_POST["delpass"])){
            $id = $_POST["num"];
            $pass = $_POST["delpass"];
            $sql = "select * from tb"; 
            $stmt = $pdo->query($sql); 
            $results = $stmt->fetchAll();
            foreach($results as $row){
                if($row['id']==$id && $row['pass']==$pass){
                    $sql = 'delete from tb where id=:id';
	                $stmt = $pdo->prepare($sql);
	                $stmt->bindParam(':id', $id, PDO::PARAM_INT);
	                $stmt->execute();
                }
            }
        }

         
        //編集取得
        elseif(!empty($_POST["edinum"])&&!empty($_POST["edipass"])){
            $id = $_POST["edinum"];
            $pass = $_POST["edipass"];
            $sql = "SELECT * FROM tb"; //取得
            $stmt = $pdo->query($sql); //実行し格納
            $results = $stmt->fetchAll();
            
            foreach($results as $row){
                if($row['id']==$id && $row['pass']==$pass){
                    $ediname=$row['name'];
                    $edicome=$row['comment'];
                    $edipass=$row['pass'];
                    $ediid=$row['id'];
                    break;
                }
            }
        }
        //編集投稿
        elseif(!empty($_POST["hide"])&&!empty($_POST["str"])&&!empty($_POST["name"])&&!empty($_POST["pass"])){
            $id = $_POST["hide"]; //変更する投稿番号
	        $name = $_POST["name"];
	        $comment = $_POST["str"]; 
	        $pass = $_POST["pass"]; //変更する名前、コメント、パスワード
	        
	        $sql = 'select * from tb';
	        $stmt = $pdo->query($sql);
	        $results = $stmt->fetchAll();
	        
	        foreach($results as $row){
	            if($row['id']==$id){
	                $sql = 'UPDATE tb SET name=:name,comment=:comment,pass=:pass,time=now() WHERE id=:id';
	                $stmt = $pdo->prepare($sql);
	                $stmt->bindParam(':name', $name, PDO::PARAM_STR);
	                $stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
	                $stmt->bindParam(':pass',$pass,PDO::PARAM_STR);
	                $stmt->bindParam(':id', $id, PDO::PARAM_INT);
	                $stmt->execute();
	            }
	        }
        }
        

        ?>
        <form action="" method="post">
            【投稿フォーム】<br>
            <input type="hidden" name="hide" value="<?php echo $ediid;?>">
            <input type="text" name="str" value="<?php echo $edicome;?>" placeholder="コメントを入力"><br>
            <input type="text" name="name" value="<?php echo $ediname;?>" placeholder="名前を入力"><br>
            <input type=="text" name="pass" value="<?php echo $edipass;?>" placeholder="パスワードを入力"><br>
            <input type="submit" name="submit"><br><br>
            【削除フォーム】<br>
            <input type="number" name="num" placeholder="削除する番号を入力"><br>
            <input type="text" name="delpass" placeholder="パスワードを入力"><br>
            <input type="submit" name="del" value="削除"><br><br>
            【編集フォーム】<br>
            <input type="number" name="edinum" value-"" placeholder="編集する番号を入力"><br>
            <input type="text" name="edipass" placeholder="パスワードを入力"><br>
            <input type="submit" name="edi" value="編集"><br><hr>
            【投稿一覧】<br><br>
        </form>
        
<?php
       
        //表示
    $sql = 'SELECT * FROM tb';
	$stmt = $pdo->query($sql);
	$results = $stmt->fetchAll();
	foreach ($results as $row){
		//$rowの中にはテーブルのカラム名が入る
		echo $row['id'].'，';
		echo $row['name'].'　';
		echo $row['comment'].'　';
		echo $row['time'].'<br>';
	}
?>
        
    </body>
</html>