
create view v_filter_headers as
select production_id,GROUP_CONCAT(name  SEPARATOR '|') from filter
GROUP BY production_id;


create view v_filter_template as select filter_article_id, GROUP_CONCAT(value SEPARATOR '|') as val
from filter_data
GROUP BY filter_article_id;

create view v_filter_data as
select f.production_id,f.schema_id,f.article_code,f.article_dicription,v.val
from filter_article as f
LEFT JOIN  v_filter_template as v on v.filter_article_id = f.id;

select * from v_filter_data;
select * from v_filter_headers;