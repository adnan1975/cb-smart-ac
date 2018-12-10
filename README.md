# cb-smart-ac
A proof of concept which integrates with AC units and provides them with an admin panel to manage

This repo has two projects 
##ac-api : 
this project is php based. using Slim framework and swagger. It implements devices api. you can register, search,
 add measuremnts and get alerts 
 
 for more documentation please check the project readme
 
 ##cb-admin
 this project is react base, it uses Material UI and react-admin. 
 
 I have added few plain components like snakbar, list
 
 I also have integrated recharts which required some plumbing.
 
 #Important API information 
 
 example Request Body for creating new device is {"serialNumber":"SN1","macAddress":"11111111","year":"2018","owner":"scott", "firmware":"2.1.5"}
 
 example Request body for creating a mesurement is [{"serialNumber":"SN3","humidity":"1","ppm":"10","healthStatus":"need_service","createdOn":"1544235156","temperature":"12"},{"serialNumber":"SN3","humidity":"1","ppm":"2","healthStatus":"need_service","createdOn":"1544235156","temperature":"12"}]


 
 
 
