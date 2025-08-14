<?php

session_start();

putenv("TZ=IST");
date_default_timezone_set("Asia/Kolkata");
require_once('./include/database.php');

if(isset($_SESSION['userlogin']) && count($_SESSION['userlogin']) > 0)
{

}
else
{
    header("Location: logout.php");
}

if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'Save')
{
    //print_r($_REQUEST);

    $InsertQuery = "INSERT INTO  lng_tanker_unloading_records SET
    unloading_date = '".$_REQUEST['unloading_date']."',
    vehicle_number = '".$_REQUEST['vehicle_number']."',
    invoice_qty_kgs = '".$_REQUEST['invoice_qty_kgs']."',
    invoice_number = '".$_REQUEST['invoice_number']."',
    totalizer_value_kgs_opening = '".$_REQUEST['totalizer_value_kgs_opening']."',
    totalizer_value_kgs_closing = '".$_REQUEST['totalizer_value_kgs_closing']."',
    total_unload_qty_kgs = '".$_REQUEST['total_unload_qty_kgs']."',
    diff_qty_kgs = '".$_REQUEST['diff_qty_kgs']."',
    unload_start_time_hrs = '".$_REQUEST['unload_start_time_hrs']."',
    unload_end_time_hrs = '".$_REQUEST['unload_end_time_hrs']."',
    total_time_taken = '".$_REQUEST['total_time_taken']."',
    operator_name = '".$_REQUEST['operator_name']."',
    invoice_date = '".$_REQUEST['invoice_date']."',
    remarks = '".$_REQUEST['remarks']."',
    ro_id = '".$_SESSION['ro_details']['id']."',
    operter_id = '".$_SESSION['userlogin']['id']."'
    ";

    $mysqli->query($InsertQuery);

    $insert_id = $mysqli->insert_id;

   // print_r($_REQUEST);

   

  

    $imgDir = "./uploads/";

    $upload_arr = array();

    foreach($_FILES as $key=>$file)
    {
       // print_r($file['name']);

        for($i=0;$i<count($file['name']);$i++)
        {
            $fileName=$file['name'][$i];
          //  $upload_arr[] = $fileName;
            $tmpFileName = $file['tmp_name'][$i];
            $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

            $image = time().'-'.$i.'.'.$fileExt;

            $upload_arr[] = $image;

			move_uploaded_file($tmpFileName, $imgDir.$image);


        }
        
    }

    $upload_success = "Records added successfully";

    $upload_json = json_encode($upload_arr);
    $update_query = "update lng_tanker_unloading_records SET file_list = '".$upload_json."' where id = '".$insert_id."'";

             $mysqli->query($update_query);
   


}

?><!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Ultra Gas & Energy Admin</title>

    <!-- Custom fonts for this template -->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">

    <!-- Custom styles for this page -->
    <link href="vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">

</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.php">
                
                <div class="sidebar-brand-text mx-3"><img src='./img/logo.png'  height="70" width="160" ></div>
            </a>

            <!-- Divider -->
            <hr class="sidebar-divider my-0">

            <!-- Nav Item - Dashboard -->
             <!--
            <li class="nav-item">
                <a class="nav-link" href="index.php">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Dashboard</span></a>
            </li>

-->

            <!-- Divider -->
            <hr class="sidebar-divider">

          
             <!-- Nav Item - Charts -->
         <!--   <li class="nav-item active">
                <a class="nav-link" href="unloading.php">
                    <i class="fas fa-fw fa-chart-area"></i>
                    <span>Tanker Unloading</span></a>
            </li>
-->
            <!-- Nav Item - Tables -->
           <!-- <li class="nav-item">
                <a class="nav-link" href="mis.php">
                    <i class="fas fa-fw fa-table"></i>
                    <span>Mis</span></a>
            </li>

