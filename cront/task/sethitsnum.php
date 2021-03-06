<?php

define('WEKIT_VERSION',1);
$root=dirname(__FILE__).'/';
require_once($root.'../grab/db.class.php');
require_once($root.'../../src/library/base/PwBaseRediscache.php');


$model = new model();

$redis = new PwBaseRediscache();
$keys = $redis->keys('emuhitslog:*');

//var_dump($keys);exit;

foreach($keys as $k){
  $id = explode(':',$k);
  $id = array_pop($id);
//var_dump($id);exit;
  $model->setTopicHitsLog($id);
  $redis->delete($k);
  usleep(1000);
}

echo "\n===$id=== Update Emule Topic Hit Log OK! ========\n";

class model{
  protected $db;

  function __construct(){
    $this->db = new DB_MYSQL();
  }
  function setTopicHitsLog($id){
    if(!$id){
       return false;
    }
    $sql = sprintf('UPDATE %s SET `hits`=`hits`+1 WHERE `id`=%d LIMIT 1',$this->db->getTable('emule_article'), $id);
    $this->db->query($sql);
    return true;
  }
}
