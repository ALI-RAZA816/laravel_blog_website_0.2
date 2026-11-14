<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateSettingRequest;
use App\Models\Setting;
use App\Support\ImageUpload;

class SettingController extends Controller
{
    public function fetchSetting()
    {
        $setting = Setting::first();
        if($setting){
            return response()->json([
                'setting'=>$setting
            ],200);
        }
        return response()->json([
            'setting'=>$setting
        ],200);
    }


    public function updateSetting(UpdateSettingRequest $request)
    {
        $setting = Setting::first();
        $imageName = $setting?->site_logo;
        if($request->hasFile('site_logo')){
            ImageUpload::delete($setting?->site_logo, 'posts-images');
            $imageName = ImageUpload::save($request->file('site_logo'), 'posts-images');
        }else{
            $imageName = $setting?->site_logo;
        }

        Setting::updateOrCreate([
            'id'=>1
        ],[
            'site_title'=>$request->site_title,
            'site_description'=>$request->site_desc,
            'site_copyright'=>$request->site_copyright,
            'site_logo'=>$imageName ?? null,
            'f_url'=>$request->f_url ?? null,
            't_url'=>$request->t_url ?? null,
            'i_url'=>$request->i_url ?? null,
            'l_url'=>$request->l_url ?? null,
            'site_maintence'=>$request->maintence ?? false,
        ]);

        return response()->json([
            'message'=>'Setting successfully saved'
        ],200);
    }

    public function removeLogo()
    {
        $setting = Setting::first();
        if(!$setting){
            return response()->json([
                'message'=>'Not found'
            ],404);
        }

        $path = public_path('/posts-images/');
        if($setting->site_logo){
            $old_image = $path. $setting->site_logo;
            if(file_exists($old_image)){
                ImageUpload::delete($setting->site_logo, 'posts-images');
            }
        }else{
            return response()->json([
                'message'=>'No logo to deleted'
            ],200);
        }

        $setting->update([
            'site_logo'=>null
        ]);
        return response()->json([
            'message'=>'Logo successfully deleted'
        ],200);
    }
}
