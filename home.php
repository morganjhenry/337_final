<!DOCTYPE html>
<html>
<head>
<title>Recipes</title>
<link rel="stylesheet" href="recipe.css">
</head>
<body onload="featured()">

<?php
session_start ();
?>

<h1 class="header">EZ Menus</h1>
<div class="buttons">
<button onclick="featured()">Home</button>
<button onclick="ingredients()">Ingredients</button>
<button onclick="recipes()">Recipes</button>
<!--  <button>Weekly Plan</button>-->
<button onclick="shoppingList()">Shopping List</button>
<button class="right" onclick="login()">Login</button>
<button class="right" onclick="register()">Register</button>
</div>

<div id="display"></div>
<div class="footer"></div>
<script>
ingIDs = [];
function featured()
{
	var display = document.getElementById("display");
	display.innerHTML = '<div class="featured"></div><div class="recipe">Recipe of the Week</div>';
}
function login()
{
	var display = document.getElementById("display");
	display.innerHTML ='<div class="login"></div><div class="recipe"><br>Login<form action="controller.php" method="POST">User ID: <input type="text" maxlength="13" size="13" name="ID" class="user" required> <br>Password: <input  maxlength="13" size="13" type="password" name="password" required> <br><input type="submit" class="submit" name="Login" value="Login" class="submit"> <br> <br></form></div>';
}
function register()
{
	var display = document.getElementById("display");
	display.innerHTML ='<div class="login"></div><div class="recipe">Register<form action="controller.php" method="POST">First Name: <input type="text" maxlength="13" size="13" name="firstName" class="user" required> <br>Last Name: <input type="text" maxlength="13" size="13" name="lastName" class="user" required> <br>User ID: <input type="text" maxlength="13" size="13" name="regID" class="user" required> <br>Password: <input  maxlength="13" size="13" type="password" name="regPassword" required> <br><input type="submit" class="submit" name="Register" value="Register" class="submit"> <br> <br></form></div>';
}
function ingredients()
{
	var display = document.getElementById("display");
	var str = "";
	str += '<div class="ingredients"></div><div class="recipe">Ingredients<button class = "addIngredient" onclick="newIngredient()">+</button><br>';
	str += '<div id="IngTable"></div>';
	str += '<br><button onclick="addIngredients()">Add Ingredients to List</button></div>';
	display.innerHTML = str;
	getIngredients();
}
function getIngredients()
{
	var str= '<table><tr><th>Name</th><th>Cost</th><th>Unit</th><th>Description</th><th>Add</th></tr>';
	var qdiv = document.getElementById("IngTable");
	var ajax = new XMLHttpRequest();
	ajax.open("GET","controller.php?call=ingredients", true); 
	ajax.send();
	ingIDs = [];
	ajax.onreadystatechange = function() 
	{
		if (ajax.readyState == 4 && ajax.status == 200) 
		{
			var arr = JSON.parse(ajax.responseText);
			for(i = 0; i < arr.length; i++)
			{
				ingIDs.push(arr[i]['id']);
				var unit = "";
				var notes = "";
				if(arr[i]['unit'] == null)
					unit = "N/A";
				else
					unit = arr[i]['unit'];

				if(arr[i]['notes'] == null)
					notes = "N/A";
				else
					notes = arr[i]['notes'];
					
				str+= '<tr><td>' + arr[i]['name'] + '</td><td>$' + arr[i]['cost'] +'</td><td>'+ unit +'</td><td>'+notes+
					  '</td><td><input type="number" min=0 max=10 id="addIng_' + arr[i]['id'] +'" class="ing"></td></tr>'
			}
			str += "</table>";
		 	qdiv.innerHTML = str;
		}
	} 
}
function addIngredients()
{
	var addArray = [];
	for(i = 0; i < ingIDs.length; i++)
	{
		var cur = document.getElementById("addIng_" + ingIDs[i]);
		if(cur.value > 0)
			addArray[ingIDs[i]] = cur.value;
	}


	var ajax = new XMLHttpRequest();
	ajax.open("GET","controller.php?call=addToShoppingList&addArr=" + addArray, true); 
	ajax.send();
	ingIDs = [];
	ajax.onreadystatechange = function() 
	{
		if (ajax.readyState == 4 && ajax.status == 200) 
		{
			console.log("completed adding to shopping list");
		}
	} 

	
}
function newIngredient()
{
	var display = document.getElementById("display");
	var str = "";
	str += '<div class="ingredients"></div><div class="recipe">New Ingredient';
	str +='<form action="controller.php" id="newIng" method="POST"><div class="newIng"> Name: <input type="text" maxlength="13" size="13" name="ingName" class="ingBox" required> <br>Cost: <input type="text" maxlength="13" size="13" name="ingCost" class="ingBox" required> <br>Unit: <input type="text" maxlength="13" size="13" name="ingUnit" class="ingBox" required> <br>Description: <textarea  form="newIng" rows="4" col="30" name="ingDescription" class="ingBox"required></textarea> <br><input type="submit" class="submit" name="Add Ingredient" value="Add Ingredient" class="submit"></div></form>';
	str += '<button onclick="ingredients()">Back</button</div>';
	display.innerHTML = str;
}
function recipes()
{
	var display = document.getElementById("display");
	var str = "";
	str += '<div class="recipePic"></div><div class="multipleRecipe">Recipes';
	str += '<button class = "addRecipe" onclick="addRecipe()">Add a Recipe</button>';
	str += getRecipes();
	str += '<button onclick="addRecipeToList()">Add to List</button</div>';
	display.innerHTML = str;
}
function addRecipe()
{
	var display = document.getElementById("display");
	var str = "";
	str += '<div class="ingredients"></div><div class="recipe">New Recipe';
	str +='<form action="controller.php" id="newIng" method="POST"><div class="newIng"> Name: <input type="text" maxlength="13" size="13" name="recName" class="ingBox" required> <br>Ingredient: <input type="text" maxlength="13" size="13" name="recIng" class="ingBox" required> <br>Description: <textarea  form="newIng" rows="4" col="30" name="recDescription" class="ingBox"required></textarea> <br><input type="submit" class="submit" name="Add Recipe" value="Add Recipe" class="submit"></div></form>';
	str += '<button onclick="recipes()">Back</button</div>';
	display.innerHTML = str;
}
function getRecipes()
{
	var str = "";
	str+='<div class="recipeTitle">PB&J Pancakes </div>';
	str+='<img class="recipeImage"src="./images/pancakes.jpeg" alt="Recipe Pic" width="300">';
	str+='<div class="recipeInfo"><ul>';
	str+='<li>Pancakes</li><br><li>Peanut Butter</li><br><li>Jelly</li><br>';
	str+='</ul><br>';
	str+='<div class="recipeDes">This is how to make the pancakes but im not sure bc im not a chef also i hate jelly<br></div></div>';
	
	/*var ajax = new XMLHttpRequest();
	var str = "";
	ajax.open("GET","controller.php?call=recipes", true); 
	ajax.send();
	ajax.onreadystatechange = function() 
	{
		console.log("State: " + ajax.readyState);
		if (ajax.readyState == 4 && ajax.status == 200) 
		{
			var array = JSON.parse(ajax.responseText);
			printQuotes(array);
		}
	}  */
	return str;
}
function shoppingList()
{
	var display = document.getElementById("display");
	display.innerHTML = '<div class="featured"></div><div class="recipe">Shopping List</div>';
}
function addRecipeToList()
{
	alert();
}
</script>

</body>
</html>
