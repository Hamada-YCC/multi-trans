<?php
if (!ini_get('display_errors')) {
    ini_set('display_errors', '1');
}

include("lib/OAuth/OAuthStore.php");
include("lib/OAuth/OAuthRequester.php");

$request = json_decode(file_get_contents("php://input"));
$text = $request->text;
$lang = $request->lang;

define("NAME",   "yhamada");
define("KEY",    "a70b0c016048092033d3ba80e99631b5062b5964f");
define("SECRET", "e06c484a620e27d35e0183cbae44d659");
define("URL",    "https://mt-auto-minhon-mlt.ucri.jgn-x.jp/api/mt/generalNT_{$lang}/");
define("TEXT",   $text);

$options = array("consumer_key" => KEY, "consumer_secret" => SECRET);
OAuthStore::instance("2Leg", $options);

$method = "POST";
$params = array(
	"key"  => KEY,
	"name" => NAME,
	"text" => TEXT,
);

$result = '';
$result['languege'] = $lang;
$result['original'] = '';
$result['translation'] = '';

try {
	$request = new OAuthRequester(URL, $method, $params);
	$responce = $request->doRequest();
    $responce = json_decode($responce['body']);
    $result['original']    = $responce->resultset->request->text;
    $result['translation'] = $responce->resultset->result->text;
	echo json_encode($result);
} catch(OAuthException2 $e) {
	echo $e->getMessage();
}
