 				<div class="breadcrumbs">
						<div class="col-sm-6">
								<div class="page-header ">
										<div class="page-title">
												<h1>Penyusunan RMP</h1>
										</div>
								</div>
						</div>
						<div class="col-sm-6">
								<div class="page-header float-right">
										<div class="page-title">
												<ol class="breadcrumb text-right">
														<li><a href="#">Kegiatan</a></li>
														<li class="active">Penyusunan RMP</li>
												</ol>
										</div>
								</div>
						</div>
				</div>

				<div class="content mt-3">
						<div class="animated fadeIn">
								<div class="row">
									<div class="col-md-12">
										<div>
											<center><h2><?=$row['judul'] ?></h2>Bab IV (Persyaratan Teknis dan Administrasi)</center>
										</div>
										<div>
											<div class="form-group col-md-2">
									      <select id="navigate" class="form-control" style="margin-left:-15px; background-color:#ffd700;">
									        <option disabled><b>-Navigasi-</b></option>
									        <option value="judul" <?php if($this->uri->segment(2) == "judul") echo "selected"; ?> >Judul</option>
									        <option value="rekap" <?php if($this->uri->segment(2) == "rekap") echo "selected"; ?> >Rekap</option>
									        <option value="sejarah" <?php if($this->uri->segment(2) == "sejarah") echo "selected"; ?> >Sejarah</option>
									        <option value="satu" <?php if($this->uri->segment(2) == "satu") echo "selected"; ?> >Bab I</option>
									        <option value="dua" <?php if($this->uri->segment(2) == "dua") echo "selected"; ?>>Bab II</option>
									        <option value="tiga" <?php if($this->uri->segment(2) == "tiga") echo "selected"; ?>>Bab III</option>
									        <option value="empat" <?php if($this->uri->segment(2) == "empat") echo "selected"; ?>>Bab IV</option>
									        <?php if($row['jenis'] == "Litbang"){ ?>
									        <option value="limabelas" <?php if($this->uri->segment(2) == "limabelas") echo "selected"; ?>>Daftar Pustaka</option>
									        <?php } ?>
									        <option value="att1" <?php if($this->uri->segment(2) == "att1") echo "selected"; ?>>Lampiran 1</option>
									        <option value="att2" <?php if($this->uri->segment(2) == "att2") echo "selected"; ?>>Lampiran 2</option>
									        <option value="att3" <?php if($this->uri->segment(2) == "att3") echo "selected"; ?>>Lampiran 3</option>
									      </select>
									    </div>											
											<div class="pull-right" style="margin-bottom: 15px;">
												<a href="<?=base_url(); ?>RMP/tiga/<?=$this->uri->segment(3)?>" type="button" class="btn btn-labeled btn-danger">
														<span class="btn-label"><i class="fa fa-fw fa-arrow-left"></i></span> Back
												</a>												
											<?php if($row['jenis'] == "Litbang"){ ?>
												<a href="<?=base_url(); ?>RMP/limabelas/<?=$this->uri->segment(3)?>" type="button" class="btn btn-labeled btn-success">
														<span class="btn-label"><i class="fa fa-fw fa-arrow-right"></i></span> Daftar Pustaka
												</a>
											<?php }else{ ?>
												<a href="<?=base_url(); ?>RMP/att1/<?=$this->uri->segment(3)?>" type="button" class="btn btn-labeled btn-success">
														<span class="btn-label"><i class="fa fa-fw fa-arrow-right"></i></span> Lampiran 1 (RAB)
												</a>
											<?php } ?>
											</div>
										</div>
									</div>
								</div>
								<?php echo form_open_multipart('RMP/empat/'.$this->uri->segment(3)); ?>
								<div class="row">
									<div class="col-md-3">
										<div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
											<a class="nav-link active" id="v-pills-home-tab" data-toggle="pill" href="#strukturorg" role="tab" aria-selected="true">Struktur Organisasi</a>
											<a class="nav-link" id="v-pills-home-tab" data-toggle="pill" href="#sdm" role="tab" aria-selected="true">Sumber Daya Manusia</a>
											<!-- <a class="nav-link" id="v-pills-home-tab" data-toggle="pill" href="#sdk" role="tab" aria-selected="true">Sumber Daya Keuangan</a> -->

											<a class="nav-link" id="v-pills-home-tab" data-toggle="pill" href="#lingkungan" role="tab" aria-selected="true">Kondisi Lingkungan</a>
											<input type="hidden" name="id" value="<?=$row['id']; ?>">
											<button type="submit" class="btn btn-success">
													<span class=""><i class="fa fa-fw fa-save"></i></span> Simpan Draf
											</button>
											<!-- <button type="submit" class="btn btn-outline-dark">
													<span class=""><i class="fa fa-fw fa-eye"></i></span> Preview
											</button> -->
										</div>
									</div>
									<div class="col-md-9">
										<div class="tab-content" id="v-pills-tabContent">
											<div class="tab-pane fade show active" id="strukturorg" role="tabpanel">
												<div class="card-title">
													<h3 class="text-center">Struktur Organisasi <a href="#" data-toggle="tooltip" data-placement="bottom"  title="Upload Struktur"><i class="fa fa-info-circle"></i></a></h3>
													<hr>
													<?php if($row['strukturorg'] != NULL){ ?>
														<img src="<?=base_url(); ?>assets/uploads/strukturorg/<?=$row['strukturorg']?>" alt="" height="500px" class="img">
													<?php } ?>														
														<div class="input-group">
															<div class="custom-file">
																<input type="file" name="strukturorg" >
															</div>
														</div>
												</div>
											</div>
											<!-- <div class="tab-pane fade" id="v-pills-settings" role="tabpanel">EWEWEWEW</div> -->
											<div class="tab-pane fade show" id="sdm" role="tabpanel">
												<div class="card-title">
													<h3 class="text-center">Sumber Daya Manusia</h3>
													<hr>
														<div class="text-right" style="margin-bottom: 15px;">
															<button type="button" class=" btn-sm btn-labeled btn-primary" data-toggle="modal" data-target="#previewfoto">
																	<span class="btn-label"><i class="fa fa-fw fa-eye"></i></span> Lihat Struktur
															</button>
															<button type="button" class=" btn-sm btn-labeled btn-success" data-toggle="modal" data-target="#tambahsdm">
																	<span class="btn-label"><i class="fa fa-fw fa-plus"></i></span> Tambah SDM
															</button>
														</div>
													<table class="table table-striped table-bordered">
														<thead>
															<tr>
																<th>No.</th>
																<th>Jabatan</th>
																<!-- <th>Jumlah</th> -->
																<th>Kompentensi</th>
																<th>Tugas</th>
																<th>Tanggung Jawab</th>
																<th>Wewenang</th>
																<th>Aksi</th>
															</tr>
														</thead>
														<tbody>
															<?php if(isset($allsdm)){$no=1;foreach($allsdm as $row2){ ?>
															<tr>
																<td><?=$no++;?></td>
																<td><?=$row2['name'];?></td>
																<td><?=$row2['kompetensi'];?></td>
																<td><?=$row2['tugas'];?></td>
																<td><?=$row2['tanggungjawab'];?></td>
																<td><?=$row2['wewenang'];?></td>
																<td>
																	<!-- <a class="text-warning" href="<?=base_url(); ?>RMP/editSDM/<?=$row2['id'];?>/<?=$this->uri->segment(3);?>" title="Hapus Jabatan"><span class="fa fa-fw fa-pencil"></span></a> -->
																	<a class="text-danger" href="<?=base_url(); ?>RMP/deleteSDM/<?=$row2['id'];?>/<?=$this->uri->segment(3);?>" title="Edit Jabatan"><span class="fa fa-fw fa-trash"></span></a>
																</td>
															</tr>
															<?php }}else{ ?>
															<tr>
																<td>Belum ada data</td>
																<td>Belum ada data</td>
																<td>Belum ada data</td>
																<td>Belum ada data</td>
																<td>Belum ada data</td>
															</tr>
															<?php } ?>
														</tbody>
													</table>
												</div>
											</div>

											<div class="tab-pane fade" id="lingkungan" role="tabpanel">
												<div class="card-title">
													<h3 class="text-center">Kondisi Lingkungan</h3>
													<hr>
													<textarea class="ckeditor" name="lingkungan" ><?=$row['lingkungan'];?></textarea>
												</div>
											</div>											
										</div>
										<?php echo form_close(); ?>
									</div>

								</div>
						</div><!-- .animated -->
				</div><!-- .content -->


		</div><!-- /#right-panel -->

		<!-- Right Panel -->
		<div class="modal fade" id="tambahsdm" tabindex="-1" role="dialog" aria-hidden="true">
				<div class="modal-dialog modal-lg" role="document">
						<div class="modal-content">
								<div class="modal-header">
										<h5 class="modal-title" id="largeModalLabel">Tambah SDM</h5>
										<button type="button" class="close" data-dismiss="modal" aria-label="Close">
												<span aria-hidden="true">&times;</span>
										</button>
								</div>
								<div class="modal-body">
								<?php echo form_open('RMP/addSDM/'.$this->uri->segment(3)); ?>
									<div class="row">
										<div class="col-md-12">
											<div class="form-group">
												<label class=" form-control-label">Pilih SDM </label>
												<div class="input-group">
														<select name="rmp_position" class="form-control">
															<?php foreach($positions as $position){ ?>
															<option value="<?=$position['id']; ?>"><?=$position['name']; ?></option>
															<?php } ?>
															
														</select>
												</div>
											</div>

										</div>
									</div>
								</div>
								<div class="modal-footer">
										<button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
										<input type="hidden" name="rmp_id" value="<?=$row['id'];?>">
										<button type="submit" class="btn btn-primary"
											<i class="fa fa-fw fa-dot-circle-o"></i> Submit
								</div>
								<?php echo form_close(); ?>
						</div>
				</div>
		</div>	
		<div class="modal fade" id="previewfoto" tabindex="-1" role="dialog" aria-hidden="true">
				<div class="modal-dialog modal-lg" role="document">
						<div class="modal-content">
								<div class="modal-header">
										<h5 class="modal-title" id="largeModalLabel">Preview Foto</h5>
										<button type="button" class="close" data-dismiss="modal" aria-label="Close">
												<span aria-hidden="true">&times;</span>
										</button>
								</div>
								<div class="modal-body">
										<img width="600" src="<?=base_url(); ?>assets/uploads/strukturorg/<?=$row['strukturorg']?>" alt="">
								</div>
								<div class="modal-footer">
										<button type="button" class="btn btn-secondary" data-dismiss="modal">Kembali</button>
								</div>
								<?php echo form_close(); ?>
						</div>
				</div>
		</div>