<?php


	spl_autoload_register(function ($class_name) {
		$class_name = str_replace("\\","/",$class_name);
		echo $class_name . ".php";
		echo "<br>";
	  require_once $class_name . ".php";
	});


	$cat = new Cat();
	$fridge = new Fridge();

?>

<html>
<head>
	<title>Cat and Fridge Age</title>

</head>

<body>

	<main>
		<h1>Cat and Fridge Age</h2>
		<?php
			$cat->set_age(22);

			echo "Cat's age: " . $cat->get_age();

			echo "<br>";

		  $fridge->set_age(2);

			echo "Fridge's age: " . $fridge->get_age();
		?>
	</main>
</body>

</html>
