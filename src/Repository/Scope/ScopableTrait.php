<?php

namespace App\Repository\Scope;

use Doctrine\ORM\QueryBuilder;

trait ScopableTrait {
    public function applyScope(QueryBuilder $qBuilder, array $scopes)
    {
        foreach ($scopes as $scope => $value) {
            try {
                $this->{$scope}($qBuilder, $value);
            } catch (\Exception $e) {
                // TODO: handle error
            }
        }
    }
}