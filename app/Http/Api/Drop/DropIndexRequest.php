<?php
namespace Aigis\Http\Api\Drop;

use Aigis\Http\BaseFormRequest;

class DropIndexRequest extends BaseFormRequest
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
