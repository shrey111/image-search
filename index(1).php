<?php
  
  require_once "login.php";
  session_start();
  //if(!isset($_SESSION['email'])){
  //  header('location: signin.php');
  //}
  $conn = new mysqli($hn, $un, $pw, $db);
  if ($conn->connect_error) die($conn->connect_error);
  
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>

	<title>Api Test</title>
	<style type="text/css">
		img {
			height: 200px;
		}
	</style>
</head>
<body>

<!-- Div for the cointainer and form searchbar-->
	<div class="container">
		<div class="jumbotron">
			<div class="row">
			<form action="index.php" method="get" class="col-6 offset-md-3">
				<div class="align-items-center">
					<input type="text" class="form-control mb-2" name="imgSearch" id="imgSearch" placeholder="Search Flickr...">
					<button type="submit" class="btn btn-primary mb-2">Submit</button>
				</div>
			</form>
			</div>
		</div>
	</div>

<!-- Table -->
<div class="container">
	<div class="table-responsive">
	<table class="table">
		<thead class="thead-dark">
			<tr>
				<th scope="col">Image</th>
				<th scope="col">Url</th>
				<th scope="col">Date</th>
			</tr>
		</thead>
		<tbody>
<?php
// api url


	$text = str_replace(' ', '+', $_GET['imgSearch']);
	$url = 'https://api.flickr.com/services/rest/?method=flickr.photos.search&api_key=aa3b15a3f5b48a8589cad7bb3cf090d9&text='.$text.'&sort=relevance&per_page=100&format=json&nojsoncallback=1';

	$data = json_decode(file_get_contents($url));
	$photos = $data->photos->photo;



	foreach ($photos as $photo) {

		$picurl = 'https://farm'.$photo->farm.'.staticflickr.com/'.$photo->server.'/'.$photo->id.'_'.$photo->secret.'_z.jpg';
		$dateUrl = 'https://api.flickr.com/services/rest/?method=flickr.photos.getInfo&api_key=aa3b15a3f5b48a8589cad7bb3cf090d9&photo_id='.$photo->id.'&format=json&nojsoncallback=1';



		$datejson = json_decode(file_get_contents($dateUrl));
		$postedDate = $datejson->photo->dates->posted;
		$formatDate = date("Y-m-d", $postedDate);
//$formatDate = 'change';
		
		
		//echo '<h1>TITLE: '.$photo->title.'</h1>';
		//echo '<img src="'.$picurl.'">';
		//echo '</br>';
		//print_r($photo);

		echo '<tr>';
		echo '<td><img src="'.$picurl.'"></td>';
		echo '<td>'.$picurl.'</td>';
		echo '<td>'.$formatDate.'</td>';
		echo '</tr>';
		//copy($picurl, './pic'.$photo->id.'.jpg');

		copy($picurl, './flower.jpg');
		$blob = addslashes(file_get_contents('./flower.jpg'));
		//echo $blob;
		//$mime = "image/jpeg";
		$mime = mime_content_type('./flower.jpg');

		$insert = 'INSERT INTO Images(imageBlob, fileType, uploadDate, imageCategory, userId) VALUES ("'.$blob.'", "'.$mime.'", "'.$formatDate.'", "'.$_GET['imgSearch'].'", 10)';

		if ($conn->query($insert) === TRUE) {
		    echo "New record created successfully";
		} else {
		    echo "Error: " . $insert . "<br>" . $conn->error;
		}

	}

//	echo $picurl;

//	INSERT


/*
	copy($picurl, './flower.jpg');
	$blob = addslashes(file_get_contents('./flower.jpg'));
	//echo $blob;
	//$mime = "image/jpeg";
	$mime = mime_content_type('./flower.jpg');

	$insert = 'INSERT INTO Images(imageBlob, fileType, uploadDate, imageCategory, userId) VALUES ("'.$blob.'", "'.$mime.'", "'.$formatDate.'", "'.$_GET['imgSearch'].'", 10)';

	if ($conn->query($insert) === TRUE) {
	    echo "New record created successfully";
	} else {
	    echo "Error: " . $insert . "<br>" . $conn->error;
	}
*/




?>
		</tbody>
	</table>
	</div>
</div>

</body>
</html>