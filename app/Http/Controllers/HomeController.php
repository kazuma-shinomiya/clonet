<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Outfit;
use App\Profile;
use App\Cloth;
use App\Tag;
use App\Like;
use App\User;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    // /**
    //  * Create a new controller instance.
    //  *
    //  * @return void
    //  */
    // public function __construct()
    // {
    //     $this->middleware('auth');
    // }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function getOther_outfitShow()
    {
        if (Auth::check()) 
        {
            //コーディネートの取得
            $id = Auth::id();
            $outfits=Outfit::where('user_id','!=',$id)->get();

            //いいねの取得
            $likes=array();
            $likes[0]='dummy';
            foreach($outfits as $outfit)
            {
                $like=Like::where('user_id',$id)->where('outfit_id',$outfit['id'])->first();
                if(!empty($like))
                {
                    $like=1;
                }else
                {
                    $like=0;
                }
                array_push($likes,$like);
            }
        }else
        {
            $outfits=Outfit::inRandomOrder()->get();
            $likes=null;
        }

    
        foreach($outfits as $outfit)
        {
            //プロフィールの取得
            $profiles=Profile::where('user_id','=',$outfit['user_id'])->first();
            //ユーザーの名前を取得
            $users=User::where('id','=',$outfit['user_id'])->first();
        }

        


        if(empty($profiles))
        {
            $msg="他の人の投稿はまだありません";
            return view('home',compact('msg'));
        }else
        {
            return view('home',compact('outfits','profiles','users','likes'));
        }
    }

    /**
     * コーディネートの検索(他人)
     * 
     * @return view
     */

    public function getOther_outfitSearch(Request $request)
    {
        //ログイン者のユーザーidを取得
        $id=Auth::id();
        //ログイン者以外のコーディネートの取得
        $outfits=Outfit::where('user_id','!=',$id)->get();

        //フォームから受け取る
        $keyword=$request->tag_search;
        if(!empty($keyword))
        {
            $outfits=Outfit::where('user_id','!=',$id)->where('tag_comment','like',"%$keyword%")->get();
        }

        //いいねの取得
        $likes=array();
        $likes[0]='dummy';
        foreach($outfits as $outfit)
        {
            $like=Like::where('user_id',$id)->where('outfit_id',$outfit['id'])->first();
            if(!empty($like))
            {
                $like=1;
            }else
            {
                $like=0;
            }
            array_push($likes,$like);
        }

        //プロフィール情報をわたす
        foreach($outfits as $outfit)
        {
            $profiles=Profile::where('user_id','=',$outfit['user_id'])->first();
            //ユーザーの名前を取得
            $users=User::where('id','=',$outfit['user_id'])->first();
        }

        // Clothモデルのデータを取得
        $clothes = $request->user()->clothes;
        
        return view('home',compact('clothes','outfits','profiles','users','likes'));
    }

    /**
     * いいねをつける
     * @param $id outfitのid
     * @return view
     */
    public function exeOther_outfitLike($id)
    {
        $like=new Like;
        $like->user_id=Auth::id();
        $like->outfit_id=$id;
        $like->save();

        return redirect(route('home'));
    }
    /**
     * いいねを消す
     * @param $id outfitのid
     * @return view
     */
    public function exeOther_outfitNolike($id)
    {
        $user_id=Auth::id();
        $like=Like::where('user_id',$user_id)->where('outfit_id',$id)->first();
        $like->delete();

        return redirect(route('home'));
    }


}
