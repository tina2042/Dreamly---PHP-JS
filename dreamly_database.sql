--
-- PostgreSQL database dump
--

-- Dumped from database version 16.1 (Debian 16.1-1.pgdg120+1)
-- Dumped by pg_dump version 16.1

-- Started on 2024-01-24 22:19:49 UTC

SET statement_timeout = 0;
SET lock_timeout = 0;
SET idle_in_transaction_session_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SELECT pg_catalog.set_config('search_path', '', false);
SET check_function_bodies = false;
SET xmloption = content;
SET client_min_messages = warning;
SET row_security = off;

--
-- TOC entry 242 (class 1255 OID 24848)
-- Name: delete_comments_and_likes(); Type: FUNCTION; Schema: public; Owner: docker
--

CREATE FUNCTION public.delete_comments_and_likes() RETURNS trigger
    LANGUAGE plpgsql
    AS $$
BEGIN
    -- Delete comments associated with the deleted dream
    DELETE FROM comments WHERE dream_id = OLD.dream_id;

    -- Delete likes associated with the deleted dream
    DELETE FROM likes WHERE dream_id = OLD.dream_id;

    RETURN OLD;
END;
$$;


ALTER FUNCTION public.delete_comments_and_likes() OWNER TO docker;

--
-- TOC entry 256 (class 1255 OID 24854)
-- Name: delete_user(integer); Type: PROCEDURE; Schema: public; Owner: docker
--

CREATE PROCEDURE public.delete_user(IN p_user_id integer)
    LANGUAGE plpgsql
    AS $$
BEGIN

    DELETE FROM public.comments WHERE user_id = p_user_id;
    DELETE FROM public.likes WHERE user_id = p_user_id;
    DELETE FROM public.dreams WHERE user_id = p_user_id;

    DELETE from public.friends where user_id=p_user_id or 
                                     friend_id=p_user_id;
    DELETE FROM public.usersdetails WHERE detail_id IN (
        SELECT detail_id
        FROM public.users
        WHERE user_id = p_user_id
    );
 
    DELETE FROM public.users WHERE user_id = p_user_id;
    COMMIT;
END;
$$;


ALTER PROCEDURE public.delete_user(IN p_user_id integer) OWNER TO docker;

--
-- TOC entry 240 (class 1255 OID 16725)
-- Name: is_admin(integer); Type: FUNCTION; Schema: public; Owner: docker
--

CREATE FUNCTION public.is_admin(user_id_input integer) RETURNS boolean
    LANGUAGE plpgsql
    AS $$
DECLARE
    user_type_name character varying(255);
    is_administrator boolean;
BEGIN
    SELECT ut.role INTO user_type_name
    FROM public.users u
             JOIN public.role ut ON u.role_id = ut.role_id
    WHERE u.user_id = user_id_input;

    is_administrator := (user_type_name = 'admin');

    RETURN is_administrator;
END;
$$;


ALTER FUNCTION public.is_admin(user_id_input integer) OWNER TO docker;

--
-- TOC entry 243 (class 1255 OID 24856)
-- Name: like_or_unlike_dream(integer, integer); Type: PROCEDURE; Schema: public; Owner: docker
--

CREATE PROCEDURE public.like_or_unlike_dream(IN tuser_id integer, IN tdream_id integer)
    LANGUAGE plpgsql
    AS $$
BEGIN
    IF EXISTS (
        SELECT 1
        FROM likes
        WHERE user_id = tuser_id AND dream_id = tdream_id
    ) THEN
        DELETE FROM likes
        WHERE user_id = tuser_id AND dream_id = tdream_id;
    ELSE
        INSERT INTO likes (user_id, dream_id, date)
        VALUES (tuser_id, tdream_id, current_date);
    END IF;
END;
$$;


ALTER PROCEDURE public.like_or_unlike_dream(IN tuser_id integer, IN tdream_id integer) OWNER TO docker;

--
-- TOC entry 241 (class 1255 OID 24846)
-- Name: update_userstatistics_on_delete(); Type: FUNCTION; Schema: public; Owner: docker
--

CREATE FUNCTION public.update_userstatistics_on_delete() RETURNS trigger
    LANGUAGE plpgsql
    AS $$
BEGIN
    
    IF OLD.user_id IS NOT NULL THEN
       
        UPDATE userstatistics
        SET
            dreams_amount = (SELECT COUNT(dream_id) FROM dreams WHERE user_id = OLD.user_id),
            like_amount = (SELECT COUNT(like_id) FROM likes WHERE user_id = OLD.user_id),
            comments_amount = (SELECT COUNT(comment_id) FROM comments WHERE user_id = OLD.user_id)
        WHERE user_id = OLD.user_id;
    END IF;

    RETURN OLD;
