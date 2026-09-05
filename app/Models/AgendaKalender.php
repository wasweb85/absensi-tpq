<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AgendaKalender extends Model
{
    use HasFactory;

    protected $table = 'tb_agenda_kalender';

    protected $fillable = [
        'judul',
        'deskripsi',
        'tanggal_mulai',
        'tanggal_selesai',
        'kategori',
        'warna',
        'is_libur',
        'created_by',
    ];

    protected $casts = [
        'tanggal_mulai'   => 'date:Y-m-d',
        'tanggal_selesai' => 'date:Y-m-d',
        'is_libur'        => 'boolean',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Scope untuk memfilter agenda yang aktif pada bulan dan tahun tertentu
     */
    public function scopeBulanTahun($query, $month, $year)
    {
        $startDate = Carbon::createFromDate($year, $month, 1)->startOfMonth()->toDateString();
        $endDate   = Carbon::createFromDate($year, $month, 1)->endOfMonth()->toDateString();

        return $query->where(function ($q) use ($startDate, $endDate) {
            $q->whereBetween('tanggal_mulai', [$startDate, $endDate])
              ->orWhereBetween('tanggal_selesai', [$startDate, $endDate])
              ->orWhere(function ($sub) use ($startDate, $endDate) {
                  $sub->where('tanggal_mulai', '<=', $startDate)
                      ->where('tanggal_selesai', '>=', $endDate);
              });
        });
    }

    /**
     * Scope filter kategori
     */
    public function scopeKategori($query, $kategori)
    {
        if (!empty($kategori) && $kategori !== 'semua') {
            return $query->where('kategori', strtolower($kategori));
        }
        return $query;
    }

    /**
     * Cek apakah suatu tanggal berstatus libur (Libur Mingguan TPQ atau Agenda Libur)
     *
     * @param Carbon|string $date
     * @return bool
     */
    public static function isTanggalLibur($date): bool
    {
        if (!$date instanceof Carbon) {
            $date = Carbon::parse($date);
        }

        $dateStr = $date->toDateString();

        // 1. Cek Hari Libur Rutin Mingguan TPQ dari GeneralSetting (Default TPQ: Selasa & Jum'at)
        $setting = GeneralSetting::first();
        $liburMingguan = strtolower($setting->hari_libur_mingguan ?? 'selasa_jumat');

        $isWeeklyHoliday = match ($liburMingguan) {
            'selasa_jumat', 'jumat_selasa', 'tue_fri', 'fri_tue' => ($date->isTuesday() || $date->isFriday()),
            'jumat', 'fri', 'friday' => $date->isFriday(),
            'ahad', 'minggu', 'sun', 'sunday' => $date->isSunday(),
            'sabtu', 'sat', 'saturday' => $date->isSaturday(),
            'jumat_ahad', 'ahad_jumat' => ($date->isFriday() || $date->isSunday()),
            default => ($date->isTuesday() || $date->isFriday()),
        };

        if ($isWeeklyHoliday) {
            return true;
        }

        // 2. Cek Agenda Khusus TPQ yang ditandai is_libur = true
        return self::where('is_libur', true)
            ->where(function ($q) use ($dateStr) {
                $q->where(function ($sub) use ($dateStr) {
                    $sub->whereNull('tanggal_selesai')
                        ->where('tanggal_mulai', $dateStr);
                })
                ->orWhere(function ($sub) use ($dateStr) {
                    $sub->whereNotNull('tanggal_selesai')
                        ->where('tanggal_mulai', '<=', $dateStr)
                        ->where('tanggal_selesai', '>=', $dateStr);
                });
            })
            ->exists();
    }

    /**
     * Dapatkan rincian keterangan jika tanggal tersebut adalah hari libur
     */
    public static function getKeteranganLibur($date): ?string
    {
        if (!$date instanceof Carbon) {
            $date = Carbon::parse($date);
        }

        $dateStr = $date->toDateString();

        // Cek Agenda Libur khusus terlebih dahulu
        $agendaLibur = self::where('is_libur', true)
            ->where(function ($q) use ($dateStr) {
                $q->where(function ($sub) use ($dateStr) {
                    $sub->whereNull('tanggal_selesai')
                        ->where('tanggal_mulai', $dateStr);
                })
                ->orWhere(function ($sub) use ($dateStr) {
                    $sub->whereNotNull('tanggal_selesai')
                        ->where('tanggal_mulai', '<=', $dateStr)
                        ->where('tanggal_selesai', '>=', $dateStr);
                });
            })
            ->first();

        if ($agendaLibur) {
            return $agendaLibur->judul;
        }

        $setting = GeneralSetting::first();
        $liburMingguan = strtolower($setting->hari_libur_mingguan ?? 'selasa_jumat');

        $isWeeklyHoliday = match ($liburMingguan) {
            'selasa_jumat', 'jumat_selasa', 'tue_fri', 'fri_tue' => ($date->isTuesday() || $date->isFriday()),
            'jumat', 'fri', 'friday' => $date->isFriday(),
            'ahad', 'minggu', 'sun', 'sunday' => $date->isSunday(),
            'sabtu', 'sat', 'saturday' => $date->isSaturday(),
            'jumat_ahad', 'ahad_jumat' => ($date->isFriday() || $date->isSunday()),
            default => ($date->isTuesday() || $date->isFriday()),
        };

        if ($isWeeklyHoliday) {
            $namaHari = $date->isTuesday() ? 'Selasa' : ($date->isFriday() ? "Jum'at" : ($date->isSunday() ? 'Ahad' : 'Sabtu'));
            return "Libur Rutin ({$namaHari})";
        }

        return null;
    }
}
