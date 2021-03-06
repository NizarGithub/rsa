<?php
ini_set('display_errors', 1);
defined('BASEPATH') OR exit('No direct script access allowed');

class Laporan extends MY_Controller {
	public function __construct(){
        parent::__construct();
        $this->data['menu9'] = true;
        $this->cek_session_in();
        $this->cek_session_in();
        $this->load->model('akuntansi/Laporan_model', 'Laporan_model');
        $this->load->model('akuntansi/Akun_model', 'Akun_model');
        $this->load->model('akuntansi/Output_model', 'Output_model');
        $this->load->model('akuntansi/Unit_kerja_model', 'Unit_kerja_model');
        $this->load->model('akuntansi/Jurnal_rsa_model', 'Jurnal_rsa_model');
        $this->load->model('akuntansi/Pajak_model', 'Pajak_model');
        $this->load->model('akuntansi/Pejabat_model', 'Pejabat_model');        
        $this->data['db2'] = $this->load->database('rba',TRUE);
        setlocale(LC_NUMERIC, 'Indonesian');

        $this->load->library('excel');
    }

    public function buku_besar($id = 0){
        $this->data['tab1'] = true;

//      $this->data['query_debet'] = $this->Laporan_model->read_buku_besar_group('akun_debet');
//      $this->data['query_debet_akrual'] = $this->Laporan_model->read_buku_besar_group('akun_debet_akrual');
//      $this->data['query_kredit'] = $this->Laporan_model->read_buku_besar_group('akun_kredit');
//      $this->data['query_kredit_akrual'] = $this->Laporan_model->read_buku_besar_group('akun_kredit_akrual');
        $this->db2 = $this->load->database('rba', true);
        $this->load->model('akuntansi/Memorial_model', 'Memorial_model');
        $this->data['query_unit'] = $this->db2->query("SELECT * FROM unit");
        $this->data['query_akun_kas'] = $this->get_akun_kas();
        $this->data['query_akun_akrual'] = $this->get_akun_akrual();

        $temp_data['content'] = $this->load->view('akuntansi/buku_besar_list',$this->data,true);
        $this->load->view('akuntansi/content_template',$temp_data,false);
    }

    public function rekap_jurnal($id = 0){
        $this->data['tab1'] = true;

//      $this->data['query_debet'] = $this->Laporan_model->read_buku_besar_group('akun_debet');
//      $this->data['query_debet_akrual'] = $this->Laporan_model->read_buku_besar_group('akun_debet_akrual');
//      $this->data['query_kredit'] = $this->Laporan_model->read_buku_besar_group('akun_kredit');
//      $this->data['query_kredit_akrual'] = $this->Laporan_model->read_buku_besar_group('akun_kredit_akrual');
        $this->db2 = $this->load->database('rba', true);
        $this->load->model('akuntansi/Memorial_model', 'Memorial_model');
        $this->data['query_unit'] = $this->db2->query("SELECT * FROM unit");
        $this->data['query_akun_kas'] = $this->get_akun_kas();
        $this->data['query_akun_akrual'] = $this->get_akun_akrual();

        $temp_data['content'] = $this->load->view('akuntansi/rekap_jurnal_list',$this->data,true);
        $this->load->view('akuntansi/content_template',$temp_data,false);
    }

	public function neraca_saldo($id = 0){
		$this->data['tab1'] = true;

//		$this->data['query_debet'] = $this->Laporan_model->read_buku_besar_group('akun_debet');
//		$this->data['query_debet_akrual'] = $this->Laporan_model->read_buku_besar_group('akun_debet_akrual');
//		$this->data['query_kredit'] = $this->Laporan_model->read_buku_besar_group('akun_kredit');
//		$this->data['query_kredit_akrual'] = $this->Laporan_model->read_buku_besar_group('akun_kredit_akrual');
        $this->db2 = $this->load->database('rba', true);
        $this->load->model('akuntansi/Memorial_model', 'Memorial_model');
        $this->data['query_unit'] = $this->db2->query("SELECT * FROM unit");
        $this->data['query_akun_kas'] = $this->get_akun_kas();
        $this->data['query_akun_akrual'] = $this->get_akun_akrual();

		$temp_data['content'] = $this->load->view('akuntansi/neraca_list',$this->data,true);
		$this->load->view('akuntansi/content_template',$temp_data,false);
	}
    
    public function get_akun_kas($get_json=null){
        $this->load->model('akuntansi/Memorial_model', 'Memorial_model');
        $query_1 = $this->Memorial_model->read_akun('akuntansi_aset_6');
        $query_2 = $this->Memorial_model->read_akun('akuntansi_hutang_6');
        $query_3 = $this->Memorial_model->read_akun('akuntansi_aset_bersih_6');
        $query_4 = $this->Memorial_model->read_akun('akuntansi_lra_6');
        $query_5 = $this->Memorial_model->read_akun_rba('akun_belanja');
        $query_6 = $this->Memorial_model->read_akun('akuntansi_pembiayaan_6');
        $query_7 = $this->Pajak_model->get_pajak();
        $query_8 = $this->Memorial_model->read_akun('akuntansi_sal_6');

        $i = 0;
        foreach($query_1->result() as $result){
            if($i==0){
                $data[$i]['akun_6'] = 'all';
                $data[$i]['nama'] = 'Semua Akun';
            }else{
                $data[$i]['akun_6'] = $result->akun_6;
                $data[$i]['nama'] = $result->nama;
            }
            $i++;
        }
        foreach($query_2->result() as $result){
            $data[$i]['akun_6'] = $result->akun_6;
            $data[$i]['nama'] = $result->nama;
            $i++;
        }
        foreach($query_3->result() as $result){
            $data[$i]['akun_6'] = $result->akun_6;
            $data[$i]['nama'] = $result->nama;
            $i++;
        }
        foreach($query_4->result() as $result){
            $data[$i]['akun_6'] = $result->akun_6;
            $data[$i]['nama'] = $result->nama;
            $i++;
        }
        foreach($query_5->result() as $result){
            $data[$i]['akun_6'] = $result->kode_akun;
            $data[$i]['nama'] = $result->nama_akun;
            $i++;
        }
        foreach($query_6->result() as $result){
            $data[$i]['akun_6'] = $result->akun_6;
            $data[$i]['nama'] = $result->nama;
            $i++;
        }
        foreach($query_7->result() as $result){
            $data[$i]['akun_6'] = $result->kode_akun;
            $data[$i]['nama'] = $result->nama_akun;
            $i++;
        }
        foreach($query_8->result() as $result){
            $data[$i]['akun_6'] = $result->akun_6;
            $data[$i]['nama'] = $result->nama;
            $i++;
        }

        if($get_json){
            $json_data['hasil'] = $data;

            header('Content-Type: application/json');
            echo json_encode($json_data);
        } else return $data;
    }

