<?php
require 'S3api.php';
require 'vendor/autoload.php';

main();

function usage()
{
  $messages = <<< EOM
Usage: ./s3-mini-cli --api GetObject|PutObject|DeleteObject|CopyObject --bucket BUCKET --key PATH/TO/KEY [OPTIONS]

OPTIONS:
  --usage
  --region (default : us-east-1)
  --endpoint (default : https://s3.amazonaws.com/)
  --acl (default : private)
  --save_as
  --source_file
  --copy_source
EOM;

  printf("%s\n", $messages);
}


function main()
{
  $longopts = array(
    "usage",
    "api:",
    "bucket:",
    "region:",
    "endpoint:",
    "acl:",
    "key:",
    "save_as:",
    "source_file:",
    "copy_source:",
  );
  $args = getopt(null, $longopts);

  if ( isset($args['usage']) )
  {
    usage();
    exit;
  }

  $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
  $dotenv->load();

  $aws_access_key_id     = $_ENV['AWS_ACCESS_KEY_ID'];
  $aws_secret_access_key = $_ENV['AWS_SECRET_ACCESS_KEY'];
  $region                = $args['region'] ?: 'us-east-1';
  $endpoint              = $args['endpoint'] ?: 'https://s3.amazonaws.com/';
  $api                   = $args['api'];

  $bucket                       = $args['bucket'];
  $key                          = $args['key'];
  $api_parameters['ACL']        = $args['acl'] ?: 'private';
  $api_parameters['SaveAs']     = $args['save_as'];
  $api_parameters['SourceFile'] = $args['source_file'];
  $api_parameters['CopySource'] = $args['copy_source'];

  $s3api = new S3api($aws_access_key_id, $aws_secret_access_key, $region, $endpoint);

  switch($api)
  {
    case "GetObject":
      $result = $s3api->GetObject($bucket, $key, $api_parameters);
      break;

    case "PutObject":
      $result = $s3api->PutObject($bucket, $key, $api_parameters);
      break;

    case "DeleteObject":
      $result = $s3api->DeleteObject($bucket, $key, $api_parameters);
      break;

    case "CopyObject":
      $result = $s3api->CopyObject($bucket, $key, $api_parameters);
      break;

    default:
      usage();
      break;
  }

  echo $result;
}
