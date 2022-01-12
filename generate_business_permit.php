<?php include 'server/server.php' ?>
<?php 
    $id = $_GET['id'];
    $id_req = $_GET['id_req'];
	$query = "SELECT * FROM tblpermit WHERE id='$id'";
    $result = $conn->query($query);
    $permit = $result->fetch_assoc();

    $query1 = "SELECT * FROM tblofficials JOIN tblposition ON tblofficials.position=tblposition.id AND `status`='Active' ORDER BY `order` ASC";
    $result1 = $conn->query($query1);
    $officials = array();
    while($row = $result1->fetch_assoc()){
    $officials[] = $row; 
    }
    
    $query2 = "SELECT * FROM tblpayments WHERE id_docs='$id'";
    $orcr = $conn->query($query2)->fetch_assoc();

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
<title>Business Permit -  E-Request</title>
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
                            <div class="card-title">Barangay Certificate</div>
                            <div class="card-tools">
                                <button class="btn btn-info btn-border btn-round btn-sm" onclick="printDiv('printThis')">
                                    <i class="fa fa-print"></i>
                                    Print Certificate
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body m-5" id="printThis">
                        <div class="d-flex flex-wrap justify-content-around" style="border-bottom:1px solid black">
                            <div class="text-center">
                                <img src="assets/uploads/<?= $city_logo ?>" class="img-fluid" width="150">
                            </div>
                            <div class="text-center" style= "color:black">
                                <h3 class="mb-0">Republic of the Philippines</h3>
                                <h3 class="mb-0">Municipality of <?= ucwords($town) ?></h3>
                                <h3 class="fw-bold mb-0 text-uppercase"><?= ucwords($brgy) ?></i></h3>
                                <p><i>Telephone No. <?= $number ?></i></p>
                                <h2 class="mt-4 fw-bold">OFFICE OF THE PUNONG BARANGAY</h3>
                            </div>
                            
                            <div class="text-center">
                                <img src="assets/uploads/<?= $brgy_logo ?>" class="img-fluid" width="150">
                            </div>
                        </div>
                        <div class="row mt-2" style= "color:black">
                            <div class="col-md-3">
                                <div class="text-center p-3" style = "border:2px solid black">
                                   
                                    <?php if(!empty($officials)):?>
                                        <?php foreach($officials as $row): ?>
                                            <h4 class="mt-3 fw-bold mb-0 text-uppercase"><?= ucwords($row['name']) ?></h4>
                                            <h4 class="mb-2"><?= ucwords($row['position']) ?></h4>
                                        <?php endforeach ?>
                                    <?php endif ?>

                                </div>
                            </div>
                            <div class="col-md-9" style= "color:black">
                            
                                <div class="text-center">
                                    <h1 class="mt-4 fw-bold mb-3">BARANGAY KLIRANS</h1>
                                    <h5 class="mb-2">(Para sa Pagpapatayo/Pagpapatuloy ng Isang Negosyo o Kalakal)</h5>
                                </div>
                                <h2 class="mt-5 fw-bold">PARA SA KAALAMAN NG LAHAT:</h2>
                                <h2 class="mt-3 " style="text-indent: 40px;">Ito ay nagpapatunay na si <span class= "text-uppercase fw-bold" style="font-size:20px"><?= ucwords($permit['owner1']) ?></span>, ay nakapaghain na sa tanggapang ito noong <span class="fw-bold" >ika-
                                 <?=date(' d ')?>ng  <?=date(' F Y ')?></span>  para sa pagpapatayo/ pagpapatuloy ng isang kalakal o negosyo na <span class = "text-uppercase"style="font-size:25px text-uppercase"><?= ucwords($permit['name']) ?></span> na matatagpuan sa Sitio <?= ucwords($permit['sitio']) ?> ng barangay na ito; ang nasabing kahilingan
                                 na napatunayang hindi lumabag sa mga umiiral na mga ordinansa, reglamento at mga alituntunin, ay pinagkalooban ng hiniling na Barangay Klirans at ang tanggapang ito ay walang anumang tutol para pagkalooban ng <span class= " fw-bold" >" Mayor's Permit"</span> para sa pagpapatayo/ pagpapatuloy ng nasabing negosyo o kalakal.
                                <h2 class="mt-3" style="text-indent: 40px;">Ang klirans ay ipinagkaloob bilang pagtupad sa Seksyon 152 ng R.A. 7160 na lalong kilala bilang "Local Government Code of 1991".</h2>
                            <div class="col-md-12">
                                <div class="p-5 text-right mr-3 mt-5">
                                <h4 class="mb-0 mt-5"><i></i>_________________________</h4>
                                    <h2 class="fw-bold mb-1  text-uppercase"><?= empty($captain['name']) ? :  ucwords($captain['name']) ?></h2>
                                    <p class="mr-3">PUNONG BARANGAY</p>
                            </div>
                    </div>
                    
                            <div class="col-md-10 mb-4 mt-5">
                                <h4 class="mb-0" ><i>OR No.</i> :<span class="fw-bold"> <?= $_SESSION['orno'] ?></span></h4>
                                <h4 class="mt-2"><i>CTC No.</i>:<span class="fw-bold"> <?= $_SESSION['CTCno'] ?></span></h4>
                                <h4 class="mb-0"><i>Petsa</i>:<span class="fw-bold"><?= date('F d, Y') ?></span> </h4>
                                <h4 class="mb-0 "><i>Kinuha sa</i>:  <span class="fw-bold">   <?= ucwords($brgy.','.$town.','.$province) ?> </span></h4>

                            </div>
                            <p class="ml-3"><i>(*Pasubali: "Hindi pinahihintulutan ng walang orihinal na resibo at silyong panatak")</i></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="pment" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Create Payment</h5>
                
            </div>
            <div class="modal-body">
                <form method="POST" action="model/save_pment.php">
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
                        <label>O.R. Number</label>
                        <input type="text" class="form-control" name="ORno" placeholder="Enter O.R. Number" required>
                    </div>
                    <div class="form-group">
                        <label>CTC Number</label>
                        <input type="text" class="form-control" name="CTCno" placeholder="Enter CTC Number" required>
                    </div>
                    <div class="form-group">
                        <label>Payment Details(Optional)</label>
                        <textarea class="form-control" placeholder="Enter Payment Details" name="details">Business Permit Payment</textarea>
                    </div>
            </div>
            <div class="modal-footer">
                <input type="hidden" class="form-control" name="id" value="<?= ucfirst($permit['id']) ?>">
                <input type="hidden" class="form-control" name="name" value="<?= ucfirst($permit['name']) ?>">
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