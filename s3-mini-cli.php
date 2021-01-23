<?php
require 'vendor/autoload.php';
use Aws\S3\S3Client;

main();


// https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-s3-2006-03-01.html#getobject
function GetObject($s3client, $bucket, $key, $save_as)
{
  try { 
    $result = $s3client->getObject([
      'Bucket' => $bucket,
      'Key'    => $key,
      'SaveAs' => $save_as,
    ]);
  } catch (S3Exception $e) {
    echo $e->getMessage() . PHP_EOL;
  }

  return $result;
}


// https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-s3-2006-03-01.html#putobject
function PutObject($s3client, $acl, $bucket, $key, $source_file)
{
  try { 
    $result = $s3client->putObject([
      'ACL'        => $acl,
      'Bucket'     => $bucket,
      'Key'        => $key,
      'SourceFile' => $source_file,
    ]);
  } catch (S3Exception $e) {
    echo $e->getMessage() . PHP_EOL;
  }

  return $result;
}


// https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-s3-2006-03-01.html#deleteobject
function DeleteObject($s3client, $bucket, $key)
{
  try { 
    $result = $s3client->deleteObject([
      'Bucket' => $bucket,
      'Key'    => $key,
    ]);
  } catch (S3Exception $e) {
    echo $e->getMessage() . PHP_EOL;
  }

  return $result;
}


function usage()
{
  $messages = <<< EOM
Usage: php s3-mini-cli.php --api GetObject|PutObject|DeleteObject --bucket BUCKET --key PATH/TO/KEY [OPTIONS]

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

  if ( !$args['usage'] )
  {
    usage();
    exit;
  }

  $usage       = $args['usage'];
  $api         = $args['api'];
  $bucket      = $args['bucket'];
  $region      = $args['region'] ?: 'us-east-1';
  $endpoint    = $args['endpoint'] ?: 'https://s3.amazonaws.com/';
  $acl         = $args['acl'] ?: 'private';
  $key         = $args['key'];
  $save_as     = $args['save_as'];
  $source_file = $args['source_file'];
  $copy_source = $args['copy_source'];
  
  $options = [
      'credentials' => [
          'key'    => getenv('AWS_ACCESS_KEY_ID'),
          'secret' => getenv('AWS_SECRET_ACCESS_KEY'),
      ],
      'region'   => $region,
      'endpoint' => $endpoint,
      'version'  => 'latest',
  ];
 
  // S3Clientを実行するとこんなWarningが3回くらい流れるので意図的に抑止
  // Warning: syntax error, unexpected '(' in Unknown on line 6
  // in /path/to/s3-mini-cli-php/vendor/aws/aws-sdk-php/src/functions.php on line 461
  error_reporting(0); 
  $s3client = new S3Client($options);
  error_reporting(1); 

  switch($api)
  {
    case "GetObject":
      $result = GetObject($s3client, $bucket, $key, $save_as);
      break;

    case "PutObject":
      $result = PutObject($s3client, $acl, $bucket, $key, $source_file);
      break;

    case "DeleteObject":
      $result = DeleteObject($s3client, $bucket, $key);
      break;

    default:
      usage();
      break;
  }

  echo $result;
}
