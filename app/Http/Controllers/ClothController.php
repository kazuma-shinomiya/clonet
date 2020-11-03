<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
//モデルの利用
use App\Cloth;
//バリデーションの利用
use App\Http\Requests\ClothRequest;
//Authの使用
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

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
            //画像の受け取り
            $uploadImg=$request->file('image');
             // S3に画像を保存しパスを受取る    
            $path=Storage::disk('s3')->putFile('clothes',$uploadImg,'public');
            $cloth->image = Storage::disk('s3')->url($path);
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
        
        \DB::beginTransaction();
        try{
            //入力された全データを取得
            $inputs=$request->all();
    
            //画像の処理
            $uploadImg=$request->file('image');
            // S3に画像を保存しパスを受取る   
            $path=Storage::disk('s3')->putFile('clothes',$uploadImg,'public');
            $inputs['image']= Storage::disk('s3')->url($path);

            //update処理
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
