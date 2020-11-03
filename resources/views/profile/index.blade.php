@extends('layouts.app')

@section('content')
@if(!empty($profiles))
  <div class="card m-5" >
    <div class="row no-gutters">
      <div class="col-4">
        <img class="card-img rounded-circle img-thumbnail m-4" src="{{ asset('/storage/'.$profiles->image)}}" alt="プロフィール画像" width="" height="auto">
      </div>
      <div class="col-1">
      </div>
      <div class="col-7 text-center mt-5">
        <h1 class="card-title text-muted display-4"><strong class="mr-4">あなたのプロフィール</strong><a data-toggle="modal" data-target="#edit_profiles{{$profiles->id}}"><i class="fas fa-user-edit fa-1x"></i></a></h1>
        <!-- 編集モーダル画面内容 -->
        <div class="modal fade" id="edit_profiles{{$profiles->id}}" tabindex="-1" role="dialog" aria-labelledby="basicModal" aria-hidden="true">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <h4>プロフィールを編集する</h4>
              </div>
              <div class="modal-body">
                <form method="post" action="{{route('edit_profile')}}" enctype="multipart/form-data">
                @csrf
                  <input type="hidden" name="id" value="{{$profiles->id}}">
                  <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="gender" id="man" value="MAN">
                    <label class="form-check-label" for="man">MAN</label>
                  </div>
                  <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="gender" id="woman"value="WOMAN">
                    <label class="form-check-label" for="woman">WOMAN</label>
                  </div>
                  <div class="form-group">
                    <label for="height">身長：</label>
                    <input type="number" name="height" id="height" maxlength="3" value="{{$profiles->height}}">
                  </div>
                  <div class="form-group">
                    <label for="comment">自己紹介：</label>
                    <textarea name="comment" id="comment" cols="30" rows="10" class="form-control">{{$profiles->comment}}</textarea>
                  </div>
                  <div class="form-group">
                    <label for="image">画像：</label>
                    <input type="file" class="form-control" id="image" name="image">
                  </div>
                  <button type="submit" class="btn btn-primary">変更する</button>
                </form>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">✕</button>
              </div>
            </div>
          </div>
        </div>
        <!-- ここまで -->
        <h6 class="card-text  mt-4" style="font-size:35px">名前</h6>
        <p style="font-size:25px">{{Auth::user()->name}}</p>
        <h6 class="card-text mt-4" style="font-size:35px">性別</h6>
        <p style="font-size:25px">{{$profiles->gender}}</p>
        <h6 class="card-text mt-4"style="font-size:35px">身長</h6>
        <p style="font-size:25px">{{$profiles->height}}</p>
        <h6 class="card-text mt-4"style="font-size:35px">自己紹介</h6>
        <p style="font-size:25px">{{$profiles->comment}}</p>
      </div>
    </div>
  </div>
@else
  <div class="card m-5 p-3">
    <div class="row justify-content-center">
      <div class="col-4 text-right">
        <h4>プロフィールを追加する</h4>
      </div>
      <div class="col-8 text-left">
        <a data-toggle="modal" data-target="#add_profiles{{Auth::user()->id}}"><i class="fas fa-user-edit fa-3x"></i></a>
      </div>
      <!-- 追加モーダル画面内容 -->
      <div class="modal fade" id="add_profiles{{Auth::user()->id}}" tabindex="-1" role="dialog" aria-labelledby="basicModal" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h4>プロフィールを追加する</h4>
            </div>
            <div class="modal-body">
              <form method="post" action="{{route('add_profile')}}" enctype="multipart/form-data">
              @csrf
                <div class="form-check form-check-inline">
                  <input class="form-check-input" type="radio" name="gender" id="man" value="MAN">
                  <label class="form-check-label" for="man" >MAN</label>
                </div>
                <div class="form-check form-check-inline">
                  <input class="form-check-input" type="radio" name="gender" id="woman" value="WOMAN">
                  <label class="form-check-label" for="woman">WOMAN</label>
                </div>
                <div class="form-group">
                  <label for="height">身長：</label>
                  <input type="number" name="height" id="height" maxlength="3">
                </div>
                <div class="form-group">
                  <label for="comment">自己紹介：</label>
                  <textarea name="comment" id="comment" cols="30" rows="10" class="form-control"></textarea>
                </div>
                <div class="form-group">
                  <label for="image">プロフィール画像</label>
                  <input type="file" class="form-control" id="image" name="image">
                </div>
                <button type="submit" class="btn btn-primary">変更する</button>
              </form>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal">✕</button>
            </div>
          </div>
        </div>
      </div>
      <!-- ここまで -->
    </div>
  </div>

@endif
@endsection