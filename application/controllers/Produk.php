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

   // UNTUK CEK VALIDASI MELALUI AJAX
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
