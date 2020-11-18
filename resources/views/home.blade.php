@extends('layouts.app')

@section('content')
@if(empty($msg))
    <h1 class="text-center">他の人の投稿</h1>
    <!-- 検索フォーム -->
    <div class="container">
        <div class="row">
            <form method="post" class="form-inline" action="{{route('search_home')}}" >
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
                            <a data-toggle="modal" data-target="#detail_profiles{{$profiles->id}}">
                                <img class="rounded-circle img-thumbnail float-right " src="{{$profiles->image}}" alt="プロフィール画像" width="40" height="auto">
                            </a>
                            <!-- 詳細モーダル画面内容 -->
                            <div class="modal fade" id="detail_profiles{{$profiles->id}}" tabindex="-1" role="dialog" aria-labelledby="basicModal" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-body">
                                            <div class="card">
                                                <div class="row no-gutters">
                                                    <div class="col-5">
                                                        <img class="card-img rounded-circle img-thumbnail"  src="{{$profiles->image}}" alt="プロフィール画像">
                                                    </div>
                                                    <div class="col-7">
                                                        <div class="card-body text-center">
                                                            <h4 class="card-title">{{$users->name}}</h4>
                                                            <p class="card-text lead">性別：{{$profiles->gender}}</p>
                                                            <p class="card-text lead">身長：{{$profiles->height}}</p>
                                                            <p class="card-text lead">{{$profiles->comment}}</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <!-- <button type="submit" class="btn btn-outline-primary mr-auto"><i class="fas fa-user-friends fa-1x"></i></i>フォロー</button>  -->
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
                                @endforeach
                            </div>
                            <div class="d-inline">
                                @foreach($outfit->tags as $outfit_tag)
                                    <span class="badge badge-pill badge-info">{{$outfit_tag->name}}</span>
                                @endforeach
                            </div>
                            <!-- いいねボタン -->
                            @auth
                                <div class="d-inline float-right">
                                    @if($likes[$loop->iteration]==1)
                                        <a href="{{route('nolike_home',$outfit->id)}}"><i class="fas fa-heart fa-2x " style="color:pink;"></i></a>
                                    @else
                                        <a href="{{route('like_home',$outfit->id)}}"><i class="fas fa-heart fa-2x" style="color:gray;"></i></a>
                                    @endif
                                </div>
                            @endauth
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@else
    {{$msg}}
@endif
@endsection
