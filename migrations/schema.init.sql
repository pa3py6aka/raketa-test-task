create table if not exists products
(
    id int unsigned auto_increment primary key,
    uuid varchar(36) not null comment 'UUID товара',
    category varchar(255) not null comment 'Категория товара',
    is_active tinyint default 1 not null comment 'Флаг активности',
    name VARCHAR(255) default '' not null comment 'Наименование товара',
    description text null comment 'Описание товара',
    thumbnail varchar(255) null comment 'Ссылка на картинку',
    price decimal(10, 2) not null comment 'Цена'
)
    comment 'Товары';

create index products_uuid_idx on products (uuid);
create index products_category_idx on products (category);
create index products_is_active_idx on products (is_active);