<?php
/**
 * 通用页面
 */
defined('InHG') or exit('Access Invalid!');

class commonControl extends SystemControl{
	public function __construct(){
		parent::__construct();
	}

	/**
	 * 图片上传
	 *
	 */
	public function pic_uploadOp(){
		if (chksubmit()){
			//上传图片
			$upload = new UploadFile();
			$upload->set('thumb_width',	500);
			$upload->set('thumb_height',499);
			// $upload->set('thumb_ext','_small');
			$upload->set('max_size',C('image_max_filesize')?C('image_max_filesize'):1024);
			$upload->set('ifremove',true);
			$upload->set('default_dir',$_GET['uploadpath']);

			if (!empty($_FILES['_pic']['tmp_name'])){
				$result = $upload->upfile('_pic');
				if ($result){
					exit(json_encode(array('status'=>1,'url'=>UPLOAD_SITE_URL.'/'.$_GET['uploadpath'].'/'.$upload->thumb_image)));
				}else {
					exit(json_encode(array('status'=>0,'msg'=>$upload->error)));
				}
			}
		}
	}

    /**
     * 音频或图片上传
     * @author wangjiang
     */
    public function audio_uploadOp(){
        if (chksubmit()){
            //上传图片
            $upload = new UploadFile();
            $upload->set('allow_type',array_merge($upload->get('allow_type'),[
                'wav',
                'mp3',
                'wma',
                'ogg',
                'acc',
                'aac',
                'ape',
            ]) );
            $upload->set('max_size',C('audio_max_filesize')?C('audio_max_filesize'):2048);
            $upload->set('default_dir',$_GET['uploadpath']);

            if (!empty($_FILES['comment']['tmp_name'])){

                $result = $upload->upfile('comment');
                if ($result){
                    //将图片同步上传到七牛云上 //TODO
//                    $detail_img_path = $_GET['uploadpath'].'/'.$upload->file_name;
//                    Qiniu::make()->upload(BASE_UPLOAD_PATH.'/'.$detail_img_path, $detail_img_path);
                    exit(json_encode(array(
                        'status'=>1,
                        'url'=>'/'.$_GET['uploadpath'].'/'.$upload->file_name,
                        'file_name'=>$_FILES['comment']['name'],
                        )
                    ));
                }else {
                    exit(json_encode(array('status'=>0,'msg'=>$upload->error)));
                }
            }
        }
    }






	 /**
     * 音频或图片上传
     * @author wangjiang
     */
    public function audio_upload2Op(){
        if (chksubmit()){
            //上传图片
            $upload = new UploadFile();
            $upload->set('allow_type',array_merge($upload->get('allow_type'),[
                'wav',
                'mp3',
                'wma',
				'wav',
                'ogg',
                'acc',
                'aac',
                'ape',
            ]) );
			
            $upload->set('max_size',C('audio_max_filesize')?C('audio_max_filesize'):2048);
            $upload->set('default_dir',$_GET['uploadpath']);
            if (!empty($_FILES['_pic']['tmp_name'])){

                $result = $upload->upfile('_pic');
                if ($result){
                    //将图片同步上传到七牛云上 //TODO
//                    $detail_img_path = $_GET['uploadpath'].'/'.$upload->file_name;
//                    Qiniu::make()->upload(BASE_UPLOAD_PATH.'/'.$detail_img_path, $detail_img_path);

					$file   =   [
						'size' => $_FILES['_pic']['size'],
						'type' => $_FILES['_pic']['type'],
						'name' => $_FILES['_pic']['name'],
						'uploadpath' => $_GET['uploadpath'],
						'url'        => '/'.$_GET['uploadpath'].'/'.$upload->file_name
 					];
					
					$ret   = Model('hc_images')->insert($file);

					if($ret)
					{
						exit(json_encode(array(
							'status'   => 1,
							'url'      => UPLOAD_SITE_URL .'/'.$_GET['uploadpath'].'/'.$upload->file_name,
							'file_name'=> $_FILES['_pic']['name'],
							'image_id' => $ret,
							)
						));
					}else{
						  exit(json_encode(array('status'=>0,'msg'=>'上传图片插入数据库错误：'.$ret)));
					}
                     
                }else {
                    exit(json_encode(array('status'=>0,'msg'=>$upload->error)));
                }
            }
        }
    }


