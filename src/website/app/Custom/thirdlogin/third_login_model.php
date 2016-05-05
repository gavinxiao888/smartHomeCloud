<?php

/**
 * Description of third_login_model
 *第三方接口授权，登录model
 * @author 
 */
class third_login_model extends CI_Model{
    //put your code here
    private $sina=array();
    private $qq  =array();
    private $users ='';
    private $third='';
    public function __construct() {
        parent::__construct();
//        $this->l = DIRECTORY_SEPARATOR;
        $this->load->database();   
        $this->load->library('session');
        include_once APPPATH."/libraries"."/saetv2.ex.class.php";
        $this->third =  $this->db->'third_login';//第三方登录表
        $this->users = $this->db->'user_reg';//本项目用户表
        $this->config->load("sina_conf");
        $this->sina= $this->config->item("sina_conf");
        
    }
    
    /**
      * @uses : 新浪微博登录
      * @param : 
      * @return : $sina_url----登录地址
      */
    public function sina_login(){
        $obj = new SaeTOAuthV2($this->sina['App_Key'],$this->sina['App_Secret']);
        $sina_url = $obj->getAuthorizeURL( $this->sina['WB_CALLBACK_URL'] );
        return $sina_url;
    }
    
    /**
      * @uses : 登录后，通过返回的code值，获取token，实现授权完成，然后获取用户信息
      * @param : $code
      * @return : $user_message--用户信息
      */
    public function sina_callback($code){
      $obj = new SaeTOAuthV2($this->sina['App_Key'],$this->sina['App_Secret']);
      if (isset($code)) {
      $keys = array();
      $keys['code'] = $code;
      $keys['redirect_uri'] = $this->sina['WB_CALLBACK_URL'];
      try {
        $token = $obj->getAccessToken( 'code', $keys ) ;//完成授权
      } catch (OAuthException $e) {
    }
      } 
      $c = new SaeTClientV2($this->sina['App_Key'], $this->sina['App_Secret'], $token['access_token']);
      $ms =$c->home_timeline();
      $uid_get = $c->get_uid();//获取u_id
      $uid = $uid_get['uid'];
      $user_message = $c->show_user_by_id($uid);//获取用户信息
      return $user_message;
    }
    
    /**
      * @uses : 查询第三方登录表
      * @param : $where
      * @return : 第三方登录用户记录结果集
      */
    public function select_third($where) {
        $result = false;
        $this->db->select();
        $this->db->from($this->third);
        $this->db->where($where);
        $query = $this->db->get();
        if($query){
            $result = $query->row_array();
        }
        return $result;
    }
    
    /*-
      * @uses : sina---查询用户表和第三方登录表
      * @param : $where
      * @return : 第三方登录用户记录结果集
      */
    public function select_user_name($where) {
        $field ="user.id,user.password,user.username,utl.*";
        $sql = "select {$field} from {$this->third} as utl "
                ." left join {$this->users} as user on user.id=utl.user_id"
                . " where utl.sina_id={$where}";
        $query = $this->db->query($sql);
        $result = $query->row_array();
        return $result;
    }
    
    /**
      * @uses : qq---查询用户表和第三方登录表
      * @param : $where
      * @return : 第三方登录用户记录结果集
      */
    public function select_user_qqname($where) {
        $field ="user.id,user.password,user.username,utl.*";
        $sql = "select {$field} from {$this->third} as utl "
                ." left join {$this->users} as user on user.id=utl.user_id"
                . " where utl.qq_id='{$where}'";
        $query = $this->db->query($sql);
        $result = $query->row_array();
        return $result;
    }

    
    /**
      * @uses : 将用户和第三方登录表信息绑定
      * @param : $datas
      * @return : 
      */
    public function binding_third($datas) {
        if (!is_array($datas)) show_error ('wrong param');
        if($datas['sina_id']==0 && $datas['qq_id']==0)  return;
        
        $resa ='';
        $resb ='';
        $resa = $this->select_third(array("user_id"=>$datas['user_id']));
        $temp =array(
            "user_id"=>$datas['user_id'],
            "sina_id"=>$resa['sina_id']!=0 ? $resa['sina_id'] : $datas['sina_id'],
            "qq_id"  => $resa['qq_id']!=0 ? $resa['qq_id'] : $datas['qq_id'],
        );
        if($resa){
            $resb = $this->db->update($this->third, $temp,array("user_id"=>$datas['user_id']));
        }else{
            $resb = $this->db->insert($this->third,$temp);
        }
        if($resb) {
            $this->session->unset_userdata('sina_id');//注销
            $this->session->unset_userdata('qq_id');//注销
        }
        return $resb;
    }
}






