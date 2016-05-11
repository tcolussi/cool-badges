<?php
    include_once "fbmain.php";
?>

<!DOCTYPE html>

<!-- BEGIN html -->
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:fb="http://www.facebook.com/2008/fbml">

<!-- BEGIN head -->
<head>

<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>

<title>Cool Badges</title>

<!-- Favicon -->
<link rel="shortcut icon" href="images/favicon.png" />

<!-- Stylesheet -->
<link type="text/css" href="css/style.css" rel="stylesheet" />
<link type="text/css" href="http://fonts.googleapis.com/css?family=Droid+Sans:rerular,italic,bold,bolditalic" rel="stylesheet" />
<!--[if lt IE 9]>
	<link rel="stylesheet" href="css/ie.css" type="text/css" media="screen" />
<![endif]-->

<!-- END head -->
</head>

<!-- BEGIN body -->
<body>

<div id="preloader"></div>

<div id="header" class="inner">

  <div class="logo left">

    <img src="images/logo.png" alt="Cool Badges" />

    <div class="like-button">

      <fb:like href="https://www.facebook.com/thevolumens" layout="button_count"></fb:like>

    </div><!--like-button-->

  </div><!--logo-->

  <div class="profile right">

    <a class="trigger">Hello, <strong><?php echo $user_profile['name']; ?></strong><span></span></a>

    <div class="tooltip">
      <ul>
        <li><a href="<?php echo $user_profile['link']; ?>" target="_blank"><span>Profile</span></a></li>
        <li><a href="#" onclick="feedDialog(); return false;"><span>Share</span></a></li>
        <li><a href="#" onClick="requestsDialog(); return false;"><span>Invite</span></a></li>
      </ul>
    </div><!--tooltip-->

  </div><!--profile-->

</div><!--header-->

<div id="main" class="clearfix">

  <div id="left-add" class="banner">

	<script type="text/javascript">
		google_ad_client = "ca-pub-5997029164354874";
		google_ad_slot = "8599768551";
		google_ad_width = 120;
		google_ad_height = 600;
    </script>
    <script type="text/javascript" src="http://pagead2.googlesyndication.com/pagead/show_ads.js"></script>

  </div><!--left-add-->

  <div id="right-add" class="banner">

	<script type="text/javascript">
		google_ad_client = "ca-pub-5997029164354874";
		google_ad_slot = "2553234954";
		google_ad_width = 120;
		google_ad_height = 600;
    </script>
    <script type="text/javascript" src="http://pagead2.googlesyndication.com/pagead/show_ads.js"></script>

  </div><!--right-add-->

  <?php
	if (isset($_GET['res'])) {
	  if ($_GET['res'] == 'true'){
  ?>

  <div class="alert clearfix" id="success">

    <p>Photo generated successfully. You can add another badge if you like...</p>

  </div><!--success-->

  <?php
    }
    if ($_GET['res'] == 'false'){
  ?>

  <div class="alert clearfix" id="error">

    <p>An error has occurred, please try again...</p>

  </div><!--error-->

  <?php
    }
  }
  ?>

  <div class="alert clearfix" id="loading" >

	<p>Please wait while your photo is generated and uploaded to Facebook...</p>

  </div><!--loading-->

  <div id="badges">

    <div class="wrap clearfix">

      <div class="scrollable">

        <div class="items tabs">

          <div>

            <a href="categories/emoticons.html">Emoticons</a>
            <a href="categories/christmas.html">Christmas</a>
            <a class="last" href="categories/halloween.html">Halloween</a>

          </div>

		  <div>

            <a href="categories/valentine.html">Valentine</a>
            <a href="categories/hearts.html">Hearts</a>
            <a class="last" href="categories/creatures.html">Creatures</a>

          </div>

          <div>

            <a href="categories/peppers.html">Peppers</a>
            <a href="categories/sports.html">Sports</a>
            <a class="last" href="categories/futurama.html">Futurama</a>

          </div>

        </div><!--items-->

      </div><!--scrollable-->

      <a class="prev browse left"></a>
      <a class="next browse right"></a>

    </div><!--wrap-->

    <div class="panes">

      <div class="pane clearfix"></div>
      <div class="pane clearfix"></div>
      <div class="pane clearfix"></div>
      <div class="pane clearfix"></div>
      <div class="pane clearfix"></div>
      <div class="pane clearfix"></div>
      <div class="pane clearfix"></div>
      <div class="pane clearfix"></div>
      <div class="pane clearfix"></div>

    </div><!--panes-->

  </div><!--badges-->

  <div id="picture" class="clearfix">

    <input type="text" id="description" value="Add a description..." onblur="this.value=!this.value?'Add a description...':this.value;" onclick="this.value='';" name="description">

    <div id="background" class="clearfix">

      <?php copy_remote_image("https://graph.facebook.com/{$user_profile['id']}/picture?width=960", $code2, $_CONFIG['max_width']); ?>

      <?php
		$destination = "fb_images/".$code2."_fb_photo.jpg";
		$image = getimagesize($destination);
		$width = $image[0];
		$height = $image[1];
	  ?>

      <img id="preview" src="<?php echo "fb_images/".$code2."_fb_photo.jpg";?>" width="<?php echo $width;?>" height="<?php echo $height;?>" alt="Profile Picture" />

      <div id="add-img"></div>

    </div><!--background-->

    <form id="send_form" action="index.php" name="send_form" method="post">
      <input type="hidden" name="action" value="upload_fb_image">
      <input type="hidden" name="photo" value="<?php echo $destination;?>">
      <input type="hidden" name="icon" id="icon" value="">
      <input type="hidden" name="message" id="message" value="">
    </form>
    <input id="submit" type="button" onclick="validate();" value="Upload to Facebook"/>

  </div><!--picture-->

