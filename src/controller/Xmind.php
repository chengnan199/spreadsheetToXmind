<?php
namespace src\controller;

//require './vendor/autoload.php';
//require './SnowFlake.php';
//require './Tree.php';
//(new xmind())->xmind();
class Xmind {
    protected $styleFile ;
    protected $commentsFile;
    protected $contentFile;
    protected $snowFlake;

    public function __construct()
    {
        $this->snowFlake = new snowFlake();
        $this->styleFile = dirname(__FILE__).'/../general/styles.xml';
        $this->commentsFile = dirname(__FILE__).'/../general/comments.xml';
        $this->contentFile = dirname(__FILE__).'/../general/content.xml';
    }

    /**
     * @param $fileName string 要处理的excel
     * @param $path string 生成的路径地址
     * @return void
     */
    function index($fileName,$path){
//        $fileName = './老年大学教务平台服务端.xlsx';
        $excelData = (new Excel())->getExcelData($fileName);
        $data = (new Tree())->tree($excelData);
        $this->CreateXmind($data,$path);
    }

    function CreateXmind($data,$path){
        $str = '<?xml version="1.0" encoding="UTF-8" standalone="no"?>
<xmap-content xmlns="urn:xmind:xmap:xmlns:content:2.0" xmlns:fo="http://www.w3.org/1999/XSL/Format" xmlns:svg="http://www.w3.org/2000/svg" xmlns:xhtml="http://www.w3.org/1999/xhtml" xmlns:xlink="http://www.w3.org/1999/xlink" modified-by="ddl" timestamp="1607912245582" version="2.0">
    <sheet id="3eqb5chq516v2bi0hqo52cvnjd" modified-by="ddl" theme="5disc9luc2p2tdqg4fh9sln9ub" timestamp="1607912245582">        
     <topic id="0tao7efgjol3d6p97okrr7e9e3" modified-by="ddl" structure-class="org.xmind.ui.map.unbalanced" timestamp="1607912245582">
            <title>服务端</title>  
';
        $str .= $this->getChildrenStr($data);
        $str .= '        </topic><title>服务端</title>
                </sheet>
            </xmap-content>';
        $this->zip($path,$str);
        return $str;
    }

//    获取xmind结构
    function getChildrenStr($arr){
        $str = '            <children>
                <topics type="attached">';

        foreach ($arr as $k=>$v){
            $isChild = !empty($v['child']); // 是否有子元素
            $str .=  '<topic  id="'.$this->snowFlake->generateId().'" modified-by="Administrator" timestamp="'.(microtime(true)*1000).'">
                        <title>'.$v['value'].'</title>';
            if ($isChild){
                $str .= $this->getChildrenStr($v['child']);
            }
            $str .= '</topic>';
        }
        $str .= '</topics></children>';
        return $str;
    }

//    添加为xmind压缩包
    function zip ($filename,$contentFileData =''){
        //xmind的内部结构由 三部分组成 style.xml  comments.xml content.xml
        $fileList = [];
//style.xml文件
        $styleFile = $this->styleFile;
        $style = new \SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?>
<xmap-styles xmlns:svg="http://www.w3.org/2000/svg" xmlns:fo="http://www.w3.org/1999/XSL/Format" xmlns="urn:xmind:xmap:xmlns:style:2.0" version="2.0"/>');
        $style->asXML($styleFile);

//comments.xml文件
        $commentsFile = $this->commentsFile;
        $comments = new \SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?>
<comments xmlns="urn:xmind:xmap:xmlns:comments:2.0" version="2.0"/>');
        $comments->asXML($commentsFile);

//contents.xml文件 （这个节点可以展开看一下结构,根据里面 sheet  topic ）
        $contentFile = $this->contentFile;
        $contentFileData = $contentFileData?:file_get_contents($contentFile);
        $content = new \SimpleXMLElement($contentFileData);
        $content->asXML($contentFile);

//整合成xmind
        $fileList = array($styleFile, $commentsFile, $contentFile);
        $zip = new \ZipArchive();
        $zip->open($filename, \ZipArchive::CREATE);   //打开压缩包
        foreach ($fileList as $file) {
            $zip->addFile($file, basename($file));   //向压缩包中添加文件
        }
        $zip->close();  //关闭压缩包
    }
}