	/**
     * 图片上传
     * @author wangjiang
     */
    public function pic_upload2Op(){
        if (chksubmit()){
            //上传图片
            $upload = new UploadFile();
            // $upload->set('max_size',C('audio_max_filesize')?C('audio_max_filesize'):2048);
            $upload->set('default_dir',$_GET['uploadpath']);
            if (!empty($_FILES['_pic']['tmp_name'])){

                $result = $upload->upfile('_pic');
                if ($result){
                    //将图片同步上传到七牛云上 //TODO
//                    $detail_img_path = $_GET['uploadpath'].'/'.$upload->file_name;
//                    Qiniu::make()->upload(BASE_UPLOAD_PATH.'/'.$detail_img_path, $detail_img_path);
					// 存储后台审批图片
					$image = [
						'size' => $_FILES['_pic']['size'],
						'type' => $_FILES['_pic']['type'],
						'name' => $_FILES['_pic']['name'],
						'uploadpath' => $_GET['uploadpath'],
						'url'        => '/'.$_GET['uploadpath'].'/'.$upload->file_name
 					];
					
					$ret   = Model('hc_images')->insert($image);

					if($ret)
					{
						exit(json_encode(array(
							'status'   => 1,
							'url'      => '/'.$_GET['uploadpath'].'/'.$upload->file_name,
							'file_name'=> $_FILES['_pic']['name'],
							'image_id' => $ret,
							)
						));
					}else{
						  exit(json_encode(array('status'=>0,'msg'=>'上传图片插入数据库错误：'.$ret)));
					}
                    
                }else {
                    exit(json_encode(array('status'=>0,'msg'=>$upload->error)));
                }
            }
        }
    }

	/**
	 * 图片裁剪
	 *
	 */
	public function pic_cutOp(){
		Language::read('admin_common');
		$lang = Language::getLangContent();
		import('function.thumb');
		if (chksubmit()){
			$thumb_width = $_POST['x'];
			$x1 = $_POST["x1"];
			$y1 = $_POST["y1"];
			$x2 = $_POST["x2"];
			$y2 = $_POST["y2"];
			$w = $_POST["w"];
			$h = $_POST["h"];
			$scale = $thumb_width/$w;
			$src = str_ireplace(UPLOAD_SITE_URL,BASE_UPLOAD_PATH,$_POST['url']);
			if (!empty($_POST['filename'])){
// 				$save_file2 = BASE_UPLOAD_PATH.'/'.$_POST['filename'];
				$save_file2 = str_ireplace(UPLOAD_SITE_URL,BASE_UPLOAD_PATH,$_POST['filename']);
			}else{
				$save_file2 = str_replace('_small.','_sm.',$src);
			}
			$cropped = resize_thumb($save_file2, $src,$w,$h,$x1,$y1,$scale);
			@unlink($src);
			$pathinfo = pathinfo($save_file2);
			exit($pathinfo['basename']);
		}
		$save_file = str_ireplace(UPLOAD_SITE_URL,BASE_UPLOAD_PATH,$_GET['url']);
		$_GET['resize'] = $_GET['resize'] == '0' ? '0' : '1';
		Tpl::output('height',get_height($save_file));
		Tpl::output('width',get_width($save_file));
		Tpl::showpage('common.pic_cut','null_layout');
	}

	/**
	 * 查询每月的周数组
	 */
	public function getweekofmonthOp(){
	    import('function.datehelper');
	    $year = $_GET['y'];
	    $month = $_GET['m'];
	    $week_arr = getMonthWeekArr($year, $month);
	    echo json_encode($week_arr);
	    die;
	}
}