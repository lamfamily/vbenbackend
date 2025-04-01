## project logs
- composer require --dev barryvdh/laravel-ide-helper
- php artisan ide-helper:generate
- composer require kalnoy/nestedset
- composer require tymon/jwt-auth
- composer require barryvdh/laravel-debugbar --dev
  - 不需任何配置，就会显示在页面上，（开发API，好像不需要）

## table design
### users table
- id
- username
- real_name
- avatar
- email
- password
- phone
- status
- last_login_time
- last_login_ip
- remark
- rememberToken()
- timestamps()
- softDeletes()

### roles table
- id
- name
- status
- remark
- timestamps()

### permissions table
- id
- name
- code
- remark
- timestamps()

### role_permissions table
- role_id
- permission_id
- primary key (role_id, permission_id)
- foreign key (role_id) references roles(id)
- foreign key (permission_id) references permissions(id)

### menus table
- id
- pid
- name
- status
- type 菜单类型：1-目录，2-菜单，3-按钮，4-内嵌，5-外链(catelog,menu,button,embedded,link)
- icon
- path
- component
- meta: json {icon,title,affixTab,order,badge,badgeType,badgeVariants,iframeSrc}
- nestedset()
- timestamps()

### dept table
- id
- pid
- name
- status
- timestamps()
- nestedset()
- remark
