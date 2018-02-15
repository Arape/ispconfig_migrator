<?php

$USER = 'portal';
$PASS = 'uLcKfa92';
$DB_ISP = 'dbispconfig';
$DB_FW = 'freeweb';
$FOMUNKAKONYVTAR='/home/arape/FWexport/FW_export'; //webmappa exportnál
$SQLDUMPBOTUSER = 'arapebot';
$SQLDUMPBOTUSERPASS = 'uLcKfa92';
$DOWNLOADURLDIR = '/var/www/arape.freeweb.hu/web/';

//argumentumok feldolgozása és user manual
if (!empty($argv[1])) {
    $client = $argv[1];
} else {
    $client = 'help';
}
if ($client == 'help') {
    print_r("Használat php után első paraméter a KLIENS neve pl 'gyongyikozmetika' 'bekevar' 'lunya' vagy 'turulnemzet' pecsworkok ");
    echo PHP_EOL;
    exit();
}



print_r("FW költöztető v0.5");
echo PHP_EOL;

///////////////////////////
///freeweb DBből username alapján ispconfig userID
///////////////////////////
$link = mysqli_connect("rb02-gm", $USER, $PASS, $DB_FW);
mysqli_set_charset($link, "utf8");
/* check connection */
if (mysqli_connect_errno()) {
    printf("Connect failed: %s\n", mysqli_connect_error());
    exit();
}

$query = "select username, isp_clientid from hosting_users where username='" . $client . "'";
$result = mysqli_query($link, $query);

$tomb2 = array();
while ($tomb = mysqli_fetch_array($result, MYSQLI_NUM))
/* numeric array */ {   //var_dump ($tomb);.
    $tomb2[] = array(
        "username" => $tomb[0], "isp_clientid" => $tomb[1],
    );
}

//var_dump($tomb2);

///////////////////////////
///CLIENT adatok kigyűjtése ispconfig DBből ispconfig userID alapján 
///////////////////////////
$link2 = mysqli_connect("rb02-gm", $USER, $PASS, $DB_ISP);
mysqli_set_charset($link2, "utf8");
/* check connection */
if (mysqli_connect_errno()) {
    printf("Connect failed: %s\n", mysqli_connect_error());
    exit();
}

$query2 = "select company_name, contact_name, street, zip, city, state, country, telephone, email, internet, default_mailserver, limit_mailcatchall, limit_mailfilter, limit_mailquota, limit_sendquota, limit_web_domain, limit_web_quota, limit_web_subdomain, limit_web_aliasdomain, limit_ftp_user, ssh_chroot, default_dbserver, limit_database, limit_cron, limit_cron_type, limit_cron_frequency, limit_traffic_quota, username, password, template_master, web_php_options from client where username='" . $client . "'";
$result2 = mysqli_query($link2, $query2);

$tomb4 = array();
while ($tomb3 = mysqli_fetch_array($result2, MYSQLI_NUM))
/* numeric array */ {
    $tomb4[] = array(
        "company_name" => $tomb3[0],
        "contact_name" => $tomb3[1],
        "street" => $tomb3[2],
        "zip" => $tomb3[3],
        "city" => $tomb3[4],
        "state" => $tomb3[5],
        "country" => $tomb3[6],
        "telephone" => $tomb3[7],
        "email" => $tomb3[8],
        "internet" => $tomb3[9],
        "default_mailserver" => $tomb3[10],
        "limit_mailcatchall" => $tomb3[11],
        "limit_mailfilter" => $tomb3[12],
        "limit_mailquota" => $tomb3[13],
        "limit_sendquota" => $tomb3[14],
        "limit_web_domain" => $tomb3[15],
        "limit_web_quota" => $tomb3[16],
        "limit_web_subdomain" => $tomb3[17],
        "limit_web_aliasdomain" => $tomb3[18],
        "limit_ftp_user" => $tomb3[19],
        "ssh_chroot" => $tomb3[20],
        "default_dbserver" => $tomb3[21],
        "limit_database" => $tomb3[22],
        "limit_cron" => $tomb3[23],
        "limit_cron_type" => $tomb3[24],
        "limit_cron_frequency" => $tomb3[25],
        "limit_traffic_quota" => $tomb3[26],
        "username" => $tomb3[27],
        "password" => $tomb3[28],
        "template_master" => $tomb3[29],
        "web_php_options" => $tomb3[30],
    );
}

