<?php

namespace App\Services;

use \PayPal\Api\Payout;
use App\Models\Profile;
use \PayPal\Api\Currency;
use \PayPal\Api\PayoutItem;
use App\Models\Transaction;
use \PayPal\Rest\ApiContext;
use Illuminate\Http\Request;
use \PayPal\Api\WebhookEvent;
use App\Aggregates\AccountAggregate;
use \PayPal\Auth\OAuthTokenCredential;
use \PayPal\Api\VerifyWebhookSignature;
use \PayPal\Api\PayoutSenderBatchHeader;
use App\Notifications\PayPalPayOutFailed;
use PayPal\Api\VerifyWebhookSignatureResponse;

class PayPalService {

    private $apiContext;
    private $mode;
    private $client_id;
    private $secret;
    
    // Create a new instance with our paypal credentials
    public function __construct()
    {
        // Detect if we are running in live mode or sandbox
        if(config('paypal.settings.mode') == 'live'){
            $this->client_id = config('paypal.live_client_id');
            $this->secret = config('paypal.live_secret');
        } else {
            $this->client_id = config('paypal.sandbox_client_id');
            $this->secret = config('paypal.sandbox_secret');
        }
        
        // Set the Paypal API Context/Credentials
        $this->apiContext = new ApiContext(new OAuthTokenCredential($this->client_id, $this->secret));
        $this->apiContext->setConfig(config('paypal.settings'));


    }

