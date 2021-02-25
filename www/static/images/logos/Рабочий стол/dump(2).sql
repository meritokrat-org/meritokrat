--
-- PostgreSQL database dump
--

SET client_encoding = 'UTF8';
SET standard_conforming_strings = off;
SET check_function_bodies = false;
SET client_min_messages = warning;
SET escape_string_warning = off;

--
-- Name: plpgsql; Type: PROCEDURAL LANGUAGE; Schema: -; Owner: postgres
--

CREATE PROCEDURAL LANGUAGE plpgsql;


ALTER PROCEDURAL LANGUAGE plpgsql OWNER TO postgres;

SET search_path = public, pg_catalog;

--
-- Name: w_lower(text); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION w_lower(text) RETURNS text
    AS $_$

DECLARE BEGIN

RETURN TRANSLATE($1, 'ABCDEFGHIJKLMNOPQRSTUVWXYZЁАБВГДЕЖЗИЙКЛМНОПРСТУФХЦЧШЩЪЫЬЭЮЯІЇЄ',

'abcdefghijklmnopqrstuvwxyzёабвгдежзийклмнопрстуфхцчшщъыьэюяіїє');

END; $_$
    LANGUAGE plpgsql IMMUTABLE;


ALTER FUNCTION public.w_lower(text) OWNER TO postgres;

--
-- Name: w_upper(text); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION w_upper(text) RETURNS text
    AS $_$

DECLARE BEGIN

RETURN TRANSLATE($1, 'abcdefghijklmnopqrstuvwxyzёабвгдежзийклмнопрстуфхцчшщъыьэюяіїє',

'ABCDEFGHIJKLMNOPQRSTUVWXYZЁАБВГДЕЖЗИЙКЛМНОПРСТУФХЦЧШЩЪЫЬЭЮЯІЇЄ');

END; $_$
    LANGUAGE plpgsql IMMUTABLE;


ALTER FUNCTION public.w_upper(text) OWNER TO postgres;

SET default_tablespace = '';

SET default_with_oids = false;

--
-- Name: admin_feed; Type: TABLE; Schema: public; Owner: auzo; Tablespace: 
--

CREATE TABLE admin_feed (
    id integer NOT NULL,
    user_id integer NOT NULL,
    type smallint NOT NULL,
    created_ts integer NOT NULL,
    text text NOT NULL,
    author_id integer DEFAULT 0,
    data text,
    why character varying(255),
    action smallint DEFAULT 0,
    link text
);


ALTER TABLE public.admin_feed OWNER TO auzo;

--
-- Name: admin_feed_id_seq; Type: SEQUENCE; Schema: public; Owner: auzo
--

CREATE SEQUENCE admin_feed_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.admin_feed_id_seq OWNER TO auzo;

--
-- Name: admin_feed_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: auzo
--

ALTER SEQUENCE admin_feed_id_seq OWNED BY admin_feed.id;


--
-- Name: admin_feed_id_seq; Type: SEQUENCE SET; Schema: public; Owner: auzo
--

SELECT pg_catalog.setval('admin_feed_id_seq', 1, false);


--
-- Name: attentions; Type: TABLE; Schema: public; Owner: auzo; Tablespace: 
--

CREATE TABLE attentions (
    id integer NOT NULL,
    created_ts integer DEFAULT 0 NOT NULL,
    anounces text DEFAULT ''::text,
    text text DEFAULT ''::text,
    user_id integer DEFAULT 31 NOT NULL,
    anounces_ru text DEFAULT ''::text,
    text_ru text DEFAULT ''::text
);


ALTER TABLE public.attentions OWNER TO auzo;

--
-- Name: TABLE attentions; Type: COMMENT; Schema: public; Owner: auzo
--

COMMENT ON TABLE attentions IS 'важная информация на главной';


--
-- Name: attentions_id_seq; Type: SEQUENCE; Schema: public; Owner: auzo
--

CREATE SEQUENCE attentions_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.attentions_id_seq OWNER TO auzo;

--
-- Name: attentions_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: auzo
--

ALTER SEQUENCE attentions_id_seq OWNED BY attentions.id;


--
-- Name: attentions_id_seq; Type: SEQUENCE SET; Schema: public; Owner: auzo
--

SELECT pg_catalog.setval('attentions_id_seq', 1, false);


--
-- Name: banners_id_seq; Type: SEQUENCE; Schema: public; Owner: auzo
--

CREATE SEQUENCE banners_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.banners_id_seq OWNER TO auzo;

--
-- Name: banners_id_seq; Type: SEQUENCE SET; Schema: public; Owner: auzo
--

SELECT pg_catalog.setval('banners_id_seq', 1, false);


--
-- Name: banners; Type: TABLE; Schema: public; Owner: auzo; Tablespace: 
--

CREATE TABLE banners (
    id integer DEFAULT nextval('banners_id_seq'::regclass) NOT NULL,
    photo character varying(256) NOT NULL,
    author character varying(256) NOT NULL,
    title character varying(256) NOT NULL,
    link character varying(256) NOT NULL,
    "position" smallint
);


ALTER TABLE public.banners OWNER TO auzo;

--
-- Name: blogs_comments; Type: TABLE; Schema: public; Owner: auzo; Tablespace: 
--

CREATE TABLE blogs_comments (
    id integer NOT NULL,
    post_id integer,
    user_id integer,
    text text,
    created_ts integer,
    parent_id integer DEFAULT 0 NOT NULL,
    childs text DEFAULT ''::text NOT NULL,
    rate integer DEFAULT 0 NOT NULL,
    edit smallint DEFAULT 0,
    edit_ts integer DEFAULT 0
);


ALTER TABLE public.blogs_comments OWNER TO auzo;

--
-- Name: blogs_comments_id_seq; Type: SEQUENCE; Schema: public; Owner: auzo
--

CREATE SEQUENCE blogs_comments_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.blogs_comments_id_seq OWNER TO auzo;

--
-- Name: blogs_comments_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: auzo
--

ALTER SEQUENCE blogs_comments_id_seq OWNED BY blogs_comments.id;


--
-- Name: blogs_comments_id_seq; Type: SEQUENCE SET; Schema: public; Owner: auzo
--

SELECT pg_catalog.setval('blogs_comments_id_seq', 1, false);


--
-- Name: blogs_mentions; Type: TABLE; Schema: public; Owner: auzo; Tablespace: 
--

CREATE TABLE blogs_mentions (
    post_id integer NOT NULL,
    user_id integer NOT NULL
);


ALTER TABLE public.blogs_mentions OWNER TO auzo;

--
-- Name: blogs_posts; Type: TABLE; Schema: public; Owner: auzo; Tablespace: 
--

CREATE TABLE blogs_posts (
    id integer NOT NULL,
    user_id integer NOT NULL,
    title character varying(256),
    body text NOT NULL,
    created_ts integer NOT NULL,
    "for" integer DEFAULT 0 NOT NULL,
    text_rendered text NOT NULL,
    preview text NOT NULL,
    tags_text character varying,
    public boolean DEFAULT false NOT NULL,
    visible boolean DEFAULT true NOT NULL,
    fti tsvector,
    against integer DEFAULT 0 NOT NULL,
    type smallint DEFAULT 1 NOT NULL,
    views integer DEFAULT 0 NOT NULL,
    favorite boolean DEFAULT false NOT NULL,
    sort_ts integer DEFAULT 0 NOT NULL,
    anounces text,
    edit smallint DEFAULT 0,
    edit_ts integer DEFAULT 0,
    group_id integer DEFAULT 0 NOT NULL
);


ALTER TABLE public.blogs_posts OWNER TO auzo;

--
-- Name: blogs_posts_id_seq; Type: SEQUENCE; Schema: public; Owner: auzo
--

CREATE SEQUENCE blogs_posts_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.blogs_posts_id_seq OWNER TO auzo;

--
-- Name: blogs_posts_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: auzo
--

ALTER SEQUENCE blogs_posts_id_seq OWNED BY blogs_posts.id;


--
-- Name: blogs_posts_id_seq; Type: SEQUENCE SET; Schema: public; Owner: auzo
--

SELECT pg_catalog.setval('blogs_posts_id_seq', 1, false);


--
-- Name: blogs_posts_tags; Type: TABLE; Schema: public; Owner: auzo; Tablespace: 
--

CREATE TABLE blogs_posts_tags (
    post_id integer NOT NULL,
    tag_id integer NOT NULL
);


ALTER TABLE public.blogs_posts_tags OWNER TO auzo;

--
-- Name: blogs_tags; Type: TABLE; Schema: public; Owner: auzo; Tablespace: 
--

CREATE TABLE blogs_tags (
    id integer NOT NULL,
    name character varying(64) NOT NULL
);


ALTER TABLE public.blogs_tags OWNER TO auzo;

--
-- Name: blogs_tags_id_seq; Type: SEQUENCE; Schema: public; Owner: auzo
--

CREATE SEQUENCE blogs_tags_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.blogs_tags_id_seq OWNER TO auzo;

--
-- Name: blogs_tags_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: auzo
--

ALTER SEQUENCE blogs_tags_id_seq OWNED BY blogs_tags.id;


--
-- Name: blogs_tags_id_seq; Type: SEQUENCE SET; Schema: public; Owner: auzo
--

SELECT pg_catalog.setval('blogs_tags_id_seq', 1, false);


--
-- Name: bookmarks_id_seq; Type: SEQUENCE; Schema: public; Owner: auzo
--

CREATE SEQUENCE bookmarks_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.bookmarks_id_seq OWNER TO auzo;

--
-- Name: bookmarks_id_seq; Type: SEQUENCE SET; Schema: public; Owner: auzo
--

SELECT pg_catalog.setval('bookmarks_id_seq', 1, false);


--
-- Name: bookmarks; Type: TABLE; Schema: public; Owner: auzo; Tablespace: 
--

CREATE TABLE bookmarks (
    id integer DEFAULT nextval('bookmarks_id_seq'::regclass) NOT NULL,
    user_id integer NOT NULL,
    type smallint NOT NULL,
    oid integer NOT NULL
);


ALTER TABLE public.bookmarks OWNER TO auzo;

--
-- Name: candidates; Type: TABLE; Schema: public; Owner: auzo; Tablespace: 
--

CREATE TABLE candidates (
    user_id integer NOT NULL,
    program text
);


ALTER TABLE public.candidates OWNER TO auzo;

--
-- Name: candidates_forecast; Type: TABLE; Schema: public; Owner: auzo; Tablespace: 
--

CREATE TABLE candidates_forecast (
    user_id integer NOT NULL,
    candidate_id integer DEFAULT 0 NOT NULL,
    place integer DEFAULT 0 NOT NULL,
    votes integer DEFAULT 0 NOT NULL
);


ALTER TABLE public.candidates_forecast OWNER TO auzo;

--
-- Name: candidates_votes; Type: TABLE; Schema: public; Owner: auzo; Tablespace: 
--

CREATE TABLE candidates_votes (
    user_id integer NOT NULL,
    candidate_id integer NOT NULL,
    ip character varying(16) NOT NULL,
    ts integer NOT NULL
);


ALTER TABLE public.candidates_votes OWNER TO auzo;

--
-- Name: cities; Type: TABLE; Schema: public; Owner: auzo; Tablespace: 
--

CREATE TABLE cities (
    id integer NOT NULL,
    region_id integer NOT NULL,
    name_ru character varying NOT NULL,
    name_ua character varying NOT NULL
);


ALTER TABLE public.cities OWNER TO auzo;

--
-- Name: cities_id_seq; Type: SEQUENCE; Schema: public; Owner: auzo
--

CREATE SEQUENCE cities_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.cities_id_seq OWNER TO auzo;

--
-- Name: cities_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: auzo
--

ALTER SEQUENCE cities_id_seq OWNED BY cities.id;


--
-- Name: cities_id_seq; Type: SEQUENCE SET; Schema: public; Owner: auzo
--

SELECT pg_catalog.setval('cities_id_seq', 1, false);


--
-- Name: comments_id_seq; Type: SEQUENCE; Schema: public; Owner: auzo
--

CREATE SEQUENCE comments_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.comments_id_seq OWNER TO auzo;

--
-- Name: comments_id_seq; Type: SEQUENCE SET; Schema: public; Owner: auzo
--

SELECT pg_catalog.setval('comments_id_seq', 1, false);


--
-- Name: comments; Type: TABLE; Schema: public; Owner: auzo; Tablespace: 
--

CREATE TABLE comments (
    id integer DEFAULT nextval('comments_id_seq'::regclass) NOT NULL,
    oid integer,
    otype smallint,
    user_id integer,
    text text,
    created_ts integer,
    parent_id integer DEFAULT 0 NOT NULL,
    childs text DEFAULT ''::text NOT NULL,
    rate integer DEFAULT 0 NOT NULL
);


ALTER TABLE public.comments OWNER TO auzo;

--
-- Name: complaints; Type: TABLE; Schema: public; Owner: auzo; Tablespace: 
--

CREATE TABLE complaints (
    id integer NOT NULL,
    user_id integer NOT NULL,
    moderator_id integer NOT NULL,
    content_id integer NOT NULL,
    created_ts integer DEFAULT 0 NOT NULL,
    content_type smallint DEFAULT 0,
    action smallint DEFAULT 0
);


ALTER TABLE public.complaints OWNER TO auzo;

--
-- Name: complaints_id_seq; Type: SEQUENCE; Schema: public; Owner: auzo
--

CREATE SEQUENCE complaints_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.complaints_id_seq OWNER TO auzo;

--
-- Name: complaints_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: auzo
--

ALTER SEQUENCE complaints_id_seq OWNED BY complaints.id;


--
-- Name: complaints_id_seq; Type: SEQUENCE SET; Schema: public; Owner: auzo
--

SELECT pg_catalog.setval('complaints_id_seq', 1, false);


--
-- Name: countries; Type: TABLE; Schema: public; Owner: auzo; Tablespace: 
--

CREATE TABLE countries (
    id integer NOT NULL,
    name_ua character varying,
    name_ru character varying
);


ALTER TABLE public.countries OWNER TO auzo;

--
-- Name: debates; Type: TABLE; Schema: public; Owner: auzo; Tablespace: 
--

CREATE TABLE debates (
    id integer NOT NULL,
    user_id integer NOT NULL,
    "for" integer DEFAULT 0 NOT NULL,
    against integer DEFAULT 0 NOT NULL,
    created_ts integer NOT NULL,
    text text NOT NULL,
    tags_text character varying(255),
    visible boolean DEFAULT true NOT NULL,
    fti tsvector
);


ALTER TABLE public.debates OWNER TO auzo;

--
-- Name: debates_arguments; Type: TABLE; Schema: public; Owner: auzo; Tablespace: 
--

CREATE TABLE debates_arguments (
    id integer NOT NULL,
    debate_id integer NOT NULL,
    user_id integer NOT NULL,
    created_ts integer NOT NULL,
    agree boolean DEFAULT true NOT NULL,
    text text NOT NULL,
    childs text DEFAULT ''::text NOT NULL,
    parent_id integer DEFAULT 0 NOT NULL,
    rate integer DEFAULT 0 NOT NULL,
    total integer DEFAULT 0 NOT NULL
);


ALTER TABLE public.debates_arguments OWNER TO auzo;

--
-- Name: debates_arguments_id_seq; Type: SEQUENCE; Schema: public; Owner: auzo
--

CREATE SEQUENCE debates_arguments_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.debates_arguments_id_seq OWNER TO auzo;

--
-- Name: debates_arguments_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: auzo
--

ALTER SEQUENCE debates_arguments_id_seq OWNED BY debates_arguments.id;


--
-- Name: debates_arguments_id_seq; Type: SEQUENCE SET; Schema: public; Owner: auzo
--

SELECT pg_catalog.setval('debates_arguments_id_seq', 1, false);


--
-- Name: debates_debates_tags; Type: TABLE; Schema: public; Owner: auzo; Tablespace: 
--

CREATE TABLE debates_debates_tags (
    debate_id integer NOT NULL,
    tag_id integer NOT NULL
);


ALTER TABLE public.debates_debates_tags OWNER TO auzo;

--
-- Name: debates_id_seq; Type: SEQUENCE; Schema: public; Owner: auzo
--

CREATE SEQUENCE debates_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.debates_id_seq OWNER TO auzo;

--
-- Name: debates_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: auzo
--

ALTER SEQUENCE debates_id_seq OWNED BY debates.id;


--
-- Name: debates_id_seq; Type: SEQUENCE SET; Schema: public; Owner: auzo
--

SELECT pg_catalog.setval('debates_id_seq', 1, false);


--
-- Name: debates_tags; Type: TABLE; Schema: public; Owner: auzo; Tablespace: 
--

CREATE TABLE debates_tags (
    id integer NOT NULL,
    name character varying(64) NOT NULL
);


ALTER TABLE public.debates_tags OWNER TO auzo;

--
-- Name: debates_tags_id_seq; Type: SEQUENCE; Schema: public; Owner: auzo
--

CREATE SEQUENCE debates_tags_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.debates_tags_id_seq OWNER TO auzo;

--
-- Name: debates_tags_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: auzo
--

ALTER SEQUENCE debates_tags_id_seq OWNED BY debates_tags.id;


--
-- Name: debates_tags_id_seq; Type: SEQUENCE SET; Schema: public; Owner: auzo
--

SELECT pg_catalog.setval('debates_tags_id_seq', 1, false);


--
-- Name: districts; Type: TABLE; Schema: public; Owner: auzo; Tablespace: 
--

CREATE TABLE districts (
    id integer NOT NULL,
    region_id integer,
    name_ua character varying,
    name_ru character varying,
    name_en character varying
);


ALTER TABLE public.districts OWNER TO auzo;

--
-- Name: drafts_id_seq; Type: SEQUENCE; Schema: public; Owner: auzo
--

CREATE SEQUENCE drafts_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.drafts_id_seq OWNER TO auzo;

--
-- Name: drafts_id_seq; Type: SEQUENCE SET; Schema: public; Owner: auzo
--

SELECT pg_catalog.setval('drafts_id_seq', 1, false);


--
-- Name: drafts; Type: TABLE; Schema: public; Owner: auzo; Tablespace: 
--

CREATE TABLE drafts (
    id integer DEFAULT nextval('drafts_id_seq'::regclass) NOT NULL,
    name character varying(250),
    text text
);


ALTER TABLE public.drafts OWNER TO auzo;

--
-- Name: email_lists_id_seq2; Type: SEQUENCE; Schema: public; Owner: auzo
--

CREATE SEQUENCE email_lists_id_seq2
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.email_lists_id_seq2 OWNER TO auzo;

--
-- Name: email_lists_id_seq2; Type: SEQUENCE SET; Schema: public; Owner: auzo
--

SELECT pg_catalog.setval('email_lists_id_seq2', 1, false);


--
-- Name: email_lists; Type: TABLE; Schema: public; Owner: auzo; Tablespace: 
--

CREATE TABLE email_lists (
    id integer DEFAULT nextval('email_lists_id_seq2'::regclass) NOT NULL,
    name text,
    description text
);


ALTER TABLE public.email_lists OWNER TO auzo;

--
-- Name: TABLE email_lists; Type: COMMENT; Schema: public; Owner: auzo
--

COMMENT ON TABLE email_lists IS 'Таблица списков лоя рассылок';


--
-- Name: email_lists_id_seq; Type: SEQUENCE; Schema: public; Owner: auzo
--

CREATE SEQUENCE email_lists_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.email_lists_id_seq OWNER TO auzo;

--
-- Name: email_lists_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: auzo
--

ALTER SEQUENCE email_lists_id_seq OWNED BY email_lists.id;


--
-- Name: email_lists_id_seq; Type: SEQUENCE SET; Schema: public; Owner: auzo
--

SELECT pg_catalog.setval('email_lists_id_seq', 1, false);


--
-- Name: email_lists_users; Type: TABLE; Schema: public; Owner: auzo; Tablespace: 
--

CREATE TABLE email_lists_users (
    user_id integer NOT NULL,
    list_id integer NOT NULL
);


ALTER TABLE public.email_lists_users OWNER TO auzo;

--
-- Name: TABLE email_lists_users; Type: COMMENT; Schema: public; Owner: auzo
--

COMMENT ON TABLE email_lists_users IS 'Таблица-связка списков рассылок к ящикам';


--
-- Name: email_system; Type: TABLE; Schema: public; Owner: auzo; Tablespace: 
--

CREATE TABLE email_system (
    id integer NOT NULL,
    alias character varying(250),
    name character varying(250),
    title_ua character varying(250),
    title_ru character varying(250),
    body_ua text,
    body_ru text,
    sender_mail character varying(250),
    sender_name_ua character varying(250),
    sender_name_ru character varying(250),
    has_footer smallint DEFAULT 0
);


ALTER TABLE public.email_system OWNER TO auzo;

--
-- Name: email_system_id_seq; Type: SEQUENCE; Schema: public; Owner: auzo
--

CREATE SEQUENCE email_system_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.email_system_id_seq OWNER TO auzo;

--
-- Name: email_system_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: auzo
--

ALTER SEQUENCE email_system_id_seq OWNED BY email_system.id;


--
-- Name: email_system_id_seq; Type: SEQUENCE SET; Schema: public; Owner: auzo
--

SELECT pg_catalog.setval('email_system_id_seq', 1, false);


--
-- Name: email_users_id_seq2; Type: SEQUENCE; Schema: public; Owner: auzo
--

CREATE SEQUENCE email_users_id_seq2
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.email_users_id_seq2 OWNER TO auzo;

--
-- Name: email_users_id_seq2; Type: SEQUENCE SET; Schema: public; Owner: auzo
--

SELECT pg_catalog.setval('email_users_id_seq2', 2, true);


--
-- Name: email_users; Type: TABLE; Schema: public; Owner: auzo; Tablespace: 
--

CREATE TABLE email_users (
    id integer DEFAULT nextval('email_users_id_seq2'::regclass) NOT NULL,
    email character varying(100),
    first_name character varying(50),
    last_name character varying(50),
    blacklisted smallint DEFAULT 0 NOT NULL
);


ALTER TABLE public.email_users OWNER TO auzo;

--
-- Name: TABLE email_users; Type: COMMENT; Schema: public; Owner: auzo
--

COMMENT ON TABLE email_users IS 'Спсиок email для внешней рассылки';


--
-- Name: email_users_id_seq; Type: SEQUENCE; Schema: public; Owner: auzo
--

CREATE SEQUENCE email_users_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.email_users_id_seq OWNER TO auzo;

--
-- Name: email_users_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: auzo
--

ALTER SEQUENCE email_users_id_seq OWNED BY email_users.id;


--
-- Name: email_users_id_seq; Type: SEQUENCE SET; Schema: public; Owner: auzo
--

SELECT pg_catalog.setval('email_users_id_seq', 1, false);


--
-- Name: events; Type: TABLE; Schema: public; Owner: auzo; Tablespace: 
--

CREATE TABLE events (
    id integer NOT NULL,
    type smallint DEFAULT 0,
    content_id integer DEFAULT 0,
    user_id integer,
    start integer,
    "end" integer,
    notes text,
    description text,
    name character varying(255),
    section smallint,
    cat smallint DEFAULT 1,
    confirm boolean DEFAULT false,
    catname character varying,
    region_id integer,
    city_id integer,
    photo character varying,
    adress character varying(255),
    price boolean DEFAULT false,
    site character varying(255),
    level smallint,
    hidden smallint DEFAULT 0
);


ALTER TABLE public.events OWNER TO auzo;

--
-- Name: COLUMN events.type; Type: COMMENT; Schema: public; Owner: auzo
--

COMMENT ON COLUMN events.type IS '0 - приватний

1 -спилнота

2 - лидерська группа

3 - оргкомитет';


--
-- Name: events2users; Type: TABLE; Schema: public; Owner: auzo; Tablespace: 
--

CREATE TABLE events2users (
    event_id integer,
    user_id integer,
    status smallint,
    leads smallint,
    confirm smallint DEFAULT 0 NOT NULL
);


ALTER TABLE public.events2users OWNER TO auzo;

--
-- Name: COLUMN events2users.status; Type: COMMENT; Schema: public; Owner: auzo
--

COMMENT ON COLUMN events2users.status IS '1 - пойдет

2 - не пойдет

3 - ой, я не знаю может пойду, может не пойду';


--
-- Name: events_comments; Type: TABLE; Schema: public; Owner: auzo; Tablespace: 
--

CREATE TABLE events_comments (
    id integer NOT NULL,
    event_id integer,
    user_id integer,
    text text,
    created_ts integer,
    parent_id integer DEFAULT 0 NOT NULL,
    childs text DEFAULT ''::text NOT NULL,
    rate integer DEFAULT 0 NOT NULL,
    edit smallint DEFAULT 0,
    edit_ts integer DEFAULT 0
);


ALTER TABLE public.events_comments OWNER TO auzo;

--
-- Name: events_comments_id_seq; Type: SEQUENCE; Schema: public; Owner: auzo
--

CREATE SEQUENCE events_comments_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.events_comments_id_seq OWNER TO auzo;

--
-- Name: events_comments_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: auzo
--

ALTER SEQUENCE events_comments_id_seq OWNED BY events_comments.id;


--
-- Name: events_comments_id_seq; Type: SEQUENCE SET; Schema: public; Owner: auzo
--

SELECT pg_catalog.setval('events_comments_id_seq', 1, false);


--
-- Name: events_id_seq; Type: SEQUENCE; Schema: public; Owner: auzo
--

CREATE SEQUENCE events_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.events_id_seq OWNER TO auzo;

--
-- Name: events_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: auzo
--

ALTER SEQUENCE events_id_seq OWNED BY events.id;


--
-- Name: events_id_seq; Type: SEQUENCE SET; Schema: public; Owner: auzo
--

SELECT pg_catalog.setval('events_id_seq', 1, false);


--
-- Name: feed_id_seq; Type: SEQUENCE; Schema: public; Owner: auzo
--

CREATE SEQUENCE feed_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.feed_id_seq OWNER TO auzo;

--
-- Name: feed_id_seq; Type: SEQUENCE SET; Schema: public; Owner: auzo
--

SELECT pg_catalog.setval('feed_id_seq', 1, false);


--
-- Name: feed; Type: TABLE; Schema: public; Owner: auzo; Tablespace: 
--

CREATE TABLE feed (
    id integer DEFAULT nextval('feed_id_seq'::regclass) NOT NULL,
    user_id integer NOT NULL,
    created_ts integer NOT NULL,
    actor integer NOT NULL,
    action smallint NOT NULL,
    section smallint DEFAULT 1 NOT NULL,
    text text NOT NULL,
    extra text
);


ALTER TABLE public.feed OWNER TO auzo;

--
-- Name: files_id_seq; Type: SEQUENCE; Schema: public; Owner: auzo
--

CREATE SEQUENCE files_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.files_id_seq OWNER TO auzo;

--
-- Name: files_id_seq; Type: SEQUENCE SET; Schema: public; Owner: auzo
--

SELECT pg_catalog.setval('files_id_seq', 1, false);


--
-- Name: files; Type: TABLE; Schema: public; Owner: auzo; Tablespace: 
--

CREATE TABLE files (
    id integer DEFAULT nextval('files_id_seq'::regclass) NOT NULL,
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
    "position" smallint DEFAULT 0
);


ALTER TABLE public.files OWNER TO auzo;

--
-- Name: files_dirs_id_seq; Type: SEQUENCE; Schema: public; Owner: auzo
--

CREATE SEQUENCE files_dirs_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.files_dirs_id_seq OWNER TO auzo;

--
-- Name: files_dirs_id_seq; Type: SEQUENCE SET; Schema: public; Owner: auzo
--

SELECT pg_catalog.setval('files_dirs_id_seq', 1, false);


--
-- Name: files_dirs; Type: TABLE; Schema: public; Owner: auzo; Tablespace: 
--

CREATE TABLE files_dirs (
    id integer DEFAULT nextval('files_dirs_id_seq'::regclass) NOT NULL,
    title character varying NOT NULL,
    "position" smallint,
    parent_id integer
);


ALTER TABLE public.files_dirs OWNER TO auzo;

--
-- Name: friends; Type: TABLE; Schema: public; Owner: auzo; Tablespace: 
--

CREATE TABLE friends (
    id integer NOT NULL,
    user_id integer NOT NULL,
    friend_id integer NOT NULL
);


ALTER TABLE public.friends OWNER TO auzo;

--
-- Name: friends_id_seq; Type: SEQUENCE; Schema: public; Owner: auzo
--

CREATE SEQUENCE friends_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.friends_id_seq OWNER TO auzo;

--
-- Name: friends_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: auzo
--

ALTER SEQUENCE friends_id_seq OWNED BY friends.id;


--
-- Name: friends_id_seq; Type: SEQUENCE SET; Schema: public; Owner: auzo
--

SELECT pg_catalog.setval('friends_id_seq', 1, false);


--
-- Name: friends_pending; Type: TABLE; Schema: public; Owner: auzo; Tablespace: 
--

CREATE TABLE friends_pending (
    id integer NOT NULL,
    user_id integer NOT NULL,
    sent_by integer NOT NULL
);


ALTER TABLE public.friends_pending OWNER TO auzo;

--
-- Name: friends_pending_id_seq; Type: SEQUENCE; Schema: public; Owner: auzo
--

CREATE SEQUENCE friends_pending_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.friends_pending_id_seq OWNER TO auzo;

--
-- Name: friends_pending_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: auzo
--

ALTER SEQUENCE friends_pending_id_seq OWNED BY friends_pending.id;


--
-- Name: friends_pending_id_seq; Type: SEQUENCE SET; Schema: public; Owner: auzo
--

SELECT pg_catalog.setval('friends_pending_id_seq', 1, false);


--
-- Name: groups; Type: TABLE; Schema: public; Owner: auzo; Tablespace: 
--

CREATE TABLE groups (
    id integer NOT NULL,
    user_id integer NOT NULL,
    title character varying,
    created_ts integer NOT NULL,
    rate numeric(12,4) DEFAULT 0 NOT NULL,
    description text,
    photo_salt character varying(8),
    aims text,
    url character varying(255),
    type smallint DEFAULT 0 NOT NULL,
    teritory smallint DEFAULT 1 NOT NULL,
    fti tsvector,
    privacy smallint DEFAULT 1 NOT NULL,
    active smallint,
    creator_id integer,
    category smallint,
    project smallint DEFAULT 0,
    hidden smallint DEFAULT 0,
    level smallint DEFAULT 0 NOT NULL
);


ALTER TABLE public.groups OWNER TO auzo;

--
-- Name: groups_applicants; Type: TABLE; Schema: public; Owner: auzo; Tablespace: 
--

CREATE TABLE groups_applicants (
    group_id integer NOT NULL,
    user_id integer NOT NULL
);


ALTER TABLE public.groups_applicants OWNER TO auzo;

--
-- Name: groups_files_id_seq; Type: SEQUENCE; Schema: public; Owner: auzo
--

CREATE SEQUENCE groups_files_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.groups_files_id_seq OWNER TO auzo;

--
-- Name: groups_files_id_seq; Type: SEQUENCE SET; Schema: public; Owner: auzo
--

SELECT pg_catalog.setval('groups_files_id_seq', 1, false);


--
-- Name: groups_files; Type: TABLE; Schema: public; Owner: auzo; Tablespace: 
--

CREATE TABLE groups_files (
    id integer DEFAULT nextval('groups_files_id_seq'::regclass) NOT NULL,
    dir_id integer DEFAULT 0 NOT NULL,
    group_id integer NOT NULL,
    title character varying(255),
    user_id integer DEFAULT 0 NOT NULL,
    size character varying(24),
    count integer DEFAULT 0,
    exts character varying(200),
    url character varying,
    describe text,
    author character varying(150),
    lang character varying(4),
    "position" smallint DEFAULT 0
);


ALTER TABLE public.groups_files OWNER TO auzo;

--
-- Name: groups_files_dirs_id_seq; Type: SEQUENCE; Schema: public; Owner: auzo
--

CREATE SEQUENCE groups_files_dirs_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.groups_files_dirs_id_seq OWNER TO auzo;

--
-- Name: groups_files_dirs_id_seq; Type: SEQUENCE SET; Schema: public; Owner: auzo
--

SELECT pg_catalog.setval('groups_files_dirs_id_seq', 1, false);


--
-- Name: groups_files_dirs; Type: TABLE; Schema: public; Owner: auzo; Tablespace: 
--

CREATE TABLE groups_files_dirs (
    id integer DEFAULT nextval('groups_files_dirs_id_seq'::regclass) NOT NULL,
    group_id integer NOT NULL,
    title character varying NOT NULL,
    "position" smallint DEFAULT 100 NOT NULL,
    parent_id smallint DEFAULT 0 NOT NULL
);


ALTER TABLE public.groups_files_dirs OWNER TO auzo;

--
-- Name: groups_id_seq; Type: SEQUENCE; Schema: public; Owner: auzo
--

CREATE SEQUENCE groups_id_seq
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.groups_id_seq OWNER TO auzo;

--
-- Name: groups_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: auzo
--

ALTER SEQUENCE groups_id_seq OWNED BY groups.id;


--
-- Name: groups_id_seq; Type: SEQUENCE SET; Schema: public; Owner: auzo
--

SELECT pg_catalog.setval('groups_id_seq', 2, true);


--
-- Name: groups_links_id_seq; Type: SEQUENCE; Schema: public; Owner: auzo
--

CREATE SEQUENCE groups_links_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.groups_links_id_seq OWNER TO auzo;

--
-- Name: groups_links_id_seq; Type: SEQUENCE SET; Schema: public; Owner: auzo
--

SELECT pg_catalog.setval('groups_links_id_seq', 1, false);


--
-- Name: groups_links; Type: TABLE; Schema: public; Owner: auzo; Tablespace: 
--

CREATE TABLE groups_links (
    id integer DEFAULT nextval('groups_links_id_seq'::regclass) NOT NULL,
    dir_id integer DEFAULT 0 NOT NULL,
    group_id integer NOT NULL,
    title character varying(255),
    user_id integer DEFAULT 0 NOT NULL,
    count integer DEFAULT 0,
    url character varying(1000)
);


ALTER TABLE public.groups_links OWNER TO auzo;

--
-- Name: groups_links_dirs_id_seq; Type: SEQUENCE; Schema: public; Owner: auzo
--

CREATE SEQUENCE groups_links_dirs_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.groups_links_dirs_id_seq OWNER TO auzo;

--
-- Name: groups_links_dirs_id_seq; Type: SEQUENCE SET; Schema: public; Owner: auzo
--

SELECT pg_catalog.setval('groups_links_dirs_id_seq', 1, false);


--
-- Name: groups_members; Type: TABLE; Schema: public; Owner: auzo; Tablespace: 
--

CREATE TABLE groups_members (
    group_id integer NOT NULL,
    user_id integer NOT NULL
);


ALTER TABLE public.groups_members OWNER TO auzo;

--
-- Name: groups_news; Type: TABLE; Schema: public; Owner: auzo; Tablespace: 
--

CREATE TABLE groups_news (
    id integer NOT NULL,
    group_id integer NOT NULL,
    text text NOT NULL,
    created_ts integer NOT NULL,
    title character varying(256)
);


ALTER TABLE public.groups_news OWNER TO auzo;

--
-- Name: groups_news_id_seq; Type: SEQUENCE; Schema: public; Owner: auzo
--

CREATE SEQUENCE groups_news_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.groups_news_id_seq OWNER TO auzo;

--
-- Name: groups_news_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: auzo
--

ALTER SEQUENCE groups_news_id_seq OWNED BY groups_news.id;


--
-- Name: groups_news_id_seq; Type: SEQUENCE SET; Schema: public; Owner: auzo
--

SELECT pg_catalog.setval('groups_news_id_seq', 1, false);


--
-- Name: groups_photo_comments_id_seq; Type: SEQUENCE; Schema: public; Owner: auzo
--

CREATE SEQUENCE groups_photo_comments_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.groups_photo_comments_id_seq OWNER TO auzo;

--
-- Name: groups_photo_comments_id_seq; Type: SEQUENCE SET; Schema: public; Owner: auzo
--

SELECT pg_catalog.setval('groups_photo_comments_id_seq', 1, false);


--
-- Name: groups_photo_comments; Type: TABLE; Schema: public; Owner: auzo; Tablespace: 
--

CREATE TABLE groups_photo_comments (
    id integer DEFAULT nextval('groups_photo_comments_id_seq'::regclass) NOT NULL,
    photo_id integer,
    user_id integer,
    text text,
    created_ts integer,
    parent_id integer DEFAULT 0 NOT NULL,
    childs text DEFAULT ''::text NOT NULL,
    rate integer DEFAULT 0 NOT NULL
);


ALTER TABLE public.groups_photo_comments OWNER TO auzo;

--
-- Name: groups_photos_id_seq; Type: SEQUENCE; Schema: public; Owner: auzo
--

CREATE SEQUENCE groups_photos_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.groups_photos_id_seq OWNER TO auzo;

--
-- Name: groups_photos_id_seq; Type: SEQUENCE SET; Schema: public; Owner: auzo
--

SELECT pg_catalog.setval('groups_photos_id_seq', 1, false);


--
-- Name: groups_photos; Type: TABLE; Schema: public; Owner: auzo; Tablespace: 
--

CREATE TABLE groups_photos (
    id integer DEFAULT nextval('groups_photos_id_seq'::regclass) NOT NULL,
    album_id integer DEFAULT 0 NOT NULL,
    group_id integer NOT NULL,
    salt integer NOT NULL,
    title character varying(255),
    user_id integer DEFAULT 0 NOT NULL
);


ALTER TABLE public.groups_photos OWNER TO auzo;

--
-- Name: groups_photos_albums_id_seq; Type: SEQUENCE; Schema: public; Owner: auzo
--

CREATE SEQUENCE groups_photos_albums_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.groups_photos_albums_id_seq OWNER TO auzo;

--
-- Name: groups_photos_albums_id_seq; Type: SEQUENCE SET; Schema: public; Owner: auzo
--

SELECT pg_catalog.setval('groups_photos_albums_id_seq', 1, false);


--
-- Name: groups_photos_albums; Type: TABLE; Schema: public; Owner: auzo; Tablespace: 
--

CREATE TABLE groups_photos_albums (
    id integer DEFAULT nextval('groups_photos_albums_id_seq'::regclass) NOT NULL,
    group_id integer NOT NULL,
    title character varying NOT NULL
);


ALTER TABLE public.groups_photos_albums OWNER TO auzo;

--
-- Name: groups_position; Type: TABLE; Schema: public; Owner: auzo; Tablespace: 
--

CREATE TABLE groups_position (
    id integer NOT NULL,
    group_id integer NOT NULL,
    topic character varying(255) NOT NULL,
    created_ts integer NOT NULL,
    messages_count smallint DEFAULT 0 NOT NULL,
    last_user_id integer NOT NULL,
    updated_ts integer NOT NULL,
    views integer DEFAULT 0 NOT NULL
);


ALTER TABLE public.groups_position OWNER TO auzo;

--
-- Name: groups_position_id_seq; Type: SEQUENCE; Schema: public; Owner: auzo
--

CREATE SEQUENCE groups_position_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.groups_position_id_seq OWNER TO auzo;

--
-- Name: groups_position_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: auzo
--

ALTER SEQUENCE groups_position_id_seq OWNED BY groups_position.id;


--
-- Name: groups_position_id_seq; Type: SEQUENCE SET; Schema: public; Owner: auzo
--

SELECT pg_catalog.setval('groups_position_id_seq', 1, false);


--
-- Name: groups_position_messages; Type: TABLE; Schema: public; Owner: auzo; Tablespace: 
--

CREATE TABLE groups_position_messages (
    id integer NOT NULL,
    topic_id integer NOT NULL,
    user_id integer NOT NULL,
    created_ts integer NOT NULL,
    text text NOT NULL,
    rate integer DEFAULT 0 NOT NULL
);


ALTER TABLE public.groups_position_messages OWNER TO auzo;

--
-- Name: groups_position_messages_id_seq; Type: SEQUENCE; Schema: public; Owner: auzo
--

CREATE SEQUENCE groups_position_messages_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.groups_position_messages_id_seq OWNER TO auzo;

--
-- Name: groups_position_messages_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: auzo
--

ALTER SEQUENCE groups_position_messages_id_seq OWNED BY groups_position_messages.id;


--
-- Name: groups_position_messages_id_seq; Type: SEQUENCE SET; Schema: public; Owner: auzo
--

SELECT pg_catalog.setval('groups_position_messages_id_seq', 1, false);


--
-- Name: groups_proposal; Type: TABLE; Schema: public; Owner: auzo; Tablespace: 
--

CREATE TABLE groups_proposal (
    id integer NOT NULL,
    group_id integer NOT NULL,
    topic character varying(255) NOT NULL,
    created_ts integer NOT NULL,
    messages_count smallint DEFAULT 0 NOT NULL,
    last_user_id integer NOT NULL,
    updated_ts integer NOT NULL,
    views integer DEFAULT 0 NOT NULL
);


ALTER TABLE public.groups_proposal OWNER TO auzo;

--
-- Name: groups_proposal_id_seq; Type: SEQUENCE; Schema: public; Owner: auzo
--

CREATE SEQUENCE groups_proposal_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.groups_proposal_id_seq OWNER TO auzo;

--
-- Name: groups_proposal_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: auzo
--

ALTER SEQUENCE groups_proposal_id_seq OWNED BY groups_proposal.id;


--
-- Name: groups_proposal_id_seq; Type: SEQUENCE SET; Schema: public; Owner: auzo
--

SELECT pg_catalog.setval('groups_proposal_id_seq', 1, false);


--
-- Name: groups_proposal_messages; Type: TABLE; Schema: public; Owner: auzo; Tablespace: 
--

CREATE TABLE groups_proposal_messages (
    id integer NOT NULL,
    topic_id integer NOT NULL,
    user_id integer NOT NULL,
    created_ts integer NOT NULL,
    text text NOT NULL,
    rate integer DEFAULT 0 NOT NULL
);


ALTER TABLE public.groups_proposal_messages OWNER TO auzo;

--
-- Name: groups_proposal_messages_id_seq; Type: SEQUENCE; Schema: public; Owner: auzo
--

CREATE SEQUENCE groups_proposal_messages_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.groups_proposal_messages_id_seq OWNER TO auzo;

--
-- Name: groups_proposal_messages_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: auzo
--

ALTER SEQUENCE groups_proposal_messages_id_seq OWNED BY groups_proposal_messages.id;


--
-- Name: groups_proposal_messages_id_seq; Type: SEQUENCE SET; Schema: public; Owner: auzo
--

SELECT pg_catalog.setval('groups_proposal_messages_id_seq', 1, false);


--
-- Name: groups_topics; Type: TABLE; Schema: public; Owner: auzo; Tablespace: 
--

CREATE TABLE groups_topics (
    id integer NOT NULL,
    group_id integer NOT NULL,
    topic character varying(255) NOT NULL,
    created_ts integer NOT NULL,
    messages_count smallint DEFAULT 0 NOT NULL,
    last_user_id integer NOT NULL,
    updated_ts integer NOT NULL,
    views integer DEFAULT 0 NOT NULL,
    blogpost_id integer
);


ALTER TABLE public.groups_topics OWNER TO auzo;

--
-- Name: groups_topics_id_seq; Type: SEQUENCE; Schema: public; Owner: auzo
--

CREATE SEQUENCE groups_topics_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.groups_topics_id_seq OWNER TO auzo;

--
-- Name: groups_topics_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: auzo
--

ALTER SEQUENCE groups_topics_id_seq OWNED BY groups_topics.id;


--
-- Name: groups_topics_id_seq; Type: SEQUENCE SET; Schema: public; Owner: auzo
--

SELECT pg_catalog.setval('groups_topics_id_seq', 1, false);


--
-- Name: groups_topics_messages; Type: TABLE; Schema: public; Owner: auzo; Tablespace: 
--

CREATE TABLE groups_topics_messages (
    id integer NOT NULL,
    topic_id integer NOT NULL,
    user_id integer NOT NULL,
    created_ts integer NOT NULL,
    text text NOT NULL,
    rate integer DEFAULT 0 NOT NULL
);


ALTER TABLE public.groups_topics_messages OWNER TO auzo;

--
-- Name: groups_topics_messages_id_seq; Type: SEQUENCE; Schema: public; Owner: auzo
--

CREATE SEQUENCE groups_topics_messages_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.groups_topics_messages_id_seq OWNER TO auzo;

--
-- Name: groups_topics_messages_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: auzo
--

ALTER SEQUENCE groups_topics_messages_id_seq OWNED BY groups_topics_messages.id;


--
-- Name: groups_topics_messages_id_seq; Type: SEQUENCE SET; Schema: public; Owner: auzo
--

SELECT pg_catalog.setval('groups_topics_messages_id_seq', 1, false);


--
-- Name: ideas; Type: TABLE; Schema: public; Owner: auzo; Tablespace: 
--

CREATE TABLE ideas (
    id integer NOT NULL,
    user_id integer NOT NULL,
    segment integer NOT NULL,
    text text NOT NULL,
    created_ts integer NOT NULL,
    rate integer DEFAULT 0 NOT NULL,
    title character varying(255) DEFAULT ''::character varying NOT NULL,
    visible boolean DEFAULT true NOT NULL,
    anounces text,
    tags_text character varying,
    views integer DEFAULT 0 NOT NULL,
    text_ru text DEFAULT ''::text,
    anounces_ru text DEFAULT ''::text,
    title_ru text DEFAULT ''::text
);


ALTER TABLE public.ideas OWNER TO auzo;

--
-- Name: ideas_comments_id_seq; Type: SEQUENCE; Schema: public; Owner: auzo
--

CREATE SEQUENCE ideas_comments_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.ideas_comments_id_seq OWNER TO auzo;

--
-- Name: ideas_comments_id_seq; Type: SEQUENCE SET; Schema: public; Owner: auzo
--

SELECT pg_catalog.setval('ideas_comments_id_seq', 1, false);


--
-- Name: ideas_comments; Type: TABLE; Schema: public; Owner: auzo; Tablespace: 
--

CREATE TABLE ideas_comments (
    id integer DEFAULT nextval('ideas_comments_id_seq'::regclass) NOT NULL,
    idea_id integer,
    user_id integer,
    text text,
    created_ts integer,
    parent_id integer DEFAULT 0 NOT NULL,
    childs text DEFAULT ''::text NOT NULL,
    rate integer DEFAULT 0 NOT NULL,
    edit smallint DEFAULT 0,
    edit_ts integer DEFAULT 0
);


ALTER TABLE public.ideas_comments OWNER TO auzo;

--
-- Name: ideas_id_seq; Type: SEQUENCE; Schema: public; Owner: auzo
--

CREATE SEQUENCE ideas_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.ideas_id_seq OWNER TO auzo;

--
-- Name: ideas_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: auzo
--

ALTER SEQUENCE ideas_id_seq OWNED BY ideas.id;


--
-- Name: ideas_id_seq; Type: SEQUENCE SET; Schema: public; Owner: auzo
--

SELECT pg_catalog.setval('ideas_id_seq', 1, false);


--
-- Name: ideas_tags; Type: TABLE; Schema: public; Owner: auzo; Tablespace: 
--

CREATE TABLE ideas_tags (
    id integer NOT NULL,
    name character varying(64)
);


ALTER TABLE public.ideas_tags OWNER TO auzo;

--
-- Name: ideas_tags_id_seq; Type: SEQUENCE; Schema: public; Owner: auzo
--

CREATE SEQUENCE ideas_tags_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.ideas_tags_id_seq OWNER TO auzo;

--
-- Name: ideas_tags_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: auzo
--

ALTER SEQUENCE ideas_tags_id_seq OWNED BY ideas_tags.id;


--
-- Name: ideas_tags_id_seq; Type: SEQUENCE SET; Schema: public; Owner: auzo
--

SELECT pg_catalog.setval('ideas_tags_id_seq', 1, false);


--
-- Name: ideas_to_tags; Type: TABLE; Schema: public; Owner: auzo; Tablespace: 
--

CREATE TABLE ideas_to_tags (
    idea_id integer NOT NULL,
    tag_id integer NOT NULL
);


ALTER TABLE public.ideas_to_tags OWNER TO auzo;

--
-- Name: invites; Type: TABLE; Schema: public; Owner: auzo; Tablespace: 
--

CREATE TABLE invites (
    id integer NOT NULL,
    from_id integer NOT NULL,
    to_id integer NOT NULL,
    obj_id integer NOT NULL,
    type integer NOT NULL,
    status integer DEFAULT 0 NOT NULL,
    created_ts integer NOT NULL
);


ALTER TABLE public.invites OWNER TO auzo;

--
-- Name: invites_id_seq; Type: SEQUENCE; Schema: public; Owner: auzo
--

CREATE SEQUENCE invites_id_seq
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.invites_id_seq OWNER TO auzo;

--
-- Name: invites_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: auzo
--

ALTER SEQUENCE invites_id_seq OWNED BY invites.id;


--
-- Name: invites_id_seq; Type: SEQUENCE SET; Schema: public; Owner: auzo
--

SELECT pg_catalog.setval('invites_id_seq', 4, true);


--
-- Name: leadergroups_files; Type: TABLE; Schema: public; Owner: auzo; Tablespace: 
--

CREATE TABLE leadergroups_files (
    id integer NOT NULL,
    dir_id integer DEFAULT 0 NOT NULL,
    group_id integer NOT NULL,
    salt integer NOT NULL,
    title character varying(255),
    user_id integer DEFAULT 0 NOT NULL,
    size character varying(24),
    count integer DEFAULT 0,
    ext character varying(10)
);


ALTER TABLE public.leadergroups_files OWNER TO auzo;

--
-- Name: leader_groups_files_id_seq; Type: SEQUENCE; Schema: public; Owner: auzo
--

CREATE SEQUENCE leader_groups_files_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.leader_groups_files_id_seq OWNER TO auzo;

--
-- Name: leader_groups_files_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: auzo
--

ALTER SEQUENCE leader_groups_files_id_seq OWNED BY leadergroups_files.id;


--
-- Name: leader_groups_files_id_seq; Type: SEQUENCE SET; Schema: public; Owner: auzo
--

SELECT pg_catalog.setval('leader_groups_files_id_seq', 1, false);


--
-- Name: leadergroups; Type: TABLE; Schema: public; Owner: auzo; Tablespace: 
--

CREATE TABLE leadergroups (
    id integer NOT NULL,
    user_id integer NOT NULL,
    title character varying,
    created_ts integer NOT NULL,
    rate numeric(12,4) DEFAULT 0 NOT NULL,
    description text,
    photo_salt character varying(8),
    aims text,
    url character varying(255),
    type smallint DEFAULT 1 NOT NULL,
    teritory smallint DEFAULT 1 NOT NULL,
    fti tsvector,
    privacy smallint DEFAULT 1 NOT NULL,
    active smallint,
    creator_id integer,
    category smallint,
    project smallint DEFAULT 0,
    hidden smallint DEFAULT 0
);


ALTER TABLE public.leadergroups OWNER TO auzo;

--
-- Name: leader_groups_id_seq; Type: SEQUENCE; Schema: public; Owner: auzo
--

CREATE SEQUENCE leader_groups_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.leader_groups_id_seq OWNER TO auzo;

--
-- Name: leader_groups_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: auzo
--

ALTER SEQUENCE leader_groups_id_seq OWNED BY leadergroups.id;


--
-- Name: leader_groups_id_seq; Type: SEQUENCE SET; Schema: public; Owner: auzo
--

SELECT pg_catalog.setval('leader_groups_id_seq', 1, false);


--
-- Name: leadergroups_news; Type: TABLE; Schema: public; Owner: auzo; Tablespace: 
--

CREATE TABLE leadergroups_news (
    id integer NOT NULL,
    leadergroup_id integer NOT NULL,
    text text NOT NULL,
    created_ts integer NOT NULL,
    title character varying(256)
);


ALTER TABLE public.leadergroups_news OWNER TO auzo;

--
-- Name: leader_groups_news_id_seq; Type: SEQUENCE; Schema: public; Owner: auzo
--

CREATE SEQUENCE leader_groups_news_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.leader_groups_news_id_seq OWNER TO auzo;

--
-- Name: leader_groups_news_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: auzo
--

ALTER SEQUENCE leader_groups_news_id_seq OWNED BY leadergroups_news.id;


--
-- Name: leader_groups_news_id_seq; Type: SEQUENCE SET; Schema: public; Owner: auzo
--

SELECT pg_catalog.setval('leader_groups_news_id_seq', 1, false);


--
-- Name: leadergroups_photo_comments; Type: TABLE; Schema: public; Owner: auzo; Tablespace: 
--

CREATE TABLE leadergroups_photo_comments (
    id integer NOT NULL,
    photo_id integer,
    user_id integer,
    text text,
    created_ts integer,
    parent_id integer DEFAULT 0 NOT NULL,
    childs text DEFAULT ''::text NOT NULL,
    rate integer DEFAULT 0 NOT NULL
);


ALTER TABLE public.leadergroups_photo_comments OWNER TO auzo;

--
-- Name: leader_groups_photo_comments_id_seq; Type: SEQUENCE; Schema: public; Owner: auzo
--

CREATE SEQUENCE leader_groups_photo_comments_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.leader_groups_photo_comments_id_seq OWNER TO auzo;

--
-- Name: leader_groups_photo_comments_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: auzo
--

ALTER SEQUENCE leader_groups_photo_comments_id_seq OWNED BY leadergroups_photo_comments.id;


--
-- Name: leader_groups_photo_comments_id_seq; Type: SEQUENCE SET; Schema: public; Owner: auzo
--

SELECT pg_catalog.setval('leader_groups_photo_comments_id_seq', 1, false);


--
-- Name: leadergroups_photos_albums; Type: TABLE; Schema: public; Owner: auzo; Tablespace: 
--

CREATE TABLE leadergroups_photos_albums (
    id integer NOT NULL,
    leadergroup_id integer NOT NULL,
    title character varying NOT NULL
);


ALTER TABLE public.leadergroups_photos_albums OWNER TO auzo;

--
-- Name: leader_groups_photos_albums_id_seq; Type: SEQUENCE; Schema: public; Owner: auzo
--

CREATE SEQUENCE leader_groups_photos_albums_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.leader_groups_photos_albums_id_seq OWNER TO auzo;

--
-- Name: leader_groups_photos_albums_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: auzo
--

ALTER SEQUENCE leader_groups_photos_albums_id_seq OWNED BY leadergroups_photos_albums.id;


--
-- Name: leader_groups_photos_albums_id_seq; Type: SEQUENCE SET; Schema: public; Owner: auzo
--

SELECT pg_catalog.setval('leader_groups_photos_albums_id_seq', 1, false);


--
-- Name: leadergroups_photos; Type: TABLE; Schema: public; Owner: auzo; Tablespace: 
--

CREATE TABLE leadergroups_photos (
    id integer NOT NULL,
    album_id integer DEFAULT 0 NOT NULL,
    leadergroup_id integer NOT NULL,
    salt integer NOT NULL,
    title character varying(255),
    user_id integer DEFAULT 0 NOT NULL
);


ALTER TABLE public.leadergroups_photos OWNER TO auzo;

--
-- Name: leader_groups_photos_id_seq; Type: SEQUENCE; Schema: public; Owner: auzo
--

CREATE SEQUENCE leader_groups_photos_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.leader_groups_photos_id_seq OWNER TO auzo;

--
-- Name: leader_groups_photos_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: auzo
--

ALTER SEQUENCE leader_groups_photos_id_seq OWNED BY leadergroups_photos.id;


--
-- Name: leader_groups_photos_id_seq; Type: SEQUENCE SET; Schema: public; Owner: auzo
--

SELECT pg_catalog.setval('leader_groups_photos_id_seq', 1, false);


--
-- Name: leadergroups_topics; Type: TABLE; Schema: public; Owner: auzo; Tablespace: 
--

CREATE TABLE leadergroups_topics (
    id integer NOT NULL,
    leadergroup_id integer NOT NULL,
    topic character varying(255) NOT NULL,
    created_ts integer NOT NULL,
    messages_count smallint DEFAULT 0 NOT NULL,
    last_user_id integer NOT NULL,
    updated_ts integer NOT NULL,
    views integer DEFAULT 0 NOT NULL
);


ALTER TABLE public.leadergroups_topics OWNER TO auzo;

--
-- Name: leader_groups_topics_id_seq; Type: SEQUENCE; Schema: public; Owner: auzo
--

CREATE SEQUENCE leader_groups_topics_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.leader_groups_topics_id_seq OWNER TO auzo;

--
-- Name: leader_groups_topics_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: auzo
--

ALTER SEQUENCE leader_groups_topics_id_seq OWNED BY leadergroups_topics.id;


--
-- Name: leader_groups_topics_id_seq; Type: SEQUENCE SET; Schema: public; Owner: auzo
--

SELECT pg_catalog.setval('leader_groups_topics_id_seq', 1, false);


--
-- Name: leadergroups_topics_messages; Type: TABLE; Schema: public; Owner: auzo; Tablespace: 
--

CREATE TABLE leadergroups_topics_messages (
    id integer NOT NULL,
    topic_id integer NOT NULL,
    user_id integer NOT NULL,
    created_ts integer NOT NULL,
    text text NOT NULL,
    rate integer DEFAULT 0 NOT NULL
);


ALTER TABLE public.leadergroups_topics_messages OWNER TO auzo;

--
-- Name: leader_groups_topics_messages_id_seq; Type: SEQUENCE; Schema: public; Owner: auzo
--

CREATE SEQUENCE leader_groups_topics_messages_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.leader_groups_topics_messages_id_seq OWNER TO auzo;

--
-- Name: leader_groups_topics_messages_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: auzo
--

ALTER SEQUENCE leader_groups_topics_messages_id_seq OWNED BY leadergroups_topics_messages.id;


--
-- Name: leader_groups_topics_messages_id_seq; Type: SEQUENCE SET; Schema: public; Owner: auzo
--

SELECT pg_catalog.setval('leader_groups_topics_messages_id_seq', 1, false);


--
-- Name: leadergroups_applicants; Type: TABLE; Schema: public; Owner: auzo; Tablespace: 
--

CREATE TABLE leadergroups_applicants (
    leadergroup_id integer NOT NULL,
    user_id integer NOT NULL
);


ALTER TABLE public.leadergroups_applicants OWNER TO auzo;

--
-- Name: leadergroups_members; Type: TABLE; Schema: public; Owner: auzo; Tablespace: 
--

CREATE TABLE leadergroups_members (
    leadergroup_id integer NOT NULL,
    user_id integer NOT NULL
);


ALTER TABLE public.leadergroups_members OWNER TO auzo;

--
-- Name: mailing_id_seq; Type: SEQUENCE; Schema: public; Owner: auzo
--

CREATE SEQUENCE mailing_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.mailing_id_seq OWNER TO auzo;

--
-- Name: mailing_id_seq; Type: SEQUENCE SET; Schema: public; Owner: auzo
--

SELECT pg_catalog.setval('mailing_id_seq', 1, false);


--
-- Name: mailing; Type: TABLE; Schema: public; Owner: auzo; Tablespace: 
--

CREATE TABLE mailing (
    id integer DEFAULT nextval('mailing_id_seq'::regclass) NOT NULL,
    subject character varying(256) NOT NULL,
    sender_email character varying(256) NOT NULL,
    sender_name character varying(256) NOT NULL,
    body text NOT NULL,
    is_complite boolean DEFAULT false NOT NULL,
    send_all boolean DEFAULT false NOT NULL,
    is_maillists boolean DEFAULT false,
    lists character varying(250),
    is_druft boolean DEFAULT false,
    start integer,
    "end" integer,
    filter character varying(255),
    count integer,
    count_send integer,
    data text
);


ALTER TABLE public.mailing OWNER TO auzo;

--
-- Name: mailing_send_mails_id_seq; Type: SEQUENCE; Schema: public; Owner: auzo
--

CREATE SEQUENCE mailing_send_mails_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.mailing_send_mails_id_seq OWNER TO auzo;

--
-- Name: mailing_send_mails_id_seq; Type: SEQUENCE SET; Schema: public; Owner: auzo
--

SELECT pg_catalog.setval('mailing_send_mails_id_seq', 1, false);


--
-- Name: mailing_send_mails; Type: TABLE; Schema: public; Owner: auzo; Tablespace: 
--

CREATE TABLE mailing_send_mails (
    id integer DEFAULT nextval('mailing_send_mails_id_seq'::regclass) NOT NULL,
    mailing_id integer NOT NULL,
    user_id integer NOT NULL,
    mailing_user_id integer
);


ALTER TABLE public.mailing_send_mails OWNER TO auzo;

--
-- Name: messages; Type: TABLE; Schema: public; Owner: auzo; Tablespace: 
--

CREATE TABLE messages (
    id integer NOT NULL,
    owner integer NOT NULL,
    sender_id integer NOT NULL,
    receiver_id integer NOT NULL,
    body text NOT NULL,
    created_ts integer NOT NULL,
    thread_id integer NOT NULL,
    is_read boolean DEFAULT false NOT NULL,
    attached text,
    sys smallint DEFAULT 0 NOT NULL
);


ALTER TABLE public.messages OWNER TO auzo;

--
-- Name: messages_id_seq; Type: SEQUENCE; Schema: public; Owner: auzo
--

CREATE SEQUENCE messages_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.messages_id_seq OWNER TO auzo;

--
-- Name: messages_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: auzo
--

ALTER SEQUENCE messages_id_seq OWNED BY messages.id;


--
-- Name: messages_id_seq; Type: SEQUENCE SET; Schema: public; Owner: auzo
--

SELECT pg_catalog.setval('messages_id_seq', 1, false);


--
-- Name: messages_threads; Type: TABLE; Schema: public; Owner: auzo; Tablespace: 
--

CREATE TABLE messages_threads (
    id integer NOT NULL,
    sender_id integer NOT NULL,
    receiver_id integer NOT NULL
);


ALTER TABLE public.messages_threads OWNER TO auzo;

--
-- Name: messages_threads_id_seq; Type: SEQUENCE; Schema: public; Owner: auzo
--

CREATE SEQUENCE messages_threads_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.messages_threads_id_seq OWNER TO auzo;

--
-- Name: messages_threads_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: auzo
--

ALTER SEQUENCE messages_threads_id_seq OWNED BY messages_threads.id;


--
-- Name: messages_threads_id_seq; Type: SEQUENCE SET; Schema: public; Owner: auzo
--

SELECT pg_catalog.setval('messages_threads_id_seq', 1, false);


--
-- Name: ns_temp_recomendations; Type: TABLE; Schema: public; Owner: auzo; Tablespace: 
--

CREATE TABLE ns_temp_recomendations (
    id integer,
    email text,
    first_name character varying(128),
    last_name character varying(128),
    inviter text,
    type smallint,
    meaby_user_id integer,
    inviter_id integer
);


ALTER TABLE public.ns_temp_recomendations OWNER TO auzo;

--
-- Name: parties; Type: TABLE; Schema: public; Owner: auzo; Tablespace: 
--

CREATE TABLE parties (
    id integer NOT NULL,
    user_id integer NOT NULL,
    title character varying,
    created_ts integer NOT NULL,
    rate numeric(12,4) DEFAULT 0 NOT NULL,
    description text,
    photo_salt character varying(8),
    aims text,
    url character varying(255),
    direction smallint DEFAULT 1 NOT NULL,
    trust integer DEFAULT 0 NOT NULL,
    not_trust integer DEFAULT 0 NOT NULL,
    direction_custom character varying(128),
    fti tsvector,
    contacts text,
    state character(3) DEFAULT 'new'::bpchar NOT NULL
);


ALTER TABLE public.parties OWNER TO auzo;

--
-- Name: parties_id_seq; Type: SEQUENCE; Schema: public; Owner: auzo
--

CREATE SEQUENCE parties_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.parties_id_seq OWNER TO auzo;

--
-- Name: parties_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: auzo
--

ALTER SEQUENCE parties_id_seq OWNED BY parties.id;


--
-- Name: parties_id_seq; Type: SEQUENCE SET; Schema: public; Owner: auzo
--

SELECT pg_catalog.setval('parties_id_seq', 1, false);


--
-- Name: parties_members; Type: TABLE; Schema: public; Owner: auzo; Tablespace: 
--

CREATE TABLE parties_members (
    user_id integer NOT NULL,
    party_id integer NOT NULL
);


ALTER TABLE public.parties_members OWNER TO auzo;

--
-- Name: parties_news; Type: TABLE; Schema: public; Owner: auzo; Tablespace: 
--

CREATE TABLE parties_news (
    id integer NOT NULL,
    party_id integer NOT NULL,
    text text NOT NULL,
    created_ts integer NOT NULL
);


ALTER TABLE public.parties_news OWNER TO auzo;

--
-- Name: parties_news_id_seq; Type: SEQUENCE; Schema: public; Owner: auzo
--

CREATE SEQUENCE parties_news_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.parties_news_id_seq OWNER TO auzo;

--
-- Name: parties_news_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: auzo
--

ALTER SEQUENCE parties_news_id_seq OWNED BY parties_news.id;


--
-- Name: parties_news_id_seq; Type: SEQUENCE SET; Schema: public; Owner: auzo
--

SELECT pg_catalog.setval('parties_news_id_seq', 1, false);


--
-- Name: parties_program; Type: TABLE; Schema: public; Owner: auzo; Tablespace: 
--

CREATE TABLE parties_program (
    id integer NOT NULL,
    party_id integer NOT NULL,
    segment integer NOT NULL,
    text text NOT NULL,
    "for" integer DEFAULT 0 NOT NULL,
    against integer DEFAULT 0 NOT NULL
);


ALTER TABLE public.parties_program OWNER TO auzo;

--
-- Name: parties_program_id_seq; Type: SEQUENCE; Schema: public; Owner: auzo
--

CREATE SEQUENCE parties_program_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.parties_program_id_seq OWNER TO auzo;

--
-- Name: parties_program_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: auzo
--

ALTER SEQUENCE parties_program_id_seq OWNED BY parties_program.id;


--
-- Name: parties_program_id_seq; Type: SEQUENCE SET; Schema: public; Owner: auzo
--

SELECT pg_catalog.setval('parties_program_id_seq', 1, false);


--
-- Name: parties_topics; Type: TABLE; Schema: public; Owner: auzo; Tablespace: 
--

CREATE TABLE parties_topics (
    id integer NOT NULL,
    party_id integer NOT NULL,
    topic character varying(255) NOT NULL,
    created_ts integer NOT NULL,
    messages_count smallint DEFAULT 0 NOT NULL,
    last_user_id integer NOT NULL,
    updated_ts integer NOT NULL
);


ALTER TABLE public.parties_topics OWNER TO auzo;

--
-- Name: parties_topics_id_seq; Type: SEQUENCE; Schema: public; Owner: auzo
--

CREATE SEQUENCE parties_topics_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.parties_topics_id_seq OWNER TO auzo;

--
-- Name: parties_topics_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: auzo
--

ALTER SEQUENCE parties_topics_id_seq OWNED BY parties_topics.id;


--
-- Name: parties_topics_id_seq; Type: SEQUENCE SET; Schema: public; Owner: auzo
--

SELECT pg_catalog.setval('parties_topics_id_seq', 1, false);


--
-- Name: parties_topics_messages; Type: TABLE; Schema: public; Owner: auzo; Tablespace: 
--

CREATE TABLE parties_topics_messages (
    id integer NOT NULL,
    topic_id integer NOT NULL,
    user_id integer NOT NULL,
    created_ts integer NOT NULL,
    text text NOT NULL
);


ALTER TABLE public.parties_topics_messages OWNER TO auzo;

--
-- Name: parties_topics_messages_id_seq; Type: SEQUENCE; Schema: public; Owner: auzo
--

CREATE SEQUENCE parties_topics_messages_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.parties_topics_messages_id_seq OWNER TO auzo;

--
-- Name: parties_topics_messages_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: auzo
--

ALTER SEQUENCE parties_topics_messages_id_seq OWNED BY parties_topics_messages.id;


--
-- Name: parties_topics_messages_id_seq; Type: SEQUENCE SET; Schema: public; Owner: auzo
--

SELECT pg_catalog.setval('parties_topics_messages_id_seq', 1, false);


--
-- Name: parties_trust; Type: TABLE; Schema: public; Owner: auzo; Tablespace: 
--

CREATE TABLE parties_trust (
    party_id integer NOT NULL,
    trust integer NOT NULL,
    not_trust integer NOT NULL,
    created_ts integer NOT NULL
);


ALTER TABLE public.parties_trust OWNER TO auzo;

--
-- Name: photo; Type: TABLE; Schema: public; Owner: auzo; Tablespace: 
--

CREATE TABLE photo (
    id integer NOT NULL,
    album_id integer DEFAULT 0 NOT NULL,
    user_id integer DEFAULT 0 NOT NULL,
    title character varying(255),
    salt integer NOT NULL,
    cover smallint DEFAULT 0 NOT NULL
);


ALTER TABLE public.photo OWNER TO auzo;

--
-- Name: photo_albums; Type: TABLE; Schema: public; Owner: auzo; Tablespace: 
--

CREATE TABLE photo_albums (
    id integer NOT NULL,
    obj_id integer NOT NULL,
    user_id integer NOT NULL,
    title character varying NOT NULL,
    type smallint DEFAULT 0 NOT NULL
);


ALTER TABLE public.photo_albums OWNER TO auzo;

--
-- Name: photo_albums_id_seq; Type: SEQUENCE; Schema: public; Owner: auzo
--

CREATE SEQUENCE photo_albums_id_seq
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.photo_albums_id_seq OWNER TO auzo;

--
-- Name: photo_albums_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: auzo
--

ALTER SEQUENCE photo_albums_id_seq OWNED BY photo_albums.id;


--
-- Name: photo_albums_id_seq; Type: SEQUENCE SET; Schema: public; Owner: auzo
--

SELECT pg_catalog.setval('photo_albums_id_seq', 2, true);


--
-- Name: photo_comments; Type: TABLE; Schema: public; Owner: auzo; Tablespace: 
--

CREATE TABLE photo_comments (
    id integer NOT NULL,
    photo_id integer,
    user_id integer,
    text text,
    created_ts integer DEFAULT 0,
    parent_id integer DEFAULT 0 NOT NULL,
    childs text DEFAULT ''::text NOT NULL,
    rate integer DEFAULT 0 NOT NULL
);


ALTER TABLE public.photo_comments OWNER TO auzo;

--
-- Name: photo_comments_id_seq; Type: SEQUENCE; Schema: public; Owner: auzo
--

CREATE SEQUENCE photo_comments_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.photo_comments_id_seq OWNER TO auzo;

--
-- Name: photo_comments_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: auzo
--

ALTER SEQUENCE photo_comments_id_seq OWNED BY photo_comments.id;


--
-- Name: photo_comments_id_seq; Type: SEQUENCE SET; Schema: public; Owner: auzo
--

SELECT pg_catalog.setval('photo_comments_id_seq', 1, false);


--
-- Name: photo_id_seq; Type: SEQUENCE; Schema: public; Owner: auzo
--

CREATE SEQUENCE photo_id_seq
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.photo_id_seq OWNER TO auzo;

--
-- Name: photo_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: auzo
--

ALTER SEQUENCE photo_id_seq OWNED BY photo.id;


--
-- Name: photo_id_seq; Type: SEQUENCE SET; Schema: public; Owner: auzo
--

SELECT pg_catalog.setval('photo_id_seq', 2, true);


--
-- Name: polls; Type: TABLE; Schema: public; Owner: auzo; Tablespace: 
--

CREATE TABLE polls (
    id integer NOT NULL,
    user_id integer NOT NULL,
    created_ts integer NOT NULL,
    question character varying NOT NULL,
    count integer DEFAULT 0 NOT NULL,
    is_multi boolean DEFAULT false NOT NULL,
    is_custom boolean DEFAULT false NOT NULL,
    visible boolean DEFAULT true NOT NULL,
    promoted boolean DEFAULT false NOT NULL,
    fti tsvector,
    hidden smallint DEFAULT 0,
    edit smallint DEFAULT 0,
    edit_ts integer DEFAULT 0
);


ALTER TABLE public.polls OWNER TO auzo;

--
-- Name: polls_answers; Type: TABLE; Schema: public; Owner: auzo; Tablespace: 
--

CREATE TABLE polls_answers (
    id integer NOT NULL,
    poll_id integer NOT NULL,
    answer character varying NOT NULL,
    count integer DEFAULT 0 NOT NULL
);


ALTER TABLE public.polls_answers OWNER TO auzo;

--
-- Name: polls_answers_id_seq; Type: SEQUENCE; Schema: public; Owner: auzo
--

CREATE SEQUENCE polls_answers_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.polls_answers_id_seq OWNER TO auzo;

--
-- Name: polls_answers_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: auzo
--

ALTER SEQUENCE polls_answers_id_seq OWNED BY polls_answers.id;


--
-- Name: polls_answers_id_seq; Type: SEQUENCE SET; Schema: public; Owner: auzo
--

SELECT pg_catalog.setval('polls_answers_id_seq', 1, false);


--
-- Name: polls_comments; Type: TABLE; Schema: public; Owner: auzo; Tablespace: 
--

CREATE TABLE polls_comments (
    id integer NOT NULL,
    poll_id integer,
    user_id integer,
    text text,
    created_ts integer,
    parent_id integer DEFAULT 0 NOT NULL,
    childs text DEFAULT ''::text NOT NULL,
    rate integer DEFAULT 0 NOT NULL,
    edit smallint DEFAULT 0,
    edit_ts integer DEFAULT 0
);


ALTER TABLE public.polls_comments OWNER TO auzo;

--
-- Name: polls_comments_id_seq; Type: SEQUENCE; Schema: public; Owner: auzo
--

CREATE SEQUENCE polls_comments_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.polls_comments_id_seq OWNER TO auzo;

--
-- Name: polls_comments_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: auzo
--

ALTER SEQUENCE polls_comments_id_seq OWNED BY polls_comments.id;


--
-- Name: polls_comments_id_seq; Type: SEQUENCE SET; Schema: public; Owner: auzo
--

SELECT pg_catalog.setval('polls_comments_id_seq', 1, false);


--
-- Name: polls_custom; Type: TABLE; Schema: public; Owner: auzo; Tablespace: 
--

CREATE TABLE polls_custom (
    poll_id integer NOT NULL,
    user_id integer NOT NULL,
    text text NOT NULL
);


ALTER TABLE public.polls_custom OWNER TO auzo;

--
-- Name: polls_id_seq; Type: SEQUENCE; Schema: public; Owner: auzo
--

CREATE SEQUENCE polls_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.polls_id_seq OWNER TO auzo;

--
-- Name: polls_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: auzo
--

ALTER SEQUENCE polls_id_seq OWNED BY polls.id;


--
-- Name: polls_id_seq; Type: SEQUENCE SET; Schema: public; Owner: auzo
--

SELECT pg_catalog.setval('polls_id_seq', 1, false);


--
-- Name: polls_votes; Type: TABLE; Schema: public; Owner: auzo; Tablespace: 
--

CREATE TABLE polls_votes (
    id integer NOT NULL,
    poll_id integer NOT NULL,
    answer_id integer NOT NULL,
    user_id integer NOT NULL
);


ALTER TABLE public.polls_votes OWNER TO auzo;

--
-- Name: polls_votes_id_seq; Type: SEQUENCE; Schema: public; Owner: auzo
--

CREATE SEQUENCE polls_votes_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.polls_votes_id_seq OWNER TO auzo;

--
-- Name: polls_votes_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: auzo
--

ALTER SEQUENCE polls_votes_id_seq OWNED BY polls_votes.id;


--
-- Name: polls_votes_id_seq; Type: SEQUENCE SET; Schema: public; Owner: auzo
--

SELECT pg_catalog.setval('polls_votes_id_seq', 1, false);


--
-- Name: rate_history_id_seq; Type: SEQUENCE; Schema: public; Owner: auzo
--

CREATE SEQUENCE rate_history_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.rate_history_id_seq OWNER TO auzo;

--
-- Name: rate_history_id_seq; Type: SEQUENCE SET; Schema: public; Owner: auzo
--

SELECT pg_catalog.setval('rate_history_id_seq', 1, false);


--
-- Name: rate_history; Type: TABLE; Schema: public; Owner: auzo; Tablespace: 
--

CREATE TABLE rate_history (
    id integer DEFAULT nextval('rate_history_id_seq'::regclass) NOT NULL,
    type smallint NOT NULL,
    object_id integer NOT NULL,
    user_id integer NOT NULL,
    rate smallint NOT NULL
);


ALTER TABLE public.rate_history OWNER TO auzo;

--
-- Name: regions; Type: TABLE; Schema: public; Owner: auzo; Tablespace: 
--

CREATE TABLE regions (
    id integer NOT NULL,
    country_id integer,
    name_ua character varying,
    name_ru character varying
);


ALTER TABLE public.regions OWNER TO auzo;

--
-- Name: temp_shev_mails; Type: TABLE; Schema: public; Owner: auzo; Tablespace: 
--

CREATE TABLE temp_shev_mails (
    id integer,
    email character varying(200)
);


ALTER TABLE public.temp_shev_mails OWNER TO auzo;

--
-- Name: user_access; Type: TABLE; Schema: public; Owner: auzo; Tablespace: 
--

CREATE TABLE user_access (
    user_id integer NOT NULL,
    ts integer DEFAULT 0 NOT NULL,
    ip character varying(25) NOT NULL,
    ua text NOT NULL,
    referer text,
    module character varying(20) NOT NULL,
    action character varying(50) NOT NULL,
    id integer
);


ALTER TABLE public.user_access OWNER TO auzo;

--
-- Name: user_access_user_id_seq; Type: SEQUENCE; Schema: public; Owner: auzo
--

CREATE SEQUENCE user_access_user_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.user_access_user_id_seq OWNER TO auzo;

--
-- Name: user_access_user_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: auzo
--

ALTER SEQUENCE user_access_user_id_seq OWNED BY user_access.user_id;


--
-- Name: user_access_user_id_seq; Type: SEQUENCE SET; Schema: public; Owner: auzo
--

SELECT pg_catalog.setval('user_access_user_id_seq', 1, false);


--
-- Name: user_auth; Type: TABLE; Schema: public; Owner: auzo; Tablespace: 
--

CREATE TABLE user_auth (
    id integer NOT NULL,
    email character varying(64) NOT NULL,
    password character varying(32) NOT NULL,
    security_code character varying(64) NOT NULL,
    active boolean NOT NULL,
    type smallint DEFAULT 0 NOT NULL,
    credentials character varying DEFAULT ''::character varying NOT NULL,
    created_ts integer DEFAULT 0 NOT NULL,
    ip character varying(24),
    identification smallint DEFAULT 0,
    famous smallint DEFAULT 0,
    expert text,
    inviter smallint DEFAULT 0,
    shevchenko smallint DEFAULT 0,
    activated_ts integer,
    desktop smallint DEFAULT 0 NOT NULL,
    hidden_type smallint DEFAULT 1 NOT NULL,
    novasys_id integer DEFAULT 0 NOT NULL,
    "from" smallint DEFAULT 0 NOT NULL,
    recomended_by integer DEFAULT 0 NOT NULL,
    invited_by integer DEFAULT 0 NOT NULL,
    interesting boolean DEFAULT false NOT NULL,
    coordinator_contact boolean DEFAULT false NOT NULL,
    chief_contact boolean DEFAULT false NOT NULL,
    last_invite integer DEFAULT 0,
    offline integer DEFAULT 0,
    del integer DEFAULT 0 NOT NULL,
    del_ts integer DEFAULT 0 NOT NULL,
    why character varying(128)
);


ALTER TABLE public.user_auth OWNER TO auzo;

--
-- Name: user_auth_id_seq; Type: SEQUENCE; Schema: public; Owner: auzo
--

CREATE SEQUENCE user_auth_id_seq
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.user_auth_id_seq OWNER TO auzo;

--
-- Name: user_auth_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: auzo
--

ALTER SEQUENCE user_auth_id_seq OWNED BY user_auth.id;


--
-- Name: user_auth_id_seq; Type: SEQUENCE SET; Schema: public; Owner: auzo
--

SELECT pg_catalog.setval('user_auth_id_seq', 21, true);


--
-- Name: user_bio; Type: TABLE; Schema: public; Owner: auzo; Tablespace: 
--

CREATE TABLE user_bio (
    user_id integer NOT NULL,
    birth_family text,
    major_education text,
    work text,
    society text,
    politika text,
    science text,
    additional_education text,
    progress text,
    other text
);


ALTER TABLE public.user_bio OWNER TO auzo;

--
-- Name: user_bio_society_seq; Type: SEQUENCE; Schema: public; Owner: auzo
--

CREATE SEQUENCE user_bio_society_seq
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.user_bio_society_seq OWNER TO auzo;

--
-- Name: user_bio_society_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: auzo
--

ALTER SEQUENCE user_bio_society_seq OWNED BY user_bio.society;


--
-- Name: user_bio_society_seq; Type: SEQUENCE SET; Schema: public; Owner: auzo
--

SELECT pg_catalog.setval('user_bio_society_seq', 1, false);


--
-- Name: user_contact; Type: TABLE; Schema: public; Owner: auzo; Tablespace: 
--

CREATE TABLE user_contact (
    id integer NOT NULL,
    user_id integer,
    who smallint,
    type smallint,
    date integer DEFAULT 0,
    description text
);


ALTER TABLE public.user_contact OWNER TO auzo;

--
-- Name: user_contact_id_seq; Type: SEQUENCE; Schema: public; Owner: auzo
--

CREATE SEQUENCE user_contact_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.user_contact_id_seq OWNER TO auzo;

--
-- Name: user_contact_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: auzo
--

ALTER SEQUENCE user_contact_id_seq OWNED BY user_contact.id;


--
-- Name: user_contact_id_seq; Type: SEQUENCE SET; Schema: public; Owner: auzo
--

SELECT pg_catalog.setval('user_contact_id_seq', 1, false);


--
-- Name: user_data; Type: TABLE; Schema: public; Owner: auzo; Tablespace: 
--

CREATE TABLE user_data (
    user_id integer NOT NULL,
    first_name character varying(64),
    last_name character varying(64),
    city_id integer,
    interests text,
    "position" character varying(128),
    segment integer,
    photo_salt character varying(8),
    gender "char" DEFAULT 'm'::"char" NOT NULL,
    rate numeric(12,4) DEFAULT 0.0 NOT NULL,
    trust integer DEFAULT 0 NOT NULL,
    not_trust integer DEFAULT 0 NOT NULL,
    age integer DEFAULT 16 NOT NULL,
    notify boolean DEFAULT true NOT NULL,
    political_views smallint DEFAULT 0 NOT NULL,
    political_views_sub smallint DEFAULT 0 NOT NULL,
    political_views_custom character varying(128),
    show_political_views boolean DEFAULT true NOT NULL,
    fti tsvector,
    contacts text DEFAULT ''::text NOT NULL,
    language character varying(2) DEFAULT 'ua'::character varying NOT NULL,
    about text DEFAULT ''::text NOT NULL,
    owner_id integer DEFAULT 0 NOT NULL,
    country_id integer,
    region_id integer,
    additional_segment integer,
    books text,
    mobile character varying(50),
    phone character varying(50),
    site character varying(128),
    father_name character varying(64),
    birthday date,
    location character varying(256),
    films text,
    sites text,
    music text,
    leisure text,
    icq character varying(15),
    skype character varying(50),
    last_location text,
    work_phone character varying(50),
    home_phone character varying(50),
    segmen smallint,
    email character varying(100),
    party_city_id smallint,
    party_region_id smallint,
    party_location character varying(256) DEFAULT ''::character varying NOT NULL,
    contact_status integer DEFAULT 0,
    target character varying DEFAULT 0 NOT NULL,
    family smallint DEFAULT 0 NOT NULL,
    birthday_access smallint DEFAULT 0 NOT NULL,
    contact_access smallint DEFAULT 0 NOT NULL,
    aemail character varying(128)
);


ALTER TABLE public.user_data OWNER TO auzo;

--
-- Name: user_data_region_id_seq; Type: SEQUENCE; Schema: public; Owner: auzo
--

CREATE SEQUENCE user_data_region_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.user_data_region_id_seq OWNER TO auzo;

--
-- Name: user_data_region_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: auzo
--

ALTER SEQUENCE user_data_region_id_seq OWNED BY user_data.region_id;


--
-- Name: user_data_region_id_seq; Type: SEQUENCE SET; Schema: public; Owner: auzo
--

SELECT pg_catalog.setval('user_data_region_id_seq', 1, false);


--
-- Name: user_desktop; Type: TABLE; Schema: public; Owner: auzo; Tablespace: 
--

CREATE TABLE user_desktop (
    user_id integer NOT NULL,
    information_brochure_presented smallint DEFAULT 0,
    information_people_count integer DEFAULT 0,
    information_brochure_receive integer DEFAULT 0,
    information_brochure_given integer DEFAULT 0,
    information_banners text DEFAULT 'a:0:{}'::text,
    information_publications text DEFAULT 'a:0:{}'::text,
    people_attracted smallint DEFAULT 0,
    people_recommended smallint DEFAULT 0,
    information_avtonumbers smallint DEFAULT 0,
    information_avtonumbers_photos text DEFAULT 'a:0:{}'::text,
    information_people_private_count integer DEFAULT 0 NOT NULL,
    information_people_phone_count integer DEFAULT 0 NOT NULL,
    information_people_email_count integer DEFAULT 0 NOT NULL,
    information_people_social_count integer DEFAULT 0 NOT NULL,
    other text DEFAULT ''::text NOT NULL,
    functions integer[] DEFAULT '{}'::integer[] NOT NULL,
    regions text
);


ALTER TABLE public.user_desktop OWNER TO auzo;

--
-- Name: user_desktop_education; Type: TABLE; Schema: public; Owner: auzo; Tablespace: 
--

CREATE TABLE user_desktop_education (
    id integer NOT NULL,
    user_id integer,
    education_date text,
    title text,
    description text,
    part smallint DEFAULT 0 NOT NULL
);


ALTER TABLE public.user_desktop_education OWNER TO auzo;

--
-- Name: user_desktop_education_id_seq; Type: SEQUENCE; Schema: public; Owner: auzo
--

CREATE SEQUENCE user_desktop_education_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.user_desktop_education_id_seq OWNER TO auzo;

--
-- Name: user_desktop_education_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: auzo
--

ALTER SEQUENCE user_desktop_education_id_seq OWNED BY user_desktop_education.id;


--
-- Name: user_desktop_education_id_seq; Type: SEQUENCE SET; Schema: public; Owner: auzo
--

SELECT pg_catalog.setval('user_desktop_education_id_seq', 1, false);


--
-- Name: user_desktop_event; Type: TABLE; Schema: public; Owner: auzo; Tablespace: 
--

CREATE TABLE user_desktop_event (
    id integer NOT NULL,
    user_id integer,
    event_date text,
    title text,
    description text,
    part smallint DEFAULT 0 NOT NULL
);


ALTER TABLE public.user_desktop_event OWNER TO auzo;

--
-- Name: user_desktop_events_id_seq; Type: SEQUENCE; Schema: public; Owner: auzo
--

CREATE SEQUENCE user_desktop_events_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.user_desktop_events_id_seq OWNER TO auzo;

--
-- Name: user_desktop_events_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: auzo
--

ALTER SEQUENCE user_desktop_events_id_seq OWNED BY user_desktop_event.id;


--
-- Name: user_desktop_events_id_seq; Type: SEQUENCE SET; Schema: public; Owner: auzo
--

SELECT pg_catalog.setval('user_desktop_events_id_seq', 1, false);


--
-- Name: user_desktop_meeting_id_seq; Type: SEQUENCE; Schema: public; Owner: auzo
--

CREATE SEQUENCE user_desktop_meeting_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.user_desktop_meeting_id_seq OWNER TO auzo;

--
-- Name: user_desktop_meeting_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: auzo
--

ALTER SEQUENCE user_desktop_meeting_id_seq OWNED BY user_desktop_event.id;


--
-- Name: user_desktop_meeting_id_seq; Type: SEQUENCE SET; Schema: public; Owner: auzo
--

SELECT pg_catalog.setval('user_desktop_meeting_id_seq', 1, false);


--
-- Name: user_desktop_meeting; Type: TABLE; Schema: public; Owner: auzo; Tablespace: 
--

CREATE TABLE user_desktop_meeting (
    id integer DEFAULT nextval('user_desktop_meeting_id_seq'::regclass) NOT NULL,
    user_id integer,
    meeting_date text,
    title text,
    description text,
    part smallint DEFAULT 0 NOT NULL
);


ALTER TABLE public.user_desktop_meeting OWNER TO auzo;

--
-- Name: user_desktop_signatures; Type: TABLE; Schema: public; Owner: auzo; Tablespace: 
--

CREATE TABLE user_desktop_signatures (
    id integer NOT NULL,
    user_id integer NOT NULL,
    region_id integer DEFAULT 0 NOT NULL,
    city_id integer DEFAULT 0 NOT NULL,
    plan integer DEFAULT 0 NOT NULL
);


ALTER TABLE public.user_desktop_signatures OWNER TO auzo;

--
-- Name: user_desktop_signatures_id_seq; Type: SEQUENCE; Schema: public; Owner: auzo
--

CREATE SEQUENCE user_desktop_signatures_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.user_desktop_signatures_id_seq OWNER TO auzo;

--
-- Name: user_desktop_signatures_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: auzo
--

ALTER SEQUENCE user_desktop_signatures_id_seq OWNED BY user_desktop_signatures.id;


--
-- Name: user_desktop_signatures_id_seq; Type: SEQUENCE SET; Schema: public; Owner: auzo
--

SELECT pg_catalog.setval('user_desktop_signatures_id_seq', 1, false);


--
-- Name: user_dictionary; Type: TABLE; Schema: public; Owner: auzo; Tablespace: 
--

CREATE TABLE user_dictionary (
    user_id integer NOT NULL,
    names text NOT NULL
);


ALTER TABLE public.user_dictionary OWNER TO auzo;

--
-- Name: user_education; Type: TABLE; Schema: public; Owner: auzo; Tablespace: 
--

CREATE TABLE user_education (
    user_id integer NOT NULL,
    midle_edu_country character varying(50),
    midle_edu_city character varying(80),
    midle_edu_name character varying(50),
    midle_edu_admission character varying(20),
    midle_edu_graduation character varying(20),
    smidle_edu_country character varying(50),
    smidle_edu_city character varying(128),
    smidle_edu_name character varying(128),
    smidle_edu_admission character varying(20),
    smidle_edu_graduation character varying(20),
    major_edu_country character varying(50),
    major_edu_city character varying(80),
    major_edu_name character varying(128),
    major_edu_faculty character varying(128),
    major_edu_department character varying(128),
    major_edu_form integer,
    major_edu_status integer,
    major_edu_admission character varying(20),
    major_edu_graduation character varying(20),
    additional_edu_country character varying(50),
    additional_edu_city character varying(80),
    additional_edu_name character varying(128),
    additional_edu_faculty character varying(128),
    additional_edu_department character varying(80),
    additional_edu_form integer,
    additional_edu_status integer,
    additional_edu_admission character varying(20),
    additional_edu_graduation character varying(20),
    another_edu text
);


ALTER TABLE public.user_education OWNER TO auzo;

--
-- Name: user_education_curses; Type: TABLE; Schema: public; Owner: auzo; Tablespace: 
--

CREATE TABLE user_education_curses (
    user_id integer NOT NULL,
    country smallint DEFAULT 0 NOT NULL,
    location character varying(128),
    name character varying(128),
    start character varying(20),
    "end" character varying(20),
    detail text
);


ALTER TABLE public.user_education_curses OWNER TO auzo;

--
-- Name: user_education_foreign; Type: TABLE; Schema: public; Owner: auzo; Tablespace: 
--

CREATE TABLE user_education_foreign (
    user_id integer NOT NULL,
    length smallint DEFAULT 0 NOT NULL,
    start character varying(20),
    "end" character varying(20),
    country smallint DEFAULT 0 NOT NULL,
    location character varying(128),
    name character varying(128),
    fac character varying(128),
    program smallint DEFAULT 0 NOT NULL,
    sfera smallint DEFAULT 0 NOT NULL,
    regal smallint DEFAULT 0 NOT NULL,
    finance smallint DEFAULT 0 NOT NULL,
    diplom smallint DEFAULT 0 NOT NULL,
    detail text,
    sprogram smallint DEFAULT 0 NOT NULL,
    sprivate character varying(128),
    scredit character varying(128)
);


ALTER TABLE public.user_education_foreign OWNER TO auzo;

--
-- Name: user_education_major; Type: TABLE; Schema: public; Owner: auzo; Tablespace: 
--

CREATE TABLE user_education_major (
    user_id integer NOT NULL,
    location character varying(128),
    name character varying(128),
    faculty character varying(128),
    department character varying(128),
    form smallint DEFAULT 0 NOT NULL,
    status smallint DEFAULT 0 NOT NULL,
    start character varying(20),
    "end" character varying(20)
);


ALTER TABLE public.user_education_major OWNER TO auzo;

--
-- Name: user_education_middle; Type: TABLE; Schema: public; Owner: auzo; Tablespace: 
--

CREATE TABLE user_education_middle (
    user_id integer NOT NULL,
    region smallint DEFAULT 0 NOT NULL,
    city smallint DEFAULT 0 NOT NULL,
    location character varying(128),
    start character varying(20),
    "end" character varying(20),
    name character varying(128)
);


ALTER TABLE public.user_education_middle OWNER TO auzo;

--
-- Name: user_education_smiddle; Type: TABLE; Schema: public; Owner: auzo; Tablespace: 
--

CREATE TABLE user_education_smiddle (
    user_id integer NOT NULL,
    name character varying(128),
    special character varying(128),
    location character varying(128),
    start character varying(20),
    "end" character varying(20)
);


ALTER TABLE public.user_education_smiddle OWNER TO auzo;

--
-- Name: user_education_staging; Type: TABLE; Schema: public; Owner: auzo; Tablespace: 
--

CREATE TABLE user_education_staging (
    user_id integer NOT NULL,
    country smallint DEFAULT 0 NOT NULL,
    location character varying(128),
    name character varying(128),
    site character varying(128),
    post character varying(128),
    start character varying(20),
    "end" character varying(20),
    acting character varying(128),
    detail text
);


ALTER TABLE public.user_education_staging OWNER TO auzo;

--
-- Name: user_location; Type: TABLE; Schema: public; Owner: auzo; Tablespace: 
--

CREATE TABLE user_location (
    user_id integer DEFAULT 0 NOT NULL,
    country_id integer DEFAULT 0,
    region_id integer DEFAULT 0,
    city_id integer DEFAULT 0,
    location character varying(128) NOT NULL,
    metro character varying(128) NOT NULL,
    z_city character varying(128) NOT NULL,
    z_street character varying(128) NOT NULL,
    location_start character varying(128) NOT NULL,
    location_end character varying(128) NOT NULL,
    street character varying(128) NOT NULL
);


ALTER TABLE public.user_location OWNER TO auzo;

--
-- Name: user_mail_access; Type: TABLE; Schema: public; Owner: auzo; Tablespace: 
--

CREATE TABLE user_mail_access (
    user_id integer NOT NULL,
    messages_compose smallint DEFAULT 1,
    blogs_comment smallint DEFAULT 1,
    polls_comment smallint DEFAULT 1,
    messages_wall smallint DEFAULT 1,
    invites_add_group smallint DEFAULT 1,
    invites_add_event smallint DEFAULT 1,
    friends_make smallint DEFAULT 1,
    admin_feed smallint DEFAULT 1,
    messages_spam smallint DEFAULT 1,
    profile_delete_process smallint DEFAULT 1,
    groups_join smallint DEFAULT 1,
    profile_edit smallint DEFAULT 1,
    profile_invite smallint DEFAULT 1,
    groups_create smallint DEFAULT 1
);


ALTER TABLE public.user_mail_access OWNER TO auzo;

--
-- Name: user_mail_access_user_id_seq; Type: SEQUENCE; Schema: public; Owner: auzo
--

CREATE SEQUENCE user_mail_access_user_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.user_mail_access_user_id_seq OWNER TO auzo;

--
-- Name: user_mail_access_user_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: auzo
--

ALTER SEQUENCE user_mail_access_user_id_seq OWNED BY user_mail_access.user_id;


--
-- Name: user_mail_access_user_id_seq; Type: SEQUENCE SET; Schema: public; Owner: auzo
--

SELECT pg_catalog.setval('user_mail_access_user_id_seq', 1, false);


--
-- Name: user_novasys_data; Type: TABLE; Schema: public; Owner: auzo; Tablespace: 
--

CREATE TABLE user_novasys_data (
    user_id integer DEFAULT 0,
    novasys_id integer,
    shevchenko_id integer DEFAULT 0,
    email character varying(100),
    all_contacts text DEFAULT ''::text NOT NULL,
    contacted integer DEFAULT 10,
    phone character varying(255),
    email0 character varying(255),
    site character varying(255),
    mphone1 character varying(255),
    mphone1a character varying(255),
    fax1 character varying(255),
    email1 character varying(255),
    email1a character varying(255),
    site1 character varying(255),
    skype1 character varying(255),
    icq1 character varying(255),
    phone2 character varying(255),
    fax2 character varying(255),
    email2 character varying(255),
    site2 character varying(255),
    name3 character varying(255),
    lname3 character varying(255),
    mname3 character varying(255),
    phone3 character varying(255),
    hphone3 character varying(255),
    mphone3 character varying(255),
    email3 character varying(255),
    skype3 character varying(255),
    icq3 character varying(255),
    notates text DEFAULT ''::text,
    temp_id character varying DEFAULT 0,
    name character varying(200),
    lname character varying(200),
    status integer
);


ALTER TABLE public.user_novasys_data OWNER TO auzo;

--
-- Name: user_questions; Type: TABLE; Schema: public; Owner: auzo; Tablespace: 
--

CREATE TABLE user_questions (
    id integer NOT NULL,
    profile_id integer NOT NULL,
    user_id integer NOT NULL,
    text text NOT NULL,
    rate integer DEFAULT 0 NOT NULL,
    reply text DEFAULT ''::text NOT NULL
);


ALTER TABLE public.user_questions OWNER TO auzo;

--
-- Name: user_questions_id_seq; Type: SEQUENCE; Schema: public; Owner: auzo
--

CREATE SEQUENCE user_questions_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.user_questions_id_seq OWNER TO auzo;

--
-- Name: user_questions_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: auzo
--

ALTER SEQUENCE user_questions_id_seq OWNED BY user_questions.id;


--
-- Name: user_questions_id_seq; Type: SEQUENCE SET; Schema: public; Owner: auzo
--

SELECT pg_catalog.setval('user_questions_id_seq', 1, false);


--
-- Name: user_recomendations; Type: TABLE; Schema: public; Owner: auzo; Tablespace: 
--

CREATE TABLE user_recomendations (
    id integer NOT NULL,
    user_id integer DEFAULT 0 NOT NULL,
    name character varying(30) NOT NULL,
    last_name character varying(50) NOT NULL,
    recomendation text DEFAULT ''::text NOT NULL,
    created_ts integer DEFAULT 0 NOT NULL,
    accepted_ts integer DEFAULT 0 NOT NULL,
    accepted_user_id integer DEFAULT 0 NOT NULL,
    email character varying(100) DEFAULT ''::character varying NOT NULL,
    gender "char" DEFAULT 'm'::"char" NOT NULL,
    country_id integer DEFAULT 1,
    region_id integer DEFAULT 0,
    city_id integer DEFAULT 0
);


ALTER TABLE public.user_recomendations OWNER TO auzo;

--
-- Name: user_recomendations_id_seq; Type: SEQUENCE; Schema: public; Owner: auzo
--

CREATE SEQUENCE user_recomendations_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.user_recomendations_id_seq OWNER TO auzo;

--
-- Name: user_recomendations_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: auzo
--

ALTER SEQUENCE user_recomendations_id_seq OWNED BY user_recomendations.id;


--
-- Name: user_recomendations_id_seq; Type: SEQUENCE SET; Schema: public; Owner: auzo
--

SELECT pg_catalog.setval('user_recomendations_id_seq', 1, false);


--
-- Name: user_recomendations_recomendation_seq; Type: SEQUENCE; Schema: public; Owner: auzo
--

CREATE SEQUENCE user_recomendations_recomendation_seq
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.user_recomendations_recomendation_seq OWNER TO auzo;

--
-- Name: user_recomendations_recomendation_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: auzo
--

ALTER SEQUENCE user_recomendations_recomendation_seq OWNED BY user_recomendations.recomendation;


--
-- Name: user_recomendations_recomendation_seq; Type: SEQUENCE SET; Schema: public; Owner: auzo
--

SELECT pg_catalog.setval('user_recomendations_recomendation_seq', 1, false);


--
-- Name: user_sessions; Type: TABLE; Schema: public; Owner: auzo; Tablespace: 
--

CREATE TABLE user_sessions (
    user_id integer NOT NULL,
    visit_ts integer NOT NULL,
    start integer
);


ALTER TABLE public.user_sessions OWNER TO auzo;

--
-- Name: user_shevchenko_data; Type: TABLE; Schema: public; Owner: auzo; Tablespace: 
--

CREATE TABLE user_shevchenko_data (
    shevchenko_id integer NOT NULL,
    fname character varying(255) DEFAULT ''::character varying NOT NULL,
    fathername character varying(50) DEFAULT ''::character varying NOT NULL,
    sname character varying(255) DEFAULT ''::character varying NOT NULL,
    country smallint DEFAULT 1 NOT NULL,
    region smallint DEFAULT 0 NOT NULL,
    district integer DEFAULT 0 NOT NULL,
    location character varying(255) DEFAULT ''::character varying NOT NULL,
    age character varying(200) DEFAULT ''::character varying,
    sfera character varying(255) DEFAULT ''::character varying NOT NULL,
    email character varying(255) DEFAULT ''::character varying NOT NULL,
    phone character varying(255) DEFAULT ''::character varying NOT NULL,
    site character varying(255) DEFAULT ''::character varying NOT NULL,
    about text DEFAULT ''::text NOT NULL,
    code character varying(255) DEFAULT ''::character varying NOT NULL,
    is_public smallint DEFAULT (-1) NOT NULL,
    is_email smallint DEFAULT 1 NOT NULL,
    activity integer DEFAULT 0 NOT NULL,
    activitya character varying(255) DEFAULT ''::character varying NOT NULL,
    activity2 integer DEFAULT 0 NOT NULL,
    activitya2 character varying(255) DEFAULT ''::character varying NOT NULL,
    referer integer DEFAULT 0 NOT NULL,
    rsocial character varying(255) DEFAULT ''::character varying NOT NULL,
    ranother character varying(255) DEFAULT ''::character varying NOT NULL,
    sex smallint DEFAULT 0 NOT NULL,
    adddate character varying(200) DEFAULT ''::character varying,
    influence character varying(255) DEFAULT ''::character varying NOT NULL,
    email_lang smallint DEFAULT 0 NOT NULL,
    exported smallint DEFAULT (0)::smallint NOT NULL,
    user_id integer DEFAULT 0 NOT NULL
);


ALTER TABLE public.user_shevchenko_data OWNER TO auzo;

--
-- Name: user_temp_photos; Type: TABLE; Schema: public; Owner: auzo; Tablespace: 
--

CREATE TABLE user_temp_photos (
    user_id integer NOT NULL,
    photo character(50)
);


ALTER TABLE public.user_temp_photos OWNER TO auzo;

--
-- Name: user_work; Type: TABLE; Schema: public; Owner: auzo; Tablespace: 
--

CREATE TABLE user_work (
    user_id integer NOT NULL,
    sfera smallint DEFAULT 0 NOT NULL,
    asfera smallint DEFAULT 0 NOT NULL,
    expert character varying(128),
    candidat_dir character varying(128),
    candidat_theme character varying(128),
    candidat_year character varying(128),
    candidat_place character varying(128),
    doctor_dir character varying(128),
    doctor_theme character varying(128),
    doctor_year character varying(128),
    doctor_place character varying(128),
    regal smallint DEFAULT 0 NOT NULL,
    publication character varying(128),
    speech character varying(128),
    special character varying(128)
);


ALTER TABLE public.user_work OWNER TO auzo;

--
-- Name: user_work_action; Type: TABLE; Schema: public; Owner: auzo; Tablespace: 
--

CREATE TABLE user_work_action (
    user_id integer NOT NULL,
    location character varying(128),
    post smallint DEFAULT 0 NOT NULL,
    region character varying(128),
    city character varying(128),
    start character varying(128),
    "end" character varying(128),
    name character varying(128)
);


ALTER TABLE public.user_work_action OWNER TO auzo;

--
-- Name: user_work_election; Type: TABLE; Schema: public; Owner: auzo; Tablespace: 
--

CREATE TABLE user_work_election (
    user_id integer NOT NULL,
    year character varying(128),
    location character varying(128),
    type smallint DEFAULT 0 NOT NULL,
    status smallint DEFAULT 0 NOT NULL,
    region smallint DEFAULT 0 NOT NULL,
    city smallint DEFAULT 0 NOT NULL
);


ALTER TABLE public.user_work_election OWNER TO auzo;

--
-- Name: user_work_party; Type: TABLE; Schema: public; Owner: auzo; Tablespace: 
--

CREATE TABLE user_work_party (
    user_id integer NOT NULL,
    name character varying(128),
    site character varying(128),
    post character varying(128),
    acting character varying(128),
    start character varying(128),
    "end" character varying(128)
);


ALTER TABLE public.user_work_party OWNER TO auzo;

--
-- Name: user_work_prof; Type: TABLE; Schema: public; Owner: auzo; Tablespace: 
--

CREATE TABLE user_work_prof (
    user_id integer NOT NULL,
    country integer DEFAULT 0 NOT NULL,
    location character varying(128),
    name character varying(128),
    site character varying(128),
    post character varying(128),
    acting character varying(128),
    start character varying(128),
    "end" character varying(128)
);


ALTER TABLE public.user_work_prof OWNER TO auzo;

--
-- Name: user_work_public; Type: TABLE; Schema: public; Owner: auzo; Tablespace: 
--

CREATE TABLE user_work_public (
    user_id integer NOT NULL,
    name character varying(128),
    site character varying(128),
    post character varying(128),
    acting character varying(128),
    start character varying(128),
    "end" character varying(128)
);


ALTER TABLE public.user_work_public OWNER TO auzo;

--
-- Name: id; Type: DEFAULT; Schema: public; Owner: auzo
--

ALTER TABLE admin_feed ALTER COLUMN id SET DEFAULT nextval('admin_feed_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: auzo
--

ALTER TABLE attentions ALTER COLUMN id SET DEFAULT nextval('attentions_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: auzo
--

ALTER TABLE blogs_comments ALTER COLUMN id SET DEFAULT nextval('blogs_comments_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: auzo
--

ALTER TABLE blogs_posts ALTER COLUMN id SET DEFAULT nextval('blogs_posts_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: auzo
--

ALTER TABLE blogs_tags ALTER COLUMN id SET DEFAULT nextval('blogs_tags_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: auzo
--

ALTER TABLE cities ALTER COLUMN id SET DEFAULT nextval('cities_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: auzo
--

ALTER TABLE complaints ALTER COLUMN id SET DEFAULT nextval('complaints_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: auzo
--

ALTER TABLE debates ALTER COLUMN id SET DEFAULT nextval('debates_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: auzo
--

ALTER TABLE debates_arguments ALTER COLUMN id SET DEFAULT nextval('debates_arguments_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: auzo
--

ALTER TABLE debates_tags ALTER COLUMN id SET DEFAULT nextval('debates_tags_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: auzo
--

ALTER TABLE email_system ALTER COLUMN id SET DEFAULT nextval('email_system_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: auzo
--

ALTER TABLE events ALTER COLUMN id SET DEFAULT nextval('events_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: auzo
--

ALTER TABLE events_comments ALTER COLUMN id SET DEFAULT nextval('events_comments_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: auzo
--

ALTER TABLE friends ALTER COLUMN id SET DEFAULT nextval('friends_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: auzo
--

ALTER TABLE friends_pending ALTER COLUMN id SET DEFAULT nextval('friends_pending_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: auzo
--

ALTER TABLE groups ALTER COLUMN id SET DEFAULT nextval('groups_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: auzo
--

ALTER TABLE groups_news ALTER COLUMN id SET DEFAULT nextval('groups_news_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: auzo
--

ALTER TABLE groups_position ALTER COLUMN id SET DEFAULT nextval('groups_position_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: auzo
--

ALTER TABLE groups_position_messages ALTER COLUMN id SET DEFAULT nextval('groups_position_messages_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: auzo
--

ALTER TABLE groups_proposal ALTER COLUMN id SET DEFAULT nextval('groups_proposal_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: auzo
--

ALTER TABLE groups_proposal_messages ALTER COLUMN id SET DEFAULT nextval('groups_proposal_messages_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: auzo
--

ALTER TABLE groups_topics ALTER COLUMN id SET DEFAULT nextval('groups_topics_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: auzo
--

ALTER TABLE groups_topics_messages ALTER COLUMN id SET DEFAULT nextval('groups_topics_messages_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: auzo
--

ALTER TABLE ideas ALTER COLUMN id SET DEFAULT nextval('ideas_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: auzo
--

ALTER TABLE ideas_tags ALTER COLUMN id SET DEFAULT nextval('ideas_tags_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: auzo
--

ALTER TABLE invites ALTER COLUMN id SET DEFAULT nextval('invites_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: auzo
--

ALTER TABLE leadergroups ALTER COLUMN id SET DEFAULT nextval('leader_groups_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: auzo
--

ALTER TABLE leadergroups_files ALTER COLUMN id SET DEFAULT nextval('leader_groups_files_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: auzo
--

ALTER TABLE leadergroups_news ALTER COLUMN id SET DEFAULT nextval('leader_groups_news_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: auzo
--

ALTER TABLE leadergroups_photo_comments ALTER COLUMN id SET DEFAULT nextval('leader_groups_photo_comments_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: auzo
--

ALTER TABLE leadergroups_photos ALTER COLUMN id SET DEFAULT nextval('leader_groups_photos_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: auzo
--

ALTER TABLE leadergroups_photos_albums ALTER COLUMN id SET DEFAULT nextval('leader_groups_photos_albums_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: auzo
--

ALTER TABLE leadergroups_topics ALTER COLUMN id SET DEFAULT nextval('leader_groups_topics_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: auzo
--

ALTER TABLE leadergroups_topics_messages ALTER COLUMN id SET DEFAULT nextval('leader_groups_topics_messages_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: auzo
--

ALTER TABLE messages ALTER COLUMN id SET DEFAULT nextval('messages_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: auzo
--

ALTER TABLE messages_threads ALTER COLUMN id SET DEFAULT nextval('messages_threads_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: auzo
--

ALTER TABLE parties ALTER COLUMN id SET DEFAULT nextval('parties_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: auzo
--

ALTER TABLE parties_news ALTER COLUMN id SET DEFAULT nextval('parties_news_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: auzo
--

ALTER TABLE parties_program ALTER COLUMN id SET DEFAULT nextval('parties_program_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: auzo
--

ALTER TABLE parties_topics ALTER COLUMN id SET DEFAULT nextval('parties_topics_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: auzo
--

ALTER TABLE parties_topics_messages ALTER COLUMN id SET DEFAULT nextval('parties_topics_messages_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: auzo
--

ALTER TABLE photo ALTER COLUMN id SET DEFAULT nextval('photo_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: auzo
--

ALTER TABLE photo_albums ALTER COLUMN id SET DEFAULT nextval('photo_albums_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: auzo
--

ALTER TABLE photo_comments ALTER COLUMN id SET DEFAULT nextval('photo_comments_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: auzo
--

ALTER TABLE polls ALTER COLUMN id SET DEFAULT nextval('polls_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: auzo
--

ALTER TABLE polls_answers ALTER COLUMN id SET DEFAULT nextval('polls_answers_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: auzo
--

ALTER TABLE polls_comments ALTER COLUMN id SET DEFAULT nextval('polls_comments_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: auzo
--

ALTER TABLE polls_votes ALTER COLUMN id SET DEFAULT nextval('polls_votes_id_seq'::regclass);


--
-- Name: user_id; Type: DEFAULT; Schema: public; Owner: auzo
--

ALTER TABLE user_access ALTER COLUMN user_id SET DEFAULT nextval('user_access_user_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: auzo
--

ALTER TABLE user_auth ALTER COLUMN id SET DEFAULT nextval('user_auth_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: auzo
--

ALTER TABLE user_contact ALTER COLUMN id SET DEFAULT nextval('user_contact_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: auzo
--

ALTER TABLE user_desktop_education ALTER COLUMN id SET DEFAULT nextval('user_desktop_education_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: auzo
--

ALTER TABLE user_desktop_event ALTER COLUMN id SET DEFAULT nextval('user_desktop_events_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: auzo
--

ALTER TABLE user_desktop_signatures ALTER COLUMN id SET DEFAULT nextval('user_desktop_signatures_id_seq'::regclass);


--
-- Name: user_id; Type: DEFAULT; Schema: public; Owner: auzo
--

ALTER TABLE user_mail_access ALTER COLUMN user_id SET DEFAULT nextval('user_mail_access_user_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: auzo
--

ALTER TABLE user_questions ALTER COLUMN id SET DEFAULT nextval('user_questions_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: auzo
--

ALTER TABLE user_recomendations ALTER COLUMN id SET DEFAULT nextval('user_recomendations_id_seq'::regclass);


--
-- Data for Name: admin_feed; Type: TABLE DATA; Schema: public; Owner: auzo
--



--
-- Data for Name: attentions; Type: TABLE DATA; Schema: public; Owner: auzo
--



--
-- Data for Name: banners; Type: TABLE DATA; Schema: public; Owner: auzo
--



--
-- Data for Name: blogs_comments; Type: TABLE DATA; Schema: public; Owner: auzo
--



--
-- Data for Name: blogs_mentions; Type: TABLE DATA; Schema: public; Owner: auzo
--



--
-- Data for Name: blogs_posts; Type: TABLE DATA; Schema: public; Owner: auzo
--



--
-- Data for Name: blogs_posts_tags; Type: TABLE DATA; Schema: public; Owner: auzo
--



--
-- Data for Name: blogs_tags; Type: TABLE DATA; Schema: public; Owner: auzo
--



--
-- Data for Name: bookmarks; Type: TABLE DATA; Schema: public; Owner: auzo
--



--
-- Data for Name: candidates; Type: TABLE DATA; Schema: public; Owner: auzo
--



--
-- Data for Name: candidates_forecast; Type: TABLE DATA; Schema: public; Owner: auzo
--



--
-- Data for Name: candidates_votes; Type: TABLE DATA; Schema: public; Owner: auzo
--



--
-- Data for Name: cities; Type: TABLE DATA; Schema: public; Owner: auzo
--



--
-- Data for Name: comments; Type: TABLE DATA; Schema: public; Owner: auzo
--



--
-- Data for Name: complaints; Type: TABLE DATA; Schema: public; Owner: auzo
--



--
-- Data for Name: countries; Type: TABLE DATA; Schema: public; Owner: auzo
--

INSERT INTO countries VALUES (169, 'Австралія', 'Австралия');
INSERT INTO countries VALUES (2, 'Австрія', 'Австрия');
INSERT INTO countries VALUES (3, 'Азербайджан', 'Азербайджан');
INSERT INTO countries VALUES (4, 'Албанія', 'Албания');
INSERT INTO countries VALUES (5, 'Алжир', 'Алжир');
INSERT INTO countries VALUES (6, 'Ангола', 'Ангола');
INSERT INTO countries VALUES (7, 'Андорра', 'Андорра');
INSERT INTO countries VALUES (8, 'Антигуа і Барбуда', 'Антигуа и Барбуда');
INSERT INTO countries VALUES (9, 'Аргентина', 'Аргентина');
INSERT INTO countries VALUES (10, 'Вірменія', 'Армения');
INSERT INTO countries VALUES (11, 'Афганістан', 'Афганистан');
INSERT INTO countries VALUES (12, 'Багами', 'Багамы');
INSERT INTO countries VALUES (13, 'Бангладеш', 'Бангладеш');
INSERT INTO countries VALUES (14, 'Барбадос', 'Барбадос');
INSERT INTO countries VALUES (15, 'Бахрейн', 'Бахрейн');
INSERT INTO countries VALUES (16, 'Білорусь', 'Белоруссия');
INSERT INTO countries VALUES (17, 'Беліз', 'Белиз');
INSERT INTO countries VALUES (18, 'Бельгія', 'Бельгия');
INSERT INTO countries VALUES (19, 'Бенін', 'Бенин');
INSERT INTO countries VALUES (20, 'Болгарія', 'Болгария');
INSERT INTO countries VALUES (21, 'Болівія', 'Боливия');
INSERT INTO countries VALUES (22, 'Боснія і Герцеговина', 'Босния и Герцеговина');
INSERT INTO countries VALUES (23, 'Ботсвана', 'Ботсвана');
INSERT INTO countries VALUES (24, 'Бразилія', 'Бразилия');
INSERT INTO countries VALUES (25, 'Бруней', 'Бруней');
INSERT INTO countries VALUES (26, 'Буркіна-Фасо', 'Буркина Фасо');
INSERT INTO countries VALUES (27, 'Бурунді', 'Бурунди');
INSERT INTO countries VALUES (28, 'Бутан', 'Бутан');
INSERT INTO countries VALUES (29, 'Вануату', 'Вануату');
INSERT INTO countries VALUES (30, 'Ватикан', 'Ватикан');
INSERT INTO countries VALUES (31, 'Великобританія', 'Великобритания');
INSERT INTO countries VALUES (32, 'Угорщина', 'Венгрия');
INSERT INTO countries VALUES (33, 'Венесуела', 'Венесуэла');
INSERT INTO countries VALUES (34, 'Східний Тимор', 'Восточный Тимор');
INSERT INTO countries VALUES (35, 'В''єтнам', 'Вьетнам');
INSERT INTO countries VALUES (36, 'Габон', 'Габон');
INSERT INTO countries VALUES (37, 'Гаїті', 'Гаити');
INSERT INTO countries VALUES (38, 'Гайана', 'Гайана');
INSERT INTO countries VALUES (39, 'Гамбія', 'Гамбия');
INSERT INTO countries VALUES (40, 'Гана', 'Гана');
INSERT INTO countries VALUES (41, 'Гватемала', 'Гватемала');
INSERT INTO countries VALUES (42, 'Гвінея', 'Гвинея');
INSERT INTO countries VALUES (43, 'Гвінея-Бісау', 'Гвинея-Бисау');
INSERT INTO countries VALUES (44, 'Німеччина', 'Германия');
INSERT INTO countries VALUES (45, 'Гондурас', 'Гондурас');
INSERT INTO countries VALUES (46, 'Гренада', 'Гренада');
INSERT INTO countries VALUES (47, 'Греція', 'Греция');
INSERT INTO countries VALUES (48, 'Грузія', 'Грузия');
INSERT INTO countries VALUES (49, 'Данія', 'Дания');
INSERT INTO countries VALUES (50, 'Джібуті', 'Джибути');
INSERT INTO countries VALUES (51, 'Домініка', 'Доминика');
INSERT INTO countries VALUES (52, 'Домініканська Республіка', 'Доминиканская Республика');
INSERT INTO countries VALUES (53, 'Єгипет', 'Египет');
INSERT INTO countries VALUES (54, 'Замбія', 'Замбия');
INSERT INTO countries VALUES (55, 'Зімбабве', 'Зимбабве');
INSERT INTO countries VALUES (56, 'Ізраїль', 'Израиль');
INSERT INTO countries VALUES (57, 'Індія', 'Индия');
INSERT INTO countries VALUES (58, 'Індонезія', 'Индонезия');
INSERT INTO countries VALUES (59, 'Йорданія', 'Иордания');
INSERT INTO countries VALUES (60, 'Ірак', 'Ирак');
INSERT INTO countries VALUES (61, 'Іран', 'Иран');
INSERT INTO countries VALUES (62, 'Ірландія', 'Ирландия');
INSERT INTO countries VALUES (63, 'Ісландія', 'Исландия');
INSERT INTO countries VALUES (64, 'Іспанія', 'Испания');
INSERT INTO countries VALUES (65, 'Італія', 'Италия');
INSERT INTO countries VALUES (66, 'Ємен', 'Йемен');
INSERT INTO countries VALUES (67, 'Кабо-Верде', 'Кабо-Верде');
INSERT INTO countries VALUES (68, 'Казахстан', 'Казахстан');
INSERT INTO countries VALUES (69, 'Камбоджа', 'Камбоджа');
INSERT INTO countries VALUES (70, 'Камерун', 'Камерун');
INSERT INTO countries VALUES (71, 'Канада', 'Канада');
INSERT INTO countries VALUES (72, 'Катар', 'Катар');
INSERT INTO countries VALUES (73, 'Кенія', 'Кения');
INSERT INTO countries VALUES (74, 'Кіпр', 'Кипр');
INSERT INTO countries VALUES (75, 'Киргизія', 'Киргизия');
INSERT INTO countries VALUES (76, 'Кірібаті', 'Кирибати');
INSERT INTO countries VALUES (77, 'КНР', 'КНР');
INSERT INTO countries VALUES (78, 'Комори', 'Коморы');
INSERT INTO countries VALUES (79, 'Республіка Конго', 'Республика Конго');
INSERT INTO countries VALUES (80, 'ДР Конго', 'ДР Конго');
INSERT INTO countries VALUES (81, 'Колумбія', 'Колумбия');
INSERT INTO countries VALUES (82, 'КНДР', 'КНДР');
INSERT INTO countries VALUES (83, 'Республіка Корея', 'Республика Корея');
INSERT INTO countries VALUES (84, 'Коста-Ріка', 'Коста-Рика');
INSERT INTO countries VALUES (85, 'Кот-д''Івуар', 'Кот-д’Ивуар');
INSERT INTO countries VALUES (86, 'Куба', 'Куба');
INSERT INTO countries VALUES (87, 'Кувейт', 'Кувейт');
INSERT INTO countries VALUES (88, 'Лаос', 'Лаос');
INSERT INTO countries VALUES (89, 'Латвія', 'Латвия');
INSERT INTO countries VALUES (90, 'Лесото', 'Лесото');
INSERT INTO countries VALUES (91, 'Ліберія', 'Либерия');
INSERT INTO countries VALUES (92, 'Ліван', 'Ливан');
INSERT INTO countries VALUES (93, 'Лівія', 'Ливия');
INSERT INTO countries VALUES (94, 'Литва', 'Литва');
INSERT INTO countries VALUES (95, 'Ліхтенштейн', 'Лихтенштейн');
INSERT INTO countries VALUES (96, 'Люксембург', 'Люксембург');
INSERT INTO countries VALUES (97, 'Маврикій', 'Маврикий');
INSERT INTO countries VALUES (98, 'Мавританія', 'Мавритания');
INSERT INTO countries VALUES (99, 'Мадагаскар', 'Мадагаскар');
INSERT INTO countries VALUES (100, 'Македонія', 'Македония');
INSERT INTO countries VALUES (101, 'Малаві', 'Малави');
INSERT INTO countries VALUES (102, 'Малайзія', 'Малайзия');
INSERT INTO countries VALUES (103, 'Малі', 'Мали');
INSERT INTO countries VALUES (104, 'Мальдіви', 'Мальдивы');
INSERT INTO countries VALUES (105, 'Мальта', 'Мальта');
INSERT INTO countries VALUES (106, 'Марокко', 'Марокко');
INSERT INTO countries VALUES (107, 'Маршаллові Острови', 'Маршалловы Острова');
INSERT INTO countries VALUES (108, 'Мексика', 'Мексика');
INSERT INTO countries VALUES (109, 'Мозамбік', 'Мозамбик');
INSERT INTO countries VALUES (110, 'Молдова', 'Молдавия');
INSERT INTO countries VALUES (111, 'Монако', 'Монако');
INSERT INTO countries VALUES (112, 'Монголія', 'Монголия');
INSERT INTO countries VALUES (113, 'М''янма', 'Мьянма');
INSERT INTO countries VALUES (114, 'Намібія', 'Намибия');
INSERT INTO countries VALUES (115, 'Науру', 'Науру');
INSERT INTO countries VALUES (116, 'Непал', 'Непал');
INSERT INTO countries VALUES (117, 'Нігер', 'Нигер');
INSERT INTO countries VALUES (118, 'Нігерія', 'Нигерия');
INSERT INTO countries VALUES (119, 'Нідерланди', 'Нидерланды');
INSERT INTO countries VALUES (120, 'Нікарагуа', 'Никарагуа');
INSERT INTO countries VALUES (121, 'Нова Зеландія', 'Новая Зеландия');
INSERT INTO countries VALUES (122, 'Норвегія', 'Норвегия');
INSERT INTO countries VALUES (123, 'ОАЕ', 'ОАЭ');
INSERT INTO countries VALUES (124, 'Оман', 'Оман');
INSERT INTO countries VALUES (125, 'Пакистан', 'Пакистан');
INSERT INTO countries VALUES (126, 'Палау', 'Палау');
INSERT INTO countries VALUES (127, 'Панама', 'Панама');
INSERT INTO countries VALUES (128, 'Папуа-Нова Гвінея', 'Папуа-Новая Гвинея');
INSERT INTO countries VALUES (129, 'Парагвай', 'Парагвай');
INSERT INTO countries VALUES (130, 'Перу', 'Перу');
INSERT INTO countries VALUES (131, 'Польща', 'Польша');
INSERT INTO countries VALUES (132, 'Португалія', 'Португалия');
INSERT INTO countries VALUES (133, 'Росія', 'Россия');
INSERT INTO countries VALUES (134, 'Руанда', 'Руанда');
INSERT INTO countries VALUES (135, 'Румунія', 'Румыния');
INSERT INTO countries VALUES (136, 'Сальвадор', 'Сальвадор');
INSERT INTO countries VALUES (137, 'Самоа', 'Самоа');
INSERT INTO countries VALUES (138, 'Сан-Марино', 'Сан-Марино');
INSERT INTO countries VALUES (139, 'Сан-Томе і Прінсіпі', 'Сан-Томе и Принсипи');
INSERT INTO countries VALUES (140, 'Саудівська Аравія', 'Саудовская Аравия');
INSERT INTO countries VALUES (141, 'Свазіленд', 'Свазиленд');
INSERT INTO countries VALUES (142, 'Сейшельські Острови', 'Сейшельские Острова');
INSERT INTO countries VALUES (143, 'Сенегал', 'Сенегал');
INSERT INTO countries VALUES (144, 'Сент-Вінсент і Гренадини', 'Сент-Винсент и Гренадины');
INSERT INTO countries VALUES (145, 'Сент-Кітс і Невіс', 'Сент-Китс и Невис');
INSERT INTO countries VALUES (146, 'Сент-Люсія', 'Сент-Люсия');
INSERT INTO countries VALUES (147, 'Сербія', 'Сербия');
INSERT INTO countries VALUES (148, 'Сінгапур', 'Сингапур');
INSERT INTO countries VALUES (149, 'Сирія', 'Сирия');
INSERT INTO countries VALUES (150, 'Словаччина', 'Словакия');
INSERT INTO countries VALUES (151, 'Словенія', 'Словения');
INSERT INTO countries VALUES (152, 'США', 'США');
INSERT INTO countries VALUES (153, 'Соломонові Острови', 'Соломоновы Острова');
INSERT INTO countries VALUES (154, 'Сомалі', 'Сомали');
INSERT INTO countries VALUES (155, 'Судан', 'Судан');
INSERT INTO countries VALUES (156, 'Суринам', 'Суринам');
INSERT INTO countries VALUES (157, 'Сьєрра-Леоне', 'Сьерра-Леоне');
INSERT INTO countries VALUES (158, 'Таджикистан', 'Таджикистан');
INSERT INTO countries VALUES (159, 'Таїланд', 'Таиланд');
INSERT INTO countries VALUES (160, 'Танзанія', 'Танзания');
INSERT INTO countries VALUES (161, 'Того', 'Того');
INSERT INTO countries VALUES (162, 'Тонга', 'Тонга');
INSERT INTO countries VALUES (163, 'Тринідад і Тобаго', 'Тринидад и Тобаго');
INSERT INTO countries VALUES (164, 'Тувалу', 'Тувалу');
INSERT INTO countries VALUES (165, 'Туніс', 'Тунис');
INSERT INTO countries VALUES (166, 'Туркменія', 'Туркмения');
INSERT INTO countries VALUES (167, 'Туреччина', 'Турция');
INSERT INTO countries VALUES (168, 'Уганда', 'Уганда');
INSERT INTO countries VALUES (1, 'Україна', 'Украина');
INSERT INTO countries VALUES (170, 'Узбекистан', 'Узбекистан');
INSERT INTO countries VALUES (171, 'Уругвай', 'Уругвай');
INSERT INTO countries VALUES (172, 'Федеративні Штати Мікронезії', 'Федеративные Штаты Микронезии');
INSERT INTO countries VALUES (173, 'Фіджі', 'Фиджи');
INSERT INTO countries VALUES (174, 'Філіппіни', 'Филиппины');
INSERT INTO countries VALUES (175, 'Фінляндія', 'Финляндия');
INSERT INTO countries VALUES (176, 'Франція', 'Франция');
INSERT INTO countries VALUES (177, 'Хорватія', 'Хорватия');
INSERT INTO countries VALUES (178, 'ЦАР', 'ЦАР');
INSERT INTO countries VALUES (179, 'Чад', 'Чад');
INSERT INTO countries VALUES (180, 'Чорногорія', 'Черногория');
INSERT INTO countries VALUES (181, 'Чехія', 'Чехия');
INSERT INTO countries VALUES (182, 'Чилі', 'Чили');
INSERT INTO countries VALUES (183, 'Швейцарія', 'Швейцария');
INSERT INTO countries VALUES (184, 'Швеція', 'Швеция');
INSERT INTO countries VALUES (185, 'Шрі-Ланка', 'Шри-Ланка');
INSERT INTO countries VALUES (186, 'Еквадор', 'Эквадор');
INSERT INTO countries VALUES (187, 'Екваторіальна Гвінея', 'Экваториальная Гвинея');
INSERT INTO countries VALUES (188, 'Еритрея', 'Эритрея');
INSERT INTO countries VALUES (189, 'Естонія', 'Эстония');
INSERT INTO countries VALUES (190, 'Ефіопія', 'Эфиопия');
INSERT INTO countries VALUES (191, 'ПАР', 'ЮАР');
INSERT INTO countries VALUES (192, 'Ямайка', 'Ямайка');
INSERT INTO countries VALUES (193, 'Японія', 'Япония');
INSERT INTO countries VALUES (0, '- оберіть країну -', '- выберите страну -');


--
-- Data for Name: debates; Type: TABLE DATA; Schema: public; Owner: auzo
--



--
-- Data for Name: debates_arguments; Type: TABLE DATA; Schema: public; Owner: auzo
--



--
-- Data for Name: debates_debates_tags; Type: TABLE DATA; Schema: public; Owner: auzo
--



--
-- Data for Name: debates_tags; Type: TABLE DATA; Schema: public; Owner: auzo
--



--
-- Data for Name: districts; Type: TABLE DATA; Schema: public; Owner: auzo
--

INSERT INTO districts VALUES (9, 1, 'Первомайський район', 'Первомайский район', 'Pervomayskiy district');
INSERT INTO districts VALUES (97, 6, 'Першотравневий', 'Первомайский', 'Pervomayskiy district');
INSERT INTO districts VALUES (1, 1, 'Бахчисарайський район', 'Бахчисарайский район', 'Bakhchisarai district');
INSERT INTO districts VALUES (2, 1, 'Білогірський район', 'Белогорский район', 'Belogorskij district');
INSERT INTO districts VALUES (3, 1, 'Джанкойський район', 'Джанкойский район', 'Dzhankoy district');
INSERT INTO districts VALUES (4, 1, 'Кіровський район', 'Кировский район', 'Fishing & Hunting');
INSERT INTO districts VALUES (5, 1, 'Красногвардійський район', 'Красногвардейский район', 'Red Guard District');
INSERT INTO districts VALUES (790, 1, 'Судак', 'Судак', 'Zander');
INSERT INTO districts VALUES (6, 1, 'Красноперекопський район', 'Красноперекопский район', 'Krasnoperekopsk district');
INSERT INTO districts VALUES (7, 1, 'Ленінський район', 'Ленинский район', 'Leninsky district');
INSERT INTO districts VALUES (8, 1, 'Нижньогірський район', 'Нижнегорский район', 'Nizhnegorsky district');
INSERT INTO districts VALUES (10, 1, 'Роздольненський район', 'Раздольненский район', 'Razdolnensky district');
INSERT INTO districts VALUES (11, 1, 'Сакський район', 'Сакский район', 'Saki Region');
INSERT INTO districts VALUES (12, 1, 'Сімферопольський район', 'Симферопольский район', 'Simferopol region');
INSERT INTO districts VALUES (13, 1, 'Совєтський район', 'Советский район', 'Soviet district');
INSERT INTO districts VALUES (14, 1, 'Чорноморський район', 'Черноморский район', 'The Black Sea region');
INSERT INTO districts VALUES (19, 2, 'Балаклавський район', 'Балаклавский район', 'Balaklava district');
INSERT INTO districts VALUES (20, 2, 'Гагарінський район', 'Гагаринский район', 'Gagarin district');
INSERT INTO districts VALUES (21, 2, 'Ленинский район', 'Ленинский район', 'Leninsky district');
INSERT INTO districts VALUES (22, 2, 'Нахімовський район', 'Нахимовский район', 'Nakhimovsky district');
INSERT INTO districts VALUES (23, 3, 'Володимир-Волинський район', 'Владимир-Волынский', 'Vladimir-Volyn');
INSERT INTO districts VALUES (24, 3, 'Горохівський район', 'Гороховский район', 'Gorokhovskiy district');
INSERT INTO districts VALUES (25, 3, 'Іваничівський район', 'Иваничевский район', 'Ivanichevsky district');
INSERT INTO districts VALUES (27, 3, 'Ківерцівський район', 'Киверцевский район', 'Kivertsevsky district');
INSERT INTO districts VALUES (28, 3, 'Ковельський район', 'Ковельский район', 'Kovel District');
INSERT INTO districts VALUES (29, 3, 'Локачинський район', 'Локачинский район', 'Lokachinsky district');
INSERT INTO districts VALUES (30, 3, 'Луцький район', 'Луцкий район', 'Lutsk district');
INSERT INTO districts VALUES (31, 3, 'Любешівський район', 'Любешовский район', 'Lyubeshovsky district');
INSERT INTO districts VALUES (32, 3, 'Любомльський район', 'Любомльский район', 'Lyubomlsky district');
INSERT INTO districts VALUES (33, 3, 'Маневицький район', 'Маневичский район', 'Manevichsky district');
INSERT INTO districts VALUES (34, 3, 'Ратнівський район', 'Ратновский район', 'Ratnovsky district');
INSERT INTO districts VALUES (35, 3, 'Рожищенський район', 'Рожищенский район', 'Rozhischensky district');
INSERT INTO districts VALUES (36, 3, 'Старовижівський район', 'Старовыжевский район', 'Starovyzhevsky district');
INSERT INTO districts VALUES (37, 3, 'Турійський район', 'Турийский район', 'Turijsk district');
INSERT INTO districts VALUES (38, 3, 'Щацький район', 'Шацк район', 'Shack district');
INSERT INTO districts VALUES (39, 4, 'Барський район', 'Барский район', 'Barsky district');
INSERT INTO districts VALUES (40, 4, 'Бершадський район', 'Бершадский район', 'Bershad district');
INSERT INTO districts VALUES (41, 4, 'Вінницький район', 'Винницкий район', 'Vinnytsia region');
INSERT INTO districts VALUES (42, 4, 'Гайсинський район', 'Гайсинский район', 'Gaysin district');
INSERT INTO districts VALUES (43, 4, 'Жмеринський район', 'Жмеринский район', 'Zhmerinka district');
INSERT INTO districts VALUES (44, 4, 'Іллінецький район', 'Иллинецкий район', 'Illinetsky district');
INSERT INTO districts VALUES (45, 4, 'Калинівський район', 'Калиновский район', 'Kalinowski district');
INSERT INTO districts VALUES (46, 4, 'Козятинський район', 'Казатинский район', 'Kazatinsky district');
INSERT INTO districts VALUES (47, 4, 'Крижопільський район', 'Крыжопольский район', 'Kryzhopolsky district');
INSERT INTO districts VALUES (48, 4, 'Липовецький район', 'Липовецкий район', 'Lipovetsky District');
INSERT INTO districts VALUES (49, 4, 'Літинський район', 'Литинский район', 'Litinsky district');
INSERT INTO districts VALUES (50, 4, 'Могилів-Подільський район', 'Могилев-Подольский', 'Mogilev-Podolsky');
INSERT INTO districts VALUES (51, 4, 'Мурованокуриловецький район', 'Мурованокуриловецкий район', 'Murovanokurilovetsky district');
INSERT INTO districts VALUES (52, 4, 'Немирівський район', 'Немировский район', 'Nemirovsky district');
INSERT INTO districts VALUES (53, 4, 'Оратівський район', 'Оратовский район', 'Oratovski district');
INSERT INTO districts VALUES (54, 4, 'Піщанський район', 'Песчанский район', 'Peschansky district');
INSERT INTO districts VALUES (55, 4, 'Погребищенський район', 'Погребищенский район', 'Pogrebischensky district');
INSERT INTO districts VALUES (56, 4, 'Теплицький район', 'Теплицкий район', 'Teplitsky district');
INSERT INTO districts VALUES (57, 4, 'Тиврівський район', 'Тывровский район', 'Tyvrovsky district');
INSERT INTO districts VALUES (58, 4, 'Томашпільський район', 'Томашпольский район', 'Tomashpolsky district');
INSERT INTO districts VALUES (59, 4, 'Тростянецький район', 'Тростянецкий район', 'Trostyanetskiy district');
INSERT INTO districts VALUES (60, 4, 'Тульчинський район', 'Тульчинский район', 'Tulchinsky district');
INSERT INTO districts VALUES (61, 4, 'Хмільницький район', 'Хмельницкий район', 'Khmelnitsky region');
INSERT INTO districts VALUES (62, 4, 'Чернівецький район', 'Черновицкий район', 'Chernivtsi region');
INSERT INTO districts VALUES (63, 4, 'Чечельницький район', 'Чечельницкий район', 'Chechelnitsky district');
INSERT INTO districts VALUES (64, 4, 'Шаргородський район', 'Шаргородский район', 'Shargorodsky district');
INSERT INTO districts VALUES (65, 4, 'Ямпільський район', 'Ямпольский район', 'Yampolsky district');
INSERT INTO districts VALUES (66, 5, 'Апостолівський район', 'Апостоловский район', 'Apostolovo district');
INSERT INTO districts VALUES (67, 5, 'Васильківський район', 'Васильковский район', 'Vasilkovsky district');
INSERT INTO districts VALUES (68, 5, 'Верхньодніпровський район', 'Верхнеднепровский район', 'Verhnedneprovsky district');
INSERT INTO districts VALUES (69, 5, 'Дніпропетровський район', 'Днепропетровский район', 'Dnepropetrovsk region');
INSERT INTO districts VALUES (70, 5, 'Криворізький район', 'Криворожский район', 'Krivoy Rog district');
INSERT INTO districts VALUES (71, 5, 'Криничанський район', 'Криничанский район', 'Krinichansky district');
INSERT INTO districts VALUES (26, 3, 'Камінь-Каширський район', 'Камень-Каширский район', 'Kamen-Kashirsky district');
INSERT INTO districts VALUES (72, 5, 'Магдалинівський район', 'Магдалиновский район', 'Magdalinovsky district');
INSERT INTO districts VALUES (73, 5, 'Межівський район', 'Межевский район', 'Mezhevsky district');
INSERT INTO districts VALUES (74, 5, 'Нікопольський район', 'Никопольский район', 'Nikopol district');
INSERT INTO districts VALUES (75, 5, 'Новомосковський район', 'Новомосковский район', 'Novomoskovsk district');
INSERT INTO districts VALUES (76, 5, 'Павлоградський район', 'Павлоградский район', 'Pavlogradskij district');
INSERT INTO districts VALUES (77, 5, 'Петриківський район', 'Петриковский район', 'Petrikov district');
INSERT INTO districts VALUES (78, 5, 'Петропавлівський район', 'Петропавловский район', 'Peter and Paul district');
INSERT INTO districts VALUES (79, 5, 'Покровський район', 'Покровский район', 'Pokrovsky District');
INSERT INTO districts VALUES (80, 5, 'П’ятихатський район', 'Пятихатский район', 'Pyatihatsky district');
INSERT INTO districts VALUES (81, 5, 'Синельниківський район', 'Синельниковский район', 'Sinelnikovskoye district');
INSERT INTO districts VALUES (82, 5, 'Солонянський район', 'Солонянский район', 'Solonyansky district');
INSERT INTO districts VALUES (83, 5, 'Софіївський район', 'Софиевский район', 'Sophia district');
INSERT INTO districts VALUES (84, 5, 'Томаківський район', 'Томаковский район', 'Tomakovsky district');
INSERT INTO districts VALUES (85, 5, 'Царичанський район', 'Царичанский район', 'Tsarichansky district');
INSERT INTO districts VALUES (86, 5, 'Широківський район', 'Широковский район', 'Shirokovskii district');
INSERT INTO districts VALUES (87, 5, 'Юр''ївський район', 'Юрьевський район', 'Yurevsky district');
INSERT INTO districts VALUES (88, 6, 'Амвросіївський район', 'Амвросиевский район', 'Amvrosievsky district');
INSERT INTO districts VALUES (89, 6, 'Артемівський район', 'Артемовский район', 'Artem district');
INSERT INTO districts VALUES (90, 6, 'Великоновосілківський район', 'Великоновоселковский район', 'Velikonovoselkovsky district');
INSERT INTO districts VALUES (91, 6, 'Волноваський район', 'Волновахский район', 'Volnovakha district');
INSERT INTO districts VALUES (92, 6, 'Володарський район', 'Володарский район', 'Volodarsky district');
INSERT INTO districts VALUES (93, 6, 'Добропільський район', 'Добропольский район', 'Dobropolskiy district');
INSERT INTO districts VALUES (94, 6, 'Костянтинівський район', 'Константиновский район', 'The Constantine district');
INSERT INTO districts VALUES (95, 6, 'Красноармійський район', 'Красноармейский район', 'Krasnoarmejskij district');
INSERT INTO districts VALUES (96, 6, 'Краснолиманський район', 'Краснолиманский район', 'Krasnolimanskaya district');
INSERT INTO districts VALUES (98, 6, 'Мар’їнський район', 'Марьинский район', 'Mar''insky district');
INSERT INTO districts VALUES (99, 6, 'Новоазовський район', 'Новоазовский район', 'Novoazovsk district');
INSERT INTO districts VALUES (100, 6, 'Олександрівський район', 'Александровский район', 'Alexander district');
INSERT INTO districts VALUES (101, 6, 'Слов''янський район', 'Словянський район', 'Slovyansky district');
INSERT INTO districts VALUES (102, 6, 'Старобешівський район', 'Старобешевский район', 'Starobeshevskiy district');
INSERT INTO districts VALUES (103, 6, 'Тельманівський район', 'Тельмановский район', 'Telmanovskiy district');
INSERT INTO districts VALUES (104, 6, 'Шахтарський район', 'Шахтерский район', 'Mining District');
INSERT INTO districts VALUES (105, 6, 'Ясинуватський район', 'Ясиноватский район', 'Yasinovatskiy district');
INSERT INTO districts VALUES (106, 7, 'Андрушівський район', 'Андрушевский район', 'Andrushevsky district');
INSERT INTO districts VALUES (107, 7, 'Баранівський район', 'Барановский район', 'Baranowski district');
INSERT INTO districts VALUES (108, 7, 'Бердичівський район', 'Бердичевский район', 'Berdichevsky district');
INSERT INTO districts VALUES (109, 7, 'Брусилівський район', 'Брусиловский район', 'Brusilovsky district');
INSERT INTO districts VALUES (110, 7, 'Володарсько-Волинський район', 'Володарск-Волынский район', 'Volodarsk-Volyn region');
INSERT INTO districts VALUES (112, 7, 'Ємільчинський район', 'Емильчинский район', 'Emilchinsky district');
INSERT INTO districts VALUES (113, 7, 'Житомирський район', 'Житомирский район', 'Zhytomyr region');
INSERT INTO districts VALUES (114, 7, 'Коростенський район', '', '');
INSERT INTO districts VALUES (115, 7, 'Коростишівський район', 'Коростышевский район', 'Korostishevskaya district');
INSERT INTO districts VALUES (116, 7, 'Лугинський район', 'Лугинский район', 'Luginsky district');
INSERT INTO districts VALUES (117, 7, 'Любарський район', 'Любарский район', 'Lubarsky district');
INSERT INTO districts VALUES (118, 7, 'Малинський район', 'Малинский район', 'Malin district');
INSERT INTO districts VALUES (119, 7, 'Народицький район', 'Народичский район', 'Narodichsky district');
INSERT INTO districts VALUES (120, 7, 'Новоград-Волинський район', 'Новоград-Волынский', 'Novograd Volyn');
INSERT INTO districts VALUES (121, 7, 'Овруцький район', 'Овручский район', 'Ovruch district');
INSERT INTO districts VALUES (122, 7, 'Олевський район', 'Олевский район', 'Olevsky district');
INSERT INTO districts VALUES (123, 7, 'Попільнянський район', 'Попельнянский район', 'Popelnyansky district');
INSERT INTO districts VALUES (124, 7, 'Радомишльський район', 'Радомышльский район', 'Radomyshl district');
INSERT INTO districts VALUES (125, 7, 'Ружинський район', 'Ружинский район', 'Ruzhinskaya district');
INSERT INTO districts VALUES (126, 7, 'Червоноармійський район', 'Красноармейский район', 'Krasnoarmejskij district');
INSERT INTO districts VALUES (127, 7, 'Черняхівський район', 'Черняховский район', 'Chernyakhovsky district');
INSERT INTO districts VALUES (128, 7, 'Чуднівський район', 'Чудновский район', 'Chudnovsky district');
INSERT INTO districts VALUES (129, 8, 'Берегівський район', 'Береговский район', 'Berehove district');
INSERT INTO districts VALUES (130, 8, 'Великоберезнянський район', 'Великоберезнянский район', 'Velikobereznyansky district');
INSERT INTO districts VALUES (131, 8, 'Виноградівський район', 'Виноградовский район', 'Vynohradiv district');
INSERT INTO districts VALUES (132, 8, 'Воловецький район', 'Воловецкий район', 'Volovetsky district');
INSERT INTO districts VALUES (133, 8, 'Іршавський район', 'Иршавский район', 'Irshava district');
INSERT INTO districts VALUES (134, 8, 'Міжгірський район', 'Межгорский район', 'Mezhgorsky district');
INSERT INTO districts VALUES (135, 8, 'Мукачівський район', 'Мукачевский район', 'Mukachevo district');
INSERT INTO districts VALUES (136, 8, 'Перечинський район', 'Перечинский район', 'Perechyn district');
INSERT INTO districts VALUES (137, 8, 'Рахівський район', 'Раховский район', 'Rakhiv district');
INSERT INTO districts VALUES (138, 8, 'Свалявський район', 'Свалявский район', 'Svalyava district');
INSERT INTO districts VALUES (139, 8, 'Тячівський район', 'Тячевский район', 'Tyachiv district');
INSERT INTO districts VALUES (140, 8, 'Ужгородський район', 'Ужгородский район', 'Uzhgorod district');
INSERT INTO districts VALUES (141, 8, 'Хустський район', 'Хустский район', 'Khust district');
INSERT INTO districts VALUES (142, 9, 'Бердянський район', 'Бердянский район', 'Berdyansk district');
INSERT INTO districts VALUES (143, 9, 'Василівський район', 'Васильевский район', 'Vasilevsky district');
INSERT INTO districts VALUES (144, 9, 'Великобілозерський район', 'Великобелозерский район', 'Velikobelozersky district');
INSERT INTO districts VALUES (145, 9, 'Веселівський район', 'Веселовский район', 'Veselovsky district');
INSERT INTO districts VALUES (146, 9, 'Вільнянський район', 'Вольнянский район', 'Volnyansk district');
INSERT INTO districts VALUES (147, 9, 'Гуляйпільський район', 'Гуляйпольский район', 'Gulyaypolsky district');
INSERT INTO districts VALUES (148, 9, 'Запорізький район', 'Запорожский район', 'Zaporozhye region');
INSERT INTO districts VALUES (149, 9, 'Кам’янсько-Дніпровський район', 'Каменско-Днепровский район', 'Kamensko-Dnieper region');
INSERT INTO districts VALUES (150, 9, 'Куйбишевський район', 'Куйбышевский район', 'Kuibyshev district');
INSERT INTO districts VALUES (151, 9, 'Мелітопольський район', 'Мелитопольский район', 'Melitopol district');
INSERT INTO districts VALUES (152, 9, 'Михайлівський район', 'Михайловский район', 'Mikhaylovskiy district');
INSERT INTO districts VALUES (153, 9, 'Новомиколаївський район', 'Новониколаевский район', 'Novonikolayevsky district');
INSERT INTO districts VALUES (154, 9, 'Оріхівський район', 'Ореховский район', 'Orekhovsky district');
INSERT INTO districts VALUES (155, 9, 'Пологівський район', 'Пологовский район', 'Pology district');
INSERT INTO districts VALUES (156, 9, 'Приазовський район', 'Приазовский район', 'Azov district');
INSERT INTO districts VALUES (157, 9, 'Приморський район', 'Приморский район', 'Littoral');
INSERT INTO districts VALUES (158, 9, 'Розівський район', 'Розовский район', 'Rozovsky district');
INSERT INTO districts VALUES (159, 9, 'Токмацький район', 'Токмакский район', 'Tokmakskiy district');
INSERT INTO districts VALUES (160, 9, 'Чернігівський район', 'Черниговский район', 'Chernihiv region');
INSERT INTO districts VALUES (161, 9, 'Якимівський район', 'Акимовский район', 'Akimov district');
INSERT INTO districts VALUES (162, 10, 'Богородчанський район', 'Богородчанский район', 'Bohorodchany district');
INSERT INTO districts VALUES (164, 10, 'Верховинський район', 'Верховинский район', 'Verkhovyna district');
INSERT INTO districts VALUES (165, 10, 'Галицький район', 'Галичский район', 'Galich district');
INSERT INTO districts VALUES (166, 10, 'Городенківський район', 'Городенковский район', 'Gorodenkovskogo district');
INSERT INTO districts VALUES (167, 10, 'Долинський район', 'Долинский район', 'Dolinsky District');
INSERT INTO districts VALUES (168, 10, 'Калуський район', 'Калушский район', 'Kalush district');
INSERT INTO districts VALUES (169, 10, 'Коломийський район', 'Коломыйский район', 'Kolomyia district');
INSERT INTO districts VALUES (170, 10, 'Косівський район', 'Косовский район', 'The Kosovo region');
INSERT INTO districts VALUES (171, 10, 'Надвірнянський район', 'Надворнянский район', 'Nadvirna district');
INSERT INTO districts VALUES (172, 10, 'Рогатинський район', 'Рогатынский район', 'Rogatynsky district');
INSERT INTO districts VALUES (173, 10, 'Рожнятівський район', 'Рожнятовский район', 'Rozhnyativ district');
INSERT INTO districts VALUES (174, 10, 'Снятинський район', 'Снятынский район', 'Snyatynsky district');
INSERT INTO districts VALUES (175, 10, 'Тисменицький район', 'Тисменицкий район', 'Tismenitsky district');
INSERT INTO districts VALUES (176, 10, 'Тлумацький район', 'Тлумачский район', 'Tlumachsky district');
INSERT INTO districts VALUES (177, 11, 'Бобринецький район', 'Бобринецкий район', 'Bobrinetsky district');
INSERT INTO districts VALUES (178, 11, 'Вільшанський район', 'Ольшанский район', 'Olshansky district');
INSERT INTO districts VALUES (179, 11, 'Гайворонський район', 'Гайворонский район', 'Gajvoronsky district');
INSERT INTO districts VALUES (180, 11, 'Голованівський район', 'Головановский район', 'Golovanovsky district');
INSERT INTO districts VALUES (181, 11, 'Добровеличківський район', 'Добровеличковский район', 'Dobrovelichkovsky district');
INSERT INTO districts VALUES (182, 11, 'Долинський район', 'Долинский район', 'Dolinsky District');
INSERT INTO districts VALUES (183, 11, 'Знам’янський район', 'Знаменский район', 'Znamensky district');
INSERT INTO districts VALUES (184, 11, 'Кіровоградський район', 'Кировоградский район', 'Kirovohrad region');
INSERT INTO districts VALUES (185, 11, 'Компаніївський район', 'Компанеевский район', 'Kompaneevsky district');
INSERT INTO districts VALUES (186, 11, 'Маловисківський район', 'Маловисковский район', 'Maloviskovsky district');
INSERT INTO districts VALUES (187, 11, 'Новгородківський район', 'Новгородковский район', 'Novgorodkovsky district');
INSERT INTO districts VALUES (188, 11, 'Новоархангельський район', 'Новоархангельский район', 'Novo-Arkhangelsk district');
INSERT INTO districts VALUES (189, 11, 'Новомиргородський район', 'Новомиргородский район', 'Novomirgorod district');
INSERT INTO districts VALUES (190, 11, 'Новоукраїнський район', 'Новоукраинский район', 'Novoukrainka district');
INSERT INTO districts VALUES (191, 11, 'Олександрівський район', 'Александровский район', 'Alexander district');
INSERT INTO districts VALUES (192, 11, 'Олександрійський район', 'Александрийский район', 'Alexandria district');
INSERT INTO districts VALUES (193, 11, 'Онуфріївський район', 'Онуфриевский район', 'Onufrievsky district');
INSERT INTO districts VALUES (194, 11, 'Петрівський район', 'Петровский район', 'Petrovsky District');
INSERT INTO districts VALUES (195, 11, 'Світловодський район', 'Светловодский район', 'Svetlovodsk district');
INSERT INTO districts VALUES (196, 11, 'Ульяновський район', 'Ульяновский район', 'Ulyanovsk region');
INSERT INTO districts VALUES (197, 11, 'Устинівський район', 'Устиновский район', 'Ustinov district');
INSERT INTO districts VALUES (198, 12, 'Баришівський район', 'Барышевский район', 'Baryshevsky district');
INSERT INTO districts VALUES (199, 12, 'Білоцерківський район', 'Билоцерквивський район', 'Bilotserkvivsky district');
INSERT INTO districts VALUES (200, 12, 'Богуславський район', 'Богуславский район', 'Boguslavsky district');
INSERT INTO districts VALUES (201, 12, 'Бориспільський район', 'Бориспольский район', 'Boryspil district');
INSERT INTO districts VALUES (202, 12, 'Бородянський район', 'Бородянский район', 'Borodyansky district');
INSERT INTO districts VALUES (203, 12, 'Броварський район', 'Броварской район', 'Brovarsky district');
INSERT INTO districts VALUES (204, 12, 'Васильківський район', 'Васильковский район', 'Vasilkovsky district');
INSERT INTO districts VALUES (205, 12, 'Вишгородський район', 'Вышгородский район', 'Vyshgorod district');
INSERT INTO districts VALUES (206, 12, 'Володарський район', 'Володарский район', 'Volodarsky district');
INSERT INTO districts VALUES (207, 12, 'Згурівський район', 'Згуровский район', 'Zgurovsky district');
INSERT INTO districts VALUES (208, 12, 'Іванківський район', 'Иванковский район', 'Ivankov district');
INSERT INTO districts VALUES (209, 12, 'Кагарлицький район', 'Кагарлыцкий район', 'Kagarlytsky district');
INSERT INTO districts VALUES (210, 12, 'Києво-Святошинський район', 'Киево-Святошинский район', 'Kiev Svyatoshinsky district');
INSERT INTO districts VALUES (211, 12, 'Макарівський район', 'Макаровский район', 'Makarov district');
INSERT INTO districts VALUES (212, 12, 'Миронівський район', 'Мироновский район', 'Myronivsky district');
INSERT INTO districts VALUES (213, 12, 'Обухівський район', 'Обуховский район', 'Obukhov district');
INSERT INTO districts VALUES (214, 12, 'Переяслав-Хмельницький район', 'Переяслав-Хмельницкий', 'Pereyaslav-Khmelnytsky');
INSERT INTO districts VALUES (215, 12, 'Поліський район', 'Полесский район', 'Poleski district');
INSERT INTO districts VALUES (216, 12, 'Рокитнянський район', 'Рокитнянский район', 'Rokitnyansky district');
INSERT INTO districts VALUES (217, 12, 'Сквирський район', 'Сквирский район', 'Skvirsky district');
INSERT INTO districts VALUES (218, 12, 'Ставищенський район', 'Ставищенский район', 'Stavischensky district');
INSERT INTO districts VALUES (219, 12, 'Таращанський район', 'Таращанский район', 'Tarashchansky district');
INSERT INTO districts VALUES (220, 12, 'Тетіївський район', 'Тетиевский район', 'Tetievsky district');
INSERT INTO districts VALUES (221, 12, 'Фастівський район', 'Фастовский район', 'Fastovsky district');
INSERT INTO districts VALUES (222, 12, 'Яготинський район', 'Яготинский район', 'Yagotynsky district');
INSERT INTO districts VALUES (223, 13, 'Голосіївський район', 'Голосеевский район', 'Goloseevsky district');
INSERT INTO districts VALUES (224, 13, 'Дарницький район', 'Дарницкий район', 'Darnytskyi district');
INSERT INTO districts VALUES (225, 13, 'Деснянський район', 'Деснянский район', 'Desnianskyi district');
INSERT INTO districts VALUES (226, 13, 'Дніпровський район', 'Днепровский район', 'Dnieper region');
INSERT INTO districts VALUES (227, 13, 'Оболонський район', 'Оболонский район', 'Obolonskyi district');
INSERT INTO districts VALUES (228, 13, 'Печерський район', 'Печерский район', 'Crypt district');
INSERT INTO districts VALUES (230, 13, 'Святошинський район', 'Святошинский район', 'Svyatoshinsky district');
INSERT INTO districts VALUES (232, 13, 'Шевченківський район', 'Шевченковский район', 'Shevchenko district');
INSERT INTO districts VALUES (233, 14, 'Антрацитівський район', 'Антрацитовский район', 'Antratsitovsky district');
INSERT INTO districts VALUES (234, 14, 'Біловодський район', 'Беловодский район', 'Belovodsky district');
INSERT INTO districts VALUES (235, 14, 'Білокуракинський район', 'Белокуракинский район', 'Belokurakinsky district');
INSERT INTO districts VALUES (236, 14, 'Краснодонський район', 'Краснодонский район', 'Krasnodon district');
INSERT INTO districts VALUES (237, 14, 'Кремінський район', 'Кременский район', 'Kremenskaya district');
INSERT INTO districts VALUES (238, 14, 'Лутугинський район', 'Лутугинский район', 'Lutugino district');
INSERT INTO districts VALUES (239, 14, 'Марківський район', 'Марковский район', 'Markov district');
INSERT INTO districts VALUES (240, 14, 'Міловський район', 'Меловский район', 'Melovsky district');
INSERT INTO districts VALUES (241, 14, 'Новоайдарський район', 'Новоайдарский район', 'Novoaydarsky district');
INSERT INTO districts VALUES (242, 14, 'Новопсковський район', 'Новопсковский район', 'Novopskovsky district');
INSERT INTO districts VALUES (243, 14, 'Перевальський район', 'Перевальский район', 'Perevalskiy district');
INSERT INTO districts VALUES (244, 14, 'Попаснянський район', 'Попаснянский район', 'Popasnyansky district');
INSERT INTO districts VALUES (245, 14, 'Сватівський район', 'Сватовский район', 'Svatovsky district');
INSERT INTO districts VALUES (246, 14, 'Свердловський район', 'Свердловский район', 'Sverdlovsk region');
INSERT INTO districts VALUES (247, 14, 'Слов’яносербський район', 'Славяносербский район', 'Slavyanoserbsk district');
INSERT INTO districts VALUES (248, 14, 'Станично-Луганський район', 'Станично-Луганский район', 'Stanichno-Lugansk region');
INSERT INTO districts VALUES (249, 14, 'Старобільський район', 'Старобельский район', 'Starobelsk district');
INSERT INTO districts VALUES (250, 14, 'Троїцький район', 'Троицкий район', 'Trinity District');
INSERT INTO districts VALUES (251, 15, 'Бродівський район', 'Бродовский район', 'Brody district');
INSERT INTO districts VALUES (252, 15, 'Буський район', 'Буский район', 'Busk district');
INSERT INTO districts VALUES (253, 15, 'Городоцький район', 'Городокский район', 'Gorodok district');
INSERT INTO districts VALUES (254, 15, 'Дрогобицький район', 'Дрогобычский район', 'Drogobych district');
INSERT INTO districts VALUES (255, 15, 'Жидачівський район', 'Жидачевский район', 'Zhydachiv district');
INSERT INTO districts VALUES (256, 15, 'Жовківський район', 'Жовковский район', 'Zhovkovsky district');
INSERT INTO districts VALUES (257, 15, 'Золочівський район', 'Золочевский район', 'Zolochiv district');
INSERT INTO districts VALUES (258, 15, 'Кам’янсько-Бузький район', 'Каменско-Бугский район', 'Kamensko-Bug District');
INSERT INTO districts VALUES (259, 15, 'Миколаївський район', 'Николаевский район', 'Nikolaev region');
INSERT INTO districts VALUES (260, 15, 'Мостиський район', 'Мостиский район', 'Mostiska district');
INSERT INTO districts VALUES (261, 15, 'Перемишлянський район', 'Перемышлянский район', 'Peremyshlyany District');
INSERT INTO districts VALUES (262, 15, 'Пустомитівський район', 'Пустомытовский район', 'Pustomyty District');
INSERT INTO districts VALUES (263, 15, 'Радехівський район', 'Радеховский район', 'Radehovsky district');
INSERT INTO districts VALUES (264, 15, 'Самбірський район', 'Самборский район', 'Samborski district');
INSERT INTO districts VALUES (265, 15, 'Сколівський район', 'Сколевский район', 'Skole district');
INSERT INTO districts VALUES (266, 15, 'Сокальський район', 'Сокальский район', 'Sokalski district');
INSERT INTO districts VALUES (267, 15, 'Старосамбірський район', 'Старосамборский район', 'Starosamborsky district');
INSERT INTO districts VALUES (268, 15, 'Стрийський район', 'Стрыйский район', 'Stryj district');
INSERT INTO districts VALUES (269, 15, 'Турківський район', 'Турковский район', 'Turka district');
INSERT INTO districts VALUES (270, 15, 'Яворівський район', 'Яворовский район', 'Jaworowski district');
INSERT INTO districts VALUES (271, 16, 'Арбузинський район', 'Арбузинский район', 'Arbuzinsky district');
INSERT INTO districts VALUES (272, 16, 'Баштанський район', 'Баштанский район', 'Bashtansky district');
INSERT INTO districts VALUES (273, 16, 'Березанський район', 'Березанский район', 'Berezanskii district');
INSERT INTO districts VALUES (274, 16, 'Березнегуватський район', 'Березнеговатский район', 'Bereznegovatsky district');
INSERT INTO districts VALUES (275, 16, 'Братський район', 'Братский район', 'Fraternal district');
INSERT INTO districts VALUES (276, 16, 'Веселинівський район', 'Веселиновский район', 'Veselinovsky district');
INSERT INTO districts VALUES (277, 16, 'Вознесенський район', 'Вознесенский район', 'Ascension district');
INSERT INTO districts VALUES (278, 16, 'Врадіївський район', 'Врадиевский район', 'Vradievsky district');
INSERT INTO districts VALUES (279, 16, 'Доманівський район', 'Доманевский район', 'Domanevskii district');
INSERT INTO districts VALUES (280, 16, 'Єланецький район', 'Еланецкий район', 'Elanetsky district');
INSERT INTO districts VALUES (281, 16, 'Жовтневий район', 'Октябрьский район', 'October district');
INSERT INTO districts VALUES (282, 16, 'Казанківський район', 'Казанковский район', 'Kazankovsky district');
INSERT INTO districts VALUES (283, 16, 'Кривоозерский район', 'Кривоозерский район', 'Krivoozersky district');
INSERT INTO districts VALUES (284, 16, 'Миколаївський район', 'Николаевский район', 'Nikolaev region');
INSERT INTO districts VALUES (285, 16, 'Новобузький район', 'Новобугский район', 'Novobugsky district');
INSERT INTO districts VALUES (286, 16, 'Новоодеський район', 'Новоодесский район', 'Novoodeska district');
INSERT INTO districts VALUES (287, 16, 'Очаківський район', 'Очаковский район', 'Ochakovo district');
INSERT INTO districts VALUES (288, 16, 'Первомайський район', 'Первомайский район', 'May Day district');
INSERT INTO districts VALUES (289, 16, 'Снігурівський район', 'Снигиревский район', 'Snigirevskaya district');
INSERT INTO districts VALUES (290, 17, 'Ананьївський район', 'Ананьевский район', 'Anan''evskij district');
INSERT INTO districts VALUES (291, 17, 'Арцизький район', 'Арцизский район', 'Artsizsky district');
INSERT INTO districts VALUES (292, 17, 'Балтський район', 'Балтский район', 'Balt district');
INSERT INTO districts VALUES (293, 17, 'Березівський район', 'Березовский район', 'Berezovsky district');
INSERT INTO districts VALUES (294, 17, 'Білгород-Дністровський район', 'Белгород-Днестровский', 'Belgorod-Dnestrovskiy district');
INSERT INTO districts VALUES (295, 17, 'Біляївський район', 'Беляевский район', 'Belyaev district');
INSERT INTO districts VALUES (296, 17, 'Болградський район', 'Болградский район', 'Bolgradsky district');
INSERT INTO districts VALUES (297, 17, 'Великомихайлівський район', 'Великомихайловский район', 'Velikomihaylovsky district');
INSERT INTO districts VALUES (298, 17, 'Іванівський район', 'Ивановский район', 'Ivanovo region');
INSERT INTO districts VALUES (231, 13, 'Солом’янський район', 'Соломенский район', 'Solomenskyi district');
INSERT INTO districts VALUES (299, 17, 'Ізмаїльський район', 'Измаильский район', 'Izmail district');
INSERT INTO districts VALUES (300, 17, 'Кілійський район', 'Килийский район', 'Kiliya district');
INSERT INTO districts VALUES (301, 17, 'Кодимський район', 'Кодымский район', 'Kodymsky district');
INSERT INTO districts VALUES (302, 17, 'Комінтернівський район', 'Коминтерновский район', 'Kominternovsky district');
INSERT INTO districts VALUES (303, 17, 'Котовський район', 'Котовский район', 'Kotovsky District');
INSERT INTO districts VALUES (304, 17, 'Красноокнянський район', 'Красноокнянский район', 'Krasnooknyansky district');
INSERT INTO districts VALUES (305, 17, 'Любашівський район', 'Любашевский район', 'Lyubashevsky district');
INSERT INTO districts VALUES (306, 17, 'Миколаївський район', 'Николаевский район', 'Nikolaev region');
INSERT INTO districts VALUES (307, 17, 'Овідіопольський район', 'Овидиопольский район', 'Ovidiopol district');
INSERT INTO districts VALUES (308, 17, 'Ренійський район', 'Ренийский район', 'Reni district');
INSERT INTO districts VALUES (309, 17, 'Роздільнянський район', 'Раздельнянский район', 'Razdelnyansky district');
INSERT INTO districts VALUES (310, 17, 'Савранський район', 'Савранский район', 'Savransky district');
INSERT INTO districts VALUES (311, 17, 'Саратський район', 'Саратский район', 'Saratsky district');
INSERT INTO districts VALUES (312, 17, 'Тарутинський район', 'Тарутинский район', 'Tarutino district');
INSERT INTO districts VALUES (313, 17, 'Татарбунарський район', 'Татарбунарский район', 'Tatarbunar district');
INSERT INTO districts VALUES (314, 17, 'Фрунзівський район', 'Фрунзенский район', 'Frunze district');
INSERT INTO districts VALUES (315, 17, 'Ширяївський район', 'Ширяевский район', 'Shiryaevo district');
INSERT INTO districts VALUES (316, 18, 'Великобагачанський район', 'Великобагачанский район', 'Velykobagachanskiy district');
INSERT INTO districts VALUES (317, 18, 'Гадяцький район', 'Гадячский район', 'Gadyachsky district');
INSERT INTO districts VALUES (318, 18, 'Глобинський район', 'Глобинский район', 'Globinsky district');
INSERT INTO districts VALUES (319, 18, 'Гребінківський район', 'Гребенковский район', 'Grebenkovsky district');
INSERT INTO districts VALUES (320, 18, 'Диканський район', 'Диканский район', 'Dikansky district');
INSERT INTO districts VALUES (321, 18, 'Зіньківський район', 'Зеньковский район', 'Zenkovsky district');
INSERT INTO districts VALUES (322, 18, 'Карлівський район', 'Карловский район', 'Carlo District');
INSERT INTO districts VALUES (323, 18, 'Кобеляцький район', 'Кобелякский район', 'Kobelyaksky district');
INSERT INTO districts VALUES (324, 18, 'Козельщинський район', 'Козельщинский район', 'Kozelschinsky district');
INSERT INTO districts VALUES (325, 18, 'Котелевський район', 'Котелевский район', 'Kotelevsky district');
INSERT INTO districts VALUES (326, 18, 'Кременчуцький район', 'Кременчугский район', 'Kremenchug district');
INSERT INTO districts VALUES (327, 18, 'Лохвицький район', 'Лохвицкий район', 'Lokhvitskiy district');
INSERT INTO districts VALUES (328, 18, 'Лубенський район', 'Лубенский район', 'Lubensky District');
INSERT INTO districts VALUES (329, 18, 'Машівський район', 'Машевский район', 'Mashevsky district');
INSERT INTO districts VALUES (330, 18, 'Миргородський район', 'Миргородский район', 'Mirgorod district');
INSERT INTO districts VALUES (331, 18, 'Новосанжарський район', 'Новосанжарский район', 'Novosanzharsky district');
INSERT INTO districts VALUES (332, 18, 'Оржицький район', 'Оржицкий район', 'Orzhitsky district');
INSERT INTO districts VALUES (333, 18, 'Пирятинський район', 'Пирятинский район', 'Pyriatynskiy district');
INSERT INTO districts VALUES (334, 18, 'Полтавський район', 'Полтавский район', 'Poltava region');
INSERT INTO districts VALUES (335, 18, 'Решетилівський район', 'Решетиловский район', 'Reshetylivskiy district');
INSERT INTO districts VALUES (336, 18, 'Семенівський район', 'Семеновский район', 'Semenov district');
INSERT INTO districts VALUES (337, 18, 'Хорольський район', 'Хорольский район', 'Khorolsky district');
INSERT INTO districts VALUES (338, 18, 'Чорнухинський район', 'Чернухинский район', 'Chernuhinsky district');
INSERT INTO districts VALUES (339, 18, 'Чутівський район', 'Чутовский район', 'Chutovsky district');
INSERT INTO districts VALUES (340, 18, 'Шишацький район', 'Шишацкий район', 'Shyshatskiy district');
INSERT INTO districts VALUES (341, 19, 'Березнівський район', 'Березновский район', 'Bereznovsky district');
INSERT INTO districts VALUES (342, 19, 'Володимирецький район', 'Владимирецкий район', 'Vladimiretsky district');
INSERT INTO districts VALUES (343, 19, 'Гощанський район', 'Гощанский район', 'Goshchansky district');
INSERT INTO districts VALUES (344, 19, 'Демидівський район', 'Демидовский район', 'Demidov district');
INSERT INTO districts VALUES (345, 19, 'Дубнівський район', 'Дубновский район', 'Dubnov district');
INSERT INTO districts VALUES (346, 19, 'Дубровицький район', 'Дубровицкий район', 'Dubrovitsky district');
INSERT INTO districts VALUES (347, 19, 'Зарічненський район', 'Заречненский район', 'Zarechnensky district');
INSERT INTO districts VALUES (348, 19, 'Здолбунівський район', 'Здолбуновский район', 'Zdolbuniv district');
INSERT INTO districts VALUES (349, 19, 'Корецький район', 'Корецкий район', 'Koretsky district');
INSERT INTO districts VALUES (350, 19, 'Костопільський район', 'Костопольский район', 'Kostopil district');
INSERT INTO districts VALUES (351, 19, 'Млинівський район', 'Млиновский район', 'Mlinovsky district');
INSERT INTO districts VALUES (352, 19, 'Острозький район', 'Острожский район', 'Ostrog region');
INSERT INTO districts VALUES (353, 19, 'Радивилівський район', 'Радивиловский район', 'Radivilovsky district');
INSERT INTO districts VALUES (354, 19, 'Рівненський район', 'Ровенский район', 'Rivne region');
INSERT INTO districts VALUES (355, 19, 'Рокитнівський район', 'Рокитновский район', 'Rokytnivskyi district');
INSERT INTO districts VALUES (356, 19, 'Сарненський район', 'Сарненский район', 'Sarny district');
INSERT INTO districts VALUES (357, 21, 'Білопільський район', 'Белопольский район', 'Belopolskiy district');
INSERT INTO districts VALUES (358, 21, 'Буринський район', 'Бурынский район', 'Burynsky district');
INSERT INTO districts VALUES (359, 21, 'Великописарівський район', 'Великописаревский район', 'Velikopisarevsky district');
INSERT INTO districts VALUES (360, 21, 'Глухівський район', 'Глуховский район', 'Glukhovsky district');
INSERT INTO districts VALUES (361, 21, 'Конотопський район', 'Конотопский район', 'Konotop district');
INSERT INTO districts VALUES (362, 21, 'Краснопільський район', 'Краснопольский район', 'Krasnopolsky district');
INSERT INTO districts VALUES (363, 21, 'Кролевецький район', 'Кролевецкий район', 'Krolevetsky district');
INSERT INTO districts VALUES (364, 21, 'Лебединський район', 'Лебединский район', 'Lebedinsky district');
INSERT INTO districts VALUES (365, 21, 'Липоводолинський район', 'Липоводолинский район', 'Lipovodolinsky district');
INSERT INTO districts VALUES (366, 21, 'Недригайлівський район', 'Недригайловский район', 'Nedrigaylovsky district');
INSERT INTO districts VALUES (367, 21, 'Охтирський район', 'Ахтырский район', 'Akhtyrka district');
INSERT INTO districts VALUES (368, 21, 'Путивльський район', 'Путивльский район', 'Putivl district');
INSERT INTO districts VALUES (369, 21, 'Роменський район', 'Роменский район', 'Romensky district');
INSERT INTO districts VALUES (370, 21, 'Середино-Будський район', 'Середино-Будский район', 'Mid-Budsky district');
INSERT INTO districts VALUES (371, 21, 'Сумський район', 'Сумской район', 'Sumy region');
INSERT INTO districts VALUES (372, 21, 'Тростянецький район', 'Тростянецкий район', 'Trostyanetskiy district');
INSERT INTO districts VALUES (373, 21, 'Шосткинський район', 'Шосткинский район', 'Shostkinsky district');
INSERT INTO districts VALUES (374, 21, 'Ямпільський район', 'Ямпольский район', 'Yampolsky district');
INSERT INTO districts VALUES (375, 22, 'Бережанський район', 'Бережанский район', 'Berezhany district');
INSERT INTO districts VALUES (376, 22, 'Борщівський район', 'Борщевский район', 'Borshchevsky district');
INSERT INTO districts VALUES (377, 22, 'Бучацький район', 'Бучачский район', 'Buchachsky district');
INSERT INTO districts VALUES (378, 22, 'Гусятинський район', 'Гусятинский район', 'Husyatyn district');
INSERT INTO districts VALUES (379, 22, 'Заліщицький район', 'Залещицкий район', 'Zaleschitsky district');
INSERT INTO districts VALUES (380, 22, 'Збаразький район', 'Збаражский район', 'Zbarazhsky district');
INSERT INTO districts VALUES (381, 22, 'Зборівський район', 'Зборовский район', 'Zborowski district');
INSERT INTO districts VALUES (382, 22, 'Козівський район', 'Козовский район', 'Kozovsky district');
INSERT INTO districts VALUES (383, 22, 'Кременецький район', 'Кременецкий район', 'Kremenets District');
INSERT INTO districts VALUES (384, 22, 'Лановецький район', 'Лановецкий район', 'Lanovetsky district');
INSERT INTO districts VALUES (385, 22, 'Монастириський район', 'Монастырисский район', 'Monastyrissky district');
INSERT INTO districts VALUES (386, 22, 'Підволочиський район', 'Подволочиский район', 'Pidvolochisk district');
INSERT INTO districts VALUES (387, 22, 'Підгаєцький район', 'Подгаецкий район', 'Podgaetsky district');
INSERT INTO districts VALUES (388, 22, 'Теребовлянський район', 'Теребовлянский район', 'Terebovlyansky district');
INSERT INTO districts VALUES (389, 22, 'Тернопільський район', 'Тернопольский район', 'Ternopil region');
INSERT INTO districts VALUES (390, 22, 'Чортківський район', 'Чертковский район', 'Chertkovsky district');
INSERT INTO districts VALUES (391, 22, 'Шумський район', 'Шумский район', 'Shumsky district');
INSERT INTO districts VALUES (392, 23, 'Балаклійський район', 'Балаклейский район', 'Balakleya district');
INSERT INTO districts VALUES (393, 23, 'Барвінківський район', 'Барвенковский район', 'Barvenkovsky district');
INSERT INTO districts VALUES (394, 23, 'Близнюківський район', 'Близнюковский район', 'Bliznyukovsky district');
INSERT INTO districts VALUES (395, 23, 'Богодухівський район', 'Богодуховский район', 'Bogodukhovskaya district');
INSERT INTO districts VALUES (396, 23, 'Борівський район', 'Боровский район', 'Borovsky District');
INSERT INTO districts VALUES (397, 23, 'Валківський район', 'Валковский район', 'Valkovsky district');
INSERT INTO districts VALUES (398, 23, 'Великобурлуцький район', 'Великобурлукский район', 'Velikoburluksky district');
INSERT INTO districts VALUES (399, 23, 'Вовчанський район', 'Волчанский район', 'Volchanskiy district');
INSERT INTO districts VALUES (400, 23, 'Дворічанський район', 'Двуречанский район', 'Dvurechansky district');
INSERT INTO districts VALUES (401, 23, 'Дергачівський район', 'Дергачевский район', 'Dergachi district');
INSERT INTO districts VALUES (402, 23, 'Зачепилівський район', 'Зачепиловский район', 'Zachepilovsky district');
INSERT INTO districts VALUES (403, 23, 'Зміївський район', 'Змиевской район', 'Zmievskaya district');
INSERT INTO districts VALUES (404, 23, 'Золочівський район', 'Золочевский район', 'Zolochiv district');
INSERT INTO districts VALUES (405, 23, 'Ізюмський район', 'Изюмский район', 'Izyumsky district');
INSERT INTO districts VALUES (406, 23, 'Кегичівський район', 'Кегичевский район', 'Kegichevsky district');
INSERT INTO districts VALUES (407, 23, 'Коломацький район', 'Коломакский район', 'Kolomaksky district');
INSERT INTO districts VALUES (408, 23, 'Красноградський район', 'Красноградский район', 'Krasnogradsky district');
INSERT INTO districts VALUES (409, 23, 'Краснокутський район', 'Краснокутский район', 'Krasnokutskiy district');
INSERT INTO districts VALUES (410, 23, 'Куп’янський район', 'Купянский район', 'Kupyansk district');
INSERT INTO districts VALUES (411, 23, 'Лозівський район', 'Лозовской район', 'Lozovsky district');
INSERT INTO districts VALUES (412, 23, 'Нововодолазький район', 'Нововодолажский район', 'Novovodolazhsky district');
INSERT INTO districts VALUES (413, 23, 'Первомайський район', 'Первомайский район', 'May Day district');
INSERT INTO districts VALUES (414, 23, 'Печенізький район', 'Печенежский район', 'Pecheneg district');
INSERT INTO districts VALUES (415, 23, 'Сахновщинський район', 'Сахновщинский район', 'Sahnovschinsky district');
INSERT INTO districts VALUES (416, 23, 'Харківський район', 'Харьковский район', 'Kharkiv region');
INSERT INTO districts VALUES (417, 23, 'Чугуївський район', 'Чугуевский район', 'Chuguev district');
INSERT INTO districts VALUES (418, 23, 'Шевченківський район', 'Шевченковский район', 'Shevchenko district');
INSERT INTO districts VALUES (419, 24, 'Бериславський район', 'Бериславский район', 'Berislavsky district');
INSERT INTO districts VALUES (420, 24, 'Білозерський район', 'Белозерский район', 'Belozersky District');
INSERT INTO districts VALUES (421, 24, 'Великолепетиський район', 'Великолепетихский район', 'Velikolepetihsky district');
INSERT INTO districts VALUES (422, 24, 'Великоолександрівський район', 'Великоалександровский район', 'Velikoaleksandrovsky district');
INSERT INTO districts VALUES (423, 24, 'Верхньорогачицький район', 'Верхнерогачикский район', 'Verhnerogachiksky district');
INSERT INTO districts VALUES (424, 24, 'Високопільський район', 'Высокопольский район', 'Vysokopolskoye district');
INSERT INTO districts VALUES (425, 24, 'Генічеський район', 'Генический район', 'Genichesk district');
INSERT INTO districts VALUES (426, 24, 'Голопристанський район', 'Голопристанский район', 'Golopristansky district');
INSERT INTO districts VALUES (427, 24, 'Горностаївський район', 'Горностаевский район', 'Gornostaevsky district');
INSERT INTO districts VALUES (428, 24, 'Іванівський район', 'Ивановский район', 'Ivanovo region');
INSERT INTO districts VALUES (429, 24, 'Каланчацький район', 'Каланчакский район', 'Kalanchaksky district');
INSERT INTO districts VALUES (430, 24, 'Каховський район', 'Каховский район', 'Kakhovskyi district');
INSERT INTO districts VALUES (431, 24, 'Нижньосірогозький район', 'Нижнесерогозский район', 'Nizhneserogozsky district');
INSERT INTO districts VALUES (432, 24, 'Нововоронцовський район', 'Нововоронцовский район', 'Novovorontsovsky district');
INSERT INTO districts VALUES (433, 24, 'Новотроїцький район', 'Новотроицкий район', 'Novotroitskiy district');
INSERT INTO districts VALUES (434, 24, 'Скадовський район', 'Скадовский район', 'Skadovskii district');
INSERT INTO districts VALUES (435, 24, 'Цюрупинський район', 'Цюрупинский район', 'Tsyurupynsk district');
INSERT INTO districts VALUES (436, 24, 'Чаплинський район', 'Чаплинский район', 'Chaplinsky district');
INSERT INTO districts VALUES (437, 25, 'Білогірський район', 'Белогорский район', 'Belogorskij district');
INSERT INTO districts VALUES (438, 25, 'Віньковецький район', 'Виньковецкий район', 'Vinkovetsky district');
INSERT INTO districts VALUES (439, 25, 'Волочиський район', 'Волочисский район', 'Volochissky district');
INSERT INTO districts VALUES (440, 25, 'Городоцький район', 'Городокский район', 'Gorodok district');
INSERT INTO districts VALUES (441, 25, 'Деражнянський район', 'Деражнянский район', 'Derazhnyansky district');
INSERT INTO districts VALUES (442, 25, 'Дунаєвецький район', 'Дунаевецкий район', 'Dunaevetsky district');
INSERT INTO districts VALUES (443, 25, 'Ізяславський район', 'Изяславский район', 'Izyaslavsky district');
INSERT INTO districts VALUES (444, 25, 'Кам’янець-Подільський район', 'Каменец-Подольский', 'Kamenetz-Podolsk');
INSERT INTO districts VALUES (445, 25, 'Красилівський район', 'Красиловский район', 'Krasilovsky district');
INSERT INTO districts VALUES (446, 25, 'Летичівський район', 'Летичевский район', 'Letichevsky district');
INSERT INTO districts VALUES (447, 25, 'Новоушицький район', 'Новоушицкий район', 'Novoushitsky district');
INSERT INTO districts VALUES (448, 25, 'Полонський район', 'Полонский район', 'Polonsky district');
INSERT INTO districts VALUES (449, 25, 'Славутський район', 'Славутский район', 'Slavutskii district');
INSERT INTO districts VALUES (450, 25, 'Старокостянтинівський район', 'Староконстантиновский район', 'Starokonstantinovsky district');
INSERT INTO districts VALUES (451, 25, 'Старосинявський район', 'Старосинявский район', 'Starosinyavsky district');
INSERT INTO districts VALUES (452, 25, 'Теофіпольський район', 'Теофипольский район', 'Teofipolske district');
INSERT INTO districts VALUES (453, 25, 'Хмельницький район', 'Хмельницкий', 'Khmelnitsky');
INSERT INTO districts VALUES (454, 25, 'Чемеровецький район', 'Чемеровецкий район', 'Chemerovetskyi district');
INSERT INTO districts VALUES (455, 25, 'Шепетівський район', 'Шепетовский район', 'Shepetivsky district');
INSERT INTO districts VALUES (456, 25, 'Ярмолинецький район', 'Ярмолинецкий район', 'Yarmolinetsky district');
INSERT INTO districts VALUES (457, 26, 'Городищенський район', 'Городищенский район', 'Gorodishche district');
INSERT INTO districts VALUES (458, 26, 'Драбівський район', 'Драбовский район', 'Drabowsky district');
INSERT INTO districts VALUES (459, 26, 'Жашківський район', 'Жашковский район', 'Zhashkiv district');
INSERT INTO districts VALUES (460, 26, 'Звенигородський район', 'Звенигородский район', 'Zvenigorod district');
INSERT INTO districts VALUES (461, 26, 'Золотоніський район', 'Золотоношский район', 'Zolotoniska district');
INSERT INTO districts VALUES (462, 26, 'Кам’янський район', 'Каменский район', 'Kamensky District');
INSERT INTO districts VALUES (463, 26, 'Канівський район', 'Каневский район', 'Kanevsky District');
INSERT INTO districts VALUES (464, 26, 'Катеринопільський район', 'Катеринопольский район', 'Katerinopolsky district');
INSERT INTO districts VALUES (465, 26, 'Корсунь-Шевченківський район', 'Корсунь-Шевченковский район', 'Korsun-Shevchenko district');
INSERT INTO districts VALUES (466, 26, 'Лисянський район', 'Лисянский район', 'Lisyanskii district');
INSERT INTO districts VALUES (467, 26, 'Маньківський район', 'Маньковский район', 'Mankovsky district');
INSERT INTO districts VALUES (468, 26, 'Монастирищенський район', 'Монастырищенский район', 'Monastyrischensky district');
INSERT INTO districts VALUES (469, 26, 'Смілянський район', 'Смелянский район', 'Smelyansky district');
INSERT INTO districts VALUES (470, 26, 'Тальнівський район', 'Тальновский район', 'Talnovsky district');
INSERT INTO districts VALUES (471, 26, 'Уманський район', 'Уманский район', 'Uman district');
INSERT INTO districts VALUES (472, 26, 'Христинівський район', 'Христиновский район', 'Khrystynivka district');
INSERT INTO districts VALUES (473, 26, 'Черкаський район', 'Черкасский район', 'Cherkasy region');
INSERT INTO districts VALUES (474, 26, 'Чигиринський район', 'Чигиринский район', 'Chigirinsky district');
INSERT INTO districts VALUES (475, 26, 'Чорнобаївський район', 'Чернобаевский район', 'Chernobaevsky district');
INSERT INTO districts VALUES (476, 26, 'Шполянський район', 'Шполянский район', 'Shpolyansky district');
INSERT INTO districts VALUES (477, 27, 'Вижницький район', 'Вижницкий район', 'Vizhnitsky district');
INSERT INTO districts VALUES (478, 27, 'Герцаївський район', 'Герцаевский район', 'Gertsaevsky district');
INSERT INTO districts VALUES (479, 27, 'Глибоцький район', 'Глыбокский район', 'Glyboksky district');
INSERT INTO districts VALUES (480, 27, 'Заставнівський район', 'Заставновский район', 'Zastavnovsky district');
INSERT INTO districts VALUES (481, 27, 'Кельменецький район', 'Кельменецкий район', 'Kelmenetsky district');
INSERT INTO districts VALUES (482, 27, 'Кіцманський район', 'Кицманский район', 'Kitsmansky district');
INSERT INTO districts VALUES (483, 27, 'Новоселицький район', 'Новоселицкий район', 'Novoselytsia district');
INSERT INTO districts VALUES (484, 27, 'Путильський район', 'Путильский район', 'Putilsky district');
INSERT INTO districts VALUES (485, 27, 'Сокирянський район', 'Сокирянский район', 'Sokiryanskaya district');
INSERT INTO districts VALUES (486, 27, 'Сторожинецький район', 'Сторожинецкий район', 'Storozhynets district');
INSERT INTO districts VALUES (487, 27, 'Хотинський район', 'Хотинский район', 'Khotin district');
INSERT INTO districts VALUES (488, 28, 'Бахмацький район', 'Бахмачский район', 'Bahmachsky district');
INSERT INTO districts VALUES (489, 28, 'Бобровицький район', 'Бобровицкий район', 'Bobrovitsky district');
INSERT INTO districts VALUES (490, 28, 'Борзнянський район', 'Борзнянский район', 'Borznyansky district');
INSERT INTO districts VALUES (491, 28, 'Варвинський район', 'Варвинский район', 'Varvinsky district');
INSERT INTO districts VALUES (492, 28, 'Городнянський район', 'Городнянский район', 'Gorodnyansky district');
INSERT INTO districts VALUES (493, 28, 'Ічнянський район', 'Ичнянский район', 'Ichnya district');
INSERT INTO districts VALUES (494, 28, 'Козелецький район', 'Козелецкий район', 'Kozelets district');
INSERT INTO districts VALUES (495, 28, 'Коропський район', 'Коропский район', 'Koropsky district');
INSERT INTO districts VALUES (496, 28, 'Корюківський район', 'Корюковский район', 'Koryukovka district');
INSERT INTO districts VALUES (497, 28, 'Куликівський район', 'Куликовский район', 'Kulikovskii district');
INSERT INTO districts VALUES (498, 28, 'Менський район', 'Менский район', 'Mensky district');
INSERT INTO districts VALUES (499, 28, 'Ніжинський район', 'Нежинский район', 'Nezhinskii district');
INSERT INTO districts VALUES (500, 28, 'Новгород-Сіверський район', 'Новгород-Северский район', 'Novgorod-Seversky District');
INSERT INTO districts VALUES (501, 28, 'Носівський район', 'Носовский район', 'Nosovsky district');
INSERT INTO districts VALUES (502, 28, 'Прилуцький район', 'Прилукский район', 'Pryluky district');
INSERT INTO districts VALUES (503, 28, 'Ріпкинський район', 'Репкинский район', 'Repkinsky district');
INSERT INTO districts VALUES (504, 28, 'Семенівський район', 'Семеновский район', 'Semenov district');
INSERT INTO districts VALUES (505, 28, 'Сосницький район', 'Сосницкий район', 'Sosniţchi district');
INSERT INTO districts VALUES (506, 28, 'Срібнянський район', 'Сребнянский район', 'Srebnyansky district');
INSERT INTO districts VALUES (507, 28, 'Талалаївський район', 'Талалаевский район', 'Talalayevsky district');
INSERT INTO districts VALUES (508, 28, 'Чернігівський район', 'Черниговский район', 'Chernihiv region');
INSERT INTO districts VALUES (509, 28, 'Щорський район', 'Щорский район', 'Schorsky district');
INSERT INTO districts VALUES (701, 26, 'Черкаси', 'Черкассы', 'Cherkasy');
INSERT INTO districts VALUES (702, 26, 'Ватутіне', 'Ватутино', 'Vatutine');
INSERT INTO districts VALUES (703, 27, 'Чернівці', 'Черновцы', 'Chernivtsi');
INSERT INTO districts VALUES (704, 28, 'Чернігів', 'Чернигов', 'Chernihiv');
INSERT INTO districts VALUES (705, 24, 'Херсон', 'Херсон', 'Kherson');
INSERT INTO districts VALUES (706, 24, 'Нова Каховка', 'Новая Каховка', 'New Kakhovka');
INSERT INTO districts VALUES (707, 24, 'Каховка', 'Каховка', 'Kakhovka');
INSERT INTO districts VALUES (708, 25, 'Хмельницький', 'Хмельницкий', 'Khmelnitsky');
INSERT INTO districts VALUES (709, 25, 'Нетішин', 'Нетешин', 'Neteshin');
INSERT INTO districts VALUES (710, 23, 'Харків', 'Харьков', 'Kharkov');
INSERT INTO districts VALUES (711, 23, 'Люботин', 'Люботин', 'Ljubotin');
INSERT INTO districts VALUES (712, 22, 'Тернопіль', 'Тернополь', 'Ternopil');
INSERT INTO districts VALUES (713, 21, 'Суми', 'Сумы', 'Sumy');
INSERT INTO districts VALUES (714, 19, 'Рівне', 'Ровно', 'Exactly');
INSERT INTO districts VALUES (715, 19, 'Кузнецовськ', 'Кузнецовск', 'Kuznetsovsk');
INSERT INTO districts VALUES (716, 18, 'Полтава', 'Полтава', 'Poltava');
INSERT INTO districts VALUES (717, 17, 'Теплодар', 'Теплодар', 'Teplodar');
INSERT INTO districts VALUES (718, 17, 'Одеса', 'Одесса', 'Odessa');
INSERT INTO districts VALUES (719, 16, 'Южноукраїнськ', 'Южноукраинск', 'Yuzhnoukrainsk');
INSERT INTO districts VALUES (720, 16, 'Миколаїв', 'Николаев', 'Nikolaev');
INSERT INTO districts VALUES (721, 14, 'Рубіжне', 'Рубежное', 'Rubizhne');
INSERT INTO districts VALUES (722, 14, 'Ровеньки', 'Ровеньки', 'Rovenky');
INSERT INTO districts VALUES (723, 14, 'Первомайськ', 'Первомайск', 'Pervomaisk');
INSERT INTO districts VALUES (724, 14, 'Луганськ', 'Луганск', 'Lugansk');
INSERT INTO districts VALUES (725, 14, 'Лисичанськ', 'Лисичанск', 'Lisichansk');
INSERT INTO districts VALUES (726, 14, 'Красний Луч', 'Красный Луч', 'Red Ray');
INSERT INTO districts VALUES (727, 14, 'Кіровськ', 'Кировск', 'Kirovsk');
INSERT INTO districts VALUES (728, 14, 'Брянка', 'Брянка', 'Bryanka');
INSERT INTO districts VALUES (729, 14, 'Алчевськ', 'Алчевск', 'Alchevsk');
INSERT INTO districts VALUES (730, 15, 'Червоноград', 'Червоноград', 'Chervonograd');
INSERT INTO districts VALUES (731, 15, 'Трускавець', 'Трускавец', 'Truskavets');
INSERT INTO districts VALUES (732, 15, 'Львів', 'Львов', 'Lions');
INSERT INTO districts VALUES (733, 15, 'Дрогобич', 'Дрогобыч', 'Drogobych');
INSERT INTO districts VALUES (734, 15, 'Борислав', 'Борислав', 'Borislav');
INSERT INTO districts VALUES (735, 11, 'Кіровоград', 'Кировоград', 'Kirovograd');
INSERT INTO districts VALUES (736, 11, 'Знам`янка', 'Знаменка', 'Znamenka');
INSERT INTO districts VALUES (737, 12, 'Ржищів', 'Ржищев', 'Rzhyshchiv');
INSERT INTO districts VALUES (738, 12, 'Прип`ять', 'Припять', 'Pripyat');
INSERT INTO districts VALUES (740, 12, 'Ірпінь', 'Ирпень', 'Irpen');
INSERT INTO districts VALUES (741, 12, 'Буча', 'Буча', 'Bucha');
INSERT INTO districts VALUES (742, 12, 'Березань', 'Березань', 'Berezan');
INSERT INTO districts VALUES (743, 10, 'Яремче', 'Яремче', 'Discount programs');
INSERT INTO districts VALUES (744, 10, 'Івано-Франківськ', 'Ивано-Франковск', 'Ivano-Frankivsk');
INSERT INTO districts VALUES (745, 8, 'Ужгород', 'Ужгород', 'Uzhgorod');
INSERT INTO districts VALUES (746, 8, 'Мукачеве', 'Мукачево', 'Mukachevo');
INSERT INTO districts VALUES (747, 9, 'Запоріжжя', 'Запорожье', 'Zaporozhye');
INSERT INTO districts VALUES (748, 9, 'Енергодар', 'Энергодар', 'Energodar');
INSERT INTO districts VALUES (749, 7, 'Житомир', 'Житомир', 'Zhitomir');
INSERT INTO districts VALUES (750, 5, 'Тернівка', 'Терновка', 'Ternovka');
INSERT INTO districts VALUES (751, 5, 'Першотравенськ', 'Першотравенск', 'Pershotravensk');
INSERT INTO districts VALUES (752, 5, 'Орджонікідзе', 'Орджоникидзе', 'Ordzhonikidze');
INSERT INTO districts VALUES (753, 5, 'Марганець', 'Марганец', 'Manganese');
INSERT INTO districts VALUES (754, 5, 'Жовті Води', 'Желтые Воды', 'Yellow Waters');
INSERT INTO districts VALUES (755, 5, 'Дніпропетровськ', 'Днепропетровск', 'Dnepropetrovsk');
INSERT INTO districts VALUES (756, 5, 'Дніпродзержинськ', 'Днепродзержинск', 'Dneprodzerzhinsk');
INSERT INTO districts VALUES (757, 5, 'Вільногірськ', 'Вольногорск', 'Volnogorsk');
INSERT INTO districts VALUES (758, 6, 'Ясинувата', 'Ясиноватая', 'Yasinovataya');
INSERT INTO districts VALUES (759, 6, 'Шахтарськ', 'Шахтерск', 'Shakhtyorsk');
INSERT INTO districts VALUES (760, 6, 'Харцизьк', 'Харцызск', 'Hartsyzsk');
INSERT INTO districts VALUES (761, 6, 'Торез', 'Торез', 'Thorez');
INSERT INTO districts VALUES (762, 6, 'Сніжне', 'Снежное', 'Snow');
INSERT INTO districts VALUES (763, 6, 'Селидове', 'Селидово', 'Selidovo');
INSERT INTO districts VALUES (764, 6, 'Новогродівка', 'Новогродовка', 'Novogrodovka');
INSERT INTO districts VALUES (765, 6, 'Маріуполь', 'Мариуполь', 'Mariupol');
INSERT INTO districts VALUES (766, 6, 'Макіївка', 'Макеевка', 'Makeevka');
INSERT INTO districts VALUES (767, 6, 'Красний Лиман', 'Красный Лиман', 'Krasny Liman');
INSERT INTO districts VALUES (768, 6, 'Краматорськ', 'Краматорск', 'Kramatorsk');
INSERT INTO districts VALUES (769, 6, 'Костянтинівка', 'Константиновка', 'Konstantinovka');
INSERT INTO districts VALUES (770, 6, 'Кіровське', 'Кировское', 'Kirov');
INSERT INTO districts VALUES (771, 6, 'Жданівка', 'Ждановка', 'Zhdanivka');
INSERT INTO districts VALUES (772, 6, 'Єнакієве', 'Енакиево', 'Yenakievo');
INSERT INTO districts VALUES (773, 6, 'Дружківка', 'Дружковка', 'Druzhkovka');
INSERT INTO districts VALUES (774, 6, 'Донецьк', 'Донецк', 'Donetsk');
INSERT INTO districts VALUES (775, 6, 'Докучаєвськ', 'Докучаевск', 'Dokuchayevsk');
INSERT INTO districts VALUES (776, 6, 'Добропілля', 'Доброполье', 'Dobropillia');
INSERT INTO districts VALUES (777, 6, 'Димитров', 'Димитров', 'Dimitrov');
INSERT INTO districts VALUES (778, 6, 'Дзержинськ', 'Дзержинск', 'Dzerzhinsk');
INSERT INTO districts VALUES (779, 6, 'Дебальцеве', 'Дебальцево', 'Debaltsevo');
INSERT INTO districts VALUES (780, 6, 'Горлівка', 'Горловка', 'Gorlovka');
INSERT INTO districts VALUES (781, 6, 'Вугледар', 'Угледар', 'Ugledar');
INSERT INTO districts VALUES (782, 6, 'Артемівськ', 'Артемовск', 'Donetsk');
INSERT INTO districts VALUES (783, 6, 'Авдіївка', 'Авдеевка', 'Avdiyivka');
INSERT INTO districts VALUES (784, 4, 'Ладижин', 'Ладыжин', 'Ladyzhin');
INSERT INTO districts VALUES (785, 4, 'Вінниця', 'Винница', 'Vinnitsa');
INSERT INTO districts VALUES (788, 1, 'Ялта', 'Ялта', 'Yalta');
INSERT INTO districts VALUES (789, 1, 'Феодосія', 'Феодосия', 'Feodosiya');
INSERT INTO districts VALUES (791, 1, 'Сімферополь', 'Симферополь', 'Simferopol');
INSERT INTO districts VALUES (792, 1, 'Керч', 'Керчь', 'Kerch');
INSERT INTO districts VALUES (793, 1, 'Євпаторія', 'Евпатория', 'Evpatoria');
INSERT INTO districts VALUES (794, 1, 'Армянськ', 'Армянск', 'Armyansk');
INSERT INTO districts VALUES (796, 1, 'Алушта', 'Алушта', 'Alushta');
INSERT INTO districts VALUES (848, 17, 'Южний', 'Южный', 'Southern');
INSERT INTO districts VALUES (229, 13, 'Подільский район', 'Подольский район', 'Podolsky district');
INSERT INTO districts VALUES (787, 3, 'Луцьк', 'Луцк', 'Lutsk');
INSERT INTO districts VALUES (1398, 10, 'Болехів', 'Болехов', 'Bolekhiv');
INSERT INTO districts VALUES (849, 5, 'Кривий Ріг', 'Кривой Рог', 'Krivoy Rog');
INSERT INTO districts VALUES (1179, 17, 'Іллічівськ', 'Ильичевск', 'Ilyichevsk');
INSERT INTO districts VALUES (786, 3, 'Нововолинськ', 'Нововолынск', 'Novovolynsk');
INSERT INTO districts VALUES (797, 1, 'Джанкой', 'Джанкой', 'Dzhankoy');
INSERT INTO districts VALUES (798, 1, 'Красноперекопськ', 'Красноперекопск', 'Krasnoperekopsk');
INSERT INTO districts VALUES (799, 1, 'Сакі', 'Саки', 'Saki');
INSERT INTO districts VALUES (800, 4, 'Жмеринка', 'Жмеринка ', 'Zhmerynka');
INSERT INTO districts VALUES (801, 4, 'Козятин', 'Козятин', 'Kozyatin');
INSERT INTO districts VALUES (802, 4, 'Могилів-Подільський', 'Могилёв-Подольский ', 'Mogilev-Podolsky');
INSERT INTO districts VALUES (803, 3, 'Володимир-Волинський', 'Владимир-Волынский', 'Vladimir-Volyn');
INSERT INTO districts VALUES (804, 3, 'Ковель', 'Ковель', 'Kovel');
INSERT INTO districts VALUES (806, 5, 'Нікополь', 'Никополь', 'Nikopol');
INSERT INTO districts VALUES (807, 5, 'Новомосковськ ', 'Новомосковск', 'Novomoskovsk');
INSERT INTO districts VALUES (808, 5, 'Павлоград', 'Павлоград', 'Pavlograd');
INSERT INTO districts VALUES (809, 5, 'Синельникове', 'Синельниково', 'Sinelnikovo');
INSERT INTO districts VALUES (810, 6, 'Красноармійськ', 'Красноармейск', 'Krasnoarmejsk');
INSERT INTO districts VALUES (811, 6, 'Слов''янськ', 'Словянск', 'Slovenian');
INSERT INTO districts VALUES (812, 7, 'Бердичів', 'Бердичев', 'Berdichev');
INSERT INTO districts VALUES (813, 7, 'Коростень', 'Коростень', 'Korosten');
INSERT INTO districts VALUES (814, 7, 'Малин', 'Малин', 'Malin');
INSERT INTO districts VALUES (815, 8, 'Берегове', 'Береговое', 'Riverside');
INSERT INTO districts VALUES (816, 8, 'Хуст', 'Хуст', 'Hust');
INSERT INTO districts VALUES (817, 8, 'Чоп', 'Чоп', 'Chop');
INSERT INTO districts VALUES (818, 9, 'Бердянськ', 'Бердянск', 'Berdyansk');
INSERT INTO districts VALUES (819, 9, 'Мелітополь', 'Мелитополь', 'Melitopol');
INSERT INTO districts VALUES (820, 9, 'Токмак', 'Токмак', 'Tokmak');
INSERT INTO districts VALUES (821, 10, 'Калуш', 'Калуш', 'Kalush');
INSERT INTO districts VALUES (822, 10, 'Коломия', 'Коломия', 'Colom');
INSERT INTO districts VALUES (823, 12, 'Біла Церква', 'Белая Церковь', 'White Church');
INSERT INTO districts VALUES (824, 12, 'Бориспіль', 'Борисполь', 'Borispol');
INSERT INTO districts VALUES (825, 12, 'Бровари', 'Бровари', 'Brewery');
INSERT INTO districts VALUES (826, 12, 'Васильків', 'Васильков', 'Cornflowers');
INSERT INTO districts VALUES (827, 12, 'Обухів', 'Обухов', 'Obukhov');
INSERT INTO districts VALUES (828, 12, 'Переяслав-Хмельницький', 'Переяслав-Хмельницкий', 'Pereyaslav-Khmelnytsky');
INSERT INTO districts VALUES (829, 12, 'Славутич', 'Славутич', 'Slavutich');
INSERT INTO districts VALUES (830, 12, 'Фастів', 'Фастов', 'Fastow');
INSERT INTO districts VALUES (831, 11, 'Олександрія', 'Александрия', 'Alexandria');
INSERT INTO districts VALUES (832, 11, 'Світловодськ', 'Светловодск', 'Svetlovodsk');
INSERT INTO districts VALUES (833, 14, 'Антрацит', 'Антрацит', 'Anthracite');
INSERT INTO districts VALUES (834, 14, 'Краснодон', 'Краснодон', 'Krasnodon');
INSERT INTO districts VALUES (835, 14, 'Свердловськ', 'Свердловск', 'Sverdlovsk');
INSERT INTO districts VALUES (836, 14, 'Сєвєродонецьк', 'Северодонецк', 'Severodonetsk');
INSERT INTO districts VALUES (837, 14, 'Стаханов', 'Стаханов', 'Stakhanov');
INSERT INTO districts VALUES (838, 15, 'Моршин', 'Моршин', 'Morshyn');
INSERT INTO districts VALUES (839, 15, 'Новий Розділ 
', 'Новый Раздел', 'New Section');
INSERT INTO districts VALUES (840, 15, 'Самбір', 'Самбир', 'Sambir');
INSERT INTO districts VALUES (841, 15, 'Стрий', 'Стрий', 'Striae');
INSERT INTO districts VALUES (842, 16, 'Вознесенськ', 'Вознесенск', 'Voznesensk');
INSERT INTO districts VALUES (843, 16, 'Очаків', 'Очаков', 'Ochakov');
INSERT INTO districts VALUES (844, 16, 'Первомайськ', 'Первомайск', 'Pervomaisk');
INSERT INTO districts VALUES (845, 17, 'Білгород-Дністровський', 'Белгород-Днестровский', 'Belgorod-Dniester');
INSERT INTO districts VALUES (846, 17, 'Ізмаїл', 'Ізмаил', 'Іzmail');
INSERT INTO districts VALUES (847, 17, 'Котовськ', 'Котовск', 'Kotovsk');
INSERT INTO districts VALUES (851, 18, 'Комсомольськ', 'Комсомольск', 'Komsomolsk');
INSERT INTO districts VALUES (852, 18, 'Кременчук', 'Кременчук', 'Kremenchuk');
INSERT INTO districts VALUES (853, 18, 'Лубни', 'Лубны', 'Lubny');
INSERT INTO districts VALUES (854, 18, 'Миргород', 'Миргород', 'Mirgorod');
INSERT INTO districts VALUES (855, 19, 'Дубно', 'Дубно', 'Dubno');
INSERT INTO districts VALUES (856, 19, 'Острог', 'Острог', 'Burg');
INSERT INTO districts VALUES (857, 21, 'Глухів', 'Глухов', 'Glukhov');
INSERT INTO districts VALUES (858, 21, 'Конотоп', 'Конотоп', 'Konotop');
INSERT INTO districts VALUES (859, 21, 'Лебедин', 'Лебедин', 'Lebedin');
INSERT INTO districts VALUES (860, 21, 'Охтирка', 'Охтырка', 'Ohtyrka');
INSERT INTO districts VALUES (861, 21, 'Ромни', 'Ромны', 'Romny');
INSERT INTO districts VALUES (862, 21, 'Шостка', 'Шостка', 'Shostka');
INSERT INTO districts VALUES (863, 23, 'Ізюм', 'Изюм', 'Raisins');
INSERT INTO districts VALUES (864, 23, 'Куп''янськ', 'Купянск', 'Kupyansk');
INSERT INTO districts VALUES (865, 23, 'Лозова', 'Лозовая', 'Lozova');
INSERT INTO districts VALUES (866, 23, 'Первомайський', 'Первомайский', 'May Day');
INSERT INTO districts VALUES (867, 23, 'Чугуїв', 'Чугуев', 'Chuguev');
INSERT INTO districts VALUES (868, 25, 'Кам''янець-Подільський', 'Каменец-Подольский', 'Kamenetz-Podolsk');
INSERT INTO districts VALUES (869, 25, 'Славута', 'Славута', 'Slavuta');
INSERT INTO districts VALUES (870, 25, 'Старокостянтинів', 'Староконстантинов', 'Starokostiantyniv');
INSERT INTO districts VALUES (871, 25, 'Шепетівка', 'Шепетовка', 'Shepetovka');
INSERT INTO districts VALUES (872, 26, 'Золотоноша', 'Золотоноша', 'Zolotonosha');
INSERT INTO districts VALUES (873, 26, 'Канів', 'Канев', 'Kanev');
INSERT INTO districts VALUES (874, 26, 'Сміла', 'Смела', 'Dare');
INSERT INTO districts VALUES (875, 26, 'Умань', 'Умань', 'Uman');
INSERT INTO districts VALUES (876, 27, 'Новодністровськ', 'Новоднестровск', 'Novodnistrovsk');
INSERT INTO districts VALUES (877, 28, 'Ніжин', 'Нежин', 'Nizhyn');
INSERT INTO districts VALUES (878, 28, 'Прилуки', 'Прилуки', 'Priluki');
INSERT INTO districts VALUES (111, 7, 'Романівський район', 'Романовский район', 'Romanovsky District');


--
-- Data for Name: drafts; Type: TABLE DATA; Schema: public; Owner: auzo
--



--
-- Data for Name: email_lists; Type: TABLE DATA; Schema: public; Owner: auzo
--



--
-- Data for Name: email_lists_users; Type: TABLE DATA; Schema: public; Owner: auzo
--

INSERT INTO email_lists_users VALUES (1, 116);
INSERT INTO email_lists_users VALUES (1, 101);
INSERT INTO email_lists_users VALUES (1, 102);
INSERT INTO email_lists_users VALUES (2, 116);
INSERT INTO email_lists_users VALUES (2, 101);
INSERT INTO email_lists_users VALUES (2, 102);


--
-- Data for Name: email_system; Type: TABLE DATA; Schema: public; Owner: auzo
--

INSERT INTO email_system VALUES (14, 'groups_approve_group', 'Ваша спільнота схвалена', 'Ваша спільнота схвалена', 'Ваше сообщество одобрено', '<p>%receiver%, запропонована Вами спільнота \\"%title%\\" була схвалена адміністрацією.</p>
<p>Відтепер Ви можете ініціювати обговорення у цій спільноті, анонсувати події, завантажувати фотографії та запрошувати до спільноти нових учасників.</p>
<p>Сторiнка спiльноти: <a href=\\"%link%\\">%link%</a></p>', '<p>%receiver%,  Предложенное вами сообщество \\"%title%\\" было одобрено администрацией.</p>
<p>Сообщество стало видно для остальных пользователей, сейчас уже <span id=\\"result_box\\" class=\\"long_text\\" lang=\\"ru\\"><span>можно инициировать обсуждение в этом сообществе, анонсировать события</span></span>, загружать фотографии и приглашать других участников.</p>
<p>Страница сообщества: <a href=\\"%link%\\">%link%</a></p>
<p>&nbsp;</p>', 'info@meritokrat.org', 'Мерiтократ.org', 'Меритократ.org', 1);
INSERT INTO email_system VALUES (44, 'comment_comment', 'comment_comment', 'Вiдповiдь на комментар', 'Ответ на комментарий', '<p>%receiver%, на Ваш комментар до %type% %postlink% вiдповiли:  %sender% пише: %text%.</p>
<p>Щоб переглянути та вiдповiсти, перейдiть за посиланням: <a href=\\"%link%\\">%link%</a></p>', '<p>%receiver%, на Ваш комментарий к %type% %postlink% ответили:  %sender% пишет: %text%.</p>
<p>Что-бы просмотреть и ответить, перейдите по ссылке: <a href=\\"%link%\\">%link%</a></p>', 'info@meritokrat.org', 'Мерiтократ.org', 'Меритократ.org', 1);
INSERT INTO email_system VALUES (42, 'contacts_binding', 'Прохання встановити контакт', 'Встановіть контакт', 'Установите контакт', '<p>Доброго дня, %coordinator%!</p>
<p>До нашої команди приєднався новий учасник %name%, який проживає у  регіоні, що ви координуєте. Встановіть, по  можливості, контакт з ним,  допоможіть адаптуватися в нашій мережі та  регіональній команді.</p>
<p>%message%</p>
<p>Ви можете знайти профіль цього учасника за посиланням %link%</p>
<p>З повагою Оргкомiтет МПУ</p>', '<p>Добрый день, %coordinator%!</p>
<p>К нашей команде присоединился участник %name%, который проживает в  регионе, который вы координируете. Установите, по возможности, контакт с  ним, помогите адаптироваться в нашей сети и региональной команде.</p>
<p>%message%</p>
<p>Вы можете найти профиль этого участника по ссылке %link%</p>
<p>С уважением Оргкомитет МПУ</p>', 'info@meritokrat.org', 'Мерiтократ.орг', 'Меритократ.орг', 0);
INSERT INTO email_system VALUES (41, 'invites_add_poll', 'Запрошення взяти участь у опитуванні ', 'Запрошення взяти участь у опитуванні %title%', 'Приглашение принять участие в опросе %title%', '<p>Доброго дня!</p>
<p>%name% запрошує Вас взяти участь у опитуваннi %title%.</p>
<p>%message%</p>
<p>Щоб проголосувати, натисніть <a href=\\"%profile%\\">тут</a>.</p>
<p>&nbsp;</p>', '<p>Добрый день!</p>
<p>%name% приглашает Вас принять участие в опросе %title%.</p>
<p>%message%</p>
<p>Чтобы проголосовать, нажмите <a href=\\"%profile%\\">тут</a>.</p>
<p>&nbsp;</p>', 'info@meritokrat.org', 'Meritokrat.org', 'Meritokrat.org', 1);
INSERT INTO email_system VALUES (22, 'messages_compose', 'Нове повiдомлення', 'Нове повiдомлення', 'Новое сообщение', '<p>%sender% пише %text%</p>
<p>Щоб вiдповiсти, перейдiть за посиланням: <a href=\\"%link%\\">%link%</a></p>', '<p>%sender% пишет %text%  .</p>
<p>Что-бы ответить, перейдите по ссылке: <a href=\\"%link%\\">%link%</a></p>', 'info@meritokrat.org', 'Мерiтократ.org', 'Меритократ.org', 1);
INSERT INTO email_system VALUES (4, 'user_internallist', 'Нове повiдомлення', 'Нове повiдомлення на Мерітократ.орг', 'Новое сообщение на Меритократ.орг', '<p>%sender% пише: \\"%body%\\".</p>
<p>Щоб переглянути текст повідомлення повністю та вiдповiсти, перейдiть за посиланням: <a href=\\"%link%\\">%link%</a></p>', '<p>%sender% пишет:  \\"%body%\\".</p>
<p>Чтобы просмотреть текст сообщения полностью и ответить на него, перейдите по ссылке: <a href=\\"%link%\\">%link%</a></p>', 'info@meritokrat.org', 'Мерiтократ.org', 'Меритократ.org', 1);
INSERT INTO email_system VALUES (12, 'friends_make', 'Запрошення у друзі', '%sender% запрошує Вас у друзi', '%sender% приглашает Вас в друзья', '<p>%receiver%, %sender% запрошує Вас у друзi.</p>
<p>Для схвалення або відхилення запрошення перейдiть за посиланням: <a href=\\"%link%\\">%link%</a></p>', '<p><span id=\\"result_box\\" class=\\"long_text\\" lang=\\"ru\\"><span>%receiver%, %sender% приглашает Вас в друзья. </span></span></p>
<p><span id=\\"result_box\\" class=\\"long_text\\" lang=\\"ru\\"><span>Для одобрения или отклонения приглашения, перейдите по ссылке: <a href=\\"%link%\\">%link%</a></span></span></p>', 'info@meritokrat.org', 'Мерiтократ.org', 'Меритократ.org', 1);
INSERT INTO email_system VALUES (13, 'groups_approve_applicant', 'Вас прийняли у спiльноту', 'Вас прийняли у спiльноту', 'Вас приняли в сообщество', '<p>%receiver%, Ваша заявка на вступ до спiльноти \\"%title%\\"  була схвалена.</p>
<p>Вiдвiдайте сторiнку цієї спiльноти: <a href=\\"%link%\\">%link%</a></p>', '<p>%receiver%, Ваша заявка на вступление в сообщество \\"%title%\\" была одобрена.</p>
<p>Посетите страничку этого сообщества: <a href=\\"%link%\\">%link%</a></p>', 'info@meritokrat.org', 'Мерiтократ.org', 'Меритократ.org', 1);
INSERT INTO email_system VALUES (1, 'default', 'Default', 'Default', 'Default', '<p>Якийсь текст</p>', '<p>Какой-то текст</p>', 'info@meritokrat.org', 'Мерiтократ.org', 'Меритократ.org', 1);
INSERT INTO email_system VALUES (2, 'footer', 'Footer', 'Footer', 'Footer', '<p>Не вiдповiдайте на цей лист, вiн був згенерований автоматично meritokrat.org</p>', '<p>Не отвечайте на это письмо, оно сгенерировано автоматически meritokrat.org</p>', 'Footer', 'Footer', '', 0);
INSERT INTO email_system VALUES (43, 'messages_wall', 'Новое сообщение: Запись на стене', '%sender% написав на Вашій стіні', '%sender% написал на Вашей стене', '<p>%sender% написав на Вашій стіні: %text% .</p>
<p>Щоб переглянути напис, перейдіть за посиланням: <a href=\\"%link%\\">%link%</a></p>', '<p>%sender% написал на Вашей стене: %text% .</p>
<p>Чтобы просмотреть надпись, перейдите по ссылке: <a href=\\"%link%\\">%link%</a></p>', 'info@meritokrat.org', 'Мерiтократ.org', 'Меритократ.org', 1);
INSERT INTO email_system VALUES (8, 'users_create_invite', 'Лист №1 при рекомендації', 'Ваш пароль та логін у соціальній мережі Мерiтократ.org', 'Приглашение на Меритократ.org ', '<p><!--[if gte mso 9]><xml> <w:WordDocument> <w:View>Normal</w:View> <w:Zoom>0</w:Zoom> <w:TrackMoves /> <w:TrackFormatting /> <w:PunctuationKerning /> <w:ValidateAgainstSchemas /> <w:SaveIfXMLInvalid>false</w:SaveIfXMLInvalid> <w:IgnoreMixedContent>false</w:IgnoreMixedContent> <w:AlwaysShowPlaceholderText>false</w:AlwaysShowPlaceholderText> <w:DoNotPromoteQF /> <w:LidThemeOther>RU</w:LidThemeOther> <w:LidThemeAsian>X-NONE</w:LidThemeAsian> <w:LidThemeComplexScript>X-NONE</w:LidThemeComplexScript> <w:Compatibility> <w:BreakWrappedTables /> <w:SnapToGridInCell /> <w:WrapTextWithPunct /> <w:UseAsianBreakRules /> <w:DontGrowAutofit /> <w:SplitPgBreakAndParaMark /> <w:DontVertAlignCellWithSp /> <w:DontBreakConstrainedForcedTables /> <w:DontVertAlignInTxbx /> <w:Word11KerningPairs /> <w:CachedColBalance /> </w:Compatibility> <m:mathPr> <m:mathFont m:val=\\"Cambria Math\\" /> <m:brkBin m:val=\\"before\\" /> <m:brkBinSub m:val=\\"&#45;-\\" /> <m:smallFrac m:val=\\"off\\" /> <m:dispDef /> <m:lMargin m:val=\\"0\\" /> <m:rMargin m:val=\\"0\\" /> <m:defJc m:val=\\"centerGroup\\" /> <m:wrapIndent m:val=\\"1440\\" /> <m:intLim m:val=\\"subSup\\" /> <m:naryLim m:val=\\"undOvr\\" /> </m:mathPr></w:WordDocument> </xml><![endif]--><!--[if gte mso 9]><xml> <w:LatentStyles DefLockedState=\\"false\\" DefUnhideWhenUsed=\\"true\\"   DefSemiHidden=\\"true\\" DefQFormat=\\"false\\" DefPriority=\\"99\\"   LatentStyleCount=\\"267\\"> <w:LsdException Locked=\\"false\\" Priority=\\"0\\" SemiHidden=\\"false\\"    UnhideWhenUsed=\\"false\\" QFormat=\\"true\\" Name=\\"Normal\\" /> <w:LsdException Locked=\\"false\\" Priority=\\"9\\" SemiHidden=\\"false\\"    UnhideWhenUsed=\\"false\\" QFormat=\\"true\\" Name=\\"heading 1\\" /> <w:LsdException Locked=\\"false\\" Priority=\\"9\\" QFormat=\\"true\\" Name=\\"heading 2\\" /> <w:LsdException Locked=\\"false\\" Priority=\\"9\\" QFormat=\\"true\\" Name=\\"heading 3\\" /> <w:LsdException Locked=\\"false\\" Priority=\\"9\\" QFormat=\\"true\\" Name=\\"heading 4\\" /> <w:LsdException Locked=\\"false\\" Priority=\\"9\\" QFormat=\\"true\\" Name=\\"heading 5\\" /> <w:LsdException Locked=\\"false\\" Priority=\\"9\\" QFormat=\\"true\\" Name=\\"heading 6\\" /> <w:LsdException Locked=\\"false\\" Priority=\\"9\\" QFormat=\\"true\\" Name=\\"heading 7\\" /> <w:LsdException Locked=\\"false\\" Priority=\\"9\\" QFormat=\\"true\\" Name=\\"heading 8\\" /> <w:LsdException Locked=\\"false\\" Priority=\\"9\\" QFormat=\\"true\\" Name=\\"heading 9\\" /> <w:LsdException Locked=\\"false\\" Priority=\\"39\\" Name=\\"toc 1\\" /> <w:LsdException Locked=\\"false\\" Priority=\\"39\\" Name=\\"toc 2\\" /> <w:LsdException Locked=\\"false\\" Priority=\\"39\\" Name=\\"toc 3\\" /> <w:LsdException Locked=\\"false\\" Priority=\\"39\\" Name=\\"toc 4\\" /> <w:LsdException Locked=\\"false\\" Priority=\\"39\\" Name=\\"toc 5\\" /> <w:LsdException Locked=\\"false\\" Priority=\\"39\\" Name=\\"toc 6\\" /> <w:LsdException Locked=\\"false\\" Priority=\\"39\\" Name=\\"toc 7\\" /> <w:LsdException Locked=\\"false\\" Priority=\\"39\\" Name=\\"toc 8\\" /> <w:LsdException Locked=\\"false\\" Priority=\\"39\\" Name=\\"toc 9\\" /> <w:LsdException Locked=\\"false\\" Priority=\\"35\\" QFormat=\\"true\\" Name=\\"caption\\" /> <w:LsdException Locked=\\"false\\" Priority=\\"10\\" SemiHidden=\\"false\\"    UnhideWhenUsed=\\"false\\" QFormat=\\"true\\" Name=\\"Title\\" /> <w:LsdException Locked=\\"false\\" Priority=\\"1\\" Name=\\"Default Paragraph Font\\" /> <w:LsdException Locked=\\"false\\" Priority=\\"11\\" SemiHidden=\\"false\\"    UnhideWhenUsed=\\"false\\" QFormat=\\"true\\" Name=\\"Subtitle\\" /> <w:LsdException Locked=\\"false\\" Priority=\\"22\\" SemiHidden=\\"false\\"    UnhideWhenUsed=\\"false\\" QFormat=\\"true\\" Name=\\"Strong\\" /> <w:LsdException Locked=\\"false\\" Priority=\\"20\\" SemiHidden=\\"false\\"    UnhideWhenUsed=\\"false\\" QFormat=\\"true\\" Name=\\"Emphasis\\" /> <w:LsdException Locked=\\"false\\" Priority=\\"59\\" SemiHidden=\\"false\\"    UnhideWhenUsed=\\"false\\" Name=\\"Table Grid\\" /> <w:LsdException Locked=\\"false\\" UnhideWhenUsed=\\"false\\" Name=\\"Placeholder Text\\" /> <w:LsdException Locked=\\"false\\" Priority=\\"1\\" SemiHidden=\\"false\\"    UnhideWhenUsed=\\"false\\" QFormat=\\"true\\" Name=\\"No Spacing\\" /> <w:LsdException Locked=\\"false\\" Priority=\\"60\\" SemiHidden=\\"false\\"    UnhideWhenUsed=\\"false\\" Name=\\"Light Shading\\" /> <w:LsdException Locked=\\"false\\" Priority=\\"61\\" SemiHidden=\\"false\\"    UnhideWhenUsed=\\"false\\" Name=\\"Light List\\" /> <w:LsdException Locked=\\"false\\" Priority=\\"62\\" SemiHidden=\\"false\\"    UnhideWhenUsed=\\"false\\" Name=\\"Light Grid\\" /> <w:LsdException Locked=\\"false\\" Priority=\\"63\\" SemiHidden=\\"false\\"    UnhideWhenUsed=\\"false\\" Name=\\"Medium Shading 1\\" /> <w:LsdException Locked=\\"false\\" Priority=\\"64\\" SemiHidden=\\"false\\"    UnhideWhenUsed=\\"false\\" Name=\\"Medium Shading 2\\" /> <w:LsdException Locked=\\"false\\" Priority=\\"65\\" SemiHidden=\\"false\\"    UnhideWhenUsed=\\"false\\" Name=\\"Medium List 1\\" /> <w:LsdException Locked=\\"false\\" Priority=\\"66\\" SemiHidden=\\"false\\"    UnhideWhenUsed=\\"false\\" Name=\\"Medium List 2\\" /> <w:LsdException Locked=\\"false\\" Priority=\\"67\\" SemiHidden=\\"false\\"    UnhideWhenUsed=\\"false\\" Name=\\"Medium Grid 1\\" /> <w:LsdException Locked=\\"false\\" Priority=\\"68\\" SemiHidden=\\"false\\"    UnhideWhenUsed=\\"false\\" Name=\\"Medium Grid 2\\" /> <w:LsdException Locked=\\"false\\" Priority=\\"69\\" SemiHidden=\\"false\\"    UnhideWhenUsed=\\"false\\" Name=\\"Medium Grid 3\\" /> <w:LsdException Locked=\\"false\\" Priority=\\"70\\" SemiHidden=\\"false\\"    UnhideWhenUsed=\\"false\\" Name=\\"Dark List\\" /> <w:LsdException Locked=\\"false\\" Priority=\\"71\\" SemiHidden=\\"false\\"    UnhideWhenUsed=\\"false\\" Name=\\"Colorful Shading\\" /> <w:LsdException Locked=\\"false\\" Priority=\\"72\\" SemiHidden=\\"false\\"    UnhideWhenUsed=\\"false\\" Name=\\"Colorful List\\" /> <w:LsdException Locked=\\"false\\" Priority=\\"73\\" SemiHidden=\\"false\\"    UnhideWhenUsed=\\"false\\" Name=\\"Colorful Grid\\" /> <w:LsdException Locked=\\"false\\" Priority=\\"60\\" SemiHidden=\\"false\\"    UnhideWhenUsed=\\"false\\" Name=\\"Light Shading Accent 1\\" /> <w:LsdException Locked=\\"false\\" Priority=\\"61\\" SemiHidden=\\"false\\"    UnhideWhenUsed=\\"false\\" Name=\\"Light List Accent 1\\" /> <w:LsdException Locked=\\"false\\" Priority=\\"62\\" SemiHidden=\\"false\\"    UnhideWhenUsed=\\"false\\" Name=\\"Light Grid Accent 1\\" /> <w:LsdException Locked=\\"false\\" Priority=\\"63\\" SemiHidden=\\"false\\"    UnhideWhenUsed=\\"false\\" Name=\\"Medium Shading 1 Accent 1\\" /> <w:LsdException Locked=\\"false\\" Priority=\\"64\\" SemiHidden=\\"false\\"    UnhideWhenUsed=\\"false\\" Name=\\"Medium Shading 2 Accent 1\\" /> <w:LsdException Locked=\\"false\\" Priority=\\"65\\" SemiHidden=\\"false\\"    UnhideWhenUsed=\\"false\\" Name=\\"Medium List 1 Accent 1\\" /> <w:LsdException Locked=\\"false\\" UnhideWhenUsed=\\"false\\" Name=\\"Revision\\" /> <w:LsdException Locked=\\"false\\" Priority=\\"34\\" SemiHidden=\\"false\\"    UnhideWhenUsed=\\"false\\" QFormat=\\"true\\" Name=\\"List Paragraph\\" /> <w:LsdException Locked=\\"false\\" Priority=\\"29\\" SemiHidden=\\"false\\"    UnhideWhenUsed=\\"false\\" QFormat=\\"true\\" Name=\\"Quote\\" /> <w:LsdException Locked=\\"false\\" Priority=\\"30\\" SemiHidden=\\"false\\"    UnhideWhenUsed=\\"false\\" QFormat=\\"true\\" Name=\\"Intense Quote\\" /> <w:LsdException Locked=\\"false\\" Priority=\\"66\\" SemiHidden=\\"false\\"    UnhideWhenUsed=\\"false\\" Name=\\"Medium List 2 Accent 1\\" /> <w:LsdException Locked=\\"false\\" Priority=\\"67\\" SemiHidden=\\"false\\"    UnhideWhenUsed=\\"false\\" Name=\\"Medium Grid 1 Accent 1\\" /> <w:LsdException Locked=\\"false\\" Priority=\\"68\\" SemiHidden=\\"false\\"    UnhideWhenUsed=\\"false\\" Name=\\"Medium Grid 2 Accent 1\\" /> <w:LsdException Locked=\\"false\\" Priority=\\"69\\" SemiHidden=\\"false\\"    UnhideWhenUsed=\\"false\\" Name=\\"Medium Grid 3 Accent 1\\" /> <w:LsdException Locked=\\"false\\" Priority=\\"70\\" SemiHidden=\\"false\\"    UnhideWhenUsed=\\"false\\" Name=\\"Dark List Accent 1\\" /> <w:LsdException Locked=\\"false\\" Priority=\\"71\\" SemiHidden=\\"false\\"    UnhideWhenUsed=\\"false\\" Name=\\"Colorful Shading Accent 1\\" /> <w:LsdException Locked=\\"false\\" Priority=\\"72\\" SemiHidden=\\"false\\"    UnhideWhenUsed=\\"false\\" Name=\\"Colorful List Accent 1\\" /> <w:LsdException Locked=\\"false\\" Priority=\\"73\\" SemiHidden=\\"false\\"    UnhideWhenUsed=\\"false\\" Name=\\"Colorful Grid Accent 1\\" /> <w:LsdException Locked=\\"false\\" Priority=\\"60\\" SemiHidden=\\"false\\"    UnhideWhenUsed=\\"false\\" Name=\\"Light Shading Accent 2\\" /> <w:LsdException Locked=\\"false\\" Priority=\\"61\\" SemiHidden=\\"false\\"    UnhideWhenUsed=\\"false\\" Name=\\"Light List Accent 2\\" /> <w:LsdException Locked=\\"false\\" Priority=\\"62\\" SemiHidden=\\"false\\"    UnhideWhenUsed=\\"false\\" Name=\\"Light Grid Accent 2\\" /> <w:LsdException Locked=\\"false\\" Priority=\\"63\\" SemiHidden=\\"false\\"    UnhideWhenUsed=\\"false\\" Name=\\"Medium Shading 1 Accent 2\\" /> <w:LsdException Locked=\\"false\\" Priority=\\"64\\" SemiHidden=\\"false\\"    UnhideWhenUsed=\\"false\\" Name=\\"Medium Shading 2 Accent 2\\" /> <w:LsdException Locked=\\"false\\" Priority=\\"65\\" SemiHidden=\\"false\\"    UnhideWhenUsed=\\"false\\" Name=\\"Medium List 1 Accent 2\\" /> <w:LsdException Locked=\\"false\\" Priority=\\"66\\" SemiHidden=\\"false\\"    UnhideWhenUsed=\\"false\\" Name=\\"Medium List 2 Accent 2\\" /> <w:LsdException Locked=\\"false\\" Priority=\\"67\\" SemiHidden=\\"false\\"    UnhideWhenUsed=\\"false\\" Name=\\"Medium Grid 1 Accent 2\\" /> <w:LsdException Locked=\\"false\\" Priority=\\"68\\" SemiHidden=\\"false\\"    UnhideWhenUsed=\\"false\\" Name=\\"Medium Grid 2 Accent 2\\" /> <w:LsdException Locked=\\"false\\" Priority=\\"69\\" SemiHidden=\\"false\\"    UnhideWhenUsed=\\"false\\" Name=\\"Medium Grid 3 Accent 2\\" /> <w:LsdException Locked=\\"false\\" Priority=\\"70\\" SemiHidden=\\"false\\"    UnhideWhenUsed=\\"false\\" Name=\\"Dark List Accent 2\\" /> <w:LsdException Locked=\\"false\\" Priority=\\"71\\" SemiHidden=\\"false\\"    UnhideWhenUsed=\\"false\\" Name=\\"Colorful Shading Accent 2\\" /> <w:LsdException Locked=\\"false\\" Priority=\\"72\\" SemiHidden=\\"false\\"    UnhideWhenUsed=\\"false\\" Name=\\"Colorful List Accent 2\\" /> <w:LsdException Locked=\\"false\\" Priority=\\"73\\" SemiHidden=\\"false\\"    UnhideWhenUsed=\\"false\\" Name=\\"Colorful Grid Accent 2\\" /> <w:LsdException Locked=\\"false\\" Priority=\\"60\\" SemiHidden=\\"false\\"    UnhideWhenUsed=\\"false\\" Name=\\"Light Shading Accent 3\\" /> <w:LsdException Locked=\\"false\\" Priority=\\"61\\" SemiHidden=\\"false\\"    UnhideWhenUsed=\\"false\\" Name=\\"Light List Accent 3\\" /> <w:LsdException Locked=\\"false\\" Priority=\\"62\\" SemiHidden=\\"false\\"    UnhideWhenUsed=\\"false\\" Name=\\"Light Grid Accent 3\\" /> <w:LsdException Locked=\\"false\\" Priority=\\"63\\" SemiHidden=\\"false\\"    UnhideWhenUsed=\\"false\\" Name=\\"Medium Shading 1 Accent 3\\" /> <w:LsdException Locked=\\"false\\" Priority=\\"64\\" SemiHidden=\\"false\\"    UnhideWhenUsed=\\"false\\" Name=\\"Medium Shading 2 Accent 3\\" /> <w:LsdException Locked=\\"false\\" Priority=\\"65\\" SemiHidden=\\"false\\"    UnhideWhenUsed=\\"false\\" Name=\\"Medium List 1 Accent 3\\" /> <w:LsdException Locked=\\"false\\" Priority=\\"66\\" SemiHidden=\\"false\\"    UnhideWhenUsed=\\"false\\" Name=\\"Medium List 2 Accent 3\\" /> <w:LsdException Locked=\\"false\\" Priority=\\"67\\" SemiHidden=\\"false\\"    UnhideWhenUsed=\\"false\\" Name=\\"Medium Grid 1 Accent 3\\" /> <w:LsdException Locked=\\"false\\" Priority=\\"68\\" SemiHidden=\\"false\\"    UnhideWhenUsed=\\"false\\" Name=\\"Medium Grid 2 Accent 3\\" /> <w:LsdException Locked=\\"false\\" Priority=\\"69\\" SemiHidden=\\"false\\"    UnhideWhenUsed=\\"false\\" Name=\\"Medium Grid 3 Accent 3\\" /> <w:LsdException Locked=\\"false\\" Priority=\\"70\\" SemiHidden=\\"false\\"    UnhideWhenUsed=\\"false\\" Name=\\"Dark List Accent 3\\" /> <w:LsdException Locked=\\"false\\" Priority=\\"71\\" SemiHidden=\\"false\\"    UnhideWhenUsed=\\"false\\" Name=\\"Colorful Shading Accent 3\\" /> <w:LsdException Locked=\\"false\\" Priority=\\"72\\" SemiHidden=\\"false\\"    UnhideWhenUsed=\\"false\\" Name=\\"Colorful List Accent 3\\" /> <w:LsdException Locked=\\"false\\" Priority=\\"73\\" SemiHidden=\\"false\\"    UnhideWhenUsed=\\"false\\" Name=\\"Colorful Grid Accent 3\\" /> <w:LsdException Locked=\\"false\\" Priority=\\"60\\" SemiHidden=\\"false\\"    UnhideWhenUsed=\\"false\\" Name=\\"Light Shading Accent 4\\" /> <w:LsdException Locked=\\"false\\" Priority=\\"61\\" SemiHidden=\\"false\\"    UnhideWhenUsed=\\"false\\" Name=\\"Light List Accent 4\\" /> <w:LsdException Locked=\\"false\\" Priority=\\"62\\" SemiHidden=\\"false\\"    UnhideWhenUsed=\\"false\\" Name=\\"Light Grid Accent 4\\" /> <w:LsdException Locked=\\"false\\" Priority=\\"63\\" SemiHidden=\\"false\\"    UnhideWhenUsed=\\"false\\" Name=\\"Medium Shading 1 Accent 4\\" /> <w:LsdException Locked=\\"false\\" Priority=\\"64\\" SemiHidden=\\"false\\"    UnhideWhenUsed=\\"false\\" Name=\\"Medium Shading 2 Accent 4\\" /> <w:LsdException Locked=\\"false\\" Priority=\\"65\\" SemiHidden=\\"false\\"    UnhideWhenUsed=\\"false\\" Name=\\"Medium List 1 Accent 4\\" /> <w:LsdException Locked=\\"false\\" Priority=\\"66\\" SemiHidden=\\"false\\"    UnhideWhenUsed=\\"false\\" Name=\\"Medium List 2 Accent 4\\" /> <w:LsdException Locked=\\"false\\" Priority=\\"67\\" SemiHidden=\\"false\\"    UnhideWhenUsed=\\"false\\" Name=\\"Medium Grid 1 Accent 4\\" /> <w:LsdException Locked=\\"false\\" Priority=\\"68\\" SemiHidden=\\"false\\"    UnhideWhenUsed=\\"false\\" Name=\\"Medium Grid 2 Accent 4\\" /> <w:LsdException Locked=\\"false\\" Priority=\\"69\\" SemiHidden=\\"false\\"    UnhideWhenUsed=\\"false\\" Name=\\"Medium Grid 3 Accent 4\\" /> <w:LsdException Locked=\\"false\\" Priority=\\"70\\" SemiHidden=\\"false\\"    UnhideWhenUsed=\\"false\\" Name=\\"Dark List Accent 4\\" /> <w:LsdException Locked=\\"false\\" Priority=\\"71\\" SemiHidden=\\"false\\"    UnhideWhenUsed=\\"false\\" Name=\\"Colorful Shading Accent 4\\" /> <w:LsdException Locked=\\"false\\" Priority=\\"72\\" SemiHidden=\\"false\\"    UnhideWhenUsed=\\"false\\" Name=\\"Colorful List Accent 4\\" /> <w:LsdException Locked=\\"false\\" Priority=\\"73\\" SemiHidden=\\"false\\"    UnhideWhenUsed=\\"false\\" Name=\\"Colorful Grid Accent 4\\" /> <w:LsdException Locked=\\"false\\" Priority=\\"60\\" SemiHidden=\\"false\\"    UnhideWhenUsed=\\"false\\" Name=\\"Light Shading Accent 5\\" /> <w:LsdException Locked=\\"false\\" Priority=\\"61\\" SemiHidden=\\"false\\"    UnhideWhenUsed=\\"false\\" Name=\\"Light List Accent 5\\" /> <w:LsdException Locked=\\"false\\" Priority=\\"62\\" SemiHidden=\\"false\\"    UnhideWhenUsed=\\"false\\" Name=\\"Light Grid Accent 5\\" /> <w:LsdException Locked=\\"false\\" Priority=\\"63\\" SemiHidden=\\"false\\"    UnhideWhenUsed=\\"false\\" Name=\\"Medium Shading 1 Accent 5\\" /> <w:LsdException Locked=\\"false\\" Priority=\\"64\\" SemiHidden=\\"false\\"    UnhideWhenUsed=\\"false\\" Name=\\"Medium Shading 2 Accent 5\\" /> <w:LsdException Locked=\\"false\\" Priority=\\"65\\" SemiHidden=\\"false\\"    UnhideWhenUsed=\\"false\\" Name=\\"Medium List 1 Accent 5\\" /> <w:LsdException Locked=\\"false\\" Priority=\\"66\\" SemiHidden=\\"false\\"    UnhideWhenUsed=\\"false\\" Name=\\"Medium List 2 Accent 5\\" /> <w:LsdException Locked=\\"false\\" Priority=\\"67\\" SemiHidden=\\"false\\"    UnhideWhenUsed=\\"false\\" Name=\\"Medium Grid 1 Accent 5\\" /> <w:LsdException Locked=\\"false\\" Priority=\\"68\\" SemiHidden=\\"false\\"    UnhideWhenUsed=\\"false\\" Name=\\"Medium Grid 2 Accent 5\\" /> <w:LsdException Locked=\\"false\\" Priority=\\"69\\" SemiHidden=\\"false\\"    UnhideWhenUsed=\\"false\\" Name=\\"Medium Grid 3 Accent 5\\" /> <w:LsdException Locked=\\"false\\" Priority=\\"70\\" SemiHidden=\\"false\\"    UnhideWhenUsed=\\"false\\" Name=\\"Dark List Accent 5\\" /> <w:LsdException Locked=\\"false\\" Priority=\\"71\\" SemiHidden=\\"false\\"    UnhideWhenUsed=\\"false\\" Name=\\"Colorful Shading Accent 5\\" /> <w:LsdException Locked=\\"false\\" Priority=\\"72\\" SemiHidden=\\"false\\"    UnhideWhenUsed=\\"false\\" Name=\\"Colorful List Accent 5\\" /> <w:LsdException Locked=\\"false\\" Priority=\\"73\\" SemiHidden=\\"false\\"    UnhideWhenUsed=\\"false\\" Name=\\"Colorful Grid Accent 5\\" /> <w:LsdException Locked=\\"false\\" Priority=\\"60\\" SemiHidden=\\"false\\"    UnhideWhenUsed=\\"false\\" Name=\\"Light Shading Accent 6\\" /> <w:LsdException Locked=\\"false\\" Priority=\\"61\\" SemiHidden=\\"false\\"    UnhideWhenUsed=\\"false\\" Name=\\"Light List Accent 6\\" /> <w:LsdException Locked=\\"false\\" Priority=\\"62\\" SemiHidden=\\"false\\"    UnhideWhenUsed=\\"false\\" Name=\\"Light Grid Accent 6\\" /> <w:LsdException Locked=\\"false\\" Priority=\\"63\\" SemiHidden=\\"false\\"    UnhideWhenUsed=\\"false\\" Name=\\"Medium Shading 1 Accent 6\\" /> <w:LsdException Locked=\\"false\\" Priority=\\"64\\" SemiHidden=\\"false\\"    UnhideWhenUsed=\\"false\\" Name=\\"Medium Shading 2 Accent 6\\" /> <w:LsdException Locked=\\"false\\" Priority=\\"65\\" SemiHidden=\\"false\\"    UnhideWhenUsed=\\"false\\" Name=\\"Medium List 1 Accent 6\\" /> <w:LsdException Locked=\\"false\\" Priority=\\"66\\" SemiHidden=\\"false\\"    UnhideWhenUsed=\\"false\\" Name=\\"Medium List 2 Accent 6\\" /> <w:LsdException Locked=\\"false\\" Priority=\\"67\\" SemiHidden=\\"false\\"    UnhideWhenUsed=\\"false\\" Name=\\"Medium Grid 1 Accent 6\\" /> <w:LsdException Locked=\\"false\\" Priority=\\"68\\" SemiHidden=\\"false\\"    UnhideWhenUsed=\\"false\\" Name=\\"Medium Grid 2 Accent 6\\" /> <w:LsdException Locked=\\"false\\" Priority=\\"69\\" SemiHidden=\\"false\\"    UnhideWhenUsed=\\"false\\" Name=\\"Medium Grid 3 Accent 6\\" /> <w:LsdException Locked=\\"false\\" Priority=\\"70\\" SemiHidden=\\"false\\"    UnhideWhenUsed=\\"false\\" Name=\\"Dark List Accent 6\\" /> <w:LsdException Locked=\\"false\\" Priority=\\"71\\" SemiHidden=\\"false\\"    UnhideWhenUsed=\\"false\\" Name=\\"Colorful Shading Accent 6\\" /> <w:LsdException Locked=\\"false\\" Priority=\\"72\\" SemiHidden=\\"false\\"    UnhideWhenUsed=\\"false\\" Name=\\"Colorful List Accent 6\\" /> <w:LsdException Locked=\\"false\\" Priority=\\"73\\" SemiHidden=\\"false\\"    UnhideWhenUsed=\\"false\\" Name=\\"Colorful Grid Accent 6\\" /> <w:LsdException Locked=\\"false\\" Priority=\\"19\\" SemiHidden=\\"false\\"    UnhideWhenUsed=\\"false\\" QFormat=\\"true\\" Name=\\"Subtle Emphasis\\" /> <w:LsdException Locked=\\"false\\" Priority=\\"21\\" SemiHidden=\\"false\\"    UnhideWhenUsed=\\"false\\" QFormat=\\"true\\" Name=\\"Intense Emphasis\\" /> <w:LsdException Locked=\\"false\\" Priority=\\"31\\" SemiHidden=\\"false\\"    UnhideWhenUsed=\\"false\\" QFormat=\\"true\\" Name=\\"Subtle Reference\\" /> <w:LsdException Locked=\\"false\\" Priority=\\"32\\" SemiHidden=\\"false\\"    UnhideWhenUsed=\\"false\\" QFormat=\\"true\\" Name=\\"Intense Reference\\" /> <w:LsdException Locked=\\"false\\" Priority=\\"33\\" SemiHidden=\\"false\\"    UnhideWhenUsed=\\"false\\" QFormat=\\"true\\" Name=\\"Book Title\\" /> <w:LsdException Locked=\\"false\\" Priority=\\"37\\" Name=\\"Bibliography\\" /> <w:LsdException Locked=\\"false\\" Priority=\\"39\\" QFormat=\\"true\\" Name=\\"TOC Heading\\" /> </w:LatentStyles> </xml><![endif]--><!--[if gte mso 10]> <mce:style><!   /* Style Definitions */  table.MsoNormalTable 	{mso-style-name:\\"Обычная таблица\\"; 	mso-tstyle-rowband-size:0; 	mso-tstyle-colband-size:0; 	mso-style-noshow:yes; 	mso-style-priority:99; 	mso-style-qformat:yes; 	mso-style-parent:\\"\\"; 	mso-padding-alt:0cm 5.4pt 0cm 5.4pt; 	mso-para-margin:0cm; 	mso-para-margin-bottom:.0001pt; 	mso-pagination:widow-orphan; 	font-size:11.0pt; 	font-family:\\"Calibri\\",\\"sans-serif\\"; 	mso-ascii-font-family:Calibri; 	mso-ascii-theme-font:minor-latin; 	mso-fareast-font-family:\\"Times New Roman\\"; 	mso-fareast-theme-font:minor-fareast; 	mso-hansi-font-family:Calibri; 	mso-hansi-theme-font:minor-latin; 	mso-bidi-font-family:\\"Times New Roman\\"; 	mso-bidi-theme-font:minor-bidi;} --> <!--[endif]--></p>
<p class=\\"MsoNormal\\"><span lang=\\"UK\\">Шановний %fullname%!<strong><br /> </strong><br /> %inviter%<span>&nbsp; </span>порекомендував запросити Вас приєднатися до соціальної мережі \\"Мерітократ\\", що об\\''єднує людей, яким не байдуже наше майбутнє і які готові брати учать у творенні нової України.</span></p>
<p class=\\"MsoNormal\\"><span lang=\\"UK\\">Основними завданнями мережі є: 1) створення дискусійної площадки для напрацювання стратегічних теоретичних засад та практичної програми розвитку України та 2) створення організаційної платформи для впровадження в життя напрацьованих ідей.</span></p>
<p class=\\"MsoNormal\\"><span lang=\\"UK\\">Мережа є закритою. Стати учасником мережі можливо тільки за запрошенням, тому в мережі немає неідентифікованих &laquo;віртуалів&raquo;.</span></p>
<p class=\\"MsoNormal\\"><span lang=\\"UK\\">До участі в мережі запрошуються суспільно-активні громадяни, експерти та спеціалісти в різних сферах життя, а також інші зацікавлені особи.</span></p>
<p class=\\"MsoNormal\\"><span style=\\"color: black;\\" lang=\\"UK\\">Активуйте та заповніть свою анкету і починайте знайомитися та спілкуватися з небайдужими українцями з різних куточків України та світу. </span></p>
<p class=\\"MsoNormal\\"><span style=\\"color: black;\\" lang=\\"UK\\">Щоб активувати анкету перейдіть за посиланням http://meritokrat.org/sign/autologin?email=%email%&amp;password=%password%.</span></p>
<p class=\\"MsoNormal\\"><span lang=\\"UK\\">Якщо наведене вище посилання не працює, скопіюйте його і вставте в адресний рядок браузера або перейдіть за посиланням www.meritokrat.org та введіть свій логін та пароль:</span></p>
<p>Логін: %email%</p>
<p>Пароль: %password% (радимо змінити пароль на ваш особистий при першому відвідуванні мережі)</p>
<p>У разі виникнення проблем при підтвердженні реєстрації або в роботі з мережею, зв\\''яжіться з Секретаріатом за адресою secretariat@meritokratia.info</p>
<p>Бажаємо Вам приємного перебування в мережі &laquo;Мерітократ&raquo;!</p>
<p>З повагою,<br />адміністрація Мерітократ.org</p>', '<p><span id=\\"result_box\\" class=\\"long_text\\"><span style=\\"background-color: #ffffff;\\" title=\\"Шановний %fullname%!\\" onmouseover=\\"this.style.backgroundColor=\\''#ebeff9\\''\\" onmouseout=\\"this.style.backgroundColor=\\''#fff\\''\\">Уважаемый %fullname%! <br /> <br /></span><span style=\\"background-color: #ffffff;\\" title=\\"%inviter% порекомендував запросити Вас приєднатися до соціальної мережі &quot;Мерітократ&quot;, що об\\''єднує людей, яким не байдуже наше майбутнє і які готові брати учать у творенні нової України.\\" onmouseover=\\"this.style.backgroundColor=\\''#ebeff9\\''\\" onmouseout=\\"this.style.backgroundColor=\\''#fff\\''\\">%inviter% порекомендовал пригласить Вас присоединиться к социальной сети \\"Меритократ\\", объединяющая людей, которым не безразлично наше будущее и которые готовы участвовать в создании новой Украине. <br /> <br /></span><span style=\\"background-color: #ffffff;\\" title=\\"Основними завданнями мережі є: 1) створення дискусійної площадки для напрацювання стратегічних теоретичних засад та практичної програми розвитку України та 2) створення організаційної платформи для впровадження в життя напрацьованих ідей.\\" onmouseover=\\"this.style.backgroundColor=\\''#ebeff9\\''\\" onmouseout=\\"this.style.backgroundColor=\\''#fff\\''\\">Основными задачами сети являются: 1) создание дискуссионной площадки для выработки стратегических теоретических основ и практической программы развития Украины и 2) создание организационной платформы для проведения в жизнь выработанных идей. <br /> <br /></span><span title=\\"Мережа є закритою.\\" onmouseover=\\"this.style.backgroundColor=\\''#ebeff9\\''\\" onmouseout=\\"this.style.backgroundColor=\\''#fff\\''\\">Сеть является закрытой. </span><span style=\\"background-color: #ffffff;\\" title=\\"Стати учасником мережі можливо тільки за запрошенням, тому в мережі немає неідентифікованих &laquo;віртуалів&raquo;.\\" onmouseover=\\"this.style.backgroundColor=\\''#ebeff9\\''\\" onmouseout=\\"this.style.backgroundColor=\\''#fff\\''\\">Стать участником сети возможно только по приглашению, поэтому в сети нет неидентифицированных &laquo;виртуалов&raquo;. <br /> <br /></span><span style=\\"background-color: #ebeff9;\\" title=\\"До участі в мережі запрошуються суспільно-активні громадяни, експерти та спеціалісти в різних сферах життя, а також інші зацікавлені особи.\\" onmouseover=\\"this.style.backgroundColor=\\''#ebeff9\\''\\" onmouseout=\\"this.style.backgroundColor=\\''#fff\\''\\">К участию в сети приглашаются общественно-активные граждане, эксперты и специалисты в разных сферах жизни, а также другие заинтересованные лица. <br /> <br /></span><span style=\\"background-color: #ffffff;\\" title=\\"Активуйте та заповніть свою анкету і починайте знайомитися та спілкуватися з небайдужими українцями з різних куточків України та світу.\\" onmouseover=\\"this.style.backgroundColor=\\''#ebeff9\\''\\" onmouseout=\\"this.style.backgroundColor=\\''#fff\\''\\">Активируйте и заполните свою анкету и начинайте знакомиться и общаться с неравнодушными украинцами из разных уголков Украины и мира. <br /> <br /></span><span style=\\"background-color: #ffffff;\\" title=\\"Щоб активувати анкету перейдіть за посиланням http://meritokrat.org/sign/autologin?email=%email%&amp;password=%password%.\\" onmouseover=\\"this.style.backgroundColor=\\''#ebeff9\\''\\" onmouseout=\\"this.style.backgroundColor=\\''#fff\\''\\">Для активации анкеты перейдите по ссылке </span></span></p>
<p><span id=\\"result_box\\" class=\\"long_text\\"><span style=\\"background-color: #ffffff;\\" title=\\"Щоб активувати анкету перейдіть за посиланням http://meritokrat.org/sign/autologin?email=%email%&amp;password=%password%.\\" onmouseover=\\"this.style.backgroundColor=\\''#ebeff9\\''\\" onmouseout=\\"this.style.backgroundColor=\\''#fff\\''\\">http://meritokrat.org/sign/autologin?email=%email%&amp;password=%password%.<br /> <br /></span><span style=\\"background-color: #ffffff;\\" title=\\"Якщо наведене вище посилання не працює, скопіюйте його і вставте в адресний рядок браузера або перейдіть за посиланням www.meritokrat.org та введіть свій логін та пароль:\\" onmouseover=\\"this.style.backgroundColor=\\''#ebeff9\\''\\" onmouseout=\\"this.style.backgroundColor=\\''#fff\\''\\">Если приведенное выше ссылка не работает, скопируйте ее и вставьте в адресную строку браузера или перейдите по ссылке www.meritokrat.org и введите свой логин и пароль: <br /> <br /></span><span title=\\"Логін: %email%\\" onmouseover=\\"this.style.backgroundColor=\\''#ebeff9\\''\\" onmouseout=\\"this.style.backgroundColor=\\''#fff\\''\\">Логин: %email% <br /> <br /></span><span style=\\"background-color: #ffffff;\\" title=\\"Пароль: %password% (радимо змінити пароль на ваш особистий при першому відвідуванні мережі)\\" onmouseover=\\"this.style.backgroundColor=\\''#ebeff9\\''\\" onmouseout=\\"this.style.backgroundColor=\\''#fff\\''\\">Пароль: %password% (советуем изменить пароль на ваш личный при первом посещении сети)<br /> <br /></span><span style=\\"background-color: #ffffff;\\" title=\\"У разі виникнення проблем при підтвердженні реєстрації або в роботі з мережею, зв\\''яжіться з Секретаріатом за адресою secretariat@meritokratia.info\\" onmouseover=\\"this.style.backgroundColor=\\''#ebeff9\\''\\" onmouseout=\\"this.style.backgroundColor=\\''#fff\\''\\">В случае возникновения проблем при подтверждении регистрации или в работе с сетью, свяжитесь с Секретариатом по адресу secretariat@meritokratia.info<br /> <br /></span><span style=\\"background-color: #ffffff;\\" title=\\"Бажаємо Вам приємного перебування в мережі &laquo;Мерітократ&raquo;!\\" onmouseover=\\"this.style.backgroundColor=\\''#ebeff9\\''\\" onmouseout=\\"this.style.backgroundColor=\\''#fff\\''\\">Желаем Вам приятного пребывания в сети &laquo;Меритократ&raquo;! <br /> <br /></span><span title=\\"З повагою,\\" onmouseover=\\"this.style.backgroundColor=\\''#ebeff9\\''\\" onmouseout=\\"this.style.backgroundColor=\\''#fff\\''\\">С уважением<br /></span><span title=\\"адміністрація Мерітократ.org\\" onmouseover=\\"this.style.backgroundColor=\\''#ebeff9\\''\\" onmouseout=\\"this.style.backgroundColor=\\''#fff\\''\\">администрация Меритократ.org</span></span></p>
<div id=\\"gt-res-tools\\" class=\\"g-section\\">
<div id=\\"gt-res-listen\\" class=\\"gt-icon-c\\" style=\\"display: none;\\"><span class=\\"gt-icon-text\\">Прослушать</span></div>
<div id=\\"gt-res-roman\\" class=\\"gt-icon-c\\" style=\\"display: none;\\"><span class=\\"gt-icon-text\\">На латинице</span></div>
</div>', 'info@meritokrat.org', 'Мерiтократ.org', 'Меритократ.org', 1);
INSERT INTO email_system VALUES (11, 'events_comment', 'Новий коментар до подii', 'Новий коментар до подii', 'Новый комментарий к событию', '<p>%receiver%, до подiї додали комментар.</p>
<p>%sender% пише: %text%.</p>
<p>Щоб переглянути та вiдповiсти, перейдiть за посиланням: <a href=\\"%link%\\">%link%</a></p>', '<p>%receiver%, к событию добавили комментарий.</p>
<p>%sender% пишет: %text%.</p>
<p>Чтобы ответить, перейтите по ссылке: <a href=\\"%link%\\">%link%</a></p>', 'info@meritokrat.org', 'Мерiтократ.org', 'Меритократ.org', 1);
INSERT INTO email_system VALUES (15, 'groups_create', 'Пропозиція нової спільноти', '%sender% пропонує нову спiльноту', '%sender% предлагает новое сообщество', '<p>Від учасника %sender% надійшла пропозиція щодо створення нової спільноти \\"%title%\\".</p>
<p>Щоб переглянути, перейдіть за посиланням: <a href=\\"%link%\\">%link%</a></p>', '<p>От участника %sender% поступило предложение о создании сообщества \\"%title%\\".</p>
<p>Чтобы перейти на страничку сообщества, нажчмите на ссылку: <a href=\\"%link%\\">%link%</a></p>', 'info@meritokrat.org', 'Мерiтократ.org', 'Меритократ.org', 1);
INSERT INTO email_system VALUES (20, 'leadergroups_approve_leadergroup', 'Ваша лідерська група схвалена', 'Ваша лідерська група схвалена', 'Ваше сообщество одобрили', '<p>%receiver%, запропонована Вами лідерська група схвалена.</p>
<p>Відвідайте сторінку Вашої групи: <a href=\\"%link%\\">%link%</a></p>', '<p><span id=\\"result_box\\" class=\\"long_text\\" lang=\\"ru\\"><span>%Receiver%, предложенная Вами лидерская группа одобрена. </span></span></p>
<p><span id=\\"result_box\\" class=\\"long_text\\" lang=\\"ru\\"><span>Посетите страницу Вашей группы: <a href=\\"%link%\\">%link%</a></span></span></p>
<p>&nbsp;</p>', 'info@meritokrat.org', 'Мерiтократ.org', 'Меритократ.org', 1);
INSERT INTO email_system VALUES (17, 'invites_add_event', 'Запрошення на подію', 'Запрошення на %title%', 'Приглашение на  %sender%', '<p>Доброго дня!</p>
<p>%image% %name% запрошує Вас відвідати подію %title%:</p>
<p>%message%</p>
<p>Щоб дізнатись більше про подію, натисніть <a href=\\"%profile%\\">тут</a>.</p>
<p>Щоб одразу прийняти запрошення, натисніть <a href=\\"%link%\\">тут</a>.</p>
<p>&nbsp;</p>', '<p>Добрый день!</p>
<p>%image% %name% приглашает Вас посетить событие %title%</p>
<p>%message%</p>
<p>Чтобы получить дополнительную информацию о событии, нажмите <a href=\\"%profile%\\">здесь</a>.</p>
<p>Чтобы сразу принять приглашение, нажмите <a href=\\"%link%\\">здесь</a>.</p>', 'info@meritokrat.org', 'Мерiтократ.org', 'Меритократ.org', 1);
INSERT INTO email_system VALUES (31, 'leadergroups_add_moderator', 'Вы были назначены модератором сообщества', 'Вы были назначены модератором сообщества', 'Вы были назначены модератором сообщества', '<p>%sender% пише:  Доброго дня! Ви були призначенi модератором спiльноти.</p>
<p>&nbsp;</p>', '<p>%sender% пишет: Добрый день! Вы были назначены модератором сообщества.</p>
<p>&nbsp;</p>', 'info@meritokrat.org', 'Мерiтократ.org', 'Меритократ.org', 1);
INSERT INTO email_system VALUES (33, 'admin_users', 'admin_users (не используется)', 'Активацiя аккаунта на meritokrat.org', 'Активация аккаунта на meritokrat.org', '<p>%name% Ваш аккаунт был активований.</p>
<p>Щоб зайти на сайт, перейдiть за посиланням: <a href=\\"%link%\\">%link%</a></p>', '<p>%name% Ваш аккаунт был активирован.</p>
<p>Для входа на сайт зайдите по ссылке: <a href=\\"%link%\\">%link%</a></p>', 'info@meritokrat.org', 'Мерітократ.org', 'Меритократ.org', 1);
INSERT INTO email_system VALUES (35, 'sign_recover', 'Відновлення пароля', 'Відновлення пароля на meritokrat.org', 'Восстановление пароля на meritokrat.org', '<p>Добрий день,  Ваше посилання для вiдновлення пароля: <a href=\\"%link%\\">%link%</a></p>', '<p>Здравствуйте,  Ваша ссылка для восстановления пароля: <a href=\\"%link%\\">%link%</a></p>', 'info@meritokrat.org', 'Мерітократ.org', 'Меритократ.org', 1);
INSERT INTO email_system VALUES (25, 'messages_share', 'Новое сообщение: Поделиться ', '%sender%: Нове повiдомлення', '%sender%: Новое сообщение', '<p>%sender% пише: %text% .</p>
<p>Щоб вiдповiсти, перейдiть за посиланням: <a href=\\"%link%\\">%link%</a></p>', '<p>%sender% пишет: %text% .</p>
<p>Чтобы ответить, перейдите по ссылке: <a href=\\"%link%\\">%link%</a></p>', 'info@meritokrat.org', 'Мерiтократ.org', 'Меритократ.org', 1);
INSERT INTO email_system VALUES (19, 'leadergroups_approve_applicant', 'Вас прийняли до лідерської групи', 'Вас прийняли до лідерської групи %title%', 'Вас приняли в сообщество', '<p>%receiver%, Ваша заявка на вступ до лідерської групи \\"%title%\\" схвалена.</p>
<p>Відвідайте сторінку цієї лідерської групи <a href=\\"%link%\\">%link%</a></p>', '<p><span id=\\"result_box\\" class=\\"long_text\\" lang=\\"ru\\"><span>% Receiver%, Ваша заявка на вступление в лидерскую группу \\"%title%\\" одобрена. </span></span></p>
<p><span id=\\"result_box\\" class=\\"long_text\\" lang=\\"ru\\"><span>Посетите страницу этой лидерской группы <a href=\\"%link%\\">%link%</a></span></span></p>', 'info@meritokrat.org', 'Мерiтократ.org', 'Меритократ.org', 1);
INSERT INTO email_system VALUES (16, 'ideas_comment', 'Новий коментар до ідеології', 'Новий коментар до Вашої публікації в Ідеології', 'Новый комментарий к Вашей идеологии', '<p>%receiver%, до Вашої публікації додали коментар:  %sender% пише %text%  .</p>
<p>Щоб вiдповiсти, перейдiть за посиланням: <a href=\\"%link%\\">%link%</a></p>', '<p>%receiver% к Вашей идее добавили комментарий:  %sender% пишет %text%.</p>
<p>Что-бы ответить, перейдите по ссылке: <a href=\\"%link%\\">%link%</a></p>', 'info@meritokrat.org', 'Мерiтократ.org', 'Меритократ.org', 1);
INSERT INTO email_system VALUES (5, 'users_create_recommend', 'Рекомендація учасника схвалена', 'Рекомендація учасника %first_name% %last_name% схвалена', 'Рекомендация участника %first_name% %last_name% одобрена', '<p>Шановний %from%, Ваша рекомендація учасника %first_name% %last_name% була схвалена адміністрацією.</p>
<p>Дякуємо за активність!</p>', '<p><span id=\\"result_box\\" lang=\\"ru\\"><span>Уважаемый %from%, Ваша рекомендация участника %first_name%% last_name% была одобрена администрацией. </span></span></p>
<p><span id=\\"result_box\\" lang=\\"ru\\"><span>Спасибо за активность!</span></span></p>', 'info@meritokrat.org', 'Мерiтократ.org', 'Меритократ.org', 1);
INSERT INTO email_system VALUES (28, 'profile_edit', 'Учасник змінив персональні дані', 'Учасник %name% змінив персональні дані', 'Участник %name% изменил  персональные данные', '<p>%name% змінив персональні дані.</p>
<p>Тепер його звати: %newname%</p>', '<p>%name% изменил персональные данные.</p>
<p>Тепер его зовут: %newname%</p>', 'info@meritokrat.org', 'Мерiтократ.org', 'Меритократ.org', 1);
INSERT INTO email_system VALUES (21, 'leadergroups_create', 'Пропозиція нової лідерської групи', '%sender% пропонує нову лідерську групу', '%sender% предлагает новое сообщество', '<p>Від учасника %sender% надійшла пропозиція щодо створення нової лідерської групи \\"%title%\\".</p>
<p>Щоб переглянути, перейдіть за посиланням: <a href=\\"%link%\\">%link%</a></p>', '<p><span id=\\"result_box\\" class=\\"long_text\\" lang=\\"ru\\"><span>От участника %sender% поступило предложение о создании новой лидерской группы \\"%title%\\". </span></span></p>
<p><span id=\\"result_box\\" class=\\"long_text\\" lang=\\"ru\\"><span>Для просмотра перейдите по ссылке: <a href=\\"%link%\\">%link%</a></span></span></p>
<p><span class=\\"long_text\\" lang=\\"ru\\"><span><br /></span></span></p>
<p>&nbsp;</p>
<p>&nbsp;</p>', 'info@meritokrat.org', 'Мерiтократ.org', 'Меритократ.org', 1);
INSERT INTO email_system VALUES (32, 'groups_join', 'Нова заявка на вступление в сообщество ', 'Нова заявка на вступ до спільноти %title%', 'Новая заявка на вступление в сообщество %title%', '<p>%name% подав заявку на вступ до  спільноти \\"%title%\\".</p>
<p>Щоб переглянути список заявок, перейдіть за посиланням <a href=\\"%link%\\">%link%</a></p>', '<p>%name% подал заявку на вступление в сообщество \\"%title%\\".</p>
<p><span id=\\"result_box\\" class=\\"long_text\\" lang=\\"ru\\"><span>Для просмотра списка заявок, перейдите по ссылке</span></span> <a href=\\"%link%\\">%link%</a></p>', 'info@meritokrat.org', 'Мерітократ.org', 'Меритократ.org', 1);
INSERT INTO email_system VALUES (30, 'groups_add_moderator', 'groups_add_moderator', 'Нове повiдомлення', 'Новое сообщение', '<p>%sender% пише:  Доброго дня! Ви були призначенi модератором спiльноти.</p>
<p>Щоб вiдповiсти, перейдiть за посиланням: <a href=\\"%link%\\">%link%</a></p>', '<p>%sender% пишет:  Добрый день! Вы были назначены модератором соообщества.</p>
<p>Чтобы ответить, перейдите по ссылке: <a href=\\"%link%\\">%link%</a></p>', 'info@meritokrat.org', 'Мерiтократ.org', 'Меритократ.org', 0);
INSERT INTO email_system VALUES (27, 'profile_ask', 'Новая запись на стене', '%sender% написав на Вашій стіні', '%sender% написал на Вашей стене', '<p>%receiver%,   %sender% написав на Вашій стіні: %text%.</p>
<p>Щоб переглянути напис, перейдіть за посиланням: <a href=\\"%link%\\">%link%</a></p>', '<p>%receiver%,   %sender% написал на Вашей стене: %text%.</p>
<p>Чтобы просмотреть запись, перейдите по ссылке: <a href=\\"%link%\\">%link%</a></p>', 'info@meritokrat.org', 'Мерiтократ.org', 'Меритократ.org', 1);
INSERT INTO email_system VALUES (24, 'messages_reply', 'Відповісти на повідомлення', '%sender%: Нове повiдомлення', '%sender%: Новое сообщение', '<p>%sender% пише: %text%</p>
<p>Щоб вiдповiсти, перейдiть за посиланням: <a href=\\"%link%\\">%link%</a></p>', '<p>%sender% пишет: %text%</p>
<p>Чтобы ответить, перейдите по ссылке: <a href=\\"%link%\\">%link%</a></p>', 'info@meritokrat.org', 'Мерiтократ.org', 'Меритократ.org', 1);
INSERT INTO email_system VALUES (23, 'messages_compose_sendgroup', 'Нове повідомлення: Розсилка групі', 'Нове повiдомлення', 'Новое сообщение', '<p>%sender% пише: %text%.</p>
<p>Щоб вiдповiсти, перейдiть за посиланням: <a href=\\"%link%\\">%link%</a></p>', '<p>%sender% пишет: %text%.</p>
<p>Что-бы ответить, перейдите по ссылке: <a href=\\"%link%\\">%link%</a></p>', 'info@meritokrat.org', 'Мерiтократ.org', 'Меритократ.org', 1);
INSERT INTO email_system VALUES (18, 'invites_add_group', 'Запрошення до спiльноти', 'Запрошення до спiльноти %title%', 'Приглашение в сообщество %title%', '<p>Доброго дня!</p>
<p>%image%</p>
<p>%name% запрошує Вас приєднатися до спiльноти \\"%title%\\".</p>
<p>%message%</p>
<p>Щоб дізнатись більше про спiльноту, натисніть <a href=\\"%profile%\\">тут</a>.</p>
<p>Щоб одразу прийняти запрошення, натисніть <a href=\\"%link%\\">тут</a>.</p>
<p>&nbsp;</p>', '<p><span id=\\"result_box\\" class=\\"long_text\\" lang=\\"ru\\">Добрый день!<br /><br />%image%<br /><br />%name% приглашает Вас присоединиться к сообществу \\"%title%\\".<br /><br />%message%<br /><br />Дополнительную информацию о сообществе, нажмите </span><a href=\\"%profile%\\"><span style=\\"color: #0000ff;\\"><span style=\\"text-decoration: underline;\\">здесь.</span></span></a><br /><span id=\\"result_box\\" class=\\"long_text\\" lang=\\"ru\\"><br />Чтобы сразу принять приглашение, нажмите<span style=\\"color: #0000ff;\\"><span style=\\"text-decoration: underline;\\"> <a href=\\"%link%\\">здесь.</a></span></span></span></p>', 'info@meritokrat.org', 'Мерiтократ.org', 'Меритократ.org', 1);
INSERT INTO email_system VALUES (26, 'polls_comment', 'Новый комментарий к опросу', 'Новий коментар до опитування', 'Новый комментарий к опросу', '<p>%receiver%, до Вашого опитування додали коментар: %sender% пише: %text%.</p>
<p>Щоб відповісти, перейдіть за посиланням: <a href=\\"%link%\\">%link%</a></p>', '<p>%receiver%, к Вашему опросу добавили комментарий:  %sender% пишет: %text%.</p>
<p>Что-бы ответить, перейдите по ссылке: <a href=\\"%link%\\">%link%</a></p>', 'info@meritokrat.org', 'Мерiтократ.org', 'Меритократ.org', 1);
INSERT INTO email_system VALUES (9, 'users_create_inviter', 'Лист №1 при запрошенні адміном', 'Ваш пароль та логін у соціальній мережі Мерiтократ.org', 'Ваш пароль и логин в социальной сети Меритократ.org', '<p>Шановний %fullname%!</p>
<p>%inviter% запрошує Вас приєднатися до соціальної мережі \\"Мерітократ\\", що об\\''єднує людей, яким не байдуже наше майбутнє і які готові брати учать у творенні нової України.</p>
<p>Основними завданнями мережі є: 1) створення дискусійної площадки для напрацювання стратегічних теоретичних засад та практичної програми розвитку України та 2) створення організаційної платформи для впровадження в життя напрацьованих ідей.</p>
<p>Мережа є закритою. Стати учасником мережі можливо тільки за запрошенням, тому в мережі немає неідентифікованих &laquo;віртуалів&raquo;.</p>
<p>До участі в мережі запрошуються суспільно-активні громадяни, експерти та спеціалісти в різних сферах життя, а також інші зацікавлені особи.</p>
<p><!--[if gte mso 9]><xml> <w:WordDocument> <w:View>Normal</w:View> <w:Zoom>0</w:Zoom> <w:TrackMoves /> <w:TrackFormatting /> <w:PunctuationKerning /> <w:ValidateAgainstSchemas /> <w:SaveIfXMLInvalid>false</w:SaveIfXMLInvalid> <w:IgnoreMixedContent>false</w:IgnoreMixedContent> <w:AlwaysShowPlaceholderText>false</w:AlwaysShowPlaceholderText> <w:DoNotPromoteQF /> <w:LidThemeOther>RU</w:LidThemeOther> <w:LidThemeAsian>X-NONE</w:LidThemeAsian> <w:LidThemeComplexScript>X-NONE</w:LidThemeComplexScript> <w:Compatibility> <w:BreakWrappedTables /> <w:SnapToGridInCell /> <w:WrapTextWithPunct /> <w:UseAsianBreakRules /> <w:DontGrowAutofit /> <w:SplitPgBreakAndParaMark /> <w:DontVertAlignCellWithSp /> <w:DontBreakConstrainedForcedTables /> <w:DontVertAlignInTxbx /> <w:Word11KerningPairs /> <w:CachedColBalance /> </w:Compatibility> <m:mathPr> <m:mathFont m:val=\\"Cambria Math\\" /> <m:brkBin m:val=\\"before\\" /> <m:brkBinSub m:val=\\"&#45;-\\" /> <m:smallFrac m:val=\\"off\\" /> <m:dispDef /> <m:lMargin m:val=\\"0\\" /> <m:rMargin m:val=\\"0\\" /> <m:defJc m:val=\\"centerGroup\\" /> <m:wrapIndent m:val=\\"1440\\" /> <m:intLim m:val=\\"subSup\\" /> <m:naryLim m:val=\\"undOvr\\" /> </m:mathPr></w:WordDocument> </xml><![endif]--><!--[if gte mso 9]><xml> <w:LatentStyles DefLockedState=\\"false\\" DefUnhideWhenUsed=\\"true\\"   DefSemiHidden=\\"true\\" DefQFormat=\\"false\\" DefPriority=\\"99\\"   LatentStyleCount=\\"267\\"> <w:LsdException Locked=\\"false\\" Priority=\\"0\\" SemiHidden=\\"false\\"    UnhideWhenUsed=\\"false\\" QFormat=\\"true\\" Name=\\"Normal\\" /> <w:LsdException Locked=\\"false\\" Priority=\\"9\\" SemiHidden=\\"false\\"    UnhideWhenUsed=\\"false\\" QFormat=\\"true\\" Name=\\"heading 1\\" /> <w:LsdException Locked=\\"false\\" Priority=\\"9\\" QFormat=\\"true\\" Name=\\"heading 2\\" /> <w:LsdException Locked=\\"false\\" Priority=\\"9\\" QFormat=\\"true\\" Name=\\"heading 3\\" /> <w:LsdException Locked=\\"false\\" Priority=\\"9\\" QFormat=\\"true\\" Name=\\"heading 4\\" /> <w:LsdException Locked=\\"false\\" Priority=\\"9\\" QFormat=\\"true\\" Name=\\"heading 5\\" /> <w:LsdException Locked=\\"false\\" Priority=\\"9\\" QFormat=\\"true\\" Name=\\"heading 6\\" /> <w:LsdException Locked=\\"false\\" Priority=\\"9\\" QFormat=\\"true\\" Name=\\"heading 7\\" /> <w:LsdException Locked=\\"false\\" Priority=\\"9\\" QFormat=\\"true\\" Name=\\"heading 8\\" /> <w:LsdException Locked=\\"false\\" Priority=\\"9\\" QFormat=\\"true\\" Name=\\"heading 9\\" /> <w:LsdException Locked=\\"false\\" Priority=\\"39\\" Name=\\"toc 1\\" /> <w:LsdException Locked=\\"false\\" Priority=\\"39\\" Name=\\"toc 2\\" /> <w:LsdException Locked=\\"false\\" Priority=\\"39\\" Name=\\"toc 3\\" /> <w:LsdException Locked=\\"false\\" Priority=\\"39\\" Name=\\"toc 4\\" /> <w:LsdException Locked=\\"false\\" Priority=\\"39\\" Name=\\"toc 5\\" /> <w:LsdException Locked=\\"false\\" Priority=\\"39\\" Name=\\"toc 6\\" /> <w:LsdException Locked=\\"false\\" Priority=\\"39\\" Name=\\"toc 7\\" /> <w:LsdException Locked=\\"false\\" Priority=\\"39\\" Name=\\"toc 8\\" /> <w:LsdException Locked=\\"false\\" Priority=\\"39\\" Name=\\"toc 9\\" /> <w:LsdException Locked=\\"false\\" Priority=\\"35\\" QFormat=\\"true\\" Name=\\"caption\\" /> <w:LsdException Locked=\\"false\\" Priority=\\"10\\" SemiHidden=\\"false\\"    UnhideWhenUsed=\\"false\\" QFormat=\\"true\\" Name=\\"Title\\" /> <w:LsdException Locked=\\"false\\" Priority=\\"1\\" Name=\\"Default Paragraph Font\\" /> <w:LsdException Locked=\\"false\\" Priority=\\"11\\" SemiHidden=\\"false\\"    UnhideWhenUsed=\\"false\\" QFormat=\\"true\\" Name=\\"Subtitle\\" /> <w:LsdException Locked=\\"false\\" Priority=\\"22\\" SemiHidden=\\"false\\"    UnhideWhenUsed=\\"false\\" QFormat=\\"true\\" Name=\\"Strong\\" /> <w:LsdException Locked=\\"false\\" Priority=\\"20\\" SemiHidden=\\"false\\"    UnhideWhenUsed=\\"false\\" QFormat=\\"true\\" Name=\\"Emphasis\\" /> <w:LsdException Locked=\\"false\\" Priority=\\"59\\" SemiHidden=\\"false\\"    UnhideWhenUsed=\\"false\\" Name=\\"Table Grid\\" /> <w:LsdException Locked=\\"false\\" UnhideWhenUsed=\\"false\\" Name=\\"Placeholder Text\\" /> <w:LsdException Locked=\\"false\\" Priority=\\"1\\" SemiHidden=\\"false\\"    UnhideWhenUsed=\\"false\\" QFormat=\\"true\\" Name=\\"No Spacing\\" /> <w:LsdException Locked=\\"false\\" Priority=\\"60\\" SemiHidden=\\"false\\"    UnhideWhenUsed=\\"false\\" Name=\\"Light Shading\\" /> <w:LsdException Locked=\\"false\\" Priority=\\"61\\" SemiHidden=\\"false\\"    UnhideWhenUsed=\\"false\\" Name=\\"Light List\\" /> <w:LsdException Locked=\\"false\\" Priority=\\"62\\" SemiHidden=\\"false\\"    UnhideWhenUsed=\\"false\\" Name=\\"Light Grid\\" /> <w:LsdException Locked=\\"false\\" Priority=\\"63\\" SemiHidden=\\"false\\"    UnhideWhenUsed=\\"false\\" Name=\\"Medium Shading 1\\" /> <w:LsdException Locked=\\"false\\" Priority=\\"64\\" SemiHidden=\\"false\\"    UnhideWhenUsed=\\"false\\" Name=\\"Medium Shading 2\\" /> <w:LsdException Locked=\\"false\\" Priority=\\"65\\" SemiHidden=\\"false\\"    UnhideWhenUsed=\\"false\\" Name=\\"Medium List 1\\" /> <w:LsdException Locked=\\"false\\" Priority=\\"66\\" SemiHidden=\\"false\\"    UnhideWhenUsed=\\"false\\" Name=\\"Medium List 2\\" /> <w:LsdException Locked=\\"false\\" Priority=\\"67\\" SemiHidden=\\"false\\"    UnhideWhenUsed=\\"false\\" Name=\\"Medium Grid 1\\" /> <w:LsdException Locked=\\"false\\" Priority=\\"68\\" SemiHidden=\\"false\\"    UnhideWhenUsed=\\"false\\" Name=\\"Medium Grid 2\\" /> <w:LsdException Locked=\\"false\\" Priority=\\"69\\" SemiHidden=\\"false\\"    UnhideWhenUsed=\\"false\\" Name=\\"Medium Grid 3\\" /> <w:LsdException Locked=\\"false\\" Priority=\\"70\\" SemiHidden=\\"false\\"    UnhideWhenUsed=\\"false\\" Name=\\"Dark List\\" /> <w:LsdException Locked=\\"false\\" Priority=\\"71\\" SemiHidden=\\"false\\"    UnhideWhenUsed=\\"false\\" Name=\\"Colorful Shading\\" /> <w:LsdException Locked=\\"false\\" Priority=\\"72\\" SemiHidden=\\"false\\"    UnhideWhenUsed=\\"false\\" Name=\\"Colorful List\\" /> <w:LsdException Locked=\\"false\\" Priority=\\"73\\" SemiHidden=\\"false\\"    UnhideWhenUsed=\\"false\\" Name=\\"Colorful Grid\\" /> <w:LsdException Locked=\\"false\\" Priority=\\"60\\" SemiHidden=\\"false\\"    UnhideWhenUsed=\\"false\\" Name=\\"Light Shading Accent 1\\" /> <w:LsdException Locked=\\"false\\" Priority=\\"61\\" SemiHidden=\\"false\\"    UnhideWhenUsed=\\"false\\" Name=\\"Light List Accent 1\\" /> <w:LsdException Locked=\\"false\\" Priority=\\"62\\" SemiHidden=\\"false\\"    UnhideWhenUsed=\\"false\\" Name=\\"Light Grid Accent 1\\" /> <w:LsdException Locked=\\"false\\" Priority=\\"63\\" SemiHidden=\\"false\\"    UnhideWhenUsed=\\"false\\" Name=\\"Medium Shading 1 Accent 1\\" /> <w:LsdException Locked=\\"false\\" Priority=\\"64\\" SemiHidden=\\"false\\"    UnhideWhenUsed=\\"false\\" Name=\\"Medium Shading 2 Accent 1\\" /> <w:LsdException Locked=\\"false\\" Priority=\\"65\\" SemiHidden=\\"false\\"    UnhideWhenUsed=\\"false\\" Name=\\"Medium List 1 Accent 1\\" /> <w:LsdException Locked=\\"false\\" UnhideWhenUsed=\\"false\\" Name=\\"Revision\\" /> <w:LsdException Locked=\\"false\\" Priority=\\"34\\" SemiHidden=\\"false\\"    UnhideWhenUsed=\\"false\\" QFormat=\\"true\\" Name=\\"List Paragraph\\" /> <w:LsdException Locked=\\"false\\" Priority=\\"29\\" SemiHidden=\\"false\\"    UnhideWhenUsed=\\"false\\" QFormat=\\"true\\" Name=\\"Quote\\" /> <w:LsdException Locked=\\"false\\" Priority=\\"30\\" SemiHidden=\\"false\\"    UnhideWhenUsed=\\"false\\" QFormat=\\"true\\" Name=\\"Intense Quote\\" /> <w:LsdException Locked=\\"false\\" Priority=\\"66\\" SemiHidden=\\"false\\"    UnhideWhenUsed=\\"false\\" Name=\\"Medium List 2 Accent 1\\" /> <w:LsdException Locked=\\"false\\" Priority=\\"67\\" SemiHidden=\\"false\\"    UnhideWhenUsed=\\"false\\" Name=\\"Medium Grid 1 Accent 1\\" /> <w:LsdException Locked=\\"false\\" Priority=\\"68\\" SemiHidden=\\"false\\"    UnhideWhenUsed=\\"false\\" Name=\\"Medium Grid 2 Accent 1\\" /> <w:LsdException Locked=\\"false\\" Priority=\\"69\\" SemiHidden=\\"false\\"    UnhideWhenUsed=\\"false\\" Name=\\"Medium Grid 3 Accent 1\\" /> <w:LsdException Locked=\\"false\\" Priority=\\"70\\" SemiHidden=\\"false\\"    UnhideWhenUsed=\\"false\\" Name=\\"Dark List Accent 1\\" /> <w:LsdException Locked=\\"false\\" Priority=\\"71\\" SemiHidden=\\"false\\"    UnhideWhenUsed=\\"false\\" Name=\\"Colorful Shading Accent 1\\" /> <w:LsdException Locked=\\"false\\" Priority=\\"72\\" SemiHidden=\\"false\\"    UnhideWhenUsed=\\"false\\" Name=\\"Colorful List Accent 1\\" /> <w:LsdException Locked=\\"false\\" Priority=\\"73\\" SemiHidden=\\"false\\"    UnhideWhenUsed=\\"false\\" Name=\\"Colorful Grid Accent 1\\" /> <w:LsdException Locked=\\"false\\" Priority=\\"60\\" SemiHidden=\\"false\\"    UnhideWhenUsed=\\"false\\" Name=\\"Light Shading Accent 2\\" /> <w:LsdException Locked=\\"false\\" Priority=\\"61\\" SemiHidden=\\"false\\"    UnhideWhenUsed=\\"false\\" Name=\\"Light List Accent 2\\" /> <w:LsdException Locked=\\"false\\" Priority=\\"62\\" SemiHidden=\\"false\\"    UnhideWhenUsed=\\"false\\" Name=\\"Light Grid Accent 2\\" /> <w:LsdException Locked=\\"false\\" Priority=\\"63\\" SemiHidden=\\"false\\"    UnhideWhenUsed=\\"false\\" Name=\\"Medium Shading 1 Accent 2\\" /> <w:LsdException Locked=\\"false\\" Priority=\\"64\\" SemiHidden=\\"false\\"    UnhideWhenUsed=\\"false\\" Name=\\"Medium Shading 2 Accent 2\\" /> <w:LsdException Locked=\\"false\\" Priority=\\"65\\" SemiHidden=\\"false\\"    UnhideWhenUsed=\\"false\\" Name=\\"Medium List 1 Accent 2\\" /> <w:LsdException Locked=\\"false\\" Priority=\\"66\\" SemiHidden=\\"false\\"    UnhideWhenUsed=\\"false\\" Name=\\"Medium List 2 Accent 2\\" /> <w:LsdException Locked=\\"false\\" Priority=\\"67\\" SemiHidden=\\"false\\"    UnhideWhenUsed=\\"false\\" Name=\\"Medium Grid 1 Accent 2\\" /> <w:LsdException Locked=\\"false\\" Priority=\\"68\\" SemiHidden=\\"false\\"    UnhideWhenUsed=\\"false\\" Name=\\"Medium Grid 2 Accent 2\\" /> <w:LsdException Locked=\\"false\\" Priority=\\"69\\" SemiHidden=\\"false\\"    UnhideWhenUsed=\\"false\\" Name=\\"Medium Grid 3 Accent 2\\" /> <w:LsdException Locked=\\"false\\" Priority=\\"70\\" SemiHidden=\\"false\\"    UnhideWhenUsed=\\"false\\" Name=\\"Dark List Accent 2\\" /> <w:LsdException Locked=\\"false\\" Priority=\\"71\\" SemiHidden=\\"false\\"    UnhideWhenUsed=\\"false\\" Name=\\"Colorful Shading Accent 2\\" /> <w:LsdException Locked=\\"false\\" Priority=\\"72\\" SemiHidden=\\"false\\"    UnhideWhenUsed=\\"false\\" Name=\\"Colorful List Accent 2\\" /> <w:LsdException Locked=\\"false\\" Priority=\\"73\\" SemiHidden=\\"false\\"    UnhideWhenUsed=\\"false\\" Name=\\"Colorful Grid Accent 2\\" /> <w:LsdException Locked=\\"false\\" Priority=\\"60\\" SemiHidden=\\"false\\"    UnhideWhenUsed=\\"false\\" Name=\\"Light Shading Accent 3\\" /> <w:LsdException Locked=\\"false\\" Priority=\\"61\\" SemiHidden=\\"false\\"    UnhideWhenUsed=\\"false\\" Name=\\"Light List Accent 3\\" /> <w:LsdException Locked=\\"false\\" Priority=\\"62\\" SemiHidden=\\"false\\"    UnhideWhenUsed=\\"false\\" Name=\\"Light Grid Accent 3\\" /> <w:LsdException Locked=\\"false\\" Priority=\\"63\\" SemiHidden=\\"false\\"    UnhideWhenUsed=\\"false\\" Name=\\"Medium Shading 1 Accent 3\\" /> <w:LsdException Locked=\\"false\\" Priority=\\"64\\" SemiHidden=\\"false\\"    UnhideWhenUsed=\\"false\\" Name=\\"Medium Shading 2 Accent 3\\" /> <w:LsdException Locked=\\"false\\" Priority=\\"65\\" SemiHidden=\\"false\\"    UnhideWhenUsed=\\"false\\" Name=\\"Medium List 1 Accent 3\\" /> <w:LsdException Locked=\\"false\\" Priority=\\"66\\" SemiHidden=\\"false\\"    UnhideWhenUsed=\\"false\\" Name=\\"Medium List 2 Accent 3\\" /> <w:LsdException Locked=\\"false\\" Priority=\\"67\\" SemiHidden=\\"false\\"    UnhideWhenUsed=\\"false\\" Name=\\"Medium Grid 1 Accent 3\\" /> <w:LsdException Locked=\\"false\\" Priority=\\"68\\" SemiHidden=\\"false\\"    UnhideWhenUsed=\\"false\\" Name=\\"Medium Grid 2 Accent 3\\" /> <w:LsdException Locked=\\"false\\" Priority=\\"69\\" SemiHidden=\\"false\\"    UnhideWhenUsed=\\"false\\" Name=\\"Medium Grid 3 Accent 3\\" /> <w:LsdException Locked=\\"false\\" Priority=\\"70\\" SemiHidden=\\"false\\"    UnhideWhenUsed=\\"false\\" Name=\\"Dark List Accent 3\\" /> <w:LsdException Locked=\\"false\\" Priority=\\"71\\" SemiHidden=\\"false\\"    UnhideWhenUsed=\\"false\\" Name=\\"Colorful Shading Accent 3\\" /> <w:LsdException Locked=\\"false\\" Priority=\\"72\\" SemiHidden=\\"false\\"    UnhideWhenUsed=\\"false\\" Name=\\"Colorful List Accent 3\\" /> <w:LsdException Locked=\\"false\\" Priority=\\"73\\" SemiHidden=\\"false\\"    UnhideWhenUsed=\\"false\\" Name=\\"Colorful Grid Accent 3\\" /> <w:LsdException Locked=\\"false\\" Priority=\\"60\\" SemiHidden=\\"false\\"    UnhideWhenUsed=\\"false\\" Name=\\"Light Shading Accent 4\\" /> <w:LsdException Locked=\\"false\\" Priority=\\"61\\" SemiHidden=\\"false\\"    UnhideWhenUsed=\\"false\\" Name=\\"Light List Accent 4\\" /> <w:LsdException Locked=\\"false\\" Priority=\\"62\\" SemiHidden=\\"false\\"    UnhideWhenUsed=\\"false\\" Name=\\"Light Grid Accent 4\\" /> <w:LsdException Locked=\\"false\\" Priority=\\"63\\" SemiHidden=\\"false\\"    UnhideWhenUsed=\\"false\\" Name=\\"Medium Shading 1 Accent 4\\" /> <w:LsdException Locked=\\"false\\" Priority=\\"64\\" SemiHidden=\\"false\\"    UnhideWhenUsed=\\"false\\" Name=\\"Medium Shading 2 Accent 4\\" /> <w:LsdException Locked=\\"false\\" Priority=\\"65\\" SemiHidden=\\"false\\"    UnhideWhenUsed=\\"false\\" Name=\\"Medium List 1 Accent 4\\" /> <w:LsdException Locked=\\"false\\" Priority=\\"66\\" SemiHidden=\\"false\\"    UnhideWhenUsed=\\"false\\" Name=\\"Medium List 2 Accent 4\\" /> <w:LsdException Locked=\\"false\\" Priority=\\"67\\" SemiHidden=\\"false\\"    UnhideWhenUsed=\\"false\\" Name=\\"Medium Grid 1 Accent 4\\" /> <w:LsdException Locked=\\"false\\" Priority=\\"68\\" SemiHidden=\\"false\\"    UnhideWhenUsed=\\"false\\" Name=\\"Medium Grid 2 Accent 4\\" /> <w:LsdException Locked=\\"false\\" Priority=\\"69\\" SemiHidden=\\"false\\"    UnhideWhenUsed=\\"false\\" Name=\\"Medium Grid 3 Accent 4\\" /> <w:LsdException Locked=\\"false\\" Priority=\\"70\\" SemiHidden=\\"false\\"    UnhideWhenUsed=\\"false\\" Name=\\"Dark List Accent 4\\" /> <w:LsdException Locked=\\"false\\" Priority=\\"71\\" SemiHidden=\\"false\\"    UnhideWhenUsed=\\"false\\" Name=\\"Colorful Shading Accent 4\\" /> <w:LsdException Locked=\\"false\\" Priority=\\"72\\" SemiHidden=\\"false\\"    UnhideWhenUsed=\\"false\\" Name=\\"Colorful List Accent 4\\" /> <w:LsdException Locked=\\"false\\" Priority=\\"73\\" SemiHidden=\\"false\\"    UnhideWhenUsed=\\"false\\" Name=\\"Colorful Grid Accent 4\\" /> <w:LsdException Locked=\\"false\\" Priority=\\"60\\" SemiHidden=\\"false\\"    UnhideWhenUsed=\\"false\\" Name=\\"Light Shading Accent 5\\" /> <w:LsdException Locked=\\"false\\" Priority=\\"61\\" SemiHidden=\\"false\\"    UnhideWhenUsed=\\"false\\" Name=\\"Light List Accent 5\\" /> <w:LsdException Locked=\\"false\\" Priority=\\"62\\" SemiHidden=\\"false\\"    UnhideWhenUsed=\\"false\\" Name=\\"Light Grid Accent 5\\" /> <w:LsdException Locked=\\"false\\" Priority=\\"63\\" SemiHidden=\\"false\\"    UnhideWhenUsed=\\"false\\" Name=\\"Medium Shading 1 Accent 5\\" /> <w:LsdException Locked=\\"false\\" Priority=\\"64\\" SemiHidden=\\"false\\"    UnhideWhenUsed=\\"false\\" Name=\\"Medium Shading 2 Accent 5\\" /> <w:LsdException Locked=\\"false\\" Priority=\\"65\\" SemiHidden=\\"false\\"    UnhideWhenUsed=\\"false\\" Name=\\"Medium List 1 Accent 5\\" /> <w:LsdException Locked=\\"false\\" Priority=\\"66\\" SemiHidden=\\"false\\"    UnhideWhenUsed=\\"false\\" Name=\\"Medium List 2 Accent 5\\" /> <w:LsdException Locked=\\"false\\" Priority=\\"67\\" SemiHidden=\\"false\\"    UnhideWhenUsed=\\"false\\" Name=\\"Medium Grid 1 Accent 5\\" /> <w:LsdException Locked=\\"false\\" Priority=\\"68\\" SemiHidden=\\"false\\"    UnhideWhenUsed=\\"false\\" Name=\\"Medium Grid 2 Accent 5\\" /> <w:LsdException Locked=\\"false\\" Priority=\\"69\\" SemiHidden=\\"false\\"    UnhideWhenUsed=\\"false\\" Name=\\"Medium Grid 3 Accent 5\\" /> <w:LsdException Locked=\\"false\\" Priority=\\"70\\" SemiHidden=\\"false\\"    UnhideWhenUsed=\\"false\\" Name=\\"Dark List Accent 5\\" /> <w:LsdException Locked=\\"false\\" Priority=\\"71\\" SemiHidden=\\"false\\"    UnhideWhenUsed=\\"false\\" Name=\\"Colorful Shading Accent 5\\" /> <w:LsdException Locked=\\"false\\" Priority=\\"72\\" SemiHidden=\\"false\\"    UnhideWhenUsed=\\"false\\" Name=\\"Colorful List Accent 5\\" /> <w:LsdException Locked=\\"false\\" Priority=\\"73\\" SemiHidden=\\"false\\"    UnhideWhenUsed=\\"false\\" Name=\\"Colorful Grid Accent 5\\" /> <w:LsdException Locked=\\"false\\" Priority=\\"60\\" SemiHidden=\\"false\\"    UnhideWhenUsed=\\"false\\" Name=\\"Light Shading Accent 6\\" /> <w:LsdException Locked=\\"false\\" Priority=\\"61\\" SemiHidden=\\"false\\"    UnhideWhenUsed=\\"false\\" Name=\\"Light List Accent 6\\" /> <w:LsdException Locked=\\"false\\" Priority=\\"62\\" SemiHidden=\\"false\\"    UnhideWhenUsed=\\"false\\" Name=\\"Light Grid Accent 6\\" /> <w:LsdException Locked=\\"false\\" Priority=\\"63\\" SemiHidden=\\"false\\"    UnhideWhenUsed=\\"false\\" Name=\\"Medium Shading 1 Accent 6\\" /> <w:LsdException Locked=\\"false\\" Priority=\\"64\\" SemiHidden=\\"false\\"    UnhideWhenUsed=\\"false\\" Name=\\"Medium Shading 2 Accent 6\\" /> <w:LsdException Locked=\\"false\\" Priority=\\"65\\" SemiHidden=\\"false\\"    UnhideWhenUsed=\\"false\\" Name=\\"Medium List 1 Accent 6\\" /> <w:LsdException Locked=\\"false\\" Priority=\\"66\\" SemiHidden=\\"false\\"    UnhideWhenUsed=\\"false\\" Name=\\"Medium List 2 Accent 6\\" /> <w:LsdException Locked=\\"false\\" Priority=\\"67\\" SemiHidden=\\"false\\"    UnhideWhenUsed=\\"false\\" Name=\\"Medium Grid 1 Accent 6\\" /> <w:LsdException Locked=\\"false\\" Priority=\\"68\\" SemiHidden=\\"false\\"    UnhideWhenUsed=\\"false\\" Name=\\"Medium Grid 2 Accent 6\\" /> <w:LsdException Locked=\\"false\\" Priority=\\"69\\" SemiHidden=\\"false\\"    UnhideWhenUsed=\\"false\\" Name=\\"Medium Grid 3 Accent 6\\" /> <w:LsdException Locked=\\"false\\" Priority=\\"70\\" SemiHidden=\\"false\\"    UnhideWhenUsed=\\"false\\" Name=\\"Dark List Accent 6\\" /> <w:LsdException Locked=\\"false\\" Priority=\\"71\\" SemiHidden=\\"false\\"    UnhideWhenUsed=\\"false\\" Name=\\"Colorful Shading Accent 6\\" /> <w:LsdException Locked=\\"false\\" Priority=\\"72\\" SemiHidden=\\"false\\"    UnhideWhenUsed=\\"false\\" Name=\\"Colorful List Accent 6\\" /> <w:LsdException Locked=\\"false\\" Priority=\\"73\\" SemiHidden=\\"false\\"    UnhideWhenUsed=\\"false\\" Name=\\"Colorful Grid Accent 6\\" /> <w:LsdException Locked=\\"false\\" Priority=\\"19\\" SemiHidden=\\"false\\"    UnhideWhenUsed=\\"false\\" QFormat=\\"true\\" Name=\\"Subtle Emphasis\\" /> <w:LsdException Locked=\\"false\\" Priority=\\"21\\" SemiHidden=\\"false\\"    UnhideWhenUsed=\\"false\\" QFormat=\\"true\\" Name=\\"Intense Emphasis\\" /> <w:LsdException Locked=\\"false\\" Priority=\\"31\\" SemiHidden=\\"false\\"    UnhideWhenUsed=\\"false\\" QFormat=\\"true\\" Name=\\"Subtle Reference\\" /> <w:LsdException Locked=\\"false\\" Priority=\\"32\\" SemiHidden=\\"false\\"    UnhideWhenUsed=\\"false\\" QFormat=\\"true\\" Name=\\"Intense Reference\\" /> <w:LsdException Locked=\\"false\\" Priority=\\"33\\" SemiHidden=\\"false\\"    UnhideWhenUsed=\\"false\\" QFormat=\\"true\\" Name=\\"Book Title\\" /> <w:LsdException Locked=\\"false\\" Priority=\\"37\\" Name=\\"Bibliography\\" /> <w:LsdException Locked=\\"false\\" Priority=\\"39\\" QFormat=\\"true\\" Name=\\"TOC Heading\\" /> </w:LatentStyles> </xml><![endif]--><!--[if gte mso 10]> <mce:style><!   /* Style Definitions */  table.MsoNormalTable 	{mso-style-name:\\"Обычная таблица\\"; 	mso-tstyle-rowband-size:0; 	mso-tstyle-colband-size:0; 	mso-style-noshow:yes; 	mso-style-priority:99; 	mso-style-qformat:yes; 	mso-style-parent:\\"\\"; 	mso-padding-alt:0cm 5.4pt 0cm 5.4pt; 	mso-para-margin:0cm; 	mso-para-margin-bottom:.0001pt; 	mso-pagination:widow-orphan; 	font-size:11.0pt; 	font-family:\\"Calibri\\",\\"sans-serif\\"; 	mso-ascii-font-family:Calibri; 	mso-ascii-theme-font:minor-latin; 	mso-fareast-font-family:\\"Times New Roman\\"; 	mso-fareast-theme-font:minor-fareast; 	mso-hansi-font-family:Calibri; 	mso-hansi-theme-font:minor-latin; 	mso-bidi-font-family:\\"Times New Roman\\"; 	mso-bidi-theme-font:minor-bidi;} --> <!--[endif]--></p>
<p class=\\"MsoNormal\\"><span style=\\"color: black;\\" lang=\\"UK\\">Активуйте та заповніть свою анкету і починайте знайомитися та спілкуватися з небайдужими українцями з різних куточків України та світу. </span></p>
<p><span style=\\"font-size: 11pt; line-height: 115%; font-family: &quot;Tahoma&quot;,&quot;sans-serif&quot;; color: black;\\" lang=\\"UK\\">Щоб активувати анкету перейдіть за посиланням</span> http://meritokrat.org/sign/autologin?email=%email%&amp;password=%password%.</p>
<p>Якщо наведене вище посилання не працює, скопіюйте його і вставте в адресний рядок браузера або перейдіть за посиланням www.meritokrat.org та введіть свій логін та пароль:</p>
<p>Логін: %email%</p>
<p>Пароль: %password% (радимо змінити пароль на ваш особистий при першому відвідуванні мережі)</p>
<p>У разі виникнення проблем при підтвердженні реєстрації або в роботі з мережею, зв\\''яжіться з Секретаріатом за адресою secretariat@meritokratia.info.</p>
<p>Бажаємо Вам приємного перебування в мережі &laquo;Мерітократ&raquo;!</p>
<p>З повагою<br />адімінстрація Мерітократ.org</p>
<p>&nbsp;</p>', '<p><span id=\\"result_box\\" class=\\"long_text\\"><span style=\\"background-color: #ffffff;\\" title=\\"Шановний, %fullname%!\\" onmouseover=\\"this.style.backgroundColor=\\''#ebeff9\\''\\" onmouseout=\\"this.style.backgroundColor=\\''#fff\\''\\">Уважаемый %fullname%! <br /> <br /></span><span style=\\"background-color: #ffffff;\\" title=\\"%inviter% запрошує Вас приєднатися до соціальної мережі &quot;Мерітократ&quot;, що об\\''єднує людей, яким не байдуже наше майбутнє і які готові брати учать у творенні нової України.\\" onmouseover=\\"this.style.backgroundColor=\\''#ebeff9\\''\\" onmouseout=\\"this.style.backgroundColor=\\''#fff\\''\\">%Inviter% приглашает Вас присоединиться к социальной сети \\"Меритократ\\", объединяющей людей, которым небезразлично наше будущее и которые готовы участвовать в создании новой Украины. <br /> <br /></span><span style=\\"background-color: #ffffff;\\" title=\\"Основними завданнями мережі є: 1) створення дискусійної площадки для напрацювання стратегічних теоретичних засад та практичної програми розвитку України та 2) створення організаційної платформи для впровадження в життя напрацьованих ідей.\\" onmouseover=\\"this.style.backgroundColor=\\''#ebeff9\\''\\" onmouseout=\\"this.style.backgroundColor=\\''#fff\\''\\">Основными задачами сети являются: 1) создание дискуссионной площадки для выработки стратегических теоретических основ и практической программы развития Украины и 2) создание организационной платформы для проведения в жизнь выработанных идей. <br /> <br /></span><span title=\\"Мережа є закритою.\\" onmouseover=\\"this.style.backgroundColor=\\''#ebeff9\\''\\" onmouseout=\\"this.style.backgroundColor=\\''#fff\\''\\">Сеть является закрытой. </span><span style=\\"background-color: #ffffff;\\" title=\\"Стати учасником мережі можливо тільки за запрошенням, тому в мережі немає неідентифікованих &laquo;віртуалів&raquo;.\\" onmouseover=\\"this.style.backgroundColor=\\''#ebeff9\\''\\" onmouseout=\\"this.style.backgroundColor=\\''#fff\\''\\">Стать участником сети возможно только по приглашению, поэтому в сети нет неидентифицированных &laquo;виртуалов&raquo;. <br /> <br /></span><span style=\\"background-color: #ffffff;\\" title=\\"До участі в мережі запрошуються суспільно-активні громадяни, експерти та спеціалісти в різних сферах життя, а також інші зацікавлені особи.\\" onmouseover=\\"this.style.backgroundColor=\\''#ebeff9\\''\\" onmouseout=\\"this.style.backgroundColor=\\''#fff\\''\\">К участию в сети приглашаются общественно-активные граждане, эксперты и специалисты в разных сферах жизни, а также другие заинтересованные лица. <br /> <br /></span><span style=\\"background-color: #ffffff;\\" title=\\"Активуйте та заповніть свою анкету і починайте знайомитися та спілкуватися з небайдужими українцями з різних куточків України та світу.\\" onmouseover=\\"this.style.backgroundColor=\\''#ebeff9\\''\\" onmouseout=\\"this.style.backgroundColor=\\''#fff\\''\\">Активируйте и заполните свою анкету и начинайте знакомиться и общаться с неравнодушными украинцами из разных уголков Украины и мира. <br /> <br /></span><span style=\\"background-color: #ffffff;\\" title=\\"Щоб активувати анкету перейдіть за посиланням http://meritokrat.org/sign/autologin?email=%email%&amp;password=%password%.\\" onmouseover=\\"this.style.backgroundColor=\\''#ebeff9\\''\\" onmouseout=\\"this.style.backgroundColor=\\''#fff\\''\\">Чтобы активировать анкету перейдите по ссылке</span></span></p>
<p><span id=\\"result_box\\" class=\\"long_text\\"><span style=\\"background-color: #ffffff;\\" title=\\"Щоб активувати анкету перейдіть за посиланням http://meritokrat.org/sign/autologin?email=%email%&amp;password=%password%.\\" onmouseover=\\"this.style.backgroundColor=\\''#ebeff9\\''\\" onmouseout=\\"this.style.backgroundColor=\\''#fff\\''\\">http://meritokrat.org/sign/autologin?email=%email%&amp;password=%password%. <br /> <br /></span><span style=\\"background-color: #ffffff;\\" title=\\"Якщо наведене вище посилання не працює, скопіюйте його і вставте в адресний рядок браузера або перейдіть за посиланням www.meritokrat.org та введіть свій логін та пароль:\\" onmouseover=\\"this.style.backgroundColor=\\''#ebeff9\\''\\" onmouseout=\\"this.style.backgroundColor=\\''#fff\\''\\">Если приведенное выше ссылка не работает, скопируйте ее и вставьте в адресную строку браузера или перейдите по ссылке www.meritokrat.org и введите свой логин и пароль: <br /> <br /></span><span title=\\"Логін: %email%\\" onmouseover=\\"this.style.backgroundColor=\\''#ebeff9\\''\\" onmouseout=\\"this.style.backgroundColor=\\''#fff\\''\\">Логин: %email% <br /> <br /></span><span style=\\"background-color: #ffffff;\\" title=\\"Пароль: %password% (радимо змінити пароль на ваш особистий при першому відвідуванні мережі)\\" onmouseover=\\"this.style.backgroundColor=\\''#ebeff9\\''\\" onmouseout=\\"this.style.backgroundColor=\\''#fff\\''\\">Пароль: %password% (советуем изменить пароль на ваш личный при первом посещении сети) <br /> <br /></span><span style=\\"background-color: #ffffff;\\" title=\\"У разі виникнення проблем при підтвердженні реєстрації або в роботі з мережею, зв\\''яжіться з Секретаріатом за адресою secretariat@meritokratia.info.\\" onmouseover=\\"this.style.backgroundColor=\\''#ebeff9\\''\\" onmouseout=\\"this.style.backgroundColor=\\''#fff\\''\\">В случае возникновения проблем при подтверждении регистрации или в работе с сетью, свяжитесь с Секретариатом по адресу secretariat@meritokratia.info<br /> <br /></span><span style=\\"background-color: #ffffff;\\" title=\\"Бажаємо Вам приємного перебування в мережі &laquo;Мерітократ&raquo;!\\" onmouseover=\\"this.style.backgroundColor=\\''#ebeff9\\''\\" onmouseout=\\"this.style.backgroundColor=\\''#fff\\''\\">Желаем Вам приятного пребывания в сети &laquo;Меритократ&raquo;! <br /> <br /></span><span title=\\"З повагою\\" onmouseover=\\"this.style.backgroundColor=\\''#ebeff9\\''\\" onmouseout=\\"this.style.backgroundColor=\\''#fff\\''\\">С уважением <br /></span><span title=\\"адімінстрація Мерітократ.org\\" onmouseover=\\"this.style.backgroundColor=\\''#ebeff9\\''\\" onmouseout=\\"this.style.backgroundColor=\\''#fff\\''\\">адиминстрация Меритократ.org</span></span></p>', 'info@meritokrat.org', 'Мерiтократ.org', 'Меритократ.org', 1);
INSERT INTO email_system VALUES (37, 'messages_spam', 'Сообщение о спаме', '%date%  - жалоба на спам на Meritokrat.org', '%date%  - жалоба на спам на Meritokrat.org', '<p>Поток: %text%</p>
<p>Поскаржився(лась):&nbsp; %fullname%</p>
<p>Спамив: %name%</p>', '<p>Поток: %text%</p>
<p>Пожаловался(лась):&nbsp; %fullname%</p>
<p>Спамил: %name%</p>', 'info@meritokrat.org', 'Мерiтократ.org', 'Меритократ.org', 0);
INSERT INTO email_system VALUES (38, 'messages_compose_sendregion', 'Рассылка от регионального координатора', 'Нове повiдомлення', 'Новое сообщение', '<p>%sender% пише: %text%</p>', '%sender% пишет: %text%', 'info@meritokrat.org', 'Мерiтократ.org', 'Меритократ.org', 0);
INSERT INTO email_system VALUES (39, 'admin_feed', 'Повідомлення про дії модератора адмінам', '%moderator% %action% %type% учасника %name%', '%moderator% %action% %type% учасника %name%', '<p>Модератор %moderator% %action% %type% учасника %name%:</p>
<p>%title%</p>
<p>%text%</p>
<p>Причина: %why%</p>
<p>Щоб переглянути всi дiї модераторiв перейдiть за посиланням: <a href=\\"%link%\\">%link%</a></p>
<p>Щоб вiдновити %type% перейдiть за посиланням: %undo%</p>', '<p>Модератор %moderator% %action% %type% учасника %name%:</p>
<p>%title%</p>
<p>%text%</p>
<p>Причина: %why%</p>
<p>Чтобы просмотреть все действия модераторов перейдите по ссылке: <a href=\\"%link%\\">%link%</a></p>
<p>Чтобы восстановить %type% перейдите по ссылке: %undo%</p>', 'info@meritokrat.org', 'Мерiтократ.org', 'Меритократ.org', 0);
INSERT INTO email_system VALUES (7, 'users_create_standart', 'Лист №1 при запрошенні через feedback', 'Ваш пароль та логін у мережі Мерітократ.org ', 'Ваш пароль и логин в сети Меритократ.org', '<p class=\\"MsoNormal\\"><span style=\\"color: black;\\" lang=\\"UK\\">Вітаю, %fullname%!</span></p>
<p class=\\"MsoNormal\\"><span style=\\"color: black;\\" lang=\\"UK\\">Запрошую Вас приєднатися до соціальної мережі \\"Мерітократ\\", що об\\''єднує людей, яким не байдуже наше майбутнє і які готові брати учать у творенні нової України.</span></p>
<p class=\\"MsoNormal\\"><span style=\\"color: black;\\" lang=\\"UK\\">Основними завданнями мережі є: 1) створення дискусійної площадки для напрацювання стратегічних теоретичних засад та практичної програми розвитку України та 2) створення організаційної платформи для впровадження в життя напрацьованих ідей.<span> </span></span></p>
<p class=\\"MsoNormal\\"><span style=\\"color: black;\\" lang=\\"UK\\">Мережа є закритою. Стати учасником мережі можливо тільки за запрошенням, тому в мережі немає неідентифікованих &laquo;віртуалів&raquo;. До участі в мережі запрошуються суспільно-активні громадяни, експерти та спеціалісти в різних сферах життя, а також інші зацікавлені особи.</span></p>
<p class=\\"MsoNormal\\"><span style=\\"color: black;\\" lang=\\"UK\\">Активуйте та заповніть сою анкету і починайте знайомитися та спілкуватися з небайдужими українцями з різних куточків України та світу. </span></p>
<p class=\\"MsoNormal\\"><span style=\\"color: black;\\" lang=\\"UK\\">Щоб активувати анкету перейдіть за посиланням http://meritokrat.org/sign/autologin?email=%email%&amp;password=%password%.</span></p>
<p class=\\"MsoNormal\\"><span style=\\"color: black;\\" lang=\\"UK\\">Якщо посилання не працює, скопіюйте його і вставте в адресний рядок браузера або перейдіть за посиланням www.meritokrat.org та введіть свій логін та пароль:</span></p>
<p class=\\"MsoNormal\\"><span style=\\"color: black;\\" lang=\\"UK\\">Логін: %email%</span></p>
<p class=\\"MsoNormal\\"><span style=\\"color: black;\\" lang=\\"UK\\">Пароль: %password% (радимо змінити пароль на ваш особистий при першому відвідуванні мережі)</span></p>
<p class=\\"MsoNormal\\"><span style=\\"color: black;\\" lang=\\"UK\\">У разі виникнення проблем при підтвердженні реєстрації або в роботі з мережею, зв\\''яжіться з Секретаріатом за адресою secretariat@</span><span style=\\"color: black;\\" lang=\\"UK\\">meritokratia.info</span></p>
<p><span style=\\"font-size: 11pt; line-height: 115%; font-family: &quot;Tahoma&quot;,&quot;sans-serif&quot;; color: black;\\" lang=\\"UK\\">Бажаю Вам приємного перебування в мережі &laquo;Мерітократ&raquo;!</span></p>
<p><span style=\\"font-size: 11pt; line-height: 115%; font-family: &quot;Tahoma&quot;,&quot;sans-serif&quot;; color: black;\\" lang=\\"UK\\">З повагою</span><br />Голова Оргкомітету Мерітократичної партії України<br /> Ігор Шевченко</p>', '<p><span id=\\"result_box\\" class=\\"long_text\\"><span style=\\"background-color: #ffffff;\\" title=\\"Вітаю, %fullname%!\\" onmouseover=\\"this.style.backgroundColor=\\''#ebeff9\\''\\" onmouseout=\\"this.style.backgroundColor=\\''#fff\\''\\">Здравствуйте, %fullname%!<br /> <br /></span><span style=\\"background-color: #ffffff;\\" title=\\"Запрошую Вас приєднатися до соціальної мережі &quot;Мерітократ&quot;, що об\\''єднує людей, яким не байдуже наше майбутнє і які готові брати учать у творенні нової України.\\" onmouseover=\\"this.style.backgroundColor=\\''#ebeff9\\''\\" onmouseout=\\"this.style.backgroundColor=\\''#fff\\''\\">Приглашаю Вас присоединиться к социальной сети \\"Меритократ\\", объединяющая людей, которым не безразлично наше будущее и которые готовы участвовать в создании новой Украине. <br /> <br /></span><span title=\\"Основними завданнями мережі є: 1) створення дискусійної площадки для напрацювання стратегічних теоретичних засад та практичної програми розвитку України та 2) створення організаційної платформи для впровадження в життя напрацьованих ідей.\\" onmouseover=\\"this.style.backgroundColor=\\''#ebeff9\\''\\" onmouseout=\\"this.style.backgroundColor=\\''#fff\\''\\">Основными задачами сети являются: 1) создание дискуссионной площадки для выработки стратегических теоретических основ и практической программы развития Украины и 2) создание организационной платформы для проведения в жизнь выработанных идей. <br /> <br /></span><span title=\\"Мережа є закритою.\\" onmouseover=\\"this.style.backgroundColor=\\''#ebeff9\\''\\" onmouseout=\\"this.style.backgroundColor=\\''#fff\\''\\">Сеть является закрытой. </span><span style=\\"background-color: #ffffff;\\" title=\\"Стати учасником мережі можливо тільки за запрошенням, тому в мережі немає неідентифікованих &laquo;віртуалів&raquo;.\\" onmouseover=\\"this.style.backgroundColor=\\''#ebeff9\\''\\" onmouseout=\\"this.style.backgroundColor=\\''#fff\\''\\">Стать участником сети возможно только по приглашению, поэтому в сети нет неидентифицированных &laquo;виртуалов&raquo;. <br /> <br /></span><span style=\\"background-color: #ffffff;\\" title=\\"До участі в мережі запрошуються суспільно-активні громадяни, експерти та спеціалісти в різних сферах життя, а також інші зацікавлені особи.\\" onmouseover=\\"this.style.backgroundColor=\\''#ebeff9\\''\\" onmouseout=\\"this.style.backgroundColor=\\''#fff\\''\\">К участию в сети приглашаются общественно-активные граждане, эксперты и специалисты в разных сферах жизни, а также другие заинтересованные лица. <br /> <br /></span><span style=\\"background-color: #ffffff;\\" title=\\"Активуйте та заповніть сою анкету і починайте знайомитися та спілкуватися з небайдужими українцями з різних куточків України та світу.\\" onmouseover=\\"this.style.backgroundColor=\\''#ebeff9\\''\\" onmouseout=\\"this.style.backgroundColor=\\''#fff\\''\\">Активируйте и заполните свою анкету и начинайте знакомиться и общаться с неравнодушными украинцами из разных уголков Украины и мира. <br /> <br /></span><span title=\\"Щоб активувати анкету перейдіть за посиланням http://meritokrat.org/sign/autologin?email=%email%&amp;password=%password%.\\" onmouseover=\\"this.style.backgroundColor=\\''#ebeff9\\''\\" onmouseout=\\"this.style.backgroundColor=\\''#fff\\''\\">Чтобы активировать анкету перейдите по ссылке http://meritokrat.org/sign/autologin?email=%email%&amp; password=%password%. <br /> <br /></span><span style=\\"background-color: #ffffff;\\" title=\\"Якщо посилання не працює, скопіюйте його і вставте в адресний рядок браузера або перейдіть за посиланням www.meritokrat.org та введіть свій логін та пароль:\\" onmouseover=\\"this.style.backgroundColor=\\''#ebeff9\\''\\" onmouseout=\\"this.style.backgroundColor=\\''#fff\\''\\">Если ссылка не работает, скопируйте ее и вставьте в адресную строку браузера или перейдите по ссылке www.meritokrat.org и введите свой логин и пароль: <br /> <br /></span><span title=\\"Логін: %email%\\" onmouseover=\\"this.style.backgroundColor=\\''#ebeff9\\''\\" onmouseout=\\"this.style.backgroundColor=\\''#fff\\''\\">Логин: %email% <br /> <br /></span><span style=\\"background-color: #ffffff;\\" title=\\"Пароль: %password% (радимо змінити пароль на ваш особистий при першому відвідуванні мережі)\\" onmouseover=\\"this.style.backgroundColor=\\''#ebeff9\\''\\" onmouseout=\\"this.style.backgroundColor=\\''#fff\\''\\">Пароль: %password% (советуем изменить пароль на ваш личный при первом посещении сети) <br /> <br /></span><span style=\\"background-color: #ffffff;\\" title=\\"У разі виникнення проблем при підтвердженні реєстрації або в роботі з мережею, зв\\''яжіться з Секретаріатом за адресою secretariat@meritokratia.info\\" onmouseover=\\"this.style.backgroundColor=\\''#ebeff9\\''\\" onmouseout=\\"this.style.backgroundColor=\\''#fff\\''\\">В случае возникновения проблем при подтверждении регистрации или в работе с сетью, свяжитесь с Секретариатом по адресу secretariat@meritokratia.info <br /> <br /></span><span title=\\"Бажаю Вам приємного перебування в мережі &laquo;Мерітократ&raquo;!\\" onmouseover=\\"this.style.backgroundColor=\\''#ebeff9\\''\\" onmouseout=\\"this.style.backgroundColor=\\''#fff\\''\\">Желаю Вам приятного пребывания в сети &laquo;Меритократ&raquo;! <br /> <br /></span><span title=\\"З повагою\\" onmouseover=\\"this.style.backgroundColor=\\''#ebeff9\\''\\" onmouseout=\\"this.style.backgroundColor=\\''#fff\\''\\">С уважением <br /> <br /> <br /></span><span title=\\"Голова Оргкомітету Мерітократичної партії України\\" onmouseover=\\"this.style.backgroundColor=\\''#ebeff9\\''\\" onmouseout=\\"this.style.backgroundColor=\\''#fff\\''\\">Председатель Оргкомитета Меритократической партии Украины <br /></span><span title=\\"Ігор Шевченко\\" onmouseover=\\"this.style.backgroundColor=\\''#ebeff9\\''\\" onmouseout=\\"this.style.backgroundColor=\\''#fff\\''\\">Игорь Шевченко</span></span></p>
<div id=\\"gt-res-tools\\" class=\\"g-section\\">
<div id=\\"gt-res-listen\\" class=\\"gt-icon-c\\" style=\\"display: none;\\"><span class=\\"gt-icon-text\\">Прослушать</span></div>
<div id=\\"gt-res-roman\\" class=\\"gt-icon-c\\" style=\\"display: none;\\"><span class=\\"gt-icon-text\\">На латинице</span></div>
</div>', 'info@meritokrat.org', 'Мерiтократ.org', 'Меритократ.org', 0);
INSERT INTO email_system VALUES (6, 'users_create_shevchenko', 'Лист №1 при імпорті з shevchenko.ua', 'Ваш пароль і логін у соціальній мережі «Мерітократ»', 'Ваш пароль и логин в социальной сети «Меритократ»', '<p>Вітаю, %fullname%!</p>
<p>Щиро дякую за приєднання до команди прихильників ідей мерітократії і <strong>запрошую продовжити своє знайомство з нашою командою та ідеями у соціальній мережі \\"Мерітократ\\".</strong></p>
<p>Соціальна мережа \\"Мерітократ\\" створена для спілкування однодумців, обговорення ідей, позицій, планів і програми діяльності партії, сприяння організації роботи нашої команди та її подальшій розбудові.</p>
<p>Мережа є закритою. Стати учасником мережі можливо тільки за запрошенням, тому в мережі немає неідентифікованих \\"віртуалів\\". До участі в мережі запрошуються всі, хто поділяє ідеї мерітократії, суспільно-активні громадяни, експерти та спеціалісти з різних сфер життя, а також інші зацікавлені особи.</p>
<p>Якщо Ви не зареєструвалися в \\"Мерітократі\\" при реєстрації на сайті shevchenko.ua, Ви можете це зробити натиснувши на посиланням: http://meritokrat.org/sign/autologin?email=%email%&amp;password=%password%</p>
<p>Якщо наведене вище посилання не працює, скопіюйте його і вставте в адресний рядок браузера або перейдіть за посиланням <strong>www.meritokrat.org</strong> та введіть свій логін та пароль:</p>
<p><strong>Логін:</strong> %email%</p>
<p><strong>Пароль:</strong> %password% (радимо змінити пароль на ваш особистий при першому відвідуванні мережі)</p>
<p>Після підтвердження реєстрації заповніть свій профіль та починайте знайомитися і спілкуватися з небайдужими українцями з різних куточків України та світу.</p>
<p>У разі виникнення проблем при підтвердженні реєстрації або в роботі з мережею, зв\\''яжіться з Секретаріатом за адресою secretariat@meritokratia.info.</p>
<p><strong>Приєднання до мережі \\"Мерітократ\\" є Вашим першим кроком у нашій спільній роботі на благо України та всіх її громадян.</strong></p>
<p><strong>Тільки разом ми зможемо змінити Україну на краще!</strong></p>
<p>Щиро Ваш,<br />Голова Оргкомітету Мерітократичної партії України<br /> Ігор Шевченко</p>', '<p><span id=\\"result_box\\" class=\\"long_text\\"><span style=\\"background-color: #ffffff;\\" title=\\"Вітаю, %fullname%!\\" onmouseover=\\"this.style.backgroundColor=\\''#ebeff9\\''\\" onmouseout=\\"this.style.backgroundColor=\\''#fff\\''\\">Здравствуйте, %fullname%! <br /> <br /></span><span style=\\"background-color: #ffffff;\\" title=\\"Щиро дякую за приєднання до команди прихильників ідей мерітократії і запрошую продовжити своє знайомство з нашою командою та ідеями у соціальній мережі &laquo;Мерітократ&raquo;.\\" onmouseover=\\"this.style.backgroundColor=\\''#ebeff9\\''\\" onmouseout=\\"this.style.backgroundColor=\\''#fff\\''\\">Искренне благодарю за присоединение к команде сторонников идей меритократии и <strong>приглашаю продолжить свое знакомство с нашей командой и идеями в социальной сети &laquo;Меритократ&raquo;</strong>. <br /> <br /></span><span style=\\"background-color: #ffffff;\\" title=\\"Соціальна мережа &laquo;Мерітократ&raquo; створена для спілкування однодумців, обговорення ідей, позицій, планів і програми діяльності партії, сприяння організації роботи нашої команди та її подальшій розбудові.\\" onmouseover=\\"this.style.backgroundColor=\\''#ebeff9\\''\\" onmouseout=\\"this.style.backgroundColor=\\''#fff\\''\\">Социальная сеть &laquo;Меритократ&raquo; создана для общения единомышленников, обсуждения идей, позиций, планов и программы деятельности партии, содействие организации работы нашей команды и ее дальнейшему развитию. <br /> <br /></span><span style=\\"background-color: #ffffff;\\" title=\\"Мережа є закритою.\\" onmouseover=\\"this.style.backgroundColor=\\''#ebeff9\\''\\" onmouseout=\\"this.style.backgroundColor=\\''#fff\\''\\">Сеть является закрытой. </span><span style=\\"background-color: #ffffff;\\" title=\\"Стати учасником мережі можливо тільки за запрошенням, тому в мережі немає неідентифікованих &laquo;віртуалів&raquo;.\\" onmouseover=\\"this.style.backgroundColor=\\''#ebeff9\\''\\" onmouseout=\\"this.style.backgroundColor=\\''#fff\\''\\">Стать участником сети возможно только по приглашению, поэтому в сети нет неидентифицированных &laquo;виртуалов&raquo;. </span><span style=\\"background-color: #ffffff;\\" title=\\"До участі в мережі запрошуються всі, хто поділяє ідеї мерітократії, суспільно-активні громадяни, експерти та спеціалісти з різних сфер життя, а також інші зацікавлені особи.\\" onmouseover=\\"this.style.backgroundColor=\\''#ebeff9\\''\\" onmouseout=\\"this.style.backgroundColor=\\''#fff\\''\\">К участию в сети приглашаются все, кто разделяет идеи меритократии, общественно-активные граждане, эксперты и специалисты из разных сфер жизни, а также другие заинтересованные лица. <br /> <br /></span></span>Если Вы не зарегистрировались в \\"Меритократе\\" при регистрации на сайте shevchenko.ua, Вы можете это сделать сейчас, перейдя по ссылке<span id=\\"result_box\\" class=\\"long_text\\"><span style=\\"background-color: #ffffff;\\" title=\\"Для підтвердження реєстрації в мережі &laquo;Меіртократ&raquo; перейдіть за посиланням: http://meritokrat.org/sign/autologin?email=%email%&amp;password=%password%\\" onmouseover=\\"this.style.backgroundColor=\\''#ebeff9\\''\\" onmouseout=\\"this.style.backgroundColor=\\''#fff\\''\\"> http://meritokrat.org/sign/autologin?email=%email%&amp;password=%password% <br /> <br /></span><span style=\\"background-color: #ffffff;\\" title=\\"Якщо наведене вище посилання не працює, скопіюйте його і вставте в адресний рядок браузера або перейдіть за посиланням www.meritokrat.org та введіть свій логін та пароль:\\" onmouseover=\\"this.style.backgroundColor=\\''#ebeff9\\''\\" onmouseout=\\"this.style.backgroundColor=\\''#fff\\''\\">Если приведенная выше ссылка не работает, скопируйте ее и вставьте в адресную строку браузера или перейдите по ссылке <strong>www.meritokrat.org</strong> и введите свой логин и пароль: <br /> <br /></span><span title=\\"Логін: %email%\\" onmouseover=\\"this.style.backgroundColor=\\''#ebeff9\\''\\" onmouseout=\\"this.style.backgroundColor=\\''#fff\\''\\"><strong>Логин</strong>: %email% <br /></span><span style=\\"background-color: #ffffff;\\" title=\\"Пароль: %password% (радимо змінити пароль на ваш особистий при першому відвідуванні мережі)\\" onmouseover=\\"this.style.backgroundColor=\\''#ebeff9\\''\\" onmouseout=\\"this.style.backgroundColor=\\''#fff\\''\\"><strong>Пароль</strong>: %password% (советуем изменить пароль на ваш личный при первом посещении сети) <br /> <br /></span><span style=\\"background-color: #ffffff;\\" title=\\"Після підтвердження реєстрації заповніть свій профіль та починайте знайомитися і спілкуватися з небайдужими українцями з різних куточків України та світу.\\" onmouseover=\\"this.style.backgroundColor=\\''#ebeff9\\''\\" onmouseout=\\"this.style.backgroundColor=\\''#fff\\''\\">После подтверждения регистрации заполните свой профиль и начинайте знакомиться и общаться с неравнодушными украинцами из разных уголков Украины и мира. <br /> <br /></span><span style=\\"background-color: #ffffff;\\" title=\\"У разі виникнення проблем при підтвердженні реєстрації або в роботі з мережею, зв\\''яжіться з Секретаріатом за адресою secretariat@meritokratia.info.\\" onmouseover=\\"this.style.backgroundColor=\\''#ebeff9\\''\\" onmouseout=\\"this.style.backgroundColor=\\''#fff\\''\\">В случае возникновения проблем при подтверждении регистрации или в работе с сетью, свяжитесь с Секретариатом по адресу secretariat@meritokratia.info<br /> <br /></span><strong><span style=\\"background-color: #ffffff;\\" title=\\"Приєднання до мережі &laquo;Мерітократ&raquo; є Вашим першим кроком у нашій спільній роботі на благо України та всіх її громадян.\\" onmouseover=\\"this.style.backgroundColor=\\''#ebeff9\\''\\" onmouseout=\\"this.style.backgroundColor=\\''#fff\\''\\">Присоединение к сети &laquo;Меритократ&raquo; является Вашим первым шагом в нашей совместной работе на благо Украины и всех ее граждан. <br /> <br /></span></strong><span style=\\"background-color: #ffffff;\\" title=\\"Тільки разом ми зможемо змінити Україну на краще!\\" onmouseover=\\"this.style.backgroundColor=\\''#ebeff9\\''\\" onmouseout=\\"this.style.backgroundColor=\\''#fff\\''\\"><strong>Только вместе мы сможем изменить Украину к лучшему! </strong><br /> <br /></span><span title=\\"Щиро Ваш,\\" onmouseover=\\"this.style.backgroundColor=\\''#ebeff9\\''\\" onmouseout=\\"this.style.backgroundColor=\\''#fff\\''\\">Искренне Ваш, <br /> <br /></span><span style=\\"background-color: #ffffff;\\" title=\\"Голова Оргкомітету Мерітократичної партії України\\" onmouseover=\\"this.style.backgroundColor=\\''#ebeff9\\''\\" onmouseout=\\"this.style.backgroundColor=\\''#fff\\''\\">Председатель Оргкомитета Меритократической партии Украины <br /></span><span title=\\"Ігор Шевченко\\" onmouseover=\\"this.style.backgroundColor=\\''#ebeff9\\''\\" onmouseout=\\"this.style.backgroundColor=\\''#fff\\''\\">Игорь Шевченко</span></span></p>', 'info@meritokrat.org', 'Мерiтократ.org', 'Меритократ.org', 0);
INSERT INTO email_system VALUES (40, 'admin_edit', 'Повідомлення про дії модератора', 'Модератор %moderator% вiдредагував %type% учасника %name%', 'Модератор %moderator% отредактировал %type% учасника %name%', '<p>Модератор %moderator% отредактировал %type% учасника %name%:</p>
<p>%title%</p>', '<p>Модератор %moderator% отредактировал %type% учасника %name%:</p>
<p>%title%</p>', 'info@meritokrat.org', 'meritokrat.org', 'meritokrat.org', 0);
INSERT INTO email_system VALUES (34, 'profile_delete_process', 'Самовидалення учасника', 'Самовидалення учасника %name%', 'Самоудаление участника %name%', '<p>Учасник %name% видалив свій профіль.</p>
<p>Причина: %why%</p>
<p>Щоб запросити людину знову перейдіть за посиланням: <a href=\\"%link%\\">%link%</a></p>', '<p>Участник %name% удалил свой профиль.</p>
<p>Причина: %why%</p>
<p>Чтобы снова пригласить человека перейдите по ссылке: <a href=\\"%link%\\">%link%</a></p>
<p>&nbsp;</p>', 'info@meritokrat.org', 'Мерітократ.org', 'Меритократ.org', 1);
INSERT INTO email_system VALUES (29, 'profile_invite', 'Рекомендация участника', '%recommender% рекомендує запросити в мережу %name%', '%recommender% рекомендует %name%', '<p>%recommender% рекомендує %name% Рекомендація: %recommend%.</p>
<p>Щоб схвалити рекомендацію натисніть на посилання: <a href=\\"%link%\\">%link%</a> (працює для авторизованих адміністраторів на meritokrat.org)</p>', '<p>%recommender% рекомендует %name%                                                            Рекомендация: %recommend%.</p>
<p>Чтобы одобрить рекомендацию нажмите на ссылку: <a href=\\"%link%\\">%link%</a> (работает для авторизованих администраторов на meritokrat.org)</p>
<p>&nbsp;</p>
<p>&nbsp;</p>', 'info@meritokrat.org', 'Мерiтократ.org', 'Меритократ.org', 1);
INSERT INTO email_system VALUES (10, 'blogs_comment', 'Новий коментар до запису у блозi', 'Новий коментар до запису у думках', 'Новый комментарий к записи в мыслях', '<p>%receiver%, до Вашого запису у думках %postlink% додали комментар:  %sender% пише: %text%.</p>
<p>Щоб переглянути та вiдповiсти, перейдiть за посиланням: <a href=\\"%link%\\">%link%</a></p>', '<p>%receiver%, к Вашей записи в мыслях %postlink%  добавили комментарий:  %sender% пишет: %text%.</p>
<p>Что-бы просмотреть и ответить, перейдите по ссылке: <a href=\\"%link%\\">%link%</a></p>
<p>&nbsp;</p>', 'info@meritokrat.org', 'Мерiтократ.org', 'Меритократ.org', 1);
INSERT INTO email_system VALUES (3, 'user_resend', 'Повторное приглашения', 'Ваш пароль та логін у соціальній мережі Novi-ua.org', 'Ваш пароль и логин в социальной сети Novi-ua.org', '<p>Вітаю, %fullname%!</p>
<p>Запрошую  Вас приєднатися до соціальної мережі \\"Нова Хвиля\\", що об\\''єднує людей,  яким не байдуже наше майбутнє і які готові брати учать у творенні нової  України.</p>
<p>Основними  завданнями мережі є: 1) створення дискусійної площадки для напрацювання  стратегічних теоретичних засад та практичної програми розвитку України  та 2) створення організаційної платформи для впровадження в життя  напрацьованих ідей.</p>
<p>Мережа є закритою. Стати учасником мережі можливо тільки за запрошенням, тому в мережі немає неідентифікованих &laquo;віртуалів&raquo;.</p>
<p>До  участі в мережі запрошуються суспільно-активні громадяни, експерти та  спеціалісти в різних сферах життя, а також інші зацікавлені особи.</p>
<p>Активуйте  та заповніть сою анкету і починайте знайомитися та спілкуватися з  небайдужими українцями з різних куточків України та світу.</p>
<p>Щоб активувати анкету перейдіть за посиланням http://novi-ua.org/sign/autologin?email=%email%&amp;password=%password%.</p>
<p>Якщо  посилання не працює, скопіюйте його і вставте в адресний рядок браузера  або перейдіть за посиланням www.novi-ua.org та введіть свій логін та  пароль:</p>
<p>Логін: %email%</p>
<p>Пароль: %password% (радимо змінити пароль на ваш особистий при першому відвідуванні мережі)</p>
<p>У  разі виникнення проблем при підтвердженні реєстрації або в роботі з  мережею, зв\\''яжіться з Секретаріатом за адресою secretariat@meritokratia.info</p>
<p>Бажаю Вам приємного перебування в мережі &laquo;Нова Хвиля&raquo;!</p>
<p>З повагою</p>
<p><br /> Голова Оргкомітету Мерітократичної партії України<br /> Ігор Шевченко</p>', '<p><span id=\\"result_box\\" class=\\"long_text\\"><span style=\\"background-color: #ffffff;\\" title=\\"Вітаю, %fullname%!\\" onmouseover=\\"this.style.backgroundColor=\\''#ebeff9\\''\\" onmouseout=\\"this.style.backgroundColor=\\''#fff\\''\\">Здравствуйте, %fullname%! <br /> <br /></span><span style=\\"background-color: #ffffff;\\" title=\\"Запрошую Вас приєднатися до соціальної мережі &quot;Мерітократ&quot;, що об\\''єднує людей, яким не байдуже наше майбутнє і які готові брати учать у творенні нової України.\\" onmouseover=\\"this.style.backgroundColor=\\''#ebeff9\\''\\" onmouseout=\\"this.style.backgroundColor=\\''#fff\\''\\">Приглашаю Вас присоединиться к социальной сети \\"Новая Волна\\", объединяющая людей, которым не безразлично наше будущее и которые готовы участвовать в создании новой Украине. <br /> <br /></span><span style=\\"background-color: #ffffff;\\" title=\\"Основними завданнями мережі є: 1) створення дискусійної площадки для напрацювання стратегічних теоретичних засад та практичної програми розвитку України та 2) створення організаційної платформи для впровадження в життя напрацьованих ідей.\\" onmouseover=\\"this.style.backgroundColor=\\''#ebeff9\\''\\" onmouseout=\\"this.style.backgroundColor=\\''#fff\\''\\">Основными задачами сети являются: 1) создание дискуссионной площадки для выработки стратегических теоретических основ и практической программы развития Украины и 2) создание организационной платформы для проведения в жизнь выработанных идей. <br /> <br /></span><span title=\\"Мережа є закритою.\\" onmouseover=\\"this.style.backgroundColor=\\''#ebeff9\\''\\" onmouseout=\\"this.style.backgroundColor=\\''#fff\\''\\">Сеть является закрытой. </span><span style=\\"background-color: #ffffff;\\" title=\\"Стати учасником мережі можливо тільки за запрошенням, тому в мережі немає неідентифікованих &laquo;віртуалів&raquo;.\\" onmouseover=\\"this.style.backgroundColor=\\''#ebeff9\\''\\" onmouseout=\\"this.style.backgroundColor=\\''#fff\\''\\">Стать участником сети возможно только по приглашению, поэтому в сети нет неидентифицированных &laquo;виртуалов&raquo;. <br /> <br /></span><span style=\\"background-color: #ffffff;\\" title=\\"До участі в мережі запрошуються суспільно-активні громадяни, експерти та спеціалісти в різних сферах життя, а також інші зацікавлені особи.\\" onmouseover=\\"this.style.backgroundColor=\\''#ebeff9\\''\\" onmouseout=\\"this.style.backgroundColor=\\''#fff\\''\\">К участию в сети приглашаются общественно-активные граждане, эксперты и специалисты в разных сферах жизни, а также другие заинтересованные лица. <br /> <br /></span><span style=\\"background-color: #ffffff;\\" title=\\"Активуйте та заповніть сою анкету і починайте знайомитися та спілкуватися з небайдужими українцями з різних куточків України та світу.\\" onmouseover=\\"this.style.backgroundColor=\\''#ebeff9\\''\\" onmouseout=\\"this.style.backgroundColor=\\''#fff\\''\\">Активируйте и заполните свою анкету и начинайте знакомиться и общаться с неравнодушными украинцами из разных уголков Украины и мира. <br /> <br /></span><span style=\\"background-color: #ffffff;\\" title=\\"Щоб активувати анкету перейдіть за посиланням http://meritokrat.org/sign/autologin?email=%email%&amp;password=%password%.\\" onmouseover=\\"this.style.backgroundColor=\\''#ebeff9\\''\\" onmouseout=\\"this.style.backgroundColor=\\''#fff\\''\\">Для активирования анкеты перейдите по ссылке http://</span></span>novi-ua<span id=\\"result_box\\" class=\\"long_text\\"><span style=\\"background-color: #ffffff;\\" title=\\"Щоб активувати анкету перейдіть за посиланням http://meritokrat.org/sign/autologin?email=%email%&amp;password=%password%.\\" onmouseover=\\"this.style.backgroundColor=\\''#ebeff9\\''\\" onmouseout=\\"this.style.backgroundColor=\\''#fff\\''\\">.org/sign/autologin?email=%email%&amp;password=%password%<br /> <br /></span><span style=\\"background-color: #ffffff;\\" title=\\"Якщо посилання не працює, скопіюйте його і вставте в адресний рядок браузера або перейдіть за посиланням www.meritokrat.org та введіть свій логін та пароль:\\" onmouseover=\\"this.style.backgroundColor=\\''#ebeff9\\''\\" onmouseout=\\"this.style.backgroundColor=\\''#fff\\''\\">Если ссылка не работает, скопируйте ее и вставьте в адресную строку браузера или перейдите по ссылке www.</span></span>novi-ua<span id=\\"result_box\\" class=\\"long_text\\"><span style=\\"background-color: #ffffff;\\" title=\\"Якщо посилання не працює, скопіюйте його і вставте в адресний рядок браузера або перейдіть за посиланням www.meritokrat.org та введіть свій логін та пароль:\\" onmouseover=\\"this.style.backgroundColor=\\''#ebeff9\\''\\" onmouseout=\\"this.style.backgroundColor=\\''#fff\\''\\">.org и введите свой логин и пароль: <br /> <br /></span><span title=\\"Логін: %email%\\" onmouseover=\\"this.style.backgroundColor=\\''#ebeff9\\''\\" onmouseout=\\"this.style.backgroundColor=\\''#fff\\''\\">Логин: %email% <br /> <br /></span><span style=\\"background-color: #ffffff;\\" title=\\"Пароль: %password% (радимо змінити пароль на ваш особистий при першому відвідуванні мережі)\\" onmouseover=\\"this.style.backgroundColor=\\''#ebeff9\\''\\" onmouseout=\\"this.style.backgroundColor=\\''#fff\\''\\">Пароль: %password% (советуем изменить пароль на ваш личный при первом посещении сети) <br /> <br /></span><span style=\\"background-color: #ffffff;\\" title=\\"У разі виникнення проблем при підтвердженні реєстрації або в роботі з мережею, зв\\''яжіться з Секретаріатом за адресою secretariat@meritokratia.info\\" onmouseover=\\"this.style.backgroundColor=\\''#ebeff9\\''\\" onmouseout=\\"this.style.backgroundColor=\\''#fff\\''\\">В случае возникновения проблем при подтверждении регистрации или в работе с сетью, свяжитесь с Секретариатом по адресу secretariat@meritokratia.info <br /> <br /></span><span style=\\"background-color: #ffffff;\\" title=\\"Бажаю Вам приємного перебування в мережі &laquo;Мерітократ&raquo;!\\" onmouseover=\\"this.style.backgroundColor=\\''#ebeff9\\''\\" onmouseout=\\"this.style.backgroundColor=\\''#fff\\''\\">Желаю Вам приятного пребывания в сети &laquo;Новая Волна&raquo;! <br /> <br /></span><span title=\\"З повагою\\" onmouseover=\\"this.style.backgroundColor=\\''#ebeff9\\''\\" onmouseout=\\"this.style.backgroundColor=\\''#fff\\''\\">С уважением <br /> <br /> <br /></span><span style=\\"background-color: #ffffff;\\" title=\\"Голова Оргкомітету Мерітократичної партії України\\" onmouseover=\\"this.style.backgroundColor=\\''#ebeff9\\''\\" onmouseout=\\"this.style.backgroundColor=\\''#fff\\''\\">Председатель Оргкомитета Меритократичнои партии Украины <br /></span><span title=\\"Ігор Шевченко\\" onmouseover=\\"this.style.backgroundColor=\\''#ebeff9\\''\\" onmouseout=\\"this.style.backgroundColor=\\''#fff\\''\\">Игорь Шевченко</span></span></p>', 'info@meritokrat.org', 'Novi-ua.org', 'Novi-ua.org', 0);
INSERT INTO email_system VALUES (36, 'sign_up', 'Письмо №2 (после активации)', 'Дякуємо за приєднання до мережі', 'Благодарим за активацию в сети', '<p class=\\"MsoNormal\\"><span lang=\\"UK\\">Вітаю, </span>%name%<span lang=\\"UK\\">!</span></p>
<p class=\\"MsoNormal\\"><span lang=\\"UK\\">Дякую за активацію Вашого профілю в мережі &laquo;Нова Хвиля&raquo;.</span></p>', '<p><span id=\\"result_box\\" class=\\"long_text\\" lang=\\"ru\\">Здравствуйте, %name%!<br /><br />Спасибо за активацию Вашего профиля в сети &laquo;Нова хвиля&raquo;.<br /><br /></span></p>', 'i.shevchenko@meritokrat.org', 'Ігор Шевченко', 'Игорь Шевченко', 0);


--
-- Data for Name: email_users; Type: TABLE DATA; Schema: public; Owner: auzo
--

INSERT INTO email_users VALUES (1, 'pa@shevchenko.ua', 'Людмила', 'Лірник', 0);
INSERT INTO email_users VALUES (2, 'julia@kostukova.com', 'Юлія', 'Костюкова', 0);


--
-- Data for Name: events; Type: TABLE DATA; Schema: public; Owner: auzo
--



--
-- Data for Name: events2users; Type: TABLE DATA; Schema: public; Owner: auzo
--



--
-- Data for Name: events_comments; Type: TABLE DATA; Schema: public; Owner: auzo
--



--
-- Data for Name: feed; Type: TABLE DATA; Schema: public; Owner: auzo
--



--
-- Data for Name: files; Type: TABLE DATA; Schema: public; Owner: auzo
--



--
-- Data for Name: files_dirs; Type: TABLE DATA; Schema: public; Owner: auzo
--



--
-- Data for Name: friends; Type: TABLE DATA; Schema: public; Owner: auzo
--



--
-- Data for Name: friends_pending; Type: TABLE DATA; Schema: public; Owner: auzo
--



--
-- Data for Name: groups; Type: TABLE DATA; Schema: public; Owner: auzo
--

INSERT INTO groups VALUES (1, 31, 'Тестова спiльнота', 1301558285, 0.0000, '', NULL, 'просто тестова спiльнота', NULL, 0, 1, '''тестов'':1 ''спiльнот'':2', 1, 1, 1360, 1, 0, 0, 0);
INSERT INTO groups VALUES (2, 31, 'WEF Global Shapers', 1310458872, 0.0000, '', NULL, '', '', 0, 1, '''wef'':1 ''global'':2 ''shaper'':3', 2, 1, 5, 1, 0, 1, 0);


--
-- Data for Name: groups_applicants; Type: TABLE DATA; Schema: public; Owner: auzo
--



--
-- Data for Name: groups_files; Type: TABLE DATA; Schema: public; Owner: auzo
--



--
-- Data for Name: groups_files_dirs; Type: TABLE DATA; Schema: public; Owner: auzo
--



--
-- Data for Name: groups_links; Type: TABLE DATA; Schema: public; Owner: auzo
--



--
-- Data for Name: groups_members; Type: TABLE DATA; Schema: public; Owner: auzo
--

INSERT INTO groups_members VALUES (1, 1360);
INSERT INTO groups_members VALUES (2, 5);


--
-- Data for Name: groups_news; Type: TABLE DATA; Schema: public; Owner: auzo
--



--
-- Data for Name: groups_photo_comments; Type: TABLE DATA; Schema: public; Owner: auzo
--



--
-- Data for Name: groups_photos; Type: TABLE DATA; Schema: public; Owner: auzo
--



--
-- Data for Name: groups_photos_albums; Type: TABLE DATA; Schema: public; Owner: auzo
--



--
-- Data for Name: groups_position; Type: TABLE DATA; Schema: public; Owner: auzo
--



--
-- Data for Name: groups_position_messages; Type: TABLE DATA; Schema: public; Owner: auzo
--



--
-- Data for Name: groups_proposal; Type: TABLE DATA; Schema: public; Owner: auzo
--



--
-- Data for Name: groups_proposal_messages; Type: TABLE DATA; Schema: public; Owner: auzo
--



--
-- Data for Name: groups_topics; Type: TABLE DATA; Schema: public; Owner: auzo
--



--
-- Data for Name: groups_topics_messages; Type: TABLE DATA; Schema: public; Owner: auzo
--



--
-- Data for Name: ideas; Type: TABLE DATA; Schema: public; Owner: auzo
--



--
-- Data for Name: ideas_comments; Type: TABLE DATA; Schema: public; Owner: auzo
--



--
-- Data for Name: ideas_tags; Type: TABLE DATA; Schema: public; Owner: auzo
--



--
-- Data for Name: ideas_to_tags; Type: TABLE DATA; Schema: public; Owner: auzo
--



--
-- Data for Name: invites; Type: TABLE DATA; Schema: public; Owner: auzo
--

INSERT INTO invites VALUES (3, 5, 2, 2, 2, 1, 1310458900);
INSERT INTO invites VALUES (4, 2, 2, 2, 2, 1, 1310592652);


--
-- Data for Name: leadergroups; Type: TABLE DATA; Schema: public; Owner: auzo
--



--
-- Data for Name: leadergroups_applicants; Type: TABLE DATA; Schema: public; Owner: auzo
--



--
-- Data for Name: leadergroups_files; Type: TABLE DATA; Schema: public; Owner: auzo
--



--
-- Data for Name: leadergroups_members; Type: TABLE DATA; Schema: public; Owner: auzo
--



--
-- Data for Name: leadergroups_news; Type: TABLE DATA; Schema: public; Owner: auzo
--



--
-- Data for Name: leadergroups_photo_comments; Type: TABLE DATA; Schema: public; Owner: auzo
--



--
-- Data for Name: leadergroups_photos; Type: TABLE DATA; Schema: public; Owner: auzo
--



--
-- Data for Name: leadergroups_photos_albums; Type: TABLE DATA; Schema: public; Owner: auzo
--



--
-- Data for Name: leadergroups_topics; Type: TABLE DATA; Schema: public; Owner: auzo
--



--
-- Data for Name: leadergroups_topics_messages; Type: TABLE DATA; Schema: public; Owner: auzo
--



--
-- Data for Name: mailing; Type: TABLE DATA; Schema: public; Owner: auzo
--



--
-- Data for Name: mailing_send_mails; Type: TABLE DATA; Schema: public; Owner: auzo
--



--
-- Data for Name: messages; Type: TABLE DATA; Schema: public; Owner: auzo
--



--
-- Data for Name: messages_threads; Type: TABLE DATA; Schema: public; Owner: auzo
--



--
-- Data for Name: ns_temp_recomendations; Type: TABLE DATA; Schema: public; Owner: auzo
--



--
-- Data for Name: parties; Type: TABLE DATA; Schema: public; Owner: auzo
--



--
-- Data for Name: parties_members; Type: TABLE DATA; Schema: public; Owner: auzo
--



--
-- Data for Name: parties_news; Type: TABLE DATA; Schema: public; Owner: auzo
--



--
-- Data for Name: parties_program; Type: TABLE DATA; Schema: public; Owner: auzo
--



--
-- Data for Name: parties_topics; Type: TABLE DATA; Schema: public; Owner: auzo
--



--
-- Data for Name: parties_topics_messages; Type: TABLE DATA; Schema: public; Owner: auzo
--



--
-- Data for Name: parties_trust; Type: TABLE DATA; Schema: public; Owner: auzo
--



--
-- Data for Name: photo; Type: TABLE DATA; Schema: public; Owner: auzo
--

INSERT INTO photo VALUES (1, 1, 1360, 'text', 6091301, 0);
INSERT INTO photo VALUES (2, 2, 1360, 'test text', 5879566, 0);


--
-- Data for Name: photo_albums; Type: TABLE DATA; Schema: public; Owner: auzo
--

INSERT INTO photo_albums VALUES (1, 1360, 1360, 'test', 1);
INSERT INTO photo_albums VALUES (2, 1, 1360, 'groups photos', 3);


--
-- Data for Name: photo_comments; Type: TABLE DATA; Schema: public; Owner: auzo
--



--
-- Data for Name: polls; Type: TABLE DATA; Schema: public; Owner: auzo
--



--
-- Data for Name: polls_answers; Type: TABLE DATA; Schema: public; Owner: auzo
--



--
-- Data for Name: polls_comments; Type: TABLE DATA; Schema: public; Owner: auzo
--



--
-- Data for Name: polls_custom; Type: TABLE DATA; Schema: public; Owner: auzo
--



--
-- Data for Name: polls_votes; Type: TABLE DATA; Schema: public; Owner: auzo
--



--
-- Data for Name: rate_history; Type: TABLE DATA; Schema: public; Owner: auzo
--



--
-- Data for Name: regions; Type: TABLE DATA; Schema: public; Owner: auzo
--

INSERT INTO regions VALUES (1, 1, 'АР Крим', 'АР Крым');
INSERT INTO regions VALUES (2, 1, 'Севастополь', 'Севастополь');
INSERT INTO regions VALUES (3, 1, 'Волинська область', 'Волынская область');
INSERT INTO regions VALUES (4, 1, 'Вінницька область', 'Винницкая область');
INSERT INTO regions VALUES (5, 1, 'Дніпропетровська область', 'Днепропетровская область');
INSERT INTO regions VALUES (6, 1, 'Донецька область', 'Донецкая область');
INSERT INTO regions VALUES (7, 1, 'Житомирська область', 'Житомирская область');
INSERT INTO regions VALUES (8, 1, 'Закарпатська область', 'Закарпатская область');
INSERT INTO regions VALUES (9, 1, 'Запорізька область', 'Запорожская область');
INSERT INTO regions VALUES (10, 1, 'Івано-Франківська область', 'Ивано-Франковская область');
INSERT INTO regions VALUES (11, 1, 'Кіровоградська область', 'Кировоградская область');
INSERT INTO regions VALUES (12, 1, 'Київська область', 'Киевская область');
INSERT INTO regions VALUES (13, 1, 'Київ', 'Киев');
INSERT INTO regions VALUES (14, 1, 'Луганська область', 'Луганская область');
INSERT INTO regions VALUES (15, 1, 'Львівська область', 'Львовская область');
INSERT INTO regions VALUES (16, 1, 'Миколаївська область', 'Николаевская область');
INSERT INTO regions VALUES (17, 1, 'Одеська область', 'Одесская область');
INSERT INTO regions VALUES (18, 1, 'Полтавська область', 'Полтавская область');
INSERT INTO regions VALUES (19, 1, 'Рівненська область', 'Ровенская область');
INSERT INTO regions VALUES (21, 1, 'Сумська область', 'Сумская область');
INSERT INTO regions VALUES (22, 1, 'Тернопільська область', 'Тернопольская область');
INSERT INTO regions VALUES (23, 1, 'Харківська область', 'Харьковская область');
INSERT INTO regions VALUES (24, 1, 'Херсонська область', 'Херсонская область');
INSERT INTO regions VALUES (25, 1, 'Хмельницька область', 'Хмельницкая область');
INSERT INTO regions VALUES (26, 1, 'Черкаська область', 'Черкасская область');
INSERT INTO regions VALUES (27, 1, 'Чернівецька область', 'Черновицкая область');
INSERT INTO regions VALUES (28, 1, 'Чернігівська область', 'Черниговская область');
INSERT INTO regions VALUES (9999, 0, 'закордон', 'закордон');


--
-- Data for Name: temp_shev_mails; Type: TABLE DATA; Schema: public; Owner: auzo
--



--
-- Data for Name: user_access; Type: TABLE DATA; Schema: public; Owner: auzo
--

INSERT INTO user_access VALUES (29, 1296172989, '::ffff:93.73.68.173', 'Mozilla/5.0 (X11; U; Linux i686; uk; rv:1.9.2.13) Gecko/20101206 Ubuntu/10.04 (lucid) Firefox/3.6.13', '', 'profile', '', 0);
INSERT INTO user_access VALUES (29, 1296173001, '::ffff:93.73.68.173', 'Mozilla/5.0 (X11; U; Linux i686; uk; rv:1.9.2.13) Gecko/20101206 Ubuntu/10.04 (lucid) Firefox/3.6.13', 'http://newwave.in.ua/profile', 'home', '', 0);
INSERT INTO user_access VALUES (29, 1296173152, '::ffff:93.73.68.173', 'Mozilla/5.0 (X11; U; Linux i686; uk; rv:1.9.2.13) Gecko/20101206 Ubuntu/10.04 (lucid) Firefox/3.6.13', '', 'home', '', 0);
INSERT INTO user_access VALUES (29, 1296173154, '::ffff:93.73.68.173', 'Mozilla/5.0 (X11; U; Linux i686; uk; rv:1.9.2.13) Gecko/20101206 Ubuntu/10.04 (lucid) Firefox/3.6.13', '', 'home', '', 0);
INSERT INTO user_access VALUES (2, 1296202271, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; ru; rv:1.9.2.10) Gecko/20100914 Firefox/3.6.10 (.NET CLR 3.5.30729)', 'http://newwave.in.ua/', 'profile', '', 0);
INSERT INTO user_access VALUES (2, 1296202278, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; ru; rv:1.9.2.10) Gecko/20100914 Firefox/3.6.10 (.NET CLR 3.5.30729)', 'http://newwave.in.ua/profile', 'home', '', 0);
INSERT INTO user_access VALUES (1360, 1296226764, '::ffff:95.134.71.210', 'Mozilla/5.0 (X11; U; Linux i686; ru; rv:1.9.2.12) Gecko/20101027 Ubuntu/10.04 (lucid) Firefox/3.6.12', 'http://newwave.in.ua/', 'profile', '', 0);
INSERT INTO user_access VALUES (29, 1296240898, '::ffff:93.73.68.173', 'Mozilla/5.0 (X11; U; Linux i686; uk; rv:1.9.2.13) Gecko/20101206 Ubuntu/10.04 (lucid) Firefox/3.6.13', '', 'home', '', 0);
INSERT INTO user_access VALUES (1360, 1296242296, '::ffff:95.134.234.158', 'Mozilla/5.0 (X11; U; Linux i686; ru; rv:1.9.2.12) Gecko/20101027 Ubuntu/10.04 (lucid) Firefox/3.6.12', 'http://newwave.in.ua/', 'profile', '', 0);
INSERT INTO user_access VALUES (29, 1296299573, '::ffff:93.73.68.173', 'Mozilla/5.0 (X11; U; Linux i686; uk; rv:1.9.2.13) Gecko/20101206 Ubuntu/10.04 (lucid) Firefox/3.6.13', '', 'home', '', 0);
INSERT INTO user_access VALUES (29, 1296342568, '::ffff:93.73.68.173', 'Mozilla/5.0 (X11; U; Linux i686; uk; rv:1.9.2.13) Gecko/20101206 Ubuntu/10.04 (lucid) Firefox/3.6.13', '', 'home', '', 0);
INSERT INTO user_access VALUES (2, 1296554149, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; ru; rv:1.9.2.10) Gecko/20100914 Firefox/3.6.10 (.NET CLR 3.5.30729)', '', 'home', '', 0);
INSERT INTO user_access VALUES (29, 1296666320, '::ffff:213.227.210.148', 'Mozilla/5.0 (X11; U; Linux x86_64; uk; rv:1.9.2.13) Gecko/20101206 Ubuntu/10.04 (lucid) Firefox/3.6.13', 'http://newwave.in.ua/sign', 'profile', '', 0);
INSERT INTO user_access VALUES (29, 1296666324, '::ffff:213.227.210.148', 'Mozilla/5.0 (X11; U; Linux x86_64; uk; rv:1.9.2.13) Gecko/20101206 Ubuntu/10.04 (lucid) Firefox/3.6.13', 'http://newwave.in.ua/profile', 'home', '', 0);
INSERT INTO user_access VALUES (1360, 1296681085, '::ffff:95.134.211.219', 'Mozilla/5.0 (X11; U; Linux i686; ru; rv:1.9.2.12) Gecko/20101027 Ubuntu/10.04 (lucid) Firefox/3.6.12', '', 'home', '', 0);
INSERT INTO user_access VALUES (29, 1296719745, '::ffff:213.227.210.148', 'Mozilla/5.0 (X11; U; Linux x86_64; uk; rv:1.9.2.13) Gecko/20101206 Ubuntu/10.04 (lucid) Firefox/3.6.13', 'http://newwave.in.ua/home', 'home', '', 0);
INSERT INTO user_access VALUES (29, 1296719756, '::ffff:213.227.210.148', 'Mozilla/5.0 (X11; U; Linux x86_64; uk; rv:1.9.2.13) Gecko/20101206 Ubuntu/10.04 (lucid) Firefox/3.6.13', 'http://newwave.in.ua/home', 'help', 'index', 0);
INSERT INTO user_access VALUES (29, 1296719766, '::ffff:213.227.210.148', 'Mozilla/5.0 (X11; U; Linux x86_64; uk; rv:1.9.2.13) Gecko/20101206 Ubuntu/10.04 (lucid) Firefox/3.6.13', 'http://newwave.in.ua/help/index?party', 'blogs', '', 0);
INSERT INTO user_access VALUES (29, 1296719768, '::ffff:213.227.210.148', 'Mozilla/5.0 (X11; U; Linux x86_64; uk; rv:1.9.2.13) Gecko/20101206 Ubuntu/10.04 (lucid) Firefox/3.6.13', 'http://newwave.in.ua/blogs', 'help', '', 0);
INSERT INTO user_access VALUES (2, 1296722589, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; ru; rv:1.9.2.10) Gecko/20100914 Firefox/3.6.10 (.NET CLR 3.5.30729)', '', 'home', '', 0);
INSERT INTO user_access VALUES (1360, 1296724117, '::ffff:95.134.176.45', 'Mozilla/5.0 (X11; U; Linux i686; ru; rv:1.9.2.12) Gecko/20101027 Ubuntu/10.04 (lucid) Firefox/3.6.12', '', 'home', '', 0);
INSERT INTO user_access VALUES (1360, 1296724133, '::ffff:95.134.176.45', 'Mozilla/5.0 (X11; U; Linux i686; ru; rv:1.9.2.12) Gecko/20101027 Ubuntu/10.04 (lucid) Firefox/3.6.12', '', 'home', '', 0);
INSERT INTO user_access VALUES (1360, 1296724189, '::ffff:95.134.176.45', 'Mozilla/5.0 (X11; U; Linux i686; ru; rv:1.9.2.12) Gecko/20101027 Ubuntu/10.04 (lucid) Firefox/3.6.12', '', 'home', '', 0);
INSERT INTO user_access VALUES (1360, 1296724608, '::ffff:95.134.176.45', 'Mozilla/5.0 (X11; U; Linux i686; ru; rv:1.9.2.12) Gecko/20101027 Ubuntu/10.04 (lucid) Firefox/3.6.12', '', 'home', '', 0);
INSERT INTO user_access VALUES (1360, 1296725062, '::ffff:95.134.176.45', 'Mozilla/5.0 (X11; U; Linux i686; ru; rv:1.9.2.12) Gecko/20101027 Ubuntu/10.04 (lucid) Firefox/3.6.12', '', 'home', '', 0);
INSERT INTO user_access VALUES (1360, 1296725215, '::ffff:95.134.176.45', 'Mozilla/5.0 (X11; U; Linux i686; ru; rv:1.9.2.12) Gecko/20101027 Ubuntu/10.04 (lucid) Firefox/3.6.12', '', 'home', '', 0);
INSERT INTO user_access VALUES (1360, 1296725329, '::ffff:95.134.176.45', 'Mozilla/5.0 (X11; U; Linux i686; ru; rv:1.9.2.12) Gecko/20101027 Ubuntu/10.04 (lucid) Firefox/3.6.12', '', 'home', '', 0);
INSERT INTO user_access VALUES (1360, 1296726024, '::ffff:95.134.176.45', 'Mozilla/5.0 (X11; U; Linux i686; ru; rv:1.9.2.12) Gecko/20101027 Ubuntu/10.04 (lucid) Firefox/3.6.12', '', 'home', '', 0);
INSERT INTO user_access VALUES (1360, 1296726152, '::ffff:95.134.176.45', 'Mozilla/5.0 (X11; U; Linux i686; ru; rv:1.9.2.12) Gecko/20101027 Ubuntu/10.04 (lucid) Firefox/3.6.12', '', 'home', '', 0);
INSERT INTO user_access VALUES (1360, 1296726447, '::ffff:95.134.176.45', 'Mozilla/5.0 (X11; U; Linux i686; ru; rv:1.9.2.12) Gecko/20101027 Ubuntu/10.04 (lucid) Firefox/3.6.12', '', 'home', '', 0);
INSERT INTO user_access VALUES (1360, 1296726460, '::ffff:95.134.176.45', 'Mozilla/5.0 (X11; U; Linux i686; ru; rv:1.9.2.12) Gecko/20101027 Ubuntu/10.04 (lucid) Firefox/3.6.12', '', 'home', '', 0);
INSERT INTO user_access VALUES (1360, 1296727635, '::ffff:95.134.176.45', 'Mozilla/5.0 (X11; U; Linux i686; ru; rv:1.9.2.12) Gecko/20101027 Ubuntu/10.04 (lucid) Firefox/3.6.12', '', 'home', '', 0);
INSERT INTO user_access VALUES (1360, 1296727709, '::ffff:95.134.176.45', 'Mozilla/5.0 (X11; U; Linux i686; ru; rv:1.9.2.12) Gecko/20101027 Ubuntu/10.04 (lucid) Firefox/3.6.12', '', 'home', '', 0);
INSERT INTO user_access VALUES (1360, 1296730287, '::ffff:95.134.193.211', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; ru; rv:1.9.2.13) Gecko/20101203 Firefox/3.6.13', 'http://newwave.in.ua/', 'profile', '', 0);
INSERT INTO user_access VALUES (1360, 1296730306, '::ffff:95.134.193.211', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; ru; rv:1.9.2.13) Gecko/20101203 Firefox/3.6.13', 'http://newwave.in.ua/profile', 'home', '', 0);
INSERT INTO user_access VALUES (1360, 1296730820, '::ffff:95.134.136.229', 'Mozilla/5.0 (X11; U; Linux i686; ru; rv:1.9.2.12) Gecko/20101027 Ubuntu/10.04 (lucid) Firefox/3.6.12', '', 'home', '', 0);
INSERT INTO user_access VALUES (1360, 1296730831, '::ffff:95.134.136.229', 'Mozilla/5.0 (X11; U; Linux i686; ru; rv:1.9.2.12) Gecko/20101027 Ubuntu/10.04 (lucid) Firefox/3.6.12', '', 'home', '', 0);
INSERT INTO user_access VALUES (1360, 1296730891, '::ffff:95.134.136.229', 'Mozilla/5.0 (X11; U; Linux i686; ru; rv:1.9.2.12) Gecko/20101027 Ubuntu/10.04 (lucid) Firefox/3.6.12', '', 'home', '', 0);
INSERT INTO user_access VALUES (1360, 1296730921, '::ffff:95.134.136.229', 'Mozilla/5.0 (X11; U; Linux i686; ru; rv:1.9.2.12) Gecko/20101027 Ubuntu/10.04 (lucid) Firefox/3.6.12', '', 'home', '', 0);
INSERT INTO user_access VALUES (1360, 1296730990, '::ffff:95.134.136.229', 'Mozilla/5.0 (X11; U; Linux i686; ru; rv:1.9.2.12) Gecko/20101027 Ubuntu/10.04 (lucid) Firefox/3.6.12', '', 'home', '', 0);
INSERT INTO user_access VALUES (1360, 1296731783, '::ffff:95.134.206.219', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; ru; rv:1.9.2.13) Gecko/20101203 Firefox/3.6.13', '', 'home', '', 0);
INSERT INTO user_access VALUES (1360, 1296732482, '::ffff:95.134.14.22', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; ru; rv:1.9.2.13) Gecko/20101203 Firefox/3.6.13', '', 'home', '', 0);
INSERT INTO user_access VALUES (1360, 1296732593, '::ffff:95.134.110.27', 'Mozilla/5.0 (X11; U; Linux i686; ru; rv:1.9.2.12) Gecko/20101027 Ubuntu/10.04 (lucid) Firefox/3.6.12', '', 'home', '', 0);
INSERT INTO user_access VALUES (1360, 1296732643, '::ffff:95.134.110.27', 'Mozilla/5.0 (X11; U; Linux i686; ru; rv:1.9.2.12) Gecko/20101027 Ubuntu/10.04 (lucid) Firefox/3.6.12', 'http://newwave.in.ua/home', 'blogs', 'edit', 0);
INSERT INTO user_access VALUES (5, 1296739570, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows; U; Windows NT 6.1; ru; rv:1.9.2.13) Gecko/20101203 Firefox/3.6.13 GTB7.1 ( .NET CLR 3.5.30729; .NET4.0E) WebMoney Advisor', 'http://newwave.in.ua/', 'profile', '', 0);
INSERT INTO user_access VALUES (5, 1296739576, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows; U; Windows NT 6.1; ru; rv:1.9.2.13) Gecko/20101203 Firefox/3.6.13 GTB7.1 ( .NET CLR 3.5.30729; .NET4.0E) WebMoney Advisor', 'http://newwave.in.ua/profile', 'people', '', 0);
INSERT INTO user_access VALUES (5, 1296739589, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows; U; Windows NT 6.1; ru; rv:1.9.2.13) Gecko/20101203 Firefox/3.6.13 GTB7.1 ( .NET CLR 3.5.30729; .NET4.0E) WebMoney Advisor', 'http://newwave.in.ua/people', 'profile', 'index', 5);
INSERT INTO user_access VALUES (5, 1296739598, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows; U; Windows NT 6.1; ru; rv:1.9.2.13) Gecko/20101203 Firefox/3.6.13 GTB7.1 ( .NET CLR 3.5.30729; .NET4.0E) WebMoney Advisor', 'http://newwave.in.ua/profile-5', 'profile', 'edit', 5);
INSERT INTO user_access VALUES (5, 1296739625, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows; U; Windows NT 6.1; ru; rv:1.9.2.13) Gecko/20101203 Firefox/3.6.13 GTB7.1 ( .NET CLR 3.5.30729; .NET4.0E) WebMoney Advisor', 'http://newwave.in.ua/profile/edit?id=5', 'profile', 'edit', 5);
INSERT INTO user_access VALUES (29, 1296739841, '::ffff:213.227.210.148', 'Mozilla/5.0 (X11; U; Linux x86_64; uk; rv:1.9.2.13) Gecko/20101206 Ubuntu/10.04 (lucid) Firefox/3.6.13', '', 'profile', 'index', 5);
INSERT INTO user_access VALUES (29, 1296739852, '::ffff:213.227.210.148', 'Mozilla/5.0 (X11; U; Linux x86_64; uk; rv:1.9.2.13) Gecko/20101206 Ubuntu/10.04 (lucid) Firefox/3.6.13', 'http://newwave.in.ua/profile-5', 'home', '', 0);
INSERT INTO user_access VALUES (29, 1296739874, '::ffff:213.227.210.148', 'Mozilla/5.0 (X11; U; Linux x86_64; uk; rv:1.9.2.13) Gecko/20101206 Ubuntu/10.04 (lucid) Firefox/3.6.13', 'http://newwave.in.ua/home', 'home', '', 0);
INSERT INTO user_access VALUES (29, 1296739892, '::ffff:213.227.210.148', 'Mozilla/5.0 (X11; U; Linux x86_64; uk; rv:1.9.2.13) Gecko/20101206 Ubuntu/10.04 (lucid) Firefox/3.6.13', 'http://newwave.in.ua/home', 'people', '', 0);
INSERT INTO user_access VALUES (29, 1296739900, '::ffff:213.227.210.148', 'Mozilla/5.0 (X11; U; Linux x86_64; uk; rv:1.9.2.13) Gecko/20101206 Ubuntu/10.04 (lucid) Firefox/3.6.13', 'http://newwave.in.ua/people', 'home', '', 0);
INSERT INTO user_access VALUES (29, 1296739903, '::ffff:213.227.210.148', 'Mozilla/5.0 (X11; U; Linux x86_64; uk; rv:1.9.2.13) Gecko/20101206 Ubuntu/10.04 (lucid) Firefox/3.6.13', 'http://newwave.in.ua/home', 'people', '', 0);
INSERT INTO user_access VALUES (29, 1296739931, '::ffff:213.227.210.148', 'Mozilla/5.0 (X11; U; Linux x86_64; uk; rv:1.9.2.13) Gecko/20101206 Ubuntu/10.04 (lucid) Firefox/3.6.13', 'http://newwave.in.ua/people', 'home', '', 0);
INSERT INTO user_access VALUES (29, 1296739935, '::ffff:213.227.210.148', 'Mozilla/5.0 (X11; U; Linux x86_64; uk; rv:1.9.2.13) Gecko/20101206 Ubuntu/10.04 (lucid) Firefox/3.6.13', 'http://newwave.in.ua/people', 'home', '', 0);
INSERT INTO user_access VALUES (29, 1296740305, '::ffff:213.227.210.148', 'Mozilla/5.0 (X11; U; Linux x86_64; uk; rv:1.9.2.13) Gecko/20101206 Ubuntu/10.04 (lucid) Firefox/3.6.13', 'http://newwave.in.ua/home', 'home', '', 0);
INSERT INTO user_access VALUES (29, 1296740309, '::ffff:213.227.210.148', 'Mozilla/5.0 (X11; U; Linux x86_64; uk; rv:1.9.2.13) Gecko/20101206 Ubuntu/10.04 (lucid) Firefox/3.6.13', 'http://newwave.in.ua/home', 'events', '', 0);
INSERT INTO user_access VALUES (29, 1296740311, '::ffff:213.227.210.148', 'Mozilla/5.0 (X11; U; Linux x86_64; uk; rv:1.9.2.13) Gecko/20101206 Ubuntu/10.04 (lucid) Firefox/3.6.13', 'http://newwave.in.ua/events', 'polls', '', 0);
INSERT INTO user_access VALUES (29, 1296740312, '::ffff:213.227.210.148', 'Mozilla/5.0 (X11; U; Linux x86_64; uk; rv:1.9.2.13) Gecko/20101206 Ubuntu/10.04 (lucid) Firefox/3.6.13', 'http://newwave.in.ua/polls', 'library', '', 0);
INSERT INTO user_access VALUES (29, 1296740344, '::ffff:213.227.210.148', 'Mozilla/5.0 (X11; U; Linux x86_64; uk; rv:1.9.2.13) Gecko/20101206 Ubuntu/10.04 (lucid) Firefox/3.6.13', 'http://newwave.in.ua/polls', 'library', '', 0);
INSERT INTO user_access VALUES (29, 1296740356, '::ffff:213.227.210.148', 'Mozilla/5.0 (X11; U; Linux x86_64; uk; rv:1.9.2.13) Gecko/20101206 Ubuntu/10.04 (lucid) Firefox/3.6.13', 'http://newwave.in.ua/polls', 'library', '', 0);
INSERT INTO user_access VALUES (29, 1296740387, '::ffff:213.227.210.148', 'Mozilla/5.0 (X11; U; Linux x86_64; uk; rv:1.9.2.13) Gecko/20101206 Ubuntu/10.04 (lucid) Firefox/3.6.13', 'http://newwave.in.ua/polls', 'library', '', 0);
INSERT INTO user_access VALUES (29, 1296740409, '::ffff:213.227.210.148', 'Mozilla/5.0 (X11; U; Linux x86_64; uk; rv:1.9.2.13) Gecko/20101206 Ubuntu/10.04 (lucid) Firefox/3.6.13', 'http://newwave.in.ua/polls', 'library', '', 0);
INSERT INTO user_access VALUES (29, 1296740427, '::ffff:213.227.210.148', 'Mozilla/5.0 (X11; U; Linux x86_64; uk; rv:1.9.2.13) Gecko/20101206 Ubuntu/10.04 (lucid) Firefox/3.6.13', 'http://newwave.in.ua/polls', 'library', '', 0);
INSERT INTO user_access VALUES (29, 1296740488, '::ffff:213.227.210.148', 'Mozilla/5.0 (X11; U; Linux x86_64; uk; rv:1.9.2.13) Gecko/20101206 Ubuntu/10.04 (lucid) Firefox/3.6.13', 'http://newwave.in.ua/polls', 'library', '', 0);
INSERT INTO user_access VALUES (29, 1296740496, '::ffff:213.227.210.148', 'Mozilla/5.0 (X11; U; Linux x86_64; uk; rv:1.9.2.13) Gecko/20101206 Ubuntu/10.04 (lucid) Firefox/3.6.13', 'http://newwave.in.ua/library', 'home', '', 0);
INSERT INTO user_access VALUES (29, 1296740503, '::ffff:213.227.210.148', 'Mozilla/5.0 (X11; U; Linux x86_64; uk; rv:1.9.2.13) Gecko/20101206 Ubuntu/10.04 (lucid) Firefox/3.6.13', 'http://newwave.in.ua/home', 'blogs', '', 0);
INSERT INTO user_access VALUES (29, 1296740507, '::ffff:213.227.210.148', 'Mozilla/5.0 (X11; U; Linux x86_64; uk; rv:1.9.2.13) Gecko/20101206 Ubuntu/10.04 (lucid) Firefox/3.6.13', 'http://newwave.in.ua/blogs', 'blogs', 'favorites', 0);
INSERT INTO user_access VALUES (29, 1296740509, '::ffff:213.227.210.148', 'Mozilla/5.0 (X11; U; Linux x86_64; uk; rv:1.9.2.13) Gecko/20101206 Ubuntu/10.04 (lucid) Firefox/3.6.13', 'http://newwave.in.ua/blogs/favorites', 'blogs', 'discussed', 0);
INSERT INTO user_access VALUES (29, 1296740511, '::ffff:213.227.210.148', 'Mozilla/5.0 (X11; U; Linux x86_64; uk; rv:1.9.2.13) Gecko/20101206 Ubuntu/10.04 (lucid) Firefox/3.6.13', 'http://newwave.in.ua/blogs/discussed', 'blogs', 'comments', 0);
INSERT INTO user_access VALUES (29, 1296740517, '::ffff:213.227.210.148', 'Mozilla/5.0 (X11; U; Linux x86_64; uk; rv:1.9.2.13) Gecko/20101206 Ubuntu/10.04 (lucid) Firefox/3.6.13', 'http://newwave.in.ua/blogs/comments', 'help', 'index', 0);
INSERT INTO user_access VALUES (29, 1296740525, '::ffff:213.227.210.148', 'Mozilla/5.0 (X11; U; Linux x86_64; uk; rv:1.9.2.13) Gecko/20101206 Ubuntu/10.04 (lucid) Firefox/3.6.13', 'http://newwave.in.ua/blogs/comments', 'help', 'index', 0);
INSERT INTO user_access VALUES (1360, 1296748180, '::ffff:95.134.154.188', 'Mozilla/5.0 (X11; U; Linux i686; ru; rv:1.9.2.12) Gecko/20101027 Ubuntu/10.04 (lucid) Firefox/3.6.12', '', 'home', '', 0);
INSERT INTO user_access VALUES (1360, 1296748196, '::ffff:95.134.154.188', 'Mozilla/5.0 (X11; U; Linux i686; ru; rv:1.9.2.12) Gecko/20101027 Ubuntu/10.04 (lucid) Firefox/3.6.12', 'http://newwave.in.ua/home', 'admin', '', 0);
INSERT INTO user_access VALUES (29, 1296748285, '::ffff:213.227.210.148', 'Mozilla/5.0 (X11; U; Linux x86_64; uk; rv:1.9.2.13) Gecko/20101206 Ubuntu/10.04 (lucid) Firefox/3.6.13', '', 'home', '', 0);
INSERT INTO user_access VALUES (1360, 1296749361, '::ffff:95.134.154.188', 'Mozilla/5.0 (X11; U; Linux i686; ru; rv:1.9.2.12) Gecko/20101027 Ubuntu/10.04 (lucid) Firefox/3.6.12', 'http://newwave.in.ua/home', 'admin', '', 0);
INSERT INTO user_access VALUES (1360, 1296749368, '::ffff:95.134.154.188', 'Mozilla/5.0 (X11; U; Linux i686; ru; rv:1.9.2.12) Gecko/20101027 Ubuntu/10.04 (lucid) Firefox/3.6.12', 'http://newwave.in.ua/admin', 'home', '', 0);
INSERT INTO user_access VALUES (1360, 1296749444, '::ffff:95.134.154.188', 'Mozilla/5.0 (X11; U; Linux i686; ru; rv:1.9.2.12) Gecko/20101027 Ubuntu/10.04 (lucid) Firefox/3.6.12', 'http://newwave.in.ua/admin', 'home', '', 0);
INSERT INTO user_access VALUES (1360, 1296749454, '::ffff:95.134.154.188', 'Mozilla/5.0 (X11; U; Linux i686; ru; rv:1.9.2.12) Gecko/20101027 Ubuntu/10.04 (lucid) Firefox/3.6.12', 'http://newwave.in.ua/home', 'blogs', 'edit', 0);
INSERT INTO user_access VALUES (1360, 1296749551, '::ffff:95.134.154.188', 'Mozilla/5.0 (X11; U; Linux i686; ru; rv:1.9.2.12) Gecko/20101027 Ubuntu/10.04 (lucid) Firefox/3.6.12', 'http://newwave.in.ua/home', 'blogs', 'edit', 0);
INSERT INTO user_access VALUES (1360, 1296750199, '::ffff:95.134.154.188', 'Mozilla/5.0 (X11; U; Linux i686; ru; rv:1.9.2.12) Gecko/20101027 Ubuntu/10.04 (lucid) Firefox/3.6.12', 'http://newwave.in.ua/home', 'blogs', 'edit', 0);
INSERT INTO user_access VALUES (1360, 1296750220, '::ffff:95.134.154.188', 'Mozilla/5.0 (X11; U; Linux i686; ru; rv:1.9.2.12) Gecko/20101027 Ubuntu/10.04 (lucid) Firefox/3.6.12', 'http://newwave.in.ua/blogs/edit?type=3', 'blogs', 'comments', 0);
INSERT INTO user_access VALUES (1360, 1296750223, '::ffff:95.134.154.188', 'Mozilla/5.0 (X11; U; Linux i686; ru; rv:1.9.2.12) Gecko/20101027 Ubuntu/10.04 (lucid) Firefox/3.6.12', 'http://newwave.in.ua/blogs/comments', 'admin', '', 0);
INSERT INTO user_access VALUES (1360, 1296750225, '::ffff:95.134.154.188', 'Mozilla/5.0 (X11; U; Linux i686; ru; rv:1.9.2.12) Gecko/20101027 Ubuntu/10.04 (lucid) Firefox/3.6.12', 'http://newwave.in.ua/admin', 'profile', 'index', 1360);
INSERT INTO user_access VALUES (1360, 1296750233, '::ffff:95.134.154.188', 'Mozilla/5.0 (X11; U; Linux i686; ru; rv:1.9.2.12) Gecko/20101027 Ubuntu/10.04 (lucid) Firefox/3.6.12', 'http://newwave.in.ua/admin', 'profile', 'index', 1360);
INSERT INTO user_access VALUES (1360, 1296750355, '::ffff:95.134.154.188', 'Mozilla/5.0 (X11; U; Linux i686; ru; rv:1.9.2.12) Gecko/20101027 Ubuntu/10.04 (lucid) Firefox/3.6.12', 'http://newwave.in.ua/admin', 'profile', 'index', 1360);
INSERT INTO user_access VALUES (1360, 1296750760, '::ffff:95.134.154.188', 'Mozilla/5.0 (X11; U; Linux i686; ru; rv:1.9.2.12) Gecko/20101027 Ubuntu/10.04 (lucid) Firefox/3.6.12', 'http://newwave.in.ua/admin', 'profile', 'index', 1360);
INSERT INTO user_access VALUES (1360, 1296750797, '::ffff:95.134.154.188', 'Mozilla/5.0 (X11; U; Linux i686; ru; rv:1.9.2.12) Gecko/20101027 Ubuntu/10.04 (lucid) Firefox/3.6.12', 'http://newwave.in.ua/admin', 'profile', 'index', 1360);
INSERT INTO user_access VALUES (1360, 1296751010, '::ffff:95.134.154.188', 'Mozilla/5.0 (X11; U; Linux i686; ru; rv:1.9.2.12) Gecko/20101027 Ubuntu/10.04 (lucid) Firefox/3.6.12', 'http://newwave.in.ua/admin', 'profile', 'index', 1360);
INSERT INTO user_access VALUES (1360, 1296751573, '::ffff:95.134.154.188', 'Mozilla/5.0 (X11; U; Linux i686; ru; rv:1.9.2.12) Gecko/20101027 Ubuntu/10.04 (lucid) Firefox/3.6.12', 'http://newwave.in.ua/admin', 'profile', 'index', 1360);
INSERT INTO user_access VALUES (1360, 1296751657, '::ffff:95.134.154.188', 'Mozilla/5.0 (X11; U; Linux i686; ru; rv:1.9.2.12) Gecko/20101027 Ubuntu/10.04 (lucid) Firefox/3.6.12', 'http://newwave.in.ua/admin', 'profile', 'index', 1360);
INSERT INTO user_access VALUES (1360, 1296751714, '::ffff:95.134.154.188', 'Mozilla/5.0 (X11; U; Linux i686; ru; rv:1.9.2.12) Gecko/20101027 Ubuntu/10.04 (lucid) Firefox/3.6.12', 'http://newwave.in.ua/admin', 'profile', 'index', 1360);
INSERT INTO user_access VALUES (1360, 1296751862, '::ffff:95.134.154.188', 'Mozilla/5.0 (X11; U; Linux i686; ru; rv:1.9.2.12) Gecko/20101027 Ubuntu/10.04 (lucid) Firefox/3.6.12', 'http://newwave.in.ua/admin', 'profile', 'index', 1360);
INSERT INTO user_access VALUES (1360, 1296751928, '::ffff:95.134.154.188', 'Mozilla/5.0 (X11; U; Linux i686; ru; rv:1.9.2.12) Gecko/20101027 Ubuntu/10.04 (lucid) Firefox/3.6.12', 'http://newwave.in.ua/admin', 'profile', 'index', 1360);
INSERT INTO user_access VALUES (1360, 1296752056, '::ffff:95.134.154.188', 'Mozilla/5.0 (X11; U; Linux i686; ru; rv:1.9.2.12) Gecko/20101027 Ubuntu/10.04 (lucid) Firefox/3.6.12', 'http://newwave.in.ua/admin', 'profile', 'index', 1360);
INSERT INTO user_access VALUES (1360, 1296752063, '::ffff:95.134.154.188', 'Mozilla/5.0 (X11; U; Linux i686; ru; rv:1.9.2.12) Gecko/20101027 Ubuntu/10.04 (lucid) Firefox/3.6.12', 'http://newwave.in.ua/profile-1360', 'home', '', 0);
INSERT INTO user_access VALUES (1360, 1296752092, '::ffff:95.134.154.188', 'Mozilla/5.0 (X11; U; Linux i686; ru; rv:1.9.2.12) Gecko/20101027 Ubuntu/10.04 (lucid) Firefox/3.6.12', 'http://newwave.in.ua/profile-1360', 'home', '', 0);
INSERT INTO user_access VALUES (1360, 1296752108, '::ffff:95.134.154.188', 'Mozilla/5.0 (X11; U; Linux i686; ru; rv:1.9.2.12) Gecko/20101027 Ubuntu/10.04 (lucid) Firefox/3.6.12', 'http://newwave.in.ua/home', 'home', '', 0);
INSERT INTO user_access VALUES (1360, 1296752110, '::ffff:95.134.154.188', 'Mozilla/5.0 (X11; U; Linux i686; ru; rv:1.9.2.12) Gecko/20101027 Ubuntu/10.04 (lucid) Firefox/3.6.12', 'http://newwave.in.ua/home', 'profile', 'index', 1360);
INSERT INTO user_access VALUES (1360, 1296752117, '::ffff:95.134.154.188', 'Mozilla/5.0 (X11; U; Linux i686; ru; rv:1.9.2.12) Gecko/20101027 Ubuntu/10.04 (lucid) Firefox/3.6.12', 'http://newwave.in.ua/profile-1360', 'profile', 'edit', 1360);
INSERT INTO user_access VALUES (1360, 1296752189, '::ffff:95.134.154.188', 'Mozilla/5.0 (X11; U; Linux i686; ru; rv:1.9.2.12) Gecko/20101027 Ubuntu/10.04 (lucid) Firefox/3.6.12', 'http://newwave.in.ua/profile/edit?id=1360', 'profile', 'index', 1360);
INSERT INTO user_access VALUES (1360, 1296752202, '::ffff:95.134.154.188', 'Mozilla/5.0 (X11; U; Linux i686; ru; rv:1.9.2.12) Gecko/20101027 Ubuntu/10.04 (lucid) Firefox/3.6.12', 'http://newwave.in.ua/profile-1360', 'home', '', 0);
INSERT INTO user_access VALUES (1360, 1296752607, '::ffff:95.134.154.188', 'Mozilla/5.0 (X11; U; Linux i686; ru; rv:1.9.2.12) Gecko/20101027 Ubuntu/10.04 (lucid) Firefox/3.6.12', 'http://newwave.in.ua/', 'profile', '', 0);
INSERT INTO user_access VALUES (1360, 1296752619, '::ffff:95.134.154.188', 'Mozilla/5.0 (X11; U; Linux i686; ru; rv:1.9.2.12) Gecko/20101027 Ubuntu/10.04 (lucid) Firefox/3.6.12', 'http://newwave.in.ua/profile', 'home', '', 0);
INSERT INTO user_access VALUES (1360, 1296752707, '::ffff:95.134.154.188', 'Mozilla/5.0 (X11; U; Linux i686; ru; rv:1.9.2.12) Gecko/20101027 Ubuntu/10.04 (lucid) Firefox/3.6.12', 'http://newwave.in.ua/home', 'people', '', 0);
INSERT INTO user_access VALUES (1360, 1296752711, '::ffff:95.134.154.188', 'Mozilla/5.0 (X11; U; Linux i686; ru; rv:1.9.2.12) Gecko/20101027 Ubuntu/10.04 (lucid) Firefox/3.6.12', 'http://newwave.in.ua/people', 'profile', 'index', 5);
INSERT INTO user_access VALUES (1360, 1296752794, '::ffff:95.134.154.188', 'Mozilla/5.0 (X11; U; Linux i686; ru; rv:1.9.2.12) Gecko/20101027 Ubuntu/10.04 (lucid) Firefox/3.6.12', 'http://newwave.in.ua/', 'profile', '', 0);
INSERT INTO user_access VALUES (1360, 1296752823, '::ffff:95.134.154.188', 'Mozilla/5.0 (X11; U; Linux i686; ru; rv:1.9.2.12) Gecko/20101027 Ubuntu/10.04 (lucid) Firefox/3.6.12', 'http://newwave.in.ua/', 'profile', '', 0);
INSERT INTO user_access VALUES (1360, 1296752832, '::ffff:95.134.154.188', 'Mozilla/5.0 (X11; U; Linux i686; ru; rv:1.9.2.12) Gecko/20101027 Ubuntu/10.04 (lucid) Firefox/3.6.12', 'http://newwave.in.ua/profile', 'profile', '', 0);
INSERT INTO user_access VALUES (1360, 1296752933, '::ffff:95.134.154.188', 'Mozilla/5.0 (X11; U; Linux i686; ru; rv:1.9.2.12) Gecko/20101027 Ubuntu/10.04 (lucid) Firefox/3.6.12', 'http://newwave.in.ua/profile', 'profile', '', 0);
INSERT INTO user_access VALUES (1360, 1296752941, '::ffff:95.134.154.188', 'Mozilla/5.0 (X11; U; Linux i686; ru; rv:1.9.2.12) Gecko/20101027 Ubuntu/10.04 (lucid) Firefox/3.6.12', 'http://newwave.in.ua/profile', 'profile', '', 0);
INSERT INTO user_access VALUES (1360, 1296752973, '::ffff:95.134.154.188', 'Mozilla/5.0 (X11; U; Linux i686; ru; rv:1.9.2.12) Gecko/20101027 Ubuntu/10.04 (lucid) Firefox/3.6.12', 'http://newwave.in.ua/profile', 'profile', '', 0);
INSERT INTO user_access VALUES (1360, 1296752981, '::ffff:95.134.154.188', 'Mozilla/5.0 (X11; U; Linux i686; ru; rv:1.9.2.12) Gecko/20101027 Ubuntu/10.04 (lucid) Firefox/3.6.12', 'http://newwave.in.ua/profile', 'polls', '', 0);
INSERT INTO user_access VALUES (1360, 1296752984, '::ffff:95.134.154.188', 'Mozilla/5.0 (X11; U; Linux i686; ru; rv:1.9.2.12) Gecko/20101027 Ubuntu/10.04 (lucid) Firefox/3.6.12', 'http://newwave.in.ua/polls', 'blogs', '', 0);
INSERT INTO user_access VALUES (1360, 1296752989, '::ffff:95.134.154.188', 'Mozilla/5.0 (X11; U; Linux i686; ru; rv:1.9.2.12) Gecko/20101027 Ubuntu/10.04 (lucid) Firefox/3.6.12', 'http://newwave.in.ua/blogs', 'invites', '', 0);
INSERT INTO user_access VALUES (1360, 1296752996, '::ffff:95.134.154.188', 'Mozilla/5.0 (X11; U; Linux i686; ru; rv:1.9.2.12) Gecko/20101027 Ubuntu/10.04 (lucid) Firefox/3.6.12', 'http://newwave.in.ua/invites', 'events', '', 0);
INSERT INTO user_access VALUES (1360, 1296752998, '::ffff:95.134.154.188', 'Mozilla/5.0 (X11; U; Linux i686; ru; rv:1.9.2.12) Gecko/20101027 Ubuntu/10.04 (lucid) Firefox/3.6.12', 'http://newwave.in.ua/events', 'profile', 'desktop', 1360);
INSERT INTO user_access VALUES (1360, 1296753187, '::ffff:95.134.154.188', 'Mozilla/5.0 (X11; U; Linux i686; ru; rv:1.9.2.12) Gecko/20101027 Ubuntu/10.04 (lucid) Firefox/3.6.12', 'http://newwave.in.ua/events', 'profile', 'desktop', 1360);
INSERT INTO user_access VALUES (1360, 1296753414, '::ffff:95.134.154.188', 'Mozilla/5.0 (X11; U; Linux i686; ru; rv:1.9.2.12) Gecko/20101027 Ubuntu/10.04 (lucid) Firefox/3.6.12', 'http://newwave.in.ua/events', 'profile', 'desktop', 1360);
INSERT INTO user_access VALUES (1360, 1296753503, '::ffff:95.134.154.188', 'Mozilla/5.0 (X11; U; Linux i686; ru; rv:1.9.2.12) Gecko/20101027 Ubuntu/10.04 (lucid) Firefox/3.6.12', 'http://newwave.in.ua/events', 'profile', 'desktop', 1360);
INSERT INTO user_access VALUES (1360, 1296753569, '::ffff:95.134.154.188', 'Mozilla/5.0 (X11; U; Linux i686; ru; rv:1.9.2.12) Gecko/20101027 Ubuntu/10.04 (lucid) Firefox/3.6.12', 'http://newwave.in.ua/events', 'profile', 'desktop', 1360);
INSERT INTO user_access VALUES (1360, 1296753580, '::ffff:95.134.154.188', 'Mozilla/5.0 (X11; U; Linux i686; ru; rv:1.9.2.12) Gecko/20101027 Ubuntu/10.04 (lucid) Firefox/3.6.12', 'http://newwave.in.ua/profile/desktop?id=1360', 'profile', 'desktop_edit', 0);
INSERT INTO user_access VALUES (1360, 1296754304, '::ffff:95.134.154.188', 'Mozilla/5.0 (X11; U; Linux i686; ru; rv:1.9.2.12) Gecko/20101027 Ubuntu/10.04 (lucid) Firefox/3.6.12', 'http://newwave.in.ua/profile/desktop_edit?id=&tab=tasks', 'home', '', 0);
INSERT INTO user_access VALUES (1360, 1296758154, '::ffff:95.134.154.188', 'Mozilla/5.0 (X11; U; Linux i686; ru; rv:1.9.2.12) Gecko/20101027 Ubuntu/10.04 (lucid) Firefox/3.6.12', 'http://newwave.in.ua/home', 'people', '', 0);
INSERT INTO user_access VALUES (1360, 1296808763, '::ffff:95.134.100.199', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; ru; rv:1.9.2.13) Gecko/20101203 Firefox/3.6.13', '', 'home', '', 0);
INSERT INTO user_access VALUES (1360, 1296808773, '::ffff:95.134.100.199', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; ru; rv:1.9.2.13) Gecko/20101203 Firefox/3.6.13', '', 'home', '', 0);
INSERT INTO user_access VALUES (1360, 1296810114, '::ffff:95.134.100.199', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; ru; rv:1.9.2.13) Gecko/20101203 Firefox/3.6.13', 'http://newwave.in.ua/home', 'people', '', 0);
INSERT INTO user_access VALUES (1360, 1296810140, '::ffff:95.134.100.199', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; ru; rv:1.9.2.13) Gecko/20101203 Firefox/3.6.13', 'http://newwave.in.ua/people', 'profile', 'index', 1360);
INSERT INTO user_access VALUES (1360, 1296810142, '::ffff:95.134.100.199', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; ru; rv:1.9.2.13) Gecko/20101203 Firefox/3.6.13', 'http://newwave.in.ua/profile-1360', 'profile', 'edit', 1360);
INSERT INTO user_access VALUES (1360, 1296810184, '::ffff:95.134.100.199', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; ru; rv:1.9.2.13) Gecko/20101203 Firefox/3.6.13', 'http://newwave.in.ua/profile/edit?id=1360', 'profile', 'index', 1360);
INSERT INTO user_access VALUES (1360, 1296810189, '::ffff:95.134.100.199', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; ru; rv:1.9.2.13) Gecko/20101203 Firefox/3.6.13', 'http://newwave.in.ua/profile-1360', 'people', '', 0);
INSERT INTO user_access VALUES (2, 1296824182, '::ffff:46.202.70.153', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; uk; rv:1.9.2.13) Gecko/20101203 Firefox/3.6.13', 'http://newwave.in.ua/', 'profile', '', 0);
INSERT INTO user_access VALUES (5, 1296826500, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows; U; Windows NT 6.1; ru; rv:1.9.2.13) Gecko/20101203 Firefox/3.6.13 GTB7.1 ( .NET CLR 3.5.30729; .NET4.0E) WebMoney Advisor', '', 'home', '', 0);
INSERT INTO user_access VALUES (5, 1296826566, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows; U; Windows NT 6.1; ru; rv:1.9.2.13) Gecko/20101203 Firefox/3.6.13 GTB7.1 ( .NET CLR 3.5.30729; .NET4.0E) WebMoney Advisor', 'http://newwave.in.ua/home', 'people', '', 0);
INSERT INTO user_access VALUES (5, 1296826586, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows; U; Windows NT 6.1; ru; rv:1.9.2.13) Gecko/20101203 Firefox/3.6.13 GTB7.1 ( .NET CLR 3.5.30729; .NET4.0E) WebMoney Advisor', 'http://newwave.in.ua/people', 'library', '', 0);
INSERT INTO user_access VALUES (5, 1296826590, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows; U; Windows NT 6.1; ru; rv:1.9.2.13) Gecko/20101203 Firefox/3.6.13 GTB7.1 ( .NET CLR 3.5.30729; .NET4.0E) WebMoney Advisor', 'http://newwave.in.ua/library', 'home', '', 0);
INSERT INTO user_access VALUES (5, 1296826610, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows; U; Windows NT 6.1; ru; rv:1.9.2.13) Gecko/20101203 Firefox/3.6.13 GTB7.1 ( .NET CLR 3.5.30729; .NET4.0E) WebMoney Advisor', 'http://newwave.in.ua/home', 'groups', '', 0);
INSERT INTO user_access VALUES (5, 1296826616, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows; U; Windows NT 6.1; ru; rv:1.9.2.13) Gecko/20101203 Firefox/3.6.13 GTB7.1 ( .NET CLR 3.5.30729; .NET4.0E) WebMoney Advisor', 'http://newwave.in.ua/groups', 'home', '', 0);
INSERT INTO user_access VALUES (5, 1296826630, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows; U; Windows NT 6.1; ru; rv:1.9.2.13) Gecko/20101203 Firefox/3.6.13 GTB7.1 ( .NET CLR 3.5.30729; .NET4.0E) WebMoney Advisor', 'http://newwave.in.ua/home', 'help', '', 0);
INSERT INTO user_access VALUES (5, 1296826632, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows; U; Windows NT 6.1; ru; rv:1.9.2.13) Gecko/20101203 Firefox/3.6.13 GTB7.1 ( .NET CLR 3.5.30729; .NET4.0E) WebMoney Advisor', 'http://newwave.in.ua/groups', 'home', '', 0);
INSERT INTO user_access VALUES (5, 1296826645, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows; U; Windows NT 6.1; ru; rv:1.9.2.13) Gecko/20101203 Firefox/3.6.13 GTB7.1 ( .NET CLR 3.5.30729; .NET4.0E) WebMoney Advisor', 'http://newwave.in.ua/home', 'profile', 'index', 5);
INSERT INTO user_access VALUES (5, 1296826647, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows; U; Windows NT 6.1; ru; rv:1.9.2.13) Gecko/20101203 Firefox/3.6.13 GTB7.1 ( .NET CLR 3.5.30729; .NET4.0E) WebMoney Advisor', 'http://newwave.in.ua/profile-5', 'profile', 'edit', 5);
INSERT INTO user_access VALUES (29, 1296826779, '::ffff:213.227.210.148', 'Mozilla/5.0 (X11; U; Linux x86_64; uk; rv:1.9.2.13) Gecko/20101206 Ubuntu/10.04 (lucid) Firefox/3.6.13', 'http://newwave.in.ua/sign', 'profile', '', 0);
INSERT INTO user_access VALUES (29, 1296826783, '::ffff:213.227.210.148', 'Mozilla/5.0 (X11; U; Linux x86_64; uk; rv:1.9.2.13) Gecko/20101206 Ubuntu/10.04 (lucid) Firefox/3.6.13', 'http://newwave.in.ua/profile', 'home', '', 0);
INSERT INTO user_access VALUES (5, 1296826784, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows; U; Windows NT 6.1; ru; rv:1.9.2.13) Gecko/20101203 Firefox/3.6.13 GTB7.1 ( .NET CLR 3.5.30729; .NET4.0E) WebMoney Advisor', 'http://newwave.in.ua/profile/edit?id=5', 'home', '', 0);
INSERT INTO user_access VALUES (2, 1296826785, '::ffff:46.202.70.153', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; uk; rv:1.9.2.13) Gecko/20101203 Firefox/3.6.13', 'http://newwave.in.ua/profile', 'profile', 'branding', 0);
INSERT INTO user_access VALUES (5, 1296826788, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows; U; Windows NT 6.1; ru; rv:1.9.2.13) Gecko/20101203 Firefox/3.6.13 GTB7.1 ( .NET CLR 3.5.30729; .NET4.0E) WebMoney Advisor', 'http://newwave.in.ua/profile/edit?id=5', 'home', '', 0);
INSERT INTO user_access VALUES (2, 1296826793, '::ffff:46.202.70.153', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; uk; rv:1.9.2.13) Gecko/20101203 Firefox/3.6.13', 'http://newwave.in.ua/profile/branding', 'groups', '', 0);
INSERT INTO user_access VALUES (29, 1296826800, '::ffff:213.227.210.148', 'Mozilla/5.0 (X11; U; Linux x86_64; uk; rv:1.9.2.13) Gecko/20101206 Ubuntu/10.04 (lucid) Firefox/3.6.13', 'http://newwave.in.ua/home', 'people', '', 0);
INSERT INTO user_access VALUES (2, 1296826803, '::ffff:46.202.70.153', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; uk; rv:1.9.2.13) Gecko/20101203 Firefox/3.6.13', 'http://newwave.in.ua/groups', 'home', '', 0);
INSERT INTO user_access VALUES (29, 1296826806, '::ffff:213.227.210.148', 'Mozilla/5.0 (X11; U; Linux x86_64; uk; rv:1.9.2.13) Gecko/20101206 Ubuntu/10.04 (lucid) Firefox/3.6.13', 'http://newwave.in.ua/home', 'people', '', 0);
INSERT INTO user_access VALUES (29, 1296826810, '::ffff:213.227.210.148', 'Mozilla/5.0 (X11; U; Linux x86_64; uk; rv:1.9.2.13) Gecko/20101206 Ubuntu/10.04 (lucid) Firefox/3.6.13', 'http://newwave.in.ua/home', 'people', '', 0);
INSERT INTO user_access VALUES (29, 1296826816, '::ffff:213.227.210.148', 'Mozilla/5.0 (X11; U; Linux x86_64; uk; rv:1.9.2.13) Gecko/20101206 Ubuntu/10.04 (lucid) Firefox/3.6.13', 'http://newwave.in.ua/home', 'people', '', 0);
INSERT INTO user_access VALUES (29, 1296826822, '::ffff:213.227.210.148', 'Mozilla/5.0 (X11; U; Linux x86_64; uk; rv:1.9.2.13) Gecko/20101206 Ubuntu/10.04 (lucid) Firefox/3.6.13', 'http://newwave.in.ua/home', 'people', '', 0);
INSERT INTO user_access VALUES (29, 1296826825, '::ffff:213.227.210.148', 'Mozilla/5.0 (X11; U; Linux x86_64; uk; rv:1.9.2.13) Gecko/20101206 Ubuntu/10.04 (lucid) Firefox/3.6.13', 'http://newwave.in.ua/home', 'people', '', 0);
INSERT INTO user_access VALUES (29, 1296826833, '::ffff:213.227.210.148', 'Mozilla/5.0 (X11; U; Linux x86_64; uk; rv:1.9.2.13) Gecko/20101206 Ubuntu/10.04 (lucid) Firefox/3.6.13', 'http://newwave.in.ua/people', 'blogs', '', 0);
INSERT INTO user_access VALUES (29, 1296826833, '::ffff:213.227.210.148', 'Mozilla/5.0 (X11; U; Linux x86_64; uk; rv:1.9.2.13) Gecko/20101206 Ubuntu/10.04 (lucid) Firefox/3.6.13', 'http://newwave.in.ua/people', 'blogs', '', 0);
INSERT INTO user_access VALUES (29, 1296826834, '::ffff:213.227.210.148', 'Mozilla/5.0 (X11; U; Linux x86_64; uk; rv:1.9.2.13) Gecko/20101206 Ubuntu/10.04 (lucid) Firefox/3.6.13', 'http://newwave.in.ua/blogs', 'groups', '', 0);
INSERT INTO user_access VALUES (29, 1296826836, '::ffff:213.227.210.148', 'Mozilla/5.0 (X11; U; Linux x86_64; uk; rv:1.9.2.13) Gecko/20101206 Ubuntu/10.04 (lucid) Firefox/3.6.13', 'http://newwave.in.ua/groups', 'events', '', 0);
INSERT INTO user_access VALUES (29, 1296826839, '::ffff:213.227.210.148', 'Mozilla/5.0 (X11; U; Linux x86_64; uk; rv:1.9.2.13) Gecko/20101206 Ubuntu/10.04 (lucid) Firefox/3.6.13', 'http://newwave.in.ua/events', 'polls', '', 0);
INSERT INTO user_access VALUES (29, 1296826840, '::ffff:213.227.210.148', 'Mozilla/5.0 (X11; U; Linux x86_64; uk; rv:1.9.2.13) Gecko/20101206 Ubuntu/10.04 (lucid) Firefox/3.6.13', 'http://newwave.in.ua/polls', 'library', '', 0);
INSERT INTO user_access VALUES (29, 1296826844, '::ffff:213.227.210.148', 'Mozilla/5.0 (X11; U; Linux x86_64; uk; rv:1.9.2.13) Gecko/20101206 Ubuntu/10.04 (lucid) Firefox/3.6.13', 'http://newwave.in.ua/library', 'search', '', 0);
INSERT INTO user_access VALUES (29, 1296826845, '::ffff:213.227.210.148', 'Mozilla/5.0 (X11; U; Linux x86_64; uk; rv:1.9.2.13) Gecko/20101206 Ubuntu/10.04 (lucid) Firefox/3.6.13', 'http://newwave.in.ua/search', 'groups', '', 0);
INSERT INTO user_access VALUES (29, 1296826848, '::ffff:213.227.210.148', 'Mozilla/5.0 (X11; U; Linux x86_64; uk; rv:1.9.2.13) Gecko/20101206 Ubuntu/10.04 (lucid) Firefox/3.6.13', 'http://newwave.in.ua/groups', 'help', 'index', 0);
INSERT INTO user_access VALUES (29, 1296826849, '::ffff:213.227.210.148', 'Mozilla/5.0 (X11; U; Linux x86_64; uk; rv:1.9.2.13) Gecko/20101206 Ubuntu/10.04 (lucid) Firefox/3.6.13', 'http://newwave.in.ua/help/index?party', 'home', '', 0);
INSERT INTO user_access VALUES (29, 1296826854, '::ffff:213.227.210.148', 'Mozilla/5.0 (X11; U; Linux x86_64; uk; rv:1.9.2.13) Gecko/20101206 Ubuntu/10.04 (lucid) Firefox/3.6.13', 'http://newwave.in.ua/home', 'help', 'index', 0);
INSERT INTO user_access VALUES (29, 1296826871, '::ffff:213.227.210.148', 'Mozilla/5.0 (X11; U; Linux x86_64; uk; rv:1.9.2.13) Gecko/20101206 Ubuntu/10.04 (lucid) Firefox/3.6.13', 'http://newwave.in.ua/help/index?rules', 'home', '', 0);
INSERT INTO user_access VALUES (2, 1296826878, '::ffff:46.202.70.153', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; uk; rv:1.9.2.13) Gecko/20101203 Firefox/3.6.13', 'http://newwave.in.ua/home', 'profile', 'index', 2);
INSERT INTO user_access VALUES (5, 1296826879, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows; U; Windows NT 6.1; ru; rv:1.9.2.13) Gecko/20101203 Firefox/3.6.13 GTB7.1 ( .NET CLR 3.5.30729; .NET4.0E) WebMoney Advisor', 'http://newwave.in.ua/home', 'profile', 'index', 5);
INSERT INTO user_access VALUES (2, 1296826914, '::ffff:46.202.70.153', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; uk; rv:1.9.2.13) Gecko/20101203 Firefox/3.6.13', 'http://newwave.in.ua/profile-2', 'admin', '', 0);
INSERT INTO user_access VALUES (2, 1296826932, '::ffff:46.202.70.153', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; uk; rv:1.9.2.13) Gecko/20101203 Firefox/3.6.13', 'http://newwave.in.ua/admin', 'home', '', 0);
INSERT INTO user_access VALUES (5, 1296827036, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows; U; Windows NT 6.1; ru; rv:1.9.2.13) Gecko/20101203 Firefox/3.6.13 GTB7.1 ( .NET CLR 3.5.30729; .NET4.0E) WebMoney Advisor', 'http://newwave.in.ua/profile-5', 'people', '', 0);
INSERT INTO user_access VALUES (5, 1296827048, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows; U; Windows NT 6.1; ru; rv:1.9.2.13) Gecko/20101203 Firefox/3.6.13 GTB7.1 ( .NET CLR 3.5.30729; .NET4.0E) WebMoney Advisor', 'http://newwave.in.ua/people', 'profile', 'index', 5);
INSERT INTO user_access VALUES (5, 1296827050, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows; U; Windows NT 6.1; ru; rv:1.9.2.13) Gecko/20101203 Firefox/3.6.13 GTB7.1 ( .NET CLR 3.5.30729; .NET4.0E) WebMoney Advisor', 'http://newwave.in.ua/profile-5', 'profile', 'edit', 5);
INSERT INTO user_access VALUES (5, 1296827071, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows; U; Windows NT 6.1; ru; rv:1.9.2.13) Gecko/20101203 Firefox/3.6.13 GTB7.1 ( .NET CLR 3.5.30729; .NET4.0E) WebMoney Advisor', 'http://newwave.in.ua/profile/edit?id=5', 'profile', 'edit', 5);
INSERT INTO user_access VALUES (2, 1296827171, '::ffff:46.202.70.153', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; uk; rv:1.9.2.13) Gecko/20101203 Firefox/3.6.13', 'http://newwave.in.ua/home', 'profile', 'index', 2);
INSERT INTO user_access VALUES (2, 1296827197, '::ffff:46.202.70.153', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; uk; rv:1.9.2.13) Gecko/20101203 Firefox/3.6.13', 'http://newwave.in.ua/home', 'profile', 'index', 2);
INSERT INTO user_access VALUES (29, 1296827589, '::ffff:213.227.210.148', 'Mozilla/5.0 (X11; U; Linux x86_64; uk; rv:1.9.2.13) Gecko/20101206 Ubuntu/10.04 (lucid) Firefox/3.6.13', '', 'home', '', 0);
INSERT INTO user_access VALUES (29, 1296827593, '::ffff:213.227.210.148', 'Mozilla/5.0 (X11; U; Linux x86_64; uk; rv:1.9.2.13) Gecko/20101206 Ubuntu/10.04 (lucid) Firefox/3.6.13', 'http://newwave.in.ua/home', 'admin', '', 0);
INSERT INTO user_access VALUES (29, 1296827594, '::ffff:213.227.210.148', 'Mozilla/5.0 (X11; U; Linux x86_64; uk; rv:1.9.2.13) Gecko/20101206 Ubuntu/10.04 (lucid) Firefox/3.6.13', 'http://newwave.in.ua/home', 'profile', 'index', 29);
INSERT INTO user_access VALUES (29, 1296827594, '::ffff:213.227.210.148', 'Mozilla/5.0 (X11; U; Linux x86_64; uk; rv:1.9.2.13) Gecko/20101206 Ubuntu/10.04 (lucid) Firefox/3.6.13', 'http://newwave.in.ua/admin', 'profile', 'index', 29);
INSERT INTO user_access VALUES (29, 1296827597, '::ffff:213.227.210.148', 'Mozilla/5.0 (X11; U; Linux x86_64; uk; rv:1.9.2.13) Gecko/20101206 Ubuntu/10.04 (lucid) Firefox/3.6.13', 'http://newwave.in.ua/profile-29', 'profile', 'edit', 29);
INSERT INTO user_access VALUES (29, 1296827617, '::ffff:213.227.210.148', 'Mozilla/5.0 (X11; U; Linux x86_64; uk; rv:1.9.2.13) Gecko/20101206 Ubuntu/10.04 (lucid) Firefox/3.6.13', 'http://newwave.in.ua/profile/edit?id=29', 'profile', 'edit', 0);
INSERT INTO user_access VALUES (29, 1296827622, '::ffff:213.227.210.148', 'Mozilla/5.0 (X11; U; Linux x86_64; uk; rv:1.9.2.13) Gecko/20101206 Ubuntu/10.04 (lucid) Firefox/3.6.13', 'http://newwave.in.ua/profile/edit?tab=photo', 'friends', '', 0);
INSERT INTO user_access VALUES (29, 1296827625, '::ffff:213.227.210.148', 'Mozilla/5.0 (X11; U; Linux x86_64; uk; rv:1.9.2.13) Gecko/20101206 Ubuntu/10.04 (lucid) Firefox/3.6.13', 'http://newwave.in.ua/friends', 'people', '', 0);
INSERT INTO user_access VALUES (29, 1296827635, '::ffff:213.227.210.148', 'Mozilla/5.0 (X11; U; Linux x86_64; uk; rv:1.9.2.13) Gecko/20101206 Ubuntu/10.04 (lucid) Firefox/3.6.13', 'http://newwave.in.ua/people', 'profile', 'index', 5);
INSERT INTO user_access VALUES (29, 1296827636, '::ffff:213.227.210.148', 'Mozilla/5.0 (X11; U; Linux x86_64; uk; rv:1.9.2.13) Gecko/20101206 Ubuntu/10.04 (lucid) Firefox/3.6.13', 'http://newwave.in.ua/profile-5', 'profile', 'edit', 5);
INSERT INTO user_access VALUES (29, 1296827747, '::ffff:213.227.210.148', 'Mozilla/5.0 (X11; U; Linux x86_64; uk; rv:1.9.2.13) Gecko/20101206 Ubuntu/10.04 (lucid) Firefox/3.6.13', 'http://newwave.in.ua/profile/edit?id=5', 'profile', 'edit', 0);
INSERT INTO user_access VALUES (29, 1296827753, '::ffff:213.227.210.148', 'Mozilla/5.0 (X11; U; Linux x86_64; uk; rv:1.9.2.13) Gecko/20101206 Ubuntu/10.04 (lucid) Firefox/3.6.13', 'http://newwave.in.ua/profile/edit?tab=photo', 'people', '', 0);
INSERT INTO user_access VALUES (29, 1296827755, '::ffff:213.227.210.148', 'Mozilla/5.0 (X11; U; Linux x86_64; uk; rv:1.9.2.13) Gecko/20101206 Ubuntu/10.04 (lucid) Firefox/3.6.13', 'http://newwave.in.ua/people', 'profile', 'index', 29);
INSERT INTO user_access VALUES (29, 1296827758, '::ffff:213.227.210.148', 'Mozilla/5.0 (X11; U; Linux x86_64; uk; rv:1.9.2.13) Gecko/20101206 Ubuntu/10.04 (lucid) Firefox/3.6.13', 'http://newwave.in.ua/profile-29', 'profile', 'branding', 0);
INSERT INTO user_access VALUES (29, 1296827764, '::ffff:213.227.210.148', 'Mozilla/5.0 (X11; U; Linux x86_64; uk; rv:1.9.2.13) Gecko/20101206 Ubuntu/10.04 (lucid) Firefox/3.6.13', 'http://newwave.in.ua/profile/branding', 'profile', 'branding', 0);
INSERT INTO user_access VALUES (29, 1296828239, '::ffff:213.227.210.148', 'Mozilla/5.0 (X11; U; Linux x86_64; uk; rv:1.9.2.13) Gecko/20101206 Ubuntu/10.04 (lucid) Firefox/3.6.13', 'http://newwave.in.ua/profile/branding', 'home', '', 0);
INSERT INTO user_access VALUES (29, 1296828241, '::ffff:213.227.210.148', 'Mozilla/5.0 (X11; U; Linux x86_64; uk; rv:1.9.2.13) Gecko/20101206 Ubuntu/10.04 (lucid) Firefox/3.6.13', 'http://newwave.in.ua/home', 'people', '', 0);
INSERT INTO user_access VALUES (1360, 1296830640, '::ffff:95.134.184.254', 'Mozilla/5.0 (X11; U; Linux i686; ru; rv:1.9.2.12) Gecko/20101027 Ubuntu/10.04 (lucid) Firefox/3.6.12', '', 'home', '', 0);
INSERT INTO user_access VALUES (1360, 1296830665, '::ffff:95.134.184.254', 'Mozilla/5.0 (X11; U; Linux i686; ru; rv:1.9.2.12) Gecko/20101027 Ubuntu/10.04 (lucid) Firefox/3.6.12', 'http://newwave.in.ua/home', 'people', '', 0);
INSERT INTO user_access VALUES (5, 1296832116, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows; U; Windows NT 6.1; ru; rv:1.9.2.13) Gecko/20101203 Firefox/3.6.13 GTB7.1 ( .NET CLR 3.5.30729; .NET4.0E) WebMoney Advisor', '', 'home', '', 0);
INSERT INTO user_access VALUES (5, 1296832116, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows; U; Windows NT 6.1; ru; rv:1.9.2.13) Gecko/20101203 Firefox/3.6.13 GTB7.1 ( .NET CLR 3.5.30729; .NET4.0E) WebMoney Advisor', '', 'home', '', 0);
INSERT INTO user_access VALUES (2, 1296883986, '::ffff:46.202.4.196', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; uk; rv:1.9.2.13) Gecko/20101203 Firefox/3.6.13', 'http://newwave.in.ua/home', 'profile', 'index', 2);
INSERT INTO user_access VALUES (2, 1296885211, '::ffff:46.202.4.196', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; uk; rv:1.9.2.13) Gecko/20101203 Firefox/3.6.13', 'http://newwave.in.ua/profile-2', 'search', '', 0);
INSERT INTO user_access VALUES (2, 1296885217, '::ffff:46.202.4.196', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; uk; rv:1.9.2.13) Gecko/20101203 Firefox/3.6.13', 'http://newwave.in.ua/home', 'profile', 'index', 2);
INSERT INTO user_access VALUES (2, 1296885225, '::ffff:46.202.4.196', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; uk; rv:1.9.2.13) Gecko/20101203 Firefox/3.6.13', 'http://newwave.in.ua/profile-2', 'library', '', 0);
INSERT INTO user_access VALUES (2, 1296885232, '::ffff:46.202.4.196', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; uk; rv:1.9.2.13) Gecko/20101203 Firefox/3.6.13', 'http://newwave.in.ua/profile-2', 'library', '', 0);
INSERT INTO user_access VALUES (2, 1296896771, '::ffff:46.202.61.16', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; uk; rv:1.9.2.13) Gecko/20101203 Firefox/3.6.13', 'http://newwave.in.ua/profile-2', 'library', '', 0);
INSERT INTO user_access VALUES (1360, 1296897265, '::ffff:95.134.222.247', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; ru; rv:1.9.2.13) Gecko/20101203 Firefox/3.6.13', 'http://newwave.in.ua/profile-1360', 'people', '', 0);
INSERT INTO user_access VALUES (5, 1296898326, '::ffff:93.75.185.122', 'Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 5.1; Trident/4.0; WebMoney Advisor; GTB5; .NET CLR 1.1.4322; .NET CLR 2.0.50727; InfoPath.1; .NET CLR 3.0.4506.2152; .NET CLR 3.5.30729)', 'http://newwave.in.ua/', 'profile', '', 0);
INSERT INTO user_access VALUES (5, 1296898349, '::ffff:93.75.185.122', 'Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 5.1; Trident/4.0; WebMoney Advisor; GTB5; .NET CLR 1.1.4322; .NET CLR 2.0.50727; InfoPath.1; .NET CLR 3.0.4506.2152; .NET CLR 3.5.30729)', 'http://newwave.in.ua/profile', 'profile', 'edit', 5);
INSERT INTO user_access VALUES (5, 1296898408, '::ffff:93.75.185.122', 'Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 5.1; Trident/4.0; WebMoney Advisor; GTB5; .NET CLR 1.1.4322; .NET CLR 2.0.50727; InfoPath.1; .NET CLR 3.0.4506.2152; .NET CLR 3.5.30729)', 'http://newwave.in.ua/profile/edit?id=5', 'profile', 'index', 5);
INSERT INTO user_access VALUES (5, 1296898422, '::ffff:93.75.185.122', 'Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 5.1; Trident/4.0; WebMoney Advisor; GTB5; .NET CLR 1.1.4322; .NET CLR 2.0.50727; InfoPath.1; .NET CLR 3.0.4506.2152; .NET CLR 3.5.30729)', 'http://newwave.in.ua/profile-5', 'profile', 'edit', 5);
INSERT INTO user_access VALUES (5, 1296898478, '::ffff:93.75.185.122', 'Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 5.1; Trident/4.0; WebMoney Advisor; GTB5; .NET CLR 1.1.4322; .NET CLR 2.0.50727; InfoPath.1; .NET CLR 3.0.4506.2152; .NET CLR 3.5.30729)', 'http://newwave.in.ua/profile/edit?id=5', 'profile', 'index', 5);
INSERT INTO user_access VALUES (5, 1296898529, '::ffff:93.75.185.122', 'Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 5.1; Trident/4.0; WebMoney Advisor; GTB5; .NET CLR 1.1.4322; .NET CLR 2.0.50727; InfoPath.1; .NET CLR 3.0.4506.2152; .NET CLR 3.5.30729)', 'http://newwave.in.ua/profile/edit?id=5', 'profile', 'index', 5);
INSERT INTO user_access VALUES (2, 1296902219, '::ffff:46.202.70.94', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; uk; rv:1.9.2.13) Gecko/20101203 Firefox/3.6.13', 'http://newwave.in.ua/profile-2', 'library', '', 0);
INSERT INTO user_access VALUES (2, 1296903480, '::ffff:46.202.70.94', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; uk; rv:1.9.2.13) Gecko/20101203 Firefox/3.6.13', 'http://newwave.in.ua/library', 'home', '', 0);
INSERT INTO user_access VALUES (5, 1296905278, '::ffff:93.75.185.122', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; ru; rv:1.9.2.13) Gecko/20101203 Firefox/3.6.13 GTB7.1', 'http://newwave.in.ua/', 'profile', '', 0);
INSERT INTO user_access VALUES (5, 1296905289, '::ffff:93.75.185.122', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; ru; rv:1.9.2.13) Gecko/20101203 Firefox/3.6.13 GTB7.1', 'http://newwave.in.ua/profile', 'home', '', 0);
INSERT INTO user_access VALUES (5, 1296914515, '::ffff:93.75.185.122', 'Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 5.1; Trident/4.0; WebMoney Advisor; GTB5; .NET CLR 1.1.4322; .NET CLR 2.0.50727; InfoPath.1; .NET CLR 3.0.4506.2152; .NET CLR 3.5.30729)', '', 'home', '', 0);
INSERT INTO user_access VALUES (5, 1296914520, '::ffff:93.75.185.122', 'Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 5.1; Trident/4.0; WebMoney Advisor; GTB5; .NET CLR 1.1.4322; .NET CLR 2.0.50727; InfoPath.1; .NET CLR 3.0.4506.2152; .NET CLR 3.5.30729)', '', 'home', '', 0);
INSERT INTO user_access VALUES (1360, 1296922396, '::ffff:95.134.155.235', 'Mozilla/5.0 (X11; U; Linux i686; ru; rv:1.9.2.12) Gecko/20101027 Ubuntu/10.04 (lucid) Firefox/3.6.12', '', 'home', '', 0);
INSERT INTO user_access VALUES (1360, 1296922939, '::ffff:95.134.155.235', 'Mozilla/5.0 (X11; U; Linux i686; ru; rv:1.9.2.12) Gecko/20101027 Ubuntu/10.04 (lucid) Firefox/3.6.12', '', 'home', '', 0);
INSERT INTO user_access VALUES (1360, 1296923213, '::ffff:95.134.155.235', 'Mozilla/5.0 (X11; U; Linux i686; ru; rv:1.9.2.12) Gecko/20101027 Ubuntu/10.04 (lucid) Firefox/3.6.12', '', 'home', '', 0);
INSERT INTO user_access VALUES (1360, 1296923328, '::ffff:95.134.155.235', 'Mozilla/5.0 (X11; U; Linux i686; ru; rv:1.9.2.12) Gecko/20101027 Ubuntu/10.04 (lucid) Firefox/3.6.12', '', 'home', '', 0);
INSERT INTO user_access VALUES (1360, 1296923521, '::ffff:95.134.155.235', 'Mozilla/5.0 (X11; U; Linux i686; ru; rv:1.9.2.12) Gecko/20101027 Ubuntu/10.04 (lucid) Firefox/3.6.12', '', 'home', '', 0);
INSERT INTO user_access VALUES (1360, 1296923577, '::ffff:95.134.155.235', 'Mozilla/5.0 (X11; U; Linux i686; ru; rv:1.9.2.12) Gecko/20101027 Ubuntu/10.04 (lucid) Firefox/3.6.12', '', 'home', '', 0);
INSERT INTO user_access VALUES (1360, 1296923607, '::ffff:95.134.155.235', 'Mozilla/5.0 (X11; U; Linux i686; ru; rv:1.9.2.12) Gecko/20101027 Ubuntu/10.04 (lucid) Firefox/3.6.12', '', 'home', '', 0);
INSERT INTO user_access VALUES (1360, 1296923773, '::ffff:95.134.155.235', 'Mozilla/5.0 (X11; U; Linux i686; ru; rv:1.9.2.12) Gecko/20101027 Ubuntu/10.04 (lucid) Firefox/3.6.12', '', 'home', '', 0);
INSERT INTO user_access VALUES (1360, 1296923843, '::ffff:95.134.155.235', 'Mozilla/5.0 (X11; U; Linux i686; ru; rv:1.9.2.12) Gecko/20101027 Ubuntu/10.04 (lucid) Firefox/3.6.12', 'http://newwave.in.ua/home', 'profile', 'index', 1360);
INSERT INTO user_access VALUES (1360, 1296923907, '::ffff:95.134.155.235', 'Mozilla/5.0 (X11; U; Linux i686; ru; rv:1.9.2.12) Gecko/20101027 Ubuntu/10.04 (lucid) Firefox/3.6.12', 'http://newwave.in.ua/home', 'profile', 'index', 1360);
INSERT INTO user_access VALUES (1360, 1296924016, '::ffff:95.134.155.235', 'Mozilla/5.0 (X11; U; Linux i686; ru; rv:1.9.2.12) Gecko/20101027 Ubuntu/10.04 (lucid) Firefox/3.6.12', 'http://newwave.in.ua/home', 'profile', 'index', 1360);
INSERT INTO user_access VALUES (1360, 1296924027, '::ffff:95.134.155.235', 'Mozilla/5.0 (X11; U; Linux i686; ru; rv:1.9.2.12) Gecko/20101027 Ubuntu/10.04 (lucid) Firefox/3.6.12', 'http://newwave.in.ua/profile-1360', 'profile', 'edit', 1360);
INSERT INTO user_access VALUES (1360, 1296924040, '::ffff:95.134.155.235', 'Mozilla/5.0 (X11; U; Linux i686; ru; rv:1.9.2.12) Gecko/20101027 Ubuntu/10.04 (lucid) Firefox/3.6.12', 'http://newwave.in.ua/profile/edit?id=1360', 'profile', 'index', 1360);
INSERT INTO user_access VALUES (1360, 1296924164, '::ffff:95.134.155.235', 'Mozilla/5.0 (X11; U; Linux i686; ru; rv:1.9.2.12) Gecko/20101027 Ubuntu/10.04 (lucid) Firefox/3.6.12', 'http://newwave.in.ua/profile/edit?id=1360', 'profile', 'index', 1360);
INSERT INTO user_access VALUES (1360, 1296924213, '::ffff:95.134.155.235', 'Mozilla/5.0 (X11; U; Linux i686; ru; rv:1.9.2.12) Gecko/20101027 Ubuntu/10.04 (lucid) Firefox/3.6.12', 'http://newwave.in.ua/profile-1360', 'library', '', 0);
INSERT INTO user_access VALUES (1360, 1296924402, '::ffff:95.134.155.235', 'Mozilla/5.0 (X11; U; Linux i686; ru; rv:1.9.2.12) Gecko/20101027 Ubuntu/10.04 (lucid) Firefox/3.6.12', 'http://newwave.in.ua/profile-1360', 'library', '', 0);
INSERT INTO user_access VALUES (1360, 1296924866, '::ffff:95.134.155.235', 'Mozilla/5.0 (X11; U; Linux i686; ru; rv:1.9.2.12) Gecko/20101027 Ubuntu/10.04 (lucid) Firefox/3.6.12', '', 'home', '', 0);
INSERT INTO user_access VALUES (1360, 1296924968, '::ffff:95.134.155.235', 'Mozilla/5.0 (X11; U; Linux i686; ru; rv:1.9.2.12) Gecko/20101027 Ubuntu/10.04 (lucid) Firefox/3.6.12', 'http://newwave.in.ua/home', 'profile', 'index', 1360);
INSERT INTO user_access VALUES (1360, 1296924972, '::ffff:95.134.155.235', 'Mozilla/5.0 (X11; U; Linux i686; ru; rv:1.9.2.12) Gecko/20101027 Ubuntu/10.04 (lucid) Firefox/3.6.12', 'http://newwave.in.ua/profile-1360', 'profile', 'edit', 1360);
INSERT INTO user_access VALUES (1360, 1296924975, '::ffff:95.134.155.235', 'Mozilla/5.0 (X11; U; Linux i686; ru; rv:1.9.2.12) Gecko/20101027 Ubuntu/10.04 (lucid) Firefox/3.6.12', 'http://newwave.in.ua/home', 'profile', 'index', 1360);
INSERT INTO user_access VALUES (1360, 1296924978, '::ffff:95.134.155.235', 'Mozilla/5.0 (X11; U; Linux i686; ru; rv:1.9.2.12) Gecko/20101027 Ubuntu/10.04 (lucid) Firefox/3.6.12', 'http://newwave.in.ua/profile-1360', 'profile', 'branding', 0);
INSERT INTO user_access VALUES (1360, 1296924996, '::ffff:95.134.155.235', 'Mozilla/5.0 (X11; U; Linux i686; ru; rv:1.9.2.12) Gecko/20101027 Ubuntu/10.04 (lucid) Firefox/3.6.12', 'http://newwave.in.ua/profile/branding', 'profile', 'branding', 0);
INSERT INTO user_access VALUES (1360, 1296925460, '::ffff:95.134.155.235', 'Mozilla/5.0 (X11; U; Linux i686; ru; rv:1.9.2.12) Gecko/20101027 Ubuntu/10.04 (lucid) Firefox/3.6.12', 'http://newwave.in.ua/profile/branding', 'profile', 'branding', 0);
INSERT INTO user_access VALUES (1360, 1296925524, '::ffff:95.134.155.235', 'Mozilla/5.0 (X11; U; Linux i686; ru; rv:1.9.2.12) Gecko/20101027 Ubuntu/10.04 (lucid) Firefox/3.6.12', 'http://newwave.in.ua/profile/branding', 'profile', 'branding', 0);
INSERT INTO user_access VALUES (1360, 1296925545, '::ffff:95.134.155.235', 'Mozilla/5.0 (X11; U; Linux i686; ru; rv:1.9.2.12) Gecko/20101027 Ubuntu/10.04 (lucid) Firefox/3.6.12', 'http://newwave.in.ua/profile/branding', 'blogs', '', 0);
INSERT INTO user_access VALUES (1360, 1296925548, '::ffff:95.134.155.235', 'Mozilla/5.0 (X11; U; Linux i686; ru; rv:1.9.2.12) Gecko/20101027 Ubuntu/10.04 (lucid) Firefox/3.6.12', 'http://newwave.in.ua/blogs', 'blogs', 'edit', 0);
INSERT INTO user_access VALUES (1360, 1296925571, '::ffff:95.134.155.235', 'Mozilla/5.0 (X11; U; Linux i686; ru; rv:1.9.2.12) Gecko/20101027 Ubuntu/10.04 (lucid) Firefox/3.6.12', 'http://newwave.in.ua/blogs/edit', 'profile', 'upload', 0);
INSERT INTO user_access VALUES (1360, 1296925696, '::ffff:95.134.155.235', 'Mozilla/5.0 (X11; U; Linux i686; ru; rv:1.9.2.12) Gecko/20101027 Ubuntu/10.04 (lucid) Firefox/3.6.12', 'http://newwave.in.ua/profile/branding', 'blogs', '', 0);
INSERT INTO user_access VALUES (1360, 1296926067, '::ffff:95.134.155.235', 'Mozilla/5.0 (X11; U; Linux i686; ru; rv:1.9.2.12) Gecko/20101027 Ubuntu/10.04 (lucid) Firefox/3.6.12', 'http://newwave.in.ua/blogs', 'home', '', 0);
INSERT INTO user_access VALUES (5, 1296928155, '::ffff:93.75.185.122', 'Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 5.1; Trident/4.0; WebMoney Advisor; GTB5; .NET CLR 1.1.4322; .NET CLR 2.0.50727; InfoPath.1; .NET CLR 3.0.4506.2152; .NET CLR 3.5.30729)', '', 'home', '', 0);
INSERT INTO user_access VALUES (5, 1296928189, '::ffff:93.75.185.122', 'Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 5.1; Trident/4.0; WebMoney Advisor; GTB5; .NET CLR 1.1.4322; .NET CLR 2.0.50727; InfoPath.1; .NET CLR 3.0.4506.2152; .NET CLR 3.5.30729)', 'http://newwave.in.ua/home', 'people', '', 0);
INSERT INTO user_access VALUES (5, 1296928192, '::ffff:93.75.185.122', 'Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 5.1; Trident/4.0; WebMoney Advisor; GTB5; .NET CLR 1.1.4322; .NET CLR 2.0.50727; InfoPath.1; .NET CLR 3.0.4506.2152; .NET CLR 3.5.30729)', 'http://newwave.in.ua/people', 'groups', '', 0);
INSERT INTO user_access VALUES (5, 1296928209, '::ffff:93.75.185.122', 'Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 5.1; Trident/4.0; WebMoney Advisor; GTB5; .NET CLR 1.1.4322; .NET CLR 2.0.50727; InfoPath.1; .NET CLR 3.0.4506.2152; .NET CLR 3.5.30729)', 'http://newwave.in.ua/groups', 'profile', 'index', 5);
INSERT INTO user_access VALUES (5, 1296928223, '::ffff:93.75.185.122', 'Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 5.1; Trident/4.0; WebMoney Advisor; GTB5; .NET CLR 1.1.4322; .NET CLR 2.0.50727; InfoPath.1; .NET CLR 3.0.4506.2152; .NET CLR 3.5.30729)', 'http://newwave.in.ua/profile-5', 'people', '', 0);
INSERT INTO user_access VALUES (5, 1296928232, '::ffff:93.75.185.122', 'Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 5.1; Trident/4.0; WebMoney Advisor; GTB5; .NET CLR 1.1.4322; .NET CLR 2.0.50727; InfoPath.1; .NET CLR 3.0.4506.2152; .NET CLR 3.5.30729)', 'http://newwave.in.ua/people', 'profile', 'index', 2);
INSERT INTO user_access VALUES (5, 1296928274, '::ffff:93.75.185.122', 'Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 5.1; Trident/4.0; WebMoney Advisor; GTB5; .NET CLR 1.1.4322; .NET CLR 2.0.50727; InfoPath.1; .NET CLR 3.0.4506.2152; .NET CLR 3.5.30729)', 'http://newwave.in.ua/profile-2', 'people', '', 0);
INSERT INTO user_access VALUES (5, 1296928289, '::ffff:93.75.185.122', 'Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 5.1; Trident/4.0; WebMoney Advisor; GTB5; .NET CLR 1.1.4322; .NET CLR 2.0.50727; InfoPath.1; .NET CLR 3.0.4506.2152; .NET CLR 3.5.30729)', 'http://newwave.in.ua/people', 'people', '', 0);
INSERT INTO user_access VALUES (5, 1296928310, '::ffff:93.75.185.122', 'Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 5.1; Trident/4.0; WebMoney Advisor; GTB5; .NET CLR 1.1.4322; .NET CLR 2.0.50727; InfoPath.1; .NET CLR 3.0.4506.2152; .NET CLR 3.5.30729)', 'http://newwave.in.ua/people', 'library', '', 0);
INSERT INTO user_access VALUES (5, 1296928319, '::ffff:93.75.185.122', 'Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 5.1; Trident/4.0; WebMoney Advisor; GTB5; .NET CLR 1.1.4322; .NET CLR 2.0.50727; InfoPath.1; .NET CLR 3.0.4506.2152; .NET CLR 3.5.30729)', 'http://newwave.in.ua/library', 'home', '', 0);
INSERT INTO user_access VALUES (5, 1296928335, '::ffff:93.75.185.122', 'Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 5.1; Trident/4.0; WebMoney Advisor; GTB5; .NET CLR 1.1.4322; .NET CLR 2.0.50727; InfoPath.1; .NET CLR 3.0.4506.2152; .NET CLR 3.5.30729)', 'http://newwave.in.ua/home', 'people', '', 0);
INSERT INTO user_access VALUES (5, 1296928339, '::ffff:93.75.185.122', 'Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 5.1; Trident/4.0; WebMoney Advisor; GTB5; .NET CLR 1.1.4322; .NET CLR 2.0.50727; InfoPath.1; .NET CLR 3.0.4506.2152; .NET CLR 3.5.30729)', 'http://newwave.in.ua/people', 'home', '', 0);
INSERT INTO user_access VALUES (1360, 1297002467, '::ffff:95.134.83.106', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; ru; rv:1.9.2.13) Gecko/20101203 Firefox/3.6.13', '', 'home', '', 0);
INSERT INTO user_access VALUES (1360, 1297002488, '::ffff:95.134.83.106', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; ru; rv:1.9.2.13) Gecko/20101203 Firefox/3.6.13', 'http://newwave.in.ua/home', 'people', '', 0);
INSERT INTO user_access VALUES (5, 1297010083, '::ffff:93.75.185.122', 'Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 5.1; Trident/4.0; WebMoney Advisor; GTB5; .NET CLR 1.1.4322; .NET CLR 2.0.50727; InfoPath.1; .NET CLR 3.0.4506.2152; .NET CLR 3.5.30729)', '', 'home', '', 0);
INSERT INTO user_access VALUES (5, 1297010096, '::ffff:93.75.185.122', 'Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 5.1; Trident/4.0; WebMoney Advisor; GTB5; .NET CLR 1.1.4322; .NET CLR 2.0.50727; InfoPath.1; .NET CLR 3.0.4506.2152; .NET CLR 3.5.30729)', 'http://newwave.in.ua/home', 'people', '', 0);
INSERT INTO user_access VALUES (5, 1297010116, '::ffff:93.75.185.122', 'Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 5.1; Trident/4.0; WebMoney Advisor; GTB5; .NET CLR 1.1.4322; .NET CLR 2.0.50727; InfoPath.1; .NET CLR 3.0.4506.2152; .NET CLR 3.5.30729)', 'http://newwave.in.ua/people', 'home', '', 0);
INSERT INTO user_access VALUES (5, 1297010121, '::ffff:93.75.185.122', 'Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 5.1; Trident/4.0; WebMoney Advisor; GTB5; .NET CLR 1.1.4322; .NET CLR 2.0.50727; InfoPath.1; .NET CLR 3.0.4506.2152; .NET CLR 3.5.30729)', 'http://newwave.in.ua/home', 'home', '', 0);
INSERT INTO user_access VALUES (5, 1297010126, '::ffff:93.75.185.122', 'Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 5.1; Trident/4.0; WebMoney Advisor; GTB5; .NET CLR 1.1.4322; .NET CLR 2.0.50727; InfoPath.1; .NET CLR 3.0.4506.2152; .NET CLR 3.5.30729)', 'http://newwave.in.ua/home', 'people', '', 0);
INSERT INTO user_access VALUES (2, 1297018418, '::ffff:46.202.130.124', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; uk; rv:1.9.2.13) Gecko/20101203 Firefox/3.6.13', '', 'home', '', 0);
INSERT INTO user_access VALUES (2, 1297018446, '::ffff:46.202.130.124', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; uk; rv:1.9.2.13) Gecko/20101203 Firefox/3.6.13', 'http://newwave.in.ua/home', 'profile', 'index', 2);
INSERT INTO user_access VALUES (2, 1297018455, '::ffff:46.202.130.124', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; uk; rv:1.9.2.13) Gecko/20101203 Firefox/3.6.13', 'http://newwave.in.ua/home', 'profile', 'index', 2);
INSERT INTO user_access VALUES (2, 1297018461, '::ffff:46.202.130.124', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; uk; rv:1.9.2.13) Gecko/20101203 Firefox/3.6.13', 'http://newwave.in.ua/profile-2', 'profile', 'edit', 2);
INSERT INTO user_access VALUES (2, 1297018480, '::ffff:46.202.130.124', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; uk; rv:1.9.2.13) Gecko/20101203 Firefox/3.6.13', 'http://newwave.in.ua/profile/edit?id=2', 'profile', 'index', 2);
INSERT INTO user_access VALUES (2, 1297018484, '::ffff:46.202.130.124', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; uk; rv:1.9.2.13) Gecko/20101203 Firefox/3.6.13', 'http://newwave.in.ua/profile-2', 'profile', 'edit', 2);
INSERT INTO user_access VALUES (2, 1297019624, '::ffff:46.202.130.124', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; uk; rv:1.9.2.13) Gecko/20101203 Firefox/3.6.13', 'http://newwave.in.ua/profile/edit?id=2', 'home', '', 0);
INSERT INTO user_access VALUES (2, 1297019628, '::ffff:46.202.130.124', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; uk; rv:1.9.2.13) Gecko/20101203 Firefox/3.6.13', 'http://newwave.in.ua/home', 'people', '', 0);
INSERT INTO user_access VALUES (1360, 1297071953, '::ffff:95.134.191.157', 'Mozilla/5.0 (X11; U; Linux i686; ru; rv:1.9.2.12) Gecko/20101027 Ubuntu/10.04 (lucid) Firefox/3.6.12', 'http://newwave.in.ua/blogs', 'home', '', 0);
INSERT INTO user_access VALUES (2, 1297095021, '::ffff:46.202.152.216', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; uk; rv:1.9.2.13) Gecko/20101203 Firefox/3.6.13', '', 'home', '', 0);
INSERT INTO user_access VALUES (2, 1297095031, '::ffff:46.202.152.216', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; uk; rv:1.9.2.13) Gecko/20101203 Firefox/3.6.13', 'http://newwave.in.ua/home', 'profile', 'index', 2);
INSERT INTO user_access VALUES (1360, 1297109887, '::ffff:95.134.109.250', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; ru; rv:1.9.2.13) Gecko/20101203 Firefox/3.6.13', '', 'home', '', 0);
INSERT INTO user_access VALUES (1360, 1297109894, '::ffff:95.134.109.250', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; ru; rv:1.9.2.13) Gecko/20101203 Firefox/3.6.13', '', 'home', '', 0);
INSERT INTO user_access VALUES (2, 1297157086, '::ffff:46.202.97.124', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; uk; rv:1.9.2.13) Gecko/20101203 Firefox/3.6.13', 'http://newwave.in.ua/profile-2', 'invites', '', 0);
INSERT INTO user_access VALUES (2, 1297157094, '::ffff:46.202.97.124', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; uk; rv:1.9.2.13) Gecko/20101203 Firefox/3.6.13', 'http://newwave.in.ua/invites', 'home', '', 0);
INSERT INTO user_access VALUES (2, 1297157100, '::ffff:46.202.97.124', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; uk; rv:1.9.2.13) Gecko/20101203 Firefox/3.6.13', 'http://newwave.in.ua/home', 'people', '', 0);
INSERT INTO user_access VALUES (29, 1297239595, '::ffff:213.227.210.148', 'Mozilla/5.0 (X11; U; Linux x86_64; uk; rv:1.9.2.13) Gecko/20101206 Ubuntu/10.04 (lucid) Firefox/3.6.13', '', 'home', '', 0);
INSERT INTO user_access VALUES (29, 1297239619, '::ffff:213.227.210.148', 'Mozilla/5.0 (X11; U; Linux x86_64; uk; rv:1.9.2.13) Gecko/20101206 Ubuntu/10.04 (lucid) Firefox/3.6.13', '', 'home', '', 0);
INSERT INTO user_access VALUES (29, 1297239690, '::ffff:213.227.210.148', 'Mozilla/5.0 (X11; U; Linux x86_64; uk; rv:1.9.2.13) Gecko/20101206 Ubuntu/10.04 (lucid) Firefox/3.6.13', '', 'home', '', 0);
INSERT INTO user_access VALUES (29, 1297239726, '::ffff:213.227.210.148', 'Mozilla/5.0 (X11; U; Linux x86_64; uk; rv:1.9.2.13) Gecko/20101206 Ubuntu/10.04 (lucid) Firefox/3.6.13', '', 'home', '', 0);
INSERT INTO user_access VALUES (29, 1297239731, '::ffff:213.227.210.148', 'Mozilla/5.0 (X11; U; Linux x86_64; uk; rv:1.9.2.13) Gecko/20101206 Ubuntu/10.04 (lucid) Firefox/3.6.13', 'http://newwave.in.ua/home', 'home', '', 0);
INSERT INTO user_access VALUES (2, 1297247485, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; ru; rv:1.9.2.10) Gecko/20100914 Firefox/3.6.10 (.NET CLR 3.5.30729)', '', 'home', '', 0);
INSERT INTO user_access VALUES (2, 1297247490, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; ru; rv:1.9.2.10) Gecko/20100914 Firefox/3.6.10 (.NET CLR 3.5.30729)', '', 'home', '', 0);
INSERT INTO user_access VALUES (2, 1297247505, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; ru; rv:1.9.2.10) Gecko/20100914 Firefox/3.6.10 (.NET CLR 3.5.30729)', '', 'home', '', 0);
INSERT INTO user_access VALUES (2, 1297254510, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; ru; rv:1.9.2.10) Gecko/20100914 Firefox/3.6.10 (.NET CLR 3.5.30729)', '', 'home', '', 0);
INSERT INTO user_access VALUES (2, 1297257162, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; ru; rv:1.9.2.10) Gecko/20100914 Firefox/3.6.10 (.NET CLR 3.5.30729)', '', 'home', '', 0);
INSERT INTO user_access VALUES (29, 1297257564, '::ffff:213.227.210.148', 'Mozilla/5.0 (X11; U; Linux x86_64; uk; rv:1.9.2.13) Gecko/20101206 Ubuntu/10.04 (lucid) Firefox/3.6.13', '', 'home', '', 0);
INSERT INTO user_access VALUES (2, 1297328729, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; ru; rv:1.9.2.10) Gecko/20100914 Firefox/3.6.10 (.NET CLR 3.5.30729)', '', 'home', '', 0);
INSERT INTO user_access VALUES (2, 1297340394, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; ru; rv:1.9.2.10) Gecko/20100914 Firefox/3.6.10 (.NET CLR 3.5.30729)', '', 'home', '', 0);
INSERT INTO user_access VALUES (2, 1297340593, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; ru; rv:1.9.2.10) Gecko/20100914 Firefox/3.6.10 (.NET CLR 3.5.30729)', 'http://newwave.in.ua/home', 'profile', 'index', 2);
INSERT INTO user_access VALUES (2, 1297340596, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; ru; rv:1.9.2.10) Gecko/20100914 Firefox/3.6.10 (.NET CLR 3.5.30729)', 'http://newwave.in.ua/profile-2', 'profile', 'edit', 2);
INSERT INTO user_access VALUES (2, 1297343616, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; ru; rv:1.9.2.10) Gecko/20100914 Firefox/3.6.10 (.NET CLR 3.5.30729)', 'http://newwave.in.ua/profile/edit?id=2', 'home', '', 0);
INSERT INTO user_access VALUES (2, 1297343619, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; ru; rv:1.9.2.10) Gecko/20100914 Firefox/3.6.10 (.NET CLR 3.5.30729)', 'http://newwave.in.ua/home', 'profile', 'index', 2);
INSERT INTO user_access VALUES (2, 1297343621, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; ru; rv:1.9.2.10) Gecko/20100914 Firefox/3.6.10 (.NET CLR 3.5.30729)', 'http://newwave.in.ua/profile-2', 'profile', 'edit', 2);
INSERT INTO user_access VALUES (2, 1297344752, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; ru; rv:1.9.2.10) Gecko/20100914 Firefox/3.6.10 (.NET CLR 3.5.30729)', 'http://newwave.in.ua/profile/edit?id=2', 'profile', 'index', 2);
INSERT INTO user_access VALUES (2, 1297412243, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; ru; rv:1.9.2.10) Gecko/20100914 Firefox/3.6.10 (.NET CLR 3.5.30729)', 'http://newwave.in.ua/profile/edit?id=2', 'profile', 'index', 2);
INSERT INTO user_access VALUES (5, 1297418990, '::ffff:213.227.210.148', 'Mozilla/5.0 (Windows; U; Windows NT 6.1; ru; rv:1.9.2.13) Gecko/20101203 Firefox/3.6.13 GTB7.1 ( .NET CLR 3.5.30729; .NET4.0E) WebMoney Advisor', '', 'home', '', 0);
INSERT INTO user_access VALUES (5, 1297419009, '::ffff:213.227.210.148', 'Mozilla/5.0 (Windows; U; Windows NT 6.1; ru; rv:1.9.2.13) Gecko/20101203 Firefox/3.6.13 GTB7.1 ( .NET CLR 3.5.30729; .NET4.0E) WebMoney Advisor', '', 'home', '', 0);
INSERT INTO user_access VALUES (5, 1297419034, '::ffff:213.227.210.148', 'Mozilla/5.0 (Windows; U; Windows NT 6.1; ru; rv:1.9.2.13) Gecko/20101203 Firefox/3.6.13 GTB7.1 ( .NET CLR 3.5.30729; .NET4.0E) WebMoney Advisor', '', 'home', '', 0);
INSERT INTO user_access VALUES (5, 1297421082, '::ffff:213.227.210.148', 'Mozilla/5.0 (Windows; U; Windows NT 6.1; ru; rv:1.9.2.13) Gecko/20101203 Firefox/3.6.13 GTB7.1 ( .NET CLR 3.5.30729; .NET4.0E) WebMoney Advisor', 'http://newwave.in.ua/home', 'home', '', 0);
INSERT INTO user_access VALUES (2, 1297496542, '::ffff:93.72.196.171', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; uk; rv:1.9.2.13) Gecko/20101203 Firefox/3.6.13', 'http://newwave.in.ua/home', 'people', '', 0);
INSERT INTO user_access VALUES (1360, 1297497543, '::ffff:95.134.164.75', 'Mozilla/5.0 (X11; U; Linux i686; ru; rv:1.9.2.12) Gecko/20101027 Ubuntu/10.04 (lucid) Firefox/3.6.12', '', 'home', '', 0);
INSERT INTO user_access VALUES (1360, 1297497608, '::ffff:95.134.164.75', 'Mozilla/5.0 (X11; U; Linux i686; ru; rv:1.9.2.12) Gecko/20101027 Ubuntu/10.04 (lucid) Firefox/3.6.12', 'http://newwave.in.ua/home', 'profile', 'index', 1360);
INSERT INTO user_access VALUES (1360, 1297497612, '::ffff:95.134.164.75', 'Mozilla/5.0 (X11; U; Linux i686; ru; rv:1.9.2.12) Gecko/20101027 Ubuntu/10.04 (lucid) Firefox/3.6.12', 'http://newwave.in.ua/profile-1360', 'profile', 'branding', 0);
INSERT INTO user_access VALUES (1360, 1297497643, '::ffff:95.134.164.75', 'Mozilla/5.0 (X11; U; Linux i686; ru; rv:1.9.2.12) Gecko/20101027 Ubuntu/10.04 (lucid) Firefox/3.6.12', 'http://newwave.in.ua/profile/branding', 'profile', 'branding', 0);
INSERT INTO user_access VALUES (1360, 1297498194, '::ffff:95.134.164.75', 'Mozilla/5.0 (X11; U; Linux i686; ru; rv:1.9.2.12) Gecko/20101027 Ubuntu/10.04 (lucid) Firefox/3.6.12', 'http://newwave.in.ua/profile/branding', 'profile', 'branding', 0);
INSERT INTO user_access VALUES (1360, 1297498295, '::ffff:95.134.164.75', 'Mozilla/5.0 (X11; U; Linux i686; ru; rv:1.9.2.12) Gecko/20101027 Ubuntu/10.04 (lucid) Firefox/3.6.12', 'http://newwave.in.ua/profile/branding', 'profile', 'branding', 0);
INSERT INTO user_access VALUES (1360, 1297498456, '::ffff:95.134.164.75', 'Mozilla/5.0 (X11; U; Linux i686; ru; rv:1.9.2.12) Gecko/20101027 Ubuntu/10.04 (lucid) Firefox/3.6.12', 'http://newwave.in.ua/profile/branding', 'profile', 'branding', 0);
INSERT INTO user_access VALUES (1360, 1297499128, '::ffff:95.134.164.75', 'Mozilla/5.0 (X11; U; Linux i686; ru; rv:1.9.2.12) Gecko/20101027 Ubuntu/10.04 (lucid) Firefox/3.6.12', 'http://newwave.in.ua/profile/branding', 'profile', 'branding', 0);
INSERT INTO user_access VALUES (2, 1297543471, '::ffff:93.72.196.171', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; uk; rv:1.9.2.13) Gecko/20101203 Firefox/3.6.13', 'http://newwave.in.ua/home', 'people', '', 0);
INSERT INTO user_access VALUES (2, 1297543593, '::ffff:93.72.196.171', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; uk; rv:1.9.2.13) Gecko/20101203 Firefox/3.6.13', 'http://newwave.in.ua/home', 'people', '', 0);
INSERT INTO user_access VALUES (2, 1297543623, '::ffff:93.72.196.171', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; uk; rv:1.9.2.13) Gecko/20101203 Firefox/3.6.13', 'http://newwave.in.ua/home', 'people', '', 0);
INSERT INTO user_access VALUES (5, 1297672285, '::ffff:213.227.210.148', 'Mozilla/5.0 (Windows; U; Windows NT 6.1; ru; rv:1.9.2.13) Gecko/20101203 Firefox/3.6.13 GTB7.1 ( .NET CLR 3.5.30729; .NET4.0E) WebMoney Advisor', '', 'home', '', 0);
INSERT INTO user_access VALUES (5, 1297672288, '::ffff:213.227.210.148', 'Mozilla/5.0 (Windows; U; Windows NT 6.1; ru; rv:1.9.2.13) Gecko/20101203 Firefox/3.6.13 GTB7.1 ( .NET CLR 3.5.30729; .NET4.0E) WebMoney Advisor', 'http://newwave.in.ua/home', 'people', '', 0);
INSERT INTO user_access VALUES (5, 1297672293, '::ffff:213.227.210.148', 'Mozilla/5.0 (Windows; U; Windows NT 6.1; ru; rv:1.9.2.13) Gecko/20101203 Firefox/3.6.13 GTB7.1 ( .NET CLR 3.5.30729; .NET4.0E) WebMoney Advisor', 'http://newwave.in.ua/people', 'profile', 'index', 5);
INSERT INTO user_access VALUES (5, 1297672297, '::ffff:213.227.210.148', 'Mozilla/5.0 (Windows; U; Windows NT 6.1; ru; rv:1.9.2.13) Gecko/20101203 Firefox/3.6.13 GTB7.1 ( .NET CLR 3.5.30729; .NET4.0E) WebMoney Advisor', 'http://newwave.in.ua/profile-5', 'profile', 'edit', 5);
INSERT INTO user_access VALUES (5, 1297672311, '::ffff:213.227.210.148', 'Mozilla/5.0 (Windows; U; Windows NT 6.1; ru; rv:1.9.2.13) Gecko/20101203 Firefox/3.6.13 GTB7.1 ( .NET CLR 3.5.30729; .NET4.0E) WebMoney Advisor', 'http://newwave.in.ua/profile/edit?id=5', 'people', '', 0);
INSERT INTO user_access VALUES (5, 1297672318, '::ffff:213.227.210.148', 'Mozilla/5.0 (Windows; U; Windows NT 6.1; ru; rv:1.9.2.13) Gecko/20101203 Firefox/3.6.13 GTB7.1 ( .NET CLR 3.5.30729; .NET4.0E) WebMoney Advisor', 'http://newwave.in.ua/people', 'profile', 'index', 5);
INSERT INTO user_access VALUES (5, 1297672321, '::ffff:213.227.210.148', 'Mozilla/5.0 (Windows; U; Windows NT 6.1; ru; rv:1.9.2.13) Gecko/20101203 Firefox/3.6.13 GTB7.1 ( .NET CLR 3.5.30729; .NET4.0E) WebMoney Advisor', 'http://newwave.in.ua/profile-5', 'profile', 'edit', 5);
INSERT INTO user_access VALUES (5, 1297672339, '::ffff:213.227.210.148', 'Mozilla/5.0 (Windows; U; Windows NT 6.1; ru; rv:1.9.2.13) Gecko/20101203 Firefox/3.6.13 GTB7.1 ( .NET CLR 3.5.30729; .NET4.0E) WebMoney Advisor', 'http://newwave.in.ua/profile-5', 'profile', 'edit', 5);
INSERT INTO user_access VALUES (5, 1297672795, '::ffff:213.227.210.148', 'Mozilla/5.0 (Windows; U; Windows NT 6.1; ru; rv:1.9.2.13) Gecko/20101203 Firefox/3.6.13 GTB7.1 ( .NET CLR 3.5.30729; .NET4.0E) WebMoney Advisor', '', 'home', '', 0);
INSERT INTO user_access VALUES (5, 1297672810, '::ffff:213.227.210.148', 'Mozilla/5.0 (Windows; U; Windows NT 6.1; ru; rv:1.9.2.13) Gecko/20101203 Firefox/3.6.13 GTB7.1 ( .NET CLR 3.5.30729; .NET4.0E) WebMoney Advisor', '', 'home', '', 0);
INSERT INTO user_access VALUES (5, 1297672951, '::ffff:213.227.210.148', 'Mozilla/5.0 (Windows; U; Windows NT 6.1; ru; rv:1.9.2.13) Gecko/20101203 Firefox/3.6.13 GTB7.1 ( .NET CLR 3.5.30729; .NET4.0E) WebMoney Advisor', 'http://newwave.in.ua/profile-5', 'profile', 'edit', 5);
INSERT INTO user_access VALUES (5, 1297676054, '::ffff:213.227.210.148', 'Mozilla/5.0 (Windows; U; Windows NT 6.1; ru; rv:1.9.2.13) Gecko/20101203 Firefox/3.6.13 GTB7.1 ( .NET CLR 3.5.30729; .NET4.0E) WebMoney Advisor', 'http://newwave.in.ua/profile/edit?id=5', 'home', '', 0);
INSERT INTO user_access VALUES (5, 1297686541, '::ffff:213.227.210.148', 'Mozilla/5.0 (Windows; U; Windows NT 6.1; ru; rv:1.9.2.13) Gecko/20101203 Firefox/3.6.13 GTB7.1 ( .NET CLR 3.5.30729; .NET4.0E) WebMoney Advisor', '', 'home', '', 0);
INSERT INTO user_access VALUES (5, 1297686545, '::ffff:213.227.210.148', 'Mozilla/5.0 (Windows; U; Windows NT 6.1; ru; rv:1.9.2.13) Gecko/20101203 Firefox/3.6.13 GTB7.1 ( .NET CLR 3.5.30729; .NET4.0E) WebMoney Advisor', 'http://newwave.in.ua/home', 'profile', 'index', 5);
INSERT INTO user_access VALUES (5, 1297686548, '::ffff:213.227.210.148', 'Mozilla/5.0 (Windows; U; Windows NT 6.1; ru; rv:1.9.2.13) Gecko/20101203 Firefox/3.6.13 GTB7.1 ( .NET CLR 3.5.30729; .NET4.0E) WebMoney Advisor', 'http://newwave.in.ua/profile-5', 'profile', 'edit', 5);
INSERT INTO user_access VALUES (5, 1297693753, '::ffff:213.227.210.148', 'Mozilla/5.0 (Windows; U; Windows NT 6.1; ru; rv:1.9.2.13) Gecko/20101203 Firefox/3.6.13 GTB7.1 ( .NET CLR 3.5.30729; .NET4.0E) WebMoney Advisor', '', 'home', '', 0);
INSERT INTO user_access VALUES (5, 1297693808, '::ffff:213.227.210.148', 'Mozilla/5.0 (Windows; U; Windows NT 6.1; ru; rv:1.9.2.13) Gecko/20101203 Firefox/3.6.13 GTB7.1 ( .NET CLR 3.5.30729; .NET4.0E) WebMoney Advisor', 'http://newwave.in.ua/home', 'profile', 'index', 5);
INSERT INTO user_access VALUES (5, 1297693812, '::ffff:213.227.210.148', 'Mozilla/5.0 (Windows; U; Windows NT 6.1; ru; rv:1.9.2.13) Gecko/20101203 Firefox/3.6.13 GTB7.1 ( .NET CLR 3.5.30729; .NET4.0E) WebMoney Advisor', 'http://newwave.in.ua/profile-5', 'profile', 'edit', 5);
INSERT INTO user_access VALUES (1360, 1297694214, '::ffff:92.113.159.13', 'Mozilla/5.0 (X11; U; Linux i686; ru; rv:1.9.2.12) Gecko/20101027 Ubuntu/10.04 (lucid) Firefox/3.6.12', '', 'home', '', 0);
INSERT INTO user_access VALUES (1360, 1297694220, '::ffff:92.113.159.13', 'Mozilla/5.0 (X11; U; Linux i686; ru; rv:1.9.2.12) Gecko/20101027 Ubuntu/10.04 (lucid) Firefox/3.6.12', 'http://newwave.in.ua/home', 'profile', 'index', 1360);
INSERT INTO user_access VALUES (1360, 1297694223, '::ffff:92.113.159.13', 'Mozilla/5.0 (X11; U; Linux i686; ru; rv:1.9.2.12) Gecko/20101027 Ubuntu/10.04 (lucid) Firefox/3.6.12', 'http://newwave.in.ua/profile-1360', 'profile', 'edit', 1360);
INSERT INTO user_access VALUES (1360, 1297694746, '::ffff:92.113.159.13', 'Mozilla/5.0 (X11; U; Linux i686; ru; rv:1.9.2.12) Gecko/20101027 Ubuntu/10.04 (lucid) Firefox/3.6.12', 'http://newwave.in.ua/profile-1360', 'profile', 'edit', 1360);
INSERT INTO user_access VALUES (1360, 1297695007, '::ffff:92.113.159.13', 'Mozilla/5.0 (X11; U; Linux i686; ru; rv:1.9.2.12) Gecko/20101027 Ubuntu/10.04 (lucid) Firefox/3.6.12', 'http://newwave.in.ua/profile-1360', 'profile', 'edit', 1360);
INSERT INTO user_access VALUES (1360, 1297695051, '::ffff:92.113.159.13', 'Mozilla/5.0 (X11; U; Linux i686; ru; rv:1.9.2.12) Gecko/20101027 Ubuntu/10.04 (lucid) Firefox/3.6.12', 'http://newwave.in.ua/profile-1360', 'profile', 'edit', 1360);
INSERT INTO user_access VALUES (1360, 1297695512, '::ffff:92.113.159.13', 'Mozilla/5.0 (X11; U; Linux i686; ru; rv:1.9.2.12) Gecko/20101027 Ubuntu/10.04 (lucid) Firefox/3.6.12', 'http://newwave.in.ua/profile-1360', 'profile', 'edit', 1360);
INSERT INTO user_access VALUES (1360, 1297695645, '::ffff:92.113.159.13', 'Mozilla/5.0 (X11; U; Linux i686; ru; rv:1.9.2.12) Gecko/20101027 Ubuntu/10.04 (lucid) Firefox/3.6.12', 'http://newwave.in.ua/profile-1360', 'profile', 'edit', 1360);
INSERT INTO user_access VALUES (1360, 1297695700, '::ffff:92.113.159.13', 'Mozilla/5.0 (X11; U; Linux i686; ru; rv:1.9.2.12) Gecko/20101027 Ubuntu/10.04 (lucid) Firefox/3.6.12', 'http://newwave.in.ua/profile-1360', 'profile', 'edit', 1360);
INSERT INTO user_access VALUES (1360, 1297695707, '::ffff:92.113.159.13', 'Mozilla/5.0 (X11; U; Linux i686; ru; rv:1.9.2.12) Gecko/20101027 Ubuntu/10.04 (lucid) Firefox/3.6.12', 'http://newwave.in.ua/profile-1360', 'profile', 'edit', 1360);
INSERT INTO user_access VALUES (5, 1297696092, '::ffff:213.227.210.148', 'Mozilla/5.0 (Windows; U; Windows NT 6.1; ru; rv:1.9.2.13) Gecko/20101203 Firefox/3.6.13 GTB7.1 ( .NET CLR 3.5.30729; .NET4.0E) WebMoney Advisor', 'http://newwave.in.ua/profile/edit?id=5', 'profile', 'index', 5);
INSERT INTO user_access VALUES (5, 1297696098, '::ffff:213.227.210.148', 'Mozilla/5.0 (Windows; U; Windows NT 6.1; ru; rv:1.9.2.13) Gecko/20101203 Firefox/3.6.13 GTB7.1 ( .NET CLR 3.5.30729; .NET4.0E) WebMoney Advisor', 'http://newwave.in.ua/profile-5', 'profile', 'edit', 5);
INSERT INTO user_access VALUES (1360, 1297698030, '::ffff:92.113.159.13', 'Mozilla/5.0 (X11; U; Linux i686; ru; rv:1.9.2.12) Gecko/20101027 Ubuntu/10.04 (lucid) Firefox/3.6.12', 'http://newwave.in.ua/profile-1360', 'profile', 'edit', 1360);
INSERT INTO user_access VALUES (1360, 1297698100, '::ffff:92.113.159.13', 'Mozilla/5.0 (X11; U; Linux i686; ru; rv:1.9.2.12) Gecko/20101027 Ubuntu/10.04 (lucid) Firefox/3.6.12', 'http://newwave.in.ua/profile-1360', 'profile', 'edit', 1360);
INSERT INTO user_access VALUES (5, 1297702859, '::ffff:93.75.185.122', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; ru; rv:1.9.2.13) Gecko/20101203 Firefox/3.6.13 GTB7.1', '', 'home', '', 0);
INSERT INTO user_access VALUES (5, 1297702866, '::ffff:93.75.185.122', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; ru; rv:1.9.2.13) Gecko/20101203 Firefox/3.6.13 GTB7.1', 'http://newwave.in.ua/home', 'profile', 'index', 5);
INSERT INTO user_access VALUES (5, 1297702870, '::ffff:93.75.185.122', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; ru; rv:1.9.2.13) Gecko/20101203 Firefox/3.6.13 GTB7.1', 'http://newwave.in.ua/profile-5', 'profile', 'edit', 5);
INSERT INTO user_access VALUES (1360, 1297704117, '::ffff:92.113.159.13', 'Mozilla/5.0 (X11; U; Linux i686; ru; rv:1.9.2.12) Gecko/20101027 Ubuntu/10.04 (lucid) Firefox/3.6.12', 'http://newwave.in.ua/profile-1360', 'profile', 'edit', 1360);
INSERT INTO user_access VALUES (1360, 1297704125, '::ffff:92.113.159.13', 'Mozilla/5.0 (X11; U; Linux i686; ru; rv:1.9.2.12) Gecko/20101027 Ubuntu/10.04 (lucid) Firefox/3.6.12', 'http://newwave.in.ua/profile-1360', 'profile', 'edit', 1360);
INSERT INTO user_access VALUES (1360, 1297704134, '::ffff:92.113.159.13', 'Mozilla/5.0 (X11; U; Linux i686; ru; rv:1.9.2.12) Gecko/20101027 Ubuntu/10.04 (lucid) Firefox/3.6.12', 'http://newwave.in.ua/profile-1360', 'profile', 'edit', 1360);
INSERT INTO user_access VALUES (1360, 1297704141, '::ffff:92.113.159.13', 'Mozilla/5.0 (X11; U; Linux i686; ru; rv:1.9.2.12) Gecko/20101027 Ubuntu/10.04 (lucid) Firefox/3.6.12', 'http://newwave.in.ua/profile-1360', 'profile', 'edit', 1360);
INSERT INTO user_access VALUES (5, 1297706995, '::ffff:93.75.185.122', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; ru; rv:1.9.2.13) Gecko/20101203 Firefox/3.6.13 GTB7.1', 'http://newwave.in.ua/profile/edit?id=5', 'profile', 'index', 5);
INSERT INTO user_access VALUES (5, 1297706997, '::ffff:93.75.185.122', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; ru; rv:1.9.2.13) Gecko/20101203 Firefox/3.6.13 GTB7.1', 'http://newwave.in.ua/profile-5', 'profile', 'edit', 5);
INSERT INTO user_access VALUES (5, 1297709328, '::ffff:93.75.185.122', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; ru; rv:1.9.2.13) Gecko/20101203 Firefox/3.6.13 GTB7.1', 'http://newwave.in.ua/profile/edit?id=5', 'profile', 'index', 5);
INSERT INTO user_access VALUES (5, 1297709331, '::ffff:93.75.185.122', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; ru; rv:1.9.2.13) Gecko/20101203 Firefox/3.6.13 GTB7.1', 'http://newwave.in.ua/profile-5', 'profile', 'edit', 5);
INSERT INTO user_access VALUES (5, 1297712411, '::ffff:93.75.185.122', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; ru; rv:1.9.2.13) Gecko/20101203 Firefox/3.6.13 GTB7.1', 'http://newwave.in.ua/profile/edit?id=5', 'profile', 'index', 5);
INSERT INTO user_access VALUES (5, 1297712416, '::ffff:93.75.185.122', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; ru; rv:1.9.2.13) Gecko/20101203 Firefox/3.6.13 GTB7.1', 'http://newwave.in.ua/profile-5', 'profile', 'edit', 5);
INSERT INTO user_access VALUES (5, 1297752039, '::ffff:93.75.185.122', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; ru; rv:1.9.2.13) Gecko/20101203 Firefox/3.6.13 GTB7.1', '', 'home', '', 0);
INSERT INTO user_access VALUES (5, 1297752046, '::ffff:93.75.185.122', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; ru; rv:1.9.2.13) Gecko/20101203 Firefox/3.6.13 GTB7.1', 'http://newwave.in.ua/home', 'profile', 'index', 5);
INSERT INTO user_access VALUES (5, 1297752048, '::ffff:93.75.185.122', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; ru; rv:1.9.2.13) Gecko/20101203 Firefox/3.6.13 GTB7.1', 'http://newwave.in.ua/profile-5', 'profile', 'edit', 5);
INSERT INTO user_access VALUES (5, 1297752093, '::ffff:93.75.185.122', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; ru; rv:1.9.2.13) Gecko/20101203 Firefox/3.6.13 GTB7.1', 'http://newwave.in.ua/profile/edit?id=5', 'people', '', 0);
INSERT INTO user_access VALUES (5, 1297752101, '::ffff:93.75.185.122', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; ru; rv:1.9.2.13) Gecko/20101203 Firefox/3.6.13 GTB7.1', 'http://newwave.in.ua/people', 'blogs', '', 0);
INSERT INTO user_access VALUES (5, 1297752125, '::ffff:93.75.185.122', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; ru; rv:1.9.2.13) Gecko/20101203 Firefox/3.6.13 GTB7.1', 'http://newwave.in.ua/blogs', 'home', '', 0);
INSERT INTO user_access VALUES (5, 1297752215, '::ffff:93.75.185.122', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; ru; rv:1.9.2.13) Gecko/20101203 Firefox/3.6.13 GTB7.1', 'http://newwave.in.ua/home', 'home', '', 0);
INSERT INTO user_access VALUES (5, 1297752266, '::ffff:93.75.185.122', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; ru; rv:1.9.2.13) Gecko/20101203 Firefox/3.6.13 GTB7.1', 'http://newwave.in.ua/home', 'home', '', 0);
INSERT INTO user_access VALUES (5, 1297752289, '::ffff:93.75.185.122', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; ru; rv:1.9.2.13) Gecko/20101203 Firefox/3.6.13 GTB7.1', 'http://newwave.in.ua/home', 'home', '', 0);
INSERT INTO user_access VALUES (5, 1297753068, '::ffff:93.75.185.122', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; ru; rv:1.9.2.13) Gecko/20101203 Firefox/3.6.13 GTB7.1', 'http://newwave.in.ua/home', 'home', '', 0);
INSERT INTO user_access VALUES (5, 1297753081, '::ffff:93.75.185.122', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; ru; rv:1.9.2.13) Gecko/20101203 Firefox/3.6.13 GTB7.1', 'http://newwave.in.ua/home', 'people', '', 0);
INSERT INTO user_access VALUES (5, 1297753087, '::ffff:93.75.185.122', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; ru; rv:1.9.2.13) Gecko/20101203 Firefox/3.6.13 GTB7.1', 'http://newwave.in.ua/people', 'profile', 'index', 5);
INSERT INTO user_access VALUES (5, 1297753089, '::ffff:93.75.185.122', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; ru; rv:1.9.2.13) Gecko/20101203 Firefox/3.6.13 GTB7.1', 'http://newwave.in.ua/profile-5', 'profile', 'edit', 5);
INSERT INTO user_access VALUES (1360, 1297758976, '::ffff:92.113.129.52', 'Mozilla/5.0 (X11; U; Linux i686; ru; rv:1.9.2.12) Gecko/20101027 Ubuntu/10.04 (lucid) Firefox/3.6.12', '', 'home', '', 0);
INSERT INTO user_access VALUES (1360, 1297758983, '::ffff:92.113.129.52', 'Mozilla/5.0 (X11; U; Linux i686; ru; rv:1.9.2.12) Gecko/20101027 Ubuntu/10.04 (lucid) Firefox/3.6.12', 'http://newwave.in.ua/home', 'profile', 'index', 1360);
INSERT INTO user_access VALUES (1360, 1297758986, '::ffff:92.113.129.52', 'Mozilla/5.0 (X11; U; Linux i686; ru; rv:1.9.2.12) Gecko/20101027 Ubuntu/10.04 (lucid) Firefox/3.6.12', 'http://newwave.in.ua/profile-1360', 'profile', 'edit', 1360);
INSERT INTO user_access VALUES (2, 1297759348, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; ru; rv:1.9.2.10) Gecko/20100914 Firefox/3.6.10 (.NET CLR 3.5.30729)', 'http://newwave.in.ua/profile/edit?id=2', 'profile', 'index', 2);
INSERT INTO user_access VALUES (1360, 1297759494, '::ffff:92.113.129.52', 'Mozilla/5.0 (X11; U; Linux i686; ru; rv:1.9.2.12) Gecko/20101027 Ubuntu/10.04 (lucid) Firefox/3.6.12', 'http://newwave.in.ua/profile-1360', 'profile', 'edit', 1360);
INSERT INTO user_access VALUES (1360, 1297759549, '::ffff:92.113.129.52', 'Mozilla/5.0 (X11; U; Linux i686; ru; rv:1.9.2.12) Gecko/20101027 Ubuntu/10.04 (lucid) Firefox/3.6.12', 'http://newwave.in.ua/profile-1360', 'profile', 'edit', 1360);
INSERT INTO user_access VALUES (2, 1297762886, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; ru; rv:1.9.2.10) Gecko/20100914 Firefox/3.6.10 (.NET CLR 3.5.30729)', '', 'home', '', 0);
INSERT INTO user_access VALUES (2, 1297762895, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; ru; rv:1.9.2.10) Gecko/20100914 Firefox/3.6.10 (.NET CLR 3.5.30729)', 'http://newwave.in.ua/home', 'profile', 'index', 2);
INSERT INTO user_access VALUES (2, 1297762902, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; ru; rv:1.9.2.10) Gecko/20100914 Firefox/3.6.10 (.NET CLR 3.5.30729)', 'http://newwave.in.ua/profile-2', 'profile', 'edit', 2);
INSERT INTO user_access VALUES (2, 1297764170, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; ru; rv:1.9.2.10) Gecko/20100914 Firefox/3.6.10 (.NET CLR 3.5.30729)', 'http://newwave.in.ua/profile/edit?id=2', 'profile', 'index', 2);
INSERT INTO user_access VALUES (1360, 1297771462, '::ffff:92.113.111.160', 'Mozilla/5.0 (X11; U; Linux i686; ru; rv:1.9.2.12) Gecko/20101027 Ubuntu/10.04 (lucid) Firefox/3.6.12', 'http://newwave.in.ua/profile-1360', 'profile', 'edit', 1360);
INSERT INTO user_access VALUES (1360, 1297778111, '::ffff:92.113.111.160', 'Mozilla/5.0 (X11; U; Linux i686; ru; rv:1.9.2.12) Gecko/20101027 Ubuntu/10.04 (lucid) Firefox/3.6.12', '', 'message', 'compose', 0);
INSERT INTO user_access VALUES (1360, 1297778173, '::ffff:92.113.111.160', 'Mozilla/5.0 (X11; U; Linux i686; ru; rv:1.9.2.12) Gecko/20101027 Ubuntu/10.04 (lucid) Firefox/3.6.12', '', 'messages', 'compose', 0);
INSERT INTO user_access VALUES (29, 1297779409, '::ffff:213.227.210.148', 'Mozilla/5.0 (X11; U; Linux x86_64; uk; rv:1.9.2.13) Gecko/20101206 Ubuntu/10.04 (lucid) Firefox/3.6.13', 'http://novi-ua.org/', 'profile', '', 0);
INSERT INTO user_access VALUES (29, 1297779648, '::ffff:213.227.210.148', 'Mozilla/5.0 (X11; U; Linux x86_64; uk; rv:1.9.2.13) Gecko/20101206 Ubuntu/10.04 (lucid) Firefox/3.6.13', 'http://novi-ua.org/', 'profile', '', 0);
INSERT INTO user_access VALUES (29, 1297779652, '::ffff:213.227.210.148', 'Mozilla/5.0 (X11; U; Linux x86_64; uk; rv:1.9.2.13) Gecko/20101206 Ubuntu/10.04 (lucid) Firefox/3.6.13', 'http://novi-ua.org/profile', 'home', '', 0);
INSERT INTO user_access VALUES (29, 1297779663, '::ffff:213.227.210.148', 'Mozilla/5.0 (X11; U; Linux x86_64; uk; rv:1.9.2.13) Gecko/20101206 Ubuntu/10.04 (lucid) Firefox/3.6.13', 'http://novi-ua.org/home', 'home', '', 0);
INSERT INTO user_access VALUES (29, 1297779666, '::ffff:213.227.210.148', 'Mozilla/5.0 (X11; U; Linux x86_64; uk; rv:1.9.2.13) Gecko/20101206 Ubuntu/10.04 (lucid) Firefox/3.6.13', 'http://novi-ua.org/home', 'polls', '', 0);
INSERT INTO user_access VALUES (2, 1297780831, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; ru; rv:1.9.2.10) Gecko/20100914 Firefox/3.6.10 (.NET CLR 3.5.30729)', 'http://novi-ua.org/', 'profile', '', 0);
INSERT INTO user_access VALUES (2, 1297840807, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; ru; rv:1.9.2.10) Gecko/20100914 Firefox/3.6.10 ( .NET CLR 3.5.30729)', 'http://novi-ua.org/', 'profile', '', 0);
INSERT INTO user_access VALUES (2, 1297846247, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; ru; rv:1.9.2.10) Gecko/20100914 Firefox/3.6.10 ( .NET CLR 3.5.30729)', '', 'home', '', 0);
INSERT INTO user_access VALUES (2, 1297846252, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; ru; rv:1.9.2.10) Gecko/20100914 Firefox/3.6.10 ( .NET CLR 3.5.30729)', 'http://novi-ua.org/home', 'profile', 'index', 2);
INSERT INTO user_access VALUES (2, 1297846254, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; ru; rv:1.9.2.10) Gecko/20100914 Firefox/3.6.10 ( .NET CLR 3.5.30729)', 'http://novi-ua.org/profile-2', 'profile', 'edit', 2);
INSERT INTO user_access VALUES (1360, 1297867749, '::ffff:95.134.166.218', 'Mozilla/5.0 (X11; U; Linux i686; ru; rv:1.9.2.12) Gecko/20101027 Ubuntu/10.04 (lucid) Firefox/3.6.12', 'http://novi-ua.org/', 'profile', '', 0);
INSERT INTO user_access VALUES (1360, 1297867754, '::ffff:95.134.166.218', 'Mozilla/5.0 (X11; U; Linux i686; ru; rv:1.9.2.12) Gecko/20101027 Ubuntu/10.04 (lucid) Firefox/3.6.12', 'http://novi-ua.org/profile', 'profile', 'index', 1360);
INSERT INTO user_access VALUES (1360, 1297867797, '::ffff:95.134.166.218', 'Mozilla/5.0 (X11; U; Linux i686; ru; rv:1.9.2.12) Gecko/20101027 Ubuntu/10.04 (lucid) Firefox/3.6.12', 'http://novi-ua.org/profile-1360', 'profile', 'edit', 1360);
INSERT INTO user_access VALUES (1360, 1297894263, '::ffff:92.113.122.121', 'Mozilla/5.0 (X11; U; Linux i686; ru; rv:1.9.2.12) Gecko/20101027 Ubuntu/10.04 (lucid) Firefox/3.6.12', '', 'home', '', 0);
INSERT INTO user_access VALUES (1360, 1297894268, '::ffff:92.113.122.121', 'Mozilla/5.0 (X11; U; Linux i686; ru; rv:1.9.2.12) Gecko/20101027 Ubuntu/10.04 (lucid) Firefox/3.6.12', 'http://novi-ua.org/home', 'profile', 'index', 1360);
INSERT INTO user_access VALUES (1360, 1297894270, '::ffff:92.113.122.121', 'Mozilla/5.0 (X11; U; Linux i686; ru; rv:1.9.2.12) Gecko/20101027 Ubuntu/10.04 (lucid) Firefox/3.6.12', 'http://novi-ua.org/profile-1360', 'profile', 'edit', 1360);
INSERT INTO user_access VALUES (5, 1297934969, '::ffff:213.227.210.148', 'Mozilla/5.0 (Windows; U; Windows NT 6.1; ru; rv:1.9.2.13) Gecko/20101203 Firefox/3.6.13 GTB7.1 ( .NET CLR 3.5.30729; .NET4.0E) WebMoney Advisor', 'http://novi-ua.org/', 'profile', '', 0);
INSERT INTO user_access VALUES (5, 1297935015, '::ffff:213.227.210.148', 'Mozilla/5.0 (Windows; U; Windows NT 6.1; ru; rv:1.9.2.13) Gecko/20101203 Firefox/3.6.13 GTB7.1 ( .NET CLR 3.5.30729; .NET4.0E) WebMoney Advisor', '', 'profile', '', 0);
INSERT INTO user_access VALUES (5, 1297935019, '::ffff:213.227.210.148', 'Mozilla/5.0 (Windows; U; Windows NT 6.1; ru; rv:1.9.2.13) Gecko/20101203 Firefox/3.6.13 GTB7.1 ( .NET CLR 3.5.30729; .NET4.0E) WebMoney Advisor', '', 'profile', '', 0);
INSERT INTO user_access VALUES (5, 1297935019, '::ffff:213.227.210.148', 'Mozilla/5.0 (Windows; U; Windows NT 6.1; ru; rv:1.9.2.13) Gecko/20101203 Firefox/3.6.13 GTB7.1 ( .NET CLR 3.5.30729; .NET4.0E) WebMoney Advisor', '', 'profile', '', 0);
INSERT INTO user_access VALUES (5, 1297935035, '::ffff:213.227.210.148', 'Mozilla/5.0 (Windows; U; Windows NT 6.1; ru; rv:1.9.2.13) Gecko/20101203 Firefox/3.6.13 GTB7.1 ( .NET CLR 3.5.30729; .NET4.0E) WebMoney Advisor', 'http://novi-ua.org/profile', 'profile', 'index', 5);
INSERT INTO user_access VALUES (5, 1297935039, '::ffff:213.227.210.148', 'Mozilla/5.0 (Windows; U; Windows NT 6.1; ru; rv:1.9.2.13) Gecko/20101203 Firefox/3.6.13 GTB7.1 ( .NET CLR 3.5.30729; .NET4.0E) WebMoney Advisor', 'http://novi-ua.org/profile-5', 'profile', 'edit', 5);
INSERT INTO user_access VALUES (2, 1297935859, '::ffff:194.187.108.86', 'Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 5.1; Trident/4.0; .NET CLR 1.1.4322; .NET CLR 2.0.50727; .NET CLR 3.0.4506.2152; .NET CLR 3.5.30729)', 'http://novi-ua.org/', 'profile', '', 0);
INSERT INTO user_access VALUES (2, 1297936735, '::ffff:194.187.108.86', 'Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 5.1; Trident/4.0; .NET CLR 1.1.4322; .NET CLR 2.0.50727; .NET CLR 3.0.4506.2152; .NET CLR 3.5.30729)', 'http://novi-ua.org/profile', 'profile', 'edit', 2);
INSERT INTO user_access VALUES (5, 1297941144, '::ffff:213.227.210.148', 'Mozilla/5.0 (Windows; U; Windows NT 6.1; ru; rv:1.9.2.13) Gecko/20101203 Firefox/3.6.13 GTB7.1 ( .NET CLR 3.5.30729; .NET4.0E) WebMoney Advisor', 'http://novi-ua.org/profile/edit?id=5', 'profile', 'edit', 5);
INSERT INTO user_access VALUES (29, 1297944453, '::ffff:213.227.210.148', 'Mozilla/5.0 (X11; U; Linux x86_64; uk; rv:1.9.2.13) Gecko/20101206 Ubuntu/10.04 (lucid) Firefox/3.6.13', '', 'profile', 'index', 5);
INSERT INTO user_access VALUES (29, 1297944572, '::ffff:213.227.210.148', 'Mozilla/5.0 (X11; U; Linux x86_64; uk; rv:1.9.2.13) Gecko/20101206 Ubuntu/10.04 (lucid) Firefox/3.6.13', 'http://novi-ua.org/profile-5', 'home', '', 0);
INSERT INTO user_access VALUES (29, 1297944575, '::ffff:213.227.210.148', 'Mozilla/5.0 (X11; U; Linux x86_64; uk; rv:1.9.2.13) Gecko/20101206 Ubuntu/10.04 (lucid) Firefox/3.6.13', 'http://novi-ua.org/profile-5', 'home', '', 0);
INSERT INTO user_access VALUES (29, 1297944575, '::ffff:213.227.210.148', 'Mozilla/5.0 (X11; U; Linux x86_64; uk; rv:1.9.2.13) Gecko/20101206 Ubuntu/10.04 (lucid) Firefox/3.6.13', 'http://novi-ua.org/profile-5', 'home', '', 0);
INSERT INTO user_access VALUES (29, 1297944586, '::ffff:213.227.210.148', 'Mozilla/5.0 (X11; U; Linux x86_64; uk; rv:1.9.2.13) Gecko/20101206 Ubuntu/10.04 (lucid) Firefox/3.6.13', 'http://novi-ua.org/home', 'people', '', 0);
INSERT INTO user_access VALUES (29, 1297944594, '::ffff:213.227.210.148', 'Mozilla/5.0 (X11; U; Linux x86_64; uk; rv:1.9.2.13) Gecko/20101206 Ubuntu/10.04 (lucid) Firefox/3.6.13', 'http://novi-ua.org/people', 'profile', 'index', 2);
INSERT INTO user_access VALUES (29, 1297944599, '::ffff:213.227.210.148', 'Mozilla/5.0 (X11; U; Linux x86_64; uk; rv:1.9.2.13) Gecko/20101206 Ubuntu/10.04 (lucid) Firefox/3.6.13', 'http://novi-ua.org/profile-2', 'home', '', 0);
INSERT INTO user_access VALUES (5, 1297945568, '::ffff:213.227.210.148', 'Mozilla/5.0 (Windows; U; Windows NT 6.1; ru; rv:1.9.2.13) Gecko/20101203 Firefox/3.6.13 GTB7.1 ( .NET CLR 3.5.30729; .NET4.0E) WebMoney Advisor', '', 'profile', '', 0);
INSERT INTO user_access VALUES (5, 1297946106, '::ffff:213.227.210.148', 'Mozilla/5.0 (Windows; U; Windows NT 6.1; ru; rv:1.9.2.13) Gecko/20101203 Firefox/3.6.13 GTB7.1 ( .NET CLR 3.5.30729; .NET4.0E) WebMoney Advisor', 'http://novi-ua.org/profile', 'profile', 'index', 5);
INSERT INTO user_access VALUES (5, 1297946110, '::ffff:213.227.210.148', 'Mozilla/5.0 (Windows; U; Windows NT 6.1; ru; rv:1.9.2.13) Gecko/20101203 Firefox/3.6.13 GTB7.1 ( .NET CLR 3.5.30729; .NET4.0E) WebMoney Advisor', 'http://novi-ua.org/profile-5', 'profile', 'edit', 5);
INSERT INTO user_access VALUES (5, 1297946138, '::ffff:213.227.210.148', 'Mozilla/5.0 (Windows; U; Windows NT 6.1; ru; rv:1.9.2.13) Gecko/20101203 Firefox/3.6.13 GTB7.1 ( .NET CLR 3.5.30729; .NET4.0E) WebMoney Advisor', 'http://novi-ua.org/profile/edit?id=5', 'profile', 'edit', 5);
INSERT INTO user_access VALUES (29, 1297946630, '::ffff:213.227.210.148', 'Mozilla/5.0 (X11; U; Linux x86_64; uk; rv:1.9.2.13) Gecko/20101206 Ubuntu/10.04 (lucid) Firefox/3.6.13', '', 'home', '', 0);
INSERT INTO user_access VALUES (29, 1297946633, '::ffff:213.227.210.148', 'Mozilla/5.0 (X11; U; Linux x86_64; uk; rv:1.9.2.13) Gecko/20101206 Ubuntu/10.04 (lucid) Firefox/3.6.13', 'http://novi-ua.org/home', 'people', '', 0);
INSERT INTO user_access VALUES (29, 1297946817, '::ffff:213.227.210.148', 'Mozilla/5.0 (X11; U; Linux x86_64; uk; rv:1.9.2.13) Gecko/20101206 Ubuntu/10.04 (lucid) Firefox/3.6.13', 'http://novi-ua.org/home', 'people', '', 0);
INSERT INTO user_access VALUES (5, 1297948809, '::ffff:213.227.210.148', 'Mozilla/5.0 (Windows; U; Windows NT 6.1; ru; rv:1.9.2.13) Gecko/20101203 Firefox/3.6.13 GTB7.1 ( .NET CLR 3.5.30729; .NET4.0E) WebMoney Advisor', '', 'profile', '', 0);
INSERT INTO user_access VALUES (5, 1297948813, '::ffff:213.227.210.148', 'Mozilla/5.0 (Windows; U; Windows NT 6.1; ru; rv:1.9.2.13) Gecko/20101203 Firefox/3.6.13 GTB7.1 ( .NET CLR 3.5.30729; .NET4.0E) WebMoney Advisor', 'http://novi-ua.org/profile', 'home', '', 0);
INSERT INTO user_access VALUES (5, 1297948816, '::ffff:213.227.210.148', 'Mozilla/5.0 (Windows; U; Windows NT 6.1; ru; rv:1.9.2.13) Gecko/20101203 Firefox/3.6.13 GTB7.1 ( .NET CLR 3.5.30729; .NET4.0E) WebMoney Advisor', 'http://novi-ua.org/home', 'profile', 'index', 5);
INSERT INTO user_access VALUES (5, 1298015601, '::ffff:213.227.210.148', 'Mozilla/5.0 (Windows; U; Windows NT 6.1; ru; rv:1.9.2.13) Gecko/20101203 Firefox/3.6.13 GTB7.1 ( .NET CLR 3.5.30729; .NET4.0E) WebMoney Advisor', '', 'profile', '', 0);
INSERT INTO user_access VALUES (5, 1298032419, '::ffff:213.227.210.148', 'Mozilla/5.0 (Windows; U; Windows NT 6.1; ru; rv:1.9.2.13) Gecko/20101203 Firefox/3.6.13 GTB7.1 ( .NET CLR 3.5.30729; .NET4.0E) WebMoney Advisor', '', 'profile', '', 0);
INSERT INTO user_access VALUES (5, 1298048436, '::ffff:213.227.210.148', 'Mozilla/5.0 (Windows; U; Windows NT 6.1; ru; rv:1.9.2.13) Gecko/20101203 Firefox/3.6.13 GTB7.1 ( .NET CLR 3.5.30729; .NET4.0E) WebMoney Advisor', '', 'profile', '', 0);
INSERT INTO user_access VALUES (29, 1299075981, '::ffff:213.227.210.148', 'Mozilla/5.0 (X11; U; Linux x86_64; uk; rv:1.9.2.13) Gecko/20101206 Ubuntu/10.04 (lucid) Firefox/3.6.13', '', 'home', '', 0);
INSERT INTO user_access VALUES (29, 1299075986, '::ffff:213.227.210.148', 'Mozilla/5.0 (X11; U; Linux x86_64; uk; rv:1.9.2.13) Gecko/20101206 Ubuntu/10.04 (lucid) Firefox/3.6.13', 'http://novi-ua.org/home', 'people', '', 0);
INSERT INTO user_access VALUES (29, 1299075986, '::ffff:213.227.210.148', 'Mozilla/5.0 (X11; U; Linux x86_64; uk; rv:1.9.2.13) Gecko/20101206 Ubuntu/10.04 (lucid) Firefox/3.6.13', 'http://novi-ua.org/home', 'people', '', 0);
INSERT INTO user_access VALUES (29, 1299075986, '::ffff:213.227.210.148', 'Mozilla/5.0 (X11; U; Linux x86_64; uk; rv:1.9.2.13) Gecko/20101206 Ubuntu/10.04 (lucid) Firefox/3.6.13', 'http://novi-ua.org/home', 'people', '', 0);
INSERT INTO user_access VALUES (29, 1299075986, '::ffff:213.227.210.148', 'Mozilla/5.0 (X11; U; Linux x86_64; uk; rv:1.9.2.13) Gecko/20101206 Ubuntu/10.04 (lucid) Firefox/3.6.13', 'http://novi-ua.org/home', 'people', '', 0);
INSERT INTO user_access VALUES (29, 1299075989, '::ffff:213.227.210.148', 'Mozilla/5.0 (X11; U; Linux x86_64; uk; rv:1.9.2.13) Gecko/20101206 Ubuntu/10.04 (lucid) Firefox/3.6.13', 'http://novi-ua.org/people', 'profile', 'index', 5);
INSERT INTO user_access VALUES (29, 1299076359, '::ffff:213.227.210.148', 'Mozilla/5.0 (X11; U; Linux x86_64; uk; rv:1.9.2.13) Gecko/20101206 Ubuntu/10.04 (lucid) Firefox/3.6.13', '', 'profile', 'index', 2);
INSERT INTO user_access VALUES (2, 1299325570, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; ru; rv:1.9.2.14) Gecko/20110218 Firefox/3.6.14 ( .NET CLR 3.5.30729)', '', 'home', '', 0);
INSERT INTO user_access VALUES (1360, 1301557223, '::ffff:95.134.100.96', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.16) Gecko/20110319 Firefox/3.6.16', 'http://novi-ua.org/', 'profile', '', 0);
INSERT INTO user_access VALUES (1360, 1301557231, '::ffff:95.134.100.96', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.16) Gecko/20110319 Firefox/3.6.16', 'http://novi-ua.org/profile', 'groups', '', 0);
INSERT INTO user_access VALUES (29, 1301557235, '::ffff:213.227.210.148', 'Mozilla/5.0 (X11; U; Linux x86_64; uk; rv:1.9.2.13) Gecko/20101206 Ubuntu/10.04 (lucid) Firefox/3.6.13', 'http://novi-ua.org/', 'profile', '', 0);
INSERT INTO user_access VALUES (29, 1301557240, '::ffff:213.227.210.148', 'Mozilla/5.0 (X11; U; Linux x86_64; uk; rv:1.9.2.13) Gecko/20101206 Ubuntu/10.04 (lucid) Firefox/3.6.13', 'http://novi-ua.org/profile', 'home', '', 0);
INSERT INTO user_access VALUES (1360, 1301557247, '::ffff:95.134.100.96', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.16) Gecko/20110319 Firefox/3.6.16', 'http://novi-ua.org/groups', 'people', '', 0);
INSERT INTO user_access VALUES (1360, 1301558252, '::ffff:95.134.100.96', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.16) Gecko/20110319 Firefox/3.6.16', 'http://novi-ua.org/people', 'groups', '', 0);
INSERT INTO user_access VALUES (1360, 1301558254, '::ffff:95.134.100.96', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.16) Gecko/20110319 Firefox/3.6.16', 'http://novi-ua.org/people', 'groups', '', 0);
INSERT INTO user_access VALUES (1360, 1301558259, '::ffff:95.134.100.96', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.16) Gecko/20110319 Firefox/3.6.16', 'http://novi-ua.org/groups', 'groups', 'create', 0);
INSERT INTO user_access VALUES (1360, 1301558286, '::ffff:95.134.100.96', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.16) Gecko/20110319 Firefox/3.6.16', 'http://novi-ua.org/groups/create', 'groups', 'view', 1);
INSERT INTO user_access VALUES (1360, 1301558336, '::ffff:95.134.100.96', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.16) Gecko/20110319 Firefox/3.6.16', 'http://novi-ua.org/group1', 'invites', 'add', 0);
INSERT INTO user_access VALUES (1360, 1301562999, '::ffff:95.134.100.96', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.16) Gecko/20110319 Firefox/3.6.16', '', 'photo', '', 0);
INSERT INTO user_access VALUES (1360, 1301563110, '::ffff:95.134.100.96', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.16) Gecko/20110319 Firefox/3.6.16', '', 'photo', '', 0);
INSERT INTO user_access VALUES (1360, 1301563124, '::ffff:95.134.100.96', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.16) Gecko/20110319 Firefox/3.6.16', 'http://novi-ua.org/photo', 'photo', 'add', 0);
INSERT INTO user_access VALUES (1360, 1301563137, '::ffff:95.134.100.96', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.16) Gecko/20110319 Firefox/3.6.16', 'http://novi-ua.org/photo/add?type=1&oid=1360', 'photo', 'add', 0);
INSERT INTO user_access VALUES (1360, 1301563157, '::ffff:95.134.100.96', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.16) Gecko/20110319 Firefox/3.6.16', 'http://novi-ua.org/photo/add?album_id=1&type=1&oid=1360', 'photo', '', 0);
INSERT INTO user_access VALUES (1360, 1301563172, '::ffff:95.134.100.96', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.16) Gecko/20110319 Firefox/3.6.16', 'http://novi-ua.org/photo/add?album_id=1&type=1&oid=1360', 'photo', '', 0);
INSERT INTO user_access VALUES (1360, 1301564242, '::ffff:95.134.100.96', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.16) Gecko/20110319 Firefox/3.6.16', 'http://novi-ua.org/photo/add?album_id=1&type=1&oid=1360', 'photo', '', 0);
INSERT INTO user_access VALUES (1360, 1301564249, '::ffff:95.134.100.96', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.16) Gecko/20110319 Firefox/3.6.16', 'http://novi-ua.org/photo/add?album_id=1&type=1&oid=1360', 'photo', '', 0);
INSERT INTO user_access VALUES (1360, 1301564886, '::ffff:95.134.100.96', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.16) Gecko/20110319 Firefox/3.6.16', 'http://novi-ua.org/photo/add?album_id=1&type=1&oid=1360', 'photo', '', 0);
INSERT INTO user_access VALUES (1360, 1301564903, '::ffff:95.134.100.96', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.16) Gecko/20110319 Firefox/3.6.16', 'http://novi-ua.org/photo/add?album_id=1&type=1&oid=1360', 'photo', '', 0);
INSERT INTO user_access VALUES (1360, 1301565190, '::ffff:95.134.100.96', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.16) Gecko/20110319 Firefox/3.6.16', 'http://novi-ua.org/photo/add?album_id=1&type=1&oid=1360', 'photo', '', 0);
INSERT INTO user_access VALUES (1360, 1301565216, '::ffff:95.134.100.96', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.16) Gecko/20110319 Firefox/3.6.16', 'http://novi-ua.org/photo?album_id=1', 'photo', 'edit', 0);
INSERT INTO user_access VALUES (1360, 1301565282, '::ffff:95.134.100.96', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.16) Gecko/20110319 Firefox/3.6.16', 'http://novi-ua.org/photo/edit?album_id=1', 'home', '', 0);
INSERT INTO user_access VALUES (1360, 1301565284, '::ffff:95.134.100.96', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.16) Gecko/20110319 Firefox/3.6.16', 'http://novi-ua.org/photo?album_id=1', 'photo', 'edit', 0);
INSERT INTO user_access VALUES (1360, 1301565286, '::ffff:95.134.100.96', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.16) Gecko/20110319 Firefox/3.6.16', 'http://novi-ua.org/photo/edit?album_id=1', 'photo', '', 0);
INSERT INTO user_access VALUES (1360, 1301565291, '::ffff:95.134.100.96', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.16) Gecko/20110319 Firefox/3.6.16', 'http://novi-ua.org/photo?album_id=1', 'photo', '', 0);
INSERT INTO user_access VALUES (1360, 1301565436, '::ffff:95.134.100.96', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.16) Gecko/20110319 Firefox/3.6.16', 'http://novi-ua.org/photo?album_id=1', 'photo', '', 0);
INSERT INTO user_access VALUES (1360, 1301565781, '::ffff:95.134.100.96', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.16) Gecko/20110319 Firefox/3.6.16', 'http://novi-ua.org/photo?type=1&oid=1360', 'photo', 'add', 0);
INSERT INTO user_access VALUES (1360, 1301565793, '::ffff:95.134.100.96', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.16) Gecko/20110319 Firefox/3.6.16', 'http://novi-ua.org/photo/add?type=1&oid=1360', 'photo', '', 0);
INSERT INTO user_access VALUES (1360, 1301565802, '::ffff:95.134.100.96', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.16) Gecko/20110319 Firefox/3.6.16', 'http://novi-ua.org/photo?type=1&oid=1360', 'photo', 'add', 0);
INSERT INTO user_access VALUES (1360, 1301565805, '::ffff:95.134.100.96', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.16) Gecko/20110319 Firefox/3.6.16', 'http://novi-ua.org/photo/add?type=1&oid=1360', 'photo', '', 0);
INSERT INTO user_access VALUES (1360, 1301565806, '::ffff:95.134.100.96', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.16) Gecko/20110319 Firefox/3.6.16', 'http://novi-ua.org/photo?type=1&oid=1360', 'photo', '', 0);
INSERT INTO user_access VALUES (1360, 1301565828, '::ffff:95.134.100.96', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.16) Gecko/20110319 Firefox/3.6.16', 'http://novi-ua.org/photo?album_id=1', 'photo', 'edit', 0);
INSERT INTO user_access VALUES (1360, 1301565835, '::ffff:95.134.100.96', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.16) Gecko/20110319 Firefox/3.6.16', 'http://novi-ua.org/photo?type=1&oid=1360', 'photo', '', 0);
INSERT INTO user_access VALUES (1360, 1301565837, '::ffff:95.134.100.96', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.16) Gecko/20110319 Firefox/3.6.16', 'http://novi-ua.org/photo?album_id=1', 'photo', 'add', 0);
INSERT INTO user_access VALUES (1360, 1301565945, '::ffff:95.134.100.96', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.16) Gecko/20110319 Firefox/3.6.16', 'http://novi-ua.org/photo?album_id=1', 'photo', 'add', 0);
INSERT INTO user_access VALUES (1360, 1301565952, '::ffff:95.134.100.96', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.16) Gecko/20110319 Firefox/3.6.16', 'http://novi-ua.org/photo/add?album_id=1', 'photo', '', 0);
INSERT INTO user_access VALUES (1360, 1301566207, '::ffff:95.134.100.96', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.16) Gecko/20110319 Firefox/3.6.16', 'http://novi-ua.org/photo/add?album_id=1', 'photo', '', 0);
INSERT INTO user_access VALUES (1360, 1301566212, '::ffff:95.134.100.96', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.16) Gecko/20110319 Firefox/3.6.16', 'http://novi-ua.org/photo?type=1&oid=1360', 'photo', '', 0);
INSERT INTO user_access VALUES (1360, 1301566213, '::ffff:95.134.100.96', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.16) Gecko/20110319 Firefox/3.6.16', 'http://novi-ua.org/photo?type=1&oid=1360', 'photo', '', 0);
INSERT INTO user_access VALUES (1360, 1301566216, '::ffff:95.134.100.96', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.16) Gecko/20110319 Firefox/3.6.16', 'http://novi-ua.org/photo?album_id=1', 'photo', 'add', 0);
INSERT INTO user_access VALUES (1360, 1301566222, '::ffff:95.134.100.96', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.16) Gecko/20110319 Firefox/3.6.16', 'http://novi-ua.org/photo?type=1&oid=1360', 'photo', '', 0);
INSERT INTO user_access VALUES (1360, 1301567725, '::ffff:95.134.100.96', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.16) Gecko/20110319 Firefox/3.6.16', 'http://novi-ua.org/photo?album_id=1', 'profile', 'index', 1360);
INSERT INTO user_access VALUES (1360, 1301567733, '::ffff:95.134.100.96', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.16) Gecko/20110319 Firefox/3.6.16', 'http://novi-ua.org/photo?album_id=1', 'profile', 'index', 1360);
INSERT INTO user_access VALUES (1360, 1301567735, '::ffff:95.134.100.96', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.16) Gecko/20110319 Firefox/3.6.16', 'http://novi-ua.org/photo?album_id=1', 'profile', 'index', 1360);
INSERT INTO user_access VALUES (1360, 1301567737, '::ffff:95.134.100.96', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.16) Gecko/20110319 Firefox/3.6.16', 'http://novi-ua.org/photo?album_id=1', 'profile', 'index', 1360);
INSERT INTO user_access VALUES (1360, 1301567745, '::ffff:95.134.100.96', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.16) Gecko/20110319 Firefox/3.6.16', 'http://novi-ua.org/photo?album_id=1', 'profile', 'index', 1360);
INSERT INTO user_access VALUES (1360, 1301567886, '::ffff:95.134.100.96', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.16) Gecko/20110319 Firefox/3.6.16', 'http://novi-ua.org/photo?album_id=1', 'profile', 'index', 1360);
INSERT INTO user_access VALUES (1360, 1301567915, '::ffff:95.134.100.96', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.16) Gecko/20110319 Firefox/3.6.16', 'http://novi-ua.org/profile-1360', 'photo', '', 0);
INSERT INTO user_access VALUES (1360, 1301567919, '::ffff:95.134.100.96', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.16) Gecko/20110319 Firefox/3.6.16', 'http://novi-ua.org/photo?type=1&oid=1360', 'groups', '', 0);
INSERT INTO user_access VALUES (1360, 1301567922, '::ffff:95.134.100.96', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.16) Gecko/20110319 Firefox/3.6.16', 'http://novi-ua.org/groups', 'groups', 'view', 1);
INSERT INTO user_access VALUES (1360, 1301567925, '::ffff:95.134.100.96', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.16) Gecko/20110319 Firefox/3.6.16', 'http://novi-ua.org/group1', 'photo', 'add', 0);
INSERT INTO user_access VALUES (1360, 1301567947, '::ffff:95.134.100.96', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.16) Gecko/20110319 Firefox/3.6.16', 'http://novi-ua.org/photo/add?type=3&oid=1', 'photo', 'add', 0);
INSERT INTO user_access VALUES (1360, 1301567960, '::ffff:95.134.100.96', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.16) Gecko/20110319 Firefox/3.6.16', 'http://novi-ua.org/photo/add?album_id=2&type=3&oid=1', 'photo', '', 0);
INSERT INTO user_access VALUES (1360, 1301567969, '::ffff:95.134.100.96', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.16) Gecko/20110319 Firefox/3.6.16', 'http://novi-ua.org/photo?album_id=2', 'home', '', 0);
INSERT INTO user_access VALUES (1360, 1301567971, '::ffff:95.134.100.96', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.16) Gecko/20110319 Firefox/3.6.16', 'http://novi-ua.org/photo/add?album_id=2&type=3&oid=1', 'photo', '', 0);
INSERT INTO user_access VALUES (1360, 1301567973, '::ffff:95.134.100.96', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.16) Gecko/20110319 Firefox/3.6.16', 'http://novi-ua.org/photo?album_id=2', 'groups', 'view', 1);
INSERT INTO user_access VALUES (1360, 1301568005, '::ffff:95.134.100.96', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.16) Gecko/20110319 Firefox/3.6.16', 'http://novi-ua.org/group1', 'profile', 'index', 1360);
INSERT INTO user_access VALUES (1360, 1301568023, '::ffff:95.134.100.96', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.16) Gecko/20110319 Firefox/3.6.16', 'http://novi-ua.org/photo?album_id=2', 'groups', 'view', 1);
INSERT INTO user_access VALUES (1360, 1301568041, '::ffff:95.134.100.96', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.16) Gecko/20110319 Firefox/3.6.16', 'http://novi-ua.org/group1', 'profile', 'index', 1360);
INSERT INTO user_access VALUES (1360, 1301568078, '::ffff:95.134.100.96', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.16) Gecko/20110319 Firefox/3.6.16', 'http://novi-ua.org/photo?album_id=2', 'groups', 'view', 1);
INSERT INTO user_access VALUES (1360, 1301568148, '::ffff:95.134.100.96', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.16) Gecko/20110319 Firefox/3.6.16', 'http://novi-ua.org/photo?album_id=2', 'groups', 'view', 1);
INSERT INTO user_access VALUES (1360, 1301568879, '::ffff:95.134.100.96', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.16) Gecko/20110319 Firefox/3.6.16', 'http://novi-ua.org/group1', 'profile', 'index', 1360);
INSERT INTO user_access VALUES (1360, 1301568882, '::ffff:95.134.100.96', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.16) Gecko/20110319 Firefox/3.6.16', 'http://novi-ua.org/profile-1360', 'profile', 'edit', 1360);
INSERT INTO user_access VALUES (1360, 1301568963, '::ffff:95.134.100.96', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.16) Gecko/20110319 Firefox/3.6.16', 'http://novi-ua.org/profile-1360', 'profile', 'edit', 1360);
INSERT INTO user_access VALUES (1360, 1301568995, '::ffff:95.134.100.96', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.16) Gecko/20110319 Firefox/3.6.16', 'http://novi-ua.org/profile/edit?id=1360', 'profile', 'edit', 1360);
INSERT INTO user_access VALUES (1360, 1301580575, '::ffff:95.134.100.96', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.16) Gecko/20110319 Firefox/3.6.16', 'http://novi-ua.org/profile-1360', 'profile', 'edit', 1360);
INSERT INTO user_access VALUES (1360, 1301580607, '::ffff:95.134.100.96', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.16) Gecko/20110319 Firefox/3.6.16', 'http://novi-ua.org/profile-1360', 'profile', 'edit', 1360);
INSERT INTO user_access VALUES (1360, 1301580664, '::ffff:95.134.100.96', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.16) Gecko/20110319 Firefox/3.6.16', 'http://novi-ua.org/profile-1360', 'profile', 'edit', 1360);
INSERT INTO user_access VALUES (1360, 1301581145, '::ffff:95.134.100.96', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.16) Gecko/20110319 Firefox/3.6.16', 'http://novi-ua.org/profile-1360', 'profile', 'edit', 1360);
INSERT INTO user_access VALUES (1360, 1301583459, '::ffff:95.134.100.96', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.16) Gecko/20110319 Firefox/3.6.16', 'http://novi-ua.org/profile/edit?id=1360', 'profile', 'edit', 1360);
INSERT INTO user_access VALUES (1360, 1301583709, '::ffff:95.134.100.96', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.16) Gecko/20110319 Firefox/3.6.16', 'http://novi-ua.org/profile-1360', 'profile', 'edit', 1360);
INSERT INTO user_access VALUES (1360, 1301584458, '::ffff:95.134.100.96', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.16) Gecko/20110319 Firefox/3.6.16', 'http://novi-ua.org/profile-1360', 'profile', 'edit', 1360);
INSERT INTO user_access VALUES (1360, 1301584497, '::ffff:95.134.100.96', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.16) Gecko/20110319 Firefox/3.6.16', 'http://novi-ua.org/profile-1360', 'profile', 'edit', 1360);
INSERT INTO user_access VALUES (1360, 1301584518, '::ffff:95.134.100.96', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.16) Gecko/20110319 Firefox/3.6.16', 'http://novi-ua.org/profile-1360', 'profile', 'edit', 1360);
INSERT INTO user_access VALUES (1360, 1301584582, '::ffff:95.134.100.96', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.16) Gecko/20110319 Firefox/3.6.16', 'http://novi-ua.org/profile-1360', 'profile', 'edit', 1360);
INSERT INTO user_access VALUES (1360, 1301584617, '::ffff:95.134.100.96', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.16) Gecko/20110319 Firefox/3.6.16', 'http://novi-ua.org/profile-1360', 'profile', 'edit', 1360);
INSERT INTO user_access VALUES (1360, 1301584848, '::ffff:95.134.100.96', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.16) Gecko/20110319 Firefox/3.6.16', 'http://novi-ua.org/profile-1360', 'profile', 'edit', 1360);
INSERT INTO user_access VALUES (1360, 1301584870, '::ffff:95.134.100.96', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.16) Gecko/20110319 Firefox/3.6.16', 'http://novi-ua.org/profile-1360', 'profile', 'edit', 1360);
INSERT INTO user_access VALUES (1360, 1301584930, '::ffff:95.134.100.96', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.16) Gecko/20110319 Firefox/3.6.16', 'http://novi-ua.org/profile-1360', 'profile', 'edit', 1360);
INSERT INTO user_access VALUES (1360, 1301584947, '::ffff:95.134.100.96', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.16) Gecko/20110319 Firefox/3.6.16', 'http://novi-ua.org/profile-1360', 'profile', 'edit', 1360);
INSERT INTO user_access VALUES (1360, 1301642932, '::ffff:92.113.164.57', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.16) Gecko/20110319 Firefox/3.6.16', 'http://novi-ua.org/profile-1360', 'profile', 'edit', 1360);
INSERT INTO user_access VALUES (1360, 1301653939, '::ffff:92.113.164.57', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.16) Gecko/20110319 Firefox/3.6.16', 'http://novi-ua.org/profile-1360', 'profile', 'edit', 1360);
INSERT INTO user_access VALUES (1360, 1301904809, '::ffff:92.113.106.170', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.16) Gecko/20110319 Firefox/3.6.16', 'http://novi-ua.org/profile-1360', 'profile', 'edit', 1360);
INSERT INTO user_access VALUES (1360, 1301927076, '::ffff:92.113.106.170', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.16) Gecko/20110319 Firefox/3.6.16', 'http://novi-ua.org/profile-1360', 'profile', 'edit', 1360);
INSERT INTO user_access VALUES (1360, 1301929411, '::ffff:92.113.106.170', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.16) Gecko/20110319 Firefox/3.6.16', 'http://novi-ua.org/profile-1360', 'profile', 'edit', 1360);
INSERT INTO user_access VALUES (1360, 1301989751, '::ffff:95.134.89.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.16) Gecko/20110319 Firefox/3.6.16', 'http://novi-ua.org/profile-1360', 'profile', 'edit', 1360);
INSERT INTO user_access VALUES (29, 1302014416, '::ffff:213.227.210.148', 'Mozilla/5.0 (X11; U; Linux x86_64; uk; rv:1.9.2.13) Gecko/20101206 Ubuntu/10.04 (lucid) Firefox/3.6.13', '', 'home', '', 0);
INSERT INTO user_access VALUES (5, 1302517916, '::ffff:213.227.210.148', 'Mozilla/5.0 (Windows; U; Windows NT 6.1; ru; rv:1.9.2.16) Gecko/20110319 Firefox/3.6.16 GTB7.1 ( .NET CLR 3.5.30729; .NET4.0E) WebMoney Advisor', 'http://novi-ua.org/sign', 'profile', '', 0);
INSERT INTO user_access VALUES (5, 1302517925, '::ffff:213.227.210.148', 'Mozilla/5.0 (Windows; U; Windows NT 6.1; ru; rv:1.9.2.16) Gecko/20110319 Firefox/3.6.16 GTB7.1 ( .NET CLR 3.5.30729; .NET4.0E) WebMoney Advisor', 'http://novi-ua.org/profile', 'people', '', 0);
INSERT INTO user_access VALUES (5, 1302517931, '::ffff:213.227.210.148', 'Mozilla/5.0 (Windows; U; Windows NT 6.1; ru; rv:1.9.2.16) Gecko/20110319 Firefox/3.6.16 GTB7.1 ( .NET CLR 3.5.30729; .NET4.0E) WebMoney Advisor', 'http://novi-ua.org/people', 'profile', 'index', 5);
INSERT INTO user_access VALUES (5, 1302517936, '::ffff:213.227.210.148', 'Mozilla/5.0 (Windows; U; Windows NT 6.1; ru; rv:1.9.2.16) Gecko/20110319 Firefox/3.6.16 GTB7.1 ( .NET CLR 3.5.30729; .NET4.0E) WebMoney Advisor', 'http://novi-ua.org/profile-5', 'profile', 'edit', 5);
INSERT INTO user_access VALUES (5, 1302518141, '::ffff:213.227.210.148', 'Mozilla/5.0 (Windows; U; Windows NT 6.1; ru; rv:1.9.2.16) Gecko/20110319 Firefox/3.6.16 GTB7.1 ( .NET CLR 3.5.30729; .NET4.0E) WebMoney Advisor', 'http://novi-ua.org/profile/edit?id=5', 'profile', 'index', 5);
INSERT INTO user_access VALUES (5, 1302518221, '::ffff:213.227.210.148', 'Mozilla/5.0 (Windows; U; Windows NT 6.1; ru; rv:1.9.2.16) Gecko/20110319 Firefox/3.6.16 GTB7.1 ( .NET CLR 3.5.30729; .NET4.0E) WebMoney Advisor', 'http://novi-ua.org/profile-5', 'home', '', 0);
INSERT INTO user_access VALUES (5, 1302518237, '::ffff:213.227.210.148', 'Mozilla/5.0 (Windows; U; Windows NT 6.1; ru; rv:1.9.2.16) Gecko/20110319 Firefox/3.6.16 GTB7.1 ( .NET CLR 3.5.30729; .NET4.0E) WebMoney Advisor', 'http://novi-ua.org/home', 'home', '', 0);
INSERT INTO user_access VALUES (5, 1302518242, '::ffff:213.227.210.148', 'Mozilla/5.0 (Windows; U; Windows NT 6.1; ru; rv:1.9.2.16) Gecko/20110319 Firefox/3.6.16 GTB7.1 ( .NET CLR 3.5.30729; .NET4.0E) WebMoney Advisor', 'http://novi-ua.org/home', 'people', '', 0);
INSERT INTO user_access VALUES (1360, 1305635006, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.17) Gecko/20110420 Firefox/3.6.17', 'http://novi-ua.org/', 'profile', '', 0);
INSERT INTO user_access VALUES (1360, 1305635018, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.17) Gecko/20110420 Firefox/3.6.17', 'http://novi-ua.org/profile', 'profile', 'edit', 1360);
INSERT INTO user_access VALUES (1360, 1305642142, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.17) Gecko/20110420 Firefox/3.6.17', 'http://novi-ua.org/profile', 'profile', 'edit', 1360);
INSERT INTO user_access VALUES (2, 1306132202, '::ffff:46.202.234.178', 'Mozilla/5.0 (iPad; U; CPU OS 4_3 like Mac OS X; ru-ru) AppleWebKit/533.17.9 (KHTML, like Gecko) Version/5.0.2 Mobile/8F191 Safari/6533.18.5', 'http://novi-ua.org/', 'profile', '', 0);
INSERT INTO user_access VALUES (2, 1306132236, '::ffff:46.202.234.178', 'Mozilla/5.0 (iPad; U; CPU OS 4_3 like Mac OS X; ru-ru) AppleWebKit/533.17.9 (KHTML, like Gecko) Version/5.0.2 Mobile/8F191 Safari/6533.18.5', 'http://novi-ua.org/profile', 'profile', 'edit', 2);
INSERT INTO user_access VALUES (2, 1306132281, '::ffff:46.202.234.178', 'Mozilla/5.0 (iPad; U; CPU OS 4_3 like Mac OS X; ru-ru) AppleWebKit/533.17.9 (KHTML, like Gecko) Version/5.0.2 Mobile/8F191 Safari/6533.18.5', 'http://novi-ua.org/profile/edit?id=2', 'home', '', 0);
INSERT INTO user_access VALUES (2, 1309422984, '::ffff:193.107.184.85', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; ru; rv:1.9.0.6) Gecko/2009011913 Firefox/3.0.6 (.NET CLR 3.5.30729)', 'http://novi-ua.org/', 'profile', '', 0);
INSERT INTO user_access VALUES (2, 1309423024, '::ffff:193.107.184.85', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; ru; rv:1.9.0.6) Gecko/2009011913 Firefox/3.0.6 (.NET CLR 3.5.30729)', 'http://novi-ua.org/profile', 'profile', 'edit', 2);
INSERT INTO user_access VALUES (2, 1309423072, '::ffff:193.107.184.85', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; ru; rv:1.9.0.6) Gecko/2009011913 Firefox/3.0.6 (.NET CLR 3.5.30729)', 'http://novi-ua.org/profile/edit?id=2', 'home', '', 0);
INSERT INTO user_access VALUES (2, 1309423236, '::ffff:193.107.184.85', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; ru; rv:1.9.0.6) Gecko/2009011913 Firefox/3.0.6 (.NET CLR 3.5.30729)', 'http://novi-ua.org/home', 'people', '', 0);
INSERT INTO user_access VALUES (2, 1309423241, '::ffff:193.107.184.85', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; ru; rv:1.9.0.6) Gecko/2009011913 Firefox/3.0.6 (.NET CLR 3.5.30729)', 'http://novi-ua.org/people', 'profile', 'index', 5);
INSERT INTO user_access VALUES (2, 1309423252, '::ffff:193.107.184.85', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; ru; rv:1.9.0.6) Gecko/2009011913 Firefox/3.0.6 (.NET CLR 3.5.30729)', 'http://novi-ua.org/profile-5', 'profile', 'edit', 5);
INSERT INTO user_access VALUES (2, 1309423298, '::ffff:193.107.184.85', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; ru; rv:1.9.0.6) Gecko/2009011913 Firefox/3.0.6 (.NET CLR 3.5.30729)', 'http://novi-ua.org/people', 'profile', 'index', 5);
INSERT INTO user_access VALUES (2, 1309423302, '::ffff:193.107.184.85', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; ru; rv:1.9.0.6) Gecko/2009011913 Firefox/3.0.6 (.NET CLR 3.5.30729)', 'http://novi-ua.org/profile-5', 'home', '', 0);
INSERT INTO user_access VALUES (2, 1309603610, '::ffff:31.40.220.184', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; ru; rv:1.9.0.6) Gecko/2010031422 Firefox/3.0.19 (.NET CLR 3.5.30729)', '', 'home', '', 0);
INSERT INTO user_access VALUES (1360, 1310047625, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.18) Gecko/20110614 Firefox/3.6.18', 'http://novi-ua.org/sign', 'profile', '', 0);
INSERT INTO user_access VALUES (1360, 1310047634, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.18) Gecko/20110614 Firefox/3.6.18', 'http://novi-ua.org/profile', 'people', '', 0);
INSERT INTO user_access VALUES (1360, 1310047636, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.18) Gecko/20110614 Firefox/3.6.18', 'http://novi-ua.org/people', 'profile', 'index', 5);
INSERT INTO user_access VALUES (1360, 1310047639, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.18) Gecko/20110614 Firefox/3.6.18', 'http://novi-ua.org/profile-5', 'profile', 'edit', 5);
INSERT INTO user_access VALUES (5, 1310047705, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.18) Gecko/20110614 Firefox/3.6.18', 'http://novi-ua.org/', 'profile', '', 0);
INSERT INTO user_access VALUES (5, 1310047899, '::ffff:194.187.108.86', 'Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 6.1; WOW64; Trident/4.0; WebMoney Advisor; SLCC2; .NET CLR 2.0.50727; .NET CLR 3.5.30729; .NET CLR 3.0.30729; Media Center PC 6.0; InfoPath.2; .NET4.0C; .NET4.0E)', 'http://novi-ua.org/', 'profile', '', 0);
INSERT INTO user_access VALUES (5, 1310048034, '::ffff:194.187.108.86', 'Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 6.1; WOW64; Trident/4.0; WebMoney Advisor; SLCC2; .NET CLR 2.0.50727; .NET CLR 3.5.30729; .NET CLR 3.0.30729; Media Center PC 6.0; InfoPath.2; .NET4.0C; .NET4.0E)', 'http://novi-ua.org/profile', 'profile', 'edit', 5);
INSERT INTO user_access VALUES (5, 1310048310, '::ffff:194.187.108.86', 'Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 6.1; WOW64; Trident/4.0; WebMoney Advisor; SLCC2; .NET CLR 2.0.50727; .NET CLR 3.5.30729; .NET CLR 3.0.30729; Media Center PC 6.0; InfoPath.2; .NET4.0C; .NET4.0E)', 'http://novi-ua.org/profile/edit?id=5', 'home', '', 0);
INSERT INTO user_access VALUES (5, 1310048321, '::ffff:194.187.108.86', 'Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 6.1; WOW64; Trident/4.0; WebMoney Advisor; SLCC2; .NET CLR 2.0.50727; .NET CLR 3.5.30729; .NET CLR 3.0.30729; Media Center PC 6.0; InfoPath.2; .NET4.0C; .NET4.0E)', 'http://novi-ua.org/home', 'profile', 'index', 5);
INSERT INTO user_access VALUES (5, 1310048324, '::ffff:194.187.108.86', 'Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 6.1; WOW64; Trident/4.0; WebMoney Advisor; SLCC2; .NET CLR 2.0.50727; .NET CLR 3.5.30729; .NET CLR 3.0.30729; Media Center PC 6.0; InfoPath.2; .NET4.0C; .NET4.0E)', 'http://novi-ua.org/profile-5', 'profile', 'edit', 5);
INSERT INTO user_access VALUES (5, 1310048353, '::ffff:194.187.108.86', 'Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 6.1; WOW64; Trident/4.0; WebMoney Advisor; SLCC2; .NET CLR 2.0.50727; .NET CLR 3.5.30729; .NET CLR 3.0.30729; Media Center PC 6.0; InfoPath.2; .NET4.0C; .NET4.0E)', 'http://novi-ua.org/profile/edit?id=5', 'people', '', 0);
INSERT INTO user_access VALUES (5, 1310053986, '::ffff:194.187.108.86', 'Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 6.1; WOW64; Trident/4.0; WebMoney Advisor; SLCC2; .NET CLR 2.0.50727; .NET CLR 3.5.30729; .NET CLR 3.0.30729; Media Center PC 6.0; InfoPath.2; .NET4.0C; .NET4.0E)', 'http://novi-ua.org/people', 'home', '', 0);
INSERT INTO user_access VALUES (5, 1310113836, '::ffff:194.187.108.86', 'Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 6.1; WOW64; Trident/4.0; WebMoney Advisor; SLCC2; .NET CLR 2.0.50727; .NET CLR 3.5.30729; .NET CLR 3.0.30729; Media Center PC 6.0; InfoPath.2; .NET4.0C; .NET4.0E)', '', 'home', '', 0);
INSERT INTO user_access VALUES (5, 1310113841, '::ffff:194.187.108.86', 'Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 6.1; WOW64; Trident/4.0; WebMoney Advisor; SLCC2; .NET CLR 2.0.50727; .NET CLR 3.5.30729; .NET CLR 3.0.30729; Media Center PC 6.0; InfoPath.2; .NET4.0C; .NET4.0E)', 'http://novi-ua.org/home', 'profile', 'index', 5);
INSERT INTO user_access VALUES (5, 1310113844, '::ffff:194.187.108.86', 'Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 6.1; WOW64; Trident/4.0; WebMoney Advisor; SLCC2; .NET CLR 2.0.50727; .NET CLR 3.5.30729; .NET CLR 3.0.30729; Media Center PC 6.0; InfoPath.2; .NET4.0C; .NET4.0E)', 'http://novi-ua.org/profile-5', 'profile', 'invite', 0);
INSERT INTO user_access VALUES (5, 1310113891, '::ffff:194.187.108.86', 'Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 6.1; WOW64; Trident/4.0; WebMoney Advisor; SLCC2; .NET CLR 2.0.50727; .NET CLR 3.5.30729; .NET CLR 3.0.30729; Media Center PC 6.0; InfoPath.2; .NET4.0C; .NET4.0E)', 'http://novi-ua.org/profile/invite', 'profile', 'index', 1);
INSERT INTO user_access VALUES (1360, 1310113935, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.18) Gecko/20110614 Firefox/3.6.18', 'http://novi-ua.org/', 'profile', '', 0);
INSERT INTO user_access VALUES (1360, 1310113963, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.18) Gecko/20110614 Firefox/3.6.18', 'http://novi-ua.org/', 'profile', '', 0);
INSERT INTO user_access VALUES (1360, 1310114026, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.18) Gecko/20110614 Firefox/3.6.18', 'http://novi-ua.org/', 'profile', '', 0);
INSERT INTO user_access VALUES (1360, 1310114447, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.18) Gecko/20110614 Firefox/3.6.18', 'http://novi-ua.org/', 'profile', '', 0);
INSERT INTO user_access VALUES (1360, 1310114683, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.18) Gecko/20110614 Firefox/3.6.18', 'http://novi-ua.org/profile', 'home', '', 0);
INSERT INTO user_access VALUES (1360, 1310114690, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.18) Gecko/20110614 Firefox/3.6.18', 'http://novi-ua.org/', 'profile', '', 0);
INSERT INTO user_access VALUES (1360, 1310114718, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.18) Gecko/20110614 Firefox/3.6.18', 'http://novi-ua.org/', 'profile', '', 0);
INSERT INTO user_access VALUES (1360, 1310114722, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.18) Gecko/20110614 Firefox/3.6.18', 'http://novi-ua.org/profile', 'home', '', 0);
INSERT INTO user_access VALUES (1360, 1310114729, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.18) Gecko/20110614 Firefox/3.6.18', 'http://novi-ua.org/', 'profile', '', 0);
INSERT INTO user_access VALUES (1360, 1310115443, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.18) Gecko/20110614 Firefox/3.6.18', 'http://novi-ua.org/home', 'home', '', 0);
INSERT INTO user_access VALUES (1360, 1310115448, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.18) Gecko/20110614 Firefox/3.6.18', 'http://novi-ua.org/home', 'home', '', 0);
INSERT INTO user_access VALUES (1360, 1310115502, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.18) Gecko/20110614 Firefox/3.6.18', 'http://novi-ua.org/home', 'home', '', 0);
INSERT INTO user_access VALUES (1360, 1310115509, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.18) Gecko/20110614 Firefox/3.6.18', 'http://novi-ua.org/home', 'home', '', 0);
INSERT INTO user_access VALUES (1360, 1310115616, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.18) Gecko/20110614 Firefox/3.6.18', 'http://novi-ua.org/home', 'home', '', 0);
INSERT INTO user_access VALUES (1360, 1310115639, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.18) Gecko/20110614 Firefox/3.6.18', 'http://novi-ua.org/', 'home', '', 0);
INSERT INTO user_access VALUES (1360, 1310115691, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.18) Gecko/20110614 Firefox/3.6.18', 'http://novi-ua.org/', 'home', '', 0);
INSERT INTO user_access VALUES (1360, 1310116680, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.18) Gecko/20110614 Firefox/3.6.18', 'http://novi-ua.org/', 'home', '', 0);
INSERT INTO user_access VALUES (1360, 1310116683, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.18) Gecko/20110614 Firefox/3.6.18', 'http://novi-ua.org/home', 'home', '', 0);
INSERT INTO user_access VALUES (1360, 1310116729, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.18) Gecko/20110614 Firefox/3.6.18', 'http://novi-ua.org/', 'home', '', 0);
INSERT INTO user_access VALUES (1360, 1310117024, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.18) Gecko/20110614 Firefox/3.6.18', 'http://novi-ua.org/', 'home', '', 0);
INSERT INTO user_access VALUES (1360, 1310117032, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.18) Gecko/20110614 Firefox/3.6.18', 'http://novi-ua.org/home', 'profile', 'index', 1360);
INSERT INTO user_access VALUES (1360, 1310117038, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.18) Gecko/20110614 Firefox/3.6.18', 'http://novi-ua.org/profile-1360', 'profile', 'edit', 1360);
INSERT INTO user_access VALUES (1360, 1310117216, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.18) Gecko/20110614 Firefox/3.6.18', 'http://novi-ua.org/profile/edit?id=1360', 'home', '', 0);
INSERT INTO user_access VALUES (1360, 1310117436, '::ffff:194.187.108.86', 'Mozilla/5.0 (X11; Linux i686; rv:2.0.1) Gecko/20100101 Firefox/4.0.1', '', 'home', '', 0);
INSERT INTO user_access VALUES (5, 1310117522, '::ffff:194.187.108.86', 'Mozilla/5.0 (X11; Linux i686; rv:5.0) Gecko/20100101 Firefox/5.0', '', 'home', '', 0);
INSERT INTO user_access VALUES (5, 1310117539, '::ffff:194.187.108.86', 'Mozilla/5.0 (X11; Linux i686; rv:5.0) Gecko/20100101 Firefox/5.0', 'http://novi-ua.org/home', 'people', '', 0);
INSERT INTO user_access VALUES (5, 1310117562, '::ffff:194.187.108.86', 'Mozilla/5.0 (X11; Linux i686; rv:5.0) Gecko/20100101 Firefox/5.0', 'http://novi-ua.org/people', 'home', '', 0);
INSERT INTO user_access VALUES (1360, 1310118351, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.18) Gecko/20110614 Firefox/3.6.18', 'http://novi-ua.org/home', 'home', '', 0);
INSERT INTO user_access VALUES (1360, 1310118367, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.18) Gecko/20110614 Firefox/3.6.18', 'http://novi-ua.org/home', 'profile', 'index', 1360);
INSERT INTO user_access VALUES (1360, 1310123989, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.18) Gecko/20110614 Firefox/3.6.18', '', 'profile', 'add', 0);
INSERT INTO user_access VALUES (1360, 1310124005, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.18) Gecko/20110614 Firefox/3.6.18', 'http://novi-ua.org/profile/add', 'profile', 'add', 0);
INSERT INTO user_access VALUES (1360, 1310124078, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.18) Gecko/20110614 Firefox/3.6.18', 'http://novi-ua.org/profile/add', 'profile', 'index', 1360);
INSERT INTO user_access VALUES (1360, 1310124081, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.18) Gecko/20110614 Firefox/3.6.18', 'http://novi-ua.org/profile-1360', 'profile', 'edit', 1360);
INSERT INTO user_access VALUES (1360, 1310124436, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.18) Gecko/20110614 Firefox/3.6.18', '', 'profile', 'add', 0);
INSERT INTO user_access VALUES (1360, 1310124607, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.18) Gecko/20110614 Firefox/3.6.18', '', 'profile', 'add', 0);
INSERT INTO user_access VALUES (1360, 1310124845, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.18) Gecko/20110614 Firefox/3.6.18', '', 'profile', 'add', 0);
INSERT INTO user_access VALUES (1360, 1310124859, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.18) Gecko/20110614 Firefox/3.6.18', 'http://novi-ua.org/profile/add', 'profile', 'add', 0);
INSERT INTO user_access VALUES (1360, 1310124872, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.18) Gecko/20110614 Firefox/3.6.18', 'http://novi-ua.org/profile/add', 'people', '', 0);
INSERT INTO user_access VALUES (1360, 1310124886, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.18) Gecko/20110614 Firefox/3.6.18', 'http://novi-ua.org/profile/add', 'profile', 'add', 0);
INSERT INTO user_access VALUES (1360, 1310125457, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.18) Gecko/20110614 Firefox/3.6.18', 'http://novi-ua.org/profile/add', 'profile', 'add', 0);
INSERT INTO user_access VALUES (1360, 1310126038, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.18) Gecko/20110614 Firefox/3.6.18', 'http://novi-ua.org/profile/add', 'profile', 'add', 0);
INSERT INTO user_access VALUES (1360, 1310126041, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.18) Gecko/20110614 Firefox/3.6.18', 'http://novi-ua.org/profile/add', 'profile', 'add', 0);
INSERT INTO user_access VALUES (1360, 1310126084, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.18) Gecko/20110614 Firefox/3.6.18', 'http://novi-ua.org/profile/add', 'profile', 'add', 0);
INSERT INTO user_access VALUES (1360, 1310126180, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.18) Gecko/20110614 Firefox/3.6.18', 'http://novi-ua.org/profile/add', 'profile', 'add', 0);
INSERT INTO user_access VALUES (1360, 1310126204, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.18) Gecko/20110614 Firefox/3.6.18', '', 'profile', 'add', 0);
INSERT INTO user_access VALUES (1360, 1310126213, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.18) Gecko/20110614 Firefox/3.6.18', 'http://novi-ua.org/profile/add', 'profile', 'add', 0);
INSERT INTO user_access VALUES (1360, 1310126223, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.18) Gecko/20110614 Firefox/3.6.18', 'http://novi-ua.org/people', 'people', '', 0);
INSERT INTO user_access VALUES (1360, 1310126240, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.18) Gecko/20110614 Firefox/3.6.18', 'http://novi-ua.org/profile/add', 'profile', 'index', 6);
INSERT INTO user_access VALUES (1360, 1310126254, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.18) Gecko/20110614 Firefox/3.6.18', '', 'profile', 'add', 0);
INSERT INTO user_access VALUES (1360, 1310126268, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.18) Gecko/20110614 Firefox/3.6.18', 'http://novi-ua.org/profile/add', 'profile', 'index', 7);
INSERT INTO user_access VALUES (1360, 1310126279, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.18) Gecko/20110614 Firefox/3.6.18', 'http://novi-ua.org/people', 'people', '', 0);
INSERT INTO user_access VALUES (1360, 1310126282, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.18) Gecko/20110614 Firefox/3.6.18', 'http://novi-ua.org/people', 'people', '', 0);
INSERT INTO user_access VALUES (1360, 1310126284, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.18) Gecko/20110614 Firefox/3.6.18', 'http://novi-ua.org/people', 'people', '', 0);
INSERT INTO user_access VALUES (1360, 1310126285, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.18) Gecko/20110614 Firefox/3.6.18', 'http://novi-ua.org/people', 'profile', 'index', 1360);
INSERT INTO user_access VALUES (1360, 1310126359, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.18) Gecko/20110614 Firefox/3.6.18', '', 'profile', 'add', 0);
INSERT INTO user_access VALUES (1360, 1310126366, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.18) Gecko/20110614 Firefox/3.6.18', 'http://novi-ua.org/profile/add', 'profile', 'index', 1360);
INSERT INTO user_access VALUES (1360, 1310126382, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.18) Gecko/20110614 Firefox/3.6.18', 'http://novi-ua.org/profile-1360', 'admin', '', 0);
INSERT INTO user_access VALUES (1360, 1310126388, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.18) Gecko/20110614 Firefox/3.6.18', 'http://novi-ua.org/admin', 'admin', 'users', 0);
INSERT INTO user_access VALUES (1360, 1310126390, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.18) Gecko/20110614 Firefox/3.6.18', 'http://novi-ua.org/admin/users', 'admin', 'users_list', 0);
INSERT INTO user_access VALUES (1360, 1310126397, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.18) Gecko/20110614 Firefox/3.6.18', 'http://novi-ua.org/admin/users_list', 'profile', 'index', 1360);
INSERT INTO user_access VALUES (1360, 1310126606, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.18) Gecko/20110614 Firefox/3.6.18', 'http://novi-ua.org/admin/users_list', 'profile', 'index', 1360);
INSERT INTO user_access VALUES (1360, 1310126610, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.18) Gecko/20110614 Firefox/3.6.18', 'http://novi-ua.org/profile-1360', 'profile', 'add', 0);
INSERT INTO user_access VALUES (1360, 1310126621, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.18) Gecko/20110614 Firefox/3.6.18', 'http://novi-ua.org/profile/add', 'profile', 'index', 8);
INSERT INTO user_access VALUES (1360, 1310126627, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.18) Gecko/20110614 Firefox/3.6.18', 'http://novi-ua.org/profile-8', 'profile', 'index', 1360);
INSERT INTO user_access VALUES (1360, 1310126649, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.18) Gecko/20110614 Firefox/3.6.18', 'http://novi-ua.org/profile-1360', 'people', '', 0);
INSERT INTO user_access VALUES (1360, 1310126654, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.18) Gecko/20110614 Firefox/3.6.18', 'http://novi-ua.org/people', 'people', 'index', 0);
INSERT INTO user_access VALUES (1360, 1310126663, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.18) Gecko/20110614 Firefox/3.6.18', 'http://novi-ua.org/people/index?offline=all', 'profile', 'index', 8);
INSERT INTO user_access VALUES (1360, 1310126665, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.18) Gecko/20110614 Firefox/3.6.18', 'http://novi-ua.org/profile-8', 'profile', 'edit', 8);
INSERT INTO user_access VALUES (1360, 1310126933, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.18) Gecko/20110614 Firefox/3.6.18', 'http://novi-ua.org/profile-8', 'profile', 'edit', 8);
INSERT INTO user_access VALUES (1360, 1310127062, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.18) Gecko/20110614 Firefox/3.6.18', 'http://novi-ua.org/profile-8', 'profile', 'edit', 8);
INSERT INTO user_access VALUES (1360, 1310127370, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.18) Gecko/20110614 Firefox/3.6.18', 'http://novi-ua.org/profile-8', 'profile', 'edit', 8);
INSERT INTO user_access VALUES (1360, 1310127372, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.18) Gecko/20110614 Firefox/3.6.18', 'http://novi-ua.org/profile-8', 'profile', 'edit', 8);
INSERT INTO user_access VALUES (1360, 1310127375, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.18) Gecko/20110614 Firefox/3.6.18', 'http://novi-ua.org/profile-8', 'profile', 'edit', 8);
INSERT INTO user_access VALUES (1360, 1310127398, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.18) Gecko/20110614 Firefox/3.6.18', 'http://novi-ua.org/profile-8', 'profile', 'edit', 8);
INSERT INTO user_access VALUES (1360, 1310127677, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.18) Gecko/20110614 Firefox/3.6.18', 'http://novi-ua.org/profile-8', 'profile', 'edit', 8);
INSERT INTO user_access VALUES (1360, 1310127679, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.18) Gecko/20110614 Firefox/3.6.18', '', 'profile', 'edit', 8);
INSERT INTO user_access VALUES (1360, 1310127685, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.18) Gecko/20110614 Firefox/3.6.18', '', 'profile', 'edit', 8);
INSERT INTO user_access VALUES (1360, 1310127712, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.18) Gecko/20110614 Firefox/3.6.18', '', 'profile', 'edit', 8);
INSERT INTO user_access VALUES (1360, 1310127844, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.18) Gecko/20110614 Firefox/3.6.18', '', 'profile', 'edit', 8);
INSERT INTO user_access VALUES (1360, 1310127848, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.18) Gecko/20110614 Firefox/3.6.18', '', 'profile', 'edit', 8);
INSERT INTO user_access VALUES (1360, 1310127921, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.18) Gecko/20110614 Firefox/3.6.18', '', 'profile', 'edit', 8);
INSERT INTO user_access VALUES (1360, 1310127945, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.18) Gecko/20110614 Firefox/3.6.18', 'http://novi-ua.org/profile/edit?id=8', 'home', '', 0);
INSERT INTO user_access VALUES (1360, 1310127950, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.18) Gecko/20110614 Firefox/3.6.18', 'http://novi-ua.org/home', 'people', '', 0);
INSERT INTO user_access VALUES (1360, 1310127957, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.18) Gecko/20110614 Firefox/3.6.18', 'http://novi-ua.org/people', 'people', 'index', 0);
INSERT INTO user_access VALUES (1360, 1310127961, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.18) Gecko/20110614 Firefox/3.6.18', 'http://novi-ua.org/people/index?offline=all', 'home', '', 0);
INSERT INTO user_access VALUES (1360, 1310127965, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.18) Gecko/20110614 Firefox/3.6.18', 'http://novi-ua.org/home', 'people', '', 0);
INSERT INTO user_access VALUES (1360, 1310127972, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.18) Gecko/20110614 Firefox/3.6.18', 'http://novi-ua.org/people', 'people', '', 0);
INSERT INTO user_access VALUES (1360, 1310127975, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.18) Gecko/20110614 Firefox/3.6.18', 'http://novi-ua.org/people', 'profile', 'index', 1360);
INSERT INTO user_access VALUES (1360, 1310127978, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.18) Gecko/20110614 Firefox/3.6.18', 'http://novi-ua.org/profile-1360', 'admin', '', 0);
INSERT INTO user_access VALUES (1360, 1310127980, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.18) Gecko/20110614 Firefox/3.6.18', 'http://novi-ua.org/admin', 'admin', 'users', 0);
INSERT INTO user_access VALUES (1360, 1310127981, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.18) Gecko/20110614 Firefox/3.6.18', 'http://novi-ua.org/admin/users', 'admin', 'users_list', 0);
INSERT INTO user_access VALUES (1360, 1310127996, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.18) Gecko/20110614 Firefox/3.6.18', 'http://novi-ua.org/admin/users_list', 'people', '', 0);
INSERT INTO user_access VALUES (1360, 1310127999, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.18) Gecko/20110614 Firefox/3.6.18', 'http://novi-ua.org/people', 'home', '', 0);
INSERT INTO user_access VALUES (1360, 1310128001, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.18) Gecko/20110614 Firefox/3.6.18', 'http://novi-ua.org/home', 'people', '', 0);
INSERT INTO user_access VALUES (1360, 1310128010, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.18) Gecko/20110614 Firefox/3.6.18', 'http://novi-ua.org/people', 'people', 'index', 0);
INSERT INTO user_access VALUES (1360, 1310128015, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.18) Gecko/20110614 Firefox/3.6.18', 'http://novi-ua.org/people/index?identification=check', 'people', 'index', 0);
INSERT INTO user_access VALUES (1360, 1310128172, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.18) Gecko/20110614 Firefox/3.6.18', 'http://novi-ua.org/people/index?activate=1', 'people', 'index', 0);
INSERT INTO user_access VALUES (1360, 1310128174, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.18) Gecko/20110614 Firefox/3.6.18', 'http://novi-ua.org/people/index?offline=all', 'profile', 'index', 6);
INSERT INTO user_access VALUES (1360, 1310128176, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.18) Gecko/20110614 Firefox/3.6.18', 'http://novi-ua.org/profile-6', 'profile', 'edit', 6);
INSERT INTO user_access VALUES (1360, 1310128194, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.18) Gecko/20110614 Firefox/3.6.18', 'http://novi-ua.org/profile/edit?id=6', 'people', '', 0);
INSERT INTO user_access VALUES (1360, 1310128201, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.18) Gecko/20110614 Firefox/3.6.18', 'http://novi-ua.org/people', 'people', 'index', 0);
INSERT INTO user_access VALUES (1360, 1310128206, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.18) Gecko/20110614 Firefox/3.6.18', 'http://novi-ua.org/people/index?offline=all', 'people', 'index', 0);
INSERT INTO user_access VALUES (1360, 1310128209, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.18) Gecko/20110614 Firefox/3.6.18', 'http://novi-ua.org/people/index?type=1', 'home', '', 0);
INSERT INTO user_access VALUES (1360, 1310128221, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.18) Gecko/20110614 Firefox/3.6.18', 'http://novi-ua.org/profile-1360', 'admin', '', 0);
INSERT INTO user_access VALUES (1360, 1310128225, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.18) Gecko/20110614 Firefox/3.6.18', 'http://novi-ua.org/admin', 'admin', 'users', 0);
INSERT INTO user_access VALUES (1360, 1310128227, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.18) Gecko/20110614 Firefox/3.6.18', 'http://novi-ua.org/admin/users', 'admin', 'users_list', 0);
INSERT INTO user_access VALUES (1360, 1310128235, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.18) Gecko/20110614 Firefox/3.6.18', 'http://novi-ua.org/admin/users_list', 'admin', 'users', 0);
INSERT INTO user_access VALUES (1360, 1310128243, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.18) Gecko/20110614 Firefox/3.6.18', 'http://novi-ua.org/admin/users?key=6', 'people', '', 0);
INSERT INTO user_access VALUES (1360, 1310128246, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.18) Gecko/20110614 Firefox/3.6.18', 'http://novi-ua.org/people', 'people', 'index', 0);
INSERT INTO user_access VALUES (1360, 1310128259, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.18) Gecko/20110614 Firefox/3.6.18', 'http://novi-ua.org/people/index?identification=check', 'people', 'index', 0);
INSERT INTO user_access VALUES (1360, 1310128263, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.18) Gecko/20110614 Firefox/3.6.18', 'http://novi-ua.org/people/index?type=0', 'people', 'index', 0);
INSERT INTO user_access VALUES (1360, 1310128266, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.18) Gecko/20110614 Firefox/3.6.18', 'http://novi-ua.org/people/index?famous=1', 'people', 'index', 0);
INSERT INTO user_access VALUES (1360, 1310128271, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.18) Gecko/20110614 Firefox/3.6.18', 'http://novi-ua.org/people/index?identification=check', 'people', '', 0);
INSERT INTO user_access VALUES (1360, 1310128409, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.18) Gecko/20110614 Firefox/3.6.18', 'http://novi-ua.org/people/index?identification=check', 'people', '', 0);
INSERT INTO user_access VALUES (1360, 1310128411, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.18) Gecko/20110614 Firefox/3.6.18', 'http://novi-ua.org/people/index?identification=check', 'people', '', 0);
INSERT INTO user_access VALUES (1360, 1310128416, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.18) Gecko/20110614 Firefox/3.6.18', 'http://novi-ua.org/people/index?identification=check', 'people', '', 0);
INSERT INTO user_access VALUES (1360, 1310128418, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.18) Gecko/20110614 Firefox/3.6.18', 'http://novi-ua.org/people/index?identification=check', 'people', '', 0);
INSERT INTO user_access VALUES (1360, 1310128450, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.18) Gecko/20110614 Firefox/3.6.18', 'http://novi-ua.org/people/index?identification=check', 'people', '', 0);
INSERT INTO user_access VALUES (1360, 1310128452, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.18) Gecko/20110614 Firefox/3.6.18', 'http://novi-ua.org/people/index?identification=check', 'people', '', 0);
INSERT INTO user_access VALUES (1360, 1310128630, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.18) Gecko/20110614 Firefox/3.6.18', 'http://novi-ua.org/home', 'people', '', 0);
INSERT INTO user_access VALUES (1360, 1310128634, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.18) Gecko/20110614 Firefox/3.6.18', 'http://novi-ua.org/people', 'people', 'index', 0);
INSERT INTO user_access VALUES (1360, 1310128637, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.18) Gecko/20110614 Firefox/3.6.18', 'http://novi-ua.org/people/index?identification=check', 'profile', 'index', 8);
INSERT INTO user_access VALUES (1360, 1310128641, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.18) Gecko/20110614 Firefox/3.6.18', 'http://novi-ua.org/profile-8', 'profile', 'edit', 8);
INSERT INTO user_access VALUES (1360, 1310128702, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.18) Gecko/20110614 Firefox/3.6.18', 'http://novi-ua.org/profile/edit?id=8', 'home', '', 0);
INSERT INTO user_access VALUES (1360, 1310128706, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.18) Gecko/20110614 Firefox/3.6.18', 'http://novi-ua.org/home', 'people', '', 0);
INSERT INTO user_access VALUES (1360, 1310128708, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.18) Gecko/20110614 Firefox/3.6.18', 'http://novi-ua.org/people', 'home', '', 0);
INSERT INTO user_access VALUES (1360, 1310128710, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.18) Gecko/20110614 Firefox/3.6.18', 'http://novi-ua.org/home', 'people', '', 0);
INSERT INTO user_access VALUES (1360, 1310128714, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.18) Gecko/20110614 Firefox/3.6.18', 'http://novi-ua.org/people', 'people', 'index', 0);
INSERT INTO user_access VALUES (1360, 1310128716, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.18) Gecko/20110614 Firefox/3.6.18', 'http://novi-ua.org/people/index?identification=check', 'profile', 'index', 8);
INSERT INTO user_access VALUES (1360, 1310128718, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.18) Gecko/20110614 Firefox/3.6.18', 'http://novi-ua.org/profile-8', 'profile', 'edit', 8);
INSERT INTO user_access VALUES (1360, 1310128873, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.18) Gecko/20110614 Firefox/3.6.18', 'http://novi-ua.org/profile/edit?id=8', 'home', '', 0);
INSERT INTO user_access VALUES (1360, 1310128883, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.18) Gecko/20110614 Firefox/3.6.18', 'http://novi-ua.org/home', 'people', '', 0);
INSERT INTO user_access VALUES (1360, 1310128887, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.18) Gecko/20110614 Firefox/3.6.18', 'http://novi-ua.org/people', 'people', 'index', 0);
INSERT INTO user_access VALUES (1360, 1310128889, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.18) Gecko/20110614 Firefox/3.6.18', 'http://novi-ua.org/people/index?identification=check', 'profile', 'index', 8);
INSERT INTO user_access VALUES (1360, 1310128895, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.18) Gecko/20110614 Firefox/3.6.18', 'http://novi-ua.org/profile-8', 'profile', 'edit', 8);
INSERT INTO user_access VALUES (1360, 1310128920, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.18) Gecko/20110614 Firefox/3.6.18', 'http://novi-ua.org/profile/edit?id=8', 'admin', '', 0);
INSERT INTO user_access VALUES (1360, 1310128922, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.18) Gecko/20110614 Firefox/3.6.18', 'http://novi-ua.org/admin', 'admin', 'users', 0);
INSERT INTO user_access VALUES (1360, 1310128924, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.18) Gecko/20110614 Firefox/3.6.18', 'http://novi-ua.org/admin/users', 'admin', 'users_list', 0);
INSERT INTO user_access VALUES (1360, 1310128933, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.18) Gecko/20110614 Firefox/3.6.18', 'http://novi-ua.org/admin/users_list', 'admin', 'users', 0);
INSERT INTO user_access VALUES (1360, 1310128949, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.18) Gecko/20110614 Firefox/3.6.18', 'http://novi-ua.org/admin/users?key=8', 'admin', 'users', 0);
INSERT INTO user_access VALUES (1360, 1310129193, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.18) Gecko/20110614 Firefox/3.6.18', 'http://novi-ua.org/admin/users?key=8', 'home', '', 0);
INSERT INTO user_access VALUES (1360, 1310129200, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.18) Gecko/20110614 Firefox/3.6.18', 'http://novi-ua.org/home', 'profile', 'index', 1360);
INSERT INTO user_access VALUES (1360, 1310129203, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.18) Gecko/20110614 Firefox/3.6.18', 'http://novi-ua.org/profile-1360', 'people', '', 0);
INSERT INTO user_access VALUES (1360, 1310129208, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.18) Gecko/20110614 Firefox/3.6.18', 'http://novi-ua.org/people', 'people', 'index', 0);
INSERT INTO user_access VALUES (1360, 1310129223, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.18) Gecko/20110614 Firefox/3.6.18', 'http://novi-ua.org/people/index?identification=check', 'people', 'index', 0);
INSERT INTO user_access VALUES (1360, 1310129225, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.18) Gecko/20110614 Firefox/3.6.18', 'http://novi-ua.org/people/index?offline=all', 'profile', 'index', 7);
INSERT INTO user_access VALUES (1360, 1310129227, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.18) Gecko/20110614 Firefox/3.6.18', 'http://novi-ua.org/profile-7', 'profile', 'edit', 7);
INSERT INTO user_access VALUES (7, 1310129323, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.18) Gecko/20110614 Firefox/3.6.18', 'http://novi-ua.org/profile/edit?id=7', 'home', '', 0);
INSERT INTO user_access VALUES (1360, 1310129377, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.18) Gecko/20110614 Firefox/3.6.18', 'http://novi-ua.org/home', 'home', '', 0);
INSERT INTO user_access VALUES (1360, 1310129388, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.18) Gecko/20110614 Firefox/3.6.18', 'http://novi-ua.org/home', 'admin', '', 0);
INSERT INTO user_access VALUES (1360, 1310129391, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.18) Gecko/20110614 Firefox/3.6.18', 'http://novi-ua.org/admin', 'admin', 'users', 0);
INSERT INTO user_access VALUES (1360, 1310129393, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.18) Gecko/20110614 Firefox/3.6.18', 'http://novi-ua.org/admin/users', 'admin', 'users_list', 0);
INSERT INTO user_access VALUES (1360, 1310129408, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.18) Gecko/20110614 Firefox/3.6.18', 'http://novi-ua.org/admin/users_list', 'admin', 'users', 0);
INSERT INTO user_access VALUES (1360, 1310129417, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.18) Gecko/20110614 Firefox/3.6.18', 'http://novi-ua.org/admin/users?key=4', 'admin', 'users_list', 0);
INSERT INTO user_access VALUES (1360, 1310129423, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.18) Gecko/20110614 Firefox/3.6.18', 'http://novi-ua.org/admin/users_list', 'admin', 'users', 0);
INSERT INTO user_access VALUES (1360, 1310129427, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.18) Gecko/20110614 Firefox/3.6.18', 'http://novi-ua.org/admin/users?key=8', 'profile', 'index', 8);
INSERT INTO user_access VALUES (1360, 1310129429, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.18) Gecko/20110614 Firefox/3.6.18', 'http://novi-ua.org/profile-8', 'profile', 'edit', 8);
INSERT INTO user_access VALUES (1360, 1310129592, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.18) Gecko/20110614 Firefox/3.6.18', 'http://novi-ua.org/profile/edit?id=8', 'home', '', 0);
INSERT INTO user_access VALUES (1360, 1310129597, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.18) Gecko/20110614 Firefox/3.6.18', 'http://novi-ua.org/home', 'profile', 'index', 1360);
INSERT INTO user_access VALUES (1360, 1310129600, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.18) Gecko/20110614 Firefox/3.6.18', '', 'profile', 'index', 4);
INSERT INTO user_access VALUES (1360, 1310129602, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.18) Gecko/20110614 Firefox/3.6.18', 'http://novi-ua.org/profile-4', 'profile', 'edit', 0);
INSERT INTO user_access VALUES (1360, 1310129670, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.18) Gecko/20110614 Firefox/3.6.18', '', 'profile', 'edit', 4);
INSERT INTO user_access VALUES (1360, 1310129699, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.18) Gecko/20110614 Firefox/3.6.18', '', 'profile', 'edit', 6);
INSERT INTO user_access VALUES (1360, 1310129707, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.18) Gecko/20110614 Firefox/3.6.18', 'http://novi-ua.org/profile/edit?id=6', 'home', '', 0);
INSERT INTO user_access VALUES (1360, 1310129710, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.18) Gecko/20110614 Firefox/3.6.18', 'http://novi-ua.org/home', 'profile', 'index', 1360);
INSERT INTO user_access VALUES (1360, 1310129712, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.18) Gecko/20110614 Firefox/3.6.18', '', 'profile', 'index', 7);
INSERT INTO user_access VALUES (1360, 1310129714, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.18) Gecko/20110614 Firefox/3.6.18', 'http://novi-ua.org/profile-7', 'profile', 'edit', 7);
INSERT INTO user_access VALUES (1360, 1310129720, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.18) Gecko/20110614 Firefox/3.6.18', 'http://novi-ua.org/profile/edit?id=7', 'home', '', 0);
INSERT INTO user_access VALUES (1360, 1310129725, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.18) Gecko/20110614 Firefox/3.6.18', 'http://novi-ua.org/home', 'profile', 'index', 1360);
INSERT INTO user_access VALUES (1360, 1310129729, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.18) Gecko/20110614 Firefox/3.6.18', '', 'profile', 'index', 8);
INSERT INTO user_access VALUES (1360, 1310129734, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.18) Gecko/20110614 Firefox/3.6.18', 'http://novi-ua.org/profile-8', 'profile', 'index', 1360);
INSERT INTO user_access VALUES (1360, 1310129736, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.18) Gecko/20110614 Firefox/3.6.18', 'http://novi-ua.org/profile-1360', 'profile', 'add', 0);
INSERT INTO user_access VALUES (1360, 1310129751, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.18) Gecko/20110614 Firefox/3.6.18', 'http://novi-ua.org/profile/add', 'profile', 'index', 9);
INSERT INTO user_access VALUES (1360, 1310129754, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.18) Gecko/20110614 Firefox/3.6.18', 'http://novi-ua.org/profile-9', 'profile', 'edit', 9);
INSERT INTO user_access VALUES (9, 1310129845, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10_6_6; ru-ru) AppleWebKit/533.19.4 (KHTML, like Gecko) Version/5.0.3 Safari/533.19.4', 'http://novi-ua.org/', 'home', '', 0);
INSERT INTO user_access VALUES (1360, 1310129865, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.18) Gecko/20110614 Firefox/3.6.18', 'http://novi-ua.org/profile-9', 'profile', 'edit', 9);
INSERT INTO user_access VALUES (1360, 1310129878, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.18) Gecko/20110614 Firefox/3.6.18', 'http://novi-ua.org/profile-9', 'profile', 'edit', 9);
INSERT INTO user_access VALUES (1360, 1310130005, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.18) Gecko/20110614 Firefox/3.6.18', 'http://novi-ua.org/people/index?identification=check', 'people', '', 0);
INSERT INTO user_access VALUES (1360, 1310130008, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.18) Gecko/20110614 Firefox/3.6.18', 'http://novi-ua.org/people', 'profile', 'index', 9);
INSERT INTO user_access VALUES (1360, 1310130011, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.18) Gecko/20110614 Firefox/3.6.18', 'http://novi-ua.org/profile-9', 'profile', 'edit', 9);
INSERT INTO user_access VALUES (1360, 1310130307, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.18) Gecko/20110614 Firefox/3.6.18', 'http://novi-ua.org/profile-9', 'profile', 'edit', 9);
INSERT INTO user_access VALUES (1360, 1310130636, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.18) Gecko/20110614 Firefox/3.6.18', '', 'home', '', 0);
INSERT INTO user_access VALUES (1360, 1310130643, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.18) Gecko/20110614 Firefox/3.6.18', 'http://novi-ua.org/home', 'people', '', 0);
INSERT INTO user_access VALUES (1360, 1310130655, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.18) Gecko/20110614 Firefox/3.6.18', 'http://novi-ua.org/people', 'admin', '', 0);
INSERT INTO user_access VALUES (1360, 1310130659, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.18) Gecko/20110614 Firefox/3.6.18', 'http://novi-ua.org/admin', 'admin', 'users', 0);
INSERT INTO user_access VALUES (1360, 1310130661, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.18) Gecko/20110614 Firefox/3.6.18', 'http://novi-ua.org/admin/users', 'admin', 'users_list', 0);
INSERT INTO user_access VALUES (1360, 1310130674, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.18) Gecko/20110614 Firefox/3.6.18', 'http://novi-ua.org/admin/users_list', 'profile', 'index', 1360);
INSERT INTO user_access VALUES (1360, 1310130678, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.18) Gecko/20110614 Firefox/3.6.18', 'http://novi-ua.org/profile-1360', 'profile', 'add', 0);
INSERT INTO user_access VALUES (1360, 1310130693, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.18) Gecko/20110614 Firefox/3.6.18', 'http://novi-ua.org/profile/add', 'profile', 'index', 10);
INSERT INTO user_access VALUES (1360, 1310130702, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.18) Gecko/20110614 Firefox/3.6.18', 'http://novi-ua.org/profile-10', 'profile', 'edit', 10);
INSERT INTO user_access VALUES (1360, 1310130778, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.18) Gecko/20110614 Firefox/3.6.18', 'http://novi-ua.org/profile-10', 'profile', 'edit', 10);
INSERT INTO user_access VALUES (1360, 1310130884, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.18) Gecko/20110614 Firefox/3.6.18', 'http://novi-ua.org/profile-9', 'profile', 'edit', 9);
INSERT INTO user_access VALUES (1360, 1310130937, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.18) Gecko/20110614 Firefox/3.6.18', 'http://novi-ua.org/profile-9', 'profile', 'edit', 9);
INSERT INTO user_access VALUES (1360, 1310130985, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.18) Gecko/20110614 Firefox/3.6.18', 'http://novi-ua.org/profile-9', 'profile', 'edit', 9);
INSERT INTO user_access VALUES (1360, 1310135586, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.18) Gecko/20110614 Firefox/3.6.18', 'http://novi-ua.org/profile-9', 'profile', 'edit', 9);
INSERT INTO user_access VALUES (1360, 1310135595, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.18) Gecko/20110614 Firefox/3.6.18', 'http://novi-ua.org/profile-9', 'profile', 'edit', 9);
INSERT INTO user_access VALUES (1360, 1310135601, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.18) Gecko/20110614 Firefox/3.6.18', 'http://novi-ua.org/profile-9', 'profile', 'edit', 9);
INSERT INTO user_access VALUES (1360, 1310135654, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.18) Gecko/20110614 Firefox/3.6.18', 'http://novi-ua.org/profile-9', 'profile', 'edit', 9);
INSERT INTO user_access VALUES (1360, 1310135672, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.18) Gecko/20110614 Firefox/3.6.18', 'http://novi-ua.org/profile-9', 'profile', 'edit', 9);
INSERT INTO user_access VALUES (1360, 1310135692, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.18) Gecko/20110614 Firefox/3.6.18', 'http://novi-ua.org/profile-9', 'profile', 'edit', 9);
INSERT INTO user_access VALUES (1360, 1310135706, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.18) Gecko/20110614 Firefox/3.6.18', 'http://novi-ua.org/profile-9', 'profile', 'edit', 9);
INSERT INTO user_access VALUES (1360, 1310135711, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.18) Gecko/20110614 Firefox/3.6.18', 'http://novi-ua.org/profile-9', 'profile', 'edit', 9);
INSERT INTO user_access VALUES (1360, 1310135723, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.18) Gecko/20110614 Firefox/3.6.18', 'http://novi-ua.org/profile-9', 'profile', 'edit', 9);
INSERT INTO user_access VALUES (1360, 1310135723, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.18) Gecko/20110614 Firefox/3.6.18', 'http://novi-ua.org/profile-9', 'profile', 'edit', 9);
INSERT INTO user_access VALUES (1360, 1310135763, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.18) Gecko/20110614 Firefox/3.6.18', 'http://novi-ua.org/profile-9', 'profile', 'edit', 9);
INSERT INTO user_access VALUES (1360, 1310135894, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.18) Gecko/20110614 Firefox/3.6.18', 'http://novi-ua.org/profile-9', 'profile', 'edit', 9);
INSERT INTO user_access VALUES (1360, 1310135897, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.18) Gecko/20110614 Firefox/3.6.18', 'http://novi-ua.org/profile-9', 'profile', 'edit', 9);
INSERT INTO user_access VALUES (1360, 1310135938, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.18) Gecko/20110614 Firefox/3.6.18', 'http://novi-ua.org/profile/edit?id=9', 'home', '', 0);
INSERT INTO user_access VALUES (1360, 1310135940, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.18) Gecko/20110614 Firefox/3.6.18', 'http://novi-ua.org/home', 'people', '', 0);
INSERT INTO user_access VALUES (1360, 1310135947, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.18) Gecko/20110614 Firefox/3.6.18', 'http://novi-ua.org/people', 'people', 'index', 0);
INSERT INTO user_access VALUES (1360, 1310135953, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.18) Gecko/20110614 Firefox/3.6.18', 'http://novi-ua.org/people/index?identification=check', 'people', 'index', 0);
INSERT INTO user_access VALUES (1360, 1310135956, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.18) Gecko/20110614 Firefox/3.6.18', 'http://novi-ua.org/people/index?offline=all', 'profile', 'index', 10);
INSERT INTO user_access VALUES (1360, 1310135959, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.18) Gecko/20110614 Firefox/3.6.18', 'http://novi-ua.org/profile-10', 'profile', 'edit', 10);
INSERT INTO user_access VALUES (1360, 1310135978, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.18) Gecko/20110614 Firefox/3.6.18', 'http://novi-ua.org/profile/edit?id=10', 'home', '', 0);
INSERT INTO user_access VALUES (1, 1310197850, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows NT 5.1) AppleWebKit/534.30 (KHTML, like Gecko) Chrome/12.0.742.112 Safari/534.30', 'http://novi-ua.org/', 'home', '', 0);
INSERT INTO user_access VALUES (1, 1310197855, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows NT 5.1) AppleWebKit/534.30 (KHTML, like Gecko) Chrome/12.0.742.112 Safari/534.30', 'http://novi-ua.org/home', 'profile', 'index', 1);
INSERT INTO user_access VALUES (1, 1310199693, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows NT 5.1) AppleWebKit/534.30 (KHTML, like Gecko) Chrome/12.0.742.112 Safari/534.30', 'http://novi-ua.org/profile-1', 'profile', 'edit', 0);
INSERT INTO user_access VALUES (1, 1310200652, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows NT 5.1) AppleWebKit/534.30 (KHTML, like Gecko) Chrome/12.0.742.112 Safari/534.30', 'http://novi-ua.org/profile-1', 'profile', 'edit', 0);
INSERT INTO user_access VALUES (1, 1310200914, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows NT 5.1) AppleWebKit/534.30 (KHTML, like Gecko) Chrome/12.0.742.112 Safari/534.30', 'http://novi-ua.org/profile/edit', 'profile', 'index', 1);
INSERT INTO user_access VALUES (1, 1310201241, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows NT 5.1) AppleWebKit/534.30 (KHTML, like Gecko) Chrome/12.0.742.112 Safari/534.30', 'http://novi-ua.org/profile-1', 'profile', 'edit', 0);
INSERT INTO user_access VALUES (1, 1310201253, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows NT 5.1) AppleWebKit/534.30 (KHTML, like Gecko) Chrome/12.0.742.112 Safari/534.30', 'http://novi-ua.org/profile/edit', 'profile', 'index', 1);
INSERT INTO user_access VALUES (1, 1310201255, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows NT 5.1) AppleWebKit/534.30 (KHTML, like Gecko) Chrome/12.0.742.112 Safari/534.30', 'http://novi-ua.org/profile-1', 'profile', 'edit', 0);
INSERT INTO user_access VALUES (1, 1310201258, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows NT 5.1) AppleWebKit/534.30 (KHTML, like Gecko) Chrome/12.0.742.112 Safari/534.30', 'http://novi-ua.org/profile/edit', 'profile', 'index', 1);
INSERT INTO user_access VALUES (1, 1310201260, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows NT 5.1) AppleWebKit/534.30 (KHTML, like Gecko) Chrome/12.0.742.112 Safari/534.30', 'http://novi-ua.org/profile-1', 'profile', 'edit', 0);
INSERT INTO user_access VALUES (1, 1310201262, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows NT 5.1) AppleWebKit/534.30 (KHTML, like Gecko) Chrome/12.0.742.112 Safari/534.30', 'http://novi-ua.org/profile/edit', 'profile', 'index', 1);
INSERT INTO user_access VALUES (1, 1310203395, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows NT 5.1) AppleWebKit/534.30 (KHTML, like Gecko) Chrome/12.0.742.112 Safari/534.30', 'http://novi-ua.org/profile-1', 'home', '', 0);
INSERT INTO user_access VALUES (1, 1310203408, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows NT 5.1) AppleWebKit/534.30 (KHTML, like Gecko) Chrome/12.0.742.112 Safari/534.30', 'http://novi-ua.org/home', 'people', '', 0);
INSERT INTO user_access VALUES (1, 1310203422, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows NT 5.1) AppleWebKit/534.30 (KHTML, like Gecko) Chrome/12.0.742.112 Safari/534.30', 'http://novi-ua.org/people', 'profile', 'index', 2);
INSERT INTO user_access VALUES (1, 1310203474, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows NT 5.1) AppleWebKit/534.30 (KHTML, like Gecko) Chrome/12.0.742.112 Safari/534.30', 'http://novi-ua.org/profile-2', 'home', '', 0);
INSERT INTO user_access VALUES (1, 1310203476, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows NT 5.1) AppleWebKit/534.30 (KHTML, like Gecko) Chrome/12.0.742.112 Safari/534.30', 'http://novi-ua.org/home', 'groups', '', 0);
INSERT INTO user_access VALUES (1, 1310203477, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows NT 5.1) AppleWebKit/534.30 (KHTML, like Gecko) Chrome/12.0.742.112 Safari/534.30', 'http://novi-ua.org/groups', 'events', '', 0);
INSERT INTO user_access VALUES (1, 1310203480, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows NT 5.1) AppleWebKit/534.30 (KHTML, like Gecko) Chrome/12.0.742.112 Safari/534.30', 'http://novi-ua.org/events', 'polls', '', 0);
INSERT INTO user_access VALUES (1, 1310203481, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows NT 5.1) AppleWebKit/534.30 (KHTML, like Gecko) Chrome/12.0.742.112 Safari/534.30', 'http://novi-ua.org/polls', 'events', '', 0);
INSERT INTO user_access VALUES (1360, 1310216829, '::ffff:82.193.98.172', 'Mozilla/5.0 (Windows NT 5.1; rv:5.0) Gecko/20100101 Firefox/5.0', '', 'home', '', 0);
INSERT INTO user_access VALUES (1360, 1310216877, '::ffff:82.193.98.172', 'Mozilla/5.0 (Windows NT 5.1; rv:5.0) Gecko/20100101 Firefox/5.0', 'http://novi-ua.org/home', 'people', '', 0);
INSERT INTO user_access VALUES (1360, 1310216884, '::ffff:82.193.98.172', 'Mozilla/5.0 (Windows NT 5.1; rv:5.0) Gecko/20100101 Firefox/5.0', 'http://novi-ua.org/people', 'profile', 'index', 1);
INSERT INTO user_access VALUES (1360, 1310216901, '::ffff:82.193.98.172', 'Mozilla/5.0 (Windows NT 5.1; rv:5.0) Gecko/20100101 Firefox/5.0', 'http://novi-ua.org/profile-1', 'profile', 'index', 1);
INSERT INTO user_access VALUES (1360, 1310216969, '::ffff:82.193.98.172', 'Mozilla/5.0 (Windows NT 5.1; rv:5.0) Gecko/20100101 Firefox/5.0', 'http://novi-ua.org/profile-1', 'profile', 'index', 1);
INSERT INTO user_access VALUES (1360, 1310216973, '::ffff:82.193.98.172', 'Mozilla/5.0 (Windows NT 5.1; rv:5.0) Gecko/20100101 Firefox/5.0', 'http://novi-ua.org/profile-1', 'people', '', 0);
INSERT INTO user_access VALUES (1360, 1310216978, '::ffff:82.193.98.172', 'Mozilla/5.0 (Windows NT 5.1; rv:5.0) Gecko/20100101 Firefox/5.0', 'http://novi-ua.org/people', 'profile', 'index', 1360);
INSERT INTO user_access VALUES (1360, 1310216985, '::ffff:82.193.98.172', 'Mozilla/5.0 (Windows NT 5.1; rv:5.0) Gecko/20100101 Firefox/5.0', 'http://novi-ua.org/profile-1360', 'people', '', 0);
INSERT INTO user_access VALUES (1360, 1310216988, '::ffff:82.193.98.172', 'Mozilla/5.0 (Windows NT 5.1; rv:5.0) Gecko/20100101 Firefox/5.0', 'http://novi-ua.org/people', 'profile', 'index', 1);
INSERT INTO user_access VALUES (1360, 1310216998, '::ffff:82.193.98.172', 'Mozilla/5.0 (Windows NT 5.1; rv:5.0) Gecko/20100101 Firefox/5.0', 'http://novi-ua.org/profile-1', 'profile', 'index', 1);
INSERT INTO user_access VALUES (1360, 1310217812, '::ffff:82.193.98.172', 'Mozilla/5.0 (Windows NT 5.1; rv:5.0) Gecko/20100101 Firefox/5.0', 'http://novi-ua.org/profile-1', 'profile', 'index', 1);
INSERT INTO user_access VALUES (1360, 1310217865, '::ffff:82.193.98.172', 'Mozilla/5.0 (Windows NT 5.1; rv:5.0) Gecko/20100101 Firefox/5.0', 'http://novi-ua.org/profile-1', 'profile', 'index', 1);
INSERT INTO user_access VALUES (1360, 1310217873, '::ffff:82.193.98.172', 'Mozilla/5.0 (Windows NT 5.1; rv:5.0) Gecko/20100101 Firefox/5.0', 'http://novi-ua.org/profile-1', 'people', '', 0);
INSERT INTO user_access VALUES (1360, 1310362513, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.18) Gecko/20110614 Firefox/3.6.18', '', 'home', '', 0);
INSERT INTO user_access VALUES (1360, 1310362535, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.18) Gecko/20110614 Firefox/3.6.18', 'http://novi-ua.org/home', 'people', '', 0);
INSERT INTO user_access VALUES (1360, 1310362544, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.18) Gecko/20110614 Firefox/3.6.18', 'http://novi-ua.org/people', 'people', 'index', 0);
INSERT INTO user_access VALUES (1360, 1310362550, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.18) Gecko/20110614 Firefox/3.6.18', 'http://novi-ua.org/people/index?offline=all', 'admin', '', 0);
INSERT INTO user_access VALUES (1360, 1310362555, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.18) Gecko/20110614 Firefox/3.6.18', 'http://novi-ua.org/admin', 'admin', 'users', 0);
INSERT INTO user_access VALUES (1360, 1310362557, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.18) Gecko/20110614 Firefox/3.6.18', 'http://novi-ua.org/admin/users', 'admin', 'users_list', 0);
INSERT INTO user_access VALUES (1, 1310365654, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows NT 5.1) AppleWebKit/534.30 (KHTML, like Gecko) Chrome/12.0.742.112 Safari/534.30', '', 'home', '', 0);
INSERT INTO user_access VALUES (1, 1310370796, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows NT 5.1) AppleWebKit/534.30 (KHTML, like Gecko) Chrome/12.0.742.112 Safari/534.30', 'http://novi-ua.org/home', 'profile', 'index', 1);
INSERT INTO user_access VALUES (1, 1310370800, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows NT 5.1) AppleWebKit/534.30 (KHTML, like Gecko) Chrome/12.0.742.112 Safari/534.30', 'http://novi-ua.org/profile-1', 'profile', 'add', 0);
INSERT INTO user_access VALUES (1, 1310370842, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows NT 5.1) AppleWebKit/534.30 (KHTML, like Gecko) Chrome/12.0.742.112 Safari/534.30', 'http://novi-ua.org/profile/add', 'profile', 'add', 0);
INSERT INTO user_access VALUES (1, 1310370846, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows NT 5.1) AppleWebKit/534.30 (KHTML, like Gecko) Chrome/12.0.742.112 Safari/534.30', 'http://novi-ua.org/profile/add', 'profile', 'index', 11);
INSERT INTO user_access VALUES (1, 1310370850, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows NT 5.1) AppleWebKit/534.30 (KHTML, like Gecko) Chrome/12.0.742.112 Safari/534.30', 'http://novi-ua.org/profile-11', 'profile', 'edit', 11);
INSERT INTO user_access VALUES (1, 1310371223, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows NT 5.1) AppleWebKit/534.30 (KHTML, like Gecko) Chrome/12.0.742.112 Safari/534.30', 'http://novi-ua.org/profile/edit?id=11', 'profile', 'index', 11);
INSERT INTO user_access VALUES (1, 1310371425, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows NT 5.1) AppleWebKit/534.30 (KHTML, like Gecko) Chrome/12.0.742.112 Safari/534.30', 'http://novi-ua.org/profile-11', 'people', '', 0);
INSERT INTO user_access VALUES (1, 1310371427, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows NT 5.1) AppleWebKit/534.30 (KHTML, like Gecko) Chrome/12.0.742.112 Safari/534.30', 'http://novi-ua.org/people', 'blogs', '', 0);
INSERT INTO user_access VALUES (1, 1310371428, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows NT 5.1) AppleWebKit/534.30 (KHTML, like Gecko) Chrome/12.0.742.112 Safari/534.30', 'http://novi-ua.org/blogs', 'groups', '', 0);
INSERT INTO user_access VALUES (1, 1310371431, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows NT 5.1) AppleWebKit/534.30 (KHTML, like Gecko) Chrome/12.0.742.112 Safari/534.30', 'http://novi-ua.org/groups', 'events', '', 0);
INSERT INTO user_access VALUES (1, 1310371433, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows NT 5.1) AppleWebKit/534.30 (KHTML, like Gecko) Chrome/12.0.742.112 Safari/534.30', 'http://novi-ua.org/events', 'polls', '', 0);
INSERT INTO user_access VALUES (1, 1310371436, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows NT 5.1) AppleWebKit/534.30 (KHTML, like Gecko) Chrome/12.0.742.112 Safari/534.30', 'http://novi-ua.org/polls', 'library', '', 0);
INSERT INTO user_access VALUES (1, 1310371440, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows NT 5.1) AppleWebKit/534.30 (KHTML, like Gecko) Chrome/12.0.742.112 Safari/534.30', 'http://novi-ua.org/library', 'people', '', 0);
INSERT INTO user_access VALUES (1, 1310371485, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows NT 5.1) AppleWebKit/534.30 (KHTML, like Gecko) Chrome/12.0.742.112 Safari/534.30', 'http://novi-ua.org/people', 'home', '', 0);
INSERT INTO user_access VALUES (1, 1310371770, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows NT 5.1) AppleWebKit/534.30 (KHTML, like Gecko) Chrome/12.0.742.112 Safari/534.30', 'http://novi-ua.org/home', 'events', '', 0);
INSERT INTO user_access VALUES (1, 1310371809, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows NT 5.1) AppleWebKit/534.30 (KHTML, like Gecko) Chrome/12.0.742.112 Safari/534.30', 'http://novi-ua.org/events', 'groups', '', 0);
INSERT INTO user_access VALUES (1, 1310371810, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows NT 5.1) AppleWebKit/534.30 (KHTML, like Gecko) Chrome/12.0.742.112 Safari/534.30', 'http://novi-ua.org/groups', 'blogs', '', 0);
INSERT INTO user_access VALUES (1, 1310371812, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows NT 5.1) AppleWebKit/534.30 (KHTML, like Gecko) Chrome/12.0.742.112 Safari/534.30', 'http://novi-ua.org/blogs', 'people', '', 0);
INSERT INTO user_access VALUES (1, 1310371815, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows NT 5.1) AppleWebKit/534.30 (KHTML, like Gecko) Chrome/12.0.742.112 Safari/534.30', 'http://novi-ua.org/people', 'polls', '', 0);
INSERT INTO user_access VALUES (1, 1310371817, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows NT 5.1) AppleWebKit/534.30 (KHTML, like Gecko) Chrome/12.0.742.112 Safari/534.30', 'http://novi-ua.org/polls', 'library', '', 0);
INSERT INTO user_access VALUES (1, 1310371821, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows NT 5.1) AppleWebKit/534.30 (KHTML, like Gecko) Chrome/12.0.742.112 Safari/534.30', 'http://novi-ua.org/library', 'search', '', 0);
INSERT INTO user_access VALUES (1, 1310371825, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows NT 5.1) AppleWebKit/534.30 (KHTML, like Gecko) Chrome/12.0.742.112 Safari/534.30', 'http://novi-ua.org/search', 'profile', 'index', 1);
INSERT INTO user_access VALUES (1, 1310371831, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows NT 5.1) AppleWebKit/534.30 (KHTML, like Gecko) Chrome/12.0.742.112 Safari/534.30', 'http://novi-ua.org/profile-1', 'profile', 'branding', 0);
INSERT INTO user_access VALUES (1, 1310371838, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows NT 5.1) AppleWebKit/534.30 (KHTML, like Gecko) Chrome/12.0.742.112 Safari/534.30', 'http://novi-ua.org/search', 'profile', 'index', 1);
INSERT INTO user_access VALUES (1, 1310372549, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows NT 5.1) AppleWebKit/534.30 (KHTML, like Gecko) Chrome/12.0.742.112 Safari/534.30', 'http://novi-ua.org/profile-1', 'people', '', 0);
INSERT INTO user_access VALUES (1, 1310372554, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows NT 5.1) AppleWebKit/534.30 (KHTML, like Gecko) Chrome/12.0.742.112 Safari/534.30', 'http://novi-ua.org/people', 'people', '', 0);
INSERT INTO user_access VALUES (1, 1310372561, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows NT 5.1) AppleWebKit/534.30 (KHTML, like Gecko) Chrome/12.0.742.112 Safari/534.30', 'http://novi-ua.org/people', 'people', 'index', 0);
INSERT INTO user_access VALUES (1, 1310372565, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows NT 5.1) AppleWebKit/534.30 (KHTML, like Gecko) Chrome/12.0.742.112 Safari/534.30', 'http://novi-ua.org/people/index?offline=all', 'profile', 'index', 11);
INSERT INTO user_access VALUES (1, 1310372599, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows NT 5.1) AppleWebKit/534.30 (KHTML, like Gecko) Chrome/12.0.742.112 Safari/534.30', 'http://novi-ua.org/profile-11', 'profile', 'edit', 11);
INSERT INTO user_access VALUES (1, 1310372605, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows NT 5.1) AppleWebKit/534.30 (KHTML, like Gecko) Chrome/12.0.742.112 Safari/534.30', 'http://novi-ua.org/profile/edit?id=11', 'home', '', 0);
INSERT INTO user_access VALUES (1, 1310372609, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows NT 5.1) AppleWebKit/534.30 (KHTML, like Gecko) Chrome/12.0.742.112 Safari/534.30', 'http://novi-ua.org/home', 'people', '', 0);
INSERT INTO user_access VALUES (1, 1310372612, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows NT 5.1) AppleWebKit/534.30 (KHTML, like Gecko) Chrome/12.0.742.112 Safari/534.30', 'http://novi-ua.org/people', 'people', 'index', 0);
INSERT INTO user_access VALUES (1, 1310372614, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows NT 5.1) AppleWebKit/534.30 (KHTML, like Gecko) Chrome/12.0.742.112 Safari/534.30', 'http://novi-ua.org/people/index?offline=all', 'profile', 'index', 11);
INSERT INTO user_access VALUES (5, 1310381815, '::ffff:194.187.108.86', 'Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 6.1; WOW64; Trident/4.0; WebMoney Advisor; SLCC2; .NET CLR 2.0.50727; .NET CLR 3.5.30729; .NET CLR 3.0.30729; Media Center PC 6.0; InfoPath.2; .NET4.0C; .NET4.0E)', 'http://novi-ua.org/profile-1', 'home', '', 0);
INSERT INTO user_access VALUES (5, 1310381821, '::ffff:194.187.108.86', 'Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 6.1; WOW64; Trident/4.0; WebMoney Advisor; SLCC2; .NET CLR 2.0.50727; .NET CLR 3.5.30729; .NET CLR 3.0.30729; Media Center PC 6.0; InfoPath.2; .NET4.0C; .NET4.0E)', 'http://novi-ua.org/home', 'profile', 'index', 5);
INSERT INTO user_access VALUES (5, 1310381825, '::ffff:194.187.108.86', 'Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 6.1; WOW64; Trident/4.0; WebMoney Advisor; SLCC2; .NET CLR 2.0.50727; .NET CLR 3.5.30729; .NET CLR 3.0.30729; Media Center PC 6.0; InfoPath.2; .NET4.0C; .NET4.0E)', 'http://novi-ua.org/profile-5', 'profile', 'edit', 5);
INSERT INTO user_access VALUES (5, 1310381835, '::ffff:194.187.108.86', 'Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 6.1; WOW64; Trident/4.0; WebMoney Advisor; SLCC2; .NET CLR 2.0.50727; .NET CLR 3.5.30729; .NET CLR 3.0.30729; Media Center PC 6.0; InfoPath.2; .NET4.0C; .NET4.0E)', 'http://novi-ua.org/profile/edit?id=5', 'profile', 'index', 5);
INSERT INTO user_access VALUES (5, 1310381838, '::ffff:194.187.108.86', 'Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 6.1; WOW64; Trident/4.0; WebMoney Advisor; SLCC2; .NET CLR 2.0.50727; .NET CLR 3.5.30729; .NET CLR 3.0.30729; Media Center PC 6.0; InfoPath.2; .NET4.0C; .NET4.0E)', 'http://novi-ua.org/profile-5', 'profile', 'edit', 5);
INSERT INTO user_access VALUES (29, 1310381846, '::ffff:213.227.210.148', 'Mozilla/5.0 (X11; U; Linux x86_64; uk; rv:1.9.2.17) Gecko/20110422 Ubuntu/10.04 (lucid) Firefox/3.6.17', '', 'home', '', 0);
INSERT INTO user_access VALUES (29, 1310381881, '::ffff:213.227.210.148', 'Mozilla/5.0 (X11; U; Linux x86_64; uk; rv:1.9.2.17) Gecko/20110422 Ubuntu/10.04 (lucid) Firefox/3.6.17', 'http://novi-ua.org/home', 'home', '', 0);
INSERT INTO user_access VALUES (29, 1310381885, '::ffff:213.227.210.148', 'Mozilla/5.0 (X11; U; Linux x86_64; uk; rv:1.9.2.17) Gecko/20110422 Ubuntu/10.04 (lucid) Firefox/3.6.17', 'http://novi-ua.org/home', 'home', '', 0);
INSERT INTO user_access VALUES (5, 1310381891, '::ffff:194.187.108.86', 'Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 6.1; WOW64; Trident/4.0; WebMoney Advisor; SLCC2; .NET CLR 2.0.50727; .NET CLR 3.5.30729; .NET CLR 3.0.30729; Media Center PC 6.0; InfoPath.2; .NET4.0C; .NET4.0E)', 'http://novi-ua.org/profile/edit?id=5', 'home', '', 0);
INSERT INTO user_access VALUES (29, 1310381930, '::ffff:213.227.210.148', 'Mozilla/5.0 (X11; U; Linux x86_64; uk; rv:1.9.2.17) Gecko/20110422 Ubuntu/10.04 (lucid) Firefox/3.6.17', 'http://novi-ua.org/home', 'profile', 'index', 29);
INSERT INTO user_access VALUES (29, 1310381934, '::ffff:213.227.210.148', 'Mozilla/5.0 (X11; U; Linux x86_64; uk; rv:1.9.2.17) Gecko/20110422 Ubuntu/10.04 (lucid) Firefox/3.6.17', 'http://novi-ua.org/profile-29', 'profile', 'edit', 29);
INSERT INTO user_access VALUES (29, 1310381969, '::ffff:213.227.210.148', 'Mozilla/5.0 (X11; U; Linux x86_64; uk; rv:1.9.2.17) Gecko/20110422 Ubuntu/10.04 (lucid) Firefox/3.6.17', 'http://novi-ua.org/profile/edit?id=29', 'profile', 'edit', 0);
INSERT INTO user_access VALUES (29, 1310381973, '::ffff:213.227.210.148', 'Mozilla/5.0 (X11; U; Linux x86_64; uk; rv:1.9.2.17) Gecko/20110422 Ubuntu/10.04 (lucid) Firefox/3.6.17', '', 'profile', 'index', 5);
INSERT INTO user_access VALUES (5, 1310382521, '::ffff:194.187.108.86', 'Mozilla/5.0 (X11; Linux i686; rv:5.0) Gecko/20100101 Firefox/5.0', '', 'home', '', 0);
INSERT INTO user_access VALUES (1360, 1310382603, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10_6_6; ru-ru) AppleWebKit/533.19.4 (KHTML, like Gecko) Version/5.0.3 Safari/533.19.4', 'http://novi-ua.org/', 'home', '', 0);
INSERT INTO user_access VALUES (5, 1310382834, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows; U; Windows NT 6.1; ru; rv:1.9.2.17) Gecko/20110420 Firefox/3.6.17 GTB7.1 ( .NET CLR 3.5.30729; .NET4.0E) WebMoney Advisor', 'http://novi-ua.org/sign', 'profile', '', 0);
INSERT INTO user_access VALUES (5, 1310382839, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows; U; Windows NT 6.1; ru; rv:1.9.2.17) Gecko/20110420 Firefox/3.6.17 GTB7.1 ( .NET CLR 3.5.30729; .NET4.0E) WebMoney Advisor', 'http://novi-ua.org/profile', 'profile', 'edit', 5);
INSERT INTO user_access VALUES (5, 1310382968, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows; U; Windows NT 6.1; ru; rv:1.9.2.17) Gecko/20110420 Firefox/3.6.17 GTB7.1 ( .NET CLR 3.5.30729; .NET4.0E) WebMoney Advisor', 'http://novi-ua.org/profile/edit?id=5', 'people', '', 0);
INSERT INTO user_access VALUES (5, 1310382975, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows; U; Windows NT 6.1; ru; rv:1.9.2.17) Gecko/20110420 Firefox/3.6.17 GTB7.1 ( .NET CLR 3.5.30729; .NET4.0E) WebMoney Advisor', 'http://novi-ua.org/people', 'people', '', 0);
INSERT INTO user_access VALUES (5, 1310383066, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows; U; Windows NT 6.1; ru; rv:1.9.2.17) Gecko/20110420 Firefox/3.6.17 GTB7.1 ( .NET CLR 3.5.30729; .NET4.0E) WebMoney Advisor', 'http://novi-ua.org/people', 'home', '', 0);
INSERT INTO user_access VALUES (5, 1310383100, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows; U; Windows NT 6.1; ru; rv:1.9.2.17) Gecko/20110420 Firefox/3.6.17 GTB7.1 ( .NET CLR 3.5.30729; .NET4.0E) WebMoney Advisor', 'http://novi-ua.org/home', 'people', '', 0);
INSERT INTO user_access VALUES (1360, 1310383160, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.18) Gecko/20110614 Firefox/3.6.18', '', 'home', '', 0);
INSERT INTO user_access VALUES (1360, 1310383179, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.18) Gecko/20110614 Firefox/3.6.18', 'http://novi-ua.org/home', 'people', '', 0);
INSERT INTO user_access VALUES (1360, 1310383188, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.18) Gecko/20110614 Firefox/3.6.18', 'http://novi-ua.org/people', 'people', 'index', 0);
INSERT INTO user_access VALUES (5, 1310383232, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows; U; Windows NT 6.1; ru; rv:1.9.2.17) Gecko/20110420 Firefox/3.6.17 GTB7.1 ( .NET CLR 3.5.30729; .NET4.0E) WebMoney Advisor', 'http://novi-ua.org/people', 'people', 'index', 0);
INSERT INTO user_access VALUES (1360, 1310383469, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.18) Gecko/20110614 Firefox/3.6.18', 'http://novi-ua.org/people/index?offline=all', 'people', '', 0);
INSERT INTO user_access VALUES (1360, 1310383499, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.18) Gecko/20110614 Firefox/3.6.18', 'http://novi-ua.org/people/index?offline=all', 'people', '', 0);
INSERT INTO user_access VALUES (1360, 1310383582, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.18) Gecko/20110614 Firefox/3.6.18', 'http://novi-ua.org/people/index?offline=all', 'people', '', 0);
INSERT INTO user_access VALUES (1360, 1310383592, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.18) Gecko/20110614 Firefox/3.6.18', 'http://novi-ua.org/people/index?offline=all', 'people', '', 0);
INSERT INTO user_access VALUES (29, 1310383610, '::ffff:213.227.210.148', 'Mozilla/5.0 (X11; U; Linux x86_64; uk; rv:1.9.2.17) Gecko/20110422 Ubuntu/10.04 (lucid) Firefox/3.6.17', 'http://novi-ua.org/profile-5', 'home', '', 0);
INSERT INTO user_access VALUES (29, 1310383613, '::ffff:213.227.210.148', 'Mozilla/5.0 (X11; U; Linux x86_64; uk; rv:1.9.2.17) Gecko/20110422 Ubuntu/10.04 (lucid) Firefox/3.6.17', '', 'profile', 'index', 5);
INSERT INTO user_access VALUES (29, 1310383616, '::ffff:213.227.210.148', 'Mozilla/5.0 (X11; U; Linux x86_64; uk; rv:1.9.2.17) Gecko/20110422 Ubuntu/10.04 (lucid) Firefox/3.6.17', 'http://novi-ua.org/profile-5', 'people', '', 0);
INSERT INTO user_access VALUES (1360, 1310384120, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.18) Gecko/20110614 Firefox/3.6.18', 'http://novi-ua.org/people/index?offline=all', 'people', '', 0);
INSERT INTO user_access VALUES (1360, 1310384571, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.18) Gecko/20110614 Firefox/3.6.18', 'http://novi-ua.org/people/index?offline=all', 'people', '', 0);
INSERT INTO user_access VALUES (1360, 1310384682, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.18) Gecko/20110614 Firefox/3.6.18', 'http://novi-ua.org/people/index?offline=all', 'people', '', 0);
INSERT INTO user_access VALUES (5, 1310387068, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows; U; Windows NT 6.1; ru; rv:1.9.2.17) Gecko/20110420 Firefox/3.6.17 GTB7.1 ( .NET CLR 3.5.30729; .NET4.0E) WebMoney Advisor', 'http://novi-ua.org/people/index?offline=all', 'people', '', 0);
INSERT INTO user_access VALUES (1, 1310457114, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows NT 5.1) AppleWebKit/534.30 (KHTML, like Gecko) Chrome/12.0.742.112 Safari/534.30', '', 'home', '', 0);
INSERT INTO user_access VALUES (1, 1310457132, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows NT 5.1) AppleWebKit/534.30 (KHTML, like Gecko) Chrome/12.0.742.112 Safari/534.30', 'http://novi-ua.org/home', 'profile', 'index', 1);
INSERT INTO user_access VALUES (1, 1310457134, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows NT 5.1) AppleWebKit/534.30 (KHTML, like Gecko) Chrome/12.0.742.112 Safari/534.30', 'http://novi-ua.org/profile-1', 'profile', 'add', 0);
INSERT INTO user_access VALUES (5, 1310457612, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows; U; Windows NT 6.1; ru; rv:1.9.2.17) Gecko/20110420 Firefox/3.6.17 GTB7.1 ( .NET CLR 3.5.30729; .NET4.0E) WebMoney Advisor', '', 'profile', '', 0);
INSERT INTO user_access VALUES (5, 1310457626, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows; U; Windows NT 6.1; ru; rv:1.9.2.17) Gecko/20110420 Firefox/3.6.17 GTB7.1 ( .NET CLR 3.5.30729; .NET4.0E) WebMoney Advisor', 'http://novi-ua.org/profile', 'groups', '', 0);
INSERT INTO user_access VALUES (5, 1310457629, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows; U; Windows NT 6.1; ru; rv:1.9.2.17) Gecko/20110420 Firefox/3.6.17 GTB7.1 ( .NET CLR 3.5.30729; .NET4.0E) WebMoney Advisor', 'http://novi-ua.org/groups', 'home', '', 0);
INSERT INTO user_access VALUES (5, 1310457635, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows; U; Windows NT 6.1; ru; rv:1.9.2.17) Gecko/20110420 Firefox/3.6.17 GTB7.1 ( .NET CLR 3.5.30729; .NET4.0E) WebMoney Advisor', 'http://novi-ua.org/home', 'groups', '', 0);
INSERT INTO user_access VALUES (5, 1310457637, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows; U; Windows NT 6.1; ru; rv:1.9.2.17) Gecko/20110420 Firefox/3.6.17 GTB7.1 ( .NET CLR 3.5.30729; .NET4.0E) WebMoney Advisor', 'http://novi-ua.org/groups', 'home', '', 0);
INSERT INTO user_access VALUES (5, 1310457640, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows; U; Windows NT 6.1; ru; rv:1.9.2.17) Gecko/20110420 Firefox/3.6.17 GTB7.1 ( .NET CLR 3.5.30729; .NET4.0E) WebMoney Advisor', 'http://novi-ua.org/home', 'groups', '', 0);
INSERT INTO user_access VALUES (5, 1310457643, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows; U; Windows NT 6.1; ru; rv:1.9.2.17) Gecko/20110420 Firefox/3.6.17 GTB7.1 ( .NET CLR 3.5.30729; .NET4.0E) WebMoney Advisor', 'http://novi-ua.org/groups', 'home', '', 0);
INSERT INTO user_access VALUES (5, 1310457646, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows; U; Windows NT 6.1; ru; rv:1.9.2.17) Gecko/20110420 Firefox/3.6.17 GTB7.1 ( .NET CLR 3.5.30729; .NET4.0E) WebMoney Advisor', 'http://novi-ua.org/home', 'groups', '', 0);
INSERT INTO user_access VALUES (5, 1310457732, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows; U; Windows NT 6.1; ru; rv:1.9.2.17) Gecko/20110420 Firefox/3.6.17 GTB7.1 ( .NET CLR 3.5.30729; .NET4.0E) WebMoney Advisor', 'http://novi-ua.org/groups', 'groups', '', 0);
INSERT INTO user_access VALUES (5, 1310457735, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows; U; Windows NT 6.1; ru; rv:1.9.2.17) Gecko/20110420 Firefox/3.6.17 GTB7.1 ( .NET CLR 3.5.30729; .NET4.0E) WebMoney Advisor', 'http://novi-ua.org/groups', 'home', '', 0);
INSERT INTO user_access VALUES (1, 1310458689, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows NT 5.1) AppleWebKit/534.30 (KHTML, like Gecko) Chrome/12.0.742.112 Safari/534.30', 'http://novi-ua.org/profile/add', 'profile', 'add', 0);
INSERT INTO user_access VALUES (1, 1310458694, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows NT 5.1) AppleWebKit/534.30 (KHTML, like Gecko) Chrome/12.0.742.112 Safari/534.30', 'http://novi-ua.org/profile/add', 'groups', '', 0);
INSERT INTO user_access VALUES (1, 1310458701, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows NT 5.1) AppleWebKit/534.30 (KHTML, like Gecko) Chrome/12.0.742.112 Safari/534.30', 'http://novi-ua.org/groups', 'home', '', 0);
INSERT INTO user_access VALUES (1, 1310458708, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows NT 5.1) AppleWebKit/534.30 (KHTML, like Gecko) Chrome/12.0.742.112 Safari/534.30', 'http://novi-ua.org/home', 'groups', '', 0);
INSERT INTO user_access VALUES (1, 1310458711, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows NT 5.1) AppleWebKit/534.30 (KHTML, like Gecko) Chrome/12.0.742.112 Safari/534.30', 'http://novi-ua.org/groups', 'groups', 'create', 0);
INSERT INTO user_access VALUES (1, 1310458715, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows NT 5.1) AppleWebKit/534.30 (KHTML, like Gecko) Chrome/12.0.742.112 Safari/534.30', 'http://novi-ua.org/groups/create', 'polls', '', 0);
INSERT INTO user_access VALUES (1, 1310458717, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows NT 5.1) AppleWebKit/534.30 (KHTML, like Gecko) Chrome/12.0.742.112 Safari/534.30', 'http://novi-ua.org/polls', 'groups', '', 0);
INSERT INTO user_access VALUES (1, 1310458719, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows NT 5.1) AppleWebKit/534.30 (KHTML, like Gecko) Chrome/12.0.742.112 Safari/534.30', 'http://novi-ua.org/groups', 'home', '', 0);
INSERT INTO user_access VALUES (1, 1310458721, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows NT 5.1) AppleWebKit/534.30 (KHTML, like Gecko) Chrome/12.0.742.112 Safari/534.30', 'http://novi-ua.org/home', 'groups', '', 0);
INSERT INTO user_access VALUES (1, 1310458726, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows NT 5.1) AppleWebKit/534.30 (KHTML, like Gecko) Chrome/12.0.742.112 Safari/534.30', 'http://novi-ua.org/groups', 'groups', 'create', 0);
INSERT INTO user_access VALUES (1, 1310458729, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows NT 5.1) AppleWebKit/534.30 (KHTML, like Gecko) Chrome/12.0.742.112 Safari/534.30', 'http://novi-ua.org/groups/create', 'polls', '', 0);
INSERT INTO user_access VALUES (1, 1310458729, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows NT 5.1) AppleWebKit/534.30 (KHTML, like Gecko) Chrome/12.0.742.112 Safari/534.30', 'http://novi-ua.org/polls', 'groups', '', 0);
INSERT INTO user_access VALUES (1, 1310458735, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows NT 5.1) AppleWebKit/534.30 (KHTML, like Gecko) Chrome/12.0.742.112 Safari/534.30', 'http://novi-ua.org/groups', 'groups', 'create', 0);
INSERT INTO user_access VALUES (5, 1310458778, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows; U; Windows NT 6.1; ru; rv:1.9.2.17) Gecko/20110420 Firefox/3.6.17 GTB7.1 ( .NET CLR 3.5.30729; .NET4.0E) WebMoney Advisor', 'http://novi-ua.org/home', 'groups', '', 0);
INSERT INTO user_access VALUES (5, 1310458833, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows; U; Windows NT 6.1; ru; rv:1.9.2.17) Gecko/20110420 Firefox/3.6.17 GTB7.1 ( .NET CLR 3.5.30729; .NET4.0E) WebMoney Advisor', 'http://novi-ua.org/groups', 'groups', 'create', 0);
INSERT INTO user_access VALUES (5, 1310458863, '::ffff:194.187.108.86', 'Mozilla/5.0 (X11; Linux i686; rv:5.0) Gecko/20100101 Firefox/5.0', '', 'home', '', 0);
INSERT INTO user_access VALUES (5, 1310458866, '::ffff:194.187.108.86', 'Mozilla/5.0 (X11; Linux i686; rv:5.0) Gecko/20100101 Firefox/5.0', 'http://novi-ua.org/home', 'groups', '', 0);
INSERT INTO user_access VALUES (5, 1310458873, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows; U; Windows NT 6.1; ru; rv:1.9.2.17) Gecko/20110420 Firefox/3.6.17 GTB7.1 ( .NET CLR 3.5.30729; .NET4.0E) WebMoney Advisor', 'http://novi-ua.org/groups/create', 'groups', 'view', 2);
INSERT INTO user_access VALUES (5, 1310459601, '::ffff:194.187.108.86', 'Mozilla/5.0 (X11; Linux i686; rv:5.0) Gecko/20100101 Firefox/5.0', 'http://novi-ua.org/home', 'groups', '', 0);
INSERT INTO user_access VALUES (5, 1310459612, '::ffff:194.187.108.86', 'Mozilla/5.0 (X11; Linux i686; rv:5.0) Gecko/20100101 Firefox/5.0', 'http://novi-ua.org/home', 'groups', '', 0);
INSERT INTO user_access VALUES (5, 1310459622, '::ffff:194.187.108.86', 'Mozilla/5.0 (X11; Linux i686; rv:5.0) Gecko/20100101 Firefox/5.0', 'http://novi-ua.org/groups', 'home', '', 0);
INSERT INTO user_access VALUES (5, 1310459625, '::ffff:194.187.108.86', 'Mozilla/5.0 (X11; Linux i686; rv:5.0) Gecko/20100101 Firefox/5.0', 'http://novi-ua.org/groups', 'home', '', 0);
INSERT INTO user_access VALUES (5, 1310459625, '::ffff:194.187.108.86', 'Mozilla/5.0 (X11; Linux i686; rv:5.0) Gecko/20100101 Firefox/5.0', 'http://novi-ua.org/groups', 'home', '', 0);
INSERT INTO user_access VALUES (5, 1310459629, '::ffff:194.187.108.86', 'Mozilla/5.0 (X11; Linux i686; rv:5.0) Gecko/20100101 Firefox/5.0', 'http://novi-ua.org/home', 'groups', '', 0);
INSERT INTO user_access VALUES (5, 1310460009, '::ffff:194.187.108.86', 'Mozilla/5.0 (X11; Linux i686; rv:5.0) Gecko/20100101 Firefox/5.0', 'http://novi-ua.org/home', 'groups', '', 0);
INSERT INTO user_access VALUES (5, 1310460247, '::ffff:194.187.108.86', 'Mozilla/5.0 (X11; Linux i686; rv:5.0) Gecko/20100101 Firefox/5.0', 'http://novi-ua.org/home', 'groups', '', 0);
INSERT INTO user_access VALUES (5, 1310460444, '::ffff:194.187.108.86', 'Mozilla/5.0 (X11; Linux i686; rv:5.0) Gecko/20100101 Firefox/5.0', 'http://novi-ua.org/home', 'groups', '', 0);
INSERT INTO user_access VALUES (5, 1310460695, '::ffff:194.187.108.86', 'Mozilla/5.0 (X11; Linux i686; rv:5.0) Gecko/20100101 Firefox/5.0', 'http://novi-ua.org/groups', 'groups', '', 0);
INSERT INTO user_access VALUES (5, 1310460698, '::ffff:194.187.108.86', 'Mozilla/5.0 (X11; Linux i686; rv:5.0) Gecko/20100101 Firefox/5.0', 'http://novi-ua.org/groups', 'groups', '', 0);
INSERT INTO user_access VALUES (5, 1310460721, '::ffff:194.187.108.86', 'Mozilla/5.0 (X11; Linux i686; rv:5.0) Gecko/20100101 Firefox/5.0', 'http://novi-ua.org/groups', 'groups', '', 0);
INSERT INTO user_access VALUES (1, 1310462104, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows NT 5.1) AppleWebKit/534.30 (KHTML, like Gecko) Chrome/12.0.742.112 Safari/534.30', 'http://novi-ua.org/groups/create', 'profile', 'index', 1);
INSERT INTO user_access VALUES (5, 1310462364, '::ffff:194.187.108.86', 'Mozilla/5.0 (X11; Linux i686; rv:5.0) Gecko/20100101 Firefox/5.0', 'http://novi-ua.org/groups', 'home', '', 0);
INSERT INTO user_access VALUES (5, 1310462365, '::ffff:194.187.108.86', 'Mozilla/5.0 (X11; Linux i686; rv:5.0) Gecko/20100101 Firefox/5.0', 'http://novi-ua.org/groups', 'home', '', 0);
INSERT INTO user_access VALUES (5, 1310462915, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows; U; Windows NT 6.1; ru; rv:1.9.2.17) Gecko/20110420 Firefox/3.6.17 GTB7.1 ( .NET CLR 3.5.30729; .NET4.0E) WebMoney Advisor', 'http://novi-ua.org/group2', 'home', '', 0);
INSERT INTO user_access VALUES (5, 1310462919, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows; U; Windows NT 6.1; ru; rv:1.9.2.17) Gecko/20110420 Firefox/3.6.17 GTB7.1 ( .NET CLR 3.5.30729; .NET4.0E) WebMoney Advisor', 'http://novi-ua.org/home', 'people', '', 0);
INSERT INTO user_access VALUES (5, 1310462928, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows; U; Windows NT 6.1; ru; rv:1.9.2.17) Gecko/20110420 Firefox/3.6.17 GTB7.1 ( .NET CLR 3.5.30729; .NET4.0E) WebMoney Advisor', 'http://novi-ua.org/people', 'home', '', 0);
INSERT INTO user_access VALUES (5, 1310463132, '::ffff:194.187.108.86', 'Mozilla/5.0 (X11; Linux i686; rv:5.0) Gecko/20100101 Firefox/5.0', 'http://novi-ua.org/home', 'banners', '', 0);
INSERT INTO user_access VALUES (5, 1310463146, '::ffff:194.187.108.86', 'Mozilla/5.0 (X11; Linux i686; rv:5.0) Gecko/20100101 Firefox/5.0', 'http://novi-ua.org/banners', 'profile', 'index', 5);
INSERT INTO user_access VALUES (5, 1310463149, '::ffff:194.187.108.86', 'Mozilla/5.0 (X11; Linux i686; rv:5.0) Gecko/20100101 Firefox/5.0', 'http://novi-ua.org/profile-5', 'profile', 'edit', 5);
INSERT INTO user_access VALUES (2, 1310466749, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; ru; rv:1.9.2.14) Gecko/20110218 Firefox/3.6.14 ( .NET CLR 3.5.30729; .NET4.0E)', 'http://novi-ua.org/sign', 'invites', 'edit', 3);
INSERT INTO user_access VALUES (2, 1310466754, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; ru; rv:1.9.2.14) Gecko/20110218 Firefox/3.6.14 ( .NET CLR 3.5.30729; .NET4.0E)', '', 'invites', 'edit', 3);
INSERT INTO user_access VALUES (2, 1310466758, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; ru; rv:1.9.2.14) Gecko/20110218 Firefox/3.6.14 ( .NET CLR 3.5.30729; .NET4.0E)', 'http://novi-ua.org/invites/edit?commit=1&user=2&id=3&status=1', 'groups', '', 0);
INSERT INTO user_access VALUES (2, 1310466763, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; ru; rv:1.9.2.14) Gecko/20110218 Firefox/3.6.14 ( .NET CLR 3.5.30729; .NET4.0E)', 'http://novi-ua.org/groups', 'groups', 'view', 2);
INSERT INTO user_access VALUES (2, 1310466779, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; ru; rv:1.9.2.14) Gecko/20110218 Firefox/3.6.14 ( .NET CLR 3.5.30729; .NET4.0E)', 'http://novi-ua.org/group2', 'profile', 'index', 5);
INSERT INTO user_access VALUES (1, 1310469505, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows NT 5.1) AppleWebKit/534.30 (KHTML, like Gecko) Chrome/12.0.742.112 Safari/534.30', 'http://novi-ua.org/profile-1', 'people', '', 0);
INSERT INTO user_access VALUES (1, 1310469513, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows NT 5.1) AppleWebKit/534.30 (KHTML, like Gecko) Chrome/12.0.742.112 Safari/534.30', 'http://novi-ua.org/people', 'home', '', 0);
INSERT INTO user_access VALUES (1, 1310469515, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows NT 5.1) AppleWebKit/534.30 (KHTML, like Gecko) Chrome/12.0.742.112 Safari/534.30', 'http://novi-ua.org/home', 'profile', 'index', 1);
INSERT INTO user_access VALUES (1, 1310469516, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows NT 5.1) AppleWebKit/534.30 (KHTML, like Gecko) Chrome/12.0.742.112 Safari/534.30', 'http://novi-ua.org/profile-1', 'people', '', 0);
INSERT INTO user_access VALUES (1, 1310469520, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows NT 5.1) AppleWebKit/534.30 (KHTML, like Gecko) Chrome/12.0.742.112 Safari/534.30', 'http://novi-ua.org/people', 'people', 'index', 0);
INSERT INTO user_access VALUES (1, 1310469523, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows NT 5.1) AppleWebKit/534.30 (KHTML, like Gecko) Chrome/12.0.742.112 Safari/534.30', 'http://novi-ua.org/people/index?offline=all', 'profile', 'index', 1);
INSERT INTO user_access VALUES (1, 1310469526, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows NT 5.1) AppleWebKit/534.30 (KHTML, like Gecko) Chrome/12.0.742.112 Safari/534.30', 'http://novi-ua.org/profile-1', 'profile', 'add', 0);
INSERT INTO user_access VALUES (1, 1310469565, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows NT 5.1) AppleWebKit/534.30 (KHTML, like Gecko) Chrome/12.0.742.112 Safari/534.30', 'http://novi-ua.org/profile/add', 'profile', 'add', 0);
INSERT INTO user_access VALUES (1, 1310469578, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows NT 5.1) AppleWebKit/534.30 (KHTML, like Gecko) Chrome/12.0.742.112 Safari/534.30', 'http://novi-ua.org/profile/add', 'profile', 'index', 12);
INSERT INTO user_access VALUES (1, 1310469580, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows NT 5.1) AppleWebKit/534.30 (KHTML, like Gecko) Chrome/12.0.742.112 Safari/534.30', 'http://novi-ua.org/profile-12', 'profile', 'edit', 12);
INSERT INTO user_access VALUES (1, 1310469766, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows NT 5.1) AppleWebKit/534.30 (KHTML, like Gecko) Chrome/12.0.742.112 Safari/534.30', 'http://novi-ua.org/profile/edit?id=12', 'profile', 'index', 12);
INSERT INTO user_access VALUES (1, 1310469811, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows NT 5.1) AppleWebKit/534.30 (KHTML, like Gecko) Chrome/12.0.742.112 Safari/534.30', 'http://novi-ua.org/profile-12', 'people', '', 0);
INSERT INTO user_access VALUES (1, 1310469818, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows NT 5.1) AppleWebKit/534.30 (KHTML, like Gecko) Chrome/12.0.742.112 Safari/534.30', 'http://novi-ua.org/people', 'people', 'index', 0);
INSERT INTO user_access VALUES (1, 1310469820, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows NT 5.1) AppleWebKit/534.30 (KHTML, like Gecko) Chrome/12.0.742.112 Safari/534.30', 'http://novi-ua.org/people/index?offline=all', 'people', '', 0);
INSERT INTO user_access VALUES (1, 1310469829, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows NT 5.1) AppleWebKit/534.30 (KHTML, like Gecko) Chrome/12.0.742.112 Safari/534.30', 'http://novi-ua.org/people', 'profile', 'index', 1);
INSERT INTO user_access VALUES (1, 1310470426, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows NT 5.1) AppleWebKit/534.30 (KHTML, like Gecko) Chrome/12.0.742.112 Safari/534.30', 'http://novi-ua.org/profile-1', 'profile', 'add', 0);
INSERT INTO user_access VALUES (1, 1310470438, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows NT 5.1) AppleWebKit/534.30 (KHTML, like Gecko) Chrome/12.0.742.112 Safari/534.30', 'http://novi-ua.org/profile/add', 'profile', 'add', 0);
INSERT INTO user_access VALUES (1, 1310470441, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows NT 5.1) AppleWebKit/534.30 (KHTML, like Gecko) Chrome/12.0.742.112 Safari/534.30', 'http://novi-ua.org/profile/add', 'profile', 'index', 1);
INSERT INTO user_access VALUES (1, 1310470443, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows NT 5.1) AppleWebKit/534.30 (KHTML, like Gecko) Chrome/12.0.742.112 Safari/534.30', 'http://novi-ua.org/profile-1', 'people', '', 0);
INSERT INTO user_access VALUES (1, 1310470449, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows NT 5.1) AppleWebKit/534.30 (KHTML, like Gecko) Chrome/12.0.742.112 Safari/534.30', 'http://novi-ua.org/people', 'people', 'index', 0);
INSERT INTO user_access VALUES (1, 1310470451, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows NT 5.1) AppleWebKit/534.30 (KHTML, like Gecko) Chrome/12.0.742.112 Safari/534.30', 'http://novi-ua.org/people/index?offline=all', 'profile', 'index', 1);
INSERT INTO user_access VALUES (1, 1310470453, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows NT 5.1) AppleWebKit/534.30 (KHTML, like Gecko) Chrome/12.0.742.112 Safari/534.30', 'http://novi-ua.org/profile-1', 'profile', 'add', 0);
INSERT INTO user_access VALUES (1, 1310470466, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows NT 5.1) AppleWebKit/534.30 (KHTML, like Gecko) Chrome/12.0.742.112 Safari/534.30', 'http://novi-ua.org/profile/add', 'profile', 'index', 13);
INSERT INTO user_access VALUES (1, 1310470468, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows NT 5.1) AppleWebKit/534.30 (KHTML, like Gecko) Chrome/12.0.742.112 Safari/534.30', 'http://novi-ua.org/profile-13', 'profile', 'edit', 13);
INSERT INTO user_access VALUES (1, 1310470541, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows NT 5.1) AppleWebKit/534.30 (KHTML, like Gecko) Chrome/12.0.742.112 Safari/534.30', 'http://novi-ua.org/profile/edit?id=13', 'profile', 'index', 13);
INSERT INTO user_access VALUES (1, 1310470548, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows NT 5.1) AppleWebKit/534.30 (KHTML, like Gecko) Chrome/12.0.742.112 Safari/534.30', 'http://novi-ua.org/profile-13', 'people', '', 0);
INSERT INTO user_access VALUES (1, 1310471003, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows NT 5.1) AppleWebKit/534.30 (KHTML, like Gecko) Chrome/12.0.742.112 Safari/534.30', 'http://novi-ua.org/people', 'profile', 'index', 1);
INSERT INTO user_access VALUES (1, 1310471007, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows NT 5.1) AppleWebKit/534.30 (KHTML, like Gecko) Chrome/12.0.742.112 Safari/534.30', 'http://novi-ua.org/profile-1', 'profile', 'add', 0);
INSERT INTO user_access VALUES (1, 1310471036, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows NT 5.1) AppleWebKit/534.30 (KHTML, like Gecko) Chrome/12.0.742.112 Safari/534.30', 'http://novi-ua.org/profile/add', 'profile', 'index', 14);
INSERT INTO user_access VALUES (1, 1310471040, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows NT 5.1) AppleWebKit/534.30 (KHTML, like Gecko) Chrome/12.0.742.112 Safari/534.30', 'http://novi-ua.org/profile-14', 'profile', 'edit', 14);
INSERT INTO user_access VALUES (1, 1310471135, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows NT 5.1) AppleWebKit/534.30 (KHTML, like Gecko) Chrome/12.0.742.112 Safari/534.30', 'http://novi-ua.org/profile/edit?id=14', 'profile', 'index', 14);
INSERT INTO user_access VALUES (1, 1310471142, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows NT 5.1) AppleWebKit/534.30 (KHTML, like Gecko) Chrome/12.0.742.112 Safari/534.30', 'http://novi-ua.org/profile-14', 'profile', 'index', 1);
INSERT INTO user_access VALUES (1, 1310471865, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows NT 5.1) AppleWebKit/534.30 (KHTML, like Gecko) Chrome/12.0.742.112 Safari/534.30', 'http://novi-ua.org/profile-1', 'profile', 'add', 0);
INSERT INTO user_access VALUES (1, 1310472508, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows NT 5.1) AppleWebKit/534.30 (KHTML, like Gecko) Chrome/12.0.742.112 Safari/534.30', 'http://novi-ua.org/profile/add', 'profile', 'index', 15);
INSERT INTO user_access VALUES (1, 1310472510, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows NT 5.1) AppleWebKit/534.30 (KHTML, like Gecko) Chrome/12.0.742.112 Safari/534.30', 'http://novi-ua.org/profile-15', 'profile', 'edit', 15);
INSERT INTO user_access VALUES (1, 1310473560, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows NT 5.1) AppleWebKit/534.30 (KHTML, like Gecko) Chrome/12.0.742.112 Safari/534.30', 'http://novi-ua.org/profile/edit?id=15', 'profile', 'index', 15);
INSERT INTO user_access VALUES (1, 1310473578, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows NT 5.1) AppleWebKit/534.30 (KHTML, like Gecko) Chrome/12.0.742.112 Safari/534.30', 'http://novi-ua.org/profile-15', 'profile', 'edit', 15);
INSERT INTO user_access VALUES (1, 1310473861, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows NT 5.1) AppleWebKit/534.30 (KHTML, like Gecko) Chrome/12.0.742.112 Safari/534.30', 'http://novi-ua.org/profile/edit?id=15', 'profile', 'index', 15);
INSERT INTO user_access VALUES (1, 1310473866, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows NT 5.1) AppleWebKit/534.30 (KHTML, like Gecko) Chrome/12.0.742.112 Safari/534.30', 'http://novi-ua.org/profile-15', 'profile', 'index', 1);
INSERT INTO user_access VALUES (1, 1310473868, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows NT 5.1) AppleWebKit/534.30 (KHTML, like Gecko) Chrome/12.0.742.112 Safari/534.30', 'http://novi-ua.org/profile-1', 'profile', 'add', 0);
INSERT INTO user_access VALUES (1, 1310473923, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows NT 5.1) AppleWebKit/534.30 (KHTML, like Gecko) Chrome/12.0.742.112 Safari/534.30', 'http://novi-ua.org/profile/add', 'profile', 'index', 16);
INSERT INTO user_access VALUES (1, 1310473932, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows NT 5.1) AppleWebKit/534.30 (KHTML, like Gecko) Chrome/12.0.742.112 Safari/534.30', 'http://novi-ua.org/profile-16', 'profile', 'edit', 16);
INSERT INTO user_access VALUES (1, 1310474542, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows NT 5.1) AppleWebKit/534.30 (KHTML, like Gecko) Chrome/12.0.742.112 Safari/534.30', 'http://novi-ua.org/profile/edit?id=16', 'people', '', 0);
INSERT INTO user_access VALUES (1, 1310474545, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows NT 5.1) AppleWebKit/534.30 (KHTML, like Gecko) Chrome/12.0.742.112 Safari/534.30', 'http://novi-ua.org/people', 'people', '', 0);
INSERT INTO user_access VALUES (1, 1310474549, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows NT 5.1) AppleWebKit/534.30 (KHTML, like Gecko) Chrome/12.0.742.112 Safari/534.30', 'http://novi-ua.org/people?page=2', 'people', 'index', 0);
INSERT INTO user_access VALUES (1, 1310474703, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows NT 5.1) AppleWebKit/534.30 (KHTML, like Gecko) Chrome/12.0.742.112 Safari/534.30', 'http://novi-ua.org/people/index?offline=all', 'profile', 'index', 1);
INSERT INTO user_access VALUES (1, 1310474707, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows NT 5.1) AppleWebKit/534.30 (KHTML, like Gecko) Chrome/12.0.742.112 Safari/534.30', 'http://novi-ua.org/profile-1', 'profile', 'add', 0);
INSERT INTO user_access VALUES (1, 1310474794, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows NT 5.1) AppleWebKit/534.30 (KHTML, like Gecko) Chrome/12.0.742.112 Safari/534.30', 'http://novi-ua.org/profile/add', 'profile', 'index', 17);
INSERT INTO user_access VALUES (1, 1310474800, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows NT 5.1) AppleWebKit/534.30 (KHTML, like Gecko) Chrome/12.0.742.112 Safari/534.30', 'http://novi-ua.org/profile-17', 'profile', 'edit', 17);
INSERT INTO user_access VALUES (1, 1310475248, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows NT 5.1) AppleWebKit/534.30 (KHTML, like Gecko) Chrome/12.0.742.112 Safari/534.30', 'http://novi-ua.org/profile/edit?id=17', 'people', '', 0);
INSERT INTO user_access VALUES (1, 1310475252, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows NT 5.1) AppleWebKit/534.30 (KHTML, like Gecko) Chrome/12.0.742.112 Safari/534.30', 'http://novi-ua.org/people', 'profile', 'index', 15);
INSERT INTO user_access VALUES (1, 1310475253, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows NT 5.1) AppleWebKit/534.30 (KHTML, like Gecko) Chrome/12.0.742.112 Safari/534.30', 'http://novi-ua.org/profile-15', 'profile', 'edit', 15);
INSERT INTO user_access VALUES (1, 1310475264, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows NT 5.1) AppleWebKit/534.30 (KHTML, like Gecko) Chrome/12.0.742.112 Safari/534.30', 'http://novi-ua.org/profile/edit?id=15', 'people', '', 0);
INSERT INTO user_access VALUES (1, 1310475267, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows NT 5.1) AppleWebKit/534.30 (KHTML, like Gecko) Chrome/12.0.742.112 Safari/534.30', 'http://novi-ua.org/people', 'people', '', 0);
INSERT INTO user_access VALUES (1, 1310475370, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows NT 5.1) AppleWebKit/534.30 (KHTML, like Gecko) Chrome/12.0.742.112 Safari/534.30', 'http://novi-ua.org/people?page=2', 'profile', 'index', 17);
INSERT INTO user_access VALUES (1, 1310475371, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows NT 5.1) AppleWebKit/534.30 (KHTML, like Gecko) Chrome/12.0.742.112 Safari/534.30', 'http://novi-ua.org/profile-17', 'profile', 'edit', 17);
INSERT INTO user_access VALUES (1, 1310475391, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows NT 5.1) AppleWebKit/534.30 (KHTML, like Gecko) Chrome/12.0.742.112 Safari/534.30', 'http://novi-ua.org/profile/edit?id=17', 'people', '', 0);
INSERT INTO user_access VALUES (1, 1310475400, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows NT 5.1) AppleWebKit/534.30 (KHTML, like Gecko) Chrome/12.0.742.112 Safari/534.30', 'http://novi-ua.org/people', 'people', '', 0);
INSERT INTO user_access VALUES (1, 1310476438, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows NT 5.1) AppleWebKit/534.30 (KHTML, like Gecko) Chrome/12.0.742.112 Safari/534.30', 'http://novi-ua.org/people?page=2', 'profile', 'index', 17);
INSERT INTO user_access VALUES (1, 1310476775, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows NT 5.1) AppleWebKit/534.30 (KHTML, like Gecko) Chrome/12.0.742.112 Safari/534.30', 'http://novi-ua.org/profile-17', 'profile', 'index', 1);
INSERT INTO user_access VALUES (1, 1310476779, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows NT 5.1) AppleWebKit/534.30 (KHTML, like Gecko) Chrome/12.0.742.112 Safari/534.30', 'http://novi-ua.org/profile-1', 'profile', 'add', 0);
INSERT INTO user_access VALUES (1, 1310476800, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows NT 5.1) AppleWebKit/534.30 (KHTML, like Gecko) Chrome/12.0.742.112 Safari/534.30', 'http://novi-ua.org/profile/add', 'profile', 'index', 18);
INSERT INTO user_access VALUES (1, 1310476804, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows NT 5.1) AppleWebKit/534.30 (KHTML, like Gecko) Chrome/12.0.742.112 Safari/534.30', 'http://novi-ua.org/profile-18', 'profile', 'edit', 18);
INSERT INTO user_access VALUES (1, 1310478024, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows NT 5.1) AppleWebKit/534.30 (KHTML, like Gecko) Chrome/12.0.742.112 Safari/534.30', 'http://novi-ua.org/profile/edit?id=18', 'people', '', 0);
INSERT INTO user_access VALUES (1, 1310478027, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows NT 5.1) AppleWebKit/534.30 (KHTML, like Gecko) Chrome/12.0.742.112 Safari/534.30', 'http://novi-ua.org/people', 'people', '', 0);
INSERT INTO user_access VALUES (1, 1310478030, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows NT 5.1) AppleWebKit/534.30 (KHTML, like Gecko) Chrome/12.0.742.112 Safari/534.30', 'http://novi-ua.org/people?page=2', 'profile', 'index', 1);
INSERT INTO user_access VALUES (5, 1310534203, '::ffff:194.187.108.86', 'Mozilla/5.0 (X11; Linux i686; rv:5.0) Gecko/20100101 Firefox/5.0', 'http://novi-ua.org/profile-5', 'profile', 'edit', 5);
INSERT INTO user_access VALUES (5, 1310541235, '::ffff:194.187.108.86', 'Mozilla/5.0 (X11; Linux i686; rv:5.0) Gecko/20100101 Firefox/5.0', 'http://novi-ua.org/profile/edit?id=5', 'home', '', 0);
INSERT INTO user_access VALUES (5, 1310542361, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows; U; Windows NT 6.1; ru; rv:1.9.2.17) Gecko/20110420 Firefox/3.6.17 GTB7.1 ( .NET CLR 3.5.30729; .NET4.0E) WebMoney Advisor', '', 'profile', '', 0);
INSERT INTO user_access VALUES (5, 1310542364, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows; U; Windows NT 6.1; ru; rv:1.9.2.17) Gecko/20110420 Firefox/3.6.17 GTB7.1 ( .NET CLR 3.5.30729; .NET4.0E) WebMoney Advisor', 'http://novi-ua.org/profile', 'people', '', 0);
INSERT INTO user_access VALUES (5, 1310542372, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows; U; Windows NT 6.1; ru; rv:1.9.2.17) Gecko/20110420 Firefox/3.6.17 GTB7.1 ( .NET CLR 3.5.30729; .NET4.0E) WebMoney Advisor', 'http://novi-ua.org/people', 'people', '', 0);
INSERT INTO user_access VALUES (5, 1310542376, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows; U; Windows NT 6.1; ru; rv:1.9.2.17) Gecko/20110420 Firefox/3.6.17 GTB7.1 ( .NET CLR 3.5.30729; .NET4.0E) WebMoney Advisor', 'http://novi-ua.org/people?page=2', 'profile', 'index', 17);
INSERT INTO user_access VALUES (5, 1310542383, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows; U; Windows NT 6.1; ru; rv:1.9.2.17) Gecko/20110420 Firefox/3.6.17 GTB7.1 ( .NET CLR 3.5.30729; .NET4.0E) WebMoney Advisor', 'http://novi-ua.org/people', 'people', '', 0);
INSERT INTO user_access VALUES (5, 1310543248, '::ffff:194.187.108.86', 'Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 6.1; WOW64; Trident/4.0; WebMoney Advisor; SLCC2; .NET CLR 2.0.50727; .NET CLR 3.5.30729; .NET CLR 3.0.30729; Media Center PC 6.0; InfoPath.2; .NET4.0C; .NET4.0E)', 'http://novi-ua.org/home', 'home', '', 0);
INSERT INTO user_access VALUES (5, 1310543251, '::ffff:194.187.108.86', 'Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 6.1; WOW64; Trident/4.0; WebMoney Advisor; SLCC2; .NET CLR 2.0.50727; .NET CLR 3.5.30729; .NET CLR 3.0.30729; Media Center PC 6.0; InfoPath.2; .NET4.0C; .NET4.0E)', 'http://novi-ua.org/home', 'home', '', 0);
INSERT INTO user_access VALUES (5, 1310543258, '::ffff:194.187.108.86', 'Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 6.1; WOW64; Trident/4.0; WebMoney Advisor; SLCC2; .NET CLR 2.0.50727; .NET CLR 3.5.30729; .NET CLR 3.0.30729; Media Center PC 6.0; InfoPath.2; .NET4.0C; .NET4.0E)', 'http://novi-ua.org/home', 'home', '', 0);
INSERT INTO user_access VALUES (5, 1310543274, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows; U; Windows NT 6.1; ru; rv:1.9.2.17) Gecko/20110420 Firefox/3.6.17 GTB7.1 ( .NET CLR 3.5.30729; .NET4.0E) WebMoney Advisor', 'http://novi-ua.org/people?page=2', 'home', '', 0);
INSERT INTO user_access VALUES (5, 1310543989, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows; U; Windows NT 6.1; ru; rv:1.9.2.17) Gecko/20110420 Firefox/3.6.17 GTB7.1 ( .NET CLR 3.5.30729; .NET4.0E) WebMoney Advisor', 'http://novi-ua.org/home', 'home', '', 0);
INSERT INTO user_access VALUES (5, 1310544366, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows; U; Windows NT 6.1; ru; rv:1.9.2.17) Gecko/20110420 Firefox/3.6.17 GTB7.1 ( .NET CLR 3.5.30729; .NET4.0E) WebMoney Advisor', 'http://novi-ua.org/home', 'home', '', 0);
INSERT INTO user_access VALUES (5, 1310546208, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows; U; Windows NT 6.1; ru; rv:1.9.2.17) Gecko/20110420 Firefox/3.6.17 GTB7.1 ( .NET CLR 3.5.30729; .NET4.0E) WebMoney Advisor', 'http://novi-ua.org/home', 'people', '', 0);
INSERT INTO user_access VALUES (5, 1310546252, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows; U; Windows NT 6.1; ru; rv:1.9.2.17) Gecko/20110420 Firefox/3.6.17 GTB7.1 ( .NET CLR 3.5.30729; .NET4.0E) WebMoney Advisor', 'http://novi-ua.org/people', 'people', '', 0);
INSERT INTO user_access VALUES (5, 1310546253, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows; U; Windows NT 6.1; ru; rv:1.9.2.17) Gecko/20110420 Firefox/3.6.17 GTB7.1 ( .NET CLR 3.5.30729; .NET4.0E) WebMoney Advisor', 'http://novi-ua.org/people?page=2', 'profile', 'index', 16);
INSERT INTO user_access VALUES (5, 1310546319, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows; U; Windows NT 6.1; ru; rv:1.9.2.17) Gecko/20110420 Firefox/3.6.17 GTB7.1 ( .NET CLR 3.5.30729; .NET4.0E) WebMoney Advisor', 'http://novi-ua.org/profile-16', 'profile', 'edit', 16);
INSERT INTO user_access VALUES (1360, 1310546355, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.19) Gecko/20110707 Firefox/3.6.19', '', 'home', '', 0);
INSERT INTO user_access VALUES (1360, 1310546363, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.19) Gecko/20110707 Firefox/3.6.19', 'http://novi-ua.org/home', 'people', '', 0);
INSERT INTO user_access VALUES (5, 1310546364, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows; U; Windows NT 6.1; ru; rv:1.9.2.17) Gecko/20110420 Firefox/3.6.17 GTB7.1 ( .NET CLR 3.5.30729; .NET4.0E) WebMoney Advisor', 'http://novi-ua.org/profile/edit?id=16', 'people', '', 0);
INSERT INTO user_access VALUES (1360, 1310546367, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.19) Gecko/20110707 Firefox/3.6.19', 'http://novi-ua.org/people', 'people', '', 0);
INSERT INTO user_access VALUES (5, 1310546367, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows; U; Windows NT 6.1; ru; rv:1.9.2.17) Gecko/20110420 Firefox/3.6.17 GTB7.1 ( .NET CLR 3.5.30729; .NET4.0E) WebMoney Advisor', 'http://novi-ua.org/people', 'people', '', 0);
INSERT INTO user_access VALUES (1360, 1310546368, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.19) Gecko/20110707 Firefox/3.6.19', 'http://novi-ua.org/people?page=2', 'profile', 'index', 16);
INSERT INTO user_access VALUES (5, 1310546369, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows; U; Windows NT 6.1; ru; rv:1.9.2.17) Gecko/20110420 Firefox/3.6.17 GTB7.1 ( .NET CLR 3.5.30729; .NET4.0E) WebMoney Advisor', 'http://novi-ua.org/people?page=2', 'profile', 'index', 16);
INSERT INTO user_access VALUES (1360, 1310548792, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.19) Gecko/20110707 Firefox/3.6.19', 'http://novi-ua.org/profile-16', 'admin', '', 0);
INSERT INTO user_access VALUES (1360, 1310548804, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.19) Gecko/20110707 Firefox/3.6.19', 'http://novi-ua.org/admin', 'admin', 'users', 0);
INSERT INTO user_access VALUES (1360, 1310548805, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.19) Gecko/20110707 Firefox/3.6.19', 'http://novi-ua.org/admin/users', 'admin', 'users_list', 0);
INSERT INTO user_access VALUES (1, 1310548889, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows NT 5.1) AppleWebKit/534.30 (KHTML, like Gecko) Chrome/12.0.742.112 Safari/534.30', '', 'home', '', 0);
INSERT INTO user_access VALUES (1, 1310548892, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows NT 5.1) AppleWebKit/534.30 (KHTML, like Gecko) Chrome/12.0.742.112 Safari/534.30', 'http://novi-ua.org/home', 'admin', '', 0);
INSERT INTO user_access VALUES (1, 1310548895, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows NT 5.1) AppleWebKit/534.30 (KHTML, like Gecko) Chrome/12.0.742.112 Safari/534.30', 'http://novi-ua.org/admin', 'admin', 'users', 0);
INSERT INTO user_access VALUES (1, 1310548907, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows NT 5.1) AppleWebKit/534.30 (KHTML, like Gecko) Chrome/12.0.742.112 Safari/534.30', 'http://novi-ua.org/admin/users', 'admin', 'users_list', 0);
INSERT INTO user_access VALUES (1360, 1310549195, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.19) Gecko/20110707 Firefox/3.6.19', 'http://novi-ua.org/admin/users_list', 'profile', 'index', 1360);
INSERT INTO user_access VALUES (1360, 1310549713, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.19) Gecko/20110707 Firefox/3.6.19', 'http://novi-ua.org/profile-16', 'profile', 'edit', 16);
INSERT INTO user_access VALUES (1360, 1310551003, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.19) Gecko/20110707 Firefox/3.6.19', 'http://novi-ua.org/admin/users_list', 'profile', 'index', 1360);
INSERT INTO user_access VALUES (1360, 1310551009, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.19) Gecko/20110707 Firefox/3.6.19', 'http://novi-ua.org/profile/edit?id=16', 'profile', 'index', 16);
INSERT INTO user_access VALUES (1360, 1310551047, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.19) Gecko/20110707 Firefox/3.6.19', 'http://novi-ua.org/admin/users_list', 'profile', 'index', 1360);
INSERT INTO user_access VALUES (1360, 1310551078, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.19) Gecko/20110707 Firefox/3.6.19', 'http://novi-ua.org/admin/users_list', 'profile', 'index', 1360);
INSERT INTO user_access VALUES (1360, 1310551095, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.19) Gecko/20110707 Firefox/3.6.19', 'http://novi-ua.org/admin/users_list', 'profile', 'index', 1360);
INSERT INTO user_access VALUES (1360, 1310551126, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.19) Gecko/20110707 Firefox/3.6.19', 'http://novi-ua.org/admin/users_list', 'profile', 'index', 1360);
INSERT INTO user_access VALUES (1360, 1310551250, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.19) Gecko/20110707 Firefox/3.6.19', 'http://novi-ua.org/admin/users_list', 'profile', 'index', 1360);
INSERT INTO user_access VALUES (1360, 1310551321, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.19) Gecko/20110707 Firefox/3.6.19', 'http://novi-ua.org/admin/users_list', 'profile', 'index', 1360);
INSERT INTO user_access VALUES (1360, 1310551455, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.19) Gecko/20110707 Firefox/3.6.19', 'http://novi-ua.org/admin/users_list', 'profile', 'index', 1360);
INSERT INTO user_access VALUES (1360, 1310551680, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.19) Gecko/20110707 Firefox/3.6.19', 'http://novi-ua.org/admin/users_list', 'profile', 'index', 1360);
INSERT INTO user_access VALUES (1360, 1310551715, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.19) Gecko/20110707 Firefox/3.6.19', 'http://novi-ua.org/admin/users_list', 'profile', 'index', 1360);
INSERT INTO user_access VALUES (1360, 1310551717, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.19) Gecko/20110707 Firefox/3.6.19', 'http://novi-ua.org/profile-1360', 'profile', 'add', 0);
INSERT INTO user_access VALUES (1360, 1310551729, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.19) Gecko/20110707 Firefox/3.6.19', 'http://novi-ua.org/profile/add', 'profile', 'add', 0);
INSERT INTO user_access VALUES (1360, 1310551829, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.19) Gecko/20110707 Firefox/3.6.19', '', 'profile', 'add', 0);
INSERT INTO user_access VALUES (1360, 1310551838, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.19) Gecko/20110707 Firefox/3.6.19', 'http://novi-ua.org/profile/add', 'profile', 'index', 19);
INSERT INTO user_access VALUES (1360, 1310554825, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.19) Gecko/20110707 Firefox/3.6.19', 'http://novi-ua.org/profile-19', 'profile', 'edit', 19);
INSERT INTO user_access VALUES (1360, 1310554881, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.19) Gecko/20110707 Firefox/3.6.19', 'http://novi-ua.org/profile/edit?id=19', 'profile', 'index', 19);
INSERT INTO user_access VALUES (1360, 1310554883, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.19) Gecko/20110707 Firefox/3.6.19', 'http://novi-ua.org/profile-19', 'profile', 'edit', 19);
INSERT INTO user_access VALUES (1360, 1310554888, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.19) Gecko/20110707 Firefox/3.6.19', 'http://novi-ua.org/profile/edit?id=19', 'profile', 'index', 19);
INSERT INTO user_access VALUES (1360, 1310555007, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.19) Gecko/20110707 Firefox/3.6.19', 'http://novi-ua.org/profile/edit?id=16', 'profile', 'index', 16);
INSERT INTO user_access VALUES (1360, 1310555031, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.19) Gecko/20110707 Firefox/3.6.19', 'http://novi-ua.org/profile-16', 'admin', '', 0);
INSERT INTO user_access VALUES (1360, 1310555037, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.19) Gecko/20110707 Firefox/3.6.19', 'http://novi-ua.org/admin', 'admin', 'mails', 0);
INSERT INTO user_access VALUES (1360, 1310555048, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.19) Gecko/20110707 Firefox/3.6.19', 'http://novi-ua.org/admin/mails', 'admin', 'medit', 3);
INSERT INTO user_access VALUES (1360, 1310555232, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.19) Gecko/20110707 Firefox/3.6.19', 'http://novi-ua.org/profile/edit?id=19', 'profile', 'index', 19);
INSERT INTO user_access VALUES (1360, 1310555427, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.19) Gecko/20110707 Firefox/3.6.19', '', 'home', '', 0);
INSERT INTO user_access VALUES (1360, 1310555435, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.19) Gecko/20110707 Firefox/3.6.19', 'http://novi-ua.org/home', 'people', '', 0);
INSERT INTO user_access VALUES (1360, 1310555438, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.19) Gecko/20110707 Firefox/3.6.19', 'http://novi-ua.org/people', 'people', '', 0);
INSERT INTO user_access VALUES (1360, 1310555441, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.19) Gecko/20110707 Firefox/3.6.19', 'http://novi-ua.org/people?page=2', 'profile', 'index', 19);
INSERT INTO user_access VALUES (1360, 1310555458, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.19) Gecko/20110707 Firefox/3.6.19', 'http://novi-ua.org/people?page=2', 'profile', 'index', 19);
INSERT INTO user_access VALUES (1360, 1310555487, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.19) Gecko/20110707 Firefox/3.6.19', 'http://e.mail.ru/cgi-bin/readmsg?id=13103989350000000690&folder=0', 'profile', '', 0);
INSERT INTO user_access VALUES (1360, 1310555597, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.19) Gecko/20110707 Firefox/3.6.19', '', 'home', '', 0);
INSERT INTO user_access VALUES (1360, 1310555599, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.19) Gecko/20110707 Firefox/3.6.19', 'http://novi-ua.org/home', 'people', '', 0);
INSERT INTO user_access VALUES (1360, 1310555603, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.19) Gecko/20110707 Firefox/3.6.19', 'http://novi-ua.org/people', 'people', '', 0);
INSERT INTO user_access VALUES (1360, 1310555607, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.19) Gecko/20110707 Firefox/3.6.19', 'http://novi-ua.org/people?page=2', 'profile', 'index', 19);
INSERT INTO user_access VALUES (1360, 1310555619, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.19) Gecko/20110707 Firefox/3.6.19', 'http://e.mail.ru/cgi-bin/readmsg?id=13103989350000000690&folder=0', 'profile', '', 0);
INSERT INTO user_access VALUES (19, 1310555813, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.19) Gecko/20110707 Firefox/3.6.19', 'http://e.mail.ru/cgi-bin/readmsg?id=13103989350000000690&folder=0', 'profile', '', 0);
INSERT INTO user_access VALUES (1360, 1310555823, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.19) Gecko/20110707 Firefox/3.6.19', 'http://novi-ua.org/profile', 'home', '', 0);
INSERT INTO user_access VALUES (1360, 1310555826, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.19) Gecko/20110707 Firefox/3.6.19', 'http://novi-ua.org/home', 'people', '', 0);
INSERT INTO user_access VALUES (1360, 1310555834, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.19) Gecko/20110707 Firefox/3.6.19', 'http://novi-ua.org/people', 'profile', 'index', 19);
INSERT INTO user_access VALUES (1360, 1310555836, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.19) Gecko/20110707 Firefox/3.6.19', 'http://novi-ua.org/profile-19', 'profile', 'edit', 19);
INSERT INTO user_access VALUES (1360, 1310555847, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.19) Gecko/20110707 Firefox/3.6.19', 'http://novi-ua.org/profile/edit?id=19', 'home', '', 0);
INSERT INTO user_access VALUES (1360, 1310555853, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.19) Gecko/20110707 Firefox/3.6.19', 'http://novi-ua.org/home', 'profile', 'index', 1360);
INSERT INTO user_access VALUES (1360, 1310555909, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.19) Gecko/20110707 Firefox/3.6.19', 'http://novi-ua.org/home', 'profile', 'index', 1360);
INSERT INTO user_access VALUES (1, 1310555937, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows NT 5.1) AppleWebKit/534.30 (KHTML, like Gecko) Chrome/12.0.742.112 Safari/534.30', '', 'home', '', 0);
INSERT INTO user_access VALUES (1, 1310555940, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows NT 5.1) AppleWebKit/534.30 (KHTML, like Gecko) Chrome/12.0.742.112 Safari/534.30', 'http://novi-ua.org/home', 'people', '', 0);
INSERT INTO user_access VALUES (1, 1310555943, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows NT 5.1) AppleWebKit/534.30 (KHTML, like Gecko) Chrome/12.0.742.112 Safari/534.30', 'http://novi-ua.org/people', 'profile', 'index', 29);
INSERT INTO user_access VALUES (1, 1310555958, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows NT 5.1) AppleWebKit/534.30 (KHTML, like Gecko) Chrome/12.0.742.112 Safari/534.30', 'http://novi-ua.org/profile-29', 'profile', 'index', 1);
INSERT INTO user_access VALUES (1, 1310555981, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows NT 5.1) AppleWebKit/534.30 (KHTML, like Gecko) Chrome/12.0.742.112 Safari/534.30', 'http://novi-ua.org/profile-1', 'profile', 'index', 1);
INSERT INTO user_access VALUES (1, 1310555987, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows NT 5.1) AppleWebKit/534.30 (KHTML, like Gecko) Chrome/12.0.742.112 Safari/534.30', 'http://novi-ua.org/profile-1', 'people', '', 0);
INSERT INTO user_access VALUES (1, 1310555990, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows NT 5.1) AppleWebKit/534.30 (KHTML, like Gecko) Chrome/12.0.742.112 Safari/534.30', 'http://novi-ua.org/people', 'profile', 'index', 5);
INSERT INTO user_access VALUES (1, 1310555993, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows NT 5.1) AppleWebKit/534.30 (KHTML, like Gecko) Chrome/12.0.742.112 Safari/534.30', 'http://novi-ua.org/profile-5', 'profile', 'index', 1);
INSERT INTO user_access VALUES (1, 1310556000, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows NT 5.1) AppleWebKit/534.30 (KHTML, like Gecko) Chrome/12.0.742.112 Safari/534.30', 'http://novi-ua.org/profile-1', 'people', '', 0);
INSERT INTO user_access VALUES (1, 1310556001, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows NT 5.1) AppleWebKit/534.30 (KHTML, like Gecko) Chrome/12.0.742.112 Safari/534.30', 'http://novi-ua.org/people', 'profile', 'index', 11);
INSERT INTO user_access VALUES (1, 1310556010, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows NT 5.1) AppleWebKit/534.30 (KHTML, like Gecko) Chrome/12.0.742.112 Safari/534.30', '', 'profile', 'index', 1);
INSERT INTO user_access VALUES (1, 1310556064, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows NT 5.1) AppleWebKit/534.30 (KHTML, like Gecko) Chrome/12.0.742.112 Safari/534.30', 'http://novi-ua.org/profile-1', 'admin', '', 0);
INSERT INTO user_access VALUES (1, 1310556070, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows NT 5.1) AppleWebKit/534.30 (KHTML, like Gecko) Chrome/12.0.742.112 Safari/534.30', 'http://novi-ua.org/admin', 'admin', 'mails', 0);
INSERT INTO user_access VALUES (1, 1310556081, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows NT 5.1) AppleWebKit/534.30 (KHTML, like Gecko) Chrome/12.0.742.112 Safari/534.30', 'http://novi-ua.org/admin/mails', 'admin', 'medit', 3);
INSERT INTO user_access VALUES (1, 1310556186, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows NT 5.1) AppleWebKit/534.30 (KHTML, like Gecko) Chrome/12.0.742.112 Safari/534.30', 'http://novi-ua.org/admin/medit?id=3', 'profile', 'index', 1);
INSERT INTO user_access VALUES (1360, 1310556188, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.19) Gecko/20110707 Firefox/3.6.19', 'http://novi-ua.org/profile-1360', 'people', '', 0);
INSERT INTO user_access VALUES (1360, 1310556190, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.19) Gecko/20110707 Firefox/3.6.19', 'http://novi-ua.org/people', 'profile', 'index', 1);
INSERT INTO user_access VALUES (1360, 1310556298, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.19) Gecko/20110707 Firefox/3.6.19', 'http://novi-ua.org/people', 'profile', 'index', 1);
INSERT INTO user_access VALUES (1360, 1310556301, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.19) Gecko/20110707 Firefox/3.6.19', 'http://novi-ua.org/people', 'profile', 'index', 1);
INSERT INTO user_access VALUES (1360, 1310556310, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.19) Gecko/20110707 Firefox/3.6.19', 'http://novi-ua.org/people', 'profile', 'index', 1);
INSERT INTO user_access VALUES (1360, 1310556364, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.19) Gecko/20110707 Firefox/3.6.19', 'http://novi-ua.org/people', 'profile', 'index', 1);
INSERT INTO user_access VALUES (1360, 1310556369, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.19) Gecko/20110707 Firefox/3.6.19', 'http://novi-ua.org/profile-1', 'profile', 'index', 1360);
INSERT INTO user_access VALUES (1360, 1310556380, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.19) Gecko/20110707 Firefox/3.6.19', 'http://novi-ua.org/profile-1360', 'profile', 'add', 0);
INSERT INTO user_access VALUES (1360, 1310556384, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.19) Gecko/20110707 Firefox/3.6.19', 'http://novi-ua.org/profile/add', 'profile', 'index', 20);
INSERT INTO user_access VALUES (1360, 1310556388, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.19) Gecko/20110707 Firefox/3.6.19', 'http://novi-ua.org/profile-20', 'profile', 'edit', 20);
INSERT INTO user_access VALUES (1360, 1310556403, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.19) Gecko/20110707 Firefox/3.6.19', 'http://novi-ua.org/profile/edit?id=20', 'profile', 'index', 20);
INSERT INTO user_access VALUES (1360, 1310556409, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.19) Gecko/20110707 Firefox/3.6.19', 'http://novi-ua.org/profile/edit?id=20', 'profile', 'index', 20);
INSERT INTO user_access VALUES (20, 1310556442, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.19) Gecko/20110707 Firefox/3.6.19', 'http://e.mail.ru/cgi-bin/readmsg?id=13103989350000000690&folder=0', 'profile', '', 0);
INSERT INTO user_access VALUES (1360, 1310556451, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.19) Gecko/20110707 Firefox/3.6.19', 'http://novi-ua.org/profile', 'home', '', 0);
INSERT INTO user_access VALUES (1360, 1310556456, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.19) Gecko/20110707 Firefox/3.6.19', 'http://novi-ua.org/home', 'people', '', 0);
INSERT INTO user_access VALUES (1360, 1310556459, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.19) Gecko/20110707 Firefox/3.6.19', 'http://novi-ua.org/people', 'profile', 'index', 20);
INSERT INTO user_access VALUES (1360, 1310556463, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.19) Gecko/20110707 Firefox/3.6.19', 'http://novi-ua.org/profile-20', 'profile', 'edit', 20);
INSERT INTO user_access VALUES (1360, 1310556468, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.19) Gecko/20110707 Firefox/3.6.19', 'http://novi-ua.org/profile/edit?id=20', 'home', '', 0);
INSERT INTO user_access VALUES (1360, 1310556535, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.19) Gecko/20110707 Firefox/3.6.19', '', 'home', '', 0);
INSERT INTO user_access VALUES (1360, 1310556536, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.19) Gecko/20110707 Firefox/3.6.19', 'http://novi-ua.org/home', 'admin', '', 0);
INSERT INTO user_access VALUES (1360, 1310556538, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.19) Gecko/20110707 Firefox/3.6.19', 'http://novi-ua.org/admin', 'admin', 'mails', 0);
INSERT INTO user_access VALUES (1360, 1310556604, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.19) Gecko/20110707 Firefox/3.6.19', 'http://novi-ua.org/admin/mails', 'admin', 'medit', 36);
INSERT INTO user_access VALUES (1, 1310556649, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows NT 5.1) AppleWebKit/534.30 (KHTML, like Gecko) Chrome/12.0.742.112 Safari/534.30', '', 'home', '', 0);
INSERT INTO user_access VALUES (1, 1310556652, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows NT 5.1) AppleWebKit/534.30 (KHTML, like Gecko) Chrome/12.0.742.112 Safari/534.30', 'http://novi-ua.org/home', 'admin', '', 0);
INSERT INTO user_access VALUES (1, 1310556654, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows NT 5.1) AppleWebKit/534.30 (KHTML, like Gecko) Chrome/12.0.742.112 Safari/534.30', 'http://novi-ua.org/admin', 'admin', 'mails', 0);
INSERT INTO user_access VALUES (1, 1310556660, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows NT 5.1) AppleWebKit/534.30 (KHTML, like Gecko) Chrome/12.0.742.112 Safari/534.30', 'http://novi-ua.org/admin/mails', 'admin', 'medit', 36);
INSERT INTO user_access VALUES (5, 1310558220, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows; U; Windows NT 6.1; ru; rv:1.9.2.17) Gecko/20110420 Firefox/3.6.17 GTB7.1 ( .NET CLR 3.5.30729; .NET4.0E) WebMoney Advisor', 'http://novi-ua.org/profile-16', 'home', '', 0);
INSERT INTO user_access VALUES (5, 1310558224, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows; U; Windows NT 6.1; ru; rv:1.9.2.17) Gecko/20110420 Firefox/3.6.17 GTB7.1 ( .NET CLR 3.5.30729; .NET4.0E) WebMoney Advisor', 'http://novi-ua.org/home', 'people', '', 0);
INSERT INTO user_access VALUES (5, 1310558228, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows; U; Windows NT 6.1; ru; rv:1.9.2.17) Gecko/20110420 Firefox/3.6.17 GTB7.1 ( .NET CLR 3.5.30729; .NET4.0E) WebMoney Advisor', 'http://novi-ua.org/people', 'people', '', 0);
INSERT INTO user_access VALUES (5, 1310558230, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows; U; Windows NT 6.1; ru; rv:1.9.2.17) Gecko/20110420 Firefox/3.6.17 GTB7.1 ( .NET CLR 3.5.30729; .NET4.0E) WebMoney Advisor', 'http://novi-ua.org/people?page=2', 'profile', 'index', 16);
INSERT INTO user_access VALUES (5, 1310574820, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows; U; Windows NT 6.1; ru; rv:1.9.2.17) Gecko/20110420 Firefox/3.6.17 GTB7.1 ( .NET CLR 3.5.30729; .NET4.0E) WebMoney Advisor', 'http://novi-ua.org/profile-16', 'home', '', 0);
INSERT INTO user_access VALUES (5, 1310574822, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows; U; Windows NT 6.1; ru; rv:1.9.2.17) Gecko/20110420 Firefox/3.6.17 GTB7.1 ( .NET CLR 3.5.30729; .NET4.0E) WebMoney Advisor', 'http://novi-ua.org/home', 'people', '', 0);
INSERT INTO user_access VALUES (5, 1310574825, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows; U; Windows NT 6.1; ru; rv:1.9.2.17) Gecko/20110420 Firefox/3.6.17 GTB7.1 ( .NET CLR 3.5.30729; .NET4.0E) WebMoney Advisor', 'http://novi-ua.org/people', 'people', '', 0);
INSERT INTO user_access VALUES (5, 1310574828, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows; U; Windows NT 6.1; ru; rv:1.9.2.17) Gecko/20110420 Firefox/3.6.17 GTB7.1 ( .NET CLR 3.5.30729; .NET4.0E) WebMoney Advisor', 'http://novi-ua.org/people?page=2', 'profile', 'index', 16);
INSERT INTO user_access VALUES (5, 1310574836, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows; U; Windows NT 6.1; ru; rv:1.9.2.17) Gecko/20110420 Firefox/3.6.17 GTB7.1 ( .NET CLR 3.5.30729; .NET4.0E) WebMoney Advisor', 'http://novi-ua.org/profile-16', 'home', '', 0);
INSERT INTO user_access VALUES (5, 1310577975, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows; U; Windows NT 6.1; ru; rv:1.9.2.17) Gecko/20110420 Firefox/3.6.17 GTB7.1 ( .NET CLR 3.5.30729; .NET4.0E) WebMoney Advisor', 'http://novi-ua.org/home', 'people', '', 0);
INSERT INTO user_access VALUES (5, 1310578183, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows; U; Windows NT 6.1; ru; rv:1.9.2.17) Gecko/20110420 Firefox/3.6.17 GTB7.1 ( .NET CLR 3.5.30729; .NET4.0E) WebMoney Advisor', 'http://novi-ua.org/people', 'home', '', 0);
INSERT INTO user_access VALUES (2, 1310592408, '::ffff:31.40.219.175', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; ru; rv:1.9.0.6) Gecko/2010031422 Firefox/3.0.19 (.NET CLR 3.5.30729)', '', 'home', '', 0);
INSERT INTO user_access VALUES (2, 1310592422, '::ffff:31.40.219.175', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; ru; rv:1.9.0.6) Gecko/2010031422 Firefox/3.0.19 (.NET CLR 3.5.30729)', 'http://novi-ua.org/home', 'people', '', 0);
INSERT INTO user_access VALUES (2, 1310592432, '::ffff:31.40.219.175', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; ru; rv:1.9.0.6) Gecko/2010031422 Firefox/3.0.19 (.NET CLR 3.5.30729)', 'http://novi-ua.org/people', 'profile', 'index', 12);
INSERT INTO user_access VALUES (2, 1310592492, '::ffff:31.40.219.175', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; ru; rv:1.9.0.6) Gecko/2010031422 Firefox/3.0.19 (.NET CLR 3.5.30729)', 'http://novi-ua.org/profile-12', 'profile', 'edit', 12);
INSERT INTO user_access VALUES (2, 1310592550, '::ffff:31.40.219.175', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; ru; rv:1.9.0.6) Gecko/2010031422 Firefox/3.0.19 (.NET CLR 3.5.30729)', 'http://novi-ua.org/profile/edit?id=12', 'groups', '', 0);
INSERT INTO user_access VALUES (2, 1310592575, '::ffff:31.40.219.175', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; ru; rv:1.9.0.6) Gecko/2010031422 Firefox/3.0.19 (.NET CLR 3.5.30729)', 'http://novi-ua.org/groups', 'events', '', 0);
INSERT INTO user_access VALUES (2, 1310592579, '::ffff:31.40.219.175', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; ru; rv:1.9.0.6) Gecko/2010031422 Firefox/3.0.19 (.NET CLR 3.5.30729)', 'http://novi-ua.org/events', 'polls', '', 0);
INSERT INTO user_access VALUES (2, 1310592582, '::ffff:31.40.219.175', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; ru; rv:1.9.0.6) Gecko/2010031422 Firefox/3.0.19 (.NET CLR 3.5.30729)', 'http://novi-ua.org/polls', 'library', '', 0);
INSERT INTO user_access VALUES (2, 1310592588, '::ffff:31.40.219.175', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; ru; rv:1.9.0.6) Gecko/2010031422 Firefox/3.0.19 (.NET CLR 3.5.30729)', 'http://novi-ua.org/library', 'search', '', 0);
INSERT INTO user_access VALUES (2, 1310592591, '::ffff:31.40.219.175', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; ru; rv:1.9.0.6) Gecko/2010031422 Firefox/3.0.19 (.NET CLR 3.5.30729)', 'http://novi-ua.org/search', 'library', '', 0);
INSERT INTO user_access VALUES (2, 1310592595, '::ffff:31.40.219.175', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; ru; rv:1.9.0.6) Gecko/2010031422 Firefox/3.0.19 (.NET CLR 3.5.30729)', 'http://novi-ua.org/library', 'people', '', 0);
INSERT INTO user_access VALUES (2, 1310592611, '::ffff:31.40.219.175', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; ru; rv:1.9.0.6) Gecko/2010031422 Firefox/3.0.19 (.NET CLR 3.5.30729)', 'http://novi-ua.org/people', 'people', '', 0);
INSERT INTO user_access VALUES (2, 1310592622, '::ffff:31.40.219.175', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; ru; rv:1.9.0.6) Gecko/2010031422 Firefox/3.0.19 (.NET CLR 3.5.30729)', 'http://novi-ua.org/people?page=2', 'invites', '', 0);
INSERT INTO user_access VALUES (2, 1310592628, '::ffff:31.40.219.175', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; ru; rv:1.9.0.6) Gecko/2010031422 Firefox/3.0.19 (.NET CLR 3.5.30729)', 'http://novi-ua.org/invites', 'events', '', 0);
INSERT INTO user_access VALUES (2, 1310592632, '::ffff:31.40.219.175', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; ru; rv:1.9.0.6) Gecko/2010031422 Firefox/3.0.19 (.NET CLR 3.5.30729)', 'http://novi-ua.org/invites', 'groups', '', 0);
INSERT INTO user_access VALUES (2, 1310592634, '::ffff:31.40.219.175', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; ru; rv:1.9.0.6) Gecko/2010031422 Firefox/3.0.19 (.NET CLR 3.5.30729)', 'http://novi-ua.org/groups', 'groups', 'view', 2);
INSERT INTO user_access VALUES (2, 1310592657, '::ffff:31.40.219.175', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; ru; rv:1.9.0.6) Gecko/2010031422 Firefox/3.0.19 (.NET CLR 3.5.30729)', 'http://novi-ua.org/group2', 'invites', '', 0);
INSERT INTO user_access VALUES (2, 1310592662, '::ffff:31.40.219.175', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; ru; rv:1.9.0.6) Gecko/2010031422 Firefox/3.0.19 (.NET CLR 3.5.30729)', 'http://novi-ua.org/invites', 'invites', 'edit', 4);
INSERT INTO user_access VALUES (2, 1310592666, '::ffff:31.40.219.175', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; ru; rv:1.9.0.6) Gecko/2010031422 Firefox/3.0.19 (.NET CLR 3.5.30729)', 'http://novi-ua.org/invites', 'groups', '', 0);
INSERT INTO user_access VALUES (2, 1310592669, '::ffff:31.40.219.175', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; ru; rv:1.9.0.6) Gecko/2010031422 Firefox/3.0.19 (.NET CLR 3.5.30729)', 'http://novi-ua.org/groups', 'groups', 'view', 2);
INSERT INTO user_access VALUES (5, 1310637849, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows; U; Windows NT 6.1; ru; rv:1.9.2.17) Gecko/20110420 Firefox/3.6.17 GTB7.1 ( .NET CLR 3.5.30729; .NET4.0E) WebMoney Advisor', '', 'profile', '', 0);
INSERT INTO user_access VALUES (2, 1310643587, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; ru; rv:1.9.2.14) Gecko/20110218 Firefox/3.6.14 ( .NET CLR 3.5.30729; .NET4.0E)', 'http://novi-ua.org/group2', 'profile', 'index', 5);
INSERT INTO user_access VALUES (2, 1310729753, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; ru; rv:1.9.2.14) Gecko/20110218 Firefox/3.6.14 ( .NET CLR 3.5.30729; .NET4.0E)', 'http://novi-ua.org/group2', 'profile', 'index', 5);
INSERT INTO user_access VALUES (2, 1310968845, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; ru; rv:1.9.2.14) Gecko/20110218 Firefox/3.6.14 ( .NET CLR 3.5.30729; .NET4.0E)', 'http://novi-ua.org/group2', 'profile', 'index', 5);
INSERT INTO user_access VALUES (1360, 1310972001, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.19) Gecko/20110707 Firefox/3.6.19', '', 'home', '', 0);
INSERT INTO user_access VALUES (1360, 1310972006, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.19) Gecko/20110707 Firefox/3.6.19', 'http://novi-ua.org/home', 'groups', '', 0);
INSERT INTO user_access VALUES (1360, 1310972009, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.19) Gecko/20110707 Firefox/3.6.19', 'http://novi-ua.org/groups', 'groups', 'view', 2);
INSERT INTO user_access VALUES (1360, 1311075309, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.19) Gecko/20110707 Firefox/3.6.19', '', 'home', '', 0);
INSERT INTO user_access VALUES (1360, 1311075315, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.19) Gecko/20110707 Firefox/3.6.19', 'http://novi-ua.org/home', 'profile', 'index', 1360);
INSERT INTO user_access VALUES (1360, 1311075318, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.19) Gecko/20110707 Firefox/3.6.19', 'http://novi-ua.org/profile-1360', 'people', '', 0);
INSERT INTO user_access VALUES (1360, 1311075324, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.19) Gecko/20110707 Firefox/3.6.19', 'http://novi-ua.org/people', 'people', '', 0);
INSERT INTO user_access VALUES (1360, 1311075328, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.19) Gecko/20110707 Firefox/3.6.19', 'http://novi-ua.org/people?page=2', 'people', '', 0);
INSERT INTO user_access VALUES (1360, 1311075333, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.19) Gecko/20110707 Firefox/3.6.19', 'http://novi-ua.org/people?page=1', 'profile', 'index', 11);
INSERT INTO user_access VALUES (1360, 1311075336, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.19) Gecko/20110707 Firefox/3.6.19', 'http://novi-ua.org/profile-11', 'profile', 'edit', 11);
INSERT INTO user_access VALUES (1360, 1311075343, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.19) Gecko/20110707 Firefox/3.6.19', 'http://novi-ua.org/profile/edit?id=11', 'profile', 'index', 11);
INSERT INTO user_access VALUES (1360, 1311075498, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.19) Gecko/20110707 Firefox/3.6.19', 'http://novi-ua.org/profile/edit?id=11', 'profile', 'index', 11);
INSERT INTO user_access VALUES (1360, 1311141526, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.19) Gecko/20110707 Firefox/3.6.19', '', 'home', '', 0);
INSERT INTO user_access VALUES (1360, 1311141531, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.19) Gecko/20110707 Firefox/3.6.19', '', 'groups', 'view', 2);
INSERT INTO user_access VALUES (1360, 1311141541, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.19) Gecko/20110707 Firefox/3.6.19', 'http://novi-ua.org/group2', 'groups', 'edit', 2);
INSERT INTO user_access VALUES (1360, 1311141577, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.19) Gecko/20110707 Firefox/3.6.19', 'http://novi-ua.org/groups/edit?id=2', 'groups', '', 0);
INSERT INTO user_access VALUES (1360, 1311141581, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.19) Gecko/20110707 Firefox/3.6.19', 'http://novi-ua.org/groups', 'groups', 'view', 1);
INSERT INTO user_access VALUES (1360, 1311141669, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.19) Gecko/20110707 Firefox/3.6.19', 'http://novi-ua.org/group1', 'profile', 'index', 1360);
INSERT INTO user_access VALUES (1360, 1311141673, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.19) Gecko/20110707 Firefox/3.6.19', 'http://novi-ua.org/profile-1360', 'profile', 'edit', 1360);
INSERT INTO user_access VALUES (1360, 1311141691, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.19) Gecko/20110707 Firefox/3.6.19', 'http://novi-ua.org/profile/edit?id=1360', 'admin', '', 0);
INSERT INTO user_access VALUES (1360, 1311141694, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.19) Gecko/20110707 Firefox/3.6.19', 'http://novi-ua.org/admin', 'admin', 'users', 0);
INSERT INTO user_access VALUES (1360, 1311141697, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.19) Gecko/20110707 Firefox/3.6.19', 'http://novi-ua.org/admin/users', 'admin', 'users_list', 0);
INSERT INTO user_access VALUES (1360, 1311141701, '::ffff:194.187.108.86', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ru; rv:1.9.2.19) Gecko/20110707 Firefox/3.6.19', 'http://novi-ua.org/admin/users_list', 'admin', 'users', 0);
INSERT INTO user_access VALUES (2, 1311145565, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; ru; rv:1.9.2.14) Gecko/20110218 Firefox/3.6.14 ( .NET CLR 3.5.30729; .NET4.0E)', 'http://novi-ua.org/group2', 'profile', 'index', 5);
INSERT INTO user_access VALUES (2, 1311230886, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; ru; rv:1.9.2.14) Gecko/20110218 Firefox/3.6.14 ( .NET CLR 3.5.30729; .NET4.0E)', 'http://novi-ua.org/group2', 'profile', 'index', 5);
INSERT INTO user_access VALUES (2, 1311231808, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; ru; rv:1.9.2.14) Gecko/20110218 Firefox/3.6.14 ( .NET CLR 3.5.30729; .NET4.0E)', 'http://novi-ua.org/profile-5', 'home', '', 0);
INSERT INTO user_access VALUES (2, 1311231895, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; ru; rv:1.9.2.14) Gecko/20110218 Firefox/3.6.14 ( .NET CLR 3.5.30729; .NET4.0E)', 'http://novi-ua.org/home', 'people', '', 0);
INSERT INTO user_access VALUES (2, 1311234093, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; ru; rv:1.9.2.14) Gecko/20110218 Firefox/3.6.14 ( .NET CLR 3.5.30729; .NET4.0E)', 'http://novi-ua.org/people', 'blogs', '', 0);
INSERT INTO user_access VALUES (2, 1311234101, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; ru; rv:1.9.2.14) Gecko/20110218 Firefox/3.6.14 ( .NET CLR 3.5.30729; .NET4.0E)', 'http://novi-ua.org/blogs', 'blogs', 'edit', 0);
INSERT INTO user_access VALUES (2, 1311234111, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; ru; rv:1.9.2.14) Gecko/20110218 Firefox/3.6.14 ( .NET CLR 3.5.30729; .NET4.0E)', 'http://novi-ua.org/people', 'blogs', '', 0);
INSERT INTO user_access VALUES (2, 1311234145, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; ru; rv:1.9.2.14) Gecko/20110218 Firefox/3.6.14 ( .NET CLR 3.5.30729; .NET4.0E)', 'http://novi-ua.org/blogs', 'groups', '', 0);
INSERT INTO user_access VALUES (2, 1311234192, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; ru; rv:1.9.2.14) Gecko/20110218 Firefox/3.6.14 ( .NET CLR 3.5.30729; .NET4.0E)', 'http://novi-ua.org/groups', 'events', '', 0);
INSERT INTO user_access VALUES (2, 1311234227, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; ru; rv:1.9.2.14) Gecko/20110218 Firefox/3.6.14 ( .NET CLR 3.5.30729; .NET4.0E)', 'http://novi-ua.org/events', 'events', 'search', 0);
INSERT INTO user_access VALUES (2, 1311234244, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; ru; rv:1.9.2.14) Gecko/20110218 Firefox/3.6.14 ( .NET CLR 3.5.30729; .NET4.0E)', 'http://novi-ua.org/events/search', 'events', 'create', 0);
INSERT INTO user_access VALUES (2, 1311234273, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; ru; rv:1.9.2.14) Gecko/20110218 Firefox/3.6.14 ( .NET CLR 3.5.30729; .NET4.0E)', 'http://novi-ua.org/events/create', 'polls', '', 0);
INSERT INTO user_access VALUES (2, 1311234276, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; ru; rv:1.9.2.14) Gecko/20110218 Firefox/3.6.14 ( .NET CLR 3.5.30729; .NET4.0E)', 'http://novi-ua.org/polls', 'polls', 'create', 0);
INSERT INTO user_access VALUES (2, 1311234282, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; ru; rv:1.9.2.14) Gecko/20110218 Firefox/3.6.14 ( .NET CLR 3.5.30729; .NET4.0E)', 'http://novi-ua.org/polls/create', 'library', '', 0);
INSERT INTO user_access VALUES (2, 1311234308, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; ru; rv:1.9.2.14) Gecko/20110218 Firefox/3.6.14 ( .NET CLR 3.5.30729; .NET4.0E)', 'http://novi-ua.org/library', 'search', '', 0);
INSERT INTO user_access VALUES (2, 1311234312, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; ru; rv:1.9.2.14) Gecko/20110218 Firefox/3.6.14 ( .NET CLR 3.5.30729; .NET4.0E)', 'http://novi-ua.org/search', 'library', '', 0);
INSERT INTO user_access VALUES (2, 1311234323, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; ru; rv:1.9.2.14) Gecko/20110218 Firefox/3.6.14 ( .NET CLR 3.5.30729; .NET4.0E)', 'http://novi-ua.org/library', 'search', '', 0);
INSERT INTO user_access VALUES (2, 1311234382, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; ru; rv:1.9.2.14) Gecko/20110218 Firefox/3.6.14 ( .NET CLR 3.5.30729; .NET4.0E)', 'http://novi-ua.org/search', 'home', '', 0);
INSERT INTO user_access VALUES (2, 1311234393, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; ru; rv:1.9.2.14) Gecko/20110218 Firefox/3.6.14 ( .NET CLR 3.5.30729; .NET4.0E)', 'http://novi-ua.org/home', 'profile', 'edit', 0);
INSERT INTO user_access VALUES (2, 1311234615, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; ru; rv:1.9.2.14) Gecko/20110218 Firefox/3.6.14 ( .NET CLR 3.5.30729; .NET4.0E)', 'http://novi-ua.org/profile/edit?tab=settings', 'groups', '', 0);
INSERT INTO user_access VALUES (2, 1311234617, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; ru; rv:1.9.2.14) Gecko/20110218 Firefox/3.6.14 ( .NET CLR 3.5.30729; .NET4.0E)', 'http://novi-ua.org/groups', 'groups', 'create', 0);
INSERT INTO user_access VALUES (2, 1311234651, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; ru; rv:1.9.2.14) Gecko/20110218 Firefox/3.6.14 ( .NET CLR 3.5.30729; .NET4.0E)', 'http://novi-ua.org/groups/create', 'profile', 'index', 2);
INSERT INTO user_access VALUES (2, 1311234670, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; ru; rv:1.9.2.14) Gecko/20110218 Firefox/3.6.14 ( .NET CLR 3.5.30729; .NET4.0E)', 'http://novi-ua.org/profile-2', 'profile', 'branding', 0);
INSERT INTO user_access VALUES (2, 1311234686, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; ru; rv:1.9.2.14) Gecko/20110218 Firefox/3.6.14 ( .NET CLR 3.5.30729; .NET4.0E)', 'http://novi-ua.org/profile/branding', 'profile', 'branding', 0);
INSERT INTO user_access VALUES (2, 1311234829, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; ru; rv:1.9.2.14) Gecko/20110218 Firefox/3.6.14 ( .NET CLR 3.5.30729; .NET4.0E)', 'http://novi-ua.org/profile/branding', 'profile', 'branding', 0);
INSERT INTO user_access VALUES (2, 1311234832, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; ru; rv:1.9.2.14) Gecko/20110218 Firefox/3.6.14 ( .NET CLR 3.5.30729; .NET4.0E)', 'http://novi-ua.org/profile/branding', 'profile', 'index', 2);
INSERT INTO user_access VALUES (2, 1311234835, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; ru; rv:1.9.2.14) Gecko/20110218 Firefox/3.6.14 ( .NET CLR 3.5.30729; .NET4.0E)', 'http://novi-ua.org/profile-2', 'profile', 'invite', 0);
INSERT INTO user_access VALUES (2, 1311234849, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; ru; rv:1.9.2.14) Gecko/20110218 Firefox/3.6.14 ( .NET CLR 3.5.30729; .NET4.0E)', 'http://novi-ua.org/profile/invite', 'bookmarks', '', 0);
INSERT INTO user_access VALUES (2, 1311234862, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; ru; rv:1.9.2.14) Gecko/20110218 Firefox/3.6.14 ( .NET CLR 3.5.30729; .NET4.0E)', 'http://novi-ua.org/bookmarks', 'profile', 'index', 2);
INSERT INTO user_access VALUES (2, 1311234867, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; ru; rv:1.9.2.14) Gecko/20110218 Firefox/3.6.14 ( .NET CLR 3.5.30729; .NET4.0E)', 'http://novi-ua.org/profile-2', 'admin', 'users', 0);
INSERT INTO user_access VALUES (2, 1311235107, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; ru; rv:1.9.2.14) Gecko/20110218 Firefox/3.6.14 ( .NET CLR 3.5.30729; .NET4.0E)', 'http://novi-ua.org/admin/users?key=2', 'profile', 'index', 2);
INSERT INTO user_access VALUES (2, 1311235109, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; ru; rv:1.9.2.14) Gecko/20110218 Firefox/3.6.14 ( .NET CLR 3.5.30729; .NET4.0E)', 'http://novi-ua.org/profile-2', 'profile', 'edit', 2);
INSERT INTO user_access VALUES (2, 1311235477, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; ru; rv:1.9.2.14) Gecko/20110218 Firefox/3.6.14 ( .NET CLR 3.5.30729; .NET4.0E)', 'http://novi-ua.org/profile/edit?id=2', 'blogs', '', 0);
INSERT INTO user_access VALUES (2, 1311235480, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; ru; rv:1.9.2.14) Gecko/20110218 Firefox/3.6.14 ( .NET CLR 3.5.30729; .NET4.0E)', 'http://novi-ua.org/blogs', 'blogs', 'comments', 0);
INSERT INTO user_access VALUES (2, 1311253393, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; ru; rv:1.9.2.14) Gecko/20110218 Firefox/3.6.14 ( .NET CLR 3.5.30729; .NET4.0E)', 'http://novi-ua.org/comments', 'home', '', 0);
INSERT INTO user_access VALUES (2, 1311253397, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; ru; rv:1.9.2.14) Gecko/20110218 Firefox/3.6.14 ( .NET CLR 3.5.30729; .NET4.0E)', 'http://novi-ua.org/home', 'profile', 'index', 2);
INSERT INTO user_access VALUES (2, 1311253419, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; ru; rv:1.9.2.14) Gecko/20110218 Firefox/3.6.14 ( .NET CLR 3.5.30729; .NET4.0E)', 'http://novi-ua.org/profile-2', 'profile', 'edit', 2);
INSERT INTO user_access VALUES (2, 1311253771, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; ru; rv:1.9.2.18) Gecko/20110614 Firefox/3.6.18 (.NET CLR 3.5.30729)', '', 'home', '', 0);
INSERT INTO user_access VALUES (2, 1311253785, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; ru; rv:1.9.2.18) Gecko/20110614 Firefox/3.6.18 (.NET CLR 3.5.30729)', 'http://novi-ua.org/home', 'admin', '', 0);
INSERT INTO user_access VALUES (2, 1311253790, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; ru; rv:1.9.2.18) Gecko/20110614 Firefox/3.6.18 (.NET CLR 3.5.30729)', 'http://novi-ua.org/admin', 'admin', 'users', 0);
INSERT INTO user_access VALUES (2, 1311253792, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; ru; rv:1.9.2.18) Gecko/20110614 Firefox/3.6.18 (.NET CLR 3.5.30729)', 'http://novi-ua.org/admin/users', 'admin', 'users_create', 0);
INSERT INTO user_access VALUES (2, 1311253825, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; ru; rv:1.9.2.18) Gecko/20110614 Firefox/3.6.18 (.NET CLR 3.5.30729)', 'http://novi-ua.org/admin/users_create', 'profile', 'index', 21);
INSERT INTO user_access VALUES (21, 1311253835, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows NT 5.1; rv:5.0) Gecko/20100101 Firefox/5.0', '', 'home', '', 0);
INSERT INTO user_access VALUES (2, 1311255424, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; ru; rv:1.9.2.14) Gecko/20110218 Firefox/3.6.14 ( .NET CLR 3.5.30729; .NET4.0E)', 'http://novi-ua.org/profile/edit?id=2', 'profile', 'index', 2);
INSERT INTO user_access VALUES (2, 1311255438, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; ru; rv:1.9.2.14) Gecko/20110218 Firefox/3.6.14 ( .NET CLR 3.5.30729; .NET4.0E)', 'http://novi-ua.org/profile-2', 'search', '', 0);
INSERT INTO user_access VALUES (2, 1311255553, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; ru; rv:1.9.2.14) Gecko/20110218 Firefox/3.6.14 ( .NET CLR 3.5.30729; .NET4.0E)', 'http://novi-ua.org/profile-2', 'profile', 'edit', 2);
INSERT INTO user_access VALUES (5, 1311257531, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows; U; Windows NT 6.1; ru; rv:1.9.2.17) Gecko/20110420 Firefox/3.6.17 GTB7.1 ( .NET CLR 3.5.30729; .NET4.0E) WebMoney Advisor', '', 'profile', '', 0);
INSERT INTO user_access VALUES (5, 1311257537, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows; U; Windows NT 6.1; ru; rv:1.9.2.17) Gecko/20110420 Firefox/3.6.17 GTB7.1 ( .NET CLR 3.5.30729; .NET4.0E) WebMoney Advisor', '', 'profile', '', 0);
INSERT INTO user_access VALUES (5, 1311257553, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows; U; Windows NT 6.1; ru; rv:1.9.2.17) Gecko/20110420 Firefox/3.6.17 GTB7.1 ( .NET CLR 3.5.30729; .NET4.0E) WebMoney Advisor', 'http://novi-ua.org/profile', 'profile', 'edit', 5);
INSERT INTO user_access VALUES (29, 1311326309, '::ffff:194.187.108.86', 'Mozilla/5.0 (X11; U; Linux x86_64; uk; rv:1.9.2.17) Gecko/20110422 Ubuntu/10.04 (lucid) Firefox/3.6.17', '', 'home', '', 0);
INSERT INTO user_access VALUES (2, 1311332769, '::ffff:31.40.221.72', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; ru; rv:1.9.2.13) Gecko/20101203 Firefox/3.6.13 (.NET CLR 3.5.30729)', '', 'groups', 'view', 2);
INSERT INTO user_access VALUES (2, 1311333588, '::ffff:31.40.221.72', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; ru; rv:1.9.2.13) Gecko/20101203 Firefox/3.6.13 (.NET CLR 3.5.30729)', 'http://novi-ua.org/group2', 'home', '', 0);
INSERT INTO user_access VALUES (5, 1311512483, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows; U; Windows NT 6.1; ru; rv:1.9.2.17) Gecko/20110420 Firefox/3.6.17 GTB7.1 ( .NET CLR 3.5.30729; .NET4.0E) WebMoney Advisor', '', 'profile', '', 0);
INSERT INTO user_access VALUES (5, 1311512497, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows; U; Windows NT 6.1; ru; rv:1.9.2.17) Gecko/20110420 Firefox/3.6.17 GTB7.1 ( .NET CLR 3.5.30729; .NET4.0E) WebMoney Advisor', 'http://novi-ua.org/profile', 'profile', 'edit', 5);
INSERT INTO user_access VALUES (5, 1311512571, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows; U; Windows NT 6.1; ru; rv:1.9.2.17) Gecko/20110420 Firefox/3.6.17 GTB7.1 ( .NET CLR 3.5.30729; .NET4.0E) WebMoney Advisor', 'http://novi-ua.org/profile/edit?id=5', 'people', '', 0);
INSERT INTO user_access VALUES (5, 1311512584, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows; U; Windows NT 6.1; ru; rv:1.9.2.17) Gecko/20110420 Firefox/3.6.17 GTB7.1 ( .NET CLR 3.5.30729; .NET4.0E) WebMoney Advisor', 'http://novi-ua.org/people', 'profile', 'index', 5);
INSERT INTO user_access VALUES (5, 1311512622, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows; U; Windows NT 6.1; ru; rv:1.9.2.17) Gecko/20110420 Firefox/3.6.17 GTB7.1 ( .NET CLR 3.5.30729; .NET4.0E) WebMoney Advisor', 'http://novi-ua.org/profile-5', 'groups', 'view', 2);
INSERT INTO user_access VALUES (5, 1311512626, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows; U; Windows NT 6.1; ru; rv:1.9.2.17) Gecko/20110420 Firefox/3.6.17 GTB7.1 ( .NET CLR 3.5.30729; .NET4.0E) WebMoney Advisor', 'http://novi-ua.org/group2', 'groups', 'edit', 2);
INSERT INTO user_access VALUES (5, 1311512635, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows; U; Windows NT 6.1; ru; rv:1.9.2.17) Gecko/20110420 Firefox/3.6.17 GTB7.1 ( .NET CLR 3.5.30729; .NET4.0E) WebMoney Advisor', 'http://novi-ua.org/groups/edit?id=2', 'people', '', 0);
INSERT INTO user_access VALUES (5, 1311512639, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows; U; Windows NT 6.1; ru; rv:1.9.2.17) Gecko/20110420 Firefox/3.6.17 GTB7.1 ( .NET CLR 3.5.30729; .NET4.0E) WebMoney Advisor', 'http://novi-ua.org/people', 'people', '', 0);
INSERT INTO user_access VALUES (5, 1311512642, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows; U; Windows NT 6.1; ru; rv:1.9.2.17) Gecko/20110420 Firefox/3.6.17 GTB7.1 ( .NET CLR 3.5.30729; .NET4.0E) WebMoney Advisor', 'http://novi-ua.org/people?page=2', 'profile', 'index', 16);
INSERT INTO user_access VALUES (5, 1311512650, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows; U; Windows NT 6.1; ru; rv:1.9.2.17) Gecko/20110420 Firefox/3.6.17 GTB7.1 ( .NET CLR 3.5.30729; .NET4.0E) WebMoney Advisor', 'http://novi-ua.org/profile-16', 'home', '', 0);
INSERT INTO user_access VALUES (2, 1311576096, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; ru; rv:1.9.2.14) Gecko/20110218 Firefox/3.6.14 ( .NET CLR 3.5.30729; .NET4.0E)', 'http://novi-ua.org/profile-2', 'profile', 'edit', 2);
INSERT INTO user_access VALUES (5, 1311587442, '::ffff:193.107.225.65', 'Mozilla/5.0 (X11; Linux i686; rv:5.0) Gecko/20100101 Firefox/5.0', '', 'home', '', 0);
INSERT INTO user_access VALUES (5, 1311587449, '::ffff:193.107.225.65', 'Mozilla/5.0 (X11; Linux i686; rv:5.0) Gecko/20100101 Firefox/5.0', 'http://novi-ua.org/home', 'profile', 'index', 5);
INSERT INTO user_access VALUES (5, 1311587451, '::ffff:193.107.225.65', 'Mozilla/5.0 (X11; Linux i686; rv:5.0) Gecko/20100101 Firefox/5.0', 'http://novi-ua.org/profile-5', 'profile', 'edit', 5);
INSERT INTO user_access VALUES (5, 1311587999, '::ffff:193.107.225.65', 'Mozilla/5.0 (X11; Linux i686; rv:5.0) Gecko/20100101 Firefox/5.0', 'http://novi-ua.org/profile/edit?id=5', 'search', '', 0);
INSERT INTO user_access VALUES (5, 1311590131, '::ffff:193.107.225.65', 'Mozilla/5.0 (X11; Linux i686; rv:5.0) Gecko/20100101 Firefox/5.0', 'http://novi-ua.org/profile/edit?id=5', 'search', '', 0);
INSERT INTO user_access VALUES (5, 1311601710, '::ffff:193.107.225.65', 'Mozilla/5.0 (X11; Linux i686; rv:5.0) Gecko/20100101 Firefox/5.0', 'http://novi-ua.org/search', 'home', '', 0);
INSERT INTO user_access VALUES (5, 1311601715, '::ffff:193.107.225.65', 'Mozilla/5.0 (X11; Linux i686; rv:5.0) Gecko/20100101 Firefox/5.0', 'http://novi-ua.org/search', 'home', '', 0);
INSERT INTO user_access VALUES (5, 1311601728, '::ffff:193.107.225.65', 'Mozilla/5.0 (X11; Linux i686; rv:5.0) Gecko/20100101 Firefox/5.0', 'http://novi-ua.org/home', 'people', '', 0);
INSERT INTO user_access VALUES (5, 1311601734, '::ffff:193.107.225.65', 'Mozilla/5.0 (X11; Linux i686; rv:5.0) Gecko/20100101 Firefox/5.0', 'http://novi-ua.org/people', 'home', '', 0);
INSERT INTO user_access VALUES (5, 1311601737, '::ffff:193.107.225.65', 'Mozilla/5.0 (X11; Linux i686; rv:5.0) Gecko/20100101 Firefox/5.0', 'http://novi-ua.org/home', 'blogs', '', 0);
INSERT INTO user_access VALUES (5, 1311601743, '::ffff:193.107.225.65', 'Mozilla/5.0 (X11; Linux i686; rv:5.0) Gecko/20100101 Firefox/5.0', 'http://novi-ua.org/blogs', 'blogs', 'user', 0);
INSERT INTO user_access VALUES (5, 1311601748, '::ffff:193.107.225.65', 'Mozilla/5.0 (X11; Linux i686; rv:5.0) Gecko/20100101 Firefox/5.0', 'http://novi-ua.org/blog-5', 'polls', '', 0);
INSERT INTO user_access VALUES (5, 1311601750, '::ffff:193.107.225.65', 'Mozilla/5.0 (X11; Linux i686; rv:5.0) Gecko/20100101 Firefox/5.0', 'http://novi-ua.org/polls', 'library', '', 0);
INSERT INTO user_access VALUES (5, 1311601751, '::ffff:193.107.225.65', 'Mozilla/5.0 (X11; Linux i686; rv:5.0) Gecko/20100101 Firefox/5.0', 'http://novi-ua.org/library', 'search', '', 0);
INSERT INTO user_access VALUES (5, 1311601755, '::ffff:193.107.225.65', 'Mozilla/5.0 (X11; Linux i686; rv:5.0) Gecko/20100101 Firefox/5.0', 'http://novi-ua.org/search', 'profile', 'index', 5);
INSERT INTO user_access VALUES (5, 1311601773, '::ffff:193.107.225.65', 'Mozilla/5.0 (X11; Linux i686; rv:5.0) Gecko/20100101 Firefox/5.0', 'http://novi-ua.org/profile-5', 'profile', 'invite', 0);
INSERT INTO user_access VALUES (5, 1311608678, '::ffff:193.107.225.65', 'Mozilla/5.0 (X11; Linux i686; rv:5.0) Gecko/20100101 Firefox/5.0', 'http://novi-ua.org/profile/invite', 'profile', 'index', 5);
INSERT INTO user_access VALUES (5, 1311608681, '::ffff:193.107.225.65', 'Mozilla/5.0 (X11; Linux i686; rv:5.0) Gecko/20100101 Firefox/5.0', 'http://novi-ua.org/profile-5', 'profile', 'edit', 5);
INSERT INTO user_access VALUES (5, 1311608687, '::ffff:193.107.225.65', 'Mozilla/5.0 (X11; Linux i686; rv:5.0) Gecko/20100101 Firefox/5.0', 'http://novi-ua.org/profile-5', 'profile', 'edit', 5);
INSERT INTO user_access VALUES (2, 1311625229, '::ffff:193.107.184.190', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; ru; rv:1.9.2.18) Gecko/20110614 Firefox/3.6.18 (.NET CLR 3.5.30729)', 'http://novi-ua.org/group2', 'home', '', 0);
INSERT INTO user_access VALUES (5, 1311662081, '::ffff:194.187.108.86', 'Mozilla/5.0 (X11; Linux i686; rv:5.0) Gecko/20100101 Firefox/5.0', '', 'home', '', 0);
INSERT INTO user_access VALUES (5, 1311662087, '::ffff:194.187.108.86', 'Mozilla/5.0 (X11; Linux i686; rv:5.0) Gecko/20100101 Firefox/5.0', '', 'home', '', 0);
INSERT INTO user_access VALUES (5, 1311662137, '::ffff:194.187.108.86', 'Mozilla/5.0 (X11; Linux i686; rv:5.0) Gecko/20100101 Firefox/5.0', '', 'home', '', 0);
INSERT INTO user_access VALUES (5, 1311662151, '::ffff:194.187.108.86', 'Mozilla/5.0 (X11; Linux i686; rv:5.0) Gecko/20100101 Firefox/5.0', 'http://novi-ua.org/home', 'people', '', 0);
INSERT INTO user_access VALUES (5, 1311662160, '::ffff:194.187.108.86', 'Mozilla/5.0 (X11; Linux i686; rv:5.0) Gecko/20100101 Firefox/5.0', 'http://novi-ua.org/people', 'people', '', 0);
INSERT INTO user_access VALUES (5, 1311662168, '::ffff:194.187.108.86', 'Mozilla/5.0 (X11; Linux i686; rv:5.0) Gecko/20100101 Firefox/5.0', 'http://novi-ua.org/people?page=2', 'people', '', 0);
INSERT INTO user_access VALUES (5, 1311662812, '::ffff:194.187.108.86', 'Mozilla/5.0 (X11; Linux i686; rv:5.0) Gecko/20100101 Firefox/5.0', 'http://novi-ua.org/people?page=1', 'home', '', 0);
INSERT INTO user_access VALUES (2, 1311674271, '::ffff:193.107.184.190', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; ru; rv:1.9.2.18) Gecko/20110614 Firefox/3.6.18 (.NET CLR 3.5.30729)', '', 'home', '', 0);
INSERT INTO user_access VALUES (2, 1311674285, '::ffff:193.107.184.190', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; ru; rv:1.9.2.18) Gecko/20110614 Firefox/3.6.18 (.NET CLR 3.5.30729)', 'http://novi-ua.org/home', 'profile', 'index', 2);
INSERT INTO user_access VALUES (5, 1311675137, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows; U; Windows NT 6.1; ru; rv:1.9.2.17) Gecko/20110420 Firefox/3.6.17 GTB7.1 ( .NET CLR 3.5.30729; .NET4.0E) WebMoney Advisor', '', 'profile', '', 0);
INSERT INTO user_access VALUES (5, 1311684498, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows; U; Windows NT 6.1; ru; rv:1.9.2.17) Gecko/20110420 Firefox/3.6.17 GTB7.1 ( .NET CLR 3.5.30729; .NET4.0E) WebMoney Advisor', '', 'profile', '', 0);
INSERT INTO user_access VALUES (5, 1311684503, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows; U; Windows NT 6.1; ru; rv:1.9.2.17) Gecko/20110420 Firefox/3.6.17 GTB7.1 ( .NET CLR 3.5.30729; .NET4.0E) WebMoney Advisor', '', 'profile', '', 0);
INSERT INTO user_access VALUES (5, 1311684508, '::ffff:194.187.108.86', 'Mozilla/5.0 (X11; Linux i686; rv:5.0) Gecko/20100101 Firefox/5.0', 'http://novi-ua.org/people?page=1', 'home', '', 0);
INSERT INTO user_access VALUES (5, 1311684623, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows; U; Windows NT 6.1; ru; rv:1.9.2.17) Gecko/20110420 Firefox/3.6.17 GTB7.1 ( .NET CLR 3.5.30729; .NET4.0E) WebMoney Advisor', '', 'profile', '', 0);
INSERT INTO user_access VALUES (5, 1311684753, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows; U; Windows NT 6.1; ru; rv:1.9.2.17) Gecko/20110420 Firefox/3.6.17 GTB7.1 ( .NET CLR 3.5.30729; .NET4.0E) WebMoney Advisor', 'http://novi-ua.org/profile', 'home', '', 0);
INSERT INTO user_access VALUES (5, 1311684770, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows; U; Windows NT 6.1; ru; rv:1.9.2.17) Gecko/20110420 Firefox/3.6.17 GTB7.1 ( .NET CLR 3.5.30729; .NET4.0E) WebMoney Advisor', 'http://novi-ua.org/home', 'home', '', 0);
INSERT INTO user_access VALUES (5, 1311684780, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows; U; Windows NT 6.1; ru; rv:1.9.2.17) Gecko/20110420 Firefox/3.6.17 GTB7.1 ( .NET CLR 3.5.30729; .NET4.0E) WebMoney Advisor', 'http://novi-ua.org/home', 'blogs', 'edit', 0);
INSERT INTO user_access VALUES (5, 1311684782, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows; U; Windows NT 6.1; ru; rv:1.9.2.17) Gecko/20110420 Firefox/3.6.17 GTB7.1 ( .NET CLR 3.5.30729; .NET4.0E) WebMoney Advisor', 'http://novi-ua.org/blogs/edit?type=2', 'home', '', 0);
INSERT INTO user_access VALUES (5, 1311684791, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows; U; Windows NT 6.1; ru; rv:1.9.2.17) Gecko/20110420 Firefox/3.6.17 GTB7.1 ( .NET CLR 3.5.30729; .NET4.0E) WebMoney Advisor', 'http://novi-ua.org/home', 'people', '', 0);
INSERT INTO user_access VALUES (5, 1311684794, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows; U; Windows NT 6.1; ru; rv:1.9.2.17) Gecko/20110420 Firefox/3.6.17 GTB7.1 ( .NET CLR 3.5.30729; .NET4.0E) WebMoney Advisor', 'http://novi-ua.org/people', 'home', '', 0);
INSERT INTO user_access VALUES (21, 1311684959, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows NT 5.1; rv:5.0) Gecko/20100101 Firefox/5.0', '', 'home', '', 0);
INSERT INTO user_access VALUES (5, 1311685155, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows; U; Windows NT 6.1; ru; rv:1.9.2.17) Gecko/20110420 Firefox/3.6.17 GTB7.1 ( .NET CLR 3.5.30729; .NET4.0E) WebMoney Advisor', 'http://novi-ua.org/people', 'home', '', 0);
INSERT INTO user_access VALUES (5, 1311685162, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows; U; Windows NT 6.1; ru; rv:1.9.2.17) Gecko/20110420 Firefox/3.6.17 GTB7.1 ( .NET CLR 3.5.30729; .NET4.0E) WebMoney Advisor', 'http://novi-ua.org/home', 'home', '', 0);
INSERT INTO user_access VALUES (5, 1311685932, '::ffff:194.187.108.86', 'Mozilla/5.0 (X11; Linux i686; rv:5.0) Gecko/20100101 Firefox/5.0', '', 'home', '', 0);
INSERT INTO user_access VALUES (5, 1311685978, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows; U; Windows NT 6.1; ru; rv:1.9.2.17) Gecko/20110420 Firefox/3.6.17 GTB7.1 ( .NET CLR 3.5.30729; .NET4.0E) WebMoney Advisor', 'http://novi-ua.org/home', 'home', '', 0);
INSERT INTO user_access VALUES (5, 1311686154, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows; U; Windows NT 6.1; ru; rv:1.9.2.17) Gecko/20110420 Firefox/3.6.17 GTB7.1 ( .NET CLR 3.5.30729; .NET4.0E) WebMoney Advisor', 'http://novi-ua.org/home', 'home', '', 0);
INSERT INTO user_access VALUES (5, 1311686328, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows; U; Windows NT 6.1; ru; rv:1.9.2.17) Gecko/20110420 Firefox/3.6.17 GTB7.1 ( .NET CLR 3.5.30729; .NET4.0E) WebMoney Advisor', 'http://novi-ua.org/home', 'home', '', 0);
INSERT INTO user_access VALUES (5, 1311686949, '::ffff:194.187.108.86', 'Mozilla/5.0 (X11; Linux i686; rv:5.0) Gecko/20100101 Firefox/5.0', '', 'home', '', 0);
INSERT INTO user_access VALUES (21, 1311687798, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows NT 5.1; rv:5.0) Gecko/20100101 Firefox/5.0', '', 'home', '', 0);
INSERT INTO user_access VALUES (5, 1311697578, '::ffff:93.75.178.6', 'Mozilla/5.0 (iPad; U; CPU OS 4_3 like Mac OS X; uk-ua) AppleWebKit/533.17.9 (KHTML, like Gecko) Version/5.0.2 Mobile/8F191 Safari/6533.18.5', 'http://novi-ua.org/', 'home', '', 0);
INSERT INTO user_access VALUES (5, 1311697670, '::ffff:93.75.178.6', 'Mozilla/5.0 (iPad; U; CPU OS 4_3 like Mac OS X; uk-ua) AppleWebKit/533.17.9 (KHTML, like Gecko) Version/5.0.2 Mobile/8F191 Safari/6533.18.5', 'http://novi-ua.org/home', 'people', '', 0);
INSERT INTO user_access VALUES (5, 1311697691, '::ffff:93.75.178.6', 'Mozilla/5.0 (iPad; U; CPU OS 4_3 like Mac OS X; uk-ua) AppleWebKit/533.17.9 (KHTML, like Gecko) Version/5.0.2 Mobile/8F191 Safari/6533.18.5', 'http://novi-ua.org/people', 'home', '', 0);
INSERT INTO user_access VALUES (2, 1311749472, '::ffff:46.203.238.51', 'Mozilla/5.0 (iPad; U; CPU OS 4_3 like Mac OS X; ru-ru) AppleWebKit/533.17.9 (KHTML, like Gecko) Version/5.0.2 Mobile/8F191 Safari/6533.18.5', 'http://novi-ua.org/', 'home', '', 0);
INSERT INTO user_access VALUES (2, 1311750560, '::ffff:46.203.238.51', 'Mozilla/5.0 (iPad; U; CPU OS 4_3 like Mac OS X; ru-ru) AppleWebKit/533.17.9 (KHTML, like Gecko) Version/5.0.2 Mobile/8F191 Safari/6533.18.5', 'http://novi-ua.org/home', 'profile', 'index', 2);
INSERT INTO user_access VALUES (5, 1311750567, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows; U; Windows NT 6.1; ru; rv:1.9.2.17) Gecko/20110420 Firefox/3.6.17 GTB7.1 ( .NET CLR 3.5.30729; .NET4.0E) WebMoney Advisor', 'http://novi-ua.org/home', 'home', '', 0);
INSERT INTO user_access VALUES (2, 1311750576, '::ffff:46.203.238.51', 'Mozilla/5.0 (iPad; U; CPU OS 4_3 like Mac OS X; ru-ru) AppleWebKit/533.17.9 (KHTML, like Gecko) Version/5.0.2 Mobile/8F191 Safari/6533.18.5', 'http://novi-ua.org/profile-2', 'profile', 'invite', 0);
INSERT INTO user_access VALUES (5, 1311750590, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows; U; Windows NT 6.1; ru; rv:1.9.2.17) Gecko/20110420 Firefox/3.6.17 GTB7.1 ( .NET CLR 3.5.30729; .NET4.0E) WebMoney Advisor', 'http://novi-ua.org/home', 'profile', 'index', 5);
INSERT INTO user_access VALUES (5, 1311750602, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows; U; Windows NT 6.1; ru; rv:1.9.2.17) Gecko/20110420 Firefox/3.6.17 GTB7.1 ( .NET CLR 3.5.30729; .NET4.0E) WebMoney Advisor', 'http://novi-ua.org/profile-5', 'profile', 'invite', 0);
INSERT INTO user_access VALUES (2, 1311750632, '::ffff:46.203.238.51', 'Mozilla/5.0 (iPad; U; CPU OS 4_3 like Mac OS X; ru-ru) AppleWebKit/533.17.9 (KHTML, like Gecko) Version/5.0.2 Mobile/8F191 Safari/6533.18.5', 'http://novi-ua.org/profile/invite', 'profile', 'index', 2);
INSERT INTO user_access VALUES (5, 1311750730, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows; U; Windows NT 6.1; ru; rv:1.9.2.17) Gecko/20110420 Firefox/3.6.17 GTB7.1 ( .NET CLR 3.5.30729; .NET4.0E) WebMoney Advisor', 'http://novi-ua.org/profile/invite', 'home', '', 0);
INSERT INTO user_access VALUES (5, 1311750735, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows; U; Windows NT 6.1; ru; rv:1.9.2.17) Gecko/20110420 Firefox/3.6.17 GTB7.1 ( .NET CLR 3.5.30729; .NET4.0E) WebMoney Advisor', 'http://novi-ua.org/home', 'home', '', 0);
INSERT INTO user_access VALUES (5, 1311751185, '::ffff:194.187.108.86', 'Mozilla/5.0 (Windows; U; Windows NT 6.1; ru; rv:1.9.2.17) Gecko/20110420 Firefox/3.6.17 GTB7.1 ( .NET CLR 3.5.30729; .NET4.0E) WebMoney Advisor', 'http://novi-ua.org/home', 'home', '', 0);


--
-- Data for Name: user_auth; Type: TABLE DATA; Schema: public; Owner: auzo
--

INSERT INTO user_auth VALUES (2, 'sl.kolomiyets@gmail.com', 'f97b513b833cdba0309d27ee71e2c04d', '691446cddd14122b0357ad367d1949cb89f0fd5c927d466d6ec9a21b9ac34ffa', true, 1, 'editor,admin,moderator,selfmoderator', 1272020457, '::ffff:194.187.108.86', 1, 0, '0', 1, 0, 1272020457, 0, 1, 0, 0, 0, 0, false, false, false, 1292371200, 0, 0, 0, NULL);
INSERT INTO user_auth VALUES (1360, 'ninjapull@gmail.com', '26740a30eb773d03cf6ef8f7737d3fa3', 'c3a5e7ce828acd78eda7d54df7f526de81e74d678581a3bb7a720b019f4f1a93', true, 8, 'admin,moderator,selfmoderator', 1279875871, '::ffff:194.187.108.86', 0, 0, '0', 0, 0, 1286882786, 0, 1, 0, 0, 0, 0, false, false, false, 1292371200, 0, 0, 0, NULL);
INSERT INTO user_auth VALUES (29, 'andimov@gmail.com', 'b355556453d2bf012bcf677bf75d566b', 'd25773956d96a03306906b53d55b5178c3c59e5f8b3e9753913f4d435b53c308', true, 8, 'editor,admin,moderator,selfmoderator', 1272020457, '::ffff:194.187.108.86', 1, 0, '22', 0, 0, 1279018801, 0, 1, 1674, 0, 0, 29, true, false, false, 1292495119, 0, 0, 0, NULL);
INSERT INTO user_auth VALUES (1, 'pa@shevchenko.ua', '2c23d3c4519f706efe9bdcf6dda82e0f', '9dc512e4a8f86cc0d3cb61b2ded59ce320f07591c6fcb220ffe637cda29bb3f6', true, 5, 'admin,moderator,selfmoderator', 1310113890, '::ffff:194.187.108.86', 0, 0, NULL, 0, 1, 1310197849, 0, 1, 0, 0, 0, 5, false, false, false, 1310113890, 0, 0, 0, NULL);
INSERT INTO user_auth VALUES (11, 'offline_1_1310370846', '27a21354321dfbc28f0cb17b995bb9ca', '3ee8804555986ecab562edb4e951a5f5941e1aaaba585b952b62c14a3a175a61', false, 1, '', 1310370846, '::ffff:194.187.108.86', 0, 0, NULL, 0, 0, NULL, 0, 1, 0, 0, 0, 0, false, false, false, 1310370846, 1, 0, 0, NULL);
INSERT INTO user_auth VALUES (12, 'offline_1_1310469578', '27a21354321dfbc28f0cb17b995bb9ca', '48bbbdbf891a1310f48c51a818838d5ee07413354875be01a996dc560274708e', false, 1, '', 1310469578, '::ffff:194.187.108.86', 0, 0, NULL, 0, 0, NULL, 0, 1, 0, 0, 0, 0, false, false, false, 1310469578, 1, 0, 0, NULL);
INSERT INTO user_auth VALUES (13, 'offline_1_1310470466', '27a21354321dfbc28f0cb17b995bb9ca', 'bfcd1623c7e0a40d15dd85a6513b135273278a4a86960eeb576a8fd4c9ec6997', false, 1, '', 1310470466, '::ffff:194.187.108.86', 0, 0, NULL, 0, 0, NULL, 0, 1, 0, 0, 0, 0, false, false, false, 1310470466, 1, 0, 0, NULL);
INSERT INTO user_auth VALUES (14, 'offline_1_1310471035', '27a21354321dfbc28f0cb17b995bb9ca', '41a8e8d3a0153c9a12a78d96c65dee36621461af90cadfdaf0e8d4cc25129f91', false, 1, '', 1310471035, '::ffff:194.187.108.86', 0, 0, NULL, 0, 0, NULL, 0, 1, 0, 0, 0, 0, false, false, false, 1310471035, 1, 0, 0, NULL);
INSERT INTO user_auth VALUES (15, 'offline_1_1310472508', '27a21354321dfbc28f0cb17b995bb9ca', '73c98d21818091226e0f0e709cc00e81f85454e8279be180185cac7d243c5eb3', false, 1, '', 1310472508, '::ffff:194.187.108.86', 0, 0, NULL, 0, 0, NULL, 0, 1, 0, 0, 0, 0, false, false, false, 1310472508, 1, 0, 0, NULL);
INSERT INTO user_auth VALUES (17, 'offline_1_1310474794', '27a21354321dfbc28f0cb17b995bb9ca', '1c9cd77ee6a0e7b9bf0137adb8e4ce1c07c5807d0d927dcd0980f86024e5208b', false, 1, '', 1310474794, '::ffff:194.187.108.86', 0, 0, NULL, 0, 0, NULL, 0, 1, 0, 0, 0, 0, false, false, false, 1310474794, 1, 0, 0, NULL);
INSERT INTO user_auth VALUES (18, 'offline_1_1310476800', '27a21354321dfbc28f0cb17b995bb9ca', '34a2944c891123f2e4df9a0d0a7eb0dd3dd48ab31d016ffcbf3314df2b3cb9ce', false, 1, '', 1310476800, '::ffff:194.187.108.86', 0, 0, NULL, 0, 0, NULL, 0, 1, 0, 0, 0, 0, false, false, false, 1310476800, 1, 0, 0, NULL);
INSERT INTO user_auth VALUES (16, 'olha@olha.com.ua', 'c9d392c330c30611980c083dc99c8375', 'd01472f6f1c290793bed3a97c7cbefc19b72e31dac81715466cd580a448cf823', false, 1, '', 1310473922, '::ffff:194.187.108.86', 0, 0, NULL, 0, 0, NULL, 0, 1, 0, 0, 0, 0, false, false, false, 1310574831, 1, 0, 0, NULL);
INSERT INTO user_auth VALUES (21, 'julia@kostukova.com', 'da86f4961a76f92ad5ce4f4c008b9e01', '3aebab1dd068842efa4756a681c812e9109a0ca3bc27f3e96597370d5c8cf03d', true, 5, '', 1311253824, '::ffff:194.187.108.86', 0, 0, NULL, 0, 0, 1311253835, 0, 1, 0, 0, 0, 2, false, false, false, 1311253824, 0, 0, 0, NULL);
INSERT INTO user_auth VALUES (5, 'igor@shevchenko.ua', '82e44a95f95b00e518c030a93610a342', '61c80ab6fd65eda26e293f46182d1cbf35cf8659cfcb13224cbd47863a34fc58', true, 1, 'editor,admin,moderator,selfmoderator', 1272020457, '::ffff:194.187.108.86', 1, 0, '0', 1, 0, 1276601112, 0, 1, 0, 0, 0, 0, false, false, false, 1292371200, 0, 0, 0, NULL);


--
-- Data for Name: user_bio; Type: TABLE DATA; Schema: public; Owner: auzo
--

INSERT INTO user_bio VALUES (29, 'Родился 18 мая 1988 года в городе Белгород-Днестровском Одеской области, в семье программиста и воспитательницы детского сада', '', 'с лета 2004 года работал оператором фотолаборатории.
с 2006 работал на позиции технического директора студии по разработке ПО, контента и сайтов для мобильных устройств
с лета 2009 года работал в качестве ведущего разработчика мобильной платежной системы
с 2010 года работаю программистом в секретариате Игоря Шевченка', '', '', '', '', '', '');
INSERT INTO user_bio VALUES (5, '10 січня 1971 року, м. Олександрія, Кіровоградська обл., неодружений', '', '', '', 'В січні 2010 року ініціював створення Мерітократичної партії України. До цього ніколи не був членом жодної політичної партії, не брав участь в жодних політичних виборах', '', '', 'Створення та розвиток юридичної фірми \\"Шевченко Дідковський і Партнери\\", яка стала юридичною фірмою №1 в Україні в 2004 та 2005 роках, та \\"Кращий роботодавець року\\" серед всіх юридичних та аудиторських фірм в 2006 році.

Створення та розвиток \\"Асоціації правників України\\" (www.uba.ua), яка стала найчисельнішою та найавторитетнішою громадською організацією юристів в Україні.
', 'Моя детальна біографія на www.shevchenko.ua');
INSERT INTO user_bio VALUES (2, 'Народилася 13 серпня 1986 року у м. Донецьку. Батько - Леонов Леонід Володимирович, інженер, помер у 2000 році, мати - Леонова (Пошита) Ніна Василівна, кандидат економічних наук, доцент кафедри економічної статистики Донецького національного університету (зараз на пенсії).', 'За освітою юрист (спеціалізація - господарське право), ступінь бакалавра, а за нею магістра, отримала на економіко-правовому факультеті Донецького національного університету.', 'Трудову діяльність розпочала на посаді менеджера проектів у команді Форуму молодих лідерів України у 2008 році, у 2009 році стала заступником Виконавчого директора МГО \\"Українська Громада\\", у січні 2010 року очолила Секретаріат Ігоря Шевченка.

За період навчання в університеті пройшла також декілька стажувань: на посаді помічника судді Господарського суду Донецької області, помічника юриста у юридичній фірмі \\"Апріорі-Лєкс\\" (Донецьк), а також у юридичній фірмі \\"Valko Mejer & partners\\" (Братислава, Словаччина).', 'Громадську діяльність розпочала на 2-му курсі навчання в університеті. У 2005 році стала проектним менеджером місцевої громадської організації \\"Молодіжний центр правничих досліджень\\", у 2006 очолила Центр, а також увійшла до складу Ради Ліги студенів Асоціації правників України (молодіжного крила найпотужнішого професійного об’єднання правників у країні). У 2007 році після закінчення строку повноважень у Раді Ліги студентів АПУ увійшла до Національного правління Європейської асоціації студентів права (ELSA – міжнародна організація студентів-правників, що діє у 35 країнах Європи). За час роботи в цих організаціях реалізувала більше 10 всеукраїнських проектів, серед яких національні правничі змагання, наукові конференції та круглі столи, літні школи, а також всеукраїнське дослідження сучасного стану юридичної освіти в країні.', 'Член Оргкомітету Мерітократичної партії України.', 'Автор декількох наукових публікацій у сфері права інтелектуальної власності.', '', 'Найбільші успіхи і досягнення пов\\''язую із періодом отримання юридичної освіти. У 2006 році у складі команди Донецького національного університету стала Національним чемпіоном змагань із міжнародного публічного права ім. Ф.Джессопа і представляла Україну у міжнародному раунді цих змагань у м. Вашингтон. Вважаю це досягненням, оскільки вперше за 10 років національним переможцем цих змагань стала команда не одного з київських ВНЗ, а Донецького національного університету. Таким чином наша команда вивела факультет та університет на міжнародний рівень. 

У 2007 році увійшла до складу команди, що представляла наш університет на змаганнях з міжнародного комерційного арбітражу ім. Віллема Віса у Відні (Австрія). Щоб увійти до цієї команди, пройшла факультетський відбір і посіла перше місце серед 10 претендентів на місце у команді.

У 2007 ж році перемогла у Всеукраїнських змаганнях з права інтелектуальної власності \\"Таврійський Золотовуст\\", посівши 1 місце.

У 2008 році відмовилась від усіх пропозицій роботи з боку юридичних фірм і вирішила присвітити себе громадсько-політичній роботі.', '');
INSERT INTO user_bio VALUES (1360, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO user_bio VALUES (1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO user_bio VALUES (11, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO user_bio VALUES (12, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO user_bio VALUES (13, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO user_bio VALUES (14, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO user_bio VALUES (15, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO user_bio VALUES (16, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO user_bio VALUES (17, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO user_bio VALUES (18, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO user_bio VALUES (21, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);


--
-- Data for Name: user_contact; Type: TABLE DATA; Schema: public; Owner: auzo
--



--
-- Data for Name: user_data; Type: TABLE DATA; Schema: public; Owner: auzo
--

INSERT INTO user_data VALUES (1360, 'Андрiй', 'Зуєв', 229, NULL, '', 11, 'ee5b9e3f', 'm', 0.5000, 0, 0, 16, true, 0, 0, NULL, true, '''зуєв'':2 ''андрi'':1', 'a:9:{i:1;s:0:"";i:2;s:0:"";i:3;s:0:"";i:4;s:0:"";i:5;s:0:"";i:6;s:0:"";i:7;s:0:"";i:9;s:0:"";i:10;s:0:"";}', 'ua', '', 0, 1, 13, 0, NULL, '', '', '', 'Валерiйович', NULL, '', NULL, NULL, NULL, NULL, '', '', '', '', '', NULL, 'ninjapull@gmail.com', 229, 13, '', 2, '4', 2, 0, 0, '');
INSERT INTO user_data VALUES (29, 'Андрій', 'Дімов', 229, 'Программирование', '', 11, NULL, 'm', 2.0400, 0, 0, 16, true, 4, 0, NULL, true, '''андрі'':1 ''дімов'':2 ''программирован'':3', 'a:9:{i:1;s:10:"id34535181";i:2;s:0:"";i:3;s:0:"";i:4;s:0:"";i:5;s:0:"";i:6;s:0:"";i:7;s:0:"";i:9;s:0:"";i:10;s:0:"";}', 'ua', '', 0, 1, 13, 23, 'Библия, книги Достоевского, Клайва Льюиса, Честертона', '+380986613369', '', '', '', '1988-05-18', 'Контрактова площа', '', '', '', '', '450746123', '', 'Запоріжжя', '', '', NULL, '', 229, 13, 'Межигірська', 1, '0', 0, 0, 0, NULL);
INSERT INTO user_data VALUES (2, 'Світлана', 'Коломієць', 232, '', '', 20, 'b1ac3aab', 'f', 18.8650, 0, 0, 16, true, 0, 0, NULL, false, '''світлан'':1 ''коломієц'':2', 'a:9:{i:1;s:0:"";i:2;s:0:"";i:3;s:0:"";i:4;s:0:"";i:5;s:0:"";i:6;s:0:"";i:7;s:0:"";i:9;s:0:"";i:10;s:0:"";}', 'ua', '', 4, 1, 13, 21, '', '0931487729', '', 'http://www.m-p-u-.org', 'Леонідівна', '1986-08-13', '', '', '', '', '', '', 'svitlana.leonova', 'Донецьк', '0444929292', '', NULL, 'sl.kolomiyets@gmail.com', 232, 13, '', 2, '2', 1, 0, 1, 's.kolomiyets@gmail.com');
INSERT INTO user_data VALUES (18, 'Святослав', 'Цеголко', 223, NULL, NULL, NULL, 'f3c07e48', 'm', 0.0000, 0, 0, 16, true, 0, 0, NULL, true, '''цеголк'':2 ''святосла'':1', 'a:9:{i:1;s:0:"";i:2;s:0:"";i:3;s:0:"";i:4;s:0:"";i:5;s:0:"";i:6;s:0:"";i:7;s:0:"";i:9;s:0:"";i:10;s:0:"";}', 'ua', '', 0, 1, 13, NULL, NULL, '050-442-76-71', '', '', 'Петрович', '1979-04-30', NULL, NULL, NULL, NULL, NULL, '', '', NULL, '', '', NULL, 'svyatt@5.ua ', NULL, NULL, '', 0, '0', 0, 0, 0, 'swyattt@gmail.com');
INSERT INTO user_data VALUES (12, 'Юрій', 'Кривошея', 223, NULL, NULL, NULL, NULL, 'm', 0.0000, 0, 0, 16, true, 0, 0, NULL, true, '''юрі'':1 ''кривоше'':2', 'a:9:{i:1;s:0:"";i:2;s:0:"";i:3;s:0:"";i:4;s:0:"";i:5;s:0:"";i:6;s:0:"";i:7;s:0:"";i:9;s:0:"";i:10;s:0:"";}', 'ua', '', 0, 1, 13, NULL, NULL, '050-334-09-11', '', '', '', NULL, NULL, NULL, NULL, NULL, NULL, '', '', NULL, '', '', NULL, 'yuriy@ymedia.com.ua', NULL, NULL, '', 0, '0', 0, 0, 0, 'yuriykryvosheya@yahoo.com');
INSERT INTO user_data VALUES (1, 'Людмила', 'Лірник', 0, NULL, NULL, NULL, '1031201c', 'f', 0.0000, 0, 0, 16, false, 0, 0, NULL, true, '''людм'':1 ''лірник'':2', 'a:9:{i:1;s:29:"http://vkontakte.ru/id9524721";i:2;s:50:"http://www.odnoklassniki.ru/#/profile/258748404787";i:3;s:35:"http://www.facebook.com/luda.lirnyk";i:4;s:30:"http://twitter.com/#!/Liudmula";i:5;s:0:"";i:6;s:0:"";i:7;s:33:"http://politiko.ua/profile-107254";i:9;s:0:"";i:10;s:0:"";}', 'ua', '', 0, 0, 0, NULL, NULL, '0938082493', '', '', 'Олександрівна', '1986-09-21', NULL, NULL, NULL, NULL, NULL, '', '', NULL, '0444929290', '', NULL, 'pa@shevchenko.ua', 0, 0, '', 0, '4', 0, 2, 2, '');
INSERT INTO user_data VALUES (11, 'Rodion', 'Pryntsevsky', 10000, NULL, NULL, NULL, NULL, 'm', 0.0000, 0, 0, 16, true, 0, 0, NULL, true, '''rodion'':1 ''pryntsevski'':2', 'a:9:{i:1;s:0:"";i:2;s:0:"";i:3;s:0:"";i:4;s:0:"";i:5;s:0:"";i:6;s:0:"";i:7;s:0:"";i:9;s:0:"";i:10;s:0:"";}', 'ua', '', 0, 31, 9999, NULL, NULL, '+44(0)7771 377 266', '', '', '', NULL, NULL, NULL, NULL, NULL, NULL, '', '', NULL, '+44(0)2088 114 322', '', NULL, 'Rodion_Pryntsevsky@discovery-europe.com', NULL, NULL, '', 0, '0', 0, 0, 0, '');
INSERT INTO user_data VALUES (17, 'Вікторія', 'Сюмар', 223, NULL, NULL, NULL, 'fc7e4431', 'f', 0.0000, 0, 0, 16, true, 0, 0, NULL, true, '''сюмар'':2 ''вікторі'':1', 'a:9:{i:1;s:0:"";i:2;s:0:"";i:3;s:0:"";i:4;s:0:"";i:5;s:0:"";i:6;s:0:"";i:7;s:0:"";i:9;s:0:"";i:10;s:0:"";}', 'ua', '', 0, 1, 13, NULL, NULL, '050-380-27-00', '', '', 'Петрівна', NULL, NULL, NULL, NULL, NULL, NULL, '', '', NULL, '255-62-42 (ф: 226-27-75)', '', NULL, 'sjumar@yahoo.com', NULL, NULL, '', 0, '0', 0, 0, 0, 'vika@imi.org.ua');
INSERT INTO user_data VALUES (13, 'Ярослав', 'Ажнюк', 223, NULL, NULL, NULL, NULL, 'm', 0.0000, 0, 0, 16, true, 0, 0, NULL, true, '''ажнюк'':2 ''яросла'':1', 'a:9:{i:1;s:0:"";i:2;s:0:"";i:3;s:0:"";i:4;s:0:"";i:5;s:0:"";i:6;s:0:"";i:7;s:0:"";i:9;s:0:"";i:10;s:0:"";}', 'ua', '', 0, 1, 13, NULL, NULL, '050-640-41-68', '', '', '', NULL, NULL, NULL, NULL, NULL, NULL, '', '', NULL, '', '', NULL, 'ya@socialmedia.com.ua ', NULL, NULL, '', 0, '0', 0, 0, 0, '');
INSERT INTO user_data VALUES (16, 'Ольга', 'Ситник', 227, NULL, NULL, NULL, 'bd14878c', 'f', 0.0000, 0, 0, 16, true, 0, 0, NULL, true, '''ольг'':1 ''ситник'':2', 'a:9:{i:1;s:29:"http://vkontakte.ru/id6377679";i:2;s:0:"";i:3;s:0:"";i:4;s:0:"";i:5;s:0:"";i:6;s:0:"";i:7;s:0:"";i:9;s:0:"";i:10;s:0:"";}', 'ua', '', 0, 1, 13, NULL, NULL, '050 371 77 31', '', '', 'Юріївна', '1985-02-14', NULL, NULL, NULL, NULL, NULL, '', '', NULL, '', '', NULL, 'ola.sytnyk@gmail.com', NULL, NULL, '', 0, '0', 1, 0, 0, '');
INSERT INTO user_data VALUES (14, 'Світлана', 'Заліщук', 228, NULL, NULL, NULL, NULL, 'f', 0.0000, 0, 0, 16, true, 0, 0, NULL, true, '''заліщук'':2 ''світлан'':1', 'a:9:{i:1;s:0:"";i:2;s:0:"";i:3;s:0:"";i:4;s:0:"";i:5;s:0:"";i:6;s:0:"";i:7;s:0:"";i:9;s:0:"";i:10;s:0:"";}', 'ua', '', 0, 1, 13, NULL, NULL, '0503855513', '', '', '', NULL, NULL, NULL, NULL, NULL, NULL, '', '', NULL, '254-63-08', '', NULL, 'svitlana.zalishchuk@gmail.com', NULL, NULL, '', 0, '0', 2, 0, 0, '');
INSERT INTO user_data VALUES (15, 'Ганна', 'Гопко', 223, NULL, NULL, NULL, '8de83180', 'f', 0.0000, 0, 0, 16, true, 0, 0, NULL, true, '''ган'':1 ''гопк'':2', 'a:9:{i:1;s:0:"";i:2;s:0:"";i:3;s:0:"";i:4;s:0:"";i:5;s:0:"";i:6;s:0:"";i:7;s:0:"";i:9;s:0:"";i:10;s:0:"";}', 'ua', '', 0, 1, 13, NULL, NULL, '067-672-11-20', '', '', '', NULL, NULL, NULL, NULL, NULL, NULL, '', '', NULL, '044-287-28-70', '', NULL, 'Hhopko@tobaccofreekids.org', NULL, NULL, '', 0, '0', 0, 0, 0, '');
INSERT INTO user_data VALUES (21, 'Юлія', 'Костюкова', 0, NULL, NULL, NULL, NULL, 'f', 0.0000, 0, 0, 16, true, 0, 0, NULL, true, '''юлі'':1 ''костюков'':2', '', 'ua', '', 0, 1, 0, NULL, NULL, '', '', NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', NULL, NULL, 0, 0, '', 0, '0', 0, 0, 0, NULL);
INSERT INTO user_data VALUES (5, 'Ігор', 'Шевченко', 228, 'Здоровий спосіб життя, історія, подорожі, кімнатні та садові рослини ', '', 20, '8e5a44d1', 'm', 293.3730, 0, 0, 0, true, 0, 0, NULL, false, '''та'':9 ''житт'':5 ''ігор'':1 ''здоров'':3 ''рослин'':11 ''садові'':10 ''спосіб'':4 ''історі'':6 ''шевченк'':2 ''кімнатні'':8 ''подорожі'':7', 'a:9:{i:1;s:29:"http://vkontakte.ru/id7018152";i:2;s:24:"http://odnoklassniki.ru/";i:3;s:39:"http://www.facebook.com/igor.shevchenko";i:4;s:33:"http://twitter.com/IgorShevchenko";i:5;s:40:"http://ua.linkedin.com/in/igorshevchenko";i:6;s:39:"http://igor-shevchenko.livejournal.com/";i:7;s:37:"http://politiko.com.ua/profile-106397";i:9;s:0:"";i:10;s:28:"http://connect.ua/user-48551";}', 'ua', '', 0, 1, 13, 21, '', '067 401 4477', '', 'http://shevchenko.ua', 'Анатолійович', '1971-01-10', 'Київ', 'Секрет моего успеха (Майкл Фокс)', '', 'Нью-Ейдж', 'Природа, море, гори, читання, спорт', '', 'shevchenkoi', '', '044 492-9292', '', NULL, 'igor@shevchenko.ua', 228, 13, '', 0, '0', 2, 2, 0, '');


--
-- Data for Name: user_desktop; Type: TABLE DATA; Schema: public; Owner: auzo
--

INSERT INTO user_desktop VALUES (1, 0, 0, 0, 0, 'a:0:{}', 'a:0:{}', 0, 0, 0, 'a:0:{}', 0, 0, 0, 0, '', '{}', NULL);
INSERT INTO user_desktop VALUES (6, 0, 0, 0, 0, 'a:0:{}', 'a:0:{}', 0, 0, 0, 'a:0:{}', 0, 0, 0, 0, '', '{}', NULL);
INSERT INTO user_desktop VALUES (7, 0, 0, 0, 0, 'a:0:{}', 'a:0:{}', 0, 0, 0, 'a:0:{}', 0, 0, 0, 0, '', '{}', NULL);
INSERT INTO user_desktop VALUES (8, 0, 0, 0, 0, 'a:0:{}', 'a:0:{}', 0, 0, 0, 'a:0:{}', 0, 0, 0, 0, '', '{}', NULL);
INSERT INTO user_desktop VALUES (9, 0, 0, 0, 0, 'a:0:{}', 'a:0:{}', 0, 0, 0, 'a:0:{}', 0, 0, 0, 0, '', '{}', NULL);
INSERT INTO user_desktop VALUES (10, 0, 0, 0, 0, 'a:0:{}', 'a:0:{}', 0, 0, 0, 'a:0:{}', 0, 0, 0, 0, '', '{}', NULL);
INSERT INTO user_desktop VALUES (11, 0, 0, 0, 0, 'a:0:{}', 'a:0:{}', 0, 0, 0, 'a:0:{}', 0, 0, 0, 0, '', '{}', NULL);
INSERT INTO user_desktop VALUES (12, 0, 0, 0, 0, 'a:0:{}', 'a:0:{}', 0, 0, 0, 'a:0:{}', 0, 0, 0, 0, '', '{}', NULL);
INSERT INTO user_desktop VALUES (13, 0, 0, 0, 0, 'a:0:{}', 'a:0:{}', 0, 0, 0, 'a:0:{}', 0, 0, 0, 0, '', '{}', NULL);
INSERT INTO user_desktop VALUES (14, 0, 0, 0, 0, 'a:0:{}', 'a:0:{}', 0, 0, 0, 'a:0:{}', 0, 0, 0, 0, '', '{}', NULL);
INSERT INTO user_desktop VALUES (15, 0, 0, 0, 0, 'a:0:{}', 'a:0:{}', 0, 0, 0, 'a:0:{}', 0, 0, 0, 0, '', '{}', NULL);
INSERT INTO user_desktop VALUES (16, 0, 0, 0, 0, 'a:0:{}', 'a:0:{}', 0, 0, 0, 'a:0:{}', 0, 0, 0, 0, '', '{}', NULL);
INSERT INTO user_desktop VALUES (17, 0, 0, 0, 0, 'a:0:{}', 'a:0:{}', 0, 0, 0, 'a:0:{}', 0, 0, 0, 0, '', '{}', NULL);
INSERT INTO user_desktop VALUES (18, 0, 0, 0, 0, 'a:0:{}', 'a:0:{}', 0, 0, 0, 'a:0:{}', 0, 0, 0, 0, '', '{}', NULL);
INSERT INTO user_desktop VALUES (19, 0, 0, 0, 0, 'a:0:{}', 'a:0:{}', 0, 0, 0, 'a:0:{}', 0, 0, 0, 0, '', '{}', NULL);
INSERT INTO user_desktop VALUES (20, 0, 0, 0, 0, 'a:0:{}', 'a:0:{}', 0, 0, 0, 'a:0:{}', 0, 0, 0, 0, '', '{}', NULL);
INSERT INTO user_desktop VALUES (21, 0, 0, 0, 0, 'a:0:{}', 'a:0:{}', 0, 0, 0, 'a:0:{}', 0, 0, 0, 0, '', '{}', NULL);


--
-- Data for Name: user_desktop_education; Type: TABLE DATA; Schema: public; Owner: auzo
--



--
-- Data for Name: user_desktop_event; Type: TABLE DATA; Schema: public; Owner: auzo
--



--
-- Data for Name: user_desktop_meeting; Type: TABLE DATA; Schema: public; Owner: auzo
--



--
-- Data for Name: user_desktop_signatures; Type: TABLE DATA; Schema: public; Owner: auzo
--



--
-- Data for Name: user_dictionary; Type: TABLE DATA; Schema: public; Owner: auzo
--



--
-- Data for Name: user_education; Type: TABLE DATA; Schema: public; Owner: auzo
--

INSERT INTO user_education VALUES (2, 'Україна', 'Донецьк', '95', '1993', '2003', '', '', '', '', '', 'Україна', 'Донецьк', 'Донецький національний університет', 'Економіко-правовий', '', 1, 6, '2003', '2008', '', '', '', '', '', 0, 0, '', '', '');
INSERT INTO user_education VALUES (5, 'Україна', 'Київ', '206', '1978', '1988', '', '', '', '', '', 'Україна', 'Київ', 'Інститут міжнародних відносин КНУ ім. Шевченка', 'міжнародне право', 'приватне міжнародне право', 1, 0, '1992', '1996', 'США', 'Мінеаполіс', 'Державний університет штату Міннесота', 'юридичний', 'корпоративне право', 1, 6, '1996', '1997', 'Єльський університет,  міжнародна програма лідерства, Нью-Хевен, США, 2006 рік


Гарвардський університет, Школа державного управління ім. Дж. Кенеді, програма міжнародного лідерства та державного управління, Кембрідж, США, 2007');
INSERT INTO user_education VALUES (29, 'Україна', 'Білгород-Дністровський', '11', '1994', '2004', 'Україна', 'Запоріжжя', 'Запорізький Будівельний Центр Профтехосвіти', '2004', '2005', 'Україна', 'Запоріжжя', 'КПУ', 'Управління', 'ИТ', 3, 2, '2005', '', 'Україна', 'Запоріжжя', 'КПУ', 'Управління', 'ИТ', 3, 6, '2012', '2025', 'Інша освіта');
INSERT INTO user_education VALUES (1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO user_education VALUES (11, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO user_education VALUES (12, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO user_education VALUES (13, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO user_education VALUES (14, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO user_education VALUES (15, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO user_education VALUES (16, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO user_education VALUES (17, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO user_education VALUES (18, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO user_education VALUES (21, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);


--
-- Data for Name: user_education_curses; Type: TABLE DATA; Schema: public; Owner: auzo
--

INSERT INTO user_education_curses VALUES (1360, 4, 'dsfas', 'dsfdsaf', '2011-03-15', '2011-03-30', 'sdfsdaf');


--
-- Data for Name: user_education_foreign; Type: TABLE DATA; Schema: public; Owner: auzo
--

INSERT INTO user_education_foreign VALUES (1360, 1, '2011', '2011', 3, 'dfdsg', 'd', 'dfgfdg', 1, 1, 1, 0, 0, 'dfgfdgfdg', 1, '', '');
INSERT INTO user_education_foreign VALUES (5, 1, '1997', '2011', 0, 'Міннеаполіс', 'U', '', 2, 36, 5, 1, 1, '', 2, '', '');


--
-- Data for Name: user_education_major; Type: TABLE DATA; Schema: public; Owner: auzo
--

INSERT INTO user_education_major VALUES (1360, 'gfdg', 'dfg', 'dfg', 'gsdfg', 2, 5, '2008', '2011');
INSERT INTO user_education_major VALUES (5, 'Київ', 'Інститут міжнародних відносин КНУ ім.Шевченка', 'міжнародне право', 'міжнародне приватне право', 1, 5, '1992', '1996');
INSERT INTO user_education_major VALUES (1, 'Львів', 'Національний університет \\"Львівська політехніка\\"', 'Міжнародні відносини', 'Міжнародної інформації', 1, 5, '2004', '2009');
INSERT INTO user_education_major VALUES (18, 'Львів', 'Львівський університет ім. І.Франка', 'журналістики', '', 1, 0, '1996', '2001');
INSERT INTO user_education_major VALUES (2, 'Донецьк', 'Донецький національний університет', 'Економіко-правовий факультет', 'Господарського права', 1, 6, '2003', '2008');


--
-- Data for Name: user_education_middle; Type: TABLE DATA; Schema: public; Owner: auzo
--

INSERT INTO user_education_middle VALUES (2, 6, 774, 'Донецьк', '2001', '2003', '20');
INSERT INTO user_education_middle VALUES (2, 6, 774, 'Донецьк', '1999', '2001', '95');


--
-- Data for Name: user_education_smiddle; Type: TABLE DATA; Schema: public; Owner: auzo
--

INSERT INTO user_education_smiddle VALUES (1360, 'Академiя', '', 'Запорiжжя', '2009', '2011');


--
-- Data for Name: user_education_staging; Type: TABLE DATA; Schema: public; Owner: auzo
--

INSERT INTO user_education_staging VALUES (1360, 3, 'sdf', 'sdf', 'sdfasdf', 'dsfsdaf', '2011-03-22', '2011-03-23', 'cxvxcvcxzvc', 'er3333');
INSERT INTO user_education_staging VALUES (1, 131, 'Вроцлав', 'AIESEC', 'http://www.aiesec.org.ua/', 'тренер', '', '', 'проведення тренінгів', '');
INSERT INTO user_education_staging VALUES (2, 150, 'Братіслава', 'Valko, Mejer & partners', '', 'помічник юриста', '2006-08-01', '2006-08-31', '', '');


--
-- Data for Name: user_location; Type: TABLE DATA; Schema: public; Owner: auzo
--

INSERT INTO user_location VALUES (1360, 1, 13, 223, 'dgfdg', 'yyyy', '', '', '1988-02-17', '2011-02-01', 'cvbcvb');
INSERT INTO user_location VALUES (9, 1, 3, 24, 'sdfsaf', '', '', '', '2011-07-03', '2011-07-20', 'sdfsaf');
INSERT INTO user_location VALUES (1, 1, 13, 230, '', '', '', '', '', '', '');
INSERT INTO user_location VALUES (11, 31, 0, 0, '', '', 'London W45YB', '566 Chiswick High Road', '', '', '');
INSERT INTO user_location VALUES (12, 1, 13, 0, '', '', '', '', '', '', '');
INSERT INTO user_location VALUES (13, 1, 13, 0, '', '', '', '', '', '', '');
INSERT INTO user_location VALUES (14, 1, 13, 0, '', '', '', '', '', '', '');
INSERT INTO user_location VALUES (15, 1, 13, 0, '', '', '', '', '', '', '');
INSERT INTO user_location VALUES (16, 1, 13, 227, 'Київ', '', '', '', '', '', 'Гуроїв Сталінграду');
INSERT INTO user_location VALUES (17, 1, 13, 0, '', '', '', '', '', '', '');
INSERT INTO user_location VALUES (18, 1, 13, 0, '', '', '', '', '', '', '');
INSERT INTO user_location VALUES (2, 1, 12, 201, 'Бориспіль', '', '', '', '2010-03-19', '2011-07-21', 'Кропивницького');


--
-- Data for Name: user_mail_access; Type: TABLE DATA; Schema: public; Owner: auzo
--

INSERT INTO user_mail_access VALUES (1360, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1);
INSERT INTO user_mail_access VALUES (29, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1);
INSERT INTO user_mail_access VALUES (2, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1);
INSERT INTO user_mail_access VALUES (5, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1);
INSERT INTO user_mail_access VALUES (6, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1);
INSERT INTO user_mail_access VALUES (7, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1);
INSERT INTO user_mail_access VALUES (8, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1);
INSERT INTO user_mail_access VALUES (4, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1);
INSERT INTO user_mail_access VALUES (10, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1);
INSERT INTO user_mail_access VALUES (9, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1);
INSERT INTO user_mail_access VALUES (1, 1, 1, 0, 1, 0, 0, 1, 0, 0, 0, 0, 0, 0, 0);
INSERT INTO user_mail_access VALUES (11, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1);
INSERT INTO user_mail_access VALUES (12, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1);
INSERT INTO user_mail_access VALUES (13, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1);
INSERT INTO user_mail_access VALUES (14, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1);
INSERT INTO user_mail_access VALUES (15, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1);
INSERT INTO user_mail_access VALUES (17, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1);
INSERT INTO user_mail_access VALUES (18, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1);
INSERT INTO user_mail_access VALUES (16, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1);
INSERT INTO user_mail_access VALUES (19, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1);
INSERT INTO user_mail_access VALUES (20, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1);
INSERT INTO user_mail_access VALUES (21, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1);


--
-- Data for Name: user_novasys_data; Type: TABLE DATA; Schema: public; Owner: auzo
--

INSERT INTO user_novasys_data VALUES (1, NULL, 0, NULL, '', 1, NULL, 'pa@shevchenko.ua', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '0', NULL, NULL, NULL);
INSERT INTO user_novasys_data VALUES (21, NULL, 0, NULL, '', 0, NULL, 'julia@kostukova.com', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '0', NULL, NULL, NULL);


--
-- Data for Name: user_questions; Type: TABLE DATA; Schema: public; Owner: auzo
--



--
-- Data for Name: user_recomendations; Type: TABLE DATA; Schema: public; Owner: auzo
--



--
-- Data for Name: user_sessions; Type: TABLE DATA; Schema: public; Owner: auzo
--

INSERT INTO user_sessions VALUES (29, 1311326309, 1311326309);
INSERT INTO user_sessions VALUES (9, 1310129845, 1310129845);
INSERT INTO user_sessions VALUES (19, 1310555813, 1310555813);
INSERT INTO user_sessions VALUES (1360, 1311141702, 1311141526);
INSERT INTO user_sessions VALUES (21, 1311687798, 1311687798);
INSERT INTO user_sessions VALUES (2, 1311750632, 1311750560);
INSERT INTO user_sessions VALUES (5, 1311751185, 1311750567);
INSERT INTO user_sessions VALUES (7, 1310129323, 1310129323);
INSERT INTO user_sessions VALUES (20, 1310556442, 1310556442);
INSERT INTO user_sessions VALUES (1, 1310556660, 1310555937);


--
-- Data for Name: user_shevchenko_data; Type: TABLE DATA; Schema: public; Owner: auzo
--

INSERT INTO user_shevchenko_data VALUES (0, 'Людмила', '', 'Лірник', 1, 0, 0, '', '', '', '', '', '', '', '', -1, 1, 0, '', 0, '', 0, '', '', 0, '', '', 0, 0, 1);
INSERT INTO user_shevchenko_data VALUES (0, 'Юлія', '', 'Костюкова', 1, 0, 0, '', '', '', '', '', '', '', '', -1, 1, 0, '', 0, '', 0, '', '', 0, '', '', 0, 0, 21);


--
-- Data for Name: user_temp_photos; Type: TABLE DATA; Schema: public; Owner: auzo
--



--
-- Data for Name: user_work; Type: TABLE DATA; Schema: public; Owner: auzo
--

INSERT INTO user_work VALUES (1360, 2, 3, 'dfasf', 'dfgdf', 'dfgdsf', '2011', 'dfgdfg', 'hgjhj', 'hjhgjg', '2011', 'hgjghj', 0, 'sfasdfsdafa', 'zxvczvzxc', '33241324');
INSERT INTO user_work VALUES (1, 20, 11, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '');
INSERT INTO user_work VALUES (11, 11, 0, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '');
INSERT INTO user_work VALUES (12, 0, 0, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '');
INSERT INTO user_work VALUES (13, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL);
INSERT INTO user_work VALUES (14, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL);
INSERT INTO user_work VALUES (15, 4, 0, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '');
INSERT INTO user_work VALUES (16, 27, 4, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '');
INSERT INTO user_work VALUES (17, 6, 10, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '');
INSERT INTO user_work VALUES (18, 10, 0, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '');
INSERT INTO user_work VALUES (21, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL);


--
-- Data for Name: user_work_action; Type: TABLE DATA; Schema: public; Owner: auzo
--

INSERT INTO user_work_action VALUES (1360, 'asdf', 1, 'asdf', 'asdf', '2011-03-14', '2011-03-15', 'sadf');
INSERT INTO user_work_action VALUES (1360, '', 0, '', '', '', '', '');


--
-- Data for Name: user_work_election; Type: TABLE DATA; Schema: public; Owner: auzo
--

INSERT INTO user_work_election VALUES (1360, '2011', 'rewtwret', 0, 0, 1, 6);


--
-- Data for Name: user_work_party; Type: TABLE DATA; Schema: public; Owner: auzo
--

INSERT INTO user_work_party VALUES (1360, '54retrewt', 'rewtr', 'rtew', 'ert', '2011-03-02', '2011-03-23');
INSERT INTO user_work_party VALUES (1360, '', '', 'rtew', '', '', '');
INSERT INTO user_work_party VALUES (2, 'Мерітократична партія України', 'www.m-p-u.org', 'керівник Секретаріату', '', '2010-01-28', '2011-07-21');


--
-- Data for Name: user_work_prof; Type: TABLE DATA; Schema: public; Owner: auzo
--

INSERT INTO user_work_prof VALUES (1360, 1, 'efsdaf', 'cxvcxv', 'sdf', 'asdf', 'asf', '2011-03-10', '2011-03-28');
INSERT INTO user_work_prof VALUES (1360, 0, '', '', '', '', '', '', '');
INSERT INTO user_work_prof VALUES (1, 1, 'Київ', 'Мерітократична партія України', 'http://m-p-u.org/', 'Особистий помічник голови партії', '', '2009-07-26', '');
INSERT INTO user_work_prof VALUES (11, 31, 'London', 'Discovery Communications Europe Limited', '', 'Affiliate Manager, Ukraine and CIS', '', '', '');
INSERT INTO user_work_prof VALUES (12, 1, 'Київ', 'Ymedia (creative business development)', 'www.ymedia.com.ua', '', '', '', '');
INSERT INTO user_work_prof VALUES (15, 1, 'Київ', 'Кампанія \\"Майбутнє без Куріння\\"', 'www.tobaccofreekids.org', 'Координатор проектів в Україні', '', '', '');
INSERT INTO user_work_prof VALUES (16, 1, 'Київ', 'Унікальна Україна', 'http://uu-travel.com/', '', '', '', '');
INSERT INTO user_work_prof VALUES (17, 1, 'Київ', 'Секретаріат Президента України', '', 'Член Національної комісії з утвердження свободи слова та розвитку інформаційної галузі', '', '', '');
INSERT INTO user_work_prof VALUES (17, 1, 'Київ', 'Інститут масоої Інформації', '', 'Директор', '', '', '');
INSERT INTO user_work_prof VALUES (18, 1, 'Київ', '5-ий канал', 'www.5.ua', 'Ведучий', '', '', '');


--
-- Data for Name: user_work_public; Type: TABLE DATA; Schema: public; Owner: auzo
--

INSERT INTO user_work_public VALUES (1360, 'gdsfgfdsgdfs', 'dfgsdf', 'fdg', 'hhhhh', '2011-03-14', '2011-03-30');
INSERT INTO user_work_public VALUES (1360, '', '', '', '', '', '');
INSERT INTO user_work_public VALUES (1, 'AIESEC', 'http://www.aiesec.org.ua/', 'Coordinator Non-corporate', '', '', '');
INSERT INTO user_work_public VALUES (1, 'Зробимо Україну чистою', 'http://letsdoit.org.ua/', 'волонтер, PR', 'робота зі ЗМІ', '', '');
INSERT INTO user_work_public VALUES (16, 'Українська Громада', 'http://ukrhromada.org/', 'Виконавчий директор', '', '', '');


--
-- Name: attentions_pkey; Type: CONSTRAINT; Schema: public; Owner: auzo; Tablespace: 
--

ALTER TABLE ONLY attentions
    ADD CONSTRAINT attentions_pkey PRIMARY KEY (id);


--
-- Name: banners_pkey; Type: CONSTRAINT; Schema: public; Owner: auzo; Tablespace: 
--

ALTER TABLE ONLY banners
    ADD CONSTRAINT banners_pkey PRIMARY KEY (id);


--
-- Name: blog_post_id; Type: CONSTRAINT; Schema: public; Owner: auzo; Tablespace: 
--

ALTER TABLE ONLY blogs_posts
    ADD CONSTRAINT blog_post_id PRIMARY KEY (id);


--
-- Name: blogs_comments_pkey; Type: CONSTRAINT; Schema: public; Owner: auzo; Tablespace: 
--

ALTER TABLE ONLY blogs_comments
    ADD CONSTRAINT blogs_comments_pkey PRIMARY KEY (id);


--
-- Name: blogs_mentions_pkey; Type: CONSTRAINT; Schema: public; Owner: auzo; Tablespace: 
--

ALTER TABLE ONLY blogs_mentions
    ADD CONSTRAINT blogs_mentions_pkey PRIMARY KEY (post_id, user_id);


--
-- Name: blogs_posts_tags_pkey; Type: CONSTRAINT; Schema: public; Owner: auzo; Tablespace: 
--

ALTER TABLE ONLY blogs_posts_tags
    ADD CONSTRAINT blogs_posts_tags_pkey PRIMARY KEY (post_id, tag_id);


--
-- Name: blogs_tags_id; Type: CONSTRAINT; Schema: public; Owner: auzo; Tablespace: 
--

ALTER TABLE ONLY blogs_tags
    ADD CONSTRAINT blogs_tags_id PRIMARY KEY (id);


--
-- Name: bookmarks_pkey; Type: CONSTRAINT; Schema: public; Owner: auzo; Tablespace: 
--

ALTER TABLE ONLY bookmarks
    ADD CONSTRAINT bookmarks_pkey PRIMARY KEY (id);


--
-- Name: bookmarks_unique; Type: CONSTRAINT; Schema: public; Owner: auzo; Tablespace: 
--

ALTER TABLE ONLY bookmarks
    ADD CONSTRAINT bookmarks_unique UNIQUE (user_id, type, oid);


--
-- Name: candidate_forecast_user; Type: CONSTRAINT; Schema: public; Owner: auzo; Tablespace: 
--

ALTER TABLE ONLY candidates_forecast
    ADD CONSTRAINT candidate_forecast_user PRIMARY KEY (user_id, candidate_id);


--
-- Name: candidate_user; Type: CONSTRAINT; Schema: public; Owner: auzo; Tablespace: 
--

ALTER TABLE ONLY candidates
    ADD CONSTRAINT candidate_user PRIMARY KEY (user_id);


--
-- Name: candidate_vote; Type: CONSTRAINT; Schema: public; Owner: auzo; Tablespace: 
--

ALTER TABLE ONLY candidates_votes
    ADD CONSTRAINT candidate_vote PRIMARY KEY (user_id, candidate_id);


--
-- Name: cities_pkey; Type: CONSTRAINT; Schema: public; Owner: auzo; Tablespace: 
--

ALTER TABLE ONLY cities
    ADD CONSTRAINT cities_pkey PRIMARY KEY (id);


--
-- Name: comments_pkey; Type: CONSTRAINT; Schema: public; Owner: auzo; Tablespace: 
--

ALTER TABLE ONLY comments
    ADD CONSTRAINT comments_pkey PRIMARY KEY (id);


--
-- Name: complaints_pkey; Type: CONSTRAINT; Schema: public; Owner: auzo; Tablespace: 
--

ALTER TABLE ONLY complaints
    ADD CONSTRAINT complaints_pkey PRIMARY KEY (id);


--
-- Name: country_pkey; Type: CONSTRAINT; Schema: public; Owner: auzo; Tablespace: 
--

ALTER TABLE ONLY countries
    ADD CONSTRAINT country_pkey PRIMARY KEY (id);


--
-- Name: debates_arguments_pkey; Type: CONSTRAINT; Schema: public; Owner: auzo; Tablespace: 
--

ALTER TABLE ONLY debates_arguments
    ADD CONSTRAINT debates_arguments_pkey PRIMARY KEY (id);


--
-- Name: debates_debates_tags_pkey; Type: CONSTRAINT; Schema: public; Owner: auzo; Tablespace: 
--

ALTER TABLE ONLY debates_debates_tags
    ADD CONSTRAINT debates_debates_tags_pkey PRIMARY KEY (debate_id, tag_id);


--
-- Name: debates_pkey; Type: CONSTRAINT; Schema: public; Owner: auzo; Tablespace: 
--

ALTER TABLE ONLY debates
    ADD CONSTRAINT debates_pkey PRIMARY KEY (id);


--
-- Name: debates_tags_id; Type: CONSTRAINT; Schema: public; Owner: auzo; Tablespace: 
--

ALTER TABLE ONLY debates_tags
    ADD CONSTRAINT debates_tags_id PRIMARY KEY (id);


--
-- Name: districts_pkey; Type: CONSTRAINT; Schema: public; Owner: auzo; Tablespace: 
--

ALTER TABLE ONLY districts
    ADD CONSTRAINT districts_pkey PRIMARY KEY (id);


--
-- Name: drafts_id; Type: CONSTRAINT; Schema: public; Owner: auzo; Tablespace: 
--

ALTER TABLE ONLY drafts
    ADD CONSTRAINT drafts_id PRIMARY KEY (id);


--
-- Name: email_lists_pkey; Type: CONSTRAINT; Schema: public; Owner: auzo; Tablespace: 
--

ALTER TABLE ONLY email_lists
    ADD CONSTRAINT email_lists_pkey PRIMARY KEY (id);


--
-- Name: email_system_id; Type: CONSTRAINT; Schema: public; Owner: auzo; Tablespace: 
--

ALTER TABLE ONLY email_system
    ADD CONSTRAINT email_system_id PRIMARY KEY (id);


--
-- Name: events_comments_pkey; Type: CONSTRAINT; Schema: public; Owner: auzo; Tablespace: 
--

ALTER TABLE ONLY events_comments
    ADD CONSTRAINT events_comments_pkey PRIMARY KEY (id);


--
-- Name: feed_pkey; Type: CONSTRAINT; Schema: public; Owner: auzo; Tablespace: 
--

ALTER TABLE ONLY feed
    ADD CONSTRAINT feed_pkey PRIMARY KEY (id);


--
-- Name: files_dirs_pkey; Type: CONSTRAINT; Schema: public; Owner: auzo; Tablespace: 
--

ALTER TABLE ONLY files_dirs
    ADD CONSTRAINT files_dirs_pkey PRIMARY KEY (id);


--
-- Name: files_pkey; Type: CONSTRAINT; Schema: public; Owner: auzo; Tablespace: 
--

ALTER TABLE ONLY files
    ADD CONSTRAINT files_pkey PRIMARY KEY (id);


--
-- Name: friends_pending_pkey; Type: CONSTRAINT; Schema: public; Owner: auzo; Tablespace: 
--

ALTER TABLE ONLY friends_pending
    ADD CONSTRAINT friends_pending_pkey PRIMARY KEY (id);


--
-- Name: friends_pkey; Type: CONSTRAINT; Schema: public; Owner: auzo; Tablespace: 
--

ALTER TABLE ONLY friends
    ADD CONSTRAINT friends_pkey PRIMARY KEY (id);


--
-- Name: groups_applicants_pkey; Type: CONSTRAINT; Schema: public; Owner: auzo; Tablespace: 
--

ALTER TABLE ONLY groups_applicants
    ADD CONSTRAINT groups_applicants_pkey PRIMARY KEY (group_id, user_id);


--
-- Name: groups_files_dirs_pkey; Type: CONSTRAINT; Schema: public; Owner: auzo; Tablespace: 
--

ALTER TABLE ONLY groups_files_dirs
    ADD CONSTRAINT groups_files_dirs_pkey PRIMARY KEY (id);


--
-- Name: groups_files_pkey; Type: CONSTRAINT; Schema: public; Owner: auzo; Tablespace: 
--

ALTER TABLE ONLY groups_files
    ADD CONSTRAINT groups_files_pkey PRIMARY KEY (id);


--
-- Name: groups_links_pkey; Type: CONSTRAINT; Schema: public; Owner: auzo; Tablespace: 
--

ALTER TABLE ONLY groups_links
    ADD CONSTRAINT groups_links_pkey PRIMARY KEY (id);


--
-- Name: groups_members_pkey; Type: CONSTRAINT; Schema: public; Owner: auzo; Tablespace: 
--

ALTER TABLE ONLY groups_members
    ADD CONSTRAINT groups_members_pkey PRIMARY KEY (group_id, user_id);


--
-- Name: groups_news_pkey; Type: CONSTRAINT; Schema: public; Owner: auzo; Tablespace: 
--

ALTER TABLE ONLY groups_news
    ADD CONSTRAINT groups_news_pkey PRIMARY KEY (id);


--
-- Name: groups_photo_comments_pkey; Type: CONSTRAINT; Schema: public; Owner: auzo; Tablespace: 
--

ALTER TABLE ONLY groups_photo_comments
    ADD CONSTRAINT groups_photo_comments_pkey PRIMARY KEY (id);


--
-- Name: groups_photos_albums_pkey; Type: CONSTRAINT; Schema: public; Owner: auzo; Tablespace: 
--

ALTER TABLE ONLY groups_photos_albums
    ADD CONSTRAINT groups_photos_albums_pkey PRIMARY KEY (id);


--
-- Name: groups_photos_pkey; Type: CONSTRAINT; Schema: public; Owner: auzo; Tablespace: 
--

ALTER TABLE ONLY groups_photos
    ADD CONSTRAINT groups_photos_pkey PRIMARY KEY (id);


--
-- Name: groups_pkey; Type: CONSTRAINT; Schema: public; Owner: auzo; Tablespace: 
--

ALTER TABLE ONLY groups
    ADD CONSTRAINT groups_pkey PRIMARY KEY (id);


--
-- Name: groups_position_pkey; Type: CONSTRAINT; Schema: public; Owner: auzo; Tablespace: 
--

ALTER TABLE ONLY groups_position
    ADD CONSTRAINT groups_position_pkey PRIMARY KEY (id);


--
-- Name: groups_proposal_pkey; Type: CONSTRAINT; Schema: public; Owner: auzo; Tablespace: 
--

ALTER TABLE ONLY groups_proposal
    ADD CONSTRAINT groups_proposal_pkey PRIMARY KEY (id);


--
-- Name: groups_topics_pkey; Type: CONSTRAINT; Schema: public; Owner: auzo; Tablespace: 
--

ALTER TABLE ONLY groups_topics
    ADD CONSTRAINT groups_topics_pkey PRIMARY KEY (id);


--
-- Name: id; Type: CONSTRAINT; Schema: public; Owner: auzo; Tablespace: 
--

ALTER TABLE ONLY user_auth
    ADD CONSTRAINT id PRIMARY KEY (id);


--
-- Name: id_key; Type: CONSTRAINT; Schema: public; Owner: auzo; Tablespace: 
--

ALTER TABLE ONLY user_desktop_signatures
    ADD CONSTRAINT id_key PRIMARY KEY (id);


--
-- Name: ideas_comments_pkey; Type: CONSTRAINT; Schema: public; Owner: auzo; Tablespace: 
--

ALTER TABLE ONLY ideas_comments
    ADD CONSTRAINT ideas_comments_pkey PRIMARY KEY (id);


--
-- Name: ideas_pkey; Type: CONSTRAINT; Schema: public; Owner: auzo; Tablespace: 
--

ALTER TABLE ONLY ideas
    ADD CONSTRAINT ideas_pkey PRIMARY KEY (id);


--
-- Name: ideas_tags_name_key; Type: CONSTRAINT; Schema: public; Owner: auzo; Tablespace: 
--

ALTER TABLE ONLY ideas_tags
    ADD CONSTRAINT ideas_tags_name_key UNIQUE (name);


--
-- Name: ideas_tags_pkey; Type: CONSTRAINT; Schema: public; Owner: auzo; Tablespace: 
--

ALTER TABLE ONLY ideas_to_tags
    ADD CONSTRAINT ideas_tags_pkey PRIMARY KEY (idea_id, tag_id);


--
-- Name: ideas_tags_pkey1; Type: CONSTRAINT; Schema: public; Owner: auzo; Tablespace: 
--

ALTER TABLE ONLY ideas_tags
    ADD CONSTRAINT ideas_tags_pkey1 PRIMARY KEY (id);


--
-- Name: invites_pkey; Type: CONSTRAINT; Schema: public; Owner: auzo; Tablespace: 
--

ALTER TABLE ONLY invites
    ADD CONSTRAINT invites_pkey PRIMARY KEY (id);


--
-- Name: leader_groups_applicants_pkey; Type: CONSTRAINT; Schema: public; Owner: auzo; Tablespace: 
--

ALTER TABLE ONLY leadergroups_applicants
    ADD CONSTRAINT leader_groups_applicants_pkey PRIMARY KEY (leadergroup_id, user_id);


--
-- Name: leader_groups_files_pkey; Type: CONSTRAINT; Schema: public; Owner: auzo; Tablespace: 
--

ALTER TABLE ONLY leadergroups_files
    ADD CONSTRAINT leader_groups_files_pkey PRIMARY KEY (id);


--
-- Name: leader_groups_members_pkey; Type: CONSTRAINT; Schema: public; Owner: auzo; Tablespace: 
--

ALTER TABLE ONLY leadergroups_members
    ADD CONSTRAINT leader_groups_members_pkey PRIMARY KEY (leadergroup_id, user_id);


--
-- Name: leader_groups_news_pkey; Type: CONSTRAINT; Schema: public; Owner: auzo; Tablespace: 
--

ALTER TABLE ONLY leadergroups_news
    ADD CONSTRAINT leader_groups_news_pkey PRIMARY KEY (id);


--
-- Name: leader_groups_photo_comments_pkey; Type: CONSTRAINT; Schema: public; Owner: auzo; Tablespace: 
--

ALTER TABLE ONLY leadergroups_photo_comments
    ADD CONSTRAINT leader_groups_photo_comments_pkey PRIMARY KEY (id);


--
-- Name: leader_groups_photos_albums_pkey; Type: CONSTRAINT; Schema: public; Owner: auzo; Tablespace: 
--

ALTER TABLE ONLY leadergroups_photos_albums
    ADD CONSTRAINT leader_groups_photos_albums_pkey PRIMARY KEY (id);


--
-- Name: leader_groups_photos_pkey; Type: CONSTRAINT; Schema: public; Owner: auzo; Tablespace: 
--

ALTER TABLE ONLY leadergroups_photos
    ADD CONSTRAINT leader_groups_photos_pkey PRIMARY KEY (id);


--
-- Name: leader_groups_pkey; Type: CONSTRAINT; Schema: public; Owner: auzo; Tablespace: 
--

ALTER TABLE ONLY leadergroups
    ADD CONSTRAINT leader_groups_pkey PRIMARY KEY (id);


--
-- Name: leader_groups_topics_pkey; Type: CONSTRAINT; Schema: public; Owner: auzo; Tablespace: 
--

ALTER TABLE ONLY leadergroups_topics
    ADD CONSTRAINT leader_groups_topics_pkey PRIMARY KEY (id);


--
-- Name: mailing_pkey; Type: CONSTRAINT; Schema: public; Owner: auzo; Tablespace: 
--

ALTER TABLE ONLY mailing
    ADD CONSTRAINT mailing_pkey PRIMARY KEY (id);


--
-- Name: mailing_send_mails_pkey; Type: CONSTRAINT; Schema: public; Owner: auzo; Tablespace: 
--

ALTER TABLE ONLY mailing_send_mails
    ADD CONSTRAINT mailing_send_mails_pkey PRIMARY KEY (id);


--
-- Name: messages_pkey; Type: CONSTRAINT; Schema: public; Owner: auzo; Tablespace: 
--

ALTER TABLE ONLY messages
    ADD CONSTRAINT messages_pkey PRIMARY KEY (id);


--
-- Name: messages_threads_pkey; Type: CONSTRAINT; Schema: public; Owner: auzo; Tablespace: 
--

ALTER TABLE ONLY messages_threads
    ADD CONSTRAINT messages_threads_pkey PRIMARY KEY (id);


--
-- Name: parties_members_pkey; Type: CONSTRAINT; Schema: public; Owner: auzo; Tablespace: 
--

ALTER TABLE ONLY parties_members
    ADD CONSTRAINT parties_members_pkey PRIMARY KEY (user_id);


--
-- Name: parties_news_pkey; Type: CONSTRAINT; Schema: public; Owner: auzo; Tablespace: 
--

ALTER TABLE ONLY parties_news
    ADD CONSTRAINT parties_news_pkey PRIMARY KEY (id);


--
-- Name: parties_pkey; Type: CONSTRAINT; Schema: public; Owner: auzo; Tablespace: 
--

ALTER TABLE ONLY parties
    ADD CONSTRAINT parties_pkey PRIMARY KEY (id);


--
-- Name: parties_program_pkey; Type: CONSTRAINT; Schema: public; Owner: auzo; Tablespace: 
--

ALTER TABLE ONLY parties_program
    ADD CONSTRAINT parties_program_pkey PRIMARY KEY (id);


--
-- Name: parties_topics_messages_pkey; Type: CONSTRAINT; Schema: public; Owner: auzo; Tablespace: 
--

ALTER TABLE ONLY parties_topics_messages
    ADD CONSTRAINT parties_topics_messages_pkey PRIMARY KEY (id);


--
-- Name: parties_topics_pkey; Type: CONSTRAINT; Schema: public; Owner: auzo; Tablespace: 
--

ALTER TABLE ONLY parties_topics
    ADD CONSTRAINT parties_topics_pkey PRIMARY KEY (id);


--
-- Name: polls_answers_pkey; Type: CONSTRAINT; Schema: public; Owner: auzo; Tablespace: 
--

ALTER TABLE ONLY polls_answers
    ADD CONSTRAINT polls_answers_pkey PRIMARY KEY (id);


--
-- Name: polls_comments_pk; Type: CONSTRAINT; Schema: public; Owner: auzo; Tablespace: 
--

ALTER TABLE ONLY polls_comments
    ADD CONSTRAINT polls_comments_pk PRIMARY KEY (id);


--
-- Name: polls_custom_pkey; Type: CONSTRAINT; Schema: public; Owner: auzo; Tablespace: 
--

ALTER TABLE ONLY polls_custom
    ADD CONSTRAINT polls_custom_pkey PRIMARY KEY (poll_id, user_id);


--
-- Name: polls_pkey; Type: CONSTRAINT; Schema: public; Owner: auzo; Tablespace: 
--

ALTER TABLE ONLY polls
    ADD CONSTRAINT polls_pkey PRIMARY KEY (id);


--
-- Name: polls_votes_pkey; Type: CONSTRAINT; Schema: public; Owner: auzo; Tablespace: 
--

ALTER TABLE ONLY polls_votes
    ADD CONSTRAINT polls_votes_pkey PRIMARY KEY (id);


--
-- Name: rate_history_pkey; Type: CONSTRAINT; Schema: public; Owner: auzo; Tablespace: 
--

ALTER TABLE ONLY rate_history
    ADD CONSTRAINT rate_history_pkey PRIMARY KEY (id);


--
-- Name: regions_pkey; Type: CONSTRAINT; Schema: public; Owner: auzo; Tablespace: 
--

ALTER TABLE ONLY regions
    ADD CONSTRAINT regions_pkey PRIMARY KEY (id);


--
-- Name: user_bio_pkey; Type: CONSTRAINT; Schema: public; Owner: auzo; Tablespace: 
--

ALTER TABLE ONLY user_bio
    ADD CONSTRAINT user_bio_pkey PRIMARY KEY (user_id);


--
-- Name: user_contact_pkey; Type: CONSTRAINT; Schema: public; Owner: auzo; Tablespace: 
--

ALTER TABLE ONLY user_contact
    ADD CONSTRAINT user_contact_pkey PRIMARY KEY (id);


--
-- Name: user_desktop_education_pkey; Type: CONSTRAINT; Schema: public; Owner: auzo; Tablespace: 
--

ALTER TABLE ONLY user_desktop_education
    ADD CONSTRAINT user_desktop_education_pkey PRIMARY KEY (id);


--
-- Name: user_desktop_event_pkey; Type: CONSTRAINT; Schema: public; Owner: auzo; Tablespace: 
--

ALTER TABLE ONLY user_desktop_event
    ADD CONSTRAINT user_desktop_event_pkey PRIMARY KEY (id);


--
-- Name: user_desktop_meeting_pkey; Type: CONSTRAINT; Schema: public; Owner: auzo; Tablespace: 
--

ALTER TABLE ONLY user_desktop_meeting
    ADD CONSTRAINT user_desktop_meeting_pkey PRIMARY KEY (id);


--
-- Name: user_desktop_pkey; Type: CONSTRAINT; Schema: public; Owner: auzo; Tablespace: 
--

ALTER TABLE ONLY user_desktop
    ADD CONSTRAINT user_desktop_pkey PRIMARY KEY (user_id);


--
-- Name: user_dictionaty_user; Type: CONSTRAINT; Schema: public; Owner: auzo; Tablespace: 
--

ALTER TABLE ONLY user_dictionary
    ADD CONSTRAINT user_dictionaty_user PRIMARY KEY (user_id);


--
-- Name: user_education_pkey; Type: CONSTRAINT; Schema: public; Owner: auzo; Tablespace: 
--

ALTER TABLE ONLY user_education
    ADD CONSTRAINT user_education_pkey PRIMARY KEY (user_id);


--
-- Name: user_id; Type: CONSTRAINT; Schema: public; Owner: auzo; Tablespace: 
--

ALTER TABLE ONLY user_data
    ADD CONSTRAINT user_id PRIMARY KEY (user_id);


--
-- Name: user_mail_access_pkey; Type: CONSTRAINT; Schema: public; Owner: auzo; Tablespace: 
--

ALTER TABLE ONLY user_mail_access
    ADD CONSTRAINT user_mail_access_pkey PRIMARY KEY (user_id);


--
-- Name: user_questions_pkey; Type: CONSTRAINT; Schema: public; Owner: auzo; Tablespace: 
--

ALTER TABLE ONLY user_questions
    ADD CONSTRAINT user_questions_pkey PRIMARY KEY (id);


--
-- Name: user_recomendations_pkey; Type: CONSTRAINT; Schema: public; Owner: auzo; Tablespace: 
--

ALTER TABLE ONLY user_recomendations
    ADD CONSTRAINT user_recomendations_pkey PRIMARY KEY (id);


--
-- Name: user_sessions_pkey; Type: CONSTRAINT; Schema: public; Owner: auzo; Tablespace: 
--

ALTER TABLE ONLY user_sessions
    ADD CONSTRAINT user_sessions_pkey PRIMARY KEY (user_id);


--
-- Name: user_temp_photos_pkey; Type: CONSTRAINT; Schema: public; Owner: auzo; Tablespace: 
--

ALTER TABLE ONLY user_temp_photos
    ADD CONSTRAINT user_temp_photos_pkey PRIMARY KEY (user_id);


--
-- Name: user_temp_photos_salt_key; Type: CONSTRAINT; Schema: public; Owner: auzo; Tablespace: 
--

ALTER TABLE ONLY user_temp_photos
    ADD CONSTRAINT user_temp_photos_salt_key UNIQUE (photo);


--
-- Name: user_work_pkey; Type: CONSTRAINT; Schema: public; Owner: auzo; Tablespace: 
--

ALTER TABLE ONLY user_work
    ADD CONSTRAINT user_work_pkey PRIMARY KEY (user_id);


--
-- Name: blogs_comments_parent_id; Type: INDEX; Schema: public; Owner: auzo; Tablespace: 
--

CREATE INDEX blogs_comments_parent_id ON blogs_comments USING btree (parent_id);


--
-- Name: blogs_comments_post_id; Type: INDEX; Schema: public; Owner: auzo; Tablespace: 
--

CREATE INDEX blogs_comments_post_id ON blogs_comments USING btree (post_id);


--
-- Name: blogs_posts_created; Type: INDEX; Schema: public; Owner: auzo; Tablespace: 
--

CREATE INDEX blogs_posts_created ON blogs_posts USING btree (created_ts, id);


--
-- Name: blogs_posts_created_ts; Type: INDEX; Schema: public; Owner: auzo; Tablespace: 
--

CREATE INDEX blogs_posts_created_ts ON blogs_posts USING btree (created_ts);


--
-- Name: blogs_posts_rate; Type: INDEX; Schema: public; Owner: auzo; Tablespace: 
--

CREATE INDEX blogs_posts_rate ON blogs_posts USING btree ("for");


--
-- Name: blogs_posts_sort; Type: INDEX; Schema: public; Owner: auzo; Tablespace: 
--

CREATE INDEX blogs_posts_sort ON blogs_posts USING btree (sort_ts);


--
-- Name: candidate_vote_index; Type: INDEX; Schema: public; Owner: auzo; Tablespace: 
--

CREATE INDEX candidate_vote_index ON candidates_votes USING btree (candidate_id);


--
-- Name: cities_name_ru; Type: INDEX; Schema: public; Owner: auzo; Tablespace: 
--

CREATE INDEX cities_name_ru ON cities USING btree (name_ru);


--
-- Name: cities_name_ua; Type: INDEX; Schema: public; Owner: auzo; Tablespace: 
--

CREATE INDEX cities_name_ua ON cities USING btree (name_ua);


--
-- Name: cities_region; Type: INDEX; Schema: public; Owner: auzo; Tablespace: 
--

CREATE INDEX cities_region ON cities USING btree (region_id);


--
-- Name: comments_oidid; Type: INDEX; Schema: public; Owner: auzo; Tablespace: 
--

CREATE INDEX comments_oidid ON comments USING btree (otype, oid);


--
-- Name: comments_parent_id; Type: INDEX; Schema: public; Owner: auzo; Tablespace: 
--

CREATE INDEX comments_parent_id ON comments USING btree (parent_id);


--
-- Name: debate_argument_debate_id; Type: INDEX; Schema: public; Owner: auzo; Tablespace: 
--

CREATE INDEX debate_argument_debate_id ON debates_arguments USING btree (debate_id);


--
-- Name: debate_tag_id; Type: INDEX; Schema: public; Owner: auzo; Tablespace: 
--

CREATE INDEX debate_tag_id ON debates_debates_tags USING btree (tag_id, debate_id);


--
-- Name: debates_for_against; Type: INDEX; Schema: public; Owner: auzo; Tablespace: 
--

CREATE INDEX debates_for_against ON debates USING btree ("for", against);


--
-- Name: debates_tag_name; Type: INDEX; Schema: public; Owner: auzo; Tablespace: 
--

CREATE UNIQUE INDEX debates_tag_name ON debates_tags USING btree (name);


--
-- Name: debates_user_id; Type: INDEX; Schema: public; Owner: auzo; Tablespace: 
--

CREATE INDEX debates_user_id ON debates USING btree (user_id);


--
-- Name: email_index; Type: INDEX; Schema: public; Owner: auzo; Tablespace: 
--

CREATE UNIQUE INDEX email_index ON email_users USING btree (email);


--
-- Name: events_comments_event_id; Type: INDEX; Schema: public; Owner: auzo; Tablespace: 
--

CREATE INDEX events_comments_event_id ON events_comments USING btree (event_id);


--
-- Name: events_comments_parent_id; Type: INDEX; Schema: public; Owner: auzo; Tablespace: 
--

CREATE INDEX events_comments_parent_id ON events_comments USING btree (parent_id);


--
-- Name: feed_user_idx; Type: INDEX; Schema: public; Owner: auzo; Tablespace: 
--

CREATE INDEX feed_user_idx ON feed USING btree (user_id);


--
-- Name: friend_pengind_user_id; Type: INDEX; Schema: public; Owner: auzo; Tablespace: 
--

CREATE INDEX friend_pengind_user_id ON friends_pending USING btree (user_id);


--
-- Name: friend_user_id; Type: INDEX; Schema: public; Owner: auzo; Tablespace: 
--

CREATE INDEX friend_user_id ON friends USING btree (user_id, friend_id);


--
-- Name: groups_member_user; Type: INDEX; Schema: public; Owner: auzo; Tablespace: 
--

CREATE INDEX groups_member_user ON groups_members USING btree (user_id, group_id);


--
-- Name: groups_messages_position; Type: INDEX; Schema: public; Owner: auzo; Tablespace: 
--

CREATE INDEX groups_messages_position ON groups_position_messages USING btree (topic_id);


--
-- Name: groups_messages_proposal; Type: INDEX; Schema: public; Owner: auzo; Tablespace: 
--

CREATE INDEX groups_messages_proposal ON groups_proposal_messages USING btree (topic_id);


--
-- Name: groups_messages_topic; Type: INDEX; Schema: public; Owner: auzo; Tablespace: 
--

CREATE INDEX groups_messages_topic ON groups_topics_messages USING btree (topic_id);


--
-- Name: groups_news_group; Type: INDEX; Schema: public; Owner: auzo; Tablespace: 
--

CREATE INDEX groups_news_group ON groups_news USING btree (group_id);


--
-- Name: groups_photo_comments_parent_id; Type: INDEX; Schema: public; Owner: auzo; Tablespace: 
--

CREATE INDEX groups_photo_comments_parent_id ON groups_photo_comments USING btree (parent_id);


--
-- Name: groups_photo_comments_photo_id; Type: INDEX; Schema: public; Owner: auzo; Tablespace: 
--

CREATE INDEX groups_photo_comments_photo_id ON groups_photo_comments USING btree (photo_id);


--
-- Name: groups_position_group; Type: INDEX; Schema: public; Owner: auzo; Tablespace: 
--

CREATE INDEX groups_position_group ON groups_position USING btree (group_id);


--
-- Name: groups_proposal_group; Type: INDEX; Schema: public; Owner: auzo; Tablespace: 
--

CREATE INDEX groups_proposal_group ON groups_proposal USING btree (group_id);


--
-- Name: groups_rate; Type: INDEX; Schema: public; Owner: auzo; Tablespace: 
--

CREATE INDEX groups_rate ON groups USING btree (rate);


--
-- Name: groups_teritory_rate; Type: INDEX; Schema: public; Owner: auzo; Tablespace: 
--

CREATE INDEX groups_teritory_rate ON groups USING btree (teritory, rate);


--
-- Name: groups_topic_group; Type: INDEX; Schema: public; Owner: auzo; Tablespace: 
--

CREATE INDEX groups_topic_group ON groups_topics USING btree (group_id);


--
-- Name: groups_type_rate; Type: INDEX; Schema: public; Owner: auzo; Tablespace: 
--

CREATE INDEX groups_type_rate ON groups USING btree (type, rate);


--
-- Name: id_index; Type: INDEX; Schema: public; Owner: auzo; Tablespace: 
--

CREATE UNIQUE INDEX id_index ON email_users USING btree (id);


--
-- Name: ideas_comments_id; Type: INDEX; Schema: public; Owner: auzo; Tablespace: 
--

CREATE INDEX ideas_comments_id ON ideas_comments USING btree (idea_id);


--
-- Name: ideas_comments_parent_id; Type: INDEX; Schema: public; Owner: auzo; Tablespace: 
--

CREATE INDEX ideas_comments_parent_id ON ideas_comments USING btree (parent_id);


--
-- Name: ideas_rate; Type: INDEX; Schema: public; Owner: auzo; Tablespace: 
--

CREATE INDEX ideas_rate ON ideas USING btree (rate);


--
-- Name: ideas_segment; Type: INDEX; Schema: public; Owner: auzo; Tablespace: 
--

CREATE INDEX ideas_segment ON ideas USING btree (segment, rate);


--
-- Name: ideas_user; Type: INDEX; Schema: public; Owner: auzo; Tablespace: 
--

CREATE INDEX ideas_user ON ideas USING btree (user_id);


--
-- Name: leader_groups_member_user; Type: INDEX; Schema: public; Owner: auzo; Tablespace: 
--

CREATE INDEX leader_groups_member_user ON leadergroups_members USING btree (user_id, leadergroup_id);


--
-- Name: leader_groups_messages_topic; Type: INDEX; Schema: public; Owner: auzo; Tablespace: 
--

CREATE INDEX leader_groups_messages_topic ON leadergroups_topics_messages USING btree (topic_id);


--
-- Name: leader_groups_news_group; Type: INDEX; Schema: public; Owner: auzo; Tablespace: 
--

CREATE INDEX leader_groups_news_group ON leadergroups_news USING btree (leadergroup_id);


--
-- Name: leader_groups_photo_comments_parent_id; Type: INDEX; Schema: public; Owner: auzo; Tablespace: 
--

CREATE INDEX leader_groups_photo_comments_parent_id ON leadergroups_photo_comments USING btree (parent_id);


--
-- Name: leader_groups_photo_comments_photo_id; Type: INDEX; Schema: public; Owner: auzo; Tablespace: 
--

CREATE INDEX leader_groups_photo_comments_photo_id ON leadergroups_photo_comments USING btree (photo_id);


--
-- Name: leader_groups_rate; Type: INDEX; Schema: public; Owner: auzo; Tablespace: 
--

CREATE INDEX leader_groups_rate ON leadergroups USING btree (rate);


--
-- Name: leader_groups_teritory_rate; Type: INDEX; Schema: public; Owner: auzo; Tablespace: 
--

CREATE INDEX leader_groups_teritory_rate ON leadergroups USING btree (teritory, rate);


--
-- Name: leader_groups_topic_group; Type: INDEX; Schema: public; Owner: auzo; Tablespace: 
--

CREATE INDEX leader_groups_topic_group ON leadergroups_topics USING btree (leadergroup_id);


--
-- Name: leader_groups_type_rate; Type: INDEX; Schema: public; Owner: auzo; Tablespace: 
--

CREATE INDEX leader_groups_type_rate ON leadergroups USING btree (type, rate);


--
-- Name: list_id_index; Type: INDEX; Schema: public; Owner: auzo; Tablespace: 
--

CREATE INDEX list_id_index ON email_lists_users USING btree (user_id);


--
-- Name: mailing_id_index; Type: INDEX; Schema: public; Owner: auzo; Tablespace: 
--

CREATE INDEX mailing_id_index ON mailing_send_mails USING btree (mailing_id);


--
-- Name: mega_index; Type: INDEX; Schema: public; Owner: auzo; Tablespace: 
--

CREATE INDEX mega_index ON email_lists_users USING btree (user_id, list_id);


--
-- Name: messages_owner_read; Type: INDEX; Schema: public; Owner: auzo; Tablespace: 
--

CREATE INDEX messages_owner_read ON messages USING btree (owner, is_read);


--
-- Name: name; Type: INDEX; Schema: public; Owner: auzo; Tablespace: 
--

CREATE UNIQUE INDEX name ON blogs_tags USING btree (name);


--
-- Name: parties_members_party; Type: INDEX; Schema: public; Owner: auzo; Tablespace: 
--

CREATE INDEX parties_members_party ON parties_members USING btree (party_id);


--
-- Name: parties_messages_topic; Type: INDEX; Schema: public; Owner: auzo; Tablespace: 
--

CREATE INDEX parties_messages_topic ON parties_topics_messages USING btree (topic_id);


--
-- Name: parties_news_party; Type: INDEX; Schema: public; Owner: auzo; Tablespace: 
--

CREATE INDEX parties_news_party ON parties_news USING btree (party_id);


--
-- Name: parties_program_party; Type: INDEX; Schema: public; Owner: auzo; Tablespace: 
--

CREATE INDEX parties_program_party ON parties_program USING btree (party_id);


--
-- Name: parties_program_segment_for; Type: INDEX; Schema: public; Owner: auzo; Tablespace: 
--

CREATE INDEX parties_program_segment_for ON parties_program USING btree (segment, "for", against);


--
-- Name: parties_rate; Type: INDEX; Schema: public; Owner: auzo; Tablespace: 
--

CREATE INDEX parties_rate ON parties USING btree (rate);


--
-- Name: parties_topics_party; Type: INDEX; Schema: public; Owner: auzo; Tablespace: 
--

CREATE INDEX parties_topics_party ON parties_topics USING btree (party_id, messages_count);


--
-- Name: parties_trust_created; Type: INDEX; Schema: public; Owner: auzo; Tablespace: 
--

CREATE INDEX parties_trust_created ON parties_trust USING btree (created_ts);


--
-- Name: polls_anwer_poll; Type: INDEX; Schema: public; Owner: auzo; Tablespace: 
--

CREATE INDEX polls_anwer_poll ON polls_answers USING btree (poll_id);


--
-- Name: polls_comments_parent_id; Type: INDEX; Schema: public; Owner: auzo; Tablespace: 
--

CREATE INDEX polls_comments_parent_id ON polls_comments USING btree (parent_id);


--
-- Name: polls_comments_poll_id; Type: INDEX; Schema: public; Owner: auzo; Tablespace: 
--

CREATE INDEX polls_comments_poll_id ON polls_comments USING btree (poll_id);


--
-- Name: polls_custom_user_id; Type: INDEX; Schema: public; Owner: auzo; Tablespace: 
--

CREATE INDEX polls_custom_user_id ON polls_custom USING btree (poll_id);


--
-- Name: polls_user; Type: INDEX; Schema: public; Owner: auzo; Tablespace: 
--

CREATE INDEX polls_user ON polls USING btree (user_id);


--
-- Name: polls_votes_poll; Type: INDEX; Schema: public; Owner: auzo; Tablespace: 
--

CREATE INDEX polls_votes_poll ON polls_votes USING btree (poll_id);


--
-- Name: user_access_ip; Type: INDEX; Schema: public; Owner: auzo; Tablespace: 
--

CREATE INDEX user_access_ip ON user_access USING btree (ip);


--
-- Name: user_access_path; Type: INDEX; Schema: public; Owner: auzo; Tablespace: 
--

CREATE INDEX user_access_path ON user_access USING btree (module, action, id);


--
-- Name: user_access_referer; Type: INDEX; Schema: public; Owner: auzo; Tablespace: 
--

CREATE INDEX user_access_referer ON user_access USING btree (referer);


--
-- Name: user_access_ua; Type: INDEX; Schema: public; Owner: auzo; Tablespace: 
--

CREATE INDEX user_access_ua ON user_access USING btree (ua);


--
-- Name: user_access_user_id; Type: INDEX; Schema: public; Owner: auzo; Tablespace: 
--

CREATE INDEX user_access_user_id ON user_access USING btree (user_id);


--
-- Name: user_auth_activated_index; Type: INDEX; Schema: public; Owner: auzo; Tablespace: 
--

CREATE INDEX user_auth_activated_index ON user_auth USING btree (activated_ts);


--
-- Name: user_auth_security_code; Type: INDEX; Schema: public; Owner: auzo; Tablespace: 
--

CREATE INDEX user_auth_security_code ON user_auth USING btree (security_code);


--
-- Name: user_auth_type; Type: INDEX; Schema: public; Owner: auzo; Tablespace: 
--

CREATE INDEX user_auth_type ON user_auth USING btree (type);


--
-- Name: user_email_index; Type: INDEX; Schema: public; Owner: auzo; Tablespace: 
--

CREATE UNIQUE INDEX user_email_index ON user_auth USING btree (email);


--
-- Name: user_id_indes; Type: INDEX; Schema: public; Owner: auzo; Tablespace: 
--

CREATE INDEX user_id_indes ON blogs_posts USING btree (user_id);


--
-- Name: user_id_index; Type: INDEX; Schema: public; Owner: auzo; Tablespace: 
--

CREATE INDEX user_id_index ON email_lists_users USING btree (list_id);


--
-- Name: user_questions_profile; Type: INDEX; Schema: public; Owner: auzo; Tablespace: 
--

CREATE INDEX user_questions_profile ON user_questions USING btree (profile_id, rate);


--
-- Name: user_shevchenko_data_user_id; Type: INDEX; Schema: public; Owner: auzo; Tablespace: 
--

CREATE INDEX user_shevchenko_data_user_id ON user_shevchenko_data USING btree (user_id);


--
-- Name: public; Type: ACL; Schema: -; Owner: auzo
--

REVOKE ALL ON SCHEMA public FROM PUBLIC;
REVOKE ALL ON SCHEMA public FROM auzo;
GRANT ALL ON SCHEMA public TO auzo;
GRANT ALL ON SCHEMA public TO PUBLIC;


--
-- PostgreSQL database dump complete
--

