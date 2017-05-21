

Welcome back! Here are the users available: <br>

<?php foreach($users as $user) {?>
	<div>
		<span><?= $user['ID'] ?></span>
		<span><?= $user['username'] ?></span>
		<span><?= $user['email'] ?></span>
	</div>
	
<?php } ?> 


<a href="<?= UrlUtils::getControllerUrl( "Login/Logout") ?>">Logout</a>