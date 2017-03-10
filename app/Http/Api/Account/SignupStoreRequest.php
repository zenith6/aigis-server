<?php
namespace Aigis\Http\Api\Account;

use Aigis\Http\BaseFormRequest;

class SignupStoreRequest extends BaseFormRequest
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
