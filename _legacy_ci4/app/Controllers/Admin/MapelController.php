<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\MapelModel;

class MapelController extends BaseController
{
    protected MapelModel $mapelModel;

    public function __construct()
    {
        $this->mapelModel = new MapelModel();
    }

    public function index()
    {
        $data = [
            'title' => 'Mata Pelajaran',
            'ctx'   => 'mapel',
            'mapel' => $this->mapelModel->orderBy('nama_mapel', 'ASC')->findAll(),
        ];
        return view('admin/mapel/index', $data);
    }

    public function store()
    {
        $nama = trim($this->request->getPost('nama_mapel'));

        if (empty($nama)) {
            session()->setFlashdata(['msg' => 'Nama mata pelajaran tidak boleh kosong.', 'error' => true]);
            return redirect()->to('/admin/mapel');
        }

        // Cek duplikasi
        $exist = $this->mapelModel->where('nama_mapel', $nama)->first();
        if ($exist) {
            session()->setFlashdata(['msg' => "Mata pelajaran \"$nama\" sudah ada.", 'error' => true]);
            return redirect()->to('/admin/mapel');
        }

        $this->mapelModel->insert(['nama_mapel' => $nama]);
        session()->setFlashdata(['msg' => 'Mata pelajaran berhasil ditambahkan.', 'error' => false]);
        return redirect()->to('/admin/mapel');
    }

    public function update()
    {
        $id   = $this->request->getPost('id_mapel');
        $nama = trim($this->request->getPost('nama_mapel'));

        if (empty($nama)) {
            session()->setFlashdata(['msg' => 'Nama mata pelajaran tidak boleh kosong.', 'error' => true]);
            return redirect()->to('/admin/mapel');
        }

        $this->mapelModel->update($id, ['nama_mapel' => $nama]);
        session()->setFlashdata(['msg' => 'Mata pelajaran berhasil diupdate.', 'error' => false]);
        return redirect()->to('/admin/mapel');
    }

    public function delete($id)
    {
        $this->mapelModel->delete($id);
        session()->setFlashdata(['msg' => 'Mata pelajaran berhasil dihapus.', 'error' => false]);
        return redirect()->to('/admin/mapel');
    }
}
