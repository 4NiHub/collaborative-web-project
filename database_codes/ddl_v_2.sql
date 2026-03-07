CREATE SCHEMA v_2;
CREATE TABLE v_2.news (
	news_id serial primary key,
	title varchar(200) not null,
	body varchar(3000) NOT NULL,
	creation_time timestamp NOT NULL,
	UNIQUE (title, body, creation_time)
);
CREATE TABLE v_2.applications (
	application_id serial primary key,
	student_id int4 not null,--FK
	job_id int4 NOT NULL,--FK
	resume_url varchar(500) not null,
	creation_time timestamp NOT NULL,
	status varchar(50) not null,
	UNIQUE (student_id, job_id)
);
CREATE TABLE v_2.jobs (
	job_id serial primary key,
	title varchar(200) not null,
	body varchar(3000) NOT NULL,
	creation_time timestamp NOT NULL,
	status varchar(50) not null,
	UNIQUE (title, body, creation_time)
);
CREATE TABLE v_2.participants (
	participant_id serial primary key,
	student_id int4 not null,--FK
	event_id int4 not null,--FK
	registration_time timestamp not null,
	UNIQUE (student_id, event_id)
);
CREATE TABLE v_2.students (
	student_id serial primary key,
	user_id int4 unique not null,--FK
	name varchar(200) not null,
	surname varchar(200) not null,
	entry_year int4 not null,
	group_id int4 not null,--FK
	email varchar(200) unique not null,
	phone_number varchar(50) unique not null
);
CREATE TABLE v_2.attendance (
	attendee_id serial primary key,
	student_id int4 not null,--FK
	session_id int4 not null,--FK
	session_date timestamp not null,
	is_present bool not null,
	UNIQUE (student_id, session_id, session_date)
);
CREATE TABLE v_2.submissions (
	submission_id serial primary key,
	assignment_id int4 not null,--FK
	student_id int4 not null,--FK
	file_url varchar(500) not null,
	submission_time timestamp not null,
	grade int4,
	grade_time timestamp,
	UNIQUE (assignment_id, student_id)
);
CREATE TABLE v_2.notifications (
	notification_id serial primary key,
	user_id int4 not null,--FK
	title varchar(200) not null,
	body varchar(500) not null,
	creation_time timestamp not null,
	is_active bool not null,
	UNIQUE (user_id, title, body, creation_time)
);
CREATE TABLE v_2.events (
	event_id serial primary key,
	title varchar(200) not null,
	body varchar(3000) not null,
	registration_url varchar(500) not null,
	event_time timestamp not null,
	status varchar(50) NOT NULL,
	UNIQUE (title, body, event_time)
);
CREATE TABLE v_2.groups (
	group_id serial primary key,
	group_name varchar(50) NOT NULL,
	group_level int4 not null,
	is_active bool NOT null,
	creation_date date NOT NULL,
	UNIQUE (group_name, group_level, creation_date)
);
CREATE TABLE v_2.timetable (
	session_id serial primary key,
	subject_group_id int4 not null,--FK
	time_slot int4 not null,
	day_slot int4 not null,
	room_number int4 not null,
	UNIQUE (time_slot, day_slot, room_number)
);
CREATE TABLE v_2.assignments (
	assignment_id serial primary key,
	title varchar(200) NOT NULL,
	subject_id int4 not null,--FK
	body varchar(500),
	weight int4 NOT NULL,
	due_time timestamp,
	file_url varchar(500)
);
CREATE TABLE v_2.users (
	user_id serial primary key,
	role_id int4 not null,--FK
	login varchar(200) UNIQUE NOT NULL,
	password_hash varchar(500) NOT NULL,
	creation_time timestamp NOT NULL
);
CREATE TABLE v_2.mentors (
	mentor_id serial primary key,
	user_id int4 not null,--FK
	name varchar(200) not null,
	surname varchar(200) not null,
	email varchar(200) UNIQUE NOT NULL,
	phone_number varchar(50) UNIQUE NOT NULL
);
CREATE TABLE v_2.subjects_groups_bridge_table (
	subject_group_id serial primary key,
	subject_id int4 NOT NULL,--fk
	group_id int4 NOT NULL,
	UNIQUE (subject_id, group_id)
);
CREATE TABLE v_2.subjects (
	subject_id serial primary key,
	name varchar(200) unique NOT NULL,
	mentor_id int4 NOT NULL--fk
);
CREATE TABLE v_2.messages (
	message_id serial primary key,
	sender_id int4 NOT NULL,--fk
	receiver_id int4 NOT NULL,--fk
	body varchar(500) NOT NULL,
	message_time timestamp NOT null
);
CREATE TABLE v_2.roles (
	role_id serial primary key,
	role_name varchar(50) UNIQUE NOT NULL
);
CREATE TABLE v_2.staff (
	staff_id serial primary key,
	user_id int4 NOT NULL,--fk
	name varchar(200) not null,
	surname varchar(200) not null,
	email varchar(200) UNIQUE NOT NULL,
	phone_number varchar(50) UNIQUE NOT NULL,
	job_position varchar(200)
);

