DROP DATABASE IF EXISTS software_eng;

DELETE FROM mysql.user WHERE user='sem';

CREATE DATABASE software_eng DEFAULT CHARSET utf8 COLLATE utf8_general_ci;
GRANT ALL PRIVILEGES ON software_eng.* TO "sem"@'localhost' IDENTIFIED BY 'sem2016';

USE software_eng;

CREATE TABLE teacher(
	tid		  int  not null  primary key auto_increment,
	username  varchar(20) not null,
	password  varchar(32) not null,
	verified_email  bit(1) not null,
	verified_phone  bit(1) not null,
	name	  varchar(20),
	gender	  char(1),
	phone     varchar(20),
	email     varchar(50),
	college   varchar(30),
	department     varchar(30),
	position  varchar(20),
	education varchar(20),
	direction varchar(30),
	past_evaluation varchar(100),
	desc_achive varchar(200),
	desc_teach_type varchar(100),
	desc_publish varchar(100),
	desc_honor  varchar(100),
	desc_more varchar(100)
)ENGINE=innodb DEFAULT CHARSET=utf8 auto_increment=1;

CREATE TABLE student(
	sid		  int  not null  primary key auto_increment,
	username  varchar(20) not null,
	password  varchar(32) not null,
	verified_email  bit(1) not null,
	verified_phone  bit(1) not null
)ENGINE=innodb DEFAULT CHARSET=utf8 auto_increment=1;

CREATE TABLE course(
	coid  int not null  primary key auto_increment,
	coname varchar(20) not null,
	textbook varchar(40),
	cocode varchar(10) not null,
	cotype varchar(20) not null,
	semster varchar(10) not null,
	coname_en varchar(60) not null,
	college  varchar(30) not null,
	credit float(2,1) not null,
	week_learn_time  int not null,
	weight varchar(10) not null,
	pre_learning varchar(200) not null,
	plan   varchar(200) not null,
	background  varchar(100) not null,
	assessment  varchar(100) not null,
	project_info varchar(100) not null
)ENGINE=innodb DEFAULT CHARSET=utf8 auto_increment=1;

CREATE TABLE class(
	clid  int not null  primary key auto_increment,
	coid  int not null,
	tid   int not null,
	cltime  varchar(40) not null,
	place varchar(30) not null,
	student_num int not null,
	constraint `class_refer_course` foreign key (`coid`) references `course`(`coid`) on delete cascade on update cascade,
	constraint `class_refer_teacher` foreign key (`tid`) references `teacher`(`tid`) on delete cascade on update cascade
) ENGINE=innodb DEFAULT CHARSET=utf8 auto_increment=1;

CREATE TABLE study_group(
	gid		  int  not null  primary key auto_increment,
	clid	  int  not null,
	gname  	  varchar(20) not null,
	teamleader_id  int not null,
	constraint `group_refer_class` foreign key (`clid`) references `class`(`clid`) on delete cascade on update cascade,
	constraint `group_refer_student` foreign key (`teamleader_id`) references `student`(`sid`) on delete cascade on update cascade
) ENGINE=innodb DEFAULT CHARSET=utf8 auto_increment=1;

CREATE TABLE admin(
	aid  int not null  primary key auto_increment,
	username varchar(20) not null,
	password varchar(32) not null,
	name     varchar(20) not null,
	phone     varchar(20) not null,
	email     varchar(50) not null
) ENGINE=innodb DEFAULT CHARSET=utf8 auto_increment=1;

CREATE TABLE ta_assist(
	taid     int not null  primary key auto_increment,
	clid	 int not null,
	username varchar(20) not null,
	password varchar(32) not null,
	constraint `ta_refer_class` foreign key(`clid`) references `class`(`clid`) on delete cascade on update cascade
) ENGINE=innodb DEFAULT CHARSET=utf8 auto_increment=1;