-->

                     <!-- Nav Item - Pages Collapse Menu -->
          <!--  <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTwo"
                    aria-expanded="true" aria-controls="collapseTwo">
                    <i class="fas fa-fw fa-cog"></i>
                    <span>Mis</span>
                </a>
                <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">Manage MIS</h6>
                        <a class="collapse-item" href="mis.php">Mis</a>
                        <a class="collapse-item" href="unloading.php">Tanker Unloading</a>
                    </div>
                </div>
            </li>

			-->

			<!-- Nav Item - Dashboard -->
            <li class="nav-item active">
                <a class="nav-link" href="unloading_list.php">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Unloading List</span></a>
            </li>

           
            

            <!-- Nav Item - Charts -->
            <li class="nav-item">
                <a class="nav-link" href="unloading.php">
                    <i class="fas fa-fw fa-chart-area"></i>
                    <span>Tanker Unloading</span></a>
            </li>

            <!-- Nav Item - Tables -->
            <li class="nav-item">
                <a class="nav-link" href="mis.php">
                    <i class="fas fa-fw fa-table"></i>
                    <span>Mis</span></a>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider d-none d-md-block">

            <!-- Sidebar Toggler (Sidebar) -->
            <div class="text-center d-none d-md-inline">
                <button class="rounded-circle border-0" id="sidebarToggle"></button>
            </div>

        </ul>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

                    <!-- Sidebar Toggle (Topbar) -->
                    <form class="form-inline">
                        <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                            <i class="fa fa-bars"></i>
                        </button>
                    </form>

                    <!-- Topbar Search -->
                    <form
                        class="d-none d-sm-inline-block form-inline mr-auto ml-md-3 my-2 my-md-0 mw-100 navbar-search">
                        <div class="input-group">
                          <div class="input-group-append">
                             
                            </div>
                        </div>
                    </form>

                    <!-- Topbar Navbar -->
                    <ul class="navbar-nav ml-auto">

                        <!-- Nav Item - Search Dropdown (Visible Only XS) -->
                        <li class="nav-item dropdown no-arrow d-sm-none">
                            
                        </li>

                        <!-- Nav Item - Alerts -->
                        <li class="nav-item dropdown no-arrow mx-1">
                          
                        </li>

                        <!-- Nav Item - Messages -->
                        <li class="nav-item dropdown no-arrow mx-1">
                           
                        </li>

                        <div class="topbar-divider d-none d-sm-block"></div>

                        <!-- Nav Item - User Information -->
                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="mr-2 d-none d-lg-inline text-gray-600 small"><?php echo $_SESSION['userlogin']['name']; ?></span>
                                <img class="img-profile rounded-circle"
                                    src="img/undraw_profile.svg">
                            </a>
                            <!-- Dropdown - User Information -->
                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                aria-labelledby="userDropdown">
                              
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="logout.php" >
                                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Logout
                                </a>
                            </div>
                        </li>

                    </ul>

                </nav>
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- Page Heading -->
                    <h1 class="h3 mb-2 text-gray-800">LNG Tanker Unloading Records , <?php echo $_SESSION['ro_details']['name']; ?></h1>
                    <p class="mb-4"> Fields marked
                    with * are required.</p>

                    <!-- DataTales Example -->

                       <form
            action=""
            method="POST"
            enctype="multipart/form-data"

            onsubmit="return validateStartBeforeEnd();"
        >
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <!-- <h6 class="m-0 font-weight-bold text-primary">LNG Tanker Unloading Records , <?php echo $_SESSION['userlogin']['name']; ?></h6> -->
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">

                            <div class="card card-body">

                            <!-- start -->

<?php

if(isset($upload_success) && $upload_success != '')
{
    ?>
<div class="card bg-info text-white">
    <div class="card-body"><?php echo $upload_success; ?></div>
  </div>
    <?php
}

?>

                            <div class="form-group"  >
                            <label for="input1">Date*</label>
                            <div class="col-sm-4">
                                <input type="date" required class="form-control" id="unloading_date" name="unloading_date">
                            </div>
                            </div>


<div class="form-group"  >
                             <label for="vehicle_number" class="form-label">Vehicle Number *</label>
            <input
                type="text"
                name="vehicle_number"
                id="vehicle_number"
                class="form-control"
                placeholder="Enter Vehicle Number"
                required
                onchange="validateVehicleNumber();"
            />
</div>
            <div class="form-group"  >
            <label for="invoice_qty_kgs" class="form-label"
                >Invoice Qty (Kgs) *</label
            >
            <input
                type="text"
                name="invoice_qty_kgs"
                id="invoice_qty_kgs"
                class="form-control"
                placeholder="Enter Invoice Qty (Kgs)"

                required

                onchange="differance_quantity();"
            />
