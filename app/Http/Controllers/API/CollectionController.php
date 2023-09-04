<?php


namespace App\Http\Controllers\API;


use App\helpers\Constant;
use App\helpers\Helpers;
use App\Models\AccountKey;
use App\Models\Country;
use App\Models\Operator;
use App\Models\Payment;
use Illuminate\Http\Request;

class CollectionController extends BaseController
{
    public function requestToPay(Request $request)
    {
        $data=$request->all();
        $x_merchant_key=$request->headers->get('X-merchant-key');
        if (is_null($x_merchant_key)){
            return $this->sendError("X-merchant-key is missed",['error'=>"X-merchant-key is missed",'code'=>800]);
        }
        $merchant=AccountKey::query()->firstWhere(['merchant_key'=>$x_merchant_key]);
        if (is_null($merchant)){
            return $this->sendError("X-merchant-key is not correct",['error'=>"X-merchant-key is not correct",'code'=>801]);
        }
        if (!isset($data['amount']) || is_null($data['amount'])){
            return $this->sendError("amount is not null",['error'=>"amount is not null",'code'=>841]);
        }
        if (!isset($data['reference']) || is_null($data['reference'])){
            return $this->sendError("reference is not null",['error'=>"reference is not null",'code'=>841]);
        }
        if (!isset($data['payment_options']) || is_null($data['payment_options'])){
            return $this->sendError("payment_options is not null",['error'=>"payment_options is not null",'code'=>841]);
        }
        if (!isset($data['customer']['phone']) || is_null($data['customer']['phone'])){
            return $this->sendError("phone is not null",['error'=>"phone is not null",'code'=>841]);
        }
        $country=Country::query()->firstWhere(['codeiso'=>$data['country']]);
        if (is_null($country)){
            return $this->sendError("country is not found",['error'=>"country is not found",'code'=>842]);
        }
        $payment=new Payment();
        $payment->amount=$data['amount'];
        $payment->reference=$data['reference'];
        $payment->country_id=$country->id;
        $payment->currency=$data['currency'];
        $payment->payment_options=$data['payment_options'];
        $payment->customer_phone=$data['customer']['phone'];
        $payment->customer_email=$data['customer']['email'];
        $payment->customer_name=$data['customer']['name'];
        $payment->option_title=$data['option']['title'];
        $payment->option_description=$data['option']['description'];
        $payment->option_logo=$data['option']['logo'];
        $payment->account_key_id=$merchant->id;
        $payment->code_link=Helpers::generatealeatoire(78);
        $payment->save();
        return $this->sendResponse([
            "message"=>"request send successfully",
            "status"=>Constant::PENDING,
            "pay_link"=>env("APP_URL"),
            "code_link"=>$payment->code_link,
        ], 'Operation successfully.');
    }
}
