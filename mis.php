<?php
session_start();

putenv("TZ=IST");
date_default_timezone_set("Asia/Kolkata");

if(isset($_REQUEST['mis_date']) && $_REQUEST['mis_date'] != '')
{
$mis_date_raw = $_REQUEST['mis_date'];

//echo $mis_date_raw;

$mis_date_arr = explode("-",$mis_date_raw);

$mis_date_new = $mis_date_arr[2]."-".$mis_date_arr[1]."-".$mis_date_arr[0];

}
//echo $mis_date_new;

require_once('./include/database.php');

if(isset($_SESSION['userlogin']) && count($_SESSION['userlogin']) > 0)
{
   // print_r($_SESSION);
}
else
{
    header("Location: logout.php");
}

// print_r($_REQUEST);

//print_r($_SESSION['ro_details']);

$SelectQuery = "SELECT * FROM setings ";

           $result = $mysqli->query($SelectQuery);

          

       $row = $result->fetch_assoc();

        $converter = $row['ltr_to_kg'];

        if(isset($_REQUEST['mis_date']) && $_REQUEST['mis_date'] != '')
        {
            $today = $mis_date_new;

            $_SESSION['mis_date'] = $mis_date_new;
        }
        elseif(isset($_SESSION['mis_date']) && $_SESSION['mis_date'] != '')
        {
           // $today = $_SESSION['mis_date'];
        }
        else
        {

           // $today = date("Y-m-d");
        }

           
if(isset($today) && $today != '' )
{
     $SelectQuery = "SELECT * FROM lng_storage_mis where ro_id = '".$_SESSION['ro_details']['id']."' AND DATE(mis_date) = '".$today."'";

           $result = $mysqli->query($SelectQuery);

       $row = $result->fetch_assoc();

       $prev_date = date('Y-m-d', strtotime($today . '-1 day'));


     $SelectQuery = "SELECT * FROM lng_storage_mis where ro_id = '".$_SESSION['ro_details']['id']."'  AND DATE(mis_date) = '".$prev_date."'";

           $result_prev = $mysqli->query($SelectQuery);

       $row_prev = $result_prev->fetch_assoc();
}
     //  print_r($row_prev);

if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'lng_storage_tank')
{

    if(isset($row) && count($row) > 0)
    {
       // $message = "MIS record already exists for this day. ";

       // $_REQUEST['action'] = "";

        $UpdateQuery = "UPDATE lng_storage_mis SET
    mis_date = '".$mis_date_new."',
    lng_opening_level_l = '".$_REQUEST['lng_opening_level_l']."',
    lng_opening_level_kg = '".$_REQUEST['lng_opening_level_kg']."',
    lng_closing_level_l = '".$_REQUEST['lng_closing_level_l']."',
    lng_closing_level_kg = '".$_REQUEST['lng_closing_level_kg']."',
    lng_difference_kg = '".$_REQUEST['lng_difference_kg']."',
    operter_id = '".$_SESSION['userlogin']['id']."'
    ,
    ro_id =' ".$_SESSION['ro_details']['id']."',
    step = '0'
    WHERE
    operter_id = '".$_SESSION['userlogin']['id']."' AND mis_date = '".$mis_date_new."'
    ";

   // $_SESSION['mis_date'] = $_REQUEST['mis_date'];

    $mysqli->query($UpdateQuery);

    $insert_id = $row['id'];

    $imgDir = "./uploads/";

    $i=0;

    $upload_arr = array();

    if($_FILES['lng_storage_tank_file']['name'])
    {
            $fileName=$_FILES['lng_storage_tank_file']['name'];
             $tmpFileName = $_FILES['lng_storage_tank_file']['tmp_name'];
            $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

            $image1 = time().'-'.$i.'.'.$fileExt;

            $upload_arr[] = $image1;

			move_uploaded_file($tmpFileName, $imgDir.$image1);

              $UpdateQuery = "UPDATE lng_storage_mis SET 
    lng_storage_tank_file='".$image1."'

    WHERE
    id='".$insert_id."'
    ";

    $mysqli->query($UpdateQuery);

    }



    }else
    {
       $InsertQuery = "INSERT INTO   lng_storage_mis SET
    mis_date = '".$mis_date_new."',
    lng_opening_level_l = '".$_REQUEST['lng_opening_level_l']."',
    lng_opening_level_kg = '".$_REQUEST['lng_opening_level_kg']."',
    lng_closing_level_l = '".$_REQUEST['lng_closing_level_l']."',
    lng_closing_level_kg = '".$_REQUEST['lng_closing_level_kg']."',
    lng_difference_kg = '".$_REQUEST['lng_difference_kg']."',
    operter_id = '".$_SESSION['userlogin']['id']."'
    ,
    ro_id =' ".$_SESSION['ro_details']['id']."',
    step = '0'
    ";

   // $_SESSION['mis_date'] = $_REQUEST['mis_date'];

    $mysqli->query($InsertQuery);

    $insert_id = $mysqli->insert_id;

    $imgDir = "./uploads/";

    $i=0;

    $upload_arr = array();

    if($_FILES['lng_storage_tank_file']['name'])
    {
            $fileName=$_FILES['lng_storage_tank_file']['name'];
             $tmpFileName = $_FILES['lng_storage_tank_file']['tmp_name'];
            $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

            $image1 = time().'-'.$i.'.'.$fileExt;

            $upload_arr[] = $image1;

			move_uploaded_file($tmpFileName, $imgDir.$image1);

              $UpdateQuery = "UPDATE lng_storage_mis SET 
    lng_storage_tank_file='".$image1."'

    WHERE
    id='".$insert_id."'
    ";

    $mysqli->query($UpdateQuery);

    }

    }



}


if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'lng_dispenser')
{


       $UpdateQuery = " UPDATE  lng_storage_mis SET
    
    lng_totalizer_opening_kg = '".$_REQUEST['lng_totalizer_opening_kg']."',
    lng_totalizer_closing_kg = '".$_REQUEST['lng_totalizer_closing_kg']."',
    sold_qty_dispenser_kg = '".$_REQUEST['sold_qty_dispenser_kg']."',
    step = '0'
    WHERE
    operter_id = '".$_SESSION['userlogin']['id']."' AND mis_date = '".$mis_date_new."'
    ";

    $mysqli->query($UpdateQuery);

    $insert_id = $row['id'];

    $imgDir = "./uploads/";

    $i=0;

    $upload_arr = array(); 

    if($_FILES['lng_dispenser_file']['name'])
    {
            $fileName=$_FILES['lng_dispenser_file']['name'];
             $tmpFileName = $_FILES['lng_dispenser_file']['tmp_name'];
            $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

            $image1 = time().'-'.$i.'.'.$fileExt;

            $upload_arr[] = $image1;

			move_uploaded_file($tmpFileName, $imgDir.$image1);

              $UpdateQuery = "UPDATE lng_storage_mis SET 
    lng_dispenser_file = '".$image1."'

    WHERE
    id='".$insert_id."'
    ";

    $mysqli->query($UpdateQuery);

    }



}




if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'geg')
{


       $UpdateQuery = " UPDATE  lng_storage_mis SET
    
     geg_totalizer_opening_kg = '".$_REQUEST['geg_totalizer_opening_kg']."',
    geg_totalizer_closing_kg = '".$_REQUEST['geg_totalizer_closing_kg']."',
    geg_consumption_kg = '".$_REQUEST['geg_consumption_kg']."',
    geg_running_hours = '".$_REQUEST['geg_running_hours']."',
    step = '0'
    WHERE
    operter_id = '".$_SESSION['userlogin']['id']."' AND mis_date = '".$mis_date_new."'
    ";

    $mysqli->query($UpdateQuery);

    $insert_id = $row['id'];

    $imgDir = "./uploads/";

    $i=0;

    $upload_arr = array(); 

    if($_FILES['geg_file']['name'])
    {
          $fileName=$_FILES['geg_file']['name'];
             $tmpFileName = $_FILES['geg_file']['tmp_name'];
            $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

            $image3 = time().'-'.$i.'.'.$fileExt;

            $upload_arr[] = $image3;

			move_uploaded_file($tmpFileName, $imgDir.$image3);

              $UpdateQuery = "UPDATE lng_storage_mis SET 
     geg_file='".$image3."'

    WHERE
    id='".$insert_id."'
    ";

    $mysqli->query($UpdateQuery);

    }



}




// unloading_mfm