///////////////////////////
///MAGIC, NE AKARD MEGÉRTENI 
///////////////////////////
//$clientkokanyolt = $tomb2[0]["isp_clientid"] + 1;
//ITT KELL ÁTIRNI A PROGIT

$NEMTUDOMMITCSINALOK =  'client' . $tomb2[0]["isp_clientid"];

$link53 = mysqli_connect("rb02-gm", $USER, $PASS, $DB_ISP);
mysqli_set_charset($link53, "utf8");
/* check connection */
if (mysqli_connect_errno()) {
    printf("Connect failed: %s\n", mysqli_connect_error());
    exit();
}

//var_dump($clientkokanyolt);

$query53 = "select sys_userid from web_domain where system_group='" . $NEMTUDOMMITCSINALOK . "'";
$result53 = mysqli_query($link53, $query53);

$tomb56 = array();
while ($tomb55 = mysqli_fetch_array($result53, MYSQLI_NUM))
/* numeric array */ {
    $tomb56[] = array(
        "sys_userid" => $tomb55[0],
    );
}

$clientkokanyolt = $tomb56[0]["sys_userid"];
//echo 'FASZOM';
//echo $NEMTUDOMMITCSINALOK;

//echo $clientkokanyolt;

///////////////////////////
///Webhely domain adatok kigyűjtése ispconfig DBből ispconfig userID alapján 
///////////////////////////
$link3 = mysqli_connect("rb02-gm", $USER, $PASS, $DB_ISP);
mysqli_set_charset($link3, "utf8");
/* check connection */
if (mysqli_connect_errno()) {
    printf("Connect failed: %s\n", mysqli_connect_error());
    exit();
}

//var_dump($clientkokanyolt);

$query3 = "select domain_id, domain, type, parent_domain_id, vhost_type, document_root, system_user, system_group, hd_quota, traffic_quota, suexec, is_subdomainwww, subdomain, ssl_country, stats_password, stats_type, allow_override, php_fpm_use_socket, pm_max_children, pm_start_servers, pm_min_spare_servers, pm_max_spare_servers, php_open_basedir, `force`, active, traffic_quota_lock, `php` from web_domain where sys_userid='" . $clientkokanyolt . "'";
$result3 = mysqli_query($link3, $query3);

$tomb6 = array();
while ($tomb5 = mysqli_fetch_array($result3, MYSQLI_NUM))
/* numeric array */ {
    $tomb6[] = array(
        "domain_id" => $tomb5[0],
        "domain" => $tomb5[1],
        "type" => $tomb5[2],
        "parent_domain_id" => $tomb5[3],
        "vhost_type" => $tomb5[4],
        "document_root" => $tomb5[5],
        "system_user" => $tomb5[6],
        "system_group" => $tomb5[7],
        "hd_quota" => $tomb5[8],
        "traffic_quota" => $tomb5[9],
        "suexec" => $tomb5[10],
        "is_subdomainwww" => $tomb5[11],
        "subdomain" => $tomb5[12],
        "ssl_country" => $tomb5[13],
        "stats_password" => $tomb5[14],
        "stats_type" => $tomb5[15],
        "allow_override" => $tomb5[16],
        "php_fpm_use_socket" => $tomb5[17],
        "pm_max_children" => $tomb5[18],
        "pm_start_servers" => $tomb5[19],
        "pm_min_spare_servers" => $tomb5[20],
        "pm_max_spare_servers" => $tomb5[21],
        "php_open_basedir" => $tomb5[22],
        "force" => $tomb5[23],
        "active" => $tomb5[24],
        "traffic_quota_lock" => $tomb5[25],
        "php" => $tomb5[26],
    );
}

///////////////////////////
///Webhely adatbázis adatok kigyűjtése ispconfig DBből ispconfig userID alapján 
///////////////////////////

$link4 = mysqli_connect("rb02-gm", $USER, $PASS, $DB_ISP);
mysqli_set_charset($link4, "utf8");
/* check connection */
if (mysqli_connect_errno()) {
    printf("Connect failed: %s\n", mysqli_connect_error());
    exit();
}

$query4 = "select type, database_name, database_user, database_password, database_charset, quota, remote_access, remote_ips, active from web_database where sys_userid='" . $clientkokanyolt . "'";
$result4 = mysqli_query($link4, $query4);

