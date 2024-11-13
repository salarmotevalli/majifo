<?php

namespace App\DTO\Post;


class PostIndexQueryDto {
    public  $page = null;
    public  $postType = null;
    public  $categories = null;


    // TODO: separate filters as FilterDTO
    // TODO: use symfony request mapper
    public function __construct(array $entry) {
        foreach(get_object_vars($this) as $property => $var) {
            $this->{$property} = $entry[$property] ?? null;
        }
    }

    private function filterFields() 
    {
        return [
            'postType' => $this->postType,
            'categories' => $this->categories,
        ];
    }

    public function getFilters(): array {
        return array_filter($this->filterFields(), 'boolval');
    }

    public function getPage(): int {
        return $this->page ?? 1;
    }

}