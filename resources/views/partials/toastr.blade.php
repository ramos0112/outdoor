{{-- resources/views/partials/toastr.blade.php --}}
<link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

<script>
    @if (Session::has('success'))
        toastr.success("{{ Session::get('success') }}", 'Éxito');
    @endif

    @if (Session::has('error'))
        toastr.error("{{ Session::get('error') }}", 'Error');
    @endif

    @if (Session::has('info'))
        toastr.info("{{ Session::get('info') }}", 'Información');
    @endif

    @if (Session::has('warning'))
        toastr.warning("{{ Session::get('warning') }}", 'Advertencia');
    @endif

    @if ($errors->has('error'))
        toastr.error('{{ $errors->first('error') }}');
    @endif
</script>