CREATE TABLE group_post(
	post_id   int  not null  primary key auto_increment,
	gid       int not null,
	sid       int not null,
	title     varchar(100) not null,
	post_date datetime not null,
	frozon    bit(1) not null,
	hotness   int null default 0,
	content	  varchar(500) not null,
	constraint `gpost_refer_group` foreign key (`gid`) references `study_group`(`gid`) on delete cascade on update cascade,
	constraint `gpost_refer_student` foreign key (`sid`) references `student`(`sid`) on delete cascade on update cascade
) ENGINE=innodb DEFAULT CHARSET=utf8 auto_increment=1;

CREATE TABLE homework_post(
	post_id   int  not null  primary key auto_increment,
	coid      int not null,
	tid       int not null,
	title     varchar(100) not null,
	post_date datetime not null,
	frozon    bit(1) not null,
	hotness   int null default 0,
	content	  varchar(500) not null,
	constraint `hpost_refer_course` foreign key (`coid`) references `course`(`coid`) on delete cascade on update cascade,
	constraint `hpost_refer_teacher` foreign key (`tid`) references `teacher`(`tid`) on delete cascade on update cascade
) ENGINE=innodb DEFAULT CHARSET=utf8 auto_increment=1;

CREATE TABLE group_post_floor(
	floor_id   int  not null  primary key auto_increment,
	post_id    int not null,
	utype      char(1) not null,
	tid        int,
	taid       int,
	sid        int,
	post_date  datetime not null,
	content    varchar(500) not null,
	ref_fid    int,
	ref_count  int not null default 0,
	constraint `gpfloor_refer_floor` foreign key (`ref_fid`) references `group_post_floor`(`floor_id`) on delete cascade on update cascade,
	constraint `gpfloor_refer_post` foreign key (`post_id`) references `group_post`(`post_id`) on delete cascade on update cascade,
	constraint `gpfloor_refer_teacher` foreign key (`tid`) references `teacher`(`tid`) on delete cascade on update cascade,
	constraint `gpfloor_refer_student` foreign key (`sid`) references `student`(`sid`) on delete cascade on update cascade,
	constraint `gpfloor_refer_ta` foreign key (`taid`) references `ta_assist`(`taid`) on delete cascade on update cascade
)ENGINE=innodb DEFAULT CHARSET=utf8 auto_increment=1;

CREATE TABLE homework_post_floor(
	floor_id   int  not null  primary key auto_increment,
	post_id    int not null,
	utype      char(1) not null,
	tid        int,
	taid       int,
	sid        int,
	post_date  datetime not null,
	content	   varchar(500) not null,
	ref_fid    int,
	ref_count  int not null default 0,
	constraint `hpfloor_refer_floor`foreign key (`ref_fid`) references `homework_post_floor`(`floor_id`) on delete cascade on update cascade,
	constraint `hpfloor_refer_post`foreign key (`post_id`) references `homework_post`(`post_id`) on delete cascade on update cascade,
	constraint `hpfloor_refer_teacher` foreign key (`tid`) references `teacher`(`tid`) on delete cascade on update cascade,
	constraint `hpfloor_refer_student` foreign key (`sid`) references `student`(`sid`) on delete cascade on update cascade,
	constraint `hpfloor_refer_ta` foreign key (`taid`) references `ta_assist`(`taid`) on delete cascade on update cascade
)ENGINE=innodb DEFAULT CHARSET=utf8 auto_increment=1;

CREATE TABLE material_statics(
	coid  int not null,
	tid   int not null,
	total int not null,
	t_word_usage int not null default 0,
	t_video_usage int not null default 0,
	t_audio_usage int not null default 0,
	t_other_usage int not null default 0,
	-- t_pic_usage int not null default 0,
	left_count int not null default 0,
	constraint `ms_refer_teacher` foreign key (`tid`) references `teacher`(`tid`) on delete cascade on update cascade,
	constraint `ms_refer_course` foreign key (`coid`) references `course` (`coid`) on delete cascade on update cascade
)ENGINE=innodb DEFAULT CHARSET=utf8 auto_increment=1;

