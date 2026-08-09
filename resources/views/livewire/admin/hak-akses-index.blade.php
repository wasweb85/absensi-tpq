<div class="t-page">
    {{-- Flash Messages --}}
    @if (session()->has('success'))
        <div class="t-alert success" style="margin-bottom: 1.25rem;">
            <i class="material-icons">check_circle</i>
            <span>{{ session('success') }}</span>
            <button type="button" class="t-alert-close" onclick="this.parentElement.remove()">×</button>
        </div>
    @endif
    @if (session()->has('error'))
        <div class="t-alert danger" style="margin-bottom: 1.25rem;">
            <i class="material-icons">error</i>
            <span>{{ session('error') }}</span>
            <button type="button" class="t-alert-close" onclick="this.parentElement.remove()">×</button>
        </div>
    @endif

    <div class="t-card">
        <div class="t-card-header" style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 1rem;">
            <div style="display: flex; align-items: center; gap: 12px;">
                <div class="t-card-header-icon purple">
                    <i class="material-icons">admin_panel_settings</i>
                </div>
                <div>
                    <div class="t-card-title">Manajemen Hak Akses Role &amp; Guru</div>
                    <div class="t-card-subtitle">Atur centang fitur mana saja yang dapat diakses oleh Role atau Guru spesifik (seperti Guru Bendahara)</div>
                </div>
            </div>
            <div style="display: flex; gap: 8px; align-items: center;">
                <button type="button" wire:click="selectAll" class="t-btn" style="background: #f1f5f9; color: #334155; font-size: 0.8rem; font-weight: 600; padding: 6px 12px; border: 1px solid #cbd5e1;">
                    <i class="material-icons" style="font-size: 16px;">done_all</i> Pilih Semua
                </button>
                <button type="button" wire:click="deselectAll" class="t-btn" style="background: #f1f5f9; color: #64748b; font-size: 0.8rem; font-weight: 600; padding: 6px 12px; border: 1px solid #cbd5e1;">
                    <i class="material-icons" style="font-size: 16px;">remove_done</i> Kosongkan
                </button>
            </div>
        </div>

        <div class="t-card-body">
            {{-- Role & Teacher Selector Header --}}
            <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; padding: 14px 18px; margin-bottom: 1.5rem; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 1rem;">
                <div style="display: flex; align-items: center; flex-wrap: wrap; gap: 1rem;">
                    {{-- Role Selector --}}
                    <div style="display: flex; align-items: center; gap: 8px;">
                        <label style="font-weight: 700; font-size: 0.82rem; color: #475569; text-transform: uppercase; letter-spacing: 0.03em; margin: 0; white-space: nowrap;">
                            <i class="material-icons" style="font-size: 18px; vertical-align: text-bottom; color: #4f46e5; margin-right: 2px;">manage_accounts</i> Role:
                        </label>
                        <select wire:model.live="role" class="t-select" style="width: 220px; padding: 7px 12px; font-size: 0.88rem; font-weight: 700; border-radius: 8px; border: 1.5px solid #6366f1; background: #ffffff; color: #312e81; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
                            @foreach($rolesList as $roleKey => $roleName)
                                <option value="{{ $roleKey }}">{{ $roleName }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Teacher Specific Selector (Visible when role == 'guru') --}}
                    @if($role === 'guru')
                        <div style="display: flex; align-items: center; gap: 8px; border-left: 2px solid #e2e8f0; padding-left: 1rem;">
                            <label style="font-weight: 700; font-size: 0.82rem; color: #0369a1; text-transform: uppercase; letter-spacing: 0.03em; margin: 0; white-space: nowrap;">
                                <i class="material-icons" style="font-size: 18px; vertical-align: text-bottom; color: #0284c7; margin-right: 2px;">person_search</i> Pilih Nama Guru:
                            </label>
                            <select wire:model.live="selectedGuruId" class="t-select" style="width: 280px; padding: 7px 12px; font-size: 0.88rem; font-weight: 700; border-radius: 8px; border: 1.5px solid #0284c7; background: #ffffff; color: #0369a1; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
                                <option value="">-- Semua Guru (Default Role Guru) --</option>
                                @foreach($guruList as $g)
                                    <option value="{{ $g->id_guru }}">
                                        {{ $g->nama_guru }} (NIUP: {{ $g->niup }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    @endif
                </div>

                <div style="font-size: 0.78rem; color: #64748b; font-style: italic;">
                    @if($role === 'guru' && !empty($selectedGuruId))
                        * Pengaturan khusus ini hanya berlaku untuk Guru yang dipilih.
                    @else
                        * Pengaturan ini berlaku secara default untuk seluruh pengguna role ini.
                    @endif
                </div>
            </div>

            {{-- Features Grid --}}
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 1rem; margin-bottom: 2rem;">
                @foreach($featuresList as $key => $meta)
                    <div style="background: #ffffff; border: 1.5px solid {{ isset($permissions[$key]) && $permissions[$key] ? '#c7d2fe' : '#e2e8f0' }}; border-radius: 12px; padding: 14px 16px; transition: all 0.2s; box-shadow: 0 1px 3px rgba(0,0,0,0.03); display: flex; align-items: flex-start; gap: 12px;">
                        <div style="margin-top: 2px;">
                            <input type="checkbox" wire:model="permissions.{{ $key }}" id="chk_{{ $key }}" style="width: 20px; height: 20px; accent-color: #4f46e5; cursor: pointer;">
                        </div>
                        <label for="chk_{{ $key }}" style="cursor: pointer; flex: 1; margin: 0;">
                            <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 4px;">
                                <i class="material-icons" style="font-size: 18px; color: {{ isset($permissions[$key]) && $permissions[$key] ? '#4f46e5' : '#94a3b8' }};">{{ $meta['icon'] }}</i>
                                <span style="font-weight: 700; font-size: 0.92rem; color: {{ isset($permissions[$key]) && $permissions[$key] ? '#1e1b4b' : '#64748b' }};">
                                    {{ $meta['label'] }}
                                </span>
                            </div>
                            <div style="font-size: 0.78rem; color: #64748b; line-height: 1.35;">
                                {{ $meta['desc'] }}
                            </div>
                        </label>
                    </div>
                @endforeach
            </div>

            <div style="display: flex; justify-content: flex-end; padding-top: 1rem; border-top: 1px solid var(--t-divider);">
                <button type="button" wire:click="savePermissions" class="t-btn t-btn-primary t-btn-lg" style="padding: 10px 24px; font-weight: 700;">
                    <i class="material-icons" style="font-size: 1.25rem;">save</i>
                    Simpan Hak Akses 
                    @if($role === 'guru' && !empty($selectedGuruId))
                        (Guru Spesifik)
                    @else
                        ({{ $rolesList[$role] ?? $role }})
                    @endif
                </button>
            </div>
        </div>
    </div>
</div>
