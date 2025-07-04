# チャットツール
PHP, MySQL の学習のために作った Discord のようなチャットアプリ

## 動作環境
ローカルでテストするには XAMPP などで Apache, MySQL を有効化します。

## ディレクトリ構成
```
chattool/
├─ backends/
│ ├─ dbconnect.php（データベース接続）
│ ├─ delete_account.php（アカウント削除処理）
│ ├─ delete_channel.php（チャンネル削除処理）
│ ├─ delete_message.php（メッセージ削除処理）
│ ├─ login_check.php（ログインチェック）
│ └─ logout.php（ログアウト処理）
├─ components/
│ ├─ head.php（headタグの共通部分）
│ ├─ sidebar.php（サイドバー）
│ └─ toast.php（トースト通知）
├─ icons/
├─ scripts/
│ ├─ imgPreviewer.js（ファイルアップロード時のプレビュー表示）※1
│ └─ modal.js（モーダルの制御）
├─ styles/
│ ├─ form.css（フォーム）
│ ├─ global.css（全体の共通部分）
│ ├─ login.css（ログイン前のページ）
│ ├─ main.css（ログイン後のページ）
│ └─ sidebar.css（サイドバー）
├─ uploads/（プロフィール画像のファイルを格納）
│ └─ default.svg（プロフィール画像が未設定の場合に表示する画像）
│
├─ advanced_settings.php（高度な設定ページ）
├─ confirm.php（アカウント作成確認ページ）
├─ create_ch.php（チャンネル作成ページ）
├─ edit.ch.php（チャンネル編集ページ）
├─ edit_email.php（メールアドレス変更ページ）
├─ edit_profile.php（プロフィール変更ページ）
├─ edit_pw.php（パスワード変更ページ）
├─ login.php（ログインページ）
├─ main.php（チャットページ）
└─ register.php（アカウント作成ページ）
```
※1 [ImgPreviewer](https://github.com/yuki00yossi/ImgPreviewer)