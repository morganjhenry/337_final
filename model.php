<?php

// Author: Rick Mercer , Morgan Henry
class DatabaseAdaptor {
	// The instance variable used in every one of the functions in class DatbaseAdaptor
	private $DB;
	// Make a connection to the data based named 'imdb_small' (described in project).
	public function __construct() {
		$db = 'mysql:dbname=final_project;host=127.0.0.1;charset=utf8';
		$user = 'root';
		$password = '';
		
		try {
			$this->DB = new PDO ( $db, $user, $password );
			$this->DB->setAttribute ( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
		} catch ( PDOException $e ) {
			echo ('Error establishing Connection');
			exit ();
		}
	}
	
	public function getIngredients() {
		$stmt = $this->DB->prepare( "SELECT * FROM ingredient");
		$stmt->execute ();
		return $stmt->fetchAll ( PDO::FETCH_ASSOC );
	}
	
	public function verifyCredentials ($un, $pw)
	{
		$stmt = $this->DB->prepare("select pw_hash from users where username=:un");
		$stmt->bindParam ( 'un', $un );
		$stmt->execute();
		$hash = $stmt->fetchAll ( PDO::FETCH_ASSOC );
		if($hash != NULL)
		{
			if(password_verify($pw, $hash[0]['pw_hash'])===true)
			{
			    $stmt = $this->DB->prepare("select id from users where username=:un");
			    $stmt->bindParam ( 'un', $un );
			    $stmt->execute();
			    $hash = $stmt->fetchAll ( PDO::FETCH_ASSOC );
			    return $hash[0]['id'];
			    
			}
			
			else
			{
			    return -1;
			}
		}
		else 
		{
			return -1;
		}
	}
	
	
	public function register($un, $hashed_pw, $first, $last)
	{
		$stmt = $this->DB->prepare("select * from users where username=:un");
		$stmt->bindParam('un', $un);
		$stmt->execute();
		$count = $stmt->fetchAll ( PDO::FETCH_ASSOC );
		if($count != NULL)
		{
			return false;
		}
		else 
		{
			$stmt = $this->DB->prepare("insert into users (username, pw_hash, f_name, l_name) values (:un, :hashed_pw, :first, :last )");
			$stmt->bindParam('un', $un);
			$stmt->bindParam('hashed_pw', $hashed_pw);
			$stmt->bindParam('first', $first);
			$stmt->bindParam('last', $last);
			$stmt->execute();
			return true;
		}
		
	}
	
	public function addIng($name, $cost, $unit, $description)
	{
	    $stmt = $this->DB->prepare("insert into ingredient(name, cost, unit, notes)" .
	        "values (:name, :cost, :unit, :description )");
	    $stmt->bindParam('name', $name);
	    $stmt->bindParam('cost', $cost);
	    $stmt->bindParam('unit', $unit);
	    $stmt->bindParam('description', $description);
	    $stmt->execute();
	    return true;
	}
	
	public function getShoppingList($id)
	{	
		$stmt = $this->DB->prepare("select ingredient.name, ingredient.cost, shopping_list.qty, ingredient.unit, ingredient.notes  from ( " .
		    "(shopping_list join ingredient on (shopping_list.ing_id=ingredient.id)) " .
		    " join users on (shopping_list.user_id = users.id) " .
		    ") where users.id=:id");
		$stmt->bindParam('id', $id);
		$stmt->execute();
		return $stmt->fetchAll ( PDO::FETCH_ASSOC );
	}
	
	public function getAllRecipes()
	{
	    $stmt = $this->DB->prepare("select rec_ing.rec_id, recipes.name as recipe, recipes.description, recipes.pic_url, ingredient.name as ingredient, rec_ing.qty from ( (rec_ing join recipes on (rec_ing.rec_id = recipes.id)) join ingredient on (rec_ing.ing_id = ingredient.id) ) order by rec_ing.rec_id");
	    $stmt->execute();
	    return $stmt->fetchAll ( PDO::FETCH_ASSOC );
	}
		
	
	public function addToShoppingList($igID, $qty, $id)
	{
		$stmt = $this->DB->prepare("select * from shopping_list where user_id=:id and ing_id=:igID");
        $stmt->bindParam('id', $id);
        $stmt->bindParam('igID', $igID);
		$stmt->execute();
		$record= $stmt->fetchAll ( PDO::FETCH_ASSOC );

		if($record != null)
		{
		    $stmt = $this->DB->prepare("update shopping_list set qty=qty+:qty where user_id=:id and ing_id=:igID");
		    $stmt->bindParam('id', $id);
		    $stmt->bindParam('igID', $igID);
		    $stmt->bindParam('qty', $qty);
			$stmt -> execute();
			echo $updateQty;
		}
		else
		{
			$addToList = ("insert into shopping_list (user_id, ing_id, qty) "	 .
						  "values ( :id, :igID, :qty)");
			$stmt = $this->DB->prepare($addToList);
			$stmt->bindParam('id', $id);
			$stmt->bindParam('igID', $igID);
			$stmt->bindParam('qty', $qty);
			$stmt->execute();	
			echo $addToList;
		}
	}
	
	public function checkout($id)
	{
	    $stmt = $this->DB->prepare("delete from shopping_list where user_id=:id");
	    $stmt->bindParam('id', $id);
	    $stmt -> execute();
	}

	
} // End class DatabaseAdaptor
$theDBA = new DatabaseAdaptor ();

// Testing code that should not be run when a part of MVC
//$theDBA = new DatabaseAdaptor ();
//$theDBA -> changeRating(1, 1);
//$theDBA -> unflagAll();
//print_r($arr);
//$arr=array( 1=>2);
//$theDBA -> addToShoppingList($arr, 'mjh');
// $arr = $theDBA->getMoviesByRank (6);

//$arr = $theDBA->getShoppingList('test');
//print_r($arr);

/*
$arr = $theDBA -> getIngredients();


print_r($arr);
foreach ($arr as $val)
{
	echo $val['id'] . " " . $val['name'] . PHP_EOL;
}

$theDBA = new DatabaseAdaptor();
$arr = $theDBA->getAllRecipes();
print_r($arr);*/

?>