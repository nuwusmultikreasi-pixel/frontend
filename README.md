# Frontend

| Folder | Isi | Domain |
|---|---|---|
| `dinezy/` | Tampilan Dinezy: `templates/` dan `static/` | dinezy.id |
| `nuwus/` | Website Nuwus Multikreasi (Laravel) | nuwusmultikreasi.dinezy.id |

Folder `dinezy/` tidak dijalankan sendiri. Isinya diambil oleh aplikasi Dinezy di repo `backend` saat build.
Setiap push yang mengubah `dinezy/` akan memicu deploy ulang Dinezy lewat
`.github/workflows/deploy-dinezy.yml`.