</div>

           <!-- <label for="address-line-1" class="form-label"
                >Street Address (Line 1)*</label
            >
            <input
                type="text"
                name="address-line-1"
                id="address-line-1"
                class="form-input"
                placeholder="House No., Road Name, etc."
                required
            /> -->
<div class="form-group"  >
            <label for="invoice_number" class="form-label"
                >Invoice Number *</label
            >
            <input
                type="text"
                name="invoice_number"
                id="invoice_number"
                class="form-control"
                placeholder="Invoice Number"

                required

                onchange="checkInvoiceNumber();"
            />

</div>

<div class="form-group"  >
            <label for="invoice_date" class="form-label">Invoice Date*</label>

             <div class="col-sm-4">
            <input
                type="date"
                name="invoice_date"
                id="invoice_date"
                class="form-control"
                placeholder="Invoice Date"
                required
            />
</div>
</div>

<div class="form-group"  >

            <label for="totalizer_value_kgs_opening" class="form-label"
                >Totalizer Value (Kgs) Opening  *</label
            >
            <input
                type="text"
                name="totalizer_value_kgs_opening"
                id="totalizer_value_kgs_opening"
                class="form-control"
                placeholder="Totalizer Value (Kgs) Opening"
                required
                onchange="total_unload_quantity();"
            />

</div>

<div class="form-group"  >
            <label for="totalizer_value_kgs_closing" class="form-label"
                >Totalizer Value (Kgs) Closing*</label
            >
            <input
                type="text"
                name="totalizer_value_kgs_closing"
                id="totalizer_value_kgs_closing"
               class="form-control"
                placeholder="Totalizer Value (Kgs) Closing"

                onchange="total_unload_quantity();"

                required
            />

</div>

<div class="form-group"  >

             <label for="total_unload_qty_kgs" class="form-label"
                >Total Unload Qty (Kgs)*</label
            >
            <input
                type="text"
                name="total_unload_qty_kgs"
                id="total_unload_qty_kgs"
                class="form-control"
                placeholder="Total Unload Qty (Kgs)"

                 readonly

                onchange="differance_quantity()"

                required
            />

</div>

<div class="form-group"  >

             <label for="diff_qty_kgs" class="form-label"
                >Diff. Qty (Kgs)*</label
            >
            <input
                type="text"
                name="diff_qty_kgs"
                id="diff_qty_kgs"
                class="form-control"
                placeholder="Diff. Qty (Kgs)"

                readonly

                required
            />

            </div>

<div class="form-group"  >

             <label for="unload_start_time_hrs" class="form-label"
                >Unload Start Time (Hrs)*</label
            >
             <div class="col-sm-4">
            <input
                type="datetime-local"
                name="unload_start_time_hrs"
                id="unload_start_time_hrs"
                class="form-control"
                placeholder="Unload Start Time (Hrs)"
                required

                onchange="calculateDuration();"


            />

</div>

            </div>

<div class="form-group"  >

             <label for="unload_end_time_hrs" class="form-label"
                >Unload End Time (Hrs)*</label
            >

             <div class="col-sm-4">
            <input
                type="datetime-local"
                name="unload_end_time_hrs"
                id="unload_end_time_hrs"
                class="form-control"
                placeholder="Unload End Time (Hrs)"
                required

                    onchange="calculateDuration();"

            />

</div>

            </div>

<div class="form-group"  >

             <label for="total_time_taken" class="form-label"
                >Total Time Taken (Hrs)*</label
            >
            <input
                type="text"
                name="total_time_taken"
                id="total_time_taken"
                class="form-control"
                placeholder="Total Time Taken (Hrs)"
                readonly
                required
            />

            </div>

<div class="form-group"  >

            <!-- <label for="postal-code" class="form-label"
                >Total Time Taken (Hrs)*</label
            >
            <input
                type="text"
                name="postal-code"
                id="postal-code"
                class="form-input"
                placeholder="Total Time Taken (Hrs)"
                required
            />

-->

             <label for="operator_name" class="form-label"
                >Operator Name*</label
            >
            <input
                type="text"
                name="operator_name"
                id="operator_name"
                class="form-control"
                placeholder="Operator Name"
                required
            />

            </div>

