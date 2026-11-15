<?php

namespace App\Http\Controllers\Admin;

class PartnerController extends ResourceController
{
    protected string $endpoint = 'partners';
    protected string $route = 'admin.partners';
    protected string $view = 'admin.partners';
    protected string $fileField = 'logo';
    protected string $label = 'Mitra';

    protected function rules(bool $creating): array
    {
        return [
            'name' => ['required', 'string', 'max:150'],
            'website' => ['nullable', 'url', 'max:300'],
            'order' => ['nullable', 'integer', 'min:0'],
            'logo' => [$creating ? 'required' : 'nullable', 'image', 'max:2048'],
        ];
    }
}
