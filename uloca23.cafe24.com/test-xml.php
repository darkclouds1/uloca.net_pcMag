<?

$path = $_SERVER['DOCUMENT_ROOT']."/test.xml";

$xml=simplexml_load_file($path);

//print_r($xml);

echo '<br><br>';
echo $xml->HEADER->user_id.'<br>';
echo $xml->HEADER->resp_dt.'<br>';
echo $xml->CONTENTS->E017->enp_nm.'<br>';
echo $xml->CONTENTS->E017->bzno.'<br>';
echo $xml->CONTENTS->E017->reper_nm.'<br>';
echo $xml->CONTENTS->E017->estb_dt.'<br>';
echo $xml->CONTENTS->E017->zip.'<br>';
echo $xml->CONTENTS->E017->addr1.'<br>';
echo $xml->CONTENTS->E017->addr2.'<br>';
echo $xml->CONTENTS->E017->tel_no.'<br>';
echo $xml->CONTENTS->E017->fax_no.'<br>';
echo $xml->CONTENTS->E017->email.'<br>';
echo $xml->CONTENTS->E017->major_pd.'<br>';


?>