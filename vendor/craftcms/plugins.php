<?php

$vendorDir = dirname(__DIR__);
$rootDir = dirname(dirname(__DIR__));

return array (
  'supercool/buttonbox' => 
  array (
    'class' => 'supercool\\buttonbox\\ButtonBox',
    'basePath' => $vendorDir . '/supercool/buttonbox/src',
    'handle' => 'buttonbox',
    'aliases' => 
    array (
      '@supercool/buttonbox' => $vendorDir . '/supercool/buttonbox/src',
    ),
    'name' => 'Button Box',
    'version' => '2.0.4',
    'schemaVersion' => '1.0.0',
    'description' => 'A collection of utility field types for Craft',
    'developer' => 'Supercool',
    'developerUrl' => 'http://supercooldesign.co.uk',
    'documentationUrl' => 'https://github.com/supercool/buttonbox/blob/master/README.md',
    'changelogUrl' => 'https://raw.githubusercontent.com/supercool/buttonbox/master/CHANGELOG.md',
    'hasCpSettings' => false,
    'hasCpSection' => false,
  ),
  'craftcms/redactor' => 
  array (
    'class' => 'craft\\redactor\\Plugin',
    'basePath' => $vendorDir . '/craftcms/redactor/src',
    'handle' => 'redactor',
    'aliases' => 
    array (
      '@craft/redactor' => $vendorDir . '/craftcms/redactor/src',
    ),
    'name' => 'Redactor',
    'version' => '2.10.8',
    'description' => 'Edit rich text content in Craft CMS using Redactor by Imperavi.',
    'developer' => 'Pixel & Tonic',
    'developerUrl' => 'https://pixelandtonic.com/',
    'developerEmail' => 'support@craftcms.com',
    'documentationUrl' => 'https://github.com/craftcms/redactor/blob/v2/README.md',
  ),
  'craftcms/aws-s3' => 
  array (
    'class' => 'craft\\awss3\\Plugin',
    'basePath' => $vendorDir . '/craftcms/aws-s3/src',
    'handle' => 'aws-s3',
    'aliases' => 
    array (
      '@craft/awss3' => $vendorDir . '/craftcms/aws-s3/src',
    ),
    'name' => 'Amazon S3',
    'version' => '1.3.0',
    'description' => 'Amazon S3 integration for Craft CMS',
    'developer' => 'Pixel & Tonic',
    'developerUrl' => 'https://pixelandtonic.com/',
    'developerEmail' => 'support@craftcms.com',
    'documentationUrl' => 'https://github.com/craftcms/aws-s3/blob/master/README.md',
  ),
);
