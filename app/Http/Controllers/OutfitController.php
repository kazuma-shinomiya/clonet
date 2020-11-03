<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
//バリデーションの利用
use App\Http\Requests\OutfitRequest;
use App\Cloth;
use App\Outfit;
use App\Tag;
use App\Like;
use Illuminate\Support\Facades\Auth;

class OutfitController extends Controller
{
    function __construct(){
        $this->middleware('auth');
    }

    /**
     * コーディネート一覧を取得
     * 
     * @return view
     */
    public function getOutfitShow(Request $request)
    {
        // Clothモデルのデータを取得
        $clothes = $request->user()->clothes;

        //Outfitモデルのデータを取得
        $outfits=$request->user()->outfits;

        //いいねの件数取得
        foreach($outfits as $outfit)
        {
            $like_count=0;
            $like_count=Like::where('outfit_id',$outfit['id'])->count();
            $outfit['like']=$like_count;
        }

        return view('coordination.list',compact('clothes','outfits'));
    }

    /**
     * コーディネートの検索
     * 
     * @return view
     */

    public function getOutfitSearch(Request $request)
    {
        //ログイン者のユーザーidを取得
        $id=Auth::id();
        //フォームから受け取る
        $keyword=$request->tag_search;
        //Outfitモデルのデータを取得
        $outfits=$request->user()->outfits;
        if(!empty($keyword))
        {
            $outfits=Outfit::where('user_id',$id)->where('tag_comment','like',"%$keyword%")->get();
        }

        //いいねの件数取得
        foreach($outfits as $outfit)
        {
            $like_count=0;
            $like_count=Like::where('outfit_id',$outfit['id'])->count();
            $outfit['like']=$like_count;
        }

        // Clothモデルのデータを取得
        $clothes = $request->user()->clothes;
        
       return view('coordination.list',compact('clothes','outfits'));
    }

    /**
     * コーディネートを追加
     * 
     * @return view
     */
    public function exeOutfitAdd(OutfitRequest $request)
    {
        \DB::beginTransaction();
        try{
            //コーディネートの基本情報を保存
            $outfit=new Outfit;
            $outfit->name=$request->name;
            $outfit->user_id=$request->user()->id;
            $outfit->tag_comment=$request->tag_name;
            $outfit->save();
    
            //追加されたコーディネートのidを取得
            $outfit_id=$outfit->id;
            //選択された服のidを取得
            $cloth_id=$request->cloth_id;
            //outfitとclothの紐付け
            $outfit=Outfit::find($outfit_id);
            $outfit->clothes()->attach($cloth_id);

            //タグを追加
            //正規表現で＃以降を判別
            preg_match_all('/#([a-zA-Z0-9０-９ぁ-んァ-ヶー一-龠]+)/u', $request->tag_name, $match);
            //Tagテーブルに無いtagをデータで追加
            foreach($match[1] as $input)
            {
                $tag=Tag::firstOrCreate(['name'=>$input]);
                $tag=null;
                //入力されたタグのidを取得
                $tag_id=Tag::where('name',$input)->get(['id']);
                //タグとoutfitの紐付け
                $outfit=Outfit::find($outfit_id);
                $outfit->tags()->attach($tag_id);
            }

            \DB::commit();
        }catch(\Throwable $e)
        {
            \DB::rollback();
            abort(500);
        }

        return redirect(route('show_outfit'));
    }

    /**
     * コーディネートの編集
     * 
     *
     * @return view 
     */
     public function exeOutfitEdit(OutfitRequest $request)
    {
        $inputs=$request->all();

        \DB::beginTransaction();
        try{
            $outfit=Outfit::find($inputs['id']);
            $outfit->name=$request->name;
            $outfit->tag_comment=$request->tag_name;
            $outfit->save();
    
            //編集されたコーディネートのidを取得
            $outfit_id=$outfit->id;
    
            //選択された服のidを取得
            $cloth_id=$request->cloth_id;
        
            $outfit=Outfit::find($outfit_id);
            $outfit->clothes()->sync($cloth_id);

            //タグを編集
            //現在の紐付けを解除
            $outfit->tags()->detach();
            //正規表現で＃以降を判別
            preg_match_all('/#([a-zA-Z0-9０-９ぁ-んァ-ヶー一-龠]+)/u',$request->tag_name, $match);
            //Tagテーブルに無いtagをデータで追加
            foreach($match[1] as $input)
            {
                $tag=Tag::firstOrCreate(['name'=>$input]);
                $tag=null;
                //入力されたタグのidを取得
                $tag_id=Tag::where('name',$input)->get(['id']);
                //タグとoutfitの紐付け
                $outfit=Outfit::find($outfit_id);
                $outfit->tags()->attach($tag_id);
            }
    
            \DB::commit();
        }catch(\Throwable $e)
        {
            \DB::rollback();
            abort(500);
        }

        return redirect(route('show_outfit'));
    }

    /**
     * コーディネートの削除
     * 
     * @return view
     */
    public function exeOutfitDelete($id)
    {
        //
        $outfit=Outfit::find($id);
        $outfit->clothes()->detach();
        $outfit->tags()->detach();
        
        try{
            Outfit::destroy($id);
        }catch(\Throwable $e)
        {
            abort(500);
        }
        return redirect(route('show_outfit'));
    }
}
