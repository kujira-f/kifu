<?php
	//棋譜う
	function igo_kifu_get_kifuu($x){
		//変数
		//$dsn = 'mysql:dbname=xxxxxxx; host=localhost; charset=utf8';
		//$name = 'xxxxxxxx';
		//$password = 'xxxxxxx';
		
		//DB接続
		//try{
		//    $pdo = new PDO($dsn, $name, $password, array(PDO::ATTR_EMULATE_PREPARES => false));
		//} catch(PDOException $e){
		//    echo $e->getMessage();
		//    print "CONNECT ERROR\n";
		//}
		
		//棋譜テーブルのデータ件数を取得し、IDを計算する
		//$sql = "select count(*) from kifu_kihuu";
		//$stmt = $pdo->query($sql);
		//$id = $stmt->fetchColumn();
		$id = $id +1;
		
		// 最後のページまで繰り返す（最後のページは暫定で、日時バッチでは要修正）
		for($i = 1; $i < 2; $i++){
		//for($i = 1; $i < 41; $i++){
			// 棋譜へのリンクを取得
			$html = file_get_html("http://www.kihuu.net/index.php?pageID=${i}");
			// 文字コードを変更
			$html = mb_convert_encoding($html, 'SJIS-WIN', 'UTF-8');
			$html = str_get_html($html);
			// 棋譜をログファイルに出力する
			// 以下の$html->find('a')は配列なので、ループする
			$list = $html->find('a,EMBED' );
			for ($j = 0; $j< count($list); $j++) {
				// 文字列中に「threadno」を含む場合、リンク先を取得する
				if (strpos("$list[$j]","threadno") !== false) {
					$url = substr($list[$j],48,12);
					$work = mb_convert_encoding("$list[$j]", 'UTF-8', 'SJIS-WIN');
					$work = str_get_html($work);
					$html_detail = file_get_html("http://www.kihuu.net/sgf/"."${url}".".sgf");
					print "$html_detail\n";

					//INSERT分の準備
					//$stmt = $pdo -> prepare("INSERT INTO kifu_kihuu (original_id,game_type,board_size,player_black,player_black_rate,player_white,player_white_rate,komi,result,play_date,use_time,event,kifu,available,from_cite,update_date) VALUES (:game_type,:board_size,:player_black,:player_black_rate,:player_white,:player_white_rate,:komi,:result,:play_date,:use_time,:event,:kifu,:available,:from_cite,:update_date)");
					//$stmt->bindParam(':original_id', $original_id, PDO::PARAM_STR);
					//$stmt->bindParam(':game_type', $game_type, PDO::PARAM_STR);
					//$stmt->bindParam(':board_size', $board_size, PDO::PARAM_STR);
					//$stmt->bindParam(':player_black', $player_black, PDO::PARAM_STR);
					//$stmt->bindParam(':player_black_rate', $player_black_rate, PDO::PARAM_STR);
					//$stmt->bindParam(':player_white', $player_white, PDO::PARAM_STR);
					//$stmt->bindParam(':player_white_rate', $player_white_rate, PDO::PARAM_STR);
					//$stmt->bindParam(':komi', $komi, PDO::PARAM_STR);
					//$stmt->bindParam(':result', $result, PDO::PARAM_STR);
					//$stmt->bindParam(':play_date', $play_date, PDO::PARAM_STR);
					//$stmt->bindParam(':use_time', $use_time, PDO::PARAM_STR);
					//$stmt->bindParam(':event', $event, PDO::PARAM_STR);
					//$stmt->bindParam(':kifu', $kifu, PDO::PARAM_STR);
					//$stmt->bindParam(':available', $available, PDO::PARAM_STR);
					//$stmt->bindParam(':from_cite', $from_cite, PDO::PARAM_STR);
					//$stmt->bindParam(':update_date', $update_date, PDO::PARAM_STR);

					// 空行は処理しない
					if (trim($html_detail) !== ""){

						// original_idを取得する
						$left = strpos($html_detail, "GM[");
						if ($left > 0){
							$right = strpos(substr($html_detail, $left+3), "]");
							$original_id = substr($html_detail, $left+3, $right);
						}
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
						$from_cite = "kifuu";
						// update_dateを設定する
						$update_date = date("Y/m/d");
						
						//DB更新
						//$stmt->execute();
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
 