<div class="form-group"  >

             <label for="postal-code" class="form-label"
                >Remarks</label
            >

            <textarea class="form-control" name="remarks" id="remarks">

</textarea>

</div>
<div class="form-group"  >

             <label for="total_time_taken" class="form-label"
                >Upload File </label
            >
            <input
                type="file"
                name="uploadfile[]"

                multiple
                
                class="form-control"
                
            />

            </div>
<div class="form-group"  >
            

            <input type="hidden" name="action" id="action" value="Save">

 <div class="col-sm-2">
            <button class="form-control btn btn-primary">Submit </button>
</div>

</div>
        </form>



                        <!-- End -->



                            </div>


                                
                            </div>
                        </div>
                    </div>

                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Copyright &copy; Your Website 2020</span>
                    </div>
                </div>
            </footer>
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <a class="btn btn-primary" href="login.html">Logout</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.min.js"></script>

    <!-- Page level plugins -->
    <script src="vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="vendor/datatables/dataTables.bootstrap4.min.js"></script>

    <!-- Page level custom scripts -->
    <script src="js/demo/datatables-demo.js"></script>


    
<script>

    function total_unload_quantity()
    {
        var opening_quatity = document.getElementById('totalizer_value_kgs_opening').value;

        var closing_quantity = document.getElementById('totalizer_value_kgs_closing').value;

        var total_unload_quantity;

        if(opening_quatity != '' && closing_quantity != '')
        {
                total_unload_quantity = closing_quantity - opening_quatity;

                document.getElementById('total_unload_qty_kgs').value = total_unload_quantity;

                var invoice_qty_kgs = document.getElementById('invoice_qty_kgs').value;

                if(total_unload_quantity != '' && invoice_qty_kgs != '')
                {
                     diff_qty_kgs = total_unload_quantity - invoice_qty_kgs;

                document.getElementById('diff_qty_kgs').value = diff_qty_kgs;
                }

        }
    }

    

       function differance_quantity()
    {
        var total_unload_qty_kgs = document.getElementById('total_unload_qty_kgs').value;

            

        var invoice_qty_kgs = document.getElementById('invoice_qty_kgs').value;

        var diff_qty_kgs;

        if(total_unload_qty_kgs != '' && invoice_qty_kgs != '')
        {
                diff_qty_kgs = total_unload_qty_kgs - invoice_qty_kgs;

                document.getElementById('diff_qty_kgs').value = diff_qty_kgs;
        }
    }

    function calculateDuration()
    {
        const startTimeInput = document.getElementById('unload_start_time_hrs');
    const endTimeInput = document.getElementById('unload_end_time_hrs');
        //alert(startTimeInput.value);
    const startTimeStr = startTimeInput.value; // e.g., "09:00"
    const endTimeStr = endTimeInput.value;     // e.g., "17:30"
if(startTimeStr != '' && endTimeStr!= '')
{
const date1 = new Date(startTimeStr);
const date2 = new Date(endTimeStr);

    // Difference in milliseconds
const diffMs = date2 - date1;

 const totalSeconds = Math.floor(diffMs / 1000);
    const hours = Math.floor(totalSeconds / 3600);
    const minutes = Math.floor((totalSeconds % 3600) / 60);
    const seconds = totalSeconds % 60;

    // Pad with zeros if needed (e.g. 01:05:09)
    const pad = (n) => String(n).padStart(2, '0');

    document.getElementById('total_time_taken').value = `${pad(hours)}:${pad(minutes)}:${pad(seconds)}`;

}

    }

    function checkInvoiceNumber()
    {
        var invoice_number = document.getElementById('invoice_number').value;
        //const str = "AB-123 / CD 456";
const regex = /^[A-Za-z0-9\s\-\/]+$/;

if (regex.test(invoice_number)) {
   // console.log("Valid");
} else {
    alert("Invalid Invoice Number");
}

    }

    function validateVehicleNumber()
    {
        const plate = document.getElementById('vehicle_number').value;
const regex = /^[A-Z]{2}[0-9]{2}[A-Z]{1,2}[0-9]{4}$/;

if (regex.test(plate)) {
    
} else {
    alert("Invalid vehicle number");
}

    }

    function calculateDurationSameDay() {
    const startTimeInput = document.getElementById('unload_start_time_hrs');
    const endTimeInput = document.getElementById('unload_end_time_hrs');
        //alert(startTimeInput.value);
    const startTimeStr = startTimeInput.value; // e.g., "09:00"
    const endTimeStr = endTimeInput.value;     // e.g., "17:30"

    if (!startTimeStr || !endTimeStr) {
        //document.getElementById('total_time_taken').value = "Please select both times.";
        return;
    }

    // Parse hours and minutes
    const [startHours, startMinutes] = startTimeStr.split(':').map(Number);
    const [endHours, endMinutes] = endTimeStr.split(':').map(Number);

    // Convert to total minutes from midnight for each time
    const totalMinutesStart = (startHours * 60) + startMinutes;
    const totalMinutesEnd = (endHours * 60) + endMinutes;

    // Calculate difference (assuming end time is after start time on the same day)
    let differenceInMinutes = totalMinutesEnd - totalMinutesStart;

    if (differenceInMinutes < 0) {
        // This case indicates end time is earlier than start time,
        // which might mean it's on the next day, but for "Same Day" we
        // might just show absolute difference or indicate an error.
        // For this function, we'll assume a positive difference is expected.
        document.getElementById('total_time_taken').value = "End time must be after start time for this calculation.";
        return;
    }

    // Convert difference back to hours and minutes
    const diffHours = Math.floor(differenceInMinutes / 60);
    const diffMinutes = differenceInMinutes % 60;

    //alert(diffHours);
    document.getElementById('total_time_taken').value =
        `${diffHours}:${diffMinutes}:00`;
}

