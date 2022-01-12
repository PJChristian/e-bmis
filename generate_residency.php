<?php include 'server/server.php' ?>
<?php 
    $id = $_GET['id'];
    $id_req = $_GET['id_req'];
	$query = "SELECT * FROM tblresident WHERE id='$id'";
    $result = $conn->query($query);
    $resident = $result->fetch_assoc();

    $query1 = "SELECT * FROM tblofficials JOIN tblposition ON tblofficials.position=tblposition.id
                AND `status`='Active' ORDER BY `order` ASC";
    $result1 = $conn->query($query1);
    $officials = array();
	while($row = $result1->fetch_assoc()){
		$officials[] = $row; 
	}

    $c = "SELECT * FROM tblofficials JOIN tblposition ON tblofficials.position=tblposition.id WHERE tblposition.order=1";
    $captain = $conn->query($c)->fetch_assoc();
    $s = "SELECT * FROM tblofficials JOIN tblposition ON tblofficials.position=tblposition.id WHERE tblposition.order=10";
    $sec = $conn->query($s)->fetch_assoc();
    $t = "SELECT * FROM tblofficials JOIN tblposition ON tblofficials.position=tblposition.id WHERE tblposition.order=11";
    $treas = $conn->query($t)->fetch_assoc();

?>
<!DOCTYPE html>
<html lang="en">
<head>
	<?php include 'templates/header.php' ?>
	<title>Residency -  E-Request</title>
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
								<h2 class="text-white fw-bold">Generate Residency</h2>
							</div>
						</div>
					</div>
				</div>
				<div class="page-inner">
					<div class="row mt--2">
						<div class="col-md-12">

                            <?php if(isset($_SESSION['message'])): ?>
                                <div class="alert alert-<?php echo $_SESSION['success']; ?> <?= $_SESSION['success']=='danger' ? 'bg-danger text-light' : null ?>" role="alert">
                                    <?php echo $_SESSION['message']; ?>
                                </div>
                            <?php unset($_SESSION['message']); ?>
                            <?php endif ?>

                            <div class="card">
								<div class="card-header">
									<div class="card-head-row">
										<div class="card-title">Residency</div>
										<div class="card-tools">
											<button class="btn btn-info btn-border btn-round btn-sm" onclick="printDiv('printThis')">
												<i class="fa fa-print"></i>
												Print Certificate
											</button>
										</div>
									</div>
								</div>
								<div class="card-body m-5" id="printThis">
                                    <div class="d-flex flex-wrap justify-content-around">
                                        <div class="text-center">
                                            <img src="assets/uploads/<?= $city_logo ?>" class="img-fluid" width="150">
										</div>
										<div class="text-center" style= "color:black">
                                            <h3 class="mb-0">Republika ng Pilipinas</h3>
											<h3 class="mb-0">Lalawigan ng Silangang Mindoro</h3>
                                            <h3 class="mb-0">Bayan ng <?= ucwords($town) ?></h3>
											<h3 class="fw-bold mb-0 text-uppercase"><?= ucwords($brgy) ?></i></h3>
                                            <h2 class="mt-5 fw-bold ">TANGGAPAN NG PUNONG BARANGAY</h3>
                                            <h1 class="mt-5 fw-bold">PAGPAPATUNAY</h1>
										</div>
                                        <div class="text-center">
                                            <img src="assets/uploads/<?= $brgy_logo ?>" class="img-fluid" width="150">
										</div>
									</div>
                                    <h3 class="mt-5">PARA SA KAALAMAN NG LAHAT:</h3>
                                            <div class="row mt-2">
                                            <h2 class="mt-3" style="text-indent: 60px;">Ito ay nagbibigay pahintulot kay <span class="fw-bold" style="font-size:20px"><?= ucwords($resident['firstname'].' '.$resident['middlename'].' '.$resident['lastname']) ?></span> ay naninirahan sa Sitio <?= ucwords($resident['purok']) ?>
                                            , <?= ucwords($brgy) ?>, <?= ucwords($town) ?>, <?= ucwords($province) ?> 
                                            <h2 class="mt-5" style="text-indent: 60px;">Ang pagpapatunay na ito ay isinagawa sa kahilingan ni <span class="fw-bold" style="font-size:20px"><?= ucwords($resident['firstname'].' '.$resident['middlename'].' '.$resident['lastname']) ?></span>
                                            para sa anupamang mahalagang paggagamitan nito.
                                            <h2 class="mt-3" style ="text-indent: 60px;">Iginawad ngayong ika -  <span style="font-size:25px"><?= date('d') ?></span> ng <?=date('F Y')?> dito sa Tanggapan ng Pamahalaang <span style="font-size:20px"><?= ucwords($brgy) ?>, <?= ucwords($town) ?>, <?= ucwords($province) ?></span>. 
                                        </div>
                                <div class="col-md-12">
                                            <div class="p-3 text-right ">
                                            <h4 class="mb-0 mt-5"><i></i>_________________________</h4>
                                                <h2 class="fw-bold mb-0 text-uppercase"><?= ucwords($captain['name']) ?></h2>
                                                <p class="mr-3">PUNONG BARANGAY</p>
                                                </div>
                                        </div>
							</div>
						</div>
					</div>
				</div>
			</div>
            </div>
            
            

            <!-- Modal -->
            <div class="modal fade" id="pment" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Create Payment</h5>
                            
                        </div>
                        <div class="modal-body">
                            <form method="POST" action="model/save_pment.php" >
                                <div class="form-group">
                                    <label>Amount</label> 
                                    <input type="number" class="form-control" name="amount" placeholder="Enter amount to pay" required>
                                </div>
                                <div class="form-group">
                                    <label>Date Issued</label>
                                    <input type="date" class="form-control" name="date" value="<?= date('Y-m-d') ?>">
                                </div>
                                <div class="form-group">
                                    <label>Payment Details(Optional)</label>
                                    <textarea class="form-control" placeholder="Enter Payment Details" name="details">Barangay Clearance Payment</textarea>
                                </div>
                        </div>
                        <div class="modal-footer">
                            <input type="hidden" class="form-control" name="id" value="<?= ucfirst($resident['id']) ?>">
                            <input type="hidden" name="name" value="<?= ucwords($resident['firstname'].' '.$resident['middlename'].' '.$resident['lastname']) ?>">
                            <button type="button" class="btn btn-secondary" onclick="goBack()">Close</button>
                            <button type="submit" class="btn btn-primary">Save</button>
                        </div>
                        </form>
                    </div>
                </div>
            </div>

			<!-- Main Footer -->
			<?php include 'templates/main-footer.php' ?>
			<!-- End Main Footer -->
			<?php if(!isset($_GET['closeModal'])){ ?>
            
                <script>
                    setTimeout(function(){ openModal(); }, 1000);
                </script>
            <?php } ?>
		</div>
		
	</div>
	<?php include 'templates/footer.php' ?>
    <script>
            function openModal(){
                $('#pment').modal('show');
            }

            function printDiv(divName, id_req) {
                var printContents = document.getElementById(divName).innerHTML;
                var originalContents = document.body.innerHTML;

                document.body.innerHTML = printContents;

                window.print();

                document.body.innerHTML = originalContents;

                $.ajax({
					url: 'model/update_request.php',
					data: {'id' : 1, 'req' : id_req},
					type: 'POST',
					dataType: 'JSON'
				});
            }
    </script>
</body>
</html>