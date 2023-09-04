<?php


namespace App\Http\Controllers;


use App\helpers\Helpers;
use App\Models\Country;
use App\Models\Currency;
use App\Models\Operator;
use App\Models\Partenaire;
use App\Models\User;
use App\Models\Zone;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function currencies(Request $request){
        $query_param = [];
        $search = $request['search'];
        if ($request->method() == "POST"){
            $currency=Currency::query()->firstWhere(['code'=>$request->code]);
            if (is_null($currency)){
                $currency=new Currency();
                $currency->code=$request->code;
            }
            $currency->name=$request->name;
            $currency->value=$request->value;
            $currency->save();
        }
        if ($request->has('search')) {
            $key = explode(' ', $request['search']);
            $items = Currency::where(function ($q) use ($key) {
                foreach ($key as $value) {
                    $q->orWhere('f_name', 'like', "%{$value}%")
                        ->orWhere('l_name', 'like', "%{$value}%")
                        ->orWhere('phone', 'like', "%{$value}%")
                        ->orWhere('email', 'like', "%{$value}%");
                }
            });
            $query_param = ['search' => $request['search']];
        } else {
            $items = new Currency();
        }

        $items = $items->latest()->paginate(Helpers::pagination_limit())->appends($query_param);
        return view('settings.currencies', compact('items', 'search'));

    }
    public function zones(Request $request){
        $query_param = [];
        $search = $request['search'];
        if ($request->method() == "POST"){
            $zone=Zone::query()->firstWhere(['code'=>$request->code]);
            if (is_null($zone)){
                $zone=new Zone();
                $zone->code=$request->code;
            }
            $zone->name=$request->name;
            $zone->save();
        }
        if ($request->has('search')) {
            $key = explode(' ', $request['search']);
            $items = Zone::where(function ($q) use ($key) {
                foreach ($key as $value) {
                    $q->orWhere('f_name', 'like', "%{$value}%")
                        ->orWhere('l_name', 'like', "%{$value}%")
                        ->orWhere('phone', 'like', "%{$value}%")
                        ->orWhere('email', 'like', "%{$value}%");
                }
            });
            $query_param = ['search' => $request['search']];
        } else {
            $items = new Zone();
        }

        $items = $items->latest()->paginate(Helpers::pagination_limit())->appends($query_param);
        return view('settings.zones', compact('items', 'search'));

    }
    public function countries(Request $request){
        $query_param = [];
        $search = $request['search'];
        if ($request->method() == "POST"){
            $country=Country::query()->firstWhere(['codeiso'=>$request->codeiso]);
            if (is_null($country)){
                $country=new Country();
                $country->codeiso=$request->codeiso;
            }
            $country->name=$request->name;
            $country->codephone=$request->codephone;
            $country->currency=$request->currency;
            $country->zone_id=$request->zone_id;
            $country->save();
        }
        if ($request->has('search')) {
            $key = explode(' ', $request['search']);
            $items = Country::where(function ($q) use ($key) {
                foreach ($key as $value) {
                    $q->orWhere('f_name', 'like', "%{$value}%")
                        ->orWhere('l_name', 'like', "%{$value}%")
                        ->orWhere('phone', 'like', "%{$value}%")
                        ->orWhere('email', 'like', "%{$value}%");
                }
            });
            $query_param = ['search' => $request['search']];
        } else {
            $items = new Country();
        }
        $zones=Zone::all();
        $items = $items->latest()->paginate(Helpers::pagination_limit())->appends($query_param);
        return view('settings.countries', compact('items', 'search','zones'));

    }
    public function operators(Request $request){
        $query_param = [];
        $search = $request['search'];
        if ($request->method() == "POST"){
            $operator=Operator::query()->firstWhere(['code'=>$request->code]);
            if (is_null($operator)){
                $operator=new Operator();
                $operator->code=$request->code;
                $operator->country_id=$request->country_id;
            }
            $operator->name=$request->name;
            $operator->save();
        }
        if ($request->has('search')) {
            $key = explode(' ', $request['search']);
            $items = Operator::where(function ($q) use ($key) {
                foreach ($key as $value) {
                    $q->orWhere('f_name', 'like', "%{$value}%")
                        ->orWhere('l_name', 'like', "%{$value}%")
                        ->orWhere('phone', 'like', "%{$value}%")
                        ->orWhere('email', 'like', "%{$value}%");
                }
            });
            $query_param = ['search' => $request['search']];
        } else {
            $items = new Operator();
        }
        $countries=Country::all();
        $items = $items->latest()->paginate(Helpers::pagination_limit())->appends($query_param);
        return view('settings.operators', compact('items', 'search','countries'));

    }
    public function customers(Request $request){
        $query_param = [];
        $search = $request['search'];
        if ($request->method() == "POST"){
            $operator=Operator::query()->firstWhere(['code'=>$request->code]);
            if (is_null($operator)){
                $operator=new Operator();
                $operator->code=$request->code;
                $operator->country_id=$request->country_id;
            }
            $operator->name=$request->name;
            $operator->save();
        }
        if ($request->has('search')) {
            $key = explode(' ', $request['search']);
            $items = User::where(function ($q) use ($key) {
                foreach ($key as $value) {
                    $q->orWhere('name', 'like', "%{$value}%")
                        ->orWhere('l_name', 'like', "%{$value}%")
                        ->orWhere('phone', 'like', "%{$value}%")
                        ->orWhere('email', 'like', "%{$value}%");
                }
            });
            $query_param = ['search' => $request['search']];
        } else {
            $items = new User();
        }
        $countries=Country::all();
        $items = $items->customer()->latest()->paginate(Helpers::pagination_limit())->appends($query_param);
        return view('settings.customers', compact('items', 'search','countries'));

    }
    public function partenaires(Request $request){
        $query_param = [];
        $search = $request['search'];
        if ($request->has('search')) {
        }
        $items = new Partenaire();
        $items = $items->latest()->paginate(Helpers::pagination_limit())->appends($query_param);
        return view('settings.partenaires', compact('items'));

    }
}
