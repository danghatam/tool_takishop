<!DOCTYPE html>
<html lang="">
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title></title>
		<link rel="stylesheet" href="style.css">
		<link rel="stylesheet" type="text/css" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">
		<link href='http://fonts.googleapis.com/css?family=Roboto:500,400italic,700italic,700,500italic,400&amp;subset=latin,vietnamese' rel='stylesheet' type='text/css'>
		<script type="text/javascript" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>
	</head>
	<body>
		<?php
				$client = new SoapClient('http://tuixachtaki.com/index.php/api/soap/?wsdl');
				// If some stuff requires api authentification,
				// then get a session token
				$session = $client->login('admin', 'tuixachtaki');
				if(isset($_POST['submit'])) {
					$code = $_POST['code'];
					$name = $_POST['name'];
					$price = $_POST['price'];
					$files = $_FILES['images'];
					$categories = $_POST['categories'];
					$shortDescription = $_POST['shortDescription'];
					$description = $_POST['description'];
					$weight = $_POST['weight'];
					$attributeSets = $client->call($session, 'product_attribute_set.list');
					$attributeSet = current($attributeSets);
					$result = $client->call($session, 'catalog_product.create', array('simple', $attributeSet['set_id'], $code, array(
					'categories' => $categories,
					'websites' => array(1),
					'name' => $name,
					'description' => $description,
					'short_description' => $shortDescription,
					'weight' => $weight,
					'status' => '1',
					'visibility' => '4',
					'price' => $price,
					'tax_class_id' => 1,
					'meta_title' => 'tui xach, gio xach, vi, bop',
					'meta_keyword' => 'tui xach, gio xach, vi, bop',
					'meta_description' => 'tui xach, gio xach, vi, bop'
					)));
					for($i=0; $i<count($files['name']); $i++){
                        echo $i;
                        $tmp = $files['tmp_name'][$i];
                        $data = file_get_contents($tmp);
                        $image = base64_encode($data);
                        $newImage = array(
                                'file' => array(
                                        'name' => $name,
                                        'content' => $image,
                                        'mime'    => 'image/png'
                                ),
                                'label'    => $name,
                                'position' => $i + 1,
                                'types'    => array('image', 'small_image', 'thumbnail'),
                                'exclude'  => 0
                        );
                        $client->call($session, 'product_media.create', array($code, $newImage));
                    }
				}
				$categories = $client->call($session, 'catalog_category.tree');
		?>
		
		<div class="wrapper">
			<form id="input_form" method='post' action='index.php' enctype='multipart/form-data'>
				<table>
					<tr>
						<td>Name</td>
						<td>
							<input type='text' name='name' class="form-control" />
						</td>
					</tr>
					<tr>
						<td>Code</td>
						<td>
							<input type='text' name='code' class="form-control" />
						</td>
					</tr>
					<tr>
						<td>Price</td>
						<td><input type='text' name='price' class="form-control" /> </td>
					</tr>
					<tr>
						<td>Weight</td>
						<td><input type='text' name='weight' class="form-control" /> </td>
					</tr>
					<tr>
						<td>Category</td>
						<td>
							<?php foreach($categories['children'][0]['children'] as $category): ?>
							<input type="checkbox" name="categories[]" value="<?php echo $category['category_id'] ?>"><label><?php echo $category['name'] ?></label>
							<?php endforeach ?>
						</td>
					</tr>
					<tr>
						<td>Image</td>
						<td>
							<input type="file" name="images[]"  multiple />
						</td>
					</tr>
					<tr>
						<td>Short Description</td>
						<td><textarea name="shortDescription" class="form-control"></textarea></td>
					</tr>
					<tr>
						<td>Description</td>
						<td><textarea name="description" class="form-control"></textarea></td>
					</tr>
					<tr>
						<td></td>
						<td><input type='submit' name='submit' value='Upload' class="form-control" /></td>
					</tr>
				</table>
			</form>
		</div>
	</body>
</html>