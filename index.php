<?php
include './vendor/autoload.php';

$client = new Goutte\Client();

//$crawler = $client->request('GET', 'http://blog.csdn.net/StarParker/article/list/1');
$crawler = $client->request('GET', 'http://blog.csdn.net/StarParker');

$page_count = array_pop(explode('/',$crawler->filter('#papelist > a')->last()->attr('href')));


$urls = [];
for($index=1 ; $index<=$page_count ; $index++){
	$crawler = $client->request('GET', 'http://blog.csdn.net/StarParker/article/list/' . $index);
	$urls = array_merge($urls, $crawler->filter('#article_list')->filter('.link_title > a')->extract('href'));
}

$list = [];
foreach($urls as $key => $url){
	$dom = $crawler = $client->request('GET', 'http://blog.csdn.net/' . $url);
	$list[$key]['title']=trim($dom->filter('.link_title > a')->text());
	try{
		$list[$key]['category']=$dom->filter('.link_categories > a')->text();
	}catch(Exception $e){
		$list[$key]['category']=null;
	}
	$list[$key]['postdate']=$dom->filter('.link_postdate')->text();
	$list[$key]['view']=$dom->filter('.link_view')->text();
	$list[$key]['content']=$dom->filter('#article_content')->html();

echo $list[$key]['title']."\n";
}

die(var_dump($list));

?>
