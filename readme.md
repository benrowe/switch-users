# Switch Media - User Crud Test

## Build Plan

1. setup live environment (heroku)
  - composer + php ver (7.1)
  - install buildpack + config
  - create procfile
2. build light weight framework (routing, ioc, database, view)
  - use packages:
    - `vlucas/phpdotenv` + `mrjgreen/config` for configuration (both dev & live)
    - `league/container` basic application container
    - `illuminate/database` for ORM
3. Setup config/env
2. setup api route endpoints for tabular & detail user views
  - api token verification?
3. build simple ui
  - use jquery + datagrid + modal components
4. build model
5. connect to routes

6. if time, add caching to api level for scalability
