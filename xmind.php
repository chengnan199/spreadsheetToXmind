<?php
//xmind的内部结构由 三部分组成 style.xml  comments.xml content.xml
$fileList = [];
//style.xml文件
$styleFile = './general/styles.xml';
$style = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?>
<xmap-styles xmlns:svg="http://www.w3.org/2000/svg" xmlns:fo="http://www.w3.org/1999/XSL/Format" xmlns="urn:xmind:xmap:xmlns:style:2.0" version="2.0"/>');
$style->asXML($styleFile);

//comments.xml文件
$commentsFile = './general/comments.xml';
$comments = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?>
<comments xmlns="urn:xmind:xmap:xmlns:comments:2.0" version="2.0"/>');
$comments->asXML($commentsFile);

//contents.xml文件 （这个节点可以展开看一下结构,根据里面 sheet  topic ）
$contentFile = './general/content.xml';
$content = new SimpleXMLElement(file_get_contents($contentFile));
$content->asXML($contentFile);

//整合成xmind
$fileList = array($styleFile, $commentsFile, $contentFile);
$filename = "test.xmind";
$zip = new ZipArchive();
$zip->open($filename, ZipArchive::CREATE);   //打开压缩包
foreach ($fileList as $file) {
    $zip->addFile($file, basename($file));   //向压缩包中添加文件
}
$zip->close();  //关闭压缩包