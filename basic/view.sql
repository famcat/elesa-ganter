
drop view v_filter_headers;
create view v_filter_headers as
select production_id,GROUP_CONCAT(name  SEPARATOR '|') as headers from filter
GROUP BY production_id;





create view v_temp_filter_data as select filter_article_id, GROUP_CONCAT(value SEPARATOR '|') as val
                                 from filter_data
                                 GROUP BY filter_article_id;

create view v_filter_data as
  select f.production_id,f.schema_id,f.article_code,f.article_dicription,v.val
  from filter_article as f
    LEFT JOIN  v_temp_filter_data as v on v.filter_article_id = f.id;


select * from v_filter_data;
select * from v_filter_headers;

create view v_filter_data as
  select f.production_id,f.schema_id,f.article_code,f.article_dicription,v.val,f.color_attribute
  from filter_article as f
    LEFT JOIN  v_temp_filter_data as v on v.filter_article_id = f.id;


create view v_temp_color_no_table as
  select production_id from v_filter_data where color_attribute != '-'
  GROUP BY production_id;

create view v_color_no_table as
select cl.production_id,cl.color_name,cl.color_code,cl.color_hex from color_list cl,
  v_temp_color_no_table as fd
where cl.production_id = fd.production_id;

create view v_temp_color_table as
  select production_id FROM color_list
  GROUP BY production_id;

create view v_temp_color_no_val_table as
                               select production_id from filter_article
                               where color_attribute = '-'
                               GROUP BY production_id;

create view v_color_table as
select t.production_id from v_temp_color_no_val_table t,
  v_temp_color_table p
where t.production_id = p.production_id


drop view v_filter_headers;
create view v_filter_headers as
  select schema_id,production_id,GROUP_CONCAT(name  SEPARATOR '|') as headers from filter
  GROUP BY schema_id
  ORDER BY production_id;

select * from v_filter_headers;

drop VIEW v_filter_data;
create view v_filter_data as
  select SQL_CACHE  SQL_BIG_RESULT f.id,f.production_id,f.schema_id,f.article_code,f.article_dicription,v.val,f.color_attribute
  from filter_article as f
    LEFT JOIN  v_temp_filter_data as v on v.filter_article_id = f.id
  ORDER BY f.production_id,f.schema_id;

SELECT * from v_filter_data;