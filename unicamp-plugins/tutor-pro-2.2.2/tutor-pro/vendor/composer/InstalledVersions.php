<?php











namespace Composer;

use Composer\Autoload\ClassLoader;
use Composer\Semver\VersionParser;






class InstalledVersions
{
private static $installed = array (
  'root' => 
  array (
    'pretty_version' => 'dev-master',
    'version' => 'dev-master',
    'aliases' => 
    array (
      0 => '2.x-dev',
    ),
    'reference' => 'fec7f8826a3f3eff561f79f14c6cd302a853422b',
    'name' => 'tutor-pro/dependencies',
  ),
  'versions' => 
  array (
    'brick/math' => 
    array (
      'pretty_version' => '0.9.3',
      'version' => '0.9.3.0',
      'aliases' => 
      array (
      ),
      'reference' => 'ca57d18f028f84f777b2168cd1911b0dee2343ae',
    ),
    'fgrosse/phpasn1' => 
    array (
      'pretty_version' => 'v2.3.0',
      'version' => '2.3.0.0',
      'aliases' => 
      array (
      ),
      'reference' => '20299033c35f4300eb656e7e8e88cf52d1d6694e',
    ),
    'firebase/php-jwt' => 
    array (
      'pretty_version' => 'v5.4.0',
      'version' => '5.4.0.0',
      'aliases' => 
      array (
      ),
      'reference' => 'd2113d9b2e0e349796e72d2a63cf9319100382d2',
    ),
    'google/apiclient' => 
    array (
      'pretty_version' => 'v2.10.1',
      'version' => '2.10.1.0',
      'aliases' => 
      array (
      ),
      'reference' => '11871e94006ce7a419bb6124d51b6f9ace3f679b',
    ),
    'google/apiclient-services' => 
    array (
      'pretty_version' => 'v0.212.0',
      'version' => '0.212.0.0',
      'aliases' => 
      array (
      ),
      'reference' => '2c4bd512502ad9cdfec8ea711ea1592c79d345e5',
    ),
    'google/auth' => 
    array (
      'pretty_version' => 'v1.18.0',
      'version' => '1.18.0.0',
      'aliases' => 
      array (
      ),
      'reference' => '21dd478e77b0634ed9e3a68613f74ed250ca9347',
    ),
    'guzzlehttp/guzzle' => 
    array (
      'pretty_version' => '7.3.0',
      'version' => '7.3.0.0',
      'aliases' => 
      array (
      ),
      'reference' => '7008573787b430c1c1f650e3722d9bba59967628',
    ),
    'guzzlehttp/promises' => 
    array (
      'pretty_version' => '1.4.1',
      'version' => '1.4.1.0',
      'aliases' => 
      array (
      ),
      'reference' => '8e7d04f1f6450fef59366c399cfad4b9383aa30d',
    ),
    'guzzlehttp/psr7' => 
    array (
      'pretty_version' => '1.8.2',
      'version' => '1.8.2.0',
      'aliases' => 
      array (
      ),
      'reference' => 'dc960a912984efb74d0a90222870c72c87f10c91',
    ),
    'minishlink/web-push' => 
    array (
      'pretty_version' => 'v6.0.5',
      'version' => '6.0.5.0',
      'aliases' => 
      array (
      ),
      'reference' => 'd87e9e3034ca2b95b1822b1b335e7761c14b89f6',
    ),
    'monolog/monolog' => 
    array (
      'pretty_version' => '2.3.4',
      'version' => '2.3.4.0',
      'aliases' => 
      array (
      ),
      'reference' => '437e7a1c50044b92773b361af77620efb76fff59',
    ),
    'phpseclib/phpseclib' => 
    array (
      'pretty_version' => '2.0.33',
      'version' => '2.0.33.0',
      'aliases' => 
      array (
      ),
      'reference' => 'fb53b7889497ec7c1362c94e61d8127ac67ea094',
    ),
    'psr/cache' => 
    array (
      'pretty_version' => '1.0.1',
      'version' => '1.0.1.0',
      'aliases' => 
      array (
      ),
      'reference' => 'd11b50ad223250cf17b86e38383413f5a6764bf8',
    ),
    'psr/http-client' => 
    array (
      'pretty_version' => '1.0.1',
      'version' => '1.0.1.0',
      'aliases' => 
      array (
      ),
      'reference' => '2dfb5f6c5eff0e91e20e913f8c5452ed95b86621',
    ),
    'psr/http-client-implementation' => 
    array (
      'provided' => 
      array (
        0 => '1.0',
      ),
    ),
    'psr/http-factory' => 
    array (
      'pretty_version' => '1.0.1',
      'version' => '1.0.1.0',
      'aliases' => 
      array (
      ),
      'reference' => '12ac7fcd07e5b077433f5f2bee95b3a771bf61be',
    ),
    'psr/http-message' => 
    array (
      'pretty_version' => '1.0.1',
      'version' => '1.0.1.0',
      'aliases' => 
      array (
      ),
      'reference' => 'f6561bf28d520154e4b0ec72be95418abe6d9363',
    ),
    'psr/http-message-implementation' => 
    array (
      'provided' => 
      array (
        0 => '1.0',
      ),
    ),
    'psr/log' => 
    array (
      'pretty_version' => '1.1.4',
      'version' => '1.1.4.0',
      'aliases' => 
      array (
      ),
      'reference' => 'd49695b909c3b7628b6289db5479a1c204601f11',
    ),
    'psr/log-implementation' => 
    array (
      'provided' => 
      array (
        0 => '1.0.0 || 2.0.0 || 3.0.0',
      ),
    ),
    'ralouphie/getallheaders' => 
    array (
      'pretty_version' => '3.0.3',
      'version' => '3.0.3.0',
      'aliases' => 
      array (
      ),
      'reference' => '120b605dfeb996808c31b6477290a714d356e822',
    ),
    'spomky-labs/base64url' => 
    array (
      'pretty_version' => 'v2.0.4',
      'version' => '2.0.4.0',
      'aliases' => 
      array (
      ),
      'reference' => '7752ce931ec285da4ed1f4c5aa27e45e097be61d',
    ),
    'tutor-pro/dependencies' => 
    array (
      'pretty_version' => 'dev-master',
      'version' => 'dev-master',
      'aliases' => 
      array (
        0 => '2.x-dev',
      ),
      'reference' => 'fec7f8826a3f3eff561f79f14c6cd302a853422b',
    ),
    'web-token/jwt-core' => 
    array (
      'pretty_version' => 'v2.2.11',
      'version' => '2.2.11.0',
      'aliases' => 
      array (
      ),
      'reference' => '53beb6f6c1eec4fa93c1c3e5d9e5701e71fa1678',
    ),
    'web-token/jwt-key-mgmt' => 
    array (
      'pretty_version' => 'v2.2.11',
      'version' => '2.2.11.0',
      'aliases' => 
      array (
      ),
      'reference' => '0b116379515700d237b4e5de86879078ccb09d8a',
    ),
    'web-token/jwt-signature' => 
    array (
      'pretty_version' => 'v2.2.11',
      'version' => '2.2.11.0',
      'aliases' => 
      array (
      ),
      'reference' => '015b59aaf3b6e8fb9f5bd1338845b7464c7d8103',
    ),
    'web-token/jwt-signature-algorithm-ecdsa' => 
    array (
      'pretty_version' => 'v2.2.11',
      'version' => '2.2.11.0',
      'aliases' => 
      array (
      ),
      'reference' => '44cbbb4374c51f1cf48b82ae761efbf24e1a8591',
    ),
    'web-token/jwt-util-ecc' => 
    array (
      'pretty_version' => 'v2.2.11',
      'version' => '2.2.11.0',
      'aliases' => 
      array (
      ),
      'reference' => '915f3fde86f5236c205620d61177b9ef43863deb',
    ),
  ),
);
private static $canGetVendors;
private static $installedByVendor = array();







public static function getInstalledPackages()
{
$packages = array();
foreach (self::getInstalled() as $installed) {
$packages[] = array_keys($installed['versions']);
}


if (1 === \count($packages)) {
return $packages[0];
}

return array_keys(array_flip(\call_user_func_array('array_merge', $packages)));
}









public static function isInstalled($packageName)
{
foreach (self::getInstalled() as $installed) {
if (isset($installed['versions'][$packageName])) {
return true;
}
}

return false;
}














public static function satisfies(VersionParser $parser, $packageName, $constraint)
{
$constraint = $parser->parseConstraints($constraint);
$provided = $parser->parseConstraints(self::getVersionRanges($packageName));

return $provided->matches($constraint);
}










public static function getVersionRanges($packageName)
{
foreach (self::getInstalled() as $installed) {
if (!isset($installed['versions'][$packageName])) {
continue;
}

$ranges = array();
if (isset($installed['versions'][$packageName]['pretty_version'])) {
$ranges[] = $installed['versions'][$packageName]['pretty_version'];
}
if (array_key_exists('aliases', $installed['versions'][$packageName])) {
$ranges = array_merge($ranges, $installed['versions'][$packageName]['aliases']);
}
if (array_key_exists('replaced', $installed['versions'][$packageName])) {
$ranges = array_merge($ranges, $installed['versions'][$packageName]['replaced']);
}
if (array_key_exists('provided', $installed['versions'][$packageName])) {
$ranges = array_merge($ranges, $installed['versions'][$packageName]['provided']);
}

return implode(' || ', $ranges);
}

throw new \OutOfBoundsException('Package "' . $packageName . '" is not installed');
}





public static function getVersion($packageName)
{
foreach (self::getInstalled() as $installed) {
if (!isset($installed['versions'][$packageName])) {
continue;
}

if (!isset($installed['versions'][$packageName]['version'])) {
return null;
}

return $installed['versions'][$packageName]['version'];
}

throw new \OutOfBoundsException('Package "' . $packageName . '" is not installed');
}





public static function getPrettyVersion($packageName)
{
foreach (self::getInstalled() as $installed) {
if (!isset($installed['versions'][$packageName])) {
continue;
}

if (!isset($installed['versions'][$packageName]['pretty_version'])) {
return null;
}

return $installed['versions'][$packageName]['pretty_version'];
}

throw new \OutOfBoundsException('Package "' . $packageName . '" is not installed');
}





public static function getReference($packageName)
{
foreach (self::getInstalled() as $installed) {
if (!isset($installed['versions'][$packageName])) {
continue;
}

if (!isset($installed['versions'][$packageName]['reference'])) {
return null;
}

return $installed['versions'][$packageName]['reference'];
}

throw new \OutOfBoundsException('Package "' . $packageName . '" is not installed');
}





public static function getRootPackage()
{
$installed = self::getInstalled();

return $installed[0]['root'];
}







public static function getRawData()
{
return self::$installed;
}



















public static function reload($data)
{
self::$installed = $data;
self::$installedByVendor = array();
}




private static function getInstalled()
{
if (null === self::$canGetVendors) {
self::$canGetVendors = method_exists('Composer\Autoload\ClassLoader', 'getRegisteredLoaders');
}

$installed = array();

if (self::$canGetVendors) {
foreach (ClassLoader::getRegisteredLoaders() as $vendorDir => $loader) {
if (isset(self::$installedByVendor[$vendorDir])) {
$installed[] = self::$installedByVendor[$vendorDir];
} elseif (is_file($vendorDir.'/composer/installed.php')) {
$installed[] = self::$installedByVendor[$vendorDir] = require $vendorDir.'/composer/installed.php';
}
}
}

$installed[] = self::$installed;

return $installed;
}
}
