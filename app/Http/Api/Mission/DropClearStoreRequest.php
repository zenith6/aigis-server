<?php
namespace Aigis\Http\Api\Mission;

use Aigis\Http\BaseFormRequest;

class DropClearStoreRequest extends BaseFormRequest
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
