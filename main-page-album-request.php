<?php
/**
 * Template name:page-main VK api examples
 * 
 * Made by Mad Scientist, thank you for using
 * 
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package vk_posts
 */
include 'group_req.php';

//saving photo in local storage (folder images) !!!!!!!!!!!!!!!!!!!!!!!!!	
$total = count($_FILES['attachment']['name']);
	// Loop through each file
for($i=0; $i<$total; $i++) {
	  //Get the temp file path
	  $tmpFilePath = $_FILES['attachment']['tmp_name'][$i];
	  //Make sure we have a filepath
	  if ($tmpFilePath != ""){
		//Setup our new file path
		$newFilePath = __DIR__ . '/images/' . $_FILES['attachment']['name'][$i];
		//print_r($newFilePath);
		$newFilePath2 =array(__DIR__ . '/images/' . $_FILES['attachment']['name'][$i]);
	  }
}

//using CURL for saving photo in post_data array (most interesting for VK api example)
	$var1[0]='file1';
	$var1[1]='file2';
	$var1[2]='file3';
	$var1[3]='file4';
	if($total>=1){
		for($i=0;$i<$total;$i++){
			 $post_data[$var1[$i]]=new CURLFile(__DIR__ . '/images/' . $_FILES['attachment']['name'][$i]);
		}
	}

	print_r($total);
	print_r($post_data);
	?>
	<!-- HTML form-->
<head>
      <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
</head>
<body>
	<?php if (!empty($error)): ?> 
	<?= $error ?><?php elseif (!empty($result)): ?> 
	<?= $result ?><?php endif; ?><br>
    <section class="content">
        <div class="container">
            <div class="content-container-flex">

                <div class="content-container">
                  Отправка фотографий в альбом 
                <form action="" method="post" enctype="multipart/form-data">
                            <input type="text" name="messagelink"  placeholder="Ссылка на альбом"/>
                            <input type="file" name="attachment[]" multiple="multiple" id="att" > <input type="submit"name="ok_album" >        
                </form>
            </div>
        </div>
    </section>
<?php print_r($post_data); ?>
</body>
	<?php

$string=$_POST['messagelink'];
  $pieces = explode(",", $string);
   foreach ($pieces as $line) {
	   //exmple https://vk.com/album-11111111_11111111
    $group_id= mb_strimwidth($line, 21, 40);
    $pieces2 = explode("_", $group_id);

$token = 'Your token for standalone app ';//!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
$group_id = $pieces2[0];
$album_id = $pieces2[1];
$v = '5.62'; //версия vk api
 $url = file_get_contents("https://api.vk.com/method/photos.getUploadServer?album_id=".$album_id."&group_id=".$group_id."&v=".$v."&access_token=".$token);
 $url = json_decode($url)->response->upload_url;
// //echo $url;
// //// отправка post картинки
 $ch = curl_init();
 curl_setopt($ch, CURLOPT_URL, $url);
 curl_setopt($ch, CURLOPT_POST, true);
 curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
 curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
 curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
 curl_setopt($ch, CURLOPT_POSTFIELDS,$post_data);
 $result = json_decode(curl_exec($ch),true);
 print_r($result);
 $params = array(
 'v'=> '5.00',
 'access_token'=>$token,
 'album_id'=>$album_id,
 'group_id'=>$group_id,
 'hash'=>$result['hash'],
 'server'=>$result['server'],
 'photos_list'=>$result['photos_list'],
 'caption'=>$caption
 );
//sanding 
 $safe = file_get_contents("https://api.vk.com/method/photos.save?".http_build_query($params));
 $safe = json_decode($safe,true);
 print_r($safe);

}?>


