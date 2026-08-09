<footer class="footer">
   <div class="container-fluid">
      <div class="copyright float-right text-sm text-gray-500">
         @if (!empty($appSettings->copyright))
            {{ $appSettings->copyright }}
         @else
            &copy; {{ date('Y') }} <strong>{{ $appSettings->school_name ?? 'TPQ' }}</strong> - Sistem Absensi Digital.
         @endif
      </div>
   </div>
</footer>
