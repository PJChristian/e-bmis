<?php include 'server/server.php' ?>
<?php 

	$query = "SELECT * FROM tbl_request INNER JOIN tbl_account ON tbl_request.id_account = tbl_account.id_account ORDER BY `date_request` DESC";
	$result = $conn->query($query);

	$users = array();
	while($row = $result->fetch_assoc()){
		$users[] = $row; 
	}

?>
<!DOCTYPE html>
<html lang="en">
<head>
	<?php include 'templates/header.php' ?>
	<title>Resident Information - Paclasan E- Request</title>
</head>
<body>
<?php include 'templates/loading_screen.php' ?>
	<div class="wrapper">
		<!-- Main Header -->
		<?php include 'templates/main-header.php' ?>
		<!-- End Main Header -->

		<!-- Sidebar -->
		<?php include 'templates/sidebar.php' ?>
		<!-- End Sidebar -->

		<div class="main-panel">
			<div class="content">
				<div class="panel-header bg-success-gradient">
					<div class="page-inner">
						<div class="d-flex align-items-left align-items-md-center flex-column flex-md-row">
							<div>
								<h2 class="text-white fw-bold">My Request</h2>
							</div>
						</div>
					</div>
				</div>
				<div class="page-inner">
					<div class="row mt--2">
						<div class="col-md-12">
                            <div class="card">
								<div class="card-header">
									<div class="card-head-row">
										<div class="card-title">Request</div>
										<div class="card-tools">
											<a href="#" onClick="all_request()" id="request" class="btn btn-info btn-round btn-sm">
												<i class="fa fa-folder"></i>
												All Request
											</a>
											<a href="#" onClick="all_pending()" id="pending" class="btn btn-info btn-border btn-round btn-sm">
												<i class="fa fa-plus"></i>
												All Pending
											</a>
											<a href="#" onClick="released()"  id="released" class="btn btn-info btn-border btn-round btn-sm">
												<i class="fa fa-plus"></i>
												Released
											</a>
										</div>
									</div>
								</div>
								<div class="card-body">
									<div class="table-responsive">
										<table id="residenttable" class="display table table-striped">
											<thead>
												<tr>
													<th scope="col">No. </th>
													<th scope="col">Requested By</th>
													<th scope="col">Type of Documents</th>
													<th scope="col">Purpose</th>
													<th scope="col">Status</th>
													<th scope="col">Date Request</th>
													<th scope="col">Date Released</th>
													<th scope="col">Actions</th>
												</tr>
											</thead>
											<tbody>
												<?php if($_SESSION['role'] == 'administrator'): ?>
													<?php $no=1; foreach($users as $row): ?>
													<tr>
														<td><?= $no ?></td>
														<td>
															<?= $row['acc_name'] ?></td>
														<td>
															<?= $row['tod'] ?>
														</td>
														<td><?= $row['purpose'] ?></td>
														<td><?= $row['status'] ?></td>
														<td><?= $row['date_request'] ?></td>
														<td><?= $row['date_released'] ?></td>	
														<td>
															<div class="form-button-action">
																<?php if($row['tod'] == "Business Permit"){ ?>	
																	<a type="button" onClick="proccess_link(<?= $row['id_request']?>)" data-toggle="tooltip" href="business_permit.php?id=<?= $row['id_account']?>" class="btn btn-link btn-primary" data-original-title="Process">
																		 <i class="fas fa-file-alt"></i>
																	</a>
																<?php }?>
																<?php if($row['tod'] == "Certification/Clearance"){ ?>
																	<a type="button" onClick="proccess_link(<?= $row['id_request']?>)" data-toggle="tooltip" href="resident_certification.php?id=<?= $row['id_account']?>" class="btn btn-link btn-primary" data-original-title="Process">
																		 <i class="fas fa-file-alt"></i>
																	</a>
																<?php }?>
																<?php if($row['tod'] == "Certificate of Indigency"){ ?>
																	<a type="button" onClick="proccess_link(<?= $row['id_request']?>)" data-toggle="tooltip" href="resident_indigency.php?id=<?= $row['id_account']?>" class="btn btn-link btn-primary" data-original-title="Process">
																		 <i class="fas fa-file-alt"></i>
																	</a>
																	<?php }?>
																<?php if($row['tod'] == "Certificate of Residency"){ ?>
																	<a type="button" onClick="proccess_link(<?= $row['id_request']?>)" data-toggle="tooltip" href="resident_residency.php?id=<?= $row['id_account']?>" class="btn btn-link btn-primary" data-original-title="Process">
																		 <i class="fas fa-file-alt"></i>
																	</a>
																<?php }?>
																	<a type="button" onClick="disapproved(<?= $row['id_request']?>)" data-toggle="tooltip" href="request_bin.php" class="btn btn-link btn-danger" data-original-title="Disapproved">
																		<i class="fa fa-times"></i>
																	</a>
															</div>
												
														</td>													
													</tr>
													<?php $no++; endforeach ?>
												<?php else: ?>
													<tr>
														<td colspan="7" class="text-center">No Available Data</td>
													</tr>
												<?php endif ?>
											</tbody>
											<tfoot>
												<tr>
													<th scope="col">No.</th>
													<th scope="col">Requested By</th>
													<th scope="col">Type of Documents</th>
													<th scope="col">Purpose</th>
													<th scope="col">Status</th>
													<th scope="col">Date Request</th>
													<th scope="col">Date Released</th>
													<th scope="col">Actions</th>
												</tr>
											</tfoot>
										</table>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>

            <!-- Modal Request Form -->
            <div class="modal fade" id="add_request" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Request Documents</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
								<form method="POST" action="model/request_documents.php" >
								<div class="form-group">
									<input type="hidden" name="user_id" value="<?= $id; ?>">
                                    <label>Type of Documents</label>
                                    <select class="form-control" id="pillSelect" required name="type_of_documents">
                                        <option disabled selected>Select Document Type</option>
                                        <option value="Barangay Certificate">Barangay Certificates</option>
                                        <option value="Certificate of Indigency">Certificate of Indigency</option>
                                    </select>
                                </div>
							<input type="hidden" name="size" value="1000000">
								<div class="form-group">
                                    <label>Purpose</label>
                                    <textarea type="text" class="form-control" placeholder="Your Purpose" name="purpose" required></textarea>
                                </div>
                            
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Create</button>
                        </div>
                        </form>
                    </div>
                </div>
            </div>

			<!-- Modal Business Permit -->
			<div class="modal fade" id="req_permit" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Request Business Permit</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form method="POST" action="model/request_permit.php" >
							<input type="hidden" name="user_id" value="<?= $id; ?>">
                                <div class="form-group">
                                    <label>Business Name</label>
                                    <input type="text" class="form-control" placeholder="Enter Business Name" name="name" required>
                                </div>
                                <div class="form-group">
                                    <label>Business Owner</label>
                                    <input type="text" class="form-control mb-2" placeholder="Enter Owner Name" name="owner1" required>
                                </div>
								<div class="form-group">
                                    <label>Date Applied Nature</label>
                                    <input type="date" class="form-control" name="applied" value="<?= date('Y-m-d'); ?>" required>
                                </div>
								<div class="form-group">
                                    <label>Sitio</label>
                                    <input type="text" class="form-control" placeholder="Enter Sitio" name="sitio" required>
                                </div>
                        </div>
						                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Create</button>
                        </div>
                        </form>
                    </div>
                </div>
            </div>

			<!-- Create Account -->
			<div class="modal fade" id="create_account" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
                <div class="modal-dialog modal-xl" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Create an Account</h5>
                        </div>
                        <div class="modal-body">
                            <form method="POST" action="model/create_account.php" enctype="multipart/form-data">
                            <input type="hidden" name="size" value="1000000">
                            <div class="row">
                                <div class="col-md-4">
                                    <div id="my_camera1" style="width: 370px; height: 250;" class="text-center">
                                        <img src="assets/img/person.png" alt="..." class="img img-fluid" width="250" id="image">
                                    </div>
                                    <div id="profileImage1">
                                        <input type="hidden" name="profileimg">
                                    </div>
                                    <div class="form-group">
                                        <input type="file" class="form-control" name="img" accept="image/*">
                                    </div>
                                    
                                    
                                </div>
                                <div class="col-md-8">
                                    <div class="row">
                                        <div class="col">
                                            <div class="form-group">
                                                <label>Firstname</label>
                                                <input type="text" class="form-control" placeholder="Enter Firstname" name="fname" id="fname" required>
                                            </div>
                                        </div>
                                        <div class="col">
                                            <div class="form-group">
                                                <label>Middlename</label>
                                                <input type="text" class="form-control" placeholder="Enter Middlename" name="mname" id="mname" required>
                                            </div>
                                        </div>
                                        <div class="col">
                                            <div class="form-group">
                                                <label>Lastname</label>
                                                <input type="text" class="form-control" placeholder="Enter Lastname" name="lname" id="lname" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col">
                                            <div class="form-group">
                                                <label>Email Address</label>
                                                <input type="email" class="form-control" placeholder="Email Address" id="email" name="email">
                                            </div>
                                        </div>
                                        <div class="col">
                                            <div class="form-group">
                                                <label>Contact Number</label>
                                                <input type="text" class="form-control" placeholder="Contact Number" name="contact" id="contact" required>
                                            </div>
                                        </div>
                                        <div class="col">
                                            <div class="form-group">
                                                <label>Password</label>
                                                <input type="password" class="form-control" placeholder="Password" name="password" id="password" required>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary">Create Account</button>
                        </div>
                        </form>
                    </div>
                </div>
            </div>

			<!-- Modal Login Form -->
            <div class="modal fade" id="login" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Login Account</h5>
                        </div>
                        <div class="modal-body">
							<form method="POST" action="model/user_login.php">
								<div class="form-group form-floating-label">
									<input id="email" name="email" type="email" class="form-control input-border-bottom" required>
									<label for="email" class="placeholder">Email Address</label>
								</div>
								<div class="form-group form-floating-label">
									<input id="password" name="password" type="password" class="form-control input-border-bottom" required>
									<label for="password" class="placeholder">Password</label>
									<span toggle="#password" class="fa fa-fw fa-eye field-icon toggle-password"></span>
								</div>
                        </div>
                        <div class="modal-footer">
							<div class="form-action">
								<a href="#create_account" data-toggle="modal" id="#create_an_account">Create Account </a>
								<button type="submit" class="btn btn-success btn-rounded btn-login">Sign In</button>
							</div>
							</form>
						</div>
                   	</div>
                </div>
            </div>

			<!-- Modal Change Account Password -->
			<div class="modal fade" id="changeaccountpass" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
				<div class="modal-dialog" role="document">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title" id="exampleModalLabel">Change Password</h5>
							<button type="button" class="close" data-dismiss="modal" aria-label="Close">
								<span aria-hidden="true">&times;</span>
							</button>
						</div>
						<div class="modal-body">
							<form method="POST" action="model/change_account_password.php">
								<div class="form-group">
									<label>Email Address</label>
									<input type="text" class="form-control" placeholder="Enter Name" readonly name="Email address" value="<?= $_SESSION['email'] ?>" required >
								</div>
								<div class="form-group form-floating-label">
									<label>Current Password</label>
									<input type="acc_password" id="cur_pass1" class="form-control" placeholder="Enter Current Password" name="cur_pass1" required >
									<span toggle="#cur_pass1" class="fa fa-fw fa-eye field-icon toggle-password"></span>
								</div>
								<div class="form-group form-floating-label">
									<label>New Password</label>
									<input type="acc_password" id="new_pass1" class="form-control" placeholder="Enter New Password" name="new_pass1" required >
									<span toggle="#new_pass1" class="fa fa-fw fa-eye field-icon toggle-password"></span>
								</div>
								<div class="form-group form-floating-label">
									<label>Confirm Password</label>
									<input type="acc_password" id="con_pass1" class="form-control" placeholder="Confirm Password" name="con_pass1" required >
									<span toggle="#con_pass1" class="fa fa-fw fa-eye field-icon toggle-password"></span>
								</div>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
							<button type="submit" class="btn btn-primary">Change</button>
						</div>
						</form>
					</div>
				</div>
			</div>
			<!-- Modal Edit Account Password -->
			<div class="modal fade" id="edit_account_profile" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
				<div class="modal-dialog" role="document">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title" id="exampleModalLabel">Update Profile</h5>
							<button type="button" class="close" data-dismiss="modal" aria-label="Close">
								<span aria-hidden="true">&times;</span>
							</button>
						</div>
						<div class="modal-body">
							<form method="POST" action="model/edit_profile.php" enctype="multipart/form-data">
							<input type="hidden" name="size" value="1000000">
								<div class="text-center">
									<div id="my_camera" style="height: 250;" class="text-center">
										<?php if(empty($_SESSION['avatar'])): ?>
											<img src="assets/img/person.png" alt="..." class="img img-fluid" width="250" >
										<?php else: ?>
											<img src="<?= preg_match('/data:image/i', $_SESSION['avatar']) ? $_SESSION['avatar'] : 'assets/uploads/avatar/'.$_SESSION['avatar'] ?>" alt="..." class="img img-fluid" width="250" >
										<?php endif ?>
									</div>
									<div id="profileImage">
										<input type="hidden" name="profileimg">
									</div>
									<div class="form-group">
										<input type="file" class="form-control" name="img" accept="image/*">
									</div>
								</div>
						</div>
						<div class="modal-footer">
							<input type="hidden" value="<?= $_SESSION['uid']; ?>" name="id">
							<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
							<button type="submit" class="btn btn-primary">Update</button>
						</div>
						</form>
					</div>
				</div>
			</div>

			<!-- Main Footer -->
			<?php include 'templates/main-footer.php' ?>
			<!-- End Main Footer -->
			
		</div>
	</div>
	<?php include 'templates/footer.php' ?>
	<script>
		
		function all_request(){
			$('input[type="search"]').val("").keyup()
			$("#request").removeClass("btn-border");
			$("#released").addClass("btn-border");
			$("#pending").addClass("btn-border");
		}
		function all_pending(){
			$('input[type="search"]').val("Pending").keyup()
			$("#pending").removeClass("btn-border");
			$("#request").addClass("btn-border");
			$("#released").addClass("btn-border");
		}
		function released(){
			$('input[type="search"]').val("For Release").keyup()
			$("#released").removeClass("btn-border");
			$("#pending").addClass("btn-border");
			$("#request").addClass("btn-border");
		}
		
		function proccess_link(req)
			{
				$.ajax({
					url: 'model/update_request.php',
					data: {'req' : req},
					type: 'POST',
					dataType: 'JSON'
				});
			}
		function disapproved(req)
			{
				$.ajax({
					url: 'model/disapproved_request.php',
					data: {'req' : req},
					type: 'POST',
					dataType: 'JSON'
				});
			}

        $(document).ready(function() {
            $('#residenttable').DataTable();
        });
    </script>
</body>
</html>