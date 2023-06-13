# test-fastprint

Ini adalah dokumentasi untuk Tes Junior Programmer menggunakan framework CodeIgniter 3 dengan operasi CRUD (Create, Read, Update, Delete) untuk master Produk. Dokumentasi ini akan memberikan langkah-langkah untuk membuat website dengan menggunakan CodeIgniter 3 serta mengimplementasikan operasi CRUD.

Project ini menggunakan Template [AdminLTE 3](https://github.com/ColorlibHQ/AdminLTE)

## Untuk Tampilan Web Sebagai Berikut


## Mengatur Controller Produk

```php
<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Produk extends CI_Controller
{
   function __construct()
   {
      parent::__construct();
      $this->load->model('produk_model');
      $this->load->library('form_validation');
   }

   // UNTUK MENGAMBIL DATA PRODUK DARI API
   function get_produk_from_api()
   {
      $url = "https://recruitment.fastprint.co.id/tes/api_tes_programmer";

      $data = array(
         "username" => "tesprogrammer130623C01",
         "password" => md5("bisacoding-13-06-23")
      );

      $options = array(
         "http" => array(
            "method"  => "POST",
            "header"  => "Content-Type: application/x-www-form-urlencoded",
            "content" => http_build_query($data)
         )
      );

      $response = file_get_contents($url, false, stream_context_create($options));
      $produk = json_decode($response);

      $this->db->select('id_produk');
      $ids = $this->db->get('produk')->result();

      // LOOP INSERT DAN UPDATE DATA PRODUK
      foreach ($produk->data as $i => $value) {
         if (in_array($value->id_produk, $ids)) {
            $data = array(
               'nama_produk' => $value->nama_produk,
               'kategori'    => $value->kategori,
               'harga'       => $value->harga,
               'status'      => $value->status,
            );
            $where = "id_produk = '$value->id_produk'";
            $this->db->where($where);
            $this->db->update('produk', $data);
         } else {
            $datad = array(
               'id_produk'   => $value->id_produk,
               'nama_produk' => $value->nama_produk,
               'kategori'      => $value->kategori,
               'harga'      => $value->harga,
               'status'      => $value->status,
            );
            $this->db->insert('produk', $datad);
         }
      }
   }

   // UNTUK MENAMPILKAN DATA DI DATATABLE
   function get_produk()
   {
      // AMBIL DATA DARI PRODUK_MODEL
      $produks = $this->produk_model->get_produks();
      echo $produks;
   }

   public function index()
   {
      $data = array(
         'page'  => "produk/index",
      );

      $this->load->view('layouts/main', $data);
   }

   public function create()
   {
      $data = array(
         'page'  => "produk/add",
      );

      $this->load->view('layouts/main', $data);
   }

   public function store()
   {
      $datad = array(
         'nama_produk' => $this->input->post('nama_produk', true),
         'harga'      => str_replace(',', '', $this->input->post('harga', true)),
         'kategori'      => $this->input->post('kategori', true),
         'status'      => $this->input->post('status', true),
      );

      if ($this->db->insert('produk', $datad)) {
         $this->session->set_flashdata(
            'pesan',
            'Data Berhasil Disimpan'
         );
      } else {
         $this->session->set_flashdata(
            'pesan',
            'Data Gagal Disimpan'
         );
      }
      redirect('produk');
   }

   public function edit($id)
   {
      $data = array(
         'page'  => "produk/edit",
         'data'   => $this->produk_model->load_produk($id)
      );

      $this->load->view('layouts/main', $data);
   }

   public function update()
   {
      $id = $this->input->post('id_produk', true);

      $data = array(
         'nama_produk' => $this->input->post('nama_produk', true),
         'harga'      => str_replace(',', '', $this->input->post('harga', true)),
         'kategori'      => $this->input->post('kategori', true),
         'status'      => $this->input->post('status', true),
      );

      $where = "id_produk = '$id'";
      $this->db->where($where);

      if ($this->db->update('produk', $data)) {
         $this->session->set_flashdata(
            'pesan',
            'Data Berhasil Diupdate'
         );
      } else {
         $this->session->set_flashdata(
            'pesan',
            'Data Gagal Diupdate'
         );
      }
      redirect('produk');
   }

   public function delete()
   {
      $where = array(
         'id_produk'  => $this->input->post('idx', TRUE),
      );

      if ($this->db->delete('produk', $where)) {
         $this->session->set_flashdata(
            'pesan',
            'Data Berhasil Dihapus'
         );
      } else {
         $this->session->set_flashdata(
            'pesan',
            'Data Gagal Dihapus'
         );
      }

      redirect('produk');
   }

   // UNTUK CEK VALIDASI FROM MELALUI AJAX
   public function check_validation()
   {
      $config = array(
         array(
            'field' => 'nama_produk',
            'label' => 'Nama produk',
            'rules' => 'required',
            'errors' => array(
               'required' => '%s wajib diisi.',
            ),
         ),
         array(
            'field' => 'kategori',
            'label' => 'Kategori',
            'rules' => 'required',
            'errors' => array(
               'required' => '%s wajib diisi.',
            ),
         ),
         array(
            'field' => 'status',
            'label' => 'Status',
            'rules' => 'required',
            'errors' => array(
               'required' => '%s wajib diisi.',
            ),
         ),
      );

      $this->form_validation->set_rules($config);

      if ($this->form_validation->run() != false) {
         // DATA VALID
         $data = array(
            'status' => 1,
            'errors' => "",
         );
      } else {
         // DATA TIDAK VALID
         $data = array(
            'status' => 0,
            'errors' => $this->form_validation->error_array(),
         );
      }
      echo json_encode($data);
   }
}
```
## Mengatur Model Produk

```php
<?php

class Produk_model extends CI_model
{
   //// UNTUK MENAMPILKAN DATA DI DATATABLE
   var $column_order  = array(null, null, 'nama_produk', 'harga', 'kategori', 'status'); //set column field database for datatable orderable
   var $column_search = array('nama_produk', 'harga', 'kategori', 'status'); //set column field database for datatable searchable
   var $order         = array('nama_produk' => 'asc'); // default order

   private function _get_query()
   {
      $where = array(
         'status' => "bisa dijual",
      );
      $this->db->from('produk');
      $this->db->where($where);
   }

   private function _get_datatables_query()
   {
      $this->db->select('*');
      $this->_get_query();

      $i = 0;
      foreach ($this->column_search as $item) { // loop column 
         if (@$_POST['search']['value']) { // if datatable send POST for search
            if ($i === 0) { // first loop
               $this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
               $this->db->like($item, $_POST['search']['value']);
            } else {
               $this->db->or_like($item, $_POST['search']['value']);
            }
            if (count($this->column_search) - 1 == $i) //last loop
               $this->db->group_end(); //close bracket
         }
         $i++;
      }

      if (isset($_POST['order'])) { // here order processing
         $this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
      } else if (isset($this->order)) {
         $order = $this->order;
         $this->db->order_by(key($order), $order[key($order)]);
      }
   }

   function get_datatables()
   {
      $this->_get_datatables_query();
      if (@$_POST['length'] != -1)
         $this->db->limit(@$_POST['length'], @$_POST['start']);
      $query = $this->db->get();
      return $query->result();
   }

   function count_filtered()
   {
      $this->_get_datatables_query();
      $query = $this->db->get();
      return $query->num_rows();
   }

   function count_all()
   {
      $this->_get_query();
      return $this->db->count_all_results();
   }

   function get_produks()
   {
      $list = $this->get_datatables();
      $data = array();
      $no = @$_POST['start'];
      foreach ($list as $value) {
         $no++;
         $row = array();
         $row[] = '<div class="dropdown">
                        <a class="btn btn-xs btn-flat btn-secondary dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Action
                        </a>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                            <a class="dropdown-item btn-sm" href="' . base_url('produk/edit/' . $value->id_produk) . '">
                                <i class="fa fa-edit mr-1"></i> Edit
                            </a>
                            <a class="dropdown-item btn-sm del" href="#" data-del="' . $value->id_produk . '">
                                <i class="fa fa-trash mr-1"></i> Hapus
                            </a>
                        </div>
                    </div>';
         $row[] = $no . ".";
         $row[] = $value->nama_produk;
         $row[] = number_format($value->harga, 2, '.', ',');
         $row[] = $value->kategori;
         $row[] = $value->status;

         $data[] = $row;
      }

      $output = array(
         "draw" => @$_POST['draw'],
         "recordsTotal" => $this->count_all(),
         "recordsFiltered" => $this->count_filtered(),
         "data" => $data,
      );

      // output to json format
      return json_encode($output);
   }
   ////

   // UNTUK MENAMPILKAN DATA YANG AKAN DI EDIT
   function load_produk($id)
   {
      $this->db->select('*');

      $where = array(
         'status'    => "bisa dijual",
         'id_produk' => $id
      );
      $this->db->from('produk');
      $this->db->where($where);
      $query = $this->db->get();
      return $query->row();
   }
}
```

## Mengatur View Produk

* Atur file `application/views/produk/index.php` :
```html
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

<!-- HELPER UNTUK HAPUS DATA -->
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
      // SETTING DATATABLE SERVERSIDE UNTUK MENAMPILKAN DATA PRODUK
      var tabel = $('#tabel_produk').DataTable({
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
```

* Atur file `application/views/produk/add.php` untuk tampilan tambah data Produk:
```html
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
                        <!-- MENGGUNAKAN PLUGIN AUTONUMERIC -->
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
                        <!-- MENGGUNAKAN PLUGIN SELECT2 -->
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
   // AGAR TIDAK SUBMIT SAAT TEKAN TOMBOL ENTER
   $(document).on('keyup keypress', 'form input[type="text"]', function(e) {
      if (e.keyCode == 13) {
         e.preventDefault();
         return false;
      }
   });

   $(document).ready(function() {

      // SETTING INPUT ANGKA
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
      ////

      // SETTING SELECT2
      $('.status').select2({
         theme: 'bootstrap4',
         minimumResultsForSearch: Infinity,
         placeholder: 'Pilih Status',
      })

      $(".status").val("")
      $(".status").trigger("change");
      ////

   });

   // UNTUK MENGATASI DOBEL SUBMIT DAN UNTUK FORM VALIDASI AJAX
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
```


* Atur file `application/views/produk/add.php` untuk tampilan edit data Produk:
```html
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
            <h2 style="color: #093d7c;" class="mb-0">Edit Produk</h2>
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
               <form action="<?= base_url('produk/update') ?>" id="frm_produk" method="post" autocomplete="off">
                  <div class="card-body form-master-input">
                     <input type="text" name="id_produk" id="id_produk" value="<?= $data->id_produk ?>" hidden>
                     <div class="form-group">
                        <label for="nama_produk">Nama</label>
                        <input type="text" class="form-control rounded-0" name="nama_produk" id="nama_produk" value="<?= $data->nama_produk ?>" placeholder="Nama Produk">
                        <span class="text-danger error-text nama_produk_error"></span>
                     </div>
                     <div class="form-group">
                        <label for="harga">Harga</label>
                        <!-- MENGGUNAKAN PLUGIN AUTONUMERIC -->
                        <input type="text" class="form-control rounded-0 rp" name="harga" id="harga" value="<?= $data->harga ?>">
                        <span class="text-danger error-text harga_error"></span>
                     </div>
                     <div class="form-group">
                        <label for="kategori">Kategori</label>
                        <input type="text" class="form-control rounded-0" name="kategori" id="kategori" value="<?= $data->kategori ?>" placeholder="Katergori">
                        <span class="text-danger error-text kategori_error"></span>
                     </div>
                     <div class="form-group">
                        <label for="status">Status</label>
                        <!-- MENGGUNAKAN PLUGIN SELECT2 -->
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
   // AGAR TIDAK SUBMIT SAAT TEKAN TOMBOL ENTER
   $(document).on('keyup keypress', 'form input[type="text"]', function(e) {
      if (e.keyCode == 13) {
         e.preventDefault();
         return false;
      }
   });

   $(document).ready(function() {

      // SETTING INPUT ANGKA
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
      ////

      // SETTING SELECT2
      $('.status').select2({
         theme: 'bootstrap4',
         minimumResultsForSearch: Infinity,
         placeholder: 'Pilih Status',
      })

      $(".status").val("<?= $data->status ?>")
      $(".status").trigger("change");
      ////

   });

   // UNTUK MENGATASI DOBEL SUBMIT DAN UNTUK FORM VALIDASI AJAX
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
```

## Mengatur Helper hapus()
```php
<?php

if (!function_exists('btn_del')) {
    function btn_del($x)
    {
        // return '<button type="button" class="dropdown-item del" data-del="' . $x . '"><i class="fa fa-trash"></i> Delete</button>';
        return '<a class="dropdown-item btn-sm del" href="#" data-del="' . $x . '">
                    <i class="fa fa-trash mr-1"></i> Hapus
                </a>';
    }
};

if (!function_exists('hapus')) {
    function hapus($url)
    {
        $x = "
        <div class='modal fade bd-example-modal-sm' id='del_data' tabindex='-1' role='dialog' aria-hidden='true'>
            <div class='modal-dialog modal-dialog-centered modal-sm' role='document'>
                <div class='modal-content'>
                    <div class='modal-body'>
                        <div style='text-align: center;padding: 20px;'>
                            <i class='fas fa-exclamation-triangle fa-5x' style='color:#d52a1a;'></i>
                        </div>
                        <h5 style='text-align: center;' class='font-weight-bold mb-0'>Anda Yakin</h5>
                        <p style='text-align: center;' class='mb-0 font-weight-bold'>Menghapus Data Ini !!</p>
                    </div>
                    <div class='modal-footer' style='padding: 10px;'>
                        <button type='button' class='btn btn-primary btn-sm batal' data-dismiss='modal'>Cencel</button>
                        <form class='form-inline hapus' style='margin: 0px;' method='post' action='" . base_url($url . '/delete') . "'>
                            <button type='submit' id='idx' name='idx' value=''  class='btn btn-danger btn-sm'>Delete</button>
                        </form>
                        <button type='button' class='btn btn-danger btn-sm hapus-m'>Delete</button>
                    </div>
                </div>
            </div>
        </div>
        <script>
        $(document).ready(function() {
            $('body').on('click', '.del', function() {
                let x = $(this).data('del');
                $('.hapus').show();
                $('.hapus-m').hide();
                $('#idx').val(x);
                $('#del_data').modal('toggle');
            });
            $('#del_data').on('shown.bs.modal', function() {
                $('.batal').trigger('focus')
            });
        });
        </script>";
        return $x;
    }
}
```