if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'unloading_mfm')
{


       $UpdateQuery = " UPDATE  lng_storage_mis SET
    
  unloading_mfm_opening_reading_kg = '".$_REQUEST['unloading_mfm_opening_reading_kg']."',
    unloading_mfm_closing_reading_kg = '".$_REQUEST['unloading_mfm_closing_reading_kg']."',
    unloading_mfm_difference_kg = '".$_REQUEST['unloading_mfm_difference_kg']."'
,
    step = '0'
    WHERE
    operter_id = '".$_SESSION['userlogin']['id']."' AND mis_date = '".$mis_date_new."'
    ";

    $mysqli->query($UpdateQuery);

    $insert_id = $row['id'];

    $imgDir = "./uploads/";

    $i=0;

    $upload_arr = array(); 

    if($_FILES['unloading_mfm_file']['name'])
    {
               $fileName=$_FILES['unloading_mfm_file']['name'];
             $tmpFileName = $_FILES['unloading_mfm_file']['tmp_name'];
            $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

            $image4 = time().'-'.$i.'.'.$fileExt;

            $upload_arr[] = $image4;

			move_uploaded_file($tmpFileName, $imgDir.$image4);

              $UpdateQuery = "UPDATE lng_storage_mis SET 
     unloading_mfm_file='".$image4."'

    WHERE
    id='".$insert_id."'
    ";

    $mysqli->query($UpdateQuery);

    }



}

/*

if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'unloading_mfm')
{


       $UpdateQuery = " UPDATE  lng_storage_mis SET
    
  unloading_mfm_opening_reading_kg = '".$_REQUEST['unloading_mfm_opening_reading_kg']."',
    unloading_mfm_closing_reading_kg = '".$_REQUEST['unloading_mfm_closing_reading_kg']."',
    unloading_mfm_difference_kg = '".$_REQUEST['unloading_mfm_difference_kg']."'
,
    step = '5'
    WHERE
    operter_id = '".$_SESSION['userlogin']['id']."' AND mis_date = '".$today."'
    ";

    $mysqli->query($UpdateQuery);

    $insert_id = $row['id'];

    $imgDir = "./uploads/";

    $i=0;

    $upload_arr = array(); 

    if($_FILES['unloading_mfm_file']['name'])
    {
               $fileName=$_FILES['unloading_mfm_file']['name'];
             $tmpFileName = $_FILES['unloading_mfm_file']['tmp_name'];
            $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

            $image4 = time().'-'.$i.'.'.$fileExt;

            $upload_arr[] = $image4;

			move_uploaded_file($tmpFileName, $imgDir.$image4);

              $UpdateQuery = "UPDATE lng_storage_mis SET 
     unloading_mfm_file='".$image4."'

    WHERE
    id='".$insert_id."'
    ";

    $mysqli->query($UpdateQuery);

    }



}

*/

// grid_power_meter


if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'grid_power_meter')
{


       $UpdateQuery = " UPDATE  lng_storage_mis SET
    
 grid_power_opening_kwh = '".$_REQUEST['grid_power_opening_kwh']."',
    grid_power_meter_closing_kwh = '".$_REQUEST['grid_power_meter_closing_kwh']."',
    grid_power_meter_difference_kwh = '".$_REQUEST['grid_power_meter_difference_kwh']."',
    solar_power_opening_kwh = '".$_REQUEST['solar_power_opening_kwh']."',
    solar_power_meter_closing_kwh = '".$_REQUEST['solar_power_meter_closing_kwh']."',
    solar_power_meter_difference_kwh = '".$_REQUEST['solar_power_meter_difference_kwh']."'
, grid_power_export_opening_kwh = '".$_REQUEST['grid_power_export_opening_kwh']."',
    grid_power_export_meter_closing_kwh = '".$_REQUEST['grid_power_export_meter_closing_kwh']."',
    grid_power_export_meter_difference_kwh = '".$_REQUEST['grid_power_export_meter_difference_kwh']."',
    step = '0'
    WHERE
    operter_id = '".$_SESSION['userlogin']['id']."' AND mis_date = '".$mis_date_new."'
    ";

    $mysqli->query($UpdateQuery);

    $insert_id = $row['id'];

    $imgDir = "./uploads/";

    $i=0;

    $upload_arr = array(); 

    if($_FILES['grid_power_meter_file']['name'])
    {
               $fileName=$_FILES['grid_power_meter_file']['name'];
             $tmpFileName = $_FILES['grid_power_meter_file']['tmp_name'];
            $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

            $image4 = time().'-'.$i.'.'.$fileExt;

            $upload_arr[] = $image4;

            $i++;

			move_uploaded_file($tmpFileName, $imgDir.$image4);


               $fileName=$_FILES['grid_power_export_meter_file']['name'];
             $tmpFileName = $_FILES['grid_power_export_meter_file']['tmp_name'];
            $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

            $image6 = time().'-'.$i.'.'.$fileExt;

            $upload_arr[] = $image6;

            $i++;

			move_uploaded_file($tmpFileName, $imgDir.$image6);


            $fileName=$_FILES['solar_power_meter_file']['name'];
             $tmpFileName = $_FILES['solar_power_meter_file']['tmp_name'];
            $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

            $image5 = time().'-'.$i.'.'.$fileExt;

            $upload_arr[] = $image5;

			move_uploaded_file($tmpFileName, $imgDir.$image5);

              $UpdateQuery = "UPDATE lng_storage_mis SET 
     grid_power_meter_file='".$image4."',
     grid_power_export_meter_file='".$image6."',
     solar_power_meter_file='".$image5."'

    WHERE
    id='".$insert_id."'
    ";

    $mysqli->query($UpdateQuery);

    }



}

// Update
if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'Update')
{

           $UpdateQuery = " UPDATE  lng_storage_mis SET
    is_submitted = '1',
  remarks = '".$_REQUEST['remarks']."'
  ,
    step = '0'
    WHERE
    operter_id = '".$_SESSION['userlogin']['id']."' AND mis_date = '".$mis_date_new."'
    ";

    $mysqli->query($UpdateQuery);

}

if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'Save')
{
    //print_r($_REQUEST);

   // $today = date("Y-m-d");

    $SelectQuery = "SELECT * FROM lng_storage_mis where ro_id = '".$_SESSION['ro_details']['id']."' AND DATE(created) = '".$mis_date_new."'";

           $result = $mysqli->query($SelectQuery);

       $row = $result->fetch_assoc();

       if(isset($row) && count($row) > 0)
       {
            $message = "Entry already done today";
       }
       else{

    $InsertQuery = "INSERT INTO   lng_storage_mis SET
    mis_date = '".$_REQUEST['mis_date']."',
    lng_opening_level_l = '".$_REQUEST['lng_opening_level_l']."',
    lng_opening_level_kg = '".$_REQUEST['lng_opening_level_kg']."',
    lng_closing_level_l = '".$_REQUEST['lng_closing_level_l']."',
    lng_closing_level_kg = '".$_REQUEST['lng_closing_level_kg']."',
    lng_difference_kg = '".$_REQUEST['lng_difference_kg']."',
    lng_totalizer_opening_kg = '".$_REQUEST['lng_totalizer_opening_kg']."',
    lng_totalizer_closing_kg = '".$_REQUEST['lng_totalizer_closing_kg']."',
    sold_qty_dispenser_kg = '".$_REQUEST['sold_qty_dispenser_kg']."',
    geg_totalizer_opening_kg = '".$_REQUEST['geg_totalizer_opening_kg']."',
    geg_totalizer_closing_kg = '".$_REQUEST['geg_totalizer_closing_kg']."',
    geg_consumption_kg = '".$_REQUEST['geg_consumption_kg']."',
    geg_running_hours = '".$_REQUEST['geg_running_hours']."',
    unloading_mfm_opening_reading_kg = '".$_REQUEST['unloading_mfm_opening_reading_kg']."',
    unloading_mfm_closing_reading_kg = '".$_REQUEST['unloading_mfm_closing_reading_kg']."',
    unloading_mfm_difference_kg = '".$_REQUEST['unloading_mfm_difference_kg']."',
    grid_power_opening_kwh = '".$_REQUEST['grid_power_opening_kwh']."',
    grid_power_meter_closing_kwh = '".$_REQUEST['grid_power_meter_closing_kwh']."',
    grid_power_meter_difference_kwh = '".$_REQUEST['grid_power_meter_difference_kwh']."',
    solar_power_opening_kwh = '".$_REQUEST['solar_power_opening_kwh']."',
    solar_power_meter_closing_kwh = '".$_REQUEST['solar_power_meter_closing_kwh']."',
    solar_power_meter_difference_kwh = '".$_REQUEST['solar_power_meter_difference_kwh']."',
    remarks = '".$_REQUEST['remarks']."',
    operter_id = '".$_SESSION['userlogin']['id']."'
    ";

    $mysqli->query($InsertQuery);

    $message = "Form submitted successfully";

    $insert_id = $mysqli->insert_id;

    $imgDir = "./uploads/";

    $i=0;

    $upload_arr = array();

    if($_FILES['lng_storage_tank_file']['name'])
    {
            $fileName=$_FILES['lng_storage_tank_file']['name'];
             $tmpFileName = $_FILES['lng_storage_tank_file']['tmp_name'];
            $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

            $image1 = time().'-'.$i.'.'.$fileExt;

            $upload_arr[] = $image1;

			move_uploaded_file($tmpFileName, $imgDir.$image1);
    }
    $i++;

     if($_FILES['lng_dispenser_file']['name'])
    {
            $fileName=$_FILES['lng_dispenser_file']['name'];
             $tmpFileName = $_FILES['lng_dispenser_file']['tmp_name'];
            $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

            $image2 = time().'-'.$i.'.'.$fileExt;

            $upload_arr[] = $image2;

			move_uploaded_file($tmpFileName, $imgDir.$image2);
    }

    $i++;

     if($_FILES['geg_file']['name'])
    {
            $fileName=$_FILES['geg_file']['name'];
             $tmpFileName = $_FILES['geg_file']['tmp_name'];
            $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

            $image3 = time().'-'.$i.'.'.$fileExt;

            $upload_arr[] = $image3;

			move_uploaded_file($tmpFileName, $imgDir.$image3);
    }

    $i++;

     if($_FILES['unloading_mfm_file']['name'])
    {
            $fileName=$_FILES['unloading_mfm_file']['name'];
             $tmpFileName = $_FILES['unloading_mfm_file']['tmp_name'];
            $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

            $image4 = time().'-'.$i.'.'.$fileExt;

            $upload_arr[] = $image4;

			move_uploaded_file($tmpFileName, $imgDir.$image4);
    }

    $i++;

     if($_FILES['grid_power_meter_file']['name'])
    {
            $fileName=$_FILES['grid_power_meter_file']['name'];
             $tmpFileName = $_FILES['grid_power_meter_file']['tmp_name'];
            $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

            $image5 = time().'-'.$i.'.'.$fileExt;

            $upload_arr[] = $image5;

			move_uploaded_file($tmpFileName, $imgDir.$image5);
    }

    $UpdateQuery = "UPDATE lng_storage_mis SET 
    lng_storage_tank_file='".$image1."',
    lng_dispenser_file='".$image2."',
    geg_file='".$image3."',
    unloading_mfm_file='".$image4."',
    grid_power_meter_file='".$image5."'

    WHERE
    id='".$insert_id."'
    ";

    $mysqli->query($UpdateQuery);

   // print_r($_FILES);
       }

}




    if(isset($mis_date_new) && $mis_date_new != '')
        {
            $today = $mis_date_new;

             $_SESSION['mis_date'] = $mis_date_new;
        }
        elseif(isset($_SESSION['mis_date']) && $_SESSION['mis_date'] != '')
        {
          //  $today = $_SESSION['mis_date'];
        }
        else
        {

           // $today = date("Y-m-d");
        }

        if(isset($today) && $today != '')
        {
            $SelectQuery = "SELECT * FROM lng_storage_mis where ro_id = '".$_SESSION['ro_details']['id']."' AND DATE(mis_date) = '".$today."'";

           $result = $mysqli->query($SelectQuery);

            $row = $result->fetch_assoc();
        }

       //print_r($row);


