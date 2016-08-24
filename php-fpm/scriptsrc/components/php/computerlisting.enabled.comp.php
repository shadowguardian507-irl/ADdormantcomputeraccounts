<?php
/*.
    require_module 'standard';
    require_module 'ldap';
.*/
?>
<?php
require_once('mstimestampparser.enabled.comp.php');
function dedupecomputersfromalldcsgreaterthanXdaysfromlastconnect($ldaplinks,$ldapconf,$maxnumberofdaysfromlastconnect)
{
  $date = date_create();
  date_modify($date, '-'.$maxnumberofdaysfromlastconnect.' day');
  $unixTimeStamp = date_timestamp_get($date);
  $mstimestampformaxdaysfromlastconnect = unixTimestamptomstimestamp($unixTimeStamp);

  foreach ($ldaplinks as $DCadldap) {
    $ldapfilter ='(&(objectClass=user)(objectClass=computer)(lastLogonTimestamp<='.$mstimestampformaxdaysfromlastconnect.'))';
    $sr = ldap_search($DCadldap->getLdapConnection(), $ldapconf['basedn'] , $ldapfilter, array('objectclass', 'samaccountname'));
    $accountentries = @ldap_get_entries($DCadldap->getLdapConnection(), $sr);

    foreach ($accountentries as $accountobject) {
        if( $accountobject['samaccountname'][0] != '' )
        {
          $accountnamesarry[$namearrayid][] = $accountobject['samaccountname'][0];
          $namearrayid = $namearrayid + 1;
        }
    }

  }

  $outputarry = array();
  foreach ($accountnamesarry as $accountarry) {
    $outputarry = array_unique(array_merge($outputarry,$accountarry), SORT_REGULAR);
  }

  return $outputarry;
}
?>
