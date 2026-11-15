<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\NuwusApi;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\Response;
use Illuminate\Http\Request;

/** Dasar CRUD yang meneruskan data ke REST API Django. */
abstract class ResourceController extends Controller
{
    /** endpoint API, mis. "products" */
    protected string $endpoint;

    /** prefix nama route, mis. "admin.products" */
    protected string $route;

    /** folder view, mis. "admin.products" */
    protected string $view;

    /** nama field file yang di-upload */
    protected string $fileField;

    protected string $label;

    abstract protected function rules(bool $creating): array;

    public function __construct(protected NuwusApi $api) {}

    public function index()
    {
        try {
            $res = $this->api->get($this->endpoint.'/');
        } catch (ConnectionException) {
            return view($this->view.'.index', ['items' => [], 'offline' => true]);
        }
        if ($redirect = $this->guard($res)) {
            return $redirect;
        }

        return view($this->view.'.index', ['items' => $res->json() ?? [], 'offline' => false]);
    }

    public function create()
    {
        return view($this->view.'.form', ['item' => null]);
    }

    public function store(Request $request)
    {
        return $this->save($request, 'POST', $this->endpoint.'/', 'ditambahkan');
    }

    public function edit(int $id)
    {
        $res = $this->api->get("{$this->endpoint}/{$id}/");
        if ($redirect = $this->guard($res)) {
            return $redirect;
        }
        abort_unless($res->successful(), 404);

        return view($this->view.'.form', ['item' => $res->json()]);
    }

    public function update(Request $request, int $id)
    {
        return $this->save($request, 'PATCH', "{$this->endpoint}/{$id}/", 'diperbarui');
    }

    public function destroy(int $id)
    {
        $res = $this->api->delete("{$this->endpoint}/{$id}/");
        if ($redirect = $this->guard($res)) {
            return $redirect;
        }

        return redirect()->route($this->route.'.index')->with(
            $res->successful() ? 'success' : 'error',
            $res->successful() ? "{$this->label} berhasil dihapus." : "Gagal menghapus {$this->label}."
        );
    }

    protected function save(Request $request, string $method, string $uri, string $verb)
    {
        $data = $request->validate($this->rules($method === 'POST'));
        $file = $request->file($this->fileField);
        unset($data[$this->fileField]);
        $data['is_active'] = $request->boolean('is_active');
        $data['order'] = $data['order'] ?? 0;

        try {
            $res = $this->api->sendForm($method, $uri, $data, [$this->fileField => $file]);
        } catch (ConnectionException) {
            return back()->withInput()->withErrors(['api' => 'Backend Django tidak dapat dihubungi.']);
        }
        if ($redirect = $this->guard($res)) {
            return $redirect;
        }
        if (! $res->successful()) {
            return back()->withInput()->withErrors(NuwusApi::errors($res));
        }

        return redirect()->route($this->route.'.index')->with('success', "{$this->label} berhasil {$verb}.");
    }

    /** Token kadaluarsa/tidak valid → kembali ke login. */
    protected function guard(Response $res)
    {
        if (in_array($res->status(), [401, 403], true)) {
            session()->forget(['api_token', 'api_user']);

            return redirect()->route('login')->with('error', 'Sesi berakhir, silakan login kembali.');
        }

        return null;
    }
}
