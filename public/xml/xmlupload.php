<?php
// database access parameters
// alter this as per your configuration
$host = "localhost";
$user = "vtek";
$pass = "1234567";
$db = "tmek";

// open a connection to the database server
$connection = pg_connect("host=$host dbname=$db user=$user
password=$pass");
if (!$connection) {
    die("Could not open connection to database server");
}
echo "1 step";
$directory = "in/";

echo "4 step";
$query0 = "set datestyle = 'ISO, DMY'";
$result = pg_query($connection, $query0) or die("Error in query: $query0. " . pg_last_error($connection));


$xmls = glob($directory . "*.xml");
echo $xmls;

foreach ($xmls as $x) {
    // upload xml file data to database from file
    echo "step 5  >";
    $xml = simplexml_load_file("$x");
    $request_id = $xml->request_id;
    $query1 = "INSERT INTO xmlfile (request_id, request_date, request_file_name, response_file_name)
                              VALUES ({$xml->request_id},'{$xml->request_date}','{$xml->request_file_name}','{$xml->response_file_name}')";
    echo $query1;
    $result1 = pg_query($connection, $query1) or die("Error in query: $query1. " . pg_last_error($connection));
    foreach ($xml->persons as $pfperson) {
        foreach ($pfperson->person as $book => $prs) {
            $address = $prs->address;
            $fulladdress = str_replace('\'', '_', $address);
            $vtek_doc_seriya = $prs->vtek_doc_serya;
            $vtek_doc_seriya = str_replace('\'', ' ', $vtek_doc_seriya);
            $vtek_doc_number = $prs->vtek_doc_number;
            $vtek_doc_number = str_replace('\'', ' ', $vtek_doc_number);

            if (empty($prs->invalid_percent)) {
                $prs->invalid_percent = 0;
            }
			if (empty($prs->invalid_group)) {
                $prs->invalid_group = 0;
            }
            if (empty($prs->close_date)) {
                $prs->close_date = "NULL";
            } else {
                $prs->close_date = "'" . pg_escape_string(utf8_encode($prs->close_date)) . "'";
            }
//            if(empty($prs->close_reason)) {
//                $prs->close_reason ='00';
//            }
            if (empty($prs->pens_begin_date)) {
                $prs->pens_begin_date = "NULL";
            } else {
                $prs->pens_begin_date = "'" . pg_escape_string(utf8_encode($prs->close_date)) . "'";
            }

            $query2 = "INSERT INTO xmlupload
(case_number, sobes_org_id, passport_series, passport_number, is_foreign_citizen_flag, birth_date, close_date, close_reason,
pens_reason, pens_type, pens_begin_date, vtek_doc_serya, vtek_doc_number, vtek_doc_reg_date, invalid_reason,
invalid_exp_date, address_begin_date, address_end_date, subregion_id, address, invalid_group, invalid_percent, pens_sum)
VALUES
 ( '$prs->case_number', $prs->sobes_org_id,'$prs->passport_series', '$prs->passport_number', '$prs->is_foreign_citizen_flag', '$prs->birth_date',
  $prs->close_date, '$prs->close_reason',	'$prs->pens_reason', '$prs->pens_type'," . $prs->pens_begin_date . ", '$vtek_doc_seriya',
   '$vtek_doc_number', '$prs->vtek_doc_reg_date', '$prs->invalid_reason','$prs->invalid_exp_date', '$prs->address_begin_date',
   '$prs->address_end_date', '$prs->subregion_id', '" . $fulladdress . "', $prs->invalid_group, $prs->invalid_percent, $prs->pens_sum)";
            //        echo $query2;
            $result12 = pg_query($connection, $query2) or die("Error in query: $query2. " . pg_last_error($connection));
        }
        unlink("$x");
    }
//    // function call
    $query3 = "update xmlupload set xrequest_id=".$request_id." where xrequest_id=0";
    $result3 = pg_query($connection, $query3) or die("Error in query: $query3. " . pg_last_error($connection));
    $query4="select * from npf_xml_check(".$request_id.")";
    $result4 = pg_query($connection, $query4) or die("Error in query: $query4. " . pg_last_error($connection));

}

pg_close($connection);

?>

