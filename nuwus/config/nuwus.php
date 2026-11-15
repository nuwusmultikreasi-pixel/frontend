<?php

/*
|--------------------------------------------------------------------------
| Profil PT NUWUS MULTIKREASI DIGITAL
|--------------------------------------------------------------------------
| Data diambil dari Surat Keputusan Pengesahan Pendirian (Kemenkum)
| dan Nomor Induk Berusaha (OSS). Ubah di sini bila ada perubahan.
*/

return [
    // Alamat REST API Django (backend)
    'api_url' => rtrim(env('NUWUS_API_URL', 'http://127.0.0.1:8001/api'), '/'),
    'api_timeout' => (int) env('NUWUS_API_TIMEOUT', 10),

    'company' => [
        'name' => 'PT NUWUS MULTIKREASI DIGITAL',
        'short_name' => 'Nuwus Multikreasi Digital',
        'legal_form' => 'Perseroan Perorangan (Usaha Mikro)',
        'founder' => 'M. Faza Musyaffa Al Azizi',
        'domicile' => 'Kota Malang',
        'sk_number' => 'AHU-A139487.AH.01.30.Tahun 2026',
        'sk_date' => '12 November 2026',
        'nib' => '1311260002404',
        'nib_date' => '13 November 2026',
        'kbli_code' => '58290',
        'kbli_title' => 'Penerbitan Perangkat Lunak (Software) Lainnya',
        'kbli_scope' => 'Penyelenggara Perdagangan Melalui Sistem Elektronik (PPMSE) dan Penyelenggara Sarana Perantara (PSP)',
        'investment_status' => 'PMDN',
        'business_scale' => 'Usaha Mikro',
        'risk_level' => 'Rendah',
        'address' => 'Jl. Sumbersari Gg. 4 No.286a, Sumbersari, Kec. Lowokwaru, Kota Malang, Jawa Timur 65145',
        'phone' => '0896-8460-8866',
        'whatsapp' => '6289684608866',
        'email' => 'nuwusmultikreasi@gmail.com',
    ],
];
