--
-- PostgreSQL database dump
--

-- Dumped from database version 9.5.12
-- Dumped by pg_dump version 10.3

SET statement_timeout = 0;
SET lock_timeout = 0;
SET idle_in_transaction_session_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SELECT pg_catalog.set_config('search_path', '', false);
SET check_function_bodies = false;
SET client_min_messages = warning;
SET row_security = off;

--
-- Name: plpgsql; Type: EXTENSION; Schema: -; Owner: 
--

CREATE EXTENSION IF NOT EXISTS plpgsql WITH SCHEMA pg_catalog;


--
-- Name: EXTENSION plpgsql; Type: COMMENT; Schema: -; Owner: 
--

COMMENT ON EXTENSION plpgsql IS 'PL/pgSQL procedural language';


SET default_tablespace = '';

SET default_with_oids = false;

--
-- Name: displacements; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.displacements (
    id integer NOT NULL,
    visit_at timestamp without time zone NOT NULL,
    url_came character varying(2044),
    url_went character varying(2044),
    user_id integer NOT NULL
);


ALTER TABLE public.displacements OWNER TO postgres;

--
-- Name: TABLE displacements; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON TABLE public.displacements IS 'Перемещения пользователя';


--
-- Name: COLUMN displacements.id; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN public.displacements.id IS 'Идентификатор перемещения';


--
-- Name: COLUMN displacements.visit_at; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN public.displacements.visit_at IS 'Дата и время перемещения';


--
-- Name: COLUMN displacements.url_came; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN public.displacements.url_came IS 'URL с которого зашел';


--
-- Name: COLUMN displacements.url_went; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN public.displacements.url_went IS 'URL куда зашел';


--
-- Name: COLUMN displacements.user_id; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN public.displacements.user_id IS 'Идентификатор польлзователя';


--
-- Name: displacements_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.displacements_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.displacements_id_seq OWNER TO postgres;

--
-- Name: displacements_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.displacements_id_seq OWNED BY public.displacements.id;


--
-- Name: users; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.users (
    id integer NOT NULL,
    ip character varying(15) NOT NULL,
    browser character varying(255),
    operating_system character varying(2044)
);


ALTER TABLE public.users OWNER TO postgres;

--
-- Name: TABLE users; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON TABLE public.users IS 'ip пользователя';


--
-- Name: COLUMN users.id; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN public.users.id IS 'Идентификатор польлзователя';


--
-- Name: COLUMN users.ip; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN public.users.ip IS 'ip пользователя';


--
-- Name: COLUMN users.browser; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN public.users.browser IS 'Веб-браузер';


--
-- Name: COLUMN users.operating_system; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN public.users.operating_system IS 'Операционная система';


--
-- Name: users_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.users_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.users_id_seq OWNER TO postgres;

--
-- Name: users_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.users_id_seq OWNED BY public.users.id;


--
-- Name: displacements id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.displacements ALTER COLUMN id SET DEFAULT nextval('public.displacements_id_seq'::regclass);


--
-- Name: users id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.users ALTER COLUMN id SET DEFAULT nextval('public.users_id_seq'::regclass);


--
-- Name: displacements displacement_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.displacements
    ADD CONSTRAINT displacement_pkey PRIMARY KEY (id);


--
-- Name: users unique_ip; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT unique_ip UNIQUE (ip);


--
-- Name: users user_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT user_pkey PRIMARY KEY (id);


--
-- Name: fk_user_id; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX fk_user_id ON public.displacements USING btree (user_id);


--
-- Name: index_ip; Type: INDEX; Schema: public; Owner: postgres
--

CREATE UNIQUE INDEX index_ip ON public.users USING btree (ip);


--
-- Name: index_visit_at; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX index_visit_at ON public.displacements USING btree (visit_at);


--
-- Name: displacements lnk_user_displacement; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.displacements
    ADD CONSTRAINT lnk_user_displacement FOREIGN KEY (user_id) REFERENCES public.users(id) ON DELETE CASCADE;


--
-- Name: SCHEMA public; Type: ACL; Schema: -; Owner: postgres
--

REVOKE ALL ON SCHEMA public FROM PUBLIC;
REVOKE ALL ON SCHEMA public FROM postgres;
GRANT ALL ON SCHEMA public TO postgres;
GRANT ALL ON SCHEMA public TO PUBLIC;


--
-- PostgreSQL database dump complete
--

