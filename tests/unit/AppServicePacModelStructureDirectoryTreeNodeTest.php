<?php 
use App\Model\Structure\DirectoryTreeNode;
use App\Model\Errors\ApplicationError ;
use App\Model\Filesystem\PacketFile ;
use League\Flysystem\Adapter\Local ;
use League\Flysystem\Filesystem ;
class AppServicePacModelStructureDirectoryTreeNodeTest extends \Codeception\Test\Unit
{
    /**
     * @var \UnitTester
     */
    protected $tester;
    /**
     *
     * @var DirectoryTreeNode 
     */
    protected $dad;
    /**
     *
     * @var DirectoryTreeNode 
     */
    protected $son;
    /**
     *
     * @var DirectoryTreeNode 
     */
    protected $orphan;
    /**
     *
     * @var DirectoryTreeNode 
     */
    protected $brother;
    /**
     *
     * @var DirectoryTreeNode 
     */
    protected $grandson;
    
    protected function _before()
    {
        $this->dad = new DirectoryTreeNode("dad","dad");
        $this->son = new DirectoryTreeNode("son","son");
        $this->brother = new DirectoryTreeNode("brother","brother");
        $this->grandson = new DirectoryTreeNode("grandson","grandson");
        $this->orphan = new DirectoryTreeNode("orphan","orphan");
        $this->dad->appendChild($this->son);
        $this->dad->appendChild($this->brother);
        $this->son->appendChild($this->grandson);
    }

    protected function _after()
    {
        $this->dad = null;
        $this->son = null;
        $this->orphan = null;
        $this->brother = null;
        $this->grandson = null;
    }
    
    
    

    // tests
    public function testFunctionDirectory()
    {
        $this->assertTrue($this->son->directory() === "son");
    }
    
    public function testFunctionParent()
    {
        $this->assertTrue($this->son->parent() === $this->dad);
    }
    
    public function testFunctionRoot()
    {
        $this->assertTrue($this->son->root() === $this->dad);
        $this->assertTrue($this->dad->root() === $this->dad);
        $this->assertTrue($this->grandson->root() === $this->dad);
        $sister = new DirectoryTreeNode("sister", "sister");
        $this->dad->appendChild($sister);
        $this->assertTrue($sister->root() === $this->dad);
    }
    
    public function testFunctionPath()
    {
        $this->assertTrue($this->son->path() === "dad/son");
        $this->assertTrue($this->dad->path() === "dad");
    }
    
    public function testFunctionChildren()
    {
        $this->assertTrue($this->dad->children() == [$this->son,$this->brother]);
        $this->assertTrue($this->son->children() == [$this->grandson]);
    }
    
    public function testFunctionId()
    {
        $this->assertTrue($this->dad->find("son") === $this->son);
        $this->assertTrue($this->dad->find("grandson") === $this->grandson);
    }
    
    public function testFunctionPathToChild()
    {
        $this->assertTrue($this->dad->pathToChild($this->son) === "son");
        $this->assertTrue($this->dad->pathToChild($this->grandson) === "son/grandson");
        $this->assertTrue(is_null($this->dad->pathToChild($this->orphan)));
    }
    
    public function testFunctionPathTo()
    {
        $this->assertTrue($this->son->pathTo($this->dad) === "..");
        $this->assertTrue($this->grandson->pathTo($this->dad) === "../..");
        $this->assertTrue($this->grandson->pathTo($this->brother) === "../../brother");
        $this->assertTrue($this->son->pathTo($this->brother) === "../brother");
        $this->assertTrue(is_null($this->orphan->pathTo($this->son)));
    }
    
    public function testFunctionHasChild()
    {
        $this->assertTrue($this->dad->hasChild("son"));
        $this->assertTrue($this->dad->hasChild("brother"));
        $this->assertFalse($this->dad->hasChild("orphan"));
    }
    
    public function testFunctionAllChildren()
    {
        $this->assertTrue(array_diff($this->dad->allChildren(),[$this->son,$this->brother,$this->grandson]) == []);
        $this->assertTrue(array_diff($this->son->allChildren(),[$this->grandson]) == []);
    }
    
    public function testFunctionChild()
    {
        $this->assertTrue($this->dad->child("son") === $this->son);
        $this->assertTrue($this->dad->child("brother") === $this->brother);
    }
    
    public function testToString()
    {
        $this->assertTrue((string)$this->grandson === "dad/son/grandson");
        $this->assertTrue((string)$this->brother === "dad/brother");
    }
    
    public function testAppendException()
    {
        $this->expectException(ApplicationError::class);
        $this->dad->appendChild($this->grandson);
    }
    
    public function testAppendException2()
    {
        $this->expectException(ApplicationError::class);
        $fakeSon = new DirectoryTreeNode("son","fakeson");
        $this->dad->appendChild($fakeSon);
    }
    
    public function testPathToFile()
    {
        $adapter = new Local(__DIR__.'/../_data', 0);
        $filesystem = new Filesystem($adapter);
        $file = new PacketFile($filesystem,$this->son,"file.txt");
        $this->assertTrue($this->dad->pathToFile($file) === "son/file.txt");
    }
    
    public function testDynamic()
    {
        $adapter = new Local(__DIR__.'/../data/files', 0);
        $filesystem = new Filesystem($adapter);
        $this->assertTrue($this->dad->dynamic($filesystem) === $this->dad);
    }
}