?><!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Greenline Admin </title>

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
                
                <div class="sidebar-brand-text mx-3"><img src='./img/logo.png'  height="70" width="160"></div>
            </a>

            <!-- Divider -->
            <hr class="sidebar-divider my-0">

            <!-- Nav Item - Dashboard -->
           <!-- <li class="nav-item">
                <a class="nav-link" href="index.php">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Dashboard</span></a>
            </li>

    -->
            

            <!-- Nav Item - Charts -->
         <!--   <li class="nav-item">
                <a class="nav-link" href="unloading.php">
                    <i class="fas fa-fw fa-chart-area"></i>
                    <span>Tanker Unloading</span></a>
            </li>

    -->

            <!-- Nav Item - Tables -->
         <!--   <li class="nav-item active">
                <a class="nav-link" href="mis.php">
                    <i class="fas fa-fw fa-table"></i>
                    <span>Mis</span></a>
            </li>
    -->


                 <!-- Nav Item - Pages Collapse Menu -->
           <!-- <li class="nav-item">
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
                            <!-- <input type="text" class="form-control bg-light border-0 small" placeholder="Search for..."
                                aria-label="Search" aria-describedby="basic-addon2">
-->
                            <div class="input-group-append">
                               <!-- <button class="btn btn-primary" type="button">
                                    <i class="fas fa-search fa-sm"></i>
                                </button>
-->
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
                    <h1 class="h3 mb-2 text-gray-800">MIS - <?php echo $_SESSION['ro_details']['name']; ?></h1>
                    <p class="mb-4"><!-- MIS - <?php echo $_SESSION['userlogin']['name']; ?> --></p>

                    <!-- DataTales Example -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary"><!-- MIS - <?php echo $_SESSION['userlogin']['name']; ?> --></h6>
                        </div>
                        <div class="card-body">
                              <form name="mis_date_form" id="mis_date_form"
            action=""
            method="GET"
            enctype="multipart/form-data" 
        >
                            <div class="table-responsive">

                            <?php
                            if(isset($row['ro_id']) && $row['ro_id'] != '' && $row['is_submitted'] == '1')
                            {
                                $mis_message="This day entry for MIS is filled you cannot enter again";

                                ?>
                                 <div class="alert alert-danger" role="alert">
 <?php echo $mis_message; ?>
</div>
                                <?php
                            }
                            if(isset($message) && $message!= '') {
                            ?>
                                
  <div class="alert alert-success" role="alert">
 <?php echo $message; ?>
</div>
                            <?php

                            }

                            ?>

<div class="card card-body">
                            <div class="form-group"  >
      <label for="input1">Date*</label>
      <div class="col-sm-4">
        <input type="hidden" name="action" id="action" value="change_date" >
      <input type="text"  required class="form-control" id="mis_date" value="" placeholder="dd-mm-yyyy" name="mis_date">
</div>
    </div>
</div> </form>

<form
            action=""
            method="POST"
            enctype="multipart/form-data" 
        >


                            <!-- <button class="btn btn-primary" type="button" data-toggle="collapse" data-target="#formSection1" aria-expanded="false" aria-controls="formSection1">
LNG Storage Tank
</button> -->

<div class="card bg-info text-white">
    <div class="card-body">LNG Storage Tank</div>
  </div>
<?php
// print_r($_REQUEST);
?>
<div class="collapse <?php if( (isset($row['is_submitted']) && $row['is_submitted'] == '1' ) || (isset($_REQUEST['action']) && $_REQUEST['action'] == 'change_date') )  { echo "show"; } ?>" id="formSection1">
  <div class="card card-body">
    <!-- Form elements for section 1 go here -->
    <div class="form-group">
      <label for="lng_opening_level_l">LNG Storage Tank Opening Level (L):</label>
      <input type="text"  required  onchange="lng_litre_to_kg_opening();" value="<?php  if(isset($row['lng_opening_level_l']) && $row['lng_opening_level_l'] != '') {  echo $row['lng_opening_level_l']; }elseif(isset($row_prev['lng_closing_level_l']) && $row_prev['lng_closing_level_l'] != '') { echo $row_prev['lng_closing_level_l']; } ?>" class="form-control" id="lng_opening_level_l" name="lng_opening_level_l">
    </div>
    <div class="form-group">
      <label for="lng_opening_level_kg">LNG Storage Tank Opening Level (kg)</label>
      <input type="text"  readonly class="form-control"  value="<?php  if(isset($row['lng_opening_level_kg']) && $row['lng_opening_level_kg'] != '') {  echo $row['lng_opening_level_kg']; } elseif(isset($row_prev['lng_closing_level_kg']) && $row_prev['lng_closing_level_kg'] != '') { echo $row_prev['lng_closing_level_kg']; } ?>"  id="lng_opening_level_kg"  onchange="lng_storage_tank_difference_kg()" name="lng_opening_level_kg">
    </div>

     <div class="form-group">
      <label for="lng_closing_level_l">LNG Storage Tank Closing Level (L):</label>
      <input type="text" onchange="lng_litre_to_kg_closing();" required class="form-control"  value="<?php  if(isset($row['lng_closing_level_l']) && $row['lng_closing_level_l']!='') {  echo $row['lng_closing_level_l']; } ?>"  id="lng_closing_level_l" name="lng_closing_level_l">
    </div>
    <div class="form-group">
      <label for="lng_closing_level_kg">LNG Storage Tank Closing Level (Kg)*</label>
      <input type="text"  readonly class="form-control"  value="<?php  if(isset($row['lng_closing_level_kg']) && $row['lng_closing_level_kg'] != '') {  echo $row['lng_closing_level_kg']; } ?>"  id="lng_closing_level_kg"  onchange="lng_storage_tank_difference_kg()" name="lng_closing_level_kg">
    </div>

    <div class="form-group">
      <label for="lng_difference_kg">LNG Storage Tank Difference (kg) *</label>
      <input type="text" required class="form-control" value="<?php  if(isset($row['lng_difference_kg']) && $row['lng_difference_kg'] != '') {  echo $row['lng_difference_kg']; } ?>" id="lng_difference_kg" readonly name="lng_difference_kg">
    </div>
