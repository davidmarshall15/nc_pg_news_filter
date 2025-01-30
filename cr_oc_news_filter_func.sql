CREATE OR REPLACE FUNCTION oc_news_filter_func()
                           RETURNS trigger AS
$BODY$
DECLARE
	dom_count int;
    art_count int;
BEGIN
	SELECT count(*)
	into   dom_count
    FROM   oc_news_filter
    WHERE  LOWER(NEW.url) LIKE '%' || LOWER(sub_domain) || '%'
	AND    inc_filter;

	if dom_count>0 then
	
	    SELECT count(*)
		INTO   art_count
	    FROM   oc_news_filter
	    WHERE  LOWER(NEW.url) LIKE '%' || LOWER(sub_domain) || '%'
	    AND   (LOWER(NEW.title) LIKE '%' || LOWER(txt_filter) || '%'
	    OR     LOWER(NEW.body) LIKE '%' || LOWER(txt_filter) || '%')
	    AND    inc_filter;
	    if art_count<1 then
	        NEW.unread := false;
	    end if;
	
    end if;

	SELECT count(*)
	into   dom_count
    FROM   oc_news_filter
    WHERE  LOWER(NEW.url) LIKE '%' || LOWER(sub_domain) || '%'
	AND    NOT inc_filter;

	if dom_count>0 then
	    SELECT count(*)
		INTO   art_count
	    FROM   oc_news_filter
	    WHERE  LOWER(NEW.url) LIKE '%' || LOWER(sub_domain) || '%'
	    AND   (LOWER(NEW.title) LIKE '%' || LOWER(txt_filter) || '%'
	    OR     LOWER(NEW.body) LIKE '%' || LOWER(txt_filter) || '%')
	    AND    NOT inc_filter;
	    if art_count>0 then
	        NEW.unread := false;
	    end if;
	
    end if;
  RETURN NEW;
END;
$BODY$
LANGUAGE plpgsql;