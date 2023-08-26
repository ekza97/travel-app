<!--Delete Modal -->
<div class="modal fade text-left" id="deleteModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered " role="document">
        <div class="modal-content">
            <div class="modal-header bg-danger">
                <h4 class="modal-title text-white mt-n1" id="deleteModalLabel">Konfirmasi Hapus
                </h4>
                <button type="button" class="btn-close btnBatal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Anda yakin ingin menghapus data ini ?
            </div>
            <div class="modal-footer p-2">
                <button type="button" class="btn btn-light-secondary btnBatal">
                    <i class="bi bi-x-circle"></i>
                    <span>Batal</span>
                </button>
                <button type="submit" class="btn btn-danger ml-1" id="btnYes">
                    <i class="bi bi-trash"></i>
                    <span>Iya Hapus</span>
                </button>
            </div>
        </div>
    </div>
</div>