$tomb8 = array();
while ($tomb7 = mysqli_fetch_array($result4, MYSQLI_NUM))
/* numeric array */ {
    $tomb8[] = array(
        "type" => $tomb7[0],
        "database_name" => $tomb7[1],
        "database_user" => $tomb7[2],
        "database_password" => $tomb7[3],
        "database_charset" => $tomb7[4],
        "quota" => $tomb7[5],
        "remote_access" => $tomb7[6],
        "remote_ips" => $tomb7[7],
        "active" => $tomb7[8],
    );
}

///////////////////////////
///EMAIL domain adatok kigyűjtése ispconfig DBből ispconfig userID alapján 
///////////////////////////

$link5 = mysqli_connect("rb02-gm", $USER, $PASS, $DB_ISP);
mysqli_set_charset($link5, "utf8");
/* check connection */
if (mysqli_connect_errno()) {
    printf("Connect failed: %s\n", mysqli_connect_error());
    exit();
}

$query5 = "select domain, active, minemx from mail_domain where sys_userid='" . $clientkokanyolt . "'";
$result5 = mysqli_query($link5, $query5);

$tomb10 = array();
while ($tomb9 = mysqli_fetch_array($result5, MYSQLI_NUM))
/* numeric array */ {
    $tomb10[] = array(
        "domain" => $tomb9[0],
        "active" => $tomb9[1],
        "minemx" => $tomb9[2],
    );
}

///////////////////////////
///EMAIL fiók adatok kigyűjtése ispconfig DBből ispconfig userID alapján 
///////////////////////////

$link6 = mysqli_connect("rb02-gm", $USER, $PASS, $DB_ISP);
mysqli_set_charset($link6, "utf8");
/* check connection */
if (mysqli_connect_errno()) {
    printf("Connect failed: %s\n", mysqli_connect_error());
    exit();
}

$query6 = "select email, login, password, name, maildir, quota, sendquota, sent, alltime_sent, alltime_sent_volume, cc, `to`, homedir, autoresponder, autoresponder_start_date, autoresponder_end_date, autoresponder_text, lastlogin, lastloginip, move_junk, custom_mailfilter, postfix, formpost, access, disableimap, disablepop3, disabledeliver, disablesmtp, disablesieve, disablelda, updated from mail_user where sys_userid='" . $clientkokanyolt . "'";
$result6 = mysqli_query($link6, $query6);

$tomb12 = array();
while ($tomb11 = mysqli_fetch_array($result6, MYSQLI_NUM))
/* numeric array */ {
    $tomb12[] = array(
        "email" => $tomb11[0],
        "login" => $tomb11[1],
        "password" => $tomb11[2],
        "name" => $tomb11[3],
        "maildir" => $tomb11[4],
        "quota" => $tomb11[5],
        "sendquota" => $tomb11[6],
        "sent" => $tomb11[7],
        "alltime_sent" => $tomb11[8],
        "alltime_sent_volume" => $tomb11[9],
        "cc" => $tomb11[10],
        "to" => $tomb11[11],
        "homedir" => $tomb11[12],
        "autoresponder" => $tomb11[13],
        "autoresponder_start_date" => $tomb11[14],
        "autoresponder_end_date" => $tomb11[15],
        "autoresponder_text" => $tomb11[16],
        "lastlogin" => $tomb11[17],
        "lastloginip" => $tomb11[18],
        "move_junk" => $tomb11[19],
        "custom_mailfilter" => $tomb11[20],
        "postfix" => $tomb11[21],
        "formpost" => $tomb11[22],
        "access" => $tomb11[23],
        "disableimap" => $tomb11[24],
        "disablepop3" => $tomb11[25],
        "disabledeliver" => $tomb11[26],
        "disablesmtp" => $tomb11[27],
        "disablesieve" => $tomb11[28],
        "disablelda" => $tomb11[29],
        "updated" => $tomb11[30],
    );
}

///////////////////////////
///EMAIL forwarding adatok kigyűjtése ispconfig DBből ispconfig userID alapján 
///////////////////////////

$link7 = mysqli_connect("rb02-gm", $USER, $PASS, $DB_ISP);
mysqli_set_charset($link7, "utf8");
/* check connection */
if (mysqli_connect_errno()) {
    printf("Connect failed: %s\n", mysqli_connect_error());
    exit();
}
$tomb14 = array();
$query7 = "select source, destination, type, active from mail_forwarding where sys_userid='" . $clientkokanyolt . "'";
$result7 = mysqli_query($link7, $query7);