<?php if( (isset($row['is_submitted']) && $row['is_submitted'] == '1') ) {
?>
<div class="form-group"  >
<label for="lng_storage_tank_file" class="form-label"
                >LNG Storage Tank File</label>
           
<a target="_blank" href='<?php echo "./uploads/".$row['lng_storage_tank_file']; ?>' >Open file</a>
            </div>
<?php }
else { ?>
    <div class="form-group"  >

             <label for="lng_storage_tank_file" class="form-label"
                >LNG Storage Tank File</label>
                <input
                type="file" <?php if(isset($row['lng_storage_tank_file']) && $row['lng_storage_tank_file'] != '') { } else { echo "required"; } ?>   name="lng_storage_tank_file" id="lng_storage_tank_file"  onchange="lng_storage_tank_difference_kg()" class="form-control" />
                <?php if(isset($row['lng_storage_tank_file']) && $row['lng_storage_tank_file'] != '') { ?> <a target="_blank" href='<?php echo "./uploads/".$row['lng_storage_tank_file']; ?>' >Open file</a> <?php }  ?>
            </div>

            

<div class="form-group">
      <label  for="Submit"></label>
      <div class="col-sm-2">
      <input type="hidden" name="action" id="action" value="lng_storage_tank">
      <input type="submit" class="form-control btn btn-primary" id="Submit" name="Submit"></textarea>
    </div>
    </div>
<?php } ?>
                        </form>
  </div>
</div><br>

<!-- <button class="btn btn-primary mt-3" type="button" data-toggle="collapse" data-target="#formSection2" aria-expanded="false" aria-controls="formSection2">
 LNG Dispenser
</button>
-->
<div class="card bg-info text-white">
    <div class="card-body">LNG Dispenser</div>
  </div>

<div class="collapse <?php if((isset($_REQUEST['action']) && $_REQUEST['action'] == 'lng_storage_tank') || ( isset($row['is_submitted']) && $row['is_submitted'] == '1' ) || (isset($row['step']) && $row['step'] == '1' ))
{  echo "show"; } ?>" id="formSection2">
  <div class="card card-body">
    <!-- Form elements for section 2 go here -->

     <form
            action=""
            method="POST"
            enctype="multipart/form-data" 
        >
    <div class="form-group">
      <label for="lng_totalizer_opening_kg">LNG Dispenser Totalizer Opening (kg) *</label>
      <input type="text" required onchange="lng_dispenser_sold_quantity_kg();" class="form-control" id="lng_totalizer_opening_kg" name="lng_totalizer_opening_kg" value="<?php if(isset($row['lng_totalizer_opening_kg']) && $row['lng_totalizer_opening_kg'] != '' ) { echo $row['lng_totalizer_opening_kg']; } elseif(isset($row_prev['lng_totalizer_closing_kg']) && $row_prev['lng_totalizer_closing_kg'] != '') { echo $row_prev['lng_totalizer_closing_kg']; } ?>">
    </div>
    <div class="form-group">
      <label for="lng_totalizer_closing_kg">LNG Dispenser Totalizer Closing (kg) *</label>
      <input type="text" required onchange="lng_dispenser_sold_quantity_kg();" class="form-control" id="lng_totalizer_closing_kg" name="lng_totalizer_closing_kg" value="<?php if(isset($row['lng_totalizer_closing_kg']) && $row['lng_totalizer_closing_kg'] != '' ) { echo $row['lng_totalizer_closing_kg']; } ?>" >
    </div>

     <div class="form-group">
      <label for="sold_qty_dispenser_kg">Sold Qty by Dispenser (kg) *:</label>
      <input type="text" required readonly class="form-control" id="sold_qty_dispenser_kg" name="sold_qty_dispenser_kg" value="<?php if(isset($row['sold_qty_dispenser_kg']) && $row['sold_qty_dispenser_kg'] != '' ) { echo $row['sold_qty_dispenser_kg']; } ?>">
    </div>
<?php if( isset($row['is_submitted']) && $row['is_submitted'] == '1' ) {
    ?>
   <div class="form-group"  >

             <label for="lng_dispenser_file" class="form-label"
                >LNG Dispenser File</label>
           <a target="_blank" href='<?php echo "./uploads/".$row['lng_dispenser_file']; ?>' >Open file</a>
            </div>
    <?php
 }
else { ?>
       <div class="form-group"  >

             <label for="lng_dispenser_file" class="form-label"
                >LNG Dispenser File</label>

                           
            <input
                type="file"  <?php if(isset($row['lng_dispenser_file']) && $row['lng_dispenser_file'] != '') { } else { echo "required"; } ?>   name="lng_dispenser_file"   id="lng_dispenser_file" class="form-control" />
                <?php if(isset($row['lng_dispenser_file']) && $row['lng_dispenser_file'] != '') { ?> <a target="_blank" href='<?php echo "./uploads/".$row['lng_dispenser_file']; ?>' >Open file</a> <?php }  ?>
            </div>

             <div class="form-group">
      <label  for="input1"></label>
      <div class="col-sm-2">
      <input type="hidden" name="action" id="action" value="lng_dispenser">
      <input type="submit" class="form-control  btn btn-primary" id="Submit" name="Submit"></textarea>
    </div>
    </div>
<?php } ?>

                        </form>

  </div>
</div>

<br>
<!-- <button class="btn btn-primary mt-3" type="button" data-toggle="collapse" data-target="#formSection3" aria-expanded="false" aria-controls="formSection3">
  GEG 
</button> -->
<div class="card bg-info text-white">
    <div class="card-body">GEG</div>
  </div>

<div class="collapse <?php if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'lng_dispenser' || ( isset($row['is_submitted']) && $row['is_submitted'] == '1' ) || (isset($row['step']) && $row['step'] == '2' ) )
{ echo "show"; } ?>" id="formSection3">
  <div class="card card-body">
    <!-- Form elements for section 2 go here -->

     <form
            action=""
            method="POST"
            enctype="multipart/form-data" 
        >
    <div class="form-group">
      <label for="geg_totalizer_opening_kg">GEG Totalizer Opening (kg)*</label>
      <input type="text" required onchange="lng_geg_consumption_kg();" class="form-control" id="geg_totalizer_opening_kg" name="geg_totalizer_opening_kg"   value="<?php if(isset($row['geg_totalizer_opening_kg']) && $row['geg_totalizer_opening_kg'] != '' ) { echo $row['geg_totalizer_opening_kg']; } elseif(isset($row_prev['geg_totalizer_closing_kg']) && $row_prev['geg_totalizer_closing_kg'] != '') { echo $row_prev['geg_totalizer_closing_kg']; } ?>" >
    </div>
    <div class="form-group">
      <label for="geg_totalizer_closing_kg">GEG Totalizer Closing (kg)*</label>
      <input type="text" required  onchange="lng_geg_consumption_kg();"  class="form-control" id="geg_totalizer_closing_kg" name="geg_totalizer_closing_kg"   value="<?php if(isset($row['geg_totalizer_closing_kg']) && $row['geg_totalizer_closing_kg'] != '' ) { echo $row['geg_totalizer_closing_kg']; } ?>" >
    </div>

     <div class="form-group">
      <label for="geg_consumption_kg">GEG Consumption (kg)*</label>
      <input type="text" required readonly class="form-control" id="geg_consumption_kg" name="geg_consumption_kg"   value="<?php if(isset($row['geg_consumption_kg']) && $row['geg_consumption_kg'] != '' ) { echo $row['geg_consumption_kg']; } ?>" >
    </div>

    <div class="form-group">
      <label for="geg_running_hours">GEG Running Hours *</label>
      <input type="text"  required class="form-control" id="geg_running_hours" name="geg_running_hours"   value="<?php if(isset($row['geg_running_hours']) && $row['geg_running_hours'] != '' ) { echo $row['geg_running_hours']; } ?>" >
    </div>
<?php if( isset($row['is_submitted']) && $row['is_submitted'] == '1' ) { 
?>
<div class="form-group"  >
<label for="lng_storage_tank_file" class="form-label"
                >GEG File</label>
           
<a target="_blank" href='<?php echo "./uploads/".$row['geg_file']; ?>' >Open file</a>
            </div>
<?php }
else { ?>
           <div class="form-group"  >

             <label for="geg_file" class="form-label"
                >GEG File</label>
            <input
                type="file" onchange="lng_geg_consumption_kg();"  <?php if(isset($row['geg_file']) && $row['geg_file'] != '') { } else { echo "required"; } ?>  name="geg_file" id="geg_file" class="form-control" />
<?php if(isset($row['geg_file']) && $row['geg_file'] != '') { ?> <a target="_blank" href='<?php echo "./uploads/".$row['geg_file']; ?>' >Open file</a> <?php }  ?>
        
            </div>

 <div class="form-group">
      <label  for="Submit3"></label>
      <div class="col-sm-2">
      <input type="hidden" name="action" id="action" value="geg">
      <input type="submit" class="form-control  btn btn-primary" id="Submit3" name="Submit"></textarea>
    </div>
    </div>
    <?php } ?>
                        </form>

  </div>
