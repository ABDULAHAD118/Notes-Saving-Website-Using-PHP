<?php
  $servername="localhost";
    $username="root";
    $password="";
    $database="notes";
    $conn=mysqli_connect($servername,$username,$password,$database);
    if(!$conn){
     die ("Not Connected to Database ". mysqli_connect_error());
    }  
?>
<!DOCTYPE html>

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Notes Website</title>
  <link rel="shortcut icon" href="notes.png" type="image/x-icon">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">

  <link rel="stylesheet" href="//cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
  <style>
    nav>img {
      width: 3%;
    }
  </style>
</head>

<body>
  <nav class="navbar navbar-expand-lg bg-body-tertiary">
    <img src="notes.png" class="logo me-3 ms-3">
    <a class="navbar-brand" href="Notes website.php">Note Pad</a>
    <div class="container-fluid">
      <span></span>


      <form class="d-flex" role="search">
        <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
        <button class="btn btn-outline-success" type="submit">Search</button>
      </form>
    </div>
  </nav>
  <?php
      if(isset($_GET['delete'])){
         $SrNo = $_GET['delete']; 
         $sql = "DELETE FROM `notes` WHERE `notes`.`Sr.No` = $SrNo";
         $result= mysqli_query($conn,$sql);
       }
     if($_SERVER['REQUEST_METHOD'] =='POST'){
      if(isset($_POST['SrNoEdit'])){
      $SrNo = $_POST['SrNoEdit']; 
      $Title = $_POST['TitleEdit']; 
      $Description = $_POST['DescriptionEdit'];
      $sql = "UPDATE `notes` SET `Title` = '$Title', `Description` = '$Description'  WHERE `notes`.`Sr.No` = '$SrNo'";
      $result= mysqli_query($conn,$sql);
      }
      else{
      $Title = $_POST['Title'];
      $Description = $_POST['Description'];
      $sql="INSERT INTO `notes` (`Title`, `Description`) VALUES ('$Title', '$Description')";
      $result= mysqli_query($conn,$sql);
      }
      if($result){
        echo " <div class='alert alert-success alert-dismissible fade show' role='alert'>
        <strong>Congratulation!</strong> Note has sucessfully added.
        <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
      </div>";
      }
      else
      echo " <div class='alert alert-warning alert-dismissible fade show' role='alert'>
      <strong>Warning!</strong> Note not insertedd due to this error ". mysqli_error($conn). 
       "<button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
    </div>";
       }
  ?>
  <div class="container w-50">
    <form method="post" action="Notes website.php">
      <div class="mb-3 mt-5">
        <label for="exampleInputEmail1" class="form-label">Title</label>
        <input type="text" class="form-control" name="Title" require>
      </div>
      <div class="mb-3">
        <label for="floatingTextarea" class="form-label">Description</label>
        <div class="form-floating">
          <textarea class="form-control" placeholder="Leave a comment here" name="Description"
            style="height: 100px"></textarea>
          <label for="floatingTextarea">Description</label>
        </div>
      </div>

      <button type="submit" class="btn btn-primary">Add Note</button>
    </form>
  </div>
  <div class="container w-75 mt-5">
    <table class="table" id="myTable">
      <thead>
        <tr>
          <th scope="col" class="text-center">Sr. No.</th>
          <th scope="col" class="text-center">Title</th>
          <th scope="col" class="text-center">Description</th>
          <th scope="col" class="text-center">Action</th>
        </tr>
      </thead>
      <tbody>
        <?php
  $sql = "SELECT * FROM `notes`";
  $result = mysqli_query($conn,$sql);
  $sr=1;
  while($row=mysqli_fetch_assoc($result)){

   echo "
    <tr>
      <th scope='row' class='text-center'>".$sr."</th>
      <td>".$row['Title']."</td>
      <td>".$row['Description']."</td>
      <td class='text-center'><button type='button' class='btn btn-primary m-0 edit' data-bs-toggle='modal' data-bs-target='#editModal' id=".$row['Sr.No'].">Edit</button>
      <button type='submit' class='btn btn-primary delete ' id=d".$row['Sr.No']." id='delete'>Delete</button></td>
    </tr>
  ";
  $sr++;
  }
?>
      </tbody>
    </table>
  </div>
  <!-- Modal -->
  <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="editModalLabel">Edit Note</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">

          <form method="post" action="Notes website.php">
            <input type="hidden" name="SrNoEdit" id="SrNoEdit">

            <div class="mb-3">
              <label for="exampleInputEmail1" class="form-label">Title</label>
              <input type="text" class="form-control" id="TitleEdit" name="TitleEdit">
            </div>
            <div class="mb-3">
              <label for="floatingTextarea" class="form-label">Description</label>
              <div class="form-floating">
                <textarea class="form-control" placeholder="Leave a comment here" id="DescriptionEdit"
                  name="DescriptionEdit" style="height: 100px"></textarea>
                <label for="floatingTextarea">Description</label>
              </div>
            </div>

            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
              <button type="submit" class="btn btn-primary">Save changes</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL"
    crossorigin="anonymous"></script>
  <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4="
    crossorigin="anonymous"></script>
  <script src="//cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
  <script>

    let table = new DataTable('#myTable');
    let edits = document.getElementsByClassName('edit');
    let deletes = document.getElementsByClassName('delete');
    Array.from(edits).forEach((element) => {
      element.addEventListener('click', (e) => {
        let tr = e.target.parentNode.parentNode;
        let Title = tr.getElementsByTagName("td")[0].innerText;
        let Description = tr.getElementsByTagName("td")[1].innerText;
        TitleEdit.value = Title;
        DescriptionEdit.value = Description;
        SrNoEdit.value = e.target.id;
        console.log(e.target.id);
      })
    })
    Array.from(deletes).forEach((element) => {
      element.addEventListener('click', (e) => {
        let SrNo = e.target.id.substr(1,);
        if (confirm("Are you sure you want to delte this note!")) {
          window.location = `/PHP/Notes website.php?delete=${SrNo}`;
        }
      })
    })

  </script>
</body>

</html>