<?php

require '../vendor/autoload.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

use MrShan0\PHPFirestore\FirestoreClient;
use MrShan0\PHPFirestore\Fields\FirestoreTimestamp;
use MrShan0\PHPFirestore\Fields\FirestoreArray;
use MrShan0\PHPFirestore\Fields\FirestoreBytes;
use MrShan0\PHPFirestore\Fields\FirestoreGeoPoint;
use MrShan0\PHPFirestore\Fields\FirestoreObject;
use MrShan0\PHPFirestore\Fields\FirestoreReference;
use MrShan0\PHPFirestore\Attributes\FirestoreDeleteAttribute;
use MrShan0\PHPFirestore\FirestoreDocument;

class Firestore {
    const projectID = 'citerapp-1e2e7';
    const key = 'AIzaSyDj031xDrHTgnQbdVDCHbfEfj5B6fvkR34';
    
    private $firestoreClient;

    function __construct()
    {
        $this->firestoreClient = $this->getConn();
    }

    public function getConn() {
        $firestoreClient = new FirestoreClient( self::projectID, self::key, [
            'database' => '(default)',
        ]);
        return $firestoreClient;
    }

    public function insertProvider($collection, $name, $uID) {
         
        $this->firestoreClient->addDocument($collection, [
            'certProvider' => $name,
            'collectionID' => $uID
        ], $uID);
    }
 

    public function updateProvider($collectionID, $path, $newValue)
    {
        $this->firestoreClient->setDocument($path, [
            'certProvider' => $newValue,
            'collectionID' => $collectionID,
            'existingFieldToRemove' => new FirestoreDeleteAttribute
        ], true);
        
    }


    public function insertTopic($collection, $provID, $provider, $title, $desc, $uID )
    {
        $this->firestoreClient->addDocument($collection, [
            'ID' => $uID,
            'title' => $title,
            'Provider' => $provider,
            'ProviderID' => $provID,
            'Description' => $desc
        ], $uID);
    }

    public function updateTopic($path, $title, $desc, $ID, $prov, $provID)
    {
        $this->firestoreClient->setDocument($path, [
            'ID' => $ID,
            'title' => $title,
            'Provider' => $prov,
            'ProviderID' => $provID,
            'Description' => $desc,
            'existingFieldToRemove' => new FirestoreDeleteAttribute
        ], true);
    }

    public function insertQuestion($collection, $topic, $sect, $question, $qImage, $choices, $correctAnswer, $uID)
    { 
        
        $this->firestoreClient->addDocument($collection, [
            'ID' => $uID,
            'topic' => $topic,
            'section' => $sect,
            'question' => $question,
            'qImage' => $qImage,
            'choices' => new FirestoreArray($choices),
            'correctAnswer' => $correctAnswer
        ], $uID);
    }
    public function updateQuestion($collection, $ID, $topic, $sect, $question, $qImage, $choices, $correctAnswer)
    { 
        $this->firestoreClient->setDocument($collection, [
            'ID' => $ID,
            'topic' => $topic,
            'section' => $sect,
            'question' => $question,
            'qImage' => $qImage,
            'choices' => new FirestoreArray($choices),
            'correctAnswer' => $correctAnswer,
            'existingFieldToRemove' => new FirestoreDeleteAttribute
        ], true);
    }
}

 
?>