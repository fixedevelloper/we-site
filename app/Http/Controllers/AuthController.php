<?php


namespace App\Http\Controllers;


use App\helpers\Helpers;
use App\Models\AccountKey;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest:user', ['except' => ['logout','profil']]);
    }

    public function login(){
        return view('login');
    }
    public function register(){
        return view('register');
    }
    public function submit_register(Request $request){
        $request->validate([
            'email' => 'required|min:5|max:50',
            'password' => 'required|min:8',
        ]);
        $agent = User::where(['email' => $request->email])->first();
        if (isset($agent)){
            return redirect()->back()->withErrors(['This email number is already taken']);
        }

        DB::transaction(function () use ($request) {
            $user = new User();
            $user->name = $request->name;
           // $user->image = Helpers::upload('agent/', 'png', $request->file('image'));
            $user->email = $request->email;

            $user->phone = $request->phone;
            $user->password = bcrypt($request->password);
            $user->user_type=1;
            $user->api_key = "wt-";
            $user->api_secret ="";
                $user->save();
            $user->find($user->id);
            $user->api_key = "wt-".$user->id .Helpers::generatealeatoire(40);
            $user->api_secret = Helpers::generatealeatoire(50);
            $user->save();
            $merchant=new AccountKey();
            $merchant->name="Default merchant";
            $merchant->user_id=$user->id;
            $merchant->save();
            $merchant->find($merchant->id);
            $merchant->merchant_key=$merchant->id .Helpers::generatealeatoire(40);
            $merchant->save();
            Session::put("current_merchant",$merchant->id);
        });
        if (auth('user')->attempt(['email' => $request->email, 'password' => $request->password], $request->remember)) {

            return redirect()->route('dashboard');
        }else{
            return  back();
        }
}
    public function submit(Request $request)
    {
        $request->validate([
            'email' => 'required|min:5|max:50',
            'password' => 'required|min:8',
        ]);

        if (auth('user')->attempt(['email' => $request->email, 'password' => $request->password], $request->remember)) {
            $merchant=AccountKey::query()->firstWhere(['user_id'=>auth()->id()]);
           // session("current_merchant",$merchant->id);
            Session::put("current_merchant",$merchant->id);
            return redirect()->route('dashboard');
        }

        return redirect()->back()->withInput($request->only('email', 'remember'))
            ->withErrors(['Credentials does not match.']);
    }

    public function logout(Request $request)
    {
        auth()->guard('user')->logout();
        return redirect()->route('login');
    }
    public function profil(Request $request){
        $user = Auth::user();
        if ($request->method() == "POST") {
            $user->update([
                "name" => $request->get('name'),
                "phone" => $request->get('phone'),
                "adresse" => $request->get('adress'),
                "email" => $request->get('email'),
            ]);
            return redirect()->route('profil');
        }
        return view('account.profil',['user'=>$user]);
    }
    public function changeimage(Request $request)
    {
        $user = Auth::user();   logger("test recept------------------");
        if ($request->method() == "POST") {
            logger("test recept------------------");
            $this->validate($request, [
                'photo' => 'required|image|mimes:jpg,png,jpeg'
            ]);
            $newFilename = uniqid() . '.' . $request->photo->extension();
            $path = $request->file('photo')->storeAs(
                'public/uploads', $newFilename
            );
            $user->image = $newFilename;
            $user->save();
            session()->flash('success', 'Image upload');
            return redirect()->route('profil');
        }
        return view('account.profil', ['user' => $user]);
    }

    public function changepassword(Request $request)
    {
        $user = Auth::user();
        if ($request->method() == "POST") {
            $status= $user->update([
                'password' => bcrypt($request->get('password')),
            ]);
            $data_ = array('name' => $user->name, 'password' => $request->get('password'));

         /*   Mail::send(['text'=>'mail.ressetpassword'], $data_, function($message) use ($user) {
                $message->to($user->email, $user->name)->subject
                ('Changement de mot de passe');
                $message->from(env('MAIL_FROM_ADDRESS'),env('MAIL_FROM_NAME'));
            });*/
            return $status
                ? redirect()->route('profil')->with('status', __($status))
                : back()->withInput($request->only('email'))
                    ->withErrors(['email' => __($status)]);
            //return redirect('profil');

        }
        return view('account.profil', ['user' => $user]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function reset_password(Request $request)
    {
        if ($request->method() == "POST") {
            $user = User::query()->where('email', '=', $request->get('email'))->first();
            if (is_null($user)) {
                return redirect()->route('reset_password', [])->withErrors(__('Utilisateur inexistant', ['name' => __('users.store')]));
            }
            $password = uniqid();

            $data = array('name' => $user->name, 'password' => $password);

           /* Mail::send(['text' => 'mail.ressetpassword'], $data, function ($message) use ($user) {
                $message->to($user->email, $user->name)->subject
                ('Mot de passe recuperé');
                $message->from(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'));
            });*/
            $user->update([
                'password' => bcrypt($password)
            ]);
            return redirect()->route('reset_password', [])->withSuccess(__('Mot de passe', ['name' => __('users.store')]));
        }
        return view('auth.reset_password');
    }
}
