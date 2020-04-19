@echo off
rem 囲碁の棋譜取得バッチ

chcp 65001

rem 囲碁の棋譜を取得する
php igo_kifu_get.php > log.txt
pause