while ($tomb13 = mysqli_fetch_array($result7, MYSQLI_NUM))
/* numeric array */ {
    $tomb14[] = array(
        "source" => $tomb13[0],
        "destination" => $tomb13[1],
        "type" => $tomb13[2],
        "active" => $tomb13[2],
    );
}

///////////////////////////
///EMAIL filterek kigyűjtése ispconfig DBből ispconfig userID alapján 
///////////////////////////

$link8 = mysqli_connect("rb02-gm", $USER, $PASS, $DB_ISP);
mysqli_set_charset($link8, "utf8");
/* check connection */
if (mysqli_connect_errno()) {
    printf("Connect failed: %s\n", mysqli_connect_error());
    exit();
}

$query8 = "select mailuser_id, rulename, source, searchterm, op, action, target, active, `force` from mail_user_filter where sys_userid='" . $clientkokanyolt . "'";
$result8 = mysqli_query($link8, $query8);

$tomb16 = array();

while ($tomb15 = mysqli_fetch_array($result8, MYSQLI_NUM))
/* numeric array */ {
    $tomb16[] = array(
        "mailuser_id" => $tomb15[0],
        "rulename" => $tomb15[1],
        "source" => $tomb15[2],
        "searchterm" => $tomb15[3],
        "op" => $tomb15[4],
        "action" => $tomb15[5],
        "target" => $tomb15[6],
        "active" => $tomb15[7],
        "force" => $tomb15[8],
    );
}

///////////////////////////
///FTP userek kigyűjtése ispconfig DBből ispconfig userID alapján 
///////////////////////////

$link9 = mysqli_connect("rb02-gm", $USER, $PASS, $DB_ISP);
mysqli_set_charset($link9, "utf8");
/* check connection */
if (mysqli_connect_errno()) {
    printf("Connect failed: %s\n", mysqli_connect_error());
    exit();
}

$query9 = "select parent_domain_id, username, password, quota_size, quota_size_used, fromip, lastlogin, lastloginip, active, uid, gid, dir, quota_files, quota_files_used, dynamic, ul_ratio, dl_ratio, ul_bandwidth, dl_bandwidth from ftp_user where sys_userid='" . $clientkokanyolt . "'";
$result9 = mysqli_query($link9, $query9);

$tomb18 = array();

while ($tomb17 = mysqli_fetch_array($result9, MYSQLI_NUM))
/* numeric array */ {
    $tomb18[] = array(
        "parent_domain_id" => $tomb17[0],
        "username" => $tomb17[1],
        "password" => $tomb17[2],
        "quota_size" => $tomb17[3],
        "quota_size_used" => $tomb17[4],
        "fromip" => $tomb17[5],
        "lastlogin" => $tomb17[6],
        "lastloginip" => $tomb17[7],
        "active" => $tomb17[8],
        "uid" => $tomb17[9],
        "gid" => $tomb17[10],
        "dir" => $tomb17[11],
        "quota_files" => $tomb17[12],
        "quota_files_used" => $tomb17[13],
        "dynamic" => $tomb17[14],
        "ul_ratio" => $tomb17[15],
        "dl_ratio" => $tomb17[16],
        "ul_bandwidth" => $tomb17[17],
        "dl_bandwidth" => $tomb17[18],
    );
}

///////////////////////////
///CRON jobok kigyűjtése ispconfig DBből ispconfig userID alapján 
///////////////////////////

$link10 = mysqli_connect("rb02-gm", $USER, $PASS, $DB_ISP);
mysqli_set_charset($link10, "utf8");
/* check connection */
if (mysqli_connect_errno()) {
    printf("Connect failed: %s\n", mysqli_connect_error());
    exit();
}

$query10 = "select parent_domain_id, type, command, run_min, run_hour, run_mday, run_month, run_wday, active from cron where sys_userid='" . $clientkokanyolt . "'";
$result10 = mysqli_query($link10, $query10);

$tomb20 = array();

while ($tomb19 = mysqli_fetch_array($result10, MYSQLI_NUM))
/* numeric array */ {
    $tomb20[] = array(
        "parent_domain_id" => $tomb19[0],
        "type" => $tomb19[1],
        "command" => $tomb19[2],
        "run_min" => $tomb19[3],
        "run_hour" => $tomb19[4],
        "run_mday" => $tomb19[5],
        "run_month" => $tomb19[6],
        "run_wday" => $tomb19[7],
        "active" => $tomb19[8],
    );
}

