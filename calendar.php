<?php
  error_reporting(E_ALL);

  require_once 'Zend/Loader.php';
  Zend_Loader::loadClass('Zend_Gdata');
  Zend_Loader::loadClass('Zend_Gdata_Calendar');

  /* No authentication necessary since we're accessing a public feed. */
  $gdataCal = new Zend_Gdata_Calendar();
  $query = $gdataCal->newEventQuery();
  $query->setUser('sliscalendar@gmail.com');
  $query->setProjection('basic');

  /* Get next 30 days of events. */
  $now = date('Y-n-j');
  $later = date('Y-n-j', strtotime('+1 month'));
  $query->setStartMin($now);
  $query->setStartMax($later);

  $query->setOrderby('starttime');
  $query->setSortOrder('ascending');

  try {
    $feed_err[0] = false;
    $feed = $gdataCal->getCalendarEventFeed($query);
  } catch (Zend_Gdata_App_Exception $e) {
    /* TODO: Log error messages somewhere useful. */
    $feed_err[0] = true;
    $feed_err[1] = $e->getMessage();
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
  <div data-role="page">
    <div id="logo-wrapper">
      <a href="index.html"><img id="logo" src="/assets/images/slislogo.png" /></a>
    </div>
    <div data-role="header" data-theme="b">
      <a href="index.html" data-role="button" data-icon="back">Back</a>
      <h1>Calendar</h1>
    </div>
    <div data-role="content" data-theme="b"> 
<?php
  if ($feed_err[0]) {
    echo '<p class="error">Sorry, there was an error retreiving calendar events.  Please try again later.</p>';
?>
<?php
  } else {
    echo '<ul data-role="listview" class="events">';
    echo '<li data-role="list-divider">Next 30 Days</li>';
    foreach ($feed as $item) {
      echo '<li><h2>' . $item->getTitle() . "</h2>";
      echo '<p>' . $item->getSummary() . ' ';
      $event_link = $item->getLink();
      echo '</p><p><a rel="external" href="' . $event_link[0]->getHref() .
           '">more details >></a>';
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

