<?php
namespace Basico\View\Helper;

use Jlib\Util\GitInfo As JlibGitInfo;

use Zend\View\Helper\AbstractHelper;
use Zend\Authentication\Storage\Session As SessionStorage;

class GitInfo extends AbstractHelper {

    public function __invoke(){
    	$sessionGit = new SessionStorage('Git');
        if ($sessionGit->isEmpty()){
        	$gitInfo = new JlibGitInfo();
            $sessionGit->write($gitInfo);
		}
		
		return $sessionGit->read();
    }
}

?>