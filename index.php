<?php
use Zend\Db\ResultSet\HydratingResultSet;
include __DIR__ . '/vendor/autoload.php';


$result_set = new HydratingResultSet($hydrator,new CmsModel());
$cms_resolver = new DbResolver('cms_content',$db_adapter,$result_set);
$cms_renderer = new CmsRenderer($cms_resolver);
$cms_helper = new CmsHelper($cms_renderer,$storage);

/*
 * 1. Create block in admin in production and optionally in dev. Will fallback to placeholder text when local db doesn't have a ref.
 * 2. Use it in page with slug (id) given in admin
 */
echo $cms_helper('homepage.leftcol.title');