</div>
<br>
<!-- <button class="btn btn-primary mt-3" type="button" data-toggle="collapse" data-target="#formSection4" aria-expanded="false" aria-controls="formSection4">
  Unloading MFM		
</button>
-->
<div class="card bg-info text-white">
    <div class="card-body">Unloading MFM</div>
  </div>


<div class="collapse <?php if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'geg' || ( isset($row['is_submitted']) && $row['is_submitted'] == '1' ) || (isset($row['step']) && $row['step'] == '3' ) )
{ ?> show <?php } ?>" id="formSection4">
  <div class="card card-body">

   <form
            action=""
            method="POST"
            enctype="multipart/form-data" 
        >
    <!-- Form elements for section 2 go here -->
    <div class="form-group">
      <label for="input1">Unloading MFM Opening Reading (kg) *</label>
      <input type="text" required onchange="lng_unloading_mfm_difference_kg();" class="form-control" id="unloading_mfm_opening_reading_kg" name="unloading_mfm_opening_reading_kg"  value="<?php if(isset($row['unloading_mfm_opening_reading_kg']) && $row['unloading_mfm_opening_reading_kg'] != '' ) { echo $row['unloading_mfm_opening_reading_kg']; } elseif(isset($row_prev['unloading_mfm_closing_reading_kg']) && $row_prev['unloading_mfm_closing_reading_kg'] != '') { echo $row_prev['unloading_mfm_closing_reading_kg']; } ?>"  >
    </div>
    <div class="form-group">
      <label for="input2">Unloading MFM Closing Reading (kg)*</label>
      <input type="text" required  onchange="lng_unloading_mfm_difference_kg();"  class="form-control" id="unloading_mfm_closing_reading_kg" name="unloading_mfm_closing_reading_kg"  value="<?php if(isset($row['unloading_mfm_closing_reading_kg']) && $row['unloading_mfm_closing_reading_kg'] != '' ) { echo $row['unloading_mfm_closing_reading_kg']; } ?>" >
    </div>

     <div class="form-group">
      <label for="input1">Unloading MFM Difference (kg)*</label>
      <input type="text" required readonly class="form-control" id="unloading_mfm_difference_kg" name="unloading_mfm_difference_kg"  value="<?php if(isset($row['unloading_mfm_difference_kg']) && $row['unloading_mfm_difference_kg'] != '' ) { echo $row['unloading_mfm_difference_kg']; } ?>">
    </div>

<?php if( isset($row['is_submitted']) && $row['is_submitted'] == '1' ) {
?>
<div class="form-group"  >
<label for="lng_storage_tank_file" class="form-label"
                >Unloading MFM File</label>
           
<a target="_blank" href='<?php echo "./uploads/".$row['unloading_mfm_file']; ?>' >Open file</a>
            </div>
<?php

 }
else { ?>

    <div class="form-group"  >

             <label for="lng_storage_tank_file" class="form-label"
                >Unloading MFM File</label>
            <input
                type="file" onchange="lng_unloading_mfm_difference_kg();"  <?php if(isset($row['unloading_mfm_file']) && $row['unloading_mfm_file'] != '') { } else { echo "required"; } ?>  name="unloading_mfm_file" id="unloading_mfm_file" class="form-control" />
<?php if(isset($row['unloading_mfm_file']) && $row['unloading_mfm_file'] != '') { ?> <a target="_blank" href='<?php echo "./uploads/".$row['unloading_mfm_file']; ?>' >Open file</a> <?php }  ?>

            </div>

             <div class="form-group">
      <label  for="input1"></label>
      <div class="col-sm-2">
      <input type="hidden" name="action" id="action" value="unloading_mfm">
      <input type="submit" class="form-control  btn btn-primary" id="Submit" name="Submit"></textarea>
    </div>
    </div>

    <?php } ?>

                        </form>

  </div>
</div>
<br>
<!-- <button class="btn btn-primary mt-3" type="button" data-toggle="collapse" data-target="#formSection5" aria-expanded="false" aria-controls="formSection5">
 Grid Power Meter
</button>-->
 <div class="card bg-info text-white">
    <div class="card-body">Grid Power Meter</div>
  </div>


<div class="collapse <?php if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'unloading_mfm' || ( isset($row['is_submitted']) && $row['is_submitted'] == '1' ) || (isset($row['step']) && $row['step'] == '4' ) )
{ echo "show";  }  ?>" id="formSection5">
  <div class="card card-body">
    <!-- Form elements for section 2 go here -->

     <form
            action=""
            method="POST"
            enctype="multipart/form-data" 
        >
    <div class="form-group">


        <div class="form-group">
      <label for="input1">Solar Power Meter Opening (KWH)*</label>
      <input type="text" required onchange="solar_power_meter_difference();" class="form-control" id="solar_power_opening_kwh" name="solar_power_opening_kwh"   value="<?php if(isset($row['solar_power_opening_kwh']) && $row['solar_power_opening_kwh'] != '' ) { echo $row['solar_power_opening_kwh']; } elseif(isset($row_prev['solar_power_meter_closing_kwh']) && $row_prev['solar_power_meter_closing_kwh'] != '') { echo $row_prev['solar_power_meter_closing_kwh']; } ?>">
    </div>

    <div class="form-group">
      <label for="input1">Solar Power Meter Closing (KWH)*</label>
      <input type="text" required onchange="solar_power_meter_difference();" class="form-control" id="solar_power_meter_closing_kwh" name="solar_power_meter_closing_kwh"  value="<?php if(isset($row['solar_power_meter_closing_kwh']) && $row['solar_power_meter_closing_kwh'] != '' ) { echo $row['solar_power_meter_closing_kwh']; } ?>"  >
    </div>
  

     <div class="form-group">
      <label for="input1">Solar Power Meter Difference (KWH) *</label>
      <input type="text" readonly required class="form-control" id="solar_power_meter_difference_kwh" name="solar_power_meter_difference_kwh"   value="<?php if(isset($row['solar_power_meter_difference_kwh']) && $row['solar_power_meter_difference_kwh'] != '' ) { echo $row['solar_power_meter_difference_kwh']; } ?>" >
    </div>
<?php if( isset($row['is_submitted']) && $row['is_submitted'] == '1' ) {

?>
       <div class="form-group"  >

             <label for="solar_power_meter_file" class="form-label"
                >Solar Power Meter File</label>
        <a target="_blank" href='<?php echo "./uploads/".$row['solar_power_meter_file']; ?>' >Open file</a>

            </div>

            <?php

 }
else { ?>
       <div class="form-group"  >

             <label for="lng_storage_tank_file" class="form-label"
                >Solar Power Meter File</label>
            <input
                type="file"  <?php if(isset($row['solar_power_meter_file']) && $row['solar_power_meter_file'] != '') { } else { echo "required"; } ?>  name="solar_power_meter_file"  id="solar_power_meter_file" class="form-control" />
<?php if(isset($row['solar_power_meter_file']) && $row['solar_power_meter_file'] != '') { ?> <a target="_blank" href='<?php echo "./uploads/".$row['solar_power_meter_file']; ?>' >Open file</a> <?php }  ?>


            </div>

            <?php } ?>

            <div class="form-group">
      <label for="input2">Grid Export Opening (kWH)*</label>
      <input type="text" required onchange="grid_power_export_meter_difference();" class="form-control" id="grid_power_export_opening_kwh" name="grid_power_export_opening_kwh"  value="<?php if(isset($row['grid_power_export_opening_kwh']) && $row['grid_power_export_opening_kwh'] != '' ) { echo $row['grid_power_export_opening_kwh']; } elseif(isset($row_prev['grid_power_export_meter_closing_kwh']) && $row_prev['grid_power_export_meter_closing_kwh'] != '') { echo $row_prev['grid_power_export_meter_closing_kwh']; } ?>" >
    </div>

    <div class="form-group">
      <label for="input2">Grid Export Closing (KWH)*</label>
      <input type="text" required onchange="grid_power_export_meter_difference();" class="form-control" id="grid_power_export_meter_closing_kwh" name="grid_power_export_meter_closing_kwh"  value="<?php if(isset($row['grid_power_export_meter_closing_kwh']) && $row['grid_power_export_meter_closing_kwh'] != '' ) { echo $row['grid_power_export_meter_closing_kwh']; } ?>" >
    </div>

     <div class="form-group">
      <label for="input1">Difference (kWH) = Closing - Opening *</label>
      <input type="text" readonly required class="form-control" id="grid_power_export_meter_difference_kwh"  name="grid_power_export_meter_difference_kwh"  value="<?php if(isset($row['grid_power_export_meter_difference_kwh']) && $row['grid_power_export_meter_difference_kwh'] != '' ) { echo $row['grid_power_export_meter_difference_kwh']; } ?>">
    </div>

