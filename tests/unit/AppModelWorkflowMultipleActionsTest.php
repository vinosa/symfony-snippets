<?php 
use App\Model\Workflow\MultipleActions;
use App\Model\Workflow\FacileAction;
use App\Model\Workflow\FileAction;
use App\Model\Workflow\ValidationAction;
use App\Model\Filesystem\PacketFile ;
use League\Flysystem\Adapter\Local ;
use League\Flysystem\Filesystem ;
use App\Model\Structure\DirectoryTreeNode;
use App\Model\Workflow\StopOnFail ;
use App\Model\Errors\ActionError ;
use App\Model\Workflow\FailedAction ;

class AppModelWorkflowMultipleActionsTest extends \Codeception\Test\Unit
{
    /**
     * @var \UnitTester
     */
    protected $tester;
    
    protected function _before()
    {
    }

    protected function _after()
    {
    }

    // tests
    public function testDecorators()
    {
        $adapter = new Local(__DIR__.'/../_data', 0);
        $filesystem = new Filesystem($adapter);
        $files = new DirectoryTreeNode("files");
        $file = new PacketFile($filesystem,$files,"tei.xml");
        $actions = [new ValidationAction()];
        $action = new FileAction($file,new FacileAction(new MultipleActions($actions)));
        (new MultipleActions($actions))->log("some log");
    }
    
    public function testStopOnFail()
    {
        $this->expectException(ActionError::class);
        $actions = [new ValidationAction()];
        $action = new StopOnFail(new FailedAction(new MultipleActions($actions),"error description","error message"));
    }
}