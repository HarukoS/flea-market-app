<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class CommentRequest extends FormRequest
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
            'comment' => 'required|max:255',
        ];
    }

    public function messages()
    {
        return [
            'comment.required' => 'コメントを入力してください',
            'comment.max' => 'コメントは255文字以下で入力してください',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        // 入力した item_id を取得
        $itemId = $this->input('item_id');

        throw new HttpResponseException(
            redirect()
                ->route('items.show', ['id' => $itemId]) // 商品詳細ページに戻す
                ->withErrors($validator)
                ->withInput()
        );
    }
}
