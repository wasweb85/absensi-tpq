<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use ZipArchive;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

class BackupController extends Controller
{
    /**
     * Show Backup & Restore page.
     */
    public function index()
    {
        return view('admin.backup.index', [
            'title' => 'Backup & Restore',
            'ctx' => 'backup',
        ]);
    }

    /**
     * Download database backup as .sql file.
     */
    public function dbBackup()
    {
        $hostname = config('database.connections.mysql.host');
        $username = config('database.connections.mysql.username');
        $password = config('database.connections.mysql.password');
        $database = config('database.connections.mysql.database');
        $filename = 'backup_db_' . date('Ymd_His') . '.sql';

        return response()->streamDownload(function () use ($hostname, $username, $password, $database) {
            $command = "mysqldump --skip-ssl --host={$hostname} --user={$username}";
            if ($password) {
                $command .= " --password={$password}";
            }
            $command .= " {$database}";
            passthru($command);
        }, $filename, [
            'Content-Type' => 'application/octet-stream',
        ]);
    }

    /**
     * Restore database from uploaded .sql file.
     */
    public function dbRestore(Request $request)
    {
        $request->validate([
            'file_backup_db' => 'required|file|mimes:sql',
        ]);

        $file = $request->file('file_backup_db');

        $hostname = config('database.connections.mysql.host');
        $username = config('database.connections.mysql.username');
        $password = config('database.connections.mysql.password');
        $database = config('database.connections.mysql.database');

        try {
            $filePath = $file->getRealPath();
            $command = "mysql --skip-ssl --host={$hostname} --user={$username}";
            if ($password) {
                $command .= " --password={$password}";
            }
            $command .= " {$database} < {$filePath}";

            exec($command . ' 2>&1', $output, $returnVar);

            if ($returnVar !== 0) {
                throw new \Exception(implode("\n", $output));
            }

            return redirect()->back()->with('success', 'Database berhasil direstore.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal merestore database: ' . $e->getMessage());
        }
    }

    /**
     * Download photos backup as .zip file.
     */
    public function photosBackup()
    {
        $uploadsPath = public_path('uploads');
        $zipFileName = 'backup_photos_' . date('Ymd_His') . '.zip';
        $zipFilePath = sys_get_temp_dir() . '/' . $zipFileName;

        $zip = new ZipArchive();
        if ($zip->open($zipFilePath, ZipArchive::CREATE | ZipArchive::OVERWRITE) === TRUE) {
            $files = new RecursiveIteratorIterator(
                new RecursiveDirectoryIterator($uploadsPath),
                RecursiveIteratorIterator::LEAVES_ONLY
            );

            foreach ($files as $name => $file) {
                if (!$file->isDir()) {
                    $filePath = $file->getRealPath();
                    $relativePath = substr($filePath, strlen($uploadsPath) + 1);
                    $zip->addFile($filePath, $relativePath);
                }
            }
            $zip->close();
        } else {
            return redirect()->back()->with('error', 'Gagal membuat file zip');
        }

        return response()->download($zipFilePath, $zipFileName)->deleteFileAfterSend(true);
    }

    /**
     * Restore photos from uploaded .zip file.
     */
    public function photosRestore(Request $request)
    {
        $request->validate([
            'file_backup_photos' => 'required|file|mimes:zip',
        ]);

        $file = $request->file('file_backup_photos');
        $uploadsPath = public_path('uploads');

        $zip = new ZipArchive();
        if ($zip->open($file->getRealPath()) === TRUE) {
            for ($i = 0; $i < $zip->numFiles; $i++) {
                $filename = $zip->getNameIndex($i);
                $targetPath = $uploadsPath . DIRECTORY_SEPARATOR . $filename;

                if (file_exists($targetPath) && !is_dir($targetPath)) {
                    @unlink($targetPath);
                }
            }

            $zip->extractTo($uploadsPath);
            $zip->close();
            return redirect()->back()->with('success', 'Foto berhasil direstore.');
        } else {
            return redirect()->back()->with('error', 'Gagal mengekstrak file zip.');
        }
    }
}
