@extends('layouts.app')

@section('content')
@if(empty($msg))
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
                            <img class="rounded-circle img-thumbnail float-right " src="{{$profiles->image}}" alt="プロフィール画像" width="40" height="auto">
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
                                        <a href="{{route('nolike_home',$outfit->id)}}"><i class="fas fa-heart fa-2x my-pink"></i></a>
                                    @else
                                        <a href="{{route('like_home',$outfit->id)}}"><i class="fas fa-heart fa-2x my-gray"></i></a>
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
