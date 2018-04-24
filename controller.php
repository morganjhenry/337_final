<?php
include "model.php";
session_start ();

$call = "";
if(isset($_GET['call']))
{
    $call = $_GET['call'];
}

//get ingredients
if($call == 'ingredients')
{
	$arr = $theDBA -> getIngredients();
	
	echo json_encode($arr);

}

if($call == 'addToShoppingList')
{
	echo "IN CONT";
	echo $_GET['addArr'];
	//$theDBA -> addToShoppingList($_GET['addArr']);
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
    $userId = $_POST['regID'];
    $password = $_POST['regPassword'];
    $first = $_POST['firstName'];
    $last = $_POST['lastName'];
    
    unset($_POST['regID']);
    unset($_POST['regPassword']);
    
    $valid = $theDBA->register($userId, $password, $first, $last);
    if($valid === true)
    {
        header('Location: home.php');
    }
}

//add ingredient
else if(isset($_POST['ingName'])  && isset($_POST['ingCost']) && isset($_POST['ingUnit']) && isset($_POST['ingDescription']))
{
    
}

//add recipe
else if(isset($_POST['recName'])  && isset($_POST['recIng']) &&  isset($_POST['recDescription']))
{

}