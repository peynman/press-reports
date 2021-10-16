<?php

namespace Larapress\Reports\Services\Reports;

use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;

class ReportQueryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'domains' => 'array|nullable',
            'domains.*' => 'nullable|exist:domains,id',
            'groups' => 'nullable|array',
            'groups.*' => 'nullable|exists:groups,id',
            'products' => 'nullable|array',
            'products.*' => 'nullable|exists:products,id',
            'users' => 'nullable|array',
            'users.*' => 'nullable|exists:users,id',
            'from' => 'nullable|datetime_zoned',
            'to' => 'nullable|datetime_zoned',
            'filters' => 'nullable|json_object',
            'aggregate' => 'nullable|numeric',
            'groupBy' => 'array|nullable',
            'function' => 'nullable|string|in:sum,count,avrage,min,max',
        ];
    }

    /**
     * Undocumented function
     *
     * @return array
     */
    public function getFilters(): array
    {
        return $this->get('filters', []);
    }

    /**
     * Undocumented function
     *
     * @return array
     */
    public function getDomains(): array
    {
        return $this->get('domains', []);
    }

    /**
     * Undocumented function
     *
     * @return Carbon|null
     */
    public function getFrom(): Carbon|null
    {
        if ($this->has('from')) {
            return Carbon::parse($this->get('from'));
        }

        return null;
    }


    /**
     * Undocumented function
     *
     * @return Carbon|null
     */
    public function getTo(): Carbon|null
    {
        if ($this->has('to')) {
            return Carbon::parse($this->get('to'));
        }

        return null;
    }

    /**
     * Undocumented function
     *
     * @return boolean
     */
    public function shouldAggregate(): bool
    {
        return is_null($this->get('aggregate'));
    }

    /**
     * Undocumented function
     *
     * @return integer
     */
    public function getAggregateWindow(): int
    {
        return $this->get('aggregate', 3600 * 24);
    }

    /**
     * Undocumented function
     *
     * @return string
     */
    public function getAggregateFunction(): string
    {
        return $this->get('function', 'sum');
    }

    /**
     * Undocumented function
     *
     * @return array
     */
    public function getUserGroups(): array
    {
        return $this->get('groups', []);
    }

    /**
     * Undocumented function
     *
     * @return array
     */
    public function getProducts(): array
    {
        return $this->get('products', []);
    }

    /**
     * Undocumented function
     *
     * @return array
     */
    public function getUsers(): array
    {
        return $this->get('users', []);
    }

    /**
     * Undocumented function
     *
     * @return array
     */
    public function getGroupBy(): array
    {
        return $this->get('groupBy', []);
    }
}
