@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/detail.css') }}">
@endsection

@section('content')
<div class="detail-page">
    <div class="item-content">
        <div class="item-image-wrapper">
            <img src="{{ $item->image_url }}" alt="item_image">
            @if($item->is_sold)
            <div class="sold-ribbon">SOLD</div>
            @endif
        </div>
        <div class="item-info">
            <div class="item-name">{{ $item->item_name }}</div>
            <p class="brand-name">{{ $item->brand_name }}</p>
            <div class="price">
                <p class="price__yen">¥</p>
                <p class="price__number">{{ number_format($item->price) }}</p>
                <p class="price__tax">（税込）</p>
            </div>
            <div class="icons">
                <div class="icons__star">
                    <form class="like-form" action="/like" method="post">
                        @csrf
                        <input type="hidden" name="item_id" value="{{ $item['id'] }}">
                        <button class="star__btn" type="submit"><img class="icon__img" src="{{ asset($liked ? 'img/star_like.png' : 'img/star.png') }}" alt="お気に入り" /></button>
                    </form>
                    <span class="like-number">{{ $item->likes->count() }}</span>
                </div>
                <div class="icons__comment">
                    <img class="icon__img" src="{{ asset('img/comment.png') }}" alt="コメント" />
                    <span class="comment-number">{{ $item->comments->count() }}</span>
                </div>
            </div>
            @if(!$item->is_sold)
            <div class="purchase-btn-submit">
                <a href="{{ route('purchase.page', ['item' => $item->id]) }}">購入手続きへ</a>
            </div>
            @else
            <div>
                <button class="purchase-btn-submit" type="button" disabled>SOLD</button>
            </div>
            @endif
            <div class="item-description">
                <div class="item-description__title">商品説明</div>
                <p class="item-description__text">{{ $item->description }}</p>
            </div>
            <div class="item-details">
                <div class="item-details__title">商品の情報</div>
                <p class="item-details__category">
                    <span class="label">カテゴリー</span>
                    @foreach($item->categories as $category)
                    <span class="category">{{ $category->category_name }}</span>
                    @endforeach
                </p>
                <p class="item-details__condition">
                    <span class="label">商品の状態</span>
                    <span class="condition">{{ $item->condition->condition_name }}</span>
                </p>
            </div>
            <div class="item-comments">
                <div class="item-comments__title">コメント ({{ $item->comments->count() }})</div>
                <div class="comment">
                    @foreach($item->comments as $comment)
                    <div class="comment-user_image">
                        @if($comment->user->image)
                        <img src="{{ asset('storage/' . $comment->user->image) }}" alt="profile_image">
                        @else
                        <img src="{{ asset('images/default-profile.png') }}" alt="default_profile_image">
                        @endif
                    </div>
                    <div class="comment-user_name">{{ $comment->user->name }}</div>
                    <div class="comment-text">{{ $comment->comment }}</div>
                    @endforeach
                </div>
            </div>
            <form class="comment-form" action="/comment" method="post">
                @csrf
                <label class="comment-title" for="comment-input">商品へのコメント</label>
                <input type="hidden" name="item_id" value="{{ $item['id'] }}">
                <textarea class="comment-input" id="comment-input" name="comment" placeholder="コメントを入力"></textarea>
                <button class="comment-btn-submit">コメントを送信する</button>
            </form>
            @if (count($errors) > 0)
            <ul class="error-text">
                @foreach ($errors->all() as $error)
                <li>{{$error}}</li>
                @endforeach
            </ul>
            @endif
        </div>
    </div>
</div>
@endsection