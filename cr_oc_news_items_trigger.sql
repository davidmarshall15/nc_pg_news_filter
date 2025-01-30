CREATE TRIGGER oc_news_items_before_insert
BEFORE INSERT ON oc_news_items
FOR EACH ROW
EXECUTE FUNCTION oc_news_filter_func();