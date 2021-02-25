--
-- PostgreSQL database dump
--

SET statement_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = off;
SET check_function_bodies = false;
SET client_min_messages = warning;
SET escape_string_warning = off;

SET search_path = public, pg_catalog;

SET default_tablespace = '';

SET default_with_oids = false;

--
-- Name: files; Type: TABLE; Schema: public; Owner: intra; Tablespace: 
--

CREATE TABLE files (
    id integer NOT NULL,
    dir_id integer DEFAULT 0 NOT NULL,
    url character varying,
    title character varying(255),
    user_id integer DEFAULT 0 NOT NULL,
    size character varying(24),
    count integer DEFAULT 0,
    describe text,
    author character varying(150),
    exts character varying(200),
    lang character varying(4),
    "position" smallint DEFAULT 0,
    files character varying,
    type integer,
    object_id integer,
    downloads integer DEFAULT 0 NOT NULL,
    admin_id integer DEFAULT 0,
    date integer
);


ALTER TABLE public.files OWNER TO intra;

--
-- Name: files_dirs; Type: TABLE; Schema: public; Owner: intra; Tablespace: 
--

CREATE TABLE files_dirs (
    id integer NOT NULL,
    title character varying NOT NULL,
    "position" smallint,
    parent_id integer,
    object_id integer DEFAULT 0,
    type integer DEFAULT 0
);


ALTER TABLE public.files_dirs OWNER TO intra;

--
-- Name: files_dirs_id_seq; Type: SEQUENCE; Schema: public; Owner: intra
--

CREATE SEQUENCE files_dirs_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.files_dirs_id_seq OWNER TO intra;

--
-- Name: files_dirs_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: intra
--

ALTER SEQUENCE files_dirs_id_seq OWNED BY files_dirs.id;


--
-- Name: files_dirs_id_seq; Type: SEQUENCE SET; Schema: public; Owner: intra
--

SELECT pg_catalog.setval('files_dirs_id_seq', 3, true);


--
-- Name: files_id_seq; Type: SEQUENCE; Schema: public; Owner: intra
--

CREATE SEQUENCE files_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.files_id_seq OWNER TO intra;

--
-- Name: files_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: intra
--

ALTER SEQUENCE files_id_seq OWNED BY files.id;


--
-- Name: files_id_seq; Type: SEQUENCE SET; Schema: public; Owner: intra
--

SELECT pg_catalog.setval('files_id_seq', 4, true);


--
-- Name: gallery_albums; Type: TABLE; Schema: public; Owner: intra; Tablespace: 
--

CREATE TABLE gallery_albums (
    id integer NOT NULL,
    album_name character varying(255),
    parent_id integer,
    salt character varying(32)
);


ALTER TABLE public.gallery_albums OWNER TO intra;

--
-- Name: gallery_albums_id_seq; Type: SEQUENCE; Schema: public; Owner: intra
--

CREATE SEQUENCE gallery_albums_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.gallery_albums_id_seq OWNER TO intra;

--
-- Name: gallery_albums_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: intra
--

ALTER SEQUENCE gallery_albums_id_seq OWNED BY gallery_albums.id;


--
-- Name: gallery_albums_id_seq; Type: SEQUENCE SET; Schema: public; Owner: intra
--

SELECT pg_catalog.setval('gallery_albums_id_seq', 9, true);


--
-- Name: gallery_comments; Type: TABLE; Schema: public; Owner: intra; Tablespace: 
--

CREATE TABLE gallery_comments (
    id integer NOT NULL,
    user_id integer,
    body text,
    photo_id integer,
    add_ts integer,
    type_id integer,
    obj_id integer
);


ALTER TABLE public.gallery_comments OWNER TO intra;

--
-- Name: gallery_files; Type: TABLE; Schema: public; Owner: intra; Tablespace: 
--

CREATE TABLE gallery_files (
    id integer NOT NULL,
    salt character varying(255),
    album_id integer,
    description text
);


ALTER TABLE public.gallery_files OWNER TO intra;

--
-- Name: gallery_files_id_seq; Type: SEQUENCE; Schema: public; Owner: intra
--

CREATE SEQUENCE gallery_files_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.gallery_files_id_seq OWNER TO intra;

--
-- Name: gallery_files_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: intra
--

ALTER SEQUENCE gallery_files_id_seq OWNED BY gallery_files.id;


--
-- Name: gallery_files_id_seq; Type: SEQUENCE SET; Schema: public; Owner: intra
--

SELECT pg_catalog.setval('gallery_files_id_seq', 7, true);


--
-- Name: jqcalendar; Type: TABLE; Schema: public; Owner: intra; Tablespace: 
--

CREATE TABLE jqcalendar (
    id integer NOT NULL,
    subject character varying(1000) DEFAULT NULL::character varying,
    location character varying(200) DEFAULT NULL::character varying,
    description character varying(255) DEFAULT NULL::character varying,
    starttime timestamp without time zone,
    endtime timestamp without time zone,
    isalldayevent integer,
    color character varying(200) DEFAULT NULL::character varying,
    recurringrule character varying(500) DEFAULT NULL::character varying,
    user_id integer,
    type integer
);


ALTER TABLE public.jqcalendar OWNER TO intra;

--
-- Name: jqcalendar_id_seq; Type: SEQUENCE; Schema: public; Owner: intra
--

CREATE SEQUENCE jqcalendar_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.jqcalendar_id_seq OWNER TO intra;

--
-- Name: jqcalendar_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: intra
--

ALTER SEQUENCE jqcalendar_id_seq OWNED BY jqcalendar.id;


--
-- Name: jqcalendar_id_seq; Type: SEQUENCE SET; Schema: public; Owner: intra
--

SELECT pg_catalog.setval('jqcalendar_id_seq', 22, true);


--
-- Name: news; Type: TABLE; Schema: public; Owner: intra; Tablespace: 
--

CREATE TABLE news (
    id integer NOT NULL,
    title character varying(512),
    anons character varying(1024),
    text text,
    user_id integer,
    salt character varying(32),
    important integer DEFAULT 0 NOT NULL
);


ALTER TABLE public.news OWNER TO intra;

--
-- Name: news_id_seq; Type: SEQUENCE; Schema: public; Owner: intra
--

CREATE SEQUENCE news_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.news_id_seq OWNER TO intra;

--
-- Name: news_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: intra
--

ALTER SEQUENCE news_id_seq OWNED BY news.id;


--
-- Name: news_id_seq; Type: SEQUENCE SET; Schema: public; Owner: intra
--

SELECT pg_catalog.setval('news_id_seq', 3, true);


--
-- Name: photo_comments_id_seq; Type: SEQUENCE; Schema: public; Owner: intra
--

CREATE SEQUENCE photo_comments_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.photo_comments_id_seq OWNER TO intra;

--
-- Name: photo_comments_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: intra
--

ALTER SEQUENCE photo_comments_id_seq OWNED BY gallery_comments.id;


--
-- Name: photo_comments_id_seq; Type: SEQUENCE SET; Schema: public; Owner: intra
--

SELECT pg_catalog.setval('photo_comments_id_seq', 9, true);


--
-- Name: user_auth; Type: TABLE; Schema: public; Owner: intra; Tablespace: 
--

CREATE TABLE user_auth (
    id integer NOT NULL,
    login character varying(64) NOT NULL,
    password character varying(32) NOT NULL,
    credentials character varying DEFAULT ''::character varying NOT NULL,
    created_ts integer DEFAULT 0 NOT NULL,
    creator integer DEFAULT 0
);


ALTER TABLE public.user_auth OWNER TO intra;

--
-- Name: user_auth_id_seq; Type: SEQUENCE; Schema: public; Owner: intra
--

CREATE SEQUENCE user_auth_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.user_auth_id_seq OWNER TO intra;

--
-- Name: user_auth_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: intra
--

ALTER SEQUENCE user_auth_id_seq OWNED BY user_auth.id;


--
-- Name: user_auth_id_seq; Type: SEQUENCE SET; Schema: public; Owner: intra
--

SELECT pg_catalog.setval('user_auth_id_seq', 9, true);


--
-- Name: user_data; Type: TABLE; Schema: public; Owner: intra; Tablespace: 
--

CREATE TABLE user_data (
    user_id integer NOT NULL,
    first_name character varying(64),
    last_name character varying(64),
    father_name character varying(64),
    country_id integer,
    region_id integer,
    city_id integer,
    address character varying(255),
    function character varying(255),
    photo_salt character varying(8),
    gender character varying(2) DEFAULT 'm'::"char" NOT NULL,
    mobile_phone character varying(50),
    work_phone character varying(50),
    home_phone character varying(50),
    birthday integer,
    email character varying(100),
    icq character varying(15),
    skype character varying(50),
    notify text DEFAULT true NOT NULL,
    work_begin integer DEFAULT 9 NOT NULL,
    work_end integer DEFAULT 18 NOT NULL,
    work_start_date integer,
    work_quit_date integer,
    quit_reason text,
    cabinet integer,
    card integer DEFAULT 0 NOT NULL,
    collector_email_access text,
    network_source_access character varying(255),
    specific_software text,
    workplace_design text,
    private_email character varying(255),
    passport_data character varying(512),
    test_period_end integer,
    test_period_results text
);


ALTER TABLE public.user_data OWNER TO intra;

--
-- Name: work_miss; Type: TABLE; Schema: public; Owner: intra; Tablespace: 
--

CREATE TABLE work_miss (
    id integer NOT NULL,
    user_id integer,
    begin integer,
    "end" integer,
    reason text
);


ALTER TABLE public.work_miss OWNER TO intra;

--
-- Name: work_miss_id_seq; Type: SEQUENCE; Schema: public; Owner: intra
--

CREATE SEQUENCE work_miss_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.work_miss_id_seq OWNER TO intra;

--
-- Name: work_miss_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: intra
--

ALTER SEQUENCE work_miss_id_seq OWNED BY work_miss.id;


--
-- Name: work_miss_id_seq; Type: SEQUENCE SET; Schema: public; Owner: intra
--

SELECT pg_catalog.setval('work_miss_id_seq', 1, false);


--
-- Name: work_visits; Type: TABLE; Schema: public; Owner: intra; Tablespace: 
--

CREATE TABLE work_visits (
    id integer NOT NULL,
    user_id integer,
    date integer,
    start integer,
    "end" integer DEFAULT 0 NOT NULL,
    late_reason text,
    gone_reason text
);


ALTER TABLE public.work_visits OWNER TO intra;

--
-- Name: work_time_id_seq; Type: SEQUENCE; Schema: public; Owner: intra
--

CREATE SEQUENCE work_time_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.work_time_id_seq OWNER TO intra;

--
-- Name: work_time_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: intra
--

ALTER SEQUENCE work_time_id_seq OWNED BY work_visits.id;


--
-- Name: work_time_id_seq; Type: SEQUENCE SET; Schema: public; Owner: intra
--

SELECT pg_catalog.setval('work_time_id_seq', 9, true);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: intra
--

