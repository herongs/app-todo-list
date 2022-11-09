create database todolist;
use todolist;

create table items(
	id int AUTO_INCREMENT NOT NULL UNIQUE,
    name varchar(100) NOT NULL,
    completed boolean DEFAULT false,
    completed_at timestamp
);

select * from items;
