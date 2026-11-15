<?php

namespace App\Http\Controllers\Admin;

class ProductController extends ResourceController
{
    protected string $endpoint = 'products';
    protected string $route = 'admin.products';
    protected string $view = 'admin.products';
    protected string $fileField = 'thumbnail';
    protected string $label = 'Produk';

    protected function rules(bool $creating): array
    {
        return [
            'name' => ['required', 'string', 'max:150'],
            'category' => ['nullable', 'string', 'max:80'],
            'tagline' => ['nullable', 'string', 'max:200'],
            'description' => ['required', 'string'],
            'url' => ['required', 'url', 'max:300'],
            'tech_stack' => ['nullable', 'string', 'max:200'],
            'year' => ['nullable', 'integer', 'min:2000', 'max:2100'],
            'order' => ['nullable', 'integer', 'min:0'],
            'thumbnail' => ['nullable', 'image', 'max:4096'],
        ];
    }
}
