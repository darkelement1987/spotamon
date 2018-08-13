<?php require_once 'initiate.php'; 
    $gcountquery  = $conn->query("SELECT * FROM `gyms`");
    $gcountresult = mysqli_num_rows($gcountquery);

    $scountquery  = $conn->query("SELECT * FROM `stops`");
    $scountresult = mysqli_num_rows($scountquery);

?>
<nav class="navbar fixed-top uneek navbar-expand-lg p-0">
			<a class="navbar-brand" href="index.php">
				<img src="<?=W_ASSETS?>/img/header.png" alt="Spotamon" />
			</a>

			<!--toggle button for navbar start-->
			<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbartoggles" aria-controls="navbartoggles"
			 aria-expanded="false" aria-label="Toggle navigation">
				<span class="navbar-toggler-icon"></span>
			</button>
			<!--toggle button for navbar ends -->

			<div class="collapse navbar-collapse" id="navbartoggles">
				<ul class="navbar-nav mr-auto mt-2 mt-lg-0">


					<!--Dropdown for SPOTS-TAB starts -->
					<li class="dropdown">
						<a class="nav-link" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
							<i class="fa fa-plus"></i> Add Spot
							<i class="fa fa-angle-down ml-2 mt-1 float-right"></i>
						</a>
						<ul class="dropdown-menu mt-0 p-0 listing animated fadeInUp" aria-labelledby="navbarDropdown">
							<li>
								<a href="./submit-pokemon.php">PokÃ©mon</a>
							</li>
							<?php if (empty($gcountresult)) {} else { ?>
							<li>
								<a href="./submit-raid.php">Raid</a>
							</li>
							<li>
								<a href="./submit-ex-raid.php">EX Raid</a>
							</li>
							<li>
								<a href="./submit-team.php">Team</a>
							</li>
							<li>
								<a href="./submit-egg.php">Egg</a>
							</li>
							<?php } ?>
							<?php if (empty($scountresult)) {} else { ?>
							<li>
								<a href="./submit-quest.php">Quest</a>
							</li>
							<?php } ?>
						</ul>
					</li>
					<!--Dropdown for SPOTS-TAB ends -->
					<!--Dropdown for VIEW-TAB starts -->
					<li class="dropdown">
						<a class="nav-link" href="flyout.html" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
							<i class="fa fa-search"></i> View Spots
							<i class="fa fa-angle-down ml-2 mt-1 float-right"></i>
						</a>
						<ul class="dropdown-menu mt-0 p-0 listing animated fadeInUp" aria-labelledby="navbarDropdown">
							<li>
								<a href="./pokemon.php">Pokémon</a>
							</li>
							<?php if (empty($gcountresult)) {} else { ?>
							<li>
								<a href="./raids.php">Raid</a>
							</li>
							<li>
								<a href="./exraids.php">EX Raids</a>
							</li>
							<li>
								<a href="./eggs.php">Egg</a>
							</li>
							<?php } ?>
							<?php if (empty($scountresult)) {} else { ?>
							<li>
								<a href="./quests.php">Quest</a>
							</li>
							<?php } ?>
						</ul>
					</li>

					<!--Dropdown for SPOT-TAB ends -->
					<!--Dropdown for TRADE-TAB starts -->
					<li class="dropdown">
						<a class="nav-link" href="flyout.html" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
							<span class="far fa-handshake"></span> Trades
							<i class="fa fa-angle-down ml-2 mt-1 float-right"></i>
						</a>
						<ul class="dropdown-menu mt-0 p-0 listing animated fadeInUp" aria-labelledby="navbarDropdown">
							<li>
								<a href="./offer-trade.php">Create Trade</a>
							</li>
							<li>
								<a href="./active-trades.php">Open Trades</a>
							</li>
						</ul>
					</li>

					<!--Dropdown for TRADES-TAB ends -->

					<!--Dropdown for FEEDBACK-TAB starts -->
					<?php if ($showformlink === true) { ?>
					<li class="dropdown">
						<a class="nav-link" href="./feedback.php" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
							<i class="far fa-comment"></i> Feedback
							<i class="fa fa-angle-down ml-2 mt-1 float-right"></i>
						</a>
						<ul class="dropdown-menu mt-0 p-0 listing fw animated fadeInUp" aria-labelledby="navbarDropdown">
							<div class="row m-0 py-3 contact-tab">
								<div class="col-md-4 img-fluid align-self-center py-3">
									<img src="<?=W_ASSETS?>img/feedback.png" class="img-fluid" />
								</div>
								<div class=" col-md-8 align-self-center p-0 ">
									<?php if(isset($_SESSION['uname'])){?>
									<h3 class="text-center ">Feedback</h3>
									<br />
									<form method="post " class="p-2 mx-auto w-100 border-0 " id="feedback ">
										<div class="form-group ">
											<label>Name</label>
											<input type="text " name="name " placeholder="Enter Name " class="form-control " value="<?=$name?>"
											/>
										</div>
										<div class="form-group">
											<label>Email</label>
											<input type="text" name="email" class="form-control" placeholder="Enter Email" value="<?=$email?>"
											/>
										</div>
										<div class="form-group">
											<input type="hidden" name="subject" class="form-control" placeholder="Enter Subject" value="<?=$feedbacksubject?>"
											/>
										</div>
										<div class="form-group">
											<label>Message</label>
											<textarea name="message" class="form-control" placeholder="Enter Message" rows="5"><?=$message?></textarea>
										</div>
										<div class="form-group" align="center">
											<input type="submit" name="submit" value="Submit" class="btn btn-info" />
										</div>
										<?=$error?>
									</form>
									<?php } else { ?>
									<div>
										Login to use the feedback form
										<br />
										<br />
										<a href="./login/login.php">Login Here</a>
									</div>
									<?php } ?>
								</div>
								<?php }?>
							</div>
						</ul>
					</li>
					<!--Dropdown for FEEDBACK-TAB ends -->
				</ul>
				<ul class="navbar-nav ml-auto mt-2 mt-lg-0">
					<!--Dropdown for MAIL-TAB starts -->
					<?php
