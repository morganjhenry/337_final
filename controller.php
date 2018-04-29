<?php
include "model.php";
session_start ();

$call = "";
if(isset($_GET['call']))
{
    $call = $_GET['call'];
    $call = htmlspecialchars($call);
}

//get ingredients
if($call == 'ingredients')
{
	$arr = $theDBA -> getIngredients();
	echo json_encode($arr);

}

else if($call == 'checkout')
{
    $theDBA->checkout($_SESSION['user']);
}

else if($call == 'getShoppingList')
{
	$arr = $theDBA -> getShoppingList($_SESSION['user']);
	echo json_encode($arr);

}

else if($call == 'addToShoppingList')
{
    $add_ig = $_GET['add_ig']; 
    $add_qty = $_GET['add_qty']; 
    
	$igID = json_decode($add_ig);
	$qty = json_decode($add_qty);
	$str = "";
	
	for($i = 0; $i < count($igID); $i++)
	{
		$str .= $theDBA -> addToShoppingList($igID[$i], $qty[$i], $_SESSION['user']) + "   ";
	}
}

//get recipes
else if($call == 'recipes')
{
    $arr = $theDBA->getAllRecipes();
    echo json_encode($arr);
    
}

//login
else if(isset($_POST['ID'])  && isset($_POST['password']))
{
    $userId = htmlspecialchars($_POST['ID']);
    $password = htmlspecialchars($_POST['password']);
    
    unset($_POST['ID']);
    unset($_POST['password']);
    
    $id = $theDBA->verifyCredentials($userId, $password);
    
    if($id != -1)
    {
        $_SESSION['user'] = $id;
        header('Location: home.php');
    }
    
    else 
    {
        $_SESSION['goTo'] = 'login';
        $_SESSION['error'] = "loginError";
        header('Location: home.php');
    }
}

//register
else if(isset($_POST['regID'])  && isset($_POST['regPassword']) && isset($_POST['firstName']) && isset($_POST['lastName']))
{
    $userId = htmlspecialchars($_POST['regID']);
    $password = htmlspecialchars($_POST['regPassword']);
    $first = htmlspecialchars($_POST['firstName']);
    $last = htmlspecialchars($_POST['lastName']);
    
    unset($_POST['regID']);
    unset($_POST['regPassword']);
    unset($_POST['firstName']);
    unset($_POST['lastName']);
    
    $hashed_pwd = password_hash($password, PASSWORD_DEFAULT);
    
    $valid = $theDBA->register($userId, $hashed_pwd, $first, $last);
    if($valid === true)
    {
        header('Location: home.php');
    }
    
    else 
    {
        $_SESSION['goTo'] = 'register';
        $_SESSION['error'] = "registerError";
        header('Location: home.php');
    }
    
}

//add ingredient
else if(isset($_POST['ingName'])  && isset($_POST['ingCost']) && isset($_POST['ingUnit']) && isset($_POST['ingDescription']))
{
    $name = htmlspecialchars($_POST['ingName']);
    $cost = htmlspecialchars($_POST['ingCost']);
    $unit = htmlspecialchars($_POST['ingUnit']);
    $description = htmlspecialchars($_POST['ingDescription']);
    
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