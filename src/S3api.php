<?php
require 'vendor/autoload.php';
use Aws\S3\S3Client;

class S3api
{
  private $s3client;

  public function __construct($aws_access_key_id, $aws_secret_access_key, $region, $endpoint)
  {
    $options = [
        'credentials' => [
            'key'    => $aws_access_key_id,
            'secret' => $aws_secret_access_key,
        ],
        'region'   => $region,
        'endpoint' => $endpoint,
        'version'  => 'latest',
    ];

    // S3Clientを実行するとこんなWarningが3回くらい流れるので意図的に抑止
    // Warning: syntax error, unexpected '(' in Unknown on line 6
    // in /path/to/s3-mini-cli-php/vendor/aws/aws-sdk-php/src/functions.php on line 461
    error_reporting(0);
    $this->$s3client = new S3Client($options);
    error_reporting(1);

    return;
  }

  // https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-s3-2006-03-01.html#getobject
  public function GetObject($bucket, $key, $api_parameters)
  {
    try {
      $result = $this->$s3client->getObject([
        'Bucket' => $bucket,
        'Key'    => $key,
        'SaveAs' => $api_parameters['SaveAs'],
      ]);
    } catch (S3Exception $e) {
      echo $e->getMessage() . PHP_EOL;
    }

    return $result;
  }

  // https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-s3-2006-03-01.html#putobject
  public function PutObject($bucket, $key, $api_parameters)
  {
    try {
      $result = $this->$s3client->putObject([
        'ACL'        => $api_parameters['ACL'],
        'Bucket'     => $bucket,
        'Key'        => $key,
        'SourceFile' => $api_parameters['SourceFile'],
      ]);
    } catch (S3Exception $e) {
      echo $e->getMessage() . PHP_EOL;
    }

    return $result;
  }

  // https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-s3-2006-03-01.html#deleteobject
  public function DeleteObject($bucket, $key, $api_parameters)
  {
    try {
      $result = $this->$s3client->deleteObject([
        'Bucket' => $bucket,
        'Key'    => $key,
      ]);
    } catch (S3Exception $e) {
      echo $e->getMessage() . PHP_EOL;
    }

    return $result;
  }

  // https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-s3-2006-03-01.html#copyobject
  public function CopyObject($bucket, $key, $api_parameters)
  {
    try {
      $result = $this->$s3client->copyObject([
        'Bucket'     => $bucket,
        'Key'        => $key,
        'CopySource' => $api_parameters['CopySource'],
        'ACL'        => $api_parameters['ACL'],
      ]);
    } catch (S3Exception $e) {
      echo $e->getMessage() . PHP_EOL;
    }

    return $result;
  }
}