</div><!--main-->

<div id="steps" class="clearfix">

  <div class="column">

    <h3>Choose</h3>

    <p><span class="number">1</span>Click on the desired badge to place it on your profile picture. There are many different badge categories, make sure to check them all out by clicking the next and preview buttons.</p>

  </div><!--column-->

  <div class="column">

    <h3>Upload</h3>

    <p><span class="number">2</span>When you have your badge selected, press the "Upload to Facebook" button and automatically save the picture into a new photo album in your profile. You can also add a description for your picture.</p>

  </div><!--column-->

  <div class="column" id="last">

    <h3>Share</h3>

    <p><span class="number">3</span>Now share this awesome app with your friends by clicking the "like" button, post it in your profile by clicking the "share it" button or send an invitation by email by clicking the "invite" button.</p>

  </div><!--column-->

</div><!--steps-->

<div id="footer">

  <div class="inner clearfix">

    <p class="left">Cool Badges © <?php echo date("Y") ?> | All Rights Reserved</p>

    <p class="right">Developed by <a href="http://www.volumens.com" target="_blank">Volumens</a></p>

  </div><!--inner-->

</div><!--footer-->

<!-- JS Scripts -->
<script type="text/javascript" src="js/jquery.min.js?ver=1.8.2"></script>
<script type="text/javascript" src="js/jquery.tools.min.js?ver=1.2.7"></script>
<script type="text/javascript" src="js/jquery.custom.js?ver=1.0"></script>

<script type="text/javascript">

	// Feed Dialog
	function feedDialog() {
		FB.ui({
			method: 'feed',
			link: 'http://apps.volumens.com/cool-badges/',
			picture: 'http://apps.volumens.com/cool-badges/images/thumbnail.png',
			name: 'Cool Badges',
			caption: 'Facebook Application',
			description: 'Add a cool badge to your profile picture and share it with your friends',
        });
	}

	// Requests Dialog
	function requestsDialog(){
		var receiverUserIds = FB.ui({
			method : 'apprequests',
			message: 'Add a cool badge to your profile picture and share it with your friends',
		});
	}

</script>

<div id="fb-root"></div>
<script type="text/javascript" src="https://connect.facebook.net/en_US/all.js"></script>
<script type="text/javascript">
	FB.init({
		appId  : '<?= $_CONFIG['appid'] ?>',
		status : true, // Check login status
		cookie : true, // Enable cookies to allow the server to access the session
		xfbml  : true  // Parse XFBML
	});
</script>

</body>
<!-- END body -->

</html>
<!-- END html -->
