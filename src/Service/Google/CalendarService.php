<?php
/**
 * Created by PhpStorm.
 * User: dangis
 * Date: 18.6.2
 * Time: 16.43
 */

namespace App\Service\Google;

use Google_Client;
use Google_Service_Calendar;
use Google_Service_Calendar_Event;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class CalendarService
{
    private $folderName;

    private $tokenStorage;

    public function __construct(TokenStorageInterface $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
        $this->folderName = "Nagido-Files";
    }

    public function getClient()
    {
        $user = $this->tokenStorage->getToken()->getUser();

        //Create google client
        $client = new Google_Client();
        $client->setAccessToken($user->getGoogleAccessToken());

        //Set Drive Service
        return new Google_Service_Calendar($client);
    }

    public function setDate($date, $desc, $name) {
        $service = $this->getClient();
        $event = new Google_Service_Calendar_Event(array(
            'summary' => 'Nagido Document' . ' - ' . $name ,
            'description' => $desc,
            'start' => array(
                'dateTime' => $date,
                'timeZone' => 'Europe/Vilnius',
            ),
            'end' => array(
                'dateTime' => $date,
                'timeZone' => 'Europe/Vilnius',
            ),
            'reminders' => array(
                'useDefault' => FALSE,
                'overrides' => array(
                    array('method' => 'email', 'minutes' => 1)
                ),
            ),
            "colorId" => "6"
        ));
        $service->events->insert('primary', $event);
    }
}