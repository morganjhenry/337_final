<!DOCTYPE html>
<html>
<head>
<title>Recipes</title>
<link rel="stylesheet" href="recipe.css">
</head>


<?php
session_start ();
if(isset($_SESSION['goTo']) && $_SESSION['goTo']==='ing')
{
    echo "<body onload='ingredients()'>";
    unset($_SESSION['goTo']);
}

if(isset($_SESSION['goTo']) && $_SESSION['goTo']==='register')
{
    echo "<body onload='register()'>";
    unset($_SESSION['goTo']);
}

if(isset($_SESSION['goTo']) && $_SESSION['goTo']==='login')
{
    echo "<body onload='login()'>";
    unset($_SESSION['goTo']);
}

else 
{
    echo '<body onload="featured()">';
}
?>

<h1 class="header">Shopping List</h1>
<div class="buttons">
<button onclick="featured()">Home</button>
<button onclick="ingredients()">Ingredients</button>
<button onclick="recipes()">Recipes</button>
<!--  <button>Weekly Plan</button>-->
<?php 
if(isset($_SESSION['user']))
{
    echo '<button onclick="shoppingList()">Shopping List</button>';
    echo '<button class="right" onclick="logout()">Logout</button>';
}

else 
{
   echo '<button class="right" onclick="login()">Login</button>';
}
?>

<button class="right" onclick="register()">Register</button>
</div>

<div id="display"></div>
<div class="footer"></div>
<script>
ingIDs = [];
function featured()
{
	var display = document.getElementById("display");
	var str = '<div class="featured">';
 	str += '<img src="./images/pancakes.jpeg" height="350"></div>';
 	str += '<div class="recipe">Recipe of the Week:<br>PB&J Pancakes<br>';
 	str+='<div class="recipeInfo"><ul>';
	str+='<li>Pancakes</li><br><li>Peanut Butter</li><br><li>Jelly</li><br>';
	str+='</ul>';
	str+='<div class="featuredInfo"><ol>';
	str+='<li>Make your favorite pankcake recipe</li>';
	str+='<li>While the pancakes are still warm, stack them, putting a layer of peanut butter between each layer</li>';
	str+='<li>In a saucepan, warm up your favorite jelly with some maple syrup, until disolved and mixed</li>';
	str+='<li>Pour your jelly syrup on top of the stack of pancakes</li><br>';
	str+='</ol><br></div></div></div></div>';
	display.innerHTML = str;
}

function login()
{
	var display = document.getElementById("display");
	var str = '<div class="login"></div><div class="recipe"><br>';
	str += 'Login<br>';
	<?php 
	if(isset($_SESSION['error']) && $_SESSION['error']=='loginError')
	{
	   echo "str+= '<div class=\'error\'>Username and password not recognized. Please try again</div>';";
	   unset($_SESSION['error']);
	}
	?>
	str+= '<form action="controller.php" method="POST"><div class ="reg">';
	str += 'User ID: <input type="text" maxlength="13" size="13" name="ID" class="regInputs" required>';
	str += '<br>Password: <input  maxlength="13" size="13" type="password" name="password" class="regInputs" required><br>';
	str += '</div><input type="submit" class="submit" name="Login" value="Login" class="submit"> <br> <br></form></div>';
	display.innerHTML = str;
}
function register()
{
	var display = document.getElementById("display");
	var str = '<div class="login"></div><div class="recipe">';
	str += 'Register';
	<?php 
	if(isset($_SESSION['error']) && $_SESSION['error']=='registerError')
	{
		echo "str+= '<div class=\'error\'>Username is already taken. Please try again</div>';";
	   unset($_SESSION['error']);
    }
	?>
	str += '<form action="controller.php" method="POST"><div class ="reg">';
	str += 'First Name: <input type="text" maxlength="13" size="13" name="firstName" class="regInputs" required><br>';
	str += 'Last Name: <input type="text" maxlength="13" size="13" name="lastName" class="regInputs" required> <br>';
	str += 'User ID: <input type="text" maxlength="13" size="13" name="regID" class="regInputs" pattern=".{4,}"title="User ID must be at least 4 characters" required> <br>';
	str += 'Password: <input  maxlength="13" size="13" type="password" name="regPassword" class="regInputs" pattern=".{6,}" title="Password must be at least 6 characters"required><br>';
	str += '</div><input type="submit" class="submit" name="Register" value="Register" class="submit"> <br> <br></form></div>';
	display.innerHTML = str;
}
function ingredients()
{
	var display = document.getElementById("display");
	var str = "";
	str += '<div class="ingredients"></div><div class="recipe">Ingredients';
	<?php 
	if(isset($_SESSION['user']))
	{
	    echo 'str += "<button class = \'addIngredient\' onclick=\'newIngredient()\'>+</button><br>"';
	    
	}
	?>
	
	str += '<div id="IngTable"></div>';
	<?php
	if(isset($_SESSION['user']))
	{
	    echo "str += '<br><button onclick=\"addIngredients()\" class=\"addIng\">Add Ingredients to Shopping List</button></div>'";
	}
	?>

	display.innerHTML = str;
	getIngredients();
}

