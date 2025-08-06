<?php
session_start();
 // print_r($_SESSION);

require_once('./include/database.php');

if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'sendotp')
{
    $rand = rand(1000,9999);

    $mobile_number = $_REQUEST['mobile_number'];

    $mobile_otp = array();

    $mobile_otp['mobile_number'] = $mobile_number;
    $mobile_otp['mobile_otp'] = $rand;

    $_SESSION['mobile_otp'] = $mobile_otp;

$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => 'https://control.msg91.com/api/v5/flow',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'POST',
  CURLOPT_POSTFIELDS =>'{
  "template_id": "68149892d6fc05710160f782",
  "realTimeResponse": "1", 
  "recipients": [
    {
      "mobiles": "91'.$mobile_number.'",
      "otp": "'.$rand.'"
    }
  ]
}',
  CURLOPT_HTTPHEADER => array(
    'authkey: 449291Ar1os6ig1686e2758P1',
    'Content-Type: application/json',
    'Cookie: HELLO_APP_HASH=N2JkcUtnMktadzNabUN4Z0laOGV5cFB5Z0tjMGhTZEJFK1F4ZG1OSTV4TT0%3D; PHPSESSID=9avvs5df1frq9hso54vk0k82ae'
  ),
));

$response = curl_exec($curl);

curl_close($curl);
echo $response;



}
elseif(isset($_REQUEST['action']) && $_REQUEST['action'] == 'verify_otp')
{
    if($_REQUEST['mobile_number'] == $_SESSION['mobile_otp']['mobile_number'] && $_REQUEST['otp'] == $_SESSION['mobile_otp']['mobile_otp'] )
    {
        // echo "OTP verified";

        $SelectQuery = "SELECT * FROM operator_masters WHERE mobile = '".$_SESSION['mobile_otp']['mobile_number']."'";

       $result = $mysqli->query($SelectQuery);

       $row = $result->fetch_assoc();

       if(isset($row) && count($row) > 0)
       {
            $_SESSION['userlogin'] = $row;

            header("Location: mis.php");
       }

    }
    else
    {
        $message = "Incorrect otp";
    }

}

?><!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>SB Admin 2 - Login</title>

    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">

</head>

<body class="bg-gradient-primary">

    <div class="container">

        <!-- Outer Row -->
        <div class="row justify-content-center">

            <div class="col-xl-10 col-lg-12 col-md-9">

                <div class="card o-hidden border-0 shadow-lg my-5">
                    <div class="card-body p-0">
                        <!-- Nested Row within Card Body -->
                        <div class="row">
                            <div class="col-lg-6 d-none d-lg-block bg-login-image"></div>
                            <div class="col-lg-6">
<?php if(isset($message) && $message != '') { ?>
                           <div class="alert alert-success" role="alert">
  <?php echo $message; ?>
</div>
                                             <?php } ?>
                             
                                <div class="p-5">
                                    <div class="text-center">
                                        <h1 class="h4 text-gray-900 mb-4">Welcome Back!</h1>
                                    </div>
                                    <form class="user" method="post" action="">
                                        <div class="form-group">
                                            <input type="text" class="form-control form-control-user"
                                                id="mobile_number"  pattern="[0-9]{10}" name="mobile_number" <?php if(isset($_SESSION['mobile_otp'])) { echo "readonly"; } ?> value="<?php if(isset($_SESSION['mobile_otp'])) { echo $_SESSION['mobile_otp']['mobile_number']; } ?>" aria-describedby="mobile"
                                                placeholder="Enter Mobile Number...">
                                        </div>
                                        <?php if(isset($_SESSION['mobile_otp'])) { ?>
                                                 <div class="form-group">
                                            <input type="text" class="form-control form-control-user"
                                                id="otp"   name="otp" aria-describedby="mobile"
                                                placeholder="Enter otp received on Mobile Number...">
                                        </div>
                                            
                                            <?php } ?>
                                        <input type="hidden" name="action" id="action" value="<?php if(isset($_SESSION['mobile_otp'])) { echo "verify_otp"; } else { echo "sendotp"; } ?>">
                                        <input type="submit" name="SendOtp" value="<?php if(isset($_SESSION['mobile_otp'])) { echo "verify otp"; } else { echo "send otp"; } ?>" idclass="btn btn-primary btn-user btn-block">
                                    </form>
                                    <hr>
                                   
                                </div>
                            </div>
                        </div>
                    </div>
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

</body>


</html>