<?php if( isset($row['is_submitted']) && $row['is_submitted'] == '1' ) { 
?>
<div class="form-group"  >
<label for="grid_power_export_meter_file" class="form-label"
                >Grid Power Export  File</label>
           
<a target="_blank" href='<?php echo "./uploads/".$row['grid_power_export_meter_file']; ?>' >Open file</a>
            </div>
<?php

}
else { ?>
       <div class="form-group"  >

             <label for="grid_power_export_meter_file" class="form-label"
                >Grid Power Export File</label>
            <input
                type="file"  <?php if(isset($row['grid_power_export_meter_file']) && $row['grid_power_export_meter_file'] != '') { } else { echo "required"; } ?>  name="grid_power_export_meter_file" onchange="grid_power_export_meter_difference();" id="grid_power_export_meter_file" class="form-control" />
<?php if(isset($row['grid_power_export_meter_file']) && $row['grid_power_export_meter_file'] != '') { ?> <a target="_blank" href='<?php echo "./uploads/".$row['grid_power_export_meter_file']; ?>' >Open file</a> <?php }  ?>

            </div>

            <?php } ?>

  <div class="form-group">
      <label for="input2">Grid Power Meter Opening (KWH)*</label>
      <input type="text" required onchange="grid_power_meter_difference();" class="form-control" id="grid_power_opening_kwh" name="grid_power_opening_kwh"  value="<?php if(isset($row['grid_power_opening_kwh']) && $row['grid_power_opening_kwh'] != '' ) { echo $row['grid_power_opening_kwh']; } elseif(isset($row_prev['grid_power_meter_closing_kwh']) && $row_prev['grid_power_meter_closing_kwh'] != '') { echo $row_prev['grid_power_meter_closing_kwh']; } ?>" >
    </div>

    <div class="form-group">
      <label for="input2">Grid Power Meter Closing (KWH)*</label>
      <input type="text" required onchange="grid_power_meter_difference();" class="form-control" id="grid_power_meter_closing_kwh" name="grid_power_meter_closing_kwh"  value="<?php if(isset($row['grid_power_meter_closing_kwh']) && $row['grid_power_meter_closing_kwh'] != '' ) { echo $row['grid_power_meter_closing_kwh']; } ?>" >
    </div>

     <div class="form-group">
      <label for="input1">Grid Power Meter Difference (KWH) *</label>
      <input type="text" readonly required class="form-control" id="grid_power_meter_difference_kwh"  name="grid_power_meter_difference_kwh"  value="<?php if(isset($row['grid_power_meter_difference_kwh']) && $row['grid_power_meter_difference_kwh'] != '' ) { echo $row['grid_power_meter_difference_kwh']; } ?>">
    </div>

<?php if( isset($row['is_submitted']) && $row['is_submitted'] == '1' ) {
?>
<div class="form-group"  >
<label for="grid_power_meter_file" class="form-label"
                >Grid Power Meter File</label>
           
<a target="_blank" href='<?php echo "./uploads/".$row['grid_power_meter_file']; ?>' >Open file</a>
            </div>
<?php

 }
else { ?>
       <div class="form-group"  >

             <label for="lng_storage_tank_file" class="form-label"
                >Grid Power Meter File</label>
            <input
                type="file"  <?php if(isset($row['grid_power_meter_file']) && $row['grid_power_meter_file'] != '') { } else { echo "required"; } ?>  name="grid_power_meter_file" onchange="grid_power_meter_difference();" id="grid_power_meter_file" class="form-control" />
<?php if(isset($row['grid_power_meter_file']) && $row['grid_power_meter_file'] != '') { ?> <a target="_blank" href='<?php echo "./uploads/".$row['grid_power_meter_file']; ?>' >Open file</a> <?php }  ?>

            </div>

             <div class="form-group">
      <label  for="input1"></label>
      <div class="col-sm-2">
      <input type="hidden" name="action" id="action" value="grid_power_meter">
      <input type="submit" class="form-control  btn btn-primary" id="Submit" name="Submit"></textarea>
    </div>
    </div>
<?php } ?>
                        </form>

  </div>
</div>
                        </div>

                        <div class="collapse <?php if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'grid_power_meter'  || ( isset($row['is_submitted']) && $row['is_submitted'] == '1' ) || (isset($row['step']) && $row['step'] == '5' ) )
{ echo "show";  }  ?>" id="formSection5">
  <div class="card card-body">
    <!-- Form elements for section 1 go here -->

     <form
            action=""
            method="POST"
            enctype="multipart/form-data" 
        >
    <div class="form-group">
      <label for="input1">Remark</label>
      <textarea class="form-control" id="remarks" name="remarks"><?php if(isset($row['remarks']) && $row['remarks'] != ''){ echo $row['remarks']; } ?></textarea>
    </div>
<?php if( isset($row['is_submitted']) && $row['is_submitted'] == '1' ) { }
else { ?>
     <div class="form-group">
      <label  for="input1"></label>
      <div class="col-sm-2">
      <input type="hidden" name="action" id="action" value="Update">
      <input type="submit" class="form-control  btn btn-primary" id="Submit" name="Submit"></textarea>
    </div>
    </div>

    <?php } ?>
                        </div>
                        
                        </div>
                        </div>
                    </div>
</form>
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
            $('#mis_date').on('change', function() {
        var selectedDate = $(this).val(); // get the selected date
       // console.log('Selected Date:', selectedDate);
//alert(selectedDate);
       if(selectedDate != '')
       {
           // alert(selectedDate);
            $('#formSection1').collapse('show');

            $("#mis_date_form").submit();

            $("#lng_opening_level_l").val('');
            $("#lng_opening_level_kg").val('');
            $("#lng_closing_level_l").val('');
            $("#lng_closing_level_kg").val('');
            $('#lng_difference_kg').val('');
       }

        // Your logic here
    });


function lng_litre_to_kg_opening()
{
    var lng_opening_level_l = document.getElementById('lng_opening_level_l').value;

    if(!isValidDecimal(lng_opening_level_l))
       {
            alert('Please enter valid decimal value');
            $('#lng_opening_level_l').val('');
            return false;
       }

    var lng_opening_level_kg = lng_opening_level_l*<?php echo $converter; ?>;

    document.getElementById('lng_opening_level_kg').value = lng_opening_level_kg;
    lng_storage_tank_difference_kg();
}

function lng_litre_to_kg_closing()
{
    var lng_closing_level_l = document.getElementById('lng_closing_level_l').value;

    if(!isValidDecimal(lng_closing_level_l))
       {
            alert('Please enter valid decimal value');
            $('#lng_closing_level_l').val('');
            return false;
       }

    var lng_closing_level_kg = lng_closing_level_l*<?php echo $converter; ?>;

    document.getElementById('lng_closing_level_kg').value = lng_closing_level_kg;
    lng_storage_tank_difference_kg();
}
    
