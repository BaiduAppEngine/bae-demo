BAE扩展服务使用说明

该示例涵盖了BAE3.0平台的扩展服务如：cache, image, log, mysql, mongodb, redis
通过该示例可以了解扩展服务的基本使用

注意：

1. 使用该示例时请您确保在平台创建了cache，mysql，mongodb，redis, image, log服务
    具体创建流程请您参考BAE3.0的文档

2. 请将申请的mysql, mongodb, redis, cache, 名称填写到com/baiud/cloudservice/conf/Config.java文件相应的位置处

3. 请将申请到的API KEY和SECRET KEY填写到com/baiud/cloudservice/conf/Config.java文件相应的位置处

4. 如果需要使用image服务存储处理以后的图片数据，(不使用图片服务的请忽略) 
    
    (1) 请您确保开启了云存储服务

    (2) 请将云存储中的任何一个Bucket填写到com/baiud/cloudservice/conf/Config.java文件相应的位置处（不使用图片服务的请忽略）
    
5. 更改完成后，编译并打成war包的形式，建议将war包命名为root.war

6. 将root.war通过svn或者git上传至申请的java应用中