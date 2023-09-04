<?php


namespace App\Services;


use App\helpers\Helpers;
use App\Models\Beneficiary;
use App\Models\Country;
use App\Models\Operator;
use App\Models\Sender;
use App\Models\Transfert;
use Symfony\Component\HttpKernel\Exception\NotAcceptableHttpException;

class WacePayService
{   private $config;
    private $base_url;
    /**
     * WacePayService constructor.
     */
    public function __construct()
    {
        //$this->config=Helpers::getService("wacepay");
       // $this->base_url=$this->config->url;
    }

    public function authenticate()
    {
        $endpoint = '/api/v1/login';
        $arrayJson = [
            "email" => $this->config->username,
            "password" => $this->config->password
        ];

        $response = $this->cURLAuth($endpoint, json_encode($arrayJson));
        logger(json_encode($response));
        if ($response->status === 2000) {
            $this->tokencinet=$response->access_token;
            return [
                "status" => false,
                "token" => $response->access_token,
            ];
        }
        return [
            "status" => false,
            "token" => null,
        ];
    }

    public function sendTransaction(Transfert $transfert)
    {
        $endpoint = '/api/v1/transaction/bank/create';
        $this->tokencinet=$this->authenticate()['token'];
        $amount=$transfert->amount;
        if (!is_null($this->tokencinet)){
            $customerReponse=$this->getCreateSender($transfert->sender());
            if ($customerReponse["status"] !==2000){
                throw new NotAcceptableHttpException($customerReponse['message']);
            }
            $beneficiaryReponse=$this->createBeneficiary($transfert->beneficiary(),$customerReponse['sender']['Code']);
            if ($beneficiaryReponse["status"] !==2000){
                throw new NotAcceptableHttpException($beneficiaryReponse['message']);
            }
            $bank = [
                "payoutCountry" => $transfert->beneficiary()->country()->code,
                "payoutCity" => "Douala",
                "receiveCurrency" => $transfert->beneficiary()->country()->currency,
                "amountToPaid" => $amount,
                /* "service" => $transaction->getTypetransaction(),
                 "senderCode" => $transaction->getCustomer()->getSenderCode(),
                 "beneficiaryCode" => $transaction->getBeneficiare()->getBeneficiaryCode(),
                 "sendingCurrency" => $transaction->getCustomer()->getCountry()->getMonaire(),
                 "bankAccount" => $transaction->getBeneficiare()->getBankaccountnumber(),
                 "bankName" => $transaction->getBeneficiare()->getBankname(),
                 "bankSwCode" => $transaction->getBeneficiare()->getBankswiftcode(),
                 "bankBranch" => $transaction->getBeneficiare()->getBankbranchnumber(),
                 "fromCountry" => $transaction->getCustomer()->getCountry()->getCodeString(),
                 "originFund" => "salary",
                 "reason" => $transaction->getRaisontransaction(),*/
                "relation" => "brother"
            ];
            $res = $this->cURL($endpoint, json_encode($bank));
            return [
                "status"=>200,
                "data"=>json_decode($res->getBody(), true)
            ];
        }else{
            return [
                "status"=>500,
                "data"=>['status'=>500]
            ];
        }

    }
    public function sendTransactionMobile(Transfert $transfert)
    {
        $endpoint = '/api/v1/transaction/wallet/create';
        $this->tokencinet=$this->authenticate()['token'];
        $amount=$transfert->amount;
        $beneficiary=$transfert->beneficiary;
        $sender=$transfert->sender();
        $country=Country::query()->find($beneficiary->country_id);
        $operateur=$transfert->operator();
        logger("#####----WACE------------");
        logger($this->tokencinet);
        if (!is_null($this->tokencinet)){
            $customerReponse=$this->getCreateSender($sender);
            if ($customerReponse["status"] !==2000){
                throw new NotAcceptableHttpException($customerReponse['message']);
            }
            $beneficiaryReponse=$this->createBeneficiary($beneficiary,$customerReponse['sender']['Code']);
            if ($beneficiaryReponse["status"] !==2000){
                throw new NotAcceptableHttpException($beneficiaryReponse['message']);
            }
            $wallet = [
                "payoutCountry" => $country->codeiso,
                "payoutCity" => "",
                "receiveCurrency" => $country->currency,
                "amountToPaid" => $amount,
                "service" => $operateur->code,
                "senderCode" => $customerReponse['sender']['Code'],
                "beneficiaryCode" => $beneficiaryReponse['sender']['Code'],
                "sendingCurrency" => "XAF",
                "mobileReceiveNumber" => $beneficiary->phone,
                "fromCountry" => $country->code,
                "originFund" => "Salary",
                "reason" => "Family",
                "relation" => "Brother"
            ];
            $this->logger->info("------paybody".json_encode($wallet));
            $res = $this->cURL($endpoint, json_encode($wallet));
            $transfert->update([
                't_ref' =>$res['transaction']['reference']
            ]);
            return $res;
        }else{
            return [
                "status"=>500,
                "data"=>['status'=>500]
            ];
        }

    }
    public function getStatusTransaction($reference)
    {
        $endpoint = '/api/v1/transaction/status/'.$reference;
        $this->tokencinet=$this->authenticate()['token'];
        $options = [
            'headers' => [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . $this->tokencinet,
            ]
        ];
        if (!is_null($this->tokencinet)){
            $res = $this->client->get($endpoint, $options);
            return [
                'status'=>200,
                'data'=>json_decode($res->getBody(), true)
            ];
        }else{
            return [
                'status'=>500,
                'data'=>[]
            ];
        }
    }