document.addEventListener('DOMContentLoaded', function () {
    var today = new Date();
    var yesterday = new Date();
    yesterday.setDate(today.getDate() - 1);

    function formatDate(date) {
        var dd = String(date.getDate()).padStart(2, '0');
        var mm = String(date.getMonth() + 1).padStart(2, '0'); // Month is 0-based
        var yyyy = date.getFullYear();
        return yyyy + '-' + mm + '-' + dd;
    }

    var minDate = formatDate(yesterday); // yesterday
    var maxDate = formatDate(today);     // today

    var dateInput = document.getElementById('unloading_date');
    dateInput.setAttribute('min', minDate);
    dateInput.setAttribute('max', maxDate);

    // Set today's date as default
    dateInput.value = maxDate;
});




    
        document.addEventListener('DOMContentLoaded', function() {
            var today = new Date();
            var dd = String(today.getDate()).padStart(2, '0');
            var mm = String(today.getMonth() + 1).padStart(2, '0'); // January is 0!
            var yyyy = today.getFullYear();

            var maxDate = yyyy + '-' + mm + '-' + dd;

            document.getElementById('invoice_date').setAttribute('max', maxDate);
        });

function isValidDecimal(input) {
    const regex = /^[-+]?\d+(\.\d+)?$/;
    return regex.test(input);
}


$("#invoice_qty_kgs").change(function(){
   var invoice_qty_kgs = $(this).val();

   if(isValidDecimal(invoice_qty_kgs))
   {

   }
   else{
            alert('Please enter valid decimal value');

            $(this).val('');
   }


});

$("#totalizer_value_kgs_opening").change(function(){
   var totalizer_value_kgs_opening = $(this).val();

   if(isValidDecimal(totalizer_value_kgs_opening))
   {

   }
   else{
            alert('Please enter valid decimal value');

            $(this).val('');
   }


});

$("#totalizer_value_kgs_closing").change(function(){
   var totalizer_value_kgs_closing = $(this).val();

   if(isValidDecimal(totalizer_value_kgs_closing))
   {

   }
   else{
            alert('Please enter valid decimal value');

            $(this).val('');
   }


});


document.querySelector('input[type=file]').addEventListener('change', function () {
    const allowedTypes = ['application/pdf', 'image/jpeg', 'image/png', 'image/gif'];
    const maxSize = 5 * 1024 * 1024; // 5 MB

    for (let i = 0; i < this.files.length; i++) {
        const file = this.files[i];

        if (!allowedTypes.includes(file.type)) {
            alert(`❌ "${file.name}" is not a valid file type. Only PDF and images allowed.`);
            this.value = ''; // Clear selection
            return;
        }

        if (file.size > maxSize) {
            alert(`❌ "${file.name}" exceeds the 5MB size limit.`);
            this.value = '';
            return;
        }
    }

    // ✅ All files passed validation
    console.log("✅ All files are valid.");
});

