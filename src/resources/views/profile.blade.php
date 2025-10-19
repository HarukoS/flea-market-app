@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/profile.css') }}">
@endsection

@php
$tab = session('tab', 'default_tab');
@endphp

@section('content')
<div class="profile-form__content">
    <div class="profile-form__heading">
        <h2>プロフィール設定</h2>
    </div>
    <form class="form" action="{{ route('profile.update') }}" method="post" enctype="multipart/form-data">
        @csrf
        <div class="profile-image-area">
            <div class="profile-image" style="--bg-url: url('{{ asset('storage/' . auth()->user()->image) }}')"></div>
            <input type="file" id="fileInput" name="image" accept="image/*" hidden>
            <label for="fileInput" class="file-label">画像を選択する</label>
        </div>
        <div class="form__error">
            @error('image')
            {{ $message }}
            @enderror
        </div>
        <div class="form__group">
            <div class="form__group-title">
                <span class="form__label--item">ユーザー名</span>
            </div>
            <div class="form__group-content">
                <div class="form__input--text">
                    <input type="text" name="name" value="{{ old('name', auth()->user()->name ?? '') }}">
                </div>
                <div class="form__error">
                    @error('name')
                    {{ $message }}
                    @enderror
                </div>
            </div>
        </div>
        <div class="form__group">
            <div class="form__group-title">
                <span class="form__label--item">郵便番号</span>
            </div>
            <div class="form__group-content">
                <div class="form__input--text">
                    <input type="text" name="postal_code" value="{{ old('postal_code', auth()->user()->postal_code ?? '') }}">
                </div>
                <div class="form__error">
                    @error('postal_code')
                    {{ $message }}
                    @enderror
                </div>
            </div>
        </div>
        <div class="form__group">
            <div class="form__group-title">
                <span class="form__label--item">住所</span>
            </div>
            <div class="form__group-content">
                <div class="form__input--text">
                    <input type="text" name="address" value="{{ old('address', auth()->user()->address ?? '') }}">
                </div>
                <div class="form__error">
                    @error('address')
                    {{ $message }}
                    @enderror
                </div>
            </div>
        </div>
        <div class="form__group">
            <div class="form__group-title">
                <span class="form__label--item">建物名</span>
            </div>
            <div class="form__group-content">
                <div class="form__input--text">
                    <input type="text" name="building" value="{{ old('building', auth()->user()->building ?? '') }}">
                </div>
            </div>
        </div>
        <div class="form__button">
            <button class="form__button-submit" type="submit">更新する</button>
        </div>
    </form>
</div>
@endsection

<script src="{{ asset('js/profile.js') }}"></script>