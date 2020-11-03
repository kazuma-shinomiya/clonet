<?php
namespace App\Http\Composers;

use Illuminate\View\View;
//Authの使用
use Illuminate\Support\Facades\Auth;
//モデルの利用
use App\Profile;

class HeaderComposer
{
    
    /**
     * データをビューと結合
     *
     * @param  View  $view
     * @return void
     */
    public function compose(View $view)
    {
      $profiles=Profile::where('user_id',Auth::id())->first();
      $view->with('profiles', $profiles);
    }
}