<?php

class Produk_model extends CI_model
{
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
    // end datatables

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
