<?php


namespace App\helpers;


use App\Models\BussinesService;
use Illuminate\Support\Facades\Storage;

class Helpers
{
    public static function getService($key){
        $buss=BussinesService::query()->firstWhere(["code_name"=>$key]);
        return $buss;
    }
    public static function set_symbol($amount)
    {
        $position = Helpers::get_business_settings('currency_symbol_position');
        if (!is_null($position) && $position == 'left') {
            $string = self::currency_symbol() . '' . number_format($amount, 2);
        } else {
            $string = number_format($amount, 2) . '' . self::currency_symbol();
        }
        return $string;
    }
    public static function generatealeatoire($size){
        $allowed_characters = [1, 2, 3, 4, 5, 6, 7, 8, 9, 0,"a","z","e","r","t","y","u","i","o"
            ,"p","q","s","d","f","g","h","j","k","l","m","w","x","c","v","b","n"];
        $all="";
        for ($i = 1; $i <= intval($size); ++$i) {
            $all .= $allowed_characters[rand(0, count($allowed_characters) - 1)];
        }
        return $all;
    }
    public static function generatealeatoireNumeric($size){
        $allowed_characters = [1, 2, 3, 4, 5, 6, 7, 8, 9, 0];
        $all="";
        for ($i = 1; $i <= intval($size); ++$i) {
            $all .= $allowed_characters[rand(0, count($allowed_characters) - 1)];
        }
        return $all;
    }
    public static function remove_invalid_charcaters($str)
    {
        return str_ireplace(['\'', '"', ',', ';', '<', '>', '?'], ' ', $str);
    }

    public static function pagination_limit()
    {
        // $limit = self::get_business_settings('pagination_limit');
        $limit = 10;
        return isset($limit) && $limit > 0 ? $limit : 25;
    }

    public static function delete($full_path)
    {
        if (Storage::disk('public')->exists($full_path)) {
            Storage::disk('public')->delete($full_path);
        }
        return [
            'success' => 1,
            'message' => 'Removed successfully !'
        ];
    }

    public static function pin_check($user_id, $pin)
    {
        $user = User::find($user_id);
        if (Hash::check($pin, $user->password)) {
            return true;
        }else{
            return false;
        }
    }

    public static function get_qrcode($data)
    {
        $qrcode = QrCode::size(70)->generate(json_encode([
            'name' => $data['name'],
            'phone' => $data['phone'],
            'type' => $data['type'] != 0 ? ($data['type'] == 1 ? 'agent' : 'customer') : null,
            'image' => $data['image'] ?? ''
        ]));
        return $qrcode;
    }

    public static function get_qrcode_by_phone($phone)
    {
        $user = User::where('phone', $phone)->first();
        $qrcode = QrCode::size(70)->generate(json_encode([
            'name' => $user['f_name'] . ' ' . $user['l_name'],
            'phone' => $user['phone'],
            'type' => $user['type'] != 0 ? ($user['type'] == 1 ? 'agent' : 'customer') : null,
            'image' => $user['image'] ?? ''
        ]));
        return $qrcode;

    }

    public static function filter_phone($phone) {
        $phone = str_replace([' ', '-'], '', $phone);
        return $phone;
    }
}
