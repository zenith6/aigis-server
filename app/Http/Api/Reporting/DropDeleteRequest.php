<?php
namespace Aigis\Http\Api\Reporting;

use Aigis\Http\BaseFormRequest;

class DropDeleteRequest extends BaseFormRequest
{
    public function authorize()
    {
        return $this->user();
    }

    public function rules()
    {
        return [];
    }
}
