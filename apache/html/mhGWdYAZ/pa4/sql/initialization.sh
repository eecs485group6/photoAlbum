#!/bin/bash

umask 000
rm -r -f ../pa1_images/*
cp -r ../image_backup/* ../pa1_images
mysql -u group6 --password=01302013 group6 < tbl_drop.sql
mysql -u group6 --password=01302013 group6 < tbl_create.sql
mysql -u group6 --password=01302013 group6 < load_data.sql
mysql -u group6 --password=01302013 group6 < load_data_table1.sql
mysql -u group6 --password=01302013 group6 < load_data_table2.sql
mysql -u group6 --password=01302013 group6 < load_data_table3.sql
mysql -u group6 --password=01302013 group6 < load_data_table4.sql

mysql -u group6 --password=01302013 group6 < load_data_table5.sql
mysql -u group6 --password=01302013 group6 < load_data_table6.sql
mysql -u group6 --password=01302013 group6 < load_data_table7.sql
mysql -u group6 --password=01302013 group6 < load_data_table8.sql


mysql -u group6 --password=01302013 group6 < load_data_table9.sql
mysql -u group6 --password=01302013 group6 < load_data_table10.sql

mysql -u group6 --password=01302013 group6 < load_data_table11.sql

mysql -u group6 --password=01302013 group6 < search.sql
mysql -u group6 --password=01302013 group6 < search2.sql


