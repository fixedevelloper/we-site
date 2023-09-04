<?php


namespace App\Http\Controllers;


use App\helpers\Helpers;
use App\Models\Beneficiary;
use App\Models\Country;
use App\Models\Currency;
use App\Models\Operator;
use App\Models\Payment;
use App\Models\Sender;
use App\Models\Transfert;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class TransactionController extends Controller
{
    public function transferts(Request $request){
        $query_param = [];
        $search = $request['search'];
        if ($request->has('search')) {
            $key = explode(' ', $request['search']);
            $items = Transfert::where(function ($q) use ($key) {
                foreach ($key as $value) {
                    $q->orWhere('f_name', 'like', "%{$value}%")
                        ->orWhere('l_name', 'like', "%{$value}%")
                        ->orWhere('phone', 'like', "%{$value}%")
                        ->orWhere('email', 'like', "%{$value}%");
                }
            });
            $query_param = ['search' => $request['search']];
        } else {
            $items = Transfert::query()->where(['account_key_id'=>Session::get("current_merchant")]);
        }

        $items = $items->latest()->paginate(Helpers::pagination_limit())->appends($query_param);
        return view('transaction.transfers', compact('items', 'search'));
    }
    public function addtransfert(Request $request){
        $countries=Country::all();
        $senders=Sender::all();
        if ($request->method()== "POST"){
            if ($request->sender_id==0){
                $sender=new Sender();
                $sender->name=$request->name;
                $sender->phone=$request->phone;
                $sender->email=$request->email;
                $sender->civility=$request->civility;
                $sender->gender=$request->gender;
                $sender->country_id=$request->country_id;
                $sender->type_piece=$request->type_piece;
                $sender->number_piece=$request->number_card;
                $sender->created_piece_at=$request->created_card;
                $sender->expired_piece_at=$request->expired_card;
                $sender->user_id=auth()->id();
                $sender->unique_id=Helpers::generatealeatoireNumeric(60);
                $sender->save();
                Session::put("sender",$sender->id);
            }else{
                Session::put("sender",$request->sender_id);
            }
            return redirect()->route("addtransfertstep2");
        }
        return view('transaction/addtransfert',['countries'=>$countries,
            'senders'=>$senders]);
    }
    public function addtransfertstep2(Request $request){
        $countries=Country::all();
        $beneficiares=Beneficiary::all();
        if ($request->method()== "POST"){
            if ($request->beneficiary_id==0){
                $beneficiary=new Beneficiary();
                $beneficiary->name=$request->name;
                $beneficiary->email=$request->email;
                $beneficiary->phone=$request->phone;
                $beneficiary->civility=$request->civility;
                $beneficiary->gender=$request->gender;
                $beneficiary->country_id=$request->country_id;
                $beneficiary->user_id=auth()->id();
                $beneficiary->unique_id=Helpers::generatealeatoireNumeric(60);
                $beneficiary->save();
                Session::put("beneficiary",$beneficiary->id);
            }else{
                Session::put("beneficiary",$request->beneficiary_id);
            }
            return redirect()->route("addtransfertstep3");
        }
        return view('transaction/addtransfert_step2',['countries'=>$countries,'beneficiaries'=>$beneficiares]);
    }
    public function addtransfertstep3(Request $request){
        $countries=Country::all();
        $currencies=Currency::all();
        $beneficiary=Beneficiary::query()->find(Session::get("beneficiary"));
        logger("****************************************");
        logger(Session::get("beneficiary"));
        $operators=Operator::query()->firstWhere(['country_id'=>$beneficiary->country->id])->get();
        if ($request->method()== "POST"){
            $transfert=new Transfert();
            $transfert->amount=$request->amount;
            $transfert->type="mobile_money";
            $transfert->sender_id=Session::get("sender");
            $transfert->beneficiary_id=Session::get("beneficiary");
            $transfert->account_key_id=Session::get("current_merchant");
            $transfert->operator_id=$request->operator_id;
            $transfert->t_ref=Helpers::generatealeatoireNumeric(20);
            $transfert->reference=$request->reference;
            $transfert->currency_id=$request->currency_id;
            $transfert->save();
            return redirect()->route("transferts");
        }
        return view('transaction/addtransfert_step3',['countries'=>$countries,"operators"=>$operators,"currencies"=>$currencies
            ]);
    }

    public function addbanktransfert(Request $request){
        $countries=Country::all();
        $senders=Sender::all();
        if ($request->method()== "POST"){
            if ($request->sender_id==0){
                $sender=new Sender();
                $sender->name=$request->name;
                $sender->phone=$request->phone;
                $sender->email=$request->email;
                $sender->civility=$request->civility;
                $sender->gender=$request->gender;
                $sender->country_id=$request->country_id;
                $sender->type_piece=$request->type_piece;
                $sender->number_piece=$request->number_card;
                $sender->created_piece_at=$request->created_card;
                $sender->expired_piece_at=$request->expired_card;
                $sender->user_id=auth()->id();
                $sender->unique_id=Helpers::generatealeatoireNumeric(60);
                $sender->save();
                Session::put("sender",$sender->id);
            }else{
                Session::put("sender",$request->sender_id);
            }
            return redirect()->route("addbanktransfertstep2");
        }
        return view('transaction.addbanktransfert',['countries'=>$countries,
            'senders'=>$senders]);
    }
    public function addbanktransfertstep2(Request $request){
        $countries=Country::all();
        $beneficiares=Beneficiary::all();
        if ($request->method()== "POST"){
            if ($request->beneficiary_id==0){
                $beneficiary=new Beneficiary();
                $beneficiary->name=$request->name;
                $beneficiary->email=$request->email;
                $beneficiary->phone=$request->phone;
                $beneficiary->civility=$request->civility;
                $beneficiary->gender=$request->gender;
                $beneficiary->country_id=$request->country_id;
                $beneficiary->user_id=auth()->id();
                $beneficiary->unique_id=Helpers::generatealeatoireNumeric(60);
                $beneficiary->bank_name=$request->bank_name;
                $beneficiary->account_bank=$request->bank_account;
                $beneficiary->swift_code=$request->bank_swift_code;
                $beneficiary->bank_branch=$request->bank_branch;
                //$beneficiary->routing_number=$request->routing_number;
                $beneficiary->save();
                Session::put("beneficiary",$beneficiary->id);
            }else{
                Session::put("beneficiary",$request->beneficiary_id);
            }
            return redirect()->route("addbanktransfertstep3");
        }
        return view('transaction.addbanktransfert_step2',['countries'=>$countries,'beneficiaries'=>$beneficiares]);
    }
    public function addbanktransfertstep3(Request $request){
        $countries=Country::all();
        $currencies=Currency::all();
        $beneficiary=Beneficiary::query()->find(Session::get("beneficiary"));
        logger("****************************************");
        logger(Session::get("beneficiary"));
        $operators=Operator::query()->firstWhere(['country_id'=>$beneficiary->country->id])->get();
        if ($request->method()== "POST"){
            $transfert=new Transfert();
            $transfert->amount=$request->amount;
            $transfert->type="bank";
            $transfert->sender_id=Session::get("sender");
            $transfert->beneficiary_id=Session::get("beneficiary");
            $transfert->account_key_id=Session::get("current_merchant");
           // $transfert->operator_id=$request->operator_id;
            $transfert->t_ref=Helpers::generatealeatoireNumeric(20);
            $transfert->reference=$request->reference;
            $transfert->currency_id=$request->currency_id;
            $transfert->relaction=$request->relaction;
            $transfert->save();
            return redirect()->route("transferts");
        }
        return view('transaction.addbanktransfert_step3',['countries'=>$countries,"operators"=>$operators,"currencies"=>$currencies
        ]);
    }
    public function payments(Request $request){
        $query_param = [];
        $search = $request['search'];
        if ($request->has('search')) {
            $key = explode(' ', $request['search']);
            $items = Payment::where(function ($q) use ($key) {
                foreach ($key as $value) {
                    $q->orWhere('f_name', 'like', "%{$value}%")
                        ->orWhere('l_name', 'like', "%{$value}%")
                        ->orWhere('phone', 'like', "%{$value}%")
                        ->orWhere('email', 'like', "%{$value}%");
                }
            });
            $query_param = ['search' => $request['search']];
        } else {
            $items =  Payment::query()->where(['account_key_id'=>Session::get("current_merchant")]);
        }

        $items = $items->latest()->paginate(Helpers::pagination_limit())->appends($query_param);
        return view('transaction.payments', compact('items', 'search'));
    }
    public function paymentlinks(Request $request){
        $query_param = [];
        $search = $request['search'];
        if ($request->has('search')) {
            $key = explode(' ', $request['search']);
            $items = Payment::where(function ($q) use ($key) {
                foreach ($key as $value) {
                    $q->orWhere('f_name', 'like', "%{$value}%")
                        ->orWhere('l_name', 'like', "%{$value}%")
                        ->orWhere('phone', 'like', "%{$value}%")
                        ->orWhere('email', 'like', "%{$value}%");
                }
            });
            $query_param = ['search' => $request['search']];
        } else {
            $items = Payment::query()->where(['account_key_id'=>Session::get("current_merchant")]);
        }

        $items = $items->latest()->paginate(Helpers::pagination_limit())->appends($query_param);
        return view('transaction.paymentlink', compact('items', 'search'));
    }
    public function alltransferts(Request $request){
        $query_param = [];
        $search = $request['search'];
        if ($request->has('search')) {
            $key = explode(' ', $request['search']);
            $items = Transfert::where(function ($q) use ($key) {
                foreach ($key as $value) {
                    $q->orWhere('f_name', 'like', "%{$value}%")
                        ->orWhere('l_name', 'like', "%{$value}%")
                        ->orWhere('phone', 'like', "%{$value}%")
                        ->orWhere('email', 'like', "%{$value}%");
                }
            });
            $query_param = ['search' => $request['search']];
        } else {
            $items = new Transfert();
        }

        $items = $items->latest()->paginate(Helpers::pagination_limit())->appends($query_param);
        return view('transaction.alltransfers', compact('items', 'search'));
    }
}
