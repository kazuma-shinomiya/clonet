@extends('layouts.app')

@section('content')
@error('name')
  <div class="alert alert-danger">{{ $message }}</div>
@enderror
<h1 class="text-center">Your Coordination</h1>
<!-- 検索フォーム -->
<div class="container">
  <div class="row">
    <form method="post" class="form-inline" action="{{route('search_outfit')}}" >
    @csrf
      <div class="form-group">
        <input type="text" class="form-control" name="tag_search" placeholder="--タグで検索--">
      </div>
      <button type="submit" class="btn btn-outline-primary"><i class="fas fa-search"></i></button>
    </form>
  </div>
</div>
<div class="container">
  <div class="row">
    @foreach($outfits as $outfit)
      <div class="col-lg-6">
        <div class="card m-3">
          <div class="card-header">
            <h3 class="d-inline">{{$outfit->name}}</h3>
            <!-- 削除ボタン -->
            <a class="float-right ml-2" data-toggle="modal" data-target="#delete_outfits{{$outfit->id}}">
              <i class="fas fa-trash-alt fa-2x"></i>
            </a>
            <!-- 削除確認モーダル画面 -->
            <div class="modal fade" id="delete_outfits{{$outfit->id}}" tabindex="-1" role="dialog" aria-labelledby="basicModal" aria-hidden="true">
              <div class="modal-dialog modal-lg">
                <div class="modal-content">
                  <div class="modal-body">
                    <h4 class="text-center">このコーデを削除してよろしいですか？</h4>
                    <form class="text-center" method="post" action="{{route('delete_outfit',$outfit->id)}}">
                    @csrf
                      <button type="submit" class="btn btn-outline-danger mt-4 btn-lg"><i class="fas fa-trash-alt fa-1x"></i>　削除</button>
                    </form>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">✕</button>
                  </div>
                </div>
              </div>
            </div>
            <!-- 編集ボタン -->
            <a class="float-right" data-toggle="modal" data-target="#edit_outfits{{$outfit->id}}">
              <i class="fas fa-edit fa-2x"></i>
            </a>
            <!-- 編集モーダル画面内容 -->
            <div class="modal fade" id="edit_outfits{{$outfit->id}}" tabindex="-1" role="dialog" aria-labelledby="basicModal" aria-hidden="true">
              <div class="modal-dialog modal-xl">
                <div class="modal-content">
                  <div class="modal-header">
                      <h4>コーデを編集する</h4>
                  </div>
                  <div class="modal-body">
                    <form method="post" action="{{route('edit_outfit')}}" enctype="multipart/form-data">
                    @csrf
                      <input type="hidden" name="id" value="{{$outfit->id}}">
                      <table class="table table-striped">
                        @foreach($clothes as $cloth)
                          <tbody>
                            <tr>
                              <th>
                                <div class="form-check inline">
                                  <input class="form-check-input position-static" type="checkbox"  name="cloth_id[]" value="{{$cloth->id}}" >
                                </div>
                              </th>
                              <td><img src="{{$cloth->image}}" alt="服のイメージ画像" width="90" height="auto"></td>
                              <td>{{$cloth->name}}</td>
                              <td>{{$cloth->category}}</td>
                              <td>{{$cloth->size}}</td>
                              <td>{{$cloth->buy_date}}</td>
                            </tr>
                          </tbody>
                        @endforeach
                      </table>
                      <div class="form-group">
                        <label for="name">名前:</label>
                        <input type="text" id="name" name="name" value="{{$outfit->name}}" class="form-control">
                      </div>
                      @error('name')
                        <div class="alert alert-danger">{{ $message }}</div>
                      @enderror
                      <div class="form-group">
                        <label for="tag_name">タグ:</label>
                        <input type="text" id="tag_name" name="tag_name" class="form-control" placeholder="#○○のようにタグを追加しよう" value="{{$outfit->tag_comment}}">
                      </div>
                      <button type="submit" class="btn btn-primary"><i class="fas fa-edit fa-1x"></i>編集する</button>
                    </form>
                  </div>
                  <div class="modal-footer">
                      <button type="button" class="btn btn-default" data-dismiss="modal">✕</button>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="card-body">
            <div>
              @foreach($outfit->clothes as $outfit_cloth)
                <a data-toggle="modal" data-target="#detail_outfits{{$outfit_cloth->id}}" >
                  <img class="mb-2" src="{{$outfit_cloth->image}}" alt="服のイメージ画像" width="100" height="auto">
                </a>
                <!-- 詳細モーダル画面内容 -->
                <div class="modal fade" id="detail_outfits{{$outfit_cloth->id}}" tabindex="-1" role="dialog" aria-labelledby="basicModal" aria-hidden="true">
                  <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                      <div class="modal-body">
                        <div class="card">
                          <div class="row no-gutters">
                            <div class="col-5">
                              <img class="card-img" src="{{$outfit_cloth->image}}" alt="服のイメージ画像">
                            </div>
                            <div class="col-7">
                              <div class="card-body text-center">
                                <h4 class="card-title">{{$outfit_cloth->name}}</h4>
                                <p class="card-text lead">カテゴリー：{{$outfit_cloth->category}}</p>
                                <p class="card-text lead">サイズ：{{$outfit_cloth->size}}</p>
                                <p class="card-text lead">購入日：{{$outfit_cloth->buy_date}}</p>
                                <p class="card-text lead">ブランド：{{$outfit_cloth->brand}}</p>
                                <p class="card-text lead">購入金額：{{$outfit_cloth->price}}円</p>
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
              @endforeach
            </div>
            <div>
              <!-- タグの表示 -->
              @foreach($outfit->tags as $outfit_tag)
                <span class="badge badge-pill badge-info">{{$outfit_tag->name}}</span>
              @endforeach
              <!-- いいね件数の表示 -->
              <i class="fas fa-heart fa-2x float-right" style="color:pink;">{{$outfit->like}}</i>
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
  <a data-toggle="modal" data-target="#add_outfits"><i class="fas fa-plus fa-3x"></i></a>
</div>
</div>
  <!-- 追加モーダル画面 -->
  <div class="modal fade" id="add_outfits" tabindex="-1" role="dialog" aria-labelledby="basicModal" aria-hidden="true">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">
        <div class="modal-header">
          <h4>コーディネートを追加する</h4>
        </div>
        <div class="modal-body">
          <form method="post" action="{{route('add_outfit')}}" enctype="multipart/form-data">
          @csrf
            <table class="table table-striped">
              @foreach($clothes as $cloth)
                <tbody>
                  <tr>
                    <th>
                      <div class="form-check inline">
                        <input class="form-check-input position-static" type="checkbox"  name="cloth_id[]" value="{{$cloth->id}}" >
                      </div>
                    </th>
                    <td><img src="{{$cloth->image}}" alt="服のイメージ画像" width="90" height="auto"></td>
                    <td>{{$cloth->name}}</td>
                    <td>{{$cloth->category}}</td>
                    <td>{{$cloth->size}}</td>
                    <td>{{$cloth->buy_date}}</td>
                  </tr>
                </tbody>
              @endforeach
            </table>
            <div class="form-group">
              <label for="name">名前:</label>
              <input type="text" id="name" name="name" class="form-control">
            </div>
            <div class="form-group">
              <label for="tag_name">タグ:</label>
              <input type="text" id="tag_name" name="tag_name" class="form-control" placeholder="#○○のようにタグを追加しよう">
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


@endsection
