<script src="https://code.jquery.com/jquery-3.7.0.min.js"
    integrity="sha256-2Pmvv0kuTBOenSvLm6bvfBSSHrUJ+3A7x6P5Ebd07/g=" crossorigin="anonymous"></script>
<script src="{{ asset('') }}assets/js/bootstrap.js"></script>
<script src="{{ asset('') }}assets/js/app.js"></script>

<!-- Need: Apexcharts -->
<script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.12.1/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.3.0/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.3.0/js/responsive.bootstrap5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.full.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script type="text/javascript" src="{{ asset('') }}assets/extensions/jquery-mask/jquery-mask.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.12/dist/sweetalert2.all.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.4/js/lightbox.min.js"
    integrity="sha512-Ixzuzfxv1EqafeQlTCufWfaC6ful6WFqIz4G+dWvK0beHw0NVJwvCKSgafpy5gwNqKmgUfIBraVwkKI+Cz0SEQ=="
    crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.full.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/6.6.1/tinymce.min.js"
    integrity="sha512-UAE3iwk1y0A7jx6PWZWng/s/7G+W0dfeYK8FwSvfj7Kx5EC6evlT7DJ9EDsAsPToEVi4pTaXKNedEMXq1JEK8g=="
    crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/6.6.1/plugins/code/plugin.min.js"
    integrity="sha512-m9KzLlmCIlFmsv8YNdZPP2RA5w+9qZfLsFhsYzD+DSQCsyEOdjc7KeG1iCNqFjOPQx7mJZPDuvXoOTAUkjJj2A=="
    crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdn.jsdelivr.net/npm/fslightbox@3.4.1/index.min.js"></script>

{{-- <script src="{{ asset('') }}assets/extensions/apexcharts/apexcharts.min.js"></script> --}}
{{-- <script src="{{ asset('') }}assets/js/pages/dashboard.js"></script> --}}

<script>
    $(document).ready(function() {
        const config = {
            allowInput: true,
        }
        $(".flatpicker").flatpickr(config);

        $('.select2').select2({
            theme: 'bootstrap-5'
        });

        tinymce.init({
            selector: '.editorTinyMCE',
            toolbar: 'undo redo styleselect bold italic alignleft aligncenter alignright alignjustify bullist numlist outdent indent code fullscreen',
            plugins: 'code lists fullscreen'
        });

        $('.uang').mask('000.000.000.000.000', {
            reverse: true
        });
        $('.phone').mask('000000000000', {
            reverse: true
        });
        $('.angka').mask('000000000000000', {
            reverse: true
        });
        $('.nik').mask('0000000000000000', {
            reverse: true
        });
    });
</script>