END;
$$;


ALTER FUNCTION public.update_userstatistics_on_delete() OWNER TO docker;

--
-- TOC entry 255 (class 1255 OID 24837)
-- Name: update_userstatistics_on_insert(); Type: FUNCTION; Schema: public; Owner: docker
--

CREATE FUNCTION public.update_userstatistics_on_insert() RETURNS trigger
    LANGUAGE plpgsql
    AS $$
BEGIN
    -- Check if the user already exists in userstatistics
    IF EXISTS (
        SELECT 1
        FROM userstatistics
        WHERE user_id = NEW.user_id
    ) THEN
        -- If the user exists, update the existing record
        UPDATE userstatistics
        SET
            dreams_amount = (SELECT COUNT(dream_id) FROM dreams WHERE user_id = NEW.user_id),
            like_amount = (SELECT COUNT(like_id) FROM likes WHERE user_id = NEW.user_id),
            comments_amount = (SELECT COUNT(comment_id) FROM comments WHERE user_id = NEW.user_id)
        WHERE user_id = NEW.user_id;

    ELSE
        -- If the user doesn't exist, insert a new record
        INSERT INTO userstatistics (user_id, dreams_amount, like_amount, comments_amount)
        VALUES (
                   NEW.user_id,
                   (SELECT COUNT(dream_id) FROM dreams WHERE user_id = NEW.user_id),
                   (SELECT COUNT(like_id) FROM likes WHERE user_id = NEW.user_id),
                   (SELECT COUNT(comment_id) FROM comments WHERE user_id = NEW.user_id)
               );
    END IF;

    RETURN NEW;
END;
$$;


ALTER FUNCTION public.update_userstatistics_on_insert() OWNER TO docker;

SET default_tablespace = '';

SET default_table_access_method = heap;

--
-- TOC entry 215 (class 1259 OID 16408)
-- Name: users; Type: TABLE; Schema: public; Owner: docker
--

CREATE TABLE public.users (
    user_id integer NOT NULL,
    email character varying(255) NOT NULL,
    password character varying(255) NOT NULL,
    role_id integer DEFAULT 2 NOT NULL,
    detail_id integer NOT NULL
);


ALTER TABLE public.users OWNER TO docker;

--
-- TOC entry 216 (class 1259 OID 16417)
-- Name: Users_user_id_seq; Type: SEQUENCE; Schema: public; Owner: docker
--

ALTER TABLE public.users ALTER COLUMN user_id ADD GENERATED ALWAYS AS IDENTITY (
    SEQUENCE NAME public."Users_user_id_seq"
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1
);


--
-- TOC entry 218 (class 1259 OID 16471)
-- Name: comments; Type: TABLE; Schema: public; Owner: docker
--

CREATE TABLE public.comments (
    comment_id integer NOT NULL,
    dream_id integer,
    user_id integer,
    comment_content text NOT NULL,
    comment_date date NOT NULL
);


ALTER TABLE public.comments OWNER TO docker;

--
-- TOC entry 220 (class 1259 OID 16489)
-- Name: comments_comment_id_seq; Type: SEQUENCE; Schema: public; Owner: docker
--

ALTER TABLE public.comments ALTER COLUMN comment_id ADD GENERATED ALWAYS AS IDENTITY (
    SEQUENCE NAME public.comments_comment_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1
);


--
-- TOC entry 217 (class 1259 OID 16434)
-- Name: dreams; Type: TABLE; Schema: public; Owner: docker
--

CREATE TABLE public.dreams (
    dream_id integer NOT NULL,
    user_id integer NOT NULL,
    title text DEFAULT 'Untitled'::text,
    dream_content text NOT NULL,
    date date NOT NULL,
    privacy_id integer DEFAULT 1 NOT NULL
);


ALTER TABLE public.dreams OWNER TO docker;

--
-- TOC entry 219 (class 1259 OID 16488)
-- Name: dreams_dream_id_seq; Type: SEQUENCE; Schema: public; Owner: docker
--

ALTER TABLE public.dreams ALTER COLUMN dream_id ADD GENERATED ALWAYS AS IDENTITY (
    SEQUENCE NAME public.dreams_dream_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1
);


--
-- TOC entry 227 (class 1259 OID 16606)
-- Name: dreamsemotions; Type: TABLE; Schema: public; Owner: docker
--

CREATE TABLE public.dreamsemotions (
    dream_id integer NOT NULL,
    emotion_id integer NOT NULL
);


ALTER TABLE public.dreamsemotions OWNER TO docker;

--
-- TOC entry 229 (class 1259 OID 16618)
-- Name: emotions; Type: TABLE; Schema: public; Owner: docker
--

