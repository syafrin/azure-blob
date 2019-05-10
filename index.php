<!DOCTYPE html>
<html>
<head>
    <title>Analyze Sample</title>
   
   <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.0.3/css/bootstrap.min.css">


   <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.0.3/css/bootstrap-theme.min.css">

<!-- Latest compiled and minified JavaScript -->
<script src="//netdna.bootstrapcdn.com/bootstrap/3.0.3/js/bootstrap.min.js"></script>
    
    <script src="http://code.jquery.com/jquery-1.11.1.min.js"></script>
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js"></script>
   
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
<h1>Upoad File To Cloud</h1>
    
            <form  name="form_upload" method="post" action="" enctype="multipart/form-data">
            
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
    $connectionString = "DefaultEndpointsProtocol=https;AccountName=".getenv('ACCOUNT_NAME').";AccountKey=".getenv('ACCOUNT_KEY');
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
               // $blobClient->createBlockBlob($containerName, $fileToUpload, $content);
               $blobClient->createBlockBlob($containerName, $nama, $content);

                // List blobs.
                $listBlobsOptions = new ListBlobsOptions();
               // $listBlobsOptions->setPrefix("File_upload");

              

               /* do{
                    $result = $blobClient->listBlobs($containerName);
                    foreach ($result->getBlobs() as $blob)
                    {
                        echo $blob->getName().": ".$blob->getUrl()."<br />";
                    }
                
                    $listBlobsOptions->setContinuationToken($result->getContinuationToken());
                } while($result->getContinuationToken());
                echo "<br />";*/

                do{
                    $result = $blobClient->listBlobs($containerName);
                    foreach ($result->getBlobs() as $blob)
                    {  
                                echo"<div class='container'>";
                                echo "Uploading syafrinblob: ".PHP_EOL;
                                echo $nama;
                                echo "<br /><br/>";
                    ?>
                                <img src="<?php  echo $blob->getUrl(); ?>" class="img-responsive" alt="Responsive image" width="300" height="300">
                                 <br/>                               
                                <input type="text" class="col-xs-6" name="inputImage" id="inputImage"value="<?php  echo $blob->getUrl(); ?>" />
                                <br/><br/><button class="btn btn-danger" onclick="processImage()">Analyze image</button>
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
                                    <img id="sourceImage" width="400" /><br/>
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

}


?>
</div>
</body>
</html>
