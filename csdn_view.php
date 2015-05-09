<?php
include './vendor/autoload.php';

$user_name = 'starparker';
$times = 2;


$client = new Goutte\Client();
$crawler = $client->request('GET', 'http://blog.csdn.net/' . $user_name );

$page_count = array_pop(explode('/',$crawler->filter('#papelist > a')->last()->attr('href')));


$urls = [];
for($index=1 ; $index<=$page_count ; $index++){
	$crawler = $client->request('GET', 'http://blog.csdn.net/' . $user_name . '/article/list/' . $index);
	$urls = array_merge($urls, $crawler->filter('#article_list')->filter('.link_title > a')->extract('href'));
}

for($i=1 ; $i <= $times ; $i++){
	foreach($urls as $key => $url){
		$client->request('GET', 'http://blog.csdn.net/' . $url);
		echo "阅读第[$i]次[($key+1)]篇<br/>\n";
	}
}
die("阅读量已刷完!<br/>\n");


//其实 ab -n 1000 -c 100 $url 更快只是会被屏蔽
?>