/*document.addEventListener('DOMContentLoaded', function () {
    // Get current date & time
    let now = new Date();
    
    // Format as YYYY-MM-DDTHH:MM for datetime-local
    let year = now.getFullYear();
    let month = String(now.getMonth() + 1).padStart(2, '0');
    let day = String(now.getDate()).padStart(2, '0');
    let hours = String(now.getHours()).padStart(2, '0');
    let minutes = String(now.getMinutes()).padStart(2, '0');

    // The key change: Use 'T' instead of space
    let maxDateTime = `${year}-${month}-${day}T${hours}:${minutes}`;
    
    // Set max attribute
    document.getElementById("unload_start_time_hrs").setAttribute("max", maxDateTime);
    document.getElementById("unload_end_time_hrs").setAttribute("max", maxDateTime);
});
*/
/*
document.addEventListener('DOMContentLoaded', function () {
    function getCurrentDateTimeString() {
        let now = new Date();
        let year = now.getFullYear();
        let month = String(now.getMonth() + 1).padStart(2, '0');
        let day = String(now.getDate()).padStart(2, '0');
        let hours = String(now.getHours()).padStart(2, '0');
        let minutes = String(now.getMinutes()).padStart(2, '0');
        return `${year}-${month}-${day}T${hours}:${minutes}`;
    }

    let maxDateTime = getCurrentDateTimeString();

    let startInput = document.getElementById("unload_start_time_hrs");
    let endInput = document.getElementById("unload_end_time_hrs");

    startInput.max = maxDateTime;
    endInput.max = maxDateTime;

    function preventFutureSelection(input) {
        if (input.value && input.value > getCurrentDateTimeString()) {
            input.value = getCurrentDateTimeString();
        }
    }

    startInput.addEventListener("input", function () {
        preventFutureSelection(startInput);
    });

    endInput.addEventListener("input", function () {
        preventFutureSelection(endInput);
    });
});

*/



document.addEventListener('DOMContentLoaded', function () {
    function getCurrentDateTimeString() {
        let now = new Date();
        let year = now.getFullYear();
        let month = String(now.getMonth() + 1).padStart(2, '0');
        let day = String(now.getDate()).padStart(2, '0');
        let hours = String(now.getHours()).padStart(2, '0');
        let minutes = String(now.getMinutes()).padStart(2, '0');
        return `${year}-${month}-${day}T${hours}:${minutes}`;
    }

    let startInput = document.getElementById("unload_start_time_hrs");
    let endInput = document.getElementById("unload_end_time_hrs");

    // Set max for both inputs
    function updateMax() {
        let maxDateTime = getCurrentDateTimeString();
        startInput.max = maxDateTime;
        endInput.max = maxDateTime;
    }
    updateMax();

    function preventFutureSelection(input) {
        if (input.value && input.value > getCurrentDateTimeString()) {
            input.value = getCurrentDateTimeString();
        }
    }

    function validateStartBeforeEnd() {
        if (startInput.value && endInput.value && startInput.value > endInput.value) {
            alert("Start date/time must be earlier than End date/time");
            endInput.value = "";
        }
    }

    startInput.addEventListener("input", function () {
        preventFutureSelection(startInput);
       // validateStartBeforeEnd();
    });

    endInput.addEventListener("input", function () {
        preventFutureSelection(endInput);
     //   validateStartBeforeEnd();
    });

    // Optional: keep updating max every minute in case the user stays on the form
    setInterval(updateMax, 60000);
});


    function validateStartBeforeEnd() {

            let startInput = document.getElementById("unload_start_time_hrs");
            let endInput = document.getElementById("unload_end_time_hrs");

        if (startInput.value && endInput.value && startInput.value > endInput.value) {
            alert("Start date/time must be earlier than End date/time");
            endInput.value = "";

            return false;
        }

        return true;
    }


</script>

</body>

</html>