CREATE TABLE public.emotions (
    emotion_id integer NOT NULL,
    emotion_name text NOT NULL
);


ALTER TABLE public.emotions OWNER TO docker;

--
-- TOC entry 228 (class 1259 OID 16617)
-- Name: emotions_emotion_id_seq; Type: SEQUENCE; Schema: public; Owner: docker
--

CREATE SEQUENCE public.emotions_emotion_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.emotions_emotion_id_seq OWNER TO docker;

--
-- TOC entry 3494 (class 0 OID 0)
-- Dependencies: 228
-- Name: emotions_emotion_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: docker
--

ALTER SEQUENCE public.emotions_emotion_id_seq OWNED BY public.emotions.emotion_id;


--
-- TOC entry 221 (class 1259 OID 16508)
-- Name: friends; Type: TABLE; Schema: public; Owner: docker
--

CREATE TABLE public.friends (
    friendship_id integer NOT NULL,
    user_id integer,
    friend_id integer
);


ALTER TABLE public.friends OWNER TO docker;

--
-- TOC entry 222 (class 1259 OID 16523)
-- Name: friends_friendship_id_seq; Type: SEQUENCE; Schema: public; Owner: docker
--

ALTER TABLE public.friends ALTER COLUMN friendship_id ADD GENERATED ALWAYS AS IDENTITY (
    SEQUENCE NAME public.friends_friendship_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1
);


--
-- TOC entry 237 (class 1259 OID 24858)
-- Name: usersdetails; Type: TABLE; Schema: public; Owner: docker
--

CREATE TABLE public.usersdetails (
    detail_id integer NOT NULL,
    name text NOT NULL,
    photo text DEFAULT 'https://t4.ftcdn.net/jpg/03/49/49/79/360_F_349497933_Ly4im8BDmHLaLzgyKg2f2yZOvJjBtlw5.webp'::text NOT NULL,
    surname text NOT NULL
);


ALTER TABLE public.usersdetails OWNER TO docker;

--
-- TOC entry 238 (class 1259 OID 24886)
-- Name: friends_view; Type: VIEW; Schema: public; Owner: docker
--

CREATE VIEW public.friends_view AS
 SELECT u.user_id,
    ud_user.name AS user_name,
    ud_user.surname AS user_surname,
    ud_user.photo AS user_photo,
    f.friend_id,
    ud_friend.name AS friend_name,
    ud_friend.surname AS friend_surname,
    ud_friend.photo AS friend_photo
   FROM ((((public.friends f
     JOIN public.users u ON ((f.user_id = u.user_id)))
     JOIN public.usersdetails ud_user ON ((u.detail_id = ud_user.detail_id)))
     JOIN public.users uf ON (((f.friend_id = uf.user_id) AND (f.user_id <> uf.user_id))))
     JOIN public.usersdetails ud_friend ON ((uf.detail_id = ud_friend.detail_id)));


ALTER VIEW public.friends_view OWNER TO docker;

--
-- TOC entry 224 (class 1259 OID 16533)
-- Name: likes; Type: TABLE; Schema: public; Owner: docker
--

CREATE TABLE public.likes (
    like_id integer NOT NULL,
    dream_id integer NOT NULL,
    user_id integer NOT NULL,
    date date NOT NULL
);


ALTER TABLE public.likes OWNER TO docker;

--
-- TOC entry 223 (class 1259 OID 16532)
-- Name: likes_like_id_seq; Type: SEQUENCE; Schema: public; Owner: docker
--

ALTER TABLE public.likes ALTER COLUMN like_id ADD GENERATED ALWAYS AS IDENTITY (
    SEQUENCE NAME public.likes_like_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1
);


--
-- TOC entry 235 (class 1259 OID 16712)
-- Name: privacy; Type: TABLE; Schema: public; Owner: docker
--

CREATE TABLE public.privacy (
    privacy_id integer NOT NULL,
    privacy_name text NOT NULL
);


ALTER TABLE public.privacy OWNER TO docker;

--
-- TOC entry 234 (class 1259 OID 16711)
-- Name: privacy_privacy_id_seq; Type: SEQUENCE; Schema: public; Owner: docker
--

ALTER TABLE public.privacy ALTER COLUMN privacy_id ADD GENERATED ALWAYS AS IDENTITY (
    SEQUENCE NAME public.privacy_privacy_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1
);


--
-- TOC entry 226 (class 1259 OID 16562)
-- Name: role; Type: TABLE; Schema: public; Owner: docker
--

CREATE TABLE public.role (
    role_id integer NOT NULL,
    role text NOT NULL
);


ALTER TABLE public.role OWNER TO docker;

--
-- TOC entry 225 (class 1259 OID 16561)
-- Name: role_role_id_seq; Type: SEQUENCE; Schema: public; Owner: docker
--

