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
- role_id 一个用户有一个role即可
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
- timestamps()

### dept table
- id
- pid
- name
- status
- timestamps()
- remark
