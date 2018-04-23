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
		//$hashed_pwd = password_hash($pw, PASSWORD_DEFAULT);

		$stmt = $this->DB->prepare("select hash from users where username='" . $un . "'");
		$stmt->execute();
		$hash = $stmt->fetchAll ( PDO::FETCH_ASSOC );
		if($hash != NULL)
		{

			return password_verify($pw, $hash[0]['hash']);
		}
		else 
		{
			return 0;
		}
	}
	
	
	public function register($un, $pw)
	{
		$hashed_pwd = password_hash($pw, PASSWORD_DEFAULT);
		$stmt = $this->DB->prepare("select * from users where username='" . $un . "'");
		$stmt->execute();
		$count = $stmt->fetchAll ( PDO::FETCH_ASSOC );
		if($count != NULL)
		{
			return false;
		}
		else 
		{
			$stmt = $this->DB->prepare("insert into users (username, hash)" .
					"values ('" . $un . "' , '" . $hashed_pwd . "' )");
			$stmt->execute();
			return true;
		}
		
	}
	
	
} // End class DatabaseAdaptor
$theDBA = new DatabaseAdaptor ();

// Testing code that should not be run when a part of MVC
//$theDBA = new DatabaseAdaptor ();
//$theDBA -> changeRating(1, 1);
//$theDBA -> unflagAll();
//print_r($arr);

// $arr = $theDBA->getMoviesByRank (6);

/*
$arr = $theDBA -> getIngredients();


print_r($arr);
foreach ($arr as $val)
{
	echo $val['id'] . " " . $val['name'] . PHP_EOL;
}
*/
?>