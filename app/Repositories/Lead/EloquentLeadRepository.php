<?php

namespace App\Repositories\Lead;

use App\Models\Lead;

class EloquentLeadRepository implements LeadRepository
{

    /**7
     * @var Lead
     */
    private $lead;

    /**
     * @param Lead $lead
     */
    public function __construct(Lead $lead)
    {
        $this->lead = $lead;
    }

    /**
     * @param array $input
     * @return Lead
     */
    function create(array $input): Lead
    {
        return $this->lead->create($input);
    }

    /**
     * @param $id
     * @return Lead|null
     */
    public function getById($id): ?Lead
    {
        return $this->lead->findOrFail($id);
    }

    /**
     * @param Lead $lead
     * @param array $attributes
     * @return bool
     */
    public function update(Lead $lead, array $attributes): bool
    {
        return $this->lead->update($attributes);
    }

    /**
     * @param Lead $lead
     * @return bool|null
     */
    public function delete(Lead $lead): ?bool
    {
        return $lead->delete();
    }

    /**
     * @param array $filters
     * @param bool $count
     * @return mixed
     */
    public function search(array $filters = [], bool $count = false)
    {
        $query = $this->lead
            ->distinct()
            ->select('leads.*');

        if (isset($filters['owner'])) {
            $query->ofOwner($filters['owner']);
        }

        if ($count) {
            return $query->count('leads.id');
        }

        return $query->orderBy('leads.id');
    }
}
