<?php
include('css/header.php');
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "bookstore";
$conn = mysqli_connect($servername, $username, $password, $dbname);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
session_start();
?>
<div class="container text-center">
    <h1>Inventory</h1>

    <?php
    $sql = "SELECT ID, NAME, QUANTITY, COST FROM bookinventory";
    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) > 0) {
    ?>
        <table class="table table-dark table-striped mt-5">
            <thead>
                <tr>
                    <th scope="col">Book</th>
                    <th scope="col">Quantity</th>
                    <th scope="col">Buy</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                    <tr>
                        <td><?php echo $row["NAME"] ?></td>
                        <td><?php echo $row["QUANTITY"] ?></td>
                        <td><?php echo $row["COST"] ?></td>
                        <td><button class="btn btn-primary" onclick="setCookie('book_id', <?php echo $row['ID'] ?>, 1);window.location.href = './checkout.php';">Proceed to buy</button> </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    <?php } ?>
</div>
<?php
mysqli_close($conn);
include('css/footer.php');
?>