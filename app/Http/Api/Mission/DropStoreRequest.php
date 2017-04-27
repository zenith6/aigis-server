<?php

namespace Aigis\Http\Api\Mission;

use Aigis\Http\BaseFormRequest;

class DropStoreRequest extends BaseFormRequest
{
    public function authorize()
    {
        /** @var \Aigis\Game\Mission $mission */
        $mission = $this->route('mission');

        return $this->user() && $mission->allow_report;
    }

    public function rules()
    {
        return [
            'drops.*.map_id'                 => ['required', 'exists:maps,id'],
            'drops.*.lap'                    => ['required', 'integer', 'min:0'],
            'drops.*.quantity'               => ['required', 'integer', 'min:0'],
            'drops.*.drop_rate'              => ['numeric', 'min:0'],
            'drops.*.contains_initial_bonus' => ['boolean'],
        ];
    }
}
