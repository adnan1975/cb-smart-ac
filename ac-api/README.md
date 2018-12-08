# CB AC Api 
API Server

a Slim php based API with swagger 

Database used is MySQL 

please check the file database.sql for sql scripts

Only if php had a decent collections or streaming this would been much more fun. 

Main router  is device which has all device related endpoints 

DeviceService class is reponsible of device related business logic. 


to access swagger endpoint check 
http://104.248.180.30/v1/api-doc/index.html#/

technical notes are here https://github.com/adnan1975/cb-smart-ac/wiki/Acme-AC-Unit-Application-Technical-Notes


The manipulation of data is largely due to support adminconsole features such as listing, searching, 
pagination and graps etc. 

Content-Range header is used for pagination


Next steps is to add ORM, test coverage, logs etc. 



