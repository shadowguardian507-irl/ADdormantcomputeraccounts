<?php
/*.
    require_module 'standard';
.*/
?>
<?php
function accountcontroltotext($accountcontrolvalue)
{
  switch ($accountcontrolvalue) {
    case 512:
        $accountcontrolintp="Enabled";
        break;
    case 514:
        $accountcontrolintp="Disabled";
        break;
    case 66048:
        $accountcontrolintp="Enabled (".$accountcontrolvalue.")";
        break;
    case 66050:
        $accountcontrolintp="Disabled (".$accountcontrolvalue.")";
        break;
    case 544:
        $accountcontrolintp="Change Password";
        break;
    case 262656:
        $accountcontrolintp="Requires Smart Card";
        break;
    case 1:
        $accountcontrolintp="Locked Disabled";
        break;
    case 8388608:
  	    $accountcontrolintp="Password Expired";
        break;
    case 4096:
        $accountcontrolintp="Workstation/Server";
        break;
    case 69632:
        $accountcontrolintp="Workstation/Server, No password expiry";
        break;
    case 528384:
        $accountcontrolintp="Workstation/Server, TRUSTED_FOR_DELEGATION";
        break;
    case 4128:
        $accountcontrolintp="Workstation/Server, PASSWD_NOTREQD";
        break;
    case 66080:
        $accountcontrolintp="Enabled - No password expiry - Password not required (".$accountcontrolvalue.")";
        break;
    default:
        $accountcontrolintp="Unknown (".$accountcontrolvalue.")";
  }
  return $accountcontrolintp;
}
?>
