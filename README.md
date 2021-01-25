## s3-mini-cli-php

S3のAPIを利用できるphp製の簡易CLIです。

### セットアップ

#### 1. composerの入手

https://getcomposer.org/download/ を参考にcomposerを入手します。
```
php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
php -r "if (hash_file('sha384', 'composer-setup.php') === '756890a4488ce9024fc62c56153228907f1545c228516cbf63f885e036d37e9a59d27d63f46af1d4d07ee0f76181c7d3') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"
php composer-setup.php
php -r "unlink('composer-setup.php');"
```

#### 2. aws-sdkのインストール

composerを利用してaws-sdkをインストールしてください。
```
./composer.phar require aws/aws-sdk-php
```

#### 3. アクセスキーを環境変数に設定する

アクセスキーを環境変数に設定します。
`.env.sample`というファイルをコピーして`.env`を用意して編集してください。
```
cp .env.sample .env
vi .env

アクセスキーを記載する
AWS_ACCESS_KEY_ID='********************'
AWS_SECRET_ACCESS_KEY='***********************'
```

### USAGE

現時点で利用できるのは `GetObject`/`PutObject`/`DeleteObject`/`CopyObject`の4つだけです。

```
Usage: ./s3-mini-cli --api GetObject|PutObject|DeleteObject|CopyObject --bucket BUCKET --key PATH/TO/KEY [OPTIONS]

OPTIONS:
  --usage
  --region (default : us-east-1)
  --endpoint (default : https://s3.amazonaws.com/)
  --acl (default : private)
  --save_as
  --source_file
  --copy_source
```

#### GetObjectの実行例

```
./s3-mini-cli --api GetObject --endpoint https://end.point.url/ --bucket bucket_name --key path/to/key --save_as /path/to/download
```
