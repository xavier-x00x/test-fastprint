<!-- DataTables -->
<link rel="stylesheet" href="<?= base_url() ?>assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="<?= base_url() ?>assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
<link rel="stylesheet" href="<?= base_url() ?>assets/plugins/datatables-buttons/css/buttons.bootstrap4.min.css">

<!-- SweetAlert2 -->
<link rel="stylesheet" href="<?= base_url() ?>assets/plugins/sweetalert2/sweetalert2.min.css">

<style>
    .card {
        border-radius: 0;
    }

    .table>thead>tr>th,
    .table>thead>tr>td,
    .table>tbody>tr>th,
    .table>tbody>tr>td,
    .table>tfoot>tr>th,
    .table>tfoot>tr>td {
        padding: 0.4em 1em;
        border-top-color: #ecf0f1;
        color: #404040;
        font-size: 10pt;
    }

    .card label,
    .dataTables_info,
    .paginate_button {
        font-size: 10pt;
    }

    .page-link {
        padding: 0.4rem 0.60rem;
    }

    .table-header {
        background: #E0F7FF;
        border-top: 2px solid #B3D7E5;
    }

    .popupx {
        width: 22em;
    }

    .popup2 {
        padding: 0.5em !important;
    }

    .padding-swal {
        padding: 0;
    }

    .margin2-swal {
        margin-top: 10px !important;
        margin-bottom: 0px !important;
        margin-left: 0px !important;
        margin-right: 0px !important;
    }

    .margin-swal {
        margin: 0 !important;
    }
</style>

<!-- Content Header (Page header) -->
<section class="content-header bg-white border-bottom mb-3">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6 d-flex align-items-center">
                <h2 style="color: #093d7c;" class="mb-0">Daftar Produk</h2>
            </div>
            <div class="col-sm-6 d-flex align-items-center flex-row-reverse">
                <a class="btn btn-secondary btn-flat btn-sm" href="<?= base_url('produk/create') ?>">
                    <i class="fas fa-plus"> Buat Produk Baru</i>
                </a>
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
                    <!-- /.card-header -->
                    <div class="card-body">
                        <table id="tabel_produk" class="table table-hover">
                            <thead class="table-header">
                                <tr>
                                    <th>Tindakan</th>
                                    <th>No.</th>
                                    <th>Nama</th>
                                    <th>Harga</th>
                                    <th>Kategori</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                    <!-- /.card-body -->
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

<!-- DataTables  & Plugins -->
<script src="<?= base_url() ?>assets/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?= base_url() ?>assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="<?= base_url() ?>assets/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="<?= base_url() ?>assets/plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
<script src="<?= base_url() ?>assets/plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
<script src="<?= base_url() ?>assets/plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
<script src="<?= base_url() ?>assets/plugins/jszip/jszip.min.js"></script>
<script src="<?= base_url() ?>assets/plugins/pdfmake/pdfmake.min.js"></script>
<script src="<?= base_url() ?>assets/plugins/pdfmake/vfs_fonts.js"></script>
<script src="<?= base_url() ?>assets/plugins/datatables-buttons/js/buttons.html5.min.js"></script>
<script src="<?= base_url() ?>assets/plugins/datatables-buttons/js/buttons.print.min.js"></script>
<script src="<?= base_url() ?>assets/plugins/datatables-buttons/js/buttons.colVis.min.js"></script>

<!-- SweetAlert2 -->
<script src="<?= base_url() ?>assets/plugins/sweetalert2/sweetalert2.min.js"></script>

<?= hapus("produk") ?>

<?php if ($this->session->flashdata('pesan')) { ?>
    <script>
        $(document).ready(function() {
            const Toastx = Swal.mixin({
                customClass: {
                    title: 'margin2-swal',
                    htmlContainer: 'margin-swal',
                    popup: 'popup2',
                },
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 5000,
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer)
                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                }
            });

            setTimeout(() => {
                Toastx.fire({
                    icon: 'success',
                    title: '<?= $this->session->flashdata('pesan') ?>'
                })
            }, 500);
        });
    </script>

<?php } ?>

<script type="text/javascript">
    $(document).ready(function() {
        var tabel = $('#tabel_produk').DataTable({
            // dom: "<'row'<'col-md-6'><'col-md-6'>>" + // 
            //     "<'row'<'col-md-2'l><'col-md-6 test_btn'><'col-md-4'f>>" + // peletakan entries, search, dan test_btn
            //     "<'row'<'col-md-12't>><'row'<'col-md-12'ip>>", // peletakan show dan halaman
            "order": [
                [2, 'asc']
            ],
            "lengthChange": false,
            "autoWidth": false,
            "processing": true,
            "serverSide": true,
            "ajax": {
                "url": "<?= base_url('produk/get_produk') ?>",
                "type": "POST"
            },
            "columnDefs": [{
                    "targets": 0,
                    "orderable": false,
                    "width": '7%',
                }, {
                    "targets": 1,
                    "orderable": false,
                    "width": '5%',
                },
                // {
                //     "targets": 4,
                //     "width": '15%',
                // }
                {
                    "targets": [3],
                    className: "text-right",
                },
                {
                    "targets": [5],
                    className: "text-center",
                },
            ],
        });

    });
</script>