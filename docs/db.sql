drop table if exists projects;
create table projects(
	id int auto_increment not null,
	title varchar(100) not null,
	start_date date not null,
	end_date date  null,
	priority tinyint default 5,
	status enum('Offered','Ordered','Working','Ended','Stopped','Re-Opened','Waiting'),
	complete_percent tinyint default 0,
	budget decimal(10,2) default 0.00,
	note text,
	primary key(id),
	index(title)
)engine=innodb character set=utf8;