function lng_storage_tank_difference_kg()
    {
        var lng_opening_level_kg = document.getElementById('lng_opening_level_kg').value;

             

        var lng_closing_level_kg = document.getElementById('lng_closing_level_kg').value;

        //alert(lng_closing_level_kg);

        

        var lng_difference_kg;

        if(lng_opening_level_kg != '' && lng_closing_level_kg != '')
        {


            if(!isValidDecimal(lng_opening_level_kg))
       {
          //  alert('Please enter valid decimal value');
            $('#lng_opening_level_kg').val('');
            return false;
       }

       if(!isValidDecimal(lng_closing_level_kg))
       {
         //   alert('Please enter valid decimal value');
            $('#lng_closing_level_kg').val('');
            return false;
       }

      

                lng_difference_kg = lng_closing_level_kg-lng_opening_level_kg;

                document.getElementById('lng_difference_kg').value = lng_difference_kg;

                  var lng_storage_tank_file = document.getElementById('lng_storage_tank_file').value;

       if(lng_storage_tank_file == '')
       {
           // alert('Please select file');

           // return false;
       }

                // $('#formSection1').collapse('hide');

                //  $('#formSection2').collapse('show');
        }
    }

    function lng_dispenser_sold_quantity_kg()
    {
        var lng_totalizer_opening_kg = document.getElementById('lng_totalizer_opening_kg').value;

            

        var lng_totalizer_closing_kg = document.getElementById('lng_totalizer_closing_kg').value;

       

        var sold_qty_dispenser_kg;

        if(lng_totalizer_opening_kg != '' && lng_totalizer_closing_kg != '')
        {
            
            if(!isValidDecimal(lng_totalizer_opening_kg))
       {
           // alert('Please enter valid decimal value');
            $('#lng_totalizer_opening_kg').val('');
            return false;
       }

       if(!isValidDecimal(lng_totalizer_closing_kg))
       {
         //   alert('Please enter valid decimal value');
            $('#lng_totalizer_closing_kg').val('');
            return false;
       }

       
            sold_qty_dispenser_kg = lng_totalizer_closing_kg-lng_totalizer_opening_kg;

                document.getElementById('sold_qty_dispenser_kg').value = sold_qty_dispenser_kg;

                 var lng_dispenser_file = document.getElementById('lng_dispenser_file').value;

       if(lng_dispenser_file == '')
       {
       //     alert('Please select file');

          //  return false;
       }

              //  $('#formSection2').collapse('hide');

              //    $('#formSection3').collapse('show');
        }
    }

    function lng_geg_consumption_kg()
    {
        var geg_totalizer_opening_kg = document.getElementById('geg_totalizer_opening_kg').value;

            

        var geg_totalizer_closing_kg = document.getElementById('geg_totalizer_closing_kg').value;

        

        var geg_consumption_kg;

        if(geg_totalizer_opening_kg != '' && geg_totalizer_closing_kg != '')
        {
            if(!isValidDecimal(geg_totalizer_opening_kg))
       {
         //   alert('Please enter valid decimal value');
            $('#geg_totalizer_opening_kg').val('');
            return false;
       }

       if(!isValidDecimal(geg_totalizer_closing_kg))
       {
        //    alert('Please enter valid decimal value');
            $('#geg_totalizer_closing_kg').val('');
            return false;
       }

       

                geg_consumption_kg = geg_totalizer_closing_kg-geg_totalizer_opening_kg;

                document.getElementById('geg_consumption_kg').value = geg_consumption_kg;

                 var geg_file = document.getElementById('geg_file').value;

       if(geg_file == '')
       {
         //   alert('Please select file');

         //   return false;
       }

            //    $('#formSection3').collapse('hide');

             //     $('#formSection4').collapse('show');

        }
    }


    function lng_unloading_mfm_difference_kg()
    {
        var unloading_mfm_opening_reading_kg = document.getElementById('unloading_mfm_opening_reading_kg').value;

            

        var unloading_mfm_closing_reading_kg = document.getElementById('unloading_mfm_closing_reading_kg').value;

      

        var unloading_mfm_difference_kg;

        if(unloading_mfm_opening_reading_kg != '' && unloading_mfm_closing_reading_kg != '')
        {
                if(!isValidDecimal(unloading_mfm_opening_reading_kg))
       {
          //  alert('Please enter valid decimal value');
            $('#unloading_mfm_opening_reading_kg').val('');
            return false;
       }

        if(!isValidDecimal(unloading_mfm_closing_reading_kg))
       {
          //  alert('Please enter valid decimal value');
            $('#unloading_mfm_closing_reading_kg').val('');
            return false;
       }
        
        
            unloading_mfm_difference_kg = unloading_mfm_closing_reading_kg-unloading_mfm_opening_reading_kg;

                document.getElementById('unloading_mfm_difference_kg').value = unloading_mfm_difference_kg;

                var unloading_mfm_file = document.getElementById('unloading_mfm_file').value;

       if(unloading_mfm_file == '')
       {
           // alert('Please select file');

          //  return false;
       }

             //   $('#formSection4').collapse('hide');

             //     $('#formSection5').collapse('show');
        }
    }

/*
    function lng_unloading_mfm_difference_kg()
    {
        var unloading_mfm_opening_reading_kg = document.getElementById('unloading_mfm_opening_reading_kg').value;

            

        var unloading_mfm_closing_reading_kg = document.getElementById('unloading_mfm_closing_reading_kg').value;

        var unloading_mfm_difference_kg;

        if(unloading_mfm_opening_reading_kg != '' && unloading_mfm_closing_reading_kg != '')
        {
                unloading_mfm_difference_kg = unloading_mfm_closing_reading_kg-unloading_mfm_opening_reading_kg;

                document.getElementById('unloading_mfm_difference_kg').value = unloading_mfm_difference_kg;
        }
    }

    */

    function grid_power_meter_difference()
    {
        var grid_power_opening_kwh = document.getElementById('grid_power_opening_kwh').value;

           

        var grid_power_meter_closing_kwh = document.getElementById('grid_power_meter_closing_kwh').value;

       

        var grid_power_meter_difference_kwh;

        if(grid_power_opening_kwh != '' && grid_power_meter_closing_kwh != '')
        {
             if(!isValidDecimal(grid_power_opening_kwh))
       {
          //  alert('Please enter valid decimal value');
            $('#grid_power_opening_kwh').val('');
            return false;
       }

        if(!isValidDecimal(grid_power_meter_closing_kwh))
       {
          //  alert('Please enter valid decimal value');
            $('#grid_power_meter_closing_kwh').val('');
            return false;
       }

       
                grid_power_meter_difference_kwh = grid_power_meter_closing_kwh-grid_power_opening_kwh;

                document.getElementById('grid_power_meter_difference_kwh').value = grid_power_meter_difference_kwh;

var grid_power_meter_file = document.getElementById("grid_power_meter_file").value;

       if(grid_power_meter_file == '')
       {
          //  alert('please select file');

          //  return false;
       }

          /*      $('#formSection4').collapse('show');

                  $('#formSection5').collapse('show');

                  $('#formSection3').collapse('show');

                  $('#formSection2').collapse('show');

                   $('#formSection1').collapse('show');
                   */

        }
    }


    function grid_power_export_meter_difference()
    {
        var grid_power_opening_kwh = document.getElementById('grid_power_export_opening_kwh').value;

         //  alert(grid_power_opening_kwh);

        var grid_power_meter_closing_kwh = document.getElementById('grid_power_export_meter_closing_kwh').value;

      // alert(grid_power_meter_closing_kwh);

        var grid_power_meter_difference_kwh;

        if(grid_power_opening_kwh != '' && grid_power_meter_closing_kwh != '')
        {
             if(!isValidDecimal(grid_power_opening_kwh))
       {
          //  alert('Please enter valid decimal value');
            $('#grid_power_export_opening_kwh').val('');
            return false;
       }

        if(!isValidDecimal(grid_power_meter_closing_kwh))
       {
          //  alert('Please enter valid decimal value');
            $('#grid_power_export_meter_closing_kwh').val('');
            return false;
       }

       
                grid_power_meter_difference_kwh = grid_power_meter_closing_kwh-grid_power_opening_kwh;

                document.getElementById('grid_power_export_meter_difference_kwh').value = grid_power_meter_difference_kwh;

var grid_power_meter_file = document.getElementById("grid_power_meter_file").value;

       if(grid_power_meter_file == '')
       {
         
       }

         

        }
    }


    function solar_power_meter_difference()
    {
        var solar_power_opening_kwh = document.getElementById('solar_power_opening_kwh').value;

           

        var solar_power_meter_closing_kwh = document.getElementById('solar_power_meter_closing_kwh').value;

       

        var solar_power_meter_difference_kwh;

        if(solar_power_opening_kwh != '' && solar_power_meter_closing_kwh != '')
        {
             if(!isValidDecimal(solar_power_opening_kwh))
       {
            alert('Please enter valid decimal value');
            $('#solar_power_opening_kwh').val('');
            return false;
       }

        if(!isValidDecimal(solar_power_meter_closing_kwh))
       {
            alert('Please enter valid decimal value');
            $('#solar_power_meter_closing_kwh').val('');
            return false;
       }
                solar_power_meter_difference_kwh = solar_power_meter_closing_kwh-solar_power_opening_kwh;

                document.getElementById('solar_power_meter_difference_kwh').value = solar_power_meter_difference_kwh;

       /*         $('#formSection4').collapse('show');

                  $('#formSection5').collapse('show');

                  $('#formSection3').collapse('show');

                  $('#formSection2').collapse('show');

                   $('#formSection1').collapse('show');

                   */

        }
    }
    
