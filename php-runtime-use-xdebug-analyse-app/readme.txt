1、此demo演示了，如何利用xdebug生成性能文件，并且将性能文件上传到bcs中，可下载到本地用专门的工具进行分析；
2、下载好性能文件后，用WinCacheGrind.exe来对其进行分析；
3、index.php为用户的应用文件，可以相应的更改或者加载更多也可；
4、uploadfile.php为上传生成的性能文件到bcs中，可到其中更改相应配置，如果此文件与用户的应用文件有重名，请更改此文件name；
5、每次运行uploadfile.php时，同样也会生成自身的性能日志，并就上传到bcs中，分析时注意；
6、最重要的，如何使用！ 
		第一步，先访问您的应用，各个连接啥的都点点，如“http://baegod.duapp.com/index.php”;
		第二步，访问uploadfile，如“http://[hostname]/uploadfile.php”
	成功后，即可在bcs中看到对应的文件，下载，并用2步中的应用加载即可。
	
	enjoy it！