    public function singlePayout(Profile $profile, Transaction $transaction)
    {
        $transaction_id = unique_code();
        $batch_id = uniqid();
        $transaction->transaction_id = $transaction_id;
        $transaction->batch_id = $batch_id;

        $payouts = new Payout();
        $senderBatchHeader = new PayoutSenderBatchHeader();
        $senderBatchHeader->setSenderBatchId($batch_id)
                          ->setEmailSubject("Yohli Money Transfer");
        $senderItem = new PayoutItem();
        $senderItem->setRecipientType('Email')
            ->setNote('Thanks for using Yohli!')
            ->setReceiver($profile->email)
            ->setSenderItemId($transaction_id)
            ->setAmount(new Currency("{
                'value': $transaction->amount,
                'currency':'USD'
            }"));

            
        $payouts->setSenderBatchHeader($senderBatchHeader)
            ->addItem($senderItem);

        // $request = clone $payouts;


        try {
            $output = $payouts->createSynchronous($this->apiContext);
        } catch (\Exception $e) {
            \Log::error($e->getMessage());
            $transaction->status = "pending";
            $transaction->save();
            return;
        }

        
        // $transaction->batch_id = $output->getBatchHeader()->getPayoutBatchId();
        $transaction->save();
        // \ResultPrinter::printResult("Created Single Synchronous Payout", "Payout", $output->getBatchHeader()->getPayoutBatchId(), $request, $output);
        \Log::info(print_r($output, true));
        // return $output;

    }

    public function validateWebhook(Request $request)
    {
        // Get request details
        $requestBody = $request->getContent();
        \Log::info($requestBody);
        \Log::info($headers);
        $headers = array_change_key_case($request->headers->all(), CASE_UPPER);
        
        /**
        * Receive HTTP headers that you received from PayPal webhook.
        * Just uncomment the below line to read the data from actual request.
        */
        /** @var Array $headers */
        //$headers = getallheaders();
        
        /**
        * In documentations https://developer.paypal.com/docs/api/webhooks/v1/#verify-webhook-signature
        * All header keys as UPPERCASE, but I receive the header key as the example array, First letter as UPPERCASE
        */
        
        $signatureVerification = new VerifyWebhookSignature();
        $signatureVerification->setAuthAlgo($headers['PAYPAL-AUTH-ALGO']);
        $signatureVerification->setTransmissionId($headers['PAYPAL-TRANSMISSION-ID']);
        $signatureVerification->setCertUrl($headers['PAYPAL-CERT-URL']);
        $signatureVerification->setWebhookId("0FV983392A0531201"); // Note that the Webhook ID must be a currently valid Webhook that you created with your client ID/secret.
        $signatureVerification->setTransmissionSig($headers['PAYPAL-TRANSMISSION-SIG']);
        $signatureVerification->setTransmissionTime($headers['PAYPAL-TRANSMISSION-TIME']);
        
        $signatureVerification->setRequestBody($requestBody);
        $req = clone $signatureVerification;
        
        try {
            /** @var \PayPal\Api\VerifyWebhookSignatureResponse $output */
            $output = $signatureVerification->post($apiContext);
        } catch (\Exception $e) {
            // NOTE: PLEASE DO NOT USE RESULTPRINTER CLASS IN YOUR ORIGINAL CODE. FOR SAMPLE ONLY
            // ResultPrinter::printError("Validate Received Webhook Event", "WebhookEvent", null, $req->toJSON(), $ex);
            \Log::critical($e->getMessage());

            return response('Error: Could not verify signature.', 500)->header('Content-Type', 'text/plain');

        }

        $status = $output->getVerificationStatus(); // 'SUCCESS' or 'FAILURE'

        switch(strtoupper($status)) {
            case "FAILURE":
                return response('Forbidden: Invalid signature.', 403)->header('Content-Type', 'text/plain');
            case "SUCCESS":
                $json = json_decode($request_body, 1);
                goto UPDATE_TRANSACTION;
        }
    
        UPDATE_TRANSACTION:
        switch($json['event_type']) {
            case (
                    "PAYMENT.PAYOUTS-ITEM.REFUNDED" || "PAYMENT.PAYOUTS-ITEM.CANCELED" || 
                    "PAYMENT.PAYOUTS-ITEM.DENIED" || "PAYMENT.PAYOUTS-ITEM.FAILED" || 
                    "PAYMENT.PAYOUTS-ITEM.RETURNED" 
                 ):
                $itemID = $json['event_type']['resource']['payout_item']['sender_item_id'];
                $transaction = Transaction::where('transaction_id')->first();
                $transaction->status = 'failed';
                $transaction->description = $json['transaction_status'];
                $transaction->save();
                $account = $transaction->account()->with('user')->first();

                $aggregateRoot = AccountAggregate::retrieve($account->uuid);
                $aggregateRoot->addMoney($request->amount);

                $user = $account->user;
                $user->notify(new PayPalPayOutFailed($transaction));
                return response('Success: PAYMENT.PAYOUTS-ITEM.SUCCEEDED webhook.', 400)->header('Content-Type', 'text/plain');

            case "PAYMENT.PAYOUTS-ITEM.SUCCEEDED":
                $itemID = $json['event_type']['resource']['payout_item']['sender_item_id'];
                $transaction = Transaction::where('transaction_id')->first();
                $transaction->status = 'success';
                $transaction->description = $json['transaction_status'];
                $transaction->save();

                $account = $transaction->account()->with('user')->first();
                $user = $account->user;

                $user->notify(new PayPalPayOutSuccess($transaction));
                return response('Success: PAYMENT.PAYOUTS-ITEM.SUCCEEDED webhook.', 400)->header('Content-Type', 'text/plain');


            default:
                return response('Error: Invalid webhook.', 500)->header('Content-Type', 'text/plain');

            
            // case "PAYMENT.PAYOUTSBATCH.BLOCKED":

            // case "PAYMENT.PAYOUTSBATCH.HELD":

            // case "PAYMENT.PAYOUTSBATCH.SUCCEESS":

            // case "PAYMENT.PAYOUTSBATCH.PROCESSING":

            // case "PAYMENT.PAYOUTSBATCH.DENIED":
               
        }

        
        // return response('Error: Invalid webhook.', 500)->header('Content-Type', 'text/plain');
        
        // NOTE: PLEASE DO NOT USE RESULTPRINTER CLASS IN YOUR ORIGINAL CODE. FOR SAMPLE ONLY
        // ResultPrinter::printResult("Validate Received Webhook Event", "WebhookEvent", $output->getVerificationStatus(), $req->toJSON(), $output);
    }
}