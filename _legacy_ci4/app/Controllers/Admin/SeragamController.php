<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\SeragamModel;

class SeragamController extends BaseController
{
    protected SeragamModel $seragamModel;

    public function __construct()
    {
        $this->seragamModel = new SeragamModel();
    }

    public function index()
    {
        $seragam = $this->seragamModel->getAllByHari();

        $data = [
            'title'   => 'Ketentuan Seragam',
            'ctx'     => 'seragam',
            'seragam' => $seragam,
        ];

        return view('admin/seragam/index', $data);
    }

    public function store()
    {
        $hari = $this->request->getPost('hari');

        // Cek apakah sudah ada seragam untuk hari ini
        $existing = $this->seragamModel->where('hari', $hari)->first();
        if ($existing) {
            session()->setFlashdata(['msg' => "Ketentuan seragam hari $hari sudah ada. Gunakan tombol Edit.", 'error' => true]);
            return redirect()->to('/admin/seragam');
        }

        $this->seragamModel->insert([
            'hari'         => $hari,
            'nama_seragam' => $this->request->getPost('nama_seragam'),
            'deskripsi'    => $this->request->getPost('deskripsi'),
            'keterangan'   => $this->request->getPost('keterangan'),
        ]);

        session()->setFlashdata(['msg' => 'Ketentuan seragam berhasil ditambahkan.', 'error' => false]);
        return redirect()->to('/admin/seragam');
    }

    public function update()
    {
        $id = $this->request->getPost('id_seragam');

        $this->seragamModel->update($id, [
            'hari'         => $this->request->getPost('hari'),
            'nama_seragam' => $this->request->getPost('nama_seragam'),
            'deskripsi'    => $this->request->getPost('deskripsi'),
            'keterangan'   => $this->request->getPost('keterangan'),
        ]);

        session()->setFlashdata(['msg' => 'Ketentuan seragam berhasil diupdate.', 'error' => false]);
        return redirect()->to('/admin/seragam');
    }

    public function delete($id)
    {
        $this->seragamModel->delete($id);
        session()->setFlashdata(['msg' => 'Ketentuan seragam berhasil dihapus.', 'error' => false]);
        return redirect()->to('/admin/seragam');
    }
}
