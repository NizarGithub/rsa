<!-- javascript -->
<script type="text/javascript">
	$(document).ready(function(){
		var host = location.protocol + '//' + location.host + '/sisrenbang/index.php/';

		$("#filter_tahun").change(function(){
			$("#form_filter_tahun").submit();
		});
		$("#filter_status").change(function(){
			$("#form_filter").submit();
		});
	});
</script>
<!-- javascript -->

<div class="row">
	<ol class="breadcrumb">
		<li><a href="#"><svg class="glyph stroked home"><use xlink:href="#stroked-home"></use></svg></a></li>
		<li class="active">SPM Non-Kuitansi</li>
	</ol>
</div><!--/.row-->
<hr/>
<ul class="nav nav-tabs">
  <li role="presentation" class="<?php if(isset($tab1)){ if($tab1==true) echo 'active'; } ?>"><a href="<?php echo site_url('akuntansi/kuitansi/jadi'); ?>">GUP</a></li>
  <li role="presentation" class="<?php if(isset($tab2)){ if($tab2==true) echo 'active'; } ?>"><a href="<?php echo site_url('akuntansi/kuitansi/jadi_ls'); ?>">L3</a></li>
  <li role="presentation" class="<?php if(isset($tab3)){ if($tab3==true) echo 'active'; } ?>"><a href="<?php echo site_url('akuntansi/kuitansi/jadi_spm'); ?>">SPM non-kuitansi</a></li>
</ul>
<div class="row">
	<div class="col-sm-9">
		<h1 class="page-header">SPM Non-Kuitansi</h1>
	</div>
	<div class="col-sm-3" align="right">
	</div>
</div><!--/.row-->
<div class="row">
	<div class="col-sm-4">
		<form action="<?php echo site_url('akuntansi/kuitansi/jadi_spm'); ?>" method="post">
			<div class="input-group">
				<span class="input-group-btn">
	        		<a href="<?php echo site_url('akuntansi/kuitansi/reset_search_jadi_spm'); ?>"><button class="btn btn-danger" type="button"><span class="glyphicon glyphicon-refresh"></span> Reset</button></a>
	      		</span>
	      		<input type="text" class="form-control" placeholder="No.SPM/Uraian" name="keyword_spm_jadi" value="<?php if($this->session->userdata('keyword_spm_jadi')) echo $this->session->userdata('keyword_spm_jadi'); ?>">
	      		<span class="input-group-btn">
	        		<button class="btn btn-default" type="submit">Cari</button>
	      		</span>
	    	</div>
	    </form>
	</div>
</div>
<br/>
<div class="row">
	<div class="col-sm-12">
		<table class="table table-striped" style="table-layout:fixed">
			<thead>
				<tr>
					<th width="5%">NO</th>
					<th>TANGGAL</th>
					<th>NO.SPM</th>
					<th width="20%">KODE KEGIATAN</th>
					<th>UNIT</th>
					<th>AKUN DEBET</th>
					<th>AKUN KREDIT</th>
					<th>JUMLAH</th>
					<th>AKSI</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach($query->result() as $result){ ?>
				<tr>
					<td><?php echo $no; ?></td>
					<td><?php echo date("d/m/Y", strtotime($result->tanggal)); ?></td>
					<td><?php echo $result->nomor; ?></td>
					<td><?php echo str_replace(',', '<br/>,', $result->detail_belanja); ?></td>
					<td>-</td>
					<td><?php echo substr($result->detail_belanja, 18, 6); ?></td>
					<td><?php echo '-'; ?></td>
					<td><?php echo number_format($result->jumlah_bayar); ?></td>
					<td>						
							<a href="#"><button type="button" class="btn btn-sm btn-primary">Jurnal</button></a>
						<?php if($this->session->userdata('level')==1){ ?>
							<a href="<?php echo site_url('akuntansi/jurnal_rsa/input_jurnal/'.$result->id_spmls); ?>"><button type="button" class="btn btn-sm btn-danger">Isi Kesetaraan</button></a>
						<?php }else if($this->session->userdata('level')==2){ ?>
							<a href="#"><button type="button" class="btn btn-sm btn-warning">Verifikasi</button></a>
						<?php }else if($this->session->userdata('level')==3){ ?>
							<a href="#"><button type="button" class="btn btn-sm btn-success">Posting</button></a>
						<?php } ?>
					</td>
				</tr>
				<?php $no++; } ?>
			</tbody>
		</table>
		<center><?php echo $halaman; ?></center>
	</div>
</div>

<?php
function get_pengeluaran($id_kuitansi){
	$ci =& get_instance();

	$query = "SELECT SUM(volume*harga_satuan) AS pengeluaran FROM rsa_kuitansi_detail WHERE id_kuitansi='$id_kuitansi'";
	$q = $ci->db->query($query)->result();
	foreach($q as $result){
		return number_format($result->pengeluaran);
	}
}
?>