function getIngredients()
{
	var str= '<table class = "ingredientTable"><tr><th class="nameCol">Name</th><th>Cost</th><th class="unitCol">Unit</th><th class="notes">Description</th><th>Add</th></tr>';
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
	addIDs = [];
	addQty = [];
	for(i = 0; i < ingIDs.length; i++)
	{
		var cur = document.getElementById("addIng_" + ingIDs[i]);
		if(cur.value > 0)
		{	
			console.log(cur.value);
			addIDs.push(ingIDs[i]);
			addQty.push(cur.value);
			cur.value = 0;
		}
	}
	
	
	var ajax = new XMLHttpRequest();
	ajax.open("GET","controller.php?call=addToShoppingList&add_ig=" + 
				JSON.stringify(addIDs) + "&add_qty=" + JSON.stringify(addQty), true); 
	ajax.send();
	ingIDs = [];
	ajax.onreadystatechange = function() 
	{
		if (ajax.readyState == 4 && ajax.status == 200) 
		{
			var str = (ajax.responseText);
			console.log("reponse: " + str);
			console.log("completed adding to shopping list");
		}
	} 

	ingredients();	
}
function newIngredient()
{
	var display = document.getElementById("display");
	var str = "";
	str += '<div class="ingredients"></div><div class="recipe">New Ingredient';
	str +='<form action="controller.php" id="newIng" method="POST"><div class="newIng">';
	str += 'Name: <input type="text" maxlength="13" size="13" name="ingName" class="ingBox" required> <br>';
	str += 'Cost: <input type="text" maxlength="13" size="13" name="ingCost" class="ingBox" required> <br>';
	str += 'Unit: <input type="text" maxlength="13" size="13" name="ingUnit" class="ingBox" required> <br>';
	str += '<div class="lineCols">Description: <br><textarea form="newIng" rows="4" cols="25" name="ingDescription" class="ingBox"required></textarea></div> <br>';
	str += '<input type="submit" class="submit" name="Add Ingredient" value="Add Ingredient" class="submit"></div></form>';
	str += '<button onclick="ingredients()">Back</button</div>';
	display.innerHTML = str;
}
function recipes()
{
	var display = document.getElementById("display");
	var str = "";
	str += '<div class="recipePic"></div><div class="multipleRecipe">Recipes';
	<?php
	if(isset($_SESSION['user']))
	{
	    echo 'str += "<button class = \'addRecipe\' onclick=\'addRecipe()\'>Add a Recipe</button>";';
	}
	?>
	str += '<div id="recipeList"></div>';
	<?php
    /*if(isset($_SESSION['user']))
	{
	   echo "str += '<button onclick=\"addRecipeToList()\">Add to List</button</div>';";
	}*/
	?>
	display.innerHTML = str;
	getRecipes();
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
	var recipeList = document.getElementById("recipeList");
	var ajax = new XMLHttpRequest();
	ajax.open("GET","controller.php?call=recipes", true); 
	ajax.send();
	ajax.onreadystatechange = function() 
	{
		console.log("State: " + ajax.readyState);
		if (ajax.readyState == 4 && ajax.status == 200) 
		{
			var array = JSON.parse(ajax.responseText);
			var str = "";
			var curID = -1;
			
			for(var i=0; i<array.length; i++)
			{
				if(array[i]['rec_id'] != curID)
				{
					str += '<div class="aRecipe"><div class="recipeTitle">'+array[i]['recipe']+'</div>';
					str += '<img class="recipeImage"src="./images/'+array[i]['pic_url']+'" alt="Recipe Pic" width="300">';
			 		str+='<div class="recipeInfo"><ul>';
			 		curID = array[i]['rec_id'];
				}
				
				str+='<li>'+array[i]['ingredient']+'</li><div class="indent">-'+array[i]['qty']+'</div><br>';

				if(i+1 == array.length || array[i+1]['rec_id']!=curID)
				{
					str+='</ul><br>';
					str+='<div class="recipeDes">'+array[i]['description']+'</div></div></div><br>';
				}
			}
			
			recipeList.innerHTML = str;
		}
	}  

}

function shoppingList()
{
	var display = document.getElementById("display");
	var str = "";
	str += '<div class="grocery"></div><div class="recipe">Shopping List';
	
	str += '<div id="ShoppingListTable"></div>';
	display.innerHTML = str;
	getShoppingList();
	
}

function getShoppingList()
{

	var str= '<table class = "shoppingTable"><tr><th>Name</th><th>Cost</th><th>Qty</th><th>Unit</th></tr>';
	var qdiv = document.getElementById("ShoppingListTable");
	var ajax = new XMLHttpRequest();
	ajax.open("GET","controller.php?call=getShoppingList", true); 
	ajax.send();
	ajax.onreadystatechange = function() 
	{
		if (ajax.readyState == 4 && ajax.status == 200) 
		{
			var arr = JSON.parse(ajax.responseText);
			var total = 0;
			console.log(arr.length);
			for(i = 0; i < arr.length; i++)
			{
				ingIDs.push(arr[i]['id']);
				var unit = "";
				var notes = "";
				if(arr[i]['unit'] == null)
					unit = "N/A";
				else
					unit = arr[i]['unit'];
					
				str+= '<tr><td>' + arr[i]['name'] + '</td><td>$' + arr[i]['cost'] +'</td><td>'+ arr[i]['qty'] + '</td><td>' + unit + '</td>';
				total += Number(arr[i]['cost']) * Number(arr[i]['qty']);
			}
			str += "</table><br><div>Total: $";
			str += total.toFixed(2);
			str += "<br><button onclick='checkout()' class='checkout'>Checkout</button></div>";
		 	qdiv.innerHTML = str;
		}
	} 
	
}
function addRecipeToList()
{
	alert();
}

function logout()
{
	document.location.href = "./controller.php";
}

function checkout()
{
	var ajax = new XMLHttpRequest();
	ajax.open("GET","controller.php?call=checkout", true); 
	ajax.send();
	ajax.onreadystatechange = function() 
	{
		console.log("State: " + ajax.readyState);
		if (ajax.readyState == 4 && ajax.status == 200) 
		{
		     
		}
	}

	shoppingList();
}
</script>

</body>
</html>
