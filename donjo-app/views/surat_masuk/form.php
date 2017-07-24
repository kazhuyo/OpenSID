<div id="pageC">
	<div class="content-header">

	</div>
	<div id="contentpane">
		<div class="ui-layout-north panel"><h3>Disposisi Surat Masuk</a></h3>
		</div>
		<form id="validasi" action="<?php echo $form_action?>" method="POST" enctype="multipart/form-data">
			<div class="ui-layout-center" id="maincontent" style="padding: 5px;">
				<table class="form">
					<tr>
						<th>Nomor Urut</th>
						<td><input name="nomor_urut" type="text" class="inputbox required" size="20" value="<?php echo $surat_masuk['nomor_urut']?>"/></td>
					</tr>
					<tr>
						<th>Tanggal Penerimaan</th>
						<td><input name="tanggal_penerimaan" type="text" class="inputbox datepicker required" size="20" value="<?php echo tgl_indo_out($surat_masuk['tanggal_penerimaan'])?>"/></td>
					</tr>
					<tr>
						<th>Kode/Klasifikasi Surat</th>
						<td><input name="kode_surat" type="text" class="inputbox required" size="20" value="<?php echo $surat_masuk['kode_surat']?>"/></td>
					</tr>
					<tr>
						<th>Nomor Surat</th>
						<td><input name="nomor_surat" type="text" class="inputbox required" size="20" value="<?php echo $surat_masuk['nomor_surat']?>"/></td>
					</tr>
					<tr>
						<th>Tanggal Surat</th>
						<td><input name="tanggal_surat" type="text" class="inputbox datepicker required" size="20" value="<?php echo tgl_indo_out($surat_masuk['tanggal_surat'])?>"/></td>
					</tr>
					<tr>
						<th>Pengirim</th>
						<td><input name="Pengirim" type="text" class="inputbox required" size="50" value="<?php echo $surat_masuk['pengirim']?>"/></td>
					</tr>
					<tr>
						<th>Isi Singkat/Perihal</th>
						<td><textarea name="isi_singkat" class="required" style="resize:none;width:300px;height:2em;"><?php echo $surat_masuk['isi_singkat']?></textarea></td>
					</tr>
					<tr>
						<th>Disposisi Kepada</th>
						<td><input name="disposisi_kepada" type="text" class="inputbox required" size="20" value="<?php echo $surat_masuk['disposisi_kepada']?>"/></td>
					</tr>
					<tr>
						<th>Isi Disposisi</th>
						<td><textarea name="isi_disposisi" class="required" style="resize:none;width:300px;height:3em;"><?php echo $surat_masuk['isi_disposisi']?></textarea></td>
					</tr>
				</table>
			</div>

			<div class="ui-layout-south panel bottom">
				<div class="left">
					<a href="<?php echo site_url()?>surat_masuk" class="uibutton icon prev">Kembali</a>
				</div>
				<div class="right">
					<div class="uibutton-group">
						<button class="uibutton" type="reset"><span class="fa fa-refresh"></span> Bersihkan</button>
						<button class="uibutton confirm" type="submit" ><span class="fa fa-save"></span> Simpan</button>
					</div>
				</div>
			</div>
		</form>
	</div>
</div>
