<?php

defined('InHG') or exit('Access Invalid!');

class CacheRedis extends Cache {
	private $config;
	private $connected;
	private $type;
	private $prefix;
    public function __construct() {
    	$this->config = C('redis');
    	if (empty($this->config['slave'])) $this->config['slave'] = $this->config['master'];
    	$this->prefix = $this->config['prefix'] ? $this->config['prefix'] : substr(md5($_SERVER['HTTP_HOST']), 0, 6).'_';
        if ( !extension_loaded('redis') ) {
            throw_exception('redis failed to load');
        }
    }

    private function init_master(){
    	static $_cache;
    	if (isset($_cache)){
    		$this->handler = $_cache;
    	}else{
	        $func = $this->config['pconnect'] ? 'pconnect' : 'connect';
	        $this->handler  = new Redis;
	        $this->enable = $this->handler->$func($this->config['master']['host'], $this->config['master']['port']);
	        $_cache = $this->handler;
    	}
    }

    private function init_slave(){
    	static $_cache;
    	if (isset($_cache)){
    		$this->handler = $_cache;
    	}else{
	        $func = $this->config['pconnect'] ? 'pconnect' : 'connect';
	        $this->handler = new Redis;
	        $this->enable = $this->handler->$func($this->config['slave']['host'], $this->config['slave']['port']);
	        $_cache = $this->handler;
    	}
    }

    private function isConnected() {
    	$this->init_master();
        return $this->enable;
    }

	public function get($key, $type=''){
		$this->init_slave();
		if (!$this->enable) return false;
		$this->type = $type;
		return $this->handler->get($this->_key($key));
	}

    public function set($key, $value, $prefix = '', $expire = SESSION_EXPIRE) {
    	$this->init_master();
    	if (!$this->enable) return false;
    	$this->type = $prefix;
        if(is_int($expire)) {
            $result = $this->handler->setex($this->_key($key), $expire, $value);
        }else{
            $result = $this->handler->set($this->_key($key), $value);
        }
        return $result;
    }

    public function rm($key, $type="") {
    	$this->init_master();
    	if (!$this->enable) return false;
    	$this->type = $type;
        return $this->handler->delete($this->_key($key));
    }

    public function clear() {
    	$this->init_master();
    	if (!$this->enable) return false;
        return $this->handler->flushDB();
    }

	private function _key($str) {
		return $this->prefix.$this->type.$str;
	}
}