<?php

//HA EZ A SZÁM 0 akkor minden adatbázisjelszó 'EztnemHISZEMEL2018' ha 1 akkor a a kinyertet használja
$ADATBAZISJELSZO = 1;

$username = 'arape';
$password = 'B6fCNa7pT$2SufdfgJ43';
$reseller_id = '';
$DOWNLOADMAINDIR = '/home/arape/FW_import';
$DOWNLOADURL = 'http://arape.freeweb.hu';
$servernamedbconn = "localhost";
$usernamedbconn = "root";
$passworddbconn = "gezuka77";
$dbnamedbconn = "dbispconfig";
$soap_location = 'https://freeweb.wwdh.hu:8080/remote/index.php';
$soap_uri = 'https://freeweb.wwdh.hu:8080/remote/';
$CLIENTVMAILDIR = '/var/vmail/freeweb.hu';

//argumentumok feldolgozása és user manual
if (!empty($argv[1])) {
    $clientname = $argv[1];
} else {
    $clientname = 'help';
}
if ($clientname == 'help') {
    print_r("Használat php után első paraméter a KLIENS neve pl 'lunya' vagy 'turulnemzet'");
    echo PHP_EOL;
    exit();
}

print_r("--------------------------------------"); echo PHP_EOL;
print_r("|     FW költöztető v0.9 - Arape     |"); echo PHP_EOL;
print_r("--------------------------------------"); echo PHP_EOL;
echo PHP_EOL;
print_r("--------------------------------------"); echo PHP_EOL;
echo "User költözés alatt: \033[31m " . $argv[1] . " \033[0m \n";




///////////////////////
///LETÖLTÉS

$konyvtar = $DOWNLOADMAINDIR . '/' . $argv[1];
echo "Átmeneti adatkönyvtára: \033[31m " . $konyvtar . "\033[0m"; echo PHP_EOL;
print_r("--------------------------------------"); echo PHP_EOL;
echo "\033[36mAdatok letöltése exporter hostról \033[0m \n"; echo PHP_EOL;
if (file_exists($konyvtar)) {


    if (is_dir($konyvtar)) {
        $objects = scandir($konyvtar);
        foreach ($objects as $object) {
            if ($object != "." && $object != "..") {
                if (is_dir($konyvtar . "/" . $object))
                    rrmdir($konyvtar . "/" . $object);
                else
                    unlink($konyvtar . "/" . $object);
            }
        }
        rmdir($konyvtar);
    }
}
mkdir($konyvtar, 0777, true);

//CLIENT
$name = $argv[1] . '.client.txt';
$url = $DOWNLOADURL . '/' . $name;
echo 'CLIENT letöltése ' . $url;
echo PHP_EOL;
$dir = $DOWNLOADMAINDIR . '/' . $argv[1] . '/';
copy($url, $dir . $name);

//DB
$name = $argv[1] . '.db.txt';
$url = $DOWNLOADURL . '/' . $name;
echo 'DB letöltése ' . $url;
echo PHP_EOL;
$dir = $DOWNLOADMAINDIR . '/' . $argv[1] . '/';
copy($url, $dir . $name);

//DOMAIN
$name = $argv[1] . '.domain.txt';
$url = $DOWNLOADURL . '/' . $name;
echo 'DOMAIN letöltése ' . $url;
echo PHP_EOL;
$dir = $DOWNLOADMAINDIR . '/' . $argv[1] . '/';
copy($url, $dir . $name);

//FTP
$name = $argv[1] . '.ftp.txt';
$url = $DOWNLOADURL . '/' . $name;
echo 'FTP letöltése ' . $url;
echo PHP_EOL;
$dir = $DOWNLOADMAINDIR . '/' . $argv[1] . '/';
copy($url, $dir . $name);

//MAILBOX
$name = $argv[1] . '.mailbox.txt';
$url = $DOWNLOADURL . '/' . $name;
echo 'MAILBOX letöltése ' . $url;
echo PHP_EOL;
$dir = $DOWNLOADMAINDIR . '/' . $argv[1] . '/';
copy($url, $dir . $name);

