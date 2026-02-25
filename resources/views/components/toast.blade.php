@if(session('toast_success') || session('toast_error'))
    <div id="toast" class="fixed top-5 right-5 bg-white border border-gray-300 p-4 rounded shadow-lg z-50">
        <p class="text-sm text-gray-800">
            {{ session('toast_success') ?? session('toast_error') }}
        </p>
    </div>
    <script>
        // remove toast after a few seconds
        setTimeout(function () {
            var el = document.getElementById('toast');
            if (el) { el.remove(); }
        }, 3000);
    </script>
@endif
