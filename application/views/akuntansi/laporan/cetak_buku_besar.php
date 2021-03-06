<!DOCTYPE>
<html>
	<head>
		<title>Buku Besar</title>
		<style type="text/css">
		@page{
			size:landscape;
		}
		.border {
		    border-collapse: collapse;
		}

		.border td,
		.border th{
		    border: 1px solid black;
		}
		</style>
		<script type="text/javascript">
		//window.print();
		</script>
	</head>
	<body style="font-family:arial;margin:20px 20px 20px 20px;">
		<div align="center" style="font-weight:bold">
			UNIVERSITAS DIPONEGORO<br/>
			BUKU BESAR<br/>
			<?php echo $periode_text; ?>
		</div>
		<?php
		$baris = 4;
		$item = 1;
		$total_data = 4;//count($query); 
		$break = 'on';
		foreach ($query as $key=>$entry){
			if(($baris>=30) AND ($item==$total_data)){
				echo '<div style="margin-bottom:1800px"></div>';
			}
			echo '<table style="font-size:10pt;">
					<tr>
						<td width="140px" style="font-weight:bold;">Unit Kerja</td>
						<td>'.$teks_unit.'</td>
					</tr>
					<tr>
						<td style="font-weight:bold;">Tahun Anggaran</td>
						<td>'.$teks_tahun_anggaran.'</td>
					</tr>
					<tr>
						<td style="font-weight:bold;">Kode Akun</td>
						<td>'.$key.'</td>
					</tr>
					<tr>
						<td style="font-weight:bold;">Nama Akun</td>
						<td>'.get_nama_akun_v((string)$key).'</td>
					</tr>
				</table>';
			$baris += 4;
			$saldo = $this->Akun_model->get_saldo_awal($key);
	    	$jumlah_debet = 0;
	    	$jumlah_kredit = 0;

	    	echo '<table style="font-size:10pt;width:1000px;border:1px solid #bdbdbd;margin-bottom:20px;" class="border">
	    			<thead>
	    				<tr style="background-color:#ECF379">
	    					<th>No</th>
	    					<th>Tanggal</th>
	    					<th>No. Bukti</th>
	    					<th>Uraian</th>
	    					<th>Ref</th>
	    					<th>Debet<br/>(Rp)</th>
	    					<th>Kredit<br/>(Rp)</th>
	    					<th>Saldo<br/>(Rp)</th>
	    				</tr>
	    			</thead>
	    			<tbody>';
	    			echo '<tr>
    					<td>1</td>
    					<td>1 Januari 2017</td>
    					<td></td>
    					<td>Saldo Awal</td>
    					<td></td>
    					<td></td>
    					<td></td>
    					<td align="right">'.number_format($saldo).'</td>
    				</tr>';
    				$baris += 2;
    		$iter = 1;
	    	foreach ($entry as $transaksi) {	
	    		$iter++;
				echo '<tr>
					<td>'.$iter.'</td>
					<td>'.$transaksi['tanggal'].'</td>
					<td>'.$transaksi['no_bukti'].'</td>
					<td>'.$transaksi['uraian'].'</td>
					<td>'.$transaksi['kode_user'].'</td>';
					if ($transaksi['tipe'] == 'debet'){
	    				echo '<td align="right">'.$transaksi['jumlah'].'</td>';
	    				echo '<td align="right">0</td>';
	    				$saldo += $transaksi['jumlah'];
	    				$jumlah_debet += $transaksi['jumlah'];
	    			} else if ($transaksi['tipe'] == 'kredit'){
	    				echo '<td align="right">0</td>';
						echo '<td align="right">'.$transaksi['jumlah'].'</td>';
						$saldo -= $transaksi['jumlah'];
						$jumlah_kredit += $transaksi['jumlah'];
	    			}
				echo '<td align="right">'.number_format($saldo).'</td>
				</tr>';
				$baris+=1;

				if($total_data==1 AND $baris>=30 AND $break=='on'){
					$break = 'off';
					echo '</tbody></table><div style="margin-bottom:1800px"></div><table style="font-size:10pt;width:1000px;border:1px solid #bdbdbd;margin-bottom:20px;" class="border">
	    			<thead>
	    				<tr style="background-color:#ECF379">
	    					<th>No</th>
	    					<th>Tanggal</th>
	    					<th>No. Bukti</th>
	    					<th>Uraian</th>
	    					<th>Ref</th>
	    					<th>Debet<br/>(Rp)</th>
	    					<th>Kredit<br/>(Rp)</th>
	    					<th>Saldo<br/>(Rp)</th>
	    				</tr>
	    			</thead>
	    			<tbody>';
				}
    		}
    		echo '</tbody>
    			<tfoot>
    				<tr>
    					<td align="right" colspan="5" style="background-color:#B1E9F2">Jumlah Total</td>
    					<td align="right">'.number_format($jumlah_debet).'</td>
    					<td align="right">'.number_format($jumlah_kredit).'</td>
    					<td align="right">'.number_format($saldo).'</td>
    				</tr>
    			</tfoot>
    			</table>';
    		$baris+=1;
		$item++;
		}
		?>
	</body>
	<div align="right" style="width:1000px">
		<div style="width:200px" align="left">
			<?php 
			echo 'Semarang, '.date("d-m-Y", strtotime($periode_akhir)).'<br/>Pengguna Anggaran<br/>';
			echo get_nama_unit($unit).'<br/><br/><br/><br/>';
			$pejabat = get_pejabat($unit, 'kpa'); 
			echo $pejabat['nama'].'<br/>NIP. '.$pejabat['nip'];
			?>
		</div>
	</div>
