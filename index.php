<!-- <?php

require_once('vendor/autoload.php'); // Path to Composer autoload.php

use setasign\Fpdi\Fpdi;

// Function to fetch file from URL
function fetchFile($url) {
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $fileContent = curl_exec($ch);
    curl_close($ch);
    return $fileContent;
}

// Function to download a file from a URL and save it locally
function fetchAndStoreFile($url) { 
    // Fetch file content from URL
    $fileContent = file_get_contents($url);

    // Extract filename from URL
    $fileName = basename($url);

    $destination = "tmp/".time();
    if (!file_exists($destination)) {
        mkdir($destination, 0755, true);
    }
    $destination = $destination."/". $fileName;

    // Store file locally 
    file_put_contents($destination, $fileContent);

    return $destination;
}
 
// Function to format text content into JSONL format
function formatToJSONL($text) {
    $lines = explode("\n", $text);
    $jsonl = '';
    foreach ($lines as $line) {
        if (!empty($line) && $line && is_string($line))  {
            $jsonl .= json_encode(array('text' => $line)) . "\n";
        }
    }
    return $jsonl;
}

function saveJSONLToFile($jsonlContent) {
    $fileName = uniqid('jsonl_', true) . '.jsonl';
    file_put_contents($fileName, $jsonlContent);
    return $fileName;
}

// Function to send text to OpenAI and get response
function uploadFileToOpenAI($filePath, $apiKey) { 
    $url = 'https://api.openai.com/v1/files';
    
    $headers = array(
        'Authorization: Bearer ' . $apiKey,
    );

    $postData = array(
        'purpose' => 'assistants',  
        'file' => new CURLFile($filePath),
    ); 
    // Initialize cURL session
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);

    // Execute cURL session
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    return array('code' => $httpCode, 'response' => $response);
}

$destination = "temp";

// API endpoint
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve data from the incoming request
    $requestData = json_decode(file_get_contents('php://input'), true);
    // try {
        // Ensure necessary data is provided
        if (isset($requestData['file_url']) && isset($requestData['openai_api_key'])) {
            // Fetch PDF file content from URL
            $filePath = fetchAndStoreFile($requestData['file_url']);  
            
            // Upload JSONL content to OpenAI
            $uploadResponse = uploadFileToOpenAI($filePath, $requestData['openai_api_key']);
             
            return print_r(['success' => $uploadResponse ]);
            // exit();
        } else { 
            return print_r(['error 1' => 'Missing parameters.']);
            //exit();
        }
    // } catch (\Throwable $th) {
    //     return print_r(['error 2' => $th->getMessage()]);
    // }
} else {
    //http_response_code(405);
    return print_r(['error 3' => 'Method not allowed.']);
    //exit();
}

?> -->
