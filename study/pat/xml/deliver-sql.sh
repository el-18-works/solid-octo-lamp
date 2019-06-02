#!/bin/bash

py/miss.py doc/myhost-sql.ods cache/myhost-sql
rm -rf ~/sol/mysite/myhost-sql
cp -r cache/myhost-sql ~/sol/mysite/myhost-sql