</html>
<?php
function get_nama_unit($kode_unit)
{
	$ci =& get_instance();
	$ci->db2 = $ci->load->database('rba', true);
    $hasil = $ci->db2->where('kode_unit',$kode_unit)->get('unit')->row_array();
    if ($hasil == null) {
        return '-';
    }
    return $hasil['nama_unit'];

}

function get_nama_akun_v($kode_akun){
	$ci =& get_instance();
	if (isset($kode_akun)){
		if (substr($kode_akun,0,1) == 5){
			return $ci->db->get_where('akun_belanja',array('kode_akun' => $kode_akun))->row_array()['nama_akun'];
		} else if (substr($kode_akun,0,1) == 7){
			$kode_akun[0] = 5;
			$nama = $ci->db->get_where('akun_belanja',array('kode_akun' => $kode_akun))->row_array()['nama_akun'];
			$uraian_akun = explode(' ', $nama);
			if(isset($uraian_akun[2])){
	            if($uraian_akun[2]!='beban'){
	              $uraian_akun[2] = 'beban';
	            }
	        }
            $hasil_uraian = implode(' ', $uraian_akun);
            return $hasil_uraian;
		} else if (substr($kode_akun,0,1) == 6 or substr($kode_akun,0,1) == 4){
			$kode_akun[0] = 4;
			$hasil =  $ci->db->get_where('akuntansi_lra_6',array('akun_6' => $kode_akun))->row_array()['nama'];
			if ($hasil == null) {
				$hasil = $ci->db->get_where('akuntansi_pajak',array('kode_akun' => $kode_akun))->row_array()['nama_akun'];
			}
			return $hasil;
		} else if (substr($kode_akun,0,1) == 9){
			return 'SAL';
		} else if (substr($kode_akun,0,1) == 1){
			$hasil = $ci->db->get_where('akuntansi_kas_rekening',array('kode_rekening' => $kode_akun))->row_array()['uraian'];
			if ($hasil == null){
				$hasil = $ci->db->get_where('akun_kas6',array('kd_kas_6' => $kode_akun))->row_array()['nm_kas_6'];
			}
			if ($hasil == null){
				$hasil = $ci->db->get_where('akuntansi_aset_6',array('akun_6' => $kode_akun))->row_array()['nama'];
			}
			return $hasil;
		} else {
			return 'Nama tidak ditemukan';
		}
	}
	
}

function get_saldo_awal($kode_akun){
	return 1000000000;
}

function get_pejabat($unit, $jabatan){
	$ci =& get_instance();
	$ci->db->where('unit', $unit);
	$ci->db->where('jabatan', $jabatan);
	return $ci->db->get('akuntansi_pejabat')->row_array();
}
?>