//MAILDOMAIN
$name = $argv[1] . '.maildomain.txt';
$url = $DOWNLOADURL . '/' . $name;
echo 'MAILDOMAIN letöltése ' . $url;
echo PHP_EOL;
$dir = $DOWNLOADMAINDIR . '/' . $argv[1] . '/';
copy($url, $dir . $name);

//mailfilter
$name = $argv[1] . '.mailfilter.txt';
$url = $DOWNLOADURL . '/' . $name;
echo 'mailfilter letöltése ' . $url;
echo PHP_EOL;
$dir = $DOWNLOADMAINDIR . '/' . $argv[1] . '/';
copy($url, $dir . $name);

//mailforward
$name = $argv[1] . '.mailforward.txt';
$url = $DOWNLOADURL . '/' . $name;
echo 'mailforward letöltése ' . $url;
echo PHP_EOL;
$dir = $DOWNLOADMAINDIR . '/' . $argv[1] . '/';
copy($url, $dir . $name);

//cron
$name = $argv[1] . '.cron.txt';
$url = $DOWNLOADURL . '/' . $name;
echo 'CRONok letöltése ' . $url;
echo PHP_EOL;
$dir = $DOWNLOADMAINDIR . '/' . $argv[1] . '/';
copy($url, $dir . $name);

//TAR GZ
$name = $argv[1] . '.tar.gz';
$url = $DOWNLOADURL . '/' . $name;
echo "\033[36m MOST JÖNNEK AZ ADATOK  \033[0m" . $url;
echo PHP_EOL;
$dir = $DOWNLOADMAINDIR . '/' . $argv[1] . '/';
copy($url, $dir . $name);

//VMAIL MAPPA
$name = $argv[1] . '.vmail.tar.gz';
$url = $DOWNLOADURL . '/' . $name;
echo 'VMAIL mappa letöltése ' . $url;
echo PHP_EOL;
$dir = $DOWNLOADMAINDIR . '/' . $argv[1] . '/';
copy($url, $dir . $name);

print_r("--------------------------------------"); echo PHP_EOL;
echo "\033[36mBelépés ISPconfigAPI-ba, kliens  és minden más létrehozása \033[0m \n"; echo PHP_EOL;
/*
  SOAP kapcsolódás kezdése
  $soap_location = 'http://localhost:8080/ispconfig3/interface/web/remote/index.php';
  $soap_uri = 'http://localhost:8080/ispconfig3/interface/web/remote/';
 */



$client = new SoapClient(null, array('location' => $soap_location, 'uri' => $soap_uri));
try {
    //* Login to the remote server
    if ($session_id = $client->login($username, $password)) {
        //echo 'Logged into remote server sucessfully.';
        echo PHP_EOL;
    }
} catch (SoapFault $e) {
    die('SOAP Error: ' . $e->getMessage());
    echo "Please contact the server administator";
}
//////////////////////////
////////////////////KLIENS
//////////////////////////
//JSON file behúzása
$json_data = file_get_contents("$DOWNLOADMAINDIR/$argv[1]/$clientname.client.txt");
$jsontomb = json_decode($json_data, true);

switch (json_last_error()) {
    case JSON_ERROR_NONE:
        echo '';
        break;
    case JSON_ERROR_DEPTH:
        echo ' - Maximum stack depth exceeded';
        break;
    case JSON_ERROR_STATE_MISMATCH:
        echo ' - Underflow or the modes mismatch';
        break;
    case JSON_ERROR_CTRL_CHAR:
        echo ' - Unexpected control character found';
        break;
    case JSON_ERROR_SYNTAX:
        echo ' - Syntax error, malformed JSON';
        break;
    case JSON_ERROR_UTF8:
        echo ' - Malformed UTF-8 characters, possibly incorrectly encoded';
        break;
    default:
        echo ' - Unknown error';
        break;
}

