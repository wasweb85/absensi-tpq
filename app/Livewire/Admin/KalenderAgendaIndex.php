<?php

namespace App\Livewire\Admin;

use App\Helpers\HijriHelper;
use App\Models\AgendaKalender;
use App\Models\GeneralSetting;
use App\Models\RolePermission;
use Carbon\Carbon;
use Livewire\Component;

class KalenderAgendaIndex extends Component
{
    public $viewMode = 'bulanan'; // 'bulanan' atau 'tahunan'
    public $selectedYear;
    public $selectedMonth;
    public $selectedCategory = 'semua'; // 'semua', 'umum', 'akademik', 'penting', 'tugas'
    public $searchQuery = '';
    public $showLiburPekan = true; // Toggle "JUMAT & LIBUR OFF/ON"
    public $isManageable = false; // Wewenang CRUD bagi Admin / Petugas

    // State Modal Form CRUD Agenda
    public $isOpenModal = false;
    public $agendaId = null;
    public $form_judul = '';
    public $form_deskripsi = '';
    public $form_tanggal_mulai = '';
    public $form_tanggal_selesai = '';
    public $form_kategori = 'umum';
    public $form_warna = '#10b981';
    public $form_is_libur = false;

    // State Detail Event Modal (Mobile / Viewer)
    public $isOpenDetailModal = false;
    public $detailEvent = null;

    protected $rules = [
        'form_judul' => 'required|string|max:255',
        'form_tanggal_mulai' => 'required|date',
        'form_tanggal_selesai' => 'nullable|date|after_or_equal:form_tanggal_mulai',
        'form_kategori' => 'required|in:umum,akademik,penting,tugas',
        'form_warna' => 'nullable|string|max:30',
        'form_is_libur' => 'boolean',
    ];

    public function mount($mode = 'bulanan')
    {
        $this->viewMode = in_array($mode, ['bulanan', 'tahunan']) ? $mode : 'bulanan';
        $now = Carbon::now();
        $this->selectedYear = (int) $now->year;
        $this->selectedMonth = (int) $now->month;

        // Tentukan wewenang modifikasi (Hanya Admin / Petugas)
        $user = auth()->user();
        if ($user) {
            $isSuper = (int) ($user->is_superadmin ?? 0);
            $this->isManageable = ($isSuper === 1 || $isSuper === 3 || RolePermission::hasAccess($user, 'kalender_agenda_manage'));
        } else {
            $this->isManageable = false;
        }
    }

    public function changeViewMode($mode)
    {
        if (in_array($mode, ['bulanan', 'tahunan'])) {
            $this->viewMode = $mode;
        }
    }

    public function toggleLiburPekan()
    {
        $this->showLiburPekan = !$this->showLiburPekan;
    }

    public function setCategory($cat)
    {
        $this->selectedCategory = $cat;
    }

    public function prevMonth()
    {
        if ($this->selectedMonth === 1) {
            $this->selectedMonth = 12;
            $this->selectedYear--;
        } else {
            $this->selectedMonth--;
        }
    }

    public function nextMonth()
    {
        if ($this->selectedMonth === 12) {
            $this->selectedMonth = 1;
            $this->selectedYear++;
        } else {
            $this->selectedMonth++;
        }
    }

    public function prevYear()
    {
        $this->selectedYear--;
    }

    public function nextYear()
    {
        $this->selectedYear++;
    }

    public function jumpToMonth($month)
    {
        $this->selectedMonth = (int) $month;
        $this->viewMode = 'bulanan';
    }

    /**
     * Membuka modal form tambah agenda baru
     */
    public function openCreateModal($date = null)
    {
        if (!$this->isManageable) return;

        $this->resetForm();
        $this->form_tanggal_mulai = $date ? Carbon::parse($date)->toDateString() : Carbon::createFromDate($this->selectedYear, $this->selectedMonth, 1)->toDateString();
        $this->isOpenModal = true;
    }

