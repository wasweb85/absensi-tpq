<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\JadwalPelajaranModel;
use App\Models\KelasModel;
use App\Models\MapelModel;
use App\Models\GuruModel;

class JadwalPelajaranController extends BaseController
{
    protected JadwalPelajaranModel $jadwalModel;
    protected KelasModel $kelasModel;
    protected MapelModel $mapelModel;
    protected GuruModel $guruModel;

    public function __construct()
    {
        $this->jadwalModel = new JadwalPelajaranModel();
        $this->kelasModel  = new KelasModel();
        $this->mapelModel  = new MapelModel();
        $this->guruModel   = new GuruModel();
    }

    public function index()
    {
        $jadwal = $this->jadwalModel->getAll();
        $kelas  = $this->kelasModel->getDataKelas();
        $mapel  = $this->mapelModel->getAllMapel();
        $guru   = $this->guruModel->getAllGuru();

        // Kelompokkan per hari
        $hariUrut = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
        $perHari  = array_fill_keys($hariUrut, []);
        foreach ($jadwal as $j) {
            $perHari[$j['hari']][] = $j;
        }

        $data = [
            'title'   => 'Jadwal Pelajaran',
            'ctx'     => 'jadwal-pelajaran',
            'perHari' => $perHari,
            'kelas'   => $kelas,
            'mapel'   => $mapel,
            'guru'    => $guru,
        ];

        return view('admin/jadwal-pelajaran/index', $data);
    }

    public function store()
    {
        $rules = [
            'id_kelas'   => 'required',
            'id_mapel'   => 'required',
            'id_guru'    => 'required',
            'hari'       => 'required',
            'jam_mulai'  => 'required',
            'jam_selesai' => 'required',
        ];

        if (!$this->validate($rules)) {
            session()->setFlashdata(['msg' => 'Data tidak valid.', 'error' => true]);
            return redirect()->to('/admin/jadwal-pelajaran');
        }

        $this->jadwalModel->insert([
            'id_kelas'    => $this->request->getPost('id_kelas'),
            'id_mapel'    => $this->request->getPost('id_mapel'),
            'id_guru'     => $this->request->getPost('id_guru'),
            'hari'        => $this->request->getPost('hari'),
            'jam_mulai'   => $this->request->getPost('jam_mulai'),
            'jam_selesai' => $this->request->getPost('jam_selesai'),
            'keterangan'  => $this->request->getPost('keterangan'),
        ]);

        session()->setFlashdata(['msg' => 'Jadwal berhasil ditambahkan.', 'error' => false]);
        return redirect()->to('/admin/jadwal-pelajaran');
    }

    public function update()
    {
        $id = $this->request->getPost('id_jadwal_pelajaran');

        $this->jadwalModel->update($id, [
            'id_kelas'    => $this->request->getPost('id_kelas'),
            'id_mapel'    => $this->request->getPost('id_mapel'),
            'id_guru'     => $this->request->getPost('id_guru'),
            'hari'        => $this->request->getPost('hari'),
            'jam_mulai'   => $this->request->getPost('jam_mulai'),
            'jam_selesai' => $this->request->getPost('jam_selesai'),
            'keterangan'  => $this->request->getPost('keterangan'),
        ]);

        session()->setFlashdata(['msg' => 'Jadwal berhasil diupdate.', 'error' => false]);
        return redirect()->to('/admin/jadwal-pelajaran');
    }

    public function delete($id)
    {
        $this->jadwalModel->delete($id);
        session()->setFlashdata(['msg' => 'Jadwal berhasil dihapus.', 'error' => false]);
        return redirect()->to('/admin/jadwal-pelajaran');
    }
}