/*
    document.addEventListener('DOMContentLoaded', function() {
            var today = new Date();
            var dd = String(today.getDate()).padStart(2, '0');
            var mm = String(today.getMonth() + 1).padStart(2, '0'); // January is 0!
            var yyyy = today.getFullYear();

            var minDate = yyyy + '-' + mm + '-' + dd;

            document.getElementById('mis_date').setAttribute('min', minDate);
        });

        */

     /*   document.addEventListener('DOMContentLoaded', function () {
    var today = new Date();
    var yesterday = new Date();
    yesterday.setDate(today.getDate() - 1);

    // Format date to YYYY-MM-DD
    function formatDate(date) {
        var dd = String(date.getDate()).padStart(2, '0');
        var mm = String(date.getMonth() + 1).padStart(2, '0'); // Month is 0-based
        var yyyy = date.getFullYear();
        return yyyy + '-' + mm + '-' + dd;
    }

    var minDate = formatDate(yesterday);
    var maxDate = formatDate(today);

    var dateInput = document.getElementById('mis_date');
    dateInput.setAttribute('min', minDate);
    dateInput.setAttribute('max', maxDate);
});

*/
/*
document.addEventListener('DOMContentLoaded', function () {
    var today = new Date();
    var yesterday = new Date();
    yesterday.setDate(today.getDate() - 1);

    function formatDate(date) {
        var dd = String(date.getDate()).padStart(2, '0');
        alert(dd);
        var mm = String(date.getMonth() + 1).padStart(2, '0');
        var yyyy = date.getFullYear();
        return yyyy + '-' + mm + '-' + dd;
    }

    var minDate = formatDate(yesterday);
    var maxDate = formatDate(today);

    var dateInput = document.getElementById('mis_date');
    dateInput.setAttribute('min', minDate);
    dateInput.setAttribute('max', maxDate);

    // ✅ Set default value to today
    dateInput.value = '<?php if(isset($_REQUEST['mis_date'])) { echo $_REQUEST['mis_date']; } ?>';
});

*/

document.addEventListener('DOMContentLoaded', function () { 
    var today = new Date();
    var yesterday = new Date();
    yesterday.setDate(today.getDate() - 1);

    function formatDate(date) {
        var dd = String(date.getDate()).padStart(2, '0');
        var mm = String(date.getMonth() + 1).padStart(2, '0');
        var yyyy = date.getFullYear();
        return yyyy + '-' + mm + '-' + dd; // required by <input type="date">
    }

    var minDate = formatDate(yesterday);
    var maxDate = formatDate(today);

    var dateInput = document.getElementById('mis_date');
    dateInput.setAttribute('min', minDate);
    dateInput.setAttribute('max', maxDate);

    // If PHP has a value, set it; otherwise leave empty
    var phpDate = "<?php if(isset($_REQUEST['mis_date'])) echo $_REQUEST['mis_date']; ?>";
    if (phpDate) {
        // Convert from dd-mm-yyyy to yyyy-mm-dd if needed

        
        var parts = phpDate.split('-');
        if (parts.length === 3) {
            
            dateInput.value = phpDate;
        }
        else{
            dateInput.value = "2025-03-12";
        }
    }
});



/*
$('#lng_opening_level_l').on('change', function() {
        // Get the value of the input field
        var data = $(this).val();
       // alert('Date selected: ' + dateValue); // Show the selected date in an alert box

       if(!isValidDecimal(data))
       {
            alert('Please enter valid decimal value');
            $(this).val('');
            return false;
       }

      });

      $('#lng_closing_level_l').on('change', function() {
        // Get the value of the input field
        var data = $(this).val();
       // alert('Date selected: ' + dateValue); // Show the selected date in an alert box

       if(!isValidDecimal(data))
       {
            alert('Please enter valid decimal value');
            $(this).val('');
            return false;
       }

      });

      */

      function isValidDecimal(input) {
    const regex = /^[-+]?\d+(\.\d+)?$/;
    return regex.test(input);
}

$("#lng_totalizer_opening_kg").change(function(){
   var lng_totalizer_opening_kg = $(this).val();

   if(isValidDecimal(lng_totalizer_opening_kg))
   {

   }
   else{
            alert('Please enter valid decimal value');

            $(this).val('');
   }


});

$("#lng_totalizer_closing_kg").change(function(){
   var lng_totalizer_closing_kg = $(this).val();

   if(isValidDecimal(lng_totalizer_closing_kg))
   {

   }
   else{
            alert('Please enter valid decimal value');

            $(this).val('');
   }


});



$("#geg_totalizer_opening_kg").change(function(){
   var geg_totalizer_opening_kg = $(this).val();

   if(isValidDecimal(geg_totalizer_opening_kg))
   {

   }
   else{
            alert('Please enter valid decimal value');

            $(this).val('');
   }


});


$("#geg_totalizer_closing_kg").change(function(){
   var geg_totalizer_closing_kg = $(this).val();

   if(isValidDecimal(geg_totalizer_closing_kg))
   {

   }
   else{
            alert('Please enter valid decimal value');

            $(this).val('');
   }


});


$("#unloading_mfm_opening_reading_kg").change(function(){
   var unloading_mfm_opening_reading_kg = $(this).val();

   if(isValidDecimal(unloading_mfm_opening_reading_kg))
   {

   }
   else{
            alert('Please enter valid decimal value');

            $(this).val('');

            
   }


});

$("#unloading_mfm_closing_reading_kg").change(function(){
   var unloading_mfm_closing_reading_kg = $(this).val();

   if(isValidDecimal(unloading_mfm_closing_reading_kg))
   {

   }
   else{
            alert('Please enter valid decimal value');

            $(this).val('');
   }


});

$("#grid_power_meter_closing_kwh").change(function(){
   var grid_power_meter_closing_kwh = $(this).val();

   if(isValidDecimal(grid_power_meter_closing_kwh))
   {

   }
   else{
            alert('Please enter valid decimal value');

            $(this).val('');
   }


});
$("#grid_power_opening_kwh").change(function(){
   var grid_power_opening_kwh = $(this).val();

   if(isValidDecimal(grid_power_opening_kwh))
   {

   }
   else{
            alert('Please enter valid decimal value');

            $(this).val('');
   }


});



$("#solar_power_meter_closing_kwh").change(function(){
   var solar_power_meter_closing_kwh = $(this).val();

   if(isValidDecimal(solar_power_meter_closing_kwh))
   {

   }
   else{
          //  alert('Please enter valid decimal value');

            $(this).val('');
   }


});
$("#solar_power_opening_kwh").change(function(){
   var solar_power_opening_kwh = $(this).val();

   if(isValidDecimal(solar_power_opening_kwh))
   {

   }
   else{
          //  alert('Please enter valid decimal value');

            $(this).val('');
   }


});


$("#geg_running_hours").change(function(){
   var geg_running_hours = $(this).val();

   if(isValidDecimal(geg_running_hours))
   {

   }
   else{
          //  alert('Please enter valid decimal value');

            $(this).val('');
   }


});


$("#grid_power_export_opening_kwh").change(function(){
   var geg_running_hours = $(this).val();

   if(isValidDecimal(geg_running_hours))
   {

   }
   else{
            alert('Please enter valid decimal value');

            $(this).val('');
   }


});




$("#grid_power_export_meter_closing_kwh").change(function(){
   var geg_running_hours = $(this).val();

   if(isValidDecimal(geg_running_hours))
   {

   }
   else{
           alert('Please enter valid decimal value');

            $(this).val('');
   }


});

/*

document.querySelector('input[type=file]').addEventListener('change', function () {
    const file = this.files[0]; // Declare first
alert('file')
   // alert(file ? file.name : 'No file selected');

    if (file) {
        const allowedTypes = ['application/pdf', 'image/jpeg', 'image/png', 'image/gif'];
        if (!allowedTypes.includes(file.type)) {
            alert('❌ Please select a PDF or image file.');
            this.value = '';
        } else if (file.size > 5 * 1024 * 1024) {
            alert('❌ File size must be under 5MB.');
            this.value = '';
        }
    }
});
*/

document.addEventListener('DOMContentLoaded', function () {
    const fileInputs = document.querySelectorAll('input[type=file]');

    fileInputs.forEach(function (input) {
        input.addEventListener('change', function () {
            const file = this.files[0];
            if (!file) return;

            const allowedTypes = ['application/pdf', 'image/jpeg', 'image/png', 'image/gif'];
            const maxSize = 5 * 1024 * 1024; // 5MB

            if (!allowedTypes.includes(file.type)) {
                alert(`❌ "${file.name}" is not a valid file type. Only PDF and images allowed.`);
                this.value = '';
                return;
            }

            if (file.size > maxSize) {
                alert(`❌ "${file.name}" exceeds the 5MB size limit.`);
                this.value = '';
                return;
            }

            console.log(`✅ File "${file.name}" is valid.`);
        });
    });
});


        </script>

      <!-- jQuery + jQuery UI CSS/JS -->
<link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">

<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>

<script>
$(document).ready(function () {
    // Get today's date and yesterday
    var today = new Date();
    var yesterday = new Date();
    yesterday.setDate(today.getDate() - 1);

    // Attach datepicker
    $("#mis_date").datepicker({
        dateFormat: "dd-mm-yy", // show as dd-mm-yyyy
        minDate: yesterday,     // yesterday
        maxDate: today,         // today
        changeMonth: true,
        changeYear: true
    });

    // If PHP sent a value, set it
    var phpDate = "<?php if(isset($_REQUEST['mis_date'])) echo $_REQUEST['mis_date']; ?>";
    if (phpDate) {
        $("#mis_date").val(phpDate);
    }
});
</script>


</body>

</html>