    /**
     * Membuka modal form edit agenda
     */
    public function editAgenda($id)
    {
        if (!$this->isManageable) return;

        $agenda = AgendaKalender::findOrFail($id);
        $this->agendaId = $agenda->id;
        $this->form_judul = $agenda->judul;
        $this->form_deskripsi = $agenda->deskripsi;
        $this->form_tanggal_mulai = $agenda->tanggal_mulai ? $agenda->tanggal_mulai->toDateString() : '';
        $this->form_tanggal_selesai = $agenda->tanggal_selesai ? $agenda->tanggal_selesai->toDateString() : '';
        $this->form_kategori = $agenda->kategori;
        $this->form_warna = $agenda->warna ?: '#10b981';
        $this->form_is_libur = (bool) $agenda->is_libur;

        $this->isOpenModal = true;
    }

    /**
     * Menyimpan agenda baru atau pembaruan agenda
     */
    public function saveAgenda()
    {
        if (!$this->isManageable) return;

        $this->validate();

        AgendaKalender::updateOrCreate(
            ['id' => $this->agendaId],
            [
                'judul' => $this->form_judul,
                'deskripsi' => $this->form_deskripsi,
                'tanggal_mulai' => $this->form_tanggal_mulai,
                'tanggal_selesai' => !empty($this->form_tanggal_selesai) ? $this->form_tanggal_selesai : null,
                'kategori' => $this->form_kategori,
                'warna' => $this->form_warna,
                'is_libur' => (bool) $this->form_is_libur,
                'created_by' => auth()->id(),
            ]
        );

        $this->isOpenModal = false;
        $this->resetForm();
        session()->flash('success_message', 'Agenda kegiatan berhasil disimpan!');
    }

    /**
     * Menghapus agenda
     */
    public function deleteAgenda($id)
    {
        if (!$this->isManageable) return;

        $agenda = AgendaKalender::find($id);
        if ($agenda) {
            $agenda->delete();
            session()->flash('success_message', 'Agenda kegiatan berhasil dihapus.');
        }
    }

    public function showDetail($id)
    {
        $this->detailEvent = AgendaKalender::find($id);
        if ($this->detailEvent) {
            $this->isOpenDetailModal = true;
        }
    }

    public function closeModals()
    {
        $this->isOpenModal = false;
        $this->isOpenDetailModal = false;
        $this->resetForm();
    }

    protected function resetForm()
    {
        $this->agendaId = null;
        $this->form_judul = '';
        $this->form_deskripsi = '';
        $this->form_tanggal_mulai = '';
        $this->form_tanggal_selesai = '';
        $this->form_kategori = 'umum';
        $this->form_warna = '#10b981';
        $this->form_is_libur = false;
        $this->resetErrorBag();
    }

