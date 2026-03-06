<?php
namespace App\Http\Controllers;

use App\Helpers\ApiHelper;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class SettingsController extends Controller
{

    public function createUrlSlug($urlString)
    {
        $slug = preg_replace('/[^A-Za-z0-9-]+/', '-', $urlString);
        return $slug;
    }
    public function slugify($urlString)
    {
        $search  = ['Ș', 'Ț', 'ş', 'ţ', 'Ş', 'Ţ', 'ș', 'ț', 'î', 'â', 'ă', 'Î', ' ', 'Ă', 'ë', 'Ë'];
        $replace = ['s', 't', 's', 't', 's', 't', 's', 't', 'i', 'a', 'a', 'i', '_', 'a', 'e', 'E'];
        $str     = str_ireplace($search, $replace, strtolower(trim($urlString)));
        $str     = preg_replace('/[^\w\d\-\ ]/', '', $str);
        $str     = str_replace(' ', '_', $str);
        return preg_replace('/[^A-Za-z0-9-]+/', '_', $str);
    }

    public function index(Request $request)
    {
        $user    = $request->user();
        $setting = Setting::where('user_id', $user->id)->first();
        if (! $setting) {
            //create settings for user and return it
            (new RegisteredUserController())->initializeUserSettings($user);
            $setting = Setting::where('user_id', $user->id)->first();
        }
        return ApiHelper::validResponse('Settings retrieved successfully!', [
            'setting' => $setting, 'user' => $user,
        ]);
    }

    public function update(Request $request)
    {
        // $validatedData = $request->validate([
        //     'business_name' => ['required'],
        // ]);
        $setting = Setting::where('user_id', $request->user()->user_account_id)->first();

        $setting->business_name    = $request->business_name;
        $setting->business_address = $request->business_address;
        $setting->business_phone   = $request->business_phone;
        $setting->sms_sender       = substr($request->business_name, 0, 11);

        if ($request->hasFile('business_logo')) {
            $allowedfileExtension = ['jpeg', 'jpg', 'png'];

            $name      = $request->file('business_logo')->getClientOriginalName();
            $extension = $request->business_logo->getClientOriginalExtension();
            $check     = in_array($extension, $allowedfileExtension);
            if ($check) {
                $newfilename = time() . rand(111, 9999) . "." . $extension;
                Storage::disk('logo_images')->put($newfilename, file_get_contents($request->file('business_logo')));

                $setting->business_logo = $newfilename;
            }
        }

        $setting->save();
        return ApiHelper::validResponse('Settings Updated successfully', $setting);
    }

    public function changeLogo(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'business_logo' => 'nullable|image|mimes:jpeg,jpg,png|max:10240',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'msg' => $validator->errors()], 422);
        }

        if ($request->hasFile('business_logo')) {
            $name        = $request->file('business_logo')->getClientOriginalName();
            $extension   = $request->file('business_logo')->getClientOriginalExtension();
            $newfilename = time() . rand(111, 9999) . "." . $extension;
            Storage::disk('logo_images')->put($newfilename, file_get_contents($request->file('business_logo')));
        }

        $setting = Setting::where('user_id', $request->user()->user_account_id)->first();
        if ($request->hasFile('business_logo')) {
            $setting->business_logo = $newfilename;
        }

        $setting->save();

        return ApiHelper::validResponse('Logo Uploaded Successfully', $setting);
    }

    public function deleteAccount(Request $request)
    {
        $user = $request->user();
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        $user->delete();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        return response()->json([
            'status' => 'success',
            'msg'    => 'User deleted successfully',
        ], 200);
    }
}
