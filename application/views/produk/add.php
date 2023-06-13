<!-- Select2 -->
<link rel="stylesheet" href="<?= base_url() ?>assets/plugins/select2/css/select2.min.css">
<link rel="stylesheet" href="<?= base_url() ?>assets/plugins/select2-bootstrap4-theme/select2-bootstrap4.css">

<style>
    .rp {
        text-align: right;
    }

    .card {
        border-radius: 0;
    }

    .card label {
        font-size: 14px;
        margin-bottom: 0.2rem;
    }

    .form-master-input {
        float: none;
        max-width: 570px;
        margin: 0 auto;
    }

    .form-control {
        display: block;
        width: 100%;
        height: 34px;
        padding: 6px 12px;
        font-size: 14px;
        line-height: 1.42857143;
        color: #555555;
        background-color: #fff;
        background-image: none;
        border: 1px solid #ccc;
        border-radius: 4px;
        box-shadow: inset 0 1px 1px rgb(0 0 0 / 8%);
        -webkit-transition: border-color ease-in-out 0.15s, box-shadow ease-in-out 0.15s;
        transition: border-color ease-in-out 0.15s, box-shadow ease-in-out 0.15s;
    }

    .text-danger.error-text {
        font-size: 14px;
    }
</style>


<!-- Content Header (Page header) -->
<section class="content-header bg-white border-bottom mb-3">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6 d-flex align-items-center">
                <h2 style="color: #093d7c;" class="mb-0">Buat Produk Baru</h2>
            </div>
        </div>
    </div><!-- /.container-fluid -->
</section>

<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card card-info card-outline">
                    <form action="<?= base_url('produk/store') ?>" id="frm_produk" method="post" autocomplete="off">
                        <div class="card-body form-master-input">
                            <div class="form-group">
                                <label for="nama_produk">Nama</label>
                                <input type="text" class="form-control rounded-0" name="nama_produk" id="nama_produk" placeholder="Nama Produk">
                                <span class="text-danger error-text nama_produk_error"></span>
                            </div>
                            <div class="form-group">
                                <label for="harga">Harga</label>
                                <input type="text" class="form-control rounded-0 rp" name="harga" id="harga" value="0">
                                <span class="text-danger error-text harga_error"></span>
                            </div>
                            <div class="form-group">
                                <label for="kategori">Kategori</label>
                                <input type="text" class="form-control rounded-0" name="kategori" id="kategori" placeholder="Katergori">
                                <span class="text-danger error-text kategori_error"></span>
                            </div>
                            <div class="form-group">
                                <label for="status">Status</label>
                                <select class="form-control rounded-0 status" style="width: 100%;" name="status" id="status">
                                    <option value="bisa dijual">Bisa dijual</option>
                                    <option value="tidak bisa dijual">Tidak bisa dijual</option>
                                </select>
                                <span class="text-danger error-text status_error"></span>
                            </div>
                        </div>
                        <!-- /.card-body -->
                        <div class="card-footer bg-white form-master-input d-flex justify-content-end">
                            <a class="btn btn-outline-info btn-flat btn-sm ml-5" href="<?= base_url('produk') ?>">Batal</a>
                            <button type="submit" class="ml-2 btn btn-secondary btn-flat btn-sm">Simpan</button>
                        </div>
                    </form>
                </div>
                <!-- /.card -->
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
    </div>
    <!-- /.container-fluid -->
</section>
<!-- /.content -->

<!-- Select2 -->
<script src="<?= base_url() ?>assets/plugins/select2/js/select2.full.min.js"></script>

<!-- AutoNumeric -->
<script src="<?= base_url() ?>assets/plugins/auto-numeric/autoNumeric.min.js"></script>

<script>
    $(document).on('keyup keypress', 'form input[type="text"]', function(e) {
        if (e.keyCode == 13) {
            e.preventDefault();
            return false;
        }
    });

    $(document).ready(function() {
        $('body').on('focus', 'input.rp', function() {
            $(this).select();
        });

        $('body').on('keyup', 'input.rp', function() {
            if (this.value == '') {
                this.value = 0;
                this.select();
            }
        });

        $(".rp").autoNumeric('init', {
            aSign: '',
            vMax: '99999999999999.99',
            vMin: '-99999999999999.99'
        });
        $('.rp').autoNumeric('update');

        $('.status').select2({
            theme: 'bootstrap4',
            minimumResultsForSearch: Infinity,
            placeholder: 'Pilih Status',
        })

        $(".status").val("")
        $(".status").trigger("change");

    });

    jQuery.fn.preventDoubleSubmission = function() {
        $(this).on('submit', function(e) {
            var $form = $(this);

            if ($form.data('submitted') === true) {
                // Previously submitted - don't submit again
                e.preventDefault();
            } else {
                $.ajax({
                    url: "<?= base_url('produk/check_validation') ?>",
                    method: $(this).attr('method'),
                    data: new FormData(this),
                    processData: false,
                    dataType: 'json',
                    contentType: false,
                    async: false,
                    beforeSend: function() {
                        $(document).find('span.error-text').text('');
                    },
                    success: function(data) {
                        console.log(data);
                        if (data.status == 0) {
                            e.preventDefault();
                            $.each(data.errors, function(prefix, val) {
                                $('span.' + prefix + '_error').text(val);
                            });
                        } else {
                            $form.data('submitted', true);
                        }
                    }
                });
            }
        });
        // Keep chainability
        return this;
    };

    $('#frm_produk').preventDoubleSubmission();
</script>