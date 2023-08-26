<div class="modal fade" id="formModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <form id="saveSuratKeluar" action="" method="post" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h4 class="modal-title mt-n3">Tambah Surat Keluar</h4>
                    <button type="button" class="btn-close btnCancel"></button>
                </div>
                <div class="modal-body">
                    <p class="text-danger">* Wajib diisi</p>
                    <div class="row p-1">
                        <div class="col-md-6 mb-3">
                            <label class="form-label" for="no_surat">Nomor Surat<span
                                    class="text-danger">*</span></label>
                            <input type="text" id="no_surat" name="no_surat" class="form-control" autofocus
                                required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label" for="tujuan">Tujuan Surat<span
                                    class="text-danger">*</span></label>
                            <input type="text" id="tujuan" name="tujuan" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label" for="tanggal">Tanggal Surat<span
                                    class="text-danger">*</span></label>
                            <input type="date" id="tanggal" name="tanggal" class="form-control flatpicker"
                                required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label" for="alamat">Alamat Surat<span
                                    class="text-danger">*</span></label>
                            <input type="text" id="alamat" name="alamat" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label" for="perihal">Perihal Surat<span
                                    class="text-danger">*</span></label>
                            <input type="text" id="perihal" name="perihal" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label" for="keterangan">Keterangan Surat<span
                                    class="text-danger">*</span></label>
                            <input type="text" id="keterangan" name="keterangan" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label class="form-label" for="file">File Surat<span
                                    class="text-danger">*</span></label>
                            <input type="file" id="file" name="file" class="form-control" accept=".pdf"
                                required>
                            <p class="text-muted">Ukuran maksimal file <strong>2 MB</strong>. Type file yang
                                diizinkan <strong>.pdf</strong></p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer d-flex justify-content-between">
                    <button type="button" class="btn btn-icon icon-left btnCancel" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle"></i>
                        Batal
                    </button>
                    <button type="submit" class="btn btn-icon icon-left btn-primary" id="btnSave">
                        <i class="bi bi-save"></i>
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