CREATE TABLE anouncement(
	anid  int  not null  primary key auto_increment,
	tid  int not null,
	coid int not null,
	adate  datetime not null,
	title  varchar(30) not null,
	content varchar(100) not null,
	type char(1) not null default "n",
	read_count int not null default 0,
	constraint `anounce_refer_teacher`foreign key (`tid`) references `teacher`(`tid`) on delete cascade on update cascade,
	constraint `anounce_refer_course`foreign key (`coid`) references `course`(`coid`) on delete cascade on update cascade
)ENGINE=innodb DEFAULT CHARSET=utf8 auto_increment=1;

CREATE TABLE message(
	mid  int  not null  primary key auto_increment,
	refer_mid int, #有时很多私信是和最开始发的一条联系的
	fromid int,
	toid int,
	fromtype int,
	totype  int,
	mdate  datetime not null,
	content varchar(200) not null,
	title  varchar(40) not null,
	ifread bit(1) not null default 0,
	constraint `rmid_refer_mid` foreign key (`refer_mid`) references `message`(`mid`) on delete cascade on update cascade
)ENGINE=innodb DEFAULT CHARSET=utf8 auto_increment=1;

CREATE TABLE student_info(
	sid	      int not null,
	name	  varchar(20) not null,
	gender	  char(1) not null,
	phone     varchar(20),
	email     varchar(50),
	college   varchar(30) not null,
	major     varchar(30) not null,
	constraint `sinfo_refer_student` foreign key (`sid`) references `student`(`sid`) on delete cascade on update cascade
)ENGINE=innodb DEFAULT CHARSET=utf8 auto_increment=1;

CREATE TABLE attend(
	sid       int not null,
	clid      int not null,
	gid       int,
	constraint `attent_refer_student` foreign key (`sid`) references `student`(`sid`) on delete cascade on update cascade,
	constraint `attend_refer_class` foreign key (`clid`) references `class`(`clid`) on delete cascade on update cascade,
	constraint `attend_refer_group` foreign key (`gid`) references `study_group`(`gid`) on delete cascade on update cascade
)ENGINE=innodb DEFAULT CHARSET=utf8 auto_increment=1;

CREATE TABLE homework(
	hid   int not null  primary key auto_increment,
	clid  int not null,
	type  char(1) not null,
	name  varchar(20) not null,
	end_t datetime  not null,
	hard_ddl datetime not null,
	begin_t datetime not null,
	punish_weight float(3,2) not null,
	score_face  int not null default 0,
	score_weight  float(3,2) not null,
	finish_number int not null default 0,
	url       varchar(200) not null,
	constraint `homework_refer_class` foreign key (`clid`) references `class`(`clid`) on delete cascade on update cascade
)ENGINE=innodb DEFAULT CHARSET=utf8 auto_increment=1;

CREATE TABLE hw_result(
	rid       int not null  primary key auto_increment,
	hid       int not null,
	sid       int not null,
	type      char(1) not null,
    uploadtime datetime,
	ifcorrected bit(1) not null default 0,
	score     int not null default 0,
	comment   varchar(200) not null default "",
	url       varchar(200) not null default "",
	constraint `hresult_refer_student` foreign key (`sid`) references `student`(`sid`) on delete cascade on update cascade,
	constraint `hresult_refer_homework`foreign key (`hid`) references `homework`(`hid`) on delete cascade on update cascade
)ENGINE=innodb DEFAULT CHARSET=utf8 auto_increment=1;

