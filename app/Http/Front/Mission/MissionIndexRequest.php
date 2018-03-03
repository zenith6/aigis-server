<?php

namespace Aigis\Http\Front\Mission;

use Aigis\Http\BaseFormRequest;

class MissionIndexRequest extends BaseFormRequest
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
