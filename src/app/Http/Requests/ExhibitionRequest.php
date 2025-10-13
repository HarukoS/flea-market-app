<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ExhibitionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'item_name' => 'required',
            'description' => 'required|max:255',
            'item_image' => 'required|mimes:jpeg,png',
            'categories' => 'required',
            'condition' => 'required',
            'price' => 'required|integer|min:0|max:2147483647',
        ];
    }

    public function messages()
    {
        return [
            'item_name.required' => '商品名を入力してください',
            'description.required' => '商品の説明を入力してください',
            'description.max' => '商品の説明は255文字以下で入力してください',
            'item_image.required' => '商品画像を選択してください',
            'item_image.mimes' => '拡張子が.jpegまたは.pngのファイルを選択してください',
            'categories.required' => 'カテゴリーを選択してください',
            'condition.required' => '商品の状態を選択してください',
            'price.required' => '販売価格を入力してください',
            'price.integer' => '販売価格は数字で入力してください',
            'price.min' => '販売価格は0以上の数字で入力してください',
            'price.max' => '販売価格は2,147,483,647以下の数字で入力してください',
        ];
    }
}
