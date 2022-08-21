<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Fri, 19 Aug 2022 15:02:53 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Setup;

use App\Models\Organisations\User;
use App\Models\Organisations\UserLinkCode;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;
use Illuminate\Validation\Validator;


/**
 * @property UserLinkCode userLinkCode
 * @property User user
 */
class SetupAccessCode
{
    use AsAction;


    public function handle(): void
    {
        $this->userLinkCode->organisation->users()->attach($this->userLinkCode->id);
        $this->userLinkCode->delete();
    }


    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'code' => 'required|exists:App\Models\Organisations\UserLinkCode',
        ];
    }

    /** @noinspection PhpUnused */
    public function afterValidator(Validator $validator, ActionRequest $request): void
    {
        $this->userLinkCode = UserLinkCode::where('code', $request->get('code'))->withTrashed()->first();

        if ($this->userLinkCode->trashed()) {
            $validator->errors()->add('expired_code', 'Access code expired.');
        }
    }


    /** @noinspection PhpUnused */
    public function asController(ActionRequest $request): RedirectResponse
    {
        $this->user = $request->user();
        $this->handle();

        return Redirect::route('setup.root');
    }
}
