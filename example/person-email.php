<?php

include_once '../creds.php';
include_once '../Services/FullContact.php';
include_once 'PersonDAO.php';
include_once 'PersonVO.php';
include_once 'APIKeyCreator.php';

//initialize our FullContact API object
//get your api key here:  http://fullcontact.com/getkey
$fullcontact = new Services_FullContact_Person($apikey);
$personDAO = new PersonDAO('localhost', 'root', 'B1ueW5ale', 'y_list');
$apiKeyCreator = new APIKeyCreator('keys.txt');

$persons = $personDAO->getPersons();

$count = 0;

forEach($persons as $person){

	$status = $person->getFullContactDumpJsonStatus();

	//Skip bellow if we have a result.
	if($status == 200 || $status == 404 || $status == 422)
		continue;

	$person->setFullContactDumpJson( $fullcontact->lookupByEmail( $person->getEmailAddress() ) );

	if($person->getFullContactDumpJsonStatus() == 403){
		unset($fullcontact);
		echo "Got 403 response. Out of credit? Trying next key" . "\n";
		$fullcontact = new Services_FullContact_Person( $apiKeyCreator->getNextKey() );
		continue;
	}

	echo "Status: " . $status . " ... Quering email: " . $person->getEmailAddress() . "\n" ;
	echo "Returned: " . $person->getFullContactDumpJsonStatus() . "\n" ;

	$personDAO->save($person);

	$count++;
}

echo "END\n";