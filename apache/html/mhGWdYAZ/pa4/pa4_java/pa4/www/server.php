<?php
	/*
		$PORT = the port on which we are connecting to the "remote" machine
		$HOST = the ip of the remote machine (use 'localhost' if the same machine)
	*/
	function queryIndex($port, $host, $searchterms)
	{
		// Send HTTP GET request to server. GET is the default option for cURL, so don't set it.
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, "$host:$port/search?q=".urlencode($searchterms));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$resp = curl_exec($ch);
		curl_close($ch);

		// Decode response message and send it back to caller
		$resp = json_decode($resp, true);
		$results = $resp["hits"];
		return $results;
	}
?>