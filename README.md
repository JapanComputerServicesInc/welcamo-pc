# welcamo
入退室管理システム（管理用）

# Client Requirement
PCのみでの利用可能
対象ブラウザは以下の通り
* Google Chrome
* Microsoft Edge

※スマートフォンで利用した場合、画面のレイアウト崩れが発生する可能性があります。

# Environment
* 言語：PHP 7.1.3
* フレームワーク：Laravel 5.7
* DB：MySQL 5.7.20
* WEBサーバー：Apache 2.4
* JSエンジン：Node JS（ビルドのみ）

# Library
* Bootstrap4(https://getbootstrap.com/)
* jQuery 3.1.1（https://jquery.com/）
* fontawesome 5.7.2（https://fontawesome.com/）
* Sweet Alert（https://sweetalert.js.org/）

### Laravel
* vinkla/alert
* laravel/tinker
* Carbon（標準ライブラリ）

# .env編集項目
APP_NAME=WELCAMO
APP_ENV=production
APP_KEY=（環境にあわせて設定）
APP_DEBUG=false
APP_LOG_DIR=（ログ出力先ディレクトリを指定）
APP_URL=（URLを設定：例）http://welcamo.com）
DEBUGBAR_ENABLED=false

DB_CONNECTION=mysql
DB_HOST=（ホスト名を設定）
DB_PORT=（DBポート名を設定）
DB_DATABASE=（DB名）
DB_USERNAME=（DB接続ユーザー）
DB_PASSWORD=（DB接続パスワード）

# Remarks
* 実利用環境ではHTTPS（SSL）アクセスにしてください。

* エリアを変更する場合は、resources/lang/ja、resources/lang/enのapp.phpのキー「entry_area」の値を変更してください。namesテーブルの値は参照しません。

* 受付用はwelcaom-frontのリポジトリを参照してください。
