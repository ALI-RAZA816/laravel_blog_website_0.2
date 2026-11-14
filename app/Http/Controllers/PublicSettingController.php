<?php

namespace App\Http\Controllers;

use App\Models\Setting;


class PublicSettingController extends Controller
{
    public function publicSetting()
    {
        $setting = Setting::select('site_title','site_description','site_copyright','site_logo','f_url','t_url','i_url','l_url')->first();
        return response()->json(['setting' => $setting], 200);
    }
}
