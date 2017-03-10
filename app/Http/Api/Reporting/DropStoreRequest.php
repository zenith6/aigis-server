<?php
namespace Aigis\Http\Api\Reporting;

use Aigis\Http\BaseFormRequest;

class DropStoreRequest extends BaseFormRequest
{
    public function authorize()
    {
        return $this->user();
    }

    public function rules()
    {
        return [
            'map.*.id'       => ['required', 'exists:maps,id'],
            'map.*.lap'      => ['required', 'integer', 'min:0'],
            'map.*.quantity' => ['required', 'integer', 'min:0'],
        ];
    }
}