ALTER TABLE v_2.applications ADD CONSTRAINT
	FOREIGN KEY (student_id) REFERENCES v_2.students(student_id);
ALTER TABLE v_2.applications ADD CONSTRAINT
	FOREIGN KEY (job_id) REFERENCES v_2.jobs(job_id);
ALTER TABLE v_2.participants ADD CONSTRAINT
	FOREIGN KEY (event_id) REFERENCES v_2.events(event_id);
ALTER TABLE v_2.participants ADD CONSTRAINT
	FOREIGN KEY (student_id) REFERENCES v_2.students(student_id);
ALTER TABLE v_2.students ADD CONSTRAINT
	FOREIGN KEY (group_id) REFERENCES v_2.groups(group_id);
ALTER TABLE v_2.students ADD CONSTRAINT
	FOREIGN KEY (user_id) REFERENCES v_2.users(user_id);
ALTER TABLE v_2.attendance ADD CONSTRAINT
	FOREIGN KEY (student_id) REFERENCES v_2.students(student_id);
ALTER TABLE v_2.attendance ADD CONSTRAINT
	FOREIGN KEY (session_id) REFERENCES v_2.timetable(session_id);
ALTER TABLE v_2.submissions ADD CONSTRAINT
	FOREIGN KEY (assignment_id) REFERENCES v_2.assignments(assignment_id);
ALTER TABLE v_2.submissions ADD CONSTRAINT
	FOREIGN KEY (student_id) REFERENCES v_2.students(student_id);
ALTER TABLE v_2.notifications ADD CONSTRAINT
	FOREIGN KEY (user_id) REFERENCES v_2.users(user_id);
ALTER TABLE v_2.timetable ADD CONSTRAINT
	FOREIGN KEY (subject_group_id) REFERENCES v_2.subjects_groups_bridge_table(subject_group_id);
ALTER TABLE v_2.assignments ADD CONSTRAINT
	FOREIGN KEY (subject_id) REFERENCES v_2.subjects(subject_id);
ALTER TABLE v_2.users ADD CONSTRAINT
	FOREIGN KEY (role_id) REFERENCES v_2.roles(role_id);
ALTER TABLE v_2.mentors ADD CONSTRAINT
	FOREIGN KEY (user_id) REFERENCES v_2.users(user_id);
ALTER TABLE v_2.subjects_groups_bridge_table ADD CONSTRAINT
	FOREIGN KEY (subject_id) REFERENCES v_2.subjects(subject_id);
ALTER TABLE v_2.subjects_groups_bridge_table ADD CONSTRAINT
	FOREIGN KEY (group_id) REFERENCES v_2.groups(group_id);
ALTER TABLE v_2.messages ADD CONSTRAINT
	FOREIGN KEY (sender_id) REFERENCES v_2.users(user_id);
ALTER TABLE v_2.messages ADD CONSTRAINT
	FOREIGN KEY (receiver_id) REFERENCES v_2.users(user_id);
ALTER TABLE v_2.staff ADD CONSTRAINT
	FOREIGN KEY (user_id) REFERENCES v_2.users(user_id);