CREATE TABLE ta_permission(
	clid    int not null,
	p_ma_upload  bit(1) not null,
	p_ma_modify  bit(1) not null,
	p_ma_delete  bit(1) not null,
	p_hw_deliver bit(1) not null,
	p_hw_modify	 bit(1) not null,
	p_hw_review	 bit(1) not null,
	p_BBS_reply	 bit(1) not null,
	p_nt_deliver bit(1) not null,
	p_nt_modify  bit(1) not null,
	p_nt_delete  bit(1) not null,
	p_ta_info    bit(1) not null,
	constraint `tapermiss_refer_class` foreign key (`clid`) references `class`(`clid`) on delete cascade on update cascade
)ENGINE=innodb DEFAULT CHARSET=utf8 auto_increment=1;

CREATE TABLE ta_info(
	taid   int not null,
	name   varchar(20) not null,
	gender char(1) not null,
	id     varchar(11) not null,
	college varchar(30) not null,
	major  varchar(30) not null,
	phone  varchar(20),
	email  varchar(50),
	constraint `tainfo_refer_ta` foreign key (`taid`) references `ta_assist`(`taid`) on delete cascade on update cascade
)ENGINE=innodb DEFAULT CHARSET=utf8 auto_increment=1;

CREATE TABLE teacher_info(
	tid	      int not null,
	name	  varchar(20) not null,
	gender	  char(1) not null,
	phone     varchar(20),
	email     varchar(50),
	college   varchar(30) not null,
	department     varchar(30) not null,
	position  varchar(20) not null,
	education varchar(20) not null,
	direction varchar(30) not null,
	past_evaluation varchar(100) not null,
	desc_achive varchar(200) not null,
	desc_teach_type varchar(100) not null,
	desc_publish varchar(100) not null,
	desc_honor  varchar(100) not null,
	desc_more varchar(100) not null,
	constraint `tinfo_refer_teacher` foreign key (`tid`) references `teacher`(`tid`) on delete cascade on update cascade
)ENGINE=innodb DEFAULT CHARSET=utf8 auto_increment=1;

CREATE TABLE material(
	mid  int not null  primary key auto_increment,
	father int default 0,
	type varchar(10) not null,
    name varchar(60) not null,
	size int not null,
    uploadtime datetime,
	url  varchar(200),
	tid  int not null,
	coid int not null,
	download int not null default 0,
	constraint `material_refer_teacher` foreign key (`tid`) references `teacher`(`tid`) on delete cascade on update cascade,
	constraint `material_refer_course`  foreign key (`coid`) references `course`(`coid`) on delete cascade on update cascade,
	constraint `material_refer_fatherdir` foreign key (`father`) references `material`(`mid`) on delete cascade on update cascade
)ENGINE=innodb DEFAULT CHARSET=utf8 auto_increment=1;

CREATE TABLE course_rule(
	ruleID int not null  primary key auto_increment,
	coid  int not null,
	hw_punish_type char(1) not null default 'N',
	hw_punish_weight float(3,2),
	total_material_space int,
	constraint `rule_refer_course` foreign key (`coid`) references `course`(`coid`) on delete cascade on update cascade
)ENGINE=innodb DEFAULT CHARSET=utf8 auto_increment=1;



DELIMITER //

CREATE TRIGGER ME_DeleteStudent 
  BEFORE DELETE ON student 
FOR EACH ROW
BEGIN
  Delete FROM message WHERE totype=0 and toid= old.sid ;
  DELETE FROM message WHERE fromtype=0 and fromid=old.sid;
END;

//

DELIMITER //

CREATE TRIGGER ME_DeleteTeacher 
  BEFORE DELETE ON teacher 
FOR EACH ROW
BEGIN
  Delete FROM message WHERE totype=1 and toid= old.tid ;
  DELETE FROM message WHERE fromtype=1 and fromid=old.tid;
END;

//

DELIMITER //

CREATE TRIGGER ME_DeleteTa
  BEFORE DELETE ON ta_assist 
FOR EACH ROW
BEGIN
  Delete FROM message WHERE totype=2 and toid= old.taid ;
  DELETE FROM message WHERE fromtype=2 and fromid=old.taid;
END;



