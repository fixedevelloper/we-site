<?php


namespace App\Http\Controllers;


use App\helpers\Helpers;
use App\Models\AccountKey;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class HomeController extends Controller
{

    public function home()
    {
        return view('home');
    }
    public function documentation()
    {
        return view('documentation');
    }
    public function contact()
    {
        return view('contact');
    }
    public function about()
    {
        return view('about');
    }
    public function service_collete()
    {
        return view('service_collete');
    }
    public function service_transfert()
    {
        return view('service_transfert');
    }
    public function service_sms()
    {
        return view('service_sms');
    }
    public function balances()
    {
        return view('balances');
    }

    public function merchants(Request $request)
    {
        $query_param = [];
        $search = $request['search'];
        if ($request->has('search')) {
            $key = explode(' ', $request['search']);
            $agents = AccountKey::where(function ($q) use ($key) {
                foreach ($key as $value) {
                    $q->orWhere('f_name', 'like', "%{$value}%")
                        ->orWhere('l_name', 'like', "%{$value}%")
                        ->orWhere('phone', 'like', "%{$value}%")
                        ->orWhere('email', 'like', "%{$value}%");
                }
            });
            $query_param = ['search' => $request['search']];
        } else {
            $agents = AccountKey::query()->where('user_id', '=', auth()->id());
        }

        $agents = $agents->latest()->paginate(Helpers::pagination_limit())->appends($query_param);
        return view('settings.merchants', compact('agents', 'search'));
    }

    public function addmerchant(Request $request)
    {
        if ($request->method() == "POST") {
            $merchant = new AccountKey();
            $merchant->name = $request->name;
            $merchant->url = $request->url;
            $merchant->callback_url = $request->callback_url;
            $merchant->user_id = auth()->user()->id;
            $merchant->merchant_key = $request->key;
            $merchant->save();
            return redirect()->route('merchants');
        }
        return view('settings.addmarchant');
    }

    public function changeMerchant($id)
    {
        Session::put("current_merchant", $id);
        return back();
    }
}
