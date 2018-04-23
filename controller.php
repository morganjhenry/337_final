<?php
include "model.php";

$call = "";
if(isset($_GET['call']))
{
    $call = $_GET['call'];
}

//get ingredients
if($call == 'ingredients')
{
	$arr = $theDBA -> getIngredients();
	
	$str = "";
	foreach ($arr as $val)
	{
		$str .= $val['id'] . " " . $val['name'] . "<br>";
	}
	
	echo$str;

}

//get recipes
if($call == 'recipes')
{
    echo 'bye';
}

//login
else if(isset($_POST['ID'])  && isset($_POST['password']))
{
}

//register
else if(isset($_POST['regID'])  && isset($_POST['regPassword']) && isset($_POST['firstName']) && isset($_POST['lastName']))
{
    
}

//add ingredient
else if(isset($_POST['ingName'])  && isset($_POST['ingCost']) && isset($_POST['ingUnit']) && isset($_POST['ingDescription']))
{
    
}

//add recipe
else if(isset($_POST['recName'])  && isset($_POST['recIng']) &&  isset($_POST['recDescription']))
{

}