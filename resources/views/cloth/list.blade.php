@extends('layouts.app')

@section('content')

@php
$category_options =['トップス','ジャケット/アウター','パンツ','スカート','ワンピース','スーツ/ネクタイ','バッグ','シューズ','帽子','アクセサリー'];
$size_options = ['XS','S','M','L','XL','XXL'];
@endphp

@error('name')
  <div class="alert alert-danger">{{ $message }}</div>
@enderror
<h1 class="text-center">Your Closet</h1>
<!-- 検索フォーム -->
<div class="container">
  <div class="row">
    <form method="post" class="form-inline" action="{{route('search_cloth')}}" >
    @csrf
      <div class="form-group">
        <select class="form-control"  name="category_search">
          <option value="">--カテゴリで絞り込む--</option>
          @for ($i=0; $i < count($category_options); $i++)
            <option>{{$category_options[$i]}}</option>
          @endfor
        </select>
      </div>
      <button type="submit" class="btn btn-outline-primary"><i class="fas fa-search"></i></button>
    </form>
  </div>
</div>
<!-- アイテムのカード -->
<div class="container">
  <div class="row ">
    @foreach($clothes as $cloth)
      <div class="col-lg-4">
        <div class="card mt-4">
          <img class="card-img-top" src="{{$cloth->image}}" alt="服のイメージ画像">
          <div class="card-body">
            <a class="card-title" data-toggle="modal" data-target="#detail_clothes{{$cloth->id}}"><h4>{{$cloth->name}}</h4></a>
            <!-- 詳細モーダル画面内容 -->
            <div class="modal fade" id="detail_clothes{{$cloth->id}}" tabindex="-1" role="dialog" aria-labelledby="basicModal" aria-hidden="true">
              <div class="modal-dialog modal-lg">
                <div class="modal-content">
                  <div class="modal-body">
                    <div class="card">
                      <div class="row no-gutters">
                        <div class="col-5">
                          <img class="card-img"  src="{{$cloth->image}}" alt="服のイメージ画像">
                        </div>
                        <div class="col-7">
                          <div class="card-body text-center">
                            <h4 class="card-title">{{$cloth->name}}</h4>
                            <p class="card-text lead">カテゴリー：{{$cloth->category}}</p>
                            <p class="card-text lead">サイズ：{{$cloth->size}}</p>
                            <p class="card-text lead">購入日：{{$cloth->buy_date}}</p>
                            <p class="card-text lead">ブランド：{{$cloth->brand}}</p>
                            <p class="card-text lead">購入金額：{{$cloth->price}}円</p>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">✕</button>
                  </div>
                </div>
              </div>
            </div>
            <!-- ここまで -->
            <p class="card-text">カテゴリー：{{$cloth->category}}</p>
            <p class="card-text">サイズ：{{$cloth->size}}</p>
            <p class="card-text">購入日：{{$cloth->buy_date}}</p>
            <div class="container ">
              <div class="row justify-content-end">
                <div class="col-2 mt-2">
                  <!-- 編集ボタン -->
                  <a data-toggle="modal" data-target="#edit_clothes{{$cloth->id}}">
                    <i class="fas fa-edit fa-2x"></i>
                  </a>
                  <!-- 編集モーダル画面内容 -->
                  <div class="modal fade" id="edit_clothes{{$cloth->id}}" tabindex="-1" role="dialog" aria-labelledby="basicModal" aria-hidden="true">
                    <div class="modal-dialog">
                      <div class="modal-content">
                        <div class="modal-header">
                            <h4>服を編集する</h4>
                        </div>
                        <div class="modal-body">
                          <form method="post" action="{{route('edit_cloth')}}" enctype="multipart/form-data">
                          @csrf
                            <input type="hidden" name="id" value="{{$cloth->id}}">
                            <div class="form-group">
                              <label for="category">カテゴリー：</label>
                              <select class="form-control" id="category" name="category">
                                @php
                                  $selected=$cloth->category;
                                @endphp
                                @for ($i=0; $i < count($category_options); $i++)
                                  @if($category_options[$i]==$selected)
                                    <option selected>{{$category_options[$i]}}</option>
                                  @else
                                    <option>{{$category_options[$i]}}</option>
                                  @endif
                                @endfor
                              </select>
                            </div>
                            <div class="form-group">
                              <label for="name">名前：</label>
                              <input type="text" class="form-control" id="name" name="name" value="{{$cloth->name}}">
                            </div>
                            <div class="form-group">
                              <label for="size">サイズ：</label>
                              <select class="form-control" id="size" name="size">
                                @php
                                  $selected=$cloth->size;
                                @endphp
                                @for ($i=0; $i < count($size_options); $i++)
                                  @if($size_options[$i]==$selected)
                                    <option selected>{{$size_options[$i]}}</option>
                                  @else
                                    <option>{{$size_options[$i]}}</option>
                                  @endif
                                @endfor
                              </select>
                            </div>
                            <div class="form-group">
                              <label for="brand">ブランド：</label>
                              <input type="text" class="form-control" id="brand" name="brand" value="{{$cloth->brand}}">
                            </div>
                            <div class="form-group">
                              <label for="brand">購入日：</label>
                              <input type="date" class="form-control" id="buy_date" name="buy_date" value="{{$cloth->date}}">
                            </div>
                            <div class="form-group">
                              <label for="price">価格：</label>
                              <input type="number" class="form-control" id="price" name="price"  value="{{$cloth->price}}">
                            </div>
                            <div class="form-group">
                              現在の画像：<img src="{{$cloth->image}}" width="20%" height="auto"alt="服のイメージ画像"><br>
                              <label for="image">画像：</label>
                              <input type="file" class="form-control" id="image" name="image">
                            </div>
                            <button type="submit" class="btn btn-outline-primary"><i class="fas fa-edit fa-2x"></i>変更する</button>
                          </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">✕</button>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-2 mt-2">
                  <!-- 削除ボタン -->
                  <a data-toggle="modal" data-target="#delete_clothes{{$cloth->id}}"><i class="fas fa-trash-alt fa-2x"></i></a>
                  <!-- 削除確認モーダル画面 -->
                  <div class="modal fade" id="delete_clothes{{$cloth->id}}" tabindex="-1" role="dialog" aria-labelledby="basicModal" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                      <div class="modal-content">
                        <div class="modal-header">
                          <h4>このアイテムを削除してよろしいですか？</h4>
                          <form method="post" action="{{route('delete_cloth',$cloth->id)}}">
                          @csrf
                            <button type="submit" class="btn btn-outline-danger"><i class="fas fa-trash-alt fa-1x"></i>　削除</button>
                          </form>
                        </div>
                        <div class="modal-body">
                          <div class="card">
                            <div class="row no-gutters">
                              <div class="col-5">
                                <img class="card-img" src="{{ asset('/storage/'.$cloth->image)}}" alt="服のイメージ画像">
                              </div>
                              <div class="col-7">
                                <div class="card-body text-center">
                                  <h4 class="card-title">{{$cloth->name}}</h4>
                                  <p class="card-text lead">カテゴリー：{{$cloth->category}}</p>
                                  <p class="card-text lead">サイズ：{{$cloth->size}}</p>
                                  <p class="card-text lead">購入日：{{$cloth->buy_date}}</p>
                                  <p class="card-text lead">ブランド：{{$cloth->brand}}</p>
                                  <p class="card-text lead">購入金額：{{$cloth->price}}円</p>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                        <div class="modal-footer">
                          <button type="button" class="btn btn-default" data-dismiss="modal">✕</button>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    @endforeach
  </div>
