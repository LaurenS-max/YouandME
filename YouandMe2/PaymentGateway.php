<?php
header('Content-Type: application/json');

// Simulated in-memory storage for demo purposes
$transactions = [
    1 => ['id' => 1, 'amount' => 100, 'currency' => 'ZAR', 'status' => 'completed'],
    2 => ['id' => 2, 'amount' => 200, 'currency' => 'ZAR', 'status' => 'pending'],
];

// PayFast merchant credentials (use your own in production!)
define('PF_MERCHANT_ID', '10000100');
define('PF_MERCHANT_KEY', '46f0cd694581a');
define('PF_PASSPHRASE', ''); // If enabled in PayFast dashboard

// Helper: send JSON response
function send($data, $code = 200) {
    http_response_code($code);
    echo json_encode($data);
    exit;
}

// Helper: generate a PayFast payment URL
function generate_payfast_url($transaction) {
    $data = [
        'merchant_id' => PF_MERCHANT_ID,
        'merchant_key' => PF_MERCHANT_KEY,
        'return_url' => 'https://yourdomain.com/payment_success',
        'cancel_url' => 'https://yourdomain.com/payment_cancel',
        'notify_url' => 'https://yourdomain.com/payment_callback', // Must be public!
        'amount' => number_format($transaction['amount'], 2, '.', ''),
        'item_name' => 'Order #' . $transaction['id'],
        'currency' => 'ZAR'
    ];

    // Build query string
    $query = http_build_query($data);
    // If passphrase is set, append to query for signature
    if (PF_PASSPHRASE) {
        $query .= '&passphrase=' . urlencode(PF_PASSPHRASE);
    }
    // Generate signature
    $signature = md5($query);
    $data['signature'] = $signature;

    // Build final URL
    $pfUrl = 'https://sandbox.payfast.co.za/eng/process?' . http_build_query($data);
    return $pfUrl;
}

// Get the HTTP method and path
$method = $_SERVER['REQUEST_METHOD'];
$path = isset($_GET['path']) ? $_GET['path'] : '';

// Parse ID from path if needed
function getIdFromPath($path) {
    $parts = explode('/', trim($path, '/'));
    return isset($parts[1]) ? (int)$parts[1] : null;
}

// List all transactions: GET /transactions
if ($method === 'GET' && $path === 'transactions') {
    global $transactions;
    send(array_values($transactions));
}

// Get a transaction by ID: GET /transactions/{id}
if ($method === 'GET' && preg_match('#^transactions/\d+$#', $path)) {
    $id = getIdFromPath($path);
    global $transactions;
    if (isset($transactions[$id])) {
        send($transactions[$id]);
    } else {
        send(['error' => 'Transaction not found'], 404);
    }
}

// Create a new transaction: POST /transactions
if ($method === 'POST' && $path === 'transactions') {
    $input = json_decode(file_get_contents('php://input'), true);
    if (!isset($input['amount'], $input['currency'])) {
        send(['error' => 'Amount and currency required'], 400);
    }
    global $transactions;
    $id = max(array_keys($transactions)) + 1;
    $transaction = [
        'id' => $id,
        'amount' => $input['amount'],
        'currency' => $input['currency'],
        'status' => 'pending'
    ];
    $transactions[$id] = $transaction;

    // Generate PayFast payment URL
    $payfastUrl = generate_payfast_url($transaction);

    send([
        'transaction' => $transaction,
        'payment_url' => $payfastUrl
    ], 201);
}

// PayFast IPN/webhook callback: POST /payment_callback
if ($method === 'POST' && $path === 'payment_callback') {
    // In real production, you must validate the IPN from PayFast!
    // For demo, we simulate updating status to 'completed'
    $pfData = $_POST;
    $orderId = isset($pfData['m_payment_id']) ? (int)$pfData['m_payment_id'] : null;
    global $transactions;
    if ($orderId && isset($transactions[$orderId])) {
        $transactions[$orderId]['status'] = 'completed';
        send(['status' => 'Transaction updated']);
    } else {
        send(['error' => 'Transaction not found'], 404);
    }
}

// Not found
send(['error' => 'Endpoint not found'], 404);
?>s