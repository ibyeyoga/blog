# Usage

## 本地开发
### 安装依赖
##### 1. 根目录执行，构造运行环境
```
docker-compose up -d
```
##### 2. 安装项目依赖，生成前端资源文件
``` composer install && npm install && npm run build ```

##### 3. 修改配置文件，数据库参数等
``` cp .env.example .env ```

> [!NOTE] 按需执行
> 数据库迁移，生成预设数据
``` php artisan migrate --seed ```
> 生成应用key
``` php artisan key:generate ```

## 线上部署
### 构造镜像
> [!NOTE]
假设Kind节点是 **blog-cluster**

##### 1. 根目录执行
``` docker build -t blog-php:latest -f helm/docker/Dockerfile . ```
> [!TIP]
helm/docker/Dockerfile和docker/php/8.3/Dockerfile基本一致，只是多了项目代码、依赖、资源文件集成，并去掉composer、nodejs等手动操作的工具

##### 2. 把镜像加载到Kind节点
``` kind load docker-image blog-php:latest --name blog-cluster ```

##### 3. 部署
``` helm install lara-blog ./helm/blog ```


## 管理员账号
```
admin@example.com
123456
```
