CREATE SEQUENCE IF NOT EXISTS oc_news_filter_id_seq
    INCREMENT 1
    START 1
    MINVALUE 1
    MAXVALUE 9223372036854775807
    CACHE 1;
    
CREATE TABLE IF NOT EXISTS oc_news_filter
(
    id bigint NOT NULL DEFAULT nextval('oc_news_filter_id_seq'::regclass),
    sub_domain text COLLATE pg_catalog."default",
    txt_filter text COLLATE pg_catalog."default",
    inc_filter boolean NOT NULL DEFAULT true
);