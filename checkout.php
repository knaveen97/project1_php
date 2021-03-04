<?php include('css/header.php'); ?>
<div class="container text-center">
    <?php
    $book_id = $_COOKIE['book_id'];
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "bookstore";
    $conn = mysqli_connect($servername, $username, $password, $dbname);
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }
    if ($_SERVER['REQUEST_METHOD'] === 'GET' and isset($_COOKIE['book_id'])) {
        $sql = "SELECT ID, NAME, QUANTITY, COST FROM bookinventory WHERE ID=" . $book_id;
        $result = mysqli_query($conn, $sql);
        if (mysqli_num_rows($result) == 1) {
    ?>

            <h1>Checkout</h1>
            <table class="table table-dark table-striped mt-5">
                <thead>
                    <tr>
                        <th scope="col">Book Name</th>
                        <th scope="col">Available Quantity</th>
                        <th scope="col">Cost</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                        <tr>
                            <td><?php echo $row["NAME"] ?></td>
                            <td><?php echo $row["QUANTITY"] ?></td>
                            <td><?php echo $row["COST"] ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
            <hr>
            <h2>User Details</h2>  
            <form method="POST" action="checkout.php" class="mt-5">
                <div class="row g-3">
                    <div class="col">
                        <input type="text" name="FIRST_NAME" class="form-control" placeholder="First name" aria-label="First name" required>
                    </div>
                    <div class="col">
                        <input type="text" name="LAST_NAME" class="form-control" placeholder="Last name" aria-label="Last name" required>
                    </div>
                    <div class="col">
                        <input type="number" name="CARD" class="form-control" placeholder="Card Number" aria-label="Card Number" required>
                    </div>
                    <div class="col">
                        <input type="hidden" name="BOOK" value="<?php echo $book_id ?>">
                        <input type="submit" name="buy" class="btn btn-primary" value="submit order">
                    </div>
                </div>
            </form>
        <?php } ?>
    <?php
    } elseif ($_SERVER['REQUEST_METHOD'] === 'POST' and !empty($_POST["buy"])) {
        $fname = $_POST["FIRST_NAME"];
        $lname = $_POST["LAST_NAME"];
        $card = $_POST["CARD"];
        $book = $_POST["BOOK"];
        $date = $a = date("Y-m-d H:i:s");
        $sql = "INSERT INTO `bookinventorycustomer` (`FIRST_NAME`, `LAST_NAME`, `CARD`) VALUES ('" . $fname . "','" . $lname . "','" . $card . "')";
        if (mysqli_query($conn, $sql)) {
            $last_id = mysqli_insert_id($conn);
            $sql = "INSERT INTO `bookinventoryorder` (`CUSTOMER_ID`, `BOOK_ID`, `ORDER_DATE`) VALUES ('" . $last_id . "','" . $book . "','" . $date . "')";
            if (mysqli_query($conn, $sql)) {
                $sql = "UPDATE bookinventory SET QUANTITY=QUANTITY-1 WHERE id=".$book;   //reducing the quantity
                if (mysqli_query($conn, $sql)) {
                    echo "order created successfully!!!";
                  } else {
                    echo "Error updating Quantity";
                  }
            } else {
                echo "<h1>Error While creating order</h1>";
            }
        } else {
            echo "<h1>Error While creating customer</h1>";
        }
        setcookie("book_id", "", time() - 3600);

    ?>
    <?php
    } else {
    ?>
        <h1>Invalid REQUEST. Please go to homepage</h1>

    <?php
    }
    ?>
</div>
<?php include('css/footer.php'); ?>