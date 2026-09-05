<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\RolePermission;
use App\Models\Guru;
use Illuminate\Support\Facades\Auth;

class HakAksesIndex extends Component
{
    public $role = 'guru';
    public $selectedGuruId = ''; // '' for default role, or id_guru for specific teacher override
    public $permissions = []; // Array of feature_key => boolean
    public $guruList = [];

    public static $availableRoles = [
        'guru' => 'Guru / Pengajar',
        'kepsek' => 'Kepala Sekolah',
        'admin' => 'Admin / Staf Operator'
    ];

    // List of features managed in the system
    public static $availableFeatures = [
        'dashboard' => [
            'label' => 'Dashboard Utama',
            'desc' => 'Menampilkan ringkasan statistik, grafik kehadiran, & info umum',
            'icon' => 'dashboard'
        ],
        'scan_qr' => [
            'label' => 'Scan QR Code Absensi',
            'desc' => 'Akses pemindai QR Code untuk mencatat absensi santri di gerbang/stan',
            'icon' => 'qr_code_scanner'
        ],
        'monitoring' => [
            'label' => 'Monitoring & Absensi Manual Santri',
            'desc' => 'Daftar santri realtime & pengubahan status kehadiran (Hadir, Sakit, Izin, Alfa)',
            'icon' => 'edit_note'
        ],
        'absen_guru' => [
            'label' => 'Input & Management Absensi Guru',
            'desc' => 'Menginput dan mengelola rekapitulasi kehadiran guru (untuk Guru Bendahara/Petugas)',
            'icon' => 'person_4'
        ],
        'data_santri' => [
            'label' => 'Manajemen Data Santri',
            'desc' => 'Melihat, menambah, mengedit, dan menghapus data santri',
            'icon' => 'groups'
        ],
        'data_guru' => [
            'label' => 'Manajemen Data Guru & Akun Login',
            'desc' => 'Melihat data guru, pembuatan akun login otomatis, & reset password',
            'icon' => 'person'
        ],
        'generate_qr' => [
            'label' => 'Generate & Download QR Code',
            'desc' => 'Mengunduh bundle file gambar Kartu QR Code santri per kelas atau guru',
            'icon' => 'qr_code'
        ],
        'laporan' => [
            'label' => 'Laporan Rekap Absensi',
            'desc' => 'Melihat & mencetak laporan rekapitulasi presensi santri / guru',
            'icon' => 'assessment'
        ],
        'setoran_bendahara' => [
            'label' => 'Rekap Setoran Bendahara',
            'desc' => 'Akses menu untuk menarik setoran uang tunai tabungan dari guru/wali kelas',
            'icon' => 'account_balance_wallet'
        ],
        'laporan_tabungan' => [
            'label' => 'Laporan Tabungan Global',
            'desc' => 'Melihat laporan rekapitulasi tabungan santri secara keseluruhan',
            'icon' => 'savings'
        ],
        'general_settings' => [
            'label' => 'Pengaturan Aplikasi & Sekolah',
            'desc' => 'Mengatur nama TPQ, logo, tahun ajaran, dan teks copyright',
            'icon' => 'settings'
        ],
        'backup' => [
            'label' => 'Backup & Restore Database',
            'desc' => 'Cadangan database & foto serta pemulihan data sistem',
            'icon' => 'backup'
        ]
    ];

    public function mount()
    {
        $user = Auth::user();
        if (!$user || (int) ($user->is_superadmin ?? 0) !== 1) {
            return redirect()->to('/admin/dashboard');
        }

        $this->guruList = Guru::orderBy('nama_guru')->get();
        $this->loadPermissions();
    }

    public function updatedRole()
    {
        if ($this->role !== 'guru') {
            $this->selectedGuruId = '';
        }
        $this->loadPermissions();
    }

    public function updatedSelectedGuruId()
    {
        $this->loadPermissions();
    }

    public function loadPermissions()
    {
        $query = RolePermission::where('role', $this->role);

        if ($this->role === 'guru' && !empty($this->selectedGuruId)) {
            $query->where('id_guru', $this->selectedGuruId);
        } else {
            $query->whereNull('id_guru');
        }

        $records = $query->get()->keyBy('feature_key');

        // Default configurations if new role or teacher override hasn't been saved yet
        $defaults = [
            'guru' => ['dashboard' => true, 'scan_qr' => true, 'monitoring' => true],
            'kepsek' => ['dashboard' => true, 'scan_qr' => true, 'monitoring' => true, 'data_santri' => true, 'data_guru' => true, 'generate_qr' => true, 'laporan' => true],
            'admin' => ['dashboard' => true, 'scan_qr' => true, 'monitoring' => true, 'data_santri' => true, 'data_guru' => true, 'generate_qr' => true, 'laporan' => true, 'general_settings' => true, 'backup' => true],
        ];

        // If specific teacher is selected, fallback to general role 'guru' permissions first
        $baseRolePermissions = [];
        if ($this->role === 'guru' && !empty($this->selectedGuruId)) {
            $baseRolePermissions = RolePermission::where('role', 'guru')
                ->whereNull('id_guru')
                ->get()
                ->keyBy('feature_key');
        }

        $roleDefaults = $defaults[$this->role] ?? [];

        $this->permissions = [];
        foreach (self::$availableFeatures as $key => $meta) {
            $record = $records->get($key);
            if ($record) {
                $this->permissions[$key] = (bool) $record->is_allowed;
            } elseif ($this->role === 'guru' && !empty($this->selectedGuruId) && $baseRolePermissions->has($key)) {
                $this->permissions[$key] = (bool) $baseRolePermissions->get($key)->is_allowed;
            } else {
                $this->permissions[$key] = isset($roleDefaults[$key]) ? (bool) $roleDefaults[$key] : false;
            }
        }
    }

    public function selectAll()
    {
        foreach (self::$availableFeatures as $key => $meta) {
            $this->permissions[$key] = true;
        }
    }

    public function deselectAll()
    {
        foreach (self::$availableFeatures as $key => $meta) {
            $this->permissions[$key] = false;
        }
    }

    public function savePermissions()
    {
        $user = Auth::user();
        if (!$user || (int) ($user->is_superadmin ?? 0) !== 1) {
            session()->flash('error', 'Anda tidak memiliki hak akses untuk mengubah pengaturan ini.');
            return;
        }

        $targetIdGuru = ($this->role === 'guru' && !empty($this->selectedGuruId)) ? $this->selectedGuruId : null;

        foreach ($this->permissions as $key => $isAllowed) {
            RolePermission::updateOrCreate(
                [
                    'role' => $this->role,
                    'id_guru' => $targetIdGuru,
                    'feature_key' => $key,
                ],
                [
                    'is_allowed' => (bool) $isAllowed,
                ]
            );
        }

        $targetName = self::$availableRoles[$this->role] ?? ucfirst($this->role);
        if ($this->role === 'guru' && !empty($this->selectedGuruId)) {
            $guru = Guru::find($this->selectedGuruId);
            if ($guru) {
                $targetName = "Guru Spesifik: " . $guru->nama_guru;
            }
        }

        session()->flash('success', "Hak Akses Fitur untuk '$targetName' berhasil disimpan.");
    }

    public function render()
    {
        return view('livewire.admin.hak-akses-index', [
            'rolesList' => self::$availableRoles,
            'featuresList' => self::$availableFeatures
        ])->layout('layouts.admin', ['title' => 'Manajemen Hak Akses Role & Guru', 'nav_title' => 'Hak Akses', 'context' => 'hak-akses']);
    }
}
