{{-- <!-- BEGIN GLOBAL MANDATORY SCRIPTS -->

<script src="{{ asset('plugins/notification/snackbar/snackbar.min.js') }}"></script>
<script src="{{ asset('plugins/currency/currency.js') }}"></script>

 --}}

@livewireScripts
<script src="https://code.jquery.com/jquery-3.6.4.js"></script>

<script src="{{ asset('assets/js/libs/jquery-3.1.1.min.js') }}"></script>
<script src="{{ asset('bootstrap/js/popper.min.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"></script>
<script src="{{ asset('bootstrap/js/bootstrap.min.js') }}"></script>
<script src="{{ asset('bootstrap/js/popper2.11.6.min.js') }}"></script>
<script src="{{ asset('bootstrap/js/bootstrap5.2.3.min.js') }}"></script>
<script src="{{ asset('plugins/nicescroll/nicescroll.js') }}"></script>
<script src="{{ asset('plugins/perfect-scrollbar/perfect-scrollbar.min.js') }}"></script>
<script src="{{ asset('assets/js/app.js') }}"></script>
<script src="{{ asset('vendor/livewire-alert/livewire-alert.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.0/js/i18n/es.js"></script>


<script>
    $(document).ready(function() {
        App.init();
    });
</script>
<script src="{{ asset('assets/js/custom.js') }}"></script>

<script>
    function noty(msg, option = 1) {
        Snackbar.show({
            text: msg.toUpperCase(),
            actionText: 'CERRAR',
            actionTextColor: '#fff',
            backgroundColor: option == 1 ? '#3b3f5c' : '#e7515a',
            pos: 'top-right'
        });
    }
</script>

<script>
    function validaNumericos(event) {
        if (event.charCode >= 48 && event.charCode <= 57) {
            return true;
        }
        return false;
    }
</script>
