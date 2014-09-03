<?php

include_once '../creds.php';
include_once '../Services/FullContact.php';
include_once 'PersonDAO.php';
include_once 'PersonVO.php';
include_once 'APIKeyCreator.php';

$apiKeyCreator = new APIKeyCreator('keys.txt');
//initialize our FullContact API object
//get your api key here:  http://fullcontact.com/getkey
$fullcontact = new Services_FullContact_Person( $apiKeyCreator->getNextKey() );
$personDAO = new PersonDAO('localhost', 'root', 'B1ueW5ale', 'y_list', $argv[1]);

$persons = $personDAO->getPersons();

$successCount = 0;



forEach($persons as $person){

	$status = $person->getFullContactDumpJsonStatus();

	//Skip bellow if we have a result.
	if($status == 200 || $status == 404 || $status == 422) //  || $status == 202
		continue;

	// set status: 666

	//$person->setFullContactDump()

	$person->setFullContactDumpJson( $fullcontact->lookupByEmail( $person->getEmailAddress() ) );

	// unset status: 666

	if($person->getFullContactDumpJsonStatus() == 403){
		
		echo "Got 403 response. Out of credit? Trying next key" . "\n";
		echo "\x07"; //Beep :)

		unset($fullcontact);
		$fullcontact = new Services_FullContact_Person( $apiKeyCreator->getNextKey() );
		continue;
	}

	echo "Status: " . $status . " ... Quering email: " . $person->getEmailAddress() . "\n" ;
	echo "Returned: " . $person->getFullContactDumpJsonStatus() . "\n" ;

	if($person->getFullContactDumpJsonStatus() == 200){
		$successCount++;
	}

	echo("Success count: " . $successCount . "\n");

	$personDAO->save($person);

}

echo "END\n";