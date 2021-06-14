<?php

namespace Kapps\Model\Auth\Microsoft;

use Kapps\Model\Auth\Microsoft\TokenCache;
use Kapps\Model\Auth\Microsoft\Token;
use Microsoft\Graph\Graph;
use Microsoft\Graph\Model;

class OutlookController
{
	public function mail()
	{
		if (session_status() == PHP_SESSION_NONE) {
			session_start();
		}

		$tokenCache = new TokenCache;

		$graph = new Graph();
		$graph->setAccessToken($tokenCache->getAccessToken());

		$user = $graph->createRequest('GET', '/me')
						->setReturnType(Model\User::class)
						->execute();

		$messageQueryParams = array (
			// Only return Subject, ReceivedDateTime, and From fields
			"\$select" => "subject,receivedDateTime,from",
			// Sort by ReceivedDateTime, newest first
			"\$orderby" => "receivedDateTime DESC",
			// Return at most 10 results
			"\$top" => "10"
		);

		$getMessagesUrl = '/me/mailfolders/inbox/messages?'.http_build_query($messageQueryParams);
		$messages = $graph->createRequest('GET', $getMessagesUrl)
							->setReturnType(Model\Message::class)
							->execute();

		// Rebuild messages and convert to array
		foreach ($messages as $key => $value) {
			$messages_arr[] = $this->getProtectedValue($value);
		}

		return array(
			'username' => $user->getDisplayName(),
			'messages' => $messages_arr
		);
	}



	public function calendar()
	{
        $tokenCache = new Token;

		$graph = new Graph();
		$graph->setAccessToken($tokenCache->get_access_token());

		$user = $graph->createRequest('GET', '/me')
						->setReturnType(Model\User::class)
						->execute();

		$eventsQueryParams = array (
			"\$select" => "subject,bodyPreview,organizer,attendees,start,end,location",
			"\$orderby" => "start/dateTime",
			// Return at most 10 results
			"\$top" => "10"
		);

		$getEventsUrl = '/me/events?'.http_build_query($eventsQueryParams);
		$events = $graph->createRequest('GET', $getEventsUrl)
						->setReturnType(Model\Event::class)
						->execute();

		// Rebuild events and convert to array
		foreach ($events as $key => $value) {
			$events_arr[] = $this->getProtectedValue($value);
		}

		return array(
			'username' => $user->getDisplayName(),
			'events' => $events_arr
		);
	}



    public function calendarView()
	{
        $tokenCache = new Token;

		$graph = new Graph();
		$graph->setAccessToken($tokenCache->get_access_token());

		$user = $graph->createRequest('GET', '/me')
						->setReturnType(Model\User::class)
						->execute();

		$eventsQueryParams = array (
			"\$startDateTime" => "2019-02-05T00:00:00.0000000",
			"\$endDateTime"   => "2019-02-10T00:00:00.0000000"
		);

		$getEventsUrl = '/me/calendar/calendarview?startDateTime=2019-02-05T00:00:00.0000000&endDateTime=2019-02-09T23:59:00.0000000';
		$events = $graph->createRequest('GET', $getEventsUrl)
						->setReturnType(Model\Event::class)
						->execute();

		// Rebuild events and convert to array
		foreach ($events as $key => $value) {
			$events_arr[] = $this->getProtectedValue($value);
		}

		return array(
			'username' => $user->getDisplayName(),
			'events' => $events_arr
		);
	}



	public function contacts()
	{
		if (session_status() == PHP_SESSION_NONE) {
			session_start();
		}

		$tokenCache = new TokenCache;

		$graph = new Graph();
		$graph->setAccessToken($tokenCache->getAccessToken());

		$user = $graph->createRequest('GET', '/me')
						->setReturnType(Model\User::class)
						->execute();

		$contactsQueryParams = array (
			// // Only return givenName, surname, and emailAddresses fields
			"\$select" => "givenName,surname,emailAddresses",
			// Sort by given name
			"\$orderby" => "givenName ASC",
			// Return at most 10 results
			"\$top" => "10"
		);

		$getContactsUrl = '/me/contacts?'.http_build_query($contactsQueryParams);
		$contacts = $graph->createRequest('GET', $getContactsUrl)
							->setReturnType(Model\Contact::class)
							->execute();

		return array(
			'username' => $user->getDisplayName(),
			'contacts' => $this->getProtectedValue($contacts)
		);
	}


	public function me()
	{
		if (session_status() == PHP_SESSION_NONE) {
			session_start();
		}

		$tokenCache = new TokenCache;

		$graph = new Graph();
		$graph->setAccessToken($tokenCache->getAccessToken());

		$user = $graph->createRequest('GET', '/me')
						->setReturnType(Model\User::class)
						->execute();
						
		return $this->getProtectedValue($user);
	}


	public function get_profile_photo()
	{
		$tokenCache = new TokenCache;

		$graph = new Graph();
		$graph->setAccessToken($tokenCache->getAccessToken());

		$curl = curl_init();

		curl_setopt_array($curl, array(
			CURLOPT_URL => 'https://graph.microsoft.com/v1.0/me/photo/$value',
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => "",
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 30,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => "GET",
			CURLOPT_HTTPHEADER => array(
				"Authorization: Bearer {$tokenCache->getAccessToken()}",
				"cache-control: no-cache"
			),
		));

		$response = curl_exec($curl);
		$err = curl_error($curl);

		curl_close($curl);
	}



	function getProtectedValue($obj) {
		$array = (array)$obj;
		$first_key = key($array);
		return $array[$first_key];
	}
}