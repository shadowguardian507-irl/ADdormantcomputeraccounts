<?php
/*.
    require_module 'standard';
.*/
?>
<?php
function mslogintimestamptodatecellformated($mstimestamp)
{
    $unixTimestamp = mstimestamptounixtimestamp($mstimestamp);

    if ( (isset($mstimestamp)) && ($mstimestamp != "") && ($mstimestamp != 0) ){
        print "<th>". date('Y-m-d h:i:s A', $unixTimestamp ) ." </th>";
    }
    else
    {
        print "<th> never logged in </th>";
    }
}

function mstimestamptounixtimestamp($mstimestamp)
{
    $mstimestampsec   = (int)($mstimestamp / 10000000); // divide by 10 000 000 to get seconds
    $unixTimestamp = ($mstimestampsec - 11644473600); // 1.1.1600 -> 1.1.1970 difference in seconds
    return $unixTimestamp;
}

function unixTimestamptomstimestamp($unixTimestamp)
{
    $mstimestampsec = ($unixTimestamp + 11644473600);
    $mstimestamp = (int)($mstimestampsec * 10000000);
    return $mstimestamp;

}


?>
