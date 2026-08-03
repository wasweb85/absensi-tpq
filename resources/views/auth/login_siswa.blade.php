<x-guest-layout>
    <x-authentication-card>
        <x-slot name="logo">
            <x-authentication-card-logo />
        </x-slot>

        <div class="mb-4 text-center">
            <h2 class="text-xl font-bold text-gray-800">Portal Siswa</h2>
            <p class="text-sm text-gray-600">Login menggunakan NIS dan Nama Lengkap (Sesuai di Raport)</p>
        </div>

        <x-validation-errors class="mb-4" />

        @if (session('error'))
            <div class="mb-4 font-medium text-sm text-red-600">
                {{ session('error') }}
            </div>
        @endif

        <form method="POST" action="{{ url('/login-siswa') }}">
            @csrf

            <div>
                <x-label for="nis" value="{{ __('NIS (Nomor Induk Santri)') }}" />
                <x-input id="nis" class="block mt-1 w-full" type="text" name="nis" :value="old('nis')" required autofocus />
            </div>

            <div class="mt-4">
                <x-label for="nama" value="{{ __('Nama Lengkap') }}" />
                <x-input id="nama" class="block mt-1 w-full" type="text" name="nama" :value="old('nama')" required />
                <p class="text-xs text-gray-500 mt-1">Gunakan nama yang terdaftar atau tanyakan kepada Ustadz/Wali Kelas.</p>
            </div>

            <div class="flex items-center justify-end mt-4">
                <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 mr-4" href="{{ route('login') }}">
                    {{ __('Bukan Siswa? Login Pegawai') }}
                </a>

                <x-button class="ms-4">
                    {{ __('Log in') }}
                </x-button>
            </div>
        </form>
    </x-authentication-card>
</x-guest-layout>
