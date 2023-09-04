<?php


namespace App\Http\Controllers\API;


use App\helpers\Constant;
use App\helpers\Helpers;
use App\Models\AccountKey;
use App\Models\Beneficiary;
use App\Models\Country;
use App\Models\Operator;
use App\Models\Sender;
use App\Models\Transfert;
use Illuminate\Http\Request;

class TransfertController extends BaseController
{
    public function makeTransfert(Request $request)
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
        if (!isset($data['type']) || is_null($data['type'])){
            return $this->sendError("type is not null",['error'=>"type is not null",'code'=>841]);
        }
        if (!isset($data['sender']['phone']) || is_null($data['sender']['phone'])){
            return $this->sendError("sender phone is not null",['error'=>" sender phone is not null",'code'=>841]);
        }
        if (!isset($data['sender']['email']) || is_null($data['sender']['email'])){
            return $this->sendError("sender email is not null",['error'=>" sender email is not null",'code'=>841]);
        }
        if (!isset($data['sender']['country']) || is_null($data['sender']['country'])){
            return $this->sendError("sender country is not null",['error'=>" sender country is not null",'code'=>841]);
        }
        //
        if (!isset($data['sender']['pieceID']) || is_null($data['sender']['pieceID'])){
            return $this->sendError("sender pieceID is not null",['error'=>" sender pieceID is not null",'code'=>841]);
        }
        if (!isset($data['sender']['pieceNumber']) || is_null($data['sender']['pieceNumber'])){
            return $this->sendError("sender pieceNumber is not null",['error'=>" sender pieceNumber is not null",'code'=>841]);
        }
        if (!isset($data['sender']['pieceCreatedAt']) || is_null($data['sender']['pieceCreatedAt'])){
            return $this->sendError("sender pieceCreatedAt is not null",['error'=>" sender pieceCreatedAt is not null",'code'=>841]);
        }
        if (!isset($data['sender']['pieceExpiredAt']) || is_null($data['sender']['pieceExpiredAt'])){
            return $this->sendError("sender pieceExpiredAt is not null",['error'=>" sender pieceExpiredAt is not null",'code'=>841]);
        }
        //
        if (!isset($data['beneficiary']['phone']) || is_null($data['beneficiary']['phone'])){
            return $this->sendError("sender phone is not null",['error'=>" sender phone is not null",'code'=>841]);
        }
        if (!isset($data['beneficiary']['email']) || is_null($data['beneficiary']['email'])){
            return $this->sendError("beneficiary email is not null",['error'=>" beneficiary email is not null",'code'=>841]);
        }
        if (!isset($data['beneficiary']['country']) || is_null($data['beneficiary']['country'])){
            return $this->sendError("beneficiary country is not null",['error'=>" beneficiary country is not null",'code'=>841]);
        }
        //

        $country_sender=Country::query()->firstWhere(['codeiso'=>$data['sender']['country']]);
        if (is_null($country_sender)){
            return $this->sendError("sender country not exist",['error'=>" sender country not exist",'code'=>841]);
        }

        $country_beneficiary=Country::query()->firstWhere(['codeiso'=>$data['beneficiary']['country']]);
        if (is_null($country_beneficiary)){
            return $this->sendError("beneficiary country not exist",['error'=>" beneficiary country not exist",'code'=>841]);
        }
        $operator=Operator::query()->firstWhere(['code'=>$data['operator']]);
        if (is_null($operator)){
            return $this->sendError("sender Operator not exist",['error'=>" sender Operator not exist",'code'=>841]);
        }

        $sender=Sender::query()->firstWhere([
            'email'=>$data['sender']['email'],
            'phone'=>$data['sender']['phone']
        ]);

