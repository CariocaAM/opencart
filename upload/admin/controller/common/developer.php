<?php
class ControllerCommonDeveloper extends Controller {
	public function index() {
		$this->load->language('common/developer');
		
		$data['user_token'] = $this->session->data['user_token'];

		$this->response->setOutput($this->load->view('common/developer', $data));
	}

	public function theme() {
		$this->load->language('common/developer');
		
		$json = array();
		
		if (!$this->user->hasPermission('modify', 'common/developer')) {
			$json['error'] = $this->language->get('error_permission');
		} else {
			$directories = glob(DIR_CACHE . 'template/*', GLOB_ONLYDIR);

			if ($directories) {
				foreach ($directories as $directory) {
					$files = glob($directory . '/*');
					
					foreach ($files as $file) { 
						if (is_file($file)) {
							unlink($file);
						}
					}
					
					if (is_dir($directory)) {
						rmdir($directory);
					}
				}
			}
						
			$json['success'] = sprintf($this->language->get('text_cache'), $this->language->get('text_theme'));
		}
		
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));			
	}
		
	public function sass() {
		$this->load->language('common/developer');
		
		$json = array();
		
		if (!$this->user->hasPermission('modify', 'common/developer')) {
			$json['error'] = $this->language->get('error_permission');
		} else {
			// Before we delete we need to make sure there is a sass file to regenerate the css
			$file = DIR_APPLICATION  . 'view/stylesheet/bootstrap.css';
			
			if (is_file($file) && is_file(DIR_APPLICATION . 'view/stylesheet/scss/bootstrap.scss')) {
				unlink($file);
			}
			 
			$files = glob(DIR_CATALOG  . 'view/theme/*/stylesheet/scss/bootstrap.scss');
			 
			foreach ($files as $file) {
				$file = substr($file, 0, -20) . '/bootstrap.css';
				
				if (is_file($file)) {
					unlink($file);
				}
			}

			$files = glob(DIR_CATALOG  . 'view/theme/*/stylesheet/stylesheet.scss');
			 
			foreach ($files as $file) {
				$file = substr($file, 0, -16) . '/stylesheet.css';
				
				if (is_file($file)) {
					unlink($file);
				}
			}

			$json['success'] = sprintf($this->language->get('text_cache'), $this->language->get('text_sass'));
		}	
		
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));					
	}
}