//Adatok feldolgozása
$params = array('server_id' => '1',
    'company_name' => $jsontomb[0]['company_name'],
    'gender' => '',
    'contact_firstname' => '',
    'contact_name' => $jsontomb[0]['contact_name'],
    'customer_no' => '',
    'username' => $jsontomb[0]['username'],
    'password' => $jsontomb[0]['password'],
    'language' => 'hu',
    'usertheme' => 'default',
    'street' => $jsontomb[0]['street'],
    'zip' => $jsontomb[0]['zip'],
    'city' => $jsontomb[0]['city'],
    'state' => $jsontomb[0]['state'],
    'country' => $jsontomb[0]['country'],
    'telephone' => $jsontomb[0]['telephone'],
    'mobile' => '',
    'fax' => '',
    'email' => $jsontomb[0]['email'],
    'internet' => $jsontomb[0]['internet'],
    'icq' => '',
    'vat_id' => '',
    'company_id' => '',
    'bank_account_owner' => '',
    'bank_account_number' => '',
    'bank_code' => '',
    'bank_name' => '',
    'bank_account_iban' => '',
    'bank_account_swift' => '',
    'notes' => '',
    'paypal_email' => '',
    'locked' => 'n',
    'canceled' => 'n',
    'ssh_chroot' => 'no',
    'web_php_options' => 'php-fpm',
    'added_date' => '',
    'added_by' => 'arapebot',
    'template_master' => $jsontomb[0]['template_master'],
    'web_php_options' => $jsontomb[0]['web_php_options'],);


//Adatok beküldése
$domain_id = $client->client_add($session_id, $reseller_id, $params);
//echo $client->__getLastResponse();

sleep(3);

$kliensuser = $client->client_get_by_username($session_id, $argv[1]);
//echo $client->__getLastResponse();
//echo PHP_EOL;

//var_dump($kliensuser);
/////////////////////////////
///////////////////WEBDOMAINS
/////////////////////////////

//print_r("--------------------------------------"); echo PHP_EOL;
//echo "\033[36mBelépés ISPconfigAPI-ba, weboldal(ak) létrehozása \033[0m \n"; echo PHP_EOL;

//JSON file behúzása
$json_data2 = file_get_contents("$DOWNLOADMAINDIR/$argv[1]/$clientname.domain.txt");
$jsontomb2 = json_decode($json_data2, true);

foreach ($jsontomb2 as $value) {
    if ($value["type"] == 'vhost') {
        //echo "domain_add_paramst hivom";
        //echo PHP_EOL;
        $params = array(
            'server_id' => '1',
            'ipaddress' => '',
            'domain' => $value["domain"],
            'type' => $value["type"],
            'vhost_type' => 'name',
            'cgi' => 'y',
            'ssi' => 'y',
            'hd_quota' => $value["hd_quota"],
            'suexec' => $value["suexec"],
            'is_subdomainwww' => $value["is_subdomainwww"],
            'errordocs' => 1,
            'subdomain' => $value["subdomain"],
            'ssl_country' => $value["ssl_country"],
            'stats_password' => $value["stats_password"],
            'stats_type' => $value["stats_type"],
            'allow_override' => $value["allow_override"],
            'traffic_quota_lock' => 'n',
            'pm' => 'dynamic',
            'pm.min_spare_servers' => 1,
            'pm.max_spare_servers' => 1,
            'pm.start_servers' => 1,
            'php_fpm_use_socket' => $value["php_fpm_use_socket"],
            'force' => $value["force"],
            'active' => $value["active"],
            'pm_process_idle_timeout' => '10',
            'pm_max_requests' => '0',
            'traffic_quota_lock' => $value["traffic_quota_lock"],
            'php' => 'php-fpm',
            'redirect_type' => '',
            'redirect_path' => '',
            'allow_override' => 'ALL',
            'apache_directives' => '',
            'php_open_basedir' => '/',
            'custom_php_ini' => '',
            'traffic_quota' => '-1',
            'http_port' => '80',
            'https_port' => '443',
            'client_group_id' => $kliensuser['default_group'],
        );

        $reseller_id = $kliensuser['default_group'];
        //echo $kliensuser['default_group'];
        //echo PHP_EOL;
        //Adatok beküldése
        $domain_id = $client->sites_web_domain_add($session_id, $reseller_id, $params);
        //echo $client->__getLastResponse();
        //echo PHP_EOL;

        sleep(3);
        $oldallista = $client->sites_web_domain_get($session_id, array('sys_groupid' => $kliensuser['default_group']));

        //echo $client->__getLastResponse();
       // echo PHP_EOL;

        //var_dump($oldallista);
    }
    if ($value["type"] == 'alias') {
        //echo "Aliast veszek fel";
        //echo PHP_EOL;
        $params = array(
            'server_id' => '1',
            'domain' => $value["domain"],
            'type' => $value["type"],
            'active' => $value["active"],
            'system_user' => $kliensuser['default_group'],
            'system_group' => $kliensuser['default_group'],
            'client_group_id' => $kliensuser['default_group'],
            'parent_domain_id' => $oldallista[0]["domain_id"],
        );

        $domainatadasra = $value["domain"];
        $reseller_id = $kliensuser['default_group'];
        //echo $kliensuser['default_group'];
        //echo PHP_EOL;
        //Adatok beküldése
        $domain_id = $client->sites_web_aliasdomain_add($session_id, $reseller_id, $params);
        //echo $client->__getLastResponse();

        //////////////KOKANYOLAS LEVEL 110 hajtani kellene egy cigit
        // Create connection
        $conn = new mysqli($servernamedbconn, $usernamedbconn, $passworddbconn, $dbnamedbconn);
        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $sql = "UPDATE web_domain SET sys_userid='" . $reseller_id . "', sys_groupid='" . $reseller_id . "' WHERE domain='" . $domainatadasra . "' limit 1;";

        if ($conn->query($sql) === TRUE) {
            //echo "Aliasdomain tulajátírás done";
        } else {
            echo "Error updating record: " . $conn->error;
        }

        $conn->close();
    }
}
////////////////////////////
///////////////////DATABASES
////////////////////////////
//JSON file behúzása

