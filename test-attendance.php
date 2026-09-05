<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$kelasId = App\Models\Kelas::first()->id_kelas;
$siswa = App\Models\Siswa::where('id_kelas', $kelasId)->first();
if($siswa) {
    echo "Siswa: " . $siswa->nama_siswa . "\n";
    $res = App\Models\PresensiSiswa::updateOrCreate(
        ['id_siswa' => $siswa->id_siswa, 'tanggal' => date('Y-m-d')],
        ['id_kelas' => $kelasId, 'id_kehadiran' => 1, 'jam_masuk' => date('H:i:s'), 'keterangan' => '']
    );
    echo "Saved: " . ($res ? 'Yes' : 'No') . "\n";
} else {
    echo "No siswa found.";
}
