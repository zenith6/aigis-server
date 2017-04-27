<?php
namespace Aigis\Http\Api\Mission;

use Aigis\Http\BaseFormRequest;

class DropStatIndexRequest extends BaseFormRequest
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
