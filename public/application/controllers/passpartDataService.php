<?php
  function getPersonalData($passSerie, $pinfl) {
    // $client = new SoapClient('http://172.21.1.123:8090/IDTNPersonDocInfoService?wsdl', array('soap_version' => SOAP_1_2));
    // $auth = array(
    //   'ApplicationGuid' => '182047e0-3482-4652-b4c5-e63ac0f4a2f2',
    //   'UserLogin' => 'anvar.utepov',
    //   'UserFullName' => 'Анвар Утепов',
    //   'InstitutionInn' => '222222222',
    //   'UserGuid' => '93901725-3362-4914-bc8c-59ed7afdd99e',
    //   'UserPosition' => 1,
    //   'SeriesNumber' => $passSerie,
    //   'Pinfl' => $pinfl,
    //   'UserPersonIdentifier' => '31804873950080',
    //   'langId' => 1
    // );

    // $request = array(
    //   'Data' => $auth,
    //   'SignDate' => date('Y-m-d H:i:s')
    // );
    // try {
    //   $params = json_encode($request);
    //   $response = $client->GetPersonDocInfo($params);
    //   return $response;
    // }
    // catch ( SoapFault $sf ) {
    //     echo $sf->getMessage();

    //     echo '<pre>';
    //     var_dump( $sf );
    //     echo '</pre>';
    // }

    try {
      $ch = curl_init("http://172.23.0.18:89/api/PersonInfo?passSerie=$passSerie&pinfl=$pinfl");
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($ch, CURLOPT_HEADER, 0);
      $data = curl_exec($ch);
      curl_close($ch);

      //print_r($data);

      return $data;
    }
    catch ( SoapFault $sf ) {
        echo $sf->getMessage();

        echo '<pre>';
        var_dump( $sf );
        echo '</pre>';
    }


  }
?>
