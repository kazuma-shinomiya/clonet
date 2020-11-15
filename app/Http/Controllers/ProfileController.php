<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
//モデルの利用
use App\Profile;
//Authの使用
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

use App\Http\Requests\ProfileRequest;

class ProfileController extends Controller
{
    function __construct(){
        $this->middleware('auth');
    }
    /**
     * プロフィールの表示
     * 
     * @return view
     */
    public function getProfileShow(Request $request)
    {
        //Profileモデルのデータを取得
        $profiles=$request->user()->profiles;

        return view('profile.index',compact('profiles'));
        
    }

    /**
     * プロフィールの追加
     * 
     * @return view
     */
    public function exeProfileAdd(ProfileRequest $request)
    {
        \DB::beginTransaction();
        try{
            //フォームから送られてきたものを受け取る
            $profile=new Profile;
            $profile->gender=$request->gender;
            $profile->height=$request->height;
            $profile->comment=$request->comment;
            //画像の受け取り
            $uploadImg=$request->file('image');
            // S3に画像を保存しパスを受取る    
            $path=Storage::disk('s3')->putFile('plofiles',$uploadImg,'public');
            $profile->image = Storage::disk('s3')->url($path);
            //ログイン者のuse_idを代入
            $profile->user_id=$request->user()->id;
            //データに登録
            $profile->save();
            \DB::commit();
        }catch(\Throwable $e)
        {
            \DB::rollback();
            abort(500);
        }
        
        return redirect(route('show_profile'));

    }

    /**
     * プロフィールの編集
     * 
     * @return view
     */
    public function exeProfileEdit(ProfileRequest $request)
    {
        // \DB::beginTransaction();
        // try{
            $inputs=$request->all();

            //画像の処理
            $uploadImg=$request->file('image');
            // S3に画像を保存しパスを受取る   
            $path=Storage::disk('s3')->putFile('profiles',$uploadImg,'public');
            $inputs['image']= Storage::disk('s3')->url($path);

            $profile=Profile::find($inputs['id']);
            $profile->fill([
                'gender'=>$inputs['gender'],
                'height'=>$inputs['height'],
                'comment'=>$inputs['comment'],
                'image'=>$inputs['image'],
            ]);
            $profile->save();
            \DB::commit();
        // }catch(\Throwable $e)
        // {
        //     \DB::rollback();
        //     abort(500);
        // }
        return redirect(route('show_profile'));
    }
}
