<!DOCTYPE html>
<html>
<head>
    <title>Analisis Sample</title>
   
   <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.0.3/css/bootstrap.min.css">


   <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.0.3/css/bootstrap-theme.min.css">

<!-- Latest compiled and minified JavaScript -->
    <script src="//netdna.bootstrapcdn.com/bootstrap/3.0.3/js/bootstrap.min.js"></script>
    
    <script src="https://code.jquery.com/jquery-1.11.1.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js"></script>
   
</head>
<body> 
<script type="text/javascript">
    function processImage() {
       
        var subscriptionKey = "f1ca3a4f0722417fa8ef4eaef64166a8";
 
       
        var uriBase =
            "https://southeastasia.api.cognitive.microsoft.com/vision/v2.0/analyze";
 
        
        var params = {
            "visualFeatures": "Categories,Description,Color",
            "details": "",
            "language": "en",
        };
 
        // Display the image.
        var sourceImageUrl = document.getElementById("inputImage").value;
        document.querySelector("#sourceImage").src = sourceImageUrl;
 
        // Make the REST API call.
        $.ajax({
            url: uriBase + "?" + $.param(params),
 
            // Request headers.
            beforeSend: function(xhrObj){
                xhrObj.setRequestHeader("Content-Type","application/json");
                xhrObj.setRequestHeader(
                    "Ocp-Apim-Subscription-Key", subscriptionKey);
            },
 
            type: "POST",
 
            // Request body.
            data: '{"url": ' + '"' + sourceImageUrl + '"}',
        })
 
        .done(function(data) {
            // Show formatted JSON on webpage.
            $("#responseTextArea").val(JSON.stringify(data, null, 2));
             // Extract and display the caption and confidence from the first caption in the description object.
                if (data.description && data.description.captions) {
                    var caption = data.description.captions[0];
                    
                    if (caption.text && caption.confidence) {
                        $("#captionSpan").text(caption.text);
                    }
                }
        })
 
        .fail(function(jqXHR, textStatus, errorThrown) {
            // Display error message.
            var errorString = (errorThrown === "") ? "Error. " :
                errorThrown + " (" + jqXHR.status + "): ";
            errorString += (jqXHR.responseText === "") ? "" :
                jQuery.parseJSON(jqXHR.responseText).message;
            alert(errorString);
        });
    };
</script>
<div class="container">


<?php
require_once 'vendor/autoload.php';
require_once "./random_string.php";
use MicrosoftAzure\Storage\Blob\BlobRestProxy;
use MicrosoftAzure\Storage\Common\Exceptions\ServiceException;
use MicrosoftAzure\Storage\Blob\Models\ListBlobsOptions;
use MicrosoftAzure\Storage\Blob\Models\CreateContainerOptions;
use MicrosoftAzure\Storage\Blob\Models\PublicAccessType;
   if(isset($_POST['submit'])){
    
    $nama=$_FILES['image']['name'];
    $direktori=$_FILES['image']['tmp_name'];
    $size=$_FILES['image']['size'];
    $type=$_FILES['image']['type'];
        if(!empty($direktori)){
                 if($type=="image/jpeg" || $type=="image/jpg" || $type=="image/gif" || $type=="image/x-png"){
                            if($size <= 2024000){
                       // $connectionString = "DefaultEndpointsProtocol=https;AccountName=".getenv('ACCOUNT_NAME').";AccountKey=".getenv('ACCOUNT_KEY');
                           $connectionString = "DefaultEndpointsProtocol=https;AccountName=syafrinfiles;AccountKey=uBTS2Mxt7TaswiE3O5Jvrbd7HC3jf6424RbDcOiPbr9x05vkedsNsFNSr2Y8QInQ4jnHwUc286tHceevZtE59g==;EndpointSuffix=core.windows.net";

                                $blobClient = BlobRestProxy::createBlobService($connectionString);
                                $createContainerOptions = new CreateContainerOptions();
                                $createContainerOptions->setPublicAccess(PublicAccessType::CONTAINER_AND_BLOBS);

                                $createContainerOptions->addMetaData("key1", "value1");
                                $createContainerOptions->addMetaData("key2", "value2");
                                $containerName = "syafrinblobs".generateRandomString();
                                try {
                                    // Create container.
                                    $blobClient->createContainer($containerName, $createContainerOptions);



                                    $content = fopen($direktori, "r");

                                    //Upload blob
                                  
                                   $blobClient->createBlockBlob($containerName, $nama, $content);
                                    // List blobs.
                                    $listBlobsOptions = new ListBlobsOptions();
                                  
                                    do{
                                        $result = $blobClient->listBlobs($containerName);
                                        foreach ($result->getBlobs() as $blob)
                                        {  
                                                    echo"<div class='container'>";
                                                    echo "berhasil upload: ".PHP_EOL;
                                                    echo $nama;
                                                    echo "<br /><br/>";
                                        ?>
                                                    <img src="<?php  echo $blob->getUrl(); ?>" class="img-responsive" alt="Responsive image" style="width:300px; height:200px;">
                                                     <br/>                               
                                                    <input type="text" class="col-xs-6" name="inputImage" id="inputImage"value="<?php  echo $blob->getUrl(); ?>" />
                                                    <br/><br/><button class="btn btn-danger" onclick="processImage()">Analyze image</button>&nbsp; &nbsp;<a href="index.php" class="btn btn-success">back to form upload</a>
                                                    <br><br>

                                                    <div id="wrapper" style="width:1020px; display:table;">
                                                    <div id="jsonOutput" style="width:600px; display:table-cell;">
                                                        Hasil Output:
                                                        <br><br>
                                                        <textarea id="responseTextArea" class="UIInput"
                                                                style="width:580px; height:400px;"></textarea>
                                                    </div>
                                                    <div id="imageDiv" style="width:400px; height:400px; display:table-cell;">
                                                        Source image:
                                                        <br><br>
                                                        <img id="sourceImage" style="width:400px; height:300px;"/><br/>
                                                        <span id="captionSpan"></span><br>
                                                    </div>
                                                    </div>
                                            </div>       


                                          <?php
                                        }

                                        $listBlobsOptions->setContinuationToken($result->getContinuationToken());
                                    } while($result->getContinuationToken());
                                    echo "<br />";

                                }
                                catch(ServiceException $e){
                                                   $code = $e->getCode();
                                    $error_message = $e->getMessage();
                                    echo $code.": ".$error_message."<br />";
                                }
                                catch(InvalidArgumentTypeException $e){

                                    $code = $e->getCode();
                                    $error_message = $e->getMessage();
                                    echo $code.": ".$error_message."<br />";
                                }
                            }else{
                                echo"<div class='alert alert-primary'>
                                oooopps kayaknya kamu file gambar lebih besar dari 2mb  <a href='index.php' class='alert-link'>kembali</a>
                        </div>";
                        }

                }else{
                        echo"<div class='alert alert-primary'>
                        oooopps kayaknya kamu tidak memilih sebuah file gambar bertipe jpg . jpeg .gif . png <a href='index.php' class='alert-link'>kembali</a>
                </div>";
                }
       }else{
           echo"<div class='alert alert-primary' role='alert'>
                   oooopps kayaknya kamu tidak memilih sebuah file gambar <a href='index.php' class='alert-link'>kembali</a>
           </div>";
       }
}else{
             echo"<div class='alert alert-primary' role='alert'>
                   oooopps untuk melakukan analisis gambar silahkan upload file gambarnya <a href='index.php' class='alert-link'>disini</a>
           </div>";
   }
?>
</div>
</body>
</html>
