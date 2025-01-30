# nc_pg_news_filter
Filter news items in nextcloud when data is stored in postgres

This is a quick solution until (if) filtering becomes available in the nextcloud news app.

It is intended for home use, although DBA's may also want to make use of this. 

## Installation
Download and run the sql in the following order.
1. cr_oc_news_filter.sql
2. cr_oc_news_filter_func.sql
3. cr_oc_news_items_trigger.sql

Step 1 creates table oc_news_filter, this table needs populating to apply filters. See below for a simple web form to populate the table.

Table columns:
* id - auto populating id
* sub_domain - This is the subscription domain, for example news.com
* txt_filter - This is the text to filter on, it is case insensitive
* inc_filter - Set to true to only include articles that match the txt_filter, set to false to exclude articles that match the txt_filter.
    
NOTE: each row can only have 1 filter, add additional rows for multiple filters.

Example of including only articles on nasa from theregister.com

    INSERT INTO oc_news_filter(
        sub_domain, txt_filter, inc_filter)
        VALUES ('theregister.com', 'nasa', true);
    
Example of excluding apple from engadget.com

    INSERT INTO oc_news_filter(
        sub_domain, txt_filter, inc_filter)
        VALUES ('engadget.com', 'apple', false);
        
## Web access
* Download index.php for a simple web form to insert, update and delete rows.
* The head of index.php requires updating with database access details.
* Security needs to be considered prior to using this file.

![image](https://github.com/user-attachments/assets/9072e586-fa8a-47fa-899f-8b0948e8db2e)
