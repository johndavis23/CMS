</div>
</div>
</div>
</div>
</div>
</div>

<!-- Mainly scripts
<script src="/pestblog/Barebones-SMF/Media/js/plugins/metisMenu/jquery.metisMenu.js"></script>
<script src="/pestblog/Barebones-SMF/Media/js/plugins/slimscroll/jquery.slimscroll.min.js"></script>

<!-- Custom and plugin javascript
<script src="/pestblog/Barebones-SMF/Media/js/inspinia.js"></script>
<script src="/pestblog/Barebones-SMF/Media/js/plugins/pace/pace.min.js"></script>
-->
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
<script src="/pestblog/Barebones-SMF/Media/js/bootstrap.min.js"></script>


<!-- Load WysiBB JS and Theme -->

<?php
use App\Util\UrlUtils;
use App\Classes\Registry;
?>
<script src="<?= UrlUtils::getAssetsUrl() ?>vendor/wysibb/jquery.wysibb.min.js"></script>
<link rel="stylesheet" href="<?= UrlUtils::getAssetsUrl() ?>vendor/wysibb/theme/default/wbbtheme.css" />
<script>

</script>

<?php


    Registry::get()->printJS();


?>

</body>

</html>