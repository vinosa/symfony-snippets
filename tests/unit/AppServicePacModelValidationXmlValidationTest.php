<?php 
use App\Model\Filesystem\PacketFile ;
use League\Flysystem\Adapter\Local ;
use League\Flysystem\Filesystem ;
use App\Model\Structure\DirectoryTreeNode;
use App\Model\Validation\XmlValidation;

use App\Model\Workflow\FailedAction;
use App\Model\Validation\XmlSchemas;
use App\Model\Errors\ApplicationError;
class AppServicePacModelValidationXmlValidationTest extends \Codeception\Test\Unit
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
    public function testFunctionXmlValidation()
    {
        $adapter = new Local(__DIR__.'/../_data', 0);
        $filesystem = new Filesystem($adapter);
        $files = new DirectoryTreeNode("files");
        $xsd = __DIR__. "/../_data/_xsd/tei/1.6/document.xsd";
        $schemaLocation = 'http://lodel.org/ns/tei/tei.openedition.1.6/document.xsd';
        $schemas = new XmlSchemas([$schemaLocation =>  $xsd]);
        $this->assertTrue($schemas->path($schemaLocation) === $xsd);
        
        $xml = new PacketFile($filesystem,$files,"sample.xml");
        $wrongXml = new PacketFile($filesystem,$files,"wrong_tei.xml");
        $this->assertFalse((new XmlValidation())->validation($xml,$schemas)->failed());
        $this->assertFalse((new XmlValidation())->detailedValidation($xml,$schemas)->failed());
        $description = "xml validation failed with document.xsd";
        $message = '[{"level":2,"code":1871,"column":0,"message":"Element \'{http:\/\/www.tei-c.org\/ns\/1.0}TEI\': Missing child element(s). Expected is ( {http:\/\/www.tei-c.org\/ns\/1.0}text ).","line":2}]';
        $this->assertTrue((new XmlValidation())->validation($wrongXml,$schemas)->failed());
        $this->assertTrue((new XmlValidation())->detailedValidation($wrongXml,$schemas)->failed());
        //$this->assertTrue($validation->detailedValidation($wrongXml, $xsd) == new FailedAction(new OpenEditionError(new FileError($wrongXml,new ValidationError(new ArchiveError($description,$message))))) );
    }
    
    public function testXmlSchemaException()
    {
        $this->expectException(ApplicationError::class);
        $xsd = __DIR__. "/../_data/_xsd/tei/1.6/document.xsd";
        $schemaLocation = 'http://lodel.org/ns/tei/tei.openedition.1.6/document.xsd';
        $schemas = new XmlSchemas([$schemaLocation =>  $xsd]);
        $path = $schemas->path("wrong SchemaLocation");
    }
}