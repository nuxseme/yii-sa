<?php
/**
* @author nuxse
* @date 09-01-2016 15:19
* @todo 剖析框架  流程记录
*/

function deleteLogs()
{
	$fileNames = scandir(__DIR__);
	foreach ($fileNames as $fileName) {
		if(strstr($fileName,'Yiisa'))
		{
			unlink(__DIR__.'/'.$fileName);
		}
	}
}
	
function tracelog($messages)
{
	$time = date('Y-m-d H:i:s',time());
	$format_messages = <<<eof
[{$time}]:$messages \r\n
eof;
	$file = __DIR__.'/Yiisa_trace';
	$fp = @fopen($file, 'ab');
	//file_put_contents(__DIR__.'/Yiisa_trace',$format_messages,FILE_APPEND);
	fwrite($fp, $format_messages);
}