    public function get_pajak(){
        $akun_pajak = $this->Pajak_model->get_pajak();
        echo '<select name="akun[]" class="form-control">';
        foreach($akun_pajak->result() as $result){
            echo '<option value="'.$result->kode_akun.'">'.$result->kode_akun.' - '.$result->nama_akun.'</option>';
        }
        echo '</select>';
    }

    public function get_akun_akrual($get_json = null){
        $this->load->model('akuntansi/Memorial_model', 'Memorial_model');
        $query_1 = $this->Memorial_model->read_akun('akuntansi_aset_6');
        $query_2 = $this->Memorial_model->read_akun('akuntansi_hutang_6');
        $query_3 = $this->Memorial_model->read_akun('akuntansi_aset_bersih_6');
        $query_4 = $this->Memorial_model->read_akun('akuntansi_lra_6');
        $query_5 = $this->Memorial_model->read_akun_rba('akun_belanja');
        $query_6 = $this->Memorial_model->read_akun('akuntansi_pembiayaan_6');
        $query_7 = $this->Pajak_model->get_pajak();
        $query_8 = $this->Memorial_model->read_akun('akuntansi_sal_6');

        $i = 0;
        foreach($query_1->result() as $result){
            if($i==0){
                $data[$i]['akun_6'] = 'all';
                $data[$i]['nama'] = 'Semua Akun';
            }else{
                $data[$i]['akun_6'] = $result->akun_6;
                $data[$i]['nama'] = $result->nama;
            }
            $i++;
        }
        foreach($query_2->result() as $result){
            $data[$i]['akun_6'] = $result->akun_6;
            $data[$i]['nama'] = $result->nama;
            $i++;
        }
        foreach($query_3->result() as $result){
            $data[$i]['akun_6'] = $result->akun_6;
            $data[$i]['nama'] = $result->nama;
            $i++;
        }
        foreach($query_4->result() as $result){
            $result->akun_6[0] = '6';
            $data[$i]['akun_6'] = $result->akun_6;
            $data[$i]['nama'] = $result->nama;
            $i++;
        }
        foreach($query_5->result() as $result){
            $result->kode_akun[0] = '7';
            $data[$i]['akun_6'] = $result->kode_akun;
            $data[$i]['nama'] = $result->nama_akun;
            $i++;
        }
        foreach($query_6->result() as $result){
            $data[$i]['akun_6'] = $result->akun_6;
            $data[$i]['nama'] = $result->nama;
            $i++;
        }
        foreach($query_7->result() as $result){
            $data[$i]['akun_6'] = $result->kode_akun;
            $data[$i]['nama'] = $result->nama_akun;
            $i++;
        }
        foreach($query_8->result() as $result){
            $data[$i]['akun_6'] = $result->akun_6;
            $data[$i]['nama'] = $result->nama;
            $i++;
        }
        
        if($get_json){
            $json_data['hasil'] = $data;

            header('Content-Type: application/json');
            echo json_encode($json_data);
        } else return $data;

        return $data;
    }

	public function coba($value='')
	{
		$akun = array(1,2,3,4,5,6,7,8,9);


		// $tabel_relasi = $this->Laporan_model->get_akun_tabel_relasi($akun);
		// $tabel_utama = $this->Laporan_model->get_akun_tabel_utama($akun);

		// print_r($tabel_relasi);

		// print_r($tabel_utama + $tabel_relasi);
		// print_r(array_merge($tabel_utama,$tabel_relasi));
		// print_r($this->Laporan_model->get_data_buku_besar($akun));

        print_r($this->Output_model->get_nama_output('121412040901010201521222'));

		
		// $this->Relasi_kuitansi_akun_model->get_relasi_kuitansi_akun()
	}