//var_dump($tomb4);
//var_dump($tomb6);
//var_dump($tomb8);
//var_dump($tomb10);
//var_dump($tomb12);
//var_dump($tomb14);
//var_dump($tomb16);
//var_dump($tomb18);

if (!file_exists($FOMUNKAKONYVTAR . "/" . $client ."_adatok")) {
    mkdir($FOMUNKAKONYVTAR . "/" . $client ."_adatok", 0777, true);
}

$fokonyvtar = $FOMUNKAKONYVTAR . "/" . $client ."_adatok";

//ÜRITES

$PARANCS = "sudo unlink /var/www/arape.freeweb.hu/web/" . $client .  ".client.txt "
        . "&& sudo unlink /var/www/arape.freeweb.hu/web/" . $client .  ".db.txt "
        . "&& sudo unlink /var/www/arape.freeweb.hu/web/" . $client .  ".domain.txt  "
        . "&& sudo unlink /var/www/arape.freeweb.hu/web/" . $client .  ".maildomain.txt "
        . "&& sudo unlink /var/www/arape.freeweb.hu/web/" . $client .  ".mailbox.txt "
        . "&& sudo unlink /var/www/arape.freeweb.hu/web/" . $client .  ".mailforward.txt "
        . "&& sudo unlink /var/www/arape.freeweb.hu/web/" . $client .  ".mailfilter.txt "
        . "&& sudo unlink /var/www/arape.freeweb.hu/web/" . $client .  ".ftp.txt "
        . "&& sudo unlink /var/www/arape.freeweb.hu/web/" . $client .  ".tar.gz "
        . "&& sudo unlink /var/www/arape.freeweb.hu/web/" . $client .  ".vmail.tar.gz "
        . "&& sudo unlink /var/www/arape.freeweb.hu/web/" . $client .  ".sql "
        . "&& sudo unlink /var/www/arape.freeweb.hu/web/" . $client .  ".cron.txt ";
       
        $process = exec($PARANCS, $output, $return);
        



file_put_contents("$fokonyvtar/$client.txt", "");
file_put_contents("$fokonyvtar/$client.client.txt", "");
file_put_contents("$fokonyvtar/$client.domain.txt", "");
file_put_contents("$fokonyvtar/$client.db.txt", "");
file_put_contents("$fokonyvtar/$client.maildomain.txt", "");
file_put_contents("$fokonyvtar/$client.mailbox.txt", "");
file_put_contents("$fokonyvtar/$client.mailforward.txt", "");
file_put_contents("$fokonyvtar/$client.mailfilter.txt", "");
file_put_contents("$fokonyvtar/$client.ftp.txt", "");
file_put_contents("$fokonyvtar/$client.cron.txt", "");

//FILEBA BAKOLÁS

file_put_contents("$fokonyvtar/$client.txt", json_encode($tomb2), 8);
file_put_contents("$fokonyvtar/$client.client.txt", json_encode($tomb4), 8);
file_put_contents("$fokonyvtar/$client.domain.txt", json_encode($tomb6), 8);
file_put_contents("$fokonyvtar/$client.db.txt", json_encode($tomb8), 8);
file_put_contents("$fokonyvtar/$client.maildomain.txt", json_encode($tomb10), 8);
file_put_contents("$fokonyvtar/$client.mailbox.txt", json_encode($tomb12), 8);
file_put_contents("$fokonyvtar/$client.mailforward.txt", json_encode($tomb14), 8);
file_put_contents("$fokonyvtar/$client.mailfilter.txt", json_encode($tomb16), 8);
file_put_contents("$fokonyvtar/$client.ftp.txt", json_encode($tomb18), 8);
file_put_contents("$fokonyvtar/$client.cron.txt", json_encode($tomb20), 8);


///////////////////////////
///UI és kimenet generáló 
///////////////////////////

