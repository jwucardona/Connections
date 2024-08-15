<!--
22 April 2024 - ISS 4014
Amy Hebert (4 hours) - Set up database in AWS; Fetching data from database with SQL commands; Set up word shuffle function
Megan Mkrtchyan (4 hours) - Styling and aesthetics of game; Set up toggle selection for buttons
Sam Lewis (6 hours) - Set up database data - SQL insert commands to add NYT categories/answers to database; Quality assurance; Finding and solving bugs in the game
Joella Wu-Cardona (6 hours) - Displaying category names on successful connection; Checks selected words to see if they're from same category and changes colors; Added deselect button
-->
<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>NYT Connections</title>
   <style>
       .button {
          width: 150px;
          height: 100px;
          margin: 5px;
          border-radius: 10px;
          transition-duration: 0.4s;
          border: none;
          font-family: "Barlow Semi Condensed", sans-serif;
           font-weight: 700;
           font-size: 20px;
           background-color: #efefe6;
           vertical-align: top;
       }
       .button:hover {
           border: 3px solid darkgray;
       }
       .clicked {
           background-color: darkgray;
           border: none;
           color: white;
       }
       .yellow {
           background-color: gold !important;
       }
       .blue {
           background-color: lightskyblue !important;
       }
       .green {
           background-color: palegreen !important;
       }
       .purple {
           background-color: plum !important;
       }
       body {
           font-family: "Barlow Semi Condensed", sans-serif;
           font-weight: 400;
       }
       #newgame-button{
           border: 3px solid lightskyblue;
           width: 110px;
           height: 50px;
           font-family: "Barlow Semi Condensed", sans-serif;
           font-weight: 400;
           background-color: #efefe6;
       }
       #newgame-button:hover{
           background-color: lightskyblue;
           color: white;
       }
       #submit-button{
           border: 3px solid gold;
           width: 110px;
           height: 50px;
           font-family: "Barlow Semi Condensed", sans-serif;
           font-weight: 400;
           background-color: #efefe6;
       }
       #submit-button:hover{
           background-color: gold;
           color: white;
       }
       #deselect-button{
           border: 3px solid palegreen;
           width: 110px;
           height: 50px;
           font-family: "Barlow Semi Condensed", sans-serif;
           font-weight: 400;
           background-color: #efefe6;
       }
       #deselect-button:hover{
           background-color: palegreen;
           color: white;
       }
       #help-button{
           border: 3px solid plum;
           width: 110px;
           height: 50px;
           font-family: "Barlow Semi Condensed", sans-serif;
           font-weight: 400;
           background-color: #efefe6;
       }
       #help-button:hover{
           background-color: plum;
           color: white;
       }
   </style>
   <link rel="preconnect" href="https://fonts.googleapis.com">
   <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
   <link href="https://fonts.googleapis.com/css2?family=Barlow+Semi+Condensed:wght@400;700&family=Nanum+Gothic+Coding&display=swap" rel="stylesheet">
</head>

