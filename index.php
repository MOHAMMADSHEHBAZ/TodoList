<?php 
$insert = false;
$servername="localhost";
$username="root";
$password="";
$database="todolist";
$conn = mysqli_connect($servername,$username,$password,$database);
if(!$conn){
  die("Unable to connect:" . mysql_connect_error());
}
session_start();
if(isset($_GET['delete'])){
  $sno = $_GET['delete'];
  $sql = "DELETE from `notes` where `sno` = $sno";
  $result = mysqli_query($conn,$sql);
  $task = $_GET['delete'];
  if ($task) {
    $_SESSION['error'] = "Task Deleted successfully!";}
}
if($_SERVER['REQUEST_METHOD']== 'POST'){
  if(isset($_POST['snoEdit'])){
    $sno = $_POST["snoEdit"];
    $title = $_POST["titleEdit"];
    $description = $_POST["descriptionEdit"];
    $sql = "UPDATE `notes` SET `title` = '$title' , `description` = '$description'  WHERE `notes`.`sno` = $sno";
    $result = mysqli_query($conn,$sql);
    $task = $_POST['titleEdit'];
    $task = $_POST['descriptionEdit'];
    if ($task) {
      $_SESSION['warning'] = "Task Updated successfully!";}
    header("Location: " . $_SERVER['REQUEST_URI']);
    exit();
  }
  else{
  $title = $_POST["title"];
  $description = $_POST["description"];
  $sql = "insert into `notes` (`title`,`description`) values ('$title','$description')";
  $result = mysqli_query($conn,$sql);
  $task = $_POST['title'];
  $task = $_POST['description'];
  if ($task) {
    $_SESSION['success'] = "Task added successfully!";
}
  if($result){
    $insert = true;
  }
  else{
    echo mysql_connect_error();
  }
  header("Location: " . $_SERVER['REQUEST_URI']);
  exit();
  }}
?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.datatables.net/2.1.3/css/dataTables.bootstrap5.css"> 
    <link rel="stylesheet" href="https://cdn.datatables.net/2.1.3/css/dataTables.dataTables.css"> 
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css"> 
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    
    <style>
      <?php
      include './style.css';
      ?>
    </style>
    <title>Todo List</title>
    <style>
    </style>
  </head>
  <body>
    <!--Edit Modal -->
<div class="modal fade" id="edit" tabindex="-1" aria-labelledby="edit" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="edit">Edit task</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
      <form action="" method="POST">
        <input type="hidden" name="snoEdit" id="snoEdit">
  <div class="mb-3">
    <label for="title" class="form-label">Title</label>
    <input type="text" class="form-control" placeholder="Enter Title" id="titleEdit" name="titleEdit">
  </div>
  <div class="form-group">
    <label for="Discription">Description</label>
    <textarea type="password" class="form-control" placeholder="Enter Discription" id="descriptionEdit" name="descriptionEdit"></textarea>
  </div>
  <button type="submit" class="btn btn-primary  my-2">Edit Task</button>
  <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
  <!-- <button type="button" class="btn btn-danger  my-2">Delete all</button> -->
</form>
      </div>
    </div>
  </div>
</div>
  <nav class="nav navbar nav navbar-expand-lg navbar-light bg-light">
  <div class="container-fluid d-flex justify-content-center">
    <a class="navbar-brand fst-italic" href="#">Todo List</a>
  </div>
  </nav>
  <div class="container my-4">
  <h2>Add Task</h2> 
  <form action="/TodoList/index.php" method="POST">
  <div class="mb-3">
    <label for="title" class="form-label">Title</label>
    <input type="text" class="form-control" placeholder="Enter Title" id="title" name="title" required>
  </div>
  <div class="form-group">
    <label for="Discription">Description</label>
    <textarea type="password" class="form-control" placeholder="Enter Discription" id="description" name="description" required></textarea>
  </div>
  <button type="submit" class="btn btn-primary  my-2">Add Task</button>
  <!-- <button type="button" class="btn btn-danger  my-2">Delete all</button> -->
