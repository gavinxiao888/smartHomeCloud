<?php

/**
 * Description of third_login_model
 *�������ӿ���Ȩ����¼model
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
        $this->third =  $this->db->'third_login';//��������¼��
        $this->users = $this->db->'user_reg';//����Ŀ�û���
        $this->config->load("sina_conf");
        $this->sina= $this->config->item("sina_conf");
        
    }
    
    /**
      * @uses : ����΢����¼
      * @param : 
      * @return : $sina_url----��¼��ַ
      */
    public function sina_login(){
        $obj = new SaeTOAuthV2($this->sina['App_Key'],$this->sina['App_Secret']);
        $sina_url = $obj->getAuthorizeURL( $this->sina['WB_CALLBACK_URL'] );
        return $sina_url;
    }
    
    /**
      * @uses : ��¼��ͨ�����ص�codeֵ����ȡtoken��ʵ����Ȩ��ɣ�Ȼ���ȡ�û���Ϣ
      * @param : $code
      * @return : $user_message--�û���Ϣ
      */
    public function sina_callback($code){
      $obj = new SaeTOAuthV2($this->sina['App_Key'],$this->sina['App_Secret']);
      if (isset($code)) {
      $keys = array();
      $keys['code'] = $code;
      $keys['redirect_uri'] = $this->sina['WB_CALLBACK_URL'];
      try {
        $token = $obj->getAccessToken( 'code', $keys ) ;//�����Ȩ
      } catch (OAuthException $e) {
    }
      } 
      $c = new SaeTClientV2($this->sina['App_Key'], $this->sina['App_Secret'], $token['access_token']);
      $ms =$c->home_timeline();
      $uid_get = $c->get_uid();//��ȡu_id
      $uid = $uid_get['uid'];
      $user_message = $c->show_user_by_id($uid);//��ȡ�û���Ϣ
      return $user_message;
    }
    
    /**
      * @uses : ��ѯ��������¼��
      * @param : $where
      * @return : ��������¼�û���¼�����
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
      * @uses : sina---��ѯ�û���͵�������¼��
      * @param : $where
      * @return : ��������¼�û���¼�����
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
      * @uses : qq---��ѯ�û���͵�������¼��
      * @param : $where
      * @return : ��������¼�û���¼�����
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
      * @uses : ���û��͵�������¼����Ϣ��
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
            $this->session->unset_userdata('sina_id');//ע��
            $this->session->unset_userdata('qq_id');//ע��
        }
        return $resb;
    }
}