//print_r("--------------------------------------"); echo PHP_EOL;
//echo "\033[36mBelépés ISPconfigAPI-ba, Adatbázisok és userek létrehozása \033[0m \n"; echo PHP_EOL;

$json_data3 = file_get_contents("$DOWNLOADMAINDIR/$argv[1]/$clientname.db.txt");
$jsontomb3 = json_decode($json_data3, true);

//Adatok feldolgozása
$dbusercount = 0;
$databaselist = array(); //nem értem már én se fáj a fejem
foreach ($jsontomb3 as $value) {

   // echo "database user létrehozas";
   // echo PHP_EOL;
    if ($ADATBAZISJELSZO == 0)
        {
    $params = array(
        'server_id' => '1',
        'database_user' => $value["database_user"],
        'database_password' => 'EztnemHISZEMEL2018',
        'client_id' => $kliensuser['client_id'],
    );
        }
    if ($ADATBAZISJELSZO == 1)
        {
    $params = array(
        'server_id' => '1',
        'database_user' => $value["database_user"],
        'database_password' => $value["database_password"],
        'client_id' => $kliensuser['client_id'],
    );
        }    
    $reseller_id = $kliensuser['client_id'];
    //echo $kliensuser['default_group'];

    //Adatok beküldése
    $domain_id = $client->sites_database_user_add($session_id, $reseller_id, $params);
   // echo $client->__getLastResponse();
   // echo PHP_EOL;

   // echo "database és jogosultságok létrehozasa";
   // echo PHP_EOL;
    
    $dbuser = $client->sites_database_user_get($session_id, array('sys_groupid' => $kliensuser['default_group']));
   // echo $client->__getLastResponse();
   // var_dump($dbuser); var_dump($dbusercount);
    $params = array(
        'server_id' => '1',
        'type' => 'mysql',
        'database_name' => $value["database_name"],
        'database_user_id' => $dbuser[$dbusercount]["database_user_id"],
        'client_id' => $kliensuser['client_id'],
        'parent_domain_id' => $oldallista[0]['domain_id'],
        'database_quota' => '-1',
        'active' => 'y',
    );
    $databaselist[] = $value["database_name"];
    $reseller_id = $kliensuser['client_id'];
   // echo $kliensuser['default_group'];
   // echo PHP_EOL;

    //Adatok beküldése
    $domain_id = $client->sites_database_add($session_id, $reseller_id, $params);
  //  echo $client->__getLastResponse();
   // echo PHP_EOL;
    $dbusercount = $dbusercount + 1;
}


