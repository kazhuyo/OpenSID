<?php class surat_masuk_model extends CI_Model{

	function __construct(){
		parent::__construct();
	}

	function autocomplete(){
		$sql   = "SELECT pengirim FROM surat_masuk";
		$query = $this->db->query($sql);
		$data  = $query->result_array();

		$i=0;
		$outp='';
		while($i<count($data)){
			$outp .= ",'" .$data[$i]['pengirim']. "'";
			$i++;
		}
		$outp = strtolower(substr($outp, 1));
		$outp = '[' .$outp. ']';
		return $outp;
	}


	function search_sql(){
		if(isset($_SESSION['cari'])){
		$cari = $_SESSION['cari'];
			$kw = $this->db->escape_like_str($cari);
			$kw = '%' .$kw. '%';
			$search_sql= " AND (u.pengirim LIKE '$kw' OR u.isi_singkat LIKE '$kw')";
			return $search_sql;
			}
		}

	function filter_sql(){
		if(isset($_SESSION['filter'])){
			$kf = $_SESSION['filter'];
			$filter_sql= " AND u.kode_surat = $kf";
		return $filter_sql;
		}
	}

	// Digunakan untuk paging dan query utama supaya jumlah data selalu sama
	private function list_data_sql() {
		$sql = "
			FROM surat_masuk u WHERE 1 ";
		$sql .= $this->search_sql();
		$sql .= $this->filter_sql();
		return $sql;
	}

	function paging($p=1,$o=0){

		$sql = "SELECT COUNT(id) AS id ".$this->list_data_sql();
		$query    = $this->db->query($sql);
		$row      = $query->row_array();
		$jml_data = $row['id'];

		$this->load->library('paging');
		$cfg['page']     = $p;
		$cfg['per_page'] = $_SESSION['per_page'];
		$cfg['num_rows'] = $jml_data;
		$this->paging->init($cfg);

		return $this->paging;
	}

	function list_data($o=0,$offset=0,$limit=500){

		//Ordering SQL
		switch($o){
			case 1: $order_sql = ' ORDER BY YEAR(u.tanggal_penerimaan) ASC, u.nomor_urut ASC'; break;
			case 2: $order_sql = ' ORDER BY YEAR(u.tanggal_penerimaan) DESC, u.nomor_urut DESC'; break;
			case 3: $order_sql = ' ORDER BY u.tanggal_penerimaan'; break;
			case 4: $order_sql = ' ORDER BY u.tanggal_penerimaan DESC'; break;
			case 5: $order_sql = ' ORDER BY u.pengirim'; break;
			case 6: $order_sql = ' ORDER BY u.pengirim DESC'; break;
			default:$order_sql = ' ORDER BY u.id';
		}

		//Paging SQL
		$paging_sql = ' LIMIT ' .$offset. ',' .$limit;

		//Main Query
		$sql   = "SELECT u.* ".$this->list_data_sql();
		$sql .= $order_sql;
		$sql .= $paging_sql;

		$query = $this->db->query($sql);
		$data=$query->result_array();
		return $data;
	}

	function insert(){
		$_SESSION['error_msg'] = '';
		$_SESSION['success'] = 1;
		$data = $_POST;
		$data['tanggal_penerimaan'] = tgl_indo_in($data['tanggal_penerimaan']);
		$data['tanggal_surat'] = tgl_indo_in($data['tanggal_surat']);
		// Upload scan surat masuk
		unset($data['old_gambar']);
		$file_gambar = $this->_upload_gambar();
		if($file_gambar) $data['berkas_scan'] = $file_gambar;
		$outp = $this->db->insert('surat_masuk',$data);
		if(!$outp) $_SESSION['success'] = -1;
	}

	function update($id=0){
		$_SESSION['error_msg'] = '';
		$_SESSION['success'] = 1;
		$data = $_POST;
		if($data['gambar_hapus']){
		  unlink(LOKASI_DOKUMEN . $data['gambar_hapus']);
			$data['berkas_scan'] = '';
		}
		unset($data['gambar_hapus']);
		$file_gambar = $this->_upload_gambar($data['old_gambar']);
		if($file_gambar) $data['berkas_scan'] = $file_gambar;
		unset($data['old_gambar']);
		$this->db->where('id',$id);
		$data['tanggal_penerimaan'] = tgl_indo_in($data['tanggal_penerimaan']);
		$data['tanggal_surat'] = tgl_indo_in($data['tanggal_surat']);
		$outp = $this->db->update('surat_masuk',$data);
		if(!$outp) $_SESSION['success'] = -1;
	}

	function get_surat_masuk($id){
		$surat_masuk = $this->db->where('id',$id)->get('surat_masuk')->row_array();
		return $surat_masuk;
	}

	function get_pengolah_disposisi(){
		$this->load->model('wilayah_model');
    $ref_disposisi[] = 'Sekretaris '.ucwords($this->setting->sebutan_desa);
    array_push($ref_disposisi,
      'Kasi Pemerintahan',
      'Kasi Kesejahteraan',
      'Kasi Pelayanan',
      'Kaur Keuangan',
      'Kaur Tata Usaha dan Umum',
      'Kaur Perencanaan');
    $list_dusun = $this->wilayah_model->list_data();
    foreach($list_dusun as $dusun){
    	array_push($ref_disposisi, "KAWIL ".$dusun['dusun']);
    };
    return $ref_disposisi;
	}

	function _upload_gambar($old_document=''){
		$lokasi_file = $_FILES['satuan']['tmp_name'];
		if (!empty($lokasi_file)){
			$nama_file = $_FILES['satuan']['name'];
			$nama_file   = time().'-'.urlencode($nama_file); 	 // normalkan nama file
			UploadDocument($nama_file,$old_document);
			return $nama_file;
		}
	}

	function delete($id=''){
		// Surat jenis sistem (nilai 1) tidak bisa dihapus
		$sql  = "DELETE FROM tweb_surat_format WHERE jenis <> 1 AND id=?";
		$outp = $this->db->query($sql,array($id));

		if($outp) $_SESSION['success']=1;
			else $_SESSION['success']=-1;
	}

	function delete_all(){
		$id_cb = $_POST['id_cb'];

		if(count($id_cb)){
			foreach($id_cb as $id){
				$this->delete($id);
			}
		}
	}
}

?>
