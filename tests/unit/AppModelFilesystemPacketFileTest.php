<?php 
use App\Model\Filesystem\PacketFile ;
use League\Flysystem\Adapter\Local ;
use League\Flysystem\Filesystem ;
use App\Model\Structure\DirectoryTreeNode;
use App\Entity\FileInfo ;
use App\Model\Validation\XmlValidation;
use App\Model\Workflow\SucceedAction;

class AppModelFilesystemPacketFileTest extends \Codeception\Test\Unit
{
    /**
     * @var \UnitTester
     */
    protected $tester;
    /**
     *
     * @var Filesystem
     */
    protected $filesystem;
    /**
     *
     * @var DirectoryTreeNode
     */
    protected $untracked;
    /**
     *
     * @var DirectoryTreeNode
     */
    protected $files;
    /**
     *
     * @var string
     */
    protected $data;
    /**
     *
     * @var string
     */
    protected $pathXsdTei;
    
    protected function _before()
    {
        $adapter = new Local(__DIR__.'/../_data', 0);
        $this->filesystem = new Filesystem($adapter);
        $this->untracked = new DirectoryTreeNode("_untracked");
        $this->files = new DirectoryTreeNode("files");
        $this->data = "test data" ;
        $this->pathXsdTei = __DIR__. "/../_data/_xsd/tei/1.6/document.xsd";
    }

    protected function _after()
    {
        $this->filesystem = null;
        $this->untracked = null ;
    }

    // tests
    public function testFunctionWithContents()
    {       
        $file = new PacketFile($this->filesystem,$this->untracked,"test.txt");
        $file->withContents($this->data);
        $this->assertTrue($file->contents() === $this->data);
        $this->assertTrue($file->md5() === md5($this->data));
        $this->assertTrue((string)$file === $this->data);
    }
    
    public function testFunctions()
    {
        $jpg = new PacketFile($this->filesystem,$this->files,"sample.jpg");
        $this->assertTrue($jpg->isImage());
        $this->assertTrue($jpg->isJpg());
        $xml = new PacketFile($this->filesystem,$this->files,"sample.xml");
        $this->assertTrue($xml->isXml());
        $pdf = new PacketFile($this->filesystem,$this->files,"sample.pdf");
        $this->assertTrue($pdf->isPdf());
        $this->assertTrue($pdf->fileinfo() == new FileInfo("files/sample.pdf"));
        $this->assertTrue($jpg->formatForSip() === "JPEG");
        $this->assertTrue($xml->directory() === $this->files) ;
    }
    
}