
## 安装步骤
- git clone  https://github.com/github-muzilong/yii2-layuiadmin.git
- c配置common/config/里的数据库连接信息
- composer update
- yii migrate  这一步是创建admin用户表
- yii migrate --migrationPath=@yii/rbac/migrations  这一步是创建yii的rbac表
- yii data/init 初始化数据
- 登录后台：host   帐号：root  始化密码：123456

## 图片展示
- 后台登录
![Image text](https://raw.githubusercontent.com/github-muzilong/yii2-layuiadmin/master/backend/web/static/images/1.png)
- 后台主页
![Image text](https://raw.githubusercontent.com/github-muzilong/yii2-layuiadmin/master/backend/web/static/images/2.png)
- 用户
![Image text](https://raw.githubusercontent.com/github-muzilong/yii2-layuiadmin/master/backend/web/static/images/3.png)
![Image text](https://raw.githubusercontent.com/github-muzilong/yii2-layuiadmin/master/backend/web/static/images/4.png)
![Image text](https://raw.githubusercontent.com/github-muzilong/yii2-layuiadmin/master/backend/web/static/images/5.png)
- 角色
![Image text](https://raw.githubusercontent.com/github-muzilong/yii2-layuiadmin/master/backend/web/static/images/6.png)
- 后面的自己看吧
![Image text](https://raw.githubusercontent.com/github-muzilong/yii2-layuiadmin/master/backend/web/static/images/7.png)
![Image text](https://raw.githubusercontent.com/github-muzilong/yii2-layuiadmin/master/backend/web/static/images/8.png)