echo PHP_EOL;
echo "\033[32m FW költöztető v0.5 - Arape \033[0m \n";
echo "--------------------------------------------\n";
echo PHP_EOL;
echo "User költözés alatt: \033[31m " . $client . " \033[0m \n";
echo "ISPconfig sysgroupid: \033[31m " . $clientkokanyolt . " \033[0m \n";
echo "--------------------------------------------\n";
foreach ($tomb6 as $value) {
    if ($value["type"] == 'vhost') {
        echo "Weboldalak(vhostok): \033[36m " . $value["domain"] . " \033[0m \n";
        echo "weboldalak(vhostok) document rootja: \033[36m " . $value["document_root"] . " \033[0m \n";
    }
}
echo "--------------------------------------------\n";
foreach ($tomb6 as $value) {
    if ($value["type"] == 'alias') {
        echo "Alias domainek: \033[34m " . $value["domain"] . " \033[0m \n";
    }
}
echo "--------------------------------------------\n";
foreach ($tomb8 as $value) {
    
        echo "Weboldalak adatbázisa(i) NEV: \033[36m " . $value["database_name"] . " \033[0m \n";
        echo "weboldalak adatbázisa(i) USER: \033[36m " . $value["database_user"] . " \033[0m \n";
        echo "weboldalak adatbázisa(i) PASS: \033[36m " . $value["database_password"] . " \033[0m \n";
        echo "--------------------------------------------\n";
}
foreach ($tomb10 as $value) {
    
        echo "A user email domainjei: \033[36m " . $value["domain"] . " \033[0m \n";
}
foreach ($tomb12 as $value) {
    
        echo "A user email cimei: \033[36m " . $value["email"] . " \033[0m \n";
        echo "Az emailnek a document rootja: \033[36m " . $value["maildir"] . " \033[0m \n";
}
echo "A user email átirányításai: \n";
foreach ($tomb14 as $value) {
    
        echo "Honnan: \033[36m " . $value["source"] . " \033[0m Hova: \033[36m " . $value["destination"] . " \033[0m\n";
}

echo "\n";
echo "Web mappa becsomagolás kezdődik  \033[31m legyel nagyon turelmes \033[0m \n";


foreach ($tomb6 as $value) {
    if ($value["type"] == 'vhost') {
        $PARANCS = "sudo tar -C " . $value["document_root"] . " -zcvf " . $fokonyvtar . "/" . $client . ".tar.gz .";
    }
}

echo $PARANCS;
echo "\n";

$process = exec($PARANCS, $output, $return);
if (!$return) {
    echo "\033[36m Elkészült \033[0m \n";
} else {
    echo "\033[36m asdasdsadsadasd \033[0m \n";
}

echo "\n";
echo "ADATBÁZISOK becsomagolása \n";
$databaselist = array();

foreach ($tomb8 as $value) {
    
        $PARANCS = "mysqldump -hrb02-gm -u" . $SQLDUMPBOTUSER . " -p" . $SQLDUMPBOTUSERPASS . " " . $value["database_name"] . " > " . $fokonyvtar . "/" . $client . "." . $value["database_name"] . ".sql";
        $databaselist[] = $value["database_name"];
        echo $PARANCS;
        echo "\n";
        $process = exec($PARANCS, $output, $return);
        if (!$return) {
         echo "\033[36m Elkészült \033[0m \n";
        } else {
          echo "\033[36m asdasdsadsadasd \033[0m \n";
        }
}

//var_dump($databaselist);
echo "\n";
echo "VMAIL mappa becsomagolás kezdődik  \033[31m legyel turelmes \033[0m \n";

foreach ($tomb12 as $value) {
$PARANCS = "sudo tar -C " . $value["maildir"] . " -zcvf " . $fokonyvtar . "/" . $client . ".vmail.tar.gz .";
}
echo $PARANCS;
echo "\n";

$process = exec($PARANCS, $output, $return);
if (!$return) {
    echo "\033[36m Elkészült \033[0m \n";
} else {
    echo "\033[36m asdasdsadsadasd \033[0m \n";
}

echo "\n";
echo "Weblinkek készítése \n";

$PARANCS = "sudo ln -s " . $fokonyvtar . "/" . $client . ".client.txt " . $DOWNLOADURLDIR . "";
       
        $process = exec($PARANCS, $output, $return);
        if (!$return) {
         echo "URL http://arape.freeweb.hu/" . $client . ".client.txt\n";
        } else {
          echo "\033[36m asdasdsadsadasd \033[0m \n";
        }
        
$PARANCS = "sudo ln -s " . $fokonyvtar . "/" . $client . ".domain.txt " . $DOWNLOADURLDIR . "";
        
        $process = exec($PARANCS, $output, $return);
        if (!$return) {
         echo "URL http://arape.freeweb.hu/" . $client . ".domain.txt\n";
        } else {
          echo "\033[36m asdasdsadsadasd \033[0m \n";
        }
        
