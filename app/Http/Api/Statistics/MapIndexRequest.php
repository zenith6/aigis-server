<?php
namespace Aigis\Http\Api\Statistics;

use Aigis\Http\BaseFormRequest;

class MapIndexRequest extends BaseFormRequest
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
