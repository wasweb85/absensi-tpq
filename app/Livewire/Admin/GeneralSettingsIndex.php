<?php

namespace App\Livewire\Admin;

use App\Models\GeneralSetting;
use Livewire\Component;
use Livewire\WithFileUploads;

class GeneralSettingsIndex extends Component
{
    use WithFileUploads;

    public $settingsId;
    public $school_name;
    public $school_year;
    public $hari_libur_mingguan = 'jumat';
    public $copyright;
    public $logo; // For new upload
    public $currentLogo; // For showing current logo

    public function mount()
    {
        $settings = GeneralSetting::first();
        if ($settings) {
            $this->settingsId = $settings->id;
            $this->school_name = $settings->school_name;
            $this->school_year = $settings->school_year;
            $this->hari_libur_mingguan = $settings->hari_libur_mingguan ?? 'jumat';
            $this->copyright = $settings->copyright;
            $this->currentLogo = $settings->logo;
        }
    }

    public function updateSettings()
    {
        $this->validate([
            'school_name' => 'required|max:200',
            'school_year' => 'required|max:200',
            'copyright' => 'nullable|max:200',
            'logo' => 'nullable|image|max:2048' // Max 2MB
        ]);

        $settings = GeneralSetting::find($this->settingsId);
        if (!$settings) {
            $settings = new GeneralSetting();
        }

        $settings->school_name = $this->school_name;
        $settings->school_year = $this->school_year;
        $settings->hari_libur_mingguan = $this->hari_libur_mingguan ?? 'jumat';
        $settings->copyright = $this->copyright;

        if ($this->logo) {
            $ext = strtolower($this->logo->getClientOriginalExtension());
            $filename = 'logo-tpq-' . time() . '.' . $ext;
            
            // Remove old logo file if present
            if ($settings->logo && file_exists(public_path('uploads/logo/' . $settings->logo))) {
                @unlink(public_path('uploads/logo/' . $settings->logo));
            }

            $this->logo->storeAs('logo', $filename, 'public_uploads');
            $settings->logo = $filename;
            $this->currentLogo = $filename;
            $this->logo = null;
        }

        $settings->save();

        session()->flash('msg', 'Pengaturan berhasil diperbarui!');
        session()->flash('error', false);
    }

    public function render()
    {
        return view('livewire.admin.general-settings-index')->layout('layouts.admin', ['title' => 'Pengaturan Utama', 'context' => 'general_settings']);
    }
}
