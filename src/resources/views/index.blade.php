@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/index.css') }}">
@endsection

@section('content')
<div class="items-page">

    <ul class="items-tabs">
        <li class="tab {{ $tab === 'recommend' ? 'active' : '' }}">
            <a href="{{ url('/?' . http_build_query(['search' => $search, 'tab' => 'recommend'])) }}">
                おすすめ
            </a>
        </li>
        <li class="tab {{ $tab === 'mylist' ? 'active' : '' }}">
            <a href="{{ url('/?' . http_build_query(['search' => $search, 'tab' => 'mylist'])) }}">
                マイリスト
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