</form>
  </div>
  <div class="container">
    <div class="count bg-success">
      <div><p>Total Task: </p></div>
      <p>
  <?php
  $sql = "SELECT COUNT(*) AS count FROM notes";
  $res = mysqli_query($conn, $sql);
  if ($row = mysqli_fetch_assoc($res)) {
    echo $row['count'];
  }
  ?>
      </p>
    </div>
  </div>
  <div class="container">
    <table class="table" id="data" style="width:100%">
    <thead>
    <tr>
      <th scope="col" class="sno">S.no</th>
      <th scope="col" class="title">Title</th>
      <th scope="col" class="decription">Decription</th>
      <th scope="col" class="action">Action</th>
    </tr>
  </thead>
  <tbody>  
  <?php
    $sql = "select * from `notes`";
    $res = mysqli_query($conn,$sql);
    $sno=0;
    while($row = mysqli_fetch_assoc($res)){
      $sno +=1;
      echo "<tr>
      <th scope='row'>". $sno."</th>
      <td>". $row['title']."</td>
      <td>". $row['description']."</td>
      <td>
      <button type='button' class='edit btn btn-primary' id=".$row['sno']." data-bs-toggle='modal' data-bs-target='#edit'>
      <svg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='currentColor' class='bi bi-pencil-square' viewBox='0 0 16 16'>
    <path d='M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z'/>
    <path fill-rule='evenodd' d='M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z'/>
  </svg>
      Edit
      </button>
      <button type='button' class='delete btn btn-danger' id=d".$row['sno']." data-bs-toggle='modal' data-bs-target='#delete'>
      <svg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='currentColor' class='bi bi-trash3-fill' viewBox='0 0 16 16'>
    <path d='M11 1.5v1h3.5a.5.5 0 0 1 0 1h-.538l-.853 10.66A2 2 0 0 1 11.115 16h-6.23a2 2 0 0 1-1.994-1.84L2.038 3.5H1.5a.5.5 0 0 1 0-1H5v-1A1.5 1.5 0 0 1 6.5 0h3A1.5 1.5 0 0 1 11 1.5m-5 0v1h4v-1a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 0-.5.5M4.5 5.029l.5 8.5a.5.5 0 1 0 .998-.06l-.5-8.5a.5.5 0 1 0-.998.06m6.53-.528a.5.5 0 0 0-.528.47l-.5 8.5a.5.5 0 0 0 .998.058l.5-8.5a.5.5 0 0 0-.47-.528M8 4.5a.5.5 0 0 0-.5.5v8.5a.5.5 0 0 0 1 0V5a.5.5 0 0 0-.5-.5'/>
  </svg>
      Delete
      </button>
      </tr>";
    }
      ?>
    </tbody>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
  <script
  src="https://code.jquery.com/jquery-3.7.1.js"
  integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4="
  crossorigin="anonymous"></script>
  <script src="//cdn.datatables.net/2.1.3/js/dataTables.min.js"></script> 
  <script>
    let table = new DataTable('#data');
  </script>
      <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script>
        <?php
        if (isset($_SESSION['success'])) {
            echo 'toastr.success("' . $_SESSION['success'] . '");';
            unset($_SESSION['success']);
        }
        if (isset($_SESSION['error'])) {
            echo 'toastr.error("' . $_SESSION['error'] . '");';
            unset($_SESSION['error']);
        }
        if (isset($_SESSION['warning'])) {
            echo 'toastr.warning("' . $_SESSION['warning'] . '");';
            unset($_SESSION['warning']);
        }
        ?>
    </script>    
    <script>
      let edits = document.getElementsByClassName('edit');
      Array.from(edits).forEach((e)=>{
        e.addEventListener('click',(elm)=>{
          // console.log(elm.target.parentNode.parentNode)
          tr = elm.target.parentNode.parentNode;
          title=tr.getElementsByTagName("td")[0].innerText;
          description=tr.getElementsByTagName("td")[1].innerText;
          // console.log(title,description)
          titleEdit.value = title;
          descriptionEdit.value = description;
          snoEdit.value = elm.target.id;
          // console.log(snoEdit.value);
        })
      })
      let deletes = document.getElementsByClassName('delete');
      Array.from(deletes).forEach((e)=>{
        e.addEventListener('click',(elm)=>{
          sno = elm.target.id.substr(1);
          if(confirm("Do you want to delete the task?")){
            console.log("yes");
            window.location = "/TodoList/index.php?delete="+ sno;
          }
          else{
            console.log("no");
          }
        })
      })
    </script>
  </body>
</html>