</div>
    
<!-- 追加ボタン-->
<div class="container">
  <div class="text-center mt-4">
    <a data-toggle="modal" data-target="#add_clothes"><i class="fas fa-plus fa-3x"></i></a>
  </div>
</div>
    <!-- 追加モーダル画面 -->
    <div class="modal fade" id="add_clothes" tabindex="-1" role="dialog" aria-labelledby="basicModal" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h4>新しく服を追加する</h4>
          </div>
          <div class="modal-body">
            <form method="post" action="{{route('add_cloth')}}" enctype="multipart/form-data">
            @csrf
              <div class="form-group">
                <label for="category">カテゴリー：</label>
                <select class="form-control" id="category" name="category">
                @for ($i=0; $i < count($category_options); $i++)
                  <option>{{$category_options[$i]}}</option>
                @endfor
                </select>
              </div>
              <div class="form-group">
                <label for="name">名前：</label>
                <input type="text" class="form-control" id="name" name="name">
              </div>
              <div class="form-group">
                <label for="size">サイズ：</label>
                <select class="form-control" id="size" name="size">
                  @for ($i=0; $i < count($size_options); $i++)
                    <option>{{$size_options[$i]}}</option>
                  @endfor
                </select>
              </div>
              <div class="form-group">
                <label for="brand">ブランド：</label>
                <input type="text" class="form-control" id="brand" name="brand">
              </div>
              <div class="form-group">
                <label for="brand">購入日：</label>
                <input type="date" class="form-control" id="buy_date" name="buy_date">
              </div>
              <div class="form-group">
                <label for="price">価格：</label>
                <input type="number" class="form-control" id="price" name="price">
              </div>
              <div class="form-group">
                <label for="image">画像：</label>
                <input type="file" class="form-control" id="image" name="image">
              </div>
              <button type="submit" class="btn btn-primary"><i class="fas fa-plus fa-1x"></i>　追加する</button>
            </form>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">✕</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
          
@endsection


