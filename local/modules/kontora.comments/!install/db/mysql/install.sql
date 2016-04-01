create table if not exists kontora_comments
(
	ID INT(10) not null auto_increment,
	COMMENT text not null,
	ELEMENT_ID INT(11) REFERENCES b_iblock_element(ID),
	USER_ID INT(11) REFERENCES b_user(ID),
	MODERATOR_ID INT(11),
	STATUS int(10) not null default 1,
	DATE_CREATE datetime,
	DATE_LAST_CHANGE TIMESTAMP,
	LEFT_KEY INT(10) not null DEFAULT 0,
	RIGHT_KEY INT(10) not null DEFAULT 0,
	LEVEL INT(10) not null DEFAULT 0,
	IP varchar(11),
	PRIMARY KEY (ID),
	INDEX LEFT_KEY (LEFT_KEY, RIGHT_KEY, LEVEL) 
);
create trigger kontora_comments_before_insert
	before insert
	on kontora_comments
	for each row
	set new.DATE_CREATE = NOW();