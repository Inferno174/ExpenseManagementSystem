<?php

use App\Models\ExpenseCategory;
use App\Models\UserDetails;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
// Get Host URL
if (!function_exists('getHost')) {

    function getHost()
    {
        return env('APP_URL', "");
    }
}


// Custom URL
if (!function_exists('admin_url')) {

    function admin_url($value = "")
    {
        return config('constants.ADMIN_URL') . $value;
    }
}

// Get UserName
if (!function_exists('getUsername')) {

    function getUsername($userid)
    {

        $user = DB::table('users')->select('name')->where('id', $userid)->where('trash', 'NO')->first();

        if ($user == null) {
            return '';
        } else {
            return $user->name;
        }
    }
}

// Get User Email
if (!function_exists('getUseremail')) {

    function getUseremail($userid)
    {

        $user = DB::table('users')->select('email')->where('id', $userid)->where('trash', 'NO')->first();

        if ($user == null) {
            return '';
        } else {
            return $user->email;
        }
    }
}

// Encryption and Decryption
if (!function_exists('encryptId')) {

    function encryptId($value)
    {

        $action = 'encrypt';
        $string = $value;
        $output = false;
        $encrypt_method = "AES-256-CBC";

        $secret_key = 'P(0p!e@e$k';
        $secret_iv = 'Peop!eDe$k';

        // hash
        $key = hash('sha256', $secret_key);

        $iv = substr(hash('sha256', $secret_iv), 0, 16);

        if ($action == 'encrypt') {
            $output = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
            $output = base64_encode($output);
        } else if ($action == 'decrypt') {

            $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
        }

        return $output;
    }
}

if (!function_exists('decryptId')) {

    function decryptId($encrypted)
    {

        $action = 'decrypt';
        $string = $encrypted;
        $output = false;
        $encrypt_method = "AES-256-CBC";

        $secret_key = 'P(0p!e@e$k';
        $secret_iv = 'Peop!eDe$k';

        // hash
        $key = hash('sha256', $secret_key);

        $iv = substr(hash('sha256', $secret_iv), 0, 16);

        if ($action == 'encrypt') {
            $output = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
            $output = base64_encode($output);
        } else if ($action == 'decrypt') {

            $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
        }

        return $output;
    }

    // Get Remaining Salary Based On Auth
    if(!function_exists('GetDetails'))
    {
        function GetDetails()
        {
            $data = UserDetails::where('user_id',Auth::id())->first();
            return $data;
        }
    }

    // Get Category Name
    if(!function_exists('GetCategoryName'))
    {
        function GetCategoryName($id)
        {
            $data = ExpenseCategory::where('id',$id)->first();
            return $data->category_name; 
        }
    }
}
