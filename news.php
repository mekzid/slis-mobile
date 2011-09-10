<?php
  error_reporting(E_ALL);

  $ch = curl_init("http://slisweb.sjsu.edu/rss/slis-news.xml");
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

  $feed_err[0] = false;

  if ($data = curl_exec($ch)) {
    curl_close($ch);
    $doc = new SimpleXmlElement($data);
    $news = $doc->channel->item;
  } else {
    /* TODO: Log error messages somewhere useful. */
    $feed_err[0] = true;
    $feed_err[1] = "curl_exec failed.";
  }
?>

<!DOCTYPE html> 
<html> 
<head> 
  <title>SJSU SLIS</title> 
  <meta charset="utf-8" /> 
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link rel="stylesheet" href="http://code.jquery.com/mobile/1.0b2/jquery.mobile-1.0b2.min.css" />
  <link rel="stylesheet" href="/assets/css/master.css" />
  <script type="text/javascript" src="http://code.jquery.com/jquery-1.6.2.min.js"></script>
  <script type="text/javascript" src="http://code.jquery.com/mobile/1.0b2/jquery.mobile-1.0b2.min.js"></script>
  <script type="text/javascript" src="assets/js/application.js"></script>
</head> 
<body>
  <div id="news-page" data-role="page">
    <div id="logo-wrapper">
      <a href="index.html"><img id="logo" src="/assets/images/slislogo.png" /></a>
    </div>
    <div data-role="header" data-theme="b">
      <a href="index.html" data-role="button" data-icon="back">Back</a>
      <h1>News</h1>
    </div>
    <div data-role="content" data-theme="b"> 
<?php
  if ($feed_err[0]) {
    echo '<p class="error">Sorry, there was an error retreiving the latest news.  Please try again later.</p>';
?>
<?php
  } else {
    echo '<ul data-role="listview" class="news">';

    /* Show latest 5 news items. */
    for ($i = 0; $i <= 4 && $i <= count($news); $i++) {
      echo '<li><h2>' . $news[$i]->title . "</h2>";
      echo '<p>' . $news[$i]->pubDate . '<br />';
      echo $news[$i]->description . '<br />';
      echo '<a rel="external" href="' . $news[$i]->link . '">more details >></a>';
      echo "</p></li>";
    }
    echo '</ul>';
  }
?>
    </div>
    <div id="footer">
      <p>One Washington Square<br />San Jos&eacute;, California USA, 95192-0029<br />Phone: 408-924-2490<br /><a rel="external" href="http://slisweb.sjsu.edu/email/email.php?fac=webmaster">Contact the webmaster</a></p>
    </div>
  </div>
</body>
</html>

