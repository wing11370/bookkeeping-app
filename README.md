# 記帳程式

## 路由
"api/records" 的路由是主要的功能，交易紀錄的新增、查詢、修改、刪除，需要帶入Bearer Token

"api/docs" 可以使用Swagger查看API文件
## 環境變數
APP_URL
DB的Host、Port、User、Password、Database
L5_SWAGGER_GENERATE_ALWAYS=true
L5_SWAGGER_CONST_HOST="${APP_URL}"
L5_SWAGGER_UI_DOC_EXPANSION=list
