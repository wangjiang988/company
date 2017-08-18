<table border="1" width="60%" id="rmcontent">
	<tr hight="20px" align ="center">
		<td width="15%">使用场合</td>
		<td width="45%">文件资料</td>
		<td width="15%">数量</td>
		<td>文件格式</td>
	</tr>
<?php if(count($output['file']['one']) < 2) {?>
	<tr >		
		<td>提车人非车主身份验证</td>
        <td><?php echo $output['file']['one'][0]['title'];?></td>
    <?php if ($output['file']['one'][0]['isself'] == 1 ) { ?>
     <td>√</td>
    <? }else {?>
		<td><?php echo $output['file']['one'][0]['num']; }?></td>
		<td><a href="<?php echo $output['file']['one'][0]['file_url'];?>"><?php echo $output['file']['one'][0]['file_url'];?></a></td>  
	</tr>
<?php } else {?>
	<tr>
		<td rowspan="<?php echo count($output['file']['one'])+1;?>">提车人非车主身份验证</td>    
    <?php foreach($output['file']['one'] as $ones=>$one) {?>
       <tr>
        <td><?php echo $one['title']?></td>
      <?php if ($one['isself'] == 1 ) { ?>
     <td>√</td>
    <? }else {?>
    <td><?php echo $one['num']; }?></td>
		<td><a href="<?php echo BASE_FILE_PATH.$one['file_url'];?>" target="_blank"><?php echo $one['file_url'];?></td>
       </tr>
   <?php }?>
<?php } ?>


<?php if(count($output['file']['two']) < 2) {?>
	<tr >		
		<td>投保</td>
        <td><?php echo $output['file']['two'][0]['title'];?></td>
    <?php if ($output['file']['two'][0]['isself'] == 1 ) { ?>
     <td>√</td>
    <? }else {?>
    <td><?php echo $output['file']['two'][0]['num']; }?></td>
		<td><a href="<?php echo BASE_FILE_PATH.$output['file']['two'][0]['file_url'];?>" target="_blank"><?php echo $output['file']['two'][0]['file_url'];?></td> 
	</tr>
<?php } else {?>
	<tr>
		<td rowspan="<?php echo count($output['file']['two'])+1;?>">投保</td>    
    <?php foreach($output['file']['two'] as $twos=>$two) {?>
       <tr>
        <td><?php echo $two['title']?></td>
     <?php if ($two['isself'] == 1 ) { ?>
     <td>√</td>
    <? }else {?>
    <td><?php echo $two['num']; }?></td>
		<td><a href="<?php echo BASE_FILE_PATH.$two['file_url']?>" target="_blank"><?php echo $two['file_url']?></a></td>
       </tr>
   <?php }?>
<?php } ?>

	<?php if(count($output['file']['three']) < 2) {?>
	<tr >		
		<td>上牌</td>
        <td><?php echo $output['file']['three'][0]['title'];?></td>
		<?php if ($output['file']['three'][0]['isself'] == 1 ) { ?>
     <td>√</td>
    <? }else {?>
    <td><?php echo $output['file']['three'][0]['num']; }?></td>
		<td><a href="<?php echo BASE_FILE_PATH.$output['file']['three'][0]['file_url'];?>" target="_blank"><?php echo $output['file']['three'][0]['file_url'];?></td>
	</tr>
<?php } else {?>
	<tr>
		<td rowspan="<?php echo count($output['file']['three'])+1;?>">上牌</td>    
    <?php foreach($output['file']['three'] as $threes=>$three) {?>
       <tr>
        <td><?php echo $three['title']?></td>
    <?php if ($three['isself'] == 1 ) { ?>
     <td>√</td>
    <? }else {?>
    <td><?php echo $three['num']; }?></td>
		<td><a href="<?php echo BASE_FILE_PATH.$three['file_url']?>" target="_blank"><?php echo $three['file_url']?></a></td>
       </tr>
   <?php }?>
<?php } ?>


		<?php if(count($output['file']['four']) < 2) {?>
	<tr >		
		<td>上临时牌</td>
        <td><?php echo $output['file']['four'][0]['title'];?></td>
		<?php if ($output['file']['four'][0]['isself'] == 1 ) { ?>
     <td>√</td>
    <? }else {?>
    <td><?php echo $output['four']['two'][0]['num']; }?></td>
		<td><a href="<?php echo BASE_FILE_PATH.$output['file']['four'][0]['file_url'];?>" target="_blank"><?php echo $output['file']['four'][0]['file_url'];?></td> 
	</tr>
<?php } else {?>
	<tr>
		<td rowspan="<?php echo count($output['file']['four'])+1;?>">上临时牌</td>    
    <?php foreach($output['file']['four'] as $fours=>$four) {?>
       <tr>
        <td><?php echo $four['title']?></td>
		  <?php if ($four['isself'] == 1 ) { ?>
     <td>√</td>
    <? }else {?>
    <td><?php echo $four['num']; }?></td>
		<td><a href="<?php echo BASE_FILE_PATH.$four['file_url']?>" target="_blank"><?php echo $four['file_url']?></a></td>
       </tr>
   <?php }?>
<?php } ?>

		<?php if(count($output['file']['five']) < 2) {?>
	<tr >		
		<td>非卡主刷卡</td>
        <td><?php echo $output['file']['five'][0]['title'];?></td>
		<?php if ($output['file']['five'][0]['isself'] == 1 ) { ?>
     <td>√</td>
    <? }else {?>
    <td><?php echo $output['file']['five'][0]['num']; }?></td>
		<td><a href="<?php echo BASE_FILE_PATH.$output['file']['five'][0]['file_url'];?>" target="_blank"><?php echo $output['file']['five'][0]['file_url'];?></td>  
	</tr>
<?php } else {?>
	<tr>
		<td rowspan="<?php echo count($output['file']['five'])+1;?>">非卡主刷卡</td>    
    <?php foreach($output['file']['five'] as $fives=>$five) {?>
       <tr>
        <td><?php echo $five['title']?></td>
		    <?php if ($five['isself'] == 1 ) { ?>
     <td>√</td>
    <? }else {?>
    <td><?php echo $five['num']; }?></td>
		<td><a href="<?php echo BASE_FILE_PATH.$five['file_url']?>" target="_blank"><?php echo $five['file_url']?></a></td>
       </tr>
   <?php }?>
<?php } ?>
</table>