    public function getStatusBalance()
    {
        $endpoint = '/api/v1/account/balance';
        $token=$this->authenticate()['token'];
        $options = [
            'headers' => [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . $token,
            ]
        ];
        if (!is_null($token)){
            $res = $this->client->get($endpoint, $options);
            return [
                'status'=>500,
                'data'=>json_decode($res->getBody(), true)
            ];
        }else{
            return [
                'status'=>500,
                'data'=>[]
            ];
        }

    }
    public function getBankListCountry($code)
    {
        $endpoint = '/api/v1/transaction/bank/list/'.$code;
        $token=$this->authenticate()['token'];
        $options = [
            'headers' => [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . $token,
            ]
        ];
        if (!is_null($token)){
            $res = $this->client->get($endpoint, $options);
            return [
                'status'=>500,
                'data'=>json_decode($res->getBody(), true)
            ];
        }else{
            return [
                'status'=>500,
                'data'=>[]
            ];
        }

    }

    public function getCreateSender($user)
    {
        $endpoint = '/api/v1/sender/create';
        $country=$user->country();
        $customer=$user;
        $sender = [
            "firstName" => $customer->name,
            "lastName" => $customer->name,
            "address" => "douala",
            "phone" => $customer->phone,
            "country" => $country->code,
            "city" => "Douala",
            "gender" => "M",
            "civility" => "Maried",
            "idNumber" => $customer->identification_number,
            "idType" => "PP",
            "occupation" => "Develloper",
            "state" => "",
            "nationality" => $country->name,
            "comment" => "new sender",
            "zipcode" => "78952",
            "dateOfBirth" => "1990-03-03",
            "dateExpireId" => "2029-02-03",
            "pep" => false,
            "updateIfExist" => true
        ];
        $this->logger->info("##############DataCustomer################");

        $res = $this->cURL($endpoint,json_encode($sender));
        $this->logger->info("##############ResponseCustomer################");
        $this->logger->info($res);

        return $res;
    }

    public function createBeneficiary($beneficiary_,$sendercode)
    {
        $endpoint = '/api/v1/beneficiary/create';
        $beneficiary = [
            "firstName" => $beneficiary_->name,
            "lastName" => $beneficiary_->name,
            "address" => "non defini",
            "phone" => $beneficiary_->phone,
            "country" => $beneficiary_->country()->codeiso,
            "city" => $beneficiary_->country()->name,
            "mobile" => $beneficiary_->phone,
            "email" => $beneficiary_->email,
            "idNumber" => "147852964",
            "idType" => "PP",
            "sender_code" => $sendercode,
            "updateIfExist" => true
        ];
        $this->logger->info("##############DataBeneficiary################");
        $this->logger->info(json_encode($beneficiary));
        $res = $this->cURL($endpoint, json_encode($beneficiary));
        $this->logger->info("##############ResponseBeneficiary################");

        return $res;
    }
    public function validateTransaction($reference)
    {
        $this->tokencinet=$this->authenticate()['token'];
        $endpoint = '/api/v1/transaction/confirm';
        $body=[
            "reference"=>$reference
        ];
        return $this->cURL($endpoint, json_encode($body));
    }
    protected function cURL($url, $json)
    {
        // Create curl resource
        $ch = curl_init($this->base_url.'/'.$url);

        // Request headers
        $headers = array(
            'Content-Type:application/json',
            'Authorization' => 'Bearer ' . $this->tokencinet,
        );
        // Return the transfer as a string
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        // $output contains the output string
        $output = curl_exec($ch);

        // Close curl resource to free up system resources
        curl_close($ch);
        return json_decode($output);
    }
    protected function cURLAuth($url, $json)
    {
        // Create curl resource
        $ch = curl_init($this->base_url.'/'.$url);

        // Request headers
        $headers = array(
            'Accept' => 'application/json',
            'Content-Type:application/json',
        );
        // Return the transfer as a string
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        //logger(json_encode($ch));
        // $output contains the output string
        $output = curl_exec($ch);

        // Close curl resource to free up system resources
        curl_close($ch);
        return json_decode($output);
    }
}
