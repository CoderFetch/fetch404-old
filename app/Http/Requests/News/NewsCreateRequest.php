<?php namespace App\Http\Requests\News;

use Illuminate\Foundation\Http\FormRequest;

use Zizaco\Entrust\EntrustFacade as Entrust;

class NewsCreateRequest extends FormRequest {

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Entrust::can('create_news_posts');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title' => 'required|min:5|max:40|regex:/[A-Za-z0-9\-_!\.\s]/',
            'content' => 'required|min:10|max:5000'
        ];
    }

    /**
     * Get the validation messages that apply to the request.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'title.required' => 'A title is required.',
            'title.min' => 'Titles must be at least 5 characters long.',
            'title.max' => 'Titles can be up to 40 characters long.',
            'title.regex' => 'You are using characters that are not allowed. Allowed characters are A through Z, 0 through 9, _, -, !, ., and a space.',
            'content.required' => 'A body for the post is required.',
            'content.min' => 'Posts must be at least 10 characters long.',
            'content.max' => 'Posts can be up to 5000 characters long.'
        ];
    }

    /**
     * What should we do if the user is not authorized?
     *
     * @return void
     */
    public function forbiddenResponse()
    {
        return response()->make(view('core.errors.403'), 403);
    }
}
