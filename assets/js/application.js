/* Fixup news feed links for JQuery Mobile. */
$('#news-page').live('pagecreate', function(event) {
  $(".news a").attr('rel', 'external');
});
