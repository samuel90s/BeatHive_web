{{-- Core scripts --}}
<script src="{{ asset('assets/static/js/components/dark.js') }}"></script>
<script src="{{ asset('assets/static/js/pages/horizontal-layout.js') }}"></script>
<script src="{{ asset('assets/extensions/perfect-scrollbar/perfect-scrollbar.min.js') }}"></script>
<script src="{{ asset('assets/compiled/js/app.js') }}"></script>

{{-- Charts --}}
<script src="{{ asset('assets/extensions/apexcharts/apexcharts.min.js') }}"></script>
<script>
(function () {
  const KEY = 'theme'; // 'light' | 'dark'
  const $html = document.documentElement;
  const $toggle = document.getElementById('toggle-dark');

  function applyTheme(theme) {
    if (theme === 'dark') {
      $html.classList.add('theme-dark');
      if ($toggle) $toggle.checked = true;
    } else {
      $html.classList.remove('theme-dark');
      if ($toggle) $toggle.checked = false;
    }
  }

  // init: from localStorage or system preference
  let saved = localStorage.getItem(KEY);
  if (!saved) {
    saved = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
  }
  applyTheme(saved);

  // bind toggle
  if ($toggle) {
    $toggle.addEventListener('change', () => {
      const next = $toggle.checked ? 'dark' : 'light';
      localStorage.setItem(KEY, next);
      applyTheme(next);
    });
  }
})();
</script>