///////////////////FTPUSERS
//JSON file behúzása
$json_data4 = file_get_contents("$DOWNLOADMAINDIR/$argv[1]/$clientname.ftp.txt");
$jsontomb4 = json_decode($json_data4, true);
//Adatok feldolgozása
//print_r("--------------------------------------"); echo PHP_EOL;
//echo "\033[36mBelépés ISPconfigAPI-ba, FTP userek létrehozása \033[0m \n"; echo PHP_EOL;

foreach ($jsontomb4 as $value) {

  //  echo "FTP userker user létrehozas";
  //  echo PHP_EOL;
    $params = array(
        'server_id' => '1',
        'username' => $value["username"],
        'password' => $value['password'],
        'parent_domain_id' => $oldallista[0]['domain_id'],
        'uid' => 'web' . $oldallista[0]['domain_id'],
        'gid' => 'client' . $kliensuser['client_id'],
        'dir' => '/var/www/clients/client' . $kliensuser['client_id'] . '/web' . $oldallista[0]['domain_id'],
        'quota_size' => $value['quota_size'],
        'active' => 'y',
    );

    $reseller_id = $kliensuser['client_id'];
  //  echo $kliensuser['default_group'];

    //Adatok beküldése
    $domain_id = $client->sites_ftp_user_add($session_id, $reseller_id, $params);
   // echo $client->__getLastResponse();
   // echo PHP_EOL;
}

///////////////////MAIL DOMAIN
//JSON file behúzása
$json_data5 = file_get_contents("$DOWNLOADMAINDIR/$argv[1]/$clientname.maildomain.txt");
$jsontomb5 = json_decode($json_data5, true);

//print_r("--------------------------------------"); echo PHP_EOL;
//echo "\033[36mBelépés ISPconfigAPI-ba, MAILDOMAIN létrehozása \033[0m \n"; echo PHP_EOL;

//Adatok feldolgozása

foreach ($jsontomb5 as $value) {

   // echo "MAILdomain létrehozas";
    $params = array(
        'server_id' => '1',
        'domain' => $value["domain"],
        'active' => 'y',
        'client_group_id' => $kliensuser['default_group'],
    );

    $reseller_id = $kliensuser['client_id'];
  //  echo $kliensuser['default_group'];

    //Adatok beküldése
    $domain_id = $client->mail_domain_add($session_id, $reseller_id, $params);
  //  echo $client->__getLastResponse();
}


///////////////////MAIL BOX
//JSON file behúzása
$json_data6 = file_get_contents("$DOWNLOADMAINDIR/$argv[1]/$clientname.mailbox.txt");
$jsontomb6 = json_decode($json_data6, true);

//print_r("--------------------------------------"); echo PHP_EOL;
//echo "\033[36mBelépés ISPconfigAPI-ba, MAIL fiókok létrehozása \033[0m \n"; echo PHP_EOL;

//Adatok feldolgozása

foreach ($jsontomb6 as $value) {

    //echo "MAILbox létrehozas";
    $params = array(
        'server_id' => '1',
        'email' => $value["email"],
        'login' => $value["login"],
        'password' => $value["password"],
        'name' => $value["name"],
        'quota' => $value["quota"],
        'cc' => $value["cc"],
        'disablesmtp' => $value["disablesmtp"],
        'active' => 'y',
        'client_group_id' => $kliensuser['default_group'],
    );

    $reseller_id = $kliensuser['client_id'];
   // echo $kliensuser['default_group'];

    //Adatok beküldése
    $domain_id = $client->mail_user_add($session_id, $reseller_id, $params);
  //  echo $client->__getLastResponse();
}

/*
///////////////////MAIL ALIAS
//JSON file behúzása
$json_data7 = file_get_contents("$DOWNLOADMAINDIR/$argv[1]/$clientname.mailforward.txt");
$jsontomb7 = json_decode($json_data7, true);

//print_r("--------------------------------------"); echo PHP_EOL;
//echo "\033[36mBelépés ISPconfigAPI-ba, mail alias) létrehozása \033[0m \n"; echo PHP_EOL;

//Adatok feldolgozása

foreach ($jsontomb7 as $value) {

   // echo "MAILalias létrehozas";
    $params = array(
        'server_id' => '1',
        'source' => $value["source"],
        'destination' => $value["destination"],
        'type' => $value["type"],
        'active' => 'y',
        'client_group_id' => $kliensuser['default_group'],
    );

    $reseller_id = $kliensuser['client_id'];
   // echo $kliensuser['default_group'];

    //Adatok beküldése
    $domain_id = $client->mail_alias_add($session_id, $reseller_id, $params);
    //echo $client->__getLastResponse();
}
*/

