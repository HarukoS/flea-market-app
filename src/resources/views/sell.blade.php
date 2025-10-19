@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/sell.css') }}">
@endsection

@php
$tab = session('tab', 'default_tab');
@endphp

@section('content')
<div class="sell-form__content">
    <div class="sell-form__heading">
        <h2>商品の出品</h2>
    </div>
    <form class="form" action="/sellitem" method="post" enctype="multipart/form-data">
        @csrf

        <div class="form__group">
            <div class="form__group-title">
                <span class="form__label-item">商品画像</span>
            </div>
            <div class="item-image-area">
                <div class="preview-wrapper">
                    <div class="image-preview" id="imagePreview"></div>
                </div>
                <div class="button-wrapper">
                    <input type="file" id="itemImage" name="item_image" accept="image/*" hidden>
                    <button type="button" class="upload-button" id="uploadButton">画像を選択する</button>
                </div>
            </div>
            <div class="form__error">
                @error('item_image')
                {{ $message }}
                @enderror
            </div>
        </div>

        <h3>商品の詳細</h3>
        <div class="form__group">
            <div class="form__group-title">
                <span class="form__label-item">カテゴリー</span>
            </div>
            <div class="category-list">
                @foreach($categories as $category)
                <button
                    type="button"
                    class="category-btn"
                    data-category-id="{{ $category->id }}">
                    {{ $category->category_name }}
                </button>
                <input type="checkbox" name="categories[]" value="{{ $category->id }}" class="hidden-category">
                @endforeach
            </div>
            <div class=" form__error">
                @error('categories')
                {{ $message }}
                @enderror
            </div>
        </div>
        <div class="form__group">
            <div class="form__group-title">
                <span class="form__label-item">商品の状態</span>
            </div>
            <div class="form__group-content">
                <div class="form__select-option">
                    <select name="condition">
                        <option value="" selected disabled>選択してください</option>
                        @foreach($conditions as $condition)
                        <option value="{{ $condition->id }}">{{ $condition->condition_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form__error">
                    @error('condition')
                    {{ $message }}
                    @enderror
                </div>
            </div>
        </div>
        <h3>商品名と説明</h3>
        <div class="form__group">
            <div class="form__group-title">
                <span class="form__label-item">商品名</span>
            </div>
            <div class="form__group-content">
                <div class="form__input-text">
                    <input type="text" name="item_name">
                </div>
                <div class="form__error">
                    @error('item_name')
                    {{ $message }}
                    @enderror
                </div>
            </div>
        </div>
        <div class="form__group">
            <div class="form__group-title">
                <span class="form__label-item">ブランド名</span>
            </div>
            <div class="form__group-content">
                <div class="form__input-text">
                    <input type="text" name="brand_name">
                </div>
            </div>
        </div>
        <div class="form__group">
            <div class="form__group-title">
                <span class="form__label-item">商品の説明</span>
            </div>
            <div class="form__group-content">
                <div class="form__input-text">
                    <textarea name="description" rows="5"></textarea>
                </div>
                <div class="form__error">
                    @error('description')
                    {{ $message }}
                    @enderror
                </div>
            </div>
        </div>
        <div class="form__group">
            <div class="form__group-title">
                <span class="form__label-item">販売価格</span>
            </div>
            <div class="form__group-content">
                <div class="form__input-text">
                    <span class="currency">¥</span>
                    <input type="text" name="price">
                </div>
                <div class="form__error">
                    @error('price')
                    {{ $message }}
                    @enderror
                </div>
            </div>
        </div>
        <div class="form__button">
            <button class="form__button-submit" type="submit">出品する</button>
        </div>
    </form>
</div>

@endsection

<script src="{{ asset('js/profile.js') }}"></script>

<script>
    document.addEventListener("DOMContentLoaded", () => {
        const buttons = document.querySelectorAll(".category-btn");
        const checkboxes = document.querySelectorAll(".hidden-category");

        buttons.forEach((btn, index) => {
            btn.addEventListener("click", () => {
                btn.classList.toggle("active");
                checkboxes[index].checked = btn.classList.contains("active");
            });
        });
    });
</script>

<script>
    document.addEventListener("DOMContentLoaded", () => {
        const fileInput = document.getElementById("itemImage");
        const preview = document.getElementById("imagePreview");
        const uploadBtn = document.getElementById("uploadButton");

        uploadBtn.addEventListener("click", () => {
            fileInput.click();
        });

        fileInput.addEventListener("change", (e) => {
            const file = e.target.files[0];
            if (!file) return;

            const reader = new FileReader();
            reader.onload = (event) => {
                preview.innerHTML = `<img src="${event.target.result}" alt="Preview">`;
            };
            reader.readAsDataURL(file);
        });
    });
</script>