<?php

namespace Core;

class View {

	public function generateTpl($template, $data ) {
		$twig = new \Twig_Environment(new \Twig_Loader_Filesystem(APP_PATH . DS . 'html'), array('autoescape' => false));
		$template = $template . '.html';
		$template = $twig->loadTemplate($template);		
		return $template->render($data);
	}
	
}

?>