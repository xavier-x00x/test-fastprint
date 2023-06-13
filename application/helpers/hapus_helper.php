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
