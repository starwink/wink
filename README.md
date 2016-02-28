# wink
api接口调用及辅助项目工具

# 准备
使用YII1.1.16框架开发项目过程中所需的工具

#已开发模块说明
1. ApiToolController 基于开发过程中对于接口的调试,记录过程中的接口信息及参数信息. 完善为一个易用工具后,对已有数据信息来 定期检测已上线接口的状态.

2. TableController  用于更加直接的当前数据库表结构浏览

3. WeiXinGongZhongHaoController  用于接入微信公众号的相关开发

#指南
复制/protected/config/ 中的main-dev.php或main-stable.php 复制命名为main.php,并对main.php中的服务器配置参数进行设置,配置区域以 dev或stable 开头 例  'username' => 'dev username', 编辑为
'username' => 'root',

在/protected/ 下新建 文件夹 命名为 runtime 用于存储YII缓存文件
