<?php include 'server/server_request.php' ?>
<?php 
	if($_SESSION['email'] == '')
	{
		$email = 'Guest';
		$account_type = 'Guest User';		
	} else {
		$id = $_SESSION['uid'];
		$email = $_SESSION['email'];
		$account_type = $_SESSION['account_type'];
		$image = preg_match('/data:image/i', $_SESSION['image']) ? $_SESSION['image'] : 'assets/uploads/avatar/'.$_SESSION['image'];	

		$query = "SELECT * FROM tbl_request WHERE id_account ='$id'  ORDER BY `date_request` DESC";
		$result = $conn->query($query);

		$users = array();
		while($row = $result->fetch_assoc()){
			$users[] = $row; 
		}
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<?php include 'templates/header.php' ?>
	<title>e-REQUEST</title>
	<style>
		#create_account .modal{
			display: block !important;
		}

		#create_account .modal-dialog{
			overflow-y: initial !important;
		}
		#create_account .modal-body{
			height: 70vh;
			overflow-y: auto;
		}


	</style>

</head>
<body>
<?php include 'templates/loading_screen.php' ?>
	<div class="wrapper">
		<!-- Main Header -->
		<div class="main-header">
		<!-- Logo Header -->
		<div class="logo-header" data-background-color="green">
			
			<a href="dashboard.php" class="logo">
				<img src="assets/img/logo.png" alt="navbar brand" class="navbar-brand"> <span class="text-light ml-2 fw-bold" style="font-size:20px">E-REQUEST</span>
			</a>
			<button class="topbar-toggler more"><i class="icon-options-vertical"></i></button>
        <div class="nav-toggle">
            <button class="btn btn-toggle toggle-sidebar">
                <i class="icon-menu"></i>
            </button>
        </div>
		</div>
		<!-- End Logo Header -->
		<!-- Navbar Header -->
		<nav class="navbar navbar-header navbar-expand-lg" data-background-color="green">
			
			<div class="container-fluid">
				<ul class="navbar-nav topbar-nav ml-md-auto align-items-center">
					<li class="nav-item dropdown hidden-caret">
                    <a class="nav-link dropdown-toggle" href="#" id="messageDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="icon-settings"></i>
                    </a>
					<ul class="dropdown-menu messages-notif-box animated fadeIn" aria-labelledby="messageDropdown">

					<a class="see-all" href="model/user_logout.php">Sign Out<i class="icon-logout"></i> </a>
						</a>
						</ul>
					</li>
				</ul>
			</div>
		</nav>
		<!-- End Navbar -->
	</div>
		<!-- End Main Header -->
		<!-- Sidebar -->
		<div class="sidebar sidebar-style-2">			
			<div class="sidebar-wrapper scrollbar scrollbar-inner">
				<div class="sidebar-content">
					<div class="user">
						<div class="avatar-sm float-left mr-2">
							<?php if(!empty($_SESSION['image'])): ?>
								<img src="<?= $image ?>" alt="..." class="avatar-img rounded-circle">
							<?php else: ?>
								<img src="assets/img/person.png" alt="..." class="avatar-img rounded-circle">
							<?php endif ?>
						
						</div>
						<div class="info">
							<a data-toggle="collapse" href="<?= isset($_SESSION['email']) && $_SESSION['account_type']=='User' ? '#collapseExample' : 'javascript:void(0)' ?>" aria-expanded="true">
							<span>
							<?= isset($_SESSION['email']) ? ($email) : 'Guest User' ?>
								<span class="user-level"><?= isset($_SESSION['account_type']) ? ucfirst($account_type) : 'User' ?></span>
							<?= isset($_SESSION['username']) && $account_type =='User' ? '<span class="caret"></span>' : null ?> 
							</span>
							</a>
							<div class="clearfix"></div>
							<div class="collapse in" id="collapseExample">
								<ul class="nav">
									<li>
										<a href="#edit_account_profile" data-toggle="modal">
											<span class="link-collapse">Edit Profile</span>
										</a>
										<a href="#changeaccountpass" data-toggle="modal">
											<span class="link-collapse">Change Password</span>
										</a>
									</li>
								</ul>
							</div>
						</div>
					</div>
					<ul class="nav nav-success">
						<li class="nav-item">
							<a href="#" >
								<i class="fas fa-home"></i>
								<p>My Account</p>
							</a>
						</li>
					</ul>
				</div>
			</div>
		</div>
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
											<a href="#add_request" id="btn_request" data-toggle="modal" class="btn btn-info btn-border btn-round btn-sm">
												<i class="fa fa-plus"></i>
												Request Documents
											</a>
											<a href="#req_permit" id="btn_permit" data-toggle="modal" class="btn btn-info btn-border btn-round btn-sm">
												<i class="fa fa-plus"></i>
												Request Business Permit
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
													<th scope="col">Type of Documents</th>
													<th scope="col">Purpose</th>
													<th scope="col">Status</th>
													<th scope="col">Date Request</th>
													<th scope="col">Date Released</th>
												</tr>
											</thead>
											<tbody>
												<?php if($account_type == 'User'): ?>
													<?php $no=1; foreach($users as $row): ?>
													<tr>
														<td><?= $no ?></td>
														<td>
															<?= ucwords($row['tod']) ?>
														</td>
														<td><?= $row['purpose'] ?></td>
														<td><?= $row['status'] ?></td>
														<td><?= $row['date_request'] ?></td>
														<td><?= $row['date_released'] ?></td>														
													</tr>
													<?php $no++; endforeach ?>
												<?php else: ?>
													<tr>
														<td colspan="6" class="text-center">No Available Data</td>
													</tr>
												<?php endif ?>
											</tbody>
											<tfoot>
												<tr>
													<th scope="col">No.</th>
													<th scope="col">Type of Documents</th>
													<th scope="col">Purpose</th>
													<th scope="col">Status</th>
													<th scope="col">Date Request</th>
													<th scope="col">Date Released</th>
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
                                        <option value="Certification/Clearance">Certification/Clearance</option>
                                        <option value="Certificate of Indigency">Certificate of Indigency</option>
										<option value="Certificate of Residency">Certificate of Residency</option>
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
                                    <label>Sitio</label>
                                    <input type="text" class="form-control" placeholder="Enter Sitio" name="sitio" required>
                                </div>
								<div class="form-group">
                                    <label>Date Applied Nature</label>
                                    <input type="date" class="form-control" name="applied" value="<?= date('Y-m-d'); ?>" required>
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
							<a href="#" class="pull-right" onClick="login_account()"> Login Your Account </a>
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
                                    </div>
									<div class="row">
										<div class="col">
                                            <div class="form-group">
                                                <label>Password</label>
                                                <input type="password" class="form-control" placeholder="Password" name="password" id="password" required>
												<span toggle="#password" class="fa fa-fw fa-eye field-icon toggle-password"></span>
											</div>
                                        </div>
										<div class="col">
                                            <div class="form-group">
                                                <label id="lbl_cpass">Confirm Password</label>
                                                <input type="password" class="form-control" placeholder="Password" name="cpassword" id="cpassword" required>
												<span toggle="#cpassword" class="fa fa-fw fa-eye field-icon toggle-password"></span>
												<small class="form-text text-danger" id="alert-message"></small>
											</div>
                                        </div>	
																		
									</div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
							<input type="checkbox" name="authorize" id="authorize">	
							<label for="#authorize">I hereby authorize that the information above is true valid.</label>	
                            <button type="submit" id="btn_submit" class="btn btn-primary"> Create Account</button>
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
							<a href="login.php">Back to Home</a>
                        </div>
                        <div class="modal-body">
							<form method="POST" action="model/user_login.php">
								<div class="form-group form-floating-label">
									<input id="email" name="email" type="email" class="form-control input-border-bottom" required>
									<label for="email" class="placeholder">Email Address</label>
								</div>
								<div class="form-group form-floating-label">
									<input id="user-password" name="password" type="password" class="form-control input-border-bottom" required>
									<label for="password" class="placeholder">Password</label>
									<span toggle="#user-password" class="fa fa-fw fa-eye field-icon toggle-password"></span>
									
								</div>
                        </div>
                        <div class="modal-footer">
							<div class="form-action">
								Don't have an account? <a href="#" onClick="create_an_account()"> Create An Account </a>
								<button type="submit" class="btn btn-success btn-rounded btn-login"> Sign In</button>
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
									<label>Username</label>
									<input type="text" class="form-control" placeholder="Enter Name" readonly name="email" value="<?= $_SESSION['email'] ?>" required >
								</div>
								<div class="form-group form-floating-label">
									<label>Current Password</label>
									<input type="password" id="cur_pass" class="form-control" placeholder="Enter Current Password" name="cur_pass" required >
									<span toggle="#cur_pass" class="fa fa-fw fa-eye field-icon toggle-password"></span>
								</div>
								<div class="form-group form-floating-label">
									<label>New Password</label>
									<input type="password" id="new_pass" class="form-control" placeholder="Enter New Password" name="new_pass" required >
									<span toggle="#new_pass" class="fa fa-fw fa-eye field-icon toggle-password"></span>
								</div>
								<div class="form-group form-floating-label">
									<label>Confirm Password</label>
									<input type="password" id="con_pass" class="form-control" placeholder="Confirm Password" name="con_pass" required >
									<span toggle="#con_pass" class="fa fa-fw fa-eye field-icon toggle-password"></span>
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
			<!-- Modal Edit Account Profile -->
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
							<form method="POST" action="model/update_profile.php" enctype="multipart/form-data">
							<input type="hidden" name="size" value="1000000">
								<div class="text-center">
									<div id="my_camera" style="height: 250;" class="text-center">
										<?php if(empty($_SESSION['image'])): ?>
											<img src="assets/img/person.png" alt="..." class="img img-fluid" width="250" >
										<?php else: ?>
											<img src="<?= $image ?>" alt="..." class="img img-fluid" width="250" >
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
	<script src="assets/js/plugin/datatables/datatables.min.js"></script>
	<script>
		$("#btn_submit").click(function(){
			password =	$("#password").val();
			c_pass 	 =	$("#cpassword").val()
			if(password != c_pass)
			{
				$("#cpassword").addClass("is-invalid");
				$('#alert-message').text("Password did not match. Please try again.");
			}
		});
		if($('input[name="authorize"]').is(':checked'))
		{
			alert("test");
		}

		$(document).ready(function() {
            $('#residenttable').DataTable();
        });

		var _user_ = '<?= $account_type ?>';
		function create_an_account()
		{
			$('#login').modal('hide');
			$('#create_account').modal('show');
		}

		function login_account()
		{
			$('#create_account').modal('hide');
			$('#login').modal('show');
		}

		$(document).ready(function(){
			if(_user_ == 'Guest User' || _user_ == '')
			{
				$('#login').modal('show')
			}

			$('#authorize').change(function(){
                    if($(this).is(":checked")) {
                        $('#btn_submit').prop('disabled', false);
                    }
					else {
						$('#btn_submit').prop('disabled', true);
					}
                                        
                })

				$('#btn_submit').prop('disabled', true);
		})
	</script>
</body>
</html>