$PARANCS = "sudo ln -s " . $fokonyvtar . "/" . $client . ".db.txt " . $DOWNLOADURLDIR . "";
        
        $process = exec($PARANCS, $output, $return);
        if (!$return) {
         echo "URL http://arape.freeweb.hu/" . $client . ".db.txt\n";
        } else {
          echo "\033[36m asdasdsadsadasd \033[0m \n";
        }
        
$PARANCS = "sudo ln -s " . $fokonyvtar . "/" . $client . ".maildomain.txt " . $DOWNLOADURLDIR . "";
        
        $process = exec($PARANCS, $output, $return);
        if (!$return) {
         echo "URL http://arape.freeweb.hu/" . $client . ".maildomain.txt\n";
        } else {
          echo "\033[36m asdasdsadsadasd \033[0m \n";
        }
        
$PARANCS = "sudo ln -s " . $fokonyvtar . "/" . $client . ".mailbox.txt " . $DOWNLOADURLDIR . "";
        
        $process = exec($PARANCS, $output, $return);
        if (!$return) {
         echo "URL http://arape.freeweb.hu/" . $client . ".mailbox.txt\n";
        } else {
          echo "\033[36m asdasdsadsadasd \033[0m \n";
        }

$PARANCS = "sudo ln -s " . $fokonyvtar . "/" . $client . ".mailforward.txt " . $DOWNLOADURLDIR . "";
       
        $process = exec($PARANCS, $output, $return);
        if (!$return) {
         echo "URL http://arape.freeweb.hu/" . $client . ".mailforward.txt\n";
        } else {
          echo "\033[36m asdasdsadsadasd \033[0m \n";
        }

$PARANCS = "sudo ln -s " . $fokonyvtar . "/" . $client . ".mailfilter.txt " . $DOWNLOADURLDIR . "";
        
        $process = exec($PARANCS, $output, $return);
        if (!$return) {
         echo "URL http://arape.freeweb.hu/" . $client . ".mailfilter.txt\n";
        } else {
          echo "\033[36m asdasdsadsadasd \033[0m \n";
        }

$PARANCS = "sudo ln -s " . $fokonyvtar . "/" . $client . ".ftp.txt " . $DOWNLOADURLDIR . "";
        
        $process = exec($PARANCS, $output, $return);
        if (!$return) {
         echo "URL http://arape.freeweb.hu/" . $client . ".ftp.txt\n";
        } else {
          echo "\033[36m asdasdsadsadasd \033[0m \n";
        }

$PARANCS = "sudo ln -s " . $fokonyvtar . "/" . $client . ".cron.txt " . $DOWNLOADURLDIR . "";
       
        $process = exec($PARANCS, $output, $return);
        if (!$return) {
         echo "URL http://arape.freeweb.hu/" . $client . ".cron.txt\n";
        } else {
          echo "\033[36m asdasdsadsadasd \033[0m \n";
        }        
        
$PARANCS = "sudo ln -s " . $fokonyvtar . "/" . $client . ".tar.gz " . $DOWNLOADURLDIR . "";
        
        $process = exec($PARANCS, $output, $return);
        if (!$return) {
         echo "URL http://arape.freeweb.hu/" . $client . ".tar.gz\n";
        } else {
          echo "\033[36m asdasdsadsadasd \033[0m \n";
        }


foreach ($databaselist as $value) {
    
     $PARANCS = "sudo ln -s " . $fokonyvtar . "/" . $client . "." .$value . ".sql " . $DOWNLOADURLDIR . "";
        
        $process = exec($PARANCS, $output, $return);
        if (!$return) {
         echo "URL http://arape.freeweb.hu/" . $client . "." .$value . ".sql\n";
        } else {
          echo "\033[36m asdasdsadsadasd \033[0m \n";
        }
}        
        
$PARANCS = "sudo ln -s " . $fokonyvtar . "/" . $client . ".vmail.tar.gz " . $DOWNLOADURLDIR . "";
        
        $process = exec($PARANCS, $output, $return);
        if (!$return) {
         echo "URL http://arape.freeweb.hu/" . $client . ".vmail.tar.gz\n";
        } else {
          echo "\033[36m asdasdsadsadasd \033[0m \n";
        }
