# ============================================================================
# 构建 blog 项目镜像并加载进 kind 集群
#
# 项目镜像归【项目仓库】自己管，devx 不参与 —— devx 只提供基础镜像。
#
# 前置条件：基础镜像必须先建好
#   pwsh -File d:/gits/devx/helm/images/build.ps1
#
# 产出两个 tag（对应 Dockerfile 的两个 --target）：
#   blog-php:dev   基础镜像 + vendor          ← 本地开发（代码从宿主挂载）
#   blog-php:prod  基础镜像 + vendor + 代码   ← 模拟生产
#
# 用法：
#   pwsh -File helm/docker/build.ps1                # 两个都建
#   pwsh -File helm/docker/build.ps1 -Target dev    # 只建 dev（快，日常用）
#   pwsh -File helm/docker/build.ps1 -NoLoad        # 只 build，不塞进 kind
# ============================================================================
param(
    [ValidateSet("dev", "prod", "all")]
    [string]$Target = "all",
    [switch]$NoLoad
)

$ErrorActionPreference = "Stop"

# 项目根目录（本脚本在 helm/docker/ 下）
$ProjectRoot = Split-Path -Parent (Split-Path -Parent $PSScriptRoot)   # d:/gits/blog
$Dockerfile = Join-Path $PSScriptRoot "Dockerfile"

# kind 集群名是这里唯一的环境相关信息，用环境变量覆盖，默认 local
$ClusterName = if ($env:KIND_CLUSTER_NAME) { $env:KIND_CLUSTER_NAME } else { "local" }

$targets = if ($Target -eq "all") { @("dev", "prod") } else { @($Target) }

foreach ($t in $targets) {
    $image = "blog-php:$t"

    Write-Host "==> docker build --target $t -t $image -f $Dockerfile $ProjectRoot" -ForegroundColor Cyan
    docker build --target $t -t $image -f $Dockerfile $ProjectRoot
    if ($LASTEXITCODE -ne 0) { throw "build 失败：$image" }

    if (-not $NoLoad) {
        Write-Host "==> kind load docker-image $image --name $ClusterName" -ForegroundColor Cyan
        kind load docker-image $image --name $ClusterName
        if ($LASTEXITCODE -ne 0) { throw "load 失败：$image" }
    }
}

Write-Host ""
Write-Host "完成：$($targets -join ', ')" -ForegroundColor Green
