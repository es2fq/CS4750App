<!DOCTYPE HTML>
<?php
include('sessionwithlogout.php');
include('db.php');
?>
<html>
	<head>
		<title>Macro Base</title>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
		<link rel="stylesheet" href="assets/css/main.css" />
		<!--[if lte IE 9]><link rel="stylesheet" href="assets/css/ie9.css" /><![endif]-->
		<noscript><link rel="stylesheet" href="assets/css/noscript.css" /></noscript>
	</head>
	<body>

		<!-- Wrapper -->
			<div id="wrapper">

				<!-- Header -->

				<?php
				if ($_SESSION['loggedin'] != true) {
				?>
					<header id="header">
						<div class="logo">
							<span class="icon fa-diamond"></span>
						</div>
						<div class="content">
							<div class="inner">
								<h1>Macrobase</h1>
                <p>Who says you can't have fun while dieting? With Macrobase, you can! Track your calories and diet throughout the day, and view your progress as you go on with your life!</p>
							</div>
						</div>
						<nav>
							<ul>
								<li><a href="#signin">Sign In</a></li>
								<li><a href="#signup">Sign Up</a></li>
								<!--<li><a href="#elements">Elements</a></li>-->
							</ul>
						</nav>
					</header>
				<?php
					} else {
				?>
					<header id="header">
						<div class="logo">
							<span class="icon fa-diamond"></span>
						</div>
						<div class="content">
							<div class="inner">
								<h1>Macrobase</h1>
								<p>Who says you can't have fun while dieting? With Macrobase, you can! Track your calories and diet throughout the day, and view your progress as you go on with your life!</p>
							</div>
						</div>
						<nav>
							<ul>
								<li><a href="#summary">Summary</a></li>
								<li><a href="#work">Work</a></li>
								<li><a href="#about">Add Food</a></li>
								<li><a href="#diets">Diets</a></li>
                				<li><a href="#elements">Elements</a></li>
								<li><a href="logout.php">Log Out</a><li>
								<!--<li><a href="#elements">Elements</a></li>-->
							</ul>
						</nav>
					</header>
				<?php
				}
				?>
				<!-- Main -->
					<div id="main">

						<!-- summary -->
							<article id="summary">
								<h2 class="major">Summary</h2>

								<h3>Past Foods</h3>
								<table>
					        <thead>
					            <tr>
					                <td>Date</td>
					                <td>Food</td>
													<td>Count</td>
													<td>Calories</td>
					            </tr>
					        </thead>
					        <tbody>
								<?php
									$sql = "select user_id, foods_id, count, name, calories, date from Food_Intake NATURAL JOIN Foods where user_id=".$_SESSION['user_id'];
									$result = $connection->query($sql);


									if ($result->num_rows > 0) {
									    // output data of each row
									    while($row = $result->fetch_assoc()) {
											?>
												<tr>
				                    <td><?php echo $row['date']?></td>
				                    <td><?php echo $row['name']?></td>
														<td><?php echo $row['count']?></td>
														<td><?php echo $row['calories']*$row['count']?></td>
				                </tr>
											<?php
									    	}
											}
										?>
								</tbody>
		            </table>


						</article>

						<!-- Work -->
							<article id="work">
								<h2 class="major">Work</h2>
								<span class="image main"><img src="images/pic02.jpg" alt="" /></span>
								<p>Adipiscing magna sed dolor elit. Praesent eleifend dignissim arcu, at eleifend sapien imperdiet ac. Aliquam erat volutpat. Praesent urna nisi, fringila lorem et vehicula lacinia quam. Integer sollicitudin mauris nec lorem luctus ultrices.</p>
								<p>Nullam et orci eu lorem consequat tincidunt vivamus et sagittis libero. Mauris aliquet magna magna sed nunc rhoncus pharetra. Pellentesque condimentum sem. In efficitur ligula tate urna. Maecenas laoreet massa vel lacinia pellentesque lorem ipsum dolor. Nullam et orci eu lorem consequat tincidunt. Vivamus et sagittis libero. Mauris aliquet magna magna sed nunc rhoncus amet feugiat tempus.</p>
							</article>

						<!-- About -->
							<article id="about">
								<h2 class="major">Add Food</h2>
									<form action="" method="post">
                      <select id="selectFood" name="food">
                      </select>
                      <br>
                      How many did you eat? <input id="foodCount" name="count" />
											<input name="action" type="hidden" value="addFood" />
                      <input type="submit" />
                  </form>
									<?php
										if(isset($_POST['action']))
										{
											if($_POST['action']=="addFood"){
													$count = $_POST["count"];
													$foodData = explode(",", $_POST["food"]);
													$foodId = $foodData[0];
													$foodName = $foodData[1];
													$foodCalories = $foodData[2];

													$userId = $_SESSION["user_id"];
													$date =  date('Y-m-d');

													$sql = "INSERT INTO Food_Intake (date, user_id, foods_id, count) VALUES (" . "now()" . "," . $userId . "," . $foodId . "," . $count . ")";

													if ($connection->query($sql) === TRUE) {
													    echo "Food has been added.";
															//add auto refresh
													}
											}
										}
									?>
							</article>

						<!-- Diets -->
							<article id="diets">
								<h2 class="major">Your Current Diet</h2>
									<div>
										<?php
											$user_id = $_SESSION["user_id"];
											$sql = "select user_id, recommended_diets_id, recommended_diets_name, total_calories, diet_food_intakes, total_carbs, total_fat, total_protein, total_cholestrol, total_calories from Users NATURAL JOIN Recommended_Diets where user_id=".$user_id;

											$result = $connection->query($sql);
											if ($result->num_rows > 0) {
												while($row = $result->fetch_assoc()) {
													echo '<p> Your currently selected diet is <b>'.$row['recommended_diets_name'] .'</b>.</p>';
													$recommended_diets_id = $row['recommended_diets_id'];
													$diet_sql = "select diet_food_intakes, diet_food_intake_id, recommended_diets_id, food_id, name, count from Recommended_Diets d join Diet_Food_Intake f on d.diet_food_intakes = f.diet_food_intake_id join Foods x on f.food_id = x.foods_id WHERE recommended_diets_id=".$recommended_diets_id;
													$diet_result = $connection->query($diet_sql);
													while($diet_row = $diet_result->fetch_assoc()) {
													?>
														<table>
															<caption><b>Diet Breakdown</b></caption>
											        <thead>
											            <tr>
											                <td>Food</td>
											                <td>Count</td>
																			<td>Total Calories</td>
																			<td>Total Carbs</td>
																			<td>Total Fat</td>
																			<td>Total Protein</td>
																			<td>Total Cholestrol</td>
											            </tr>
											        </thead>
											        <tbody>
															<tr>
																	<td><?php echo $diet_row['name']?></td>
																	<td><?php echo $diet_row['count']?></td>
																	<td><?php echo $row['total_calories']?></td>
																	<td><?php echo $row['total_carbs']?></td>
																	<td><?php echo $row['total_fat']?></td>
																	<td><?php echo $row['total_protein']?></td>
																	<td><?php echo $row['total_cholestrol']?></td>
															</tr>
														</tbody>
														</table>
														<p> <a href="#chooseDiet"> Would you like to choose a different diet? </a></p>
														<?php
													}
												}
											} else {
												echo '<p> No recommended diet has been selected. <a href="#chooseDiet">Would you like to choose a diet?</a></p>';
											}
										?>
									</div>
							</article>

						<!-- Diets -->
							<article id="chooseDiet" style="width:100%;">
								<h2 class="major">Recommended Diets</h2>
									<div>
										<table>
											<caption><b>Diets</b></caption>
											<thead>
													<tr>
															<td>Diet</td>
															<td>Food</td>
															<td>Count</td>
															<td>Calories</td>
															<td>Carbs</td>
															<td>Fat</td>
															<td>Protein</td>
															<td>Cholestrol</td>
															<td>  </td>
													</tr>
											</thead>
											<tbody>
										<?php
											$user_id = $_SESSION["user_id"];
											$sql = "select recommended_diets_id, recommended_diets_name, total_calories, diet_food_intakes, total_carbs, total_fat, name, count, total_protein, total_cholestrol, total_calories from Recommended_Diets r join Diet_Food_Intake f on r.diet_food_intakes = f.diet_food_intake_id join Foods x on f.food_id = x.foods_id";

											$result = $connection->query($sql);
											if ($result->num_rows > 0) {
												while($row = $result->fetch_assoc()) {
													?>
														<tr>
															<form  action="" method="post">
																<input name="action" type="hidden" value="select_diet"/>
																<input name="diet" type="hidden" value=" <?php echo $row['recommended_diets_id']; ?>" />
																<td><?php echo $row['recommended_diets_name']?></td>
																<td><?php echo $row['name']?></td>
																<td><?php echo $row['count']?></td>
																<td><?php echo $row['total_calories']?></td>
																<td><?php echo $row['total_carbs']?></td>
																<td><?php echo $row['total_fat']?></td>
																<td><?php echo $row['total_protein']?></td>
																<td><?php echo $row['total_cholestrol']?></td>
																<td><input type="submit" value="Select"></td>
															</form>
														</tr>
														<?php
													}
												}
										?>
									</tbody>
									</table>
									<?php
										if(isset($_POST['action']))
										{
											if($_POST['action']=="select_diet"){
													$diet_id = $_POST["diet"];
													$user_id = $_SESSION["user_id"];
													$sql = "UPDATE Users SET recommended_diets_id=".$diet_id. " WHERE user_id=".$user_id;
													$result = $connection->query($sql);
													echo "Diet has been updated.";
													header('Refresh: 2');
											}
										}
									?>
									</div>
							</article>

						<!-- Sign In -->
							<article id="signin">
								<h2 class="major">Sign In</h2>
								<form method="post" action="#signin">
									<div class="field half first">
										<label for="email">Username</label>
										<input type="text" name="email" id="email" />
									</div>
									<div class="field half second">
										<label for="password">Password</label>
										<input type="password" name="password" id="password" />
									</div>
									<input name="action" type="hidden" value="signin" /></p>
									<ul class="actions">
										<li><input type="submit" value="Sign In" class="special" /></li>
										<li><input type="reset" value="Reset" /></li>
									</ul>
									<br/>
									<p> Don't have an account? <a href="#signup"> Create an account. </a> </p>
								</form>

								<?php
								session_start(); // Starting Session
								if(isset($_POST['action']))
								{
										if($_POST['action']=="signin")
										{
												session_start();
												$email = mysqli_real_escape_string($connection,$_POST['email']);
												$password = mysqli_real_escape_string($connection,$_POST['password']);
												$strSQL = mysqli_query($connection,"select user_id, first_name, last_name, email from users where email='".$email."' and password='".md5($password)."'");
												$Results = mysqli_fetch_array($strSQL);
												if(count($Results)>=1)
												{
													$_SESSION['email']=$Results['email'];
													$_SESSION['first_name']=$Results['first_name'];
													$_SESSION['last_name']=$Results['last_name'];
													$_SESSION['user_id']=$Results['user_id'];

													echo "<script type='text/javascript'> document.location = 'index.php'; </script>";// Redirecting To Other Page
												}
												else
												{
														$message = "Invalid email or password!!";
														echo("<p class='login-error-message' stype='color: red;'>".$message."</p>");
												}
										}
								}
								?>
							</article>

							<!-- Sign Up -->
								<article id="signup">
									<h2 class="major">Sign Up</h2>
									<form method="post" action="#signup">
										<div class="field half first">
											<label for="email_signup">Email</label>
											<input type="text" name="email_signup" id="email_signup" />
										</div>
										<div class="field half second">
											<label for="password_signup">Password</label>
											<input type="password" name="password_signup" id="password_signup" />
										</div>
										<div class="field half first">
											<label for="first_name">First Name</label>
											<input type="text" name="first_name" id="first_name" />
										</div>
										<div class="field half second">
											<label for="last_name">Last Name</label>
											<input type="text" name="last_name" id="last_name" />
										</div>
										<div class="field half first">
											<label for="current_weight">Current Weight</label>
											<input type="number" name="current_weight" id="current_weight" />
										</div>
										<div class="field half second">
											<label for="target_weight">Target Weight</label>
											<input type="number" name="target_weight" id="target_weight" />
										</div>
										<div class="field">
											<label for="age">Age</label>
											<input type="number" name="age" id="age" />
										</div>
										<input name="action" type="hidden" value="signup" /></p>
										<ul class="actions">
											<li><input type="submit" value="Sign Up" class="special" /></li>
											<li><input type="reset" value="Reset" /></li>
										</ul>
										<br/>
										<p> Already have an account? <a href="#signin"> Sign in here. </a> </p>
									</form>

									<?php
									session_start(); // Starting Session
									include('db.php');
									if(isset($_POST['action']))
									{
											if($_POST['action']=="signup")
											{
													$email      = mysqli_real_escape_string($connection,$_POST['email_signup']);
													$password   = mysqli_real_escape_string($connection,$_POST['password_signup']);
													$query = "='".$email."'";
													$result = mysqli_query($connection,$query);
													$numResults = mysqli_num_rows($result);
													if (!filter_var($email, FILTER_VALIDATE_EMAIL)) // Validate email address
													{
															$message =  "Invalid email please type a valid email!!";
															echo("<p id='php_error' class='submission_message_error'>".$message."</p>");
													}
													elseif(count($numResults)>=1)
													{
														$message = "Email is already taken.";
														echo("<p class='login-error-message' stype='color: red;'>".$message."</p>");
													}
													else
													{
														$first_name = mysqli_real_escape_string($connection,$_POST['first_name']);
														$last_name = mysqli_real_escape_string($connection,$_POST['last_name']);
														$target_weight = mysqli_real_escape_string($connection,$_POST['target_weight']);
														$current_weight = mysqli_real_escape_string($connection,$_POST['current_weight']);
														$age = mysqli_real_escape_string($connection,$_POST['age']);

														mysqli_query($connection, "insert into users(email, password, first_name, last_name, target_weight, current_weight, age) values('".$email."','".md5($password)."','".$first_name."','".$last_name."','".$target_weight."','".$current_weight."','".$age."')");
														$message = "Signed up sucessfully!!";
														echo("<p class='submission_message_success'>".$message."</p>");
													}
											}
									}
									?>
								</article>





						<!-- Elements -->
							<article id="elements">
								<h2 class="major">Elements</h2>

								<section>
									<h3 class="major">Text</h3>
									<p>This is <b>bold</b> and this is <strong>strong</strong>. This is <i>italic</i> and this is <em>emphasized</em>.
									This is <sup>superscript</sup> text and this is <sub>subscript</sub> text.
									This is <u>underlined</u> and this is code: <code>for (;;) { ... }</code>. Finally, <a href="#">this is a link</a>.</p>
									<hr />
									<h2>Heading Level 2</h2>
									<h3>Heading Level 3</h3>
									<h4>Heading Level 4</h4>
									<h5>Heading Level 5</h5>
									<h6>Heading Level 6</h6>
									<hr />
									<h4>Blockquote</h4>
									<blockquote>Fringilla nisl. Donec accumsan interdum nisi, quis tincidunt felis sagittis eget tempus euismod. Vestibulum ante ipsum primis in faucibus vestibulum. Blandit adipiscing eu felis iaculis volutpat ac adipiscing accumsan faucibus. Vestibulum ante ipsum primis in faucibus lorem ipsum dolor sit amet nullam adipiscing eu felis.</blockquote>
									<h4>Preformatted</h4>
									<pre><code>i = 0;