<body>
   <center>
   <!-- <img src="ConnectionsTitle.png" alt="Title" height="300px" width="550px"> -->
   <div id="buttonGrid">
       <?php
       // Connect to database
       $dbc = @mysqli_connect("localhost","root","password","connections");
       //$dbc = @mysqli_connect("iss4014.cliqugog0hc2.us-east-1.rds.amazonaws.com","admin","password","connections");
       if(!$dbc)
       {
           die ("Connection failed: " . mysqli_connect_errno() . " : " . mysqli_connect_error()); //if failed connection
       }

       // Define arrays to store words and their categories
       $words = array();
       $categories = array();

       // Fetch words from YELLOW table
       $id = rand(1, 25);
       $sql_yellow = "SELECT WORDY_1, WORDY_2, WORDY_3, WORDY_4 FROM YELLOW where id=$id";
       $rs_yellow = mysqli_query($dbc, $sql_yellow);
       $row_yellow = mysqli_fetch_assoc($rs_yellow);
       foreach ($row_yellow as $word) {
           $words[] = $word;
           $categories[$word] = 'yellow'; // Assign category 'yellow' to each word
       }

       // Fetch words from BLUE table
       $sql_blue = "SELECT WORDB_1, WORDB_2, WORDB_3, WORDB_4 FROM BLUE WHERE id = $id";
       $rs_blue = mysqli_query($dbc, $sql_blue);
       $row_blue = mysqli_fetch_assoc($rs_blue);
       foreach ($row_blue as $word) {
           $words[] = $word;
           $categories[$word] = 'blue'; // Assign category 'blue' to each word
       }

       // Fetch words from GREEN table
       $sql_green = "SELECT WORDG_1, WORDG_2, WORDG_3, WORDG_4 FROM GREEN WHERE id = $id";
       $rs_green = mysqli_query($dbc, $sql_green);
       $row_green = mysqli_fetch_assoc($rs_green);
       foreach ($row_green as $word) {
           $words[] = $word;
           $categories[$word] = 'green'; // Assign category 'green' to each word
       }

       // Fetch words from PURPLE table
       $sql_purple = "SELECT WORDP_1, WORDP_2, WORDP_3, WORDP_4 FROM PURPLE WHERE id = $id";
       $rs_purple = mysqli_query($dbc, $sql_purple);
       $row_purple = mysqli_fetch_assoc($rs_purple);
       foreach ($row_purple as $word) {
           $words[] = $word;
           $categories[$word] = 'purple'; // Assign category 'purple' to each word
       }

       // Fetch category names
       $sql_catNameY = "SELECT CAT_NAME FROM YELLOW WHERE id=$id"; //yellow
       $rs_catNameY = mysqli_query($dbc, $sql_catNameY);
       $catNameY = mysqli_fetch_row($rs_catNameY);
       $sql_catNameG = "SELECT CAT_NAME FROM GREEN WHERE id=$id"; //green
       $rs_catNameG = mysqli_query($dbc, $sql_catNameG);
       $catNameG = mysqli_fetch_row($rs_catNameG);
       $sql_catNameB = "SELECT CAT_NAME FROM BLUE WHERE id=$id"; //blue
       $rs_catNameB = mysqli_query($dbc, $sql_catNameB);
       $catNameB = mysqli_fetch_row($rs_catNameB);
       $sql_catNameP = "SELECT CAT_NAME FROM PURPLE WHERE id=$id"; //purple
       $rs_catNameP = mysqli_query($dbc, $sql_catNameP);
       $catNameP = mysqli_fetch_row($rs_catNameP);

       // Making buttons and randomizing word order
       shuffle($words);
       $i = 0;
       foreach ($words as $word) {
           $i++;
           echo '<button class="button" data-category="' . $categories[$word] . '" data-value="' . $word . '" onclick="toggleSelection(this)" type="button">' . $word . '</button>';
           if ($i % 4 == 0) {
               echo '<br />';
           }
       }
       mysqli_close($dbc);
       ?>
   </div>

   <br>
   <!-- Buttons for submitting, deselecting all buttons, new game, and instructions -->
   <button id="submit-button" onclick="checkSelectedWords()">Submit</button>
   <button id="deselect-button" onclick="clearSelection()">Deselect All</button>
   <button id="newgame-button" onclick='window.location.reload(true);'>New Game</button>
   <button id="help-button" onclick="Instructions()">How to Play</button>

   <script>
       var selectedWords = [];
       var catCount = 0;

       // Function to toggle the clicked button's color and update selected words array
       function toggleSelection(button)
       {
           if (button.classList.contains("clicked")) //if clicked, unclick
           {
               button.classList.remove("clicked");
               var index = selectedWords.indexOf(button.getAttribute("data-value"));
               if (index !== -1)
               {
                   selectedWords.splice(index, 1);
               }
           }
           else //if not clicked, click
           {
               if (selectedWords.length < 4 && !(button.classList.contains("yellow") || button.classList.contains("green") || button.classList.contains("purple") || button.classList.contains("purple")))
               {
                   button.classList.add("clicked");
                   selectedWords.push(button.getAttribute("data-value"));
               }
           }
       }

       // Function to clear selected words
       function clearSelection() 
       {
           selectedWords = [];
           var buttons = document.querySelectorAll('.clicked');
           buttons.forEach(function(btn) {
               btn.classList.remove("clicked");
           });
       }

       // Function to check if selected words belong to the same category
       function checkSelectedWords() 
       {
           var category = document.querySelector('[data-value="' + selectedWords[0] + '"]').dataset.category; //get the category of the first word
           var allSelectedFromSameCategory = true;

           if (selectedWords.length == 4) //must submit 4 words
           {
               //go through selected words and determine if they match the same category
               for (var i = 0; i < selectedWords.length; i++) {
                   if (category !== document.querySelector('[data-value="' + selectedWords[i] + '"]').dataset.category)
                   {
                       allSelectedFromSameCategory = false;
                       alert("Selected words are not from the same category");
                       break;
                   }
               }
               if (allSelectedFromSameCategory) //if all the words are from the same category
               {
                   selectedWords = [];
                   catCount++;

                   //reveal category name
                   if (category == "yellow") //yellow category
                   { 
                       var catName = <?php echo json_encode($catNameY[0]); ?>;
                       alert("Selected words are from the category: " + catName);
                   }
                   else if (category == "green") //green category
                   { 
                       var catName = <?php echo json_encode($catNameG[0]); ?>;
                       alert("Selected words are from the category: " + catName);
                   }
                   else if (category == "blue") //blue category
                   { 
                       var catName = <?php echo json_encode($catNameB[0]); ?>;
                       alert("Selected words are from the category: " + catName);
                   }
                   else if (category == "purple") //purple category
                   { 
                       var catName = <?php echo json_encode($catNameP[0]); ?>;
                       alert("Selected words are from the category: " + catName);
                   }
                   else //if something goes horribly wrong
                   {
                       alert("Error");
                   }

                   //change button color
                   var buttons = document.querySelectorAll('[data-category="' + category + '"]');
                   buttons.forEach(function(btn) {
                       btn.classList.add(category);
                       btn.classList.remove("clicked");
                   });

                   //if they made 4 connections (win)
                   if (catCount == 4) {
                       alert("You win! Click 'New Game' to play again.");
                   }
               }
           }
           else //if submission is less than 4 words
           {
               alert("Must submit 4 words")
           }
       }

       // Function to display the instructions
       function Instructions() {
           alert("How to Play\nFind groups of four items that share something in common.\nSelect four items and tap 'Submit' to check if your guess is correct.\nFind all the connections to win!\n\nCategory Examples\nFISH: Bass, Flounder, Salmon, Trout\nFIRE ___: Ant, Drill, Island, Opal\n\nEach puzzle has exactly one solution. Watch out for words that seem to belong to multiple categories!\n\nEach group is assigned a color, which will be revealed as you solve. Order of difficulty (low to high): Yellow, Green, Blue, Purple");
       }
   </script>
   </center>
</body>
</html>