	public function get_rekap_jurnal($mode = null)
	{
        $basis = $this->input->post('basis');
        $unit = $this->input->post('unit');
        $sumber_dana = $this->input->post('sumber_dana');
        
        $daterange = $this->input->post('daterange');
        $date_t = explode(' - ', $daterange);
        $periode_awal = strtodate($date_t[0]);
        $periode_akhir = strtodate($date_t[1]);

        $teks_unit = null;

        if ($unit == 'all' or $unit == 9999) {
            $unit = null;
            $teks_unit = "UNIVERSITAS DIPONEGORO";
        } else {
            $teks_unit = $this->Unit_kerja_model->get_nama_unit($unit);
            $teks_unit = strtoupper(str_replace('Fak.', "Fakultas ", $teks_unit));
        }
        if ($sumber_dana == 'all') {
            $sumber_dana = null;
        } 

        // print_r($this->input->post());die();
		// $akun = array(1,2,3,4,5,6,7,8,9);
        //public function read_rekap_jurnal($jenis=null,$unit=null,$sumber_dana=null,$start_date=null,$end_date=null)
        $data = $this->Laporan_model->read_rekap_jurnal($basis,$unit,$sumber_dana,$periode_awal,$periode_akhir);


        // print_r($data == null);die();
        if ($data == null){
            $this->load->view('akuntansi/no_data',true);
            return 0;
        }

        // print_r($data);die();

        $n_akun = count($data);

        $path_template = realpath(FCPATH).'/assets/akuntansi/template_excel/template_jurnal_umum.xls';
        $excel = new PHPExcel_Reader_Excel5();
        $objPHPExcel = $excel->load($path_template);
        $objWorksheet = $objPHPExcel->getActiveSheet();
        $objWorksheet->setTitle('Rekap Jurnal');

        $row = $start_row = 9;

        $objPHPExcel->getActiveSheet()->insertNewRowBefore($row,2*count($data));
        foreach ($data as $entry) {
            // echo count($entry['akun']);
            $objPHPExcel->getActiveSheet()->insertNewRowBefore($row,count($entry['akun']));
        }
        // die();

        $BStyle = array(
          'borders' => array(
            'outline' => array(
              'style' => PHPExcel_Style_Border::BORDER_MEDIUM
            ),
            'top' => array(
              'style' => PHPExcel_Style_Border::BORDER_MEDIUM
            )

          )
        );

        $RowStyle = array(
          'borders' => array(
            'bottom' => array(
              'style' => PHPExcel_Style_Border::BORDER_THIN
            )
          )
        );


        $CenteredStyle = array( 'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER, );

        $teks_sumber_dana = "JURNAL UMUM ";
        $teks_periode = "";
        $teks_tahun = substr($periode_akhir,0,4);
        $teks_tahun_anggaran = "TAHUN ANGGARAN $teks_tahun";

        if ($periode_awal != null and $periode_akhir != null){
            $teks_periode .= "PER ".$this->Jurnal_rsa_model->reKonversiTanggal($periode_awal) . " - ".$this->Jurnal_rsa_model->reKonversiTanggal($periode_akhir);
        }


        if ($sumber_dana != null) {
            $teks_sumber_dana .= "DARI DANA ".strtoupper(str_replace('_',' ',$sumber_dana));
        }



        $objWorksheet->setCellValueByColumnAndRow(0,1,$teks_unit);
        $objWorksheet->setCellValueByColumnAndRow(0,2,$teks_sumber_dana);
        $objWorksheet->setCellValueByColumnAndRow(0,3,$teks_periode);
        $objWorksheet->setCellValueByColumnAndRow(0,4,$teks_tahun_anggaran);


        $iter = 1;


        $jumlah_debet = 0;
        $jumlah_kredit = 0;



        foreach ($data as $entry) {
            $transaksi = $entry['transaksi'];
            $akun = $entry['akun'];
            
            $nama_unit = $this->Unit_kerja_model->get_nama_unit($transaksi['unit_kerja']);
            $row++;

            $row_teks = $row - 1;

            $objWorksheet->getStyle("A$row_teks:F$row_teks")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

            $objWorksheet->getStyle("A$row_teks:G$row_teks")->applyFromArray(
                                                                                        array(
                                                                                            'fill' => array(
                                                                                                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                                                                                                'color' => array('rgb' => 'DCF8C6')
                                                                                            ),
                                                                                            'borders' => array(
                                                                                                'outline' => array(
                                                                                                  'style' => PHPExcel_Style_Border::BORDER_MEDIUM
                                                                                                ),
                                                                                                'top' => array(
                                                                                                  'style' => PHPExcel_Style_Border::BORDER_MEDIUM
                                                                                                )
                                                                                            )
                                                                                        )
                                                                                    );

            $objWorksheet->mergeCellsByColumnAndRow(0,$row_teks,0,$row);
            $objWorksheet->setCellValueByColumnAndRow(0,$row_teks,$iter);
            $objWorksheet->mergeCellsByColumnAndRow(1,$row_teks,5,$row);
            $objWorksheet->setCellValueByColumnAndRow(1,$row_teks,'keterangan');
            // $objWorksheet->getStyleByColumnAndRow(0,$row_teks)->applyFromArray($BStyle);
            // $objWorksheet->getStyleByColumnAndRow(1,$row_teks)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            // $objWorksheet->getStyle("A$row_teks:I$row_teks")->applyFromArray($BStyle);
            
            $objWorksheet->setCellValueByColumnAndRow(0,$row_teks,$iter);
            $objWorksheet->mergeCellsByColumnAndRow(6,$row_teks,8,$row);
            $objWorksheet->setCellValueByColumnAndRow(6,$row_teks,$nama_unit." : \n".$transaksi['uraian']);
            // $objWorksheet->getStyleByColumnAndRow(6,$row_teks)->applyFromArray($BStyle);
            // $objWorksheet->getStyle("G$row_teks:I$row_teks")->applyFromArray($BStyle);
             // $objWorksheet->getStyle("A$row_teks:G$row_teks")->applyFromArray($BStyle);


            
            // $objWorksheet->setCellValueByColumnAndRow(6,$row,$transaksi['uraian']);

            foreach ($akun as $in_akun) {
                $row++;

                $objWorksheet->getStyle('A'.$row.':I'.$row)->applyFromArray($RowStyle);
                $objWorksheet->setCellValueByColumnAndRow(1,$row,$this->Jurnal_rsa_model->reKonversiTanggal($transaksi['tanggal']));
                // $objWorksheet->getStyleByColumnAndRow(2,$row)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
                // $objWorksheet->getStyleByColumnAndRow(3,$row)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
                $objWorksheet->setCellValueExplicitByColumnAndRow(2,$row,$transaksi['no_spm'],PHPExcel_Cell_DataType::TYPE_STRING);
                $objWorksheet->setCellValueExplicitByColumnAndRow(3,$row,$transaksi['no_bukti'],PHPExcel_Cell_DataType::TYPE_STRING);
                $objWorksheet->getStyleByColumnAndRow(4,$row)->getNumberFormat()->setFormatCode('0000');
                $objWorksheet->setCellValueByColumnAndRow(4,$row,"".substr($transaksi['kode_kegiatan'],6,4));
                // echo substr($transaksi['kode_kegiatan'],6,4);die();
                $objWorksheet->setCellValueByColumnAndRow(5,$row,$in_akun['akun']);
                if ($in_akun['tipe'] == 'debet'){
                    $objWorksheet->setCellValueByColumnAndRow(7,$row,$this->eliminasi_negatif($in_akun['jumlah']));
                    $objWorksheet->setCellValueByColumnAndRow(8,$row,0);
                    $jumlah_debet += $in_akun['jumlah'];
                }elseif ($in_akun['tipe'] == 'kredit') {
                    $objWorksheet->getStyleByColumnAndRow(5,$row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                    $objWorksheet->setCellValueByColumnAndRow(8,$row,$this->eliminasi_negatif($in_akun['jumlah']));
                    $objWorksheet->setCellValueByColumnAndRow(7,$row,0);
                    $jumlah_kredit += $in_akun['jumlah'];
                }
                $objWorksheet->setCellValueByColumnAndRow(6,$row, $this->Akun_model->get_nama_akun($in_akun['akun']));

            }



            $iter++;
            $row+=1;

        }

        $objWorksheet->setCellValueByColumnAndRow(7,$row,$this->eliminasi_negatif($jumlah_debet));
        $objWorksheet->setCellValueByColumnAndRow(8,$row,$this->eliminasi_negatif($jumlah_kredit));

        if ($unit == null or $unit == 9999) {
            $kpa = $this->Pejabat_model->get_pejabat('all','rektor');
            $teks_kpa = "Rektor";
        } else {
            $kpa = $this->Pejabat_model->get_pejabat($unit,'kpa');
            $teks_kpa = "Pengguna Anggaran";
        }


        $row = $objWorksheet->getHighestRow() + 2;
        $kolom_kpa = 7;

        $objWorksheet->mergeCellsByColumnAndRow($kolom_kpa,$row,$kolom_kpa+1,$row);
        $objWorksheet->setCellValueByColumnAndRow($kolom_kpa,$row,"Semarang, ". $this->Jurnal_rsa_model->reKonversiTanggal($periode_akhir));
        $row++;
        $objWorksheet->mergeCellsByColumnAndRow($kolom_kpa,$row,$kolom_kpa+1,$row);
        $objWorksheet->setCellValueByColumnAndRow($kolom_kpa,$row,$teks_kpa);
        $row++;
        $objWorksheet->mergeCellsByColumnAndRow($kolom_kpa,$row,$kolom_kpa+1,$row);
        $objWorksheet->setCellValueByColumnAndRow($kolom_kpa,$row,$teks_unit);
        $row += 4;
        $objWorksheet->mergeCellsByColumnAndRow($kolom_kpa,$row,$kolom_kpa+2,$row);
        $objWorksheet->setCellValueByColumnAndRow($kolom_kpa,$row,$kpa['nama']);
        $row ++;
        $objWorksheet->mergeCellsByColumnAndRow($kolom_kpa,$row,$kolom_kpa+1,$row);
        $objWorksheet->setCellValueByColumnAndRow($kolom_kpa,$row,"NIP. " . $this->format_nip($kpa['nip']));



        if ($mode == 'excel'){
            $objWorksheet->getPageSetup()->setFitToPage(true);
            $objWorksheet->getPageSetup()->setFitToWidth(0);
            $objWorksheet->getPageSetup()->setFitToHeight(1);
            $objWorksheet->getPageSetup()->setRowsToRepeatAtTopByStartAndEnd(6,7);
            header("Content-type: application/vnd.ms-excel");
            header("Content-Disposition: attachment; filename=rekap_jurnal.xls");
            header('Cache-Control: max-age=0');
            // $objWriter = new PHPExcel_Writer_HTML($objPHPExcel,'excel5');  
            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
            $objWriter->save('php://output');
            exit;


        }

        $objWriter = new PHPExcel_Writer_HTML($objPHPExcel);  
        $output['data'] = $objWriter->generateHTMLHeader();
        $output['data'] .= $objWriter->generateStyles();
        $output['data'] .= $objWriter->generateSheetData();
        $output['data'] .= $objWriter->generateHTMLFooter();
        $output['teks_cetak'] = 'Print Rekap Jurnal';
        $output['sumber'] = 'get_rekap_jurnal';
        
    

        $this->load->view('akuntansi/laporan/laporan',$output);

	}

	public function get_buku_besar($mode = null)
    {
    // 	if ($tipe == 'sak'){
    // 		$akun = array(1,4);
    // 	}else if($tipe == 'lra'){
    // 		$akun = array(6,7);
    // 	}

        // print_r($this->input->post());die();

        // public function get_data_buku_besar($array_akun,$jenis=null,$unit=null,$sumber_dana=null,$start_date=null,$end_date=null)

        $akun = $this->input->post('akun')[0];
        $basis = $this->input->post('basis');
        $unit = $this->input->post('unit');
        $sumber_dana = $this->input->post('sumber_dana');
        
        $daterange = $this->input->post('daterange');
        $date_t = explode(' - ', $daterange);
        $periode_awal = strtodate($date_t[0]);
        $periode_akhir = strtodate($date_t[1]) or null;

        $mode = null;

        $teks_unit = null;


        if ($unit == 'all' or $unit == 9999) {
            $unit = null;
            // $mode = 'neraca';
            $teks_unit = "UNIVERSITAS DIPONEGORO";
        } else {
            $teks_unit = $this->Unit_kerja_model->get_nama_unit($unit);
            $teks_unit = strtoupper(str_replace('Fak.', "Fakultas ", $teks_unit));
        }


        if ($sumber_dana == 'all') {
            $sumber_dana = null;
        }

        $array_akun = array();

        if ($akun == 'all'){
            $array_akun = array(1,2,3,4,5,6,7,8,9);
            $mode = 'neraca';
        }
        else {
            $array_akun[] = $akun;
            // $mode = 'neraca';
        }

        // print_r($mode);die();


    	$data = $this->Laporan_model->get_data_buku_besar($array_akun,$basis,$unit,$sumber_dana,$periode_awal,$periode_akhir,$mode);

        if ($data == null){
            $this->load->view('akuntansi/no_data',true);
            return 0;
        }


    	$n_akun = count($data);

        $path_template = realpath(FCPATH).'/assets/akuntansi/template_excel/template_buku_besar.xls';
        $excel = new PHPExcel_Reader_Excel5();

        $objPHPExcel = $excel->load($path_template);
        $objPHPExcel->setActiveSheetIndex(0); // index of sheet
        $objWorksheet = $objPHPExcel->getActiveSheet();
        $objWorksheet->setTitle('Buku Besar');

        // $objWorksheet = $objPHPExcel->getActiveSheet();

        $row = 5;
        $height = 12;
        for ($i=0; $i < $n_akun-1; $i++) { 
    		$this->copyRows($objWorksheet,$row,$row+$height,12,8);
    		$row = $row+$height;
    	}

        $teks_sumber_dana = "BUKU BESAR ";
        $teks_periode = "";
        
        $teks_tahun = substr($periode_akhir,0,4);
        $teks_tahun_anggaran = "TAHUN ANGGARAN $teks_tahun";

        // $teks_unit = "UNIVERSITAS DIPONEGORO";

        if ($periode_awal != null and $periode_akhir != null){
            $teks_periode .= "PER ".$this->Jurnal_rsa_model->reKonversiTanggal($periode_awal) . " - ".$this->Jurnal_rsa_model->reKonversiTanggal($periode_akhir);
        }


        if ($sumber_dana != null) {
            $teks_sumber_dana .= "DARI DANA ".strtoupper(str_replace('_',' ',$sumber_dana));
        }

        $objWorksheet->setCellValueByColumnAndRow(0,2,$teks_sumber_dana);
        $objWorksheet->setCellValueByColumnAndRow(0,3,$teks_periode);


    	$row = 13;
    	$kode_row = $row-6;
    	$nama_row = $row-5;
    	foreach ($data as $key => $entry) {
    		$i = 1;
    		$next_row = 11;

            $case_hutang = in_array(substr($key,0,1),[2,3]);

	    	$kode_row = $row-6;
	    	$nama_row = $row-5;
            $tahun_row = $row-7;
            $unit_row = $row-8;

	    	$objWorksheet->setCellValueByColumnAndRow(2,$nama_row,":".$this->Akun_model->get_nama_akun((string)$key));
            $objWorksheet->setCellValueByColumnAndRow(2,$kode_row,":".$key);
            $objWorksheet->setCellValueByColumnAndRow(2,$tahun_row,":".$teks_tahun_anggaran);
	    	$objWorksheet->setCellValueByColumnAndRow(2,$unit_row,":".$teks_unit);

	    	$saldo = $this->Akun_model->get_saldo_awal($key);
            // print_r($saldo);die();
	    	$jumlah_debet = 0;
	    	$jumlah_kredit = 0;
	    	$iter = 0;

    		foreach ($entry as $transaksi) {
    			$iter++;
                if ($iter == 1 and $saldo != null) {
                    $objPHPExcel->getActiveSheet()->insertNewRowBefore($row+1,1); 
                    $objWorksheet->setCellValueByColumnAndRow(0,$row,$iter);
                    $objWorksheet->setCellValueByColumnAndRow(1,$row,'01 Januari '.$teks_tahun);
                    $objWorksheet->setCellValueByColumnAndRow(3,$row,'Saldo Awal');
                    // $objWorksheet->setCellValueByColumnAndRow(8,$row,$saldo['saldo_kredit_awal']);

                    $saldo = $saldo['saldo_awal'];

                    $objWorksheet->setCellValueByColumnAndRow(7,$row,$this->eliminasi_negatif($saldo));

                    $row++;
                    $iter++;
                }
                
    			$objPHPExcel->getActiveSheet()->insertNewRowBefore($row+1,1); 
    			$objWorksheet->setCellValueByColumnAndRow(0,$row,$iter);
                $objWorksheet->setCellValueByColumnAndRow(1,$row,$transaksi['tanggal']);
                $objWorksheet->setCellValueExplicitByColumnAndRow(2,$row,$transaksi['no_bukti'],PHPExcel_Cell_DataType::TYPE_STRING);
    			// $objWorksheet->setCellValueByColumnAndRow(2,$row,$transaksi['no_bukti']);
    			$objWorksheet->setCellValueByColumnAndRow(3,$row,$transaksi['uraian']);
    			$objWorksheet->setCellValueByColumnAndRow(4,$row,$transaksi['kode_user']);
    			if ($transaksi['tipe'] == 'debet'){
    				$objWorksheet->setCellValueByColumnAndRow(5,$row,$this->eliminasi_negatif($transaksi['jumlah']));
                    $objWorksheet->setCellValueByColumnAndRow(6,$row,0);
                    if ($case_hutang) {
                        $saldo -= $transaksi['jumlah'];
                    } else {
    				    $saldo += $transaksi['jumlah'];
                    }
    				$jumlah_debet += $transaksi['jumlah'];
    			} else if ($transaksi['tipe'] == 'kredit'){
					$objWorksheet->setCellValueByColumnAndRow(6,$row,$this->eliminasi_negatif($transaksi['jumlah']));
                    $objWorksheet->setCellValueByColumnAndRow(5,$row,0);
					if ($case_hutang) {
                        $saldo += $transaksi['jumlah'];
                    } else {
                        $saldo -= $transaksi['jumlah'];
                    }
					$jumlah_kredit += $transaksi['jumlah'];
    			}
    			$objWorksheet->setCellValueByColumnAndRow(7,$row,$this->eliminasi_negatif($saldo));
    			$next_row;
    			$row++;
    		}
    		$objWorksheet->setCellValueByColumnAndRow(5,$row+1,$this->eliminasi_negatif($jumlah_debet));
    		$objWorksheet->setCellValueByColumnAndRow(6,$row+1,$this->eliminasi_negatif($jumlah_kredit));
    		$objWorksheet->setCellValueByColumnAndRow(7,$row+1,$this->eliminasi_negatif($saldo));

    		$row = $row + $next_row + $i;

    		$i++;
    		// $objWorksheet->setCellValueByColumnAndRow(2,$i+$row,$i+1);
    	}

        // ============================================

        // ============================================


        if ($unit == null or $unit == 9999) {
            $kpa = $this->Pejabat_model->get_pejabat('all','rektor');
            $teks_kpa = "Rektor";
        } else {
            $kpa = $this->Pejabat_model->get_pejabat($unit,'kpa');
            $teks_kpa = "Pengguna Anggaran";
        }

        $row = $objWorksheet->getHighestRow() + 2;
        $kolom_kpa = 6;



        $objWorksheet->mergeCellsByColumnAndRow($kolom_kpa,$row,$kolom_kpa+1,$row);
        $objWorksheet->setCellValueByColumnAndRow($kolom_kpa,$row,"Semarang, ". $this->Jurnal_rsa_model->reKonversiTanggal($periode_akhir));
        $row++;
        $objWorksheet->mergeCellsByColumnAndRow($kolom_kpa,$row,$kolom_kpa+1,$row);
        $objWorksheet->setCellValueByColumnAndRow($kolom_kpa,$row,$teks_kpa);
        $row++;
        $objWorksheet->mergeCellsByColumnAndRow($kolom_kpa,$row,$kolom_kpa+1,$row);
        $objWorksheet->setCellValueByColumnAndRow($kolom_kpa,$row,$teks_unit);
        $row += 4;
        $objWorksheet->mergeCellsByColumnAndRow($kolom_kpa,$row,$kolom_kpa+2,$row);
        $objWorksheet->setCellValueByColumnAndRow($kolom_kpa,$row,$kpa['nama']);
        $row ++;
        $objWorksheet->mergeCellsByColumnAndRow($kolom_kpa,$row,$kolom_kpa+1,$row);
        $objWorksheet->setCellValueByColumnAndRow($kolom_kpa,$row,"NIP. " . $this->format_nip($kpa['nip']));


        // $penyusun = $this->Pejabat_model->get_pejabat($unit,'operator');
        // $ppk = $this->Pejabat_model->get_pejabat($unit,'ppk');

        // $row = $objWorksheet->getHighestRow() + 2;
        // $kolom_penyusun = 2;
        // $kolom_ppk = 7;

        // $objWorksheet->setCellValueByColumnAndRow($kolom_ppk,$row,"Semarang, ". $this->Jurnal_rsa_model->reKonversiTanggal($periode_akhir));
        // $row++;
        // $objWorksheet->setCellValueByColumnAndRow($kolom_penyusun,$row,"Penyusun Laporan");
        // $objWorksheet->setCellValueByColumnAndRow($kolom_ppk,$row,"Pejabat Pembuat Komitmen");
        // $row++;
        // $objWorksheet->setCellValueByColumnAndRow($kolom_ppk,$row,$this->Unit_kerja_model->get_nama_unit($unit));
        // $row += 4;
        // $objWorksheet->setCellValueByColumnAndRow($kolom_penyusun,$row,$penyusun['nama']);
        // $objWorksheet->setCellValueByColumnAndRow($kolom_ppk,$row,$ppk['nama']);
        // $row ++;
        // $objWorksheet->setCellValueByColumnAndRow($kolom_penyusun,$row,"NIP. " . $penyusun['nip']);
        // $objWorksheet->setCellValueByColumnAndRow($kolom_ppk,$row,"NIP. " . $ppk['nip']);

        if ($mode == 'excel'){
            $objWorksheet->getPageSetup()->setFitToPage(true);
            $objWorksheet->getPageSetup()->setFitToWidth(0);
            $objWorksheet->getPageSetup()->setFitToHeight(1);
            $objWorksheet->getPageSetup()->setRowsToRepeatAtTopByStartAndEnd(10,11);
            header("Content-type: application/vnd.ms-excel");
            header("Content-Disposition: attachment; filename=buku_besar.xls");
            header('Cache-Control: max-age=0');
            // $objWriter = new PHPExcel_Writer_HTML($objPHPExcel,'excel5');  
            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
            $objWriter->save('php://output');
            exit;


        }

        $objWriter = new PHPExcel_Writer_HTML($objPHPExcel);  
        $output['data'] = $objWriter->generateHTMLHeader();
        $output['data'] .= $objWriter->generateStyles();
        $output['data'] .= $objWriter->generateSheetData();
        $output['data'] .= $objWriter->generateHTMLFooter();
        $output['teks_cetak'] = 'Print Buku Besar';
        $output['sumber'] = 'get_buku_besar';
        
    

        $this->load->view('akuntansi/laporan/laporan',$output);

    }

    public function cetak_buku_besar(){
        $akun = $this->input->post('akun')[0];
        $basis = $this->input->post('basis');
        $unit = $this->input->post('unit');
        $sumber_dana = $this->input->post('sumber_dana');
        
        $daterange = $this->input->post('daterange');
        $date_t = explode(' - ', $daterange);
        $periode_awal = strtodate($date_t[0]);
        $periode_akhir = strtodate($date_t[1]) or null;    

        $mode = null;

        if ($unit == 'all') {
            $unit = null;
            // $mode = 'neraca';
            $data['teks_unit'] = "UNIVERSITAS DIPONEGORO";
        } else {
            $teks_unit = $this->Unit_kerja_model->get_nama_unit($unit);
            $data['teks_unit'] = strtoupper(str_replace('Fak.', "Fakultas ", $teks_unit));
        }

        if ($sumber_dana == 'all') {
            $sumber_dana = null;
        }


        $array_akun = array();

        if ($akun == 'all') {
            $array_akun = array(1,2,3,4,5,6,7,8,9);
            $mode = 'neraca';
        }
        else {
            $array_akun[] = $akun;
        }

        $teks_sumber_dana = "BUKU BESAR ";
        $teks_periode = "";
        
        $teks_tahun = substr($periode_akhir,0,4);
        $data['teks_tahun_anggaran'] = "TAHUN ANGGARAN $teks_tahun";


        if ($periode_awal != null and $periode_akhir != null){
            $teks_periode .= "PER ".$this->Jurnal_rsa_model->reKonversiTanggal($periode_awal) . " - ".$this->Jurnal_rsa_model->reKonversiTanggal($periode_akhir);
        }


        if ($sumber_dana != null) {
            $teks_sumber_dana .= "DARI DANA ".strtoupper(str_replace('_',' ',$sumber_dana));
        }

        $data['periode_text'] = $teks_periode;
        $data['unit'] = $unit;
        $data['periode_akhir'] = $this->Jurnal_rsa_model->reKonversiTanggal($periode_akhir);

        $data['query'] = $this->Laporan_model->get_data_buku_besar($array_akun,$basis,$unit,$sumber_dana,$periode_awal,$periode_akhir,$mode);
        $this->load->view('akuntansi/laporan/pdf_buku_besar',$data);

    }

    public function cetak_rekap_jurnal(){
        $basis = $this->input->post('basis');
        $unit = $this->input->post('unit');
        $sumber_dana = $this->input->post('sumber_dana');
        
        $daterange = $this->input->post('daterange');
        $date_t = explode(' - ', $daterange);
        $periode_awal = strtodate($date_t[0]);
        $periode_akhir = strtodate($date_t[1]);

        if ($unit == 'all') {
            $unit = null;
        }
        if ($sumber_dana == 'all') {
            $sumber_dana = null;
        } 

        $teks_sumber_dana = "JURNAL UMUM ";
        $teks_periode = "";
        $teks_tahun = substr($periode_akhir,0,4);
        $teks_tahun_anggaran = "TAHUN ANGGARAN $teks_tahun";

        if ($periode_awal != null and $periode_akhir != null){
            $teks_periode .= "PER ".$this->Jurnal_rsa_model->reKonversiTanggal($periode_awal) . " - ".$this->Jurnal_rsa_model->reKonversiTanggal($periode_akhir);
        }


        if ($sumber_dana != null) {
            $teks_sumber_dana .= "DARI DANA ".strtoupper(str_replace('_',' ',$sumber_dana));
        }

        $data['teks_periode'] = $teks_periode;
        $data['teks_tahun_anggaran'] = $teks_tahun_anggaran;

        $data['unit'] = $unit;
        $data['periode_akhir'] = $this->Jurnal_rsa_model->reKonversiTanggal($periode_akhir);

        // print_r($this->input->post());die();
        // $akun = array(1,2,3,4,5,6,7,8,9);
        //public function read_rekap_jurnal($jenis=null,$unit=null,$sumber_dana=null,$start_date=null,$end_date=null)
        $data['query'] = $this->Laporan_model->read_rekap_jurnal($basis,$unit,$sumber_dana,$periode_awal,$periode_akhir);

        $this->load->view('akuntansi/laporan/pdf_rekap_jurnal',$data);
    }

    public function cetak_neraca_saldo(){
        $array_akun = array(1,2,3,4,5,6,7,8,9);

        $basis = $this->input->post('basis');
        
        $daterange = $this->input->post('daterange');
        $date_t = explode(' - ', $daterange);
        $periode_awal = strtodate($date_t[0]);
        $periode_akhir = strtodate($date_t[1]);
        
        $sumber_dana = $this->input->post('sumber_dana');
        $unit = $this->input->post('unit');

        $teks_unit = null;


        if ($unit == 'all') {
            $unit = null;
            $mode = 'neraca';
            $teks_unit = "UNIVERSITAS DIPONEGORO";
        } else {
            $teks_unit = $this->Unit_kerja_model->get_nama_unit($unit);
            $teks_unit = strtoupper(str_replace('Fak.', "Fakultas ", $teks_unit));
        }

        // $data = $this->Laporan_model->get_data_buku_besar($akun,'akrual');
        $data['query'] = $this->Laporan_model->get_data_buku_besar($array_akun,$basis,$unit,$sumber_dana,$periode_awal,$periode_akhir,'neraca');
        ksort($data['query']);
        
        $teks_sumber_dana = "NERACA SALDO ";
        $teks_periode = "";

        $teks_tahun_anggaran = substr($periode_akhir,0,4);
        $teks_unit = "UNIVERSITAS DIPONEGORO";

        if ($periode_awal != null and $periode_akhir != null){
            $teks_periode .= "PER ".$this->Jurnal_rsa_model->reKonversiTanggal($periode_awal) . " - ".$this->Jurnal_rsa_model->reKonversiTanggal($periode_akhir);
        }


        if ($sumber_dana != null) {
            $teks_sumber_dana .= "DARI DANA ".strtoupper(str_replace('_',' ',$sumber_dana));
        }

        ksort($data);

        $n_akun = count($data);

        $data['teks_periode'] = $teks_periode;
        $data['teks_tahun_anggaran'] = $teks_tahun_anggaran;
        $data['unit'] = $unit;
        $data['periode_akhir'] = $this->Jurnal_rsa_model->reKonversiTanggal($periode_akhir);


        $this->load->view('akuntansi/laporan/pdf_neraca_saldo',$data);
    }

    public function get_neraca_saldo($mode = null)
    {
    // 	if ($tipe == 'sak'){
    // 		$akun = array(1,4);
    // 	}else if($tipe == 'lra'){
    // 		$akun = array(6,7);
    // 	}


    	$array_akun = array(1,2,3,4,5,6,7,8,9);

        $basis = $this->input->post('basis');
        
        $daterange = $this->input->post('daterange');
        $date_t = explode(' - ', $daterange);
        $periode_awal = strtodate($date_t[0]);
        $periode_akhir = strtodate($date_t[1]);
        
        $sumber_dana = $this->input->post('sumber_dana');
        $unit = $this->input->post('unit');

        $teks_unit = null;


        if ($unit == 'all' or $unit == 9999) {
            $unit = null;
            $mode = 'neraca';
            $teks_unit = "UNIVERSITAS DIPONEGORO";
        } else {
            $teks_unit = $this->Unit_kerja_model->get_nama_unit($unit);
            $teks_unit = strtoupper(str_replace('Fak.', "Fakultas ", $teks_unit));
        }


    	// $data = $this->Laporan_model->get_data_buku_besar($akun,'akrual');
        $data = $this->Laporan_model->get_data_buku_besar($array_akun,$basis,$unit,$sumber_dana,$periode_awal,$periode_akhir,'neraca');

        if ($data == null){
            $this->load->view('akuntansi/no_data',true);
            return 0;
        }

        $teks_sumber_dana = "NERACA SALDO ";
        $teks_periode = "";

        $teks_tahun_anggaran = substr($periode_akhir,0,4);

        if ($periode_awal != null and $periode_akhir != null){
            $teks_periode .= "PER ".$this->Jurnal_rsa_model->reKonversiTanggal($periode_awal) . " - ".$this->Jurnal_rsa_model->reKonversiTanggal($periode_akhir);
        }


        if ($sumber_dana != null) {
            $teks_sumber_dana .= "DARI DANA ".strtoupper(str_replace('_',' ',$sumber_dana));
        }

    	ksort($data);

    	$n_akun = count($data);


        $path_template = realpath(FCPATH).'/assets/akuntansi/template_excel/template_neraca_saldo.xls';
        $excel = new PHPExcel_Reader_Excel5();
        $objPHPExcel = $excel->load($path_template);
        $objWorksheet = $objPHPExcel->getActiveSheet();
        $objWorksheet->setTitle('neraca_saldo');

        $objWorksheet->setCellValueByColumnAndRow(0,1,$teks_unit);
        $objWorksheet->setCellValueByColumnAndRow(0,2,$teks_sumber_dana);
        $objWorksheet->setCellValueByColumnAndRow(0,3,$teks_periode);
        $objWorksheet->setCellValueByColumnAndRow(2,6,":".$teks_tahun_anggaran);
        $objWorksheet->setCellValueByColumnAndRow(2,5,":".$teks_unit);

        $jumlah_debet = 0;
	    $jumlah_kredit = 0;
        $jumlah_neraca_debet = 0;
        $jumlah_neraca_kredit = 0;

    	$row = 11;
    	$i = 1;

    	foreach ($data as $key => $entry) {
            // $key = '911101';
            $debet = 0;
            $kredit = 0;

            $case_hutang = in_array(substr($key,0,1),[2,3]);

	    	$saldo = $this->Akun_model->get_saldo_awal($key);
            if ($saldo != null) {
                $saldo = $saldo['saldo_awal'];
            }
	    	// $debet = 0;
	    	// $kredit = 0;
            // print_r($saldo);
	    	$objPHPExcel->getActiveSheet()->insertNewRowBefore($row,1); 
	    	$objWorksheet->setCellValueByColumnAndRow(0,$row,$i);
	    	$objWorksheet->setCellValueByColumnAndRow(1,$row,$key);
	    	$objWorksheet->setCellValueByColumnAndRow(2,$row,$this->Akun_model->get_nama_akun((string)$key));


    		foreach ($entry as $transaksi) {
    			if ($transaksi['tipe'] == 'debet'){
                    if ($case_hutang) {
                        $saldo -= $transaksi['jumlah'];
                    } else {
                        $saldo += $transaksi['jumlah'];
                    }
    				$debet += $transaksi['jumlah'];
    			} else if ($transaksi['tipe'] == 'kredit'){
                    if ($case_hutang) {
                        $saldo += $transaksi['jumlah'];
                    } else {
                        $saldo -= $transaksi['jumlah'];
                    }
					$kredit += $transaksi['jumlah'];
    			}
    		}


    		$jumlah_debet += $debet;
    		$jumlah_kredit += $kredit;

    		$objWorksheet->setCellValueByColumnAndRow(3,$row,$this->eliminasi_negatif($debet));
    		$objWorksheet->setCellValueByColumnAndRow(4,$row,$this->eliminasi_negatif($kredit));


            if ($case_hutang) {
                $saldo_neraca = $kredit - $debet;
            } else {
                $saldo_neraca = $debet - $kredit;
            }


            $objWorksheet->setCellValueByColumnAndRow(5,$row,0);
            $objWorksheet->setCellValueByColumnAndRow(6,$row,0);

            if ($saldo_neraca > 0) {
                $jumlah_neraca_debet += $saldo_neraca;
                $objWorksheet->setCellValueByColumnAndRow(5,$row,$this->eliminasi_negatif($saldo_neraca));
            } elseif ($saldo_neraca < 0) {
                $saldo_neraca = abs($saldo_neraca);
                $jumlah_neraca_kredit += $saldo_neraca;
                $objWorksheet->setCellValueByColumnAndRow(6,$row,$this->eliminasi_negatif($saldo_neraca));
            }

    		$row++;

    		$i++;
    		// $objWorksheet->setCellValueByColumnAndRow(2,$i+$row,$i+1);
    	}

        $objWorksheet->setCellValueByColumnAndRow(3,$row+1,$this->eliminasi_negatif($jumlah_debet));
        $objWorksheet->setCellValueByColumnAndRow(4,$row+1,$this->eliminasi_negatif($jumlah_kredit));
    	$objWorksheet->setCellValueByColumnAndRow(5,$row+1,$this->eliminasi_negatif($jumlah_neraca_debet));
    	$objWorksheet->setCellValueByColumnAndRow(6,$row+1,$this->eliminasi_negatif($jumlah_neraca_kredit));

        //===========================


        //===========================

        if ($unit == null or $unit == 9999) {
            $kpa = $this->Pejabat_model->get_pejabat('all','rektor');
            $teks_kpa = "Rektor";
        } else {
            $kpa = $this->Pejabat_model->get_pejabat($unit,'kpa');
            $teks_kpa = "Pengguna Anggaran";
        }

        $row = $objWorksheet->getHighestRow() + 2;
        $kolom_kpa = 5;

        $objWorksheet->mergeCellsByColumnAndRow($kolom_kpa,$row,$kolom_kpa+1,$row);
        $objWorksheet->setCellValueByColumnAndRow($kolom_kpa,$row,"Semarang, ". $this->Jurnal_rsa_model->reKonversiTanggal($periode_akhir));
        $row++;
        $objWorksheet->mergeCellsByColumnAndRow($kolom_kpa,$row,$kolom_kpa+1,$row);
        $objWorksheet->setCellValueByColumnAndRow($kolom_kpa,$row,$teks_kpa);
        $row++;
        $objWorksheet->mergeCellsByColumnAndRow($kolom_kpa,$row,$kolom_kpa+1,$row);
        $objWorksheet->setCellValueByColumnAndRow($kolom_kpa,$row,$teks_unit);
        $row += 4;
        $objWorksheet->mergeCellsByColumnAndRow($kolom_kpa,$row,$kolom_kpa+2,$row);
        $objWorksheet->setCellValueByColumnAndRow($kolom_kpa,$row,$kpa['nama']);
        $row ++;
        $objWorksheet->mergeCellsByColumnAndRow($kolom_kpa,$row,$kolom_kpa+1,$row);
        $objWorksheet->setCellValueByColumnAndRow($kolom_kpa,$row,"NIP. " . $this->format_nip($kpa['nip']));

        if ($mode == 'excel'){
            $objWorksheet->getPageSetup()->setFitToPage(true);
            $objWorksheet->getPageSetup()->setFitToWidth(0);
            $objWorksheet->getPageSetup()->setFitToHeight(1);
            $objWorksheet->getPageSetup()->setRowsToRepeatAtTopByStartAndEnd(8,9);
            header("Content-type: application/vnd.ms-excel");
            header("Content-Disposition: attachment; filename=neraca_saldo.xls");
            header('Cache-Control: max-age=0');
            // $objWriter = new PHPExcel_Writer_HTML($objPHPExcel,'excel5');  
            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
            $objWriter->save('php://output');
            exit;


        }

        $objWriter = new PHPExcel_Writer_HTML($objPHPExcel);  
        $output['data'] = $objWriter->generateHTMLHeader();
        $output['data'] .= $objWriter->generateStyles();
        $output['data'] .= $objWriter->generateSheetData();
        $output['data'] .= $objWriter->generateHTMLFooter();
        $output['teks_cetak'] = 'Print Neraca Saldo';
        $output['sumber'] = 'get_neraca_saldo';
        
    

        $this->load->view('akuntansi/laporan/laporan',$output);

    }

    function copyRows(PHPExcel_Worksheet $sheet,$srcRow,$dstRow,$height,$width) {
        for ($row = 0; $row < $height; $row++) {
               for ($col = 0; $col < $width; $col++) {
                $cell = $sheet->getCellByColumnAndRow($col, $srcRow + $row);
                $style = $sheet->getStyleByColumnAndRow($col, $srcRow + $row);
                $dstCell = PHPExcel_Cell::stringFromColumnIndex($col) . (string)($dstRow + $row);
                $sheet->setCellValue($dstCell, $cell->getValue());
                $sheet->duplicateStyle($style, $dstCell);
            }

            $h = $sheet->getRowDimension($srcRow + $row)->getRowHeight();
            $sheet->getRowDimension($dstRow + $row)->setRowHeight($h);
        }

        foreach ($sheet->getMergeCells() as $mergeCell) {
            $mc = explode(":", $mergeCell);
            $col_s = preg_replace("/[0-9]*/", "", $mc[0]);
            $col_e = preg_replace("/[0-9]*/", "", $mc[1]);
            $row_s = ((int)preg_replace("/[A-Z]*/", "", $mc[0])) - $srcRow;
            $row_e = ((int)preg_replace("/[A-Z]*/", "", $mc[1])) - $srcRow;

            if (0 <= $row_s && $row_s < $height) {
                $merge = $col_s . (string)($dstRow + $row_s) . ":" . $col_e . (string)($dstRow + $row_e);
                $sheet->mergeCells($merge);
            } 
        }
    }

    public function eliminasi_negatif($value)
    {
        if ($value < 0) 
            return "(". number_format(abs($value),2,',','.') .")";
        else
            return number_format($value,2,',','.');
    }

    public function format_nip($value)
    {
        return str_replace("'",'',$value);
    }
}