        if (is_null($sender)){
            $sender=new Sender();
            $sender->email=$data['sender']['email'];
            $sender->phone=$data['sender']['phone'];
            $sender->civility=$data['sender']['civility'];
            $sender->gender=$data['sender']['gender'];
            $sender->name=$data['sender']['name'];
            $sender->country_id=$country_sender->id;
            $sender->user_id=$merchant->user_id;
            $sender->type_piece=$data['sender']['pieceID'];;
            $sender->number_piece=$data['sender']['pieceNumber'];;
            $sender->created_piece_at=$data['sender']['pieceCreatedAt'];
            $sender->expired_piece_at=$data['sender']['pieceExpiredAt'];
            $sender->unique_id=Helpers::generatealeatoireNumeric(60);
            $sender->save();
         }
        $beneficiary=Beneficiary::query()->firstWhere(['email'=>$data['sender']['email'],'phone'=>$data['sender']['phone']]);
        if (is_null($beneficiary)){
            $beneficiary=new Beneficiary();
            $beneficiary->email=$data['beneficiary']['email'];
            $beneficiary->phone=$data['beneficiary']['phone'];
            $beneficiary->civility=$data['beneficiary']['civility'];
            $beneficiary->gender=$data['beneficiary']['gender'];
            $beneficiary->name=$data['beneficiary']['name'];
            $beneficiary->country_id=$country_beneficiary->id;
            $beneficiary->user_id=$merchant->user_id;
            $beneficiary->unique_id=Helpers::generatealeatoireNumeric(60);
            $beneficiary->save();
        }
        $transfert=new Transfert();
        $transfert->amount=$data['amount'];
        $transfert->type="mobile_money";
        $transfert->sender_id=$sender->id;
        $transfert->beneficiary_id=$beneficiary->id;
        $transfert->account_key_id=$merchant->id;
        $transfert->operator_id=$operator->id;
        $transfert->t_ref=Helpers::generatealeatoireNumeric(20);
        $transfert->reference=$data['reference'];
        $transfert->save();
        return $this->sendResponse([
            "message"=>"request send successfully",
            "status"=>Constant::PENDING,
            'reference'=>$transfert->reference,
            't_ref'=>$transfert->t_ref
        ], 'Operation successfully.');
    }
    public function makeTransfertbank(Request $request)
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
        if (!isset($data['relaction']) || is_null($data['relaction'])){
            return $this->sendError("relaction is not null",['error'=>"relaction is not null",'code'=>841]);
        }
        if (!isset($data['sender']['phone']) || is_null($data['sender']['phone'])){
            return $this->sendError("sender phone is not null",['error'=>" sender phone is not null",'code'=>841]);
        }
        if (!isset($data['sender']['email']) || is_null($data['sender']['email'])){
            return $this->sendError("sender email is not null",['error'=>" sender email is not null",'code'=>841]);
        }
        if (!isset($data['sender']['country']) || is_null($data['sender']['country'])){
            return $this->sendError("sender country is not null",['error'=>" sender country is not null",'code'=>841]);
        }
        //
        if (!isset($data['sender']['pieceID']) || is_null($data['sender']['pieceID'])){
            return $this->sendError("sender pieceID is not null",['error'=>" sender pieceID is not null",'code'=>841]);
        }
        if (!isset($data['sender']['pieceNumber']) || is_null($data['sender']['pieceNumber'])){
            return $this->sendError("sender pieceNumber is not null",['error'=>" sender pieceNumber is not null",'code'=>841]);
        }
        if (!isset($data['sender']['pieceCreatedAt']) || is_null($data['sender']['pieceCreatedAt'])){
            return $this->sendError("sender pieceCreatedAt is not null",['error'=>" sender pieceCreatedAt is not null",'code'=>841]);
        }
        if (!isset($data['sender']['pieceExpiredAt']) || is_null($data['sender']['pieceExpiredAt'])){
            return $this->sendError("sender pieceExpiredAt is not null",['error'=>" sender pieceExpiredAt is not null",'code'=>841]);
        }
        //
        if (!isset($data['beneficiary']['phone']) || is_null($data['beneficiary']['phone'])){
            return $this->sendError("sender phone is not null",['error'=>" sender phone is not null",'code'=>841]);
        }
        if (!isset($data['beneficiary']['email']) || is_null($data['beneficiary']['email'])){
            return $this->sendError("beneficiary email is not null",['error'=>" beneficiary email is not null",'code'=>841]);
        }
        if (!isset($data['beneficiary']['country']) || is_null($data['beneficiary']['country'])){
            return $this->sendError("beneficiary country is not null",['error'=>" beneficiary country is not null",'code'=>841]);
        }
        $country_sender=Country::query()->firstWhere(['codeiso'=>$data['sender']['country']]);
        if (is_null($country_sender)){
            return $this->sendError("sender country not exist",['error'=>" sender country not exist",'code'=>841]);
        }

        $country_beneficiary=Country::query()->firstWhere(['codeiso'=>$data['beneficiary']['country']]);
        if (is_null($country_beneficiary)){
            return $this->sendError("beneficiary country not exist",['error'=>" beneficiary country not exist",'code'=>841]);
        }


        $sender=Sender::query()->firstWhere(['email'=>$data['sender']['email'],'phone'=>$data['sender']['phone']]);

        if (is_null($sender)){
            $sender=new Sender();
            $sender->email=$data['sender']['email'];
            $sender->phone=$data['sender']['phone'];
            $sender->civility=$data['sender']['civility'];
            $sender->gender=$data['sender']['gender'];
            $sender->name=$data['sender']['name'];
            $sender->country_id=$country_sender->id;
            $sender->user_id=$merchant->user_id;
            $sender->type_piece=$data['sender']['pieceID'];;
            $sender->number_piece=$data['sender']['pieceNumber'];;
            $sender->created_piece_at=$data['sender']['pieceCreatedAt'];
            $sender->expired_piece_at=$data['sender']['pieceExpiredAt'];
            $sender->unique_id=Helpers::generatealeatoireNumeric(60);
            $sender->save();
        }
        $beneficiary=Beneficiary::query()->firstWhere(['email'=>$data['sender']['email'],'phone'=>$data['sender']['phone']]);
        if (is_null($beneficiary)){
            $beneficiary=new Beneficiary();
            $beneficiary->email=$data['beneficiary']['email'];
            $beneficiary->phone=$data['beneficiary']['phone'];
            $beneficiary->country_id=$country_beneficiary->id;
            $beneficiary->user_id=$merchant->user_id;
            $beneficiary->unique_id=Helpers::generatealeatoireNumeric(60);
            $beneficiary->civility=$data['beneficiary']['civility'];
            $beneficiary->gender=$data['beneficiary']['gender'];
            $beneficiary->name=$data['beneficiary']['name'];
            $beneficiary->bank_name=$data['bankaccount']['bank_name'];
            $beneficiary->account_bank=$data['bankaccount']['account_bank'];
            $beneficiary->swift_code=$data['bankaccount']['swift_code'];;
            $beneficiary->routing_number=$data['bankaccount']['routing_number'];;
            $beneficiary->bank_branch=$data['bankaccount']['bank_branch'];
            $beneficiary->save();
        }
        $transfert=new Transfert();
        $transfert->amount=$data['amount'];
        $transfert->type="bank";
        $transfert->sender_id=$sender->id;
        $transfert->beneficiary_id=$beneficiary->id;
        $transfert->account_key_id=$merchant->id;
        $transfert->t_ref=Helpers::generatealeatoireNumeric(20);
        $transfert->reference=$data['reference'];
        $transfert->reason=$data['reason'];
        $transfert->relaction=$data['relaction'];
        $transfert->save();
        return $this->sendResponse([
            "message"=>"request send successfully",
            "status"=>Constant::PENDING,
            'reference'=>$transfert->reference,
            't_ref'=>$transfert->t_ref
        ], 'Operation successfully.');
    }
}
