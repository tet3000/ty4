<?php
header("Content-type: text/plain");

$path = str_replace ('list.php', 'claro_get.php', "http://{$_SERVER['HTTP_HOST']}{$_SERVER['PHP_SELF']}");

$pais = $_GET['AR'];

$api = file_get_contents("https://mfwkweb-api.clarovideo.net/services/epg/channel?HKS=(web60dc98ba7c02d)&api_version=v5.92&authpn=html5player&authpt=ad5565dfgsftr&dateTimeShift=false&date_from=20210704000000&date_to=20210705000000&device_category=web&device_id=web&device_manufacturer=windows&device_model=html5&device_so=Edge&device_type=html5&epg_version=41375&format=json&node_id=19442&quantity=200&region=argentina&user_id=55251992".$AR);

$search = array ('ñ', 'á', 'é', 'í', 'ó', 'ú', 'Ñ', 'Á', 'É', 'Í', 'Ó', 'Ú');
$replace = array ('n', 'a', 'e', 'i', 'o', 'u', 'N', 'A', 'E', 'I', 'O', 'U');
$api = str_replace($search, $replace, $api);

$data = json_decode($api);

foreach($data->response->channels as $canal) {
    $canales["$canal->name"]['id'] = $canal->group_id;
    $canales["$canal->name"]['image'] = $canal->image;
}

ksort($canales);

echo '#EXTM3U'.PHP_EOL;
foreach($canales as $canal => $ch) {
    echo '#EXTINF:-1 tvg-logo="'.$ch['image'].'" group-title="'.$pais.'", '.$canal.PHP_EOL;
    echo $path."?id=".$ch['id']."&f=.m3u8".PHP_EOL;
}