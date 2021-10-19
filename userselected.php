
<?php
include 'config.php';

if(isset($_POST['submit']))
{
    $from = $_GET['id'];
    $to = $_POST['to'];
    $amount = $_POST['amount'];

    $sql = "SELECT * from users where id=$from";
    $query = mysqli_query($conn,$sql);
    $sql1 = mysqli_fetch_array($query); //it returns the array of user from which the amount is to be transferred.

    $sql = "SELECT * from users where id=$to";
    $query = mysqli_query($conn,$sql);
    $sql2 = mysqli_fetch_array($query);



    //checking input of negative value from user
    if (($amount)<0)
   {
        echo '<script type="text/javascript">';
        echo ' alert("Sorry, Negative values cannot be transferred!")';  // displaying an alert box.
        echo '</script>';
    }


  
    //checking insufficient balance.
    else if($amount > $sql1['balance']) 
    {
        
        echo '<script type="text/javascript">';
        echo ' alert("Sorry, Insufficient Balance!")';  // displaying an alert box.
        echo '</script>';
    }
    


    // checking zero values
    else if($amount == 0){

         echo "<script type='text/javascript'>";
         echo "alert('Sorry, Zero value cannot be transferred!')";
         echo "</script>";
     }


    else {
        
                // to deduct the amount from sender's balance
                $newbalance = $sql1['balance'] - $amount;
                $sql = "UPDATE users set balance=$newbalance where id=$from";
                mysqli_query($conn,$sql);
             

                // to add the amount to receiver's balance
                $newbalance = $sql2['balance'] + $amount;
                $sql = "UPDATE users set balance=$newbalance where id=$to";
                mysqli_query($conn,$sql);
                
                $sender = $sql1['name'];
                $receiver = $sql2['name'];
                $sql = "INSERT INTO transaction(`sender`, `receiver`, `balance`) VALUES ('$sender','$receiver','$amount')";
                $query=mysqli_query($conn,$sql);

                if($query){
                     echo "<script> alert('Your Transaction is Successful');
                                     window.location='transactions.php';
                           </script>";
                    
                }

                $newbalance= 0;
                $amount =0;
        }
    
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Money Transfer Portal</title>
    <link rel="stylesheet" href="css/style.css?v=<?php echo time(); ?>">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KyZXEAg3QhqLMpG8r+8fhAXLRk2vvoC2f3B09zVXn8CA5QIVfZOJ3BCsw2P0p/We" crossorigin="anonymous">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-U1DAWAznBHeqEIlVSCgzq+c9gqGAJn5c/t99JyeKa9xxaYpSvHU5awsuZVVFIhvj" crossorigin="anonymous"></script>  
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js" integrity="sha384-eMNCOe7tC1doHpGoWe/6oMVemdAVTMs2xqW4mwXrXsW0L84Iytr2wi5v2QjrP/xp" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.min.js" integrity="sha384-cn7l7gDp0eyniUwwAZgrzD06kc/tftFf19TOAs2zVinnD/C7E91j9yyk5//jjpt/" crossorigin="anonymous"></script> 



</head>

<body>
    <!-- Navigation bar-->
<div class="container-fluid main_menu">
         <div class="row">
             <div class = "col-md-10 col-12 mx-auto">
                <nav class="navbar navbar-expand-lg ">
                    <div class="container-fluid">
                      <a class="navbar-brand" href="#"> DJN BANK </a>
                      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                      </button>
                      <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                        <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="index.php">Home</a>
                        </li>
                        <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="customers.php">Our Customers</a>
                        </li>
                        <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="transactions.php">Transfer History</a>
                        </li>
                        </ul>
                
                      </div>
                    </div>
                </nav>
             </div>

         </div>

     </div>
 

	<div class="container">
        <h2 class="text-center pt-4" style="color : black;">Money Transfer Portal</h2>
            <?php
                include 'config.php';
                $sid=$_GET['id'];
                $sql = "SELECT * FROM  users where id=$sid";
                $result=mysqli_query($conn,$sql);
                if(!$result)
                {
                    echo "Error : ".$sql."<br>".mysqli_error($conn);
                }
                $rows=mysqli_fetch_assoc($result);
            ?>
            <form method="post" name="tcredit">
        <div>
            <table class="tablestyle">
                <tr>
                    <th>Account No.</th>
                    <th>Account Name</th>
                    <th>E-mail</th>
                    <th>Account Balance(in Rs.)</th>
                </tr>

                <tr>
                    <td><?php echo $rows['id'] ?></td>
                    <td><?php echo $rows['name'] ?></td>
                    <td><?php echo $rows['email'] ?></td>
                    <td><?php echo $rows['balance'] ?></td>
                </tr>
            </table>
        </div>
        <div class="container-fluid main_header">
        <div class="row">
            <div class = "col-md-10 col-12 mx-auto">
                <div class="row">
                    <div class="col-md-6 col-12 main_head_left">
                    <label><b>Transfer To:</b></label>
        <select name="to" class="select" required>
            <option value="" disabled selected>Choose account</option>
            <?php
                include 'config.php';
                $sid=$_GET['id'];
                $sql = "SELECT * FROM users where id!=$sid";
                $result=mysqli_query($conn,$sql);
                if(!$result)
                {
                    echo "Error ".$sql."<br>".mysqli_error($conn);
                }
                while($rows = mysqli_fetch_assoc($result)) {
            ?>
                <option value="<?php echo $rows['id'];?>" >
                
                    <?php echo $rows['name'] ;?> (Balance: 
                    <?php echo $rows['balance'] ;?> ) 
               
                </option>
            <?php 
                } 
            ?>
        
        </select>



                    </div>
                    <div class="col-md-6 col-12 main_head_right">
                    <label><b>Amount:</b></label>
           
           <input type="number" class="forminput" name="amount" required> 
           
                    </div>
                </div>
            </div>
        </div>
        <div class="transbutton" >
           <button class="transbut" name="submit" type="submit" id="myBtn" >Transfer Money</button>
           </div>


       </form>
    </div>
       
    </div>


   
    <footer>
        <p>
            created with love ❤ by Dhananjay
        </p>
    </footer>
</body>
</html>