<?php
	//TOM
	mb_language('Japanese');
	function igo_kifu_get_tom($x){
		//変数
		$dsn = 'mysql:dbname=igo; host=localhost; charset=utf8';
		$name = 'monty';
		$password = 'hjsdkafh';
		
		//DB接続
		try{
		    $pdo = new PDO($dsn, $name, $password, array(PDO::ATTR_EMULATE_PREPARES => false));
		} catch(PDOException $e){
		    echo $e->getMessage();
		    print "CONNECT ERROR\n";
		}
		
		//棋譜テーブルのデータ件数を取得し、IDを計算する
		$sql = "select count(*) from kifu_tom";
		$stmt = $pdo->query($sql);
		$id = $stmt->fetchColumn();
		$id = $id +1;
		
		// 最後のページまで繰り返す（最後のページは暫定で、日時バッチでは要修正）
		for($i = 1; $i < 41; $i++){
		//for($i = 0; $i < 2; $i++){
			// 棋譜へのリンクを取得
			if ($i == 0) {
				${worki} = str_pad(${i}, 2, 0, STR_PAD_LEFT);
				$source = file_get_contents('http://weiqi.tom.com/php/listqipu_${worki}.html');
			}
			else{
				$source = file_get_contents('http://weiqi.tom.com/php/listqipu_03.html');
			}
			
			
			// 文字コードを変更
			$source = mb_convert_encoding($source, 'utf8', 'auto');
			$html = str_get_html($source);
			//$html = mb_convert_encoding($html, 'GB2312', 'UTF-8');
			// 棋譜をログファイルに出力する
			// 以下の$html->find('a')は配列なので、ループする
			$list = $html->find('a' );
			for ($j = 0; $j< count($list); $j++) {
				// 文字列中に「sgf」を含む場合、リンク先を取得する
				if (strpos("$list[$j]","sgf") !== false) {
					//print "$list[$j]\n";
				
					$url = substr($list[$j],strpos($list[$j], "qipu"),18);
					//print "$url\n";
					$work = mb_convert_encoding("$list[$j]", 'UTF-8', 'SJIS-WIN');
					$work = str_get_html($work);
					//print "$work\n";
					$html_detail = file_get_html("http://weiqi.tom.com/"."${url}".".sgf");
					//$html_detail = mb_convert_encoding($html_detail, 'GB2312', 'UTF-8');
					$html_detail = mb_convert_encoding($html_detail, 'UTF-8', 'GB2312');
					print "$html_detail\n";
					
					//変換できない文字列を編集
					$html_detail = str_replace("?墨t??", "偰?準]", $html_detail);
					$html_detail = str_replace("申?F??", "申旻埈]", $html_detail);

					//INSERT分の準備
					$stmt = $pdo -> prepare("INSERT INTO kifu_tom (game_type,board_size,player_black,player_black_rate,player_white,player_white_rate,komi,result,play_date,use_time,event,kifu,available,from_cite,update_date) VALUES (:game_type,:board_size,:player_black,:player_black_rate,:player_white,:player_white_rate,:komi,:result,:play_date,:use_time,:event,:kifu,:available,:from_cite,:update_date)");
					$stmt->bindParam(':game_type', $game_type, PDO::PARAM_STR);
					$stmt->bindParam(':board_size', $board_size, PDO::PARAM_STR);
					$stmt->bindParam(':player_black', $player_black, PDO::PARAM_STR);
					$stmt->bindParam(':player_black_rate', $player_black_rate, PDO::PARAM_STR);
					$stmt->bindParam(':player_white', $player_white, PDO::PARAM_STR);
					$stmt->bindParam(':player_white_rate', $player_white_rate, PDO::PARAM_STR);
					$stmt->bindParam(':komi', $komi, PDO::PARAM_STR);
					$stmt->bindParam(':result', $result, PDO::PARAM_STR);
					$stmt->bindParam(':play_date', $play_date, PDO::PARAM_STR);
					$stmt->bindParam(':use_time', $use_time, PDO::PARAM_STR);
					$stmt->bindParam(':event', $event, PDO::PARAM_STR);
					$stmt->bindParam(':kifu', $kifu, PDO::PARAM_STR);
					$stmt->bindParam(':available', $available, PDO::PARAM_STR);
					$stmt->bindParam(':from_cite', $from_cite, PDO::PARAM_STR);
					$stmt->bindParam(':update_date', $update_date, PDO::PARAM_STR);

					// 空行は処理しない
					if (trim($html_detail) !== ""){

						
						// game_typeを取得する
						$left = strpos($html_detail, "GM[");
						if ($left > 0){
							$right = strpos(substr($html_detail, $left+3), "]");
							$game_type = substr($html_detail, $left+3, $right);
						}
						else{
							$game_type = null;
						}
						// board_sizeを取得する
						$left = strpos($html_detail, "SZ[");
						if ($left > 0){
							$right = strpos(substr($html_detail, $left+3), "]");
							$board_size = substr($html_detail, $left+3, $right);
						}
						else{
							$board_size = 19;
						}
						// player_blackを取得する
						$left = strpos($html_detail, "PB[");
						if ($left > 0){
							$right = strpos(substr($html_detail, $left+3), "]");
							$player_black = substr($html_detail, $left+3, $right);
						}
						else{
							$player_black = null;
						}
						// player_black_rateを取得する
						$left = strpos($html_detail, "BR[");
						if ($left > 0){
							$right = strpos(substr($html_detail, $left+3), "]");
							$player_black_rate = substr($html_detail, $left+3, $right);
						}
						else{
							$player_black_rate = null;
						}
						// player_whiteを取得する
						$left = strpos($html_detail, "PW[");
						if ($left > 0){
							$right = strpos(substr($html_detail, $left+3), "]");
							$player_white = substr($html_detail, $left+3, $right);
						}
						else{
							$player_white = null;
						}
						// player_white_rateを取得する
						$left = strpos($html_detail, "WR[");
						if ($left > 0){
							$right = strpos(substr($html_detail, $left+3), "]");
							$player_white_rate = substr($html_detail, $left+3, $right);
						}
						else{
							$player_white_rate = null;
						}
						// komiを取得する
						$left = strpos($html_detail, "KM[");
						if ($left > 0){
							$right = strpos(substr($html_detail, $left+3), "]");
							$komi = substr($html_detail, $left+3, $right);
						}
						else{
							$komi = null;
						}
						// resultを取得する
						$left = strpos($html_detail, "RE[");
						if ($left > 0){
							$right = strpos(substr($html_detail, $left+3), "]");
							$result = substr($html_detail, $left+3, $right);
						}
						else{
							$result = null;
						}
						// play_dateを取得する
						$left = strpos($html_detail, "DT[");
						if ($left > 0){
							$right = strpos(substr($html_detail, $left+3), "]");
							$play_date = substr($html_detail, $left+3, $right);
						}
						else{
							$play_date = null;
						}
						// use_timeを取得する
						$left = strpos($html_detail, "TM[");
						if ($left > 0){
							$right = strpos(substr($html_detail, $left+3), "]");
							$use_time = substr($html_detail, $left+3, $right);
						}
						else{
							$use_time = null;
						}
						// eventを取得する
						$left = strpos($html_detail, "EV[");
						if ($left > 0){
							$right = strpos(substr($html_detail, $left+3), "]");
							$event = substr($html_detail, $left+3, $right);
						}
						else{
							$event = null;
						}
						// kifuを取得する
						$left = strpos($html_detail, ";B[");
						if ($left > 0){
							$right = strpos(substr($html_detail, $left+5), ")");
							$kifu = substr($html_detail, $left+1, $right+4);
						}
						else{
							$kifu = null;
						}
						// availableを設定する
						$available = 1;
						// from_citeを設定する
						$from_cite = "tom";
						// update_dateを設定する
						$update_date = date("Y/m/d");
						
						//DB更新
						$stmt->execute();
					}
				}
			}
	 	}
	 	END:
	 	// メモリを解放する
	 	$pdo = null;
		$html->clear();
  }
?>
 