if (isset($_SESSION["uname"])) {

        // Lookup usrpic filename for user
        $urlquery  = "SELECT url FROM users WHERE uname = '" . $_SESSION['uname'] . "'";
        $resulturl = $conn->query($urlquery);
        $rowurl    = $resulturl->fetch_array(MYSQLI_NUM);
		$url       = $rowurl[0];
        $countquery = $conn->query("SELECT * FROM `messages` WHERE unread=1 AND to_user = '" . $_SESSION["uname"] . "' AND del_in='0'");
        $msgcount   = $countquery->num_rows;
        ?>
					<li class="dropdown">
						<a class="nav-link" href="flyout.html" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
							<?php if ($msgcount >= 1) { ?>
							<i class="far fa-envelope"></i> Mail
							<span class="badge">
								<?=$msgcount?>
							</span>
							<?php
                            } else {
                            ?>
							<i class="far fa-envelope-open"></i> Mail
							<?php } ?>
							<i class="fa fa-angle-down ml-2 mt-1 float-right"></i>
						</a>
						<ul class="dropdown-menu mt-0 p-0 listing animated fadeInUp" aria-labelledby="navbarDropdown">
							<li>
								<a href="./inbox.php">Inbox</a>
							</li>
							<li>
								<a href="./outbox.php">Outbox</a>
							</li>
							<li>
								<a href="./compose.php">Send message</a>
							</li>
						</ul>
					</li>
					<!--Dropdown for MAIL-TAB ends -->
					<!-- USERPROFILE begins -->
					<li>
						<a href="profile.php" class="nav-link nav-text">
							<?php if (!empty($url)) { 
								if (substr($url, 0, 5) == 'https') {?>
								<img src="<?=$url?>" height="19px" width="19px" alt="logo" style="border:1px solid black">
								<?php } else { ?>
								<img src="<?=W_ROOT?>userpics/<?=$url?>" height="19px" width="19px" 	alt="logo" style="border:1px solid black">
							<?php }} else { ?>
							<img src="<?=W_ROOT?>userpics/nopic.png" height="19px" width="20px" alt="logo" style="border:1px solid black">
							<?php }?>
							<?=$_SESSION['uname']?>
						</a>
					</li>
					<!-- USERPROFILE ends -->
					<li>
						<a href="<?=W_PAGES?>logout.php" class="nav-link" id="logout-link">
							<i class="fa fa-sign-out"></i> Logout</a>
					</li>
					<?php } else { ?>
					<li>
						<a href="login/login.php" class="nav-link" id="login-link" data-toggle="modal" data-target="#auth-modal">
							<i class="fas fa-sign-in-alt"></i> Login/Register</a>
					</li>
					<?php } ?>
				</ul>
			</div>
		</nav>