    /**
     * Menghasilkan matriks grid 7 kolom untuk bulan aktif
     */
    protected function getMonthMatrix()
    {
        $setting = GeneralSetting::first();
        $liburMingguan = strtolower($setting->hari_libur_mingguan ?? 'jumat');

        $firstDayOfMonth = Carbon::createFromDate($this->selectedYear, $this->selectedMonth, 1)->startOfDay();
        $lastDayOfMonth = $firstDayOfMonth->copy()->endOfMonth()->startOfDay();

        // Minggu = 0, Senin = 1, ..., Sabtu = 6
        $startDayOfWeek = $firstDayOfMonth->dayOfWeek; // 0 = Sunday
        $totalDaysInMonth = $lastDayOfMonth->day;

        // Ambil semua agenda yang beririsan dengan bulan ini
        $agendas = AgendaKalender::bulanTahun($this->selectedMonth, $this->selectedYear)
            ->kategori($this->selectedCategory)
            ->when(!empty($this->searchQuery), function ($q) {
                $q->where(function ($sub) {
                    $sub->where('judul', 'like', '%' . $this->searchQuery . '%')
                        ->orWhere('deskripsi', 'like', '%' . $this->searchQuery . '%');
                });
            })
            ->get();

        $matrix = [];
        $currentDate = $firstDayOfMonth->copy()->subDays($startDayOfWeek);
        $todayStr = Carbon::today()->toDateString();

        // Bangun 5 atau 6 minggu (35 atau 42 sel)
        $totalCells = ($startDayOfWeek + $totalDaysInMonth > 35) ? 42 : 35;

        for ($i = 0; $i < $totalCells; $i++) {
            $dateStr = $currentDate->toDateString();
            $isCurrentMonth = ($currentDate->month === $this->selectedMonth);
            $isToday = ($dateStr === $todayStr);

            // Cek libur mingguan (default: Jumat)
            $isWeeklyHoliday = match ($liburMingguan) {
                'jumat', 'fri', 'friday' => $currentDate->isFriday(),
                'ahad', 'minggu', 'sun', 'sunday' => $currentDate->isSunday(),
                'sabtu', 'sat', 'saturday' => $currentDate->isSaturday(),
                default => $currentDate->isFriday(),
            };

            // Dapatkan agenda pada tanggal ini
            $eventsOnDay = $agendas->filter(function ($item) use ($dateStr) {
                $start = $item->tanggal_mulai ? $item->tanggal_mulai->toDateString() : null;
                $end = $item->tanggal_selesai ? $item->tanggal_selesai->toDateString() : $start;
                
                // Hari libur resmi selalu tampil di setiap tanggal liburnya
                if ($item->is_libur) {
                    return ($dateStr >= $start && $dateStr <= $end);
                }

                // Jika agenda berdurasi panjang (> 7 hari seperti 1 semester), tampilkan di tanggal mulainya saja
                $diffDays = ($item->tanggal_mulai && $item->tanggal_selesai) 
                    ? $item->tanggal_mulai->diffInDays($item->tanggal_selesai) + 1 
                    : 1;

                if ($diffDays > 7) {
                    return ($dateStr === $start);
                }

                // Agenda singkat (1-7 hari) tampil pada seluruh rentang tanggalnya
                return ($dateStr >= $start && $dateStr <= $end);
            });

            // Konversi Hijriah
            $hijri = HijriHelper::convert($currentDate);

            $matrix[] = [
                'carbon' => $currentDate->copy(),
                'date_string' => $dateStr,
                'day' => $currentDate->day,
                'is_current_month' => $isCurrentMonth,
                'is_today' => $isToday,
                'is_weekly_holiday' => $isWeeklyHoliday,
                'hijri_day' => $hijri['day'],
                'hijri_short' => $hijri['month_short'],
                'hijri_formatted' => $hijri['formatted'],
                'events' => $eventsOnDay,
                'has_holiday_event' => $eventsOnDay->where('is_libur', true)->isNotEmpty(),
            ];

            $currentDate->addDay();
        }

        return $matrix;
    }

    /**
     * Menghasilkan data 12 bulan untuk tampilan tahunan
     */
    protected function getYearMonths()
    {
        $setting = GeneralSetting::first();
        $liburMingguan = strtolower($setting->hari_libur_mingguan ?? 'jumat');
        $now = Carbon::now();

        // Ambil semua agenda untuk tahun ini
        $allAgendasYear = AgendaKalender::whereYear('tanggal_mulai', $this->selectedYear)
            ->orWhereYear('tanggal_selesai', $this->selectedYear)
            ->get();

        $months = [];
        $indonesianMonthNames = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];

        for ($m = 1; $m <= 12; $m++) {
            $firstDay = Carbon::createFromDate($this->selectedYear, $m, 1);
            $totalDays = $firstDay->daysInMonth;
            $startDayOfWeek = $firstDay->dayOfWeek; // 0 = Min, 1 = Sen, etc.

            $days = [];
            // Padding hari sebelum tanggal 1
            for ($p = 0; $p < $startDayOfWeek; $p++) {
                $days[] = ['day' => null, 'is_holiday' => false, 'has_event' => false, 'is_today' => false];
            }

            for ($d = 1; $d <= $totalDays; $d++) {
                $dCarbon = Carbon::createFromDate($this->selectedYear, $m, $d);
                $dStr = $dCarbon->toDateString();

                $isWeekly = match ($liburMingguan) {
                    'jumat', 'fri', 'friday' => $dCarbon->isFriday(),
                    'ahad', 'minggu', 'sun', 'sunday' => $dCarbon->isSunday(),
                    default => $dCarbon->isFriday(),
                };

                $hasEvent = $allAgendasYear->first(function ($ev) use ($dStr) {
                    $start = $ev->tanggal_mulai ? $ev->tanggal_mulai->toDateString() : null;
                    $end = $ev->tanggal_selesai ? $ev->tanggal_selesai->toDateString() : $start;
                    return ($dStr >= $start && $dStr <= $end);
                });

                $isHolidayEvent = $hasEvent && $hasEvent->is_libur;

                $days[] = [
                    'day' => $d,
                    'is_holiday' => $isWeekly || $isHolidayEvent,
                    'is_weekly' => $isWeekly,
                    'has_event' => (bool) $hasEvent,
                    'is_today' => ($this->selectedYear === (int)$now->year && $m === (int)$now->month && $d === (int)$now->day),
                ];
            }

            $months[] = [
                'month_num' => $m,
                'name' => $indonesianMonthNames[$m],
                'is_current' => ($this->selectedYear === (int)$now->year && $m === (int)$now->month),
                'days' => $days,
            ];
        }