while (!deck.isInOrder()) {
    print 'Iteration ' + i;
    deck.shuffle();
    i++;
}

print 'It took ' + i + ' iterations to sort the deck.';</code></pre>
								</section>

								<section>
									<h3 class="major">Lists</h3>

									<h4>Unordered</h4>
									<ul>
										<li>Dolor pulvinar etiam.</li>
										<li>Sagittis adipiscing.</li>
										<li>Felis enim feugiat.</li>
									</ul>

									<h4>Alternate</h4>
									<ul class="alt">
										<li>Dolor pulvinar etiam.</li>
										<li>Sagittis adipiscing.</li>
										<li>Felis enim feugiat.</li>
									</ul>

									<h4>Ordered</h4>
									<ol>
										<li>Dolor pulvinar etiam.</li>
										<li>Etiam vel felis viverra.</li>
										<li>Felis enim feugiat.</li>
										<li>Dolor pulvinar etiam.</li>
										<li>Etiam vel felis lorem.</li>
										<li>Felis enim et feugiat.</li>
									</ol>
									<h4>Icons</h4>
									<ul class="icons">
										<li><a href="#" class="icon fa-twitter"><span class="label">Twitter</span></a></li>
										<li><a href="#" class="icon fa-facebook"><span class="label">Facebook</span></a></li>
										<li><a href="#" class="icon fa-instagram"><span class="label">Instagram</span></a></li>
										<li><a href="#" class="icon fa-github"><span class="label">Github</span></a></li>
									</ul>

									<h4>Actions</h4>
									<ul class="actions">
										<li><a href="#" class="button special">Default</a></li>
										<li><a href="#" class="button">Default</a></li>
									</ul>
									<ul class="actions vertical">
										<li><a href="#" class="button special">Default</a></li>
										<li><a href="#" class="button">Default</a></li>
									</ul>
								</section>

								<section>
									<h3 class="major">Table</h3>
									<h4>Default</h4>
									<div class="table-wrapper">
										<table>
											<thead>
												<tr>
													<th>Name</th>
													<th>Description</th>
													<th>Price</th>
												</tr>
											</thead>
											<tbody>
												<tr>
													<td>Item One</td>
													<td>Ante turpis integer aliquet porttitor.</td>
													<td>29.99</td>
												</tr>
												<tr>
													<td>Item Two</td>
													<td>Vis ac commodo adipiscing arcu aliquet.</td>
													<td>19.99</td>
												</tr>
												<tr>
													<td>Item Three</td>
													<td> Morbi faucibus arcu accumsan lorem.</td>
													<td>29.99</td>
												</tr>
												<tr>
													<td>Item Four</td>
													<td>Vitae integer tempus condimentum.</td>
													<td>19.99</td>
												</tr>
												<tr>
													<td>Item Five</td>
													<td>Ante turpis integer aliquet porttitor.</td>
													<td>29.99</td>
												</tr>
											</tbody>
											<tfoot>
												<tr>
													<td colspan="2"></td>
													<td>100.00</td>
												</tr>
											</tfoot>
										</table>
									</div>

									<h4>Alternate</h4>
									<div class="table-wrapper">
										<table class="alt">
											<thead>
												<tr>
													<th>Name</th>
													<th>Description</th>
													<th>Price</th>
												</tr>
											</thead>
											<tbody>
												<tr>
													<td>Item One</td>
													<td>Ante turpis integer aliquet porttitor.</td>
													<td>29.99</td>
												</tr>
												<tr>
													<td>Item Two</td>
													<td>Vis ac commodo adipiscing arcu aliquet.</td>
													<td>19.99</td>
												</tr>
												<tr>
													<td>Item Three</td>
													<td> Morbi faucibus arcu accumsan lorem.</td>
													<td>29.99</td>
												</tr>
												<tr>
													<td>Item Four</td>
													<td>Vitae integer tempus condimentum.</td>
													<td>19.99</td>
												</tr>
												<tr>
													<td>Item Five</td>
													<td>Ante turpis integer aliquet porttitor.</td>
													<td>29.99</td>
												</tr>
											</tbody>
											<tfoot>
												<tr>
													<td colspan="2"></td>
													<td>100.00</td>
												</tr>
											</tfoot>
										</table>
									</div>
								</section>

								<section>
									<h3 class="major">Buttons</h3>
									<ul class="actions">
										<li><a href="#" class="button special">Special</a></li>
										<li><a href="#" class="button">Default</a></li>
									</ul>
									<ul class="actions">
										<li><a href="#" class="button">Default</a></li>
										<li><a href="#" class="button small">Small</a></li>
									</ul>
									<ul class="actions">
										<li><a href="#" class="button special icon fa-download">Icon</a></li>
										<li><a href="#" class="button icon fa-download">Icon</a></li>
									</ul>
									<ul class="actions">
										<li><span class="button special disabled">Disabled</span></li>
										<li><span class="button disabled">Disabled</span></li>
									</ul>
								</section>

								<section>
									<h3 class="major">Form</h3>
									<form method="post" action="#">
										<div class="field half first">
											<label for="demo-name">Name</label>
											<input type="text" name="demo-name" id="demo-name" value="" placeholder="Jane Doe" />
										</div>
										<div class="field half">
											<label for="demo-email">Email</label>
											<input type="email" name="demo-email" id="demo-email" value="" placeholder="jane@untitled.tld" />
										</div>
										<div class="field">
											<label for="demo-category">Category</label>
											<div class="select-wrapper">
												<select name="demo-category" id="demo-category">
													<option value="">-</option>
													<option value="1">Manufacturing</option>
													<option value="1">Shipping</option>
													<option value="1">Administration</option>
													<option value="1">Human Resources</option>
												</select>
											</div>
										</div>
										<div class="field half first">
											<input type="radio" id="demo-priority-low" name="demo-priority" checked>
											<label for="demo-priority-low">Low</label>
										</div>
										<div class="field half">
											<input type="radio" id="demo-priority-high" name="demo-priority">
											<label for="demo-priority-high">High</label>
										</div>
										<div class="field half first">
											<input type="checkbox" id="demo-copy" name="demo-copy">
											<label for="demo-copy">Email me a copy</label>
										</div>
										<div class="field half">
											<input type="checkbox" id="demo-human" name="demo-human" checked>
											<label for="demo-human">Not a robot</label>
										</div>
										<div class="field">
											<label for="demo-message">Message</label>
											<textarea name="demo-message" id="demo-message" placeholder="Enter your message" rows="6"></textarea>
										</div>
										<ul class="actions">
											<li><input type="submit" value="Send Message" class="special" /></li>
											<li><input type="reset" value="Reset" /></li>
										</ul>
									</form>
								</section>

							</article>

					</div>

				<!-- Footer -->
					<footer id="footer">
						<p class="copyright">&copy; Macro Base</p>
					</footer>

			</div>

		<!-- BG -->
			<div id="bg"></div>

		<!-- Scripts -->
			<script src="assets/js/jquery.min.js"></script>
			<script src="assets/js/skel.min.js"></script>
			<script src="assets/js/util.js"></script>
			<script src="assets/js/main.js"></script>


      <script>
          function populateFoodList() {
          	var rawFile = new XMLHttpRequest();
          	rawFile.open("GET", "foods.txt", false);
          	rawFile.onreadystatechange = function() {
          		if (rawFile.readyState === 4) {
          			if (rawFile.status === 200 || rawFile.status == 0) {
          				var allText = rawFile.responseText;
          				console.log(allText);

          				var lines = allText.split("\n")
          				var select = document.getElementById("selectFood");
          				for (var i = 0; i < lines.length; i++) {
          					var data = lines[i].split(",");
          					var option = document.createElement("option");
          					option.text = data[1];
          					option.value = data[0] + "," + data[1] + "," + data[2];
          					select.add(option);
          				}
          			}
          		}
          	}
          	rawFile.send(null);
          }
          populateFoodList();
      </script>
	</body>
</html>
