<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Jurnal_rsa_model extends CI_Model {
	
    function __construct()
    {
        parent::__construct();
        $this->load->database('default', TRUE);
        $this->db2 = $this->load->database('rba',TRUE);
    }

    public function get_kuitansi($id_kuitansi)
    {
    	$hasil = $this->db->get_where('rsa_kuitansi',array('id_kuitansi'=>$id_kuitansi))->row_array();
    	$hasil['unit_kerja'] = $this->db2->get_where('unit',array('kode_unit'=>$hasil['kode_unit']))->row_array()['nama_unit'];
    	$hasil['tanggal'] = $this->reKonversiTanggal(date('Y-m-d', strtotime($hasil['tgl_kuitansi'])));
    	$hasil['akun_debet_kas'] = $hasil['kode_akun'] . " - ". $this->db->get_where('akun_belanja',array('kode_akun'=>$hasil['kode_akun']))->row_array()['nama_akun'];

    	$query = "SELECT SUM(rsa.rsa_kuitansi_detail.volume*rsa.rsa_kuitansi_detail.harga_satuan) AS pengeluaran FROM rsa_kuitansi,rsa_kuitansi_detail WHERE rsa_kuitansi.id_kuitansi = rsa_kuitansi_detail.id_kuitansi AND rsa_kuitansi.id_kuitansi=$id_kuitansi GROUP BY rsa.rsa_kuitansi.id_kuitansi";
    	$hasil['pengeluaran'] = number_format($this->db->query($query)->row_array()['pengeluaran'],2,',','.');

    	return $hasil;
    }


    public function reKonversiTanggal($value = null)
	{
		if ($value == null){
			return "";
		}
		$perkata = explode('-', $value);
		$daftar['01'] = 'Januari';
		$daftar['02'] = 'Februari';
		$daftar['03'] = 'Maret';
		$daftar['04'] = 'April';
		$daftar['05'] = 'Mei';
		$daftar['06'] = 'Juni';
		$daftar['07'] = 'Juli';
		$daftar['08'] = 'Agustus';
		$daftar['09'] = 'September';
		$daftar['10'] = 'Oktober';
		$daftar['11'] = 'November';
		$daftar['12'] = 'Desember';
		return $perkata[2]." ".$daftar[$perkata[1]]." ".$perkata[0];
	}

	
	function get_data_kuitansi($id_kuitansi){
        $str = "SELECT rsa.rsa_kuitansi.tgl_kuitansi,rsa.rsa_kuitansi.tahun,rsa.rsa_kuitansi.no_bukti,"
                . "rba.akun_belanja.nama_akun,"
                . "SUM(rsa.rsa_kuitansi_detail.volume*rsa.rsa_kuitansi_detail.harga_satuan) AS pengeluaran,"
                . "rsa.rsa_kuitansi.uraian,rba.subkomponen_input.nama_subkomponen,"
                . "rsa.rsa_kuitansi.penerima_uang,rsa.rsa_kuitansi.penerima_uang_nip,rsa.rsa_kuitansi.penerima_barang,rsa.rsa_kuitansi.penerima_barang_nip,"
                . "rsa.rsa_kuitansi.nmpppk,rsa.rsa_kuitansi.nippppk,rsa.rsa_kuitansi.nmbendahara,rsa.rsa_kuitansi.nipbendahara,rsa.rsa_kuitansi.nmpumk,rsa.rsa_kuitansi.nippumk,rsa.rsa_kuitansi.penerima_uang,rsa.rsa_kuitansi.aktif,rsa.rsa_kuitansi.str_nomor_trx "
                . "FROM rsa.rsa_kuitansi "
                . "JOIN rsa.rsa_kuitansi_detail "
                . "ON rsa.rsa_kuitansi_detail.id_kuitansi = rsa.rsa_kuitansi.id_kuitansi "
                . "JOIN rba.akun_belanja ON rba.akun_belanja.kode_akun5digit = rsa.rsa_kuitansi.kode_akun5digit "
                . "AND rba.akun_belanja.kode_akun = rsa.rsa_kuitansi.kode_akun "
                . "AND rba.akun_belanja.sumber_dana = rsa.rsa_kuitansi.sumber_dana "
                . "JOIN rba.subkomponen_input ON rba.subkomponen_input.kode_kegiatan = SUBSTR(rsa.rsa_kuitansi.kode_usulan_belanja,7,2) "
                . "AND rba.subkomponen_input.kode_output = SUBSTR(rsa.rsa_kuitansi.kode_usulan_belanja,9,2) "
                . "AND rba.subkomponen_input.kode_program = SUBSTR(rsa.rsa_kuitansi.kode_usulan_belanja,11,2) "
                . "AND rba.subkomponen_input.kode_komponen = SUBSTR(rsa.rsa_kuitansi.kode_usulan_belanja,13,2) "
                . "AND rba.subkomponen_input.kode_subkomponen = SUBSTR(rsa.rsa_kuitansi.kode_usulan_belanja,15,2) "
                . "LEFT JOIN rsa.rsa_kuitansi_detail_pajak "
                . "ON rsa.rsa_kuitansi_detail_pajak.id_kuitansi_detail = rsa.rsa_kuitansi_detail.id_kuitansi_detail "
                . "WHERE rsa.rsa_kuitansi.id_kuitansi = '{$id_kuitansi}' "
                . "GROUP BY rsa.rsa_kuitansi.id_kuitansi";

//            var_dump($str);die;

            $q = $this->db->query($str);
    //            var_dump($q->num_rows());die;
                if($q->num_rows() > 0){
//                    var_dump($q->row());die;
                   return $q->row();
                }else{
                    return '';
                }
    }

    function get_data_detail_kuitansi($id_kuitansi){
        $str = "SELECT rsa.rsa_kuitansi_detail.id_kuitansi_detail,rsa.rsa_kuitansi_detail.deskripsi,rsa.rsa_kuitansi_detail.volume,"
                . "rsa.rsa_kuitansi_detail.satuan,rsa.rsa_kuitansi_detail.harga_satuan,(rsa.rsa_kuitansi_detail.volume * rsa.rsa_kuitansi_detail.harga_satuan) AS bruto "
                . "" //,GROUP_CONCAT(rsa_kuitansi_detail_pajak.jenis_pajak SEPARATOR '<br>') AS pajak_nom "
                . "FROM rsa.rsa_kuitansi "
                . "JOIN rsa.rsa_kuitansi_detail "
                . "ON rsa.rsa_kuitansi_detail.id_kuitansi = rsa.rsa_kuitansi.id_kuitansi "
                . "LEFT JOIN rsa.rsa_kuitansi_detail_pajak "
                . "ON rsa.rsa_kuitansi_detail_pajak.id_kuitansi_detail = rsa.rsa_kuitansi_detail.id_kuitansi_detail "
                . "WHERE rsa.rsa_kuitansi.id_kuitansi = '{$id_kuitansi}' "
                . "GROUP BY rsa.rsa_kuitansi_detail.id_kuitansi_detail";

//            var_dump($str);die;

            $q = $this->db->query($str);
    //            var_dump($q->num_rows());die;
                if($q->num_rows() > 0){

                   return $q->result();
                }else{
                    return '';
                }
    }

    function get_data_detail_pajak_kuitansi($id_kuitansi){
        $str = "SELECT rsa.rsa_kuitansi_detail_pajak.id_kuitansi_detail,rsa.rsa_kuitansi_detail_pajak.jenis_pajak,rsa.rsa_kuitansi_detail_pajak.persen_pajak,"
                . "rsa.rsa_kuitansi_detail_pajak.dpp,rsa.rsa_kuitansi_detail_pajak.rupiah_pajak "
                . "FROM rsa.rsa_kuitansi "
                . "JOIN rsa.rsa_kuitansi_detail "
                . "ON rsa.rsa_kuitansi_detail.id_kuitansi = rsa.rsa_kuitansi.id_kuitansi "
                . "JOIN rsa.rsa_kuitansi_detail_pajak "
                . "ON rsa.rsa_kuitansi_detail_pajak.id_kuitansi_detail = rsa.rsa_kuitansi_detail.id_kuitansi_detail "
                . "WHERE rsa.rsa_kuitansi.id_kuitansi = '{$id_kuitansi}' "
                . "GROUP BY rsa.rsa_kuitansi_detail_pajak.id_kuitansi_detail_pajak";

//            var_dump($str);die;

            $q = $this->db->query($str);
    //            var_dump($q->num_rows());die;
                if($q->num_rows() > 0){
                   return $q->result();
                }else{
                    return '';
                }
    }
}