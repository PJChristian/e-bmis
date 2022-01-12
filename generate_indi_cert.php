<?php include 'server/server.php' ?>
<?php 
    $id = $_GET['id'];
	$query = "SELECT * FROM tblresident WHERE id='$id'";
    $result = $conn->query($query);
    $resident = $result->fetch_assoc();

    $query1 = "SELECT * FROM tblofficials JOIN tblposition ON tblofficials.position=tblposition.id WHERE tblposition.position NOT IN ('SK Chairrman','Secretary','Treasurer')
                AND `status`='Active' ORDER BY `order` ASC";
    $result1 = $conn->query($query1);
    $officials = array();
	while($row = $result1->fetch_assoc()){
		$officials[] = $row; 
	}

    $transaction = $_SESSION['transaction']; 
    $query2 = "SELECT * FROM tblpayments WHERE id_docs='$transaction'";
    $purpose = $conn->query($query2)->fetch_assoc();

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
	<title>Certificate of Indigency -  E-Request</title>
    
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
								<h2 class="text-white fw-bold">Generate Certificate</h2>
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
										<div class="card-title">Certificate of Indigency</div>
										<div class="card-tools">
											<button class="btn btn-info btn-border btn-round btn-sm" onclick="printDiv('printThis')">
												<i class="fa fa-print"></i>
												Print Certificate
											</button>
										</div>
									</div>
								</div>
								<div class="card-body m-5" id="printThis">
                                    <div class="d-flex flex-wrap justify-content-around" style="border-bottom:1px ">
                                        <div class="text-center">
                                            <img src="assets/uploads/<?= $brgy_logo ?>" class="img-fluid" width="170">
										</div>
										<div class="text-center ">
                                            <h3 class="mb-0">Republic of the Philippines</h3>
                                            <h3 class="mb-0">Province of <?= ucwords($province) ?></h3>
											<h3 class="mb-0">Municipality of <?= ucwords($town) ?></h3>
											<h3 class="fw-bold mb-0"><?= ucwords($brgy) ?></i></h3>
                                            <h3 class="mt-4 fw-bold">OFFICE OF THE PUNONG BARANGAY</h3>
                                            <h4 class="mt-4 fw-bold mb-5" style="font-size:30px;"><u>CERTIFICATE OF INDIGENCY</u></h4>
										</div>
                                        <div class="text-center">
									</div>
                                    <div class="row mt-2">
                                            <h2 class="mt-3" style="text-indent: 60px;">THIS IS TO CERTIFY THAT <span class="fw-bold" style="font-size:20px"><?= ucwords($resident['firstname'].' '.$resident['middlename'].' '.$resident['lastname']) ?></span>
                                            of <?= ucwords($brgy) ?>, <?= ucwords($town) ?>, <?= ucwords($province) ?> <span class="mt-3" style="text-indent: 60px;">belongs to an indigent family and needs an immediate:</span>
                                            <h3 class="mt-3" style="text-indent: 100px;"> <input type="checkbox" id="MedicalAssistance"> Medical Assistance</h3>
                                            <h3 class="mt-3" style="text-indent: 100px;"> <input type="checkbox" id="FinancialAssistance"> Financial Assistance</h3>
                                            <h3 class="mt-3" style="text-indent: 100px;"> <input type="checkbox" id="TransportationAssistance"> Transportation Assistance</h3>
                                            <h3 class="mt-3" style="text-indent: 100px;"> <input type="checkbox" id="ScholarshipProgram"> Scholarship Program</h3>
                                            <h3 class="mt-3" style="text-indent: 100px;"> <input type="checkbox" id="FoodsAssistance"> Foods Assistance</h3>
                                            <h3 class="mt-3" style="text-indent: 100px;"> <input type="checkbox" id="ShelterAssistance"> Shelter Assistance</h3>
                                            <h3 class="mt-3" style="text-indent: 100px;"> <input type="checkbox" id="SummerJob"> Summer Job</h3>
                                            <h3 class="mt-3" style="text-indent: 100px;"> <input type="checkbox" id="others"> OTHERS: <input type="text" id="others" value="<?= $_SESSION['other']?>"></h3>
                                           

                                            <h2 class="mt-5" style="text-indent: 40px;">This certification/clearance is issued for whatever legal purpose it may serve him/her best.</span>
                                            <h2 class="mt-3" style ="text-indent: 40px;">Issued this <span class="fw-bold" style="font-size:25px"><u><?= date('d') ?></u></span>th day of <u><?=date('F Y')?></u>, at <span style="font-size:20px"><?= ucwords($brgy) ?>, <?= ucwords($town) ?>, <?= ucwords($province) ?></span>. 
                                        </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="p-3 text-right mr-3">
                                            <h4 class="mb-0"><i></i>_________________________</h4>
                                                <h2 class="fw-bold mb-1 text-uppercase"><?= empty($captain['name']) ? :  ucwords($captain['name']) ?></h2>
                                                <p class="mr-3">PUNONG BARANGAY</p>
                                        </div>
                                        
								</div>
                                    </div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
            <!-- Modal -->
            <div class="modal fade" id="pment" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true"  data-backdrop="static" data-keyboard="false">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Create Payment</h5>
                            
                        </div>
                        <div class="modal-body">
                            <form method="POST" action="model/save_pment.php" >
                                <div class="row">
                                    <div class="col">
                                        <div class="form-group">
                                            <label>Amount</label>
                                            <input type="number" class="form-control" name="amount" placeholder="Enter amount to pay" required>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="form-group">
                                            <label>Date Issued</label>
                                            <input type="date" class="form-control" name="date" value="<?= date('Y-m-d') ?>">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
									<label>Purpose</label>
									<select class="form-control" name="purpose" id="option-purpose" required>
                                        <option disabled selected>Select Purpose</option>
                                        <option value="Medical Assistance">Medical Assistance</option>
                                        <option value="Financial Assistance">Financial Assistance</option>
                                        <option value="Transportation Assistance">Transportation Assistance</option>
                                        <option value="Scholarship Program">Scholarship Program</option>
                                        <option value="Foods Assistance">Foods Assistance</option>
                                        <option value="Shelter Assistance">Shelter Assistance</option>
                                        <option value="Summer Job">Summer Job</option>
                                        <option value="others" id="others">Others</option>
                                    </select>
                                </div>
                                <div class="form-group" id="other-purposes">
                                    <label>Other Purposes</label>
                                    <input type="text" class="form-control" name="other-purpose" placeholder="Enter other purpose">
                                </div>
                                <div class="form-group">
                                    <label>Payment Details(Optional)</label>
                                    <textarea class="form-control" placeholder="Enter Payment Details" name="details">Certificate of Indigency Payment</textarea>
                                </div>
                        </div>
                        <div class="modal-footer">
                            <input type="hidden" class="form-control" name="id" value="<?= ucfirst($resident['id']) ?>">
                            <input type="hidden" class="form-control" name="name" value="<?= ucwords($resident['firstname'].' '.$resident['middlename'].' '.$resident['lastname']) ?>">
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
                $('#option-purpose').change(function(){
                    if($(this).val() != "others") {
                        $('#other-purposes').hide();
                    }
                    if($(this).val() == "others") {
                        $('#other-purposes').show();
                    }
                    
                })

            $('#other-purposes').hide();
            $(document).ready(function() {
                //alert('<?= $transaction ?>');

                //$('input[type="checkbox"]').attr('checked', 'checked');
                var trans = '<?= str_replace(' ', '', $transaction)?>';
                $('#' + trans).attr('checked', 'checked');

                if(trans == "others")
                {
                    $('#' + trans).attr('checked', 'checked');
                    $('#others').val("Test")
                }


                
            });
            function openModal(){
                $('#pment').modal('show');
            }
            function printDiv(divName) {
                var printContents = document.getElementById(divName).innerHTML;
                var originalContents = document.body.innerHTML;

                document.body.innerHTML = printContents;

                window.print();

                document.body.innerHTML = originalContents;
            }
    </script>
</body>
</html>