ALTER TABLE public.role ALTER COLUMN role_id ADD GENERATED ALWAYS AS IDENTITY (
    SEQUENCE NAME public.role_role_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1
);


--
-- TOC entry 230 (class 1259 OID 16639)
-- Name: userdreamview; Type: VIEW; Schema: public; Owner: docker
--

CREATE VIEW public.userdreamview AS
 SELECT dreams.dream_id,
    dreams.title,
    dreams.dream_content AS content,
    dreams.date,
    count(likes.like_id) AS likes,
    count(comments.comment_id) AS commentamount,
    users.user_id
   FROM (((public.dreams
     JOIN public.users ON ((dreams.user_id = users.user_id)))
     LEFT JOIN public.comments ON ((dreams.dream_id = comments.dream_id)))
     LEFT JOIN public.likes ON ((dreams.dream_id = likes.dream_id)))
  GROUP BY dreams.dream_id, dreams.title, dreams.dream_content, dreams.date, users.user_id
  ORDER BY dreams.date DESC;


ALTER VIEW public.userdreamview OWNER TO docker;

--
-- TOC entry 233 (class 1259 OID 16692)
-- Name: users_friend_dreams_view; Type: VIEW; Schema: public; Owner: docker
--

CREATE VIEW public.users_friend_dreams_view AS
SELECT
    NULL::integer AS dream_id,
    NULL::text AS title,
    NULL::text AS content,
    NULL::date AS date,
    NULL::bigint AS likes,
    NULL::bigint AS commentamount,
    NULL::integer AS fuser_id,
    NULL::integer AS ffriend_id,
    NULL::integer AS user_id,
    NULL::character varying(255) AS email;


ALTER VIEW public.users_friend_dreams_view OWNER TO docker;

--
-- TOC entry 239 (class 1259 OID 24891)
-- Name: users_view; Type: VIEW; Schema: public; Owner: docker
--

CREATE VIEW public.users_view AS
 SELECT users.user_id,
    users.email,
    users.password,
    users.role_id,
    users.detail_id,
    usersdetails.name,
    usersdetails.surname,
    usersdetails.photo
   FROM (public.users
     JOIN public.usersdetails ON ((users.detail_id = usersdetails.detail_id)));


ALTER VIEW public.users_view OWNER TO docker;

--
-- TOC entry 236 (class 1259 OID 24857)
-- Name: usersdetails_detail_id_seq; Type: SEQUENCE; Schema: public; Owner: docker
--

ALTER TABLE public.usersdetails ALTER COLUMN detail_id ADD GENERATED ALWAYS AS IDENTITY (
    SEQUENCE NAME public.usersdetails_detail_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1
);


--
-- TOC entry 232 (class 1259 OID 16646)
-- Name: userstatistics; Type: TABLE; Schema: public; Owner: docker
--

CREATE TABLE public.userstatistics (
    stat_id integer NOT NULL,
    user_id integer NOT NULL,
    dreams_amount integer DEFAULT 0 NOT NULL,
    like_amount integer DEFAULT 0 NOT NULL,
    comments_amount integer DEFAULT 0 NOT NULL
);


ALTER TABLE public.userstatistics OWNER TO docker;

--
-- TOC entry 231 (class 1259 OID 16645)
-- Name: userstatistics_stat_id_seq; Type: SEQUENCE; Schema: public; Owner: docker
--

ALTER TABLE public.userstatistics ALTER COLUMN stat_id ADD GENERATED ALWAYS AS IDENTITY (
    SEQUENCE NAME public.userstatistics_stat_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1
);


--
-- TOC entry 3277 (class 2604 OID 16621)
-- Name: emotions emotion_id; Type: DEFAULT; Schema: public; Owner: docker
--

ALTER TABLE ONLY public.emotions ALTER COLUMN emotion_id SET DEFAULT nextval('public.emotions_emotion_id_seq'::regclass);


--
-- TOC entry 3471 (class 0 OID 16471)
-- Dependencies: 218
-- Data for Name: comments; Type: TABLE DATA; Schema: public; Owner: docker
--

COPY public.comments (comment_id, dream_id, user_id, comment_content, comment_date) FROM stdin;
1	1	2	So fascinating, lov u	2023-11-16
2	1	1	I love my dreams	2023-11-19
3	45	1	Im sorry to hear that	2024-01-24
4	4	1	I wish I was you!!!	2024-01-24
\.


--
-- TOC entry 3470 (class 0 OID 16434)
-- Dependencies: 217
-- Data for Name: dreams; Type: TABLE DATA; Schema: public; Owner: docker
--

