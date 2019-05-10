<!DOCTYPE html>
<html>
<head>
    <title>Upload  Sample</title>
   
   <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.0.3/css/bootstrap.min.css">


   <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.0.3/css/bootstrap-theme.min.css">

<!-- Latest compiled and minified JavaScript -->
<script src="//netdna.bootstrapcdn.com/bootstrap/3.0.3/js/bootstrap.min.js"></script>
    
    <script src="https://code.jquery.com/jquery-1.11.1.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js"></script>
   
</head>
<body>

<div class="container">
<h1>Upoad File To Cloud</h1>
    
            <form  name="form_upload" method="post" action="analisis.php" enctype="multipart/form-data">
            
            <div class="form-group">
                <label for="exampleInputFile">Input File </label>
                <input type="file" name="image" accept="image/*">
                <p class="help-block">File Image.</p>
            </div>
           
            <input type="submit" name="submit" class="btn btn-primary" value="submit">
            </form><br/><br/>
       
<!--<h4>Latihan Upload Gambar To Cloud Azure</h4>
   <form name="form_upload" method="post" action="" enctype="multipart/form-data">
   Input Gambar Disini
   <input type="file" accept="image/*" name="image"/>
   
   <input type="submit" name="submit" value="upload">
   
</form>-->


</div>
</body>
</html>
