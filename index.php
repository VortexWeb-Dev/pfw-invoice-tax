<?php 
require_once(__DIR__ . '/crest/crest.php');
require_once(__DIR__ . '/utils/index.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    file_put_contents('logs/event_data.log', print_r($_POST, true), FILE_APPEND);

    $data = $_POST;
    $deal_id = $data['data']['FIELDS']['ID'];
    file_put_contents('logs/ids.log', $deal_id, FILE_APPEND);
    
    $result = CRest::call('crm.deal.get', [
    	'id' => $deal_id,
    ]);
    
    $deal = $result['result'];
    file_put_contents('logs/deals.log', print_r($deal, true), FILE_APPEND);
    
    $totalAmount = $deal['OPPORTUNITY'];
    $totalTax = $deal['TAX_VALUE'];
    
    $taxes = getTaxes($totalTax);
    $sgst = $taxes['sgst'];
    $cgst = $taxes['cgst'];
    $amountInWords = numberToWords($totalAmount);
    
    file_put_contents('logs/taxes.log', print_r(['taxValue' => $totalTax, 'taxes' => $taxes, 'sgst' =>  $sgst, 'cgst' =>  $cgst, 'amountInWords' => $amountInWords], true), FILE_APPEND);
    
    
     $deal_response = CRest::call('crm.deal.update', [
    	'id' => $deal_id,
    	'fields' => [
    		'UF_CRM_1724828048941' => $totalTax,
	    	'UF_CRM_1724827854696' => $sgst,
	    	'UF_CRM_1724827972837' => $cgst,
	    	'UF_CRM_1724828020569' => $amountInWords
    	]
    ]);

    file_put_contents('logs/deal_res.log', print_r($deal_response, true), FILE_APPEND);


} else {
    header('HTTP/1.1 405 Method Not Allowed');
    echo json_encode(['status' => 'error', 'message' => 'Only POST requests are allowed']);
    exit;
}
