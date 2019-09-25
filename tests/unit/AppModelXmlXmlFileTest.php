<?php 
use App\Model\Filesystem\PacketFile ;
use League\Flysystem\Adapter\Local ;
use League\Flysystem\Filesystem ;
use App\Model\Xml\Tei ;
use App\Model\Structure\DirectoryTreeNode;
use App\Model\Errors\ApplicationError;
class AppModelXmlXmlFileTest extends \Codeception\Test\Unit
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
    public function testTei()
    {
        $adapter = new Local(__DIR__.'/../_data', 0);
        $filesystem = new Filesystem($adapter);
        $files = new DirectoryTreeNode("files");
 
        
        $tei = new Tei(new PacketFile($filesystem,$files,"tei.xml"));
        $this->assertTrue($tei->imagesDirectoryName() == "363");
        //$imageUrls = $tei->imageUrls();
        $this->assertTrue(in_array("http://books.openedition.org/sdo/docannexe/image/363/img-1.jpg", $tei->imageUrls()));
        $this->assertTrue($tei->hasImages());
        //foreach($tei->xpath()->query("//tei:graphic/@url")
        $this->assertInstanceOf(\DOMXPath::class, $tei->xpath());
        $this->assertTrue($tei->urlOdd() === "http://lodel.org/ns/odd/tei.openedition.1.6.1.odd.xml");
    }
    
    public function testwrongTei()
    {
        $adapter = new Local(__DIR__.'/../_data', 0);
        $filesystem = new Filesystem($adapter);
        $files = new DirectoryTreeNode("files");         
        $tei = new Tei(new PacketFile($filesystem,$files,"wrong_tei.xml"));
       // $this->assertTrue($tei->imagesDirectoryName() == "363");
        //$imageUrls = $tei->imageUrls();
        //$this->assertTrue(in_array("http://books.openedition.org/sdo/docannexe/image/363/img-1.jpg", $tei->imageUrls()));
        //$this->assertTrue($tei->hasImages());
        //$this->assertTrue($tei->urlOdd() === "http://lodel.org/ns/odd/tei.openedition.1.6.1.odd.xml");
    }
    
    public function testXmlFileContents()
    {
        $adapter = new Local(__DIR__.'/../_data', 0);
        $filesystem = new Filesystem($adapter);
        $files = new DirectoryTreeNode("files");      
        $tei = new Tei(new PacketFile($filesystem,$files,"tei.xml"));
        $file = new PacketFile($filesystem,$files,"tei.xml");
        $tei = $tei->withContents($file);
        $this->assertTrue(strpos((string) $tei,"OpenEdition") !== false);
        $this->assertTrue(strpos($tei->contents(),"OpenEdition") !== false);
        $this->assertTrue($tei->schemaName() === "document.xsd");
    }
    
    public function testThrowExceptionIfNoSchema()
    {
        
        $this->expectException(ApplicationError::class);
        $adapter = new Local(__DIR__.'/../_data', 0);
        $filesystem = new Filesystem($adapter);
        $files = new DirectoryTreeNode("files");   
        $file = new PacketFile($filesystem,$files,"tei_no_schema.xml");
        $tei = (new Tei(""))->withContents($file);
        $schema = $tei->schemaLocation();
    }
    
    
}