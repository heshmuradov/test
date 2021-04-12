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
//$directory = "xml/in/";

echo "4 step";
$query0 = "set datestyle = 'ISO, DMY'";
$result = pg_query($connection, $query0) or die("Error in query: $query0. " . pg_last_error($connection));



echo "5 step";
$query1 = "select * from xmlfile x where response_date is null";
$result1 = pg_query($connection, $query1) or die("Error in query: $query1. " . pg_last_error($connection));
$filearray=pg_fetch_all($result1);
print_r($filearray);

echo $result1->request_id;
foreach ($filearray as $x) {
    // upload xml file data to database from file
        echo "7 step";
        $request_id=$x["request_id"];
        $rrequest_date=$x["request_date"];
        $request_date=date("d.m.Y H:i:s",strtotime($rrequest_date));
        $request_file_name=$x["request_file_name"];
        $response_file_name=$x["response_file_name"];
        $today=date('d.m.Y H:i:s');
        echo $request_date;
              $xml = new SimpleXMLElement('<?xml version="1.0" encoding="windows-1251"?><response></response>');
              $xml->addChild('request_id',$request_id);
              $xml->addChild('request_date',$request_date);
              $xml->addChild('request_file_name',$request_file_name);
              $xml->addChild('response_date',$today);
              $xml->addChild('response_file_name',$response_file_name);
             // echo "step 10";
              $query2 = "update xmlfile set response_date=CURRENT_DATE where request_id=".$request_id;
              //  echo $query2;
              $result2 = pg_query($connection, $query2) or die("Error in query: $query2. " . pg_last_error($connection));

    // $this->db->query("INSERT INTO xmlfile (response_date) values ($today) where request_id=$request_id");

              $persons = $xml->addChild('persons');
             // passporti yok fuqarolar berilyapti;
              $query3="select x.case_number, x.sobes_org_id, x.passport_series, x.passport_number, x.is_foreign_citizen_flag,
                            x.birth_date, x.vtek_doc_serya, x.vtek_doc_number, x.vtek_doc_reg_date, x.invalid_group,
                            x.invalid_reason, x.invalid_percent, x.invalid_exp_date, x.xchecked as not_found_reason,
                            '' as vtek_close_date, '' as vtek_close_reason
                             from xmlupload x where x.xchecked='01' and x.xrequest_id=".$request_id;
                echo $query3;
                $result3 = pg_query($connection, $query3) or die("Error in query: $query3. " . pg_last_error($connection));
                $xml_array= pg_fetch_all($result3);


               foreach ($xml_array as $re){
                   $person = $persons->addchild('person');
                   $case_number=$re["case_number"];
                   $person->addChild('case_number',$case_number);
                   $sobes_org_id=$re["sobes_org_id"];
                   $person->addChild('sobes_org_id',$sobes_org_id);
                   $passport_series=$re["passport_series"];
                   $person->addChild('passport_series',$passport_series);
                   $passport_number=$re["passport_number"];
                   $person->addChild('passport_number',$passport_number);
                   $is_foreign_citizen_flag=$re["is_foreign_citizen_flag"];
                   $person->addChild('is_foreign_citizen_flag',$is_foreign_citizen_flag);
                   $bbirth_date=$re["birth_date"];
                   $birth_date=date("d.m.Y",strtotime($bbirth_date));
                   $person->addChild('birth_date',$birth_date);
                   $vtek_doc_serya=$re["vtek_doc_serya"];
                   $person->addChild('vtek_doc_serya',$vtek_doc_serya);
                   $vtek_doc_number=$re["vtek_doc_number"];
                   $person->addChild('vtek_doc_number',$vtek_doc_number);
                   $vvtek_doc_reg_date=$re["vtek_doc_reg_date"];
                   $vtek_doc_reg_date=date("d.m.Y",strtotime($vvtek_doc_reg_date));
                   $person->addChild('vtek_doc_reg_date',$vtek_doc_reg_date);
                   $invalid_group=$re["invalid_group"];
                   $person->addChild('invalid_group',$invalid_group);
                   $invalid_reason=$re["invalid_reason"];
                   $person->addChild('invalid_reason',$invalid_reason);
                   $invalid_percent=$re["invalid_percent"];
                   $person->addChild('invalid_percent',$invalid_percent);
                   $iinvalid_exp_date=$re["invalid_exp_date"];
                   $invalid_exp_date=date("d.m.Y",strtotime($iinvalid_exp_date));
                   $person->addChild('invalid_exp_date',$invalid_exp_date);
                   $not_found_reason=$re["not_found_reason"];
                   $person->addChild('not_found_reason',$not_found_reason);
                   $vvtek_close_date=$re["vtek_close_date"];
                   $vtek_close_date=date("d.m.Y",strtotime($vvtek_close_date));
                   $person->addChild('vtek_close_date',$vtek_close_date);
                   $vtek_close_reason=$re["vtek_close_reason"];
                   $person->addChild('vtek_close_reason',$vtek_close_reason);
               }


    // hamma malumot bor fuqarolar haqida
    $query4="select x.case_number, x.sobes_org_id, x.passport_series, x.passport_number, x.is_foreign_citizen_flag,
                                        x.birth_date, x.vtek_doc_serya, x.vtek_doc_number, x.vtek_doc_reg_date, x.invalid_group,
                                        x.invalid_reason, x.invalid_percent, x.invalid_exp_date, x.xchecked as not_found_reason,
                                        '' as vtek_close_date, '' as vtek_close_reason
                                         from xmlupload x where x.xchecked='03' and x.xrequest_id=".$request_id;
    // echo $query3;
    $result4 = pg_query($connection, $query4) or die("Error in query: $query4. " . pg_last_error($connection));
    $xml_array1= pg_fetch_all($result4);
    // print_r($xml_array1);
    //$j=0;
    foreach ($xml_array1 as $re){
        $person = $persons->addchild('person');
        $case_number=$re["case_number"];
        $person->addChild('case_number',$case_number);
        $sobes_org_id=$re["sobes_org_id"];
        $person->addChild('sobes_org_id',$sobes_org_id);
        $passport_series=$re["passport_series"];
        $person->addChild('passport_series',$passport_series);
        $passport_number=$re["passport_number"];
        $person->addChild('passport_number',$passport_number);
        $is_foreign_citizen_flag=$re["is_foreign_citizen_flag"];
        $person->addChild('is_foreign_citizen_flag',$is_foreign_citizen_flag);
        $bbirth_date=$re["birth_date"];
        $birth_date=date("d.m.Y",strtotime($bbirth_date));
        $person->addChild('birth_date',$birth_date);
        $vtek_doc_serya=$re["vtek_doc_serya"];
        $person->addChild('vtek_doc_serya',$vtek_doc_serya);
        $vtek_doc_number=$re["vtek_doc_number"];
        $person->addChild('vtek_doc_number',$vtek_doc_number);
        $vvtek_doc_reg_date=$re["vtek_doc_reg_date"];
        $vtek_doc_reg_date=date("d.m.Y",strtotime($vvtek_doc_reg_date));
        $person->addChild('vtek_doc_reg_date',$vtek_doc_reg_date);
        $invalid_group=$re["invalid_group"];
        $person->addChild('invalid_group',$invalid_group);
        $invalid_reason=$re["invalid_reason"];
        $person->addChild('invalid_reason',$invalid_reason);
        $invalid_percent=$re["invalid_percent"];
        $person->addChild('invalid_percent',$invalid_percent);
        $iinvalid_exp_date=$re["invalid_exp_date"];
        $invalid_exp_date=date("d.m.Y",strtotime($iinvalid_exp_date));
        $person->addChild('invalid_exp_date',$invalid_exp_date);
        $not_found_reason=$re["not_found_reason"];
        $person->addChild('not_found_reason',$not_found_reason);
        $vvtek_close_date=$re["vtek_close_date"];
        $vtek_close_date=date("d.m.Y",strtotime($vvtek_close_date));
        $person->addChild('vtek_close_date',$vtek_close_date);
        $vtek_close_reason=$re["vtek_close_reason"];
        $person->addChild('vtek_close_reason',$vtek_close_reason);
    }
    // passporti bor lekin malumoti yok
    $query5="select xm.case_number, xm.passport_series, xm.passport_number, xm.is_foreign_citizen_flag, xm.birth_date, xm.sobes_org_id, mih.seriya as vtek_doc_serya,  mih.nomer as vtek_doc_number,
                    mih.pdate as vtek_doc_reg_date, mih.guruh as invalid_group, ss.pf_invalid_reason as invalid_reason, mih.foiz as invalid_percent, mih.end_date invalid_exp_date, xm.xchecked as not_found_reason,
                    mih.tdate as vtek_close_date, sr.pf_close_reason as vtek_close_reason
            from xmlupload xm
                left join mijoz m ON xm.passport_series=m.pass_seriya and xm.passport_number=m.pass_code
                left join mijoz_ill_history mih ON mih.id_mijoz=m.id and mih.OLD=0 and mih.approve=1
                left join spr_sabab ss on mih.id_sabab=ss.id
                left join spr_royhat sr on mih.id_royhat=sr.id
            where
            xm.xchecked='02'
            and xm.xrequest_id=".$request_id;
    // echo $query3;
    $result5 = pg_query($connection, $query5) or die("Error in query: $query5. " . pg_last_error($connection));
    $xml_array3= pg_fetch_all($result5);
    // print_r($xml_array1);
    $j=0;
    foreach ($xml_array3 as $re){
        $person = $persons->addchild('person');
        $case_number=$re["case_number"];
        $person->addChild('case_number',$case_number);
        $sobes_org_id=$re["sobes_org_id"];
        $person->addChild('sobes_org_id',$sobes_org_id);
        $passport_series=$re["passport_series"];
        $person->addChild('passport_series',$passport_series);
        $passport_number=$re["passport_number"];
        $person->addChild('passport_number',$passport_number);
        $is_foreign_citizen_flag=$re["is_foreign_citizen_flag"];
        $person->addChild('is_foreign_citizen_flag',$is_foreign_citizen_flag);
        $bbirth_date=$re["birth_date"];
        $birth_date=date("d.m.Y",strtotime($bbirth_date));
        $person->addChild('birth_date',$birth_date);
        $vtek_doc_serya=$re["vtek_doc_serya"];
        $person->addChild('vtek_doc_serya',$vtek_doc_serya);
        $vtek_doc_number=$re["vtek_doc_number"];
        $person->addChild('vtek_doc_number',$vtek_doc_number);
        $vvtek_doc_reg_date=$re["vtek_doc_reg_date"];
        $vtek_doc_reg_date=date("d.m.Y",strtotime($vvtek_doc_reg_date));
        $person->addChild('vtek_doc_reg_date',$vtek_doc_reg_date);
        $invalid_group=$re["invalid_group"];
        $person->addChild('invalid_group',$invalid_group);
        $invalid_reason=$re["invalid_reason"];
        $person->addChild('invalid_reason',$invalid_reason);
        $invalid_percent=$re["invalid_percent"];
        $person->addChild('invalid_percent',$invalid_percent);
        $iinvalid_exp_date=$re["invalid_exp_date"];
        $invalid_exp_date=date("d.m.Y",strtotime($iinvalid_exp_date));
        $person->addChild('invalid_exp_date',$invalid_exp_date);
        $not_found_reason=$re["not_found_reason"];
        $person->addChild('not_found_reason',$not_found_reason);
        $vvtek_close_date=$re["vtek_close_date"];
        $vtek_close_date=date("d.m.Y",strtotime($vvtek_close_date));
        $person->addChild('vtek_close_date',$vtek_close_date);
        $vtek_close_reason=$re["vtek_close_reason"];
        $person->addChild('vtek_close_reason',$vtek_close_reason);
    }
    echo "j=".$j." ; ";
            $xml->asXML("out/$response_file_name");
    echo "6 step";


}

pg_close($connection);

?>
