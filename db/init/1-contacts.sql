create table if not exists db_contacts.contacts
(
	id int auto_increment
		primary key,
	first_name varchar(100) not null,
	last_name varchar(100) not null,
	email varchar(100) null,
	birthdate date null,
	phone varchar(30) null
)
charset=utf8;