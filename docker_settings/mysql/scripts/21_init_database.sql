
CREATE USER 'app'@'localhost';
CREATE DATABASE IF NOT EXISTS fm2_testing;
CREATE DATABASE IF NOT EXISTS fm2_xyz;
GRANT ALL PRIVILEGES ON fm2_testing.* TO 'app'@'localhost';
GRANT ALL PRIVILEGES ON fm2_xyz.* TO 'app'@'localhost';
ALTER USER 'app'@'localhost' identified BY 'iphaiGha#i9h';
GRANT ALL ON fm2_testing.* TO 'app'@'localhost';
GRANT ALL ON fm2_xyz.* TO 'app'@'localhost';
SHOW GRANTS FOR 'app'@'localhost';

CREATE USER 'app'@'172.140.0.4';
CREATE DATABASE IF NOT EXISTS fm2_testing;
GRANT ALL PRIVILEGES ON fm2_testing.* TO 'app'@'172.140.0.4';
GRANT ALL PRIVILEGES ON fm2_xyz.* TO 'app'@'172.140.0.4';
ALTER USER 'app'@'172.140.0.4' identified BY 'iphaiGha#i9h';
GRANT ALL ON fm2_testing.* TO 'app'@'172.140.0.4';
GRANT ALL ON fm2_xyz.* TO 'app'@'172.140.0.4';
SHOW GRANTS FOR 'app'@'172.140.0.4';

CREATE USER 'app'@'%';
CREATE DATABASE IF NOT EXISTS fm2_testing;
GRANT ALL PRIVILEGES ON fm2_testing.* TO 'app'@'%';
GRANT ALL PRIVILEGES ON fm2_xyz.* TO 'app'@'%';
ALTER USER 'app'@'%' identified BY 'iphaiGha#i9h';
GRANT ALL ON fm2_testing.* TO 'app'@'%';
GRANT ALL ON fm2_xyz.* TO 'app'@'%';
SHOW GRANTS FOR 'app'@'%';


# CREATE USER 'fm2_testing'@'%';
# GRANT ALL PRIVILEGES ON fm2_testing.* TO 'fm2_testing'@'%';
# GRANT ALL ON fm2_testing.* TO 'fm2_testing'@'%';
# ALTER USER 'fm2_testing'@'%' identified BY 'iphaiGha#i9h';
# SHOW GRANTS FOR 'fm2_testing'@'%';


FLUSH PRIVILEGES;

# 'fm2_testing'@'172.140.0.4'
#
# mysql -hlocalhost  --port=6132 -uroot -pOhce#N1eiL2i

# [0419][web]$mycli -hlocalhost  --port=6132 -uroot
# (none)🏥 show databases
#     Database
# information_schema
# mysql
# performance_schema
# fm2_testing
# sys
# 5 rows in set
#     Time: 0.019s
# (none)🏥

