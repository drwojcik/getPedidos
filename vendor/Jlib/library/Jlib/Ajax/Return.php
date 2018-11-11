<?php

namespace Jlib\Ajax;

class AjaxReturn {
	protected $_error;
	protected $_error_msg;
	protected $_error_msg_html;
	protected $_success;
	protected $_success_msg;
	protected $_success_msg_html;
	protected $_html;
	protected $_extra;
	public function __construct() {
		$this->_error = '0';
		$this->_error_msg = '';
		$this->_error_msg_html = '';
		$this->_success = '0';
		$this->_success_msg = '';
		$this->_success_msg_html = '';
		$this->_html = '';
	}
	public function setSuccess($msgStr, $msgHtml) {
		$this->_error = '0';
		$this->_error_msg = '';
		$this->_error_msg_html = '';
		$this->_success = '1';
		$this->_success_msg = $msgStr;
		$this->_success_msg_html = $msgHtml;
	}
	public function setError($msgStr, $msgHtml) {
		$this->_error = '1';
		$this->_error_msg = $msgStr;
		$this->_error_msg_html = $msgHtml;
		$this->_success = '0';
		$this->_success_msg = '';
		$this->_success_msg_html = '';
	}
	public function setHtml($html) {
		$this->_html = $html;
	}
	public function setExtra(array $dados) {
		$this->_extra = $dados;
	}
	public function toArray() {
		$ret = array (
				'error' => $this->_error,
				'error_msg' => $this->_error_msg,
				'error_msg_html' => $this->_error_msg_html,
				'success' => $this->_success,
				'success_msg' => $this->_success_msg,
				'success_msg_html' => $this->_success_msg_html,
				'html' => $this->_html,
				'extra' => $this->_extra 
		);
		
		return $ret;
	}
	public function toJson() {
		return '[' . \Zend\Json\Json::encode ( $this->toArray () ) . ']';
	}
}