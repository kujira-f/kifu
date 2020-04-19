<?php
  // 'Simple HTML DOM Parser' を読み込む
  require_once 'simple_html_dom.php';
  
  // 「棋譜う」からの棋譜取得phpを読み込む
  require_once "igo_kifu_get_kihuu.php";
  
  // 「棋譜う」から棋譜を取得する(引数は不要)
  igo_kifu_get_kifuu('test');
  
  // 「TOM」からの棋譜取得phpを読み込む
  // require_once "igo_kifu_get_tom.php";
  
  // 「TOM」から棋譜を取得する(引数は不要)
  // igo_kifu_get_tom('test');
  
?>
 