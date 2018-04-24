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

else if($call == 'addToShoppingList')
{
	echo "IN CONT";
	echo $_GET['addArr'];
	//$theDBA -> addToShoppingList($_GET['addArr']);
}

//get recipes
else if($call == 'recipes')
{
    echo 'bye';
}

//login
else if(isset($_POST['ID'])  && isset($_POST['password']))
{
    $userId = $_POST['ID'];
    $password = $_POST['password'];
    
    unset($_POST['ID']);
    unset($_POST['password']);
    
    $valid = $theDBA->verifyCredentials($userId, $password);
    if($valid === true)
    {
        $_SESSION['user'] = $userId;
        header('Location: home.php');
    }
    
    else 
    {
        header('Location: home.php');
    }
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
    unset($_POST['firstName']);
    
    $valid = $theDBA->register($userId, $password, $first, $last);
    if($valid === true)
    {
        header('Location: home.php');
    }
    
}

//add ingredient
else if(isset($_POST['ingName'])  && isset($_POST['ingCost']) && isset($_POST['ingUnit']) && isset($_POST['ingDescription']))
{
    $name = $_POST['ingName'];
    $cost = $_POST['ingCost'];
    $unit = $_POST['ingUnit'];
    $description = $_POST['ingDescription'];
    
    unset($_POST['ingName']);
    unset($_POST['ingCost']);
    unset($_POST['ingUnit']);
    unset($_POST['ingDescription']);
    
    $valid = $theDBA->addIng($name, $cost, $unit, $description);
    if($valid === true)
    {
        $_SESSION['goTo'] = 'ing';
        header('Location: home.php');
    }
}

//add recipe
else if(isset($_POST['recName'])  && isset($_POST['recIng']) &&  isset($_POST['recDescription']))
{

}

else 
{
    unset( $_SESSION['user']);
    header('Location: home.php');
}