<?php
$content='';
$response_status_code=0;
$response_message='';
$categories='';
$post_data;
if(isset($_POST['post_id'])){
    $servername = 'localhost';
    $username = 'root';
    $password = '';
    $dbname = 'geopostit';
    $con = new mysqli($servername, $username, $password, $dbname);
    if ($con->connect_error) {
        $status_code=2;
        $response_message='Error: failed to get edit window. Try again later';
    }else{
        $sql='SELECT post_id,share_option,title,comment,image,DATEDIFF(delete_date,post_creation_date) as date,category  FROM posts WHERE post_id='.$_POST['post_id'];
        $con->query("SET NAMES 'utf8'");
        $post_data=mysqli_query($con, $sql);
        if ($post_data !== false) {
                  $post_data=mysqli_fetch_all($post_data,MYSQLI_ASSOC);
                  $sql='SELECT category,icon FROM categories';
                  $cats=mysqli_query($con, $sql);
                  if ( $cats !== false) {
                            $cats=mysqli_fetch_all($cats,MYSQLI_ASSOC);
                            foreach($cats as $val){
                              $categories.="<option value='".$val['category']."'".(($post_data[0]['category']==$val['category'])?" selected":"").">".ucwords($val['category'])."</option>";
                            }
                  }
                  else {
                      $response_status_code=3;
                      $response_message='Error: failed to get edit window. Try again later' ;
                  }
                  $content="<div class='modal fade' id='editPostModal' role='dialog' aria-hidden='true'>
                                <form method='post' action='/edit_post.php' id='edit-form' class='container' enctype='multipart/form-data'>
                                <div class='modal-dialog'>
                                <div class='modal-content'>
                                    <div class='modal-header'>
                                    <button type='button' class='close' data-dismiss='modal' aria-hidden='true' class='btn-cancel-edit'>&times;</button>
                                    <h4 class='modal-title'>Edit Post</h4>
                                    </div>
                                    <div class='modal-body' style='padding:30px'>
                                <div class='row'>
                                    <div class='col-12'>
                                    <input type='hidden' name='post_id' value='".$post_data[0]['post_id']."'/>
                                    <input type='hidden' name='redirect' value='/post/".$post_data[0]['post_id']."'></input>
                                        <label>Title</label>
                                        <input type='text' class='form-control' name='title' value='".$post_data[0]['title']."'></input>
                                    </div>
                                </div>
                                <div class='row'>
                                    <div class='col-12'>
                                        <label>Comment</label>
                                        <textarea class='form-control'  name='comment' >".$post_data[0]['comment']."</textarea>
                                    </div>
                                </div>
                                <div class='row'>
                                <div class='col-12'>
                                <label>Photo</label>
                                    <img style='max-width:291px;height:auto' src='/report_images/".$post_data[0]['image']."'></img>
                                    <input type='file' class='form-control' name='image'></input>
                                </div>
                                </div>
                                <div class='row'>
                                <div class='col-12'>
                                <label>Category</label>
                                <select class='form-control' name='category'>
                                 ";
                                 $content.=$categories;
                                 $content.="</select>
                                </div>
                                </div>
                                <div class='row'>
                                        <div class='col-12'>
                                            <label>Share options</label>
                                            <select class='form-control' name='share_option'>
                                                <option value='1' ".(($post_data[0]['date']==1)?'selected':'').">Everyone</option>
                                                <option value='0' ".(($post_data[0]['share_option']==0)?'selected':'').">Only me</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class='row'>
                                        <div class='col-12'>
                                            <label> Post life span</label>
                                            <select class='form-control' name='life-span'>
                                                <option value='1' ".(($post_data[0]['date']==1)?"selected":"").">1 day</option>
                                                <option value='2'".(($post_data[0]['date']==2)?"selected":"").">2 days</option>
                                                <option value='3' ".(($post_data[0]['date']==3)?"selected":"").">3 day</option>
                                                <option value='7' ".(($post_data[0]['date']==7)?"selected":"").">1 Week</option>
                                                <option value='30' ".(($post_data[0]['date']==30)?"selected":"").">1 Month</option>
                                                <option value='365' ".(($post_data[0]['date']==365)?"selected":"").">1 Year</option>
                                                <option value='0' ".(($post_data[0]['date']>1000)?"selected":"").">Never</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                    <div class='modal-footer'>
                                    <button type='submit' class='btn btn-success' id='btn-save-edit'>Save changes</button>
                                    <button type='button' class='btn btn-default btn-cancel-edit' id='btn-cancel-edit' data-dismiss='modal'>Close</button> 
                                    </div>
                                </div>
                                </div>
                                </form>
                                </div>";
        }
        else {
            $response_status_code=3;
            $response_message='Error: failed to get edit window. Try again later' ;//'Error: '. $con->error;
        }
        $con->close();
    }
}
else{
    $response_status_code=1;
    $response_message='Error: no data';
}
if( $response_status_code==0){
   /* echo $post_data[0]['date'];
    die();*/
    echo $content;
}else{
$response['status_code']=$response_status_code;
$response['response_message']=$response_message;
echo json_encode($response);
}
die();
?>



    