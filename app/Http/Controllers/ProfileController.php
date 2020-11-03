<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
//モデルの利用
use App\Profile;
//Authの使用
use Illuminate\Support\Facades\Auth;

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
    public function exeProfileAdd(Request $request)
    {
        \DB::beginTransaction();
        try{
            //フォームから送られてきたものを受け取る
            $profile=new Profile;
            $profile->gender=$request->gender;
            $profile->height=$request->height;
            $profile->comment=$request->comment;
            //画像をpublicに保存
            $filename=$request->file('image')->store('public');
            // 保存するファイル名からpublicを除外
            $profile->image=str_replace('public/','',$filename);
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
    public function exeProfileEdit(Request $request)
    {
        $inputs=$request->all();
        $filename=$request->file('image')->store('public');
        $inputs['image']=str_replace('public/','',$filename);
        \DB::beginTransaction();
        try{
            $profile=Profile::find($inputs['id']);
            $profile->fill([
                'gender'=>$inputs['gender'],
                'height'=>$inputs['height'],
                'comment'=>$inputs['comment'],
                'image'=>$inputs['image'],
            ]);
            $profile->save();
            \DB::commit();
        }catch(\Throwable $e)
        {
            \DB::rollback();
            abort(500);
        }
        return redirect(route('show_profile'));
    }
}
