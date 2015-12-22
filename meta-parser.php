<?php
/**
 * Created by PhpStorm.
 * User: gabrielgagno
 * Date: 11/27/15
 * Time: 9:26 AM
 */

include('MetaParser.php');

use Parser\MetaParser;


$conn = mysqli_connect('localhost', 'root', '', 'nutch');
if(!$conn) {
    die('Not connected : ' . mysqli_error($conn));
}
$selectQuery = "select * from webpage where content is not null and baseUrl is not null";

$results = $conn->query($selectQuery);
$curlUrl = 'http://50.18.169.52/globe_search/webpage';
while($row = mysqli_fetch_assoc($results)) {
    if($row['content']!=null || !empty($row['content'])) {
        $meta = MetaParser::parseMetaTagsFromHtmlString($row['content'], array('description'));
        if(isset($meta['description'])) {
            $query = "update webpage set aq_md_description = \"".$meta['description']."\"where id =\"".$row['id']."\"";
            $conn->query($query);
        }
    }
}

MetaParser::addSearchString();
