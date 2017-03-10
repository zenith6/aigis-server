<?php
namespace Aigis\Http\Front;

use Aigis\Http\BaseFormRequest;

class HomeIndexRequest extends BaseFormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [];
    }
}