        return $months;
    }

    /**
     * Menghitung Live Stats untuk bulan aktif
     */
    protected function calculateStats()
    {
        $setting = GeneralSetting::first();
        $liburMingguan = strtolower($setting->hari_libur_mingguan ?? 'jumat');

        $firstDay = Carbon::createFromDate($this->selectedYear, $this->selectedMonth, 1);
        $totalDays = $firstDay->daysInMonth;

        $agendas = AgendaKalender::bulanTahun($this->selectedMonth, $this->selectedYear)->get();

        $hariLiburCount = 0;
        for ($d = 1; $d <= $totalDays; $d++) {
            $date = Carbon::createFromDate($this->selectedYear, $this->selectedMonth, $d);
            $dateStr = $date->toDateString();

            $isWeekly = match ($liburMingguan) {
                'jumat', 'fri', 'friday' => $date->isFriday(),
                'ahad', 'minggu', 'sun', 'sunday' => $date->isSunday(),
                default => $date->isFriday(),
            };

            $isAgendaLibur = $agendas->first(function ($item) use ($dateStr) {
                if (!$item->is_libur) return false;
                $start = $item->tanggal_mulai ? $item->tanggal_mulai->toDateString() : null;
                $end = $item->tanggal_selesai ? $item->tanggal_selesai->toDateString() : $start;
                return ($dateStr >= $start && $dateStr <= $end);
            });

            if ($isWeekly || $isAgendaLibur) {
                $hariLiburCount++;
            }
        }

        $hariKerjaCount = max(0, $totalDays - $hariLiburCount);
        $totalAgendaCount = $agendas->where('is_libur', false)->count();

        return [
            'hari_kerja' => $hariKerjaCount,
            'hari_libur' => $hariLiburCount,
            'total_agenda' => $totalAgendaCount,
        ];
    }

    /**
     * Dapatkan agenda penting/periode akademik aktif
     */
    protected function getActivePeriods()
    {
        return AgendaKalender::bulanTahun($this->selectedMonth, $this->selectedYear)
            ->whereIn('kategori', ['akademik', 'penting'])
            ->orderBy('tanggal_mulai', 'asc')
            ->take(3)
            ->get();
    }

    /**
     * Dapatkan daftar urutan timeline agenda dan libur pada bulan aktif
     */
    protected function getTimelineEvents()
    {
        $setting = GeneralSetting::first();
        $liburMingguan = strtolower($setting->hari_libur_mingguan ?? 'jumat');
        $namaLiburPekan = ucfirst($liburMingguan);

        $firstDay = Carbon::createFromDate($this->selectedYear, $this->selectedMonth, 1);
        $totalDays = $firstDay->daysInMonth;

        $agendas = AgendaKalender::bulanTahun($this->selectedMonth, $this->selectedYear)
            ->kategori($this->selectedCategory)
            ->when(!empty($this->searchQuery), function ($q) {
                $q->where(function ($sub) {
                    $sub->where('judul', 'like', '%' . $this->searchQuery . '%')
                        ->orWhere('deskripsi', 'like', '%' . $this->searchQuery . '%');
                });
            })
            ->get();

        $timeline = [];
        $dayNamesShort = [
            0 => 'MIN', 1 => 'SEN', 2 => 'SEL', 3 => 'RAB', 4 => 'KAM', 5 => 'JUM', 6 => 'SAB'
        ];

        for ($d = 1; $d <= $totalDays; $d++) {
            $date = Carbon::createFromDate($this->selectedYear, $this->selectedMonth, $d);
            $dateStr = $date->toDateString();
            $dayOfWeek = $date->dayOfWeek;

            // 1. Cek Libur Rutin Mingguan
            $isWeekly = match ($liburMingguan) {
                'jumat', 'fri', 'friday' => $date->isFriday(),
                'ahad', 'minggu', 'sun', 'sunday' => $date->isSunday(),
                default => $date->isFriday(),
            };

            if ($isWeekly && $this->showLiburPekan && ($this->selectedCategory === 'semua' || $this->selectedCategory === 'umum')) {
                $timeline[] = [
                    'date_string' => $dateStr,
                    'day_num' => $d,
                    'day_name' => $dayNamesShort[$dayOfWeek],
                    'judul' => "Libur Pekan (Hari {$namaLiburPekan})",
                    'deskripsi' => 'Libur rutin mingguan TPQ',
                    'kategori' => 'libur_pekan',
                    'kategori_label' => "Libur {$namaLiburPekan}",
                    'is_libur' => true,
                    'warna' => '#f43f5e',
                    'is_custom' => false,
                    'agenda_id' => null,
                ];
            }

            // 2. Agenda yang jatuh pada tanggal ini
            $dayEvents = $agendas->filter(function ($ev) use ($dateStr) {
                $start = $ev->tanggal_mulai ? $ev->tanggal_mulai->toDateString() : null;
                $end = $ev->tanggal_selesai ? $ev->tanggal_selesai->toDateString() : $start;
                return ($dateStr >= $start && $dateStr <= $end);
            });

            foreach ($dayEvents as $ev) {
                $timeline[] = [
                    'date_string' => $dateStr,
                    'day_num' => $d,
                    'day_name' => $dayNamesShort[$dayOfWeek],
                    'judul' => $ev->judul,
                    'deskripsi' => $ev->deskripsi,
                    'kategori' => $ev->kategori,
                    'kategori_label' => ucfirst($ev->kategori),
                    'is_libur' => (bool)$ev->is_libur,
                    'warna' => $ev->warna ?: '#10b981',
                    'is_custom' => true,
                    'agenda_id' => $ev->id,
                ];
            }
        }

        return $timeline;
    }

    public function render()
    {
        $matrix = ($this->viewMode === 'bulanan') ? $this->getMonthMatrix() : [];
        $yearMonths = ($this->viewMode === 'tahunan') ? $this->getYearMonths() : [];
        $stats = $this->calculateStats();
        $activePeriods = $this->getActivePeriods();
        $timelineEvents = $this->getTimelineEvents();

        $monthNames = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];
        $currentMonthName = $monthNames[$this->selectedMonth] ?? 'September';

        // Tentukan layout berdasarkan rute/portal
        if (request()->routeIs('siswa.*') || session()->has('siswa_id')) {
            return view('livewire.admin.kalender-agenda-index', [
                'matrix' => $matrix,
                'yearMonths' => $yearMonths,
                'stats' => $stats,
                'activePeriods' => $activePeriods,
                'timelineEvents' => $timelineEvents,
                'currentMonthName' => $currentMonthName,
            ])->layout('layouts.siswa', [
                'title' => 'Kalender & Agenda Santri',
                'context' => 'kalender',
            ]);
        }

        return view('livewire.admin.kalender-agenda-index', [
            'matrix' => $matrix,
            'yearMonths' => $yearMonths,
            'stats' => $stats,
            'activePeriods' => $activePeriods,
            'timelineEvents' => $timelineEvents,
            'currentMonthName' => $currentMonthName,
        ])->layout('layouts.admin', [
            'title' => 'Kalender & Agenda Terpadu',
            'context' => 'kalender-agenda',
        ]);
    }
}
