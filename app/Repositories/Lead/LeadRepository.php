<?php

namespace App\Repositories\Lead;

use App\Models\Lead;

interface LeadRepository
{
    function create(array $input): Lead;

    public function getById($id): ?Lead;

    public function update(Lead $lead, array $attributes): bool;

    public function delete(Lead $lead): ?bool;

    public function search(array $filters = [], bool $count = false);
}
