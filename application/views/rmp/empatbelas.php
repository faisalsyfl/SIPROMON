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
											<center><h2><?=$row['judul'] ?></h2>Bab XIV (Daftar Induk Rekaman)</center>
										</div>
										<div>
											<div class="form-group col-md-2">
									      <select id="navigate" class="form-control" style="margin-left:-15px; background-color:#ffd700;">
									        <option selected disabled><b>-Navigasi-</b></option>
									        <option value="satu">Bab I</option>
									        <option value="dua">Bab II</option>
									        <option value="tiga">Bab III</option>
									        <option value="empat">Bab IV</option>
									        <option value="lima">Bab V</option>
									        <option value="enam">Bab VI</option>
									        <option value="tujuh">Bab VII</option>
									        <option value="delapan">Bab VIII</option>
									        <option value="sembilan">Bab IX-XII</option>
									        <option value="tigabelas">Bab XIII</option>
									        <option value="empatbelas">Bab XIV</option>
									        <?php if($row['jenis']=="Teknologi dan Terapan Terpadu" || $row['jenis'] == "Pilot Project"){  ?>
									        <option value="limabelas">Bab XV</option>
									        <?php } ?>
									        <option value="att1">Lampiran 1</option>
									        <option value="att2">Lampiran 2</option>
									        <option value="att3">Lampiran 3</option>
									        <option value="att4">Lampiran 4-5</option>
									        <?php if($row['jenis']=="Teknologi dan Terapan Terpadu"){  ?>
									        <option value="att6">Lampiran 6</option>
									        <?php } ?>									        
									      </select>
									    </div>																																	
											<div class="pull-right" style="margin-bottom: 15px;">
												<a href="<?=base_url(); ?>RMP/tigabelas/<?=$this->uri->segment(3)?>" type="button" class="btn btn-labeled btn-danger">
														<span class="btn-label"><i class="fa fa-fw fa-arrow-left"></i></span> Back
												</a>												
												<?php if($row['jenis'] == "Teknologi dan Terapan Terpadu" || $row['jenis'] == "Pilot Project"){ ?>
												<a href="<?=base_url(); ?>RMP/limabelas/<?=$this->uri->segment(3)?>" type="button" class="btn btn-labeled btn-success">
														<span class="btn-label"><i class="fa fa-fw fa-arrow-right"></i></span> Bab XV
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
								<?php echo form_open('RMP/sembilan/'.$this->uri->segment(3)); ?>
								<div class="row">
									<div class="col-md-3">
										<div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
											<a class="nav-link active" id="v-pills-home-tab" data-toggle="pill" href="#cek" role="tab" aria-controls="latar" aria-selected="true">Kelola Daftar Rekaman</a>
											<input type="hidden" name="id" value="<?=$row['id']; ?>">
											<!-- <button type="submit" class="btn btn-success">
																								<span class=""><i class="fa fa-fw fa-save"></i></span> Simpan Draf
																						</button> -->
											<!-- <button type="submit" class="btn btn-outline-dark">
													<span class=""><i class="fa fa-fw fa-eye"></i></span> Preview
											</button>
 -->										</div>
									</div>
									<div class="col-md-9">
										<div class="tab-content" id="v-pills-tabContent">
											<div class="tab-pane fade show active" id="cek" role="tabpanel">

												<div class="card-title">
													<h3 class="text-center">Daftar Rekaman <a href="#" data-toggle="tooltip" data-placement="bottom"  title="Masa Simpan Rekaman mengacu ke Permen PU No. 39/PRT/M/2007 Pedoman Jadwal Retensi Arsip (JRA) Kementerian Pekerjaan Umum."><i class="fa fa-info-circle"></i></a></h3>
													<hr>
														<div class="text-right" style="margin-bottom: 15px;">
															<button type="button" class=" btn-sm btn-labeled btn-success" data-toggle="modal" data-target="#tambahrec">
																	<span class="btn-label"><i class="fa fa-fw fa-plus"></i></span> Tambah Rekaman
															</button>
														</div>
													<table class="table table-striped table-bordered">
														<thead>
															<tr>
																<th>No.</th>
																<th>Nama Rekaman / Bukti Kerja</th>
																<th>Lokasi dan Penanggung jawab Penyimpanan Rekaman</th>
																<th>Masa Simpan</th>
																<th>Aksi</th>
															</tr>
														</thead>
														<tbody>
															<?php if(isset($records)){$no=1;foreach($records as $record){ ?>
															<tr>
																<td><?=$no++;?></td>
																<td><?=$record['record_name'];?></td>
																<td><?=$record['location'];?></td>
																<td><?=$record['period'];?></td>
																<td>
																	<a class="text-warning" href="<?=base_url(); ?>RMP/editRecord/<?=$record['id'];?>/<?=$this->uri->segment(3);?>" title="Edit Jabatan"><span class="fa fa-fw fa-pencil"></span></a>
																	<a class="text-danger" href="<?=base_url(); ?>RMP/deleteRecord/<?=$record['id'];?>/<?=$this->uri->segment(3);?>" title="Hapus Jabatan"><span class="fa fa-fw fa-trash"></span></a>
																</td>
															</tr>
															<?php }}else{ ?>
															<tr>
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
											<!-- <div class="tab-pane fade" id="v-pills-settings" role="tabpanel">EWEWEWEW</div> -->
										</div>
										<?php echo form_close(); ?>
									</div>

								</div>
						</div><!-- .animated -->
				</div><!-- .content -->
		</div><!-- /#right-panel -->
			<div class="modal fade" id="tambahrec" tabindex="-1" role="dialog" aria-hidden="true">
				<div class="modal-dialog modal-md" role="document">
						<div class="modal-content">
								<div class="modal-header">
										<h5 class="modal-title" id="largeModalLabel">Tambah Rekaman</h5>
										<button type="button" class="close" data-dismiss="modal" aria-label="Close">
												<span aria-hidden="true">&times;</span>
										</button>
								</div>
								<div class="modal-body">
								<?php echo form_open('RMP/addRecord/'.$this->uri->segment(3)); ?>
									<div class="row">
										<div class="col-md-12">
											<div class="form-group">
												<label class=" form-control-label">Nama Rekaman / Bukti Kerja</label>
												<div class="input-group">
														<input type="text" name="record_name" class="form-control">
												</div>
											</div>
											<div class="form-group">
												<label class=" form-control-label">Lokasi & Penanggung Jawab</label>
                        <div class="input-group mb-3">
                          <div class="input-group-prepend">
                            <span class="input-group-text" id="inputGroup-sizing-default">Lemari No </span>
                          </div>
                          <input type="text" name="lemari" class="form-control" placeholder="">
                        </div>
												<div class="input-group">
														<input type="text" name="pj" class="form-control" placeholder="Penanggung Jawab">
												</div>												
											</div>
											<div class="form-group">
												<label class=" form-control-label">Masa Simpan</label>
                        <div class="input-group mb-3">
                          <div class="input-group-prepend">
                            <span class="input-group-text" id="inputGroup-sizing-default">Aktif : </span>
                          </div>
                          <input type="number" name="aktif" class="form-control" placeholder="" value="1">
                          <div class="input-group-append">
                            <span class="input-group-text" id="inputGroup-sizing-default">Tahun</span>
                          </div>                          
                        </div>
                        <div class="input-group mb-3">
                          <div class="input-group-prepend">
                            <span class="input-group-text" id="inputGroup-sizing-default">Inaktif : </span>
                          </div>
                          <input type="number" name="inaktif" class="form-control" placeholder="" value="3">
                          <div class="input-group-append">
                            <span class="input-group-text" id="inputGroup-sizing-default">Tahun</span>
                          </div>                          
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