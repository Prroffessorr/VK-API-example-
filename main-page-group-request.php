<?php
$total = count($_FILES['files']['name']);
// Loop through each file
for($i=0; $i<$total; $i++) {
  //Get the temp file path
  $tmpFilePath = $_FILES['files']['tmp_name'][$i];
  //Make sure we have a filepath
 if ($tmpFilePath != ""){
    //Setup our new file path
	$newFilePath = __DIR__ . '/images/' . $_FILES['attachment']['name'][$i];
	move_uploaded_file($tmpFilePath, $newFilePath);
  }
  }
}
    $var1[0]='file1';
	$var1[1]='file2';
	$var1[2]='file3';
    $var1[3]='file4';
	if($total>=1){
		for($i=0;$i<$total;$i++){
			 $post_datas[$var1[$i]]=new CURLFile(__DIR__ . '/images/' . $_FILES['files']['name'][$i]);
		}
	}
	print_r($total);
	print_r($post_datas);
    // separated line to separate each link
    $string_link=$_POST['messagelink'];
        $pieces_group = explode(",", $string_link);

foreach ($pieces_group as $line) { 
    $access_token = 'token of your Standlone app';//!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
    //exple https://vk.com/club111111111
    $group_id= mb_strimwidth($line, 19, 30);
    if (!empty($_POST['messagetext'])){
    $message=$_POST['messagetext'];
    } 
//Getting vk server to upload image
    $server = file_get_contents('https://api.vk.com/method/photos.getWallUploadServer?group_id=' . $group_id . '&access_token=' . $access_token . '&v=5.00');
    $server = json_decode($server);
     
    if (!empty($server->response->upload_url)) {
        // Sending the image to the server
        if (function_exists('curl_file_create')) {
            $curl_file = curl_file_create($image);
        } else {
            $curl_file = '@' . $image;
        }
     
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $server->response->upload_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_datas);
        $upload = curl_exec($ch);
        curl_close($ch);
     
        $upload = json_decode($upload);
        if (!empty($upload->server)) {
            // Сохранение фото в группе.
            $save = file_get_contents('https://api.vk.com/method/photos.saveWallPhoto?group_id=' . $group_id . '&server=' . $upload->server . '&photo=' . stripslashes($upload->photo) . '&hash=' . $upload->hash . '&access_token=' . $access_token . '&v=5.00');
            $save = json_decode($save);
            
            if (!empty($save->response[0]->id)) {
                // Send message.
                $params = array(
                    'v'            => '5.00',
                    'access_token' => $access_token,
                    'owner_id'     => '-' . $group_id, 
                    'from_group'   => '1', 
                    'message'      => $message,
                    'attachments'  =>array(
                    'file1'=>'photo' . $save->response[0]->owner_id . '_' . $save->response[0]->id,
                    'file2'=>'photo' . $save->response[1]->owner_id . '_' . $save->response[1]->id,
                    'file3'=>'photo' . $save->response[2]->owner_id . '_' . $save->response[2]->id,
                    'file4'=>'photo' . $save->response[3]->owner_id . '_' . $save->response[3]->id) 
                );    
                file_get_contents('https://api.vk.com/method/wall.post?' . http_build_query($params));
            }
        }
    }
}
?>

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
                  Отправка постов в группу  
                <form action="" method="post" enctype="multipart/form-data">
                            <input type="text" name="messagelink"  placeholder="Ссылка на группу"/>
                            <input  type="text" name="messagetext" cols="30" class="text" placeholder="Введите текст....."/> 
                            <input type="file" name="files[]" multiple="multiple" > <input type="submit"name="ok_group" >        
                </form>
            </div>
        </div>
    </section>
<?php print_r($save); ?>
<br>
<br>
</body>
<script>
   $('#group').on('change', function(){
    console.log(this.files.length);
	if(this.files.length>4){
		alert( "no more than four images" );
		document.getElementById("group").value = "";
	}
});
   </script>

