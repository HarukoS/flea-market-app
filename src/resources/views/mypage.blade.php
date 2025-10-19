@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/mypage.css') }}">
@endsection

@section('content')
<div class="profile-area">
    <div class="profile-image">
        @if(Auth::user()->image)
        <img src="{{ asset('storage/' . Auth::user()->image) }}" alt="profile_image">
        @else
        @endif
    </div>

    <div class="profile-name">
        {{ auth()->user()->name }}
    </div>

    <div class="profile-edit">
        <a href="{{ route('profile') }}">プロフィールを編集</a>
    </div>
</div>

<div class="items-page">

    <ul class="items-tabs">
        <li class="tab {{ $tab === 'myitem' ? 'active' : '' }}">
            <a href="{{ route('mypage', ['page' => 'sell', 'search' => $search]) }}">
                出品した商品
            </a>
        </li>
        <li class="tab {{ $tab === 'buy' ? 'active' : '' }}">
            <a href="{{ route('mypage', ['page' => 'buy', 'search' => $search]) }}">
                購入した商品
            </a>
        </li>
    </ul>

    <div class="items-grid" id="itemsGrid">
        @foreach ($items as $item)
        <div class="item-card">
            <a href="{{ route('items.show', $item->id) }}" class="item-image-wrapper">
                <img src="{{ $item->image_url }}" alt="item_image">
                @if($item->is_sold)
                <div class="sold-ribbon">SOLD</div>
                @endif
            </a>
            <p class="item-name">{{ $item->item_name }}</p>
        </div>
        @endforeach
    </div>

</div>
@endsection