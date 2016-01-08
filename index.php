<?php 
/*
    Disclamer : You will puke by reading this code. Fixng it could lead to critical heart failure.
 */

require __DIR__ . '/vendor/koraktor/steam-condenser/lib/steam-condenser.php';

$source_game_info = array('tf' => 232250, 'csgo' => 740);

$hls_server_ip = '188.165.231.218';

// json from Valve Master server
$hls_steamapi_url = 'http://api.steampowered.com/ISteamApps/GetServersAtAddress/v0001?addr=' . $hls_server_ip . '&format=json';
$hls_steamapi_json = json_decode(file_get_contents($hls_steamapi_url), true);
$hls_online_servers = $hls_steamapi_json['response']['servers'];

$hls_server_info = array();
$hls_server_all_info = array();
$hls_server_gamedirname
 = array();

function get_source_server_info($port) {
    global $hls_server_ip;
    global $hls_server_info;

    $server = new SourceServer( $hls_server_ip, $port );
    $server->initialize();
    array_push($hls_server_info, $server->getServerInfo() );
}

// Extracting & storing heartlessgaming server info from the valve masterserver
foreach ($hls_online_servers as $hls_online_server) {
    $hls_server_all_info[ $hls_online_server['gamedir'] ] = $hls_online_server['gameport'];
}

foreach ($hls_server_all_info as $gamedir => $port) {

    foreach ($source_game_info as $source_gamedir => $source_appid) {
        if ($source_gamedir === $gamedir) {
            get_source_server_info( $port );
        }
    }

}

echo json_encode($hls_server_info);
