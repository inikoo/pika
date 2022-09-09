<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Thu, 23 Sep 2021 02:39:37 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Actions\HumanResources\Employee;

use App\Actions\UI\WithInertia;
use App\Http\Resources\HumanResources\EmployeeInertiaResource;
use App\Http\Resources\HumanResources\EmployeeResource;
use App\Models\HumanResources\Employee;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Inertia\Inertia;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;
use ProtoneMedia\LaravelQueryBuilderInertiaJs\InertiaTable;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

/**
 * @property array $breadcrumbs
 * @property bool $canEdit
 * @property string $title
 */
class IndexEmployee
{
    use AsAction;
    use WithInertia;


    public function handle(): LengthAwarePaginator
    {
        $globalSearch = AllowedFilter::callback('global', function ($query, $value) {
            $query->where(function ($query) use ($value) {
                $query->where('employees.name', 'LIKE', "%$value%")
                    ->orWhere('employees.code', 'LIKE', "%$value%");
            });
        });


        return QueryBuilder::for(Employee::class)
            ->defaultSort('employees.code')
            ->select(['code', 'id', 'worker_number', 'name'])
            ->allowedSorts(['code', 'worker_number', 'name'])
            ->allowedFilters([$globalSearch])
            ->paginate($this->perPage ?? 15)
            ->withQueryString();
    }

    public function authorize(ActionRequest $request): bool
    {
        return
            (
                $request->user()->tokenCan('root') or
                $request->user()->hasPermissionTo('employees.view')
            );
    }


    public function jsonResponse(): AnonymousResourceCollection
    {
        return EmployeeResource::collection($this->handle());
    }


    public function htmlResponse(LengthAwarePaginator $employees)
    {
        return Inertia::render(
            'HumanResources/Employees',
            [


                'labels' => [
                    'headTitle' => __('Employees'),
                    'title'     => __('Employees'),
                ],
                'employees' => EmployeeInertiaResource::collection($employees),


            ]
        )->table(function (InertiaTable $table) {
            $table
                ->withGlobalSearch()
                ->column(key: 'code', canBeHidden: false, sortable: true, searchable: true)
                ->column(key: 'name', canBeHidden: false, sortable: true, searchable: true)
                ->defaultSort('code');
        });
    }




    public function asController(Request $request): LengthAwarePaginator
    {
        $this->fillFromRequest($request);
        return $this->handle();
    }


}