<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
//モデルの利用
use App\Cloth;
//バリデーションの利用
use App\Http\Requests\ClothRequest;
//Authの使用
use Illuminate\Support\Facades\Auth;

class ClothController extends Controller
{
    function __construct(){
        $this->middleware('auth');
    }

    /**
     * 服一覧を表示
     * 
     * @return view
     */
    public function getClothShow(Request $request)
    {
        // Clothモデルのデータを取得
        $clothes = $request->user()->clothes;


        return view('cloth.list',compact('clothes'));
    }

    /**
     * 服の絞り込み
     * 
     * @return view
     */

     public function getClothSearch(Request $request)
     {
        // Clothモデルのデータを取得
        $clothes = $request->user()->clothes;
        //フォームから受け取る
        $keyword=$request->category_search;
        if(!empty($keyword))
        {
            $clothes=$clothes->where('category',$keyword);
        }

        return view('cloth.list',compact('clothes'));
     }

    /**
     * 服を追加する
     * 
     * @return view
     */
    public function exeClothAdd(ClothRequest $request)
    {   
        \DB::beginTransaction();
        try{
            //フォームから送られてきたものを受け取る
            $cloth=new Cloth;
            $cloth->category=$request->category;
            $cloth->name=$request->name;
            $cloth->size=$request->size;
            $cloth->brand=$request->brand;
            $cloth->buy_date=$request->buy_date;
            $cloth->price=$request->price;
            //画像をpublicに保存
            $filename=$request->file('image')->store('public');
            // 保存するファイル名からpublicを除外
            $cloth->image=str_replace('public/','',$filename);
            //ログイン者のuse_idを代入
            $cloth->user_id=$request->user()->id;
            //データに登録
            $cloth->save();
            \DB::commit();
        }catch(\Throwable $e)
        {
            \DB::rollback();
            abort(500);
        }

        return redirect(route('show_cloth'));
    }


    /**
     * 服の編集
     * 
     * @return view
     */
    public function exeClothEdit(ClothRequest $request)
    {
        $inputs=$request->all();
        $filename=$request->file('image')->store('public');
        $inputs['image']=str_replace('public/','',$filename);
        \DB::beginTransaction();
        try{
            $cloth=Cloth::find($inputs['id']);
            $cloth->fill([
                'category'=>$inputs['category'],
                'name'=>$inputs['name'],
                'size'=>$inputs['size'],
                'brand'=>$inputs['brand'],
                'buy_date'=>$inputs['buy_date'],
                'price'=>$inputs['price'],
                'image'=>$inputs['image'],
            ]);
            $cloth->save();
            \DB::commit();
        }catch(\Throwable $e)
        {
            \DB::rollback();
            abort(500);
        }
        return redirect(route('show_cloth'));
    }

    /**
     * 服の削除
     * 
     * @param int $id
     * @return view
     */
    public function exeClothDelete($id)
    {
        try{
            Cloth::destroy($id);
        }catch(\Throwable $e)
        {
            abort(500);
        }
        return redirect(route('show_cloth'));
    }

}