///////////////////MAIL ALIAS ES CATCHALL
//JSON file behúzása
$json_data8 = file_get_contents("$DOWNLOADMAINDIR/$argv[1]/$clientname.mailforward.txt");
$jsontomb8 = json_decode($json_data8, true);



//Adatok feldolgozása

foreach ($jsontomb8 as $value) {

    if ($value["type"] == 'alias') {
        //echo "MAILalias létrehozas";
        $params = array(
            'server_id' => '1',
            'source' => $value["source"],
            'destination' => $value["destination"],
            'type' => $value["type"],
            'active' => 'y',
            'client_group_id' => $kliensuser['default_group'],
        );

        $reseller_id = $kliensuser['client_id'];
        //echo $kliensuser['default_group'];
        //Adatok beküldése
        $domain_id = $client->mail_alias_add($session_id, $reseller_id, $params);
        //echo $client->__getLastResponse();
    }

    if ($value["type"] == 'catchall') {
        //echo "MAILcatchall létrehozas";
        $params = array(
            'server_id' => '1',
            'source' => $value["source"],
            'destination' => $value["destination"],
            'type' => $value["type"],
            'active' => 'y',
            'client_group_id' => $kliensuser['default_group'],
        );

        $reseller_id = $kliensuser['client_id'];
        //echo $kliensuser['default_group'];
        //Adatok beküldése
        $domain_id = $client->mail_catchall_add($session_id, $reseller_id, $params);
        //echo $client->__getLastResponse();
    }
}

///////////////////CRON JOBOK
//JSON file behúzása
$json_data10 = file_get_contents("$DOWNLOADMAINDIR/$argv[1]/$clientname.cron.txt");
$jsontomb10 = json_decode($json_data10, true);

//print_r("--------------------------------------"); echo PHP_EOL;
//echo "\033[36mBelépés ISPconfigAPI-ba, MAIL fiókok létrehozása \033[0m \n"; echo PHP_EOL;

//Adatok feldolgozása

foreach ($jsontomb10 as $value) {

    //echo "CRON létrehozas";
    $params = array(
        'server_id' => '1',
        'parent_domain_id' => $oldallista[0]["domain_id"],
        'type' => $value["type"],
        'command' => $value["command"],
        'run_min' => $value["run_min"],
        'run_hour' => $value["run_hour"],
        'run_mday' => $value["run_mday"],
        'run_month' => $value["run_month"],
        'run_wday' => $value['run_wday'],
        'active' => $value['active'],
    );

    $reseller_id = $kliensuser['client_id'];
   // echo $kliensuser['default_group'];

    //Adatok beküldése
    $domain_id = $client->sites_cron_add($session_id, $reseller_id, $params);
  //  echo $client->__getLastResponse();
}

//////////SQL letöltés

print_r("--------------------------------------"); echo PHP_EOL;
echo "\033[36mSQL dumpok letöltése \033[0m \n"; echo PHP_EOL;



//sql
//var_dump($databaselist);

foreach ($databaselist as $value) {
$name = $argv[1] . '.' . $value . '.sql';
$url = $DOWNLOADURL . '/' . $name;
echo 'SQL letöltése ' . $url;
echo PHP_EOL;
$dir = $DOWNLOADMAINDIR . '/' . $argv[1] . '/';
copy($url, $dir . $name);
}

//INNEN JÖN A GATYASAG
//regi indexhtml törlése
echo "\033[31m Egy percet várunk, hogy biztos létrejöjjön a webmappa \033[0m";
echo PHP_EOL;
sleep(80); //kávészünet amíg a kliens mappa létrejön

