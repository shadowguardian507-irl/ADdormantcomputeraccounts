<?php
/*.
    require_module 'standard';
    require_module 'ldap';
.*/
?>
<?php
$debugenable = false;
if($_GET["debug"]==="1")
{
    $debugenable = true;
}
error_reporting(E_ERROR | E_WARNING | E_PARSE);
?>
<?php
$maxdaysfromlastconnect = 60;
?>
<?php
foreach (glob("./config.d/active/*.conf.php") as $configfilename)
{
    include $configfilename;
}
require_once(dirname(__FILE__) . '/adLDAP-4.0.4/adLDAP.php');

foreach (glob("./components/php/*.enabled.comp.php") as $enabledcompname)
{
    require_once($enabledcompname);
}

if (!checkldapconfigexists())
  {
    echo "ldap config file missing please check that ./config.d/active/ldap.conf.php exists exists template can be found in ./config.d/template/ldap.conf.php";
    die;
  }

if(!checkthemeconfigexists())
  {
    echo "theme config file mising please check that ./config.d/active/theme.conf.php exists template can be found in ./config.d/template/theme.conf.php";
    die;
  }


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
  <title>AD computer accounts</title>
  <meta charset="UTF-8">
  <meta name="description" content="list of computer accounts">
</head>
<body>
  <h1>AD computer accounts that have not been used in the last <?php echo $maxdaysfromlastconnect ?></h1>

<?php
$bdn = $ldapconf['basedn'];
$acctsif = $ldapconf['accountsuffix'];
$lun = $ldapconf['linkaccountname'];
$lup = $ldapconf['linkaccountpassword'];
$rpg = $ldapconf['realprimarygroup'];
$rg = $ldapconf['recursivegroups'];
$dcs = $ldapconf['dcarray'];

$adldap = connecttodcs($dcs,$ldapconf);
htmldebugprint_r($adldap,$debugenable);
htmldebugprint("<hr>",$debugenable);


$fullcomputerslist = dedupecomputersfromalldcsgreaterthanXdaysfromlastconnect($adldap, $ldapconf,$maxdaysfromlastconnect);

htmldebugprint_r($fullcomputerslist,$debugenable);

?>

<table style="width:100%">
  <tr>
    <th style="font-weight: bold;">Account Status</th>
    <th style="font-weight: bold;">PC Name</th>
    <th style="font-weight: bold;">Operating System</th>
    <th style="font-weight: bold;">Operating System Service Pack</th>
    <th style="font-weight: bold;">When Created (year-month-day time)</th>
    <?php foreach ($dcs as $dc)
    {
      print '<th style="font-weight: bold;">Last Logon (year-month-day time)<br/> from DC '.$dc.'</th>';
    }
    ?>
    <th style="font-weight: bold;">Last Logon Timestamp (year-month-day time)<br/> synced every 15 days between DC's</th>
  </tr>

<?php

foreach ($fullcomputerslist as $accountname)
  {
      $sanitisedaccountname = str_replace('$', '',$accountname);
      $accountinfo = $adldap[0]->computer()->info($sanitisedaccountname,array("displayname","operatingSystemServicePack","operatingSystem","lastLogonTimestamp","lastLogon","whenCreated","userAccountControl","description"));

      htmldebugprint($sanitisedaccountname,$debugenable);
      htmldebugprint_r($accountinfo,$debugenable);

      print "<tr>";
      print "<th>". accountcontroltotext($accountinfo[0]["useraccountcontrol"][0]) ."</th>";
      print "<th>". $sanitisedaccountname ."</th>";
      print "<th>". $accountinfo[0]["operatingsystem"][0] ."</th>";
      print "<th>". $accountinfo[0]["operatingsystemservicepack"][0]."</th>";
      $wcsrc = $accountinfo[0]["whencreated"][0];
      $wcconvd = substr($wcsrc,0,4)."-".substr($wcsrc,4,2)."-".substr($wcsrc,6,2)." ".substr($wcsrc,8,2).":".substr($wcsrc,10,2);
      print "<th>". $wcconvd  ."</th>";
      foreach ($adldap as $DCadldap) {
          $DCaccountinfo = $DCadldap->computer()->info($sanitisedaccountname, array("displayname","lastLogonTimestamp","lastLogon","whenCreated"));
          mslogintimestamptodatecellformated($DCaccountinfo[0]["lastlogon"][0]);
        }
      mslogintimestamptodatecellformated($accountinfo[0]["lastlogontimestamp"][0]);
      print "</tr>";
      if((isset($_GET["enabledesc"]))&&($_GET["enabledesc"]==="true")){
        renderoptionalrow($accountinfo[0]["description"][0],true);
      }

  }

?>

</table>
</body>
<?php
closedcconnections($adldap);
?>