ALTER TABLE files ALTER COLUMN id SET DEFAULT nextval('files_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: intra
--

ALTER TABLE files_dirs ALTER COLUMN id SET DEFAULT nextval('files_dirs_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: intra
--

ALTER TABLE gallery_albums ALTER COLUMN id SET DEFAULT nextval('gallery_albums_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: intra
--

ALTER TABLE gallery_comments ALTER COLUMN id SET DEFAULT nextval('photo_comments_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: intra
--

ALTER TABLE gallery_files ALTER COLUMN id SET DEFAULT nextval('gallery_files_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: intra
--

ALTER TABLE jqcalendar ALTER COLUMN id SET DEFAULT nextval('jqcalendar_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: intra
--

ALTER TABLE news ALTER COLUMN id SET DEFAULT nextval('news_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: intra
--

ALTER TABLE user_auth ALTER COLUMN id SET DEFAULT nextval('user_auth_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: intra
--

ALTER TABLE work_miss ALTER COLUMN id SET DEFAULT nextval('work_miss_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: intra
--

ALTER TABLE work_visits ALTER COLUMN id SET DEFAULT nextval('work_time_id_seq'::regclass);


--
-- Data for Name: files; Type: TABLE DATA; Schema: public; Owner: intra
--

INSERT INTO files (id, dir_id, url, title, user_id, size, count, describe, author, exts, lang, "position", files, type, object_id, downloads, admin_id, date) VALUES (54, 10, 'http://www.ex.ua/view/486568?r=2', 'Нація фастфуду', 29, NULL, 0, 'Дон Андерсон, директор по маркетингу сети ресторанов быстрого питания «Mickey’s», продающих гамбургеры, узнаёт, что независимая экспертиза обнаружила в полуфабрикатах кишечную палочку. Для изучения обстоятельств Андерсон направляется на мясокомбинат в штате Колорадо, являющийся поставщиком этих полуфабрикатов. Другие сюжетные линии связаны со школьницей по имени Эмбер, которая подрабатывает в забегаловке фастфуда, группой молодых антиглобалистов, а также нелегальными иммигрантами из Мексики, которые подвергаются на мясокомбинате трудовой эксплуатации в условиях травмоопасного производства.', '', NULL, '', 252, NULL, NULL, 29, 0, NULL, NULL);
INSERT INTO files (id, dir_id, url, title, user_id, size, count, describe, author, exts, lang, "position", files, type, object_id, downloads, admin_id, date) VALUES (34, 11, 'http://www.shevchenko.ua/ua/library/books/healtth/nervous_force/', 'Нервная сила', 5, NULL, 0, '', 'Поль Брег', NULL, '', 242, NULL, NULL, 5, 0, NULL, NULL);
INSERT INTO files (id, dir_id, url, title, user_id, size, count, describe, author, exts, lang, "position", files, type, object_id, downloads, admin_id, date) VALUES (85, 17, './files/library/17/3051510.pdf', 'ЗРАЗОК правильного заповнення бланку (місто обласного підпорядкування)', 2, '1.54 MB', 0, '', '', 'pdf', '', 223, 'a:1:{i:0;a:2:{s:4:"name";s:11:"3051510.pdf";s:4:"salt";i:8402546;}}', NULL, 2, 0, NULL, NULL);
INSERT INTO files (id, dir_id, url, title, user_id, size, count, describe, author, exts, lang, "position", files, type, object_id, downloads, admin_id, date) VALUES (282, 0, 'Аль-Каддафи Муаммар. Зеленая книга.fb2.zip', 'Зеленая книга', 0, '120.8 KB', 0, 'Я, простой бедуин, который ездил на осле и босым пас коз, проживший жизнь среди таких же простых людей, вручаю вам свою маленькую, состоящую из трёх частей Зелёную книгу, схожую со знаменем Иисуса, скрижалями Моисея, и краткой проповедью того, кто ехал на верблюде.', 'Аль-Каддафи Муаммар', NULL, 'ru', 5, 'a:1:{i:0;a:2:{s:4:"name";s:71:"Аль-Каддафи Муаммар. Зеленая книга.fb2.zip";s:4:"salt";i:9139880;}}', 1, 8689, 0, 0, 1307955699);
INSERT INTO files (id, dir_id, url, title, user_id, size, count, describe, author, exts, lang, "position", files, type, object_id, downloads, admin_id, date) VALUES (101, 12, './files/library/12/2443333.jpg', 'Листівка про МПУ_А5 (сторінки 2 та 3)', 1299, '1002.71 KB', 0, '', '', 'jpg', 'ua', 207, 'a:1:{i:0;a:2:{s:4:"name";s:11:"2443333.jpg";s:4:"salt";i:8482767;}}', NULL, 1299, 16, NULL, NULL);
INSERT INTO files (id, dir_id, url, title, user_id, size, count, describe, author, exts, lang, "position", files, type, object_id, downloads, admin_id, date) VALUES (97, 12, './files/library/12/7547375.jpg', 'Листівка про МПУ_А5 (сторінки 1 та 4)', 2, '1.18 MB', 0, '', '', 'jpg', 'ua', 212, 'a:1:{i:0;a:2:{s:4:"name";s:11:"7547375.jpg";s:4:"salt";i:5504017;}}', NULL, 2, 10, NULL, NULL);
INSERT INTO files (id, dir_id, url, title, user_id, size, count, describe, author, exts, lang, "position", files, type, object_id, downloads, admin_id, date) VALUES (61, 15, './files/library/0/8014625.doc', '7 духовных законов успеха', 29, '236.5 KB', 0, '', 'Дипак Чопра', 'doc', '', 242, 'a:1:{i:0;a:2:{s:4:"name";s:11:"8014625.doc";s:4:"salt";i:7653634;}}', NULL, 29, 19, NULL, NULL);
INSERT INTO files (id, dir_id, url, title, user_id, size, count, describe, author, exts, lang, "position", files, type, object_id, downloads, admin_id, date) VALUES (269, 35, 'protocol_zboriv_PPO_forma.doc', 'ФОРМА протоколу зборів ППО', 0, '39 KB', 0, '', '', NULL, 'ua', 20, 'a:1:{i:0;a:2:{s:4:"name";s:29:"protocol_zboriv_PPO_forma.doc";s:4:"salt";i:9148384;}}', NULL, 2641, 23, 0, NULL);
INSERT INTO files (id, dir_id, url, title, user_id, size, count, describe, author, exts, lang, "position", files, type, object_id, downloads, admin_id, date) VALUES (274, 0, 'Meritocracy, Cognitive Ability,and the Sources of Occupational Success.pdf', 'Meritocracy, Cognitive Ability, and the Sources of Occupational Success', 0, '1.23 MB', 0, 'Center for Demography and Ecology University of Wisconsin-Madison Meritocracy, Cognitive Ability, and the Sources of Occupational Success Robert M. Hauser CDE Working Paper', 'Robert M. Hauser', NULL, 'en', 14, 'a:1:{i:0;a:2:{s:4:"name";s:74:"Meritocracy, Cognitive Ability,and the Sources of Occupational Success.pdf";s:4:"salt";i:6699931;}}', 1, 8689, 1, 0, 1307848830);
INSERT INTO files (id, dir_id, url, title, user_id, size, count, describe, author, exts, lang, "position", files, type, object_id, downloads, admin_id, date) VALUES (159, 0, 'Закон України про політичні партії.doc', 'Закон України про політичні партії', 0, '110.5 KB', 0, '', 'Віктор Демчишин', NULL, 'ua', 142, 'a:1:{i:0;a:2:{s:4:"name";s:68:"Закон України про політичні партії.doc";s:4:"salt";i:9662290;}}', 1, 4183, 1, NULL, NULL);
INSERT INTO files (id, dir_id, url, title, user_id, size, count, describe, author, exts, lang, "position", files, type, object_id, downloads, admin_id, date) VALUES (182, 21, 'Jak_meritokrat.doc', 'Як отримати у мережі статус «Мерітократ»', 0, '31 KB', 0, '', '', NULL, 'ua', 118, 'a:1:{i:0;a:2:{s:4:"name";s:18:"Jak_meritokrat.doc";s:4:"salt";i:7557261;}}', NULL, 2641, 35, NULL, NULL);
INSERT INTO files (id, dir_id, url, title, user_id, size, count, describe, author, exts, lang, "position", files, type, object_id, downloads, admin_id, date) VALUES (166, 32, 'sudba-civilizatora.-teorija-i-praktika-gibeli-imperij.zip', 'Судьба цивилизатора. Теория и практика гибели империй', 0, '250.06 KB', 0, '', 'Александр Петрович Никонов ', NULL, 'ru', 134, 'a:1:{i:0;a:2:{s:4:"name";s:57:"sudba-civilizatora.-teorija-i-praktika-gibeli-imperij.zip";s:4:"salt";i:4017143;}}', 1, 2051, 10, NULL, NULL);
INSERT INTO files (id, dir_id, url, title, user_id, size, count, describe, author, exts, lang, "position", files, type, object_id, downloads, admin_id, date) VALUES (43, 12, './files/library/12/3162140.pdf', 'Влада достойних', 29, '5.77 MB', 0, '', '', 'pdf', 'ua', 249, 'a:1:{i:0;a:2:{s:4:"name";s:11:"3162140.pdf";s:4:"salt";i:1525097;}}', NULL, 29, 11, NULL, NULL);
INSERT INTO files (id, dir_id, url, title, user_id, size, count, describe, author, exts, lang, "position", files, type, object_id, downloads, admin_id, date) VALUES (157, 0, 'Механізми державного управління.docx', 'Механізми державного управління.', 4183, '514.52 KB', 0, '', '', NULL, 'en', 145, 'a:1:{i:0;a:2:{s:4:"name";s:65:"Механізми державного управління.docx";s:4:"salt";i:6298816;}}', 1, 4183, 1, NULL, NULL);
INSERT INTO files (id, dir_id, url, title, user_id, size, count, describe, author, exts, lang, "position", files, type, object_id, downloads, admin_id, date) VALUES (181, 21, 'Prava_v_merezi.doc', 'ПРАВА користувачів у мережі «Мерітократ»', 4, '124.5 KB', 0, '', '', NULL, 'ua', 116, 'a:1:{i:0;a:2:{s:4:"name";s:18:"Prava_v_merezi.doc";s:4:"salt";i:1398835;}}', NULL, 2641, 33, NULL, NULL);
INSERT INTO files (id, dir_id, url, title, user_id, size, count, describe, author, exts, lang, "position", files, type, object_id, downloads, admin_id, date) VALUES (237, 0, 'Bazovye_zennosti_2008_Magun.pdf', 'Bazovye_zennosti_2008_Magun.pdf', 0, '1.86 MB', 0, '', '', NULL, '', 53, 'a:1:{i:0;a:2:{s:4:"name";s:31:"Bazovye_zennosti_2008_Magun.pdf";s:4:"salt";i:8171974;}}', 1, 7862, 1, 0, NULL);
INSERT INTO files (id, dir_id, url, title, user_id, size, count, describe, author, exts, lang, "position", files, type, object_id, downloads, admin_id, date) VALUES (189, 0, 'Выборы 2012г.docx', 'Выборы 2012г.docx', 7862, '108.3 KB', 0, 'план действий.', 'Кривоносов Андрей Михайлович', NULL, '', 108, 'a:1:{i:0;a:2:{s:4:"name";s:24:"Выборы 2012г.docx";s:4:"salt";i:2813425;}}', 1, 7862, 11, NULL, NULL);
INSERT INTO files (id, dir_id, url, title, user_id, size, count, describe, author, exts, lang, "position", files, type, object_id, downloads, admin_id, date) VALUES (162, 0, 'стаття про здоровий спосіб житт.doc', 'Стаття про здоровий спосіб життя', 0, '26.5 KB', 0, '', 'Віктор Демчишин', NULL, 'ua', 138, 'a:1:{i:0;a:2:{s:4:"name";s:62:"стаття про здоровий спосіб житт.doc";s:4:"salt";i:7198534;}}', 1, 4183, 2, NULL, NULL);
INSERT INTO files (id, dir_id, url, title, user_id, size, count, describe, author, exts, lang, "position", files, type, object_id, downloads, admin_id, date) VALUES (164, 30, 'Oruell_Skotnyiy_dvor.rtf', 'Скотный двор', 7930, '1.34 MB', 0, 'Сатира на события в России начала 20-го века. Всем, кто интересуется или занимается партийным строительством', 'Джордж Оруэлл', NULL, 'ru', 137, 'a:1:{i:0;a:2:{s:4:"name";s:24:"Oruell_Skotnyiy_dvor.rtf";s:4:"salt";i:9507926;}}', 1, 7930, 2, NULL, NULL);
INSERT INTO files (id, dir_id, url, title, user_id, size, count, describe, author, exts, lang, "position", files, type, object_id, downloads, admin_id, date) VALUES (113, 19, './files/library/19/4479499.doc', 'Мерітократична ідентичність: хто такий мерітократ', 2641, '31.5 KB', 0, '', '', 'doc', 'ua', 195, 'a:1:{i:0;a:2:{s:4:"name";s:11:"4479499.doc";s:4:"salt";i:4984591;}}', NULL, 2641, 14, NULL, NULL);
INSERT INTO files (id, dir_id, url, title, user_id, size, count, describe, author, exts, lang, "position", files, type, object_id, downloads, admin_id, date) VALUES (272, 0, '', '', 0, '0 B', 0, '', '', NULL, '', 16, 'a:0:{}', 1, 5, 0, 0, 1307714579);
INSERT INTO files (id, dir_id, url, title, user_id, size, count, describe, author, exts, lang, "position", files, type, object_id, downloads, admin_id, date) VALUES (27, 5, 'http://shevchenko.ua/ru/position', 'Январские тезисы: что делать и как?', 29, NULL, 0, '', 'Игорь Шевченко', NULL, '', 245, NULL, NULL, 29, 0, NULL, NULL);
INSERT INTO files (id, dir_id, url, title, user_id, size, count, describe, author, exts, lang, "position", files, type, object_id, downloads, admin_id, date) VALUES (48, 13, 'http://www.zkh.com.ua/index.php/component/content/article/1', 'Система авто­мати­зації управління житло­во-офісними комплексами', 5, NULL, 0, ' Комп’ютерна система “ЖКГ-Соціум” призначена для комплексної авто­мати­зації управління житло­во-офісними комплексами, будівельними кооперативами та об’єд­нан­нями співвласників багато­квартир­них бу­динків (ЖКГ – житлово-комунальними господарствами). ', '', NULL, 'ua', 252, NULL, NULL, 5, 0, NULL, NULL);
INSERT INTO files (id, dir_id, url, title, user_id, size, count, describe, author, exts, lang, "position", files, type, object_id, downloads, admin_id, date) VALUES (92, 19, './files/library/14/1476944.doc', 'Базовая програма МПУ (рус)', 2, '85.5 KB', 0, '', '', 'doc', 'ru', 217, 'a:1:{i:0;a:2:{s:4:"name";s:11:"1476944.doc";s:4:"salt";i:9119154;}}', NULL, 2, 8, NULL, NULL);
INSERT INTO files (id, dir_id, url, title, user_id, size, count, describe, author, exts, lang, "position", files, type, object_id, downloads, admin_id, date) VALUES (278, 28, '2.  115  видеозаписей  к  странице  Алексея  Иванова..docx', '2).  115  Видеозаписей', 7612, '829.98 KB', 0, 'Ссылки видеофильмов. 
Документальные, художественные, познавательные.', 'Собрал:  Алексей  Иванов', NULL, 'ru', 9, 'a:1:{i:0;a:2:{s:4:"name";s:93:"2.  115  видеозаписей  к  странице  Алексея  Иванова..docx";s:4:"salt";i:3350657;}}', 1, 7612, 0, 0, 1307899650);
INSERT INTO files (id, dir_id, url, title, user_id, size, count, describe, author, exts, lang, "position", files, type, object_id, downloads, admin_id, date) VALUES (198, 27, '6.  (20 стр.)   Концепция  Общественной  Безопастности..doc', 'КОБ.   Концепция  Общественной  Безопасности.', 7612, '268 KB', 0, '', '', NULL, 'ru', 97, 'a:1:{i:0;a:2:{s:4:"name";s:96:"6.  (20 стр.)   Концепция  Общественной  Безопастности..doc";s:4:"salt";i:8697314;}}', 1, 7612, 9, NULL, NULL);
INSERT INTO files (id, dir_id, url, title, user_id, size, count, describe, author, exts, lang, "position", files, type, object_id, downloads, admin_id, date) VALUES (99, 22, './files/library/14/4223238.doc', 'Базові принципи МПУ', 2641, '35.5 KB', 0, '', 'Затверджені Рішенням Політичної Ради МПУ №4 від 10.02.2011', 'doc', 'ua', 211, 'a:1:{i:0;a:2:{s:4:"name";s:11:"4223238.doc";s:4:"salt";i:8248412;}}', NULL, 2641, 16, NULL, NULL);
INSERT INTO files (id, dir_id, url, title, user_id, size, count, describe, author, exts, lang, "position", files, type, object_id, downloads, admin_id, date) VALUES (47, 5, './files/library/5/8621871.doc', 'Інтелектуальна політика', 29, '5.98 MB', 0, '', 'Сергій Дацюк', 'doc', 'ua', 243, 'a:1:{i:0;a:2:{s:4:"name";s:11:"8621871.doc";s:4:"salt";i:4032694;}}', NULL, 29, 4, NULL, NULL);
INSERT INTO files (id, dir_id, url, title, user_id, size, count, describe, author, exts, lang, "position", files, type, object_id, downloads, admin_id, date) VALUES (25, 5, 'http://www.fosanime.tk', 'Керівництво з захоплення влади в Україні: рекомендації з майбутнього.', 29, NULL, 0, '', 'Сергій Гайдай', NULL, 'ua', 247, NULL, NULL, 29, 0, NULL, NULL);
INSERT INTO files (id, dir_id, url, title, user_id, size, count, describe, author, exts, lang, "position", files, type, object_id, downloads, admin_id, date) VALUES (261, 0, 'TestDoc.doc', 'TestDoc.doc', 0, '32.5 KB', 0, 'тест', '', NULL, '', 28, 'a:1:{i:0;a:2:{s:4:"name";s:11:"TestDoc.doc";s:4:"salt";i:4168574;}}', 9, 1337, 0, 1337, 1307622264);
INSERT INTO files (id, dir_id, url, title, user_id, size, count, describe, author, exts, lang, "position", files, type, object_id, downloads, admin_id, date) VALUES (209, 18, 'oplata vneskiv v MPU.doc', 'Порядок сплати членстких та благодійних внесків в МПУ', 0, '53 KB', 0, '', '', NULL, 'ua', 93, 'a:1:{i:0;a:2:{s:4:"name";s:24:"oplata vneskiv v MPU.doc";s:4:"salt";i:2848550;}}', NULL, 2, 7, NULL, NULL);
INSERT INTO files (id, dir_id, url, title, user_id, size, count, describe, author, exts, lang, "position", files, type, object_id, downloads, admin_id, date) VALUES (137, 14, './files/library/14/5489596.jpeg', 'Свідоцтво про реєстрацію партії', 1299, '804.17 KB', 0, '', '', 'jpeg', 'ua', 171, 'a:1:{i:0;a:2:{s:4:"name";s:12:"5489596.jpeg";s:4:"salt";i:2778830;}}', NULL, 1299, 15, NULL, NULL);
INSERT INTO files (id, dir_id, url, title, user_id, size, count, describe, author, exts, lang, "position", files, type, object_id, downloads, admin_id, date) VALUES (267, 35, 'Zayava_pro_legalizaciju_PPO_forma.doc', 'ЗАЯВА про легалізацію ППО', 0, '26 KB', 0, '', '', NULL, 'ua', 22, 'a:1:{i:0;a:2:{s:4:"name";s:37:"Zayava_pro_legalizaciju_PPO_forma.doc";s:4:"salt";i:2132445;}}', NULL, 2641, 15, 0, NULL);
INSERT INTO files (id, dir_id, url, title, user_id, size, count, describe, author, exts, lang, "position", files, type, object_id, downloads, admin_id, date) VALUES (236, 0, '2BE0936Fd01.pdf', '2BE0936Fd01.pdf', 0, '2.58 MB', 0, '', '', NULL, '', 54, 'a:1:{i:0;a:2:{s:4:"name";s:15:"2BE0936Fd01.pdf";s:4:"salt";i:1378537;}}', 1, 7862, 1, 0, NULL);
INSERT INTO files (id, dir_id, url, title, user_id, size, count, describe, author, exts, lang, "position", files, type, object_id, downloads, admin_id, date) VALUES (58, 10, 'http://www.ex.ua/view/16457?r=2', 'Когда Солнце было Богом', 5, NULL, 0, '', '', NULL, '', 247, NULL, NULL, 5, 0, NULL, NULL);
INSERT INTO files (id, dir_id, url, title, user_id, size, count, describe, author, exts, lang, "position", files, type, object_id, downloads, admin_id, date) VALUES (275, 0, 'Meritocracy and Economic Inequality.pdf', 'Meritocracy and Economic Inequality', 8689, '430.67 KB', 0, '	Most Americans strongly favor equality of opportunity if not outcome, but many are weary of poverty\\''s seeming immunity to public policy. This helps to explain the recent attention paid to cultural and genetic explanations of persistent poverty, includingclaims that economic inequality is a function of intellectual ability, as well as more subtle depictions of the United States as a meritocracy where barriers to achievement are personal--either voluntary or inherited--rather than systemic. This volume oforiginal essays by luminaries in the economic, social, and biological sciences, however, confirms mounting evidence that the connection between intelligence and inequality is surprisingly weak and demonstrates that targeted educational and economic reforms can reduce the income gap and improve the country\\''s aggregate productivity and economic well-being. It also offers a novel agenda of equal access to valuable associations.', 'Kenneth Joseph Arrow, Samuel Bowles, Steven N. Durlauf', NULL, 'en', 13, 'a:1:{i:0;a:2:{s:4:"name";s:39:"Meritocracy and Economic Inequality.pdf";s:4:"salt";i:8513687;}}', 1, 8689, 1, 0, 1307848940);
INSERT INTO files (id, dir_id, url, title, user_id, size, count, describe, author, exts, lang, "position", files, type, object_id, downloads, admin_id, date) VALUES (160, 0, 'ДЛЯ ЧОГО ПОТРІБНЕ СТРАХУВАННЯ.doc', 'Для чого потрібне  страхування?', 0, '34.5 KB', 0, '', 'Віктор Демчишин', NULL, 'ua', 141, 'a:1:{i:0;a:2:{s:4:"name";s:59:"ДЛЯ ЧОГО ПОТРІБНЕ СТРАХУВАННЯ.doc";s:4:"salt";i:6382893;}}', 1, 4183, 0, NULL, NULL);
INSERT INTO files (id, dir_id, url, title, user_id, size, count, describe, author, exts, lang, "position", files, type, object_id, downloads, admin_id, date) VALUES (239, 0, 'Instr-koord-meritokrat.doc', 'письмо', 0, '3.12 MB', 0, 'тест', '', NULL, 'ua', 51, 'a:1:{i:0;a:2:{s:4:"name";s:26:"Instr-koord-meritokrat.doc";s:4:"salt";i:2088620;}}', 9, 504, 0, 2, NULL);
INSERT INTO files (id, dir_id, url, title, user_id, size, count, describe, author, exts, lang, "position", files, type, object_id, downloads, admin_id, date) VALUES (142, 28, 'Друзья,  прошу  Распространить.  Знать правду.  (Ссылки).docx', '1).  Ссылки.  Док/фильмы.  Россия.', 7612, '30.53 KB', 0, 'Документальные  фильмы о  действительности  в  России.', 'Предоставленно  российской  стороной.', NULL, 'ru', 165, 'a:1:{i:0;a:2:{s:4:"name";s:103:"Друзья,  прошу  Распространить.  Знать правду.  (Ссылки).docx";s:4:"salt";i:9187732;}}', 1, 7612, 8, NULL, NULL);
INSERT INTO files (id, dir_id, url, title, user_id, size, count, describe, author, exts, lang, "position", files, type, object_id, downloads, admin_id, date) VALUES (87, 17, './files/library/17/6828015.doc', 'Рекомендації по збору підписів', 2, '46 KB', 0, '', '', 'doc', 'ua', 221, 'a:1:{i:0;a:2:{s:4:"name";s:11:"6828015.doc";s:4:"salt";i:6158451;}}', NULL, 2, 1, NULL, NULL);
INSERT INTO files (id, dir_id, url, title, user_id, size, count, describe, author, exts, lang, "position", files, type, object_id, downloads, admin_id, date) VALUES (45, 12, './files/library/12/5625786.pdf', 'Мерітократія - політична система майбутнього', 29, '8.79 MB', 0, '', '', 'pdf', 'ua', 246, 'a:1:{i:0;a:2:{s:4:"name";s:11:"5625786.pdf";s:4:"salt";i:1007643;}}', NULL, 29, 11, NULL, NULL);
INSERT INTO files (id, dir_id, url, title, user_id, size, count, describe, author, exts, lang, "position", files, type, object_id, downloads, admin_id, date) VALUES (270, 35, 'Instruktion_PPO_final.doc', 'ІНСТРУКЦІЯ  Про порядок створення первинних партійних організацій МПУ', 0, '70 KB', 0, '', '', NULL, 'ua', 19, 'a:1:{i:0;a:2:{s:4:"name";s:25:"Instruktion_PPO_final.doc";s:4:"salt";i:8124165;}}', NULL, 2641, 22, 0, NULL);
INSERT INTO files (id, dir_id, url, title, user_id, size, count, describe, author, exts, lang, "position", files, type, object_id, downloads, admin_id, date) VALUES (202, 32, '', 'І Н С Т Р У К Ц І Я  Про порядок створення первинних партійних організацій МПУ', 0, '0 B', 0, '', '', NULL, '', 93, 'a:0:{}', NULL, 2, 4, NULL, NULL);
INSERT INTO files (id, dir_id, url, title, user_id, size, count, describe, author, exts, lang, "position", files, type, object_id, downloads, admin_id, date) VALUES (143, 29, '1.  Dimitry.  ПУТЬ  ЛЮБВИ..doc', '1).  ПУТЬ  ЛЮБВИ', 7612, '202 KB', 0, 'Правильная постановка цели.', 'Dimitry', NULL, 'ru', 164, 'a:1:{i:0;a:2:{s:4:"name";s:39:"1.  Dimitry.  ПУТЬ  ЛЮБВИ..doc";s:4:"salt";i:5855500;}}', 1, 7612, 22, NULL, NULL);
INSERT INTO files (id, dir_id, url, title, user_id, size, count, describe, author, exts, lang, "position", files, type, object_id, downloads, admin_id, date) VALUES (104, 20, './files/library/20/9854824.doc', 'Автобіографія кандидата у представники ГО \\"Мерітократична Україна\\" у ВНЗ', 2641, '52 KB', 0, '', '', 'doc', 'ua', 204, 'a:1:{i:0;a:2:{s:4:"name";s:11:"9854824.doc";s:4:"salt";i:9103324;}}', NULL, 2641, 4, NULL, NULL);
INSERT INTO files (id, dir_id, url, title, user_id, size, count, describe, author, exts, lang, "position", files, type, object_id, downloads, admin_id, date) VALUES (114, 19, './files/library/19/6625628.doc', 'Січневі тези: що і як робити?', 2641, '72 KB', 0, '', '', 'doc', 'ua', 194, 'a:1:{i:0;a:2:{s:4:"name";s:11:"6625628.doc";s:4:"salt";i:1340646;}}', NULL, 2641, 16, NULL, NULL);
INSERT INTO files (id, dir_id, url, title, user_id, size, count, describe, author, exts, lang, "position", files, type, object_id, downloads, admin_id, date) VALUES (127, 22, './files/library/22/8498605.doc', 'ФОРМА плану діяльності регіонального активу МПУ', 2641, '34 KB', 0, '', '', 'doc', 'ua', 181, 'a:1:{i:0;a:2:{s:4:"name";s:11:"8498605.doc";s:4:"salt";i:6442201;}}', NULL, 2641, 12, NULL, NULL);
INSERT INTO files (id, dir_id, url, title, user_id, size, count, describe, author, exts, lang, "position", files, type, object_id, downloads, admin_id, date) VALUES (268, 35, 'protocol_zboriv_PPO_zrazok.pdf', 'ЗРАЗОК заповнення протоколу зборів ППО', 0, '113.33 KB', 0, '', '', NULL, 'ua', 21, 'a:2:{i:0;a:2:{s:4:"name";s:30:"protocol_zboriv_PPO_zrazok.pdf";s:4:"salt";i:9583631;}i:1;a:2:{s:4:"name";s:32:"1-protocol_zboriv_PPO_zrazok.doc";s:4:"salt";i:2813702;}}', NULL, 2641, 26, 0, NULL);
INSERT INTO files (id, dir_id, url, title, user_id, size, count, describe, author, exts, lang, "position", files, type, object_id, downloads, admin_id, date) VALUES (266, 35, 'Zayava_legalizaciju_PPO_zrazok.pdf', 'ЗРАЗОК заповнення заяви про легалізацію ППО', 0, '77.74 KB', 0, '', '', NULL, 'ua', 23, 'a:2:{i:0;a:2:{s:4:"name";s:34:"Zayava_legalizaciju_PPO_zrazok.pdf";s:4:"salt";i:9740940;}i:1;a:2:{s:4:"name";s:36:"1-Zayava_legalizaciju_PPO_zrazok.doc";s:4:"salt";i:4697961;}}', NULL, 2641, 26, 0, NULL);
INSERT INTO files (id, dir_id, url, title, user_id, size, count, describe, author, exts, lang, "position", files, type, object_id, downloads, admin_id, date) VALUES (277, 37, '148-1.jpg', '148-1.jpg', 0, '1.55 MB', 0, '', '', NULL, 'ru', 10, 'a:1:{i:0;a:2:{s:4:"name";s:9:"148-1.jpg";s:4:"salt";i:2492795;}}', 1, 3827, 35, 0, 1307867238);
INSERT INTO files (id, dir_id, url, title, user_id, size, count, describe, author, exts, lang, "position", files, type, object_id, downloads, admin_id, date) VALUES (49, 10, 'http://www.ex.ua/view/123484?r=2', 'Прекрасная зеленая', 5, NULL, 0, 'Мила прибывает на нашу прекрасную зелёную Землю с другой планеты, являющейся праобразом более гармоничной и приближённой к природе жизни. На Земле она знакомится с доктором Максом, его семьёй и другими людьми, на примере которых познаёт всю несуразность нашего современного систематизированного технократического мира. Однако Мила обладает удивительной способностью, она может отключать людей от довлеющей над ними системы и стереотипов, после чего люди становятся теми, кем они являются на самом деле, начинают вести себя искренне, порой абсурдно с точки зрение современного мира … ', '', NULL, '', 248, NULL, NULL, 5, 0, NULL, NULL);
INSERT INTO files (id, dir_id, url, title, user_id, size, count, describe, author, exts, lang, "position", files, type, object_id, downloads, admin_id, date) VALUES (117, 12, './files/library/12/3629695.pdf', 'Календар настінний, формат А3', 1299, '552.01 KB', 0, '', '', 'pdf', '', 190, 'a:1:{i:0;a:2:{s:4:"name";s:11:"3629695.pdf";s:4:"salt";i:4057213;}}', NULL, 1299, 12, NULL, NULL);
INSERT INTO files (id, dir_id, url, title, user_id, size, count, describe, author, exts, lang, "position", files, type, object_id, downloads, admin_id, date) VALUES (201, 0, '121.jpg', '121.jpg', 0, '969.15 KB', 0, '', '', NULL, 'ru', 94, 'a:1:{i:0;a:2:{s:4:"name";s:7:"121.jpg";s:4:"salt";i:4222551;}}', 1, 3827, 27, NULL, NULL);
INSERT INTO files (id, dir_id, url, title, user_id, size, count, describe, author, exts, lang, "position", files, type, object_id, downloads, admin_id, date) VALUES (51, 10, 'http://www.ex.ua/view/331938?r=2', 'Свой человек', 29, NULL, 0, 'Джеффри Уайгэнда увольняют с поста вице-президента крупнейшей табачной компании за то, что он протестует против использования в табачной продукции компонента, вызывающего у курильщиков наркотическую зависимость. Лоуэлл Бергман, продюсер популярнейшего телешоу \\"60 минут\\", узнает об этом и убеждает Уайгэнда выступить с разоблачительным интервью по телевидению. Естественно, это очень не нравится столпам табачной индустрии, заверяющих в безвредности производимой продукции. Олигархи принимают самые суровые меры, чтобы информация не стала достоянием гласности. От Уайгэнда уходит жена, Лоуэлла Бергмана отправляют в отпуск. Там, где задействованы большие деньги, бесполезно бороться за справедливость, но Лоуэлл и Уайгэнд не прекращают борьбы.', '', NULL, '', 251, NULL, NULL, 29, 0, NULL, NULL);
INSERT INTO files (id, dir_id, url, title, user_id, size, count, describe, author, exts, lang, "position", files, type, object_id, downloads, admin_id, date) VALUES (62, 10, 'http://www.ex.ua/view/108866', 'Плати вперед (Pay it forward)', 5, NULL, 0, 'Представьте себе. Вы оказываете кому либо существенную услугу и просите его, или ее, отблагодарить не вас, а трех других людей, которые, в свою очередь, отблагодарят еще троих, и так далее, в мировом масштабе, распространяя доброту и тепло. Невозможно? Школьник Тревор МакКинни не признает это слово. ', '', NULL, '', 246, NULL, NULL, 5, 0, NULL, NULL);
INSERT INTO files (id, dir_id, url, title, user_id, size, count, describe, author, exts, lang, "position", files, type, object_id, downloads, admin_id, date) VALUES (129, 22, './files/library/22/6394483.doc', 'ФОРМА плану діяльності ІГ ППО', 2641, '34 KB', 0, '', '', 'doc', 'ua', 179, 'a:1:{i:0;a:2:{s:4:"name";s:11:"6394483.doc";s:4:"salt";i:3973485;}}', NULL, 2641, 17, NULL, NULL);
INSERT INTO files (id, dir_id, url, title, user_id, size, count, describe, author, exts, lang, "position", files, type, object_id, downloads, admin_id, date) VALUES (264, 35, 'Zayava_oblik_MPU_zrazok.pdf', 'ЗРАЗОК заповнення заяви про взяття ППО на партійний облік', 0, '61.71 KB', 0, '', '', NULL, 'ua', 25, 'a:2:{i:0;a:2:{s:4:"name";s:27:"Zayava_oblik_MPU_zrazok.pdf";s:4:"salt";i:1129033;}i:1;a:2:{s:4:"name";s:29:"1-Zayava_oblik_MPU_zrazok.doc";s:4:"salt";i:2063624;}}', NULL, 2641, 12, 0, NULL);
INSERT INTO files (id, dir_id, url, title, user_id, size, count, describe, author, exts, lang, "position", files, type, object_id, downloads, admin_id, date) VALUES (170, 32, 'Никонов Александр Петрович. Наполеон. Попытка # 2 - modernlib.ru.doc.zip', 'Наполеон. Попытка № 2', 0, '284.69 KB', 0, '', 'Александр Петрович Никонов ', NULL, 'ru', 130, 'a:1:{i:0;a:2:{s:4:"name";s:111:"Никонов Александр Петрович. Наполеон. Попытка # 2 - modernlib.ru.doc.zip";s:4:"salt";i:6840462;}}', 1, 2051, 22, NULL, NULL);
INSERT INTO files (id, dir_id, url, title, user_id, size, count, describe, author, exts, lang, "position", files, type, object_id, downloads, admin_id, date) VALUES (279, 28, '1.  414 видеозаписей  Леонида  Писулина.docx', '3).  414  Видеозаписей', 7612, '3.42 MB', 0, 'Видеофильмы различной  тематики... ', 'Собрал: Леонид Писулин', NULL, 'ru', 8, 'a:1:{i:0;a:2:{s:4:"name";s:71:"1.  414 видеозаписей  Леонида  Писулина.docx";s:4:"salt";i:6526273;}}', 1, 7612, 2, 0, 1307900214);
INSERT INTO files (id, dir_id, url, title, user_id, size, count, describe, author, exts, lang, "position", files, type, object_id, downloads, admin_id, date) VALUES (158, 0, 'соц.консультування.doc', 'Соціальне консультування', 4183, '63.5 KB', 0, '', 'Віктор Демчишин', NULL, 'ua', 143, 'a:1:{i:0;a:2:{s:4:"name";s:39:"соц.консультування.doc";s:4:"salt";i:6230645;}}', 1, 4183, 0, NULL, NULL);
INSERT INTO files (id, dir_id, url, title, user_id, size, count, describe, author, exts, lang, "position", files, type, object_id, downloads, admin_id, date) VALUES (196, 0, 'http://www.ex.ua/view/1435589', 'Убить Короля', 0, NULL, 0, 'Фильм о Великой Английской Буржуазной революции.', 'Режиссер Майк Баркер', NULL, '', 99, NULL, 1, 2051, 0, NULL, NULL);
INSERT INTO files (id, dir_id, url, title, user_id, size, count, describe, author, exts, lang, "position", files, type, object_id, downloads, admin_id, date) VALUES (123, 21, './files/library/21/8674563.doc', 'Про підвищення ефективності функціонування соціально-політичної мережі «Мерітократ»', 2, '63 KB', 0, '', 'Рішення Політичної Ради МПУ №6 від 10.03.2011', 'doc', 'ua', 185, 'a:1:{i:0;a:2:{s:4:"name";s:11:"8674563.doc";s:4:"salt";i:9716856;}}', NULL, 2, 14, NULL, NULL);
INSERT INTO files (id, dir_id, url, title, user_id, size, count, describe, author, exts, lang, "position", files, type, object_id, downloads, admin_id, date) VALUES (33, 11, 'http://www.shevchenko.ua/ua/library/books/healtth/10_health_secrets/', '10 секретов здоровья', 29, NULL, 0, '', 'Адам Джексон', NULL, '', 241, NULL, NULL, 29, 1, NULL, NULL);
INSERT INTO files (id, dir_id, url, title, user_id, size, count, describe, author, exts, lang, "position", files, type, object_id, downloads, admin_id, date) VALUES (90, 17, './files/library/17/7227519.doc', 'Бланк для  збору підписів', 8, '41 KB', 0, '', '', 'doc', 'ua', 218, 'a:1:{i:0;a:2:{s:4:"name";s:11:"7227519.doc";s:4:"salt";i:6290070;}}', NULL, 8, 0, NULL, NULL);
INSERT INTO files (id, dir_id, url, title, user_id, size, count, describe, author, exts, lang, "position", files, type, object_id, downloads, admin_id, date) VALUES (64, 4, 'http://www.dt.ua/3000/4078/70580/', 'Основне в управлінні містом — системність', 2, NULL, 0, 'Інтерв\\''ю В. Гройсмана  - мера Вінниці газеті \\"Дзеркало тижня\\"', 'Інтерв\\''ю В. Гройсмана  - мера Вінниці', NULL, 'ua', 244, NULL, NULL, 2, 0, NULL, NULL);
INSERT INTO files (id, dir_id, url, title, user_id, size, count, describe, author, exts, lang, "position", files, type, object_id, downloads, admin_id, date) VALUES (273, 0, 'Perepiska_Necivka.txt', 'Переписка в сети - неэтичные высказывания', 0, '2.26 KB', 0, 'Провокационное общение с Нецивкой', '', NULL, '', 15, 'a:1:{i:0;a:2:{s:4:"name";s:21:"Perepiska_Necivka.txt";s:4:"salt";i:8734975;}}', 9, 8698, 1, 4, 1307824844);
INSERT INTO files (id, dir_id, url, title, user_id, size, count, describe, author, exts, lang, "position", files, type, object_id, downloads, admin_id, date) VALUES (119, 12, './files/library/12/9506528.pdf', 'Чашка брендована', 1299, '241.86 KB', 0, '', '', 'pdf', '', 189, 'a:1:{i:0;a:2:{s:4:"name";s:11:"9506528.pdf";s:4:"salt";i:5267064;}}', NULL, 1299, 8, NULL, NULL);
INSERT INTO files (id, dir_id, url, title, user_id, size, count, describe, author, exts, lang, "position", files, type, object_id, downloads, admin_id, date) VALUES (194, 29, '9.  (66 стр.)  Кн.  КАК  ТВОРИТЬ  СВОЮ  РЕАЛЬНОСТЬ.  РАМТА..doc', 'КАК  ТВОРИТЬ  СВОЮ  РЕАЛЬНОСТЬ.', 7612, '877 KB', 0, '', 'РАМТА', NULL, 'ru', 101, 'a:1:{i:0;a:2:{s:4:"name";s:97:"9.  (66 стр.)  Кн.  КАК  ТВОРИТЬ  СВОЮ  РЕАЛЬНОСТЬ.  РАМТА..doc";s:4:"salt";i:7434674;}}', 1, 7612, 8, NULL, NULL);
INSERT INTO files (id, dir_id, url, title, user_id, size, count, describe, author, exts, lang, "position", files, type, object_id, downloads, admin_id, date) VALUES (232, 18, 'Polozhennya_pro_clensk.vnesky_02.06.2011.doc', 'ПОЛОЖЕННЯ про членські та інші внески в МПУ', 0, '41 KB', 0, '', '', NULL, 'ua', 59, 'a:1:{i:0;a:2:{s:4:"name";s:44:"Polozhennya_pro_clensk.vnesky_02.06.2011.doc";s:4:"salt";i:2005460;}}', NULL, 2641, 40, 0, NULL);
INSERT INTO files (id, dir_id, url, title, user_id, size, count, describe, author, exts, lang, "position", files, type, object_id, downloads, admin_id, date) VALUES (133, 22, './files/library/22/3772383.doc', 'Завдання координаторів району/міста на найближчий період', 2641, '38 KB', 0, '', '', 'doc', 'ua', 177, 'a:1:{i:0;a:2:{s:4:"name";s:11:"3772383.doc";s:4:"salt";i:6717236;}}', NULL, 2641, 25, NULL, NULL);
INSERT INTO files (id, dir_id, url, title, user_id, size, count, describe, author, exts, lang, "position", files, type, object_id, downloads, admin_id, date) VALUES (152, 22, 'Пам\\''ятка.doc', 'Пам\\''ятка ЛК з розповсюдження агітаційних матеріалів', 2546, '30.5 KB', 0, '', '', NULL, 'ua', 150, 'a:1:{i:0;a:2:{s:4:"name";s:20:"Пам\\''ятка.doc";s:4:"salt";i:7451195;}}', NULL, 1337, 16, NULL, NULL);
INSERT INTO files (id, dir_id, url, title, user_id, size, count, describe, author, exts, lang, "position", files, type, object_id, downloads, admin_id, date) VALUES (128, 22, './files/library/22/3120491.doc', 'ФОРМА плану діяльності районного/міського активу МПУ', 2641, '33.5 KB', 0, '', '', 'doc', 'ua', 180, 'a:1:{i:0;a:2:{s:4:"name";s:11:"3120491.doc";s:4:"salt";i:3999536;}}', NULL, 2641, 20, NULL, NULL);
INSERT INTO files (id, dir_id, url, title, user_id, size, count, describe, author, exts, lang, "position", files, type, object_id, downloads, admin_id, date) VALUES (106, 20, './files/library/20/3349358.doc', 'Заява на отримання статусу представника ГО \\"Мерітократична Україна\\" у ВНЗ', 2641, '60 KB', 0, '', '', 'doc', 'ua', 202, 'a:1:{i:0;a:2:{s:4:"name";s:11:"3349358.doc";s:4:"salt";i:6864517;}}', NULL, 2641, 4, NULL, NULL);
INSERT INTO files (id, dir_id, url, title, user_id, size, count, describe, author, exts, lang, "position", files, type, object_id, downloads, admin_id, date) VALUES (82, 17, './files/library/17/7906187.xls', 'Перелік районів та міст обласного значення для регіонів України', 2, '116.5 KB', 0, '', '', 'xls', '', 226, 'a:1:{i:0;a:2:{s:4:"name";s:11:"7906187.xls";s:4:"salt";i:7851715;}}', NULL, 2, 0, NULL, NULL);
INSERT INTO files (id, dir_id, url, title, user_id, size, count, describe, author, exts, lang, "position", files, type, object_id, downloads, admin_id, date) VALUES (241, 0, 'instr-PPO.doc', 'instr-PPO.doc', 0, '72.5 KB', 0, '', '', NULL, '', 49, 'a:1:{i:0;a:2:{s:4:"name";s:13:"instr-PPO.doc";s:4:"salt";i:7341210;}}', 9, 999, 0, 2, NULL);
INSERT INTO files (id, dir_id, url, title, user_id, size, count, describe, author, exts, lang, "position", files, type, object_id, downloads, admin_id, date) VALUES (53, 13, './files/library/13/3111801.pdf', 'Управління житловими будинками', 31, '429.05 KB', 0, 'Посібник', 'Інститут місцевого розвитку', 'pdf', 'ua', 251, 'a:1:{i:0;a:2:{s:4:"name";s:11:"3111801.pdf";s:4:"salt";i:6612236;}}', NULL, 31, 4, NULL, NULL);
INSERT INTO files (id, dir_id, url, title, user_id, size, count, describe, author, exts, lang, "position", files, type, object_id, downloads, admin_id, date) VALUES (67, 13, 'http://www.osbbua.org/2010/08/scho-maje-znaty-spozhyvach-komunalnyh-posluh/', 'Що має знати кожен споживач житлово-комунальних послуг', 2, NULL, 0, '', 'Країна відповідальних власників', NULL, 'ua', 241, NULL, NULL, 2, 0, NULL, NULL);
INSERT INTO files (id, dir_id, url, title, user_id, size, count, describe, author, exts, lang, "position", files, type, object_id, downloads, admin_id, date) VALUES (68, 11, 'http://brilpower.narod.ru/4t.html', 'Пять тибетских жемчужин для долголетия', 2, NULL, 0, '', '', NULL, '', 242, NULL, NULL, 2, 1, NULL, NULL);
INSERT INTO files (id, dir_id, url, title, user_id, size, count, describe, author, exts, lang, "position", files, type, object_id, downloads, admin_id, date) VALUES (46, 12, './files/library/12/4113982.pdf', 'Меритократия - политическая система будущего', 29, '8.88 MB', 0, '', '', 'pdf', '', 248, 'a:1:{i:0;a:2:{s:4:"name";s:11:"4113982.pdf";s:4:"salt";i:2041398;}}', NULL, 29, 10, NULL, NULL);
INSERT INTO files (id, dir_id, url, title, user_id, size, count, describe, author, exts, lang, "position", files, type, object_id, downloads, admin_id, date) VALUES (7, 4, 'http://shevchenko.ua/ua/library/books/politic/lee-kuan-yew', 'Сингапурская история: Из \\"третьего\\" мира в \\"первый\\". Том 2.', 29, NULL, 0, '', 'Ли Куан Ю', NULL, '', 254, NULL, NULL, 29, 0, NULL, NULL);
INSERT INTO files (id, dir_id, url, title, user_id, size, count, describe, author, exts, lang, "position", files, type, object_id, downloads, admin_id, date) VALUES (163, 0, '', '', 4183, '0 B', 0, '', '', NULL, '', 139, 'a:0:{}', 1, 4183, 0, NULL, NULL);
INSERT INTO files (id, dir_id, url, title, user_id, size, count, describe, author, exts, lang, "position", files, type, object_id, downloads, admin_id, date) VALUES (280, 0, 'http://slogans.ru/index.php?id=105', 'Слоганы (политические)', 6156, NULL, 0, 'Каталог слоганов, в том числе и политические.', 'подборка', NULL, 'ru', 7, NULL, 1, 6156, 0, 0, 1307905436);
INSERT INTO files (id, dir_id, url, title, user_id, size, count, describe, author, exts, lang, "position", files, type, object_id, downloads, admin_id, date) VALUES (156, 0, 'Механізми державного управління.docx', 'Механізми державного управління.', 0, '514.52 KB', 0, '', '', NULL, 'ua', 144, 'a:1:{i:0;a:2:{s:4:"name";s:65:"Механізми державного управління.docx";s:4:"salt";i:9826129;}}', 1, 4183, 0, NULL, NULL);
INSERT INTO files (id, dir_id, url, title, user_id, size, count, describe, author, exts, lang, "position", files, type, object_id, downloads, admin_id, date) VALUES (120, 12, './files/library/12/7805825.pdf', 'Ручка брендована (Арт.1)', 2546, '4.14 MB', 0, '', '', 'pdf', '', 188, 'a:1:{i:0;a:2:{s:4:"name";s:11:"7805825.pdf";s:4:"salt";i:5315664;}}', NULL, 2546, 21, NULL, NULL);
INSERT INTO files (id, dir_id, url, title, user_id, size, count, describe, author, exts, lang, "position", files, type, object_id, downloads, admin_id, date) VALUES (42, 4, './files/library/4/1282023.pdf', 'К эффективным обществам: пути в будущее', 31, '6.26 MB', 0, '', 'Богдан Гаврылышин', 'pdf', '', 252, 'a:1:{i:0;a:2:{s:4:"name";s:11:"1282023.pdf";s:4:"salt";i:2221491;}}', NULL, 31, 2, NULL, NULL);
INSERT INTO files (id, dir_id, url, title, user_id, size, count, describe, author, exts, lang, "position", files, type, object_id, downloads, admin_id, date) VALUES (124, 22, './files/library/22/5160994.doc', 'Завдання координаторів регіону на найближчий період', 2641, '42 KB', 0, '', '', 'doc', 'ua', 185, 'a:1:{i:0;a:2:{s:4:"name";s:11:"5160994.doc";s:4:"salt";i:3862405;}}', NULL, 2641, 25, NULL, NULL);
INSERT INTO files (id, dir_id, url, title, user_id, size, count, describe, author, exts, lang, "position", files, type, object_id, downloads, admin_id, date) VALUES (44, 12, './files/library/12/4332348.pdf', 'Власть достойных', 29, '3.9 MB', 0, '', '', 'pdf', '', 250, 'a:1:{i:0;a:2:{s:4:"name";s:11:"4332348.pdf";s:4:"salt";i:9061815;}}', NULL, 29, 9, NULL, NULL);
INSERT INTO files (id, dir_id, url, title, user_id, size, count, describe, author, exts, lang, "position", files, type, object_id, downloads, admin_id, date) VALUES (26, 5, 'http://shevchenko.ua/ua/position', 'Січневі тези: що робити і як?', 29, NULL, 0, '', 'Ігор Шевченко', NULL, 'ua', 244, NULL, NULL, 29, 0, NULL, NULL);
INSERT INTO files (id, dir_id, url, title, user_id, size, count, describe, author, exts, lang, "position", files, type, object_id, downloads, admin_id, date) VALUES (118, 12, './files/library/12/9403295.pdf', 'Календарі кишенькові', 2, '901.74 KB', 0, '', '', 'pdf', '', 191, 'a:1:{i:0;a:2:{s:4:"name";s:11:"9403295.pdf";s:4:"salt";i:5645702;}}', NULL, 2, 7, NULL, NULL);
INSERT INTO files (id, dir_id, url, title, user_id, size, count, describe, author, exts, lang, "position", files, type, object_id, downloads, admin_id, date) VALUES (29, 4, 'http://shevchenko.ua/ua/library/books/politic/gavrylyshyn', 'До ефективних суспільств: дороговкази в майбутнє', 29, NULL, 0, '', 'Богдан Гаврилишин', NULL, 'ua', 252, NULL, NULL, 29, 0, NULL, NULL);
INSERT INTO files (id, dir_id, url, title, user_id, size, count, describe, author, exts, lang, "position", files, type, object_id, downloads, admin_id, date) VALUES (265, 35, 'Zayava_pro_vzyattya_na_oblik_v_MPU_forma.doc', 'ЗАЯВА про взяття ППО на партійний облік', 0, '23 KB', 0, '', '', NULL, 'ua', 24, 'a:1:{i:0;a:2:{s:4:"name";s:44:"Zayava_pro_vzyattya_na_oblik_v_MPU_forma.doc";s:4:"salt";i:7807371;}}', NULL, 2641, 12, 0, NULL);
INSERT INTO files (id, dir_id, url, title, user_id, size, count, describe, author, exts, lang, "position", files, type, object_id, downloads, admin_id, date) VALUES (83, 12, './files/library/12/6801173.doc', 'Листівка про МПУ_А4', 2, '38 KB', 0, '', '', 'doc', 'ua', 225, 'a:1:{i:0;a:2:{s:4:"name";s:11:"6801173.doc";s:4:"salt";i:8348359;}}', NULL, 2, 14, NULL, NULL);
INSERT INTO files (id, dir_id, url, title, user_id, size, count, describe, author, exts, lang, "position", files, type, object_id, downloads, admin_id, date) VALUES (136, 21, './files/library/21/7245390.doc', 'ІНСТРУКЦІЯ з користування можливостями спільнот у мережі «Мерітократ»', 2641, '2.14 MB', 0, '
', '(функції, доступні для керівництва спільнот)', 'doc', 'ua', 172, 'a:1:{i:0;a:2:{s:4:"name";s:11:"7245390.doc";s:4:"salt";i:6357784;}}', NULL, 2641, 44, NULL, NULL);
INSERT INTO files (id, dir_id, url, title, user_id, size, count, describe, author, exts, lang, "position", files, type, object_id, downloads, admin_id, date) VALUES (66, 5, './files/library/5/5970431.docx', 'Великий маг', 29, '567.74 KB', 0, '', 'Юрій Нікітін', 'docx', '', 242, 'a:1:{i:0;a:2:{s:4:"name";s:12:"5970431.docx";s:4:"salt";i:7047983;}}', NULL, 29, 5, NULL, NULL);
INSERT INTO files (id, dir_id, url, title, user_id, size, count, describe, author, exts, lang, "position", files, type, object_id, downloads, admin_id, date) VALUES (199, 26, '3.  Как  стать  биороботом..docx', 'Как  стать  биороботом.', 7612, '29.98 KB', 0, '', '', NULL, '', 96, 'a:1:{i:0;a:2:{s:4:"name";s:50:"3.  Как  стать  биороботом..docx";s:4:"salt";i:8159102;}}', 1, 7612, 2, NULL, NULL);
INSERT INTO files (id, dir_id, url, title, user_id, size, count, describe, author, exts, lang, "position", files, type, object_id, downloads, admin_id, date) VALUES (283, 0, '2.jpg', 'агіт-листівка', 0, '59.4 KB', 0, 'агіт-листівка', 'мирослав', NULL, 'ua', 4, 'a:1:{i:0;a:2:{s:4:"name";s:5:"2.jpg";s:4:"salt";i:5280699;}}', 1, 2587, 6, 0, 1308126314);
INSERT INTO files (id, dir_id, url, title, user_id, size, count, describe, author, exts, lang, "position", files, type, object_id, downloads, admin_id, date) VALUES (233, 18, 'Polozhennja_pro_chlenstvo_02.06.2011.doc', 'ПОЛОЖЕННЯ про членство у Мерітократичній партії України', 0, '79 KB', 0, '', '', NULL, 'ua', 58, 'a:1:{i:0;a:2:{s:4:"name";s:40:"Polozhennja_pro_chlenstvo_02.06.2011.doc";s:4:"salt";i:2918155;}}', NULL, 2641, 23, 0, NULL);
INSERT INTO files (id, dir_id, url, title, user_id, size, count, describe, author, exts, lang, "position", files, type, object_id, downloads, admin_id, date) VALUES (197, 18, 'zayava_MPU_f1.doc', 'ЗАЯВА про вступ до Мерітократичної партії України', 0, '64.5 KB', 0, '', '', NULL, 'ua', 97, 'a:1:{i:0;a:2:{s:4:"name";s:17:"zayava_MPU_f1.doc";s:4:"salt";i:7598569;}}', NULL, 2641, 300, NULL, NULL);
INSERT INTO files (id, dir_id, url, title, user_id, size, count, describe, author, exts, lang, "position", files, type, object_id, downloads, admin_id, date) VALUES (28, 10, 'http://www.ex.ua/view/117540', 'Мирний воїн', 5, NULL, 0, 'Дэн Миллмен - хороший студент, но главное - талантливый гимнаст, который имеет отличные шансы попасть в олимпийскую сборную США. Однако есть у него необъяснимое \\"темное пятно\\" - кошмары, приходящие каждую ночь, которые он не может ни объяснить, ни победить. Случайная встреча с человеком, которого он назовет Сократом, поможет Дэну понять, что главное, даже для спортсмена - совсем не сила тела, а сила духа.', '', NULL, '', 249, NULL, NULL, 5, 0, NULL, NULL);
INSERT INTO files (id, dir_id, url, title, user_id, size, count, describe, author, exts, lang, "position", files, type, object_id, downloads, admin_id, date) VALUES (238, 0, 'HDI_Urban.pdf', 'HDI_Urban.pdf', 0, '818.83 KB', 0, '', '', NULL, '', 52, 'a:1:{i:0;a:2:{s:4:"name";s:13:"HDI_Urban.pdf";s:4:"salt";i:9248686;}}', 1, 7862, 2, 0, NULL);
INSERT INTO files (id, dir_id, url, title, user_id, size, count, describe, author, exts, lang, "position", files, type, object_id, downloads, admin_id, date) VALUES (195, 25, '14.  (1-17)  Лия  Лий.  ЭНЕРГЕТИЧЕСКАЯ  КНИГА  МИРОВ  ИЛИ  СЕРЕБРЯНАЯ  КНИГА..doc', 'ЭНЕРГЕТИЧЕСКАЯ  КНИГА  МИРОВ  ИЛИ  СЕРЕБРЯНАЯ  КНИГА', 0, '145 KB', 0, '', 'Лия  Лий', NULL, 'ru', 100, 'a:1:{i:0;a:2:{s:4:"name";s:129:"14.  (1-17)  Лия  Лий.  ЭНЕРГЕТИЧЕСКАЯ  КНИГА  МИРОВ  ИЛИ  СЕРЕБРЯНАЯ  КНИГА..doc";s:4:"salt";i:6145650;}}', 1, 7612, 18, NULL, NULL);
INSERT INTO files (id, dir_id, url, title, user_id, size, count, describe, author, exts, lang, "position", files, type, object_id, downloads, admin_id, date) VALUES (96, 21, './files/library/14/9274160.doc', 'Положення про функціональні групи в мережі \\"Мерітократ\\"', 2641, '43 KB', 0, '', 'Затверджене Рішенням Політичної Ради МПУ №2 від 03.02.2011', 'doc', 'ua', 215, 'a:1:{i:0;a:2:{s:4:"name";s:11:"9274160.doc";s:4:"salt";i:4125597;}}', NULL, 2641, 8, NULL, NULL);
INSERT INTO files (id, dir_id, url, title, user_id, size, count, describe, author, exts, lang, "position", files, type, object_id, downloads, admin_id, date) VALUES (161, 0, 'Медіація у системі охорони здоровя.doc', 'Медіація у системі охорони здоровя', 0, '52.5 KB', 0, '', 'Віктор Демчишин', NULL, 'ua', 140, 'a:1:{i:0;a:2:{s:4:"name";s:68:"Медіація у системі охорони здоровя.doc";s:4:"salt";i:7768275;}}', 1, 4183, 1, NULL, NULL);
INSERT INTO files (id, dir_id, url, title, user_id, size, count, describe, author, exts, lang, "position", files, type, object_id, downloads, admin_id, date) VALUES (88, 17, './files/library/17/2244841.pdf', 'ЗРАЗОК правильного заповнення бланку збору підписів (район області)', 2, '3.02 MB', 0, '', '', 'pdf', 'ua', 220, 'a:1:{i:0;a:2:{s:4:"name";s:11:"2244841.pdf";s:4:"salt";i:9462708;}}', NULL, 2, 0, NULL, NULL);
INSERT INTO files (id, dir_id, url, title, user_id, size, count, describe, author, exts, lang, "position", files, type, object_id, downloads, admin_id, date) VALUES (112, 20, './files/library/20/9442663.doc', 'Положення про представників ГО «Мерітократична Україна» у ВНЗ', 2641, '94.5 KB', 0, '', 'Затверджено рішенням Політичної Ради МПУ від 3 березня 2011 року №5', 'doc', 'ua', 196, 'a:1:{i:0;a:2:{s:4:"name";s:11:"9442663.doc";s:4:"salt";i:4717553;}}', NULL, 2641, 2, NULL, NULL);
INSERT INTO files (id, dir_id, url, title, user_id, size, count, describe, author, exts, lang, "position", files, type, object_id, downloads, admin_id, date) VALUES (249, 0, 'TestDoc.doc', 'TestDoc.doc', 0, '32.5 KB', 0, '', '', NULL, '', 40, 'a:1:{i:0;a:2:{s:4:"name";s:11:"TestDoc.doc";s:4:"salt";i:6837635;}}', 9, 996, 0, 1337, 1307621365);
INSERT INTO files (id, dir_id, url, title, user_id, size, count, describe, author, exts, lang, "position", files, type, object_id, downloads, admin_id, date) VALUES (65, 10, 'http://www.ex.ua/view/17193', '12', 5, NULL, 0, '', '', NULL, '', 244, NULL, NULL, 5, 0, NULL, NULL);
INSERT INTO files (id, dir_id, url, title, user_id, size, count, describe, author, exts, lang, "position", files, type, object_id, downloads, admin_id, date) VALUES (188, 0, 'SN59.pdf', 'SN59.pdf', 0, '9.94 MB', 0, '', '', NULL, '', 107, 'a:1:{i:0;a:2:{s:4:"name";s:8:"SN59.pdf";s:4:"salt";i:1126854;}}', 1, 7862, 4, NULL, NULL);
INSERT INTO files (id, dir_id, url, title, user_id, size, count, describe, author, exts, lang, "position", files, type, object_id, downloads, admin_id, date) VALUES (89, 10, 'http://www.ex.ua/view/510262', 'Джузеппе Москати: исцеляющая любовь', 5, NULL, 0, 'История святого Джузеппе Москати - неаполитанского врача и величайшего гуманиста. Москати всю свою жизнь декларировал, что главная сила - любовь. Он постоянно доказывал это, сочетая блестящие врачебные способности с любовью к ближнему.

Москати утверждал, что даже простое сочувствие исцелит больного скорее, чем равнодушное исполнение врачом своих обязанностей, и убеждал в этом своих учеников...', '', NULL, '', 221, NULL, NULL, 5, 0, NULL, NULL);
INSERT INTO files (id, dir_id, url, title, user_id, size, count, describe, author, exts, lang, "position", files, type, object_id, downloads, admin_id, date) VALUES (135, 20, './files/library/20/8279891.doc', 'ВИМОГИ  для отримання статусу представника  у ВНЗ', 2641, '31 KB', 0, '', '', 'doc', 'ua', 173, 'a:1:{i:0;a:2:{s:4:"name";s:11:"8279891.doc";s:4:"salt";i:6630717;}}', NULL, 2641, 7, NULL, NULL);
INSERT INTO files (id, dir_id, url, title, user_id, size, count, describe, author, exts, lang, "position", files, type, object_id, downloads, admin_id, date) VALUES (63, 15, 'http://www.ex.ua/view/635249', 'Секрет моего успеха', 29, NULL, 0, 'Гумористична кінострічка з правильним глибинним змістом', '', NULL, '', 243, NULL, NULL, 29, 1, NULL, NULL);
INSERT INTO files (id, dir_id, url, title, user_id, size, count, describe, author, exts, lang, "position", files, type, object_id, downloads, admin_id, date) VALUES (138, 14, '4258542.doc', 'Статут МПУ', 0, '374.5 KB', 0, '', '', NULL, 'ua', 170, 'a:2:{i:0;a:2:{s:4:"name";s:11:"4258542.doc";s:4:"salt";i:7333719;}i:1;a:2:{s:4:"name";s:11:"4258542.pdf";s:4:"salt";i:8154429;}}', NULL, 1337, 34, NULL, NULL);
INSERT INTO files (id, dir_id, url, title, user_id, size, count, describe, author, exts, lang, "position", files, type, object_id, downloads, admin_id, date) VALUES (52, 13, './files/library/13/3633670.pdf', 'Створення та діяльність ОСББ', 31, '789.79 KB', 0, 'Практичний посібник', 'Інститут місцевого розвитку', 'pdf', 'ua', 251, 'a:1:{i:0;a:2:{s:4:"name";s:11:"3633670.pdf";s:4:"salt";i:3515330;}}', NULL, 31, 4, NULL, NULL);
INSERT INTO files (id, dir_id, url, title, user_id, size, count, describe, author, exts, lang, "position", files, type, object_id, downloads, admin_id, date) VALUES (18, 5, 'http://www.dedusenko.at.ua/Gajdaj.doc', 'Пособие по захвату власти в Украине: рекомендации из будущего.', 2546, NULL, 0, '', 'Сергей Гайдай', 'doc', '', 248, NULL, NULL, 2546, 0, NULL, NULL);
INSERT INTO files (id, dir_id, url, title, user_id, size, count, describe, author, exts, lang, "position", files, type, object_id, downloads, admin_id, date) VALUES (193, 18, 'kvit.doc', 'Квитанція для сплати внесків в МПУ', 0, '59.5 KB', 0, '', '', NULL, 'ua', 102, 'a:1:{i:0;a:2:{s:4:"name";s:8:"kvit.doc";s:4:"salt";i:5342399;}}', NULL, 2641, 54, NULL, NULL);
INSERT INTO files (id, dir_id, url, title, user_id, size, count, describe, author, exts, lang, "position", files, type, object_id, downloads, admin_id, date) VALUES (91, 19, './files/library/14/5636323.doc', 'Базова програма МПУ (укр)', 2, '58 KB', 0, '', '', 'doc', 'ua', 215, 'a:1:{i:0;a:2:{s:4:"name";s:11:"5636323.doc";s:4:"salt";i:6640927;}}', NULL, 2, 10, NULL, NULL);
INSERT INTO files (id, dir_id, url, title, user_id, size, count, describe, author, exts, lang, "position", files, type, object_id, downloads, admin_id, date) VALUES (200, 0, 'i_v.rar', 'i_v.rar', 0, '979.5 KB', 0, '', '', NULL, '', 95, 'a:1:{i:0;a:2:{s:4:"name";s:7:"i_v.rar";s:4:"salt";i:7642359;}}', 1, 7862, 4, NULL, NULL);
INSERT INTO files (id, dir_id, url, title, user_id, size, count, describe, author, exts, lang, "position", files, type, object_id, downloads, admin_id, date) VALUES (50, 10, 'http://www.ex.ua/view/1734639?r=2', 'Здесь курят', 29, NULL, 0, 'Работа у Ника Тэйлора не из легких. Он должен лоббировать табакокурение, как это только возможно. Казалось бы, какой абсурд вступать в конфликт с ярыми противниками курения и пытаться доказать полезность последнего. Но такая уж у Ника работа. И он в ней добился не малых результатов, агитируя всех к курению в ток-шоу на телевидении, и продвигая сигареты в кинофильмах. Однако, сам Ник никогда не считал курение сколько либо полезным занятием.', '', NULL, '', 253, NULL, NULL, 29, 0, NULL, NULL);
INSERT INTO files (id, dir_id, url, title, user_id, size, count, describe, author, exts, lang, "position", files, type, object_id, downloads, admin_id, date) VALUES (32, 4, 'http://shevchenko.ua/ua/library/books/politic/t.drayzer-major', 'Мер и его избиратели', 5, NULL, 0, '', 'Теодор Драйзер', NULL, '', 255, NULL, NULL, 5, 0, NULL, NULL);
INSERT INTO files (id, dir_id, url, title, user_id, size, count, describe, author, exts, lang, "position", files, type, object_id, downloads, admin_id, date) VALUES (56, 10, 'http://www.ex.ua/view/4422?r=2', 'Листоноша', 5, NULL, 0, '', '', NULL, '', 250, NULL, NULL, 5, 0, NULL, NULL);
INSERT INTO files (id, dir_id, url, title, user_id, size, count, describe, author, exts, lang, "position", files, type, object_id, downloads, admin_id, date) VALUES (253, 0, '', '', 0, '0 B', 0, 'тек кпк упку пп купкуп куп куп', '', NULL, '', 36, 'a:0:{}', 9, 996, 0, 1337, 1307621696);
INSERT INTO files (id, dir_id, url, title, user_id, size, count, describe, author, exts, lang, "position", files, type, object_id, downloads, admin_id, date) VALUES (55, 10, 'http://www.ex.ua/view/531159?r=2', 'Удивительная легкость', 29, NULL, 0, 'Уильям Уилберфорс - очень принципиальный политик, известный своим обаянием, умом и рвением. В отличии от большинства своих коллег, он уже в начале своей карьеры был признан человеком очень честным и смелым. Судьбоносная встреча с бывшим рабом Олаудау Эквиано побуждает Уилберфорса начать борьбу с антигуманной практикой работорговли - экономической силы, столь жизненно важной для истеблишмента, что он вступает в жестокое противостояние с большинством могущественных людей в стране. Его друг - будущий британский премьер-министр Уильям Питт является его идеальным партнером. Питт предпочитает играть по правилам, а Уилберфорс врывается во все двери, во весь голос требуя реформ. Оба они живут надеждой на лучшее будущее страны, но перемены наступают медленно. Однако Уилберфорс не сдается и с помощью пестрой коалиции союзников, в которую входят бывший работорговец Джон Ньютон, страстный аболиционист Томас Кларксон и его энергичная жена и политическая сторонница Барбара Спунер, основывает движение, которое, в конечном счете, изменит историю.', '', NULL, '', 242, NULL, NULL, 29, 0, NULL, NULL);
INSERT INTO files (id, dir_id, url, title, user_id, size, count, describe, author, exts, lang, "position", files, type, object_id, downloads, admin_id, date) VALUES (57, 10, 'http://www.ex.ua/view/1821112', 'Черчилль', 5, NULL, 0, '\\"Я не обещаю вам легкой победы и радости. Я обещаю вам кровь, пот и слезы ... Уинстон Черчылль

Великобритания, середина 30-х годов. Мир грозит взорваться Второй Мировой войной. Но члены Британского Парламента не верят в ее неизбежность и надеются найти мирные пути сосуществования с фашистской Германией. Только Уинстон Черчилль видит в нацизме угрозу безопасности Европы и Великобритании. Ему в руки попадают секретные документы, оглашение которых заставляет Парламент осознать всю реальность надвигающейся войны. Теперь политическая карьера Черчилля на взлете. Зато терпит крах его личная жизнь. Но, несмотря на одиночество, глубокую депрессию и огромное количество врагов, Уинстон Черчилль продолжает свою борьбу против нацистской угрозы и пытается вернуть семейное счастье. ', '', NULL, '', 245, NULL, NULL, 5, 0, NULL, NULL);
INSERT INTO files (id, dir_id, url, title, user_id, size, count, describe, author, exts, lang, "position", files, type, object_id, downloads, admin_id, date) VALUES (276, 0, 'Japan\\''s meritocratic education system.pdf', 'IS Japan’s education system meritocratic?', 0, '87.97 KB', 0, 'Japan\\''s meritocratic education system', 'Dr Christopher P. Hood', NULL, 'en', 11, 'a:1:{i:0;a:2:{s:4:"name";s:42:"Japan\\''s meritocratic education system.pdf";s:4:"salt";i:4281103;}}', 1, 8689, 0, 0, 1307849980);
INSERT INTO files (id, dir_id, url, title, user_id, size, count, describe, author, exts, lang, "position", files, type, object_id, downloads, admin_id, date) VALUES (134, 20, './files/library/20/8984613.doc', 'Анкета кандидата у представники ГО \\"Мерітократична Україна\\" у ВНЗ', 2641, '27 KB', 0, '', '', 'doc', 'ua', 174, 'a:1:{i:0;a:2:{s:4:"name";s:11:"8984613.doc";s:4:"salt";i:4514454;}}', NULL, 2641, 3, NULL, NULL);
INSERT INTO files (id, dir_id, url, title, user_id, size, count, describe, author, exts, lang, "position", files, type, object_id, downloads, admin_id, date) VALUES (122, 21, './files/library/21/4535879.doc', 'Правила створення нових спільнот у соціально-політичній мережі «Мерітократ»', 2, '50 KB', 0, '', 'Затверджені Рішенням Політичної Ради МПУ №7 від 24.03.2011', 'doc', 'ua', 186, 'a:1:{i:0;a:2:{s:4:"name";s:11:"4535879.doc";s:4:"salt";i:7992067;}}', NULL, 2, 11, NULL, NULL);
INSERT INTO files (id, dir_id, url, title, user_id, size, count, describe, author, exts, lang, "position", files, type, object_id, downloads, admin_id, date) VALUES (271, 0, 'http://www.englisharticles.info/2011/03/07/meritocracy/', 'Meritocracy-EnglishArticles', 0, NULL, 0, '', '', NULL, 'en', 17, NULL, 1, 5, 0, 0, 1307712997);
INSERT INTO files (id, dir_id, url, title, user_id, size, count, describe, author, exts, lang, "position", files, type, object_id, downloads, admin_id, date) VALUES (281, 0, 'http://www.sloganbase.ru/?PageID=19&id_ra=7&id=42', 'Еще слоганы', 0, NULL, 0, 'Политические слоганы', 'народ', NULL, 'ru', 6, NULL, 1, 6156, 0, 0, 1307908248);
INSERT INTO files (id, dir_id, url, title, user_id, size, count, describe, author, exts, lang, "position", files, type, object_id, downloads, admin_id, date) VALUES (235, 29, '7.  ЗОЛОТОЙ   КОДЕКС   СОВЕСТИ   ЧЕЛОВЕЧЕСТВА..docx', 'ЗОЛОТОЙ  КОДЕКС  СОВЕСТИ  ЧЕЛОВЕКА.', 0, '31.74 KB', 0, 'Мы, Сыновья и дочери земли принимаем обязательства выполнить Кодекс Совести, во имя Спасения Планеты фиксируем Доброе Слово в Психосфере, согласно рекомендациям Духовного Правительства  Шамбалы, записанных в Мега-компьютере Ноосферы.', '', NULL, 'ru', 55, 'a:1:{i:0;a:2:{s:4:"name";s:83:"7.  ЗОЛОТОЙ   КОДЕКС   СОВЕСТИ   ЧЕЛОВЕЧЕСТВА..docx";s:4:"salt";i:2569554;}}', 1, 7612, 6, 0, NULL);


--
-- Data for Name: files_dirs; Type: TABLE DATA; Schema: public; Owner: intra
--

INSERT INTO files_dirs (id, title, "position", parent_id, object_id, type) VALUES (4, 'Державне управління', 28, 0, NULL, 1);
INSERT INTO files_dirs (id, title, "position", parent_id, object_id, type) VALUES (14, 'Партійні документи МПУ', 20, 0, NULL, 1);
INSERT INTO files_dirs (id, title, "position", parent_id, object_id, type) VALUES (1, 'New folder(Edited)', 3, 4, 0, 2);
INSERT INTO files_dirs (id, title, "position", parent_id, object_id, type) VALUES (10, 'Кіно', 31, 0, NULL, 2);
INSERT INTO files_dirs (id, title, "position", parent_id, object_id, type) VALUES (18, 'Членство в МПУ', 22, 14, NULL, 2);
INSERT INTO files_dirs (id, title, "position", parent_id, object_id, type) VALUES (19, 'Ідеологія МПУ', 17, 14, NULL, 2);
INSERT INTO files_dirs (id, title, "position", parent_id, object_id, type) VALUES (20, 'Представники у ВНЗ', 23, 14, NULL, 2);
INSERT INTO files_dirs (id, title, "position", parent_id, object_id, type) VALUES (22, 'Партійне будівництво', 21, 14, NULL, 2);
INSERT INTO files_dirs (id, title, "position", parent_id, object_id, type) VALUES (37, 'Образец листовки', 2, 4, 3827, 2);
INSERT INTO files_dirs (id, title, "position", parent_id, object_id, type) VALUES (2, 'documentFolder', 32, 0, 0, 1);
INSERT INTO files_dirs (id, title, "position", parent_id, object_id, type) VALUES (21, 'Мережа \\"Мерітократ\\"', 35, 14, NULL, 2);
INSERT INTO files_dirs (id, title, "position", parent_id, object_id, type) VALUES (35, 'Створення ППО', 34, 14, 0, 2);
INSERT INTO files_dirs (id, title, "position", parent_id, object_id, type) VALUES (3, 'publicFolder', 33, 0, 0, 2);


--
-- Data for Name: gallery_albums; Type: TABLE DATA; Schema: public; Owner: intra
--

INSERT INTO gallery_albums (id, album_name, parent_id, salt) VALUES (9, 'FineImage', 0, '1bed0e47');


--
-- Data for Name: gallery_comments; Type: TABLE DATA; Schema: public; Owner: intra
--

INSERT INTO gallery_comments (id, user_id, body, photo_id, add_ts, type_id, obj_id) VALUES (9, 7, 'комментарий к имейдж 2', 6, 1310023446, NULL, NULL);


--
-- Data for Name: gallery_files; Type: TABLE DATA; Schema: public; Owner: intra
--

INSERT INTO gallery_files (id, salt, album_id, description) VALUES (5, 'd9f12ae9', 9, 'имеидж 1');
INSERT INTO gallery_files (id, salt, album_id, description) VALUES (6, '924c8bb0', 9, 'имеидж 2');
INSERT INTO gallery_files (id, salt, album_id, description) VALUES (7, '446a91e1', 9, 'имеидж 2');


--
-- Data for Name: jqcalendar; Type: TABLE DATA; Schema: public; Owner: intra
--

INSERT INTO jqcalendar (id, subject, location, description, starttime, endtime, isalldayevent, color, recurringrule, user_id, type) VALUES (22, 'Конференция', 'Конференц-зала', '...', '2011-07-07 18:00:00', '2011-07-07 20:00:00', 0, '1', NULL, 8, 2);
INSERT INTO jqcalendar (id, subject, location, description, starttime, endtime, isalldayevent, color, recurringrule, user_id, type) VALUES (11, 'туц туц туц', '', '', '2011-06-17 01:00:00', '2011-06-17 02:00:00', 0, '10', NULL, NULL, 2);
INSERT INTO jqcalendar (id, subject, location, description, starttime, endtime, isalldayevent, color, recurringrule, user_id, type) VALUES (10, 'Fast add (edited)', '', '', '2011-06-09 05:00:00', '2011-06-09 06:00:00', 0, '20', NULL, NULL, 2);
INSERT INTO jqcalendar (id, subject, location, description, starttime, endtime, isalldayevent, color, recurringrule, user_id, type) VALUES (15, '222', 'ssss', 'qweqw', '2011-07-04 07:00:00', '2011-07-04 08:30:00', 0, '-1', NULL, NULL, 2);
INSERT INTO jqcalendar (id, subject, location, description, starttime, endtime, isalldayevent, color, recurringrule, user_id, type) VALUES (16, 'add', NULL, NULL, '2011-07-04 10:00:00', '2011-07-04 11:00:00', 0, NULL, NULL, NULL, 2);
INSERT INTO jqcalendar (id, subject, location, description, starttime, endtime, isalldayevent, color, recurringrule, user_id, type) VALUES (13, '1', NULL, NULL, '2011-07-04 15:30:00', '2011-07-04 16:30:00', 0, NULL, NULL, NULL, 1);
INSERT INTO jqcalendar (id, subject, location, description, starttime, endtime, isalldayevent, color, recurringrule, user_id, type) VALUES (18, 'Тренінг з лідерами', 'Подія', '', '2011-07-07 14:00:00', '2011-07-07 16:00:00', 0, '10', NULL, 7, 2);
INSERT INTO jqcalendar (id, subject, location, description, starttime, endtime, isalldayevent, color, recurringrule, user_id, type) VALUES (17, 'event', '222', '333', '2011-07-06 11:30:00', '2011-07-06 13:00:00', 0, '-1', NULL, NULL, 1);
INSERT INTO jqcalendar (id, subject, location, description, starttime, endtime, isalldayevent, color, recurringrule, user_id, type) VALUES (19, 'Зустріч', 'Конференц-зала', '', '2011-07-07 09:00:00', '2011-07-07 12:00:00', 0, '-1', NULL, 7, 1);
INSERT INTO jqcalendar (id, subject, location, description, starttime, endtime, isalldayevent, color, recurringrule, user_id, type) VALUES (7, 'asdasdasd', '', '', '2011-06-14 05:00:00', '2011-06-14 06:00:00', 0, '17', NULL, NULL, 1);


--
-- Data for Name: news; Type: TABLE DATA; Schema: public; Owner: intra
--

INSERT INTO news (id, title, anons, text, user_id, salt, important) VALUES (2, 'Edited title', 'Edited Anons', '<p>Edited text</p>', 7, 'e3f6243d', 0);
INSERT INTO news (id, title, anons, text, user_id, salt, important) VALUES (3, 'News title', 'Anouncement', '<p>news_text</p>', 7, '03eae5ba', 0);


--
-- Data for Name: user_auth; Type: TABLE DATA; Schema: public; Owner: intra
--

INSERT INTO user_auth (id, login, password, credentials, created_ts, creator) VALUES (7, 'work.antony@gmail.com', '29222d67', 'admin', 1307376353, NULL);
INSERT INTO user_auth (id, login, password, credentials, created_ts, creator) VALUES (8, 'sl.kolomiyets@gmail.com', '70f87474', 'admin', 1307439786, 7);
INSERT INTO user_auth (id, login, password, credentials, created_ts, creator) VALUES (9, 'tarasenko_a@list.ru', 'd9faac9', 'admin', 1308291418, 7);


--
-- Data for Name: user_data; Type: TABLE DATA; Schema: public; Owner: intra
--

INSERT INTO user_data (user_id, first_name, last_name, father_name, country_id, region_id, city_id, address, function, photo_salt, gender, mobile_phone, work_phone, home_phone, birthday, email, icq, skype, notify, work_begin, work_end, work_start_date, work_quit_date, quit_reason, cabinet, card, collector_email_access, network_source_access, specific_software, workplace_design, private_email, passport_data, test_period_end, test_period_results) VALUES (7, 'Антон', 'Тарасенко', 'Александрович', 2, 0, 0, 'Ушинского 19, кв. 14', 'программист', NULL, 'm', '0633660765', '777', '0445556677', 1306875600, 'work.antony@gmail.com', '593063882', 'skype000', 'phpinfo()', 9, 18, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO user_data (user_id, first_name, last_name, father_name, country_id, region_id, city_id, address, function, photo_salt, gender, mobile_phone, work_phone, home_phone, birthday, email, icq, skype, notify, work_begin, work_end, work_start_date, work_quit_date, quit_reason, cabinet, card, collector_email_access, network_source_access, specific_software, workplace_design, private_email, passport_data, test_period_end, test_period_results) VALUES (8, 'Світлана', 'Коломієць', '', 1, 12, 824, 'м.Бориспіль', 'Керівник Секретаріату МПУ', NULL, 'f', '0931487729', '', '', 524264400, 'sl.kolomiyets@gmail.com', '', '', '', 9, 18, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO user_data (user_id, first_name, last_name, father_name, country_id, region_id, city_id, address, function, photo_salt, gender, mobile_phone, work_phone, home_phone, birthday, email, icq, skype, notify, work_begin, work_end, work_start_date, work_quit_date, quit_reason, cabinet, card, collector_email_access, network_source_access, specific_software, workplace_design, private_email, passport_data, test_period_end, test_period_results) VALUES (9, 'Антон', 'Тарасенко', 'Александрович', 1, 13, 231, 'Ушинского 19, кв. 14', 'программист', NULL, 'm', '0633660765', '706', '0445556677', 562197600, 'tarasenko_a@list.ru', '593063882', 'skype000', 'notify', 9, 18, 1297634400, 1356904800, 'Конец света!', 503, 1, 'noAccess', '{1,2,3}', 'phpinfo()', 'grandSrach', 'work.antony@gmail.com', 'ME 8822883', 1302728400, 'sehr gut');


--
-- Data for Name: work_miss; Type: TABLE DATA; Schema: public; Owner: intra
--

INSERT INTO work_miss (id, user_id, begin, "end", reason) VALUES (1, 7, 1306875600, 1306962000, 'хэв ризон');


--
-- Data for Name: work_visits; Type: TABLE DATA; Schema: public; Owner: intra
--

INSERT INTO work_visits (id, user_id, date, start, "end", late_reason, gone_reason) VALUES (2, 7, 1307521116, 1307521116, 1307521238, 'Проспал...', 'Устал...');
INSERT INTO work_visits (id, user_id, date, start, "end", late_reason, gone_reason) VALUES (4, 7, 1307690111, 1307690111, 1307717343, NULL, NULL);
INSERT INTO work_visits (id, user_id, date, start, "end", late_reason, gone_reason) VALUES (3, 7, 1307610075, 1307685600, 1307721600, NULL, NULL);
INSERT INTO work_visits (id, user_id, date, start, "end", late_reason, gone_reason) VALUES (1, 7, 1307463974, 1307463974, 1307464074, NULL, NULL);
INSERT INTO work_visits (id, user_id, date, start, "end", late_reason, gone_reason) VALUES (5, 7, 1308229995, 1308229995, 1308230093, NULL, NULL);
INSERT INTO work_visits (id, user_id, date, start, "end", late_reason, gone_reason) VALUES (6, 7, 1308293102, 1308293102, 0, NULL, NULL);
INSERT INTO work_visits (id, user_id, date, start, "end", late_reason, gone_reason) VALUES (8, 7, 1309847981, 1309847981, 1309878000, NULL, NULL);
INSERT INTO work_visits (id, user_id, date, start, "end", late_reason, gone_reason) VALUES (9, 7, 1309931683, 1309931683, 0, NULL, NULL);
INSERT INTO work_visits (id, user_id, date, start, "end", late_reason, gone_reason) VALUES (7, 7, 1309786448, 1309786448, 1309788311, 'Reason....', NULL);


--
-- Name: files_dirs_pkey; Type: CONSTRAINT; Schema: public; Owner: intra; Tablespace: 
--

ALTER TABLE ONLY files_dirs
    ADD CONSTRAINT files_dirs_pkey PRIMARY KEY (id);


--
-- Name: files_pkey; Type: CONSTRAINT; Schema: public; Owner: intra; Tablespace: 
--

ALTER TABLE ONLY files
    ADD CONSTRAINT files_pkey PRIMARY KEY (id);


--
-- Name: gallery_albums_pkey; Type: CONSTRAINT; Schema: public; Owner: intra; Tablespace: 
--

ALTER TABLE ONLY gallery_albums
    ADD CONSTRAINT gallery_albums_pkey PRIMARY KEY (id);


--
-- Name: gallery_files_pkey; Type: CONSTRAINT; Schema: public; Owner: intra; Tablespace: 
--

ALTER TABLE ONLY gallery_files
    ADD CONSTRAINT gallery_files_pkey PRIMARY KEY (id);


--
-- Name: jqcalendar_pkey; Type: CONSTRAINT; Schema: public; Owner: intra; Tablespace: 
--

ALTER TABLE ONLY jqcalendar
    ADD CONSTRAINT jqcalendar_pkey PRIMARY KEY (id);


--
-- Name: news_pkey; Type: CONSTRAINT; Schema: public; Owner: intra; Tablespace: 
--

ALTER TABLE ONLY news
    ADD CONSTRAINT news_pkey PRIMARY KEY (id);


--
-- Name: photo_comments_pkey; Type: CONSTRAINT; Schema: public; Owner: intra; Tablespace: 
--

ALTER TABLE ONLY gallery_comments
    ADD CONSTRAINT photo_comments_pkey PRIMARY KEY (id);


--
-- Name: work_miss_pkey; Type: CONSTRAINT; Schema: public; Owner: intra; Tablespace: 
--

ALTER TABLE ONLY work_miss
    ADD CONSTRAINT work_miss_pkey PRIMARY KEY (id);


--
-- Name: work_time_pkey; Type: CONSTRAINT; Schema: public; Owner: intra; Tablespace: 
--

ALTER TABLE ONLY work_visits
    ADD CONSTRAINT work_time_pkey PRIMARY KEY (id);


--
-- Name: public; Type: ACL; Schema: -; Owner: postgres
--

REVOKE ALL ON SCHEMA public FROM PUBLIC;
REVOKE ALL ON SCHEMA public FROM postgres;
GRANT ALL ON SCHEMA public TO postgres;
GRANT ALL ON SCHEMA public TO PUBLIC;


--
-- PostgreSQL database dump complete
--