COPY public.dreams (dream_id, user_id, title, dream_content, date, privacy_id) FROM stdin;
8	7	Meeting James Bond	In my dream I was a villain and met James Bond who shot me because I was trying to kill him. I'm a looser.	2023-12-29	1
5	7	I was a star	I was a star in the space and other stars were laughing at me :(((((	2023-12-29	1
3	2	Time-Traveling Exploration	Visiting different historical eras.	2023-11-18	1
10	7	Walking on the moon	After James Bond shot me I woke up on the moon and took a walk	2023-12-29	1
2	2	Underwater Adventure	Exploring a vibrant underwater world.	2023-11-17	1
4	3	Meeting a Famous Personality	Encountering a personal hero.	2023-11-19	1
1	1	Flying High	Soaring through the sky among the clouds.	2023-11-16	1
34	3	Private dream	I am not happy with my dream	2024-01-19	2
45	1	Stressfull day	This dream was awful, everybody was fighting against me.	2024-01-21	1
\.


--
-- TOC entry 3480 (class 0 OID 16606)
-- Dependencies: 227
-- Data for Name: dreamsemotions; Type: TABLE DATA; Schema: public; Owner: docker
--

COPY public.dreamsemotions (dream_id, emotion_id) FROM stdin;
1	1
1	5
2	1
3	1
4	1
5	2
10	1
10	5
8	4
8	2
\.


--
-- TOC entry 3482 (class 0 OID 16618)
-- Dependencies: 229
-- Data for Name: emotions; Type: TABLE DATA; Schema: public; Owner: docker
--

COPY public.emotions (emotion_id, emotion_name) FROM stdin;
1	Happy
2	Sad
3	Terrified
4	Shocked
5	Amused
\.


--
-- TOC entry 3474 (class 0 OID 16508)
-- Dependencies: 221
-- Data for Name: friends; Type: TABLE DATA; Schema: public; Owner: docker
--

COPY public.friends (friendship_id, user_id, friend_id) FROM stdin;
1	1	2
2	1	3
\.


--
-- TOC entry 3477 (class 0 OID 16533)
-- Dependencies: 224
-- Data for Name: likes; Type: TABLE DATA; Schema: public; Owner: docker
--

COPY public.likes (like_id, dream_id, user_id, date) FROM stdin;
1	1	1	2010-12-12
59	45	1	2024-01-24
60	3	1	2024-01-24
61	4	1	2024-01-24
\.


--
-- TOC entry 3486 (class 0 OID 16712)
-- Dependencies: 235
-- Data for Name: privacy; Type: TABLE DATA; Schema: public; Owner: docker
--

COPY public.privacy (privacy_id, privacy_name) FROM stdin;
1	Public
2	Private
\.


--
-- TOC entry 3479 (class 0 OID 16562)
-- Dependencies: 226
-- Data for Name: role; Type: TABLE DATA; Schema: public; Owner: docker
--

COPY public.role (role_id, role) FROM stdin;
1	admin
2	user
\.


--
-- TOC entry 3468 (class 0 OID 16408)
-- Dependencies: 215
-- Data for Name: users; Type: TABLE DATA; Schema: public; Owner: docker
--

COPY public.users (user_id, email, password, role_id, detail_id) FROM stdin;
2	pmorgan@pk.edu.pl	$2y$10$Hvw4NeYXc4fjX6cTNw3VfOnLdvWKzC5qtT05NcHykU0sL54BMq/76	2	4
8	ala1@gmail.com	$2y$10$le3BW38A//WWCSmIprRJpOErxDlcbCjZnj0.26ovf5GUbfctbdsK2	2	2
7	ala@gmail.com	$2y$10$QhkaikTd5dSjvOX5KRbb9uNCSgrLScndNTLqNGn8ASsnU4OoEp1KO	2	1
3	pp@gmail.com	$2y$10$wOLB5DkTXt6du.x1WcoCuOFQTKb1woacKaunqav0sKjxO5JMt/RWS	2	5
1	jsnow@pk.edu.pl	$2y$10$oAtmNSSrUOogAWdPU1uhpObdBZ7AMB.FTOn7DFYyCSiA8fteaqgKC	1	3
\.


--
-- TOC entry 3488 (class 0 OID 24858)
-- Dependencies: 237
-- Data for Name: usersdetails; Type: TABLE DATA; Schema: public; Owner: docker
--

COPY public.usersdetails (detail_id, name, photo, surname) FROM stdin;
5	Peter	https://t4.ftcdn.net/jpg/03/49/49/79/360_F_349497933_Ly4im8BDmHLaLzgyKg2f2yZOvJjBtlw5.webp	Parker
4	Paul	public/img/uploaded_files/photo3.png	Morgan
3	Johnny	public/img/uploaded_files/photo4.png	Snow
2	Ala	public/img/uploaded_files/photo1.png	Kot
1	Ala	public/img/uploaded_files/photo2.png	Kot
\.


--
-- TOC entry 3484 (class 0 OID 16646)
-- Dependencies: 232
-- Data for Name: userstatistics; Type: TABLE DATA; Schema: public; Owner: docker
--

COPY public.userstatistics (stat_id, user_id, dreams_amount, like_amount, comments_amount) FROM stdin;
3	2	3	0	1
4	3	4	0	0
5	7	4	0	0
6	8	1	0	0
2	1	2	4	3
\.


--
-- TOC entry 3495 (class 0 OID 0)
-- Dependencies: 216
-- Name: Users_user_id_seq; Type: SEQUENCE SET; Schema: public; Owner: docker
--

SELECT pg_catalog.setval('public."Users_user_id_seq"', 16, true);


--
-- TOC entry 3496 (class 0 OID 0)
-- Dependencies: 220
-- Name: comments_comment_id_seq; Type: SEQUENCE SET; Schema: public; Owner: docker
--

SELECT pg_catalog.setval('public.comments_comment_id_seq', 4, true);


--
-- TOC entry 3497 (class 0 OID 0)
-- Dependencies: 219
-- Name: dreams_dream_id_seq; Type: SEQUENCE SET; Schema: public; Owner: docker
--

SELECT pg_catalog.setval('public.dreams_dream_id_seq', 45, true);


--
-- TOC entry 3498 (class 0 OID 0)
-- Dependencies: 228
-- Name: emotions_emotion_id_seq; Type: SEQUENCE SET; Schema: public; Owner: docker
--

SELECT pg_catalog.setval('public.emotions_emotion_id_seq', 5, true);


--
-- TOC entry 3499 (class 0 OID 0)
-- Dependencies: 222
-- Name: friends_friendship_id_seq; Type: SEQUENCE SET; Schema: public; Owner: docker
--

SELECT pg_catalog.setval('public.friends_friendship_id_seq', 5, true);


--
-- TOC entry 3500 (class 0 OID 0)
-- Dependencies: 223
-- Name: likes_like_id_seq; Type: SEQUENCE SET; Schema: public; Owner: docker
--

SELECT pg_catalog.setval('public.likes_like_id_seq', 61, true);


--
-- TOC entry 3501 (class 0 OID 0)
-- Dependencies: 234
-- Name: privacy_privacy_id_seq; Type: SEQUENCE SET; Schema: public; Owner: docker
--

SELECT pg_catalog.setval('public.privacy_privacy_id_seq', 2, true);


--
-- TOC entry 3502 (class 0 OID 0)
-- Dependencies: 225
-- Name: role_role_id_seq; Type: SEQUENCE SET; Schema: public; Owner: docker
--

SELECT pg_catalog.setval('public.role_role_id_seq', 2, true);


--
-- TOC entry 3503 (class 0 OID 0)
-- Dependencies: 236
-- Name: usersdetails_detail_id_seq; Type: SEQUENCE SET; Schema: public; Owner: docker
--

SELECT pg_catalog.setval('public.usersdetails_detail_id_seq', 12, true);


--
-- TOC entry 3504 (class 0 OID 0)
-- Dependencies: 231
-- Name: userstatistics_stat_id_seq; Type: SEQUENCE SET; Schema: public; Owner: docker
--

SELECT pg_catalog.setval('public.userstatistics_stat_id_seq', 16, true);


--
-- TOC entry 3287 (class 2606 OID 16477)
-- Name: comments comments_pkey; Type: CONSTRAINT; Schema: public; Owner: docker
--

ALTER TABLE ONLY public.comments
    ADD CONSTRAINT comments_pkey PRIMARY KEY (comment_id);


--
-- TOC entry 3285 (class 2606 OID 16441)
-- Name: dreams dreams_pkey; Type: CONSTRAINT; Schema: public; Owner: docker
--

ALTER TABLE ONLY public.dreams
    ADD CONSTRAINT dreams_pkey PRIMARY KEY (dream_id);


--
-- TOC entry 3295 (class 2606 OID 16625)
-- Name: emotions emotions_pk; Type: CONSTRAINT; Schema: public; Owner: docker
--

ALTER TABLE ONLY public.emotions
    ADD CONSTRAINT emotions_pk PRIMARY KEY (emotion_id);


--
-- TOC entry 3289 (class 2606 OID 16512)
-- Name: friends friends_pkey; Type: CONSTRAINT; Schema: public; Owner: docker
--

ALTER TABLE ONLY public.friends
    ADD CONSTRAINT friends_pkey PRIMARY KEY (friendship_id);


--
-- TOC entry 3291 (class 2606 OID 16537)
-- Name: likes likes_pkey; Type: CONSTRAINT; Schema: public; Owner: docker
--

ALTER TABLE ONLY public.likes
    ADD CONSTRAINT likes_pkey PRIMARY KEY (like_id);


--
-- TOC entry 3299 (class 2606 OID 16718)
-- Name: privacy privacy_pk; Type: CONSTRAINT; Schema: public; Owner: docker
--

ALTER TABLE ONLY public.privacy
    ADD CONSTRAINT privacy_pk PRIMARY KEY (privacy_id);


--
-- TOC entry 3293 (class 2606 OID 16568)
-- Name: role role_pkey; Type: CONSTRAINT; Schema: public; Owner: docker
--

ALTER TABLE ONLY public.role
    ADD CONSTRAINT role_pkey PRIMARY KEY (role_id);


--
-- TOC entry 3283 (class 2606 OID 16416)
-- Name: users users_pkey; Type: CONSTRAINT; Schema: public; Owner: docker
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_pkey PRIMARY KEY (user_id);


--
-- TOC entry 3301 (class 2606 OID 24865)
-- Name: usersdetails usersdetails_pk; Type: CONSTRAINT; Schema: public; Owner: docker
--

ALTER TABLE ONLY public.usersdetails
    ADD CONSTRAINT usersdetails_pk PRIMARY KEY (detail_id);


--
-- TOC entry 3297 (class 2606 OID 16653)
-- Name: userstatistics userstatistics_pk; Type: CONSTRAINT; Schema: public; Owner: docker
--

ALTER TABLE ONLY public.userstatistics
    ADD CONSTRAINT userstatistics_pk PRIMARY KEY (stat_id);


--
-- TOC entry 3465 (class 2618 OID 16695)
-- Name: users_friend_dreams_view _RETURN; Type: RULE; Schema: public; Owner: docker
--

CREATE OR REPLACE VIEW public.users_friend_dreams_view AS
 SELECT dreams.dream_id,
    dreams.title,
    dreams.dream_content AS content,
    dreams.date,
    count(likes.like_id) AS likes,
    count(comments.comment_id) AS commentamount,
    friends.user_id AS fuser_id,
    friends.friend_id AS ffriend_id,
    users.user_id,
    users.email
   FROM (((((public.dreams
     JOIN public.users ON ((dreams.user_id = users.user_id)))
     LEFT JOIN public.comments ON ((dreams.dream_id = comments.dream_id)))
     LEFT JOIN public.likes ON ((dreams.dream_id = likes.dream_id)))
     JOIN public.friends ON (((dreams.user_id = friends.friend_id) OR (dreams.user_id = friends.user_id))))
     JOIN public.privacy ON ((dreams.privacy_id = privacy.privacy_id)))
  WHERE (privacy.privacy_name = 'Public'::text)
  GROUP BY dreams.dream_id, dreams.title, dreams.dream_content, dreams.date, users.user_id, friends.user_id, friends.friend_id
  ORDER BY dreams.date DESC;


--
-- TOC entry 3319 (class 2620 OID 24842)
-- Name: comments after_adding_comment; Type: TRIGGER; Schema: public; Owner: docker
--

CREATE TRIGGER after_adding_comment AFTER INSERT OR UPDATE ON public.comments FOR EACH ROW EXECUTE FUNCTION public.update_userstatistics_on_insert();


--
-- TOC entry 3316 (class 2620 OID 24841)
-- Name: dreams after_adding_dream; Type: TRIGGER; Schema: public; Owner: docker
--

CREATE TRIGGER after_adding_dream AFTER INSERT OR UPDATE ON public.dreams FOR EACH ROW EXECUTE FUNCTION public.update_userstatistics_on_insert();


--
-- TOC entry 3320 (class 2620 OID 24843)
-- Name: likes after_adding_like; Type: TRIGGER; Schema: public; Owner: docker
--

CREATE TRIGGER after_adding_like AFTER INSERT OR UPDATE ON public.likes FOR EACH ROW EXECUTE FUNCTION public.update_userstatistics_on_insert();


--
-- TOC entry 3315 (class 2620 OID 24840)
-- Name: users after_adding_user; Type: TRIGGER; Schema: public; Owner: docker
--

CREATE TRIGGER after_adding_user AFTER INSERT OR UPDATE ON public.users FOR EACH ROW EXECUTE FUNCTION public.update_userstatistics_on_insert();


--
-- TOC entry 3317 (class 2620 OID 24849)
-- Name: dreams before_delete_dream_trigger; Type: TRIGGER; Schema: public; Owner: docker
--

CREATE TRIGGER before_delete_dream_trigger BEFORE DELETE ON public.dreams FOR EACH ROW EXECUTE FUNCTION public.delete_comments_and_likes();


--
-- TOC entry 3318 (class 2620 OID 24847)
-- Name: dreams dreams_after_delete_trigger; Type: TRIGGER; Schema: public; Owner: docker
--

CREATE TRIGGER dreams_after_delete_trigger AFTER DELETE ON public.dreams FOR EACH ROW EXECUTE FUNCTION public.update_userstatistics_on_delete();


--
-- TOC entry 3306 (class 2606 OID 16478)
-- Name: comments comments_dream_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: docker
--

ALTER TABLE ONLY public.comments
    ADD CONSTRAINT comments_dream_id_fkey FOREIGN KEY (dream_id) REFERENCES public.dreams(dream_id);


--
-- TOC entry 3307 (class 2606 OID 16483)
-- Name: comments comments_user_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: docker
--

ALTER TABLE ONLY public.comments
    ADD CONSTRAINT comments_user_id_fkey FOREIGN KEY (user_id) REFERENCES public.users(user_id) NOT VALID;


--
-- TOC entry 3312 (class 2606 OID 16612)
-- Name: dreamsemotions dream; Type: FK CONSTRAINT; Schema: public; Owner: docker
--

ALTER TABLE ONLY public.dreamsemotions
    ADD CONSTRAINT dream FOREIGN KEY (dream_id) REFERENCES public.dreams(dream_id);


--
-- TOC entry 3310 (class 2606 OID 16538)
-- Name: likes dream_id; Type: FK CONSTRAINT; Schema: public; Owner: docker
--

ALTER TABLE ONLY public.likes
    ADD CONSTRAINT dream_id FOREIGN KEY (dream_id) REFERENCES public.dreams(dream_id);


--
-- TOC entry 3313 (class 2606 OID 16630)
-- Name: dreamsemotions emotion; Type: FK CONSTRAINT; Schema: public; Owner: docker
--

ALTER TABLE ONLY public.dreamsemotions
    ADD CONSTRAINT emotion FOREIGN KEY (emotion_id) REFERENCES public.emotions(emotion_id);


--
-- TOC entry 3308 (class 2606 OID 24881)
-- Name: friends friends_friend_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: docker
--

ALTER TABLE ONLY public.friends
    ADD CONSTRAINT friends_friend_id_fkey FOREIGN KEY (friend_id) REFERENCES public.users(user_id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- TOC entry 3309 (class 2606 OID 24876)
-- Name: friends friends_user_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: docker
--

ALTER TABLE ONLY public.friends
    ADD CONSTRAINT friends_user_id_fkey FOREIGN KEY (user_id) REFERENCES public.users(user_id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- TOC entry 3304 (class 2606 OID 16719)
-- Name: dreams privacy; Type: FK CONSTRAINT; Schema: public; Owner: docker
--

ALTER TABLE ONLY public.dreams
    ADD CONSTRAINT privacy FOREIGN KEY (privacy_id) REFERENCES public.privacy(privacy_id);


--
-- TOC entry 3302 (class 2606 OID 16569)
-- Name: users role; Type: FK CONSTRAINT; Schema: public; Owner: docker
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT role FOREIGN KEY (role_id) REFERENCES public.role(role_id) NOT VALID;


--
-- TOC entry 3305 (class 2606 OID 16442)
-- Name: dreams user_id; Type: FK CONSTRAINT; Schema: public; Owner: docker
--

ALTER TABLE ONLY public.dreams
    ADD CONSTRAINT user_id FOREIGN KEY (user_id) REFERENCES public.users(user_id) NOT VALID;


--
-- TOC entry 3311 (class 2606 OID 16543)
-- Name: likes user_id; Type: FK CONSTRAINT; Schema: public; Owner: docker
--

ALTER TABLE ONLY public.likes
    ADD CONSTRAINT user_id FOREIGN KEY (user_id) REFERENCES public.users(user_id);


--
-- TOC entry 3314 (class 2606 OID 16654)
-- Name: userstatistics user_statistics; Type: FK CONSTRAINT; Schema: public; Owner: docker
--

ALTER TABLE ONLY public.userstatistics
    ADD CONSTRAINT user_statistics FOREIGN KEY (user_id) REFERENCES public.users(user_id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- TOC entry 3303 (class 2606 OID 24871)
-- Name: users users_detail; Type: FK CONSTRAINT; Schema: public; Owner: docker
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_detail FOREIGN KEY (detail_id) REFERENCES public.usersdetails(detail_id) ON UPDATE CASCADE ON DELETE CASCADE;


-- Completed on 2024-01-24 22:19:50 UTC

--
-- PostgreSQL database dump complete
--