$CLIENTWWWDIR = '/var/www';
$indexhtmlhelye = $CLIENTWWWDIR . '/' . $argv[1] . '.freeweb.hu/web';
unlink($indexhtmlhelye . '/index.html');
unlink($indexhtmlhelye . '/favicon.ico');
unlink($indexhtmlhelye . '/robots.txt');

print_r("--------------------------------------"); echo PHP_EOL; echo PHP_EOL;
//kicsomagolás
echo "\033[0m Elkezdem kitomoriteni a web mappat! \033[0m ";
echo PHP_EOL;
$webroot = $CLIENTWWWDIR . '/' . $argv[1] . '.freeweb.hu/';
$PARANCS = "sudo tar xvzf " . $DOWNLOADMAINDIR . "/" . $argv[1] . "/" . $argv[1] . ".tar.gz -C " . $webroot . "";
echo $PARANCS;

$process = exec($PARANCS, $output, $return);
if (!$return) {
    echo "kitomorites kesz"; echo PHP_EOL;
} else {
    echo "\033[36m kitomorites kesz \033[0m \n";
}

//tulajváltás
echo "\033[0m Tulajt váltok a web mappan! \033[0m ";
echo PHP_EOL;


foreach ($oldallista as $value) {
    if ($value["type"] == 'vhost') {
        $SYSTEMUSER = $value["system_user"];
        $SYSTEMGROUP = $value["system_group"];
        $DOCUMENTROOT = $value["document_root"];
    }
}
$PARANCS = "sudo chown -R " . $SYSTEMUSER . ":" . $SYSTEMGROUP . " " . $DOCUMENTROOT . "/*"; echo PHP_EOL;
var_dump($PARANCS); echo PHP_EOL;

$process = exec($PARANCS, $output, $return);
if (!$return) {
    echo "tulajmodositas kesz"; echo PHP_EOL;
} else {
    echo "\033[36m asdasdsadsadasd \033[0m \n";
}

echo "\033[0m Adatbázis adatok betöltése sql-ből! \033[0m ";
echo PHP_EOL;

foreach ($jsontomb3 as $value)
{
    if ($ADATBAZISJELSZO == 0 )
    {
    $value["database_password"] = 'EztnemHISZEMEL2018';
    }
    $PARANCS = "sudo mysql -u" . $value["database_user"] . " -p'" . $value["database_password"] . "' " . $value["database_name"] . " < " . $DOWNLOADMAINDIR . "/"  . $argv[1] . "/" . $argv[1] . "." . $value["database_name"] . ".sql";
    var_dump($PARANCS); echo PHP_EOL;

    $process = exec($PARANCS, $output, $return);
    if (!$return) {
    echo "SQL betöltés kesz";
    } else {
    echo "\033[36m asdasdsadsadasd \033[0m \n";
    }
  
 
}

//kicsomagolás
echo "Elkezdem kitomoriteni a vmail mappat!";
echo PHP_EOL;
$vmailroot = $CLIENTVMAILDIR . '/' . $argv[1];
$MAILDIR = $vmailroot . '/Maildir';
mkdir($MAILDIR, 0777, true);
$PARANCS = "sudo tar xvzf " . $DOWNLOADMAINDIR . "/" . $argv[1] . "/" . $argv[1] . ".vmail.tar.gz -C " . $MAILDIR . "";
echo $PARANCS; echo PHP_EOL;

$process = exec($PARANCS, $output, $return);
if (!$return) {
    echo "kitomorites kesz";
} else {
    echo "\033[36m kitomorites kesz \033[0m \n";
}

//tulajváltás
echo "tulajt váltok a vmail mappan!";
echo PHP_EOL;

$PARANCS = "sudo chown -R vmail:vmail " . $vmailroot . "/*";
echo $PARANCS; echo PHP_EOL;

$process = exec($PARANCS, $output, $return);
if (!$return) {
    echo "tulajmodositas kesz";
} else {
    echo "\033[36m asdasdsadsadasd \033[0m \n";
}

//* Logout
echo PHP_EOL;
if ($client->logout($session_id)) {
    echo 'Kliens ' . $argv[1] . ' létrejött';
    echo PHP_EOL;
    echo 'Webkövtára:/var/www/clients/client' . $kliensuser['client_id'] . '/web' . $oldallista[0]['domain_id'];
    echo PHP_EOL;
}
