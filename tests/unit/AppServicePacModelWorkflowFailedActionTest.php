<?php 
use App\Model\Workflow\FailedAction;
use App\Entity\ArchiveError ;
use App\Entity\ErrorType;
use App\Entity\ErrorSource;
use App\Model\Errors\FacileError;
use App\Entity\ArchiveStatus ;

class AppServicePacModelWorkflowFailedActionTest extends \Codeception\Test\Unit
{
    /**
     * @var \UnitTester
     */
    protected $tester;
    protected $action;
    protected $error ;
    protected $description ;
    
    protected function _before()
    {
        $this->description = "error";
        //$this->action = new FailedAction(new FacileError(new HttpError(new ArchiveError($this->description))));
        $this->error = (new ArchiveError($this->description))->setType(ErrorType::HTTP)
                                                  ->setSource(ErrorSource::FACILE);
    }

    protected function _after()
    {
    }

    // tests
    
    public function testFunctiontoString()
    {
        $this->assertTrue((string)$this->error === $this->description . "-" . ErrorType::HTTP . "-" . ErrorSource::FACILE);       
    }
}