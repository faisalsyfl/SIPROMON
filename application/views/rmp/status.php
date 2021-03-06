				<div class="breadcrumbs">
						<div class="col-sm-4">
								<div class="page-header float-left">
										<div class="page-title">
												<h1>Status RMP <br><?=$row['judul']; ?></h1>
										</div>
								</div>
						</div>
						<div class="col-sm-8">
								<div class="page-header float-right">
										<div class="page-title">
												<ol class="breadcrumb text-right">
														<li><a href="#">RMP</a></li>
														<li class="active">Status</li>
												</ol>
										</div>
								</div>
						</div>
				</div>

				<div class="content mt-3" style="background-color: white;">
						<div class="animated fadeIn">
								<div class="row">
									<div class="col-md-8 offset-md-2">
										<table class="table table-bordered"  style="background-color: white;">
											<tbody>
												<tr>
													<td colspan="3"><img src="<?=base_url()?>assets/images/rmp/cover.jpg" alt=""></td>
												</tr>
											</tbody>
										</table>
										<div style="text-align: center;">
											<h3 style="font-weight: bold;">RENCANA MUTU PELAKSANAAN</h3>
											<h3 style="font-weight: bold;"><?=strtoupper($row['judul']);  ?></h3>
											<!-- <h3>....</h3> -->
											<h4 style="font-weight: bold;">TAHUN ANGGARAN <?=$row['tahun_anggaran'] ?></h4>
											<h4 style="font-weight: bold;">SATUAN KERJA <?=$row['satuankerja']; ?></h4>
											<h5 style="color:red;">No.Dok. : RMP/PUSAIR/BLSUNGAI/??   tanggal .... Januari 2019 Rev. 00</h5><br>
											<h5 style="font-weight: bold;">Pengesahan</h5><br><br>
										</div>
										<table class="table table-bordered table-striped"  style="background-color: white;">
											<tbody>
											<tr>
												<td></td>
												<td class="text-center"><b>Nama dan Jabatan</b></td>
												<td class="text-center"><b>Tanda Tangan</b></td>
											</tr>
											<tr>
												<td><b>Konseptor</b></td>
												<td><?=$row['konseptor']; ?><br>Ketua Tim Kegiatan</td>
												<td class="text-center">
<!-- 													<?php if($row['rmp_stats'] == 1 ){ ?>
													<a href="javascript:void(0);" onclick="sign(<?php echo $row['id'];?>);" class="btn btn-primary btn-sm <?php if($this->session->userdata('position') != NULL) echo "disabled"; ?>" data-toggle="tooltip" data-placement="bottom" title="Terbitkan RMP" >
														<i class="fa fa-fw fa-exclamation-circle"></i> Sign
													</a>
													<?php }else if($row['rmp_stats'] > 1){ ?>
													<a href="#"  class="btn btn-success btn-sm disabled">
														<i class="fa fa-fw fa-check"></i> Approved
													</a>
													<?php } ?> -->
												</td>
											</tr>										
											<tr>
												<td><b>Diperiksa Oleh</b></td>
												<td><?=$row['pemeriksa']; ?><br>Kepala <?=$row['satuankerja'] ?></td>
												<td class="text-center" >
<!-- 													<?php if($row['rmp_stats'] == 2){ ?>
													<a href="<?=base_url(); ?>Review/form"  class="btn btn-warning btn-sm" data-toggle="tooltip" data-placement="bottom" title="Periksa RMP">
														<i class="fa fa-fw fa-pencil"></i> Review
													</a>
													<a href="javascript:void(0);" onclick="check(<?php echo $row['id'];?>);" class="btn btn-primary btn-sm" data-toggle="tooltip" data-placement="bottom" title="Periksa RMP">
														<i class="fa fa-fw fa-exclamation-circle"></i> Sign
													</a>
													<?php }else if($row['rmp_stats'] > 2){ ?>
													<a href="" class="btn btn-success btn-sm disabled" >
														<i class="fa fa-fw fa-check"></i> Approved
													</a>
													<?php }else{ ?>
														<i style="color:red;">(belum di periksa)</i>
													<?php } ?> -->
												</td>
											</tr>										
											<tr>
												<td><b>Disahkan Oleh</b></td>
												<td><?=$row['pengesah']; ?><br>Kepala Pusat Litbang SDA</td>
												<td class="text-center" >
<!-- 													<?php if($row['rmp_stats'] == 3){ ?>
													<a href="javascript:void(0);" onclick="pas(<?php echo $row['id'];?>);" class="btn btn-primary btn-sm" data-toggle="tooltip" data-placement="bottom" title="Periksa RMP">
														<i class="fa fa-fw fa-exclamation-circle"></i> Sign
													</a>
													<?php }else if($row['rmp_stats'] > 3){ ?>
													<a href="" class="btn btn-success btn-sm disabled" >
														<i class="fa fa-fw fa-check"></i> Approved
													</a>
													<?php }else{ ?>
														<i style="color:red;">(belum di periksa)</i>
													<?php } ?>												 -->
												</td>
											</tr>
											</tbody>
										</table>
										<table class="table table-bordered table-sm">
											<col width="33%">
											<tbody>
											<tr>
												<td style="font-weight: bold;">Status Dokumen</td>
												<td> </td>
												<td> </td>
											</tr>
											<tr>
												<td style="font-weight: bold;">Tanggal Distribusi</td>
												<td> </td>
												<td> </td>
											</tr>											
											</tbody>
										</table>									
										<h5 class="pull-left" style="font-weight: bold;">Distribusi Ke</h5>
										<table class="table table-bordered table-sm">
											<col width="5%">											
											<col width="45%">											
											<col width="5%">											
											<col width="45%">											
											<tbody>
											<tr>
												<td align="center">No. Urut</td>
												<td align="center">Nama Jabatan</td>
												<td align="center">No. Urut</td>
												<td align="center">Nama Jabatan</td>
											</tr>
											<tr>
												<td align="center">1</td>
												<td>Kepala Pusat Litbang SDA</td>
												<td align="center">7</td>
												<td></td>
											</tr>
											<tr>
												<td align="center">2</td>
												<td>Kepala Bidang Program dan Evaluasi</td>
												<td align="center">8</td>
												<td></td>
											</tr>
											<tr>
												<td align="center">3</td>
												<td>Kepala <?=$row['satuankerja'] ?></td>
												<td align="center">9</td>
												<td></td>
											</tr>											
											<tr>
												<td align="center">4</td>
												<td>PPK <?=$row['satuankerja'] ?></td>
												<td align="center">10</td>
												<td></td>
											</tr>
											<tr>
												<td align="center">5</td>
												<td>Deputi Pengendali Dokumen</td>
												<td align="center">11</td>
												<td></td>
											</tr>
											<tr>
												<td align="center">6</td>
												<td>Sekretariat SMM (Dokumen Asli)</td>
												<td align="center">12</td>
												<td></td>
											</tr>											
											</tbody>
										</table>			
										<br>
										<br>
									</div>

								</div>
						</div><!-- .animated -->
				</div><!-- .content -->
		</div><!-- /#right-panel -->

		<!-- Right Panel -->