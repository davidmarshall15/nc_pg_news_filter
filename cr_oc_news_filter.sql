CREATE TABLE IF NOT EXISTS oc_news_filter
(
    sub_domain text COLLATE pg_catalog."default",
    txt_filter text COLLATE pg_catalog."default",
    inc_filter boolean NOT NULL DEFAULT true
);